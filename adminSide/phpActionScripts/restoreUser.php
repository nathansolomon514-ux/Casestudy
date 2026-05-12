<?php
    include_once "functions.php";
    include_once "../../Front-End/connection.php";
    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['restoreUser'])){
        $userID =  $_POST['userID'];

        if(restoreUser($con, $userID)){
            $page = $_POST['returnPage'] ?? 1;
            $search = $_POST['returnSearch'] ?? '';
            $status = $_POST ['returnStatus'] ?? 'activeUsers';
            header("Location: ../adminScreens/customerProfiles.php?status=restored&page=$page&searchBar=" . urlencode($search) . "&status=$status");
            exit();
        } else {
            echo "<script>alert('Error: Failed to restore customer'); window.location.href='../adminScreens/customerProfiles.php'; </script>";
        }
    }

?>