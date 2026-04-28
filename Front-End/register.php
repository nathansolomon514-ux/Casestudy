<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
require_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // These names must match the 'name' attribute in your HTML inputs
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

   try {
    $stmt = $pdo->prepare("INSERT INTO users (email, password_hash, first_name, last_name) VALUES (?, ?, ?, ?)");
    $stmt->execute([$email, $hashed_password, $first_name, $last_name]);
    echo "success"; // AJAX looks for this exact word
    exit();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
}
?>