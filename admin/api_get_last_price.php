<?php
session_start();
require_once 'connect.php';

header('Content-Type: application/json');

// Kiểm tra đăng nhập
if (!isset($_SESSION['NV_MA'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

// Lấy ID sản phẩm từ request
$productId = $_GET['product_id'] ?? null;
if (!$productId) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Missing product ID']);
    exit;
}

try {
    // Lấy giá nhập gần nhất
    $sql = "SELECT 
                ctpn.CTPN_DONGIA as last_price,
                ctpn.CTPN_DONVITINH as unit,
                nh.NH_TEN as supplier_name,
                pn.PN_NGAYNHAP as import_date
            FROM chitiet_pn ctpn
            JOIN phieu_nhap pn ON ctpn.PN_STT = pn.PN_STT
            JOIN nguon_hang nh ON ctpn.NH_MA = nh.NH_MA
            WHERE ctpn.SP_MA = ?
            ORDER BY pn.PN_NGAYNHAP DESC
            LIMIT 1";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Database error: " . $conn->error);
    }

    $stmt->bind_param("i", $productId);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode([
            'status' => 'success',
            'data' => $data
        ]);
    } else {
        echo json_encode([
            'status' => 'success',
            'data' => null
        ]);
    }

} catch (Exception $e) {
    error_log("API Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Internal Server Error: ' . $e->getMessage()
    ]);
}

if (isset($stmt)) {
    $stmt->close();
}
$conn->close(); 