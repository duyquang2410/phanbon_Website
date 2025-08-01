<?php
// Prevent any output before JSON response
ob_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Include required files
require_once 'connect.php';

// Hàm trả về lỗi
function returnError($message, $code = 500) {
    ob_clean(); // Clear any previous output
    header('Content-Type: application/json; charset=utf-8');
    http_response_code($code);
    die(json_encode([
        'status' => 'error',
        'message' => $message
    ], JSON_UNESCAPED_UNICODE));
}

// Hàm ghi log debug
function debug_log($label, $data) {
    $log_file = __DIR__ . '/debug.log';
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[{$timestamp}] {$label}:\n" . print_r($data, true) . "\n------------------------\n";
    file_put_contents($log_file, $log_entry, FILE_APPEND);
}

// Hàm validate dữ liệu
function validateData($data) {
    $errors = [];
    
    if (empty($data['ngayNhap'])) {
        $errors[] = 'Ngày nhập không được để trống';
    }
    
    if (empty($data['nhaCungCap'])) {
        $errors[] = 'Nhà cung cấp không được để trống';
    }
    
    if (empty($data['maphieunhap'])) {
        $errors[] = 'Mã phiếu nhập không được để trống';
    }
    
    if (empty($data['products']) || !is_array($data['products'])) {
        $errors[] = 'Danh sách sản phẩm không hợp lệ';
    }
    
    if (empty($data['quantities']) || !is_array($data['quantities'])) {
        $errors[] = 'Danh sách số lượng không hợp lệ';
    }
    
    if (empty($data['prices']) || !is_array($data['prices'])) {
        $errors[] = 'Danh sách giá không hợp lệ';
    }
    
    if (count($data['products']) !== count($data['quantities']) || 
        count($data['products']) !== count($data['prices'])) {
        $errors[] = 'Số lượng sản phẩm, số lượng và giá không khớp';
    }
    
    return $errors;
}

try {
    // Kiểm tra session
    session_start();
    if (!isset($_SESSION['NV_MA'])) {
        returnError('Vui lòng đăng nhập để thực hiện chức năng này', 401);
    }

    // Lấy dữ liệu từ request
    $data = [
        'ngayNhap' => $_POST['ngayNhap'] ?? '',
        'nhaCungCap' => $_POST['nhaCungCap'] ?? '',
        'ghiChu' => $_POST['ghiChu'] ?? '',
        'products' => $_POST['products'] ?? [],
        'quantities' => $_POST['quantities'] ?? [],
        'prices' => $_POST['prices'] ?? [],
        'maphieunhap' => $_POST['maphieunhap'] ?? ''
    ];

    debug_log('Processed Data:', $data);

    // Validate dữ liệu
    $errors = validateData($data);
    if (!empty($errors)) {
        debug_log('Validation Errors:', $errors);
        returnError('Dữ liệu không hợp lệ: ' . implode(', ', $errors), 400);
    }

    // Kiểm tra nhà cung cấp có tồn tại và đang hoạt động
    $stmt = $conn->prepare("SELECT NH_MA FROM nguon_hang WHERE NH_MA = ? AND NH_TRANGTHAI = 1");
    if (!$stmt) {
        debug_log('SQL Error (Check Supplier):', $conn->error);
        returnError('Lỗi database: ' . $conn->error);
    }
    $stmt->bind_param("i", $data['nhaCungCap']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        returnError('Nhà cung cấp không tồn tại hoặc đã ngừng hoạt động');
    }
    $stmt->close();

    // Bắt đầu transaction
    if (!$conn->begin_transaction()) {
        debug_log('Transaction Error:', $conn->error);
        returnError('Không thể bắt đầu transaction');
    }

    try {
        // Lấy PN_STT tiếp theo
        $result = $conn->query("SELECT MAX(PN_STT) as max_stt FROM phieu_nhap");
        $row = $result->fetch_assoc();
        $nextSTT = ($row['max_stt'] ?? 0) + 1;

        // Format ngày giờ
        $ngayNhap = date('Y-m-d H:i:s', strtotime($data['ngayNhap']));

        // Tạo phiếu nhập mới
        $sqlPhieuNhap = "INSERT INTO phieu_nhap (PN_STT, PN_MA, NV_MA, PN_NGAYNHAP, PN_GHICHU, PN_TRANGTHAI) 
                         VALUES (?, ?, ?, ?, ?, 'Chờ duyệt')";
        $stmtPhieuNhap = $conn->prepare($sqlPhieuNhap);
        if (!$stmtPhieuNhap) {
            throw new Exception("Lỗi khi chuẩn bị câu lệnh tạo phiếu nhập: " . $conn->error);
        }

        $stmtPhieuNhap->bind_param("isiss", 
            $nextSTT,
            $data['maphieunhap'],
            $_SESSION['NV_MA'],
            $ngayNhap,
            $data['ghiChu']
        );

        if (!$stmtPhieuNhap->execute()) {
            throw new Exception("Lỗi khi tạo phiếu nhập: " . $stmtPhieuNhap->error);
        }

        // Chuẩn bị các statement cho chi tiết
        $sqlChiTiet = "INSERT INTO chitiet_pn (PN_STT, SP_MA, NH_MA, CTPN_KHOILUONG, CTPN_DONVITINH, CTPN_DONGIA) 
                       VALUES (?, ?, ?, ?, ?, ?)";
        $stmtChiTiet = $conn->prepare($sqlChiTiet);
        if (!$stmtChiTiet) {
            throw new Exception("Lỗi khi chuẩn bị câu lệnh thêm chi tiết: " . $stmtChiTiet->error);
        }

        $sqlUpdateStock = "UPDATE san_pham 
                          SET SP_SOLUONGTON = SP_SOLUONGTON + ?,
                              SP_CAPNHAT = CURRENT_TIMESTAMP
                          WHERE SP_MA = ? AND SP_TRANGTHAI = 1";
        $stmtUpdateStock = $conn->prepare($sqlUpdateStock);
        if (!$stmtUpdateStock) {
            throw new Exception("Lỗi khi chuẩn bị câu lệnh cập nhật tồn kho: " . $conn->error);
        }

        $sqlLichSu = "INSERT INTO lich_su_ton_kho 
                      (SP_MA, LSTK_LOAI, LSTK_SOLUONG, LSTK_SOLUONG_CU, LSTK_SOLUONG_MOI, 
                       LSTK_GHICHU, NV_MA, LSTK_THAMCHIEU) 
                      VALUES (?, 'NHAP', ?, ?, ?, ?, ?, ?)";
        $stmtLichSu = $conn->prepare($sqlLichSu);
        if (!$stmtLichSu) {
            throw new Exception("Lỗi khi chuẩn bị câu lệnh ghi lịch sử: " . $stmtLichSu->error);
        }

        // Xử lý từng sản phẩm
        for ($i = 0; $i < count($data['products']); $i++) {
            $productId = $data['products'][$i];
            $quantity = $data['quantities'][$i];
            $price = $data['prices'][$i];

            // Kiểm tra và lấy thông tin sản phẩm hiện tại
            $sqlGetStock = "SELECT SP_SOLUONGTON, SP_DONVITINH FROM san_pham WHERE SP_MA = ? AND SP_TRANGTHAI = 1";
            $stmtGetStock = $conn->prepare($sqlGetStock);
            if (!$stmtGetStock) {
                throw new Exception("Lỗi khi chuẩn bị câu lệnh lấy thông tin sản phẩm: " . $conn->error);
            }

            $stmtGetStock->bind_param("i", $productId);
            if (!$stmtGetStock->execute()) {
                throw new Exception("Lỗi khi lấy thông tin sản phẩm: " . $stmtGetStock->error);
            }

            $result = $stmtGetStock->get_result();
            if ($result->num_rows === 0) {
                throw new Exception("Sản phẩm không tồn tại hoặc đã ngừng kinh doanh");
            }

            $row = $result->fetch_assoc();
            $currentStock = $row['SP_SOLUONGTON'];
            $donViTinh = $row['SP_DONVITINH'];
            $newStock = $currentStock + $quantity;

            // Thêm chi tiết phiếu nhập
            $stmtChiTiet->bind_param("iiidsd", 
                $nextSTT,
                $productId,
                $data['nhaCungCap'],
                $quantity,
                $donViTinh,
                $price
            );
            
            if (!$stmtChiTiet->execute()) {
                throw new Exception("Lỗi khi thêm chi tiết phiếu nhập: " . $stmtChiTiet->error);
            }

            // Cập nhật tồn kho
            $stmtUpdateStock->bind_param("di", $quantity, $productId);
            if (!$stmtUpdateStock->execute()) {
                throw new Exception("Lỗi khi cập nhật tồn kho: " . $stmtUpdateStock->error);
            }

            // Ghi lịch sử tồn kho
            $ghiChuLichSu = sprintf(
                "Nhập kho từ phiếu nhập #%s - %s",
                $data['maphieunhap'],
                $data['ghiChu']
            );

            $stmtLichSu->bind_param("idddsss", 
                $productId,
                $quantity,
                $currentStock,
                $newStock,
                $ghiChuLichSu,
                $_SESSION['NV_MA'],
                $data['maphieunhap']
            );

            if (!$stmtLichSu->execute()) {
                throw new Exception("Lỗi khi ghi lịch sử tồn kho: " . $stmtLichSu->error);
            }

            $stmtGetStock->close();
        }

        // Commit transaction nếu mọi thứ OK
        $conn->commit();

        ob_clean(); // Clear any previous output
        header('Content-Type: application/json; charset=utf-8');
        die(json_encode([
            'status' => 'success',
            'message' => 'Nhập kho thành công',
            'data' => [
                'phieu_nhap_id' => $data['maphieunhap']
            ]
        ], JSON_UNESCAPED_UNICODE));

    } catch (Exception $e) {
        // Rollback transaction nếu có lỗi
        $conn->rollback();
        throw $e;
    } finally {
        // Đóng tất cả các statements
        if (isset($stmtPhieuNhap)) $stmtPhieuNhap->close();
        if (isset($stmtChiTiet)) $stmtChiTiet->close();
        if (isset($stmtUpdateStock)) $stmtUpdateStock->close();
        if (isset($stmtLichSu)) $stmtLichSu->close();
    }

} catch (Exception $e) {
    debug_log('Error:', $e->getMessage());
    returnError($e->getMessage());
} finally {
    if (isset($conn)) $conn->close();
}

// Đảm bảo không có output nào sau JSON response
exit(); 