<?php
include_once "../../Front-End/connection.php";
include_once "functions.php";
include_once "sessionCheck.php";

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['addProduct'])) {
    
    // Extract shared form tracking metrics
    $description = isset($_POST['newProductDescription']) ? mysqli_real_escape_string($con, $_POST['newProductDescription']) : '';
    $entryType         = $_POST['entryType'] ?? 'new';
    $existingProductID = $_POST['productID'] ?? 0;
    
    $name              = $_POST['newProductName'] ?? '';
    $catID             = $_POST['categoryID'] ?? 0;
    $sizeID            = $_POST['sizeID'] ?? null;
    $colorID           = $_POST['colorID'] ?? null;
    $stock             = $_POST['stock'] ?? 0;
    $price             = $_POST['price'] ?? 0.00;
    
    // CHANGED: Check if a description was typed. If not, fallback to a clean blank string or default text.
    $description       = $_POST['newProductDescription'] ?? ''; 
    $tierID            = 1; 

    // Capture return tracking indicators for interface preservation
    $page   = $_POST['returnPage'] ?? 1;
    $search = $_POST['returnSearch'] ?? '';
    $status = $_POST['returnStatus'] ?? 'activeProducts';
    
    // Unique item tracker hash key properties sequence definition
    $sku = "SKU-PROD-" . time() . "-" . rand(10, 99);

    // Call centralized logic context function completely isolated in functions.php
    $executionSuccess = addProductVariant(
        $con, 
        $entryType, 
        $existingProductID, 
        $name, 
        $description, // Passes the description from the form safely here!
        $price,       // Maps to $basePrice (double type)
        $catID, 
        $tierID, 
        $sizeID, 
        $colorID, 
        $sku, 
        $stock,       // Maps to $stock (integer type)
        $price        // Maps to $priceOverride (double type)
    );

    if ($executionSuccess) {
        header("Location: ../adminScreens/adminInventory.php?page=$page&searchBar=" . urlencode($search) . "&status=$status&add=success");
        exit();
    } else {
        echo "<script>alert('Error: Failed to process product addition sequence.'); window.location.href='../adminScreens/adminInventory.php'; </script>";
    }
}