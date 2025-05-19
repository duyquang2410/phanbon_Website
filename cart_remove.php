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

// Check if product ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = intval($_GET['id']);
    
    // Check if product exists in cart
    if (isProductInCart($conn, $cart_id, $product_id)) {
        // Get product name for the message
        $product_sql = "SELECT SP_TEN FROM san_pham WHERE SP_MA = ?";
        $product_stmt = $conn->prepare($product_sql);
        $product_stmt->bind_param("i", $product_id);
        $product_stmt->execute();
        $product_result = $product_stmt->get_result();
        
        if ($product_result->num_rows > 0) {
            $product = $product_result->fetch_assoc();
            $product_name = $product['SP_TEN'];
        } else {
            $product_name = "Sản phẩm";
        }
        
        $product_stmt->close();
        
        // Remove product from cart
        if (removeCartItem($conn, $cart_id, $product_id)) {
            $_SESSION['cart_success'] = "Đã xóa " . $product_name . " khỏi giỏ hàng.";
        } else {
            $_SESSION['cart_error'] = "Có lỗi xảy ra khi xóa sản phẩm khỏi giỏ hàng.";
        }
    } else {
        $_SESSION['cart_error'] = "Sản phẩm không tồn tại trong giỏ hàng.";
    }
} else {
    $_SESSION['cart_error'] = "Thông tin sản phẩm không hợp lệ.";
}

// Redirect back to cart page
header("Location: cart.php");
exit();
?> 