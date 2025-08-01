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
        // Khách hàng có giá trị cao nhất
        $query = "SELECT 
                    kh.KH_MA,
                    kh.KH_TEN,
                    kh.KH_EMAIL,
                    COUNT(DISTINCT hd.HD_STT) as total_orders,
                    SUM(hd.HD_TONGTIEN) as total_spent,
                    MAX(hd.HD_NGAYLAP) as last_order_date
                 FROM khach_hang kh
                 LEFT JOIN hoa_don hd ON kh.KH_MA = hd.KH_MA
                    AND hd.HD_TRANGTHAI = 'Hoàn thành'
                    $timeCondition
                 GROUP BY kh.KH_MA, kh.KH_TEN, kh.KH_EMAIL
                 HAVING total_orders > 0
                 ORDER BY total_spent DESC
                 LIMIT $limit";
        break;

    case 'new':
        // Khách hàng mới
        $query = "SELECT 
                    kh.KH_MA,
                    kh.KH_TEN,
                    kh.KH_EMAIL,
                    kh.KH_NGAYDK as registration_date,
                    COUNT(DISTINCT hd.HD_STT) as total_orders,
                    SUM(hd.HD_TONGTIEN) as total_spent
                 FROM khach_hang kh
                 LEFT JOIN hoa_don hd ON kh.KH_MA = hd.KH_MA
                    AND hd.HD_TRANGTHAI = 'Hoàn thành'
                 WHERE kh.KH_NGAYDK >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                 GROUP BY kh.KH_MA, kh.KH_TEN, kh.KH_EMAIL, kh.KH_NGAYDK
                 ORDER BY kh.KH_NGAYDK DESC
                 LIMIT $limit";
        break;

    case 'frequency':
        // Phân tích tần suất mua hàng
        $query = "SELECT 
                    frequency_group,
                    COUNT(*) as customer_count
                 FROM (
                     SELECT 
                         kh.KH_MA,
                         CASE 
                             WHEN COUNT(DISTINCT hd.HD_STT) >= 5 THEN 'Thường xuyên'
                             WHEN COUNT(DISTINCT hd.HD_STT) >= 2 THEN 'Thỉnh thoảng'
                             WHEN COUNT(DISTINCT hd.HD_STT) = 1 THEN 'Một lần'
                             ELSE 'Chưa mua'
                         END as frequency_group
                     FROM khach_hang kh
                     LEFT JOIN hoa_don hd ON kh.KH_MA = hd.KH_MA
                        AND hd.HD_TRANGTHAI = 'Hoàn thành'
                        $timeCondition
                     GROUP BY kh.KH_MA
                 ) frequency_analysis
                 GROUP BY frequency_group
                 ORDER BY 
                    CASE frequency_group
                        WHEN 'Thường xuyên' THEN 1
                        WHEN 'Thỉnh thoảng' THEN 2
                        WHEN 'Một lần' THEN 3
                        ELSE 4
                    END";
        break;

    case 'overview':
        // Thống kê tổng quan khách hàng
        $query = "SELECT 
                    COUNT(*) as total_customers,
                    COUNT(DISTINCT hd.KH_MA) as active_customers,
                    COUNT(DISTINCT CASE WHEN kh.KH_NGAYDK >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN kh.KH_MA END) as new_customers,
                    AVG(customer_total.total_spent) as avg_customer_value
                 FROM khach_hang kh
                 LEFT JOIN hoa_don hd ON kh.KH_MA = hd.KH_MA
                    AND hd.HD_TRANGTHAI = 'Hoàn thành'
                    $timeCondition
                 LEFT JOIN (
                     SELECT 
                         KH_MA,
                         SUM(HD_TONGTIEN) as total_spent
                     FROM hoa_don
                     WHERE HD_TRANGTHAI = 'Hoàn thành'
                        $timeCondition
                     GROUP BY KH_MA
                 ) customer_total ON kh.KH_MA = customer_total.KH_MA";
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
    
    // Tính tổng chi tiêu và số đơn hàng
    if ($type === 'top' || $type === 'new') {
        $totalSpent = 0;
        $totalOrders = 0;
        foreach ($data as $row) {
            $totalSpent += isset($row['total_spent']) ? $row['total_spent'] : 0;
            $totalOrders += isset($row['total_orders']) ? $row['total_orders'] : 0;
        }
        
        $response['summary'] = array(
            'total_spent' => $totalSpent,
            'total_orders' => $totalOrders,
            'avg_order_value' => $totalOrders > 0 ? $totalSpent / $totalOrders : 0
        );
    }
    
    $response['data'] = $data;
    $response['type'] = $type;
    $response['timeRange'] = $timeRange;
} else {
    $response['error'] = 'Failed to fetch data';
}

echo json_encode($response);
?> 