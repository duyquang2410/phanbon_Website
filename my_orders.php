<?php
session_start();
include 'connect.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];

// Lấy danh sách đơn hàng của user
$order_sql = "SELECT 
    hd.HD_STT,
    hd.HD_NGAYLAP,
    hd.HD_TONGTIEN,
    tt.TT_TEN AS trang_thai,
    pttt.PTTT_TEN AS phuong_thuc,
    km.Code AS promo_code,
    km.KM_GIATRI AS discount_percentage
FROM hoa_don hd
JOIN trang_thai tt ON hd.TT_MA = tt.TT_MA
JOIN phuong_thuc_thanh_toan pttt ON hd.PTTT_MA = pttt.PTTT_MA
LEFT JOIN khuyen_mai km ON hd.KM_MA = km.KM_MA
WHERE hd.KH_MA = ?
ORDER BY hd.HD_NGAYLAP DESC";
$stmt = $conn->prepare($order_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đơn hàng của tôi - Plants Shop</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/global.css" rel="stylesheet">
    <style>
        body { background: #f4f6fb; }
        .order-card {
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(44,62,80,0.07);
            border: none;
            margin-bottom: 32px;
        }
        .order-table th, .order-table td { vertical-align: middle; }
        .order-table th { background: #f8fafc; }
        .order-row { transition: box-shadow 0.2s, background 0.2s; }
        .order-row:hover { background: #f1f7ff; box-shadow: 0 2px 12px rgba(52,152,219,0.08); }
        .order-status {
            font-weight: bold;
            border-radius: 12px;
            padding: 4px 12px;
            display: inline-block;
            font-size: 14px;
        }
        .status-wait { background: #fff3cd; color: #856404; }
        .status-shipping { background: #d1ecf1; color: #0c5460; }
        .status-done { background: #d4edda; color: #155724; }
        .status-cancel { background: #f8d7da; color: #721c24; }
        .order-icon { font-size: 18px; margin-right: 6px; }
        .btn-detail {
            border-radius: 20px;
            font-weight: 500;
            padding: 6px 18px;
            transition: background 0.2s;
        }
        .btn-detail:hover { background: #27ae60; color: #fff; }
        @media (max-width: 767px) {
            .order-table th, .order-table td { font-size: 13px; }
            .btn-detail { padding: 4px 10px; font-size: 13px; }
        }
    </style>
    <script>
    // Hiển thị icon trạng thái
    function getStatusClass(status) {
        status = status.toLowerCase();
        if (status.includes('chờ')) return 'status-wait';
        if (status.includes('đang')) return 'status-shipping';
        if (status.includes('đã giao')) return 'status-done';
        if (status.includes('hủy')) return 'status-cancel';
        return '';
    }
    </script>
</head>
<body>
<?php include 'header.php'; ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="order-card card">
                <div class="card-body">
                    <h3 class="mb-4 text-center" style="font-weight:600; letter-spacing:1px;">Lịch sử đơn hàng của bạn</h3>
                    <div class="table-responsive">
                        <table class="table order-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Ngày đặt</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Thanh toán</th>
                                    <th>Mã KM</th>
                                    <th>Giảm (%)</th>
                                    <th>Chi tiết</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                <?php
                                    // Xác định class trạng thái
                                    $status = mb_strtolower($row['trang_thai'], 'UTF-8');
                                    $statusClass = 'order-status ';
                                    if (strpos($status, 'chờ') !== false) $statusClass .= 'status-wait';
                                    else if (strpos($status, 'đang') !== false) $statusClass .= 'status-shipping';
                                    else if (strpos($status, 'đã giao') !== false) $statusClass .= 'status-done';
                                    else if (strpos($status, 'hủy') !== false) $statusClass .= 'status-cancel';
                                    else $statusClass .= '';
                                    // Icon phương thức thanh toán
                                    $payIcon = '<i class="fa fa-money order-icon"></i>';
                                    if (stripos($row['phuong_thuc'], 'momo') !== false) $payIcon = '<i class="fa fa-mobile order-icon"></i>';
                                    else if (stripos($row['phuong_thuc'], 'visa') !== false) $payIcon = '<i class="fa fa-credit-card order-icon"></i>';
                                    else if (stripos($row['phuong_thuc'], 'chuyển khoản') !== false) $payIcon = '<i class="fa fa-bank order-icon"></i>';
                                ?>
                                <tr class="order-row">
                                    <td><span class="badge bg-primary bg-opacity-75" style="font-size:15px;">#<?php echo $row['HD_STT']; ?></span></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($row['HD_NGAYLAP'])); ?></td>
                                    <td><span style="color:#e74c3c;font-weight:600;"><?php echo number_format($row['HD_TONGTIEN'], 0, ',', '.'); ?>đ</span></td>
                                    <td><span class="<?php echo $statusClass; ?>"><?php echo htmlspecialchars($row['trang_thai']); ?></span></td>
                                    <td><?php echo $payIcon . htmlspecialchars($row['phuong_thuc']); ?></td>
                                    <td><?php echo isset($row['promo_code']) && $row['promo_code'] !== null && $row['promo_code'] !== '' ? htmlspecialchars($row['promo_code']) : '-'; ?></td>
                                    <td><?php echo isset($row['discount_percentage']) && $row['discount_percentage'] !== null && $row['discount_percentage'] > 0 ? $row['discount_percentage'].'%' : '-'; ?></td>
                                    <td><a href="order_confirmation.php?id=<?php echo $row['HD_STT']; ?>" class="btn btn-outline-success btn-detail"><i class="fa fa-eye"></i> Xem</a></td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="8" class="text-center py-4">Bạn chưa có đơn hàng nào.</td></tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html> 