<?php
include 'connect.php';

header('Content-Type: application/json');

$type = isset($_GET['type']) ? $_GET['type'] : 'top';
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
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

switch ($type) {
    case 'top':
        // Sản phẩm bán chạy
        $query = "SELECT 
                    sp.SP_MA,
                    sp.SP_TEN,
                    dm.DM_TEN as category_name,
                    COUNT(DISTINCT hd.HD_STT) as total_orders,
                    SUM(ct.CTHD_SOLUONG) as total_quantity,
                    SUM(ct.CTHD_SOLUONG * ct.CTHD_DONGIA) as total_revenue
                 FROM san_pham sp
                 LEFT JOIN danh_muc dm ON sp.DM_MA = dm.DM_MA
                 LEFT JOIN chi_tiet_hd ct ON sp.SP_MA = ct.SP_MA
                 LEFT JOIN hoa_don hd ON ct.HD_STT = hd.HD_STT
                    AND hd.HD_TRANGTHAI = 'Hoàn thành'
                    $timeCondition
                 GROUP BY sp.SP_MA, sp.SP_TEN, dm.DM_TEN
                 HAVING total_quantity > 0
                 ORDER BY total_quantity DESC
                 LIMIT $limit";
        break;

    case 'slow':
        // Sản phẩm bán chậm
        $query = "SELECT 
                    sp.SP_MA,
                    sp.SP_TEN,
                    dm.DM_TEN as category_name,
                    sp.SP_SOLUONGTON as current_stock,
                    COALESCE(SUM(ct.CTHD_SOLUONG), 0) as total_sold
                 FROM san_pham sp
                 LEFT JOIN danh_muc dm ON sp.DM_MA = dm.DM_MA
                 LEFT JOIN chi_tiet_hd ct ON sp.SP_MA = ct.SP_MA
                 LEFT JOIN hoa_don hd ON ct.HD_STT = hd.HD_STT
                    AND hd.HD_TRANGTHAI = 'Hoàn thành'
                    $timeCondition
                 GROUP BY sp.SP_MA, sp.SP_TEN, dm.DM_TEN, sp.SP_SOLUONGTON
                 HAVING total_sold = 0
                 ORDER BY sp.SP_SOLUONGTON DESC
                 LIMIT $limit";
        break;

    case 'inventory':
        // Thống kê tồn kho
        $query = "SELECT 
                    sp.SP_MA,
                    sp.SP_TEN,
                    dm.DM_TEN as category_name,
                    sp.SP_SOLUONGTON as current_stock,
                    sp.SP_AVAILABLE as available_stock,
                    COALESCE(SUM(ct.CTHD_SOLUONG), 0) as total_sold
                 FROM san_pham sp
                 LEFT JOIN danh_muc dm ON sp.DM_MA = dm.DM_MA
                 LEFT JOIN chi_tiet_hd ct ON sp.SP_MA = ct.SP_MA
                 LEFT JOIN hoa_don hd ON ct.HD_STT = hd.HD_STT
                    AND hd.HD_TRANGTHAI = 'Hoàn thành'
                    $timeCondition
                 GROUP BY sp.SP_MA, sp.SP_TEN, dm.DM_TEN, sp.SP_SOLUONGTON, sp.SP_AVAILABLE
                 ORDER BY current_stock ASC
                 LIMIT $limit";
        break;

    case 'category':
        // Thống kê theo danh mục
        $query = "SELECT 
                    dm.DM_MA,
                    dm.DM_TEN as category_name,
                    COUNT(DISTINCT sp.SP_MA) as total_products,
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
        break;

    default:
        echo json_encode(['error' => 'Invalid statistics type']);
        exit;
}

$result = mysqli_query($conn, $query);

if ($result) {
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    
    // Tính tổng số liệu
    $totalRevenue = 0;
    $totalQuantity = 0;
    foreach ($data as $row) {
        $totalRevenue += isset($row['total_revenue']) ? $row['total_revenue'] : 0;
        $totalQuantity += isset($row['total_quantity']) ? $row['total_quantity'] : 0;
    }
    
    // Thêm phần trăm doanh thu cho mỗi sản phẩm/danh mục
    if ($totalRevenue > 0) {
        foreach ($data as &$row) {
            if (isset($row['total_revenue'])) {
                $row['revenue_percentage'] = round(($row['total_revenue'] / $totalRevenue) * 100, 2);
            }
        }
    }
    
    $response['data'] = $data;
    $response['summary'] = array(
        'total_revenue' => $totalRevenue,
        'total_quantity' => $totalQuantity
    );
    $response['type'] = $type;
    $response['timeRange'] = $timeRange;
} else {
    $response['error'] = 'Failed to fetch data';
}

echo json_encode($response);
?> 