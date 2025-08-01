<?php
require 'billing_header.php';
include "head.php";
?>

<body class="g-sidenav-show bg-gray-200">
    <?php $active = 'hd'; require 'aside.php'; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            <!-- Thống kê thanh toán -->
            <div class="row mb-4">
                <?php foreach ($payment_stats as $stat): ?>
                <div class="col-xl-3 col-sm-6 mb-4">
                    <div class="card stats-card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold"><?php echo $stat['PTTT_TEN']; ?></p>
                                        <h5 class="font-weight-bolder mb-0">
                                            <?php echo number_format($stat['total_amount'], 0, ',', '.'); ?>đ
                                        </h5>
                                        <p class="mb-0 text-sm">
                                            <span class="text-success text-sm font-weight-bolder"><?php echo $stat['total_orders']; ?></span>
                                            đơn hàng
                                        </p>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                        <i class="fas fa-money-bill text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Main content row -->
            <div class="row">
                <!-- Left column - Order list -->
                <div class="col-lg-8">
                    <div class="card card-plain mb-4 shadow-lg">
                        <div class="card-header p-3 pb-0">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4 class="font-weight-bolder mb-0">Danh sách hóa đơn</h4>
                                </div>
                                <div class="col-md-6">
                                    <div class="search-wrapper">
                                        <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3" style="z-index: 3; color: #adb5bd;"></i>
                                        <input type="text" class="form-control ps-5" id="searchInput" placeholder="Tìm kiếm theo mã HD, tên khách hàng, SĐT...">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-4 pt-2">
                            <div class="table-responsive">
                                <table id="orderTable" class="table table-hover align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-dark font-weight-bolder">Mã HD</th>
                                            <th class="text-uppercase text-dark font-weight-bolder">Ngày lập</th>
                                            <th class="text-uppercase text-dark font-weight-bolder">Khách hàng</th>
                                            <th class="text-center text-uppercase text-dark font-weight-bolder">Số SP</th>
                                            <th class="text-uppercase text-dark font-weight-bolder">PT thanh toán</th>
                                            <th class="text-uppercase text-dark font-weight-bolder">Tổng tiền</th>
                                            <th class="text-uppercase text-dark font-weight-bolder">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if ($result_orders && $result_orders->num_rows > 0) {
                                            while ($order = $result_orders->fetch_assoc()): 
                                        ?>
                                        <tr class="bill-row" data-id="<?php echo $order['HD_STT']; ?>">
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">#<?php echo $order['HD_STT']; ?></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm"><?php echo $order['ngay_lap']; ?></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column">
                                                        <h6 class="mb-0 text-sm"><?php echo $order['ten_khach_hang']; ?></h6>
                                                        <p class="text-xs text-secondary mb-0"><?php echo $order['sdt_khach_hang']; ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="badge badge-lg bg-gradient-success">
                                                    <?php echo $order['so_luong_sp']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm"><?php echo $order['phuong_thuc']; ?></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm"><?php echo number_format($order['HD_TONGTIEN'], 0, ',', '.'); ?>đ</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <a href="?hd_id=<?php echo $order['HD_STT']; ?>" 
                                                       class="btn btn-link text-info text-gradient px-3 mb-0">
                                                        <i class="fas fa-info-circle me-2"></i>Chi tiết
                                                    </a>
                                                    <?php if (!empty($order['DVC_MA'])): ?>
                                                    <form action="detail_trans_bill.php" method="POST" style="margin: 0;">
                                                        <input type="hidden" name="dvcid" value="<?php echo $order['DVC_MA']; ?>">
                                                        <input type="hidden" name="nvc" value="<?php echo htmlspecialchars($order['ten_dvc']); ?>">
                                                        <input type="hidden" name="des" value="<?php echo htmlspecialchars($order['dia_chi_dvc']); ?>">
                                                        <input type="hidden" name="start" value="<?php echo $order['ngay_bat_dau']; ?>">
                                                        <input type="hidden" name="finish" value="<?php echo $order['ngay_ket_thuc']; ?>">
                                                        <button type="submit" class="btn btn-link text-warning text-gradient px-3 mb-0">
                                                            <i class="fas fa-truck me-2"></i>Vận chuyển
                                                        </button>
                                                    </form>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php 
                                            endwhile;
                                        } else {
                                        ?>
                                        <tr>
                                            <td colspan="7" class="text-center">Không có hóa đơn nào</td>
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right column - Order details -->
                <div class="col-lg-4">
                    <?php if(isset($_GET["hd_id"])) {
                        $hdid = $_GET["hd_id"];
                        
                        $sql = "SELECT 
                            hd.HD_STT AS mahd, 
                            hd.HD_NGAYLAP AS ngay,
                            kh.KH_MA AS makh,
                            kh.KH_TEN AS tenkh,
                            kh.KH_SDT AS sdtkh,
                            kh.KH_DIACHI AS dckh,
                            nv.NV_MA AS manv,
                            nv.NV_TEN AS tennv
                        FROM hoa_don hd
                        JOIN khach_hang kh ON hd.KH_MA = kh.KH_MA
                        LEFT JOIN nhan_vien nv ON hd.NV_MA = nv.NV_MA
                        WHERE hd.HD_STT = ?";

                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $hdid);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc();
                        
                        if ($row) {
                    ?>
                    <div class="card card-plain shadow-lg animate__animated animate__fadeIn">
                        <div class="card-header bg-gradient-primary p-3">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h6 class="text-white mb-0">Hóa đơn bán hàng</h6>
                                    <p class="text-white opacity-8 mb-0 text-xs">#<?php echo $row['mahd']; ?> - <?php echo date('d/m/Y', strtotime($row['ngay'])); ?></p>
                                </div>
                                <div class="col-4 text-end">
                                    <button onclick="printBillDetail()" class="btn btn-sm btn-white">
                                        In hóa đơn
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <!-- Thông tin cơ bản -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="border-bottom pb-2 mb-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <p class="text-xs text-uppercase text-muted mb-1">Ngày lập hóa đơn</p>
                                                <p class="text-sm font-weight-bold mb-0"><?php echo date('d/m/Y', strtotime($row['ngay'])); ?></p>
                                            </div>
                                            <div class="col-6 text-end">
                                                <p class="text-xs text-uppercase text-muted mb-1">Mã hóa đơn</p>
                                                <p class="text-sm font-weight-bold mb-0">#<?php echo $row['mahd']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Thông tin khách hàng và nhân viên -->
                            <div class="row mb-4">
                                <div class="col-6">
                                    <h6 class="text-uppercase text-muted mb-2 text-xs">Thông tin khách hàng</h6>
                                    <p class="text-sm mb-1">
                                        <span class="font-weight-bold"><?php echo $row['tenkh']; ?></span>
                                    </p>
                                    <p class="text-sm mb-1">Mã KH: <?php echo $row['makh']; ?></p>
                                    <p class="text-sm mb-1">SĐT: <?php echo $row['sdtkh']; ?></p>
                                    <p class="text-sm mb-0">Địa chỉ: <?php echo $row['dckh']; ?></p>
                                </div>
                                <?php if ($row['manv']): ?>
                                <div class="col-6">
                                    <h6 class="text-uppercase text-muted mb-2 text-xs">Thông tin nhân viên</h6>
                                    <p class="text-sm mb-1">
                                        <span class="font-weight-bold"><?php echo $row['tennv']; ?></span>
                                    </p>
                                    <p class="text-sm mb-0">Mã NV: <?php echo $row['manv']; ?></p>
                                </div>
                                <?php endif; ?>
                            </div>

                            <!-- Chi tiết đơn hàng -->
                            <div class="mb-4">
                                <h6 class="text-uppercase text-muted mb-3 text-xs">Chi tiết đơn hàng</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder" style="width: 50%;">Sản phẩm</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center" style="width: 10%;">SL</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-end" style="width: 20%;">Đơn giá</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-end" style="width: 20%;">Thành tiền</th>
                                            </tr>
                                        </thead>
                                        <tbody class="border-top">
                                            <?php
                                            $sql_detail = "SELECT 
                                                sp.SP_TEN,
                                                cthd.CTHD_SOLUONG,
                                                cthd.CTHD_DONGIA,
                                                (cthd.CTHD_SOLUONG * cthd.CTHD_DONGIA) as thanh_tien
                                            FROM chi_tiet_hd cthd
                                            JOIN san_pham sp ON cthd.SP_MA = sp.SP_MA
                                            WHERE cthd.HD_STT = ?";
                                            
                                            $stmt_detail = $conn->prepare($sql_detail);
                                            $stmt_detail->bind_param("i", $hdid);
                                            $stmt_detail->execute();
                                            $result_detail = $stmt_detail->get_result();
                                            
                                            $total = 0;
                                            while ($detail = $result_detail->fetch_assoc()):
                                                $total += $detail['thanh_tien'];
                                            ?>
                                            <tr>
                                                <td class="text-wrap" style="max-width: 300px;">
                                                    <p class="text-sm font-weight-bold mb-0"><?php echo $detail['SP_TEN']; ?></p>
                                                </td>
                                                <td class="text-center">
                                                    <p class="text-sm mb-0"><?php echo $detail['CTHD_SOLUONG']; ?></p>
                                                </td>
                                                <td class="text-end">
                                                    <p class="text-sm mb-0"><?php echo number_format($detail['CTHD_DONGIA'], 0, ',', '.'); ?>đ</p>
                                                </td>
                                                <td class="text-end">
                                                    <p class="text-sm font-weight-bold mb-0"><?php echo number_format($detail['thanh_tien'], 0, ',', '.'); ?>đ</p>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Tổng tiền -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-sm align-items-center mb-0">
                                            <tbody>
                                                <tr>
                                                    <td class="border-0 text-sm" style="width: 60%;">Tổng tiền hàng:</td>
                                                    <td class="border-0 text-sm text-end" style="width: 40%;"><?php echo number_format($total, 0, ',', '.'); ?>đ</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-sm" style="width: 60%;">Phí vận chuyển:</td>
                                                    <td class="text-sm text-end" style="width: 40%;">0đ</td>
                                                </tr>
                                                <tr class="bg-light">
                                                    <td class="text-sm font-weight-bold" style="width: 60%;">Tổng thanh toán:</td>
                                                    <td class="text-sm text-end font-weight-bold" style="width: 40%;"><?php echo number_format($total, 0, ',', '.'); ?>đ</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } else { ?>
                    <div class="card card-plain shadow-lg">
                        <div class="card-body">
                            <div class="text-center">
                                <i class="fas fa-file-invoice fa-4x mb-3 text-secondary"></i>
                                <h5>Chọn một hóa đơn để xem chi tiết</h5>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>

            <?php include 'billing_styles.php'; ?>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
            <?php include 'billing_scripts.php'; ?>
        </div>
    </main>
    <?php 
    } // Close if(isset($_GET["hd_id"]))
    $conn->close(); 
    ?>
</body>
</html> 