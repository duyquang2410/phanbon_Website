<?php
session_start();
include 'connect.php';
header('Content-Type: application/json');

// Kiểm tra quyền admin
if (!isset($_SESSION['NV_MA'])) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
    exit;
}

// Kiểm tra dữ liệu gửi lên
if (!isset($_POST['productId']) || !isset($_POST['adjustmentType']) || !isset($_POST['quantity']) || !isset($_POST['reason'])) {
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin cần thiết']);
    exit;
}

$productId = intval($_POST['productId']);
$adjustmentType = $_POST['adjustmentType'];
$quantity = floatval($_POST['quantity']);
$reason = $_POST['reason'];
$nv_ma = $_SESSION['NV_MA'];

try {
    // Bắt đầu transaction
    $conn->begin_transaction();

    // Lấy số lượng hiện tại
    $sql = "SELECT SP_SOLUONGTON FROM san_pham WHERE SP_MA = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        throw new Exception("Không tìm thấy sản phẩm");
    }

    $currentStock = $product['SP_SOLUONGTON'];
    $newStock = $currentStock;

    // Tính số lượng mới
    if ($adjustmentType === 'increase') {
        $newStock += $quantity;
    } else {
        if ($currentStock < $quantity) {
            throw new Exception("Số lượng giảm không thể lớn hơn số lượng tồn kho");
        }
        $newStock -= $quantity;
    }

    // Cập nhật số lượng tồn kho
    $sql = "UPDATE san_pham SET SP_SOLUONGTON = ? WHERE SP_MA = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("di", $newStock, $productId);
    $stmt->execute();

    // Ghi log
    $sql = "INSERT INTO lich_su_ton_kho (SP_MA, LSTK_LOAI, LSTK_SOLUONG, LSTK_SOLUONG_CU, LSTK_SOLUONG_MOI, LSTK_GHICHU, NV_MA) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $loai = $adjustmentType === 'increase' ? 'NHAP' : 'XUAT';
    $stmt->bind_param("isddisi", $productId, $loai, $quantity, $currentStock, $newStock, $reason, $nv_ma);
    $stmt->execute();

    // Commit transaction
    $conn->commit();

    echo json_encode([
        'success' => true, 
        'message' => 'Cập nhật tồn kho thành công',
        'newStock' => $newStock
    ]);

} catch (Exception $e) {
    // Rollback nếu có lỗi
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?> 