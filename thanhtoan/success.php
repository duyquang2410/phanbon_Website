<?php
session_start();
require_once '../config.php';
require_once '../connect.php';
require_once 'PaymenVnpayClass.php';

// Khởi tạo đối tượng VNPay
$vnpay = new payment_vnpay();

// Log file
$log_file = dirname(__DIR__) . '/logs/vnpay_payment.log';

function writeLog($message) {
    global $log_file;
    $log_entry = date('Y-m-d H:i:s') . " - " . $message . "\n";
    file_put_contents($log_file, $log_entry, FILE_APPEND);
}

writeLog("Received VNPay response: " . json_encode($_GET));

// Kiểm tra và xác thực dữ liệu từ VNPay
if ($vnpay->verifyResponse($_GET)) {
    writeLog("VNPay response verification successful");
    
    // Lấy thông tin từ VNPay
    $vnp_ResponseCode = $_GET['vnp_ResponseCode'];
    $vnp_TxnRef = $_GET['vnp_TxnRef']; // Mã đơn hàng
    $vnp_Amount = $_GET['vnp_Amount']; // Số tiền thanh toán (đã nhân 100)
    $vnp_TransactionNo = $_GET['vnp_TransactionNo']; // Mã giao dịch tại VNPay
    
    writeLog("Response Code: " . $vnp_ResponseCode);
    writeLog("Transaction Ref: " . $vnp_TxnRef);
    writeLog("Transaction No: " . $vnp_TransactionNo);
    
    // Kiểm tra trạng thái thanh toán
    if ($vnp_ResponseCode == '00') {
        writeLog("Payment successful, updating order status");
        try {
            // Cập nhật trạng thái đơn hàng trong database thành "Chờ giao"
            $sql = "UPDATE hoa_don SET TT_MA = 6, HD_MAGIAODICH = ? WHERE HD_STT = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $vnp_TransactionNo, $vnp_TxnRef);
            
            if ($stmt->execute()) {
                writeLog("Order status updated successfully");
                $_SESSION['success_message'] = "Thanh toán thành công! Cảm ơn bạn đã mua hàng.";
                
                // Đảm bảo không có output trước khi chuyển hướng
                ob_clean();
                writeLog("Redirecting to success_result.php with order_id: " . $vnp_TxnRef);
                header("Location: success_result.php?order_id=" . $vnp_TxnRef);
                exit();
            } else {
                throw new Exception("Không thể cập nhật trạng thái đơn hàng: " . $conn->error);
            }
        } catch (Exception $e) {
            writeLog("Error updating order status: " . $e->getMessage());
            $_SESSION['error_message'] = "Lỗi xử lý thanh toán: " . $e->getMessage();
            header("Location: ../checkout.php");
            exit();
        }
    } else {
        writeLog("Payment failed with response code: " . $vnp_ResponseCode);
        // Thanh toán thất bại hoặc bị hủy
        $_SESSION['error_message'] = "Thanh toán không thành công hoặc đã bị hủy";
        header("Location: failure_result.php?order_id=" . $vnp_TxnRef);
        exit();
    }
} else {
    writeLog("Invalid VNPay response data");
    // Dữ liệu không hợp lệ
    $_SESSION['error_message'] = "Dữ liệu không hợp lệ hoặc đã bị chỉnh sửa";
    header("Location: ../checkout.php");
    exit();
}
?>