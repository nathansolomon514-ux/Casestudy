<?php
    include_once "functions.php";
    include_once "../../Front-End/connection.php";
    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['deleteAdmin'])){
        $adminID =  $_POST['adminID'];
        if(deleteAdmin($con, $adminID)){
            $page = $_POST['returnPage'] ?? 1;
            $search = $_POST['returnSearch'] ?? '';
            header("Location: ../adminScreens/adminUsers.php?statusFlag=deleted&page=$page&searchBar=" . urlencode($search));
            exit();
        } else {
            echo "<script>alert('Error: Failed to delete Admin')";
        }
    }

?>