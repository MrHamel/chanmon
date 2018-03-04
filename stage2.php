<?php

require_once '/srv/www/vendor/autoload.php';

session_start();

$client = new Google_Client();
// Set to name/location of your client_secrets.json file.
$client->setAuthConfigFile('../client_secrets.json');
// Set to valid redirect URI for your project.
$client->setRedirectUri('http://chanmon.rkhtech.org/stage2.php');

// Exchange authorization code for an access token.    
$accessToken = $client->authenticate($_GET["code"]);

$_SESSION["refresh_token"] = $accessToken["refresh_token"];
$_SESSION["access_token"] = $accessToken["access_token"];

header("Location: finish.php");

?>
