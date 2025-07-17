<?php
session_start();
require_once '../config.php';
require_once 'PaymenVnpayClass.php';

// Kiểm tra dữ liệu đơn hàng trong session
if (!isset($_SESSION['order_data'])) {
    $_SESSION['error_message'] = "Không tìm thấy thông tin đơn hàng";
    header("Location: ../checkout.php");
    exit();
}

// Lấy thông tin đơn hàng từ session
$order_data = $_SESSION['order_data'];
$order_id = $order_data['new_id'];
$total_amount = $order_data['total_amount'] + $order_data['shipping_fee'];

try {
    // Khởi tạo đối tượng thanh toán VNPay
    $vnpay = new payment_vnpay();
    
    // Gọi phương thức thanh toán
    $vnpay->payment_vnpay(
        $order_id,
        $total_amount,
        "Thanh toan don hang #" . $order_id
    );
} catch (Exception $e) {
    $_SESSION['error_message'] = "Lỗi khi xử lý thanh toán: " . $e->getMessage();
    header("Location: ../checkout.php");
    exit();
}
?> 