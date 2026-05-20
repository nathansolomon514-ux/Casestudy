<?php
header('Content-Type: application/json');
include_once "../phpActionScripts/functions.php";
include_once "../../Front-End/connection.php";

$data = getPaymentMethodData($con);
echo json_encode($data);
exit;
?>