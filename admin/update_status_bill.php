<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra đăng nhập
if (!isset($_SESSION['NV_MA'])) {
    $_SESSION['error'] = "Vui lòng đăng nhập để thực hiện thao tác này";
    header('Location: sign_in.php');
    exit;
}

require_once 'connect.php';

// Xử lý POST request từ form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['status'])) {
    $id = $_POST['order_id'];
    $new_status = $_POST['status'];
    $cancel_reason = isset($_POST['cancel_reason']) ? $_POST['cancel_reason'] : null;
    $start_delivery = isset($_POST['start_delivery']) ? $_POST['start_delivery'] : null;
    
    try {
        mysqli_begin_transaction($conn);
        
        // Lấy thông tin đơn hàng
        $check_sql = "SELECT TT_MA FROM hoa_don WHERE HD_STT = ?";
        $stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $order = mysqli_fetch_assoc($result);
        
        if (!$order) {
            throw new Exception("Không tìm thấy đơn hàng");
        }
        
        // Cập nhật trạng thái
        if ($new_status == 4 && $cancel_reason) {
            // Nếu là hủy đơn và có lý do
            $update_sql = "UPDATE hoa_don SET TT_MA = ?, NV_MA = ?, HD_GHICHU = ? WHERE HD_STT = ?";
            $stmt = mysqli_prepare($conn, $update_sql);
            mysqli_stmt_bind_param($stmt, "iisi", $new_status, $_SESSION['NV_MA'], $cancel_reason, $id);
        } else {
            // Cập nhật bình thường
            $update_sql = "UPDATE hoa_don SET TT_MA = ?, NV_MA = ? WHERE HD_STT = ?";
            $stmt = mysqli_prepare($conn, $update_sql);
            mysqli_stmt_bind_param($stmt, "iii", $new_status, $_SESSION['NV_MA'], $id);
        }
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Không thể cập nhật trạng thái đơn hàng");
        }
        
        // Nếu hủy đơn, cập nhật số lượng tồn kho
        if ($new_status == 4) {
            $restock_sql = "UPDATE san_pham sp 
                           INNER JOIN chi_tiet_hd cthd ON sp.SP_MA = cthd.SP_MA 
                           SET sp.SP_SOLUONGTON = sp.SP_SOLUONGTON + cthd.CTHD_SOLUONG 
                           WHERE cthd.HD_STT = ?";
            $stmt = mysqli_prepare($conn, $restock_sql);
            mysqli_stmt_bind_param($stmt, "i", $id);
            
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Không thể cập nhật số lượng tồn kho");
            }
            
            // Ghi nhận vào stock_movements
            $items_sql = "SELECT cthd.*, sp.SP_SOLUONGTON, sp.SP_AVAILABLE 
                         FROM chi_tiet_hd cthd 
                         JOIN san_pham sp ON cthd.SP_MA = sp.SP_MA 
                         WHERE cthd.HD_STT = ?";
            $stmt = mysqli_prepare($conn, $items_sql);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $items_result = mysqli_stmt_get_result($stmt);
            
            while ($item = mysqli_fetch_assoc($items_result)) {
                $movement_sql = "INSERT INTO stock_movements (
                    SP_MA, 
                    SM_SOLUONG, 
                    SM_LOAI,
                    SM_THAMCHIEU,
                    SM_SOLUONG_CU,
                    SM_SOLUONG_MOI,
                    SM_AVAILABLE_CU,
                    SM_AVAILABLE_MOI,
                    SM_GHICHU,
                    NV_MA
                ) VALUES (?, ?, 'HUY', ?, ?, ?, ?, ?, ?, ?)";
                
                $new_quantity = $item['SP_SOLUONGTON'] + $item['CTHD_SOLUONG'];
                $new_available = $item['SP_AVAILABLE'] + $item['CTHD_SOLUONG'];
                $note = "Hoàn lại số lượng từ đơn hàng hủy #" . $id;
                if ($cancel_reason) {
                    $note .= " - Lý do: " . $cancel_reason;
                }
                
                $stmt = mysqli_prepare($conn, $movement_sql);
                mysqli_stmt_bind_param(
                    $stmt, 
                    "ididdddsi",
                    $item['SP_MA'],
                    $item['CTHD_SOLUONG'],
                    $id,
                    $item['SP_SOLUONGTON'],
                    $new_quantity,
                    $item['SP_AVAILABLE'],
                    $new_available,
                    $note,
                    $_SESSION['NV_MA']
                );
                
                if (!mysqli_stmt_execute($stmt)) {
                    throw new Exception("Không thể ghi nhận movement");
                }
            }
        }
        
        // Nếu chuyển sang trạng thái đang giao và có yêu cầu bắt đầu giao hàng
        if ($new_status == 2 && $start_delivery) {
            // Kiểm tra xem bảng delivery_status đã tồn tại chưa
            $check_table_sql = "SHOW TABLES LIKE 'delivery_status'";
            $table_exists = $conn->query($check_table_sql)->num_rows > 0;
            
            if (!$table_exists) {
                // Tạo bảng nếu chưa tồn tại
                $create_table_sql = "CREATE TABLE delivery_status (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    HD_STT INT NOT NULL,
                    status ENUM('NEW', 'DELIVERING', 'DELIVERED', 'FAILED') NOT NULL DEFAULT 'NEW',
                    tracking_info TEXT,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    FOREIGN KEY (HD_STT) REFERENCES hoa_don(HD_STT)
                )";
                if (!$conn->query($create_table_sql)) {
                    throw new Exception("Không thể tạo bảng delivery_status");
                }
            }

            // Thêm bản ghi theo dõi giao hàng mới
            $insert_delivery_sql = "INSERT INTO delivery_status (HD_STT, status, tracking_info) 
                                  VALUES (?, 'NEW', 'Đơn hàng đã được xác nhận và bắt đầu giao')";
            $stmt = mysqli_prepare($conn, $insert_delivery_sql);
            mysqli_stmt_bind_param($stmt, "i", $id);
            
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Không thể tạo bản ghi theo dõi giao hàng");
            }
        }
        
        mysqli_commit($conn);
        $_SESSION['success'] = "Đã cập nhật trạng thái đơn hàng thành công";
        
        // Chuyển hướng đến trang theo dõi giao hàng nếu cần
        if ($new_status == 2 && $start_delivery) {
            header('Location: delivery_tracking.php');
            exit;
        }
        
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $_SESSION['error'] = $e->getMessage();
    }
}

// Quay lại trang trước nếu không chuyển hướng đến trang theo dõi giao hàng
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
?>