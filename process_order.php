<?php
require_once 'connect.php';
session_start();
include 'cart_functions.php';
include 'cart_selection.php';
require_once 'error_log.php';
require_once 'config.php';

$logger = ErrorLogger::getInstance('logs/order_processing.log');

function logOrderProcessing($message, $data = null) {
    $logDir = __DIR__ . '/logs';
    if (!file_exists($logDir)) {
        mkdir($logDir, 0777, true);
    }
    
    $logFile = $logDir . '/order_processing.log';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message";
    
    if ($data !== null) {
        $logMessage .= "\nData: " . print_r($data, true);
    }
    
    $logMessage .= "\n" . str_repeat('-', 80) . "\n";
    
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

function writeDetailedLog($message, $data = null) {
    $log = "[" . date('Y-m-d H:i:s') . "] " . $message;
    if ($data !== null) {
        $log .= " | Data: " . print_r($data, true);
    }
    file_put_contents("logs/cart.log", $log . "\n", FILE_APPEND);
}

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Vui lòng đăng nhập để thanh toán.";
    header("Location: login.php");
    exit();
}

// Kiểm tra phương thức POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: cart.php");
    exit();
}

// Lấy thông tin từ form
$user_id = $_SESSION['user_id'];
$fullName = isset($_POST['fullName']) ? $_POST['fullName'] : '';
$phone = isset($_POST['phone']) ? $_POST['phone'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$province = isset($_POST['province']) ? $_POST['province'] : '';
$district = isset($_POST['district']) ? $_POST['district'] : '';
$ward = isset($_POST['ward']) ? $_POST['ward'] : '';
$street_address = isset($_POST['address']) ? $_POST['address'] : '';
$full_address = $street_address . ', ' . $ward . ', ' . $district . ', ' . $province;
$note = isset($_POST['note']) ? $_POST['note'] : '';
$payment_method = isset($_POST['paymentMethod']) ? (int)$_POST['paymentMethod'] : 1;
$shipping_fee = isset($_POST['shipping_fee']) ? floatval($_POST['shipping_fee']) : 0;
$total_amount = isset($_POST['total_amount']) ? floatval($_POST['total_amount']) : 0;
$total_discount = isset($_POST['total_discount']) ? floatval($_POST['total_discount']) : 0;
$selected_items = isset($_POST['selected_items']) ? $_POST['selected_items'] : [];

// Các giá trị mặc định cho hóa đơn
$tt_ma = 1; // trạng thái
$nv_ma = 1; // nhân viên

// Lấy KM_MA từ mã khuyến mãi truyền qua form (nếu có)
$promo_code = null;
if (!empty($_POST['promo_code'])) {
    $promo_code_str = trim($_POST['promo_code']);
    $promo_stmt = $conn->prepare("SELECT KM_MA FROM khuyen_mai WHERE Code = ?");
    $promo_stmt->bind_param("s", $promo_code_str);
    $promo_stmt->execute();
    $promo_stmt->bind_result($promo_id);
    if ($promo_stmt->fetch()) {
        $promo_code = $promo_id;
    }
    $promo_stmt->close();
}

// Khởi tạo các biến trước khi log
$shipping_id = null;
$cart_items = [];

// Lấy mã khuyến mãi theo từng sản phẩm (nếu có)
$promo_code_items = isset($_POST['promo_code_item']) ? $_POST['promo_code_item'] : array();

try {
    logOrderProcessing("Starting order process", [
        'user_id' => $user_id,
        'payment_method' => $payment_method,
        'shipping_id' => $shipping_id,
        'cart_items' => $cart_items,
        'total_amount' => $total_amount
    ]);
    
    $conn->begin_transaction();
    writeDetailedLog("Transaction started");

    // Lấy thông tin giỏ hàng hiện tại
    $cart_id = getCurrentCart($conn, $user_id);
    writeDetailedLog("Got current cart", ['cart_id' => $cart_id]);
    
    // Thêm đơn vận chuyển mới
    $shipping_sql = "INSERT INTO don_van_chuyen (NVC_MA, DVC_DIACHI, DVC_TGBATDAU, DVC_TGHOANTHANH) 
                    VALUES (?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 3 DAY))";
    writeDetailedLog("Preparing shipping order SQL", ['sql' => $shipping_sql]);
    
    $shipping_stmt = $conn->prepare($shipping_sql);
    $nvc_ma = isset($_POST['shipping_provider']) && $_POST['shipping_provider'] === 'ghn' ? 2 : 1; // 2 for GHN, 1 for Viettel Post
    $shipping_stmt->bind_param("is", $nvc_ma, $full_address);
    
    writeDetailedLog("Executing shipping order insert", [
        'nvc_ma' => $nvc_ma,
        'address' => $full_address,
        'provider' => $nvc_ma === 2 ? 'GHN' : 'Viettel Post'
    ]);
    
    if (!$shipping_stmt->execute()) {
        throw new Exception("Lỗi khi tạo đơn vận chuyển: " . $shipping_stmt->error);
    }
    
    $dvc_ma = $conn->insert_id;
    writeDetailedLog("Shipping order created", ['dvc_ma' => $dvc_ma]);
    
    if ($dvc_ma <= 0) {
        throw new Exception("Không thể tạo đơn vận chuyển mới");
    }
    $shipping_stmt->close();

    // Lấy danh sách sản phẩm đã chọn
    $selected_items = getSelectedItems($conn, $cart_id);
    writeDetailedLog("Got selected items", ['count' => count($selected_items), 'items' => $selected_items]);
    
    if (empty($selected_items)) {
        throw new Exception("Vui lòng chọn ít nhất một sản phẩm để thanh toán!");
    }

    // Lấy thông tin chi tiết các sản phẩm đã chọn
    $cart_data = getCartItems($conn, $cart_id);
    $order_items = [];

    foreach ($selected_items as $sp_ma) {
        if (isset($cart_data['items'][$sp_ma])) {
            $item = $cart_data['items'][$sp_ma];
            $item_discount = 0;
            $item_promo_code = isset($promo_code_items[$sp_ma]) ? trim($promo_code_items[$sp_ma]) : '';
            if ($item_promo_code) {
                $promo = applyPromoCode($conn, $item_promo_code);
                if ($promo['valid']) {
                    $item_discount = $item['subtotal'] * ($promo['value'] / 100);
                    $total_discount += $item_discount;
                    logOrderProcessing("Applied product promotion", [
                        'product_id' => $sp_ma,
                        'promo_code' => $item_promo_code,
                        'discount_amount' => $item_discount
                    ]);
                }
            }
            $item['discount'] = $item_discount;
            $item['final_subtotal'] = $item['subtotal'] - $item_discount;
            $total_amount += $item['final_subtotal'];
            $order_items[] = $item;
        }
    }

    // Áp dụng mã giảm giá nếu có
    $discount_amount = 0;
    if ($promo_code) {
        $promo = applyPromoCode($conn, $promo_code);
        if ($promo['valid']) {
            $discount_amount = $total_amount * ($promo['value'] / 100);
            $total_amount -= $discount_amount;
            logOrderProcessing("Applied promotion", [
                'promo_code' => $promo_code,
                'discount_amount' => $discount_amount,
                'final_total' => $total_amount
            ]);
        }
    }

    // Calculate final total
    $total_order = $total_amount - $total_discount + $shipping_fee;

    // Tạo đơn hàng mới
    $order_sql = "INSERT INTO hoa_don (TT_MA, DVC_MA, NV_MA, PTTT_MA, KM_MA, GH_MA, HD_NGAYLAP, HD_TONGTIEN, HD_PHISHIP, KH_MA) 
                  VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?)";
    writeDetailedLog("Preparing order SQL", [
        'sql' => $order_sql,
        'params' => [
            'tt_ma' => $tt_ma,
            'dvc_ma' => $dvc_ma,
            'nv_ma' => $nv_ma,
            'payment_method' => $payment_method,
            'promo_code' => $promo_code,
            'cart_id' => $cart_id,
            'total_amount' => $total_amount,
            'shipping_fee' => $shipping_fee,
            'user_id' => $user_id
        ]
    ]);

    $stmt = $conn->prepare($order_sql);
    $stmt->bind_param("iiiiiiddi", $tt_ma, $dvc_ma, $nv_ma, $payment_method, $promo_code, $cart_id, $total_amount, $shipping_fee, $user_id);
    
    writeDetailedLog("Executing order insert");
    if (!$stmt->execute()) {
        throw new Exception("Lỗi khi tạo hóa đơn: " . $stmt->error);
    }
    
    $order_id = $conn->insert_id;
    writeDetailedLog("Order created", ['order_id' => $order_id]);
    
    if ($order_id <= 0) {
        throw new Exception("Không thể tạo hóa đơn mới");
    }
    $stmt->close();

    // Lặp qua các sản phẩm để kiểm tra tồn kho, insert chi tiết hóa đơn và cập nhật tồn kho
    foreach ($order_items as $item) {
        logOrderProcessing("Processing cart item", $item);
        $sp_ma = $item['id'];
        // Check stock with lock
        $stock_sql = "SELECT SP_SOLUONGTON FROM san_pham WHERE SP_MA = ? FOR UPDATE";
        $stock_stmt = $conn->prepare($stock_sql);
        $stock_stmt->bind_param("i", $sp_ma);
        $stock_stmt->execute();
        $stock_stmt->bind_result($current_stock);
        $stock_stmt->fetch();
        $stock_stmt->close();
        logOrderProcessing("Current stock check", [
            'product_id' => $sp_ma,
            'current_stock' => $current_stock,
            'requested_quantity' => $item['quantity']
        ]);
        if ($current_stock < $item['quantity']) {
            throw new Exception("Insufficient stock for product ID {$sp_ma}");
        }
        // Insert order detail
        $detail_sql = "INSERT INTO chi_tiet_hd (HD_STT, SP_MA, CTHD_SOLUONG, CTHD_DONGIA) VALUES (?, ?, ?, ?)";
        $detail_stmt = $conn->prepare($detail_sql);
        $detail_stmt->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
        if (!$detail_stmt->execute()) {
            throw new Exception("Failed to insert order detail: " . $detail_stmt->error);
        }
        $detail_stmt->close();
        logOrderProcessing("Inserted order detail", [
            'order_id' => $order_id,
            'product_id' => $item['id'],
            'quantity' => $item['quantity'],
            'price' => $item['price']
        ]);
        // Update stock
        $update_sql = "UPDATE san_pham SET SP_SOLUONGTON = SP_SOLUONGTON - ? WHERE SP_MA = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("di", $item['quantity'], $item['id']);
        if (!$update_stmt->execute()) {
            throw new Exception("Failed to update stock: " . $update_stmt->error);
        }
        $update_stmt->close();
        logOrderProcessing("Updated stock", [
            'product_id' => $item['id'],
            'quantity_reduced' => $item['quantity']
        ]);
    }

    // Cập nhật trạng thái DA_MUA cho tất cả sản phẩm đã chọn
    $update_cart_sql = "UPDATE chitiet_gh SET DA_MUA = 1 
                       WHERE GH_MA = ? AND SP_MA IN (SELECT SP_MA FROM selected_cart_items WHERE GH_MA = ?)";
    $update_cart_stmt = $conn->prepare($update_cart_sql);
    $update_cart_stmt->bind_param("ii", $cart_id, $cart_id);
    $update_cart_stmt->execute();
    $affected_rows = $update_cart_stmt->affected_rows;
    writeDetailedLog("Updated cart items status", ['affected_rows' => $affected_rows]);
    $update_cart_stmt->close();

    // Xóa các sản phẩm đã chọn khỏi bảng selected_cart_items
    clearSelectedItems($conn, $cart_id);
    writeDetailedLog("Cleared selected items");

    // Xóa mã giảm giá nếu có
    if (isset($_SESSION['promo_code'])) {
        unset($_SESSION['promo_code']);
        unset($_SESSION['promo_id']);
        unset($_SESSION['promo_value']);
        writeDetailedLog("Cleared promotion code");
    }

    // Save shipping address
    if (isset($_POST['province']) && isset($_POST['district']) && isset($_POST['ward']) && isset($_POST['address'])) {
        $address_sql = "INSERT INTO dia_chi_giao_hang (DH_MA, DCGH_TINH, DCGH_HUYEN, DCGH_XA, DCGH_DIACHI) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($address_sql);
        $stmt->bind_param("issss", $order_id, $_POST['province'], $_POST['district'], $_POST['ward'], $_POST['address']);
        $stmt->execute();
    }

    // Commit transaction
    $conn->commit();
    logOrderProcessing("Order completed successfully", ['order_id' => $order_id]);

    // Lưu thông tin đơn hàng vào session
    $_SESSION['order_id'] = $order_id;
    $_SESSION['success_message'] = "Đặt hàng thành công! Cảm ơn bạn đã mua sắm tại Plants Shop.";
    
    $_SESSION['order_details'] = [
        'order_id' => $order_id,
        'total_amount' => $total_amount,
        'payment_method' => $payment_method,
        'order_date' => date('Y-m-d H:i:s')
    ];
    
    header("Location: order_confirmation.php?id=" . $order_id);
    exit();

} catch (Exception $e) {
    $conn->rollback();
    logOrderProcessing("Error processing order", [
        'error_message' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
    $_SESSION['error_message'] = "Lỗi khi đặt hàng: " . $e->getMessage();
    header("Location: checkout.php?error=" . urlencode($e->getMessage()));
    exit();
} finally {
    $conn->close();
}
?>