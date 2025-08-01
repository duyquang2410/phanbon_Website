<?php
// Tắt hiển thị lỗi trực tiếp
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Đảm bảo header là JSON
header('Content-Type: application/json');

// Custom error handler
function handleError($errno, $errstr, $errfile, $errline) {
    echo json_encode([
        'success' => false,
        'message' => "Lỗi hệ thống: $errstr"
    ]);
    exit;
}

// Set custom error handler
set_error_handler("handleError");

// Custom exception handler
function handleException($e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    exit;
}

// Set custom exception handler
set_exception_handler("handleException");

session_start();
require_once 'connect.php';
require_once 'create_logs.php';

// Khởi tạo logger
$logger = Logger::getInstance('logs/order_processing.log');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}

if (!isset($_SESSION['user_id']) || !isset($_POST['order_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized access'
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];
$order_id = $_POST['order_id'];

function canRequestCancelOrder($conn, $order_id, $user_id) {
    try {
        // Lấy thông tin đơn hàng
        $sql = "SELECT hd.*, TIMESTAMPDIFF(HOUR, hd.HD_NGAYLAP, NOW()) as hours_since_order,
                tt.TT_TEN as trang_thai, hd.PTTT_MA, hd.HD_MAGIAODICH, hd.HD_TONGTIEN
                FROM hoa_don hd 
                JOIN trang_thai tt ON hd.TT_MA = tt.TT_MA
                WHERE hd.HD_STT = ? AND hd.KH_MA = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $order_id, $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $order = mysqli_fetch_assoc($result);
        
        if (!$order) {
            throw new Exception('Không tìm thấy đơn hàng');
        }
        
        // 1. Kiểm tra trạng thái - cho phép yêu cầu hủy khi đơn hàng đang "Chờ xác nhận" hoặc "Chờ giao"
        if ($order['trang_thai'] !== 'Chờ xác nhận' && $order['trang_thai'] !== 'Chờ giao') {
            throw new Exception('Không thể yêu cầu hủy đơn hàng ở trạng thái này');
        }
        
        // 2. Kiểm tra thời gian (24h)
        if ($order['hours_since_order'] > 24) {
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

try {
    mysqli_begin_transaction($conn);
    
    // Kiểm tra điều kiện yêu cầu hủy đơn
    $check = canRequestCancelOrder($conn, $order_id, $user_id);
    if (!$check['success']) {
        throw new Exception($check['message']);
    }
    
    $order = $check['order'];
    $cancel_reason = $_POST['cancel_reason'] ?? 'Khách hàng yêu cầu hủy';
    
    // Cập nhật trạng thái đơn hàng sang "Chờ hủy" (TT_MA = 5)
    $sql = "UPDATE hoa_don 
            SET TT_MA = 5,
                HD_LIDOHUY = ?
            WHERE HD_STT = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $cancel_reason, $order_id);
    mysqli_stmt_execute($stmt);

    // Nếu là đơn hàng thanh toán VNPay (PTTT_MA = 2), tạo yêu cầu hoàn tiền
    if ($order['PTTT_MA'] == 2) {
        // Tạo yêu cầu hoàn tiền
        $sql = "INSERT INTO hoan_tien (
            HD_STT,
            HT_SOTIEN,
            HT_MAGIAODICH,
            HT_LYDO,
            HT_TRANGTHAI,
            HT_NGAYTAO
        ) VALUES (?, ?, ?, ?, 'PENDING', NOW())";
        
        $refund_amount = $order['HD_TONGTIEN'];
        $transaction_id = $order['HD_MAGIAODICH'];
        $refund_reason = "Hoàn tiền do hủy đơn hàng #$order_id - $cancel_reason";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "idss", $order_id, $refund_amount, $transaction_id, $refund_reason);
        mysqli_stmt_execute($stmt);

        // Thêm thông báo về hoàn tiền
        $sql = "INSERT INTO thong_bao (
            TB_LOAI,
            TB_NOIDUNG,
            TB_LINK,
            TB_DADOC,
            NV_MA
        ) VALUES (
            'HOAN_TIEN',
            ?,
            ?,
            0,
            NULL
        )";
        
        $noidung = "Yêu cầu hoàn tiền cho đơn hàng #$order_id. Số tiền: " . number_format($refund_amount, 0, ',', '.') . 'đ';
        $link = "admin/refund_detail.php?id=$order_id";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $noidung, $link);
        mysqli_stmt_execute($stmt);

        $success_message = 'Yêu cầu hủy đơn hàng đã được gửi. Số tiền sẽ được hoàn trả trong vòng 3-5 ngày làm việc.';
    } else {
        $success_message = 'Yêu cầu hủy đơn hàng đã được gửi. Vui lòng chờ xác nhận từ admin.';
    }

    // Thêm thông báo cho admin
    $sql = "INSERT INTO thong_bao (
        TB_LOAI,
        TB_NOIDUNG,
        TB_LINK,
        TB_DADOC,
        NV_MA
    ) VALUES (
        'YEU_CAU_HUY',
        ?,
        ?,
        0,
        NULL
    )";
    
    $noidung = "Đơn hàng #$order_id yêu cầu hủy. Lý do: $cancel_reason";
    $link = "admin/order_detail.php?id=$order_id";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $noidung, $link);
    mysqli_stmt_execute($stmt);

    mysqli_commit($conn);
    
    // Log thành công
    $logger->info('Order cancellation requested', [
        'order_id' => $order_id,
        'user_id' => $user_id,
        'reason' => $cancel_reason,
        'payment_method' => $order['PTTT_MA'],
        'refund_required' => ($order['PTTT_MA'] == 2)
    ]);

    echo json_encode([
        'success' => true,
        'message' => $success_message
    ]);

} catch (Exception $e) {
    mysqli_rollback($conn);
    
    // Log lỗi
    $logger->error('Error requesting order cancellation', [
        'order_id' => $order_id,
        'user_id' => $user_id,
        'error' => $e->getMessage()
    ]);
    
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?> 