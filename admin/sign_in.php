<?php
// Bắt đầu session
session_start();

// Nếu đã đăng nhập, chuyển hướng đến dashboard
if (isset($_SESSION['NV_MA'])) {
    header('Location: dashboard.php');
    exit;
}

// Kết nối database
require_once 'connect.php';

// Hàm log
function writeLog($message, $type = 'error') {
    $logFile = __DIR__ . '/login.log';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[{$timestamp}] [{$type}] {$message}\n";
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate input
        if (empty($_POST['username']) || empty($_POST['password'])) {
            throw new Exception("Vui lòng nhập đầy đủ thông tin đăng nhập");
        }

        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        // Kiểm tra thông tin đăng nhập
        $sql = "SELECT nv.NV_MA, nv.NV_TEN, nv.NV_QUYEN, nv.NV_AVATAR, nv.NV_MATKHAU, nv.NV_TRANGTHAI, 
                       nv.CV_MA, cv.CV_TEN, cv.CV_QUYEN, cv.CV_TRANGTHAI
                FROM nhan_vien nv
                LEFT JOIN chuc_vu cv ON nv.CV_MA = cv.CV_MA
                WHERE nv.NV_TENDANGNHAP = ?";
        
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            writeLog("Lỗi SQL: " . mysqli_error($conn));
            throw new Exception("Lỗi hệ thống, vui lòng thử lại sau");
        }

        mysqli_stmt_bind_param($stmt, "s", $username);
        if (!mysqli_stmt_execute($stmt)) {
            writeLog("Lỗi execute: " . mysqli_stmt_error($stmt));
            throw new Exception("Lỗi hệ thống, vui lòng thử lại sau");
        }

        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            // Log thông tin debug
            writeLog("Thông tin đăng nhập: " . json_encode($row, JSON_UNESCAPED_UNICODE), 'debug');

            // Kiểm tra trạng thái nhân viên
            if ($row['NV_TRANGTHAI'] != 1) {
                writeLog("Tài khoản {$username} đã bị vô hiệu hóa");
                throw new Exception("Tài khoản đã bị vô hiệu hóa");
            }

            // Kiểm tra trạng thái chức vụ
            if ($row['CV_MA'] && $row['CV_TRANGTHAI'] != 1) {
                writeLog("Chức vụ của tài khoản {$username} đã bị vô hiệu hóa");
                throw new Exception("Chức vụ của tài khoản đã bị vô hiệu hóa");
            }

            // Verify password
            if ($password === $row['NV_MATKHAU']) {
                // Lưu thông tin vào session
                $_SESSION['NV_MA'] = $row['NV_MA'];
                $_SESSION['NV_TEN'] = $row['NV_TEN'];
                $_SESSION['NV_QUYEN'] = $row['NV_QUYEN'];
                $_SESSION['NV_AVATAR'] = $row['NV_AVATAR'];
                
                // Lưu thông tin chức vụ
                if ($row['CV_MA']) {
                    $_SESSION['CV_MA'] = $row['CV_MA'];
                    $_SESSION['CV_TEN'] = $row['CV_TEN'];
                    $_SESSION['CV_QUYEN'] = $row['CV_QUYEN'] ? json_decode($row['CV_QUYEN'], true) : [];
                }
                
                // Log successful login
                writeLog("Đăng nhập thành công: {$username} (Quyền: {$row['NV_QUYEN']}, Chức vụ: {$row['CV_TEN']})", 'success');
                
                // Chuyển hướng dựa trên quyền
                if ($row['NV_QUYEN'] === 'ADMIN') {
                header('Location: dashboard.php');
                } else {
                    // Nhân viên luôn được chuyển đến trang khách hàng
                    header('Location: custommer.php');
                }
                exit;
            } else {
                writeLog("Mật khẩu không đúng cho tài khoản: {$username}");
            }
        } else {
            writeLog("Không tìm thấy tài khoản: {$username}");
        }

        // Log failed login attempt
        if (isset($row['NV_MA'])) {
            $nv_ma = $row['NV_MA'];
            $log_sql = "INSERT INTO nhan_vien_login_history (NV_MA, LOGIN_TIME, LOGIN_IP, LOGIN_STATUS) 
                       VALUES (?, NOW(), ?, 'FAILED')";
            $log_stmt = mysqli_prepare($conn, $log_sql);
            mysqli_stmt_bind_param($log_stmt, "ss", $nv_ma, $_SERVER['REMOTE_ADDR']);
            mysqli_stmt_execute($log_stmt);
        }

        throw new Exception("Tên đăng nhập hoặc mật khẩu không đúng");

    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Đăng nhập - Quản lý cửa hàng phân bón</title>
    <!-- Favicon -->
    <link href="../asset_admin/img/favicon.png" rel="icon" type="image/png">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <!-- Icons -->
    <link href="../asset_admin/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../asset_admin/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- CSS Files -->
    <link id="pagestyle" href="../asset_admin/css/material-dashboard.css?v=3.0.0" rel="stylesheet" />
    <style>
        .input-group.input-group-outline.is-focused .form-label,
        .input-group.input-group-outline.is-filled .form-label {
            transform: translateY(-20px) scale(0.85);
            background: white;
            padding: 0 5px;
        }
        .input-group.input-group-outline .form-label {
            transition: all 0.2s ease;
        }
    </style>
</head>

<body class="bg-gray-200">
    <main class="main-content mt-0">
        <div class="page-header align-items-start min-vh-100" style="background-image: url('../asset_admin/img/bg-pricing.jpg');">
            <span class="mask bg-gradient-dark opacity-6"></span>
            <div class="container my-auto">
                <div class="row">
                    <div class="col-lg-4 col-md-8 col-12 mx-auto">
                        <div class="card z-index-0 fadeIn3 fadeInBottom">
                            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                                    <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Đăng nhập</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php if (isset($error_message)): ?>
                                    <div class="alert alert-danger text-white" role="alert">
                                        <?php echo htmlspecialchars($error_message); ?>
                                    </div>
                                <?php endif; ?>

                                <form role="form" class="text-start" method="POST" autocomplete="off">
                                    <div class="input-group input-group-outline mb-4">
                                        <label class="form-label">Tên đăng nhập</label>
                                        <input type="text" name="username" class="form-control" required>
                                    </div>
                                    <div class="input-group input-group-outline mb-4">
                                        <label class="form-label">Mật khẩu</label>
                                        <input type="password" name="password" class="form-control" required>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2">Đăng nhập</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!--   Core JS Files   -->
    <script src="../asset_admin/js/core/popper.min.js"></script>
    <script src="../asset_admin/js/core/bootstrap.min.js"></script>
    <script src="../asset_admin/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../asset_admin/js/plugins/smooth-scrollbar.min.js"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }

        // Xử lý animation cho input
        document.querySelectorAll('.input-group-outline input').forEach(function(input) {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('is-focused');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('is-focused');
                if (this.value) {
                    this.parentElement.classList.add('is-filled');
                } else {
                    this.parentElement.classList.remove('is-filled');
                }
            });

            // Check initial value
            if (input.value) {
                input.parentElement.classList.add('is-filled');
            }
        });
    </script>
</body>
</html>