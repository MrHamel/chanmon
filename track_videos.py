import requests, json, pprint
import os, commands
import MySQLdb

import smtplib
from email.mime.text import MIMEText

# Google API - For processing account specific data.
client_id = ""
client_secret = ""

# YouTube API Key
yt_api_key = ""

pretty_channels = {}

def fetch_videos(api_key, refresh_token, channel_id, pageToken=None):
    base_url = "https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults=50&playlistId=" + channel_id
    addon_url = ""

    if pageToken is not None: addon_url = addon_url + "&pageToken=" + pageToken

    if refresh_token is None:
        addon_url = addon_url + "&key=" + api_key
    else:
        r = requests.post("https://www.googleapis.com/oauth2/v4/token", data={"client_id":client_id,"client_secret":client_secret,"refresh_token":refresh_token,"grant_type":"refresh_token"})
        access_token = json.loads(r.text)["access_token"]
        addon_url = addon_url + "&oauth_token=" + access_token

    r = requests.get(base_url + addon_url)
    page_data = json.loads(r.text)
    videos = {}

    for video in page_data["items"]:
        id = video["snippet"]["resourceId"]["videoId"]
        videos[id] = video["snippet"]["title"]

        if channel_id not in pretty_channels:
            pretty_channels[channel_id] = video["snippet"]["channelTitle"]

    if "nextPageToken" in page_data:
        add_data = fetch_videos(api_key, refresh_token, channel_id, page_data["nextPageToken"])
        videos.update(add_data)

    return videos

def compare_changes(channel, videos):
    # Initialize variables for easy tracking.
    videos_added = {}
    videos_removed = {}

    # Get all old videos from the database for a particular channel.
    cursor.execute("SELECT video_id,video_title,available from videos where yt_channel = %s", (channel,))
    old_videos = cursor.fetchall()
    old_video_ids = map(lambda x: x[0], old_videos)

    # If the query returned no results, it is safe to bulk load everything in and consider everything an addition.
    if len(old_videos) == 0:
        for video in videos.keys():
            vid_title = videos[video].encode('utf-8').strip()
            cursor.execute("INSERT INTO videos VALUES(DEFAULT,CURRENT_DATE(),NULL,%s,%s,%s,1)", (channel, video, vid_title))
        db.commit()
        return [videos, {}]

    # Loop over the old videos from the database to see if they are still online. (TLDR: Detect removal of videos from YouTube.)
    for old_video in old_videos:
        o_vid_id, o_vid_title, o_vid_available = old_video

        if o_vid_id not in videos.keys() and o_vid_available:
            videos_removed[o_vid_id] = o_vid_title
            cursor.execute("UPDATE videos set available = 0, date_removed = CURRENT_DATE() where video_id = %s", (o_vid_id,))

    # Loop over the current videos from the YouTube API to see if they are already in the database. (TLDR: Add new videos to keep track of.)
    for video in videos.keys():
        vid_id = video
        vid_title = videos[video].encode('utf-8').strip()

        if vid_id not in old_video_ids:
            videos_added[vid_id] = vid_title
            cursor.execute("INSERT INTO videos VALUES(DEFAULT,CURRENT_DATE(),NULL,%s,%s,%s,1)", (channel, vid_id, vid_title))

    db.commit()
    return [videos_added,videos_removed]

db = MySQLdb.connect("localhost","yt_chan_mon","mysql_password","yt_chan_mon")
cursor = db.cursor()

cursor.execute("SELECT yt_upload_playlist,email,refresh_token from channels")
data = cursor.fetchall()

for channel, email, refresh_token in data:
    current_channel_videos = fetch_videos(yt_api_key, refresh_token, channel, None)

    videos_added, videos_removed = compare_changes(channel, current_channel_videos)

    if (len(videos_added) or len(videos_removed)) or (len(videos_added) and len(videos_removed)):
        mail_array = []

        if len(videos_added):
            for video in videos_added.keys():
                mail_array.append("Added: " + videos_added[video] + " - (https://www.youtube.com/watch?v=" + video + ")")

        if len(videos_removed):
            for video in videos_added.keys():
                mail_array.append("Removed: " + videos_added[video] + " - (https://www.youtube.com/watch?v=" + video + ")")

        mail_str = '\n'.join(mail_array)
        msg = MIMEText(mail_str.encode("utf-8"))

        msg['Subject'] = "Video Count Changed For: " + pretty_channels[channel]
        msg['From'] = "YouTube Bot <from email here>"
        msg['To'] = email

        server = smtplib.SMTP('localhost')
        server.sendmail("from email here", [email], msg.as_string())
        server.quit()
