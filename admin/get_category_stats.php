<?php
include 'connect.php';

header('Content-Type: application/json');

$timeRange = isset($_GET['timeRange']) ? $_GET['timeRange'] : '30days';

$response = array();

// Điều kiện thời gian
$timeCondition = "";
switch ($timeRange) {
    case '7days':
        $timeCondition = "AND hd.HD_NGAYLAP >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
        break;
    case '30days':
        $timeCondition = "AND hd.HD_NGAYLAP >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
        break;
    case 'thismonth':
        $timeCondition = "AND DATE_FORMAT(hd.HD_NGAYLAP, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')";
        break;
    case 'lastmonth':
        $timeCondition = "AND DATE_FORMAT(hd.HD_NGAYLAP, '%Y-%m') = DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH), '%Y-%m')";
        break;
    case 'thisyear':
        $timeCondition = "AND YEAR(hd.HD_NGAYLAP) = YEAR(CURDATE())";
        break;
    default:
        $timeCondition = "AND hd.HD_NGAYLAP >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
}

// Thống kê doanh thu theo danh mục
$query = "SELECT 
            dm.DM_MA,
            dm.DM_TEN,
            COUNT(DISTINCT sp.SP_MA) as total_products,
            COUNT(DISTINCT hd.HD_STT) as total_orders,
            SUM(ct.CTHD_SOLUONG) as total_quantity,
            SUM(ct.CTHD_SOLUONG * ct.CTHD_DONGIA) as total_revenue
         FROM danh_muc dm
         LEFT JOIN san_pham sp ON dm.DM_MA = sp.DM_MA
         LEFT JOIN chi_tiet_hd ct ON sp.SP_MA = ct.SP_MA
         LEFT JOIN hoa_don hd ON ct.HD_STT = hd.HD_STT
            AND hd.HD_TRANGTHAI = 'Hoàn thành'
            $timeCondition
         GROUP BY dm.DM_MA, dm.DM_TEN
         ORDER BY total_revenue DESC";

$result = mysqli_query($conn, $query);

if ($result) {
    $data = array();
    $totalRevenue = 0;
    $totalQuantity = 0;
    
    // First pass: calculate totals
    while ($row = mysqli_fetch_assoc($result)) {
        $totalRevenue += $row['total_revenue'];
        $totalQuantity += $row['total_quantity'];
        $data[] = $row;
    }
    
    // Second pass: calculate percentages
    foreach ($data as &$category) {
        $category['revenue_percentage'] = $totalRevenue > 0 ? 
            round(($category['total_revenue'] / $totalRevenue) * 100, 2) : 0;
        $category['quantity_percentage'] = $totalQuantity > 0 ? 
            round(($category['total_quantity'] / $totalQuantity) * 100, 2) : 0;
    }
    
    // Get trend data for each category
    $trendQuery = "SELECT 
                    dm.DM_MA,
                    dm.DM_TEN,
                    DATE_FORMAT(hd.HD_NGAYLAP, '%Y-%m') as month,
                    SUM(ct.CTHD_SOLUONG * ct.CTHD_DONGIA) as monthly_revenue,
                    SUM(ct.CTHD_SOLUONG) as monthly_quantity
                 FROM danh_muc dm
                 LEFT JOIN san_pham sp ON dm.DM_MA = sp.DM_MA
                 LEFT JOIN chi_tiet_hd ct ON sp.SP_MA = ct.SP_MA
                 LEFT JOIN hoa_don hd ON ct.HD_STT = hd.HD_STT
                    AND hd.HD_TRANGTHAI = 'Hoàn thành'
                    AND hd.HD_NGAYLAP >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                 GROUP BY dm.DM_MA, dm.DM_TEN, month
                 ORDER BY dm.DM_MA, month";

    $trendResult = mysqli_query($conn, $trendQuery);
    
    if ($trendResult) {
        $trends = array();
        while ($row = mysqli_fetch_assoc($trendResult)) {
            if (!isset($trends[$row['DM_MA']])) {
                $trends[$row['DM_MA']] = array(
                    'name' => $row['DM_TEN'],
                    'data' => array()
                );
            }
            $trends[$row['DM_MA']]['data'][$row['month']] = array(
                'revenue' => $row['monthly_revenue'],
                'quantity' => $row['monthly_quantity']
            );
        }
        $response['trends'] = $trends;
    }
    
    // Get top products in each category
    $topProductsQuery = "SELECT 
                            dm.DM_MA,
                            sp.SP_MA,
                            sp.SP_TEN,
                            SUM(ct.CTHD_SOLUONG) as total_quantity,
                            SUM(ct.CTHD_SOLUONG * ct.CTHD_DONGIA) as total_revenue
                         FROM danh_muc dm
                         JOIN san_pham sp ON dm.DM_MA = sp.DM_MA
                         JOIN chi_tiet_hd ct ON sp.SP_MA = ct.SP_MA
                         JOIN hoa_don hd ON ct.HD_STT = hd.HD_STT
                            AND hd.HD_TRANGTHAI = 'Hoàn thành'
                            $timeCondition
                         GROUP BY dm.DM_MA, sp.SP_MA, sp.SP_TEN
                         ORDER BY dm.DM_MA, total_revenue DESC";

    $topProductsResult = mysqli_query($conn, $topProductsQuery);
    
    if ($topProductsResult) {
        $topProducts = array();
        while ($row = mysqli_fetch_assoc($topProductsResult)) {
            if (!isset($topProducts[$row['DM_MA']])) {
                $topProducts[$row['DM_MA']] = array();
            }
            if (count($topProducts[$row['DM_MA']]) < 5) { // Lấy top 5 sản phẩm mỗi danh mục
                $topProducts[$row['DM_MA']][] = $row;
            }
        }
        $response['top_products'] = $topProducts;
    }
    
    $response['data'] = $data;
    $response['summary'] = array(
        'total_revenue' => $totalRevenue,
        'total_quantity' => $totalQuantity
    );
} else {
    $response['error'] = 'Failed to fetch data';
}

$response['timeRange'] = $timeRange;

echo json_encode($response);
?> 