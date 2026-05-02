<?php
    include_once "../phpActionScripts/sessionCheck.php";
    include_once "../../Front-End/connection.php";
    include_once "../phpActionScripts/functions.php";
    include_once "../phpActionScripts/addAdmin.php";
    //include_once "../phpActionScripts/fetchEditAdmin.php";
    include_once "../phpActionScripts/deleteAdmin.php";

    $adminSearch = $_GET["searchBar"] ?? "";
    $result = adminQuery($con, $adminSearch);
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
        <a href="">User Menu</a>
        <a href="adminUsers.php">Admin Menu</a>
        <a href="">Orders</a>
        <a href="adminAccount.php">Admin Account</a>
    </nav>
        
        </div>
   <h3 class="welcomeAdmin">Welcome Admin, <?php echo htmlspecialchars($_SESSION['adminName']); ?></h3>


<main class="dashboardContent">

        <i class="fa fa-plus" aria-hidden="true"></i><button onclick="adminModal.showModal()"> Add Admin </button>
    <form action="adminUsers.php" method="get">
    <label>Search: </label>
    <input type="text" name="searchBar" placeholder="Search Admin">
    <button type="submit">Search</button>
</form>

    <div class="inventoryContainer">
    <!--Table Dashboard -->
    <table class="inventoryTable">
        <thead>
            <tr>
                 <th>AdminID</th>
                 <th>Admin Name</th>
                 <th>Admin Password</th>
                 <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php //checks if the database returns any rows
                if (mysqli_num_rows($result) > 0){
                    //checks each following row
                    while ($row = mysqli_fetch_assoc($result)){ ?>
                        <tr>
                    <td><?php echo htmlspecialchars($row['adminID']); ?> </td>
                    <td><?php echo htmlspecialchars($row['adminName']); ?> </td>
                    <td><main><?php echo substr($row['adminPassword'],0, 20). "..."; ?> </main></td>

                    <td>
                        <div class="actionContainer">
                            <!--Edit-->
                        <a href="adminUsers.php?editAdminID=<?php echo $row['adminID']; ?>" class="editLink">Edit</a>
                            <!--Delete-->
                            <form action="../phpActionScripts/deleteAdmin.php" method="POST" onsubmit="return confirm('Delete this Admin');">
                                <input type="hidden" name="adminID" value="<?php echo $row['adminID']; ?>">
                                <button type="submit" name="deleteAdmin" class="deleteButton"> Delete </button>
                            </form>
                        </div>
                    </td>
                </tr>
                    
                    <?php
                    }
                }
                 else {
                    echo "<tr>
                            <td colspan = '3' style='text-align: center;'> No admins found matching your request. </td>
                            </tr>";
                    }                  
                ?>
                
        </tbody>

    </table>
    </div>
</main>

    <!--Modal for Adding admins-->
    <dialog id="adminModal" class="adminModal">
        <div class="modalHeader">
            <h2>Add New Admin</h2>
            <form action="" method="dialog">
                <button class="closeButton">&times</button> 
            </form>
        </div>
        
        <form action="../phpActionScripts/addAdmin.php" method="post">
            <div class="modalContent">
                <label>Admin Name</label>
                <input type="text" name="adminName" required>

                <label>Password</label>
                <input type="password" name="adminPassword" required>
            </div>

            <div class="modalFooter">
                <button type="submit" name="createAdmin"> Create Admin</button>
            </div>
        </form>
    </dialog>
</body>
</html>