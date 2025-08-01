<?php
header('Content-Type: application/json');
require_once 'connect.php';

try {
    // Lấy các đơn hàng đang trong quá trình giao
    $sql = "SELECT * FROM delivery_status WHERE status IN ('NEW', 'DELIVERING')";
    $result = $conn->query($sql);
    
    $updated_orders = [];
    $current_time = date('Y-m-d H:i:s'); // Lấy thời gian hiện tại

    while ($order = $result->fetch_assoc()) {
        $order_id = $order['HD_STT'];
        $current_status = $order['status'];
        
        // Debug log
        error_log("Processing Order #$order_id - Current status: $current_status");
        
        // Cập nhật trạng thái dựa trên thời gian
        $new_status = $current_status;
        $tracking_info = $order['tracking_info'];
        
        if ($current_status === 'NEW') {
            // Chuyển sang DELIVERING ngay lập tức
            $new_status = 'DELIVERING';
            $tracking_info .= "\n" . $current_time . " - Đơn hàng đang được giao đến khách hàng";
            error_log("Order #$order_id - Changing to DELIVERING");
        } 
        else if ($current_status === 'DELIVERING') {
            // Chuyển sang DELIVERED sau 10 giây
            $delivering_time = strtotime($order['updated_at']);
            $time_diff = time() - $delivering_time;
            
            error_log("Order #$order_id - Time in DELIVERING state: $time_diff seconds");
            
            if ($time_diff >= 10) {
                $new_status = 'DELIVERED';
                $tracking_info .= "\n" . $current_time . " - Đơn hàng đã được giao thành công";
                error_log("Order #$order_id - Changing to DELIVERED");
                
                // Cập nhật trạng thái trong bảng hoa_don
                $update_order_sql = "UPDATE hoa_don SET TT_MA = 3 WHERE HD_STT = ?"; // 3 là mã trạng thái "Đã giao"
                $stmt = $conn->prepare($update_order_sql);
                $stmt->bind_param("i", $order_id);
                if (!$stmt->execute()) {
                    error_log("Order #$order_id - Failed to update hoa_don status: " . $stmt->error);
                }
            }
        }
        
        // Cập nhật trạng thái mới nếu có thay đổi
        if ($new_status !== $current_status) {
            $update_sql = "UPDATE delivery_status SET status = ?, tracking_info = ?, updated_at = ? WHERE HD_STT = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("sssi", $new_status, $tracking_info, $current_time, $order_id);
            
            if ($stmt->execute()) {
                $updated_orders[] = [
                    'order_id' => $order_id,
                    'status' => $new_status,
                    'tracking_info' => $tracking_info,
                    'updated_at' => $current_time
                ];
                error_log("Order #$order_id - Successfully updated to $new_status");
            } else {
                error_log("Order #$order_id - Failed to update status: " . $stmt->error);
            }
        }
    }
    
    echo json_encode([
        'success' => true,
        'updated_orders' => $updated_orders,
        'timestamp' => $current_time,
        'message' => count($updated_orders) . ' orders updated'
    ]);
    
} catch (Exception $e) {
    error_log("Error in update_delivery_status.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?> 