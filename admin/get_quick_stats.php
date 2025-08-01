<?php
include 'connect.php';

// Set header to return JSON
header('Content-Type: application/json');

// Get today's date
$today = date('Y-m-d');
$yesterday = date('Y-m-d', strtotime('-1 day'));
$currentMonth = date('Y-m');
$lastMonth = date('Y-m', strtotime('-1 month'));

// Initialize response array
$response = array();

// Get today's revenue
$todayRevenueQuery = "SELECT COALESCE(SUM(HD_TONGTIEN), 0) as today_revenue 
                      FROM hoa_don 
                      WHERE DATE(HD_NGAYLAP) = '$today' 
                      AND HD_TRANGTHAI = 'Hoàn thành'";
$todayRevenueResult = mysqli_query($conn, $todayRevenueQuery);
$todayRevenue = mysqli_fetch_assoc($todayRevenueResult)['today_revenue'];

// Get yesterday's revenue
$yesterdayRevenueQuery = "SELECT COALESCE(SUM(HD_TONGTIEN), 0) as yesterday_revenue 
                         FROM hoa_don 
                         WHERE DATE(HD_NGAYLAP) = '$yesterday' 
                         AND HD_TRANGTHAI = 'Hoàn thành'";
$yesterdayRevenueResult = mysqli_query($conn, $yesterdayRevenueQuery);
$yesterdayRevenue = mysqli_fetch_assoc($yesterdayRevenueResult)['yesterday_revenue'];

// Calculate revenue change percentage
$revenueChange = $yesterdayRevenue > 0 ? 
                 round((($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100, 2) : 
                 100;

// Get today's orders count
$todayOrdersQuery = "SELECT COUNT(*) as today_orders 
                     FROM hoa_don 
                     WHERE DATE(HD_NGAYLAP) = '$today'";
$todayOrdersResult = mysqli_query($conn, $todayOrdersQuery);
$todayOrders = mysqli_fetch_assoc($todayOrdersResult)['today_orders'];

// Get yesterday's orders count
$yesterdayOrdersQuery = "SELECT COUNT(*) as yesterday_orders 
                        FROM hoa_don 
                        WHERE DATE(HD_NGAYLAP) = '$yesterday'";
$yesterdayOrdersResult = mysqli_query($conn, $yesterdayOrdersQuery);
$yesterdayOrders = mysqli_fetch_assoc($yesterdayOrdersResult)['yesterday_orders'];

// Calculate orders change percentage
$ordersChange = $yesterdayOrders > 0 ? 
                round((($todayOrders - $yesterdayOrders) / $yesterdayOrders) * 100, 2) : 
                100;

// Get total products sold this month
$monthProductsQuery = "SELECT COALESCE(SUM(CTHD_SOLUONG), 0) as month_products 
                      FROM chi_tiet_hd ct 
                      JOIN hoa_don hd ON ct.HD_MA = hd.HD_MA 
                      WHERE DATE_FORMAT(HD_NGAYLAP, '%Y-%m') = '$currentMonth' 
                      AND HD_TRANGTHAI = 'Hoàn thành'";
$monthProductsResult = mysqli_query($conn, $monthProductsQuery);
$monthProducts = mysqli_fetch_assoc($monthProductsResult)['month_products'];

// Get total products sold last month
$lastMonthProductsQuery = "SELECT COALESCE(SUM(CTHD_SOLUONG), 0) as last_month_products 
                          FROM chi_tiet_hd ct 
                          JOIN hoa_don hd ON ct.HD_MA = hd.HD_MA 
                          WHERE DATE_FORMAT(HD_NGAYLAP, '%Y-%m') = '$lastMonth' 
                          AND HD_TRANGTHAI = 'Hoàn thành'";
$lastMonthProductsResult = mysqli_query($conn, $lastMonthProductsQuery);
$lastMonthProducts = mysqli_fetch_assoc($lastMonthProductsResult)['last_month_products'];

// Calculate products change percentage
$productsChange = $lastMonthProducts > 0 ? 
                 round((($monthProducts - $lastMonthProducts) / $lastMonthProducts) * 100, 2) : 
                 100;

// Get new customers this month
$newCustomersQuery = "SELECT COUNT(*) as new_customers 
                     FROM khach_hang 
                     WHERE DATE_FORMAT(KH_NGAYTAO, '%Y-%m') = '$currentMonth'";
$newCustomersResult = mysqli_query($conn, $newCustomersQuery);
$newCustomers = mysqli_fetch_assoc($newCustomersResult)['new_customers'];

// Get new customers last month
$lastMonthCustomersQuery = "SELECT COUNT(*) as last_month_customers 
                          FROM khach_hang 
                          WHERE DATE_FORMAT(KH_NGAYTAO, '%Y-%m') = '$lastMonth'";
$lastMonthCustomersResult = mysqli_query($conn, $lastMonthCustomersQuery);
$lastMonthCustomers = mysqli_fetch_assoc($lastMonthCustomersResult)['last_month_customers'];

// Calculate customers change percentage
$customersChange = $lastMonthCustomers > 0 ? 
                  round((($newCustomers - $lastMonthCustomers) / $lastMonthCustomers) * 100, 2) : 
                  100;

// Prepare response
$response = array(
    'todayRevenue' => $todayRevenue,
    'revenueChange' => $revenueChange,
    'todayOrders' => $todayOrders,
    'ordersChange' => $ordersChange,
    'totalProducts' => $monthProducts,
    'productsChange' => $productsChange,
    'newCustomers' => $newCustomers,
    'customersChange' => $customersChange
);

// Return JSON response
echo json_encode($response);
?> 