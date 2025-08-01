<?php
session_start();
require 'connect.php';

if (!isset($_POST["temp_id"]) || !isset($_POST["quantity"]) || empty($_POST["quantity"])) {
    echo "Thiếu thông tin cần thiết!";
    exit;
}

// Lấy và validate dữ liệu
$pdid = $conn->real_escape_string($_POST["temp_id"]);
$quantity = (int)$_POST["quantity"];

if ($quantity <= 0) {
    echo "Số lượng phải lớn hơn 0!";
    exit;
}

// Bắt đầu giao dịch
$conn->begin_transaction();

try {
    // Lấy thông tin sản phẩm và lần nhập cuối cùng
    $sql_info = "SELECT sp.SP_DONGIA, ct.NH_MA, ct.CTPN_DONVITINH 
                 FROM san_pham sp 
                 LEFT JOIN chitiet_pn ct ON sp.SP_MA = ct.SP_MA 
                 LEFT JOIN phieu_nhap pn ON ct.PN_STT = pn.PN_STT 
                 WHERE sp.SP_MA = ? 
                 ORDER BY pn.PN_NGAYNHAP DESC 
                 LIMIT 1";
    
    $stmt_info = $conn->prepare($sql_info);
    $stmt_info->bind_param("i", $pdid);
    $stmt_info->execute();
    $result = $stmt_info->get_result();
    $info = $result->fetch_assoc();
    
    if (!$info) {
        throw new Exception("Không tìm thấy thông tin sản phẩm!");
    }

    // Nếu không tìm thấy nguồn hàng hoặc đơn vị tính, sử dụng giá trị mặc định
    $nh_ma = $info['NH_MA'] ?? 1; // Giả sử 1 là mã nguồn hàng mặc định
    $donvitinh = $info['CTPN_DONVITINH'] ?? 'cái'; // Sử dụng đơn vị tính mặc định là 'cái'

    // Lấy giá trị PN_STT mới
    $sql_max = "SELECT COALESCE(MAX(PN_STT), 0) + 1 AS new_pn_stt FROM phieu_nhap";
    $result = $conn->query($sql_max);
    $row = $result->fetch_assoc();
    $new_pn_stt = $row['new_pn_stt'];

    // Tạo phiếu nhập mới
    $sql_pn = "INSERT INTO phieu_nhap (PN_STT, NV_MA, PN_NGAYNHAP) VALUES (?, ?, NOW())";
    $stmt_pn = $conn->prepare($sql_pn);
    $stmt_pn->bind_param("is", $new_pn_stt, $_SESSION["nvid"]);
    if (!$stmt_pn->execute()) {
        throw new Exception("Lỗi khi tạo phiếu nhập: " . $stmt_pn->error);
    }
    $pn_stt = $new_pn_stt;

    // Thêm chi tiết phiếu nhập
    $sql_ctpn = "INSERT INTO chitiet_pn (SP_MA, PN_STT, NH_MA, CTPN_KHOILUONG, CTPN_DONVITINH, CTPN_DONGIA) 
                VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_ctpn = $conn->prepare($sql_ctpn);
    $stmt_ctpn->bind_param("iiidsd", $pdid, $pn_stt, $nh_ma, $quantity, $donvitinh, $info['SP_DONGIA']);
    if (!$stmt_ctpn->execute()) {
        throw new Exception("Lỗi khi thêm chi tiết phiếu nhập: " . $stmt_ctpn->error);
    }

    // Cập nhật số lượng sản phẩm
    $sql_update = "UPDATE san_pham SET SP_SOLUONGTON = SP_SOLUONGTON + ? WHERE SP_MA = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ii", $quantity, $pdid);
    if (!$stmt_update->execute()) {
        throw new Exception("Lỗi khi cập nhật số lượng sản phẩm: " . $stmt_update->error);
    }

    // Commit giao dịch
    $conn->commit();
    echo "<script type='text/javascript'>alert('Nhập thêm sản phẩm thành công!'); window.location='products.php';</script>";

} catch (Exception $e) {
    // Rollback nếu có lỗi
    $conn->rollback();
    echo "Lỗi: " . $e->getMessage();
} finally {
    // Đóng các statement
    if (isset($stmt_info)) $stmt_info->close();
    if (isset($stmt_pn)) $stmt_pn->close();
    if (isset($stmt_ctpn)) $stmt_ctpn->close();
    if (isset($stmt_update)) $stmt_update->close();
    $conn->close();
}
?>