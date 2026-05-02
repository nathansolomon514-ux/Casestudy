<?php

        /*data cleaning for search inputs*/
        function cleanSearch($searchData) {
            $searchData = trim($searchData);
            $searchData = str_replace(['-', ' '], '', $searchData);
            $searchData = strtolower($searchData);
            return $searchData;
        }


        /*Search Bar, can be used in other files if want. Just copy and change the query.*/
        function searchInventory($con, $search ="") {
            $inventoryQuery = "SELECT productName, stock, price FROM INVENTORY 
                      WHERE LOWER(REPLACE(REPLACE(productName, ' ', ''), '-', '')) LIKE ?";

            $stmt = mysqli_prepare($con, $inventoryQuery);
            $cleanSearchData = cleanSearch($search);
            $searchTerm = "%". $cleanSearchData ."%";
            mysqli_stmt_bind_param($stmt, "s", $searchTerm);
            mysqli_stmt_execute($stmt);
            return mysqli_stmt_get_result($stmt);
        }

        /*This will used to call this function later
            $adminSearch = $_GET["searchBar"];
            $result = adminQuery($con, $)
        */
        function adminQuery($con, $adminSearch="") {
            $adminQuery = "SELECT adminID, adminName, adminPassword FROM ADMINS
                           WHERE adminID LIKE ? OR adminName LIKE ?";
            $stmt = mysqli_prepare($con, $adminQuery);
            $cleanAdminSearch = cleanSearch($adminSearch);
            $searchAdminName = "%". $cleanAdminSearch ."%";
            mysqli_stmt_bind_param($stmt, "ss", $searchAdminName, $searchAdminName);
            mysqli_stmt_execute($stmt);
            return mysqli_stmt_get_result($stmt);
        }


        /*addAdmin function to be called in addAmin.php*/
        function addAdmin($con, $adminName, $adminPassword){
            $adminInsert = "INSERT INTO ADMINS (adminName, adminPassword)
                            VALUES ( ?, ?)";
            $stmt = mysqli_prepare($con, $adminInsert);
            mysqli_stmt_bind_param($stmt, "ss", $adminName, $adminPassword);
            return mysqli_stmt_execute($stmt);

        }

        function deleteAdmin($con, $adminID){
            $deleteQuery = "DELETE FROM ADMINS WHERE adminID = ?";
            $stmt = mysqli_prepare($con, $deleteQuery);
            mysqli_stmt_bind_param($stmt, "i", $adminID);
            return mysqli_stmt_execute($stmt);
        }

        function updateAdmin($con, $adminID, $newAdminName, $newAdminPassword= null){
            if(!empty($newAdminPassword)) { //update both pass and name
                $hashPassword = password_hash($newAdminPassword, PASSWORD_DEFAULT);
                $adminEditQuery = "UPDATE ADMINS
                                       SET adminName = ?, adminPassword = ?
                                       WHERE adminID =?";
                $stmt = mysqli_prepare($con, $adminEditQuery);
                mysqli_stmt_bind_param($stmt, "ssi", $newAdminName, $hashPassword, $adminID);
            } else { //name only
                $adminEditQuery = "UPDATE ADMINS SET adminName = ? WHERE adminID = ?";
                $stmt = mysqli_prepare($con, $adminEditQuery);
                mysqli_stmt_bind_param($stmt, "si", $newAdminName, $adminID);
            }
            return mysqli_stmt_execute($stmt);
        }

        function getAdminByID($con, $adminID){
            $getEditAdminQuery = "SELECT * FROM ADMINS WHERE adminID=?";
            $stmt = mysqli_prepare($con, $getEditAdminQuery);
            mysqli_stmt_bind_param($stmt, "i", $adminID);
            mysqli_stmt_execute($stmt);
            return mysqli_stmt_fetch_assoc(mysqli_stmt_get_result($stmt));
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

        /*Add all functions here so it can be used by other webpages*/

?>