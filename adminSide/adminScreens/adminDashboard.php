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
    </div> 
</main>
</body>
</html>