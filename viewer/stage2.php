<?php

require_once '/srv/www/vendor/autoload.php';

session_start();

$client = new Google_Client();
// Set to name/location of your client_secrets.json file.
$client->setAuthConfigFile('../../client_secrets.json');
// Set to valid redirect URI for your project.
$client->setRedirectUri('http://chanmon.rkhtech.org/viewer/stage2.php');

// Exchange authorization code for an access token.    
$accessToken = $client->authenticate($_GET["code"]);

$raw_data = file_get_contents("https://www.googleapis.com/youtube/v3/channels?part=snippet%2CcontentDetails%2Cstatistics&maxResults=50&mine=true&oauth_token=".$accessToken["access_token"]);

$parsed_data = json_decode($raw_data, TRUE);

$name = "";

foreach ($parsed_data["items"] as $channel) {
    $playlist = $channel["contentDetails"]["relatedPlaylists"]["uploads"];
    $name = $channel["snippet"]["title"];
}

$_SESSION["channel"] = $playlist;
$_SESSION["pretty_channel"] = $name;

header("Location: list.php");

?>
