<?php
include 'connect.php';

header('Content-Type: application/json');

$timeRange = isset($_GET['timeRange']) ? $_GET['timeRange'] : 'month';
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : null;
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : null;

$response = array();

switch ($timeRange) {
    case 'day':
        // Doanh thu theo ngày trong 30 ngày gần nhất
        $query = "SELECT 
                    DATE(HD_NGAYLAP) as date,
                    COUNT(*) as total_orders,
                    SUM(CTHD_SOLUONG) as total_products,
                    SUM(HD_TONGTIEN) as total_revenue
                 FROM hoa_don hd
                 LEFT JOIN chi_tiet_hd ct ON hd.HD_STT = ct.HD_STT
                 WHERE HD_NGAYLAP >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                    AND HD_TRANGTHAI = 'Hoàn thành'
                 GROUP BY DATE(HD_NGAYLAP)
                 ORDER BY date";
        break;

    case 'month':
        // Doanh thu theo tháng trong năm hiện tại
        $query = "SELECT 
                    DATE_FORMAT(HD_NGAYLAP, '%Y-%m') as month,
                    COUNT(*) as total_orders,
                    SUM(HD_TONGTIEN) as total_revenue,
                    AVG(HD_TONGTIEN) as avg_order_value
                 FROM hoa_don
                 WHERE YEAR(HD_NGAYLAP) = YEAR(CURDATE())
                    AND HD_TRANGTHAI = 'Hoàn thành'
                 GROUP BY month
                 ORDER BY month";
        break;

    case 'year':
        // Doanh thu theo năm
        $query = "SELECT 
                    YEAR(HD_NGAYLAP) as year,
                    COUNT(*) as total_orders,
                    SUM(HD_TONGTIEN) as total_revenue
                 FROM hoa_don
                 WHERE HD_TRANGTHAI = 'Hoàn thành'
                 GROUP BY year
                 ORDER BY year";
        break;

    case 'custom':
        // Doanh thu theo khoảng thời gian tùy chọn
        if ($startDate && $endDate) {
            $query = "SELECT 
                        DATE(HD_NGAYLAP) as date,
                        COUNT(*) as total_orders,
                        SUM(CTHD_SOLUONG) as total_products,
                        SUM(HD_TONGTIEN) as total_revenue
                     FROM hoa_don hd
                     LEFT JOIN chi_tiet_hd ct ON hd.HD_STT = ct.HD_STT
                     WHERE HD_NGAYLAP BETWEEN '$startDate' AND '$endDate'
                        AND HD_TRANGTHAI = 'Hoàn thành'
                     GROUP BY DATE(HD_NGAYLAP)
                     ORDER BY date";
        } else {
            echo json_encode(['error' => 'Start date and end date are required for custom range']);
            exit;
        }
        break;

    default:
        echo json_encode(['error' => 'Invalid time range']);
        exit;
}

$result = mysqli_query($conn, $query);

if ($result) {
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    
    // Tính tổng doanh thu và số đơn hàng
    $totalRevenue = 0;
    $totalOrders = 0;
    foreach ($data as $row) {
        $totalRevenue += isset($row['total_revenue']) ? $row['total_revenue'] : 0;
        $totalOrders += isset($row['total_orders']) ? $row['total_orders'] : 0;
    }
    
    $response['data'] = $data;
    $response['summary'] = array(
        'total_revenue' => $totalRevenue,
        'total_orders' => $totalOrders,
        'average_order_value' => $totalOrders > 0 ? $totalRevenue / $totalOrders : 0
    );
    $response['timeRange'] = $timeRange;
} else {
    $response['error'] = 'Failed to fetch data';
}

echo json_encode($response);
?> 