<?php
error_reporting(0);
header('Content-Type: application/json');

include_once "../../Front-End/connection.php";
include_once "../phpActionScripts/functions.php";

$data = getWeeklyRevenueData($con);
echo json_encode($data);
?>