<?php
session_start();
include 'connect.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['NV_MA'])) {
    header('Location: sign_in.php');
    exit;
}

// Tạo đơn hàng test nếu được yêu cầu
if (isset($_POST['create_test_order'])) {
    try {
        $conn->begin_transaction();

        // Thêm vào delivery_status
        $sql = "INSERT INTO delivery_status (HD_STT, status, tracking_info) 
                SELECT HD_STT, 'NEW', 'Đơn hàng mới được tạo' 
                FROM hoa_don 
                WHERE HD_STT = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_POST['order_id']);
        $stmt->execute();

        $conn->commit();
        $success_message = "Đã thêm đơn hàng vào hệ thống theo dõi!";
    } catch (Exception $e) {
        $conn->rollback();
        $error_message = "Lỗi: " . $e->getMessage();
    }
}

// Lấy danh sách đơn hàng chưa có trong delivery_status
$sql = "SELECT hd.HD_STT, hd.HD_NGAYLAP, kh.KH_TEN, hd.HD_TONGTIEN
        FROM hoa_don hd
        JOIN khach_hang kh ON hd.KH_MA = kh.KH_MA
        LEFT JOIN delivery_status ds ON hd.HD_STT = ds.HD_STT
        WHERE ds.id IS NULL
        ORDER BY hd.HD_NGAYLAP DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Delivery System</title>
    <?php include 'head.php'; ?>
</head>
<body class="g-sidenav-show bg-gray-200">
    <?php include 'aside.php'; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                                <h6 class="text-white text-capitalize ps-3">Test Hệ Thống Giao Hàng</h6>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2">
                            <?php if (isset($success_message)): ?>
                                <div class="alert alert-success mx-3">
                                    <?php echo $success_message; ?>
                                </div>
                            <?php endif; ?>

                            <?php if (isset($error_message)): ?>
                                <div class="alert alert-danger mx-3">
                                    <?php echo $error_message; ?>
                                </div>
                            <?php endif; ?>

                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Mã đơn</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Khách hàng</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ngày đặt</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tổng tiền</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm"><?php echo $row['HD_STT']; ?></h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($row['KH_TEN']); ?></p>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span class="text-secondary text-xs font-weight-bold">
                                                        <?php echo date('d/m/Y H:i', strtotime($row['HD_NGAYLAP'])); ?>
                                                    </span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span class="text-secondary text-xs font-weight-bold">
                                                        <?php echo number_format($row['HD_TONGTIEN'], 0, ',', '.'); ?> đ
                                                    </span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <form method="post" style="display: inline;">
                                                        <input type="hidden" name="order_id" value="<?php echo $row['HD_STT']; ?>">
                                                        <button type="submit" name="create_test_order" class="btn btn-info btn-sm">
                                                            Thêm vào theo dõi
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12 text-center">
                                    <a href="delivery_tracking.php" class="btn btn-primary">
                                        Xem trang theo dõi giao hàng
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include 'billing_scripts.php'; ?>
</body>
</html> 