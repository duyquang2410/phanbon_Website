<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['NV_MA'])) {
    header('Location: sign_in.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['action'])) {
    $order_id = $_POST['order_id'];
    $action = $_POST['action']; // 'approve' hoặc 'reject'
    
    try {
        mysqli_begin_transaction($conn);
        
        // Lấy thông tin đơn hàng
        $sql = "SELECT * FROM hoa_don WHERE HD_STT = ? AND TT_MA = 5"; // Chỉ xử lý đơn đang chờ hủy
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $order_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $order = mysqli_fetch_assoc($result);
        
        if (!$order) {
            throw new Exception("Không tìm thấy đơn hàng hoặc đơn hàng không ở trạng thái chờ hủy");
        }
        
        if ($action === 'approve') {
            // Cập nhật trạng thái đơn hàng thành "Đã hủy"
            $update_sql = "UPDATE hoa_don SET TT_MA = 4, NV_MA = ? WHERE HD_STT = ?";
            $stmt = mysqli_prepare($conn, $update_sql);
            mysqli_stmt_bind_param($stmt, "ii", $_SESSION['NV_MA'], $order_id);
            
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Không thể cập nhật trạng thái đơn hàng: " . mysqli_error($conn));
            }
            
            // Nếu có yêu cầu hoàn tiền, cập nhật trạng thái
            if ($order['PTTT_MA'] == 2 || $order['PTTT_MA'] == 3) { // Momo hoặc VNPay
                $refund_sql = "UPDATE hoan_tien SET HT_TRANGTHAI = 'PROCESSING' WHERE HD_STT = ?";
                $stmt = mysqli_prepare($conn, $refund_sql);
                mysqli_stmt_bind_param($stmt, "i", $order_id);
                mysqli_stmt_execute($stmt);
            }
            
            // Lấy chi tiết đơn hàng để hoàn lại số lượng
            $detail_sql = "SELECT cthd.*, sp.SP_SOLUONGTON, sp.SP_AVAILABLE 
                          FROM chi_tiet_hd cthd 
                          JOIN san_pham sp ON cthd.SP_MA = sp.SP_MA 
                          WHERE cthd.HD_STT = ?";
            $stmt = mysqli_prepare($conn, $detail_sql);
            mysqli_stmt_bind_param($stmt, "i", $order_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            while ($item = mysqli_fetch_assoc($result)) {
                // Cập nhật số lượng tồn kho
                $update_stock_sql = "UPDATE san_pham 
                                   SET SP_SOLUONGTON = SP_SOLUONGTON + ? 
                                   WHERE SP_MA = ?";
                $stmt = mysqli_prepare($conn, $update_stock_sql);
                mysqli_stmt_bind_param($stmt, "di", $item['CTHD_SOLUONG'], $item['SP_MA']);
                
                if (!mysqli_stmt_execute($stmt)) {
                    throw new Exception("Không thể cập nhật số lượng tồn kho: " . mysqli_error($conn));
                }
                
                // Ghi nhận vào stock_movements
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
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
                $new_quantity = $item['SP_SOLUONGTON'] + $item['CTHD_SOLUONG'];
                $new_available = $item['SP_AVAILABLE'] + $item['CTHD_SOLUONG'];
                $movement_type = 'HOAN';
                $note = "Hoàn lại số lượng từ đơn hàng hủy #" . $order_id;
                
                $stmt = mysqli_prepare($conn, $movement_sql);
                mysqli_stmt_bind_param(
                    $stmt, 
                    "idsiddddsi",
                    $item['SP_MA'],
                    $item['CTHD_SOLUONG'],
                    $movement_type,
                    $order_id,
                    $item['SP_SOLUONGTON'],
                    $new_quantity,
                    $item['SP_AVAILABLE'],
                    $new_available,
                    $note,
                    $_SESSION['NV_MA']
                );
                
                if (!mysqli_stmt_execute($stmt)) {
                    throw new Exception("Không thể ghi nhận movement: " . mysqli_error($conn));
                }
            }
            
            $_SESSION['success'] = "Đã xác nhận hủy đơn hàng #" . $order_id;
        } else {
            // Từ chối hủy đơn, đưa về trạng thái trước đó
            $update_sql = "UPDATE hoa_don SET TT_MA = ?, NV_MA = ? WHERE HD_STT = ?";
            $previous_status = $order['TT_MA'] <= 1 ? $order['TT_MA'] : 1; // Giữ nguyên trạng thái cũ nếu <= 1, ngược lại về 1
            $stmt = mysqli_prepare($conn, $update_sql);
            mysqli_stmt_bind_param($stmt, "iii", $previous_status, $_SESSION['NV_MA'], $order_id);
            
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Không thể cập nhật trạng thái đơn hàng: " . mysqli_error($conn));
            }
            
            $_SESSION['success'] = "Đã từ chối yêu cầu hủy đơn hàng #" . $order_id;
        }
        
        mysqli_commit($conn);
        
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $_SESSION['error'] = $e->getMessage();
        error_log("Error in process_cancel_order.php: " . $e->getMessage());
    }
}

header('Location: product_waits.php');
exit;
?> 