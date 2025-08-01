<?php
session_start();
require_once 'connect.php';

// Hàm ghi log
function writeLog($message, $type = 'INFO') {
    $logFile = '../logs/inventory.log';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp][$type] $message\n";
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['productId'];
    $adjustmentType = $_POST['adjustmentType'];
    $quantity = floatval($_POST['adjustmentQuantity']); // Đổi sang float vì số lượng có thể là thập phân
    $reason = trim($_POST['adjustmentReason']); // Thêm trim() để loại bỏ khoảng trắng thừa
    
    // Log dữ liệu đầu vào
    writeLog("Received adjustment request - Product: $productId, Type: $adjustmentType, Quantity: $quantity, Reason: $reason");
    
    try {
        // Bắt đầu transaction
        $conn->begin_transaction();
        writeLog("Started transaction");

        // Kiểm tra số lượng tồn hiện tại
        $sql_check = "SELECT SP_MA, SP_TEN, SP_SOLUONGTON FROM san_pham WHERE SP_MA = ?";
        $stmt = $conn->prepare($sql_check);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if (!$row = $result->fetch_assoc()) {
            throw new Exception('Không tìm thấy sản phẩm');
        }

        $currentStock = $row['SP_SOLUONGTON'];
        $productName = $row['SP_TEN'];
        writeLog("Current stock for product $productName (ID: $productId): $currentStock");
        
        // Kiểm tra nếu là giảm số lượng
        if ($adjustmentType === 'decrease' && $quantity > $currentStock) {
            throw new Exception('Số lượng giảm không thể lớn hơn số lượng tồn kho');
        }

        // Tính số lượng mới
        $newStock = $adjustmentType === 'increase' ? 
            $currentStock + $quantity : 
            $currentStock - $quantity;

        writeLog("Calculated new stock: $newStock");

        // Cập nhật số lượng tồn kho
        $sql_update = "UPDATE san_pham SET 
            SP_SOLUONGTON = ?
            WHERE SP_MA = ?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("di", $newStock, $productId);
        if (!$stmt->execute()) {
            throw new Exception("Lỗi cập nhật tồn kho: " . $stmt->error);
        }
        writeLog("Updated product stock successfully");

        // Thêm vào lịch sử tồn kho
        $sql_history = "INSERT INTO lich_su_ton_kho 
            (SP_MA, LSTK_LOAI, LSTK_SOLUONG, LSTK_SOLUONG_CU, LSTK_SOLUONG_MOI, LSTK_GHICHU, NV_MA) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        // Chuyển đổi adjustmentType sang định dạng trong DB
        $loai = $adjustmentType === 'increase' ? 'NHAP' : 'XUAT';
        
        writeLog("Preparing to insert history record - Type: $loai, Old: $currentStock, New: $newStock, Note: $reason");
        
        $stmt = $conn->prepare($sql_history);
        if (!$stmt) {
            throw new Exception("Lỗi prepare statement: " . $conn->error);
        }

        $nv_ma = $_SESSION['NV_MA'];
        writeLog("Staff ID for history record: $nv_ma");

        $stmt->bind_param(
            "isdddsi", 
            $productId,
            $loai,
            $quantity,
            $currentStock,
            $newStock,
            $reason,
            $nv_ma
        );
        
        if (!$stmt->execute()) {
            writeLog("SQL Error: " . $stmt->error, 'ERROR');
            throw new Exception("Lỗi thêm lịch sử: " . $stmt->error);
        }
        writeLog("Inserted history record successfully");

        // Commit transaction
        $conn->commit();
        writeLog("Transaction committed successfully");
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Điều chỉnh tồn kho thành công'
        ]);
    } catch (Exception $e) {
        // Rollback nếu có lỗi
        $conn->rollback();
        writeLog("Error occurred: " . $e->getMessage(), 'ERROR');
        writeLog("Transaction rolled back", 'ERROR');
        echo json_encode([
            'status' => 'error',
            'message' => 'Lỗi: ' . $e->getMessage()
        ]);
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
    }
} else {
    writeLog("Invalid request method: " . $_SERVER['REQUEST_METHOD'], 'ERROR');
    echo json_encode([
        'status' => 'error',
        'message' => 'Phương thức không được hỗ trợ'
    ]);
}
?> 