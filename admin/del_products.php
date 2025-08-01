<?php 
require 'connect.php';

// Tắt hiển thị lỗi trên trang web
ini_set('display_errors', 0);
error_reporting(0);

$pdid = $_POST["pdid"];

// Bắt đầu giao dịch
$conn->begin_transaction();

try {
    // Bước 1: Lấy tên sản phẩm
    $stmt = $conn->prepare("SELECT sp_ten FROM san_pham WHERE sp_ma = ?");
    $stmt->bind_param("i", $pdid);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        throw new Exception("Không tìm thấy sản phẩm");
    }
    
    $row = $result->fetch_assoc();
    $spten = $row["sp_ten"];
    $stmt->close();
    
    // Tạm thời tắt kiểm tra khóa ngoại để xóa dữ liệu
    $conn->query("SET FOREIGN_KEY_CHECKS=0");
    
    // Bước 2: Xóa từ bảng chitiet_gh
    $stmt_gh = $conn->prepare("DELETE FROM chitiet_gh WHERE sp_ma = ?");
    $stmt_gh->bind_param("i", $pdid);
    $stmt_gh->execute();
    $stmt_gh->close();
    
    // Bước 3: Xóa từ bảng chitiet_pn
    $stmt_pn = $conn->prepare("DELETE FROM chitiet_pn WHERE sp_ma = ?");
    $stmt_pn->bind_param("i", $pdid);
    $stmt_pn->execute();
    $stmt_pn->close();
    
    // Bước 4: Xóa từ bảng chi_tiet_hd
    $stmt_hd = $conn->prepare("DELETE FROM chi_tiet_hd WHERE sp_ma = ?");
    $stmt_hd->bind_param("i", $pdid);
    $stmt_hd->execute();
    $stmt_hd->close();
    
    // Bước 5: Xóa từ bảng danh_gia
    $stmt_dg = $conn->prepare("DELETE FROM danh_gia WHERE sp_ma = ?");
    $stmt_dg->bind_param("i", $pdid);
    $stmt_dg->execute();
    $stmt_dg->close();
    
    // Bước 6: Xóa từ bảng thuvien_anh
    $stmt_tva = $conn->prepare("DELETE FROM thuvien_anh WHERE sp_ma = ?");
    $stmt_tva->bind_param("i", $pdid);
    $stmt_tva->execute();
    $stmt_tva->close();
    
    // Bước 7: Xóa từ bảng san_pham
    $stmt_sp = $conn->prepare("DELETE FROM san_pham WHERE sp_ma = ?");
    $stmt_sp->bind_param("i", $pdid);
    $stmt_sp->execute();
    
    if ($stmt_sp->affected_rows === 0) {
        throw new Exception("Không thể xóa sản phẩm");
    }
    $stmt_sp->close();
    
    // Bật lại kiểm tra khóa ngoại
    $conn->query("SET FOREIGN_KEY_CHECKS=1");
    
    // Commit giao dịch
    $conn->commit();
    
    // Chuyển hướng với thông báo thành công
    header("Location: http://localhost/shopquanao_nl/Eshopper/admin/products.php?action=delete_success");
    exit;
} catch (Exception $e) {
    // Rollback nếu có lỗi
    $conn->rollback();
    // Bật lại kiểm tra khóa ngoại
    $conn->query("SET FOREIGN_KEY_CHECKS=1");
    
    // Chuyển hướng với thông báo lỗi
    header("Location: http://localhost/shopquanao_nl/Eshopper/admin/products.php?action=delete_error&msg=" . urlencode($e->getMessage()));
    exit;
}

$conn->close();
?>
