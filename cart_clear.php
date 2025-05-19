<?php
// Start the session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['login_required_message'] = "Vui lòng đăng nhập để thực hiện chức năng này.";
    header("Location: login.php");
    exit();
}

// Include database connection and cart functions
include 'connect.php';
include 'cart_functions.php';

// Get the user ID and cart ID
$user_id = $_SESSION['user_id'];
$cart_id = getCurrentCart($conn, $user_id);

// Clear cart
if (clearCart($conn, $cart_id)) {
    $_SESSION['cart_success'] = "Đã xóa toàn bộ sản phẩm trong giỏ hàng.";
} else {
    $_SESSION['cart_error'] = "Có lỗi xảy ra khi xóa giỏ hàng.";
}

// Remove promo code if exists
if (isset($_SESSION['promo_code'])) {
    unset($_SESSION['promo_code']);
    unset($_SESSION['promo_id']);
    unset($_SESSION['promo_value']);
}

// Redirect back to cart page
header("Location: cart.php");
exit();
?> 