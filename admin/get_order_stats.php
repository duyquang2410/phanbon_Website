<?php
include 'connect.php';

header('Content-Type: application/json');

$timeRange = isset($_GET['timeRange']) ? $_GET['timeRange'] : '30days';

$response = array();

// Điều kiện thời gian
$timeCondition = "";
switch ($timeRange) {
    case '7days':
        $timeCondition = "AND HD_NGAYLAP >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
        break;
    case '30days':
        $timeCondition = "AND HD_NGAYLAP >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
        break;
    case 'thismonth':
        $timeCondition = "AND DATE_FORMAT(HD_NGAYLAP, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')";
        break;
    case 'lastmonth':
        $timeCondition = "AND DATE_FORMAT(HD_NGAYLAP, '%Y-%m') = DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH), '%Y-%m')";
        break;
    case 'thisyear':
        $timeCondition = "AND YEAR(HD_NGAYLAP) = YEAR(CURDATE())";
        break;
    default:
        $timeCondition = "AND HD_NGAYLAP >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
}

// Thống kê tổng quan
$overviewQuery = "SELECT 
                    COUNT(*) as total_orders,
                    SUM(HD_TONGTIEN) as total_revenue,
                    AVG(HD_TONGTIEN) as avg_order_value,
                    COUNT(DISTINCT KH_MA) as unique_customers
                 FROM hoa_don
                 WHERE 1=1 $timeCondition";

$overviewResult = mysqli_query($conn, $overviewQuery);
if ($overviewResult) {
    $response['overview'] = mysqli_fetch_assoc($overviewResult);
} else {
    $response['error'] = 'Failed to fetch overview data';
}

// Thống kê theo trạng thái
$statusQuery = "SELECT 
                    HD_TRANGTHAI as status,
                    COUNT(*) as count,
                    SUM(HD_TONGTIEN) as total_revenue
                 FROM hoa_don
                 WHERE 1=1 $timeCondition
                 GROUP BY HD_TRANGTHAI";

$statusResult = mysqli_query($conn, $statusQuery);
if ($statusResult) {
    $statusData = array();
    while ($row = mysqli_fetch_assoc($statusResult)) {
        $statusData[] = $row;
    }
    $response['status'] = $statusData;
} else {
    $response['error'] = 'Failed to fetch status data';
}

// Thống kê theo thời gian
$timelineQuery = "SELECT 
                    DATE(HD_NGAYLAP) as date,
                    COUNT(*) as total_orders,
                    SUM(HD_TONGTIEN) as total_revenue,
                    AVG(HD_TONGTIEN) as avg_order_value
                 FROM hoa_don
                 WHERE 1=1 $timeCondition
                 GROUP BY DATE(HD_NGAYLAP)
                 ORDER BY date";

$timelineResult = mysqli_query($conn, $timelineQuery);
if ($timelineResult) {
    $timelineData = array();
    while ($row = mysqli_fetch_assoc($timelineResult)) {
        $timelineData[] = $row;
    }
    $response['timeline'] = $timelineData;
} else {
    $response['error'] = 'Failed to fetch timeline data';
}

// Thống kê theo phương thức thanh toán
$paymentQuery = "SELECT 
                    pt.PT_TEN as payment_method,
                    COUNT(*) as total_orders,
                    SUM(hd.HD_TONGTIEN) as total_revenue
                 FROM hoa_don hd
                 JOIN phuong_thuc_thanh_toan pt ON hd.PTTT_MA = pt.PT_MA
                 WHERE 1=1 $timeCondition
                 GROUP BY pt.PT_MA, pt.PT_TEN";

$paymentResult = mysqli_query($conn, $paymentQuery);
if ($paymentResult) {
    $paymentData = array();
    while ($row = mysqli_fetch_assoc($paymentResult)) {
        $paymentData[] = $row;
    }
    $response['payment'] = $paymentData;
} else {
    $response['error'] = 'Failed to fetch payment data';
}

// Thống kê theo nhà vận chuyển
$shippingQuery = "SELECT 
                    nvc.NVC_TEN as shipping_method,
                    COUNT(*) as total_orders,
                    SUM(hd.HD_TONGTIEN) as total_revenue,
                    AVG(hd.HD_PHISHIP) as avg_shipping_fee
                 FROM hoa_don hd
                 JOIN don_van_chuyen dvc ON hd.DVC_MA = dvc.DVC_MA
                 JOIN nha_van_chuyen nvc ON dvc.NVC_MA = nvc.NVC_MA
                 WHERE 1=1 $timeCondition
                 GROUP BY nvc.NVC_MA, nvc.NVC_TEN";

$shippingResult = mysqli_query($conn, $shippingQuery);
if ($shippingResult) {
    $shippingData = array();
    while ($row = mysqli_fetch_assoc($shippingResult)) {
        $shippingData[] = $row;
    }
    $response['shipping'] = $shippingData;
} else {
    $response['error'] = 'Failed to fetch shipping data';
}

// Thống kê đơn hàng hoàn trả
$refundQuery = "SELECT 
                    COUNT(*) as total_refunds,
                    SUM(HT_SOTIEN) as total_refund_amount,
                    AVG(HT_SOTIEN) as avg_refund_amount
                 FROM hoan_tien ht
                 JOIN hoa_don hd ON ht.HD_STT = hd.HD_STT
                 WHERE ht.HT_TRANGTHAI = 'COMPLETED'
                    $timeCondition";

$refundResult = mysqli_query($conn, $refundQuery);
if ($refundResult) {
    $response['refund'] = mysqli_fetch_assoc($refundResult);
} else {
    $response['error'] = 'Failed to fetch refund data';
}

$response['timeRange'] = $timeRange;

echo json_encode($response);
?> 