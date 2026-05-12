<?php
    include_once "functions.php";
    include_once "../../Front-End/connection.php";
    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['deleteUser'])){
        $userID =  $_POST['userID'];
        if(deleteUser($con, $userID)){
            $page = $_POST['returnPage'] ?? 1;
            $search = $_POST['returnSearch'] ?? '';
            $status = $_POST ['returnStatus'] ?? 'activeUsers';
            header("Location: ../adminScreens/customerProfiles.php?status=deleted&page=$page&searchBar=" . urlencode($search) . "&status=$status");
            exit();
        } else {
            echo "<script>alert('Error: Failed to delete customer'); window.location.href='../adminScreens/customerProfiles.php';";
        }
    }

?>