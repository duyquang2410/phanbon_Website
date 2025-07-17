<?php
session_start();
require_once '../config.php';
require_once 'PaymenVnpayClass.php';

// Kiểm tra tham số đầu vào
if (!isset($_GET['order_id']) || !isset($_GET['amount'])) {
    $_SESSION['error_message'] = "Thiếu thông tin đơn hàng";
    header("Location: ../checkout.php");
    exit();
}

$order_id = $_GET['order_id'];
$amount = $_GET['amount'];

try {
    // Khởi tạo đối tượng thanh toán VNPay
    $vnpay = new payment_vnpay();
    
    // Gọi phương thức thanh toán
    $vnpay->payment_vnpay(
        $order_id,
        $amount,
        "Thanh toan don hang #" . $order_id
    );
} catch (Exception $e) {
    $_SESSION['error_message'] = "Lỗi khi xử lý thanh toán: " . $e->getMessage();
    header("Location: ../checkout.php");
    exit();
}
?> 