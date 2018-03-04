# YouTube Channel Video Monitor

## Project Notes

1. This project is a giant proof of concept, which is running at http://chanmon.rkhtech.org/.
2. If you notice any bugs with this project, please create an issue or email ryan@rkhtech.org.

## Features

1. Implements the YouTube API, to be able to access private and unlisted videos on a channel.
2. Uses a shared database (MySQL) for simple web integration.
3. Proof of concept web interface for signup and viewing the list.
4. Requires Google API authorization for signups and viewing of channel video history.

## Setup Instructions (RTFM)

0. Setup API key and oauth client at Google. (https://console.developers.google.com/apis/credentials) 
1. Install Python and the required modules. (MySQLdb connector and requests (web request library)).
2. Install and setup your LAMP/LEMP stack. (PHP 7 and MySQL/MariaDB required)
3. Create the database and its associated username + password, then import the schema provided (schema.sql).
4. Create the db.php file outside the public_html directory with the following format:
```
<?php

$db_host = "localhost";
$db_user = "";
$db_pass = "";
$db_db   = "";

?>
```
5. Download the oauth secret file, rename it to client_secrets.json, and place it outside the public_html directory.
6. Go through and edit the PHP files to ensure they are pointing to the correct file locations for db.php and client_secrets.json.
7. If all goes well, you should be able to authorize a YouTube channel, and watch it add an entry into the channels table of the MySQL database.
8. From there, you should be able to give the Python script a test run, if it does not show any errors, then you are free to add it into your systems crontab. (Recommended to be run hourly.)
9. Profit?

## To Do

1. Consolidate the db.php locations and client_secrets location to absolute file locations, and place them into the assets/header/main.php file, which is included on every API and front facing web page.
2. Optimize the 'videos' table of the MySQL database to ensure high performance when requesting the list of videos for a creator.
