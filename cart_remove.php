<?php
// Start the session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Vui lòng đăng nhập để thực hiện chức năng này.";
    header("Location: login.php");
    exit();
}

// Include database connection and cart functions
include 'connect.php';
include 'cart_functions.php';
include 'cart_selection.php';

// Get the user ID and product ID
$user_id = $_SESSION['user_id'];
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$product_id) {
    $_SESSION['error_message'] = "Không tìm thấy sản phẩm cần xóa.";
    header("Location: cart.php");
    exit();
}

try {
    $conn->begin_transaction();

    // Get the current cart ID
    $cart_id = getCurrentCart($conn, $user_id);

    // Remove product from selected_cart_items table
    $delete_selection_sql = "DELETE FROM selected_cart_items WHERE GH_MA = ? AND SP_MA = ?";
    $delete_selection_stmt = $conn->prepare($delete_selection_sql);
    $delete_selection_stmt->bind_param("ii", $cart_id, $product_id);
    $delete_selection_stmt->execute();
    $delete_selection_stmt->close();

    // Remove product from cart
    $delete_cart_sql = "DELETE FROM chitiet_gh WHERE GH_MA = ? AND SP_MA = ?";
    $delete_cart_stmt = $conn->prepare($delete_cart_sql);
    $delete_cart_stmt->bind_param("ii", $cart_id, $product_id);
    
    if ($delete_cart_stmt->execute()) {
        $conn->commit();
        $_SESSION['success_message'] = "Đã xóa sản phẩm khỏi giỏ hàng.";
    } else {
        throw new Exception("Không thể xóa sản phẩm khỏi giỏ hàng.");
    }
    
    $delete_cart_stmt->close();

} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['error_message'] = $e->getMessage();
} finally {
    $conn->close();
}

// Redirect back to cart page
header("Location: cart.php");
exit();
?> 