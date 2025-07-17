process_order.php<?php
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

    // Lấy thông tin chi tiết các sản phẩm
    $cart_data = getCartItems($conn, $cart_id);

    $total_amount_hang = 0; // Tổng tiền hàng (không gồm phí ship)
    $valid_items = [];
    foreach ($cart_data['items'] as $item) {
        if (in_array($item['id'], $selected_items)) {
            $total_amount_hang += $item['price'] * $item['quantity'];
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

    // Bắt đầu transaction
    $conn->begin_transaction();

    // Tính số tiền giảm giá thực tế (chỉ trên tổng tiền hàng, không gồm phí ship)
    $discount_amount = 0;
    if ($promo_code) {
        $discount_sql = "SELECT KM_GIATRI, hinh_thuc_km, KM_DKSD FROM khuyen_mai WHERE KM_MA = ? AND KM_TRANGTHAI = 1";
        $discount_stmt = $conn->prepare($discount_sql);
        if ($discount_stmt) {
            $discount_stmt->bind_param("i", $promo_code);
            if ($discount_stmt->execute()) {
                $discount_result = $discount_stmt->get_result();
                if ($discount_result->num_rows > 0) {
                    $discount_data = $discount_result->fetch_assoc();
                    if ($total_amount_hang >= $discount_data['KM_DKSD']) {
                        if ($discount_data['hinh_thuc_km'] == 'percent') {
                            if ($discount_data['KM_GIATRI'] > 0 && $discount_data['KM_GIATRI'] <= 100) {
                                $discount_amount = round($total_amount_hang * ($discount_data['KM_GIATRI'] / 100));
                            }
                        } else if ($discount_data['hinh_thuc_km'] == 'fixed') {
                            if ($discount_data['KM_GIATRI'] > 0) {
                                $discount_amount = min($discount_data['KM_GIATRI'], $total_amount_hang);
                            }
                        }
                    }
                }
            }
            $discount_stmt->close();
        }
    }

    // Tổng tiền hàng gốc (không gồm phí ship)
    $total_amount_goc = $total_amount_hang;

    // Tạo đơn hàng mới
    $order_sql = "INSERT INTO hoa_don (KH_MA, PTTT_MA, TT_MA, NV_MA, HD_NGAYLAP, HD_DIACHI, HD_SDT, HD_EMAIL, HD_TONGTIEN, HD_PHISHIP, KM_MA, HD_GIAMGIA) 
                  VALUES (?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?)";
    $order_stmt = $conn->prepare($order_sql);
    if (!$order_stmt) {
        writeOrderProcessingLog("Failed to prepare order statement", ['error' => $conn->error]);
        throw new Exception("Lỗi khi chuẩn bị tạo đơn hàng: " . $conn->error);
    }

    // Chỉ lưu số nhà và tên đường vào HD_DIACHI
    $order_stmt->bind_param("iiissssiddi", 
        $user_id,
        $payment_method,
        $tt_ma,
        $nv_ma,
        $street_address, // Chỉ lưu địa chỉ đường/số nhà
        $phone,
        $email,
        $total_amount_goc,
        $shipping_fee,
        $promo_code,
        $discount_amount
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
    $shipping_address_sql = "INSERT INTO dia_chi_giao_hang (DH_MA, DCGH_TENNGUOINHAN, DCGH_SDT, DCGH_EMAIL, DCGH_DIACHI, DCGH_TINH, DCGH_HUYEN, DCGH_XA, DCGH_GHICHU) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $shipping_address_stmt = $conn->prepare($shipping_address_sql);
    if (!$shipping_address_stmt) {
        writeOrderProcessingLog("Failed to prepare shipping address statement", ['error' => $conn->error]);
        throw new Exception("Lỗi khi chuẩn bị lưu địa chỉ giao hàng: " . $conn->error);
    }

    // Lưu thông tin địa chỉ đầy đủ vào bảng dia_chi_giao_hang
    $shipping_address_stmt->bind_param("issssiiis", 
        $order_id,
        $fullName,
        $phone,
        $email,
        $full_address, // Lưu địa chỉ đầy đủ
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

        // Lấy giá gốc từ bảng san_pham
        $giagoc_sql = "SELECT SP_DONGIA FROM san_pham WHERE SP_MA = ?";
        $giagoc_stmt = $conn->prepare($giagoc_sql);
        $giagoc_stmt->bind_param("i", $product_id);
        $giagoc_stmt->execute();
        $giagoc_stmt->bind_result($gia_goc);
        $giagoc_stmt->fetch();
        $giagoc_stmt->close();

        $detail_sql = "INSERT INTO chi_tiet_hd (SP_MA, HD_STT, CTHD_SOLUONG, CTHD_DONGIA, CTHD_DONVITINH, CTHD_GIAGOC) 
                       VALUES (?, ?, ?, ?, ?, ?)";
        $detail_stmt = $conn->prepare($detail_sql);
        if (!$detail_stmt) {
            writeOrderProcessingLog("Failed to prepare detail statement", ['error' => $conn->error]);
            throw new Exception("Lỗi khi chuẩn bị thêm chi tiết đơn hàng: " . $conn->error);
        }

        $detail_stmt->bind_param("iiddsd", 
            $product_id,
            $order_id,
            $quantity,
            $price,
            $unit,
            $gia_goc
        );
        
        if (!$detail_stmt->execute()) {
            writeOrderProcessingLog("Failed to execute detail insert", [
                'error' => $detail_stmt->error,
                'product_id' => $product_id,
                'order_id' => $order_id
            ]);
            throw new Exception("Lỗi khi thêm chi tiết đơn hàng: " . $detail_stmt->error);
        }

        $detail_stmt->close();

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

    // Áp dụng giảm giá nếu có
    if ($promo_code) {
        $discount_sql = "SELECT KM_GIATRI, hinh_thuc_km, KM_DKSD FROM khuyen_mai WHERE KM_MA = ? AND KM_TRANGTHAI = 1";
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
                'original_total' => $total_amount_goc
            ]);

            // Kiểm tra điều kiện tối thiểu
            if ($total_amount_goc < $discount_data['KM_DKSD']) {
                writeOrderProcessingLog("Order value too low for discount", [
                    'required' => $discount_data['KM_DKSD'],
                    'current' => $total_amount_goc
                ]);
                throw new Exception("Giá trị đơn hàng chưa đủ điều kiện áp dụng mã giảm giá. Tối thiểu " . 
                    number_format($discount_data['KM_DKSD'], 0, ',', '.') . 'đ');
            }

            $discount_amount = 0;
            if ($discount_data['hinh_thuc_km'] == 'percent') {
                if ($discount_data['KM_GIATRI'] > 0 && $discount_data['KM_GIATRI'] <= 100) {
                    $discount_amount = $total_amount_goc * ($discount_data['KM_GIATRI'] / 100);
                } else {
                    writeOrderProcessingLog("Invalid percentage discount value", [
                        'value' => $discount_data['KM_GIATRI']
                    ]);
                }
            } else if ($discount_data['hinh_thuc_km'] == 'fixed') {
                if ($discount_data['KM_GIATRI'] > 0) {
                    $discount_amount = min($discount_data['KM_GIATRI'], $total_amount_goc);
                } else {
                    writeOrderProcessingLog("Invalid direct discount value", [
                        'value' => $discount_data['KM_GIATRI'],
                        'total_amount' => $total_amount_goc
                    ]);
                }
            }

            // Đảm bảo số tiền giảm giá không âm và không vượt quá tổng tiền
            $discount_amount = max(0, min($discount_amount, $total_amount_goc));
            // Không update lại HD_TONGTIEN!
            // $total_amount = max(0, $total_amount_goc - $discount_amount);
            // Nếu muốn lưu giảm giá vào DB, có thể thêm trường HD_GIAMGIA
        }
        $discount_stmt->close();
    }

    // Thêm địa chỉ giao hàng
    $insert_address_sql = "INSERT INTO dia_chi_giao_hang (DH_MA, DCGH_TINH, DCGH_HUYEN, DCGH_XA, DCGH_DIACHI, DCGH_TENNGUOINHAN, DCGH_SDT, DCGH_EMAIL, DCGH_GHICHU) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $insert_address_stmt = $conn->prepare($insert_address_sql);
    if (!$insert_address_stmt) {
        writeOrderProcessingLog("Failed to prepare address statement", ['error' => $conn->error]);
        throw new Exception("Lỗi khi chuẩn bị thêm địa chỉ giao hàng: " . $conn->error);
    }
    $insert_address_stmt->bind_param("iiissssss", $order_id, $province, $district, $ward, $full_address, $fullName, $phone, $email, $note);
    if (!$insert_address_stmt->execute()) {
        writeOrderProcessingLog("Failed to execute address insert", [
            'error' => $insert_address_stmt->error,
            'order_id' => $order_id
        ]);
        throw new Exception("Lỗi khi thêm địa chỉ giao hàng: " . $insert_address_stmt->error);
    }
    $insert_address_stmt->close();

    // Commit transaction
    $conn->commit();
    writeOrderProcessingLog("Transaction committed successfully", [
        'order_id' => $order_id,
        'total_amount' => $total_amount_goc,
        'shipping_fee' => $shipping_fee,
        'discount_amount' => isset($discount_amount) ? $discount_amount : 0
    ]);

    // Kiểm tra phương thức thanh toán
    if ($payment_method == 2) { // 2 là chuyển khoản ngân hàng
        require_once __DIR__ . '/thanhtoan/PaymenVnpayClass.php';
        $vnpay = new payment_vnpay();
        // Tổng tiền cần thanh toán là (tổng gốc + phí ship) - giảm giá
        $order_price = ($total_amount_goc + $shipping_fee) - $total_discount;
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