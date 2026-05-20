<?php
    include_once "../phpActionScripts/sessionCheck.php";
    include_once "../../Front-End/connection.php";
    include_once "../phpActionScripts/functions.php"; 
    include_once "../phpActionScripts/dashboardCounter.php";
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../adminCSS/adminDashboard.css">
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
   <h3 class="welcomeAdmin">Welcome Admin, <?php echo htmlspecialchars($_SESSION['adminName']); ?></h3>


<main class="dashboardContent">
    <div class="dashboardStatistics">
        <a href="adminInventory.php" style="text-decoration: none;">
            <div class="statCard"> 
                <div class="cardTitle">Products in Stock</div>
                <div class="cardValue"><?php echo $countProducts; ?></div>
            </div>
        </a>

        <a href="customerProfiles.php" style="text-decoration: none;">
        <div class="statCard">
            <div class="cardTitle">User Accounts</div>
            <div class="cardValue"><?php echo $countUsers; ?></div>
        </div>
        </a>

        <a href="adminUsers.php" style="text-decoration: none;">
        <div class="statCard">
            <div class="cardTitle">Admin Accounts</div>
            <div class="cardValue"><?php echo $countAdmins; ?></div>
        </div>
         </a>

        <a href="adminOrders.php" style="text-decoration: none;">
        <div class="statCard">
            <div class="cardTitle">Total Orders</div>
            <div class="cardValue"><?php echo $countOrders; ?></div>
        </div>
        </a>

        <a href="adminOrders.php" style="text-decoration: none;">
        <div class="statCard">
            <div class="cardTitle">Orders Pending</div>
            <div class="cardValue"><?php echo $pendingOrders; ?></div>
        </div>
            </a>

        <a href="adminOrders.php" style="text-decoration: none;">
             <div class="statCard">
                <div class="cardTitle">Top Courier</div>
                <div class="cardValue">
                    <?php echo $popularCourier ? htmlSpecialCharss($popularCourier['full_name']) : "No Deliveries"; ?>
                </div>
    </div>
        </a>

        <a href="adminOrders.php" style="text-decoration: none;">
            <div class="statCard">
                <div class="cardTitle">Daily Revenue</div>
                <div class="cardValue">₱<?php echo number_format($dailyRevenue, 2); ?></div>
            </div>
        </a>

        <a href="adminOrders.php" style="text-decoration: none;">
            <div class="statCard">
                <div class="cardTitle">Weekly Revenue</div>
                <div class="cardValue">₱<?php echo number_format($weeklyRevenue, 2); ?></div>
            </div>
        </a>

        <a href="adminOrders.php" style="text-decoration: none;">
            <div class="statCard">
                <div class="cardTitle">Monthly Revenue</div>
                <div class="cardValue">₱<?php echo number_format($monthlyRevenue, 2); ?></div>
            </div>
        </a>

        <a href="adminInventory.php" style="text-decoration: none;">
    <div class="statCard">
        <div class="cardTitle">Top Selling Variant</div>
        <div class="cardValue" style="font-size: 0.95rem; line-height: 1.3; padding: 5px 10px; word-break: break-word; max-width: 100%;">
            <?php echo $popularProduct ? htmlSpecialCharss($popularProduct['variant_full_name']) : "No Sales Yet"; ?>
        </div>
        <small style="color: #666; font-size: 0.8rem;">
            <?php echo $popularProduct ? $popularProduct['order_count'] . " orders" : ""; ?>
        </small>
    </div>
</a>

<a href="adminInventory.php" style="text-decoration: none;">
    <div class="statCard">
        <div class="cardTitle">Least Selling Variant</div>
        <div class="cardValue" style="font-size: 0.95rem; line-height: 1.3; padding: 5px 10px; word-break: break-word; max-width: 100%;">
            <?php echo $leastProduct ? htmlSpecialCharss($leastProduct['variant_full_name']) : "No Sales Yet"; ?>
        </div>
        <small style="color: #666; font-size: 0.8rem;">
            <?php echo $leastProduct ? $leastProduct['order_count'] . " orders" : ""; ?>
        </small>
    </div>
</a>
            </div>


    </div> 
</main>
</body>
</html>