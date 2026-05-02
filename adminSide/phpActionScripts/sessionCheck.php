<?php
    session_start();
    include_once "functions.php";
    include_once "../../Front-End/connection.php";
    if(!isset($_SESSION['adminStatus']) || $_SESSION['adminStatus'] !== "logged_in") {
        header("Location: ../adminScreens/adminLogin.php");
        exit();
    }
    ?>