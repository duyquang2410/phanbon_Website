<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        mysqli_begin_transaction($conn);

        // Lấy thông tin từ form
        $customer_id = $_POST['customer_id'];
        $products = $_POST['products']; // Mảng các sản phẩm [id => quantity]
        $payment_method = $_POST['payment_method'];
        $shipping_address = $_POST['shipping_address'];
        $note = $_POST['note'] ?? '';

        // Tạo hóa đơn mới
        $sql = "INSERT INTO hoa_don (
            KH_MA, 
            TT_MA,
            NV_MA,
            PTTT_MA,
            HD_NGAYLAP,
            HD_TRANGTHAI,
            HD_DIACHI,
            HD_GHICHU
        ) VALUES (?, 1, ?, ?, NOW(), 'PENDING', ?, ?)";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param(
            $stmt, 
            "iiiss",
            $customer_id,
            $_SESSION['NV_MA'],
            $payment_method,
            $shipping_address,
            $note
        );
        mysqli_stmt_execute($stmt);
        // Sau khi tạo đơn hàng thành công
        $order_id = $conn->insert_id;

        // Thêm vào bảng delivery_status
        $sql = "INSERT INTO delivery_status (HD_STT, status, tracking_info) 
                VALUES (?, 'NEW', 'Đơn hàng mới được tạo')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();

        $total_amount = 0;

        // Xử lý từng sản phẩm
        foreach ($products as $product_id => $quantity) {
            // Kiểm tra và lock sản phẩm
            $sql = "SELECT SP_MA, SP_TEN, SP_DONGIA, SP_AVAILABLE, SP_SOLUONGTON 
                    FROM san_pham 
                    WHERE SP_MA = ? 
                    FOR UPDATE";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $product_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $product = mysqli_fetch_assoc($result);

            // Kiểm tra số lượng khả dụng
            if ($product['SP_AVAILABLE'] < $quantity) {
                throw new Exception("Sản phẩm {$product['SP_TEN']} chỉ còn {$product['SP_AVAILABLE']} sản phẩm");
            }

            // Thêm chi tiết hóa đơn
            $sql = "INSERT INTO chi_tiet_hd (
                SP_MA,
                HD_STT,
                CTHD_SOLUONG,
                CTHD_DONGIA,
                CTHD_GIAGOC
            ) VALUES (?, ?, ?, ?, ?)";
            
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param(
                $stmt, 
                "iidd",
                $product_id,
                $order_id,
                $quantity,
                $product['SP_DONGIA'],
                $product['SP_DONGIA']
            );
            mysqli_stmt_execute($stmt);

            // Cập nhật số lượng khả dụng
            $new_available = $product['SP_AVAILABLE'] - $quantity;
            $sql = "UPDATE san_pham 
                    SET SP_AVAILABLE = ?,
                        SP_CAPNHAT = CURRENT_TIMESTAMP 
                    WHERE SP_MA = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "di", $new_available, $product_id);
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
            ) VALUES (?, 'DAT_HANG', ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param(
                $stmt, 
                "iddddddssi",
                $product_id,
                $quantity,
                $product['SP_SOLUONGTON'],
                $product['SP_SOLUONGTON'], // Số tồn không đổi
                $product['SP_AVAILABLE'],
                $new_available,
                $product['SP_DONGIA'],
                "Đặt hàng #" . $order_id,
                $order_id,
                $_SESSION['NV_MA']
            );
            mysqli_stmt_execute($stmt);

            // Cộng vào tổng tiền
            $total_amount += $quantity * $product['SP_DONGIA'];
        }

        // Cập nhật tổng tiền hóa đơn
        $sql = "UPDATE hoa_don 
                SET HD_TONGTIEN = ? 
                WHERE HD_STT = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "di", $total_amount, $order_id);
        mysqli_stmt_execute($stmt);

        mysqli_commit($conn);

        // Trả về kết quả thành công
        echo json_encode([
            'success' => true,
            'message' => 'Đặt hàng thành công',
            'order_id' => $order_id
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