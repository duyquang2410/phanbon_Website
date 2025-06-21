<?php
session_start();
include 'connect.php';

// Kiểm tra đăng nhập và ID đơn hàng
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$order_id = $_GET['id'];

// Lấy thông tin đơn hàng
$order_sql = "SELECT hd.*, kh.KH_TEN, kh.KH_SDT, kh.KH_EMAIL, kh.KH_DIACHI, 
              pttt.PTTT_TEN, pttt.PTTT_MA,
              hd.HD_TONGTIEN as total_amount,
              km.KM_GIATRI as discount_percentage,
              km.Code as promo_code,
              CASE 
                WHEN km.KM_GIATRI IS NOT NULL THEN (hd.HD_TONGTIEN * km.KM_GIATRI / 100)
                ELSE 0 
              END as discount_amount,
              CASE 
                WHEN km.KM_GIATRI IS NOT NULL THEN (hd.HD_TONGTIEN - (hd.HD_TONGTIEN * km.KM_GIATRI / 100))
                ELSE hd.HD_TONGTIEN 
              END as final_amount
              FROM hoa_don hd 
              JOIN khach_hang kh ON hd.KH_MA = kh.KH_MA 
              JOIN phuong_thuc_thanh_toan pttt ON hd.PTTT_MA = pttt.PTTT_MA
              LEFT JOIN khuyen_mai km ON hd.KM_MA = km.KM_MA
              WHERE hd.HD_STT = ? AND hd.KH_MA = ?";

$stmt = $conn->prepare($order_sql);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order_result = $stmt->get_result();

if ($order_result->num_rows === 0) {
    header("Location: index.php");
    exit();
}

$order = $order_result->fetch_assoc();

// Lấy chi tiết đơn hàng
$detail_sql = "SELECT cthd.*, sp.SP_TEN, sp.SP_HINHANH, sp.SP_DONGIA, sp.SP_DONVITINH
               FROM chi_tiet_hd cthd 
               JOIN san_pham sp ON cthd.SP_MA = sp.SP_MA 
               WHERE cthd.HD_STT = ?";

$detail_stmt = $conn->prepare($detail_sql);
$detail_stmt->bind_param("i", $order_id);
$detail_stmt->execute();
$details = $detail_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Xác Nhận Đơn Hàng - Plants Shop</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/global.css" rel="stylesheet">
    <style>
        .confirmation-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
        }
        .order-success {
            text-align: center;
            padding: 30px;
            background: #fff;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            animation: fadeIn 0.5s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .success-icon {
            color: #27ae60;
            font-size: 64px;
            margin-bottom: 20px;
            display: block;
        }
        .order-success h2 {
            color: #27ae60;
            margin-bottom: 15px;
        }
        .order-success p {
            color: #666;
            margin-bottom: 10px;
        }
        .order-details {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .order-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .order-items {
            margin-bottom: 20px;
        }
        .item-row {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .item-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            margin-right: 15px;
        }
        .item-details {
            flex-grow: 1;
        }
        .item-price {
            text-align: right;
            color: #e74c3c;
            font-weight: 600;
        }
        .order-summary {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .total-row {
            font-size: 18px;
            font-weight: 600;
            border-top: 2px solid #ddd;
            padding-top: 10px;
        }
        .btn-continue {
            background: #27ae60;
            color: white;
            padding: 12px 25px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        .btn-continue:hover {
            background: #219a52;
            color: white;
        }
        .shipping-info {
            margin-top: 20px;
            padding: 15px;
            background: #fff;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body class="bg-light">
    <?php include 'header.php'; ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center mb-4">Xác nhận đơn hàng</h4>
                        <div class="confirmation-details">
                            <div class="order-summary mb-4">
                                <h5 class="mb-3">Chi tiết đơn hàng</h5>
                                <?php while ($item = $details->fetch_assoc()): ?>
                                <div class="product-item d-flex align-items-center mb-3">
                                    <img src="img/<?php echo htmlspecialchars($item['SP_HINHANH']); ?>" alt="" class="product-image me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                    <div class="product-info flex-grow-1">
                                        <h6 class="mb-1"><?php echo htmlspecialchars($item['SP_TEN']); ?></h6>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted">SL: <?php echo htmlspecialchars($item['CTHD_SOLUONG']); ?> <?php echo htmlspecialchars($item['SP_DONVITINH']); ?></span>
                                            <span class="text-danger"><?php echo number_format($item['SP_DONGIA'], 0, ',', '.'); ?>đ</span>
                                        </div>
                                    </div>
                                </div>
                                <?php endwhile; ?>

                                <div class="summary-details mt-4">
                                    <div class="row mb-2">
                                        <div class="col-6 text-start">Tổng tiền hàng:</div>
                                        <div class="col-6 text-end"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?>đ</div>
                                    </div>
                                    
                                    <?php if ($order['discount_amount'] > 0): ?>
                                    <div class="row mb-2">
                                        <div class="col-6 text-start">Giảm giá<?php echo $order['promo_code'] ? " (Mã: {$order['promo_code']})" : ''; ?>:</div>
                                        <div class="col-6 text-end text-danger">
                                            -<?php echo number_format($order['discount_amount'], 0, ',', '.'); ?>đ
                                            (<?php echo $order['discount_percentage']; ?>%)
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <div class="row mb-2">
                                        <div class="col-6 text-start">Phí vận chuyển:</div>
                                        <div class="col-6 text-end text-success">Miễn phí</div>
                                    </div>

                                    <div class="row total-row">
                                        <div class="col-6 text-start"><strong>Tổng thanh toán:</strong></div>
                                        <div class="col-6 text-end"><strong class="text-danger"><?php echo number_format($order['final_amount'], 0, ',', '.'); ?>đ</strong></div>
                                    </div>
                                </div>
                            </div>

                            <div class="shipping-info mb-4">
                                <h5 class="mb-3">Thông tin giao hàng</h5>
                                <p class="mb-1"><strong>Họ tên:</strong> <?php echo htmlspecialchars($order['KH_TEN']); ?></p>
                                <p class="mb-1"><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($order['KH_SDT']); ?></p>
                                <p class="mb-1"><strong>Email:</strong> <?php echo htmlspecialchars($order['KH_EMAIL']); ?></p>
                                <p class="mb-1"><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($order['KH_DIACHI']); ?></p>
                            </div>

                            <div class="payment-info mb-4">
                                <h5 class="mb-3">Phương thức thanh toán</h5>
                                <p><?php echo htmlspecialchars($order['PTTT_TEN']); ?></p>
                            </div>

                            <div class="text-center">
                                <a href="index.php" class="btn btn-primary">Tiếp tục mua sắm</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
<?php
$stmt->close();
$detail_stmt->close();
$conn->close();
?> 