<?php
    include_once "../phpActionScripts/sessionCheck.php";
    include_once "../../Front-End/connection.php";
    include_once "../phpActionScripts/functions.php";
    include_once "../phpActionScripts/editProduct.php";
    include_once "../phpActionScripts/archiveProduct.php";
    include_once "../phpActionScripts/restoreProducts.php";

    $statusView = $_GET['status'] ?? 'activeProducts';
    $isArchived = ($statusView === 'archivedProducts');

    $search = $_GET["searchBar"] ?? "";

    $limit = 5; //rows to return

    //rows to show next page
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    if ($page < 1) $page = 1;

    $offset = ($page - 1) * $limit;
    $result = searchInventory($con, $limit, $offset, $search, $isArchived);
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
    <div class="header">
        <img src="../../Resources/Images/Logo.png" class="logo-black" alt="Black Logo">
    </div>

    <div class="navHeader">
        <nav>
            <a href="adminDashboard.php">Dashboard</a>
            <a class="active" href="adminInventory.php">Inventory</a>
            <a href="customerProfiles.php">User Menu</a>
            <a href="adminUsers.php">Admin Menu</a>
            <a href="adminOrders.php">Orders</a>
            <a href="adminAccount.php">Admin Account</a>
        </nav>
    </div>

    <h3 class="welcomeAdmin">Welcome Admin, <?php echo htmlspecialchars($_SESSION['adminName']); ?></h3>

    <main class="dashboardContent">
        <form action="adminInventory.php" method="get">
            <label>Search: </label>
            <input type="text" name="searchBar" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search Item">
                    <button type="submit" style="margin-bottom: 10px; padding: 8px 15px; background: #dac50b; color: white; border: none; border-radius: 4px; cursor: pointer;">Search</button>
            <label style="margin-left: 20px;">View Products: </label>
            <select name="status" onchange="this.form.submit()">
                <option value="activeProducts" <?php echo ($statusView == 'activeProducts') ? 'selected' : ''; ?>>Active Products</option>
                <option value="archivedProducts" <?php echo ($statusView == 'archivedProducts') ? 'selected' : ''; ?>>Inactive Products</option>
            </select>
            <button type="button" onclick="document.getElementById('addProductModal').showModal()" style="margin-bottom: 10px; padding: 8px 15px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">+ Add New Product</button>

        </form>

        <div class="inventoryContainer">
            <table class="inventoryTable">
                <thead>
                    <tr>
                         <th>Product ID</th>
                         <th>Product</th>
                         <th>Category</th>
                         <th>Variant</th>
                         <th>Stock</th>
                         <th>Price</th>
                         <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><strong><?php echo htmlSpecialCharss($row['productId']); ?></strong></td>
                            <td><strong><?php echo htmlSpecialCharss($row['productName']); ?></strong></td>
                            <td><?php echo htmlSpecialCharss($row['categoryName']); ?></td>
                            <td>
                                <?php 
                                    $size = $row['sizeName'] ?? 'N/A';
                                    $color = $row['colorName'] ?? 'N/A';
                                    echo htmlSpecialCharss("$size / $color"); 
                                ?>
                            </td>

                            <td>
                                <?php if(!$isArchived): ?>
                                    <form action="../phpActionScripts/editProduct.php" method="post" style="display: flex; gap: 5px;">
                                        <input type="hidden" name="variantID" value="<?php echo $row['variantId']; ?>">
                                        <input type="number" name="newStock" value="<?php echo $row['stock'];?>" min="0" style="width: 60px;">
                                        <button type="submit" name="updateStock" style="background:#28a745; color:white; border:none; cursor:pointer;">SAVE</button>
                                    </form>
                                <?php else: echo $row['stock']; endif; ?>
                            </td>

                            <td>
                                <?php if(!$isArchived): ?>
                                    <form action="../phpActionScripts/editProduct.php" method="post" style="display: flex; gap: 5px;">
                                        <input type="hidden" name="variantID" value="<?php echo $row['variantId']; ?>">
                                        <input type="number" name="newPrice" value="<?php echo $row['price'];?>" min="0" style="width: 60px;">
                                        <button type="submit" name="updatePrice" style="background:#28a745; color:white; border:none; cursor:pointer;">SAVE</button>
                                    </form>
                                <?php else: echo $row['price']; endif; ?>
                            </td>

                            <td> <!--fix later-->
                                <div class="actionContainer">
                                    <?php if(!$isArchived): ?>
                                        <form action="../phpActionScripts/archiveProduct.php" method="post" onsubmit="return confirm('Archive product?');">
                                            <input type="hidden" name="variantID" value="<?php echo $row['variantId']; ?>">
                                            <button type="submit" name="deleteProduct" style="background:#dc3534; color:white; border:none; padding: 5px 10px; cursor:pointer;">DELETE</button>
                                        </form>
                                    <?php else: ?>
                                        <form action="../phpActionScripts/restoreProducts.php" method="post" onsubmit="return confirm('Revive product?');">
                                            
                                            <input type="hidden" name="variantID" value="<?php echo $row['variantId']; ?>">
                                            <input type="hidden" name="returnPage" value="<?php echo $page; ?>">
                                            <input type="hidden" name="returnSearch" value="<?php echo htmlSpecialCharss($search); ?>">
                                            <input type="hidden" name="returnStatus" value="<?php echo $statusView; ?>">
                                            <button type="submit" name="restoreProduct" style="background:#a7c525; color:white; border:none; padding: 5px 10px; cursor:pointer;">RESTORE</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="7" style="text-align:center;">No products found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="adminInventory.php?page=<?php echo $page - 1; ?>&searchBar=<?php echo urlencode($search); ?>&status=<?php echo $statusView; ?>" class="prevNext">« Previous</a>
            <?php endif; ?>
            <span class="pageNumber">Page <?php echo $page; ?></span>
            <?php if (mysqli_num_rows($result) == $limit): ?>
                <a href="adminInventory.php?page=<?php echo $page + 1; ?>&searchBar=<?php echo urlencode($search); ?>&status=<?php echo $statusView; ?>" class="prevNext">Next »</a>
            <?php endif; ?>
        </div>
    </main>

    <!--Add Product Modal-->
    <dialog id="addProductModal" class="adminModal">
    <div class="modalHeader">
        <h2>Add New Product Variant</h2>
        <button type="button" onclick="document.getElementById('addProductModal').close()" class="closeButton">&times;</button>
    </div>
    
    <form action="../phpActionScripts/addProduct.php" method="post">
        <div class="modalContent" style="padding: 20px; display: flex; flex-direction: column; gap: 5px;">
            <input type="hidden" name="returnPage" value="<?php echo $page; ?>">
            <input type="hidden" name="returnSearch" value="<?php echo htmlspecialchars($search); ?>">
            <input type="hidden" name="returnStatus" value="<?php echo $statusView; ?>">

            <label style="font-weight: bold; font-size: 14px;">Product Name</label>
            <input type="text" name="productName" required placeholder="e.g. Graphic Tee" style="padding: 10px; width: 100%; box-sizing: border-box;">

            <label style="font-weight: bold; font-size: 14px; margin-top: 10px;">Category</label>
            <select name="categoryID" required style="padding: 10px; width: 100%; box-sizing: border-box;">
                <option value="1">Apparel</option>
                <option value="2">Accessories</option>
            </select>

            <div style="display: flex; gap: 10px; margin-top: 10px;">
                <div style="flex: 1;">
                    <label style="font-weight: bold; font-size: 14px;">Size</label>
                    <select name="sizeID" required style="padding: 10px; width: 100%; box-sizing: border-box;">
                        <option value="1">Small</option>
                        <option value="2">Medium</option>
                        <option value="3">Large</option>
                    </select>
                </div>
                <div style="flex: 1;">
                    <label style="font-weight: bold; font-size: 14px;">Color</label>
                    <select name="colorID" required style="padding: 10px; width: 100%; box-sizing: border-box;">
                        <option value="1">Black</option>
                        <option value="2">White</option>
                    </select>
                </div>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 10px;">
                <div style="flex: 1;">
                    <label style="font-weight: bold; font-size: 14px;">Initial Stock</label>
                    <input type="number" name="stock" value="0" min="0" required style="padding: 10px; width: 100%; box-sizing: border-box;">
                </div>
                <div style="flex: 1;">
                    <label style="font-weight: bold; font-size: 14px;">Price (₱)</label>
                    <input type="number" name="price" step="0.01" value="0.00" min="0" required style="padding: 10px; width: 100%; box-sizing: border-box;">
                </div>
            </div>
        </div>

        <div class="modalFooter" style="padding: 20px;">
            <button type="submit" name="addProduct" style="background: #28a745; color: white; padding: 10px; border: none; width: 100%; cursor: pointer; border-radius: 5px; font-weight: bold;">
                Confirm Add Product
            </button>
        </div>
    </form>
</dialog>
</body>
</html>