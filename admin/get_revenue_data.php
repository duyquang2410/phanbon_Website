<?php
require_once 'connect.php';

// Hàm lấy doanh thu theo tháng trong năm hiện tại
function getMonthlyRevenue($conn) {
    $sql = "SELECT 
            MONTH(hd.HD_NGAYLAP) as month,
            YEAR(hd.HD_NGAYLAP) as year,
            COALESCE(SUM(hd.HD_TONGTIEN), 0) as revenue
            FROM hoa_don hd 
            WHERE YEAR(hd.HD_NGAYLAP) = YEAR(CURRENT_DATE)
            AND hd.TT_MA = 4
            GROUP BY MONTH(hd.HD_NGAYLAP), YEAR(hd.HD_NGAYLAP)
            ORDER BY year, month";
    
    $result = $conn->query($sql);
    $monthlyData = array_fill(1, 12, 0); // Khởi tạo mảng 12 tháng với giá trị 0
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $monthlyData[$row['month']] = (float)$row['revenue'];
        }
    }
    
    return array_values($monthlyData); // Chuyển về mảng tuần tự
}

header('Content-Type: application/json');

try {
    // Lấy tham số từ request
    $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-d', strtotime('-30 days'));
    $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-d');
    $stats_type = isset($_POST['stats_type']) ? $_POST['stats_type'] : 'day';

    // Tạo câu query dựa trên kiểu thống kê
    switch($stats_type) {
        case 'day':
            $sql = "SELECT 
                    DATE(HD_NGAYLAP) as date,
                    COUNT(DISTINCT HD_STT) as total_orders,
                    SUM(HD_TONGTIEN) as revenue 
                    FROM hoa_don 
                    WHERE TT_MA = 4 
                    AND HD_NGAYLAP BETWEEN ? AND ?
                    GROUP BY DATE(HD_NGAYLAP)
                    ORDER BY date";
            break;
        case 'month':
            $sql = "SELECT 
                    DATE_FORMAT(HD_NGAYLAP, '%Y-%m-01') as date,
                    COUNT(DISTINCT HD_STT) as total_orders,
                    SUM(HD_TONGTIEN) as revenue 
                    FROM hoa_don 
                    WHERE TT_MA = 4 
                    AND HD_NGAYLAP BETWEEN ? AND ?
                    GROUP BY DATE_FORMAT(HD_NGAYLAP, '%Y-%m-01')
                    ORDER BY date";
            break;
        case 'year':
            $sql = "SELECT 
                    DATE_FORMAT(HD_NGAYLAP, '%Y-01-01') as date,
                    COUNT(DISTINCT HD_STT) as total_orders,
                    SUM(HD_TONGTIEN) as revenue 
                    FROM hoa_don 
                    WHERE TT_MA = 4
                    AND HD_NGAYLAP BETWEEN ? AND ?
                    GROUP BY DATE_FORMAT(HD_NGAYLAP, '%Y-01-01')
                    ORDER BY date";
            break;
        default:
            throw new Exception('Invalid stats type');
    }

    // Thực thi query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();

    // Chuẩn bị dữ liệu cho biểu đồ
    $data = [
        'labels' => [],
        'values' => [],
        'total_orders' => [],
        'dates' => [] // Thêm mảng dates để lưu ngày gốc
    ];

    // Xử lý dữ liệu
    while ($row = $result->fetch_assoc()) {
        // Lưu ngày gốc
        $data['dates'][] = $row['date'];
        
        // Format ngày hiển thị theo kiểu thống kê
        switch($stats_type) {
            case 'day':
                $label = date('d/m/Y', strtotime($row['date']));
                break;
            case 'month':
                $label = date('m/Y', strtotime($row['date']));
                break;
            case 'year':
                $label = date('Y', strtotime($row['date']));
                break;
        }
        
        $data['labels'][] = $label;
        $data['values'][] = (float)$row['revenue'];
        $data['total_orders'][] = (int)$row['total_orders'];
    }

    // Thêm dữ liệu doanh thu theo tháng vào response
    $data['monthly_revenue'] = getMonthlyRevenue($conn);

    // Thêm thông tin bổ sung
    $data['stats_type'] = $stats_type;
    $data['start_date'] = $start_date;
    $data['end_date'] = $end_date;

    echo json_encode($data);

} catch (Exception $e) {
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}
?> 