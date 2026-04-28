<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
require_once 'connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect the data from the AJAX FormData
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['first_name'];
            
            echo "success"; 
            exit();
        } else {
            echo "INVALID EMAIL OR PASSWORD";
            exit();
        }
    } catch (PDOException $e) {
        echo "DATABASE ERROR: " . $e->getMessage();
        exit();
    }
}
?>