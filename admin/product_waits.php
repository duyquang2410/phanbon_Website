<!--
=========================================================
* Material Dashboard 2 - v3.1.0
=========================================================

* Product Page: https://www.creative-tim.com/product/material-dashboard
* Copyright 2023 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<?php
session_start();
include "head.php";
?>

<style>
    /* Custom font styles */
    .table thead th {
        font-size: 0.85rem !important;
        font-weight: 600 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 16px !important;
    }

    .table tbody td {
        font-size: 0.9rem !important;
        padding: 12px 16px !important;
        vertical-align: middle !important;
    }

    .table .text-xs {
        font-size: 0.85rem !important;
    }

    /* Add these new styles */
    .table td p {
        margin-bottom: 0 !important;
    }

    .table td .customer-name {
        margin-bottom: 4px !important;
    }

    .table td .price {
        white-space: nowrap;
    }

    .table td .d-flex {
        align-items: center;
    }

    .breadcrumb-item {
        font-size: 1rem !important;
    }

    .font-weight-bolder {
        font-weight: 600 !important;
    }

    .card-header .text-sm {
        font-size: 0.9rem !important;
    }

    .card-header h5 {
        font-size: 1.25rem !important;
        font-weight: 600;
    }

    .stats-card {
        font-size: 1rem !important;
    }

    .stats-card h4 {
        font-size: 1.5rem !important;
        font-weight: 600;
    }

    .form-select {
        font-size: 0.9rem !important;
    }

    .search-input {
        font-size: 0.9rem !important;
    }

    .badge {
        font-size: 0.8rem !important;
        padding: 0.5em 0.8em;
    }

    .btn-sm {
        font-size: 0.85rem !important;
    }

    .customer-name {
        font-size: 1rem !important;
        font-weight: 600;
    }

    .customer-info {
        font-size: 0.9rem !important;
    }

    .price {
        font-size: 1rem !important;
        font-weight: 600;
    }

    /* Action Buttons Styling */
    .action-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 6px;
        min-width: 200px; /* Đảm bảo cột thao tác có độ rộng cố định */
    }

    .action-btn {
        padding: 6px 16px !important;
        border-radius: 4px !important;
        transition: all 0.2s ease;
        font-size: 13px !important;
        font-weight: 500 !important;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        min-width: 80px;
        color: #fff !important;
        text-decoration: none;
    }

    .action-btn:hover {
        opacity: 0.9;
    }

    .btn-view {
        background-color: #2196f3 !important;
    }

    .btn-confirm {
        background-color: #4caf50 !important;
    }

    .btn-cancel {
        background-color: #f44336 !important;
    }

    /* Status badge styles */
    .status-badge {
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
        text-align: center;
        min-width: 100px;
    }

    .status-pending {
        background-color: #2196f3;
        color: #fff;
    }

    .status-processing {
        background-color: #ff9800;
        color: #fff;
    }

    .status-delivered {
        background-color: #4caf50;
        color: #fff;
    }

    .status-cancelled {
        background-color: #f44336;
        color: #fff;
    }

    /* Cột thao tác */
    .action-column {
        width: 100px !important;
        text-align: center;
    }

    .action-buttons {
        display: flex !important;
        justify-content: flex-end !important;
        gap: 8px !important;
    }

    .action-btn {
        padding: 6px 12px !important;
        border-radius: 4px !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        height: 32px !important;
        min-width: auto !important;
        white-space: nowrap !important;
        color: #fff !important;
        text-decoration: none !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    /* Responsive table */
    .table-responsive {
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch !important;
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
    }

    .table {
        margin-bottom: 0 !important;
        width: 100% !important;
    }

    /* Fix column widths */
    .table th:nth-child(1) { width: 8% !important; }  /* Mã đơn */
    .table th:nth-child(2) { width: 20% !important; } /* Khách hàng */
    .table th:nth-child(3) { width: 12% !important; } /* Nhân viên */
    .table th:nth-child(4) { width: 12% !important; } /* Tổng tiền */
    .table th:nth-child(5) { width: 15% !important; } /* Phương thức */
    .table th:nth-child(6) { width: 15% !important; } /* Ngày đặt */
    .table th:nth-child(7) { width: 10% !important; } /* Trạng thái */
    .table th:nth-child(8) { width: 8% !important; }  /* Thao tác */

    @media (max-width: 1200px) {
        .table-responsive {
            overflow-x: auto !important;
        }
        
        .table {
            min-width: 1000px !important;
        }
        
        .action-btn {
            padding: 6px 8px !important;
            font-size: 12px !important;
        }
    }

    .sidenav {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        bottom: 0 !important;
        width: 250px !important;
        background: #344767 !important;
        z-index: 999 !important;
        overflow-y: auto !important;
        overflow-x: hidden !important;
        transition: all 0.3s ease !important;
    }

    .main-content {
        margin-left: 250px !important;
        min-height: 100vh !important;
        padding: 1rem !important;
    }

    /* Responsive */
    @media (max-width: 991.98px) {
        .sidenav {
            transform: translateX(-250px) !important;
        }
        
        .main-content {
            margin-left: 0 !important;
        }
        
        .g-sidenav-show.g-sidenav-pinned .sidenav {
            transform: translateX(0) !important;
        }
    }

    /* Fix scrollbar style */
    .sidenav::-webkit-scrollbar {
        width: 6px !important;
    }

    .sidenav::-webkit-scrollbar-track {
        background: transparent !important;
    }

    .sidenav::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2) !important;
        border-radius: 3px !important;
    }

    /* Fix content height */
    .container-fluid {
        min-height: calc(100vh - 120px) !important;
    }

    /* Stats cards row */
    .stats-cards-row {
        position: sticky !important;
        top: 0 !important;
        z-index: 100 !important;
        background: #f8f9fa !important;
        padding: 1rem 0 !important;
        margin: -1rem -1rem 1rem -1rem !important;
    }

    /* Table container */
    .table-container {
        margin-top: 1rem !important;
        background: white !important;
        border-radius: 0.5rem !important;
        box-shadow: 0 2px 12px 0 rgba(0,0,0,0.1) !important;
    }
</style>

<body class="g-sidenav-show bg-gray-200">
    <?php $active = 'dh'; require 'aside.php'; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item"><a class="opacity-5 text-dark" href="javascript:;">Trang chủ</a></li>
                        <li class="breadcrumb-item text-dark active" aria-current="page">Quản lý đơn hàng</li>
                    </ol>
                    <h3 class="font-weight-bolder mb-0" style="font-size: 1.75rem;">Quản lý đơn hàng</h3>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                        <div class="input-group input-group-outline border-radius-xl bg-white shadow-lg">
                            <form method="GET" action="" class="d-flex w-100" style="position: relative;" id="searchForm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-transparent border-0">
                                        <i class="fas fa-search text-primary"></i>
                                    </span>
                                </div>
                                <input type="text" name="search" id="searchInput" class="form-control border-0 ps-2 search-input" 
                                    placeholder="Tìm kiếm đơn hàng..." 
                                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                                    oninput="handleSearchInput(this.value)">
                                <?php if(isset($_GET['status'])): ?>
                                    <input type="hidden" name="status" value="<?php echo htmlspecialchars($_GET['status']); ?>">
                                <?php endif; ?>
                                <?php if(isset($_GET['payment'])): ?>
                                    <input type="hidden" name="payment" value="<?php echo htmlspecialchars($_GET['payment']); ?>">
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                    <ul class="navbar-nav justify-content-end">
                        <li class="nav-item d-flex align-items-center mb-4 me-4">
                            <img src="../asset_admin/img/staff_img/team-4.jpg" class="rounded-circle avatar avatar-sm me-2">
                            <span class="d-sm-inline d-none me-2">Xin chào, <?php echo isset($_SESSION['NV_TEN']) ? $_SESSION['NV_TEN'] : 'Khách'; ?></span>
                            <a href="log_out.php" class="btn btn-outline-primary btn-sm mb-0">Đăng xuất</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid py-4">
            <!-- Stats cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-sm-6 mb-xl-0">
                    <div class="card stats-card">
                        <div class="card-header p-3 pt-2">
                            <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                                <i class="fas fa-shopping-cart opacity-10"></i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">Tổng đơn hàng</p>
                                <h4 class="mb-0">
                                    <?php
                                    require 'connect.php';
                                    $sql = "SELECT COUNT(*) as total FROM hoa_don";
                                    $result = $conn->query($sql);
                                    $row = $result->fetch_assoc();
                                    echo number_format($row['total']);
                                    ?>
                                </h4>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-3">
                            <p class="mb-0">Tất cả đơn hàng</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0">
                    <div class="card stats-card">
                        <div class="card-header p-3 pt-2">
                            <div class="icon icon-lg icon-shape bg-gradient-warning shadow-warning text-center border-radius-xl mt-n4 position-absolute">
                                <i class="fas fa-clock opacity-10"></i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">Chờ xác nhận</p>
                                <h4 class="mb-0">
                                    <?php
                                    $sql = "SELECT COUNT(*) as total FROM hoa_don WHERE TT_MA = 0";
                                    $result = $conn->query($sql);
                                    $row = $result->fetch_assoc();
                                    echo number_format($row['total']);
                                    ?>
                                </h4>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-3">
                            <p class="mb-0">Đơn hàng mới</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0">
                    <div class="card stats-card">
                        <div class="card-header p-3 pt-2">
                            <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                                <i class="fas fa-check opacity-10"></i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">Đã giao</p>
                                <h4 class="mb-0">
                                    <?php
                                    $sql = "SELECT COUNT(*) as total FROM hoa_don WHERE TT_MA = 3";
                                    $result = $conn->query($sql);
                                    $row = $result->fetch_assoc();
                                    echo number_format($row['total']);
                                    ?>
                                </h4>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-3">
                            <p class="mb-0">Đơn hàng thành công</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="card stats-card">
                        <div class="card-header p-3 pt-2">
                            <div class="icon icon-lg icon-shape bg-gradient-danger shadow-danger text-center border-radius-xl mt-n4 position-absolute">
                                <i class="fas fa-times opacity-10"></i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">Đã hủy</p>
                                <h4 class="mb-0">
                                    <?php
                                    $sql = "SELECT COUNT(*) as total FROM hoa_don WHERE TT_MA = 4";
                                    $result = $conn->query($sql);
                                    $row = $result->fetch_assoc();
                                    echo number_format($row['total']);
                                    ?>
                                </h4>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-3">
                            <p class="mb-0">Đơn hàng đã hủy</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter section -->
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0">Danh sách đơn hàng</h5>
                                    <p class="text-sm mb-0">Quản lý tất cả đơn hàng của bạn</p>
                                </div>
                                <div class="d-flex gap-2">
                                    <select name="status" class="form-select border px-3 py-2" style="border-radius: 8px; min-width: 180px;" onchange="filterOrders()">
                                        <option value="" selected>Tất cả trạng thái</option>
                                        <?php
                                        $sql = "SELECT * FROM trang_thai";
                                        $result = $conn->query($sql);
                                        if ($result->num_rows > 0) {
                                            while($row = $result->fetch_assoc()) {
                                                $selected = (isset($_GET['status']) && $_GET['status'] == $row["TT_MA"]) ? 'selected' : '';
                                                echo "<option value='" . $row["TT_MA"] . "' " . $selected . ">" . $row["TT_TEN"] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    <select name="payment" class="form-select border px-3 py-2" style="border-radius: 8px; min-width: 200px;" onchange="filterOrders()">
                                        <option value="" selected>Tất cả phương thức</option>
                                        <?php
                                        $sql = "SELECT * FROM phuong_thuc_thanh_toan";
                                        $result = $conn->query($sql);
                                        if ($result->num_rows > 0) {
                                            while($row = $result->fetch_assoc()) {
                                                $selected = (isset($_GET['payment']) && $_GET['payment'] == $row["PTTT_MA"]) ? 'selected' : '';
                                                echo "<option value='" . $row["PTTT_MA"] . "' " . $selected . ">" . $row["PTTT_TEN"] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <?php if (isset($_SESSION['success'])): ?>
                                <div class="alert alert-success alert-dismissible fade show mx-4 mt-4" role="alert">
                                    <?php 
                                    echo $_SESSION['success'];
                                    unset($_SESSION['success']);
                                    ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <?php if (isset($_SESSION['error'])): ?>
                                <div class="alert alert-danger alert-dismissible fade show mx-4 mt-4" role="alert">
                                    <?php 
                                    echo $_SESSION['error'];
                                    unset($_SESSION['error']);
                                    ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="width: 10%;">Mã đơn</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="width: 20%;">Khách hàng</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="width: 15%;">Nhân viên</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="width: 12%;">Tổng tiền</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="width: 15%;">Phương thức</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="width: 15%;">Ngày đặt</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="width: 13%;">Trạng thái</th>
                                            <th class="text-uppercase text-secondary font-weight-bolder opacity-7 action-column">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        require 'connect.php';
                                        $sql = "SELECT hd.*, tt.TT_TEN, pttt.PTTT_TEN, kh.KH_TEN, kh.KH_SDT, kh.KH_DIACHI, nv.NV_TEN 
                                                FROM hoa_don hd
                                                LEFT JOIN trang_thai tt ON hd.TT_MA = tt.TT_MA
                                                LEFT JOIN phuong_thuc_thanh_toan pttt ON hd.PTTT_MA = pttt.PTTT_MA
                                                LEFT JOIN khach_hang kh ON hd.KH_MA = kh.KH_MA
                                                LEFT JOIN nhan_vien nv ON hd.NV_MA = nv.NV_MA
                                                WHERE 1=1";

                                        if(isset($_GET['search']) && !empty($_GET['search'])) {
                                            $search = trim($_GET['search']);
                                            $search = mysqli_real_escape_string($conn, $search);
                                            // Remove # if present at the start of search term
                                            $search = ltrim($search, '#');
                                            $sql .= " AND (hd.HD_STT LIKE '%$search%' OR kh.KH_TEN LIKE '%$search%' OR kh.KH_SDT LIKE '%$search%')";
                                        }

                                        if(isset($_GET['status']) && !empty($_GET['status'])) {
                                            $status = $_GET['status'];
                                            $sql .= " AND hd.TT_MA = $status";
                                        }

                                        if(isset($_GET['payment']) && !empty($_GET['payment'])) {
                                            $payment = $_GET['payment'];
                                            $sql .= " AND hd.PTTT_MA = $payment";
                                        }

                                        $sql .= " ORDER BY hd.HD_NGAYLAP DESC";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            while($row = $result->fetch_assoc()) {
                                                ?>
                                                <tr>
                                                    <td>
                                                        <p class="font-weight-bold mb-0 ps-3" style="font-size: 0.95rem;">#<?php echo $row["HD_STT"]; ?></p>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex px-2 py-1">
                                                            <div class="d-flex flex-column justify-content-center">
                                                                <h6 class="mb-0 customer-name"><?php echo $row["KH_TEN"]; ?></h6>
                                                                <p class="customer-info text-secondary mb-0">
                                                                    <i class="fas fa-phone-alt me-1"></i>
                                                                    <?php echo $row["KH_SDT"]; ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p class="font-weight-bold mb-0" style="font-size: 0.95rem;">
                                                            <?php 
                                                            if($row["NV_TEN"]) {
                                                                echo '<i class="fas fa-user-tie me-1"></i>' . $row["NV_TEN"];
                                                            } else {
                                                                echo '<span class="text-warning"><i class="fas fa-clock me-1"></i>Chưa được duyệt</span>';
                                                            }
                                                            ?>
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p class="price text-success mb-0">
                                                            <?php echo number_format($row["HD_TONGTIEN"], 0); ?>đ
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <?php 
                                                            $icon = '';
                                                            switch($row["PTTT_MA"]) {
                                                                case 1: $icon = 'money-bill-wave'; break;
                                                                case 2: $icon = 'credit-card'; break;
                                                                case 3: $icon = 'wallet'; break;
                                                                default: $icon = 'money-check-alt';
                                                            }
                                                            ?>
                                                            <i class="fas fa-<?php echo $icon; ?> me-2 text-primary"></i>
                                                            <p class="font-weight-bold mb-0" style="font-size: 0.95rem;"><?php echo $row["PTTT_TEN"]; ?></p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p class="font-weight-bold mb-0" style="font-size: 0.95rem;">
                                                            <i class="far fa-calendar-alt me-1"></i>
                                                            <?php echo date('d/m/Y H:i', strtotime($row["HD_NGAYLAP"])); ?>
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $status_class = '';
                                                        switch($row["TT_MA"]) {
                                                            case 0: $status_class = 'bg-gradient-secondary'; break;
                                                            case 1: $status_class = 'bg-gradient-info'; break;
                                                            case 2: $status_class = 'bg-gradient-primary'; break;
                                                            case 3: $status_class = 'bg-gradient-success'; break;
                                                            case 4: $status_class = 'bg-gradient-danger'; break;
                                                            case 5: $status_class = 'bg-gradient-warning'; break; // Thêm style cho trạng thái chờ hủy
                                                            default: $status_class = 'bg-gradient-secondary';
                                                        }
                                                        ?>
                                                        <span class="badge badge-sm <?php echo $status_class; ?>">
                                                            <?php echo $row["TT_TEN"]; ?>
                                                        </span>
                                                    </td>
                                                    <td class="action-column">
                                                        <div class="action-buttons">
                                                            <a href="detail_pdwait.php?id=<?php echo $row["HD_STT"]; ?>" 
                                                               class="action-btn btn-view">
                                                                Chi tiết
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="8" class="text-center py-4">
                                                    <div class="d-flex flex-column align-items-center">
                                                        <i class="fas fa-box-open fa-3x text-secondary mb-2"></i>
                                                        <h6 class="text-secondary" style="font-size: 1rem;">Không có đơn hàng nào</h6>
                                                    </div>
                                                </td>
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
            </div>
        </div>
    </main>

    <!--   Core JS Files   -->
    <script src="../asset_admin/js/core/popper.min.js"></script>
    <script src="../asset_admin/js/core/bootstrap.min.js"></script>
    <script src="../asset_admin/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../asset_admin/js/plugins/smooth-scrollbar.min.js"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }

        // Enable tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        // Enhanced search function with form submission
        function handleSearch(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                event.target.closest('form').submit();
                return;
            }
            
            // Client-side filtering for immediate feedback
            var input = document.getElementById("searchInput");
            var filter = input.value.toUpperCase().replace(/^#/, ''); // Remove # if present at start
            var table = document.querySelector("table");
            var tr = table.getElementsByTagName("tr");

            for (var i = 1; i < tr.length; i++) {
                var tdArray = tr[i].getElementsByTagName("td");
                var found = false;
                
                // Check order ID (first column)
                if (tdArray[0]) {
                    var orderIdText = tdArray[0].textContent || tdArray[0].innerText;
                    orderIdText = orderIdText.toUpperCase().replace(/^#/, ''); // Remove # for comparison
                    if (orderIdText.indexOf(filter) > -1) {
                        found = true;
                    }
                }
                
                // Check customer name and phone (second column)
                if (!found && tdArray[1]) {
                    var customerText = tdArray[1].textContent || tdArray[1].innerText;
                    if (customerText.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                    }
                }
                
                if (found) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }

        // Function to handle search input changes
        function handleSearchInput(value) {
            // If search input is empty, reset to initial state
            if (!value.trim()) {
                var currentUrl = new URL(window.location.href);
                currentUrl.searchParams.delete('search');
                
                // Preserve other filters if they exist
                if (!currentUrl.searchParams.toString()) {
                    window.location.href = window.location.pathname;
                } else {
                    window.location.href = currentUrl.toString();
                }
                return;
            }
            
            // Perform client-side filtering while typing
            handleSearch({ key: 'other' });
        }

        // Add event listener to search input
        document.getElementById('searchInput').addEventListener('keyup', handleSearch);

        // Filter function for status
        function filterOrders() {
            var status = document.querySelector('select[name="status"]').value;
            var currentUrl = new URL(window.location.href);
            
            if (status) {
                currentUrl.searchParams.set('status', status);
            } else {
                currentUrl.searchParams.delete('status');
            }
            
            // Preserve search parameter
            var searchValue = document.getElementById("searchInput").value;
            if (searchValue) {
                currentUrl.searchParams.set('search', searchValue);
            }
            
            window.location.href = currentUrl.toString();
        }

        function cancelOrder(orderId) {
            Swal.fire({
                title: 'Xác nhận hủy đơn?',
                text: "Bạn có chắc chắn muốn hủy đơn hàng này?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy bỏ'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `update_status_bill.php?id=${orderId}&action=cancel`;
                }
            })
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="../asset_admin/js/material-dashboard.min.js"></script>
</body>
</html>
