<?php
header('Content-Type: application/json');
require 'connect.php';

try {
    $conn->begin_transaction();

    // Lấy các đơn hàng đã giao thành công trong delivery_status nhưng chưa cập nhật trong hoa_don
    $sql = "SELECT ds.HD_STT 
            FROM delivery_status ds
            JOIN hoa_don hd ON ds.HD_STT = hd.HD_STT
            WHERE ds.status = 'DELIVERED' 
            AND hd.TT_MA = 2";  // TT_MA = 2 là trạng thái "ĐANG GIAO"
    
    $result = $conn->query($sql);
    $updated_orders = [];

    while ($row = $result->fetch_assoc()) {
        // Cập nhật trạng thái trong bảng hoa_don thành "ĐÃ GIAO" (TT_MA = 3)
        $update_sql = "UPDATE hoa_don 
                      SET TT_MA = 3,
                          HD_NGAYGIAO = NOW()  
                      WHERE HD_STT = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("i", $row['HD_STT']);
        $stmt->execute();

        // Thêm vào log
        $log_sql = "INSERT INTO nhan_vien_activity_log 
                   (NV_MA, ACTION_TYPE, TARGET_TYPE, TARGET_ID, ACTION_DESCRIPTION) 
                   VALUES (?, 'UPDATE', 'ORDER', ?, 'Cập nhật trạng thái đơn hàng thành Đã giao hàng')";
        $stmt = $conn->prepare($log_sql);
        $nv_ma = isset($_SESSION['NV_MA']) ? $_SESSION['NV_MA'] : 1;
        $stmt->bind_param("ii", $nv_ma, $row['HD_STT']);
        $stmt->execute();

        $updated_orders[] = $row['HD_STT'];
    }

    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Đã cập nhật trạng thái đơn hàng thành công',
        'updated_orders' => $updated_orders
    ]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode([
        'success' => false,
        'message' => 'Lỗi: ' . $e->getMessage()
    ]);
}

$conn->close(); 