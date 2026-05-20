<?php
    $countProducts = getTableCount($con, "product_variants", "WHERE is_active = 1");
    $countUsers    = getTableCount($con, "users");
    $countAdmins   = getTableCount($con, "krnk_admin");
    $countOrders   = getTableCount($con, "orders");
    $pendingOrders = getTableCount($con, "orders", "WHERE order_status_id = 1");
    $popularCourier = getMostPopularCourier($con);

    $dailyRevenue   = getDailyRevenue($con);
    $weeklyRevenue  = getWeeklyRevenue($con);
    $monthlyRevenue = getMonthlyRevenue($con);

    $popularProduct = getMostPopularProduct($con);
    $leastProduct   = getLeastPopularProduct($con);
?>