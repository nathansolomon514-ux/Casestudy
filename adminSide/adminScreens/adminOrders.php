<?php
    include_once "../phpActionScripts/sessionCheck.php";
    include_once "../../Front-End/connection.php";
    include_once "../phpActionScripts/functions.php";
    include_once "../phpActionScripts/fetchOrderData.php";
    include_once "../phpActionScripts/updateOrderStatus.php";


    $statusFilter = $_GET['status'] ?? 'All';
    //$isArchived = ($statusView === 'archivedUsers');

    $orderSearch = $_GET["searchBar"] ?? "";
    //$result = customerQuery($con, $adminSearch);
    $limit = 5; //rows to return
    //rows to show next page
    $page = isset($_GET['page']) ? /*(int)*/$_GET['page'] : 1;
    if ($page < 1) $page = 1;
    $offset = ($page - 1) * $limit;

    $result = getOrders($con, $limit, $offset, $orderSearch, $statusFilter);
?>
    

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../adminCSS/adminInventory.css">
    <title>Admin Dashboard</title>
    
</head>
<body>
    <!--Logo Header-->
    <div class="header">
        <img src="../../Resources/Images/Logo.png" class="logo-black"alt="Black Logo">
    </div>
    <!--Navigation Header-->
        <div class="navHeader">
    <nav>
        <a class="active" href="adminDashboard.php">Dashboard</a>
        <a href="adminInventory.php">Inventory</a>
        <a href="customerProfiles.php">User Menu</a>
        <a href="adminUsers.php">Admin Menu</a>
        <a href="adminOrders.php">Orders</a>
        <a href="adminAccount.php">Admin Account</a>
    </nav>
        
        </div>
   <h3 class="welcomeAdmin">Welcome Admin, <?php echo htmlSpecialCharss($_SESSION['adminName']); ?></h3>


<main class="dashboardContent">

    <form action="adminOrders.php" method="get" id="filterForm">
        <!--Search Bar-->
    <label>Search: </label>
    <input type="text" name="searchBar" placeholder="Search Customer" value="<?php echo htmlSpecialCharss($orderSearch); ?>">
    <button type="submit" style="margin-bottom: 10px; padding: 8px 15px; background: #dac50b; color: white; border: none; border-radius: 4px; cursor: pointer;">Search</button>

    <!--Active Inactive Filter-->
        <label>Order Status</label>
        <select name="status" onchange="this.form.submit()">
            <option value="All" <?php echo ($statusFilter == 'All') ? 'selected' : ''; ?>>All Orders</option>
            <option value="1" <?php echo ($statusFilter == '1') ? 'selected' : ''; ?>>Pending</option>
            <option value="2" <?php echo ($statusFilter == '2') ? 'selected' : ''; ?>>Processing</option>
            <option value="3" <?php echo ($statusFilter == '3') ? 'selected' : ''; ?>>Shipped</option>
            <option value="4" <?php echo ($statusFilter == '4') ? 'selected' : ''; ?>>Delivered</option>
            <option value="5" <?php echo ($statusFilter == '5') ? 'selected' : ''; ?>>Cancelled</option>
        
        </select>

        </form>

    <div class="inventoryContainer">
    <!--Table Dashboard -->
    <table class="inventoryTable">
        <thead>
            <tr>
                <th>Order ID</th>
                 <th>Date</th>
                 <th>Customer</th>
                 <th>Total Amount</th>
                 <th>Courier Name</th>
                 <th>Destination</th>
                 <th>Status</th>
                 <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php 
                if(mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) { 
                        //$addressArray = !empty($row['all_addresses']) ? explode('|', $row['all_addresses']) : [];
                        $fullName = $row['first_name']. " ". $row['last_name'];

                        ?>
                <tr>
                        <td> <?php echo htmlSpecialCharss($row['order_id']); ?>                                  </td>
                        <td> <?php echo date('M d, Y', strtotime($row['created_at'])); ?>                        </td>
                        <td> <?php echo htmlSpecialCharss($fullName); ?>                                          </td>
                        <td> <?php echo number_format($row['total_amount'], 2); ?>                               </td>
                        <td> <?php echo htmlSpecialCharss($row['courier_name'] ?? 'not selected') ;?>            </td>
                        <td> <?php echo htmlSpecialCharss($row['shipping_address'] ?? 'no address provided') ;?> </td>
                        <td>
                            <form action="../phpActionScripts/updateOrderStatus.php" method="POST">
                                <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                <input type="hidden" name="returnPage" value="<?php echo $page; ?>">
                                <input type="hidden" name="returnSearch" value="<?php echo htmlSpecialCharss($orderSearch); ?>">
                                <input type="hidden" name="returnStatus" value="<?php echo $statusFilter; ?>">

                                <select name="new_status" onchange="this.form.submit()" style="padding: 5px; border-radius: 4px;">
                                    <option value="1" <?php echo ($row['order_status_id'] == 1) ? 'selected' : ''; ?>>Pending</option>
                                    <option value="2" <?php echo ($row['order_status_id'] == 2) ? 'selected' : ''; ?>>Processing</option>
                                    <option value="3" <?php echo ($row['order_status_id'] == 3) ? 'selected' : ''; ?>>Shipped</option>
                                    <option value="4" <?php echo ($row['order_status_id'] == 4) ? 'selected' : ''; ?>>Delivered</option>
                                </select>
                            </form>
                        </td>
                    <td>
                        <div class="actionContainer">
                            <!--Open order modal--> 
                                <button type="button" onclick="openOrderModal('<?php echo $row['order_id'];?>')"> View Details </button>
                        </div>
                    </td>
                </tr>
            <?php 
                    } // end of while
                } else {
                    echo "<tr> 
                            <td colspan='8' style='text-align: center;'> No Orders Matching Your Request. </td>
                            </tr>";

                } ?>

        </tbody>

    </table>
    </div>

    <div class="pagination">
        <!--Prev button-->
            <?php if ($page > 1): ?>
                <a href="adminOrders.php?page=<?php echo $page - 1; ?>&searchBar=<?php echo htmlSpecialCharss(urlencode($orderSearch)); ?>&status=<?php echo $statusFilter;?>"  class= "prevNext">« Previous</a>
                <?php endif; ?>
        <span class="pageNumber">Page <?php echo $page; ?></span>

        <!--Next Button--> 
            <?php if (mysqli_num_rows($result) == $limit) : ?>
                <a href="adminOrders.php?page=<?php echo $page + 1?>&searchBar=<?php echo htmlSpecialCharss(urlencode($orderSearch)); ?>&status=<?php echo $statusFilter;?>"  class= "prevNext"> Next » </a>
                <?php endif; ?>
            </div>

</main>

            <!--Order Details Modal--> 
        <dialog id="orderDetailModal" class="addressModal">
            <div class="modalWrapper">
                <div class = "modalHeader">
                    <h2>Order Details #<?php echo htmlSpecialCharss($viewOrderId ?? ''); ?></h2>
                   <!-- <button type="button" onclick="document.getElementById('orderDetailModal').close()" class="closeButton">&times;</button> -->
                    <button type="button" onclick="closeModal()" class="closeButton">&times;</button>

                </div>
                <div class="modalContent" style="max-height: 60vh; overflow-y: auto; padding: 20px;">
                    <ul style="list-style: none; padding: 0;">
                    <?php if ($viewDetails && mysqli_num_rows($viewDetails) > 0): ?>
                        <?php while($item = mysqli_fetch_assoc($viewDetails)): ?>
                            <li style = "padding: 8px; border-bottom: 1px solid #ddd;">
                                <strong><?php echo $item['quantity']; ?>x</strong>
                                <?php echo htmlSpecialCharss($item['product_name']); ?>
                                <small style="color: #666;">
                                    Category: <?php echo htmlSpecialCharss($item['category_name']); ?> |
                                    Tier: <?php echo htmlSpecialCharss($item['tier_name']); ?> |
                                    Color: <?php echo htmlSpecialCharss($item['color_name']); ?>
                                (₱<?php echo number_format($item['price_at_purchase'], 2); ?>)
                        </li>
                        <?php endwhile; ?>
                        <?php else: ?>
                            <li style = "padding: 8px;">Select on order to view details.</li>
                            <?php endif;?>
                </ul>
                </div>
            </div>
        </dialog>


    <script>

    //php based rendering
    function openOrderModal(orderId) {
        const params = new URLSearchParams(window.location.search);
        params.set('view_order', orderId);
        window.location.href = "adminOrders.php?" + params.toString();
    }

    function closeModal() {
        const params = new URLSearchParams(window.location.search);
        params.delete('view_order');
        window.location.href = "adminOrders.php?" + params.toString();
    }

    const orderDetailModal= document.getElementById('orderDetailModal');
    
    <?php if(isset($viewDetails) && $viewDetails !== null) : ?>
        if(orderDetailModal) {
            orderDetailModal.showModal();
        }
        <?php endif; ?>
    
                </script>

</body>
</html>