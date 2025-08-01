<?php
require 'connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $status = $_POST['order_status'];
    $cancel_reason = isset($_POST['cancel_reason']) ? $_POST['cancel_reason'] : null;

    try {
        // Cập nhật trạng thái đơn hàng
        $sql = "UPDATE hoa_don SET TT_MA = ?, HD_LIDOHUY = ? WHERE HD_STT = ?";
        $stmt = $conn->prepare($sql);
        
        if ($status === 'TT4') { // Nếu là trạng thái hủy
            if (empty($cancel_reason)) {
                echo json_encode(['success' => false, 'message' => 'Vui lòng nhập lý do hủy đơn hàng']);
                exit;
            }
            $stmt->bind_param('ssi', $status, $cancel_reason, $order_id);
        } else {
            $cancel_reason = null;
            $stmt->bind_param('ssi', $status, $cancel_reason, $order_id);
        }

        if ($stmt->execute()) {
            // Nếu là trạng thái hủy, cập nhật lại số lượng sản phẩm
            if ($status === 'TT4') {
                $sql_products = "SELECT SP_MA, CTHD_SOLUONG FROM chi_tiet_hd WHERE HD_STT = ?";
                $stmt_products = $conn->prepare($sql_products);
                $stmt_products->bind_param('i', $order_id);
                $stmt_products->execute();
                $result_products = $stmt_products->get_result();

                while ($row = $result_products->fetch_assoc()) {
                    $sql_update_stock = "UPDATE san_pham SET SP_SOLUONG = SP_SOLUONG + ? WHERE SP_MA = ?";
                    $stmt_update_stock = $conn->prepare($sql_update_stock);
                    $stmt_update_stock->bind_param('is', $row['CTHD_SOLUONG'], $row['SP_MA']);
                    $stmt_update_stock->execute();
                }
            }

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể cập nhật trạng thái đơn hàng']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
}
?> 