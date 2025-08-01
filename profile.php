<?php
session_start();

// Debug session data
error_log('PROFILE - Session ID: ' . session_id());
error_log('PROFILE - Full Session Data: ' . print_r($_SESSION, true));
error_log('PROFILE - Session Cookie: ' . print_r($_COOKIE, true));
error_log('PROFILE - Session Save Path: ' . session_save_path());
error_log('PROFILE - Session Status: ' . session_status());

// Kiểm tra nếu chưa đăng nhập thì chuyển hướng về trang đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'connect.php';

$user_id = $_SESSION['user_id'];
$success_message = "";
$error_message = "";

// Lấy thông tin người dùng
$sql = "SELECT * FROM khach_hang WHERE KH_MA = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    header("Location: logout.php");
    exit();
}

// Hàm lấy nội dung từ URL bằng cURL (dùng cho API nội bộ)
function file_get_contents_curl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

// Xử lý cập nhật thông tin
if (isset($_POST['update_profile'])) {
    // Debug log cho dữ liệu POST
    error_log("Profile Update POST Data: " . print_r($_POST, true));

    // Kiểm tra CSRF token (nếu có, cần triển khai thêm)
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING);
    $birthdate = filter_input(INPUT_POST, 'birthdate', FILTER_SANITIZE_STRING);

    // Xử lý địa chỉ trước khi cập nhật database
    if (isset($_POST['province']) && isset($_POST['district']) && isset($_POST['ward']) && isset($_POST['street_address'])) {
        try {
            // Xác định base URL
            $base_url = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . '/';

            // Gọi API để lấy tên tỉnh/thành phố
            $province_url = $base_url . "viettelpost_api.php?endpoint=categories/listProvince";
            $province_response = file_get_contents_curl($province_url);
            $province_data = json_decode($province_response, true);
            $province_name = '';
            if ($province_data && isset($province_data['data'])) {
                foreach ($province_data['data'] as $province) {
                    if ($province['PROVINCE_ID'] == $_POST['province']) {
                        $province_name = $province['PROVINCE_NAME'];
                        break;
                    }
                }
            }

            // Gọi API để lấy tên quận/huyện
            $district_url = $base_url . "viettelpost_api.php?endpoint=categories/listDistrict&provinceId=" . $_POST['province'];
            $district_response = file_get_contents_curl($district_url);
            $district_data = json_decode($district_response, true);
            $district_name = '';
            if ($district_data && isset($district_data['data'])) {
                foreach ($district_data['data'] as $district) {
                    if ($district['DISTRICT_ID'] == $_POST['district']) {
                        $district_name = $district['DISTRICT_NAME'];
                        break;
                    }
                }
            }

            // Gọi API để lấy tên phường/xã
            $ward_url = $base_url . "viettelpost_api.php?endpoint=categories/listWards&districtId=" . $_POST['district'];
            $ward_response = file_get_contents_curl($ward_url);
            $ward_data = json_decode($ward_response, true);
            $ward_name = '';
            if ($ward_data && isset($ward_data['data'])) {
                foreach ($ward_data['data'] as $ward) {
                    if ($ward['WARDS_ID'] == $_POST['ward']) {
                        $ward_name = $ward['WARDS_NAME'];
                        break;
                    }
                }
            }

            // Tạo địa chỉ đầy đủ
            $address = $_POST['street_address'];
            if ($ward_name) $address .= ', ' . $ward_name;
            if ($district_name) $address .= ', ' . $district_name;
            if ($province_name) $address .= ', ' . $province_name;

            // Debug log trước khi lưu
            error_log("POST data for address: " . print_r($_POST, true));
            error_log("Current session before saving: " . print_r($_SESSION, true));

            // Lưu thông tin địa chỉ vào session
            $_SESSION['saved_address'] = [
                'street' => $_POST['street_address'],
                'province_id' => $_POST['province'],
                'province_name' => $province_name,
                'district_id' => $_POST['district'],
                'district_name' => $district_name,
                'ward_id' => $_POST['ward'],
                'ward_name' => $ward_name,
                'full_address' => $address
            ];

            error_log("Saved address to session: " . print_r($_SESSION['saved_address'], true));
            error_log("Full session after saving: " . print_r($_SESSION, true));

            // Kiểm tra xem địa chỉ đã được lưu thành công chưa
            if (!isset($_SESSION['saved_address'])) {
                error_log("ERROR: Address was not saved to session!");
            }

        } catch (Exception $e) {
            error_log("Error saving address: " . $e->getMessage());
            $error_message = "Có lỗi xảy ra khi lưu địa chỉ: " . $e->getMessage();
            goto end_update;
        }
    }

    // Kiểm tra email đã tồn tại chưa (nếu thay đổi email)
    if ($email !== $user['KH_EMAIL']) {
        $check_email_sql = "SELECT * FROM khach_hang WHERE KH_EMAIL = ? AND KH_MA != ?";
        $check_email_stmt = $conn->prepare($check_email_sql);
        $check_email_stmt->bind_param("si", $email, $user_id);
        $check_email_stmt->execute();
        $check_email_result = $check_email_stmt->get_result();

        if ($check_email_result->num_rows > 0) {
            $error_message = "Email đã được sử dụng bởi tài khoản khác!";
            $check_email_stmt->close();
            goto end_update;
        }
        $check_email_stmt->close();
    }

    // Cập nhật thông tin người dùng
    $update_sql = "UPDATE khach_hang SET KH_TEN = ?, KH_EMAIL = ?, KH_SDT = ?, KH_DIACHI = ?, KH_GIOITINH = ?, KH_NGAYSINH = ? WHERE KH_MA = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssssi", $name, $email, $phone, $address, $gender, $birthdate, $user_id);

    if ($update_stmt->execute()) {
        $success_message = "Cập nhật thông tin thành công!";
        $_SESSION['user_name'] = $name;

        // Lấy lại thông tin người dùng sau khi cập nhật
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
    } else {
        $error_message = "Cập nhật thông tin thất bại: " . $conn->error;
    }

    $update_stmt->close();
}

// Xử lý đổi mật khẩu
if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Kiểm tra mật khẩu hiện tại
    if (!password_verify($current_password, $user['KH_MATKHAU'])) {
        $error_message = "Mật khẩu hiện tại không đúng!";
        goto end_update;
    }

    // Kiểm tra mật khẩu mới và xác nhận
    if ($new_password !== $confirm_password) {
        $error_message = "Mật khẩu mới và xác nhận mật khẩu không khớp!";
        goto end_update;
    }

    // Mã hóa mật khẩu mới
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $update_password_sql = "UPDATE khach_hang SET KH_MATKHAU = ? WHERE KH_MA = ?";
    $update_password_stmt = $conn->prepare($update_password_sql);
    $update_password_stmt->bind_param("si", $hashed_password, $user_id);

    if ($update_password_stmt->execute()) {
        $success_message = "Đổi mật khẩu thành công!";
    } else {
        $error_message = "Đổi mật khẩu thất bại: " . $conn->error;
    }

    $update_password_stmt->close();
}

end_update:
$stmt->close();
// Không đóng $conn ở đây để header.php có thể sử dụng
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thông Tin Cá Nhân - Plants Shop</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/global.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <style>
        .profile-container {
            max-width: 1000px;
            margin: 30px auto;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .profile-header {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            padding: 25px 20px;
            text-align: center;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .profile-header-left {
            display: flex;
            align-items: center;
            gap: 20px;
            flex: 1;
            min-width: 300px;
            justify-content: center;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.3);
            overflow: hidden;
            position: relative;
            background: #fff;
            flex-shrink: 0;
        }

        .profile-info {
            text-align: left;
        }

        .profile-name {
            font-size: 20px;
            font-weight: 600;
            margin: 0;
            color: #ffffff;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .profile-email {
            font-size: 13px;
            opacity: 0.95;
            margin: 3px 0;
            color: #ffffff;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .profile-stats {
            display: flex;
            gap: 20px;
            margin: 0;
            flex-wrap: wrap;
            justify-content: center;
            flex: 1;
            min-width: 300px;
        }

        .stat-item {
            text-align: center;
            background: rgba(255, 255, 255, 0.2);
            padding: 8px 15px;
            border-radius: 10px;
            min-width: 120px;
            backdrop-filter: blur(5px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .stat-value {
            font-size: 18px;
            font-weight: 600;
            color: #ffffff;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .stat-label {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.95);
            margin-top: 2px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .nav-pills {
            padding: 20px 20px 0;
            gap: 10px;
            border-bottom: 1px solid #eee;
        }

        .nav-pills .nav-link {
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 500;
            color: #666;
            transition: all 0.3s;
            position: relative;
        }

        .nav-pills .nav-link.active {
            background: #4CAF50;
            color: #ffffff !important;
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
            font-weight: 600;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .nav-pills .nav-link:not(.active):hover {
            background: #f8f9fa;
            transform: translateY(-2px);
            color: #4CAF50;
        }

        .nav-pills .nav-link i {
            margin-right: 8px;
            font-size: 14px;
        }

        .tab-content {
            padding: 30px;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 2px solid #eee;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.25);
        }

        .form-label {
            font-weight: 500;
            color: #444;
            margin-bottom: 8px;
        }

        .btn-update {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }

        .profile-section {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            animation: fadeIn 0.5s ease;
        }

        .profile-section h4 {
            color: #333;
            font-size: 18px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .profile-section h4 i {
            color: #4CAF50;
        }

        .input-group-text {
            background: #f8f9fa;
            border: 2px solid #eee;
            border-right: none;
        }

        .password-toggle {
            cursor: pointer;
            padding: 12px 15px;
            background: #f8f9fa;
            border: 2px solid #eee;
            border-left: none;
            border-radius: 0 10px 10px 0;
        }

        .alert {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 20px;
            animation: slideInDown 0.5s ease;
        }

        .alert-success {
            background: #E8F5E9;
            color: #2E7D32;
        }

        .alert-danger {
            background: #FFEBEE;
            color: #C62828;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideInDown {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .order-history {
            margin-top: 20px;
        }

        .order-card {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid #eee;
            transition: all 0.3s;
        }

        .order-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .order-id {
            font-weight: 600;
            color: #4CAF50;
        }

        .order-date {
            color: #666;
            font-size: 14px;
        }

        .order-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-pending { background: #FFF3E0; color: #E65100; }
        .status-processing { background: #E3F2FD; color: #1565C0; }
        .status-completed { background: #E8F5E9; color: #2E7D32; }
        .status-cancelled { background: #FFEBEE; color: #C62828; }

        .form-floating {
            position: relative;
            margin-bottom: 1rem;
        }

        .form-floating > .form-control,
        .form-floating > .form-select {
            height: 56px;
            padding: 1rem 0.75rem;
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 8px;
            transition: all 0.2s ease-in-out;
            background-color: #fff;
            color: #344767;
            font-size: 0.95rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .form-floating > .form-control:focus,
        .form-floating > .form-select:focus {
            border-color: #4CAF50;
            box-shadow: 0 3px 6px rgba(76, 175, 80, 0.08);
        }

        .form-floating > label {
            padding: 1rem 0.75rem;
            color: #8392AB;
            font-size: 0.875rem;
        }

        .form-label {
            color: #344767;
            font-size: 0.875rem;
        }

        .form-select {
            background-position: right 0.75rem center;
            background-size: 16px 12px;
            cursor: pointer;
            color: #344767;
        }

        .form-select option {
            color: #344767;
            font-size: 0.875rem;
        }

        .form-select:disabled {
            background-color: #f8f9fa;
            cursor: not-allowed;
            border-color: rgba(0, 0, 0, 0.08);
            color: #8392AB;
        }

        #full-address-display {
            background-color: #fff;
            border: 1px solid rgba(0, 0, 0, 0.08) !important;
            transition: all 0.3s ease;
            font-size: 0.875rem;
            line-height: 1.5;
            margin-top: 0.5rem;
            color: #344767;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
            padding: 0.75rem 1rem;
        }

        #full-address-display:hover {
            border-color: #4CAF50 !important;
            box-shadow: 0 3px 6px rgba(76, 175, 80, 0.08);
        }

        .address-preview {
            margin-top: 1rem;
        }

        .address-preview .form-label {
            color: #8392AB;
            font-weight: 400;
            font-size: 0.875rem;
        }

        .input-group > .form-control {
            border-radius: 0 8px 8px 0;
        }

        .input-group > .input-group-text {
            border-radius: 8px 0 0 8px;
            border: 1px solid rgba(0, 0, 0, 0.08);
            background-color: #fff;
            padding: 0.75rem 1rem;
            color: #8392AB;
        }

        .input-group > .input-group-text i {
            color: #4CAF50;
            font-size: 1rem;
        }

        @media (max-width: 768px) {
            .address-section {
                padding: 1rem !important;
            }
            
            .form-floating > .form-control,
            .form-floating > .form-select {
                height: 50px;
            }

            .address-section .row.g-3 > .col-12 {
                margin-bottom: 0.75rem;
            }
        }

        .form-label.fw-bold {
            color: #344767;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .form-label i.text-primary {
            color: #4CAF50 !important;
        }

        .form-floating i.text-muted {
            color: #8392AB !important;
        }

        .fa-check-circle.text-success {
            color: #4CAF50 !important;
        }

        .fa-info-circle.text-primary {
            color: #4CAF50 !important;
        }

        .form-control::placeholder {
            color: #8392AB;
            opacity: 0.8;
        }

        .form-control:focus::placeholder {
            opacity: 0.5;
        }

        .alert-danger {
            background-color: #fff2f2;
            border: none;
            color: #ff3366;
        }

        /* Tiêu đề các trường */
        label[for="street_address"],
        label[for="province"],
        label[for="district"],
        label[for="ward"] {
            color: #8392AB;
            font-size: 0.875rem;
        }

        /* Placeholder */
        .form-control::placeholder {
            color: #8392AB;
            opacity: 0.8;
        }

        /* Text trong select box */
        .form-select option:not(:first-child) {
            color: #344767;
        }

        /* Text trong input khi đã nhập */
        .form-control:not(:placeholder-shown) {
            color: #344767;
        }

        /* Địa chỉ đầy đủ */
        #full-address-display span {
            color: #344767;
        }

        /* Label chính */
        .section-label {
            color: #344767;
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }

        /* Icon màu */
        .text-primary {
            color: #4CAF50 !important;
        }

        .text-success {
            color: #4CAF50 !important;
        }

        .text-muted {
            color: #8392AB !important;
        }

        /* Input group */
        .input-group > .form-control {
            border-radius: 0 8px 8px 0;
        }

        .input-group > .input-group-text {
            border-radius: 8px 0 0 8px;
            border: 1px solid rgba(0, 0, 0, 0.08);
            background-color: #fff;
            padding: 0.75rem 1rem;
            color: #8392AB;
        }

        .input-group > .input-group-text i {
            color: #4CAF50;
            font-size: 1rem;
        }

        /* Label icons */
        .form-label i {
            font-size: 0.875rem;
            margin-right: 0.5rem;
        }

        /* Hover effects */
        .form-control:hover,
        .form-select:hover,
        #full-address-display:hover {
            border-color: rgba(76, 175, 80, 0.3);
        }

        /* Focus styles */
        .form-control:focus,
        .form-select:focus {
            border-color: #4CAF50;
            box-shadow: 0 3px 6px rgba(76, 175, 80, 0.08);
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <div class="profile-container">
            <div class="profile-header">
                <div class="profile-header-left">
                <div class="profile-avatar">
                        <?php 
                        $default_avatar = 'asset_admin/img/staff_img/team-2.jpg';
                        $avatar_path = !empty($user['KH_AVATAR']) ? 'img/avatars/' . $user['KH_AVATAR'] : $default_avatar;
                        ?>
                        <img src="<?php echo $avatar_path; ?>" 
                             alt="<?php echo htmlspecialchars($user['KH_TEN']); ?>"
                             onerror="this.src='asset_admin/img/staff_img/team-2.jpg'">
                    <div class="edit-avatar">
                            <i class="fa fa-camera"></i>
                        </div>
                    </div>
                    <div class="profile-info">
                        <h2 class="profile-name"><?php echo htmlspecialchars($user['KH_TEN']); ?></h2>
                        <p class="profile-email"><?php echo htmlspecialchars($user['KH_EMAIL']); ?></p>
                    </div>
                </div>
                <div class="profile-stats">
                    <?php
                    // Lấy số đơn hàng
                    $order_sql = "SELECT 
                        COUNT(*) as total_orders,
                        SUM(CASE WHEN TT_MA = 3 THEN 1 ELSE 0 END) as completed_orders,
                        SUM(HD_TONGTIEN) as total_spent
                        FROM hoa_don WHERE KH_MA = ?";
                    $order_stmt = $conn->prepare($order_sql);
                    $order_stmt->bind_param("i", $user_id);
                    $order_stmt->execute();
                    $order_stats = $order_stmt->get_result()->fetch_assoc();
                    ?>
                    <div class="stat-item">
                        <div class="stat-value"><?php echo $order_stats['total_orders']; ?></div>
                        <div class="stat-label">Tổng đơn hàng</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value"><?php echo $order_stats['completed_orders']; ?></div>
                        <div class="stat-label">Đơn thành công</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value"><?php echo number_format($order_stats['total_spent'], 0, ',', '.'); ?>đ</div>
                        <div class="stat-label">Tổng chi tiêu</div>
                    </div>
                </div>
            </div>

            <?php if (!empty($success_message)): ?>
            <div class="alert alert-success" role="alert">
                <i class="fa fa-check-circle me-2"></i>
                <?php echo htmlspecialchars($success_message); ?>
            </div>
            <?php endif; ?>

            <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <i class="fa fa-exclamation-circle me-2"></i>
                <?php echo htmlspecialchars($error_message); ?>
            </div>
            <?php endif; ?>

            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-profile-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-profile" type="button" role="tab">
                        <i class="fa fa-user me-2"></i>Thông tin cá nhân
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-password-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-password" type="button" role="tab">
                        <i class="fa fa-lock me-2"></i>Đổi mật khẩu
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-orders-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-orders" type="button" role="tab">
                        <i class="fa fa-shopping-bag me-2"></i>Lịch sử đơn hàng
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="pills-tabContent">
                <!-- Tab Thông tin cá nhân -->
                <div class="tab-pane fade show active" id="pills-profile" role="tabpanel">
                    <form method="post" action="" class="profile-form" name="update_profile">
                        <div class="profile-section">
                            <h4><i class="fa fa-info-circle"></i>Thông tin cơ bản</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Họ và tên</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-user"></i></span>
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="<?php echo htmlspecialchars($user['KH_TEN']); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                            <input type="email" class="form-control" id="email" name="email"
                                                value="<?php echo htmlspecialchars($user['KH_EMAIL']); ?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="profile-section">
                            <h4><i class="fa fa-phone"></i>Thông tin liên hệ</h4>
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Số điện thoại</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                            <input type="text" class="form-control" id="phone" name="phone"
                                                value="<?php echo htmlspecialchars($user['KH_SDT']); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-4">
                                        <label class="form-label fw-bold mb-3">
                                            <i class="fa fa-map-marker-alt me-2 text-primary"></i>Địa chỉ
                                        </label>
                                        <div class="address-section p-4 rounded-3 border">
                                            <div class="row g-4">
                                                <div class="col-12">
                                                    <div id="address-error" class="alert alert-danger d-none"></div>
                                                </div>
                                                
                                                <!-- Số nhà, tên đường -->
                                                <div class="col-12">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" id="street_address" name="street_address" 
                                                            placeholder="Nhập số nhà, tên đường" value="<?php 
                                                                $address_parts = explode(', ', $user['KH_DIACHI']);
                                                                echo htmlspecialchars($address_parts[0] ?? '');
                                                            ?>" required>
                                                        <label for="street_address">
                                                            <i class="fa fa-home me-1 text-muted"></i>Số nhà, tên đường
                                                        </label>
                                                    </div>
                                                </div>

                                                <!-- Địa chỉ hành chính -->
                                                <div class="col-12">
                                                    <div class="row g-3">
                                                        <div class="col-12">
                                                            <div class="form-floating">
                                                                <select class="form-select" id="province" name="province" required>
                                                                    <option value="">Chọn Tỉnh/Thành phố</option>
                                                                </select>
                                                                <label for="province">
                                                                    <i class="fa fa-city me-1 text-muted"></i>Tỉnh/Thành phố
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-floating">
                                                                <select class="form-select" id="district" name="district" required disabled>
                                                                    <option value="">Chọn Quận/Huyện</option>
                                                                </select>
                                                                <label for="district">
                                                                    <i class="fa fa-building me-1 text-muted"></i>Quận/Huyện
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-floating">
                                                                <select class="form-select" id="ward" name="ward" required disabled>
                                                                    <option value="">Chọn Phường/Xã</option>
                                                                </select>
                                                                <label for="ward">
                                                                    <i class="fa fa-map-pin me-1 text-muted"></i>Phường/Xã
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Địa chỉ đầy đủ -->
                                                <div class="col-12">
                                                    <div class="address-preview">
                                                        <div class="d-flex align-items-center mb-2">
                                                            <i class="fa fa-check-circle text-success me-2"></i>
                                                            <label class="form-label text-muted mb-0 small">Địa chỉ đầy đủ</label>
                                                        </div>
                                                        <div id="full-address-display" class="p-3 bg-light rounded-3 border">
                                                            <div class="d-flex align-items-start">
                                                                <i class="fa fa-info-circle text-primary me-3 mt-1"></i>
                                                                <span class="text-dark"><?php echo htmlspecialchars($user['KH_DIACHI']); ?></span>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" id="full_address" name="full_address" value="<?php echo htmlspecialchars($user['KH_DIACHI']); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="profile-section">
                            <h4><i class="fa fa-user-circle"></i>Thông tin khác</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="birthdate" class="form-label">Ngày sinh</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                            <input type="date" class="form-control" id="birthdate" name="birthdate"
                                                value="<?php echo $user['KH_NGAYSINH'] ? date('Y-m-d', strtotime($user['KH_NGAYSINH'])) : ''; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="gender" class="form-label">Giới tính</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-venus-mars"></i></span>
                                            <select class="form-control" id="gender" name="gender">
                                                <option value="">-- Chọn giới tính --</option>
                                                <option value="M" <?php if ($user['KH_GIOITINH'] == 'M') echo 'selected'; ?>>Nam</option>
                                                <option value="F" <?php if ($user['KH_GIOITINH'] == 'F') echo 'selected'; ?>>Nữ</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" name="update_profile" class="btn btn-update">
                                <i class="fa fa-save me-2"></i>Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tab Đổi mật khẩu -->
                <div class="tab-pane fade" id="pills-password" role="tabpanel">
                    <div class="profile-section">
                        <h4><i class="fa fa-lock"></i>Đổi mật khẩu</h4>
                        <form method="post" action="" class="profile-form">
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-key"></i></span>
                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                    <span class="password-toggle" onclick="togglePassword('current_password')">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">Mật khẩu mới</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                                    <span class="password-toggle" onclick="togglePassword('new_password')">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Xác nhận mật khẩu mới</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    <span class="password-toggle" onclick="togglePassword('confirm_password')">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" name="change_password" class="btn btn-update">
                                    <i class="fa fa-key me-2"></i>Đổi mật khẩu
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tab Lịch sử đơn hàng -->
                <div class="tab-pane fade" id="pills-orders" role="tabpanel">
                    <div class="profile-section">
                        <h4><i class="fa fa-history"></i>Lịch sử đơn hàng gần đây</h4>
                        <div class="order-history">
                            <?php
                            // Lấy lịch sử đơn hàng
                            $orders_sql = "SELECT hd.*, tt.TT_TEN as trangthai 
                                         FROM hoa_don hd 
                                         JOIN trang_thai tt ON hd.TT_MA = tt.TT_MA 
                                         WHERE hd.KH_MA = ? 
                                         ORDER BY hd.HD_NGAYLAP DESC 
                                         LIMIT 5";
                            $orders_stmt = $conn->prepare($orders_sql);
                            $orders_stmt->bind_param("i", $user_id);
                            $orders_stmt->execute();
                            $orders_result = $orders_stmt->get_result();

                            while ($order = $orders_result->fetch_assoc()):
                                $status_class = '';
                                switch($order['TT_MA']) {
                                    case 0: $status_class = 'status-pending'; break;
                                    case 1: case 2: $status_class = 'status-processing'; break;
                                    case 3: $status_class = 'status-completed'; break;
                                    case 4: case 5: $status_class = 'status-cancelled'; break;
                                }
                            ?>
                            <div class="order-card">
                                <div class="order-header">
                                    <span class="order-id">#<?php echo $order['HD_STT']; ?></span>
                                    <span class="order-date">
                                        <i class="fa fa-calendar me-1"></i>
                                        <?php echo date('d/m/Y', strtotime($order['HD_NGAYLAP'])); ?>
                                    </span>
                                    <span class="order-status <?php echo $status_class; ?>">
                                        <?php echo $order['trangthai']; ?>
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted mb-1">Tổng tiền:</div>
                                        <div class="fw-bold"><?php echo number_format($order['HD_TONGTIEN'], 0, ',', '.'); ?>đ</div>
                                    </div>
                                    <a href="order_confirmation.php?id=<?php echo $order['HD_STT']; ?>" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fa fa-eye me-1"></i>Xem chi tiết
                                    </a>
                                </div>
                            </div>
                            <?php endwhile; ?>
                            
                            <div class="text-center mt-4">
                                <a href="my_orders.php" class="btn btn-outline-primary">
                                    <i class="fa fa-list me-2"></i>Xem tất cả đơn hàng
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="js/profile-address.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Thêm xử lý cho form submit
            const form = document.querySelector('form[name="update_profile"]');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (!window.profileAddressManager.isAddressComplete()) {
                        e.preventDefault();
                        alert('Vui lòng chọn đầy đủ thông tin địa chỉ');
                        return false;
                    }
                });
            }

            // Animation cho các section khi chuyển tab
            document.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', function() {
                    document.querySelectorAll('.profile-section').forEach(section => {
                        section.style.animation = 'fadeIn 0.5s ease';
                    });
                });
            });
        });

        // Chức năng hiện/ẩn mật khẩu
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
            if (input) {
        const icon = input.nextElementSibling.querySelector('i');
                if (icon) {
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
            }
    }
    </script>

    <?php $conn->close(); ?>
</body>
</html>