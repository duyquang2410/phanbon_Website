<?php
// Start the session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Set a message in session to display on login page
    $_SESSION['login_required_message'] = "Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.";
    
    // Redirect to login page
    header("Location: login.php");
    exit();
}

// Include database connection and cart functions
include 'connect.php';
include 'cart_functions.php';

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get product id and quantity from form
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $quantity = isset($_POST['quantity']) ? floatval($_POST['quantity']) : 1;
    $unit = isset($_POST['unit']) ? $_POST['unit'] : 'cái';
    
    // Validate input
    if ($product_id <= 0 || $quantity <= 0) {
        $_SESSION['cart_error'] = "Thông tin sản phẩm không hợp lệ.";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
    
    // Check if product exists
    $check_product_sql = "SELECT SP_MA, SP_TEN, SP_SOLUONGTON FROM san_pham WHERE SP_MA = ?";
    $check_stmt = $conn->prepare($check_product_sql);
    $check_stmt->bind_param("i", $product_id);
    $check_stmt->execute();
    $product_result = $check_stmt->get_result();
    
    if ($product_result->num_rows === 0) {
        $_SESSION['cart_error'] = "Sản phẩm không tồn tại.";
        $check_stmt->close();
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
    
    $product = $product_result->fetch_assoc();
    
    // Check product stock
    if ($quantity > $product['SP_SOLUONGTON']) {
        $_SESSION['cart_error'] = "Số lượng sản phẩm trong kho không đủ. Hiện chỉ còn " . $product['SP_SOLUONGTON'] . " sản phẩm.";
        $check_stmt->close();
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
    
    $check_stmt->close();
    
    // Get current cart or create a new one
    $user_id = $_SESSION['user_id'];
    $cart_id = getCurrentCart($conn, $user_id);
    
    // Add product to cart
    if (addToCart($conn, $cart_id, $product_id, $quantity, $unit)) {
        $_SESSION['cart_success'] = "Đã thêm " . $product['SP_TEN'] . " vào giỏ hàng.";
        
        // Redirect to cart page instead of previous page
        header("Location: cart.php");
        exit();
    } else {
        $_SESSION['cart_error'] = "Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
} else {
    // If not POST request, redirect to home page
    header("Location: index.php");
    exit();
}
?> 