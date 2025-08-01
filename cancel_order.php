<?php
session_start();
require_once 'connect.php';

function canCancelOrder($order_id) {
    global $conn;
    
    try {
        // Lấy thông tin đơn hàng
        $sql = "SELECT * FROM hoa_don WHERE HD_STT = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $order_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $order = mysqli_fetch_assoc($result);
        
        if (!$order) {
            throw new Exception('Không tìm thấy đơn hàng');
        }
        
        // 1. Kiểm tra trạng thái
        $allowed_statuses = [1, 6]; // 1: Chờ xác nhận, 6: Chờ giao
        if (!in_array($order['TT_MA'], $allowed_statuses)) {
            throw new Exception('Không thể hủy đơn hàng ở trạng thái này');
        }
        
        // 2. Kiểm tra thời gian
        $order_time = strtotime($order['HD_NGAYLAP']);
        $current_time = time();
        $time_diff = $current_time - $order_time;
        
        if ($time_diff > 24 * 60 * 60) { // 24 giờ
            throw new Exception('Đã quá thời hạn hủy đơn (24h kể từ khi đặt)');
        }
        
        return [
            'success' => true,
            'order' => $order
        ];
        
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    
    // Kiểm tra điều kiện hủy đơn
    $check = canCancelOrder($order_id);
    if (!$check['success']) {
        $_SESSION['error'] = $check['message'];
        header('Location: my_orders.php');
        exit;
    }
    
    $order = $check['order'];
    
    try {
        // Bắt đầu transaction
        mysqli_begin_transaction($conn);
        
        // Cập nhật trạng thái đơn hàng thành "Chờ hủy" (TT_MA = 5)
        $update_sql = "UPDATE hoa_don SET TT_MA = 5 WHERE HD_STT = ?";
        $stmt = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($stmt, "i", $order_id);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Không thể cập nhật trạng thái đơn hàng");
        }
        
        // Nếu đã thanh toán online thì tạo yêu cầu hoàn tiền
        if ($order['PTTT_MA'] == 2 || $order['PTTT_MA'] == 3) { // Momo hoặc VNPay
            $refund_sql = "INSERT INTO hoan_tien (HD_STT, HT_SOTIEN, HT_LYDO, HT_TRANGTHAI, HT_NGAYTAO) 
                          VALUES (?, ?, ?, 'PENDING', NOW())";
            $stmt = mysqli_prepare($conn, $refund_sql);
            $reason = 'Hoàn tiền do hủy đơn #' . $order_id;
            mysqli_stmt_bind_param($stmt, "ids", $order_id, $order['HD_TONGTIEN'], $reason);
            
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Không thể tạo yêu cầu hoàn tiền");
            }
        }
        
        // Ghi log
        $log_sql = "INSERT INTO logs (LOG_TYPE, LOG_ACTION, LOG_CONTENT, LOG_TIME) 
                    VALUES ('ORDER', 'CANCEL_REQUEST', ?, NOW())";
        $log_content = "Khách hàng yêu cầu hủy đơn hàng #" . $order_id;
        $stmt = mysqli_prepare($conn, $log_sql);
        mysqli_stmt_bind_param($stmt, "s", $log_content);
        mysqli_stmt_execute($stmt);
        
        // Commit transaction
        mysqli_commit($conn);
        
        $_SESSION['success'] = "Yêu cầu hủy đơn hàng đã được gửi và đang chờ xác nhận";
        header('Location: my_orders.php');
        exit;
        
    } catch (Exception $e) {
        // Rollback nếu có lỗi
        mysqli_rollback($conn);
        $_SESSION['error'] = $e->getMessage();
        header('Location: my_orders.php');
        exit;
    }
}

header('Location: my_orders.php');
exit;
?> 