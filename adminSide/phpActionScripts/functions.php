<?php

        /*data cleaning for search inputs*/
        function cleanSearch($searchData) {
            $searchData = trim($searchData);
            $searchData = str_replace(['-', ' '], '', $searchData);
            $searchData = strtolower($searchData);
            return $searchData;
        }

        function searchInventory($con, $limit, $offset, $search ="", $isArchived = false) {
            $deleteStatus = $isArchived ? 0 : 1;
            $inventoryQuery = "SELECT 
                                pv.variant_id AS variantId,
                                p.product_id AS productId,
                                p.product_name AS productName,
                                c.category_name AS categoryName,
                                pv.stock_quantity AS stock,
                                COALESCE(pv.price_override, p.base_price) AS price,
                                cl.color_name AS colorName,
                                s.size_name AS sizeName
                                FROM product_variants pv
                                JOIN products p ON pv.product_id = p.product_id
                                JOIN categories c ON p.category_id = c.category_id
                                LEFT JOIN colors cl ON pv.color_id = cl.color_id
                                LEFT JOIN sizes s ON pv.size_id = s.size_id
                                WHERE pv.is_active = $deleteStatus
                                AND (LOWER(REPLACE(REPLACE(p.product_name, ' ', ''), '-', '')) LIKE ?
                                OR p.product_id LIKE ?)
                                LIMIT ? OFFSET ?";

            $stmt = mysqli_prepare($con, $inventoryQuery);
            $cleanSearchData = cleanSearch($search);
            $searchTerm = "%". $cleanSearchData ."%";
            mysqli_stmt_bind_param($stmt, "ssii", $searchTerm, $searchTerm, $limit, $offset);
            mysqli_stmt_execute($stmt);
            return mysqli_stmt_get_result($stmt);
        }

        function getOrders($con, $limit, $offset, $orderSearch = "", $statusFilter="All") {
            $matchStatus = ($statusFilter === "All") ? "%" : $statusFilter;
            $ordersQuery = "SELECT
                            o.order_id, o.created_at, o.order_status_id ,u.first_name, u.last_name, os.order_status_name,
                            CONCAT(c.first_name, ' ' , c.last_name) AS courier_name, ds.delivery_status_name, 
                            loc.user_loc AS shipping_address,
                            (SELECT SUM(oi.quantity * oi.price_at_purchase)
                                FROM order_items oi WHERE oi.order_id = o.order_id) AS total_amount
                                FROM orders o
                            JOIN users u ON o.user_id = u.user_id
                            JOIN order_statuses os ON o.order_status_id = os.order_status_id
                            LEFT JOIN deliveries d ON o.order_id = d.order_id
                            LEFT JOIN couriers c ON d.courier_id = c.courier_id
                            LEFT JOIN delivery_statuses ds ON d.delivery_status_id = ds.delivery_status_id
                            LEFT JOIN user_location loc ON d.location_id = loc.location_id
                            WHERE (u.first_name LIKE ? OR u.last_name LIKE ? OR o.order_id LIKE?)
                            AND o.order_status_id LIKE ?
                            ORDER BY o.created_at DESC, o.order_id DESC
                            LIMIT ? OFFSET ?";
            $stmt = mysqli_prepare($con, $ordersQuery);
            $cleanSearchData = cleanSearch($orderSearch);
            $searchTerm = "%". $cleanSearchData . "%";
            mysqli_stmt_bind_param($stmt, 'ssssii', $searchTerm, $searchTerm, $searchTerm, $matchStatus,
                                  $limit, $offset);
            mysqli_stmt_execute($stmt);
            return mysqli_stmt_get_result($stmt);
        }

        function getOrderItemsList($con, $orderID) {
            $orderIdQuery = "SELECT oi.quantity,
                             oi.price_at_purchase,
                             p.product_name,
                             cl.color_name,
                             s.size_name,
                             cat.category_name, pt.tier_name
                                FROM order_items oi
                                JOIN product_variants pv ON oi.variant_id = pv.variant_id
                                JOIN products p ON pv.product_id = p.product_id
                                JOIN categories cat ON p.category_id = cat.category_id
                                JOIN product_tiers pt ON p.tier_id = pt.tier_id
                                LEFT JOIN colors cl ON pv.color_id = cl.color_id
                                LEFT JOIN sizes s ON pv.size_id = s.size_id
                                WHERE oi.order_id = ?
                                ";

            $stmt = mysqli_prepare($con, $orderIdQuery);
            mysqli_stmt_bind_param($stmt, "i", $orderID);
            mysqli_stmt_execute($stmt);
            return mysqli_stmt_get_result($stmt);
        }

        function adminQuery($con, $adminSearch="", $limit, $offset) {
            $adminQuery = "SELECT admin_id, first_name, last_name, email FROM krnk_admin
                           WHERE admin_id LIKE ? OR first_name LIKE ? OR first_name LIKE ?
                           LIMIT ? OFFSET ?";
            $stmt = mysqli_prepare($con, $adminQuery);
            $cleanAdminSearch = cleanSearch($adminSearch);
            $searchAdminName = "%". $cleanAdminSearch ."%";
            mysqli_stmt_bind_param($stmt, "sssii", $searchAdminName, $searchAdminName, $searchAdminName, $limit, $offset);
            mysqli_stmt_execute($stmt);
            return mysqli_stmt_get_result($stmt);
        }


        /*addAdmin function to be called in addAmin.php*/
        function addAdmin($con, $first_name, $last_name, $email, $password){
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    return false;
                }

            $adminInsert = "INSERT INTO krnk_admin (first_name, last_name, email, admin_password) VALUES (?, ?, ?, ?)";
            $hashAdminPassword = passwordHashing($password);
            $stmt = mysqli_prepare($con, $adminInsert);
            mysqli_stmt_bind_param($stmt, "ssss", $first_name, $last_name, $email, $hashAdminPassword);
            return mysqli_stmt_execute($stmt);

        }

        function deleteAdmin($con, $adminID){
            $deleteQuery = "DELETE FROM krnk_admin WHERE admin_id = ?";
            $stmt = mysqli_prepare($con, $deleteQuery);
            mysqli_stmt_bind_param($stmt, "i", $adminID);
            return mysqli_stmt_execute($stmt);
        }

        function deleteUser($con, $userID){
            $softDeleteQuery = "UPDATE USERS 
                                SET is_deleted = 1
                                WHERE user_id = ?";
            $stmt = mysqli_prepare($con, $softDeleteQuery);
            mysqli_stmt_bind_param($stmt, "i", $userID);
            return mysqli_stmt_execute($stmt);
        }

        function updateAdmin($con, $adminID, $newAdminFirstName, $newAdminLastName, $email, $newAdminPassword= null){
            if(!empty($newAdminPassword)) { //update both pass name, and email
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    return false;
                }

                $hashPassword = passwordHashing($newAdminPassword);
                $adminEditQuery = "UPDATE krnk_admin
                                       SET first_name = ?, last_name = ?, email = ?, admin_password = ?
                                       WHERE admin_id =?";
                $stmt = mysqli_prepare($con, $adminEditQuery); 
                mysqli_stmt_bind_param($stmt, "ssssi", $newAdminFirstName, $newAdminLastName, $email, $hashPassword, $adminID);
            } else { //name and email
                $adminEditQuery = "UPDATE krnk_admin SET first_name = ?, last_name =?, email = ? WHERE admin_id = ?";
                $stmt = mysqli_prepare($con, $adminEditQuery);
                mysqli_stmt_bind_param($stmt, "sssi", $newAdminFirstName, $newAdminLastName, $email ,$adminID);

            
            } 
            return mysqli_stmt_execute($stmt);
         } 
        

        function updateCustomer($con, $userID, $newCustomerFirstName, $newCustomerLastName) {
            $updateCustomerName = "UPDATE users 
                                   SET first_name = ?, last_name = ?
                                   WHERE user_id = ?";
            $stmt = mysqli_prepare($con, $updateCustomerName);
            mysqli_stmt_bind_param($stmt, "ssi", $newCustomerFirstName, $newCustomerLastName, $userID);
            return mysqli_stmt_execute($stmt);
        }

        function getAdminByID($con, $adminID){
            $getEditAdminQuery = "SELECT * FROM krnk_admin WHERE admin_id=?";
            $stmt = mysqli_prepare($con, $getEditAdminQuery);
            mysqli_stmt_bind_param($stmt, "i", $adminID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            return mysqli_fetch_assoc($result);
        }

        function getUserByID($con, $userID) {
            $getEditUserQuery = "SELECT * FROM users WHERE is_deleted = 0 AND user_id = ?";
            $stmt = mysqli_prepare($con, $getEditUserQuery);
            mysqli_stmt_bind_param($stmt, "i", $userID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            return mysqli_fetch_assoc($result);
        }

        function passwordHashing($data) {
            $data = password_hash($data, PASSWORD_DEFAULT);
            return $data;
        }

        function dataCleaning($data){ //Keep for verifying later
           // $data = password_verify($data); Keep this.
           $data = trim($data); //can delete later
            return $data;
            }

        function htmlSpecialCharss($data) {
            return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        }

        function authenticateAdmin($con, $adminID, $adminPassword) {
            $adminQuery = "SELECT * FROM krnk_admin WHERE admin_id=?";
            $stmt = mysqli_prepare($con, $adminQuery);
            mysqli_stmt_bind_param($stmt, "i", $adminID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if($row = mysqli_fetch_assoc($result)) {
                if(password_verify($adminPassword, $row['admin_password'])) {
                    return $row;
                } 

            }
                return false;
            }

            function getUsersWithAddress($con, $limit, $offset, $customerSearch="", $isArchived = false) {

                $deleteStatus = $isArchived ? 1 : 0;
                $userWithAddressQuery = " SELECT u.user_id, u.first_name, u.last_name, u.email, 
                                         GROUP_CONCAT(ul.user_loc SEPARATOR '|') AS all_addresses
                                          FROM users u
                                          LEFT JOIN user_location ul ON u.user_id = ul.user_id
                                          WHERE is_deleted = $deleteStatus
                                          AND (u.user_id LIKE ? OR
                                          u.first_name LIKE ? OR 
                                          u.last_name LIKE ? OR 
                                          u.email LIKE ?)
                                          GROUP BY u.user_id
                                          LIMIT ? OFFSET ?";
                
                $stmt = mysqli_prepare($con, $userWithAddressQuery);
                $cleanCustomerSearch = cleanSearch($customerSearch);
                $searchCustomerName = "%". $cleanCustomerSearch. "%";
                mysqli_stmt_bind_param($stmt, "ssssii",
                                                         $searchCustomerName, 
                                                         $searchCustomerName, 
                                                         $searchCustomerName, 
                                                         $searchCustomerName, 
                                                         $limit, $offset);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                return $result;
            }

            function restoreUser($con, $userID) {
                $restoreUserQuery = "UPDATE users
                                     SET is_deleted = 0
                                     WHERE user_id = ?";
                
                $stmt = mysqli_prepare($con, $restoreUserQuery);
                mysqli_stmt_bind_param($stmt, 'i', $userID);
                return mysqli_stmt_execute($stmt);
            }

            function updateProductStock($con, $variantID, $newStock) {
                $newStockQuery = "UPDATE PRODUCT_VARIANTS 
                                  SET stock_quantity = ?
                                  WHERE variant_id = ?
                                  ";
                $stmt = mysqli_prepare($con, $newStockQuery);
                mysqli_stmt_bind_param($stmt, "ii", $newStock, $variantID);
                return mysqli_stmt_execute($stmt);
            }

            function updateProductPrice($con, $variantID, $newPrice) {
                $newPriceQuery = "UPDATE PRODUCT_VARIANTS 
                                  SET price_override = ?
                                  WHERE variant_id = ?
                                  ";
                $stmt = mysqli_prepare($con, $newPriceQuery);
                mysqli_stmt_bind_param($stmt, "ii", $newPrice, $variantID);
                return mysqli_stmt_execute($stmt);
            }

            function archiveProduct($con, $variantID) {
                $archiveProductQuery = "UPDATE PRODUCT_VARIANTS 
                                        SET is_active = 0
                                        WHERE variant_id = ?";
                $stmt = mysqli_prepare($con, $archiveProductQuery);
                mysqli_stmt_bind_param($stmt, "i", $variantID);
                return mysqli_stmt_execute($stmt);
            }
        
            function restoreProduct($con, $variantID) {
                $archiveProductQuery = "UPDATE PRODUCT_VARIANTS 
                                        SET is_active = 1
                                        WHERE variant_id = ?";
                $stmt = mysqli_prepare($con, $archiveProductQuery);
                mysqli_stmt_bind_param($stmt, "i", $variantID);
                return mysqli_stmt_execute($stmt);
            }

            
            function getTableCount($con, $table, $condition = "") {
                // Note: Table names can't be bound in prepared statements, 
                // but we use the flow for consistency.
                    $sql = "SELECT COUNT(*) AS total FROM $table $condition";
                    $stmt = mysqli_prepare($con, $sql);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $data = mysqli_fetch_assoc($result);
                     return $data['total'] ?? 0;
            }


                function getMostPopularCourier($con) {
                    $sql = "SELECT CONCAT(c.first_name, ' ', c.last_name) AS full_name, COUNT(d.order_id) AS total_deliveries 
                    FROM couriers c 
                    LEFT JOIN deliveries d ON c.courier_id = d.courier_id 
                    GROUP BY c.courier_id 
                    ORDER BY total_deliveries DESC 
                    LIMIT 1";

                    $stmt = mysqli_prepare($con, $sql);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if ($row = mysqli_fetch_assoc($result)) {
                     return $row;
                    }

                    return false;
                }

            function updateOrderStatus($con, $orderID, $statusID) {
                $updateStatusQuery = "UPDATE orders SET order_status_id = ? WHERE order_id = ?";
                $stmt = mysqli_prepare($con, $updateStatusQuery);
                mysqli_stmt_bind_param($stmt, "ii", $statusID, $orderID);
    
                return mysqli_stmt_execute($stmt);
            }

           function addProductVariant($con, $name, $description, $basePrice, $catID, $tierID, $sizeID, $colorID, $sku, $stock, $priceOverride) {
    // 1. Check if the product already exists
                $checkQuery = "SELECT product_id FROM products WHERE product_name = ?";
                $stmtCheck = mysqli_prepare($con, $checkQuery);
                mysqli_stmt_bind_param($stmtCheck, "s", $name);
                mysqli_stmt_execute($stmtCheck);
                $resCheck = mysqli_stmt_get_result($stmtCheck);

                if ($row = mysqli_fetch_assoc($resCheck)) {
                    $productID = $row['product_id'];
                } else {
        // 2. Insert new product using your columns: name, description, base_price, category_id, tier_id
                $insertProdQuery = "INSERT INTO products (product_name, product_description, base_price, category_id, tier_id, is_active) 
                                    VALUES (?, ?, ?, ?, ?, 1)";
                 $stmtProd = mysqli_prepare($con, $insertProdQuery);
                mysqli_stmt_bind_param($stmtProd, "ssdii", $name, $description, $basePrice, $catID, $tierID);
                mysqli_stmt_execute($stmtProd);
                $productID = mysqli_insert_id($con);
                }

    // 3. Insert the variant using your columns: product_id, size_id, color_id, sku, stock_quantity, price_override
                $insertVarQuery = "INSERT INTO product_variants (product_id, size_id, color_id, sku, stock_quantity, price_override, is_active) 
                                   VALUES (?, ?, ?, ?, ?, ?, 1)";
                $stmtVar = mysqli_prepare($con, $insertVarQuery);
    
    // Bind parameters: i = int, s = string, d = double/decimal
                mysqli_stmt_bind_param($stmtVar, "iiisid", $productID, $sizeID, $colorID, $sku, $stock, $priceOverride);
    
                return mysqli_stmt_execute($stmtVar);
}
            
        /*Add all functions here so it can be used by other webpages*/

?>