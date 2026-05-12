<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
require_once 'connection.php'; /
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name  = $_POST['last_name'];
    $email      = $_POST['email'];
    $password   = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $mysqli->prepare("INSERT INTO users (email, password_hash, first_name, last_name) VALUES (?, ?, ?, ?)");
        
        $stmt->bind_param("ssss", $email, $hashed_password, $first_name, $last_name);
        

        if ($stmt->execute()) {
            echo "success"; 
        } else {
            echo "Error: Could not execute query.";
        }
        
        $stmt->close();
        exit();

    } catch (mysqli_sql_exception $e) {

        echo "Error: " . $e->getMessage();
        exit();
    }
}
?>
