<?php 

require_once("../assets/header/main.php");

session_start();
require_once("../../db.php");

?>
<style type="text/css">th,td { text-align:center; }</style>

<div class="container" style="position:absolute;top:0">
    <div class="row">
        <div class="col-md-10">
            <br />
            <br />

            <h3 style="text-align:center;">Results For: <?php echo $_SESSION["pretty_channel"]; ?></h3>

            <br />
            <br />

            <table class='table table-sm table-hover'>
                <thead>
                    <tr>
                        <th>Date Added</th>
                        <th>Date Removed</th>
                        <th>Video</th>
                    </tr>
                </thead>
                <tbody>

<?php

$mysqli = mysqli_connect($db_host, $db_user, $db_pass, $db_db);

$res = mysqli_query($mysqli, "SELECT * FROM `videos` WHERE `yt_channel` = '".$_SESSION["channel"]."' ORDER BY `date_added` DESC, `video_title` ASC;");

if ($res && mysqli_num_rows($res)) {
    while ($row = mysqli_fetch_assoc($res)) {
        if (is_null($row["date_removed"])) { $row["date_removed"] = "<i>(None)</i>"; $row_class = ""; } else { $row_status = "table-danger"; }
        echo "<tr class='".$row_class."'><td>".$row["date_added"]."</td><td>".$row["date_removed"]."</td><td><a href='https://youtube.com/watch?v=".$row["video_id"]."'>".$row["video_title"]."</a></td></tr>";
    }
    echo "</table>";
} else {
    echo "<p>No videos could be found for the selected YouTube channel.</p>";
}

?>
        </div>
    </div>
</div>
                </tbody>
            </table>

<?php require_once("../assets/footer/main.php"); ?>
