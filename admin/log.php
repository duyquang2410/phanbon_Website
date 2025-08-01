<?php

require 'connect.php';
session_start();

$username = mysqli_real_escape_string($conn, $_POST['usname']);
$password = mysqli_real_escape_string($conn, $_POST['pass']);

// Kiểm tra thông tin đăng nhập
$sql = "SELECT NV_MA, NV_TEN, NV_QUYEN, NV_AVATAR FROM nhan_vien WHERE NV_TENDANGNHAP = ? AND NV_MATKHAU = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ss", $username, $password);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    // Lưu thông tin vào session
    $_SESSION['NV_MA'] = $row['NV_MA'];
    $_SESSION['NV_TEN'] = $row['NV_TEN'];
    $_SESSION['NV_QUYEN'] = $row['NV_QUYEN'];
    $_SESSION['NV_AVATAR'] = $row['NV_AVATAR'];
    
    // Chuyển hướng đến trang dashboard
    header('Location: dashboard.php');
    exit;
} else {
    $message = "Tài khoản hoặc mật khẩu không đúng. Vui lòng thử lại!";
    echo "<script type='text/javascript'>alert('$message');</script>";
    header('Refresh: 0;url=sign_in.php');
}

$conn->close();
?>