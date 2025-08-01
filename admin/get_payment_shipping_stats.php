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

// Thống kê theo phương thức thanh toán
$paymentQuery = "SELECT 
                    pt.PT_MA,
                    pt.PT_TEN as payment_method,
                    COUNT(*) as total_orders,
                    SUM(hd.HD_TONGTIEN) as total_revenue,
                    AVG(hd.HD_TONGTIEN) as avg_order_value
                 FROM phuong_thuc_thanh_toan pt
                 LEFT JOIN hoa_don hd ON pt.PT_MA = hd.PTTT_MA
                    AND hd.HD_TRANGTHAI = 'Hoàn thành'
                    $timeCondition
                 GROUP BY pt.PT_MA, pt.PT_TEN
                 ORDER BY total_revenue DESC";

$paymentResult = mysqli_query($conn, $paymentQuery);

if ($paymentResult) {
    $paymentData = array();
    $totalPaymentRevenue = 0;
    
    // First pass: calculate total revenue
    while ($row = mysqli_fetch_assoc($paymentResult)) {
        $totalPaymentRevenue += $row['total_revenue'];
        $paymentData[] = $row;
    }
    
    // Second pass: calculate percentages
    foreach ($paymentData as &$payment) {
        $payment['revenue_percentage'] = $totalPaymentRevenue > 0 ? 
            round(($payment['total_revenue'] / $totalPaymentRevenue) * 100, 2) : 0;
    }
    
    $response['payment'] = array(
        'data' => $paymentData,
        'total' => $totalPaymentRevenue
    );
}

// Thống kê theo nhà vận chuyển
$shippingQuery = "SELECT 
                    nvc.NVC_MA,
                    nvc.NVC_TEN as shipping_method,
                    COUNT(*) as total_orders,
                    SUM(hd.HD_TONGTIEN) as total_revenue,
                    SUM(hd.HD_PHISHIP) as total_shipping_fee,
                    AVG(hd.HD_PHISHIP) as avg_shipping_fee
                 FROM nha_van_chuyen nvc
                 LEFT JOIN don_van_chuyen dvc ON nvc.NVC_MA = dvc.NVC_MA
                 LEFT JOIN hoa_don hd ON dvc.DVC_MA = hd.DVC_MA
                    AND hd.HD_TRANGTHAI = 'Hoàn thành'
                    $timeCondition
                 GROUP BY nvc.NVC_MA, nvc.NVC_TEN
                 ORDER BY total_revenue DESC";

$shippingResult = mysqli_query($conn, $shippingQuery);

if ($shippingResult) {
    $shippingData = array();
    $totalShippingRevenue = 0;
    $totalShippingFee = 0;
    
    // First pass: calculate totals
    while ($row = mysqli_fetch_assoc($shippingResult)) {
        $totalShippingRevenue += $row['total_revenue'];
        $totalShippingFee += $row['total_shipping_fee'];
        $shippingData[] = $row;
    }
    
    // Second pass: calculate percentages
    foreach ($shippingData as &$shipping) {
        $shipping['revenue_percentage'] = $totalShippingRevenue > 0 ? 
            round(($shipping['total_revenue'] / $totalShippingRevenue) * 100, 2) : 0;
        $shipping['shipping_fee_percentage'] = $totalShippingFee > 0 ? 
            round(($shipping['total_shipping_fee'] / $totalShippingFee) * 100, 2) : 0;
    }
    
    $response['shipping'] = array(
        'data' => $shippingData,
        'total_revenue' => $totalShippingRevenue,
        'total_shipping_fee' => $totalShippingFee
    );
}

// Thống kê theo thời gian
$timelineQuery = "SELECT 
                    DATE_FORMAT(hd.HD_NGAYLAP, '%Y-%m') as month,
                    pt.PT_MA,
                    pt.PT_TEN as payment_method,
                    COUNT(*) as total_orders,
                    SUM(hd.HD_TONGTIEN) as total_revenue
                 FROM phuong_thuc_thanh_toan pt
                 LEFT JOIN hoa_don hd ON pt.PT_MA = hd.PTTT_MA
                    AND hd.HD_TRANGTHAI = 'Hoàn thành'
                    AND hd.HD_NGAYLAP >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                 GROUP BY month, pt.PT_MA, pt.PT_TEN
                 ORDER BY month, pt.PT_MA";

$timelineResult = mysqli_query($conn, $timelineQuery);

if ($timelineResult) {
    $timelineData = array();
    while ($row = mysqli_fetch_assoc($timelineResult)) {
        if (!isset($timelineData[$row['payment_method']])) {
            $timelineData[$row['payment_method']] = array(
                'name' => $row['payment_method'],
                'data' => array()
            );
        }
        $timelineData[$row['payment_method']]['data'][$row['month']] = array(
            'orders' => $row['total_orders'],
            'revenue' => $row['total_revenue']
        );
    }
    $response['timeline'] = $timelineData;
}

// Thống kê tỷ lệ hoàn thành giao hàng
$deliveryQuery = "SELECT 
                    ds.status,
                    COUNT(*) as total_orders,
                    AVG(TIMESTAMPDIFF(HOUR, hd.HD_NGAYLAP, ds.updated_at)) as avg_delivery_time
                 FROM delivery_status ds
                 JOIN hoa_don hd ON ds.HD_STT = hd.HD_STT
                    $timeCondition
                 GROUP BY ds.status";

$deliveryResult = mysqli_query($conn, $deliveryQuery);

if ($deliveryResult) {
    $deliveryData = array();
    while ($row = mysqli_fetch_assoc($deliveryResult)) {
        $deliveryData[] = $row;
    }
    $response['delivery'] = $deliveryData;
}

$response['timeRange'] = $timeRange;

echo json_encode($response);
?> 