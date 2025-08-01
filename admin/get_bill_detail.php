<?php
require 'connect.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    
    // Lấy thông tin chi tiết hóa đơn
    $sql = "SELECT 
                cthd.HD_STT,
                sp.SP_TEN,
                cthd.CTHD_SOLUONG,
                cthd.CTHD_DONGIA,
                (cthd.CTHD_SOLUONG * cthd.CTHD_DONGIA) as thanh_tien,
                hd.HD_TONGTIEN,
                hd.HD_NGAYLAP,
                kh.KH_TEN,
                kh.KH_SDT,
                kh.KH_DIACHI
            FROM chi_tiet_hd cthd
            JOIN san_pham sp ON cthd.SP_MA = sp.SP_MA
            JOIN hoa_don hd ON cthd.HD_STT = hd.HD_STT
            JOIN khach_hang kh ON hd.KH_MA = kh.KH_MA
            WHERE cthd.HD_STT = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $first_row = $result->fetch_assoc();
        ?>
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h6 class="mb-1">Mã hóa đơn: #<?php echo $first_row['HD_STT']; ?></h6>
                        <p class="mb-0 text-sm">Ngày lập: <?php echo date('d/m/Y', strtotime($first_row['HD_NGAYLAP'])); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between mb-3">
                            <h6 class="mb-0">Thông tin khách hàng</h6>
                        </div>
                        <p class="mb-0 text-sm">Tên: <?php echo $first_row['KH_TEN']; ?></p>
                        <p class="mb-0 text-sm">SĐT: <?php echo $first_row['KH_SDT']; ?></p>
                        <p class="mb-0 text-sm">Địa chỉ: <?php echo $first_row['KH_DIACHI']; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="table-responsive">
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
                                    // Reset con trỏ kết quả về đầu
                                    $result->data_seek(0);
                                    while ($row = $result->fetch_assoc()) {
                                        ?>
                                        <tr>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?php echo $row['SP_TEN']; ?></p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?php echo $row['CTHD_SOLUONG']; ?></p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?php echo number_format($row['CTHD_DONGIA'], 0, ',', '.'); ?>đ</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?php echo number_format($row['thanh_tien'], 0, ',', '.'); ?>đ</p>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="3" class="text-end">
                                            <p class="text-xs font-weight-bold mb-0">Tổng tiền:</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0"><?php echo number_format($first_row['HD_TONGTIEN'], 0, ',', '.'); ?>đ</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo '<div class="alert alert-warning">Không tìm thấy thông tin hóa đơn</div>';
    }
    
    $stmt->close();
}
$conn->close();
?> 