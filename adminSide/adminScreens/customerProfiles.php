<?php
    include_once "../phpActionScripts/sessionCheck.php";
    include_once "../../Front-End/connection.php";
    include_once "../phpActionScripts/functions.php";
    include_once "../phpActionScripts/editCustomers.php";
    include_once "../phpActionScripts/fetchEditUsers.php";
    include_once "../phpActionScripts/deleteUser.php";


    $statusView = $_GET['status'] ?? 'activeUsers';
    $isArchived = ($statusView === 'archivedUsers');

    $customerSearch = $_GET["searchBar"] ?? "";
    //$result = customerQuery($con, $adminSearch);
    $limit = 2; //rows to return

    //rows to show next page
    $page = isset($_GET['page']) ? /*(int)*/$_GET['page'] : 1;
    if ($page < 1) $page = 1;

    $offset = ($page - 1) * $limit;

    $result = getUsersWithAddress($con, $limit, $offset, $customerSearch, $isArchived);
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

    <form action="customerProfiles.php" method="get" id="filterForm">
        <!--Search Bar-->
    <label>Search: </label>
    <input type="text" name="searchBar" placeholder="Search Customer" value="<?php echo htmlSpecialCharss($customerSearch); ?>">
    <button type="submit" style="margin-bottom: 10px; padding: 8px 15px; background: #dac50b; color: white; border: none; border-radius: 4px; cursor: pointer;">Search</button>

    <!--Active Inactive Filter-->
        <label>View Users</label>
        <select name="status" onchange="this.form.submit()">
            <option value="activeUsers" <?php echo (!isset($_GET['status']) || $_GET['status'] == 'activeUsers') ? 'selected' : ''; ?>>Active Users</option>
            <option value="archivedUsers" <?php echo (!isset($_GET['status']) || $_GET['status'] == 'archivedUsers') ? 'selected' : ''; ?>>Inactive Users</option>
        
        </select>

        </form>

    <div class="inventoryContainer">
    <!--Table Dashboard -->
    <table class="inventoryTable">
        <thead>
            <tr>
                 <th>User Id</th>
                 <th>User Name</th>
                 <th>User Email</th>
                 <th>Address</th>
                 <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php 
                if(mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) { 
                        $addressArray = !empty($row['all_addresses']) ? explode('|', $row['all_addresses']) : [];
                        $fullName = $row['first_name']. " ". $row['last_name'];

                        ?>
                    <tr>
                        <td> <?php echo htmlSpecialCharss($row['user_id']); ?> </td>
                        <td> <?php echo htmlSpecialCharss($fullName) ?> </td>
                        <td> <?php echo htmlSpecialCharss($row['email']); ?> </td>
                        <td> <?php if(count($addressArray) > 1): ?>
                                <?php echo htmlSpecialCharss($addressArray[0]); ?>
                                <!--Addresses-->
                            <button type="button" onclick="openAddressModal('<?php echo htmlSpecialCharss(addslashes($fullName)); ?>', '<?php echo htmlSpecialCharss(addslashes($row['all_addresses'])); ?>')"> (+View more) </button>
                                <?php else: ?>
                                    <?php echo htmlSpecialCharss($addressArray[0] ?? 'noAddress'); ?>
                                <?php endif; ?>
                    
                    
                    
                    
                    </td>
                    <td>
                        <div class="actionContainer">
                            <!--Edit USers--> 
                            <?php if(!$isArchived): ?>
                            <a href="customerProfiles.php?editUserID=<?php echo htmlSpecialCharss($row['user_id']); ?>&page=<?php echo $page; ?>&searchBar=<?php echo urlencode($customerSearch); ?>&status=<?php echo $statusView;?>" style="background-color: #28a745; color: white; border: none; padding: 5px 15px; cursor: pointer; border-radius: 4px; display: block; margin: 0 auto 5px auto; width: 80px; text-align: center; text-decoration: none;"> Edit </a>

                            <!--Delete-->
                            <form action = "../phpActionScripts/deleteUser.php" method="post" onsubmit="return confirm('Delete This User?');">
                                <input type="hidden" name="userID" value="<?php echo htmlSpecialCharss($row['user_id']); ?>">
                                <input type="hidden" name="returnPage" value="<?php echo $page; ?>">
                                <input type="hidden" name="returnSearch" value="<?php echo htmlSpecialCharss($customerSearch); ?>">
                                <input type="hidden" name="returnStatus" value="<?php echo $statusView; ?>">
                                <button type="submit" name="deleteUser" style="background-color: #dc3534; color: white; border: none; padding: 5px 15px; cursor: pointer; border-radius: 4px; display: block; margin: 0 auto; width: 110px;"> Delete</button>
                            </form>

                            <!--For Archive Users--> 
                            <?php else: ?>
                                <form action="../phpActionScripts/restoreUser.php" method="post" onsubmit="return confirm('Restore This User?');">
                                    <input type="hidden" name="userID" value="<?php echo htmlSpecialCharss($row['user_id']); ?>">
                                    <input type="hidden" name="returnPage" value="<?php echo $page; ?>">
                                    <input type="hidden" name="returnSearch" value="<?php echo htmlSpecialCharss($customerSearch); ?>">
                                    <input type="hidden" name="returnStatus" value="<?php echo $statusView; ?>">
                                    <button type="submit" name="restoreUser" style="background-color: #a7c525; color: white; border: none; padding: 5px 15px; cursor: pointer; border-radius: 4px; display: block; margin: 0 auto 5px auto; width: 80px; text-align: center; text-decoration: none;"> Restore </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php 
                    } // end of while
                } else {
                    echo "<tr> 
                            <td colspan='5' style='text-align: center;'> No Users Matching Your Request. </td>
                            </tr>";

                } ?>


         <!--Edit Customer Data-->       
<dialog id="editUserModal" class="adminModal" style="padding: 0; border: none; border-radius: 15px; width: 450px; max-width: 90vw; margin: auto; overflow: hidden;">
            <div class="modalWrapper" style="padding: 0; min-width: 100%;">
                <div class="modalHeader">
                    <h2>Edit User</h2>
                    <a href="customerProfiles.php?page=<?php echo $page; ?>&searchBar=<?php echo urlencode($customerSearch);?>&status=<?php echo $statusView; ?>" class="closeButton">&times;</a>
                </div>

                <form action="../phpActionScripts/editCustomers.php" method="post" onsubmit="return confirm('Save Changes to this customer?')">
                    <div class="modalContent">
                        <input type="hidden" name="userID" value="<?php echo htmlSpecialCharss($editUserData['user_id']) ?? '';?>">
                        <input type="hidden" name="returnPage" value="<?php echo $page; ?>">
                        <input type="hidden" name="returnSearch" value="<?php echo htmlSpecialCharss($customerSearch); ?>">
                        <input type="hidden" name="returnStatus" value="<?php echo $statusView; ?>">
                        <label>Customer Name</label>
                        <input type="text" name="userFirstName" value="<?php echo htmlSpecialCharss($editUserData['first_name']) ?? '';?>">
                        <input type="text" name="userLastName" value="<?php echo htmlSpecialCharss($editUserData['last_name']) ?? '';?>">

                    </div>

                    <div class="modalFooter">
                        <button type="submit" name="updateCustomer">Save Changes</button>
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
                <a href="customerProfiles.php?page=<?php echo $page - 1; ?>&searchBar=<?php echo htmlSpecialCharss(urlencode($customerSearch)); ?> &status=<?php echo $statusView;?>"  class= "prevNext">« Previous</a>
                <?php endif; ?>
        <span class="pageNumber">Page <?php echo $page; ?></span>

        <!--Next Button--> 
            <?php if (mysqli_num_rows($result) == $limit) : ?>
                <a href="customerProfiles.php?page=<?php echo $page + 1?>&searchBar=<?php echo htmlSpecialCharss(urlencode($customerSearch)); ?> &status=<?php echo $statusView;?>"  class= "prevNext"> Next » </a>
                <?php endif; ?>
            </div>

</main>

            <!--Address Modal--> 
        <dialog id="addressModal" class="addressModal">
            <div class="modalWrapper">
                <div class = "modalHeader">
                    <h2 id="modalUserName">Order Details</h2>
                    <button type="button" onclick="document.getElementById('addressModal').close()" class="closeButton">&times;</button>
                </div>
                <div class="modalContent">
                    <ul id="addressUl" style="list-style: none; padding: 0;">
                    
                </ul>
                </div>
                </div>
                </dialog>


    <script>

    //For the active inactive filter
    document.getElementById('filterForm'). addEventListener('submit', function() {
        console.log("Filtering Results...");
    })

    //For the edit users modal
    const addressModal = document.getElementById('addressModal');
    const editUserModal = document.getElementById('editUserModal');

    <?php 
            if (isset($editUserData)) :?>
                if (editUserModal) {
                    editUserModal.showModal();
                }
        <?php endif; ?>
    
    //For the opening of addresses
    function openAddressModal(userName, pipeSeparatedAddresses) {
        const modal = document.getElementById('addressModal');
        const ul = document.getElementById('addressUl');
        document.getElementById('modalUserName').innerText= "Addresses for " + userName;

        ul.innerHTML = '';

        const list = pipeSeparatedAddresses.split('|');
        list.forEach(addr => {
            const li = document.createElement('li');
            li.style.padding="8px";
            li.style.borderBottom="1px solid #ddd";
            li.textContent=addr;
            ul.appendChild(li);
        });
        modal.showModal();
    }
    
                </script>

</body>
</html>