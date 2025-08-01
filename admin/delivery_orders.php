<!DOCTYPE html>
<html lang="en">

<?php
  session_start();
?>

<?php include "head.php"; ?>
<?php include "connect.php"; ?>
<body class="g-sidenav-show  bg-gray-200">

<?php
    $active = 'dh'; 
    require 'aside.php';
?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">

    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                                <h6 class="text-white text-capitalize ps-3">Danh sách đơn đang giao</h6>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2">
                            <!-- Thêm ô nhập tìm kiếm gọn gàng -->
                            <div class="row mb-4">
                                <div class="col-12 col-md-6 mx-auto">
                                    <form method="GET" action="">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Tìm kiếm theo tên khách hàng" name="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" aria-label="Tìm kiếm" aria-describedby="search-button">
                                            <button class="btn btn-primary" type="submit" id="search-button"><i class="fas fa-search"></i> Tìm</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- Bảng danh sách đơn hàng -->
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Mã đơn</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Khách hàng</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nhân viên tiếp nhận</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tổng tiền</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Phương thức</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Ngày đặt</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Trạng thái</th>
                                            <th class="text-secondary opacity-7">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $search = isset($_GET['search']) ? $_GET['search'] : '';  // Lấy giá trị từ ô tìm kiếm

                                        $sql = "SELECT hd.HD_STT, kh.KH_TEN, nv.NV_TEN, hd.HD_TONGTIEN, pttt.PTTT_TEN, hd.HD_NGAYLAP, tt.TT_TEN, hd.TT_MA 
                                                FROM hoa_don hd
                                                LEFT JOIN gio_hang gh ON hd.GH_MA = gh.GH_MA
                                                LEFT JOIN khach_hang kh ON gh.KH_MA = kh.KH_MA
                                                LEFT JOIN nhan_vien nv ON hd.NV_MA = nv.NV_MA
                                                LEFT JOIN phuong_thuc_thanh_toan pttt ON hd.PTTT_MA = pttt.PTTT_MA
                                                LEFT JOIN trang_thai tt ON hd.TT_MA = tt.TT_MA
                                                WHERE hd.TT_MA = 3";  // Lọc các đơn hàng đang giao

                                        if ($search) {
                                            $sql .= " AND kh.KH_TEN LIKE '%" . $conn->real_escape_string($search) . "%'";  // Thêm điều kiện tìm kiếm tên khách hàng
                                        }

                                        $result = $conn->query($sql);

                                        if ($result === false) {
                                            echo "<tr><td colspan='8' class='text-center'>Lỗi truy vấn: " . $conn->error . "</td></tr>";
                                        } elseif ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>";
                                                echo "<td><div class='d-flex px-2 py-1'><span class='text-xs font-weight-bold mb-0'>" . $row["HD_STT"] . "</span></div></td>";
                                                echo "<td><span class='text-xs font-weight-bold mb-0'>" . ($row["KH_TEN"] ?? "N/A") . "</span></td>";
                                                echo "<td><span class='text-xs font-weight-bold mb-0'>" . ($row["NV_TEN"] ?? "N/A") . "</span></td>";
                                                echo "<td><span class='text-xs font-weight-bold mb-0'>" . number_format($row["HD_TONGTIEN"] ?? 0, 0, ',', '.') . " VNĐ</span></td>";
                                                echo "<td><span class='text-xs font-weight-bold mb-0'>" . ($row["PTTT_TEN"] ?? "N/A") . "</span></td>";
                                                echo "<td><span class='text-xs font-weight-bold mb-0'>" . date('d/m/Y', strtotime($row["HD_NGAYLAP"])) . "</span></td>";
                                                echo "<td>";
                                                echo "<select class='form-select form-select-sm status-select' data-hd-stt='" . $row["HD_STT"] . "'>";
                                                echo "<option value='2' " . ($row["TT_MA"] == 2 ? "selected" : "") . ">Đang giao</option>";
                                                echo "<option value='4'>Hoàn thành</option>";
                                                echo "<option value='1'>Hủy đơn</option>";
                                                echo "</select>";
                                                echo "</td>";
                                                echo "<td class='align-middle'><a href='billing.php?mahd=" . $row["HD_STT"] . "' class='text-secondary font-weight-bold text-xs' data-toggle='tooltip' data-original-title='Xem chi tiết'>Xem</a></td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='8' class='text-center'>Không có đơn hàng đang giao</td></tr>";
                                        }
                                        $conn->close();
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

    <!-- Thêm jQuery và mã JavaScript để xử lý thay đổi trạng thái -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="../assets/js/soft-ui-dashboard.min.js"></script>
    <script>
    $(document).ready(function() {
        $('.status-select').on('change', function() {
            var hd_stt = $(this).data('hd-stt');  // Lấy ID đơn hàng
            var new_status = $(this).val();        // Lấy trạng thái mới

            $.ajax({
                url: 'update_status.php',  // Gửi yêu cầu đến file update_status.php
                method: 'POST',
                data: {
                    hd_stt: hd_stt,      // ID đơn hàng
                    tt_ma: new_status    // Trạng thái mới
                },
                dataType: 'json', // Đảm bảo nhận được phản hồi dạng JSON
                success: function(response) {
                    if (response.success) {
                        alert(response.message);  // Thông báo thành công
                    } else {
                        alert(response.message);  // Thông báo lỗi
                    }
                },
                error: function() {
                    alert('Có lỗi xảy ra khi cập nhật trạng thái.');
                }
            });
        });
    });
    </script>

</body>
</html>
