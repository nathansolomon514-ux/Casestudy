<?php
    include "../phpActionScripts/sessionCheck.php";
    include "../../Front-End/connection.php";
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
        <a href="">User Menu</a>
        <a href="adminUsers.php">Admin Menu</a>
        <a href="">Orders</a>
        <a href="adminAccount.php">Admin Account</a>
    </nav>
        
        </div>
   <h3 class="welcomeAdmin">Welcome Admin, <?php echo htmlspecialchars($_SESSION['adminName']); ?></h3>


<main class="dashboardContent">

    <div class="dashboardStatistics" >
        <div class="statCard"> 
            <div class="cardTitle">Product in Stock</div>
            <div class="cardValue">Placeholder: 10</div>
        </div>

        <div class="statCard">
            <div class="cardTitle">User Accounts</div>
            <div class="cardValue">Placeholder: 10</div>
        </div>

        <div class="statCard">
            <div class="cardTitle">Admin Accounts</div>
            <div class="cardValue">Placeholder: 10</div>
        </div>

        <div class="statCard">
            <div class="cardTitle">Total Orders</div>
            <div class="cardValue">Placeholder: 10</div>
        </div>

        <div class="statCard">
            <div class="cardTitle">Orders Pending</div>
            <div class="cardValue">Placeholder: 10</div>
        </div>

        <div class="statCard">
            <div class="cardTitle">Test</div>
            <div class="cardValue">Placeholder: 10</div>
        </div>
    </div> 

</main>
</body>
</html>