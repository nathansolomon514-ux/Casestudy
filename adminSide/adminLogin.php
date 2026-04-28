<?php
    include "functions.php";
session_start();
//Hardcoded since no database atm
    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["adminLogin"])){
        $placeholderUser = "Admin123";
        $placeholderPass = "pass312";

        $usernameAuth = dataCleaning($_POST["username"]);
        $passwordAuth = dataCleaning($_POST["password"]);

            

    if($usernameAuth === "Admin123" && $passwordAuth === "pass312")
        {
            //Pass
            $_SESSION['adminStatus'] = "logged_in";
            header("Location: adminDashboard.php");
            exit();
        } else {
            header("Location: adminLoginError.php?error=invalid");
            exit();
        }
}

