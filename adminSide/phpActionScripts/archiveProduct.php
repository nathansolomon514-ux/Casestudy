<?php
    include_once "functions.php";
    include_once "../../Front-End/connection.php";

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['deleteProduct'])) {
        $variantID = $_POST['variantID'];

        if(archiveProduct($con, $variantID)) {
            header("Location: ../adminScreens/adminInventory.php?status=archived");
            exit();
        } else {
            echo "<script>alert('Failed to Archive Product'); window.location.href='../adminScreens/adminInventory.php';</script>";
        }
    } 
        
    