<?php
session_start();
include 'connect.php';
include 'cart_functions.php';

header('Content-Type: application/json');

// Hàm ghi log
function logPromoCheck($message, $data = null) {
    $log = date('Y-m-d H:i:s') . " - " . $message;
    if ($data !== null) {
        $log .= "\nData: " . print_r($data, true);
    }
    $log .= "\n\n";
    error_log($log, 3, "logs/promo_errors.log");
}

// Xử lý input từ cả JSON và form-data
$input = file_get_contents('php://input');
$jsonData = json_decode($input, true);

if ($jsonData !== null) {
    // Nếu là JSON request
    $promo_code = isset($jsonData['promo_code']) ? trim($jsonData['promo_code']) : null;
    $product_id = isset($jsonData['product_id']) ? (int)$jsonData['product_id'] : null;
    $amount = isset($jsonData['total_amount']) ? (float)$jsonData['total_amount'] : 0;
} else {
    // Nếu là form-data
    $promo_code = isset($_POST['promo_code']) ? trim($_POST['promo_code']) : null;
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : null;
    $amount = isset($_POST['amount']) ? (float)$_POST['amount'] : 0;
}

if (!$promo_code) {
    logPromoCheck("Missing promo code in request", $jsonData ?? $_POST);
    echo json_encode(['success' => false, 'message' => 'Vui lòng nhập mã khuyến mãi']);
    exit;
}

logPromoCheck("Checking promo code", [
    'code' => $promo_code,
    'product_id' => $product_id,
    'amount' => $amount
]);

try {
    // Kiểm tra mã khuyến mãi trong database
    $sql = "SELECT * FROM khuyen_mai WHERE Code = ? AND KM_TGBD <= NOW() AND KM_TGKT >= NOW()";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Lỗi prepare statement: " . $conn->error);
    }

    $stmt->bind_param('s', $promo_code);
    
    if (!$stmt->execute()) {
        throw new Exception("Lỗi execute statement: " . $stmt->error);
    }

    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        logPromoCheck("Invalid or expired promo code", ['code' => $promo_code]);
        echo json_encode([
            'success' => false,
            'message' => 'Mã khuyến mãi không hợp lệ hoặc đã hết hạn'
        ]);
        exit;
    }

    $promo = $result->fetch_assoc();
    logPromoCheck("Promo code found", $promo);

    // Tính toán giảm giá
    $discount_amount = 0;
    $discount_percent = 0;
    if ($promo['hinh_thuc_km'] === 'Giảm phần trăm') {
        $discount_percent = $promo['KM_GIATRI'];
        $discount_amount = $amount * ($promo['KM_GIATRI'] / 100);
    } elseif ($promo['hinh_thuc_km'] === 'Giảm trực tiếp') {
        $discount_amount = min($promo['KM_GIATRI'], $amount);
    }

    // Kiểm tra thêm điều kiện cho sản phẩm cụ thể nếu cần
    if ($product_id) {
        logPromoCheck("Checking product-specific conditions", [
            'product_id' => $product_id,
            'discount_amount' => $discount_amount
        ]);
    }

    $response = [
        'success' => true,
        'discount_percent' => $discount_percent,
        'discount' => $discount_amount,
        'message' => sprintf(
            'Áp dụng mã giảm giá thành công: %s %s',
            $promo['hinh_thuc_km'] === 'Giảm phần trăm' ? $promo['KM_GIATRI'] . '%' : number_format($discount_amount, 0, ',', '.') . 'đ',
            $product_id ? 'cho sản phẩm' : 'cho đơn hàng'
        )
    ];

    logPromoCheck("Promo code applied successfully", $response);
    echo json_encode($response);

} catch (Exception $e) {
    logPromoCheck("Error processing promo code", [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
    
    echo json_encode([
        'success' => false,
        'message' => 'Có lỗi xảy ra khi kiểm tra mã khuyến mãi: ' . $e->getMessage()
    ]);
} 