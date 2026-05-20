<?php
    include_once "../phpActionScripts/sessionCheck.php";
    include_once "../../Front-End/connection.php";
    include_once "../phpActionScripts/functions.php"; 
    include_once "../phpActionScripts/dashboardCounter.php";
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../adminCSS/adminDashboard.css">
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
   <h3 class="welcomeAdmin">Welcome Admin, <?php echo htmlspecialchars($_SESSION['adminName']); ?></h3>


<main class="dashboardContent">
    <div class="dashboardStatistics">
        
        <a href="adminInventory.php" style="text-decoration: none;">
            <div class="statCard"> 
                <div class="cardTitle">Products in Stock</div>
                <div class="cardValue"><?php echo $countProducts; ?></div>
            </div>
        </a>

        <a href="customerProfiles.php" style="text-decoration: none;">
        <div class="statCard">
            <div class="cardTitle">User Accounts</div>
            <div class="cardValue"><?php echo $countUsers; ?></div>
        </div>
        </a>

        <a href="adminUsers.php" style="text-decoration: none;">
        <div class="statCard">
            <div class="cardTitle">Admin Accounts</div>
            <div class="cardValue"><?php echo $countAdmins; ?></div>
        </div>
         </a>

        <a href="adminOrders.php" style="text-decoration: none;">
        <div class="statCard">
            <div class="cardTitle">Total Orders</div>
            <div class="cardValue"><?php echo $countOrders; ?></div>
        </div>
        </a>

        <a href="adminOrders.php" style="text-decoration: none;">
        <div class="statCard">
            <div class="cardTitle">Orders Pending</div>
            <div class="cardValue"><?php echo $pendingOrders; ?></div>
        </div>
            </a>

        <a href="adminOrders.php" style="text-decoration: none;">
             <div class="statCard">
                <div class="cardTitle">Top Courier</div>
                <div class="cardValue">
                    <?php echo $popularCourier ? htmlSpecialCharss($popularCourier['full_name']) : "No Deliveries"; ?>
                </div>
        </div>
        </a>

        <a href="adminOrders.php" style="text-decoration: none;">
            <div class="statCard">
                <div class="cardTitle">Daily Revenue</div>
                <div class="cardValue">₱<?php echo number_format($dailyRevenue, 2); ?></div>
            </div>
        </a>

        <a href="adminOrders.php" style="text-decoration: none;">
            <div class="statCard">
                <div class="cardTitle">Weekly Revenue</div>
                <div class="cardValue">₱<?php echo number_format($weeklyRevenue, 2); ?></div>
            </div>
        </a>

        <a href="adminOrders.php" style="text-decoration: none;">
            <div class="statCard">
                <div class="cardTitle">Monthly Revenue</div>
                <div class="cardValue">₱<?php echo number_format($monthlyRevenue, 2); ?></div>
            </div>
        </a>

        <a href="adminInventory.php" style="text-decoration: none;">
        <div class="statCard">
        <div class="cardTitle">Top Selling Variant</div>
        <div class="cardValue" style="font-size: 0.95rem; line-height: 1.3; padding: 5px 10px; word-break: break-word; max-width: 100%;">
            <?php echo $popularProduct ? htmlSpecialCharss($popularProduct['variant_full_name']) : "No Sales Yet"; ?>
        </div>
        <small style="color: #666; font-size: 0.8rem;">
            <?php echo $popularProduct ? $popularProduct['order_count'] . " orders" : ""; ?>
        </small>

        
    </div>
</a>


<a href="adminInventory.php" style="text-decoration: none;">
    <div class="statCard">
        <div class="cardTitle">Least Selling Variant</div>
        <div class="cardValue" style="font-size: 0.95rem; line-height: 1.3; padding: 5px 10px; word-break: break-word; max-width: 100%;">
            <?php echo $leastProduct ? htmlSpecialCharss($leastProduct['variant_full_name']) : "No Sales Yet"; ?>
        </div>
        <small style="color: #666; font-size: 0.8rem;">
            <?php echo $leastProduct ? $leastProduct['order_count'] . " orders" : ""; ?>
        </small>
    </div>
</a>


<div class="analyticsSection" style="margin-top: 30px; background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="margin-top: 0; color: #333; font-size: 1.2rem; border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 20px;">
            Revenue Trend (Last 7 Days)
        </h2>
        
        <div style="width: 100%; height: 300px;"> <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="chartContainer" style="margin-top: 30px; background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h3>Payment Methods</h3>
        <canvas id="paymentChart"></canvas>
    </div>

    <div class="analyticsGrid" >
    <div class="chartContainer" style="margin-top: 30px; background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h3>Order Status Distribution</h3>
        <canvas id="statusChart"></canvas>
    </div>

    <div class="chartContainer" style="margin-top: 30px; background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h3>New Users (Last 7 Days)</h3>
        <canvas id="userChart"></canvas>
    </div>
</div>
</div>


</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    fetch('../phpActionScripts/getRevenueData.php')
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Revenue (₱)',
                        data: data.values,
                        backgroundColor: '#28a745',
                        borderColor: '#1e7e34',
                        fill: false,
                        borderWidth: 1,
                        tension: 0.1
                    }]
                },
                
            });
        })
        .catch(error => console.error('Error fetching chart data:', error));
});

fetch('../phpActionScripts/getStatusData.php')
        .then(r => r.json())
        .then(data => {
            console.log("Status Data:", data); // Check console to see if data arrives
            new Chart(document.getElementById('statusChart'), {
                type: 'doughnut',
                data: {
                    labels: data.labels,
                    datasets: [{ 
                        data: data.values, 
                        backgroundColor: ['#ffc107', '#17a2b8', '#28a745', '#dc3545'] 
                    }]
                }
            });
        });

    // 3. User Growth Chart
    fetch('../phpActionScripts/getUserGrowthData.php')
        .then(r => r.json())
        .then(data => {
            new Chart(document.getElementById('userChart'), {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{ 
                        label: 'New Users',
                        data: data.values, 
                        borderColor: '#007bff',
                        fill: false 
                    }]
                }
            });
        });

        fetch('../phpActionScripts/getPaymentData.php')
    .then(r => r.json())
    .then(data => {
        new Chart(document.getElementById('paymentChart'), {
            type: 'doughnut',
            data: {
                labels: data.labels,
                datasets: [{ 
                    data: data.values, 
                    backgroundColor: ['#6f42c1', '#fd7e14', '#20c997'] // Add more colors if needed
                }]
            }
        });
    });
</script>
</body>
</html>