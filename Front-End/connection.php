<?php
$host = '127.0.0.1'; 
$db   = 'krnkdb';    
$user = 'root';      
$pass = '';          

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $con = mysqli_connect($host, $user, $pass, $db);

    mysqli_set_charset($con, "utf8mb4");

} catch (mysqli_sql_exception $e) {

    throw new mysqli_sql_exception($e->getMessage(), $e->getCode());
}
?>