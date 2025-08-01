<?php
include 'connect.php';
$active = 'baocaokho';
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
    
    if ($nv_quyen !== 'ADMIN' && !in_array("all", $cv_quyen)) {
        header('Location: dashboard.php');
        exit;
    }
} else {
    header('Location: sign_in.php');
    exit;
}

// Lấy thông tin filter
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
$view = isset($_GET['view']) ? $_GET['view'] : 'overview';

// Thêm thư viện TCPDF
require_once('../vendor/tecnickcom/tcpdf/tcpdf.php');

// Hàm xuất PDF
function exportToPDF($title, $headers, $data) {
    // Tạo PDF mới
    $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8');
    
    // Set document information
    $pdf->SetCreator('Inventory System');
    $pdf->SetAuthor('Admin');
    $pdf->SetTitle($title);

    // Set font
    $pdf->SetFont('dejavusans', '', 10);

    // Add a page
    $pdf->AddPage();

    // Set title
    $pdf->SetFont('dejavusans', 'B', 16);
    $pdf->Cell(0, 10, $title, 0, 1, 'C');
    $pdf->Ln(5);

    // Set headers
    $pdf->SetFont('dejavusans', 'B', 10);
    $pdf->SetFillColor(200, 200, 200);
    foreach ($headers as $i => $header) {
        $width = ($i == 0) ? 40 : 30;
        $pdf->Cell($width, 7, $header, 1, 0, 'C', true);
    }
    $pdf->Ln();

    // Add data
    $pdf->SetFont('dejavusans', '', 9);
    foreach ($data as $row) {
        foreach ($row as $i => $cell) {
            $width = ($i == 0) ? 40 : 30;
            $pdf->Cell($width, 6, $cell, 1);
        }
        $pdf->Ln();
    }

    // Output PDF
    $pdf->Output($title . '.pdf', 'D');
    exit();
}

// Xử lý xuất báo cáo
if (isset($_POST['export']) && isset($_POST['format'])) {
    $format = $_POST['format'];
    $reportType = $_POST['report_type'];

    if ($reportType === 'overview') {
        // Query cho báo cáo tổng quan
        $sql = "SELECT sm.SM_THOIGIAN as 'Thời gian',
                       sp.SP_TEN as 'Sản phẩm',
                       sm.SM_LOAI as 'Loại',
                       sm.SM_SOLUONG as 'Số lượng',
                       sm.SM_SOLUONG_CU as 'Tồn kho cũ',
                       sm.SM_SOLUONG_MOI as 'Tồn kho mới',
                       nv.NV_TEN as 'Nhân viên',
                       sm.SM_GHICHU as 'Ghi chú'
                FROM stock_movements sm
                JOIN san_pham sp ON sm.SP_MA = sp.SP_MA
                LEFT JOIN nhan_vien nv ON sm.NV_MA = nv.NV_MA
                WHERE sm.SM_THOIGIAN BETWEEN ? AND ?";
        
        if ($product_id) {
            $sql .= " AND sm.SP_MA = ?";
        }
        $sql .= " ORDER BY sm.SM_THOIGIAN DESC";

        $stmt = mysqli_prepare($conn, $sql);
        if ($product_id) {
            mysqli_stmt_bind_param($stmt, "ssi", $start_date, $end_date, $product_id);
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $start_date, $end_date);
        }

        $headers = ['Thời gian', 'Sản phẩm', 'Loại', 'Số lượng', 'Tồn kho cũ', 'Tồn kho mới', 'Nhân viên', 'Ghi chú'];
        $title = 'Báo cáo tổng quan tồn kho';

    } else {
        // Query cho lịch sử nhập kho
        $sql = "SELECT pn.PN_NGAYNHAP as 'Thời gian',
                       pn.PN_MA as 'Mã phiếu',
                       nh.NH_TEN as 'Nhà cung cấp',
                       sp.SP_TEN as 'Sản phẩm',
                       CONCAT(ctpn.CTPN_KHOILUONG, ' ', ctpn.CTPN_DONVITINH) as 'Số lượng',
                       ctpn.CTPN_DONGIA as 'Đơn giá',
                       (ctpn.CTPN_KHOILUONG * ctpn.CTPN_DONGIA) as 'Thành tiền',
                       nv.NV_TEN as 'Nhân viên'
                FROM phieu_nhap pn
                JOIN chitiet_pn ctpn ON pn.PN_STT = ctpn.PN_STT
                JOIN san_pham sp ON ctpn.SP_MA = sp.SP_MA
                JOIN nguon_hang nh ON ctpn.NH_MA = nh.NH_MA
                JOIN nhan_vien nv ON pn.NV_MA = nv.NV_MA
                WHERE pn.PN_NGAYNHAP BETWEEN ? AND ?";

        if ($product_id) {
            $sql .= " AND ctpn.SP_MA = ?";
        }
        $sql .= " ORDER BY pn.PN_NGAYNHAP DESC";

        $stmt = mysqli_prepare($conn, $sql);
        if ($product_id) {
            mysqli_stmt_bind_param($stmt, "ssi", $start_date, $end_date, $product_id);
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $start_date, $end_date);
        }

        $headers = ['Thời gian', 'Mã phiếu', 'Nhà cung cấp', 'Sản phẩm', 'Số lượng', 'Đơn giá', 'Thành tiền', 'Nhân viên'];
        $title = 'Báo cáo lịch sử nhập kho';
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        // Format data
        if (isset($row['Thời gian'])) {
            $row['Thời gian'] = date('d/m/Y H:i:s', strtotime($row['Thời gian']));
        }
        if (isset($row['Đơn giá'])) {
            $row['Đơn giá'] = number_format($row['Đơn giá'], 0, ',', '.');
        }
        if (isset($row['Thành tiền'])) {
            $row['Thành tiền'] = number_format($row['Thành tiền'], 0, ',', '.');
        }
        $data[] = array_values($row);
    }

    if ($format === 'excel') {
        // Xuất Excel
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $title . '.xls"');
        header('Cache-Control: max-age=0');

        // Print headers
        echo '<table border="1">';
        echo '<tr>';
        foreach ($headers as $header) {
            echo '<th>' . $header . '</th>';
        }
        echo '</tr>';

        // Print data
        foreach ($data as $row) {
            echo '<tr>';
            foreach ($row as $cell) {
                echo '<td>' . $cell . '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
        exit();

    } else if ($format === 'pdf') {
        // Xuất PDF
        exportToPDF($title, $headers, $data);
    }
}

?>

<style>
.main-content {
    margin-left: 17rem;
    padding: 2rem;
    min-height: 100vh;
    transition: margin-left 0.3s ease-in-out;
}

@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
    }
}

.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    max-width: 100%;
}

.table {
    width: 100%;
    margin-bottom: 1rem;
    white-space: nowrap;
}

.nav-tabs .nav-link {
    color: #344767;
    font-weight: 500;
}

.nav-tabs .nav-link.active {
    color: #696cff;
    border-bottom: 2px solid #696cff;
}

.export-buttons {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.export-buttons .btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.export-buttons .btn i {
    font-size: 1.25rem;
}
</style>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <?php include 'aside.php'; ?>
    <div class="container-fluid py-4">
        <!-- Form lọc -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" class="row">
                            <input type="hidden" name="view" value="<?php echo $view; ?>">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Từ ngày</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $start_date; ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">Đến ngày</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $end_date; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="product_id" class="form-label">Sản phẩm</label>
                                    <select class="form-control" id="product_id" name="product_id">
                                        <option value="">Tất cả sản phẩm</option>
                                        <?php
                                        $sql = "SELECT SP_MA, SP_TEN FROM san_pham ORDER BY SP_TEN";
                                        $result = mysqli_query($conn, $sql);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $selected = ($row['SP_MA'] == $product_id) ? 'selected' : '';
                                            echo "<option value='" . $row['SP_MA'] . "' $selected>" 
                                                . htmlspecialchars($row['SP_TEN']) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary d-block w-100">Lọc</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link <?php echo $view === 'overview' ? 'active' : ''; ?>" 
                   href="?view=overview&start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>&product_id=<?php echo $product_id; ?>">
                    Tổng quan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $view === 'import' ? 'active' : ''; ?>" 
                   href="?view=import&start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>&product_id=<?php echo $product_id; ?>">
                    Lịch sử nhập kho
                </a>
            </li>
        </ul>

        <?php if ($view === 'overview'): ?>
        <!-- Nút xuất báo cáo cho tổng quan -->
        <div class="export-buttons">
            <form method="post" style="display: inline;">
                <input type="hidden" name="report_type" value="overview">
                <input type="hidden" name="start_date" value="<?php echo $start_date; ?>">
                <input type="hidden" name="end_date" value="<?php echo $end_date; ?>">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                
                <button type="submit" name="export" value="export" class="btn btn-success" onclick="this.form.format.value='excel'">
                    <i class="material-icons">file_download</i>
                    Xuất Excel
                </button>
                <input type="hidden" name="format" value="">
                
                <button type="submit" name="export" value="export" class="btn btn-danger" onclick="this.form.format.value='pdf'">
                    <i class="material-icons">picture_as_pdf</i>
                    Xuất PDF
                </button>
            </form>
        </div>

        <!-- Báo cáo tổng quan -->
        <div class="row mb-4">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div class="text-center pt-1">
                            <h4 class="mb-0">Tổng nhập kho</h4>
                            <?php
                            $sql = "SELECT COALESCE(SUM(SM_SOLUONG), 0) as total_in 
                                    FROM stock_movements 
                                    WHERE SM_LOAI = 'NHAP'
                                    AND SM_THOIGIAN BETWEEN ? AND ?";
                            if ($product_id) {
                                $sql .= " AND SP_MA = ?";
                            }
                            $stmt = mysqli_prepare($conn, $sql);
                            if ($product_id) {
                                mysqli_stmt_bind_param($stmt, "ssi", $start_date, $end_date, $product_id);
                            } else {
                                mysqli_stmt_bind_param($stmt, "ss", $start_date, $end_date);
                            }
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            $row = mysqli_fetch_assoc($result);
                            ?>
                            <h6 class="mb-0"><?php echo number_format($row['total_in'], 0, ',', '.'); ?></h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div class="text-center pt-1">
                            <h4 class="mb-0">Tổng xuất kho</h4>
                            <?php
                            $sql = "SELECT COALESCE(SUM(SM_SOLUONG), 0) as total_out 
                                    FROM stock_movements 
                                    WHERE SM_LOAI = 'XUAT'
                                    AND SM_THOIGIAN BETWEEN ? AND ?";
                            if ($product_id) {
                                $sql .= " AND SP_MA = ?";
                            }
                            $stmt = mysqli_prepare($conn, $sql);
                            if ($product_id) {
                                mysqli_stmt_bind_param($stmt, "ssi", $start_date, $end_date, $product_id);
                            } else {
                                mysqli_stmt_bind_param($stmt, "ss", $start_date, $end_date);
                            }
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            $row = mysqli_fetch_assoc($result);
                            ?>
                            <h6 class="mb-0"><?php echo number_format($row['total_out'], 0, ',', '.'); ?></h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div class="text-center pt-1">
                            <h4 class="mb-0">Tổng điều chỉnh</h4>
                            <?php
                            $sql = "SELECT COUNT(*) as total_adjustments 
                                    FROM stock_movements 
                                    WHERE SM_LOAI = 'KIEM'
                                    AND SM_THOIGIAN BETWEEN ? AND ?";
                            if ($product_id) {
                                $sql .= " AND SP_MA = ?";
                            }
                            $stmt = mysqli_prepare($conn, $sql);
                            if ($product_id) {
                                mysqli_stmt_bind_param($stmt, "ssi", $start_date, $end_date, $product_id);
                            } else {
                                mysqli_stmt_bind_param($stmt, "ss", $start_date, $end_date);
                            }
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            $row = mysqli_fetch_assoc($result);
                            ?>
                            <h6 class="mb-0"><?php echo number_format($row['total_adjustments'], 0, ',', '.'); ?></h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div class="text-center pt-1">
                            <h4 class="mb-0">Sản phẩm sắp hết</h4>
                            <?php
                            $sql = "SELECT COUNT(*) as low_stock 
                                    FROM san_pham 
                                    WHERE SP_SOLUONGTON <= 10";
                            if ($product_id) {
                                $sql .= " AND SP_MA = ?";
                            }
                            $stmt = mysqli_prepare($conn, $sql);
                            if ($product_id) {
                                mysqli_stmt_bind_param($stmt, "i", $product_id);
                            }
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            $row = mysqli_fetch_assoc($result);
                            ?>
                            <h6 class="mb-0"><?php echo number_format($row['low_stock'], 0, ',', '.'); ?></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chi tiết báo cáo -->
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">Chi tiết báo cáo tồn kho</h6>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Thời gian</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Sản phẩm</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Loại</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Số lượng</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tồn kho cũ</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tồn kho mới</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nhân viên</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Ghi chú</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT sm.*, sp.SP_TEN, nv.NV_TEN 
                                            FROM stock_movements sm
                                            JOIN san_pham sp ON sm.SP_MA = sp.SP_MA
                                            LEFT JOIN nhan_vien nv ON sm.NV_MA = nv.NV_MA
                                            WHERE sm.SM_THOIGIAN BETWEEN ? AND ?";
                                    if ($product_id) {
                                        $sql .= " AND sm.SP_MA = ?";
                                    }
                                    $sql .= " ORDER BY sm.SM_THOIGIAN DESC";
                                    
                                    $stmt = mysqli_prepare($conn, $sql);
                                    if ($product_id) {
                                        mysqli_stmt_bind_param($stmt, "ssi", $start_date, $end_date, $product_id);
                                    } else {
                                        mysqli_stmt_bind_param($stmt, "ss", $start_date, $end_date);
                                    }
                                    mysqli_stmt_execute($stmt);
                                    $result = mysqli_stmt_get_result($stmt);

                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $statusClass = '';
                                        switch ($row['SM_LOAI']) {
                                            case 'NHAP':
                                                $statusClass = 'bg-gradient-success';
                                                break;
                                            case 'XUAT':
                                                $statusClass = 'bg-gradient-danger';
                                                break;
                                            case 'KIEM':
                                                $statusClass = 'bg-gradient-info';
                                                break;
                                        }
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm"><?php echo date('d/m/Y H:i:s', strtotime($row['SM_THOIGIAN'])); ?></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($row['SP_TEN']); ?></p>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm <?php echo $statusClass; ?>"><?php echo $row['SM_LOAI']; ?></span>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?php echo number_format($row['SM_SOLUONG'], 0, ',', '.'); ?></p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?php echo number_format($row['SM_SOLUONG_CU'], 0, ',', '.'); ?></p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?php echo number_format($row['SM_SOLUONG_MOI'], 0, ',', '.'); ?></p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($row['NV_TEN'] ?? 'N/A'); ?></p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($row['SM_GHICHU']); ?></p>
                                            </td>
                                        </tr>
                                        <?php
                                    }

                                    if (mysqli_num_rows($result) == 0) {
                                        echo '<tr><td colspan="8" class="text-center">Không có dữ liệu trong khoảng thời gian này</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <!-- Nút xuất báo cáo cho lịch sử nhập kho -->
        <div class="export-buttons">
            <form method="post" style="display: inline;">
                <input type="hidden" name="report_type" value="import">
                <input type="hidden" name="start_date" value="<?php echo $start_date; ?>">
                <input type="hidden" name="end_date" value="<?php echo $end_date; ?>">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                
                <button type="submit" name="export" value="export" class="btn btn-success" onclick="this.form.format.value='excel'">
                    <i class="material-icons">file_download</i>
                    Xuất Excel
                </button>
                <input type="hidden" name="format" value="">
                
                <button type="submit" name="export" value="export" class="btn btn-danger" onclick="this.form.format.value='pdf'">
                    <i class="material-icons">picture_as_pdf</i>
                    Xuất PDF
                </button>
            </form>
        </div>

        <!-- Lịch sử nhập kho -->
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">Lịch sử nhập kho</h6>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Thời gian</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Mã phiếu</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nhà cung cấp</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Sản phẩm</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Số lượng</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Đơn giá</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Thành tiền</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nhân viên</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT pn.PN_NGAYNHAP, pn.PN_MA, nh.NH_TEN, sp.SP_TEN, 
                                                  ctpn.CTPN_KHOILUONG, ctpn.CTPN_DONVITINH, ctpn.CTPN_DONGIA,
                                                  nv.NV_TEN
                                           FROM phieu_nhap pn
                                           JOIN chitiet_pn ctpn ON pn.PN_STT = ctpn.PN_STT
                                           JOIN san_pham sp ON ctpn.SP_MA = sp.SP_MA
                                           JOIN nguon_hang nh ON ctpn.NH_MA = nh.NH_MA
                                           JOIN nhan_vien nv ON pn.NV_MA = nv.NV_MA
                                           WHERE pn.PN_NGAYNHAP BETWEEN ? AND ?";
                                    if ($product_id) {
                                        $sql .= " AND ctpn.SP_MA = ?";
                                    }
                                    $sql .= " ORDER BY pn.PN_NGAYNHAP DESC";
                                    
                                    $stmt = mysqli_prepare($conn, $sql);
                                    if ($product_id) {
                                        mysqli_stmt_bind_param($stmt, "ssi", $start_date, $end_date, $product_id);
                                    } else {
                                        mysqli_stmt_bind_param($stmt, "ss", $start_date, $end_date);
                                    }
                                    mysqli_stmt_execute($stmt);
                                    $result = mysqli_stmt_get_result($stmt);

                                    while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm"><?php echo date('d/m/Y H:i:s', strtotime($row['PN_NGAYNHAP'])); ?></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($row['PN_MA']); ?></p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($row['NH_TEN']); ?></p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($row['SP_TEN']); ?></p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    <?php echo number_format($row['CTPN_KHOILUONG'], 1) . ' ' . $row['CTPN_DONVITINH']; ?>
                                                </p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    <?php echo number_format($row['CTPN_DONGIA'], 0, ',', '.'); ?>
                                                </p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    <?php echo number_format($row['CTPN_KHOILUONG'] * $row['CTPN_DONGIA'], 0, ',', '.'); ?>
                                                </p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($row['NV_TEN']); ?></p>
                                            </td>
                                        </tr>
                                        <?php
                                    }

                                    if (mysqli_num_rows($result) == 0) {
                                        echo '<tr><td colspan="9" class="text-center">Không có dữ liệu trong khoảng thời gian này</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</main>

<?php include 'billing_scripts.php'; ?> 