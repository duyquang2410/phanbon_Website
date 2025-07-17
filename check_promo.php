<?php
session_start();
include 'connect.php';
include 'cart_functions.php';

header('Content-Type: application/json');

// Hàm ghi log
function logPromoCheck($message, $data = null) {
    try {
        $log_dir = __DIR__ . '/logs';
        if (!file_exists($log_dir)) {
            mkdir($log_dir, 0777, true);
        }

        $log_file = $log_dir . '/promo_errors.log';
        
        $log = date('Y-m-d H:i:s') . " - " . $message;
        if ($data !== null) {
            if (is_array($data)) {
                $log .= "\nData: " . print_r($data, true);
            } else {
                $log .= "\nData: " . strval($data);
            }
        }
        $log .= "\n\n";
        
        if (!file_put_contents($log_file, $log, FILE_APPEND | LOCK_EX)) {
            error_log("Failed to write to promo_errors.log");
        }
    } catch (Exception $e) {
        error_log("Error in logPromoCheck: " . $e->getMessage());
    }
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
    logPromoCheck("Missing promo code in request", ['post_data' => $_POST, 'json_data' => $jsonData]);
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
    $sql = "SELECT * FROM khuyen_mai WHERE Code = ? AND KM_TGBD <= NOW() AND KM_TGKT >= NOW() AND KM_TRANGTHAI = 1";
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

    // Kiểm tra điều kiện tối thiểu
    if ($amount < $promo['KM_DKSD']) {
        logPromoCheck("Order value too low", [
            'required' => $promo['KM_DKSD'],
            'current' => $amount
        ]);
        echo json_encode([
            'success' => false,
            'message' => 'Giá trị đơn hàng chưa đủ điều kiện áp dụng. Tối thiểu ' . number_format($promo['KM_DKSD'], 0, ',', '.') . 'đ'
        ]);
        exit;
    }

    // Tính toán giảm giá
    $discount_amount = 0;
    $discount_percent = 0;
    if ($promo['hinh_thuc_km'] === 'percent') {
        $discount_percent = min(100, max(0, $promo['KM_GIATRI'])); // Ensure valid percentage
        // Giới hạn giảm giá tối đa là 100% giá trị đơn hàng và làm tròn
        $discount_amount = round(min($amount, $amount * ($discount_percent / 100)));
    } elseif ($promo['hinh_thuc_km'] === 'fixed') {
        $discount_amount = min($promo['KM_GIATRI'], $amount);
    }

    // Trả về kết quả
    echo json_encode([
        'success' => true,
        'message' => 'Áp dụng mã khuyến mãi thành công!',
        'discount' => $discount_amount,
        'discount_type' => $promo['hinh_thuc_km'],
        'percent_value' => $discount_percent,
        'final_amount' => $amount - $discount_amount
    ]);

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