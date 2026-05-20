<?php
    include_once "../phpActionScripts/sessionCheck.php";
    include_once "../../Front-End/connection.php";
    include_once "../phpActionScripts/functions.php";
    include_once "../phpActionScripts/editProduct.php";
    include_once "../phpActionScripts/archiveProduct.php";
    include_once "../phpActionScripts/restoreProducts.php";
    include_once "../phpActionScripts/addProductQuery.php";

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
                                        <input type="hidden" name="returnPage" value="<?php echo $page; ?>">
                                        <input type="hidden" name="returnSearch" value="<?php echo htmlspecialchars($search); ?>">
                                        <input type="hidden" name="returnStatus" value="<?php echo $statusView; ?>">
    
                                        <input type="number" name="newStock" value="<?php echo $row['stock'];?>" min="0" style="width: 60px;">
                                        <button type="submit" name="updateStock" style="background:#28a745; color:white; border:none; padding: 5px 10px; border-radius: 4px; cursor:pointer;">SAVE</button>
                                    </form>
                                <?php else: echo $row['stock']; endif; ?>
                            </td>

                            <td>
                                <?php if(!$isArchived): ?>
                                    <!--<form action="../phpActionScripts/editProduct.php" method="post" style="display: flex; gap: 5px;">
                                        <input type="hidden" name="variantID" value="<?php echo $row['variantId']; ?>">
                                        <input type="number" name="newPrice" value="<?php echo $row['price'];?>" min="0" style="width: 60px;">
                                        <button type="submit" name="updatePrice" style="background:#28a745; color:white; border:none; cursor:pointer;">SAVE</button>
                                    </form>--> 
                                    <form action="../phpActionScripts/editProduct.php" method="post" style="display: flex; gap: 5px;">
                                        <input type="hidden" name="variantID" value="<?php echo $row['variantId']; ?>">
                                        <input type="hidden" name="returnPage" value="<?php echo $page; ?>">
                                        <input type="hidden" name="returnSearch" value="<?php echo htmlspecialchars($search); ?>">
                                        <input type="hidden" name="returnStatus" value="<?php echo $statusView; ?>">
    
                                        <input type="number" name="newPrice" value="<?php echo $row['price'];?>" min="0" style="width: 60px;">
                                        <button type="submit" name="updatePrice" style="background:#28a745; color:white; border:none; padding: 5px 10px; border-radius: 4px; cursor:pointer;">SAVE</button>
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
    <dialog id="addProductModal" class="adminModal" style="width: 450px; border-radius: 8px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
    <div class="modalHeader" style="background: #000; color: #fff; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center;">
        <h2 style="margin: 0; font-size: 1.2rem;">Add Product Variant</h2>
        <button type="button" onclick="document.getElementById('addProductModal').close()" class="closeButton" style="background: none; border: none; color: #fff; font-size: 1.5rem; cursor: pointer;">&times;</button>
    </div>
    
    <form action="../phpActionScripts/addProduct.php" method="post">
        <div class="modalContent" style="padding: 20px; display: flex; flex-direction: column; gap: 12px; box-sizing: border-box;">
            <input type="hidden" name="returnPage" value="<?php echo $page; ?>">
            <input type="hidden" name="returnSearch" value="<?php echo htmlSpecialCharss($search); ?>">
            <input type="hidden" name="returnStatus" value="<?php echo $statusView; ?>">

            <label style="font-weight: bold; font-size: 14px;">Entry Type</label>
            <select id="entryType" name="entryType" onchange="toggleProductMode()" style="padding: 10px; width: 100%; border-radius: 4px; border: 1px solid #ccc;">
                <option value="existing">Add Variant to Existing Catalog Product</option>
                <option value="new">Create a Brand New Base Product</option>
            </select>

            <div id="existingProductGroup">
                <label style="font-weight: bold; font-size: 14px;">Select Product</label>
                <select name="productID" style="padding: 10px; width: 100%; border-radius: 4px; border: 1px solid #ccc;">
                    <?php while($pRow = mysqli_fetch_assoc($allProducts)): ?>
                        <option value="<?php echo $pRow['product_id']; ?>"><?php echo htmlspecialchars($pRow['product_name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div id="newProductGroup" style="display: none; flex-direction: column; gap: 12px;">
    <div>
        <label style="font-weight: bold; font-size: 14px;">New Product Name</label>
        <input type="text" name="newProductName" placeholder="e.g. Acid Green Tee" style="padding: 10px; width: 100%; box-sizing: border-box; border-radius: 4px; border: 1px solid #ccc;">
    </div>
    
    <div>
        <label style="font-weight: bold; font-size: 14px;">Product Description</label>
        <textarea name="newProductDescription" placeholder="e.g. Heavyweight cotton, vintage fit, screen-printed graphic." rows="3" style="padding: 10px; width: 100%; box-sizing: border-box; border-radius: 4px; border: 1px solid #ccc; font-family: inherit; resize: vertical;"></textarea>
    </div>

    <div>
        <label style="font-weight: bold; font-size: 14px;">Category</label>
        <select name="categoryID" style="padding: 10px; width: 100%; border-radius: 4px; border: 1px solid #ccc;">
            <?php mysqli_data_seek($allCategories, 0); while($catRow = mysqli_fetch_assoc($allCategories)): ?>
                <option value="<?php echo $catRow['category_id']; ?>"><?php echo htmlspecialchars($catRow['category_name']); ?></option>
            <?php endwhile; ?>
        </select>
    </div>
</div>

            <div style="display: flex; gap: 10px;">
                <div style="flex: 1;">
                    <label style="font-weight: bold; font-size: 14px;">Size</label>
                    <select name="sizeID" required style="padding: 10px; width: 100%; border-radius: 4px; border: 1px solid #ccc;">
                        <?php while($sRow = mysqli_fetch_assoc($allSizes)): ?>
                            <option value="<?php echo $sRow['size_id']; ?>"><?php echo htmlspecialchars($sRow['size_name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div style="flex: 1;">
                    <label style="font-weight: bold; font-size: 14px;">Color</label>
                    <select name="colorID" required style="padding: 10px; width: 100%; border-radius: 4px; border: 1px solid #ccc;">
                        <?php while($cRow = mysqli_fetch_assoc($allColors)): ?>
                            <option value="<?php echo $cRow['color_id']; ?>"><?php echo htmlspecialchars($cRow['color_name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div style="display: flex; gap: 10px;">
                <div style="flex: 1;">
                    <label style="font-weight: bold; font-size: 14px;">Initial Stock</label>
                    <input type="number" name="stock" value="0" min="0" required style="padding: 10px; width: 100%; box-sizing: border-box; border-radius: 4px; border: 1px solid #ccc;">
                </div>
                <div style="flex: 1;">
                    <label style="font-weight: bold; font-size: 14px;">Price (₱)</label>
                    <input type="number" name="price" step="0.01" value="0.00" min="0" required style="padding: 10px; width: 100%; box-sizing: border-box; border-radius: 4px; border: 1px solid #ccc;">
                </div>
            </div>
        </div>

        <div class="modalFooter" style="padding: 15px 20px;">
            <button type="submit" name="addProduct" style="background: #28a745; color: white; padding: 12px; border: none; width: 100%; cursor: pointer; border-radius: 5px; font-weight: bold; font-size: 15px;">
                Confirm Add Product
            </button>
        </div>
    </form>
</dialog>

<script>
function toggleProductMode() {
    const type = document.getElementById('entryType').value;
    const existingGroup = document.getElementById('existingProductGroup');
    const newGroup = document.getElementById('newProductGroup');
    const descriptionField = document.getElementsByName('newProductDescription')[0];

    if (type === 'existing') {
        existingGroup.style.display = 'block';
        newGroup.style.display = 'none';
        document.getElementsByName('newProductName')[0].required = false;
        
        // Clear value when hidden to prevent cross-contamination
        descriptionField.value = ''; 
    } else {
        existingGroup.style.display = 'none';
        newGroup.style.display = 'flex';
        document.getElementsByName('newProductName')[0].required = true;
    }
}
</script>
</body>
</html>