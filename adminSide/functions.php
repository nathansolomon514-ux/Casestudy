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
            $query = "SELECT productName, stock, price FROM INVENTORY 
                      WHERE LOWER(REPLACE(REPLACE(productName, ' ', ''), '-', '')) LIKE ?";

            $stmt = mysqli_prepare($con, $query);
            $cleanSearchData = cleanSearch($search);
            $searchTerm = "%". $cleanSearchData ."%";
            mysqli_stmt_bind_param($stmt, "s", $searchTerm);
            mysqli_stmt_execute($stmt);
            return mysqli_stmt_get_result($stmt);
        }

        function dataCleaning($data){ //Keep for verifying later
           // $data = password_verify($data); Keep this.
           $data = trim($data); //can delete later
            return $data;
            }

        /*Add all functions here so it can be used by other webpages*/

?>