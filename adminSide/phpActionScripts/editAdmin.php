<?php 

include_once "functions.php";
include_once "../../Front-End/connection.php";
    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['updateAdmin'])) {
    $adminID = $_POST['adminID'];
    $adminFirstName = $_POST['adminFirstName'];
    $adminLastName = $_POST['adminLastName'];
    $adminEmail = $_POST['adminEmail'];
    $adminPassword = $_POST['adminPassword'] ?? null;

    if (!filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Error: Invalid Email Format. Try again.'); window.history.back();</script>";
        }


    if(updateAdmin($con, $adminID, $adminFirstName, $adminLastName, $adminEmail, $adminPassword)) {
        $page = $_POST['returnPage'] ?? 1;
        $search = $_POST['returnSearch'] ?? '';
        header("Location: ../adminScreens/adminUsers.php?statusFlag=success&page=$page&searchBar=" . urlencode($search));
        exit();
    }
        else {
            echo "<script>alert('Failed to update admin'); window.location.href='../adminScreens/adminUsers.php';</script>";
        }

}