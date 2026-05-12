    <?php
        include_once "functions.php";
        include_once "../../Front-End/connection.php";

        $editAdminData = null;
            if(isset($_GET['editAdminID'])) {
            $editAdminData = getAdminByID($con, $_GET['editAdminID']);
        }
    ?>