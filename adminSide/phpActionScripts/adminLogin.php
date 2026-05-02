<?php
    include_once "functions.php";
    include_once "../../Front-End/connection.php";
session_start();
//Hardcoded since no database atm
    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["adminLogin"])){
        $adminID = $_POST['adminID'];
        $adminPassword = $_POST['password'];
            
            $adminQuery = "SELECT * FROM ADMINS WHERE adminID=?";
            $stmt = mysqli_prepare($con, $adminQuery);
            mysqli_stmt_bind_param($stmt, "i", $adminID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if(password_verify($adminPassword, $row['adminPassword'])) {
            $_SESSION['adminStatus'] = 'logged_in';
            $_SESSION['adminID'] = $row['adminID'];
            $_SESSION['adminName'] = $row['adminName'];
            header("Location: ../adminScreens/adminDashboard.php");
            exit();
        }
    } else {
         echo "<script>alert('Error: Failed to Login'); </script>";
    }
}

