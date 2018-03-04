<?php

require_once '/srv/www/vendor/autoload.php';

$client = new Google_Client();
// Set to name/location of your client_secrets.json file.
$client->setAuthConfigFile('../../client_secrets.json');
// Set to valid redirect URI for your project.
$client->setRedirectUri('http://chanmon.rkhtech.org/viewer/stage2.php');

$client->addScope(Google_Service_YouTube::YOUTUBE_READONLY);
$client->setAccessType('offline');

// Request authorization from the user.
$authUrl = $client->createAuthUrl();
header("Location: ".$client->createAuthUrl());

?>
