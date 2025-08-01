<?php
include 'connect.php';

// Kiểm tra đăng nhập và quyền
session_start();
if (!isset($_SESSION['NV_MA'])) {
    die(json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']));
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
        die(json_encode(['success' => false, 'message' => 'Bạn không có quyền thực hiện thao tác này']));
    }
} else {
    die(json_encode(['success' => false, 'message' => 'Không tìm thấy thông tin nhân viên']));
}

// Xử lý form xuất kho
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        mysqli_begin_transaction($conn);

        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        $reason_type = $_POST['reason_type'];
        $note = $_POST['note'] ?? '';

        // Validate dữ liệu
        if (empty($product_id) || empty($quantity) || empty($reason_type)) {
            throw new Exception('Vui lòng điền đầy đủ thông tin bắt buộc');
        }

        if ($quantity <= 0) {
            throw new Exception('Số lượng phải lớn hơn 0');
        }

        // Lock bảng sản phẩm để tránh race condition
        $sql = "SELECT SP_MA, SP_TEN, SP_DONVITINH, SP_SOLUONGTON, SP_AVAILABLE 
                FROM san_pham 
                WHERE SP_MA = ? 
                FOR UPDATE";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $product_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (!$row = mysqli_fetch_assoc($result)) {
            throw new Exception('Không tìm thấy sản phẩm');
        }

        $old_quantity = $row['SP_SOLUONGTON'];
        $old_available = $row['SP_AVAILABLE'];

        // Kiểm tra số lượng khả dụng
        if ($old_available < $quantity) {
            throw new Exception('Số lượng xuất vượt quá số lượng khả dụng');
        }

        $new_quantity = $old_quantity - $quantity;
        $new_available = $old_available - $quantity;

        // Cập nhật số lượng trong bảng san_pham
        $sql = "UPDATE san_pham 
                SET SP_SOLUONGTON = ?,
                    SP_AVAILABLE = ?,
                    SP_CAPNHAT = CURRENT_TIMESTAMP 
                WHERE SP_MA = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ddi", $new_quantity, $new_available, $product_id);
        mysqli_stmt_execute($stmt);

        // Tạo phiếu xuất kho
        $sql = "INSERT INTO phieu_xuat (PX_NGAYLAP, NV_MA, PX_LYDO) VALUES (NOW(), ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "is", $_SESSION['NV_MA'], $reason_type);
        mysqli_stmt_execute($stmt);
        $phieu_xuat_id = mysqli_insert_id($conn);

        // Thêm chi tiết phiếu xuất
        $sql = "INSERT INTO chitiet_px (PX_ID, SP_MA, CTPX_SOLUONG, CTPX_GHICHU) 
                VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iids", $phieu_xuat_id, $product_id, $quantity, $note);
        mysqli_stmt_execute($stmt);

        // Ghi log vào bảng stock_movements
        $sql = "INSERT INTO stock_movements (
                    SP_MA,
                    SM_SOLUONG,
                    SM_LOAI,
                    SM_THAMCHIEU,
                    SM_SOLUONG_CU,
                    SM_SOLUONG_MOI,
                    SM_AVAILABLE_CU,
                    SM_AVAILABLE_MOI,
                    SM_GHICHU,
                    NV_MA,
                    SM_LYDO
                ) VALUES (?, ?, 'XUAT', ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param(
            $stmt, 
            "ididdddsis", 
            $product_id,
            $quantity,
            $phieu_xuat_id,
            $old_quantity,
            $new_quantity,
            $old_available,
            $new_available,
            $note,
            $_SESSION['NV_MA'],
            $reason_type
        );
        mysqli_stmt_execute($stmt);

        mysqli_commit($conn);

        // Chuyển hướng với thông báo thành công
        $_SESSION['success_message'] = "Xuất kho thành công!";
        header('Location: inventory.php');
        exit;

    } catch (Exception $e) {
        mysqli_rollback($conn);
        $_SESSION['error_message'] = $e->getMessage();
        header('Location: inventory_out.php');
        exit;
    }
} else {
    header('Location: inventory_out.php');
    exit;
} 