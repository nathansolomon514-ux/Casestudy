<?php
include_once "../../Front-End/connection.php";
include_once "functions.php";
include_once "sessionCheck.php";

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['addProduct'])) {
    
    // 1. Extract inputs
    $entryType = $_POST['entryType'] ?? 'new';
    $existingProductID = $_POST['productID'] ?? 0;
    $name = $_POST['newProductName'] ?? '';
    $catID = $_POST['categoryID'] ?? 0;
    $stock = $_POST['stock'] ?? 0;
    $price = $_POST['price'] ?? 0.00;
    $description = $_POST['newProductDescription'] ?? ''; 
    $tierID = 1; 


    // 2. Capture the TEXT inputs instead of IDs
    $sizeName = $_POST['sizeName'] ?? 'Default'; 
    $colorName = $_POST['colorName'] ?? 'Default';

    // 3. Keep your tracking info
    $page = $_POST['returnPage'] ?? 1;
    $search = $_POST['returnSearch'] ?? '';
    $status = $_POST['returnStatus'] ?? 'activeProducts';
    
    $sku = "SKU-PROD-" . time() . "-" . rand(10, 99);

    // 4. Pass them to the function


$variantImage = ''; 
if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['productImage']['tmp_name'];
    $fileName = $_FILES['productImage']['name'];
    $newFileName = time() . '_' . $fileName; // This matches your naming convention
    $uploadPath = '../../Resources/Images/' . $newFileName;

    if (move_uploaded_file($fileTmpPath, $uploadPath)) {
        $variantImage = $newFileName; // This is what goes into your database
    }
}

if (!empty($_POST['categoryName'])) {
    $finalCatID = getOrAddCategory($con, $_POST['categoryName']);
} else {
    $finalCatID = $_POST['categoryID'] ?? 0;
}


$executionSuccess = addProductVariant(
    $con, 
    $entryType, 
    $existingProductID, 
    $name, 
    $description, 
    $price, 
    $finalCatID, 
    $tierID, 
    $sizeName, 
    $colorName, 
    $sku, 
    $stock,   
    $price,   
    $variantImage 
);

    if ($executionSuccess) {
        header("Location: ../adminScreens/adminInventory.php?page=$page&searchBar=" . urlencode($search) . "&status=$status&add=success");
        exit();
    } else {
        echo "<script>alert('Error: Failed to process product addition sequence.'); window.location.href='../adminScreens/adminInventory.php'; </script>";
    }
}