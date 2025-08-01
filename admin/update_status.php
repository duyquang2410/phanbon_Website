<?php
session_start();
include "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['hd_stt']) && isset($_POST['tt_ma'])) {
    $hd_stt = $_POST['hd_stt'];
    $tt_ma = $_POST['tt_ma'];
    
    // Xác thực đầu vào
    if (!is_numeric($hd_stt) || !is_numeric($tt_ma)) {
        echo json_encode(['success' => false, 'message' => 'Dữ liệu đầu vào không hợp lệ']);
        exit;
    }
    
    // Cập nhật trạng thái đơn hàng
    $sql = "UPDATE hoa_don SET TT_MA = ? WHERE HD_STT = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $tt_ma, $hd_stt);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái thành công']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật: ' . $stmt->error]);
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ']);
}
?>
