<?php
include_once "functions.php";
include_once "../../Front-End/connection.php";
    if(isset($_POST['createAdmin'])) {
        $adminName = $_POST['adminName'];
        $adminPassword = $_POST['adminPassword'];
        $hashAdminPassword = passwordHashing($adminPassword);
        if (addAdmin($con, $adminName, $hashAdminPassword))
            {
                header("Location: ../adminScreens/adminUsers.php?status=success");
                exit();
            } else {
                echo "<script>alert('Error: Failed to add Admin'); </script>";
            }
    }
?>