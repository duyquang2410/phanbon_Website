<?php
session_start();

// Include Google config
require_once 'google_config.php';

// Kiểm tra nếu đã đăng nhập thì chuyển hướng về trang chủ
if(isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Xử lý đăng nhập
if(isset($_POST['login'])) {
    include 'connect.php';
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM khach_hang WHERE KH_TENDANGNHAP = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Kiểm tra mật khẩu (lưu ý: trong thực tế nên sử dụng password_hash và password_verify)
        if($password == $user['KH_MATKHAU']) {
            $_SESSION['user_id'] = $user['KH_MA'];
            $_SESSION['user_name'] = $user['KH_TEN'];
            $_SESSION['user_avatar'] = $user['KH_AVATAR'];
            $_SESSION['logged_in'] = true;
            header("Location: index.php");
            exit();
        } else {
            $error = "Mật khẩu không đúng!";
        }
    } else {
        $error = "Tên đăng nhập không tồn tại!";
    }
    
    $stmt->close();
    $conn->close();
}

// Xử lý đăng ký
if(isset($_POST['register'])) {
    include 'connect.php';
    
    $name = $_POST['reg_name'];
    $email = $_POST['reg_email'];
    $phone = $_POST['reg_phone'];
    $address = $_POST['reg_address'];
    $username = $_POST['reg_username'];
    $password = $_POST['reg_password'];
    $confirm_password = $_POST['reg_confirm_password'];
    $gender = $_POST['reg_gender'];
    $birthdate = $_POST['reg_birthdate'];
    
    // Kiểm tra mật khẩu khớp nhau
    if($password !== $confirm_password) {
        $reg_error = "Mật khẩu xác nhận không khớp!";
    } else {
        // Kiểm tra tên đăng nhập đã tồn tại chưa
        $check_sql = "SELECT * FROM khach_hang WHERE KH_TENDANGNHAP = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if($check_result->num_rows > 0) {
            $reg_error = "Tên đăng nhập đã được sử dụng!";
        } else {
            // Kiểm tra email đã tồn tại chưa
            $check_email_sql = "SELECT * FROM khach_hang WHERE KH_EMAIL = ?";
            $check_email_stmt = $conn->prepare($check_email_sql);
            $check_email_stmt->bind_param("s", $email);
            $check_email_stmt->execute();
            $check_email_result = $check_email_stmt->get_result();
            
            if($check_email_result->num_rows > 0) {
                $reg_error = "Email đã được sử dụng!";
                $check_email_stmt->close();
            } else {
                // Thêm người dùng mới
                $current_date = date("Y-m-d");
                $default_avatar = "default-avatar.jpg";
                
                $insert_sql = "INSERT INTO khach_hang (KH_TEN, KH_DIACHI, KH_SDT, KH_NGAYSINH, KH_EMAIL, KH_GIOITINH, KH_NGAYDK, KH_TENDANGNHAP, KH_MATKHAU, KH_AVATAR) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $insert_stmt = $conn->prepare($insert_sql);
                $insert_stmt->bind_param("ssssssssss", $name, $address, $phone, $birthdate, $email, $gender, $current_date, $username, $password, $default_avatar);
                
                if($insert_stmt->execute()) {
                    $success = "Đăng ký thành công! Vui lòng đăng nhập.";
                } else {
                    $reg_error = "Đăng ký thất bại: " . $conn->error;
                }
                
                $insert_stmt->close();
                $check_email_stmt->close();
            }
        }
        
        $check_stmt->close();
    }
    
    $conn->close();
}

// Lấy URL đăng nhập Google
$google_login_url = getGoogleLoginUrl();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng Nhập - Plants Shop</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/global.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <style>
    body {
        background-color: #f8f9fa;
        font-family: 'Poppins', sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        padding: 20px 0;
    }

    .form-container {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
        padding: 30px;
        text-align: center;
    }

    .form-side {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .form-title {
        font-weight: 600;
        margin-bottom: 20px;
        color: #333;
    }

    .form-group {
        position: relative;
        margin-bottom: 15px;
        text-align: left;
    }

    .form-control {
        height: 45px;
        border-radius: 5px;
        border: 1px solid #ddd;
        padding-left: 40px;
        width: 100%;
        box-sizing: border-box;
    }

    .form-control:focus {
        box-shadow: none;
        border-color: #4CAF50;
    }

    .input-group-text {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        background-color: transparent;
        border: none;
        padding: 0;
        pointer-events: none;
    }

    .btn-login {
        background-color: #4CAF50;
        color: white;
        border: none;
        height: 45px;
        border-radius: 5px;
        font-weight: 600;
        width: 100%;
        margin-top: 10px;
    }

    .btn-login:hover {
        background-color: #45a049;
    }

    .nav-tabs {
        border-bottom: none;
        justify-content: center;
        margin-bottom: 20px;
    }

    .nav-tabs .nav-link {
        border: 1px solid #ddd;
        border-radius: 5px;
        color: #666;
        font-weight: 600;
        padding: 10px 20px;
        margin: 0 5px;
    }

    .nav-tabs .nav-link.active {
        color: #4CAF50;
        border: 1px solid #4CAF50;
        background-color: transparent;
    }

    .social-login {
        margin-top: 20px;
        text-align: center;
    }

    .social-btn {
        display: inline-block;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin: 0 5px;
        font-size: 20px;
        line-height: 40px;
        color: white;
    }

    .facebook {
        background-color: #3b5998;
    }

    .google {
        background-color: #dd4b39;
    }

    .twitter {
        background-color: #55acee;
    }

    .divider {
        display: flex;
        align-items: center;
        margin: 20px 0;
    }

    .divider-line {
        flex-grow: 1;
        height: 1px;
        background-color: #ddd;
    }

    .divider-text {
        padding: 0 15px;
        color: #666;
        font-size: 14px;
    }

    .form-check-label {
        color: #666;
        font-size: 14px;
    }

    .forgot-password {
        color: #4CAF50;
        text-decoration: none;
        font-size: 14px;
    }

    .forgot-password:hover {
        text-decoration: underline;
    }

    .logo {
        text-align: center;
        margin-bottom: 20px;
    }

    .logo a {
        text-decoration: none;
        color: #333;
        font-size: 24px;
        font-weight: 700;
    }

    .logo i {
        color: #4CAF50;
    }

    .logo span {
        color: #e3ae03;
    }

    .alert {
        border-radius: 5px;
        font-size: 14px;
        margin-bottom: 15px;
    }

    .home-link {
        display: block;
        text-align: center;
        margin-top: 20px;
        text-decoration: none;
        color: #666;
        font-size: 14px;
    }

    .home-link:hover {
        color: #4CAF50;
    }

    .row-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .google-btn {
        background-color: white;
        color: #757575;
        font-weight: 600;
        border: 1px solid #ddd;
        height: 45px;
        border-radius: 5px;
        width: 100%;
        margin-top: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }

    .google-btn:hover {
        background-color: #f5f5f5;
        color: #555;
    }

    .google-btn img {
        width: 20px;
        margin-right: 10px;
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="form-container">
                    <!-- Display login requirement message if set -->
                    <?php if(isset($_SESSION['login_required_message'])): ?>
                    <div class="alert alert-warning" role="alert">
                        <i class="fa fa-exclamation-circle me-1"></i>
                        <?php echo $_SESSION['login_required_message']; ?>
                        <?php unset($_SESSION['login_required_message']); ?>
                    </div>
                    <?php endif; ?>

                    <!-- Display error message if set -->
                    <?php if(isset($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Display success message if set -->
                    <?php if(isset($success)): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $success; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Display login error if set -->
                    <?php if(isset($_SESSION['login_error'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $_SESSION['login_error']; ?>
                        <?php unset($_SESSION['login_error']); ?>
                    </div>
                    <?php endif; ?>

                    <div class="form-side">
                        <div class="logo">
                            <a href="index.php">Cây Trồng <i class="fa fa-leaf align-middle"></i> <span>Shop</span></a>
                        </div>

                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="login-tab" data-bs-toggle="tab"
                                    data-bs-target="#login" type="button" role="tab" aria-controls="login"
                                    aria-selected="true">Đăng Nhập</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="register-tab" data-bs-toggle="tab"
                                    data-bs-target="#register" type="button" role="tab" aria-controls="register"
                                    aria-selected="false">Đăng Ký</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTabContent">
                            <!-- Đăng nhập form -->
                            <div class="tab-pane fade show active" id="login" role="tabpanel"
                                aria-labelledby="login-tab">
                                <h4 class="form-title">Đăng nhập vào tài khoản của bạn</h4>

                                <form method="post" action="">
                                    <div class="form-group">
                                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                                        <input type="text" class="form-control" id="username" name="username"
                                            placeholder="Tên đăng nhập" required>
                                    </div>
                                    <div class="form-group">
                                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                        <input type="password" class="form-control" id="password" name="password"
                                            placeholder="Mật khẩu" required>
                                    </div>
                                    <div class="row-options">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="remember"
                                                name="remember">
                                            <label class="form-check-label" for="remember">
                                                Ghi nhớ đăng nhập
                                            </label>
                                        </div>
                                        <a href="#" class="forgot-password">Quên mật khẩu?</a>
                                    </div>
                                    <button type="submit" name="login" class="btn btn-login">Đăng Nhập</button>
                                </form>

                                <div class="divider">
                                    <div class="divider-line"></div>
                                    <div class="divider-text">hoặc đăng nhập với</div>
                                    <div class="divider-line"></div>
                                </div>

                                <!-- Đăng nhập bằng Google -->
                                <a href="<?php echo $google_login_url; ?>" class="social-btn google">
                                    <i class="fa fa-google"></i>
                                </a>





                            </div>

                            <!-- Đăng ký form -->
                            <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="register-tab">
                                <h4 class="form-title">Tạo tài khoản mới</h4>

                                <?php if(isset($reg_error)): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $reg_error; ?>
                                </div>
                                <?php endif; ?>

                                <form method="post" action="">
                                    <div class="form-group">
                                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                                        <input type="text" class="form-control" id="reg_name" name="reg_name"
                                            placeholder="Họ và tên" required>
                                    </div>
                                    <div class="form-group">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                        <input type="email" class="form-control" id="reg_email" name="reg_email"
                                            placeholder="Email" required>
                                    </div>
                                    <div class="form-group">
                                        <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                        <input type="text" class="form-control" id="reg_phone" name="reg_phone"
                                            placeholder="Số điện thoại" required>
                                    </div>
                                    <div class="form-group">
                                        <span class="input-group-text"><i class="fa fa-map-marker"></i></span>
                                        <input type="text" class="form-control" id="reg_address" name="reg_address"
                                            placeholder="Địa chỉ" required>
                                    </div>
                                    <div class="form-group">
                                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                        <input type="date" class="form-control" id="reg_birthdate" name="reg_birthdate"
                                            placeholder="dd/mm/yyyy">
                                    </div>
                                    <div class="form-group">
                                        <span class="input-group-text"><i class="fa fa-venus-mars"></i></span>
                                        <select class="form-control" id="reg_gender" name="reg_gender">
                                            <option value="">-- Chọn giới tính --</option>
                                            <option value="M">Nam</option>
                                            <option value="F">Nữ</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <span class="input-group-text"><i class="fa fa-user-circle"></i></span>
                                        <input type="text" class="form-control" id="reg_username" name="reg_username"
                                            placeholder="Tên đăng nhập" required>
                                    </div>
                                    <div class="form-group">
                                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                        <input type="password" class="form-control" id="reg_password"
                                            name="reg_password" placeholder="Mật khẩu" required>
                                    </div>
                                    <div class="form-group">
                                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                        <input type="password" class="form-control" id="reg_confirm_password"
                                            name="reg_confirm_password" placeholder="Xác nhận mật khẩu" required>
                                    </div>
                                    <button type="submit" name="register" class="btn btn-login">Đăng Ký</button>
                                </form>
                            </div>
                        </div>

                        <a href="index.php" class="home-link">
                            <i class="fa fa-arrow-left me-2"></i> Quay lại trang chủ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>