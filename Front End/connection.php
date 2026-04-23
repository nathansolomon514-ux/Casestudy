<?php
$dbhost="losthost";
$dbuser="root";
$dppass="";
$dbname="login_sample_db";

if(!con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname))
{
    die("Failed to Connect!");
}
?>