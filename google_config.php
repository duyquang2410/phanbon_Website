<?php
// Cấu hình Google OAuth
$google_client_id = '618832015091-jj92aq3b02j048e0ete0sufih0krq8ft.apps.googleusercontent.com'; // Client ID từ Google Cloud Console
$google_client_secret = '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'; // Client Secret từ Google Cloud Console
$google_redirect_url = 'http://localhost/web/phanbon_Website/google_callback.php'; // URL callback

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