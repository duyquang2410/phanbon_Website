<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        mysqli_begin_transaction($conn);

        $order_id = $_POST['order_id'];

        // Kiểm tra trạng thái đơn hàng
        $sql = "SELECT HD_TRANGTHAI FROM hoa_don WHERE HD_STT = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $order_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $order = mysqli_fetch_assoc($result);

        if ($order['HD_TRANGTHAI'] !== 'SHIPPING') {
            throw new Exception('Đơn hàng không ở trạng thái đang giao');
        }

        // Lấy chi tiết đơn hàng
        $sql = "SELECT 
                CTHD.SP_MA,
                CTHD.CTHD_SOLUONG,
                CTHD.CTHD_DONGIA,
                SP.SP_SOLUONGTON,
                SP.SP_AVAILABLE
                FROM chi_tiet_hd CTHD
                JOIN san_pham SP ON CTHD.SP_MA = SP.SP_MA
                WHERE CTHD.HD_STT = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $order_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($item = mysqli_fetch_assoc($result)) {
            // Lock sản phẩm
            $sql = "SELECT SP_SOLUONGTON, SP_AVAILABLE 
                    FROM san_pham 
                    WHERE SP_MA = ? 
                    FOR UPDATE";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $item['SP_MA']);
            mysqli_stmt_execute($stmt);
            $product = mysqli_fetch_assoc($stmt->get_result());

            // Giảm số lượng tồn kho thực tế
            $new_quantity = $product['SP_SOLUONGTON'] - $item['CTHD_SOLUONG'];
            $sql = "UPDATE san_pham 
                    SET SP_SOLUONGTON = ?,
                        SP_CAPNHAT = CURRENT_TIMESTAMP 
                    WHERE SP_MA = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "di", $new_quantity, $item['SP_MA']);
            mysqli_stmt_execute($stmt);

            // Ghi log vào stock_movements
            $sql = "INSERT INTO stock_movements (
                SP_MA,
                SM_LOAI,
                SM_SOLUONG,
                SM_SOLUONG_CU,
                SM_SOLUONG_MOI,
                SM_AVAILABLE_CU,
                SM_AVAILABLE_MOI,
                SM_DONGIA,
                SM_GHICHU,
                SM_THAMCHIEU,
                NV_MA
            ) VALUES (?, 'GIAO', ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param(
                $stmt, 
                "iddddddssi",
                $item['SP_MA'],
                $item['CTHD_SOLUONG'],
                $product['SP_SOLUONGTON'],
                $new_quantity,
                $product['SP_AVAILABLE'],
                $product['SP_AVAILABLE'], // Available không đổi vì đã giảm lúc đặt hàng
                $item['CTHD_DONGIA'],
                "Giao hàng thành công #" . $order_id,
                $order_id,
                $_SESSION['NV_MA']
            );
            mysqli_stmt_execute($stmt);
        }

        // Cập nhật trạng thái đơn hàng
        $sql = "UPDATE hoa_don 
                SET HD_TRANGTHAI = 'DELIVERED',
                    HD_CAPNHAT = CURRENT_TIMESTAMP 
                WHERE HD_STT = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $order_id);
        mysqli_stmt_execute($stmt);

        mysqli_commit($conn);

        // Trả về kết quả thành công
        echo json_encode([
            'success' => true,
            'message' => 'Giao hàng thành công'
        ]);

    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
} 