<?php
session_start();
include 'connect.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];

// Số đơn hàng trên mỗi trang
$orders_per_page = 6;

// Lấy trang hiện tại
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$current_page = max(1, $current_page);

// Tính offset cho LIMIT trong SQL
$offset = ($current_page - 1) * $orders_per_page;

// Đếm tổng số đơn hàng
$count_sql = "SELECT COUNT(*) as total FROM hoa_don WHERE KH_MA = ?";
$count_stmt = $conn->prepare($count_sql);
$count_stmt->bind_param("i", $user_id);
$count_stmt->execute();
$total_orders = $count_stmt->get_result()->fetch_assoc()['total'];

// Tính tổng số trang
$total_pages = ceil($total_orders / $orders_per_page);
$current_page = min($current_page, $total_pages);

// Lấy danh sách đơn hàng của user với phân trang
$order_sql = "SELECT 
    hd.HD_STT,
    hd.HD_NGAYLAP,
    hd.HD_TONGTIEN as subtotal,
    hd.HD_PHISHIP as shipping_fee,
    CASE 
        WHEN km.hinh_thuc_km = 'Giảm phần trăm' THEN LEAST(hd.HD_TONGTIEN, hd.HD_TONGTIEN * km.KM_GIATRI / 100)
        WHEN km.hinh_thuc_km = 'Giảm trực tiếp' THEN LEAST(km.KM_GIATRI, hd.HD_TONGTIEN)
        ELSE 0 
    END as discount_amount,
    tt.TT_TEN AS trang_thai,
    pttt.PTTT_TEN AS phuong_thuc,
    km.Code AS promo_code,
    km.KM_GIATRI AS discount_percentage,
    km.hinh_thuc_km
FROM hoa_don hd
JOIN trang_thai tt ON hd.TT_MA = tt.TT_MA
JOIN phuong_thuc_thanh_toan pttt ON hd.PTTT_MA = pttt.PTTT_MA
LEFT JOIN khuyen_mai km ON hd.KM_MA = km.KM_MA
WHERE hd.KH_MA = ?
ORDER BY hd.HD_NGAYLAP DESC
LIMIT ? OFFSET ?";

$stmt = $conn->prepare($order_sql);
$stmt->bind_param("iii", $user_id, $orders_per_page, $offset);
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
        body { 
            background: #f4f6fb; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .order-card {
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(44,62,80,0.1);
            border: none;
            margin-bottom: 32px;
            background: white;
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border: none;
            padding: 20px;
            text-align: center;
        }
        .card-header h3 {
            margin: 0;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-size: 1.5rem;
        }
        .order-table th, .order-table td { 
            vertical-align: middle;
            padding: 15px;
        }
        .order-table th { 
            background: #f8fafc;
            font-weight: 600;
            color: #2c3e50;
        }
        .order-row { 
            transition: all 0.3s ease;
        }
        .order-row:hover { 
            background: #f1f7ff;
            transform: translateY(-2px);
        }
        .order-status {
            font-weight: 600;
            border-radius: 30px;
            padding: 6px 15px;
            display: inline-block;
            font-size: 0.85rem;
            text-transform: uppercase;
        }
        .status-wait { background: #fff3cd; color: #856404; }
        .status-shipping { background: #d1ecf1; color: #0c5460; }
        .status-done { background: #d4edda; color: #155724; }
        .status-cancel { background: #f8d7da; color: #721c24; }
        .order-icon { 
            font-size: 18px; 
            margin-right: 8px;
            vertical-align: middle;
        }
        .btn-detail {
            border-radius: 25px;
            font-weight: 500;
            padding: 8px 20px;
            transition: all 0.3s ease;
            background: white;
            border: 2px solid #28a745;
            color: #28a745;
        }
        .btn-detail:hover { 
            background: #28a745;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.2);
        }
        .pagination {
            margin-top: 2rem;
            justify-content: center;
        }
        .page-link {
            border-radius: 50%;
            margin: 0 5px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #28a745;
            border: 1px solid #28a745;
            background: white;
            transition: all 0.3s ease;
        }
        .page-link:hover,
        .page-item.active .page-link {
            background: #28a745;
            color: white;
            border-color: #28a745;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.2);
        }
        .empty-orders {
            text-align: center;
            padding: 3rem 1rem;
            color: #6c757d;
        }
        .empty-orders i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #adb5bd;
        }
        @media (max-width: 767px) {
            .order-table th, .order-table td { 
                font-size: 0.85rem;
                padding: 10px;
            }
            .btn-detail { 
                padding: 6px 12px;
                font-size: 0.85rem;
            }
            .order-status {
                padding: 4px 10px;
                font-size: 0.75rem;
            }
            .card-header h3 {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="order-card card">
                <div class="card-header">
                    <h3>Lịch sử đơn hàng của bạn</h3>
                </div>
                <div class="card-body">
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
                                    $status = mb_strtolower($row['trang_thai'], 'UTF-8');
                                    $statusClass = 'order-status ';
                                    if (strpos($status, 'chờ') !== false) $statusClass .= 'status-wait';
                                    else if (strpos($status, 'đang') !== false) $statusClass .= 'status-shipping';
                                    else if (strpos($status, 'đã giao') !== false) $statusClass .= 'status-done';
                                    else if (strpos($status, 'hủy') !== false) $statusClass .= 'status-cancel';
                                    
                                    $payIcon = '<i class="fa fa-money order-icon"></i>';
                                    if (stripos($row['phuong_thuc'], 'momo') !== false) $payIcon = '<i class="fa fa-mobile order-icon"></i>';
                                    else if (stripos($row['phuong_thuc'], 'visa') !== false) $payIcon = '<i class="fa fa-credit-card order-icon"></i>';
                                    else if (stripos($row['phuong_thuc'], 'chuyển khoản') !== false) $payIcon = '<i class="fa fa-bank order-icon"></i>';
                                ?>
                                <tr class="order-row">
                                    <td><span class="badge bg-primary bg-opacity-75" style="font-size:15px;">#<?php echo $row['HD_STT']; ?></span></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($row['HD_NGAYLAP'])); ?></td>
                                    <td>
                                        <?php
                                            $subtotal = $row['subtotal'];
                                            $shipping_fee = $row['shipping_fee'];
                                            $discount_amount = $row['discount_amount'];
                                            $total_payment = $subtotal - $discount_amount + $shipping_fee;
                                            echo '<span style="color:#e74c3c;font-weight:600;">' . number_format($total_payment, 0, ',', '.') . 'đ</span>';
                                            if ($discount_amount > 0) {
                                                echo '<br><small class="text-muted"><del>' . number_format($subtotal + $shipping_fee, 0, ',', '.') . 'đ</del></small>';
                                            }
                                        ?>
                                    </td>
                                    <td><span class="<?php echo $statusClass; ?>"><?php echo htmlspecialchars($row['trang_thai']); ?></span></td>
                                    <td><?php echo $payIcon . htmlspecialchars($row['phuong_thuc']); ?></td>
                                    <td><?php echo isset($row['promo_code']) && $row['promo_code'] !== null && $row['promo_code'] !== '' ? htmlspecialchars($row['promo_code']) : '-'; ?></td>
                                    <td><?php echo isset($row['discount_percentage']) && $row['discount_percentage'] !== null && $row['discount_percentage'] > 0 ? $row['discount_percentage'].'%' : '-'; ?></td>
                                    <td><a href="order_confirmation.php?id=<?php echo $row['HD_STT']; ?>" class="btn btn-detail"><i class="fa fa-eye"></i> Xem</a></td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="empty-orders">
                                        <i class="fa fa-shopping-cart"></i>
                                        <p class="mb-0">Bạn chưa có đơn hàng nào.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php if ($total_pages > 1): ?>
                    <nav aria-label="Phân trang đơn hàng" class="mt-4">
                        <ul class="pagination">
                            <?php if ($current_page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $current_page - 1; ?>" aria-label="Trang trước">
                                    <i class="fa fa-chevron-left"></i>
                                </a>
                            </li>
                            <?php endif; ?>
                            
                            <?php
                            $start_page = max(1, $current_page - 2);
                            $end_page = min($total_pages, $start_page + 4);
                            $start_page = max(1, $end_page - 4);
                            
                            for ($i = $start_page; $i <= $end_page; $i++):
                            ?>
                            <li class="page-item <?php echo $i === $current_page ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                            <?php endfor; ?>
                            
                            <?php if ($current_page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $current_page + 1; ?>" aria-label="Trang sau">
                                    <i class="fa fa-chevron-right"></i>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html> 