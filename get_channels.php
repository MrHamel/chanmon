<?php

session_start();

$raw_data = file_get_contents("https://www.googleapis.com/youtube/v3/channels?part=snippet%2CcontentDetails%2Cstatistics&maxResults=50&mine=true&oauth_token=".$_SESSION["access_token"]);

$parsed_data = json_decode($raw_data, TRUE);

$channels = array();

foreach ($parsed_data["items"] as $channel) {
    $channels[$channel["contentDetails"]["relatedPlaylists"]["uploads"]] = $channel["snippet"]["title"];
} 

echo json_encode($channels);

?>
