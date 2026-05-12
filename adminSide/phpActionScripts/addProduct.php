<?php
include_once "../../Front-End/connection.php";
include_once "functions.php";
include_once "sessionCheck.php";

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['addProduct'])) {
    $name = $_POST['productName'];
    $catID = $_POST['categoryID'];
    $sizeID = $_POST['sizeID'];
    $colorID = $_POST['colorID'];
    $stock = $_POST['stock'];
    $price = $_POST['price'];

    $page = $_POST['returnPage'] ?? 1;
    $search = $_POST['returnSearch'] ?? '';
    $status = $_POST['returnStatus'] ?? 'activeProducts';
    
    $description = "New product variant added via inventory management.";
    $basePrice = $price;    // Setting base price equal to the override for now
    $tierID = 1;            
    // Generates a unique SKU like: SKU-PROD-1715523456 
    $sku = "SKU-" . strtoupper(substr($name, 0, 3)) . "-" . time(); 
    $priceOverride = $price;

    if (addProductVariant($con, $name, $description, $basePrice, $catID, $tierID, $sizeID, $colorID, $sku, $stock, $priceOverride)) {
        header("Location: ../adminScreens/adminInventory.php?page=$page&searchBar=" . urlencode($search) . "&status=$status&add=success");
        exit();
    } else {
        echo "<script>alert('Error: Failed to add product'); window.location.href='../adminScreens/adminInventory.php'; </script>";
    }
}