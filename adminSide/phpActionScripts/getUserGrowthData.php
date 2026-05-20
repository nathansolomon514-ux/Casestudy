<?php
error_reporting(0);
header('Content-Type: application/json');
include_once "../../Front-End/connection.php";
include_once "../phpActionScripts/functions.php";

$data = getUserGrowthData($con);
echo json_encode($data);
?>