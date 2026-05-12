<?php
    session_start();
    include_once "functions.php";
    include_once "../../Front-End/connection.php";
    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["adminLogin"])){
        $adminID = $_POST['adminID'];
        $adminPassword = $_POST['password'];
            
        $adminData = authenticateAdmin($con, $adminID, $adminPassword);

    if ($adminData) {
            $_SESSION['adminStatus'] = 'logged_in';
            $_SESSION['adminID'] = $adminData['admin_id'];
            $_SESSION['adminName'] = $adminData['first_name']. " ". $adminData['last_name'];
            header("Location: ../adminScreens/adminDashboard.php");
            exit();
        
    } else {
         echo "<script>alert('Error: Failed to Login'); </script>";
    }
}

