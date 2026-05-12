<?php
    include_once "functions.php";
    include_once "../../Front-End/connection.php";
    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['restoreProduct'])){
        $variantID =  $_POST['variantID'];

        if(restoreProduct($con, $variantID)){
            $page = $_POST['returnPage'] ?? 1;
            $search = $_POST['returnSearch'] ?? '';
            $status = $_POST ['returnStatus'] ?? 'archivedProducts';
            header("Location: ../adminScreens/adminInventory.php?page=$page&searchBar=" . urlencode($search) . "&status=" . urlencode($status));
            exit();
        } else {
            echo "<script>alert('Error: Failed to restore products'); window.location.href='../adminScreens/adminInventory.php'; </script>";
        }
    }

?>