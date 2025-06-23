<?php
session_start();
include 'connect.php';
require_once 'create_logs.php';

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
                  km.KM_GIATRI as discount_percentage,
                  km.Code as promo_code,
                  CASE 
                    WHEN km.KM_GIATRI IS NOT NULL THEN LEAST(hd.HD_TONGTIEN, hd.HD_TONGTIEN * km.KM_GIATRI / 100)
                    ELSE 0 
                  END as discount_amount,
                  CASE 
                    WHEN km.KM_GIATRI IS NOT NULL THEN GREATEST(0, hd.HD_TONGTIEN - LEAST(hd.HD_TONGTIEN, hd.HD_TONGTIEN * km.KM_GIATRI / 100))
                    ELSE hd.HD_TONGTIEN 
                  END as final_amount
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
        $address_sql = "SELECT * FROM dia_chi_giao_hang WHERE DH_MA = ?";
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
            
            // Debug log địa chỉ giao hàng
            $logger->info('Address data retrieved', [
                'order_id' => $order_id,
                'address_data' => $addr_data
            ]);
            
            // Lấy tên tỉnh/thành phố
            $province_name = '';
            $province_response = file_get_contents("http://localhost/LVTN_PhanBon/viettelpost_api.php?endpoint=categories/listProvince");
            $province_data = json_decode($province_response, true);
            if ($province_data && isset($province_data['data'])) {
                foreach ($province_data['data'] as $province) {
                    if ($province['PROVINCE_ID'] == $addr_data['DCGH_TINH']) {
                        $province_name = $province['PROVINCE_NAME'];
                        break;
                    }
                }
            }

            // Lấy tên quận/huyện
            $district_name = '';
            if ($province_name) {
                $district_response = file_get_contents("http://localhost/LVTN_PhanBon/viettelpost_api.php?endpoint=categories/listDistrict&provinceId=" . $addr_data['DCGH_TINH']);
                $district_data = json_decode($district_response, true);
                if ($district_data && isset($district_data['data'])) {
                    foreach ($district_data['data'] as $district) {
                        if ($district['DISTRICT_ID'] == $addr_data['DCGH_HUYEN']) {
                            $district_name = $district['DISTRICT_NAME'];
                            break;
                        }
                    }
                }
            }

            // Lấy tên phường/xã
            $ward_name = '';
            if ($district_name) {
                $ward_response = file_get_contents("http://localhost/LVTN_PhanBon/viettelpost_api.php?endpoint=categories/listWards&districtId=" . $addr_data['DCGH_HUYEN']);
                $ward_data = json_decode($ward_response, true);
                if ($ward_data && isset($ward_data['data'])) {
                    foreach ($ward_data['data'] as $ward) {
                        if ($ward['WARDS_ID'] == $addr_data['DCGH_XA']) {
                            $ward_name = $ward['WARDS_NAME'];
                            break;
                        }
                    }
                }
            }
            
            // Xử lý địa chỉ chỉ khi có giá trị
            $address_parts = [];
            if (!empty(trim($addr_data['DCGH_DIACHI']))) {
                $address_parts[] = trim($addr_data['DCGH_DIACHI']);
            }
            if (!empty($ward_name)) {
                $address_parts[] = $ward_name;
            }
            if (!empty($district_name)) {
                $address_parts[] = $district_name;
            }
            if (!empty($province_name)) {
                $address_parts[] = $province_name;
            }
            
            $shipping_address = implode(', ', $address_parts);
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
            
            $logger->warning('No specific shipping address found, using default customer address', [
                'order_id' => $order_id,
                'customer_id' => $user_id,
                'default_address' => $shipping_address
            ]);
        }
    } catch (Exception $e) {
        $logger->error('Error processing shipping address', [
            'error' => $e->getMessage(),
            'order_id' => $order_id
        ]);
    }

    // Lấy chi tiết đơn hàng
    $items_sql = "SELECT cthd.*, sp.SP_TEN, sp.SP_HINHANH, sp.SP_DONVITINH,
                  hd.KM_MA,
                  CASE 
                    WHEN km.hinh_thuc_km = 'Giảm phần trăm' THEN LEAST(cthd.CTHD_DONGIA * cthd.CTHD_SOLUONG, cthd.CTHD_DONGIA * cthd.CTHD_SOLUONG * km.KM_GIATRI / 100)
                    WHEN km.hinh_thuc_km = 'Giảm trực tiếp' THEN LEAST(km.KM_GIATRI, cthd.CTHD_DONGIA * cthd.CTHD_SOLUONG)
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
        $total_discount += $item_discount;
        
        $order_items[] = [
            'name' => $item['SP_TEN'],
            'image' => $item['SP_HINHANH'],
            'quantity' => $item['CTHD_SOLUONG'],
            'price' => $item['CTHD_DONGIA'],
            'unit' => $item['SP_DONVITINH'],
            'total' => $item_total,
            'discount' => $item_discount,
            'discount_type' => $item['hinh_thuc_km'],
            'discount_value' => $item['KM_GIATRI']
        ];
    }

    // Tính tổng giảm giá (bao gồm cả giảm giá chung và giảm giá từng sản phẩm)
    $total_discount += isset($order['discount_amount']) ? $order['discount_amount'] : 0;

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
        'discount_percentage' => $order['discount_percentage'],
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
        @media (max-width: 768px) {
            .product-image {
                width: 80px;
            }
            .product-name {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container order-confirmation">
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
                            <?php if ($order['discount_percentage'] > 0): ?>
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
                                <div class="product-unit">Đơn vị: <?php echo htmlspecialchars($item['unit']); ?></div>
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

                    <table class="summary-table">
                        <tr>
                            <td>Tổng tiền hàng:</td>
                            <td><?php echo number_format($order['total_amount'], 0, ',', '.'); ?>đ</td>
                        </tr>
                        <?php if ($order['discount_amount'] > 0): ?>
                        <tr>
                            <td>Giảm giá:</td>
                            <td class="discount-price">
                                -<?php echo number_format($order['discount_amount'], 0, ',', '.'); ?>đ
                            </td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td>Phí vận chuyển:</td>
                            <td><?php echo number_format($order['shipping_fee'], 0, ',', '.'); ?>đ</td>
                        </tr>
                        <tr class="total-row">
                            <td>Tổng thanh toán:</td>
                            <td><?php echo number_format($order['final_amount'] + $order['shipping_fee'], 0, ',', '.'); ?>đ</td>
                        </tr>
                    </table>
                </div>

                <div class="text-center mt-4">
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

    <?php include 'footer.php'; ?>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$stmt->close();
$items_stmt->close();
$conn->close();
?> 