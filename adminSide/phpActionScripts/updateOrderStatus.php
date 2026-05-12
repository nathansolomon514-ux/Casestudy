<?php
include_once "../../Front-End/connection.php";
include_once "sessionCheck.php";
include_once "functions.php";

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['order_id']) && isset($_POST['new_status'])) {
    $orderId = $_POST['order_id'];
    $newStatus = $_POST['new_status'];

    $page = $_POST['returnPage'] ?? 1;
    $search = $_POST['returnSearch'] ?? '';
    $status = $_POST['returnStatus'] ?? 'All';
    // Call the function from functions.php
    if (updateOrderStatus($con, $orderId, $newStatus)) {
        header("Location: ../adminScreens/adminOrders.php?page=$page&searchBar=" . urlencode($search) . "&status=$status&update=success");
        exit();
    } else {
        echo "<script>alert('Error: Failed to update order status of customer'); window.location.href='../adminScreens/adminOrders.php'; </script>";
    }
    exit();
}