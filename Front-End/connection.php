<?php
// Database configuration
$host = '127.0.0.1'; // Standard XAMPP host
$db   = 'krnkdb';    // Your database name from phpMyAdmin
$user = 'root';      // Default XAMPP username
$pass = '';          // Default XAMPP password is empty
$charset = 'utf8mb4';

// The Data Source Name (DSN)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Extra options for security and error handling
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throws errors so you can find them
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Returns results as easy arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Uses actual prepared statements for security
];

try {
     // Create the connection
     $pdo = new PDO($dsn, $user, $pass, $options);
     // Optional: echo "Connected successfully!"; // Use this just for testing
} catch (PDOException $e) {
     // If connection fails, stop the script and show why
     throw new PDOException($e->getMessage(), (int)$e->getCode());
}
?>