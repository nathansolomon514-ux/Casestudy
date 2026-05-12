<?php 
    include_once "functions.php";
    include_once "../../Front-End/connection.php";

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['updateStock'])) {
        $variantID = $_POST['variantID'];
        $newStock = $_POST['newStock'];

        if (updateProductStock($con, $variantID, $newStock)){
            header("Location: ../adminScreens/adminInventory.php?update=success");
            exit();
        }
    }

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['updatePrice'])) {
        $variantID = $_POST['variantID'];
        $newPrice = $_POST['newPrice'];

        if (updateProductPrice($con, $variantID, $newPrice)) {
            header("Location: ../adminScreens/adminInventory.php?update=success");
            exit();
        }
    }

        if($_SERVER['REQUEST_METHOD'] == "POST"){
            echo "<script>alert('Failed to Edit Product'); window.location.href='../adminScreens/adminInventory.php';</script>";
            exit();
        }
         
    
    ?>