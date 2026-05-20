<?php
    include_once "../phpActionScripts/sessionCheck.php";
    include_once "../../Front-End/connection.php";
    include_once "../phpActionScripts/functions.php";
    include_once "../phpActionScripts/addAdmin.php";
    include_once "../phpActionScripts/editAdmin.php";
    include_once "../phpActionScripts/fetchEditAdmin.php";
    include_once "../phpActionScripts/deleteAdmin.php";

    $adminSearch = $_GET["searchBar"] ?? "";

    $limit = 10; //rows to return

    //rows to show next page
    $page = isset($_GET['page']) ? /*(int)*/$_GET['page'] : 1;
    if ($page < 1) $page = 1;

    $offset = ($page - 1) * $limit;
    $result = adminQuery($con, $adminSearch, $limit, $offset);
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

        <i class="fa fa-plus" aria-hidden="true"></i><button onclick="adminModal.showModal()" style="margin-bottom: 10px; padding: 8px 15px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;"> Add Admin </button>
    <form action="adminUsers.php" method="get">
    <label>Search: </label>
    <input type="text" name="searchBar" placeholder="Search Admin">
    <button type="submit" style="margin-bottom: 10px; padding: 8px 15px; background: #dac50b; color: white; border: none; border-radius: 4px; cursor: pointer;">Search Admin</button>
</form>

    <div class="inventoryContainer">
    <!--Table Dashboard -->
    <table class="inventoryTable">
        <thead>
            <tr>
                 <th>AdminID</th>
                 <th>Admin Name</th>
                 <th>Email</th>
                 <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php //checks if the database returns any rows
                if (mysqli_num_rows($result) > 0){
                    //checks each following row
                    while ($row = mysqli_fetch_assoc($result)){ 
                        $fullName = $row['first_name']. " ". $row['last_name'];?>
                    
                        <tr>
                    <td><?php echo htmlSpecialCharss($row['admin_id']); ?> </td>
                    <td><?php echo htmlSpecialCharss($fullName); ?> </td>
                    <td><main><?php echo htmlSpecialCharss($row['email']); ?> </main></td>

                    <td>
                        <div class="actionContainer">
                            <!--Edit-->
                            <!--Turns each row in the table into a link-->
                        <a href="adminUsers.php?editAdminID=<?php echo htmlSpecialCharss($row['admin_id']); ?>&page=<?php echo $page; ?>&searchBar=<?php echo urlencode($adminSearch); ?>" style="background-color: #28a745; color: white; border: none; padding: 5px 15px; cursor: pointer; border-radius: 4px; display: block; margin: 0 auto 5px auto; width: 80px; text-align: center; text-decoration: none;">Edit</a>
                            <!--Delete-->
                            <form action="../phpActionScripts/deleteAdmin.php" method="POST" onsubmit="return confirm('Delete this Admin');">
                                <input type="hidden" name="adminID" value="<?php echo $row['admin_id']; ?>">
                                <input type="hidden" name="returnPage" value="<?php echo $page; ?>">
                                <input type="hidden" name="returnSearch" value="<?php echo htmlSpecialCharss($adminSearch); ?>">

                                
                                <button type="submit" name="deleteAdmin" style="background-color: #dc3534; color: white; border: none; padding: 5px 15px; cursor: pointer; border-radius: 4px; display: block; margin: 0 auto; width: 110px;"> Delete </button>
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
                
            <dialog id="editAdminModal" class="adminModal" style="padding: 0; border: none; border-radius: 15px; width: 450px; max-width: 90vw; margin: auto; overflow: hidden;">
            <div class="modalWrapper" style="padding: 0; min-width: 100%;">
                <div class="modalHeader">
                    <h2>Edit Admin</h2>
                    <a href="adminUsers.php?page=<?php echo $page; ?>&searchBar=<?php echo urlencode($adminSearch);?>" class="closeButton">&times;</a>
                </div>

                <form action="../phpActionScripts/editAdmin.php" method="post">
                    <input type="hidden" name="returnPage" value="<?php echo $page; ?>">
                    <input type="hidden" name="returnSearch" value="<?php echo htmlSpecialCharss($adminSearch); ?>">
                    <div class="modalContent">
                        <input type="hidden" name="adminID" value="<?php echo $editAdminData['admin_id'] ?? '';?>">

                        <label>Admin Name</label>
                        <input type="text" name="adminFirstName" value="<?php echo $editAdminData['first_name'] ?? '';?>" placeholder="First Name" required>
                        <input type="text" name="adminLastName" value="<?php echo $editAdminData['last_name'] ?? '';?>" placeholder="Last Name" required>
                        
                        <label>Admin Email</label>
                        <input type="email" name="adminEmail" value="<?php echo $editAdminData['email'] ?? '';?>" placeholder="user@email.com" required>
                        <label>New Password (Leave Blank to Keep Current)</label>
                        <input type="password" name="adminPassword" placeholder="Enter New Password">
                    </div>

                    <div class="modalFooter">
                        <button type="submit" name="updateAdmin">Save Changes</button>
                    </div>

                </form>
            </div>
                </dialog>
                    </tbody>

    </table>
    </div>

    <div class="pagination">
        <!--Prev button-->
            <?php if ($page > 1): ?>
                <a href="adminUsers.php?page=<?php echo $page - 1; ?>&searchBar=<?php echo htmlSpecialCharss(urlencode($adminSearch)); ?>"  class= "prevNext">« Previous</a>
                <?php endif; ?>
        <span class="pageNumber">Page <?php echo $page; ?></span>

        <!--Next Button--> 
            <?php if (mysqli_num_rows($result) == $limit) : ?>
                <a href="adminUsers.php?page=<?php echo $page + 1?>&searchBar=<?php echo htmlSpecialCharss(urlencode($adminSearch)); ?>"  class= "prevNext"> Next » </a>
                <?php endif; ?>
            </div>
</main>

    <!--Modal for Adding admins-->
    <dialog id="adminModal" class="adminModal">
        <div class="modalHeader">
            <h2>Add New Admin</h2>
                <a href="adminUsers.php" class="closeButton">&times;</a>

        </div>
        
        <form action="../phpActionScripts/addAdmin.php" method="post">
            <div class="modalContent">
                <input type="hidden" name="returnPage" value="<?php echo $page; ?>">
                <input type="hidden" name="returnSearch" value="<?php echo htmlSpecialCharss($adminSearch); ?>">
                <label>Admin Name</label>
                <input type="text" name="adminFirstName" placeholder="First Name" required>
                <input type="text" name="adminLastName" placeholder="Last Name" required>
                

                <label>Admin Email</label>
                <input type="text" name="adminEmail" placeholder="user@email.com" required>

                <label>Password</label>
                <input type="password" name="adminPassword" placeholder="Make it Secure" required>
            </div>

            <div class="modalFooter">
                <button type="submit" name="createAdmin"> Create Admin</button>
            </div>
        </form>
    </dialog>

    <script> 
    //edit admin modal
    const adminModal= document.getElementById('adminModal');
    const editModal = document.getElementById('editAdminModal');
                         

        <?php 
            if (isset($editAdminData)) :?>
                if (editModal) {
                    editModal.showModal();
                }
        <?php endif; ?>
    
                </script>
</body>
</html>