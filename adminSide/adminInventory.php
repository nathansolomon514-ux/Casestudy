<?php
    include "sessionCheck.php";
    include "../Front-End/connection.php";
    include "functions.php";

    $search = $_GET["searchBar"] ?? "";
    $result = searchInventory($con, $search);
?>
    

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="adminInventory.css">
    <title>Admin Dashboard</title>
    
</head>
<body>
    <!--Logo Header-->
    <div class="header">
        <img src="../Resources/Images/Logo.png" class="logo-black"alt="Black Logo">
    </div>
    <!--Navigation Header-->
        <div class="navHeader">
    <nav>
        <a class="active" href="adminDashboard.php">Dashboard</a>
        <a href="adminInventory.php">Inventory</a>
        <a href="">User Menu</a>
        <a href="">Admin Menu</a>
        <a href="">Orders</a>
        <a href="">Admin Account</a>
    </nav>
        
        </div>
   <h3 class="welcomeAdmin">Welcome Admin, <?php echo "Admin123"//$placeholderUser?></h3>


<main class="dashboardContent">

    <form action="adminInventory.php" method="get">
    <label>Search: </label>
    <input type="text" name="searchBar" placeholder="Search Item">
    <button type="submit">Search</button>
</form>

    <div class="inventoryContainer">
    <!--Table Dashboard -->
    <table class="inventoryTable">
        <thead>
            <tr>
                 <th>Product</th>
                 <th>Stock</th>
                 <th>Price</th>
            </tr>
        </thead>

        <tbody>
            <?php //checks if the database returns any rows
                if (mysqli_num_rows($result) > 0){
                    //checks each following row
                    while ($row = mysqli_fetch_assoc($result)){ ?>
                        <tr>
                    <td><?php echo htmlspecialchars($row['productName']); ?> </td>
                    <td><?php echo $row['stock'];?> </td>
                    <td><?php echo number_format($row['price'], 2); ?> </td>
                </tr>
                    
                    <?php
                    }
                }
                 else {
                    echo "<tr>
                            <td colspan = '3' style='text-align: center;'> No products found matching your request. </td>
                            </tr>";
                    }                  
                ?>
                
        </tbody>

    </table>
    </div>
</main>
</body>
</html>