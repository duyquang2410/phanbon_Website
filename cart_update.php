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

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    
    // Check if product exists
    $check_sql = "SELECT SP_MA, SP_TEN, SP_SOLUONGTON FROM san_pham WHERE SP_MA = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $product_id);
    $check_stmt->execute();
    $product_result = $check_stmt->get_result();
    
    if ($product_result->num_rows === 0) {
        $_SESSION['cart_error'] = "Sản phẩm không tồn tại.";
        $check_stmt->close();
        header("Location: cart.php");
        exit();
    }
    
    $product = $product_result->fetch_assoc();
    $check_stmt->close();
    
    // Process different update actions
    if (isset($_POST['increase'])) {
        // Get current quantity
        $current_sql = "SELECT CTGH_KHOILUONG FROM chitiet_gh WHERE GH_MA = ? AND SP_MA = ?";
        $current_stmt = $conn->prepare($current_sql);
        $current_stmt->bind_param("ii", $cart_id, $product_id);
        $current_stmt->execute();
        $current_result = $current_stmt->get_result();
        
        if ($current_result->num_rows > 0) {
            $current_item = $current_result->fetch_assoc();
            $new_quantity = $current_item['CTGH_KHOILUONG'] + 1;
            
            // Check if new quantity exceeds stock
            if ($new_quantity > $product['SP_SOLUONGTON']) {
                $_SESSION['cart_error'] = "Số lượng sản phẩm trong kho không đủ. Hiện chỉ còn " . $product['SP_SOLUONGTON'] . " sản phẩm.";
            } else {
                updateCartItem($conn, $cart_id, $product_id, $new_quantity);
            }
        }
        
        $current_stmt->close();
    } elseif (isset($_POST['decrease'])) {
        // Get current quantity
        $current_sql = "SELECT CTGH_KHOILUONG FROM chitiet_gh WHERE GH_MA = ? AND SP_MA = ?";
        $current_stmt = $conn->prepare($current_sql);
        $current_stmt->bind_param("ii", $cart_id, $product_id);
        $current_stmt->execute();
        $current_result = $current_stmt->get_result();
        
        if ($current_result->num_rows > 0) {
            $current_item = $current_result->fetch_assoc();
            $new_quantity = $current_item['CTGH_KHOILUONG'] - 1;
            
            if ($new_quantity <= 0) {
                removeCartItem($conn, $cart_id, $product_id);
                $_SESSION['cart_success'] = "Đã xóa sản phẩm khỏi giỏ hàng.";
            } else {
                updateCartItem($conn, $cart_id, $product_id, $new_quantity);
            }
        }
        
        $current_stmt->close();
    } elseif (isset($_POST['quantity'])) {
        $quantity = floatval($_POST['quantity']);
        
        if ($quantity <= 0) {
            removeCartItem($conn, $cart_id, $product_id);
            $_SESSION['cart_success'] = "Đã xóa sản phẩm khỏi giỏ hàng.";
        } else {
            // Check if new quantity exceeds stock
            if ($quantity > $product['SP_SOLUONGTON']) {
                $_SESSION['cart_error'] = "Số lượng sản phẩm trong kho không đủ. Hiện chỉ còn " . $product['SP_SOLUONGTON'] . " sản phẩm.";
            } else {
                updateCartItem($conn, $cart_id, $product_id, $quantity);
                $_SESSION['cart_success'] = "Đã cập nhật số lượng sản phẩm.";
            }
        }
    }
    
    // Redirect back to cart page
    header("Location: cart.php");
    exit();
} else {
    // If no product ID is provided, redirect to cart page
    header("Location: cart.php");
    exit();
}
?> 