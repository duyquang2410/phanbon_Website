<?php
session_start();

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

// Xử lý cập nhật thông tin
if (isset($_POST['update_profile'])) {
    // Kiểm tra CSRF token (nếu có, cần triển khai thêm)
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING);
    $birthdate = filter_input(INPUT_POST, 'birthdate', FILTER_SANITIZE_STRING);

    // Kiểm tra định dạng email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Email không hợp lệ!";
        goto end_update;
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
    <script src="js/bootstrap.bundle.min.js"></script>
    <style>
    .profile-container {
        max-width: 800px;
        margin: 30px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .profile-header {
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }

    .profile-header h3 {
        color: #4CAF50;
        margin-bottom: 10px;
    }

    .profile-form label {
        font-weight: 500;
        margin-bottom: 5px;
    }

    .profile-form .form-control {
        margin-bottom: 15px;
    }

    .btn-update {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-weight: 600;
    }

    .btn-update:hover {
        background-color: #45a049;
        color: white;
    }

    .nav-pills .nav-link {
        color: #666;
        border-radius: 0;
        padding: 15px 20px;
    }

    .nav-pills .nav-link.active {
        background-color: #4CAF50;
        color: white;
    }

    .tab-content {
        padding: 20px;
    }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <div class="profile-container">
            <div class="profile-header">
                <h3>Thông Tin Tài Khoản</h3>
                <p>Quản lý thông tin cá nhân của bạn</p>
            </div>

            <?php if (!empty($success_message)): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
            <?php endif; ?>

            <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
            <?php endif; ?>

            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-profile-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile"
                        aria-selected="true">Thông Tin Cá Nhân</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-password-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-password" type="button" role="tab" aria-controls="pills-password"
                        aria-selected="false">Đổi Mật Khẩu</button>
                </li>
            </ul>

            <div class="tab-content" id="pills-tabContent">
                <!-- Tab Thông tin cá nhân -->
                <div class="tab-pane fade show active" id="pills-profile" role="tabpanel"
                    aria-labelledby="pills-profile-tab">
                    <form method="post" action="" class="profile-form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Họ và tên</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="<?php echo htmlspecialchars($user['KH_TEN']); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="<?php echo htmlspecialchars($user['KH_EMAIL']); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Số điện thoại</label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        value="<?php echo htmlspecialchars($user['KH_SDT']); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="birthdate" class="form-label">Ngày sinh</label>
                                    <input type="date" class="form-control" id="birthdate" name="birthdate"
                                        value="<?php echo htmlspecialchars($user['KH_NGAYSINH']); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="gender" class="form-label">Giới tính</label>
                                    <select class="form-control" id="gender" name="gender">
                                        <option value="">-- Chọn giới tính --</option>
                                        <option value="M" <?php if ($user['KH_GIOITINH'] == 'M') echo 'selected'; ?>>Nam
                                        </option>
                                        <option value="F" <?php if ($user['KH_GIOITINH'] == 'F') echo 'selected'; ?>>Nữ
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Địa chỉ</label>
                                    <textarea class="form-control" id="address" name="address"
                                        rows="3"><?php echo htmlspecialchars($user['KH_DIACHI']); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" name="update_profile" class="btn btn-update">Cập Nhật Thông
                                Tin</button>
                        </div>
                    </form>
                </div>

                <!-- Tab Đổi mật khẩu -->
                <div class="tab-pane fade" id="pills-password" role="tabpanel" aria-labelledby="pills-password-tab">
                    <form method="post" action="" class="profile-form">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                            <input type="password" class="form-control" id="current_password" name="current_password"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Mật khẩu mới</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Xác nhận mật khẩu mới</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                required>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" name="change_password" class="btn btn-update">Đổi Mật Khẩu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <?php
    // Đóng kết nối sau khi tất cả các include đã hoàn tất
    $conn->close();
    ?>
</body>

</html>