<!DOCTYPE html>
<html lang="en">
<?php
session_start();
require 'connect.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['NV_MA'])) {
    header('Location: sign_in.php');
    exit;
}

// Kiểm tra quyền truy cập
$nv_ma = $_SESSION['NV_MA'];
$sql = "SELECT nv.NV_QUYEN, cv.CV_QUYEN 
        FROM nhan_vien nv 
        JOIN chuc_vu cv ON nv.CV_MA = cv.CV_MA 
        WHERE nv.NV_MA = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $nv_ma);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $nv_quyen = $row['NV_QUYEN'];
    $cv_quyen = json_decode($row['CV_QUYEN'], true);
    
    // Kiểm tra quyền truy cập
    $has_access = false;
    
    // ADMIN có toàn quyền
    if ($nv_quyen === 'ADMIN') {
        $has_access = true;
    }
    // Nhân viên có quyền xem báo cáo
    else if (is_array($cv_quyen) && (in_array("view_reports", $cv_quyen) || in_array("all", $cv_quyen))) {
        $has_access = true;
    }
    
    if (!$has_access) {
        header('Location: error.php?message=' . urlencode('Bạn không có quyền truy cập trang này'));
        exit;
    }
} else {
    header('Location: sign_in.php');
    exit;
}

// Lấy top khách hàng tiềm năng
try {
    $sql_potential_customers = "SELECT 
        kh.KH_MA,
        kh.KH_TEN,
        COUNT(DISTINCT hd.HD_STT) as so_don_hang,
        SUM(hd.HD_TONGTIEN) as tong_chi_tieu,
        MAX(hd.HD_NGAYLAP) as lan_mua_cuoi
    FROM khach_hang kh
    JOIN hoa_don hd ON kh.KH_MA = hd.KH_MA
    WHERE hd.TT_MA = 4 
    GROUP BY kh.KH_MA, kh.KH_TEN
    HAVING COUNT(DISTINCT hd.HD_STT) >= 2
    ORDER BY tong_chi_tieu DESC, so_don_hang DESC
    LIMIT 5";

    $result_potential = $conn->query($sql_potential_customers);
    $potential_customers = [];
    if ($result_potential) {
        while($row = $result_potential->fetch_assoc()) {
            $potential_customers[] = $row;
        }
    }
} catch (Exception $e) {
    error_log("Error fetching potential customers: " . $e->getMessage());
}

// Xử lý filter date range và kiểu thống kê
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d', strtotime('-30 days'));
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');
$stats_type = isset($_GET['stats_type']) ? $_GET['stats_type'] : 'day';

// Tính tổng doanh thu
$sql_total_revenue = "SELECT SUM(HD_TONGTIEN) as total FROM hoa_don WHERE TT_MA = 4 AND HD_NGAYLAP BETWEEN ? AND ?";
$stmt_total = $conn->prepare($sql_total_revenue);
$stmt_total->bind_param("ss", $start_date, $end_date);
$stmt_total->execute();
$total_revenue = $stmt_total->get_result()->fetch_assoc()['total'] ?? 0;

// Hàm format date theo kiểu thống kê
function formatDateByType($date, $type) {
    switch($type) {
        case 'month':
            return date('m/Y', strtotime($date));
        case 'year':
            return date('Y', strtotime($date));
        default:
            return date('d/m/Y', strtotime($date));
    }
}

// 1. Thống kê doanh thu theo thời gian
function getRevenue($period = 'day', $start = null, $end = null) {
    global $conn;
    $sql = "";
    switch($period) {
        case 'day':
            $sql = "SELECT 
                    DATE(HD_NGAYLAP) as date,
                    COUNT(*) as total_orders,
                    SUM(HD_TONGTIEN) as revenue 
                    FROM hoa_don 
                    WHERE TT_MA = 4 
                    AND HD_NGAYLAP BETWEEN ? AND ?
                    GROUP BY DATE(HD_NGAYLAP)
                    ORDER BY date";
            break;
        case 'month':
            $sql = "SELECT 
                    DATE_FORMAT(HD_NGAYLAP, '%Y-%m') as date,
                    COUNT(*) as total_orders,
                    SUM(HD_TONGTIEN) as revenue 
                    FROM hoa_don 
                    WHERE TT_MA = 4 
                    AND HD_NGAYLAP BETWEEN ? AND ?
                    GROUP BY DATE_FORMAT(HD_NGAYLAP, '%Y-%m')
                    ORDER BY date";
            break;
        case 'year':
            $sql = "SELECT 
                    YEAR(HD_NGAYLAP) as date,
                    COUNT(*) as total_orders,
                    SUM(HD_TONGTIEN) as revenue 
                    FROM hoa_don 
                    WHERE TT_MA = 4
                    AND HD_NGAYLAP BETWEEN ? AND ?
                    GROUP BY YEAR(HD_NGAYLAP)
                    ORDER BY date";
            break;
    }
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $start, $end);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Hàm lấy doanh thu theo tháng trong năm hiện tại
function getMonthlyRevenue($conn) {
    $sql = "SELECT 
    MONTH(hd.HD_NGAYLAP) as month,
                YEAR(hd.HD_NGAYLAP) as year,
                COALESCE(SUM(hd.HD_TONGTIEN), 0) as revenue
    FROM hoa_don hd 
            WHERE YEAR(hd.HD_NGAYLAP) = YEAR(CURRENT_DATE)
            GROUP BY MONTH(hd.HD_NGAYLAP), YEAR(hd.HD_NGAYLAP)
            ORDER BY year, month";
    
    $result = $conn->query($sql);
    $monthlyData = array_fill(1, 12, 0); // Khởi tạo mảng 12 tháng với giá trị 0
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $monthlyData[$row['month']] = (float)$row['revenue'];
        }
    }
    
    return array_values($monthlyData); // Chuyển về mảng tuần tự
}

// Lấy dữ liệu theo filter
$revenue_data = getRevenue($stats_type, $start_date, $end_date);

// Thống kê hôm nay
$today = date('Y-m-d');
$sql_today = "SELECT 
    COUNT(*) as total_orders,
    SUM(HD_TONGTIEN) as total_revenue
    FROM hoa_don 
    WHERE DATE(HD_NGAYLAP) = ? AND TT_MA = 4";
$stmt_today = $conn->prepare($sql_today);
$stmt_today->bind_param("s", $today);
$stmt_today->execute();
$today_stats = $stmt_today->get_result()->fetch_assoc();
$today_orders = $today_stats['total_orders'];
$today_revenue = $today_stats['total_revenue'];

// Khách hàng mới
$sql_new_customers = "SELECT COUNT(*) as total 
    FROM khach_hang 
    WHERE DATE(KH_NGAYDK) BETWEEN ? AND ?";
$stmt_new_customers = $conn->prepare($sql_new_customers);
$stmt_new_customers->bind_param("ss", $start_date, $end_date);
$stmt_new_customers->execute();
$new_customers = $stmt_new_customers->get_result()->fetch_assoc()['total'];

// Tỷ lệ chuyển đổi (đơn thành công / tổng đơn)
$sql_conversion = "SELECT 
    COUNT(*) as total_orders,
    SUM(CASE WHEN TT_MA = 4 THEN 1 ELSE 0 END) as successful_orders
    FROM hoa_don 
    WHERE HD_NGAYLAP BETWEEN ? AND ?";
$stmt_conversion = $conn->prepare($sql_conversion);
$stmt_conversion->bind_param("ss", $start_date, $end_date);
$stmt_conversion->execute();
$conversion_stats = $stmt_conversion->get_result()->fetch_assoc();
$conversion_rate = $conversion_stats['total_orders'] > 0 
    ? ($conversion_stats['successful_orders'] / $conversion_stats['total_orders']) * 100 
    : 0;

// Thống kê doanh thu theo thời gian
$sql_revenue = "SELECT 
    DATE(hd.HD_NGAYLAP) as date,
    COUNT(DISTINCT hd.HD_STT) as total_orders,
    COUNT(DISTINCT ct.SP_MA) as total_products,
    SUM(hd.HD_TONGTIEN) as revenue,
    LAG(SUM(hd.HD_TONGTIEN)) OVER (ORDER BY DATE(hd.HD_NGAYLAP)) as prev_revenue
    FROM hoa_don hd
    LEFT JOIN chi_tiet_hd ct ON hd.HD_STT = ct.HD_STT
    WHERE hd.TT_MA = 4 AND hd.HD_NGAYLAP BETWEEN ? AND ?
    GROUP BY DATE(hd.HD_NGAYLAP)
    ORDER BY date";
$stmt_revenue = $conn->prepare($sql_revenue);
$stmt_revenue->bind_param("ss", $start_date, $end_date);
$stmt_revenue->execute();
$revenue_result = $stmt_revenue->get_result();
$revenue_data = [];
while ($row = $revenue_result->fetch_assoc()) {
    $row['growth'] = $row['prev_revenue'] > 0 
        ? (($row['revenue'] - $row['prev_revenue']) / $row['prev_revenue']) * 100 
        : 0;
    $revenue_data[] = $row;
}

// Thống kê sản phẩm bán chậm
$sql_slow_products = "SELECT 
    sp.SP_MA,
    sp.SP_TEN,
    sp.SP_HINHANH,
    COALESCE(SUM(ct.CTHD_SOLUONG), 0) as so_luong_ban,
    COALESCE(COUNT(DISTINCT hd.HD_STT), 0) as so_don_hang
    FROM san_pham sp
    LEFT JOIN chi_tiet_hd ct ON sp.SP_MA = ct.SP_MA
    LEFT JOIN hoa_don hd ON ct.HD_STT = hd.HD_STT AND hd.TT_MA = 4 
        AND hd.HD_NGAYLAP BETWEEN ? AND ?
    GROUP BY sp.SP_MA, sp.SP_TEN, sp.SP_HINHANH
    HAVING COALESCE(SUM(ct.CTHD_SOLUONG), 0) <= 5
    ORDER BY COALESCE(SUM(ct.CTHD_SOLUONG), 0) ASC, sp.SP_MA
    LIMIT 10";
$stmt_slow_products = $conn->prepare($sql_slow_products);
$stmt_slow_products->bind_param("ss", $start_date, $end_date);
$stmt_slow_products->execute();
$slow_products = $stmt_slow_products->get_result()->fetch_all(MYSQLI_ASSOC);

// Thống kê trạng thái đơn hàng
$sql_order_status = "SELECT 
    tt.TT_TEN,
    COUNT(*) as so_don,
    SUM(hd.HD_TONGTIEN) as tong_tien
    FROM hoa_don hd
    JOIN trang_thai tt ON hd.TT_MA = tt.TT_MA
    WHERE hd.HD_NGAYLAP BETWEEN ? AND ?
    GROUP BY tt.TT_MA, tt.TT_TEN";
$stmt_order_status = $conn->prepare($sql_order_status);
$stmt_order_status->bind_param("ss", $start_date, $end_date);
$stmt_order_status->execute();
$order_status_stats = $stmt_order_status->get_result()->fetch_all(MYSQLI_ASSOC);

// Thống kê phương thức vận chuyển
$sql_shipping = "SELECT 
    nvc.NVC_TEN as VC_TEN,
    COUNT(*) as so_don,
    SUM(hd.HD_TONGTIEN) as tong_tien
    FROM hoa_don hd
    JOIN nha_van_chuyen nvc ON hd.DVC_MA = nvc.NVC_MA
    WHERE hd.HD_NGAYLAP BETWEEN ? AND ?
    GROUP BY nvc.NVC_MA, nvc.NVC_TEN";
$stmt_shipping = $conn->prepare($sql_shipping);
$stmt_shipping->bind_param("ss", $start_date, $end_date);
$stmt_shipping->execute();
$shipping_stats = $stmt_shipping->get_result()->fetch_all(MYSQLI_ASSOC);

// 2. Thống kê theo sản phẩm với filter
$sql_products = "SELECT 
    sp.SP_MA,
    sp.SP_TEN,
    sp.SP_HINHANH,
    sp.SP_DONGIA,
    COUNT(DISTINCT hd.HD_STT) as so_don_hang,
    COALESCE(SUM(ct.CTHD_SOLUONG), 0) as so_luong_ban,
    COALESCE(SUM(ct.CTHD_SOLUONG * ct.CTHD_DONGIA), 0) as doanh_thu,
    COALESCE(COUNT(DISTINCT CASE WHEN hd.HD_NGAYLAP >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN hd.HD_STT END), 0) as don_hang_30_ngay,
    COALESCE(SUM(CASE WHEN hd.HD_NGAYLAP >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN ct.CTHD_SOLUONG ELSE 0 END), 0) as so_luong_ban_30_ngay
    FROM san_pham sp
    LEFT JOIN chi_tiet_hd ct ON sp.SP_MA = ct.SP_MA
    LEFT JOIN hoa_don hd ON ct.HD_STT = hd.HD_STT AND hd.TT_MA = 4
    WHERE (hd.HD_NGAYLAP BETWEEN ? AND ?) OR hd.HD_NGAYLAP IS NULL
    GROUP BY sp.SP_MA, sp.SP_TEN, sp.SP_HINHANH, sp.SP_DONGIA
    ORDER BY doanh_thu DESC";
$stmt_products = $conn->prepare($sql_products);
$stmt_products->bind_param("ss", $start_date, $end_date);
$stmt_products->execute();
$product_stats = $stmt_products->get_result()->fetch_all(MYSQLI_ASSOC);

// 3. Thống kê theo đơn hàng
$sql_orders = "SELECT 
    COUNT(*) as total_orders,
    SUM(CASE WHEN TT_MA = 4 THEN 1 ELSE 0 END) as completed_orders,
    SUM(CASE WHEN TT_MA = 5 THEN 1 ELSE 0 END) as cancelled_orders,
    SUM(CASE WHEN TT_MA = 6 THEN 1 ELSE 0 END) as returned_orders,
    AVG(HD_TONGTIEN) as avg_order_value
    FROM hoa_don
    WHERE HD_NGAYLAP BETWEEN ? AND ?";
$stmt_orders = $conn->prepare($sql_orders);
$stmt_orders->bind_param("ss", $start_date, $end_date);
$stmt_orders->execute();
$order_stats = $stmt_orders->get_result()->fetch_assoc();

// 4. Thống kê theo khách hàng
$sql_customers = "SELECT 
    kh.KH_MA,
    kh.KH_TEN,
    COUNT(DISTINCT hd.HD_STT) as so_don_hang,
    SUM(hd.HD_TONGTIEN) as tong_chi_tieu,
    MAX(hd.HD_NGAYLAP) as lan_mua_gan_nhat
    FROM khach_hang kh
    JOIN hoa_don hd ON kh.KH_MA = hd.KH_MA
    WHERE hd.TT_MA = 4 AND hd.HD_NGAYLAP BETWEEN ? AND ?
    GROUP BY kh.KH_MA, kh.KH_TEN
    ORDER BY tong_chi_tieu DESC
    LIMIT 10";
$stmt_customers = $conn->prepare($sql_customers);
$stmt_customers->bind_param("ss", $start_date, $end_date);
$stmt_customers->execute();
$customer_stats = $stmt_customers->get_result()->fetch_all(MYSQLI_ASSOC);

// 5. Thống kê theo danh mục
$sql_categories = "SELECT 
    dm.DM_MA,
    dm.DM_TEN, 
    COUNT(DISTINCT sp.SP_MA) as so_san_pham,
    SUM(ct.CTHD_SOLUONG) as so_luong_ban,
    SUM(ct.CTHD_SOLUONG * ct.CTHD_DONGIA) as doanh_thu
    FROM danh_muc dm
    LEFT JOIN san_pham sp ON dm.DM_MA = sp.DM_MA
    LEFT JOIN chi_tiet_hd ct ON sp.SP_MA = ct.SP_MA
    LEFT JOIN hoa_don hd ON ct.HD_STT = hd.HD_STT 
    WHERE hd.TT_MA = 4 AND hd.HD_NGAYLAP BETWEEN ? AND ?
    GROUP BY dm.DM_MA, dm.DM_TEN
    ORDER BY doanh_thu DESC";
$stmt_categories = $conn->prepare($sql_categories);
$stmt_categories->bind_param("ss", $start_date, $end_date);
$stmt_categories->execute();
$category_stats = $stmt_categories->get_result()->fetch_all(MYSQLI_ASSOC);

// 6. Thống kê theo phương thức thanh toán
$sql_payment = "SELECT 
    pt.PTTT_TEN as PT_TEN,
    COUNT(*) as so_don,
    SUM(hd.HD_TONGTIEN) as tong_tien
    FROM hoa_don hd
    JOIN phuong_thuc_thanh_toan pt ON hd.PTTT_MA = pt.PTTT_MA
    WHERE hd.TT_MA = 4 AND hd.HD_NGAYLAP BETWEEN ? AND ?
    GROUP BY pt.PTTT_MA, pt.PTTT_TEN";
$stmt_payment = $conn->prepare($sql_payment);
$stmt_payment->bind_param("ss", $start_date, $end_date);
$stmt_payment->execute();
$payment_stats = $stmt_payment->get_result()->fetch_all(MYSQLI_ASSOC);

?>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard - Quản lý cửa hàng phân bón</title>
    
    <!-- Fonts and icons -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    
    <!-- CSS Files -->
    <link href="../asset_admin/css/material-dashboard.css" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    
    <!-- Date Range Picker -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Date Range Picker Dependencies -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    
    <!-- DataTables JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 20px;
        }
        .date-filter {
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .product-image {
            transition: transform 0.2s ease;
        }

        .product-image:hover {
            transform: scale(1.1);
            cursor: pointer;
        }

        /* Update table styles */
        #productTable tbody td {
            padding: 0.75rem 1rem;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.35em 0.65em;
        }

        .text-success {
            color: #2dce89 !important;
        }

        /* Add tooltip styles */
        .tooltip-inner {
            max-width: 200px;
            padding: 0.5rem;
            background-color: rgba(0,0,0,0.8);
            border-radius: 0.375rem;
        }

        /* Thêm CSS để fix layout */
        .table-responsive {
            overflow-x: auto;
            min-height: 400px;
            width: 100%;
        }

        .product-table {
            width: 100%;
            table-layout: fixed;
        }

        .product-table th,
        .product-table td {
            padding: 1rem;
            vertical-align: middle;
        }

        /* Cột STT */
        .product-table th:first-child,
        .product-table td:first-child {
            width: 70px;
            text-align: center;
        }

        /* Cột sản phẩm */
        .product-table th:nth-child(2),
        .product-table td:nth-child(2) {
            width: 40%;
            min-width: 300px;
        }

        /* Cột số lượng */
        .product-table th:nth-child(3),
        .product-table td:nth-child(3) {
            width: 15%;
            min-width: 120px;
        }

        /* Cột doanh thu */
        .product-table th:nth-child(4),
        .product-table td:nth-child(4) {
            width: 15%;
            min-width: 150px;
        }

        /* Cột tỷ lệ */
        .product-table th:nth-child(5),
        .product-table td:nth-child(5) {
            width: 15%;
            min-width: 150px;
        }

        /* Cột xu hướng */
        .product-table th:last-child,
        .product-table td:last-child {
            width: 15%;
            min-width: 100px;
        }

        .product-image {
            position: relative;
            width: 48px;
            height: 48px;
            min-width: 48px; /* Thêm min-width để ảnh không bị co */
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-right: 1rem;
        }

        .product-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            min-width: 0; /* Cho phép nội dung co lại */
        }

        .product-details {
            flex: 1;
            min-width: 0; /* Cho phép nội dung co lại */
            overflow: hidden;
        }

        .product-name {
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .product-meta {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.75rem;
        }

        @media (max-width: 992px) {
            .product-meta {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        /* Thêm style cho bảng */
        <table id="productTable" class="table table-hover product-table">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>Sản Phẩm</th>
                    <th class="text-center">Số Lượng</th>
                    <th class="text-end">Doanh Thu</th>
                    <th class="text-center">Tỷ Lệ</th>
                    <th class="text-center">Xu Hướng</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $stt = 0;
                foreach ($product_stats as $product): 
                    $stt++;
                    $percentage = ($total_revenue > 0) ? ($product['doanh_thu'] / $total_revenue) * 100 : 0;
                    $trend = rand(-10, 20);
                ?>
                <tr>
                    <td>
                        <?php if($stt <= 3): ?>
                            <div class="icon-shape icon-sm rounded-circle bg-<?php echo $stt == 1 ? 'warning' : ($stt == 2 ? 'info' : 'success'); ?> text-white text-center">
                                <?php echo $stt; ?>
                            </div>
                        <?php else: ?>
                            <span class="text-muted"><?php echo $stt; ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="product-info">
                            <?php 
                            $image_path = !empty($product['SP_HINHANH']) ? 
                                "../img/" . $product['SP_HINHANH'] : 
                                "../img/service.jpg";
                            ?>
                            <div class="product-image" data-toggle="tooltip">
                                <img src="<?php echo htmlspecialchars($image_path); ?>" 
                                     class="rounded-3" 
                                     style="width: 100%; height: 100%; object-fit: cover;"
                                     alt="<?php echo htmlspecialchars($product['SP_TEN']); ?>"
                                     onerror="this.src='../img/service.jpg'">
                            </div>
                            <div class="product-details">
                                <h6 class="product-name text-sm text-dark mb-1"><?php echo htmlspecialchars($product['SP_TEN']); ?></h6>
                                <div class="product-meta">
                                    <span class="text-muted">Mã SP: #<?php echo $product['SP_MA']; ?></span>
                                    <span class="text-success">
                                        <i class="fas fa-shopping-cart me-1"></i>
                                        <?php echo number_format($product['so_luong_ban_30_ngay']); ?> đã bán (30 ngày)
                                    </span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="d-flex flex-column align-items-center">
                            <h6 class="mb-1 text-sm"><?php echo number_format($product['so_luong_ban']); ?></h6>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-success-subtle text-success me-2">
                                    <?php echo number_format($product['so_don_hang']); ?> đơn
                                </span>
                                <span class="text-xs text-muted">
                                    <?php echo number_format($product['SP_DONGIA'], 0, ',', '.'); ?>đ
                                </span>
                            </div>
                        </div>
                    </td>
                    <td class="text-end">
                        <span class="text-dark font-weight-bold">
                            <?php echo number_format($product['doanh_thu'], 0, ',', '.'); ?> đ
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex align-items-center justify-content-center">
                            <span class="me-2 text-sm font-weight-bold">
                                <?php echo number_format($percentage, 1); ?>%
                            </span>
                            <div class="progress" style="width: 50px; height: 3px;">
                                <div class="progress-bar bg-gradient-success" 
                                     role="progressbar" 
                                     style="width: <?php echo $percentage; ?>%"
                                     aria-valuenow="<?php echo $percentage; ?>" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        <?php if($trend > 0): ?>
                            <span class="badge bg-success-subtle text-success">
                                <i class="fas fa-arrow-up me-1"></i><?php echo $trend; ?>%
                            </span>
                        <?php elseif($trend < 0): ?>
                            <span class="badge bg-danger-subtle text-danger">
                                <i class="fas fa-arrow-down me-1"></i><?php echo abs($trend); ?>%
                            </span>
                        <?php else: ?>
                            <span class="badge bg-secondary-subtle text-secondary">
                                <i class="fas fa-minus me-1"></i>0%
                            </span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </style>
    <style>
.potential-customers-table {
    font-size: 0.85rem;
}

.potential-customers-table th {
    font-weight: 600;
    color: #344767;
    font-size: 0.75rem;
    text-transform: uppercase;
}

.potential-customers-table td {
    padding: 0.5rem;
    white-space: nowrap;
    color: #344767;
}

.potential-customers-table tbody tr:hover {
    background-color: #f8f9fa;
}

.customer-name {
    max-width: 150px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.order-count {
    color: #344767;
    font-weight: 600;
}

.total-spent {
    color: #4CAF50;
    font-weight: 600;
}

.last-purchase {
    font-size: 0.75rem;
    color: #7b809a;
}
</style>
</head>

<body class="g-sidenav-show bg-gray-200">
    <?php require 'aside.php'; ?>
    
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <h6 class="font-weight-bolder mb-0">Dashboard</h6>
                </nav>
                        </div>
        </nav>
        <!-- End Navbar -->

        <div class="container-fluid py-4">
            <!-- Date Range Filter -->
            <div class="date-filter mb-4">
                <form id="dateRangeForm" class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <label class="form-label">Khoảng thời gian</label>
                        <input type="text" id="daterange" name="daterange" class="form-control" 
                               value="<?php echo date('d/m/Y', strtotime($start_date)) . ' - ' . date('d/m/Y', strtotime($end_date)); ?>" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kiểu thống kê</label>
                        <select class="form-select" id="statsType" name="stats_type">
                            <option value="day" <?php echo $stats_type == 'day' ? 'selected' : ''; ?>>Theo ngày</option>
                            <option value="month" <?php echo $stats_type == 'month' ? 'selected' : ''; ?>>Theo tháng</option>
                            <option value="year" <?php echo $stats_type == 'year' ? 'selected' : ''; ?>>Theo năm</option>
                        </select>
                            </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="button" id="applyFilter" class="btn btn-primary me-2">
                            <i class="fas fa-sync-alt me-2"></i>Áp dụng
                        </button>
                        <div class="dropdown">
                            <button class="btn btn-success dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown">
                                <i class="fas fa-file-export me-2"></i>Xuất báo cáo
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" id="exportPDF"><i class="fas fa-file-pdf me-2"></i>PDF</a></li>
                                <li><a class="dropdown-item" href="#" id="exportExcel"><i class="fas fa-file-excel me-2"></i>Excel</a></li>
                    </ul>
                </div>
            </div>
                </form>
                </div>

            <!-- Stats Overview -->
            <div class="row mb-4">
                <div class="col-xl-3 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Doanh thu hôm nay</p>
                                        <h5 class="font-weight-bolder mb-0" id="todayRevenue">
                                            <?php echo number_format($today_revenue, 0, ',', '.'); ?> đ
                                        </h5>
                            </div>
                            </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                        <i class="fas fa-chart-line text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Đơn hàng hôm nay</p>
                                        <h5 class="font-weight-bolder mb-0" id="todayOrders">
                                            <?php echo number_format($today_orders); ?>
                                        </h5>
                            </div>
                            </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                        <i class="fas fa-shopping-cart text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Khách hàng mới</p>
                                        <h5 class="font-weight-bolder mb-0" id="newCustomers">
                                            <?php echo number_format($new_customers); ?>
                                        </h5>
                            </div>
                            </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                        <i class="fas fa-users text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
                        </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Top khách hàng tiềm năng</p>
                                        <h5 class="font-weight-bolder mb-0" id="potentialCustomers">
                                            <?php echo count($potential_customers); ?>
                                        </h5>
                            </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                        <i class="fas fa-users text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-3">
                            <div class="table-responsive">
                                <table class="table table-sm mb-0 potential-customers-table">
                                    <thead>
                                        <tr>
                                            <th>Khách hàng</th>
                                            <th class="text-center">Đơn hàng</th>
                                            <th class="text-end">Tổng chi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($potential_customers)): ?>
                                            <?php foreach($potential_customers as $customer): ?>
                                            <tr>
                                                <td>
                                                    <div class="customer-name" title="<?php echo htmlspecialchars($customer['KH_TEN']); ?>">
                                                        <?php echo htmlspecialchars($customer['KH_TEN']); ?>
                                                    </div>
                                                    <div class="last-purchase">
                                                        Mua gần nhất: <?php echo date('d/m/Y', strtotime($customer['lan_mua_cuoi'])); ?>
                                                    </div>
                                                </td>
                                                <td class="text-center order-count">
                                                    <?php echo number_format($customer['so_don_hang']); ?>
                                                </td>
                                                <td class="text-end total-spent">
                                                    <?php echo number_format($customer['tong_chi_tieu']); ?>đ
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3" class="text-center">Chưa có dữ liệu</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Tabs -->
                    <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#revenue" role="tab">
                                <i class="fas fa-chart-line me-2"></i>Doanh thu
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#products" role="tab">
                                <i class="fas fa-box me-2"></i>Sản phẩm
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#orders" role="tab">
                                <i class="fas fa-shopping-cart me-2"></i>Đơn hàng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#customers" role="tab">
                                <i class="fas fa-users me-2"></i>Khách hàng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#categories" role="tab">
                                <i class="fas fa-tags me-2"></i>Danh mục
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#payments" role="tab">
                                <i class="fas fa-credit-card me-2"></i>Thanh toán
                            </a>
                        </li>
                    </ul>
                        </div>

                <div class="card-body">
                    <div class="tab-content">
                        <!-- Tab Doanh Thu -->
                        <div class="tab-pane fade show active" id="revenue" role="tabpanel">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card mb-4">
                                        <div class="card-header pb-0">
                                            <h6>Thống kê doanh thu theo tháng năm <?php echo date('Y'); ?></h6>
                            </div>
                                        <div class="card-body px-0 pt-0 pb-2">
                                            <div id="monthlyRevenueChart" style="height: 400px;"></div>
                        </div>
                    </div>
                </div>
            </div>

                            <!-- Existing revenue content -->
                            <div class="row">
                <div class="col-12">
                                    <div class="card mb-4">
                                        <div class="card-header pb-0">
                                            <h6>Thống kê doanh thu theo thời gian</h6>
                        </div>
                                        <div class="card-body px-0 pt-0 pb-2">
                                            <div class="chart">
                                                <div id="revenueChart"></div>
                                            </div>
                                            <div class="table-responsive p-0">
                                                <table class="table align-items-center mb-0" id="revenueTable">
                                    <thead>
                                        <tr>
                                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Thời gian</th>
                                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Số đơn</th>
                                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Số SP</th>
                                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Doanh thu</th>
                                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tăng trưởng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                                        <?php foreach ($revenue_data as $data): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm"><?php echo formatDateByType($data['date'], $stats_type); ?></h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                                <p class="text-sm font-weight-bold mb-0"><?php echo number_format($data['total_orders']); ?></p>
                                                </td>
                                                            <td>
                                                                <p class="text-sm font-weight-bold mb-0"><?php echo number_format($data['total_products']); ?></p>
                                                </td>
                                                            <td>
                                                                <p class="text-sm font-weight-bold mb-0"><?php echo number_format($data['revenue'], 0, ',', '.'); ?> đ</p>
                                                </td>
                                                            <td>
                                            <?php
                                                                $growth = isset($data['growth']) ? $data['growth'] : 0;
                                                                $growthClass = $growth > 0 ? 'text-success' : ($growth < 0 ? 'text-danger' : 'text-secondary');
                                                                $growthIcon = $growth > 0 ? 'fa-arrow-up' : ($growth < 0 ? 'fa-arrow-down' : 'fa-minus');
                                                                ?>
                                                                <span class="<?php echo $growthClass; ?>">
                                                                    <i class="fas <?php echo $growthIcon; ?> me-2"></i>
                                                                    <?php echo number_format(abs($growth), 1); ?>%
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                        <!-- Products Tab -->
                        <div class="tab-pane fade" id="products" role="tabpanel">
            <div class="row">
                                <!-- Top sản phẩm bán chạy -->
                                <div class="col-12 col-lg-8 mb-4">
                    <div class="card">
                                        <div class="card-header pb-0">
                                            <h6>Top sản phẩm bán chạy</h6>
                            </div>
                                        <div class="card-body px-0 pt-0 pb-2">
                                            <div class="row g-3 p-3">
                                <?php
                                                $stt = 0;
                                                foreach ($product_stats as $product): 
                                                    $stt++;
                                                    // Tính phần trăm doanh thu của sản phẩm
                                                    $product_revenue = $product['doanh_thu'];
                                                    $percentage = $total_revenue > 0 ? ($product_revenue / $total_revenue) * 100 : 0;
                                                    $trend = rand(-10, 20); // Giả lập xu hướng, thực tế nên tính từ dữ liệu
                                                ?>
                                                <div class="col-12 col-md-6">
                                                    <div class="card border shadow-none h-100">
                                                        <div class="card-body p-3">
                                                            <!-- STT Badge -->
                                                            <div class="position-absolute top-0 start-0 mt-2 ms-2">
                                                                <?php if($stt <= 3): ?>
                                                                    <div class="badge bg-<?php echo $stt == 1 ? 'warning' : ($stt == 2 ? 'info' : 'success'); ?>">
                                                                        #<?php echo $stt; ?>
                            </div>
                                                                <?php else: ?>
                                                                    <div class="badge bg-light text-dark">
                                                                        #<?php echo $stt; ?>
                        </div>
                                                                <?php endif; ?>
                    </div>

                                                            <!-- Product Info -->
                                                            <div class="d-flex align-items-center mb-3">
                                <?php
                                                                $image_path = !empty($product['SP_HINHANH']) ? 
                                                                    "../img/" . $product['SP_HINHANH'] : 
                                                                    "../img/service.jpg";
                                                                ?>
                                                                <div class="me-3" style="width: 64px; height: 64px;">
                                                                    <img src="<?php echo htmlspecialchars($image_path); ?>" 
                                                                        class="rounded-3 w-100 h-100"
                                                                        style="object-fit: cover;"
                                                                        alt="<?php echo htmlspecialchars($product['SP_TEN']); ?>"
                                                                        onerror="this.src='../img/service.jpg'">
                            </div>
                                                                <div>
                                                                    <h6 class="mb-1 text-sm" style="max-width: 200px;">
                                                                        <?php echo htmlspecialchars($product['SP_TEN']); ?>
                                                                    </h6>
                                                                    <p class="text-xs text-muted mb-0">
                                                                        Mã SP: #<?php echo $product['SP_MA']; ?>
                                                                    </p>
                        </div>
                    </div>

                                                            <!-- Stats Grid -->
                                                            <div class="row g-2">
                                                                <!-- Số lượng -->
                                                                <div class="col-6">
                                                                    <div class="border rounded-3 p-2">
                                                                        <p class="text-xs text-muted mb-1">Đã bán</p>
                                                                        <h6 class="mb-0 d-flex align-items-center">
                                                                            <i class="fas fa-box text-primary me-2"></i>
                                                                            <?php echo number_format($product['so_luong_ban']); ?>
                                                                        </h6>
                </div>
                            </div>
                                                                
                                                                <!-- Đơn hàng -->
                                                                <div class="col-6">
                                                                    <div class="border rounded-3 p-2">
                                                                        <p class="text-xs text-muted mb-1">Đơn hàng</p>
                                                                        <h6 class="mb-0 d-flex align-items-center">
                                                                            <i class="fas fa-shopping-cart text-info me-2"></i>
                                                                            <?php echo number_format($product['so_don_hang']); ?>
                                                                        </h6>
                            </div>
                        </div>
                                                                
                                                                <!-- Doanh thu -->
                                                                <div class="col-6">
                                                                    <div class="border rounded-3 p-2">
                                                                        <p class="text-xs text-muted mb-1">Doanh thu</p>
                                                                        <h6 class="mb-0 d-flex align-items-center">
                                                                            <i class="fas fa-dollar-sign text-success me-2"></i>
                                                                            <?php echo number_format($product_revenue, 0, ',', '.'); ?>đ
                                                                        </h6>
                                                                    </div>
                                                                </div>

                                                                <!-- Tỷ lệ -->
                                                                <div class="col-6">
                                                                    <div class="border rounded-3 p-2">
                                                                        <p class="text-xs text-muted mb-1">Tỷ lệ</p>
                                                                        <div class="d-flex align-items-center">
                                                                            <h6 class="mb-0 me-2"><?php echo number_format($percentage, 1); ?>%</h6>
                                                                            <?php if($trend > 0): ?>
                                                                                <span class="badge bg-success-subtle text-success">
                                                                                    <i class="fas fa-arrow-up"></i> <?php echo $trend; ?>%
                                                                                </span>
                                                                            <?php elseif($trend < 0): ?>
                                                                                <span class="badge bg-danger-subtle text-danger">
                                                                                    <i class="fas fa-arrow-down"></i> <?php echo abs($trend); ?>%
                                                                                </span>
                                                                            <?php else: ?>
                                                                                <span class="badge bg-secondary-subtle text-secondary">
                                                                                    <i class="fas fa-minus"></i> 0%
                                                                                </span>
                                                                            <?php endif; ?>
                                                                        </div>
                    </div>
                </div>
            </div>

                                                            <!-- Progress -->
                                                            <div class="mt-3">
                                                                <div class="progress" style="height: 3px;">
                                                                    <div class="progress-bar bg-gradient-success" 
                                                                        role="progressbar" 
                                                                        style="width: <?php echo $percentage; ?>%"
                                                                        aria-valuenow="<?php echo $percentage; ?>" 
                                                                        aria-valuemin="0" 
                                                                        aria-valuemax="100">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sản phẩm bán chậm -->
                                <div class="col-12 col-lg-4 mb-4">
                    <div class="card">
                                        <div class="card-header pb-0">
                                            <h6>Sản phẩm bán chậm</h6>
                        </div>
                                        <div class="card-body px-0 pt-0 pb-2">
                                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Sản phẩm</th>
                                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Đã bán</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                                        <?php foreach ($slow_products as $product): ?>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                        <?php
                                                                    $image_path = !empty($product['SP_HINHANH']) ? 
                                                                        "../img/" . $product['SP_HINHANH'] : 
                                                                        "../img/service.jpg";
                                                                    ?>
                                                                    <div class="me-3" style="width: 40px; height: 40px;">
                                                                        <img src="<?php echo htmlspecialchars($image_path); ?>" 
                                                                            class="rounded-3 w-100 h-100"
                                                                            style="object-fit: cover;"
                                                                            alt="<?php echo htmlspecialchars($product['SP_TEN']); ?>"
                                                                            onerror="this.src='../img/service.jpg'">
                                                                    </div>
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm"><?php echo htmlspecialchars($product['SP_TEN']); ?></h6>
                                                                        <p class="text-xs text-secondary mb-0">#<?php echo $product['SP_MA']; ?></p>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-sm font-weight-bold mb-0"><?php echo number_format($product['so_luong_ban']); ?></p>
                                                            </td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Orders Tab -->
                        <div class="tab-pane fade" id="orders" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="card mb-4">
                                        <div class="card-header pb-0">
                                            <h6>Thống kê đơn hàng</h6>
                                        </div>
                                        <div class="card-body px-0 pt-0 pb-2">
                                            <div class="chart">
                                                <div id="ordersChart"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="card mb-4">
                                        <div class="card-header pb-0">
                                            <h6>Trạng thái đơn hàng</h6>
                                        </div>
                                        <div class="card-body px-0 pt-0 pb-2">
                                            <div class="chart">
                                                <div id="orderStatusChart"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Customers Tab -->
                        <div class="tab-pane fade" id="customers" role="tabpanel">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card mb-4">
                                        <div class="card-header pb-0">
                                            <h6>Top khách hàng</h6>
                                        </div>
                                        <div class="card-body px-0 pt-0 pb-2">
                                            <div class="table-responsive p-0">
                                                <table class="table align-items-center mb-0" id="customersTable">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Khách hàng</th>
                                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Số đơn</th>
                                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tổng chi tiêu</th>
                                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Lần mua gần nhất</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($customer_stats as $customer): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm"><?php echo htmlspecialchars($customer['KH_TEN']); ?></h6>
                                                                        <p class="text-xs text-secondary mb-0">#<?php echo $customer['KH_MA']; ?></p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                                <p class="text-sm font-weight-bold mb-0"><?php echo number_format($customer['so_don_hang']); ?></p>
                                                </td>
                                                            <td>
                                                                <p class="text-sm font-weight-bold mb-0"><?php echo number_format($customer['tong_chi_tieu'], 0, ',', '.'); ?> đ</p>
                                                </td>
                                                            <td>
                                                                <span class="text-secondary text-sm font-weight-bold">
                                                                    <?php echo date('d/m/Y', strtotime($customer['lan_mua_gan_nhat'])); ?>
                                                                </span>
                                                </td>
                                            </tr>
                                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                        </div>

                        <!-- Categories Tab -->
                        <div class="tab-pane fade" id="categories" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="card mb-4">
                                        <div class="card-header pb-0">
                                            <h6>Doanh thu theo danh mục</h6>
                                        </div>
                                        <div class="card-body px-0 pt-0 pb-2">
                                            <div class="chart">
                                                <div id="categoriesChart"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="card mb-4">
                                        <div class="card-header pb-0">
                                            <h6>Phân bố danh mục</h6>
                                        </div>
                                        <div class="card-body px-0 pt-0 pb-2">
                                            <div class="chart">
                                                <div id="categoryDistributionChart"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payments Tab -->
                        <div class="tab-pane fade" id="payments" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="card mb-4">
                                        <div class="card-header pb-0">
                                            <h6>Phương thức thanh toán</h6>
                                        </div>
                                        <div class="card-body px-0 pt-0 pb-2">
                                            <div class="chart">
                                                <div id="paymentMethodsChart"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card mb-4">
                                        <div class="card-header pb-0">
                                            <h6>Phương thức vận chuyển</h6>
                                        </div>
                                        <div class="card-body px-0 pt-0 pb-2">
                                            <div class="chart">
                                                <div id="shippingMethodsChart"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Core JS Files -->
    <script src="../asset_admin/js/core/popper.min.js"></script>
    <script src="../asset_admin/js/core/bootstrap.min.js"></script>
    <script src="../asset_admin/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../asset_admin/js/plugins/smooth-scrollbar.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Date Range Picker -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    
    <!-- DataTables -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    
    <script>
    $(document).ready(function() {
        // Biến toàn cục cho biểu đồ
        let revenueChart = null;

        // Hàm khởi tạo và cập nhật biểu đồ
        function initRevenueChart(data) {
            const options = {
                series: [{
                    name: 'Doanh thu',
                    type: 'column',
                    data: data.values
                }, {
                    name: 'Số đơn hàng',
                    type: 'line',
                    data: data.total_orders,
                    yAxisIndex: 1
                }],
                chart: {
                    type: 'bar',
                    height: 450,
                    toolbar: {
                        show: true,
                        tools: {
                            download: true,
                            selection: true,
                            zoom: true,
                            zoomin: true,
                            zoomout: true,
                            pan: true,
                            reset: true
                        }
                    },
                    zoom: {
                        enabled: true
                    }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        dataLabels: {
                            position: 'top'
                        },
                        columnWidth: '60%',
                        colors: {
                            ranges: [{
                                from: 0,
                                to: Infinity,
                                color: '#4CAF50'
                            }]
                    }
                }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val, opts) {
                        if (opts.seriesIndex === 0) {
                            // Format cho doanh thu
                            return new Intl.NumberFormat('vi-VN', {
                                style: 'currency',
                                currency: 'VND',
                                maximumFractionDigits: 0,
                                notation: "compact",
                                compactDisplay: "short"
                            }).format(val);
                        } else {
                            // Format cho số đơn hàng
                            return val + ' đơn';
                        }
                    },
                    offsetY: -20,
                    style: {
                        fontSize: '12px',
                        colors: ["#304758"]
                    }
            },
            stroke: {
                    width: [0, 2],
                curve: 'smooth'
            },
                colors: ['#4CAF50', '#2196F3'],
                xaxis: {
                    categories: data.labels,
                    labels: {
                        rotate: -45,
                        style: {
                            fontSize: '12px'
                        }
                    },
                    title: {
                        text: 'Thời gian',
                        style: {
                            fontSize: '14px',
                            fontWeight: 600
                }
            }
                },
                yaxis: [{
                    title: {
                        text: 'Doanh thu (VNĐ)',
                        style: {
                            fontSize: '14px',
                            fontWeight: 600
                        }
                    },
                    labels: {
                        formatter: function(val) {
                            return new Intl.NumberFormat('vi-VN', {
                                style: 'currency',
                                currency: 'VND',
                                maximumFractionDigits: 0,
                                notation: "compact",
                                compactDisplay: "short"
                            }).format(val);
                        }
                    }
                }, {
                    opposite: true,
                    title: {
                        text: 'Số đơn hàng',
                        style: {
                            fontSize: '14px',
                            fontWeight: 600
                        }
                    },
                    labels: {
                        formatter: function(val) {
                            return Math.round(val);
                        }
                    }
                }],
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: [{
                        formatter: function(val) {
                            return new Intl.NumberFormat('vi-VN', {
                                style: 'currency',
                                currency: 'VND',
                                maximumFractionDigits: 0
                            }).format(val);
                        }
                    }, {
                        formatter: function(val) {
                            return val + ' đơn';
                        }
                    }]
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right',
                    markers: {
                        width: 12,
                        height: 12,
                        radius: 12
                    }
                },
                grid: {
                    borderColor: '#f1f1f1',
                    padding: {
                        bottom: 15
                    }
                },
            responsive: [{
                    breakpoint: 576,
            options: {
                        plotOptions: {
                            bar: {
                                columnWidth: '80%'
                            }
                        },
                        dataLabels: {
                            enabled: false
                    },
                    legend: {
                            position: 'bottom',
                            horizontalAlign: 'center'
                    }
                }
            }]
        };

            // Nếu biểu đồ đã tồn tại, hủy nó trước khi tạo mới
            if (revenueChart) {
                revenueChart.destroy();
            }

            // Tạo biểu đồ mới
            revenueChart = new ApexCharts(document.querySelector("#revenueChart"), options);
            revenueChart.render();

            // Cập nhật bảng dữ liệu
            updateDataTable(data);
        }

        // Hàm cập nhật bảng dữ liệu
        function updateDataTable(data) {
            const table = $('#revenueTable').DataTable();
            table.clear();

            // Thêm dữ liệu mới vào bảng
            for (let i = 0; i < data.labels.length; i++) {
                const growth = i > 0 ? 
                    ((data.values[i] - data.values[i-1]) / data.values[i-1] * 100).toFixed(1) : 
                    0;
                
                const growthClass = growth > 0 ? 'text-success' : (growth < 0 ? 'text-danger' : 'text-secondary');
                const growthIcon = growth > 0 ? 'fa-arrow-up' : (growth < 0 ? 'fa-arrow-down' : 'fa-minus');
                
                table.row.add([
                    data.labels[i],
                    data.total_orders[i],
                    data.total_orders[i], // Số sản phẩm - có thể thay đổi nếu có dữ liệu thực tế
                    new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND',
                        maximumFractionDigits: 0
                    }).format(data.values[i]),
                    `<span class="${growthClass}">
                        <i class="fas ${growthIcon} me-2"></i>${Math.abs(growth)}%
                    </span>`
                ]);
            }
            
            table.draw();
        }

        // Hàm cập nhật các thống kê
        function updateStatistics(data) {
            // Cập nhật tổng doanh thu
            const totalRevenue = data.values.reduce((a, b) => a + b, 0);
            $('#totalRevenue').text(new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND',
                maximumFractionDigits: 0
            }).format(totalRevenue));

            // Cập nhật tổng đơn hàng
            const totalOrders = data.total_orders.reduce((a, b) => a + b, 0);
            $('#totalOrders').text(totalOrders);
        }

        // Hàm cập nhật dữ liệu biểu đồ
        function updateChartData() {
            const dates = $('#daterange').val().split(' - ');
            const startDate = moment(dates[0], 'DD/MM/YYYY').format('YYYY-MM-DD');
            const endDate = moment(dates[1], 'DD/MM/YYYY').format('YYYY-MM-DD');
            const statsType = $('#statsType').val();

            // Hiển thị loading
            Swal.fire({
                title: 'Đang tải...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Cập nhật URL mà không reload trang
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('start_date', startDate);
            urlParams.set('end_date', endDate);
            urlParams.set('stats_type', statsType);
            const newUrl = `${window.location.pathname}?${urlParams.toString()}`;
            window.history.pushState({ path: newUrl }, '', newUrl);

            // Gửi AJAX request
            $.ajax({
                url: 'get_revenue_data.php',
                type: 'POST',
                data: {
                    start_date: startDate,
                    end_date: endDate,
                    stats_type: statsType
                },
                success: function(response) {
                    if (response.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: response.message
                        });
                        return;
                    }

                    // Cập nhật biểu đồ
                    initRevenueChart(response);
                    
                    // Cập nhật các thống kê khác
                    updateStatistics(response);
                    
                    // Đóng loading
                    Swal.close();
                },
                error: function(xhr, status, error) {
                    console.error('Ajax error:', {xhr, status, error});
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Không thể kết nối đến server. Vui lòng thử lại.'
                    });
                }
            });
        }

        // Xử lý sự kiện click nút Áp dụng
        $('#applyFilter').on('click', function() {
            updateChartData();
        });

        // Xử lý sự kiện thay đổi daterange
        $('#daterange').on('apply.daterangepicker', function(ev, picker) {
            updateChartData();
        });

        // Xử lý sự kiện thay đổi kiểu thống kê
        $('#statsType').on('change', function() {
            updateChartData();
        });

        // Khởi tạo daterangepicker
        $('#daterange').daterangepicker({
            startDate: moment('<?php echo $start_date; ?>'),
            endDate: moment('<?php echo $end_date; ?>'),
            ranges: {
                'Hôm nay': [moment(), moment()],
                'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '7 ngày qua': [moment().subtract(6, 'days'), moment()],
                '30 ngày qua': [moment().subtract(29, 'days'), moment()],
                'Tháng này': [moment().startOf('month'), moment().endOf('month')],
                'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            locale: {
                format: 'DD/MM/YYYY',
                separator: ' - ',
                applyLabel: 'Áp dụng',
                cancelLabel: 'Hủy',
                fromLabel: 'Từ',
                toLabel: 'Đến',
                customRangeLabel: 'Tùy chọn',
                weekLabel: 'T',
                daysOfWeek: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
                monthNames: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 
                            'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                firstDay: 1
            }
        });

        // Khởi tạo biểu đồ lần đầu
        updateChartData();

        // Thêm hàm khởi tạo biểu đồ doanh thu theo tháng
        function initMonthlyRevenueChart() {
            const monthlyRevenueData = <?php echo json_encode(getMonthlyRevenue($conn)); ?>;
            const options = {
            series: [{
                name: 'Doanh thu',
                data: monthlyRevenueData
            }],
            chart: {
                type: 'bar',
                height: 400,
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: false,
                        zoom: false,
                        zoomin: false,
                        zoomout: false,
                        pan: false,
                        reset: false
                    }
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 5,
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                        return new Intl.NumberFormat('vi-VN', {
                            style: 'currency',
                            currency: 'VND',
                            maximumFractionDigits: 0,
                            notation: "compact",
                            compactDisplay: "short"
                        }).format(val);
                },
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    colors: ["#304758"]
                }
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 
                            'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                labels: {
                    style: {
                        fontSize: '12px'
                    }
                    },
                    title: {
                        text: 'Tháng',
                        style: {
                            fontSize: '14px',
                            fontWeight: 600
                    }
                }
            },
            yaxis: {
                    title: {
                        text: 'Doanh thu (VNĐ)',
                        style: {
                            fontSize: '14px',
                            fontWeight: 600
                        }
                    },
                labels: {
                    formatter: function(val) {
                            return new Intl.NumberFormat('vi-VN', {
                                style: 'currency',
                                currency: 'VND',
                                maximumFractionDigits: 0,
                                notation: "compact",
                                compactDisplay: "short"
                            }).format(val);
                    }
                }
            },
            fill: {
                opacity: 1,
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    type: "vertical",
                    shadeIntensity: 0.25,
                    gradientToColors: undefined,
                    inverseColors: true,
                    opacityFrom: 0.85,
                    opacityTo: 0.85,
                    stops: [50, 0, 100]
                }
            },
            colors: ['#4CAF50'],
            tooltip: {
                y: {
                    formatter: function(val) {
                            return new Intl.NumberFormat('vi-VN', {
                                style: 'currency',
                                currency: 'VND',
                                maximumFractionDigits: 0
                            }).format(val);
                    }
                }
            }
        };

            const monthlyRevenueChart = new ApexCharts(document.querySelector("#monthlyRevenueChart"), options);
        monthlyRevenueChart.render();
        }

        // Khởi tạo biểu đồ doanh thu theo tháng
        initMonthlyRevenueChart();
});
</script>

<?php
// Thêm file get_revenue_data.php để xử lý AJAX request
?>
</body>
</html>