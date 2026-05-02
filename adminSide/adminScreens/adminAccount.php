<?php
    include_once "../phpActionScripts/sessionCheck.php";
    include_once "../../Front-End/connection.php";
    include_once "../phpActionScripts/functions.php";
?>

    

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../adminCSS/adminInventory.css">
    <title>Admin Account</title>
    
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

    <div class="inventoryContainer">
        <h2>Account Details</h2>
        <hr>
        <div style = "padding: 20px; 
                     line-height: 2;">
                     
                     <!--Display Session Data--> 
                        <P><strong>Admin ID: </strong> <?php echo $_SESSION['adminID'] ?? 'N/A'; ?></p>
                        <P><strong>Admin Name: </strong> <?php echo htmlspecialchars($_SESSION['adminName']); ?></p>
                        <br>
                    <!--Terminates Session... supposedly-->
                    <form action="../phpActionScripts/adminLogout.php" method="post">
                        <button type="submit" name="logout" class="logoutButton">LOGOUT</button>
                  </form>
        </div>
</main>
</body>
</html>