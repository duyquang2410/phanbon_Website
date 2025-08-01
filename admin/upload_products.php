<?php
session_start();
require 'connect.php';

// Khởi tạo biến
$uploadOk = 1;
$file_name = null;
$target_dir = "../img/"; // Sửa lại đường dẫn thư mục

// Chỉ xử lý khi form được submit
if (isset($_POST["submit"])) {
    // Kiểm tra và xử lý file upload
    if (isset($_FILES["productImg"]) && $_FILES["productImg"]["error"] == 0) {
        $file_name = basename($_FILES["productImg"]["name"]);
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

        // Upload file
        if (!move_uploaded_file($_FILES["productImg"]["tmp_name"], $target_file)) {
            echo "Lỗi khi upload file.";
            exit;
        }
    } else {
        $file_name = "default.jpg";
    }

    // Validate dữ liệu đầu vào
    $required_fields = [
        'source' => 'Nguồn hàng',
        'types' => 'Loại sản phẩm',
        'pd_name' => 'Tên sản phẩm',
        'pd_price' => 'Giá sản phẩm',
        'pd_quantity' => 'Số lượng',
        'pd_unit' => 'Đơn vị tính',
        'pd_weight' => 'Trọng lượng',
        'pd_manufacturer' => 'Nhà sản xuất',
        'pd_ingredients' => 'Thành phần',
        'pd_instructions' => 'Hướng dẫn sử dụng'
    ];

    $errors = [];
    foreach ($required_fields as $field => $label) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            $errors[] = "$label không được để trống!";
        }
    }

    if (!empty($errors)) {
        echo implode("<br>", $errors);
        exit;
    }

    // Kiểm tra phiên đăng nhập
    if (!isset($_SESSION["nvid"])) {
        echo "Phiên đăng nhập không hợp lệ!";
        exit;
    }

    // Lấy và làm sạch dữ liệu
    $nvid = $_SESSION["nvid"];
    $nhid = $conn->real_escape_string($_POST["source"]);
    $dmid = $conn->real_escape_string($_POST["types"]);
    $pdname = $conn->real_escape_string($_POST["pd_name"]);
    $pddes = $conn->real_escape_string($_POST["mota"] ?? '');
    $pdprice = $conn->real_escape_string($_POST["pd_price"]);
    $pdsl = $conn->real_escape_string($_POST["pd_quantity"]);
    $pdunit = $conn->real_escape_string($_POST["pd_unit"]);
    $pdweight = $conn->real_escape_string($_POST["pd_weight"]);
    $pdmanufacturer = $conn->real_escape_string($_POST["pd_manufacturer"]);

    // Xử lý đặc biệt cho thành phần và hướng dẫn sử dụng
    $pdingredients = str_replace(["\r\n", "\r", "\n"], "<br>", $_POST["pd_ingredients"]);
    $pdingredients = $conn->real_escape_string($pdingredients);

    $pdinstructions = str_replace(["\r\n", "\r", "\n"], "<br>", $_POST["pd_instructions"]);
    $pdinstructions = $conn->real_escape_string($pdinstructions);

    // Bắt đầu giao dịch
    $conn->begin_transaction();

    try {
        // Lấy giá trị PN_STT mới
        $sql_max = "SELECT COALESCE(MAX(PN_STT), 0) + 1 AS new_pn_stt FROM phieu_nhap";
        $result = $conn->query($sql_max);
        $row = $result->fetch_assoc();
        $new_pn_stt = $row['new_pn_stt'];

        // Thêm vào bảng phieu_nhap
        $sql1 = "INSERT INTO phieu_nhap (PN_STT, NV_MA, PN_NGAYNHAP) VALUES (?, ?, NOW())";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param("is", $new_pn_stt, $nvid);
        if (!$stmt1->execute()) {
            throw new Exception("Lỗi khi thêm phiếu nhập: " . $stmt1->error);
        }
        $pnid = $new_pn_stt;

        // Lấy SP_MA mới
        $sql_max = "SELECT COALESCE(MAX(CAST(SP_MA AS SIGNED)), 0) + 1 AS new_spma FROM san_pham";
        $result = $conn->query($sql_max);
        $row = $result->fetch_assoc();
        $new_spma = $row['new_spma'];

        // Thêm vào bảng san_pham
        $sql2 = "INSERT INTO san_pham (SP_MA, NH_MA, DM_MA, SP_TEN, SP_DONGIA, SP_SOLUONGTON, SP_HINHANH, SP_MOTA, SP_THANHPHAN, SP_HUONGDANSUDUNG, SP_DONVITINH, SP_TRONGLUONG, SP_NHASANXUAT) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("iiisidsssssds", $new_spma, $nhid, $dmid, $pdname, $pdprice, $pdsl, $file_name, $pddes, $pdingredients, $pdinstructions, $pdunit, $pdweight, $pdmanufacturer);
        if (!$stmt2->execute()) {
            throw new Exception("Lỗi khi thêm sản phẩm: " . $stmt2->error);
        }

        // Thêm vào bảng chitiet_pn
        $sql3 = "INSERT INTO chitiet_pn (SP_MA, PN_STT, NH_MA, CTPN_KHOILUONG, CTPN_DONVITINH, CTPN_DONGIA) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt3 = $conn->prepare($sql3);
        $stmt3->bind_param("iiidsd", $new_spma, $pnid, $nhid, $pdsl, $pdunit, $pdprice);
        if (!$stmt3->execute()) {
            throw new Exception("Lỗi khi thêm chi tiết phiếu nhập: " . $stmt3->error);
        }

        // Commit giao dịch
        $conn->commit();
        echo "<script type='text/javascript'>alert('Thêm sản phẩm thành công!'); window.location='products.php';</script>";

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
}
?>