<?php
session_start();
require 'connect.php';

// Kiểm tra file upload
if (isset($_FILES["productImg"]) && $_FILES["productImg"]["error"] == 0) {
    $file_name = basename($_FILES["productImg"]["name"]);
    $target_dir = "../img/";  // Sửa lại đường dẫn
    $target_file = $target_dir . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Kiểm tra file ảnh
    $check = getimagesize($_FILES["productImg"]["tmp_name"]);
        if ($check === false) {
            echo "File không phải là hình ảnh.";
            exit;
        }

    // Kiểm tra file đã tồn tại và tạo tên mới nếu cần
    $new_name = $file_name;
    if (file_exists($target_file)) {
        $count = 1;
        $name = strtolower(pathinfo($new_name, PATHINFO_FILENAME));
        while (file_exists($target_file)) {
            $new_name = $name . "-" . $count . "." . $imageFileType;
            $target_file = $target_dir . $new_name;
            $count++;
        }
        $file_name = $new_name;
    }

        // Kiểm tra kích thước file
    if ($_FILES["productImg"]["size"] > 30000000) {
        echo "Dung lượng file quá lớn (tối đa 30MB)";
            exit;
        }

    // Kiểm tra định dạng file
    if (!in_array($imageFileType, ["jpg", "jpeg", "png"])) {
        echo "Chỉ chấp nhận file JPG, JPEG & PNG";
            exit;
        }

    // Tạo thư mục nếu chưa tồn tại
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Upload file
    if (!move_uploaded_file($_FILES["productImg"]["tmp_name"], $target_file)) {
        echo "Lỗi khi upload file.";
            exit;
        }
    } else {
    $file_name = $_POST["old_productImg"];
}

// Lấy và làm sạch dữ liệu
$idsp = $conn->real_escape_string($_POST["idsp"]);
$madm = $conn->real_escape_string($_POST["madm"]);
$tensp = $conn->real_escape_string($_POST["tensp"]);
$giasp = $conn->real_escape_string($_POST["giasp"]);
$slsp = $conn->real_escape_string($_POST["slsp"]);
$manh = $conn->real_escape_string($_POST["manh"]);
$mota = $conn->real_escape_string($_POST["mota"]);
$donvitinh = $conn->real_escape_string($_POST["pd_unit"]);
$trongluong = $conn->real_escape_string($_POST["pd_weight"]);
$nhasanxuat = $conn->real_escape_string($_POST["pd_manufacturer"]);

// Xử lý đặc biệt cho thành phần và hướng dẫn sử dụng
$thanhphan = str_replace(["\r\n", "\r", "\n"], "<br>", $_POST["pd_ingredients"]);
$thanhphan = $conn->real_escape_string($thanhphan);

$huongdan = str_replace(["\r\n", "\r", "\n"], "<br>", $_POST["pd_instructions"]);
$huongdan = $conn->real_escape_string($huongdan);

// Bắt đầu transaction
$conn->begin_transaction();

try {
    // Cập nhật bảng san_pham bằng prepared statement
    $sql1 = "UPDATE san_pham SET 
            NH_MA = ?,
            DM_MA = ?, 
            SP_TEN = ?, 
            SP_DONGIA = ?, 
            SP_SOLUONGTON = ?, 
            SP_HINHANH = ?, 
            SP_MOTA = ?,
            SP_THANHPHAN = ?,
            SP_HUONGDANSUDUNG = ?,
            SP_DONVITINH = ?,
            SP_TRONGLUONG = ?,
            SP_NHASANXUAT = ?
            WHERE SP_MA = ?";
    
    // Debug
    echo "SQL: $sql1<br>";
    echo "Params: manh=$manh, madm=$madm, tensp=$tensp, giasp=$giasp, slsp=$slsp, file_name=$file_name, mota=$mota, thanhphan=$thanhphan, huongdan=$huongdan, donvitinh=$donvitinh, trongluong=$trongluong, nhasanxuat=$nhasanxuat, idsp=$idsp<br>";
    
    $stmt1 = $conn->prepare($sql1);
    if ($stmt1 === false) {
        throw new Exception("Lỗi prepare statement: " . $conn->error);
    }

    // Chuyển đổi kiểu dữ liệu
    $giasp = (float)$giasp;
    $slsp = (float)$slsp;
    $trongluong = (float)$trongluong;
    $manh = (int)$manh;
    $madm = (int)$madm;
    $idsp = (int)$idsp;

    // Bind tất cả tham số cùng lúc
    // i: integer, d: double, s: string
    // Đếm số dấu ? trong câu SQL: 13
    // NH_MA=?, DM_MA=?, SP_TEN=?, SP_DONGIA=?, SP_SOLUONGTON=?, SP_HINHANH=?, 
    // SP_MOTA=?, SP_THANHPHAN=?, SP_HUONGDANSUDUNG=?, SP_DONVITINH=?, SP_TRONGLUONG=?,
    // SP_NHASANXUAT=?, WHERE SP_MA=?
    if (!$stmt1->bind_param('iisddsssssdsi', 
        $manh,          // NH_MA (i)
        $madm,          // DM_MA (i)
        $tensp,         // SP_TEN (s)
        $giasp,         // SP_DONGIA (d)
        $slsp,          // SP_SOLUONGTON (d)
        $file_name,     // SP_HINHANH (s)
        $mota,          // SP_MOTA (s)
        $thanhphan,     // SP_THANHPHAN (s)
        $huongdan,      // SP_HUONGDANSUDUNG (s)
        $donvitinh,     // SP_DONVITINH (s)
        $trongluong,    // SP_TRONGLUONG (d)
        $nhasanxuat,    // SP_NHASANXUAT (s)
        $idsp           // WHERE SP_MA (i)
    )) {
        // Nếu bind_param thất bại, thử sử dụng câu lệnh SQL trực tiếp
        $sql_direct = "UPDATE san_pham SET 
                NH_MA = $manh,
                DM_MA = $madm, 
                SP_TEN = '" . $conn->real_escape_string($tensp) . "', 
                SP_DONGIA = $giasp, 
                SP_SOLUONGTON = $slsp, 
                SP_HINHANH = '" . $conn->real_escape_string($file_name) . "', 
                SP_MOTA = '" . $conn->real_escape_string($mota) . "',
                SP_THANHPHAN = '" . $conn->real_escape_string($thanhphan) . "',
                SP_HUONGDANSUDUNG = '" . $conn->real_escape_string($huongdan) . "',
                SP_DONVITINH = '" . $conn->real_escape_string($donvitinh) . "',
                SP_TRONGLUONG = $trongluong,
                SP_NHASANXUAT = '" . $conn->real_escape_string($nhasanxuat) . "'
                WHERE SP_MA = $idsp";
        
        if (!$conn->query($sql_direct)) {
            throw new Exception("Lỗi khi cập nhật sản phẩm (direct): " . $conn->error);
        }
        echo "<script type='text/javascript'>alert('Cập nhật sản phẩm thành công (direct)!'); window.location='products.php';</script>";
        exit;
    }

    if (!$stmt1->execute()) {
        throw new Exception("Lỗi khi cập nhật sản phẩm: " . $stmt1->error . "\nSQL State: " . $stmt1->sqlstate);
    }

    // Tạo phiếu nhập mới
    $sql_max = "SELECT COALESCE(MAX(PN_STT), 0) + 1 AS new_pn_stt FROM phieu_nhap";
    $result = $conn->query($sql_max);
    $row = $result->fetch_assoc();
    $new_pn_stt = $row['new_pn_stt'];

    $sql2 = "INSERT INTO phieu_nhap (PN_STT, NV_MA, PN_NGAYNHAP) VALUES (?, ?, NOW())";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("is", $new_pn_stt, $_SESSION["nvid"]);
    if (!$stmt2->execute()) {
        throw new Exception("Lỗi khi tạo phiếu nhập: " . $stmt2->error);
    }
    $pn_stt = $new_pn_stt;

    // Thêm chi tiết phiếu nhập mới
    $sql3 = "INSERT INTO chitiet_pn (SP_MA, PN_STT, NH_MA, CTPN_KHOILUONG, CTPN_DONVITINH, CTPN_DONGIA) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt3 = $conn->prepare($sql3);
    $stmt3->bind_param("iiidsd", $idsp, $pn_stt, $manh, $slsp, $donvitinh, $giasp);
    if (!$stmt3->execute()) {
        throw new Exception("Lỗi khi thêm chi tiết phiếu nhập: " . $stmt3->error);
    }

    // Commit giao dịch
    $conn->commit();
    echo "<script type='text/javascript'>alert('Cập nhật sản phẩm thành công!'); window.location='products.php';</script>";

} catch (Exception $e) {
    // Rollback nếu có lỗi
    $conn->rollback();
    echo "Lỗi: " . $e->getMessage();
} finally {
    // Đóng các statement
    if (isset($stmt1)) $stmt1->close();
    if (isset($stmt2)) $stmt2->close();
    if (isset($stmt3)) $stmt3->close();
    $conn->close();
}
?>