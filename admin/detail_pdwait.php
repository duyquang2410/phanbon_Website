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

// Lấy thông tin đơn hàng từ ID
$id = isset($_GET['id']) ? $_GET['id'] : '';
$sql = "SELECT hd.*, kh.KH_TEN as tenkh, kh.KH_DIACHI as diachikh, kh.KH_SDT as sdtkh, 
        kh.KH_EMAIL as emailkh, kh.KH_NGAYSINH as ngaysinh, kh.KH_AVATAR as avtkh,
        tt.TT_TEN as tentrangthai, tt.TT_MA as trangthai,
        pttt.PTTT_TEN as phuongthuc,
        (SELECT SUM(CTHD_DONGIA * CTHD_SOLUONG) FROM chi_tiet_hd WHERE HD_STT = hd.HD_STT) as subtotal,
        CASE 
            WHEN km.hinh_thuc_km = 'percent' THEN (SELECT SUM(CTHD_DONGIA * CTHD_SOLUONG) FROM chi_tiet_hd WHERE HD_STT = hd.HD_STT) * km.KM_GIATRI / 100
            WHEN km.hinh_thuc_km = 'fixed' THEN km.KM_GIATRI
            ELSE 0 
        END as discount_amount,
        hd.HD_PHISHIP as shipping_fee
        FROM hoa_don hd
        LEFT JOIN khach_hang kh ON hd.KH_MA = kh.KH_MA
        LEFT JOIN trang_thai tt ON hd.TT_MA = tt.TT_MA
        LEFT JOIN phuong_thuc_thanh_toan pttt ON hd.PTTT_MA = pttt.PTTT_MA
        LEFT JOIN khuyen_mai km ON hd.KM_MA = km.KM_MA
        WHERE hd.HD_STT = '$id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $order = $result->fetch_assoc();
} else {
    echo "<script>alert('Không tìm thấy đơn hàng!'); window.location.href='product_waits.php';</script>";
    exit;
}

include "head.php";
?>

<body class="g-sidenav-show bg-gray-100">
    <?php $active = 'dh'; require 'aside.php'; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm">
                            <a class="opacity-5 text-dark" href="dashboard.php">
                                <i class="fas fa-home"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="product_waits.php">Đơn hàng</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Chi tiết đơn hàng #<?php echo $id; ?></li>
                    </ol>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                    </div>
                    <ul class="navbar-nav justify-content-end">
                        <li class="nav-item d-flex align-items-center mb-4 me-4">
                            <img src="../asset_admin/img/staff_img/team-4.jpg" class="avatar avatar-sm me-3">
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
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Chi tiết đơn hàng #<?php echo $order['HD_STT']; ?></h5>
                            <div class="action-buttons d-flex align-items-center">
                                <div class="me-3">
                                    <form action="update_status_bill.php" method="POST" class="d-flex align-items-center" id="statusForm">
                                        <input type="hidden" name="order_id" value="<?php echo $order['HD_STT']; ?>">
                                        <label for="orderStatus" class="me-2 mb-0">Trạng thái:</label>
                                        <select class="form-select form-select-sm" name="status" id="orderStatus" style="min-width: 200px;">
                                            <?php
                                            $sql_status = "SELECT * FROM trang_thai";
                                            $result_status = $conn->query($sql_status);
                                            while ($status = $result_status->fetch_assoc()) {
                                                $selected = ($status['TT_MA'] == $order['trangthai']) ? 'selected' : '';
                                                echo "<option value='" . $status['TT_MA'] . "' " . $selected . ">" . $status['TT_TEN'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                        <button type="submit" class="btn btn-primary btn-sm ms-2" id="updateStatusBtn">
                                            <i class="fas fa-save me-1"></i> Cập nhật
                                        </button>
                                    </form>
                                </div>
                                <?php if($order["TT_MA"] == 5): ?>
                                    <button type="button" 
                                            class="btn btn-success"
                                            onclick="if(confirm('Xác nhận hủy đơn hàng này?')) document.getElementById('approve-form').submit();">
                                        <i class="fas fa-check me-2"></i>Xác nhận hủy
                                    </button>
                                    <button type="button" 
                                            class="btn btn-danger ms-2"
                                            onclick="if(confirm('Từ chối yêu cầu hủy đơn hàng này?')) document.getElementById('reject-form').submit();">
                                        <i class="fas fa-times me-2"></i>Từ chối
                                    </button>
                                    <form id="approve-form" action="process_cancel_order.php" method="POST" style="display: none;">
                                        <input type="hidden" name="order_id" value="<?php echo $order["HD_STT"]; ?>">
                                        <input type="hidden" name="action" value="approve">
                                    </form>
                                    <form id="reject-form" action="process_cancel_order.php" method="POST" style="display: none;">
                                        <input type="hidden" name="order_id" value="<?php echo $order["HD_STT"]; ?>">
                                        <input type="hidden" name="action" value="reject">
                                    </form>
                                <?php endif; ?>
                                <a href="product_waits.php" class="btn btn-secondary ms-2">
                                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Thông tin khách hàng -->
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-header pb-0">
                                            <h6 class="mb-0"><i class="fas fa-user me-2"></i>Thông tin khách hàng</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-sm">Tên khách hàng:</span>
                                                <span class="text-sm font-weight-bold"><?php echo $order['tenkh']; ?></span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-sm">Số điện thoại:</span>
                                                <span class="text-sm font-weight-bold"><?php echo $order['sdtkh']; ?></span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-sm">Email:</span>
                                                <span class="text-sm font-weight-bold"><?php echo $order['emailkh']; ?></span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-sm">Địa chỉ:</span>
                                                <span class="text-sm font-weight-bold"><?php echo $order['diachikh']; ?></span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-sm">Ngày sinh:</span>
                                                <span class="text-sm font-weight-bold"><?php echo date('d/m/Y', strtotime($order['ngaysinh'])); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Thông tin thanh toán -->
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-header pb-0">
                                            <h6 class="mb-0"><i class="fas fa-money-bill me-2"></i>Thông tin thanh toán</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-sm">Phương thức:</span>
                                                <span class="text-sm font-weight-bold"><?php echo $order['phuongthuc']; ?></span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-sm">Tổng tiền hàng:</span>
                                                <span class="text-sm font-weight-bold"><?php echo number_format($order['subtotal'], 0, ',', '.'); ?>đ</span>
                                            </div>
                                            <?php if ($order['discount_amount'] > 0): ?>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-sm">Giảm giá:</span>
                                                <span class="text-sm font-weight-bold text-danger">-<?php echo number_format($order['discount_amount'], 0, ',', '.'); ?>đ</span>
                                            </div>
                                            <?php endif; ?>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-sm">Phí vận chuyển:</span>
                                                <span class="text-sm font-weight-bold"><?php echo number_format($order['shipping_fee'], 0, ',', '.'); ?>đ</span>
                                            </div>
                                            <div class="d-flex justify-content-between pt-2 border-top">
                                                <span class="text-sm font-weight-bold">Tổng thanh toán:</span>
                                                <span class="text-lg font-weight-bold text-primary"><?php echo number_format($order['subtotal'] + $order['shipping_fee'] - $order['discount_amount'], 0, ',', '.'); ?>đ</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Chi tiết sản phẩm -->
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header pb-0">
                                            <h6 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Chi tiết sản phẩm</h6>
                                        </div>
                                        <div class="card-body px-0 pt-0 pb-2">
                                            <div class="table-responsive p-0">
                                                <table class="table align-items-center mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Sản phẩm</th>
                                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Số lượng</th>
                                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Đơn giá</th>
                                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Thành tiền</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $sql_details = "SELECT ct.*, sp.SP_TEN, sp.SP_HINHANH 
                                                                       FROM chi_tiet_hd ct 
                                                                       JOIN san_pham sp ON ct.SP_MA = sp.SP_MA 
                                                                       WHERE ct.HD_STT = '$id'";
                                                        $result_details = $conn->query($sql_details);
                                                        while ($row = $result_details->fetch_assoc()) {
                                                            $subtotal = $row['CTHD_SOLUONG'] * $row['CTHD_DONGIA'];
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <div class="d-flex px-3 py-1">
                                                                        <div>
                                                                            <img src="../img/<?php echo $row['SP_HINHANH']; ?>" class="avatar avatar-sm me-3">
                                                                        </div>
                                                                        <div class="d-flex flex-column justify-content-center">
                                                                            <h6 class="mb-0 text-sm"><?php echo $row['SP_TEN']; ?></h6>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <span class="text-sm font-weight-bold"><?php echo $row['CTHD_SOLUONG']; ?></span>
                                                                </td>
                                                                <td>
                                                                    <span class="text-sm font-weight-bold"><?php echo number_format($row['CTHD_DONGIA'], 0, ',', '.'); ?>đ</span>
                                                                </td>
                                                                <td>
                                                                    <span class="text-sm font-weight-bold"><?php echo number_format($subtotal, 0, ',', '.'); ?>đ</span>
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
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal xác nhận đơn hàng -->
    <?php if ($order['trangthai'] == 1): ?>
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Cập nhật trạng thái đơn hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="update_status_bill.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="order_id" value="<?php echo $id; ?>">
                        <div class="form-group">
                            <label for="status" class="form-label">Trạng thái đơn hàng</label>
                            <select class="form-select" name="status" id="status" required>
                                <?php
                                $sql_status = "SELECT * FROM trang_thai";
                                $result_status = $conn->query($sql_status);
                                while ($status = $result_status->fetch_assoc()) {
                                    $selected = ($status['TT_MA'] == $order['trangthai']) ? 'selected' : '';
                                    echo "<option value='" . $status['TT_MA'] . "' " . $selected . ">" . $status['TT_TEN'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group mt-3" id="cancelReasonGroup" style="display: none;">
                            <label for="cancel_reason" class="form-label">Lý do hủy đơn</label>
                            <textarea class="form-control" id="cancel_reason" name="cancel_reason" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('status');
        const cancelReasonGroup = document.getElementById('cancelReasonGroup');
        const cancelReasonInput = document.getElementById('cancel_reason');

        statusSelect.addEventListener('change', function() {
            // Show/hide cancel reason field when status is "Đã hủy" (status 4)
            if (this.value === '4') {
                cancelReasonGroup.style.display = 'block';
                cancelReasonInput.required = true;
            } else {
                cancelReasonGroup.style.display = 'none';
                cancelReasonInput.required = false;
            }
        });
    });
    </script>
    <?php endif; ?>

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
    <script src="../asset_admin/js/material-dashboard.min.js?v=3.1.0"></script>
    <!-- Thêm SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('statusForm');
        const statusSelect = document.getElementById('orderStatus');
        const currentStatus = '<?php echo $order["trangthai"]; ?>';

        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Luôn ngăn chặn submit mặc định

            const newStatus = statusSelect.value;

            // Nếu không có thay đổi trạng thái
            if (newStatus === currentStatus) {
                Swal.fire({
                    icon: 'info',
                    title: 'Thông báo',
                    text: 'Trạng thái không thay đổi'
                });
                return;
            }

            // Nếu chọn trạng thái "Đã hủy"
            if (newStatus === '4') {
                Swal.fire({
                    title: 'Xác nhận hủy đơn hàng',
                    text: 'Vui lòng nhập lý do hủy đơn hàng:',
                    input: 'textarea',
                    inputPlaceholder: 'Nhập lý do hủy...',
                    showCancelButton: true,
                    confirmButtonText: 'Xác nhận',
                    cancelButtonText: 'Hủy bỏ',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Vui lòng nhập lý do hủy!';
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const reasonInput = document.createElement('input');
                        reasonInput.type = 'hidden';
                        reasonInput.name = 'cancel_reason';
                        reasonInput.value = result.value;
                        form.appendChild(reasonInput);
                        form.submit();
                    } else {
                        statusSelect.value = currentStatus; // Reset về trạng thái cũ
                    }
                });
            } 
            // Nếu chọn trạng thái "Đang giao" (giả sử mã là 2)
            else if (newStatus === '2') {
                Swal.fire({
                    title: 'Xác nhận chuyển sang trạng thái đang giao?',
                    text: 'Đơn hàng sẽ được chuyển sang trang theo dõi giao hàng',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Xác nhận',
                    cancelButtonText: 'Hủy bỏ'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Thêm input ẩn để đánh dấu đây là cập nhật trạng thái đang giao
                        const deliveryInput = document.createElement('input');
                        deliveryInput.type = 'hidden';
                        deliveryInput.name = 'start_delivery';
                        deliveryInput.value = '1';
                        form.appendChild(deliveryInput);
                        
                        // Submit form và chuyển hướng
                        form.submit();
                    } else {
                        statusSelect.value = currentStatus;
                    }
                });
            }
            else {
                // Xác nhận thay đổi trạng thái khác
                Swal.fire({
                    title: 'Xác nhận thay đổi trạng thái',
                    text: 'Bạn có chắc chắn muốn thay đổi trạng thái đơn hàng?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Xác nhận',
                    cancelButtonText: 'Hủy bỏ'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    } else {
                        statusSelect.value = currentStatus; // Reset về trạng thái cũ
                    }
                });
            }
        });
    });
    </script>

<style>
.action-buttons .btn {
    padding: 8px 16px;
    font-size: 14px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
}

    .action-buttons .form-select {
        padding: 0.375rem 2.25rem 0.375rem 0.75rem;
        font-size: 0.875rem;
        border-radius: 0.5rem;
        border: 1px solid #d2d6da;
        transition: box-shadow 0.15s ease, border-color 0.15s ease;
    }

    .action-buttons .form-select:focus {
        border-color: #e91e63;
        box-shadow: 0 0 0 0.2rem rgba(233, 30, 99, 0.25);
    }

    .action-buttons label {
        font-size: 0.875rem;
        color: #344767;
        font-weight: 600;
    }

.action-buttons .btn i {
    font-size: 14px;
}

.btn-success {
    background: linear-gradient(310deg, #17ad37, #98ec2d);
    border: none;
}

.btn-danger {
    background: linear-gradient(310deg, #ea0606, #ff667c);
    border: none;
}

.btn-secondary {
    background: linear-gradient(310deg, #627594, #a8b8d8);
    border: none;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 5px 10px rgba(0,0,0,0.2);
}

    .btn-primary {
        background: linear-gradient(310deg, #e91e63, #f5084f);
        border: none;
    }
</style>

<script>
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
</body>
</html>