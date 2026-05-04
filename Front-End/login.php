<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
require_once 'connection.php'; // Ensure this file now defines $mysqli
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect the data from the AJAX FormData
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        // 1. Prepare the statement
        $stmt = $mysqli->prepare("SELECT * FROM users WHERE email = ?");
        
        // 2. Bind the email parameter ("s" for string)
        $stmt->bind_param("s", $email);
        
        // 3. Execute and get the result set
        $stmt->execute();
        $result = $stmt->get_result();
        
        // 4. Fetch the user data as an associative array
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['first_name'];
            
            echo "success"; 
            exit();
        } else {
            echo "INVALID EMAIL OR PASSWORD";
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        echo "DATABASE ERROR: " . $e->getMessage();
        exit();
    }
}
?>
