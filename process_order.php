<?php
require_once 'connect.php';
session_start();
include 'cart_functions.php';
include 'cart_selection.php';
require_once 'error_log.php';
require_once 'config.php';
require_once 'create_logs.php';

$logger = ErrorLogger::getInstance('logs/order_processing.log');

function writeDetailedLog($message, $data = null) {
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

writeDetailedLog("Received POST data", $_POST);

// Khởi tạo các biến
$cart_id = isset($_SESSION['cart_id']) ? $_SESSION['cart_id'] : null;
$shipping_id = isset($_POST['shipping_id']) ? $_POST['shipping_id'] : null;
$note = isset($_POST['note']) ? $_POST['note'] : '';
$total_amount = 0;

// Lấy thông tin từ form
$user_id = $_SESSION['user_id'];
$payment_method = $_POST['paymentMethod'];
$shipping_fee = $_POST['shipping_fee'];
$dvc_ma = isset($_POST['shipping_id']) ? $_POST['shipping_id'] : null;
$fullName = $_POST['fullName'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$province = $_POST['province'];
$district = $_POST['district'];
$ward = $_POST['ward'];
$street_address = $_POST['street_address'];

// Thông tin người nhận
$full_address = $street_address . ', ' . $ward . ', ' . $district . ', ' . $province;

// Trạng thái mặc định cho đơn hàng mới
$tt_ma = 1; // Trạng thái "Đang xử lý"
$nv_ma = 1; // Mã nhân viên mặc định

// Lấy KM_MA từ mã khuyến mãi truyền qua form (nếu có)
$promo_code = null;

if (!empty($_POST['promo_code'])) {
    $promo_code_str = trim($_POST['promo_code']);
    writeDetailedLog("Processing promo code", [
        'promo_code' => $promo_code_str,
        'post_data' => $_POST
    ]);

    $promo_stmt = $conn->prepare("SELECT KM_MA, KM_GIATRI, Code FROM khuyen_mai WHERE Code = ? AND KM_TGBD <= NOW() AND KM_TGKT >= NOW()");
    if (!$promo_stmt) {
        writeDetailedLog("Failed to prepare promo code query", ['error' => $conn->error]);
    } else {
        $promo_stmt->bind_param("s", $promo_code_str);
        if (!$promo_stmt->execute()) {
            writeDetailedLog("Failed to execute promo code query", ['error' => $promo_stmt->error]);
        } else {
            $promo_result = $promo_stmt->get_result();
            if ($promo_result->num_rows > 0) {
                $promo_data = $promo_result->fetch_assoc();
                $promo_code = $promo_data['KM_MA'];
                writeDetailedLog("Promo code found and valid", [
                    'code' => $promo_code_str,
                    'km_ma' => $promo_code,
                    'discount_value' => $promo_data['KM_GIATRI']
                ]);
            } else {
                writeDetailedLog("Invalid or expired promo code", ['code' => $promo_code_str]);
            }
        }
        $promo_stmt->close();
    }
}

// Kiểm tra các trường bắt buộc
if (empty($payment_method)) {
    throw new Exception("Vui lòng chọn phương thức thanh toán");
}

if (empty($fullName) || empty($phone) || empty($email) || empty($street_address) || 
    empty($province) || empty($district) || empty($ward)) {
    throw new Exception("Vui lòng điền đầy đủ thông tin giao hàng");
}

// Lấy danh sách sản phẩm đã chọn và tính tổng tiền
$selected_items = isset($_POST['selected_items']) ? $_POST['selected_items'] : [];
if (empty($selected_items)) {
    writeOrderProcessingLog("No items selected", ['post_data' => $_POST]);
    throw new Exception("Vui lòng chọn ít nhất một sản phẩm để thanh toán!");
}

// Lấy thông tin giỏ hàng hiện tại
$cart_id = getCurrentCart($conn, $user_id);
writeOrderProcessingLog("Current cart retrieved", ['cart_id' => $cart_id]);

// Lấy thông tin chi tiết các sản phẩm
$cart_data = getCartItems($conn, $cart_id);

$total_amount = 0;
$valid_items = [];
foreach ($cart_data['items'] as $item) {
    if (in_array($item['id'], $selected_items)) {
        $total_amount += $item['price'] * $item['quantity'];
        $valid_items[] = $item['id'];
    }
}

if (empty($valid_items)) {
    writeOrderProcessingLog("No valid items found in cart", [
        'selected_items' => $selected_items,
        'cart_items' => array_keys($cart_data['items'])
    ]);
    throw new Exception("Không tìm thấy sản phẩm hợp lệ trong giỏ hàng!");
}

$selected_items = $valid_items;

// Khởi tạo các biến trước khi log
$cart_items = [];

// Lấy mã khuyến mãi theo từng sản phẩm (nếu có)
$promo_code_items = isset($_POST['promo_code_item']) ? $_POST['promo_code_item'] : array();
$item_promo_codes = array(); // Lưu trữ mã khuyến mãi hợp lệ cho từng sản phẩm

// Xử lý mã khuyến mãi cho từng sản phẩm
foreach ($promo_code_items as $product_id => $code) {
    if (!empty($code)) {
        $item_promo_stmt = $conn->prepare("SELECT KM_MA FROM khuyen_mai WHERE Code = ? AND KM_TGBD <= NOW() AND KM_TGKT >= NOW()");
        if ($item_promo_stmt) {
            $item_promo_stmt->bind_param("s", $code);
            if ($item_promo_stmt->execute()) {
                $item_promo_result = $item_promo_stmt->get_result();
                if ($item_promo_result->num_rows > 0) {
                    $item_promo_data = $item_promo_result->fetch_assoc();
                    $item_promo_codes[$product_id] = $item_promo_data['KM_MA'];
                }
            }
            $item_promo_stmt->close();
        }
    }
}

writeOrderProcessingLog("Starting order processing", [
    'user_id' => $user_id,
    'cart_id' => $cart_id,
    'payment_method' => $payment_method,
    'shipping_fee' => $shipping_fee,
    'shipping_id' => $dvc_ma,
    'address' => [
        'name' => $fullName,
        'phone' => $phone,
        'email' => $email,
        'province' => $province,
        'district' => $district,
        'ward' => $ward,
        'street' => $street_address
    ],
    'promo_code' => $promo_code
]);

try {
    $conn->begin_transaction();
    writeOrderProcessingLog("Transaction started");

    // Thêm phí ship vào tổng tiền
    $total_amount += $shipping_fee;
    
    // Thêm đơn vận chuyển mới
    $shipping_sql = "INSERT INTO don_van_chuyen (NVC_MA, DVC_DIACHI, DVC_TGBATDAU, DVC_TGHOANTHANH) 
                    VALUES (?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 3 DAY))";
    
    // Xác định nhà vận chuyển
    $nvc_ma = isset($_POST['shipping_provider']) && $_POST['shipping_provider'] === 'ghn' ? 2 : 1;
    
    writeOrderProcessingLog("Creating shipping order", [
        'sql' => $shipping_sql,
        'nvc_ma' => $nvc_ma,
        'address' => $full_address
    ]);
    
    $shipping_stmt = $conn->prepare($shipping_sql);
    if (!$shipping_stmt) {
        writeOrderProcessingLog("Failed to prepare shipping statement", ['error' => $conn->error]);
        throw new Exception("Lỗi khi chuẩn bị tạo đơn vận chuyển: " . $conn->error);
    }

    $shipping_stmt->bind_param("is", $nvc_ma, $full_address);
    
    if (!$shipping_stmt->execute()) {
        writeOrderProcessingLog("Failed to execute shipping insert", ['error' => $shipping_stmt->error]);
        throw new Exception("Lỗi khi tạo đơn vận chuyển: " . $shipping_stmt->error);
    }
    
    $dvc_ma = $conn->insert_id;
    writeOrderProcessingLog("Shipping order created successfully", ['dvc_ma' => $dvc_ma]);
    
    if ($dvc_ma <= 0) {
        writeOrderProcessingLog("Invalid shipping order ID", ['dvc_ma' => $dvc_ma]);
        throw new Exception("Không thể tạo đơn vận chuyển mới");
    }
    $shipping_stmt->close();

    // Create new order - Cập nhật theo cấu trúc bảng thực tế
    $order_sql = "INSERT INTO hoa_don (KH_MA, NV_MA, DVC_MA, PTTT_MA, TT_MA, HD_NGAYLAP, HD_TONGTIEN, HD_PHISHIP, KM_MA, HD_LIDOHUY) 
                  VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?, NULL)";
    writeOrderProcessingLog("Creating order", [
        'sql' => $order_sql,
        'params' => [
            'user_id' => $user_id,
            'nv_ma' => $nv_ma,
            'dvc_ma' => $dvc_ma,
            'payment_method' => $payment_method,
            'tt_ma' => $tt_ma,
            'total_amount' => $total_amount,
            'shipping_fee' => $shipping_fee,
            'promo_code' => $promo_code
        ]
    ]);

    $order_stmt = $conn->prepare($order_sql);
    if (!$order_stmt) {
        writeOrderProcessingLog("Failed to prepare order statement", ['error' => $conn->error]);
        throw new Exception("Lỗi khi chuẩn bị tạo đơn hàng: " . $conn->error);
    }

    $order_stmt->bind_param("iiiiiddi", 
        $user_id, 
        $nv_ma, 
        $dvc_ma, 
        $payment_method, 
        $tt_ma, 
        $total_amount, 
        $shipping_fee, 
        $promo_code
    );

    if (!$order_stmt->execute()) {
        writeOrderProcessingLog("Failed to execute order insert", ['error' => $order_stmt->error]);
        throw new Exception("Lỗi khi tạo đơn hàng: " . $order_stmt->error);
    }

    $order_id = $conn->insert_id;
    writeOrderProcessingLog("Order created successfully", ['order_id' => $order_id]);
    
    if ($order_id <= 0) {
        writeOrderProcessingLog("Invalid order ID", ['order_id' => $order_id]);
        throw new Exception("Không thể tạo đơn hàng mới");
    }
    $order_stmt->close();

    // Thêm địa chỉ giao hàng
    $address_sql = "INSERT INTO dia_chi_giao_hang (DH_MA, DCGH_TINH, DCGH_HUYEN, DCGH_XA, DCGH_DIACHI, 
                    DCGH_TENNGUOINHAN, DCGH_SDT, DCGH_EMAIL, DCGH_GHICHU) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    writeOrderProcessingLog("Adding shipping address", [
        'sql' => $address_sql,
        'params' => [
            'order_id' => $order_id,
            'province' => $province,
            'district' => $district,
            'ward' => $ward,
            'street' => $street_address,
            'recipient' => $fullName,
            'phone' => $phone,
            'email' => $email,
            'note' => $note
        ]
    ]);

    $address_stmt = $conn->prepare($address_sql);
    if (!$address_stmt) {
        writeOrderProcessingLog("Failed to prepare address statement", ['error' => $conn->error]);
        throw new Exception("Lỗi khi chuẩn bị thêm địa chỉ giao hàng: " . $conn->error);
    }

    $address_stmt->bind_param("issssssss", 
        $order_id, 
        $province, 
        $district, 
        $ward, 
        $street_address,
        $fullName,
        $phone,
        $email,
        $note
    );

    if (!$address_stmt->execute()) {
        writeOrderProcessingLog("Failed to execute address insert", ['error' => $address_stmt->error]);
        throw new Exception("Lỗi khi thêm địa chỉ giao hàng: " . $address_stmt->error);
    }
    $address_stmt->close();

    writeOrderProcessingLog("Shipping address added successfully", [
        'order_id' => $order_id,
        'address' => [
            'province' => $province,
            'district' => $district,
            'ward' => $ward,
            'street' => $street_address,
            'recipient' => $fullName,
            'phone' => $phone,
            'email' => $email
        ]
    ]);

    // Thêm chi tiết đơn hàng và áp dụng khuyến mãi cho từng sản phẩm
    foreach ($selected_items as $product_id) {
        $quantity = isset($_POST['item_quantity'][$product_id]) ? (int)$_POST['item_quantity'][$product_id] : 0;
        $price = isset($_POST['item_price'][$product_id]) ? (float)$_POST['item_price'][$product_id] : 0;
        $unit = isset($_POST['item_unit'][$product_id]) ? $_POST['item_unit'][$product_id] : '';
        
        if ($quantity <= 0 || $price <= 0) {
            writeOrderProcessingLog("Invalid product data", [
                'product_id' => $product_id,
                'quantity' => $quantity,
                'price' => $price
            ]);
            continue;
        }

        $detail_sql = "INSERT INTO chi_tiet_hd (SP_MA, HD_STT, CTHD_SOLUONG, CTHD_DONGIA, CTHD_DONVITINH) 
                       VALUES (?, ?, ?, ?, ?)";
        $detail_stmt = $conn->prepare($detail_sql);
        if (!$detail_stmt) {
            writeOrderProcessingLog("Failed to prepare detail statement", ['error' => $conn->error]);
            throw new Exception("Lỗi khi chuẩn bị thêm chi tiết đơn hàng: " . $conn->error);
        }

        $detail_stmt->bind_param("iidds", 
            $product_id,
            $order_id,
            $quantity,
            $price,
            $unit
        );
        
        if (!$detail_stmt->execute()) {
            writeOrderProcessingLog("Failed to execute detail insert", [
                'error' => $detail_stmt->error,
                'product_id' => $product_id,
                'order_id' => $order_id
            ]);
            throw new Exception("Lỗi khi thêm chi tiết đơn hàng: " . $detail_stmt->error);
        }

        writeOrderProcessingLog("Order detail added successfully", [
            'product_id' => $product_id,
            'order_id' => $order_id,
            'quantity' => $quantity,
            'price' => $price,
            'unit' => $unit
        ]);

        $detail_stmt->close();
    }

    // Xóa các sản phẩm đã mua khỏi giỏ hàng
    if (!empty($selected_items)) {
        // Xóa các sản phẩm đã mua khỏi giỏ hàng
        $delete_sql = "DELETE FROM chitiet_gh WHERE GH_MA = ? AND SP_MA IN (" . str_repeat('?,', count($selected_items) - 1) . "?)";
        $delete_stmt = $conn->prepare($delete_sql);
        
        if (!$delete_stmt) {
            writeOrderProcessingLog("Failed to prepare delete statement", ['error' => $conn->error]);
            throw new Exception("Lỗi khi chuẩn bị xóa sản phẩm khỏi giỏ hàng: " . $conn->error);
        }

        // Tạo mảng tham số cho câu lệnh DELETE
        $params = array_merge([$cart_id], $selected_items);
        $types = str_repeat('i', count($params));
        
        // Bind parameters
        $delete_stmt->bind_param($types, ...$params);
        
        if (!$delete_stmt->execute()) {
            writeOrderProcessingLog("Failed to delete products from cart", [
                'error' => $delete_stmt->error,
                'cart_id' => $cart_id,
                'selected_items' => $selected_items
            ]);
            throw new Exception("Lỗi khi xóa sản phẩm khỏi giỏ hàng: " . $delete_stmt->error);
        }

        writeOrderProcessingLog("Products removed from cart successfully", [
            'cart_id' => $cart_id,
            'selected_items' => $selected_items,
            'affected_rows' => $delete_stmt->affected_rows
        ]);

        $delete_stmt->close();
    }

    // Áp dụng giảm giá nếu có
    if ($promo_code) {
        $discount_sql = "SELECT KM_GIATRI, hinh_thuc_km FROM khuyen_mai WHERE KM_MA = ?";
        $discount_stmt = $conn->prepare($discount_sql);
        if (!$discount_stmt) {
            writeOrderProcessingLog("Failed to prepare discount query", ['error' => $conn->error]);
            throw new Exception("Lỗi khi chuẩn bị truy vấn khuyến mãi: " . $conn->error);
        }

        $discount_stmt->bind_param("i", $promo_code);
        if (!$discount_stmt->execute()) {
            writeOrderProcessingLog("Failed to execute discount query", ['error' => $discount_stmt->error]);
            throw new Exception("Lỗi khi thực hiện truy vấn khuyến mãi: " . $discount_stmt->error);
        }

        $discount_result = $discount_stmt->get_result();
        if ($discount_result->num_rows > 0) {
            $discount_data = $discount_result->fetch_assoc();
            writeOrderProcessingLog("Discount data retrieved", [
                'discount_data' => $discount_data,
                'original_total' => $total_amount
            ]);

            $discount_amount = 0;
            if ($discount_data['hinh_thuc_km'] == 'Giảm phần trăm') {
                if ($discount_data['KM_GIATRI'] > 0 && $discount_data['KM_GIATRI'] <= 100) {
                    $discount_amount = $total_amount * ($discount_data['KM_GIATRI'] / 100);
                } else {
                    writeOrderProcessingLog("Invalid percentage discount value", [
                        'value' => $discount_data['KM_GIATRI']
                    ]);
                }
            } else if ($discount_data['hinh_thuc_km'] == 'Giảm trực tiếp') {
                if ($discount_data['KM_GIATRI'] > 0 && $discount_data['KM_GIATRI'] <= $total_amount) {
                    $discount_amount = $discount_data['KM_GIATRI'];
                } else {
                    writeOrderProcessingLog("Invalid direct discount value", [
                        'value' => $discount_data['KM_GIATRI'],
                        'total_amount' => $total_amount
                    ]);
                }
            }

            $total_amount -= $discount_amount;
            writeOrderProcessingLog("Discount applied", [
                'discount_type' => $discount_data['hinh_thuc_km'],
                'discount_value' => $discount_data['KM_GIATRI'],
                'discount_amount' => $discount_amount,
                'new_total' => $total_amount
            ]);
        }
        $discount_stmt->close();
    }

    // Commit transaction
    $conn->commit();
    writeOrderProcessingLog("Transaction committed successfully", [
        'order_id' => $order_id,
        'total_amount' => $total_amount,
        'shipping_fee' => $shipping_fee
    ]);

    // Redirect to order confirmation page
    $_SESSION['order_id'] = $order_id;
    $_SESSION['success_message'] = "Đặt hàng thành công! Cảm ơn bạn đã mua sắm tại Plants Shop.";
    writeOrderProcessingLog("Redirecting to confirmation page", [
        'order_id' => $order_id,
        'session' => $_SESSION
    ]);
    header("Location: order_confirmation.php?id=" . $order_id);
    exit();

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    writeOrderProcessingLog("Order processing failed", [
        'error_message' => $e->getMessage(),
        'error_trace' => $e->getTraceAsString(),
        'post_data' => $_POST
    ]);
    $_SESSION['error_message'] = "Lỗi khi đặt hàng: " . $e->getMessage();
    header("Location: checkout.php");
    exit();
}

$conn->close();
writeOrderProcessingLog("Order processing completed");
?>