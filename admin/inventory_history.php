<?php
include 'connect.php';
$active = 'tonkho';
include 'head.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['NV_MA'])) {
    header('Location: sign_in.php');
    exit;
}

// Kiểm tra quyền admin
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
    
    // Kiểm tra nếu không phải ADMIN hoặc không có quyền "all" trong CV_QUYEN
    if ($nv_quyen !== 'ADMIN' && !in_array("all", $cv_quyen)) {
        header('Location: dashboard.php');
        exit;
    }
} else {
    header('Location: sign_in.php');
    exit;
}

// Lấy thông tin sản phẩm
$productId = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
$sql = "SELECT SP_MA, SP_TEN, SP_SOLUONGTON, SP_DONVITINH FROM san_pham WHERE SP_MA = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $productId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    header('Location: inventory.php');
    exit;
}
?>

<body class="g-sidenav-show bg-gray-200">
    <?php include 'aside.php'; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Trang</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Lịch sử tồn kho</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">Lịch sử tồn kho</h6>
                </nav>
            </div>
        </nav>
        <!-- End Navbar -->

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                                <h6 class="text-white text-capitalize ps-3 mb-0">Lịch sử tồn kho - <?php echo htmlspecialchars($product['SP_TEN']); ?></h6>
                                <a href="inventory.php" class="btn btn-sm btn-info me-3">Quay lại</a>
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- Thông tin sản phẩm -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title mb-3">Thông tin sản phẩm</h5>
                                            <p class="mb-2"><strong>Mã sản phẩm:</strong> <?php echo $product['SP_MA']; ?></p>
                                            <p class="mb-2"><strong>Tên sản phẩm:</strong> <?php echo htmlspecialchars($product['SP_TEN']); ?></p>
                                            <p class="mb-0"><strong>Số lượng hiện tại:</strong> <?php echo $product['SP_SOLUONGTON'] . ' ' . $product['SP_DONVITINH']; ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Lịch sử điều chỉnh -->
                            <div class="table-responsive">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Lịch sử điều chỉnh tồn kho</h5>
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Thời gian</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Loại điều chỉnh</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Số lượng</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tồn kho cũ</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tồn kho mới</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nhân viên</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ghi chú</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Lấy lịch sử điều chỉnh
                                                $sql = "SELECT lstk.*, nv.NV_TEN 
                                                        FROM lich_su_ton_kho lstk
                                                        LEFT JOIN nhan_vien nv ON lstk.NV_MA = nv.NV_MA
                                                        WHERE lstk.SP_MA = ?
                                                        ORDER BY lstk.LSTK_THOIGIAN DESC";
                                                $stmt = mysqli_prepare($conn, $sql);
                                                mysqli_stmt_bind_param($stmt, "i", $productId);
                                                mysqli_stmt_execute($stmt);
                                                $result = mysqli_stmt_get_result($stmt);

                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $statusClass = '';
                                                    $statusBadge = '';
                                                    switch ($row['LSTK_LOAI']) {
                                                        case 'NHAP':
                                                            $statusClass = 'bg-gradient-success';
                                                            $statusBadge = 'Nhập kho';
                                                            break;
                                                        case 'XUAT':
                                                            $statusClass = 'bg-gradient-danger';
                                                            $statusBadge = 'Xuất kho';
                                                            break;
                                                        default:
                                                            $statusClass = 'bg-gradient-info';
                                                            $statusBadge = 'Điều chỉnh';
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex px-2 py-1">
                                                                <div class="d-flex flex-column justify-content-center">
                                                                    <h6 class="mb-0 text-sm"><?php echo date('d/m/Y H:i:s', strtotime($row['LSTK_THOIGIAN'])); ?></h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-sm <?php echo $statusClass; ?>"><?php echo $statusBadge; ?></span>
                                                        </td>
                                                        <td>
                                                            <p class="text-xs font-weight-bold mb-0"><?php echo $row['LSTK_SOLUONG']; ?></p>
                                                        </td>
                                                        <td>
                                                            <p class="text-xs font-weight-bold mb-0"><?php echo $row['LSTK_SOLUONG_CU']; ?></p>
                                                        </td>
                                                        <td>
                                                            <p class="text-xs font-weight-bold mb-0"><?php echo $row['LSTK_SOLUONG_MOI']; ?></p>
                                                        </td>
                                                        <td>
                                                            <p class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($row['NV_TEN'] ?? 'N/A'); ?></p>
                                                        </td>
                                                        <td>
                                                            <p class="text-xs text-wrap mb-0" style="max-width: 250px;">
                                                                <?php 
                                                                    $ghichu = $row['LSTK_GHICHU'];
                                                                    if (empty($ghichu)) {
                                                                        echo '<span class="text-muted fst-italic">Không có ghi chú</span>';
                                                                    } else {
                                                                        echo htmlspecialchars($ghichu);
                                                                    }
                                                                ?>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }

                                                if (mysqli_num_rows($result) == 0) {
                                                    echo '<tr><td colspan="7" class="text-center">Chưa có lịch sử điều chỉnh</td></tr>';
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Lịch sử giao dịch -->
                            <div class="table-responsive mt-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Lịch sử giao dịch</h5>
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Thời gian</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Mã đơn hàng</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Số lượng</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Đơn giá</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Loại giao dịch</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Lấy lịch sử từ hóa đơn
                                                $sql = "SELECT hd.HD_STT, hd.HD_NGAYLAP, cthd.CTHD_SOLUONG, cthd.CTHD_DONGIA
                                                        FROM hoa_don hd 
                                                        JOIN chi_tiet_hd cthd ON hd.HD_STT = cthd.HD_STT
                                                        WHERE cthd.SP_MA = ?
                                                        ORDER BY hd.HD_NGAYLAP DESC";
                                                $stmt = mysqli_prepare($conn, $sql);
                                                mysqli_stmt_bind_param($stmt, "i", $productId);
                                                mysqli_stmt_execute($stmt);
                                                $result = mysqli_stmt_get_result($stmt);

                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex px-2 py-1">
                                                                <div class="d-flex flex-column justify-content-center">
                                                                    <h6 class="mb-0 text-sm"><?php echo date('d/m/Y H:i:s', strtotime($row['HD_NGAYLAP'])); ?></h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <p class="text-xs font-weight-bold mb-0"><?php echo $row['HD_STT']; ?></p>
                                                        </td>
                                                        <td>
                                                            <p class="text-xs font-weight-bold mb-0"><?php echo $row['CTHD_SOLUONG']; ?></p>
                                                        </td>
                                                        <td>
                                                            <p class="text-xs font-weight-bold mb-0"><?php echo number_format($row['CTHD_DONGIA'], 0, ',', '.'); ?> đ</p>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-sm bg-gradient-danger">Bán hàng</span>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }

                                                if (mysqli_num_rows($result) == 0) {
                                                    echo '<tr><td colspan="5" class="text-center">Chưa có lịch sử giao dịch</td></tr>';
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'billing_scripts.php'; ?>

    <style>
    .main-content {
        margin-left: 17.125rem;
        transition: 0.3s ease;
    }

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        margin: 0;
        padding: 0;
    }

    .card {
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
    }

    .card-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #344767;
    }

    .table thead th {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        border-bottom: 1px solid #e9ecef;
    }

    .table td {
        font-size: 0.875rem;
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
    }

    @media (max-width: 1199.98px) {
        .main-content {
            margin-left: 0;
        }
    }
    </style>
</body>
</html> 