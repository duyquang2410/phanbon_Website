<?php
session_start();
include 'connect.php';
include 'cart_selection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$is_selected = isset($_POST['is_selected']) ? (bool)$_POST['is_selected'] : false;

try {
    $conn->begin_transaction();

    // Lấy ID giỏ hàng hiện tại
    $sql = "SELECT GH_MA FROM gio_hang WHERE KH_MA = ? ORDER BY GH_MA DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cart = $result->fetch_assoc();
    $cart_id = $cart['GH_MA'];
    $stmt->close();

    // Thêm hoặc xóa sản phẩm khỏi bảng selected_cart_items
    if ($is_selected) {
        $res = selectCartItem($conn, $cart_id, $product_id);
    } else {
        $res = unselectCartItem($conn, $cart_id, $product_id);
    }

    $conn->commit();

    if ($res['success']) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $res['message'] ?? 'Không thể cập nhật lựa chọn sản phẩm']);
    }

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    $conn->close();
}
?> 