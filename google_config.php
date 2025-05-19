<?php
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

define('GOOGLE_CLIENT_ID', $_ENV['GOOGLE_CLIENT_ID']);
define('GOOGLE_CLIENT_SECRET', $_ENV['GOOGLE_CLIENT_SECRET']);

/*
Hướng dẫn lấy Client ID và Client Secret:
1. Truy cập https://console.cloud.google.com/
2. Tạo dự án mới hoặc chọn dự án hiện có
3. Vào mục "APIs & Services" > "Credentials"
4. Nhấp vào "Create Credentials" > "OAuth client ID"
5. Chọn "Web application"
6. Điền tên cho ứng dụng
7. Thêm "http://localhost" vào "Authorized JavaScript origins"
8. Thêm "http://localhost/LVTN_PhanBon/google_callback.php" vào "Authorized redirect URIs"
9. Nhấp "Create" và sao chép Client ID và Client Secret vào đây
*/

// Tạo URL đăng nhập Google
function getGoogleLoginUrl() {
    global $google_client_id, $google_redirect_url;
    
    // Các quyền cần thiết
    $scopes = urlencode('email profile');
    
    // URL đăng nhập Google
    $auth_url = 'https://accounts.google.com/o/oauth2/auth';
    $auth_url .= '?client_id=' . $google_client_id;
    $auth_url .= '&redirect_uri=' . urlencode($google_redirect_url);
    $auth_url .= '&response_type=code';
    $auth_url .= '&scope=' . $scopes;
    $auth_url .= '&access_type=offline';
    $auth_url .= '&prompt=consent';
    
    return $auth_url;
}
?>