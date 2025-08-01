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

try {
    writeDetailedLog("Received POST data", $_POST);

    // Khởi tạo các biến với giá trị mặc định
    $user_id = $_SESSION['user_id'];
    $cart_id = getCurrentCart($conn, $user_id);
    $shipping_id = isset($_POST['shipping_id']) ? $_POST['shipping_id'] : null;
    $note = isset($_POST['note']) ? $_POST['note'] : '';
    $payment_method = isset($_POST['paymentMethod']) ? $_POST['paymentMethod'] : '';
    $shipping_fee = isset($_POST['shipping_fee']) ? floatval($_POST['shipping_fee']) : 0;
    $fullName = isset($_POST['fullName']) ? $_POST['fullName'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $province = isset($_POST['province']) ? $_POST['province'] : '';
    $district = isset($_POST['district']) ? $_POST['district'] : '';
    $ward = isset($_POST['ward']) ? $_POST['ward'] : '';
    $street_address = isset($_POST['street_address']) ? $_POST['street_address'] : '';
    $total_amount = isset($_POST['total_amount']) ? floatval($_POST['total_amount']) : 0;
    $total_discount = isset($_POST['total_discount']) ? floatval($_POST['total_discount']) : 0;

    // Debug log
    error_log("Processing order - POST data: " . print_r($_POST, true));
    error_log("Total discount from POST: " . $total_discount);

    // Log thông tin khuyến mãi
    writeDetailedLog("Discount information", [
        'total_discount' => $total_discount,
        'post_data' => $_POST
    ]);

    // Thông tin người nhận
    $full_address = isset($_POST['full_address']) ? $_POST['full_address'] : '';

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

        $promo_stmt = $conn->prepare("SELECT KM_MA, KM_GIATRI, Code, hinh_thuc_km FROM khuyen_mai WHERE Code = ? AND KM_TGBD <= NOW() AND KM_TGKT >= NOW()");
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

                    // Tính lại giá trị khuyến mãi để đảm bảo
                    if ($promo_data['hinh_thuc_km'] == 'percent') {
                        $total_discount = min($total_amount, $total_amount * ($promo_data['KM_GIATRI'] / 100));
                    } else {
                        $total_discount = min($promo_data['KM_GIATRI'], $total_amount);
                    }

                    writeDetailedLog("Promo code found and valid", [
                        'code' => $promo_code_str,
                        'km_ma' => $promo_code,
                        'discount_value' => $promo_data['KM_GIATRI'],
                        'calculated_discount' => $total_discount
                    ]);
                } else {
                    writeDetailedLog("Invalid or expired promo code", ['code' => $promo_code_str]);
                }
            }
            $promo_stmt->close();
        }
    }

    // Debug log trước khi tạo đơn hàng
    error_log("Before creating order - Total discount: " . $total_discount);

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

    // Bắt đầu transaction
    $conn->begin_transaction();

    // Tạo hóa đơn mới
    $order_sql = "INSERT INTO hoa_don (
        KH_MA, 
        PTTT_MA,
        TT_MA,
        NV_MA,
        HD_NGAYLAP,
        HD_DIACHI,
        HD_SDT,
        HD_EMAIL,
        HD_TONGTIEN,
        HD_PHISHIP,
        KM_MA,
        HD_GIAMGIA
    ) VALUES (?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?)";
    
    $order_stmt = $conn->prepare($order_sql);
    if (!$order_stmt) {
        writeOrderProcessingLog("Failed to prepare order statement", ['error' => $conn->error]);
        throw new Exception("Lỗi khi chuẩn bị tạo đơn hàng: " . $conn->error);
    }

    $order_stmt->bind_param("iiissssiddi", 
        $user_id,
        $payment_method,
        $tt_ma,
        $nv_ma,
        $street_address,
        $phone,
        $email,
        $total_amount,
        $shipping_fee,
        $promo_code,
        $total_discount
    );

    if (!$order_stmt->execute()) {
        writeOrderProcessingLog("Failed to execute order insert", ['error' => $order_stmt->error]);
        throw new Exception("Lỗi khi tạo đơn hàng: " . $order_stmt->error);
    }

    $order_id = $order_stmt->insert_id;
    $order_stmt->close();

    writeOrderProcessingLog("Order created successfully", [
        'order_id' => $order_id,
        'user_id' => $user_id
    ]);

    // Lưu thông tin địa chỉ giao hàng
    $shipping_address_sql = "INSERT INTO dia_chi_giao_hang (
        DH_MA,
        DCGH_TENNGUOINHAN,
        DCGH_SDT,
        DCGH_EMAIL,
        DCGH_DIACHI,
        DCGH_TINH,
        DCGH_HUYEN,
        DCGH_XA,
        DCGH_GHICHU
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $shipping_address_stmt = $conn->prepare($shipping_address_sql);
    if (!$shipping_address_stmt) {
        writeOrderProcessingLog("Failed to prepare shipping address statement", ['error' => $conn->error]);
        throw new Exception("Lỗi khi chuẩn bị lưu địa chỉ giao hàng: " . $conn->error);
    }

    $shipping_address_stmt->bind_param("issssiiis", 
        $order_id,
        $fullName,
        $phone,
        $email,
        $full_address,
        $province,
        $district,
        $ward,
        $note
    );

    if (!$shipping_address_stmt->execute()) {
        writeOrderProcessingLog("Failed to save shipping address", ['error' => $shipping_address_stmt->error]);
        throw new Exception("Lỗi khi lưu địa chỉ giao hàng: " . $shipping_address_stmt->error);
    }
    $shipping_address_stmt->close();

    // Xử lý từng sản phẩm
    foreach ($selected_items as $product_id) {
        $quantity = isset($_POST['item_quantity'][$product_id]) ? (int)$_POST['item_quantity'][$product_id] : 0;
        
        // Lock và kiểm tra sản phẩm
        $sql = "SELECT SP_MA, SP_TEN, SP_DONGIA, SP_SOLUONGTON, SP_AVAILABLE 
                FROM san_pham 
                WHERE SP_MA = ? 
                FOR UPDATE";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();

        // Kiểm tra số lượng khả dụng
        if ($product['SP_AVAILABLE'] < $quantity) {
            throw new Exception("Sản phẩm {$product['SP_TEN']} chỉ còn {$product['SP_AVAILABLE']} sản phẩm");
        }

        // Thêm chi tiết hóa đơn
        $sql = "INSERT INTO chi_tiet_hd (
            SP_MA,
            HD_STT,
            CTHD_SOLUONG,
            CTHD_DONGIA,
            CTHD_GIAGOC
        ) VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "iiddd",
            $product_id,
            $order_id,
            $quantity,
            $product['SP_DONGIA'],
            $product['SP_DONGIA']
        );
        $stmt->execute();

        // Ghi log vào stock_movements
        $sql = "INSERT INTO stock_movements (
            SP_MA,
            SM_LOAI,
            SM_SOLUONG,
            SM_SOLUONG_CU,
            SM_SOLUONG_MOI,
            SM_AVAILABLE_CU,
            SM_AVAILABLE_MOI,
            SM_GHICHU,
            SM_THAMCHIEU,
            NV_MA
        ) VALUES (?, 'DAT_HANG', ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $new_available = $product['SP_AVAILABLE'] - $quantity;
        $sm_ghichu = "Đặt hàng #" . $order_id;
        $nv_ma = 1;
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "idddddsis",
            $product_id,
            $quantity,
            $product['SP_SOLUONGTON'],
            $product['SP_SOLUONGTON'],
            $product['SP_AVAILABLE'],
            $new_available,
            $sm_ghichu,
            $order_id,
            $nv_ma
        );
        $stmt->execute();

        // Cập nhật trạng thái sản phẩm trong giỏ hàng
        $update_cart_sql = "UPDATE chitiet_gh SET DA_MUA = 1 WHERE GH_MA = ? AND SP_MA = ?";
        $update_cart_stmt = $conn->prepare($update_cart_sql);
        if (!$update_cart_stmt) {
            writeOrderProcessingLog("Failed to prepare cart update", ['error' => $conn->error]);
            throw new Exception("Lỗi khi cập nhật giỏ hàng: " . $conn->error);
        }

        $update_cart_stmt->bind_param("ii", $cart_id, $product_id);
        if (!$update_cart_stmt->execute()) {
            writeOrderProcessingLog("Failed to execute cart update", [
                'error' => $update_cart_stmt->error,
                'cart_id' => $cart_id,
                'product_id' => $product_id
            ]);
            throw new Exception("Lỗi khi cập nhật giỏ hàng: " . $update_cart_stmt->error);
        }

        $update_cart_stmt->close();
    }

    // Commit transaction
    $conn->commit();
    writeOrderProcessingLog("Transaction committed successfully", [
        'order_id' => $order_id,
        'total_amount' => $total_amount,
        'shipping_fee' => $shipping_fee,
        'discount_amount' => isset($discount_amount) ? $discount_amount : 0
    ]);

    // Kiểm tra phương thức thanh toán
    if ($payment_method == 2) { // 2 là chuyển khoản ngân hàng
        require_once __DIR__ . '/thanhtoan/PaymenVnpayClass.php';
        $vnpay = new payment_vnpay();
        // Tổng tiền cần thanh toán là (tổng gốc + phí ship) - giảm giá
        $order_price = ($total_amount + $shipping_fee) - $total_discount;
        $vnpay->payment_vnpay($order_id, $order_price);
        exit;
    }

    // Nếu không phải chuyển khoản ngân hàng thì chuyển về trang xác nhận như cũ
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
    if (isset($conn)) {
        $conn->rollback();
    }
    writeOrderProcessingLog("Order processing failed", [
        'error_message' => $e->getMessage(),
        'error_trace' => $e->getTraceAsString(),
        'post_data' => $_POST
    ]);
    $_SESSION['error_message'] = "Lỗi khi đặt hàng: " . $e->getMessage();
    header("Location: checkout.php");
    exit();
}

if (isset($conn)) {
    $conn->close();
}
writeOrderProcessingLog("Order processing completed");
?>