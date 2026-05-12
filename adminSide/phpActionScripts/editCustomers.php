<?php 

include_once "functions.php";
include_once "../../Front-End/connection.php";
    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['updateCustomer'])) {
    $userID = $_POST['userID'];
    $userFirstName = $_POST['userFirstName'];
    $userLastName = $_POST['userLastName'];

    if(updateCustomer($con, $userID, $userFirstName, $userLastName)) {
            $page = $_POST['returnPage'] ?? 1;
            $search = $_POST['returnSearch'] ?? '';
            $status = $_POST ['returnStatus'] ?? 'activeUsers';
        header("Location: ../adminScreens/customerProfiles.php?status=success&page=$page&searchBar=" . urlencode($search) . "&status=$status");
        exit();
    }
        else {
            echo "<script>alert('Failed to update customer'); window.location.href='../adminScreens/customerProfiles.php';</script>";
        }

}
?>