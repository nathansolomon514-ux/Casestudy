<?php
    include_once "function.php";
    include_once "../../Front-End/connection.php";

    $editAdminID = null;
    if (isset($_GET['editAdminID'])) {
        $editAdminData = getAdminByID($con, $_GET['editAdminID'])
    }
?>