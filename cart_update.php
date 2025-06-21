<?php
// Start the session
session_start();

// Set JSON response header
header('Content-Type: application/json');

// Function to return JSON response
function sendResponse($success, $message = '', $data = []) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
    exit();
}

// Include database connection and cart functions
include 'connect.php';
include 'cart_functions.php';

// Get the user ID and cart ID
$user_id = $_SESSION['user_id'];
$cart_id = getCurrentCart($conn, $user_id);

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
    exit();
}

$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$quantity = isset($_POST['quantity']) ? (float)$_POST['quantity'] : 0;

try {
    // Kiểm tra số lượng tồn kho
    $stock_sql = "SELECT SP_SOLUONGTON FROM san_pham WHERE SP_MA = ?";
    $stock_stmt = $conn->prepare($stock_sql);
    $stock_stmt->bind_param("i", $product_id);
    $stock_stmt->execute();
    $stock_result = $stock_stmt->get_result();
    $stock_data = $stock_result->fetch_assoc();
    $stock_stmt->close();

    if ($stock_data['SP_SOLUONGTON'] < $quantity) {
        echo json_encode([
            'success' => false, 
            'message' => 'Số lượng yêu cầu vượt quá số lượng tồn kho'
        ]);
        exit();
    }

    // Lấy thông tin hiện tại của sản phẩm trong giỏ hàng
    $current_sql = "SELECT CTGH_KHOILUONG, CTGH_DONVITINH FROM chitiet_gh WHERE GH_MA = ? AND SP_MA = ?";
    $current_stmt = $conn->prepare($current_sql);
    $current_stmt->bind_param("ii", $cart_id, $product_id);
    $current_stmt->execute();
    $current_result = $current_stmt->get_result();
    $current_data = $current_result->fetch_assoc();
    $current_stmt->close();

    if ($current_data) {
        // Cập nhật số lượng
        $update_sql = "UPDATE chitiet_gh SET CTGH_KHOILUONG = ? WHERE GH_MA = ? AND SP_MA = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("dii", $quantity, $cart_id, $product_id);
        $success = $update_stmt->execute();
        $update_stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại trong giỏ hàng']);
        exit();
    }

    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không thể cập nhật số lượng']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    $conn->close();
}
?> 