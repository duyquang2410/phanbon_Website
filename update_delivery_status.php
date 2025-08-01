<?php
session_start();
require_once 'connect.php';
header('Content-Type: application/json');

try {
    if (!isset($_GET['order_id'])) {
        throw new Exception('Missing order ID');
    }

    $order_id = $_GET['order_id'];
    
    // Lấy thông tin giao hàng mới nhất
    $sql = "SELECT ds.*, hd.TT_MA 
            FROM delivery_status ds
            JOIN hoa_don hd ON ds.HD_STT = hd.HD_STT
            WHERE ds.HD_STT = ?
            ORDER BY ds.updated_at DESC LIMIT 1";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $response = [
        'success' => true,
        'updated_orders' => []
    ];
    
    if ($result->num_rows > 0) {
        $delivery = $result->fetch_assoc();
        $response['updated_orders'][] = [
            'order_id' => $order_id,
            'status' => $delivery['status'],
            'tracking_info' => $delivery['tracking_info'],
            'updated_at' => $delivery['updated_at']
        ];
    } else {
        // Nếu chưa có thông tin giao hàng, trả về trạng thái mặc định
        $response['updated_orders'][] = [
            'order_id' => $order_id,
            'status' => 'NEW',
            'tracking_info' => 'Đơn hàng mới được tạo',
            'updated_at' => date('Y-m-d H:i:s')
        ];
    }
    
    echo json_encode($response);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$conn->close();
?> 