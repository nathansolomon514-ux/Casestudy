<?php 

include_once "functions.php";
include_once "../../Front-End/connection.php";
if(isset($_POST['updateAdmin'])) {
    $adminID = $_POST['adminID'];
    $adminName = $_POST['adminName'];
    $adminPassword = $_POST['adminPassword'] ?? null;

    if(updateAdmin($con, $adminID, $adminName, $adminPassword)) {
        header("Location:../adminUsers/adminUsers.php?status=success");
        exit();
    }
        else {
            echo "<script>alert('Failed to update admin');</script>";
        }

}