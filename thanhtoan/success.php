<?php
require '../connect.php'; // Đường dẫn đến connect.php ở thư mục cha
session_start();
ob_start(); // Bật output buffering để tránh lỗi header

// Chuỗi bí mật của VNPAY (phải khớp với Sandbox hoặc Production)
$vnp_HashSecret = "MMZXWISZNUUUNKGOZQPCPASLLTHYGMTB";

// Hàm xử lý xóa đơn hàng tạm nếu thất bại
function deleteTempOrder($conn, $new_id) {
    $sql_delete = "DELETE FROM hoa_don WHERE HD_STT = ? AND TT_MA = 1";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $new_id);
    $stmt_delete->execute();
    $stmt_delete->close();
}

// Hàm xử lý thành công đơn hàng
function processSuccessfulOrder($conn, $order_data) {
    $new_id = $order_data['new_id'];
    $ghma = $order_data['gh_ma'];
    $array = $order_data['array'];
    $array_sl = $order_data['array_sl'];
    $size_array = $order_data['size_array'];
    $color_array = $order_data['color_array'];

    // Cập nhật trạng thái đơn hàng thành "Đã thanh toán" (TT_MA = 2)
    $sql_update = "UPDATE hoa_don SET TT_MA = 2 WHERE HD_STT = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("i", $new_id);
    $success = $stmt_update->execute();
    $stmt_update->close();

    if (!$success) {
        file_put_contents('debug.log', "Lỗi cập nhật trạng thái đơn hàng: " . $conn->error . "\n", FILE_APPEND);
        return false;
    }

    $ok = 1;
    foreach ($array as $index => $spid) {
        $spid = intval($spid);
        $slsp = isset($array_sl[$index]) ? intval($array_sl[$index]) : 0;
        $size = $size_array[$index] ?? '';
        $color = $color_array[$index] ?? '';

        if ($slsp <= 0) continue;

        // Lấy giá sản phẩm
        $query_price = "SELECT SP_DONGIA FROM san_pham WHERE SP_MA = ?";
        $stmt_price = $conn->prepare($query_price);
        $stmt_price->bind_param("i", $spid);
        $stmt_price->execute();
        $result_price = $stmt_price->get_result();
        $price = $result_price->fetch_assoc()['SP_DONGIA'] ?? 0;
        $stmt_price->close();

        // Thêm chi tiết hóa đơn
        $sql_ct = "INSERT INTO chi_tiet_hd (SP_MA, HD_STT, CTHD_SLB, CTHD_DONGIA, CTHD_SIZE, CTHD_MAU) 
                   VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_ct = $conn->prepare($sql_ct);
        $stmt_ct->bind_param("iiidss", $spid, $new_id, $slsp, $price, $size, $color);
        if (!$stmt_ct->execute()) {
            $ok = 0;
            file_put_contents('debug.log', "Lỗi thêm chi tiết hóa đơn: " . $stmt_ct->error . "\n", FILE_APPEND);
        }
        $stmt_ct->close();
    }

    // Xóa chi tiết giỏ hàng
    $sql_delete = "DELETE FROM chitiet_gh WHERE GH_MA = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $ghma);
    $success = $stmt_delete->execute() && $ok == 1;
    $stmt_delete->close();

    if ($success) {
        unset($_SESSION['order_data']);
        unset($_SESSION['voucherCode']);
        unset($_SESSION['km_ma']);
        unset($_SESSION['discount']);
    }
    return $success;
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Xử lý phản hồi từ VNPAY
    if (isset($_GET['vnp_ResponseCode'])) {
        $vnp_SecureHash = $_GET['vnp_SecureHash'];
        $inputData = array_filter($_GET, fn($key) => str_starts_with($key, 'vnp_'), ARRAY_FILTER_USE_KEY);
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $hashData = http_build_query($inputData, '', '&', PHP_QUERY_RFC3986);
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        // Debug chữ ký nếu cần
        // file_put_contents('debug.log', "HashData: $hashData\nSecureHash: $secureHash\nExpected: $vnp_SecureHash\n", FILE_APPEND);

        if ($secureHash === $vnp_SecureHash && $_GET['vnp_ResponseCode'] == '00') {
            if (isset($_SESSION['order_data']) && processSuccessfulOrder($conn, $_SESSION['order_data'])) {
                header('Location: success_result.php');
            } else {
                $reason = "Lỗi xử lý đơn hàng sau thanh toán VNPAY";
                header('Location: failure_result.php?reason=' . urlencode($reason));
            }
        } else {
            if (isset($_SESSION['order_data'])) {
                deleteTempOrder($conn, $_SESSION['order_data']['new_id']);
                unset($_SESSION['order_data']);
            }
            $reason = "Giao dịch VNPAY thất bại. Mã lỗi: " . ($_GET['vnp_ResponseCode'] ?? 'Không xác định');
            header('Location: failure_result.php?reason=' . urlencode($reason));
        }
    }
    // Xử lý phản hồi từ MoMo
    elseif (isset($_GET['resultCode']) && $_GET['resultCode'] == '0') {
        if (isset($_SESSION['order_data']) && processSuccessfulOrder($conn, $_SESSION['order_data'])) {
            header('Location: success_result.php');
        } else {
            $reason = "Lỗi xử lý đơn hàng sau thanh toán MoMo";
            header('Location: failure_result.php?reason=' . urlencode($reason));
        }
    } else {
        if (isset($_SESSION['order_data'])) {
            deleteTempOrder($conn, $_SESSION['order_data']['new_id']);
            unset($_SESSION['order_data']);
        }
        $reason = "Giao dịch MoMo thất bại. Mã lỗi: " . ($_GET['resultCode'] ?? 'Không xác định');
        header('Location: failure_result.php?reason=' . urlencode($reason));
    }
    exit();
}

$conn->close();
?>