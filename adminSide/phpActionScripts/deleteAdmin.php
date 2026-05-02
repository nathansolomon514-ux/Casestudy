<?php
    include_once "functions.php";
    include_once "../../Front-End/connection.php";
    if(isset($_POST['deleteAdmin'])){
        $adminID =  $_POST['adminID'];
        if(deleteAdmin($con, $adminID)){
            header("Location: ../adminScreens/adminUsers.php?status=deleted");
            exit();
        } else {
            echo "<script>alert('Error: Failed to delete Admin')";
        }
    }

?>