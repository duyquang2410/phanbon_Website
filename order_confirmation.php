<?php
session_start();
include 'connect.php';
require_once 'create_logs.php';
require_once 'cart_functions.php';

// Khởi tạo logger
$logger = Logger::getInstance('logs/order_confirmation.log');

// Debug - Log session và GET data
$logger->info('Session and GET data', [
    'session' => $_SESSION,
    'get' => $_GET
]);

// Kiểm tra đăng nhập và ID đơn hàng
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    $logger->error('Access denied - User not logged in or order ID not provided', [
        'user_id' => $_SESSION['user_id'] ?? null,
        'order_id' => $_GET['id'] ?? null
    ]);
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$order_id = $_GET['id'];

// Khởi tạo biến delivery_info
$delivery_info = null;
$delivery_status = 'NEW';

// Lấy thông tin giao hàng
try {
    $delivery_sql = "SELECT * FROM delivery_status WHERE HD_STT = ? ORDER BY updated_at DESC LIMIT 1";
    $delivery_stmt = $conn->prepare($delivery_sql);
    $delivery_stmt->bind_param("i", $order_id);
    $delivery_stmt->execute();
    $delivery_result = $delivery_stmt->get_result();
    
    if ($delivery_result && $delivery_result->num_rows > 0) {
        $delivery_info = $delivery_result->fetch_assoc();
        $delivery_status = $delivery_info['status'];
    }
    
    $delivery_stmt->close();
} catch (Exception $e) {
    $logger->error('Error fetching delivery status', [
        'error' => $e->getMessage(),
        'order_id' => $order_id
    ]);
}

// Log thông tin đơn hàng được truy cập
$logger->info('Accessing order confirmation page', [
    'user_id' => $user_id,
    'order_id' => $order_id
]);

try {
    // Lấy thông tin đơn hàng
    $order_sql = "SELECT hd.*, kh.KH_TEN, kh.KH_SDT, kh.KH_EMAIL, kh.KH_DIACHI, 
                  pttt.PTTT_TEN, pttt.PTTT_MA,
                  tt.TT_TEN as trang_thai,
                  hd.HD_PHISHIP as shipping_fee,
                  hd.HD_TONGTIEN as total_amount,
                  hd.HD_GIAMGIA as discount_amount,
                  km.Code as promo_code,
                  hd.HD_NGAYLAP as HD_NGAYDAT
                  FROM hoa_don hd 
                  JOIN khach_hang kh ON hd.KH_MA = kh.KH_MA 
                  JOIN phuong_thuc_thanh_toan pttt ON hd.PTTT_MA = pttt.PTTT_MA
                  JOIN trang_thai tt ON hd.TT_MA = tt.TT_MA
                  LEFT JOIN khuyen_mai km ON hd.KM_MA = km.KM_MA
                  LEFT JOIN don_van_chuyen dvc ON hd.DVC_MA = dvc.DVC_MA
                  WHERE hd.HD_STT = ? AND hd.KH_MA = ?";

    $stmt = $conn->prepare($order_sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ii", $order_id, $user_id);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    $order_result = $stmt->get_result();
    if ($order_result->num_rows === 0) {
        $logger->error('Order not found', [
            'user_id' => $user_id,
            'order_id' => $order_id,
            'sql' => $order_sql
        ]);
        header("Location: index.php");
        exit();
    }

    $order = $order_result->fetch_assoc();
    
    // Debug log để kiểm tra dữ liệu đơn hàng
    $logger->info('Order data retrieved', [
        'order_id' => $order_id,
        'order_data' => $order
    ]);

    // Lấy thông tin địa chỉ giao hàng
    $shipping_address = '';
    $recipient_name = '';
    $recipient_phone = '';
    $recipient_email = '';
    $shipping_notes = '';
    
    try {
        $address_sql = "SELECT * FROM dia_chi_giao_hang WHERE DH_MA = ? ORDER BY DCGH_MA DESC LIMIT 1";
        $addr_stmt = $conn->prepare($address_sql);
        if (!$addr_stmt) {
            throw new Exception("Prepare address query failed: " . $conn->error);
        }
        $addr_stmt->bind_param("i", $order_id);
        if (!$addr_stmt->execute()) {
            throw new Exception("Execute address query failed: " . $addr_stmt->error);
        }
        $addr_result = $addr_stmt->get_result();
        if ($addr_result->num_rows > 0) {
            $addr_data = $addr_result->fetch_assoc();
            // Sử dụng địa chỉ đầy đủ đã được lưu
            $shipping_address = $addr_data['DCGH_DIACHI'];
            $recipient_name = $addr_data['DCGH_TENNGUOINHAN'];
            $recipient_phone = $addr_data['DCGH_SDT'];
            $recipient_email = $addr_data['DCGH_EMAIL'];
            $shipping_notes = $addr_data['DCGH_GHICHU'];
        } else {
            // Sử dụng thông tin mặc định từ khách hàng
            $shipping_address = $order['KH_DIACHI'];
            $recipient_name = $order['KH_TEN'];
            $recipient_phone = $order['KH_SDT'];
            $recipient_email = $order['KH_EMAIL'];
        }
    } catch (Exception $e) {
        $logger->error('Error processing shipping address', [
            'error' => $e->getMessage(),
            'order_id' => $order_id
        ]);
    }

    // Lấy chi tiết đơn hàng
    $items_sql = "SELECT cthd.*, sp.SP_TEN, sp.SP_HINHANH, sp.SP_DONVITINH, sp.SP_DONGIA AS gia_goc,
                  hd.KM_MA,
                  CASE 
                    WHEN km.hinh_thuc_km = 'percent' THEN LEAST(cthd.CTHD_DONGIA * cthd.CTHD_SOLUONG, cthd.CTHD_DONGIA * cthd.CTHD_SOLUONG * km.KM_GIATRI / 100)
                    WHEN km.hinh_thuc_km = 'fixed' THEN LEAST(km.KM_GIATRI, cthd.CTHD_DONGIA * cthd.CTHD_SOLUONG)
                    ELSE 0 
                  END as item_discount,
                  km.KM_GIATRI,
                  km.hinh_thuc_km
                  FROM chi_tiet_hd cthd 
                  JOIN san_pham sp ON cthd.SP_MA = sp.SP_MA
                  JOIN hoa_don hd ON cthd.HD_STT = hd.HD_STT
                  LEFT JOIN khuyen_mai km ON hd.KM_MA = km.KM_MA
                  WHERE cthd.HD_STT = ?";

    $items_stmt = $conn->prepare($items_sql);
    if (!$items_stmt) {
        $logger->error('Failed to prepare items query', [
            'error' => $conn->error,
            'query' => $items_sql
        ]);
        throw new Exception("Failed to prepare items query: " . $conn->error);
    }

    $items_stmt->bind_param("i", $order_id);
    if (!$items_stmt->execute()) {
        $logger->error('Failed to execute items query', [
            'error' => $items_stmt->error,
            'order_id' => $order_id
        ]);
        throw new Exception("Failed to execute items query: " . $items_stmt->error);
    }

    $items_result = $items_stmt->get_result();
    $order_items = [];
    $total_discount = 0;
    $has_global_discount = !empty($order['discount_type']) && !empty($order['discount_value']);

    // Log số lượng kết quả trả về
    $logger->info('Items query result count', [
        'order_id' => $order_id,
        'count' => $items_result->num_rows
    ]);

    while ($item = $items_result->fetch_assoc()) {
        // Log từng item để debug
        $logger->info('Processing order item', [
            'order_id' => $order_id,
            'item_data' => $item
        ]);

        $item_total = $item['CTHD_DONGIA'] * $item['CTHD_SOLUONG'];
        $item_discount = $item['item_discount'] ?? 0;
        $order_items[] = [
            'name' => $item['SP_TEN'],
            'image' => $item['SP_HINHANH'],
            'quantity' => $item['CTHD_SOLUONG'],
            'price' => $item['CTHD_DONGIA'],
            'gia_goc' => $item['CTHD_GIAGOC'],
            'unit' => $item['SP_DONVITINH'],
            'total' => $item_total,
            'discount' => $item_discount,
            'discount_type' => $item['hinh_thuc_km'],
            'discount_value' => $item['KM_GIATRI']
        ];
        if (!$has_global_discount) {
            $total_discount += $item_discount;
        }
    }

    // Nếu có khuyến mãi tổng đơn, chỉ tính giảm giá tổng đơn
    if ($has_global_discount) {
        $discount_type = $order['discount_type'];
        $discount_value = $order['discount_value'];
        $gia_goc = 0;
        foreach ($order_items as $item) {
            $gia_goc += $item['gia_goc'] * $item['quantity'];
        }
        $total_discount = calculateDiscount($gia_goc, $discount_type, $discount_value);
    }

    // Debug log chi tiết đơn hàng
    $logger->info('Order details retrieved', [
        'order_id' => $order_id,
        'details' => $order_items
    ]);

} catch (Exception $e) {
    $logger->error('Error processing order confirmation', [
        'error' => $e->getMessage(),
        'order_id' => $order_id
    ]);
    // Hiển thị thông báo lỗi thân thiện với người dùng
    echo "<div class='alert alert-danger'>Có lỗi xảy ra khi xử lý đơn hàng. Vui lòng thử lại sau.</div>";
    exit();
}

// Log thông tin khuyến mãi
if (isset($order['promo_code'])) {
    $logger->info('Promotion code applied', [
        'order_id' => $order_id,
        'promo_code' => $order['promo_code'],
        'discount_percentage' => isset($order['discount_percentage']) ? $order['discount_percentage'] : null,
        'discount_amount' => $order['discount_amount']
    ]);
} else {
    $logger->info('No promotion code applied', ['order_id' => $order_id]);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác Nhận Đơn Hàng #<?php echo $order_id; ?></title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-size: 15px;
            color: #333;
        }
        .order-confirmation {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 15px;
        }
        .info-card {
            background: white;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .card-title {
            color: #28a745;
            font-size: 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .card-title i {
            color: #28a745;
        }
        .info-row {
            margin-bottom: 15px;
        }
        .info-label {
            font-weight: 600;
            color: #555;
            margin-bottom: 5px;
        }
        .info-value {
            color: #333;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            background: #28a745;
            color: white;
            border-radius: 4px;
            font-size: 14px;
        }
        .product-item {
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        .product-item:last-child {
            border-bottom: none;
        }
        .product-image {
            width: 100px;
            height: auto;
            border-radius: 4px;
        }
        .product-name {
            font-size: 16px;
            color: #333;
            margin-bottom: 5px;
            font-weight: 500;
        }
        .product-unit {
            color: #666;
            font-size: 14px;
        }
        .product-price {
            font-size: 15px;
            color: #333;
            text-align: right;
        }
        .discount-price {
            color: #dc3545;
            font-size: 14px;
        }
        .summary-table {
            width: 100%;
            margin-top: 20px;
        }
        .summary-table td {
            padding: 8px 0;
            color: #333;
        }
        .summary-table td:last-child {
            text-align: right;
            font-weight: 500;
        }
        .total-row td {
            font-weight: 600;
            font-size: 16px;
            padding-top: 15px;
            border-top: 2px solid #eee;
        }
        .btn-action {
            padding: 8px 20px;
            font-size: 14px;
            border-radius: 4px;
        }
        .quantity-badge {
            background: #f8f9fa;
            padding: 4px 8px;
            border-radius: 4px;
            color: #666;
            font-size: 14px;
        }
        .cancel-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .cancel-btn:hover {
            background-color: #c82333;
        }
        .cancel-btn:disabled {
            background-color: #6c757d;
            cursor: not-allowed;
        }
        .modal-header {
            background-color: #dc3545;
            color: white;
        }
        .modal-title {
            color: white;
        }
        .btn-close {
            color: white;
        }
        @media (max-width: 768px) {
            .product-image {
                width: 80px;
            }
            .product-name {
                font-size: 14px;
            }
        }

        /* Thêm styles cho phần theo dõi giao hàng */
        .delivery-track {
            position: relative;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            margin: 15px 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .delivery-progress {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            margin-bottom: 30px;
        }

        .progress-step {
            position: relative;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #fff;
            border: 2px solid #e91e63;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .progress-step.active {
            background: #e91e63;
            color: #fff;
        }

        .progress-step.completed {
            background: #4CAF50;
            border-color: #4CAF50;
            color: #fff;
        }

        .progress-line {
            position: absolute;
            top: 15px;
            left: 0;
            right: 0;
            height: 2px;
            background: #e0e0e0;
            z-index: 1;
        }

        .progress-line-fill {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            background: #e91e63;
            transition: width 0.5s ease;
        }

        .delivery-vehicle {
            position: absolute;
            top: -10px;
            left: 0;
            transform: translateX(-50%);
            z-index: 3;
            transition: left 0.5s ease;
        }

        .delivery-vehicle i {
            font-size: 24px;
            color: #e91e63;
            animation: bounce 1s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        .step-label {
            position: absolute;
            top: 40px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 12px;
            white-space: nowrap;
            color: #666;
        }

        .delivery-info {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-top: 20px;
        }

        .delivery-info-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 8px;
            border-bottom: 1px solid #eee;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .delivery-info-item i {
            margin-right: 10px;
            color: #e91e63;
        }

        .delivery-info-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="order-confirmation">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="mb-3">Xác Nhận Đơn Hàng #<?php echo $order_id; ?></h2>
                    <?php
                    // Kiểm tra điều kiện hiển thị nút hủy
                    $order_time = strtotime($order['HD_NGAYDAT']);
                    $current_time = time();
                    $time_diff = $current_time - $order_time;
                    $within_24h = $time_diff <= 24 * 60 * 60;
                    
                    // Kiểm tra trạng thái đơn hàng - cho phép hủy khi đơn hàng đang "Chờ xác nhận" hoặc "Chờ giao"
                    $can_cancel = ($order['trang_thai'] === 'Chờ xác nhận' || $order['trang_thai'] === 'Chờ giao');
                    
                    // Debug information
                    $logger->info('Cancel button conditions:', [
                        'order_status' => $order['trang_thai'],
                        'order_date' => $order['HD_NGAYDAT'],
                        'current_time' => date('Y-m-d H:i:s'),
                        'time_diff' => $time_diff,
                        'within_24h' => $within_24h,
                        'can_cancel' => $can_cancel
                    ]);
                    
                    if ($can_cancel && $within_24h): 
                    ?>
                    <button type="button" 
                            class="cancel-btn" 
                            data-bs-toggle="modal" 
                            data-bs-target="#cancelOrderModal">
                        <i class="fa fa-times"></i> Hủy Đơn Hàng
                    </button>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="row">
                <!-- Left Column - Customer Information -->
                <div class="col-md-4">
                    <!-- Shipping Information -->
                    <div class="info-card">
                        <h2 class="card-title">
                            <i class="fa fa-truck"></i>
                            Thông Tin Giao Hàng
                        </h2>
                        <div class="info-row">
                            <div class="info-label">Người nhận:</div>
                            <div class="info-value"><?php echo htmlspecialchars($recipient_name); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Số điện thoại:</div>
                            <div class="info-value"><?php echo htmlspecialchars($recipient_phone); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Email:</div>
                            <div class="info-value"><?php echo htmlspecialchars($recipient_email); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Địa chỉ:</div>
                            <div class="info-value"><?php echo htmlspecialchars($shipping_address); ?></div>
                        </div>
                        <?php if (!empty($shipping_notes)): ?>
                        <div class="info-row">
                            <div class="info-label">Ghi chú:</div>
                            <div class="info-value"><?php echo htmlspecialchars($shipping_notes); ?></div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Payment Information -->
                    <div class="info-card">
                        <h2 class="card-title">
                            <i class="fa fa-credit-card"></i>
                            Thông Tin Thanh Toán
                        </h2>
                        <div class="info-row">
                            <div class="info-label">Phương thức:</div>
                            <div class="info-value"><?php echo htmlspecialchars($order['PTTT_TEN']); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Trạng thái:</div>
                            <div class="info-value">
                                <span class="status-badge" style="background-color: <?php 
                                    $status = mb_strtolower($order['trang_thai'], 'UTF-8');
                                    if (strpos($status, 'chờ') !== false) echo '#ffc107';
                                    else if (strpos($status, 'đang') !== false) echo '#17a2b8';
                                    else if (strpos($status, 'đã giao') !== false) echo '#28a745';
                                    else if (strpos($status, 'hủy') !== false) echo '#dc3545';
                                    else echo '#6c757d';
                                ?>">
                                    <?php echo htmlspecialchars($order['trang_thai']); ?>
                                </span>
                            </div>
                        </div>
                        <?php if (!empty($order['promo_code'])): ?>
                        <div class="info-row">
                            <div class="info-label">Mã khuyến mãi:</div>
                            <div class="info-value">
                                <span class="badge bg-success">
                                    <i class="fa fa-tag me-1"></i>
                                    <?php echo htmlspecialchars($order['promo_code']); ?>
                                </span>
                                <?php if (isset($order['discount_percentage']) && $order['discount_percentage'] > 0): ?>
                                    <span class="text-danger ms-2">
                                        (Giảm <?php echo $order['discount_percentage']; ?>%)
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Right Column - Order Details -->
                <div class="col-md-8">
                    <div id="invoice-content">
                        <div class="info-card">
                            <h2 class="card-title">
                                <i class="fa fa-shopping-cart"></i>
                                Chi Tiết Đơn Hàng
                            </h2>
                        
                            <div class="order-header">
                                <h4 class="mb-0">
                                    <i class="fa fa-shopping-cart me-2"></i>
                                    Đơn hàng #<?php echo $order_id; ?>
                                </h4>
                                <p class="mb-0 mt-2">
                                    <span class="badge bg-info">
                                        <i class="fa fa-clock-o me-1"></i>
                                        <?php echo $order['trang_thai']; ?>
                                    </span>
                                </p>
                            </div>
                            
                            <?php foreach ($order_items as $item): ?>
                            <?php
                            // Kiểm tra đường dẫn hình ảnh
                            $image_filename = trim($item['image']);
                            $relative_path = 'img/' . $image_filename;
                            
                            $logger->info('Processing product image', [
                                'product_name' => $item['name'],
                                'image_filename' => $image_filename,
                                'relative_path' => $relative_path,
                                'file_exists' => file_exists($relative_path)
                            ]);
                            
                            // Kiểm tra file tồn tại
                            if (!empty($image_filename) && file_exists($relative_path)) {
                                $display_image = $relative_path;
                            } else {
                                $display_image = 'img/default-avatar.jpg';
                                $logger->warning('Product image not found, using default', [
                                    'product_name' => $item['name'],
                                    'image_filename' => $image_filename,
                                    'relative_path' => $relative_path
                                ]);
                            }
                            ?>
                            <div class="product-item">
                                <div class="row align-items-center">
                                    <div class="col-3 col-md-2">
                                        <img src="<?php echo htmlspecialchars($display_image); ?>" 
                                             alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                             class="product-image"
                                             onerror="this.src='img/default-avatar.jpg';">
                                    </div>
                                    <div class="col-9 col-md-6">
                                        <h3 class="product-name"><?php echo htmlspecialchars($item['name']); ?></h3>
                                     
                                       
                                       
                                    </div>
                                    <div class="col-4 col-md-2">
                                        <div class="quantity-badge">
                                            Số lượng: <?php echo $item['quantity']; ?>
                                        </div>
                                    </div>
                                    <div class="col-8 col-md-2">
                                        <div class="product-price">
                                            <?php echo number_format($item['total'], 0, ',', '.'); ?>đ
                                            <?php if ($item['discount'] > 0): ?>
                                            <div class="discount-price">
                                                -<?php echo number_format($item['discount'], 0, ',', '.'); ?>đ
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>

                            <?php
                            // Tổng tiền hàng thực tế đã mua (không gồm phí ship)
                            $tong_tien_hang = 0;
                            foreach ($order_items as $item) {
                                $tong_tien_hang += $item['price'] * $item['quantity'];
                            }
                            // Lấy số tiền giảm giá từ hóa đơn
                            $giam_gia = $order['HD_GIAMGIA'];
                            // Tổng thanh toán = (tổng tiền hàng - giảm giá) + phí vận chuyển
                            $tong_thanh_toan = max(0, $tong_tien_hang - $giam_gia) + $order['shipping_fee'];
                            ?>
                            <table class="summary-table">
                                <tr>
                                    <td>Tổng tiền hàng:</td>
                                    <td>
                                        <span style="color:#333;">
                                            <?php echo number_format($tong_tien_hang, 0, ',', '.'); ?>đ
                                        </span>
                                    </td>
                                </tr>
                                <?php if ($giam_gia > 0): ?>
                                <tr>
                                    <td>Khuyến mãi:</td>
                                    <td class="discount-price">
                                        -<?php echo number_format($giam_gia, 0, ',', '.'); ?>đ
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <td>Phí vận chuyển:</td>
                                    <td><?php echo number_format($order['shipping_fee'], 0, ',', '.'); ?>đ</td>
                                </tr>
                                <tr class="total-row">
                                    <td>Tổng thanh toán:</td>
                                    <td>
                                        <span style="color:#d63384; font-size:18px;">
                                            <?php echo number_format($tong_thanh_toan, 0, ',', '.'); ?>đ
                                        </span>
                                    </td>
                                </tr>
                            </table>

                            <!-- Thêm nút theo dõi vận chuyển -->
                            <?php if ($order['trang_thai'] === 'Đang giao' || $order['trang_thai'] === 'Đã giao'): ?>
                            <div class="text-center mt-4">
                                <button type="button" class="btn btn-primary btn-track-delivery" data-bs-toggle="modal" data-bs-target="#trackingModal">
                                    <i class="fas fa-truck me-2"></i>Theo dõi vận chuyển
                                </button>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <a href="generate_invoice.php?order_id=<?php echo $order_id; ?>" target="_blank" class="btn btn-danger btn-action me-2">
                            <i class="fa fa-print"></i> In hóa đơn PDF
                        </a>
                        <a href="index.php" class="btn btn-outline-primary btn-action me-2">
                            <i class="fa fa-home"></i> Trang Chủ
                        </a>
                        <a href="my_orders.php" class="btn btn-primary btn-action">
                            <i class="fa fa-list"></i> Xem Đơn Hàng Khác
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Theo Dõi Vận Chuyển -->
    <div class="modal fade" id="trackingModal" tabindex="-1" aria-labelledby="trackingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="trackingModalLabel">
                        <i class="fas fa-truck me-2"></i>Theo dõi vận chuyển đơn hàng #<?php echo $order_id; ?>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="delivery-track">
                        <div class="delivery-progress">
                            <div class="progress-line">
                                <?php
                                // Tính phần trăm hoàn thành
                                $progress = 0;
                                switch($delivery_status) {
                                    case 'NEW': $progress = 0; break;
                                    case 'DELIVERING': $progress = 50; break;
                                    case 'DELIVERED': $progress = 100; break;
                                    default: $progress = 0;
                                }
                                ?>
                                <div class="progress-line-fill" style="width: <?php echo $progress; ?>%"></div>
                            </div>

                            <?php
                            $steps = [
                                ['icon' => 'box', 'label' => 'Đã xác nhận', 'completed' => true],
                                ['icon' => 'truck', 'label' => 'Đang giao', 'completed' => in_array($delivery_status, ['DELIVERING', 'DELIVERED'])],
                                ['icon' => 'check-circle', 'label' => 'Đã giao', 'completed' => $delivery_status === 'DELIVERED']
                            ];

                            foreach($steps as $index => $step):
                                $stepClass = $step['completed'] ? 'completed' : 
                                          ($delivery_status === 'DELIVERING' && $index === 1 ? 'active' : '');
                            ?>
                            <div class="progress-step <?php echo $stepClass; ?>">
                                <i class="fas fa-<?php echo $step['icon']; ?>"></i>
                                <span class="step-label"><?php echo $step['label']; ?></span>
                            </div>
                            <?php endforeach; ?>

                            <?php if ($delivery_status === 'DELIVERING'): ?>
                            <div class="delivery-vehicle" style="left: <?php echo $progress; ?>%">
                                <i class="fas fa-truck"></i>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="delivery-info mt-4" id="trackingInfo">
                            <?php if ($delivery_info && !empty($delivery_info['tracking_info'])): ?>
                                <?php
                                $tracking_info_array = explode("\n", $delivery_info['tracking_info']);
                                foreach ($tracking_info_array as $info):
                                    if (!empty($info)):
                                ?>
                                <div class="delivery-info-item">
                                    <i class="fas fa-info-circle"></i>
                                    <span><?php echo htmlspecialchars($info); ?></span>
                                </div>
                                <?php 
                                    endif;
                                endforeach;
                                ?>
                            <?php else: ?>
                                <div class="text-center text-muted">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Chưa có thông tin cập nhật
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Thêm styles cho nút và modal -->
    <style>
        .btn-track-delivery {
            background: linear-gradient(45deg, #2196F3, #1976D2);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .btn-track-delivery:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            background: linear-gradient(45deg, #1976D2, #1565C0);
        }

        .btn-track-delivery i {
            animation: truck-bounce 1s infinite;
        }

        @keyframes truck-bounce {
            0%, 100% { transform: translateX(0); }
            50% { transform: translateX(3px); }
        }

        #trackingModal .modal-content {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        #trackingModal .modal-header {
            border-radius: 10px 10px 0 0;
            background: linear-gradient(45deg, #2196F3, #1976D2);
        }

        #trackingModal .modal-body {
            padding: 2rem;
        }

        #trackingModal .delivery-track {
            margin: 0;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 8px;
        }

        #trackingModal .delivery-info {
            max-height: 300px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #90A4AE #CFD8DC;
        }

        #trackingModal .delivery-info::-webkit-scrollbar {
            width: 6px;
        }

        #trackingModal .delivery-info::-webkit-scrollbar-track {
            background: #CFD8DC;
        }

        #trackingModal .delivery-info::-webkit-scrollbar-thumb {
            background-color: #90A4AE;
            border-radius: 3px;
        }

        .delivery-info-item {
            animation: slideIn 0.3s ease-out forwards;
            opacity: 0;
            transform: translateY(10px);
        }

        @keyframes slideIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .delivery-info-item:nth-child(1) { animation-delay: 0.1s; }
        .delivery-info-item:nth-child(2) { animation-delay: 0.2s; }
        .delivery-info-item:nth-child(3) { animation-delay: 0.3s; }
        .delivery-info-item:nth-child(4) { animation-delay: 0.4s; }
        .delivery-info-item:nth-child(5) { animation-delay: 0.5s; }
    </style>

    <!-- Modal Hủy Đơn Hàng -->
    <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelOrderModalLabel">Xác Nhận Hủy Đơn Hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn hủy đơn hàng này không?</p>
                    <div class="mb-3">
                        <label for="cancelReason" class="form-label">Lý do hủy đơn:</label>
                        <textarea class="form-control" id="cancelReason" rows="3" placeholder="Vui lòng nhập lý do hủy đơn hàng..."></textarea>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fa fa-exclamation-triangle"></i> Lưu ý:
                        <ul class="mb-0">
                            <li>Đơn hàng chỉ có thể hủy trong vòng 24h kể từ khi đặt</li>
                            <li>Nếu đã thanh toán online, số tiền sẽ được hoàn trả trong 3-5 ngày làm việc</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-danger" id="confirmCancelBtn">Xác Nhận Hủy</button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    // Thêm function cập nhật trạng thái giao hàng
    function updateDeliveryStatus() {
        const orderId = <?php echo $order_id; ?>;
        
        $.ajax({
            url: 'update_delivery_status.php',
            method: 'GET',
            dataType: 'json',
            data: { order_id: orderId },
            success: function(response) {
                if (response.success && response.updated_orders && response.updated_orders.length > 0) {
                    response.updated_orders.forEach(function(order) {
                        if (order.order_id == orderId) {
                            // Cập nhật tiến trình
                            let progress = 0;
                            switch(order.status) {
                                case 'NEW': progress = 0; break;
                                case 'DELIVERING': progress = 50; break;
                                case 'DELIVERED': progress = 100; break;
                            }

                            // Cập nhật thanh tiến trình
                            $('.progress-line-fill').css('width', progress + '%');
                            
                            // Cập nhật các bước
                            $('.progress-step').removeClass('completed active');
                            $('.progress-step').each(function(index) {
                                if (index === 0 || 
                                    (index === 1 && ['DELIVERING', 'DELIVERED'].includes(order.status)) ||
                                    (index === 2 && order.status === 'DELIVERED')) {
                                    $(this).addClass('completed');
                                }
                                if (order.status === 'DELIVERING' && index === 1) {
                                    $(this).addClass('active');
                                }
                            });

                            // Cập nhật xe giao hàng
                            $('.delivery-vehicle').remove();
                            if (order.status === 'DELIVERING') {
                                const vehicle = $('<div class="delivery-vehicle" style="left: ' + progress + '%"><i class="fas fa-truck"></i></div>');
                                $('.delivery-progress').append(vehicle);
                            }

                            // Cập nhật thông tin chi tiết
                            let infoHtml = '';
                            order.tracking_info.split('\n').forEach(function(info) {
                                if (info.trim()) {
                                    infoHtml += '<div class="delivery-info-item">';
                                    infoHtml += '<i class="fas fa-info-circle"></i>';
                                    infoHtml += '<span>' + info + '</span>';
                                    infoHtml += '</div>';
                                }
                            });
                            $('.delivery-info').html(infoHtml);

                            // Nếu đã giao hàng, hiển thị thông báo
                            if (order.status === 'DELIVERED') {
                                alert('Đơn hàng đã được giao thành công!');
                                location.reload(); // Tải lại trang để cập nhật trạng thái
                            }
                        }
                    });
                }
            }
        });
    }

    // Cập nhật mỗi 10 giây
    setInterval(updateDeliveryStatus, 10000);

    // Chạy lần đầu khi tải trang
    $(document).ready(function() {
        updateDeliveryStatus();
    });

    document.addEventListener('DOMContentLoaded', function() {
        const confirmCancelBtn = document.getElementById('confirmCancelBtn');
        const cancelReasonInput = document.getElementById('cancelReason');
        const cancelModal = new bootstrap.Modal(document.getElementById('cancelOrderModal'));
        
        if (confirmCancelBtn) {
            confirmCancelBtn.addEventListener('click', function() {
                const reason = cancelReasonInput.value.trim();
                
                if (!reason) {
                    alert('Vui lòng nhập lý do hủy đơn hàng');
                    return;
                }
                
                // Disable nút để tránh click nhiều lần
                confirmCancelBtn.disabled = true;
                confirmCancelBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Đang xử lý...';
                
                // Gửi request hủy đơn
                fetch('process_cancel_order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `order_id=<?php echo $order_id; ?>&cancel_reason=${encodeURIComponent(reason)}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        // Reload trang sau khi hủy thành công
                        window.location.reload();
                    } else {
                        alert(data.message || 'Có lỗi xảy ra khi hủy đơn hàng');
                        // Reset nút
                        confirmCancelBtn.disabled = false;
                        confirmCancelBtn.innerHTML = 'Xác Nhận Hủy';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi hủy đơn hàng');
                    // Reset nút
                    confirmCancelBtn.disabled = false;
                    confirmCancelBtn.innerHTML = 'Xác Nhận Hủy';
                })
                .finally(() => {
                    cancelModal.hide();
                });
            });
        }
    });

    // Thêm function cập nhật modal
    function updateTrackingModal(orderData) {
        // Cập nhật tiến trình
        let progress = 0;
        switch(orderData.status) {
            case 'NEW': progress = 0; break;
            case 'DELIVERING': progress = 50; break;
            case 'DELIVERED': progress = 100; break;
        }

        // Cập nhật thanh tiến trình trong modal
        $('#trackingModal .progress-line-fill').css('width', progress + '%');
        
        // Cập nhật các bước trong modal
        $('#trackingModal .progress-step').removeClass('completed active');
        $('#trackingModal .progress-step').each(function(index) {
            if (index === 0 || 
                (index === 1 && ['DELIVERING', 'DELIVERED'].includes(orderData.status)) ||
                (index === 2 && orderData.status === 'DELIVERED')) {
                $(this).addClass('completed');
            }
            if (orderData.status === 'DELIVERING' && index === 1) {
                $(this).addClass('active');
            }
        });

        // Cập nhật xe giao hàng trong modal
        $('#trackingModal .delivery-vehicle').remove();
        if (orderData.status === 'DELIVERING') {
            const vehicle = $('<div class="delivery-vehicle" style="left: ' + progress + '%"><i class="fas fa-truck"></i></div>');
            $('#trackingModal .delivery-progress').append(vehicle);
        }

        // Cập nhật thông tin chi tiết trong modal
        let infoHtml = '';
        orderData.tracking_info.split('\n').forEach(function(info) {
            if (info.trim()) {
                infoHtml += '<div class="delivery-info-item">';
                infoHtml += '<i class="fas fa-info-circle"></i>';
                infoHtml += '<span>' + info + '</span>';
                infoHtml += '</div>';
            }
        });
        $('#trackingModal .delivery-info').html(infoHtml);
    }

    // Cập nhật function updateDeliveryStatus để sử dụng modal
    function updateDeliveryStatus() {
        const orderId = <?php echo $order_id; ?>;
        
        $.ajax({
            url: 'update_delivery_status.php',
            method: 'GET',
            dataType: 'json',
            data: { order_id: orderId },
            success: function(response) {
                if (response.success && response.updated_orders && response.updated_orders.length > 0) {
                    response.updated_orders.forEach(function(order) {
                        if (order.order_id == orderId) {
                            updateTrackingModal(order);

                            // Nếu đã giao hàng, hiển thị thông báo
                            if (order.status === 'DELIVERED') {
                                Swal.fire({
                                    title: 'Giao hàng thành công!',
                                    text: 'Đơn hàng của bạn đã được giao thành công',
                                    icon: 'success',
                                    confirmButtonText: 'Đóng'
                                }).then((result) => {
                                    location.reload();
                                });
                            }
                        }
                    });
                }
            }
        });
    }

    // Cập nhật khi mở modal
    $('#trackingModal').on('show.bs.modal', function (e) {
        updateDeliveryStatus();
    });

    // Cập nhật mỗi 10 giây khi modal đang mở
    setInterval(function() {
        if ($('#trackingModal').hasClass('show')) {
            updateDeliveryStatus();
        }
    }, 10000);
    </script>
</body>
</html>
<?php
$stmt->close();
$items_stmt->close();
$conn->close();
?> 