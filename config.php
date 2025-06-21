<?php
// Cấu hình chung
date_default_timezone_set('Asia/Ho_Chi_Minh');
ini_set('default_charset', 'UTF-8');

// Cấu hình logging
define('LOG_DIR', __DIR__ . '/logs');
define('API_LOG_FILE', LOG_DIR . '/api.log');
define('ERROR_LOG_FILE', LOG_DIR . '/error.log');
define('SHIPPING_LOG_FILE', LOG_DIR . '/shipping.log');
define('CHECKOUT_LOG_FILE', LOG_DIR . '/checkout.log');
define('ORDER_LOG_FILE', LOG_DIR . '/order_processing.log');
define('CLIENT_LOG_FILE', LOG_DIR . '/client.log');
define('PROMO_LOG_FILE', LOG_DIR . '/promo_errors.log');

// Tạo thư mục logs nếu chưa tồn tại
if (!file_exists(LOG_DIR)) {
    mkdir(LOG_DIR, 0777, true);
}

// Cấu hình encoding
mb_internal_encoding('UTF-8');

// Return config array for JavaScript
if (isset($_GET['get_config']) && $_GET['get_config'] === 'true') {
    header('Content-Type: application/json');
    echo json_encode([
        'pick_province' => PICK_PROVINCE,
        'pick_district' => PICK_DISTRICT,
        'pick_ward' => PICK_WARD,
        'pick_address' => PICK_ADDRESS,
        'default_weight' => DEFAULT_WEIGHT_PER_ITEM,
        'default_dimensions' => [
            'length' => DEFAULT_LENGTH,
            'width' => DEFAULT_WIDTH,
            'height' => DEFAULT_HEIGHT
        ]
    ]);
    exit;
}

// Shipping Configuration - Cấu hình địa chỉ lấy hàng
define('PICK_PROVINCE', 5); // Cần Thơ
define('PICK_DISTRICT', 82); // Quận Ninh Kiều
define('PICK_WARD', 1276); // Phường An Hòa
define('PICK_ADDRESS', 'Phường An Hòa, Quận Ninh Kiều, Thành phố Cần Thơ');
define('DEFAULT_WEIGHT_PER_ITEM', 1000); // gram
define('DEFAULT_LENGTH', 15); // cm
define('DEFAULT_WIDTH', 15); // cm
define('DEFAULT_HEIGHT', 15); // cm

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'phan_bon_test');

// ViettelPost API Configuration
define('VIETTEL_POST_API_TOKEN', '51C1E0E74B4F78A912EEE41416247CF5');
define('VIETTEL_POST_API_URL', 'https://partner.viettelpost.vn/v2');

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/error.log');

// Session Configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));

return true;
?> 