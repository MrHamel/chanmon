<?php require_once("assets/header/main.php"); ?>

        <form class="form-signup">
            <div class="text-center mb-4">
                <img class="mb-4" src="https://getbootstrap.com/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
                <h1 class="h3 mb-3 font-weight-normal">YouTube Channel Video Monitor</h1>

<?php

session_start();
require_once("../db.php");


if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) && isset($_POST["yt_channel"]))
{

    $mysqli = mysqli_connect($db_host, $db_user, $db_pass, $db_db);


    if ($_SESSION["refresh_token"] != "") {
        $res = mysqli_query($mysqli, "INSERT INTO `channels` VALUES(DEFAULT,DEFAULT,'".$_POST["yt_channel"]."','".$_POST["email"]."','".$_SESSION["refresh_token"]."');");

        if ($res) {
            echo "<p>You are successfully signed up to receive daily notifications about video changes on your YouTube channel.</p>";
        } else {
            echo "<p>A database error has occurred. - ".mysqli_error($mysqli)."</p>";
        }
    } else {
        echo "<p>This application has already been authorized with your Google account.</p>";
    }

} else {
    echo "<p>There was an error with the information provided, please go back and try again.</p>";
}

?>

            </div>
        </form>

<?php require_once("assets/footer/main.php"); ?>
