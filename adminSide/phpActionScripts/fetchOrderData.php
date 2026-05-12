    <?php
        include_once "functions.php";
        include_once "../../Front-End/connection.php";

        $viewOrderId = $_GET['view_order'] ?? null;
        $viewDetails = null;
        if ($viewOrderId) {
            $viewDetails = getOrderItemsList($con, $viewOrderId);
        }
    ?>