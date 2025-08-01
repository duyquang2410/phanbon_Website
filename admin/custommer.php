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
require 'connect.php';

// Xử lý tìm kiếm
$search = '';
$searchBy = 'name'; // Mặc định tìm theo tên
if(isset($_GET["timkiem"])) {
    $search = $_GET["timkiem"];
}
if(isset($_GET["searchBy"])) {
    $searchBy = $_GET["searchBy"];
}

// Câu truy vấn tìm kiếm
$sql = "SELECT * FROM khach_hang WHERE 1=1";
if($search != '') {
    switch($searchBy) {
        case 'name':
            $sql .= " AND KH_TEN LIKE '%$search%'";
            break;
        case 'phone':
            $sql .= " AND KH_SDT LIKE '%$search%'";
            break;
        case 'email':
            $sql .= " AND KH_EMAIL LIKE '%$search%'";
            break;
        case 'address':
            $sql .= " AND KH_DIACHI LIKE '%$search%'";
            break;
    }
}
$sql .= " ORDER BY KH_NGAYDK DESC";

include "head.php";
?>

<body class="g-sidenav-show bg-gray-200">
    <?php $active = 'kh'; require 'aside.php'; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Khách hàng</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">Khách hàng</h6>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                        <form action="" method="get" class="d-flex align-items-center">
                            <div class="input-group input-group-outline">
                                <input type="text" name="timkiem" class="form-control" placeholder="Tìm kiếm..." value="<?php echo $search; ?>">
                            </div>
                            <button type="submit" class="btn btn-outline-primary btn-sm mb-0 ms-3">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
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
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="row px-2 bg-gray">
                            <form action="" method="get" class="d-flex align-items-center justify-content-between p-3">
                                <div class="col-4 font-weight-bold text-uppercase">
                                    Danh sách khách hàng
                                </div>
                                <div class="col-8 d-flex align-items-center justify-content-end">
                                    <div class="me-3">
                                        <select name="searchBy" class="form-select" onchange="this.form.submit()">
                                            <option value="name" <?php echo $searchBy == 'name' ? 'selected' : ''; ?>>Tìm theo tên</option>
                                            <option value="phone" <?php echo $searchBy == 'phone' ? 'selected' : ''; ?>>Tìm theo SĐT</option>
                                            <option value="email" <?php echo $searchBy == 'email' ? 'selected' : ''; ?>>Tìm theo email</option>
                                            <option value="address" <?php echo $searchBy == 'address' ? 'selected' : ''; ?>>Tìm theo địa chỉ</option>
                                        </select>
                                    </div>
                                    <div class="input-group input-group-outline w-50">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" name="timkiem" class="form-control" placeholder="Nhập từ khóa tìm kiếm..." value="<?php echo $search; ?>">
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm mb-0 ms-3">
                                        Tìm kiếm
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-body px-4 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-1">Mã</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Họ và tên</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Giới tính</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Số điện thoại</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Ngày đăng ký</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Địa chỉ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $result = $conn->query($sql);
                                        if ($result->num_rows > 0) {
                                            while($row = $result->fetch_assoc()) {
                                                ?>
                                                <tr>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0"><?php echo $row["KH_MA"]; ?></p>
                                                    </td>
                                                    <td class="w-30">
                                                        <div class="d-flex px-2 py-1">
                                                            <div class="d-flex flex-column justify-content-center">
                                                                <h6 class="mb-0 text-sm"><?php echo $row["KH_TEN"]; ?></h6>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    Ngày sinh: <?php echo date('d/m/Y', strtotime($row["KH_NGAYSINH"])); ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-sm <?php echo $row["KH_GIOITINH"] == 'm' ? 'bg-gradient-primary' : 'bg-gradient-success'; ?>">
                                                            <?php echo $row["KH_GIOITINH"] == 'm' ? 'Nam' : 'Nữ'; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <p class="text-sm font-weight-bold mb-0"><?php echo $row["KH_SDT"]; ?></p>
                                                    </td>
                                                    <td>
                                                        <p class="text-sm font-weight-bold mb-0">
                                                            <?php echo date('d/m/Y', strtotime($row["KH_NGAYDK"])); ?>
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p class="text-sm font-weight-bold mb-0"><?php echo $row["KH_EMAIL"]; ?></p>
                                                    </td>
                                                    <td>
                                                        <p class="text-sm font-weight-bold mb-0"><?php echo $row["KH_DIACHI"]; ?></p>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="7" class="text-center">Không tìm thấy khách hàng nào</td>
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
    </script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="../asset_admin/js/material-dashboard.min.js?v=3.1.0"></script>
</body>
</html>
