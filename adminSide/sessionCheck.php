<?php
    session_start();
    if(!isset($_SESSION['adminStatus']) || $_SESSION['adminStatus'] !== "logged_in") {
        header("Location: adminLogin.php");
        exit();
    }
    ?>