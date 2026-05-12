    <?php
        include_once "functions.php";
        include_once "../../Front-End/connection.php";

        $editUserData = null;
            if(isset($_GET['editUserID'])) {
            $editUserData = getUserByID($con, $_GET['editUserID']);
        }
    ?>