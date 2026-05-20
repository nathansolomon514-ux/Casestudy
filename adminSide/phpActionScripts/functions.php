<?php

        /*data cleaning for search inputs*/
        function cleanSearch($searchData) {
            $searchData = trim($searchData);
            $searchData = str_replace(['-', ' '], '', $searchData);
            $searchData = strtolower($searchData);
            return $searchData;
        }

        /* data inputs */
        function cleanInput($data) {
    $data = trim($data);
    $data = filter_var($data, FILTER_SANITIZE_SPECIAL_CHARS);
    $data = ucwords(strtolower($data));
    return $data;
}

        function searchInventory($con, $limit, $offset, $search ="", $isArchived = false) {
            $deleteStatus = $isArchived ? 0 : 1;
            $inventoryQuery = "SELECT 
                                pv.variant_id AS variantId,
                                p.product_id AS productId,
                                p.product_name AS productName,
                                p.product_description,
                                c.category_name AS categoryName,
                                pv.stock_quantity AS stock,
                                COALESCE(pv.price_override, p.base_price) AS price,
                                pv.variant_image,
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

        function getOrders($con, $limit, $offset, $orderSearch = "", $statusFilter="All", $paymentFilter="All") {
    $matchStatus = ($statusFilter === "All") ? "%" : $statusFilter;
    $matchPayment = ($paymentFilter === "All") ? "%" : $paymentFilter;
    $ordersQuery = "SELECT
                    o.order_id, o.created_at, o.order_status_id, u.first_name, u.last_name, os.order_status_name,
                    CONCAT(c.first_name, ' ' , c.last_name) AS courier_name, ds.delivery_status_name, 
                    loc.user_loc AS shipping_address,
                    p.payment_method, ps.payment_status_name,
                    (SELECT SUM(oi.quantity * oi.price_at_purchase)
                        FROM order_items oi WHERE oi.order_id = o.order_id) AS total_amount
                    FROM orders o
                    JOIN users u ON o.user_id = u.user_id
                    JOIN order_statuses os ON o.order_status_id = os.order_status_id
                    LEFT JOIN deliveries d ON o.order_id = d.order_id
                    LEFT JOIN couriers c ON d.courier_id = c.courier_id
                    LEFT JOIN delivery_statuses ds ON d.delivery_status_id = ds.delivery_status_id
                    LEFT JOIN user_location loc ON d.location_id = loc.location_id
                    LEFT JOIN payments p ON o.order_id = p.order_id
                    LEFT JOIN payment_statuses ps ON p.payment_status_id = ps.payment_status_id
                    WHERE (u.first_name LIKE ? OR u.last_name LIKE ? OR o.order_id LIKE ?)
                    AND o.order_status_id LIKE ?
                    AND p.payment_method LIKE ?
                    ORDER BY o.created_at DESC, o.order_id DESC
                    LIMIT ? OFFSET ?";

    $stmt = mysqli_prepare($con, $ordersQuery);
    $cleanSearchData = cleanSearch($orderSearch);
    $searchTerm = "%". $cleanSearchData . "%";
    
    mysqli_stmt_bind_param($stmt, 'sssssii', $searchTerm, $searchTerm, $searchTerm, $matchStatus, $matchPayment, $limit, $offset);
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
    // Only allow specific table names
    $allowedTables = ['products', 'users', 'orders', 'product_variants'];
    if (!in_array($table, $allowedTables)) {
        return 0;
    }
    
    $sql = "SELECT COUNT(*) AS total FROM $table $condition";
    $result = mysqli_query($con, $sql);
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

function addProductVariant($con, $entryType, $existingProductID, $name, $description, $basePrice, $catID, $tierID, $sizeName, $colorName, $sku, $stock, $priceOverride, $variantImage) {
    
    if ($entryType === 'existing') {
        $productID = $existingProductID;
    } else {
        $insertProdQuery = "INSERT INTO products (product_name, product_description, category_id, tier_id, is_active) VALUES (?, ?, ?, ?, 1)";
        $stmtProd = mysqli_prepare($con, $insertProdQuery);
        mysqli_stmt_bind_param($stmtProd, "ssii", $name, $description, $catID, $tierID);
        
        if (!mysqli_stmt_execute($stmtProd)) {
            return false; 
        }
        $productID = mysqli_insert_id($con);
    }

    $sizeID = getOrCreateId($con, 'sizes', 'size_name', $sizeName);
    $colorID = getOrCreateId($con, 'colors', 'color_name', $colorName);

$insertVarQuery = "INSERT INTO product_variants 
                       (product_id, size_id, color_id, sku, stock_quantity, price_override, is_active, variant_image) 
                       VALUES (?, ?, ?, ?, ?, ?, 1, ?)";
    
    $stmtVar = mysqli_prepare($con, $insertVarQuery);
    mysqli_stmt_bind_param($stmtVar, "iiisids", $productID, $sizeID, $colorID, $sku, $stock, $priceOverride, $variantImage);
    
    return mysqli_stmt_execute($stmtVar);
}

function getOrCreateId($con, $table, $column, $value) {
    $value = trim($value); 
    
    // Check if it exists
    $query = "SELECT " . str_replace('_name', '_id', $column) . " FROM $table WHERE LOWER($column) = LOWER(?)";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $value);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_array($res)) {
        return $row[0]; // Return existing ID
    } else {
        // Create new entry
        $insert = "INSERT INTO $table ($column) VALUES (?)";
        $stmtInsert = mysqli_prepare($con, $insert);
        mysqli_stmt_bind_param($stmtInsert, "s", $value);
        mysqli_stmt_execute($stmtInsert);
        return mysqli_insert_id($con); // Return new ID
    }
}

function getOrAddCategory($con, $categoryName) {
    $categoryName = cleanInput($categoryName);

    // 1. Check if it exists
    $stmt = mysqli_prepare($con, "SELECT category_id FROM categories WHERE category_name = ?");
    mysqli_stmt_bind_param($stmt, "s", $categoryName);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        mysqli_stmt_close($stmt);
        return $row['category_id'];
    } else {
        // 2. Insert if it doesn't exist
        mysqli_stmt_close($stmt); // Close the previous statement
        $insertStmt = mysqli_prepare($con, "INSERT INTO categories (category_name) VALUES (?)");
        mysqli_stmt_bind_param($insertStmt, "s", $categoryName);
        mysqli_stmt_execute($insertStmt);
        
        // Get the new ID using the connection link
        $newId = mysqli_insert_id($con);
        mysqli_stmt_close($insertStmt);
        return $newId;
    }
}

            function getDailyRevenue($con) {
                $query = "SELECT SUM(oi.quantity * oi.price_at_purchase) AS total 
                          FROM order_items oi 
                          JOIN orders o ON oi.order_id = o.order_id 
                          WHERE DATE(o.created_at) = CURDATE() 
                          AND o.order_status_id = 4";
                $stmt = mysqli_prepare($con, $query);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_assoc($result);
                return $row['total'] ?? 0.00;
            }

            function getWeeklyRevenue($con) {
                $query = "SELECT SUM(oi.quantity * oi.price_at_purchase) AS total 
                          FROM order_items oi 
                          JOIN orders o ON oi.order_id = o.order_id 
                          WHERE o.created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) 
                          AND o.order_status_id = 4";
                $stmt = mysqli_prepare($con, $query);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_assoc($result);
                return $row['total'] ?? 0.00;
            }

            function getMonthlyRevenue($con) {
                $query = "SELECT SUM(oi.quantity * oi.price_at_purchase) AS total 
                          FROM order_items oi 
                          JOIN orders o ON oi.order_id = o.order_id 
                          WHERE o.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) 
                          AND o.order_status_id = 4";
                $stmt = mysqli_prepare($con, $query);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_assoc($result);
                return $row['total'] ?? 0.00;
            }

            function getMostPopularProduct($con) {
                $query = "SELECT pv.variant_id, 
                            CONCAT('[', cat.category_name, '] ', p.product_name, ' - ', c.color_name, ' (', s.size_name, ')') AS variant_full_name,
                            COUNT(oi.order_id) AS order_count
                            FROM order_items oi
                            JOIN product_variants pv ON oi.variant_id = pv.variant_id
                            JOIN products p ON pv.product_id = p.product_id
                            JOIN categories cat ON p.category_id = cat.category_id
                            JOIN colors c ON pv.color_id = c.color_id
                            JOIN sizes s ON pv.size_id = s.size_id
                            GROUP BY pv.variant_id, p.product_name, cat.category_name, c.color_name, s.size_name
                            ORDER BY order_count DESC
                            LIMIT 1";
                $stmt = mysqli_prepare($con, $query);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                return mysqli_fetch_assoc($result) ?: null;
            }

            function getLeastPopularProduct($con) {
                $query = "SELECT pv.variant_id, 
                            CONCAT('[', cat.category_name, '] ', p.product_name, ' - ', c.color_name, ' (', s.size_name, ')') AS variant_full_name,
                            COUNT(oi.order_id) AS order_count
                            FROM order_items oi
                            JOIN product_variants pv ON oi.variant_id = pv.variant_id
                            JOIN products p ON pv.product_id = p.product_id
                            JOIN categories cat ON p.category_id = cat.category_id
                            JOIN colors c ON pv.color_id = c.color_id
                            JOIN sizes s ON pv.size_id = s.size_id
                            GROUP BY pv.variant_id, p.product_name, cat.category_name, c.color_name, s.size_name
                            ORDER BY order_count ASC
                            LIMIT 1";
                $stmt = mysqli_prepare($con, $query);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                return mysqli_fetch_assoc($result) ?: null;
            }


function getAllActiveProducts($con) {
    $query = "SELECT product_id, product_name FROM products WHERE is_active = 1 ORDER BY product_name ASC";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

function getAllCategories($con) {
    $query = "SELECT category_id, category_name FROM categories ORDER BY category_name ASC";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

function getAllColors($con) {
    $query = "SELECT color_id, color_name FROM colors ORDER BY color_name ASC";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

function getAllSizes($con) {
    $query = "SELECT size_id, size_name FROM sizes ORDER BY size_id ASC";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

function getWeeklyRevenueData($con) {
    $data = ['labels' => [], 'values' => []];
    $query = "SELECT DATE(o.created_at) as date, SUM(p.amount) as total 
              FROM orders o
              JOIN payments p ON o.order_id = p.order_id
              WHERE o.created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
              GROUP BY DATE(o.created_at)
              ORDER BY DATE(o.created_at) ASC";

    $stmt = mysqli_prepare($con, $query);
    
    if ($stmt) {
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        while ($row = mysqli_fetch_assoc($result)) {
            $data['labels'][] = date("D", strtotime($row['date']));
            $data['values'][] = (float)$row['total'];
        }
        mysqli_stmt_close($stmt);
    }
    
    return $data;
}

function getOrderStatusData($con) {
    $data = ['labels' => [], 'values' => []];
    $query = "SELECT os.order_status_name AS status_name, COUNT(o.order_id) as count 
              FROM orders o
              JOIN order_statuses os ON o.order_status_id = os.order_status_id
              GROUP BY os.order_status_name";

    $stmt = mysqli_prepare($con, $query);
    if ($stmt) {
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            $data['labels'][] = $row['status_name'];
            $data['values'][] = (int)$row['count'];
        }
        mysqli_stmt_close($stmt);
    }
    return $data;
}

function getUserGrowthData($con) {
    $data = ['labels' => [], 'values' => []];
    $query = "SELECT DATE(created_at) as date, COUNT(user_id) as new_users 
              FROM users 
              WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
              GROUP BY DATE(created_at)
              ORDER BY DATE(created_at) ASC";

    $stmt = mysqli_prepare($con, $query);
    if ($stmt) {
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            $data['labels'][] = date("D", strtotime($row['date']));
            $data['values'][] = (int)$row['new_users'];
        }
        mysqli_stmt_close($stmt);
    }
    return $data;
}

function getPaymentMethodData($con) {
    $data = ['labels' => [], 'values' => []];
    
    $query = "SELECT payment_method, COUNT(*) AS count 
              FROM payments 
              GROUP BY payment_method";

    $stmt = mysqli_prepare($con, $query);
    if ($stmt) {
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        while ($row = mysqli_fetch_assoc($result)) {
            $data['labels'][] = $row['payment_method'] ?? 'Unknown';
            $data['values'][] = (int)$row['count'];
        }
        mysqli_stmt_close($stmt);
    }
    return $data;
}
            
        /*Add all functions here so it can be used by other webpages*/

?>