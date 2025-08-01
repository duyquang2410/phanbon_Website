<?php
// Session Configuration - MUST be before any session_start() calls
if (session_status() == PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
}

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
    
    try {
        error_log('Đang tải cấu hình...');
        
        // Kiểm tra tính hợp lệ của các hằng số cấu hình
        if (!defined('PICK_PROVINCE') || !is_numeric(PICK_PROVINCE) || PICK_PROVINCE <= 0) {
            error_log('Lỗi: Cấu hình tỉnh/thành phố không hợp lệ');
            throw new Exception('Cấu hình tỉnh/thành phố không hợp lệ');
        }
        if (!defined('PICK_DISTRICT') || !is_numeric(PICK_DISTRICT) || PICK_DISTRICT <= 0) {
            error_log('Lỗi: Cấu hình quận/huyện không hợp lệ');
            throw new Exception('Cấu hình quận/huyện không hợp lệ');
        }
        if (!defined('PICK_WARD') || !is_numeric(PICK_WARD) || PICK_WARD <= 0) {
            error_log('Lỗi: Cấu hình phường/xã không hợp lệ');
            throw new Exception('Cấu hình phường/xã không hợp lệ');
        }
        if (!defined('PICK_ADDRESS') || empty(PICK_ADDRESS)) {
            error_log('Lỗi: Cấu hình địa chỉ không hợp lệ');
            throw new Exception('Cấu hình địa chỉ không hợp lệ');
        }
        if (!defined('DEFAULT_WEIGHT_PER_ITEM') || !is_numeric(DEFAULT_WEIGHT_PER_ITEM) || DEFAULT_WEIGHT_PER_ITEM <= 0) {
            error_log('Lỗi: Cấu hình trọng lượng mặc định không hợp lệ');
            throw new Exception('Cấu hình trọng lượng mặc định không hợp lệ');
        }
        if (!defined('DEFAULT_LENGTH') || !is_numeric(DEFAULT_LENGTH) || DEFAULT_LENGTH <= 0) {
            error_log('Lỗi: Cấu hình chiều dài mặc định không hợp lệ');
            throw new Exception('Cấu hình chiều dài mặc định không hợp lệ');
        }
        if (!defined('DEFAULT_WIDTH') || !is_numeric(DEFAULT_WIDTH) || DEFAULT_WIDTH <= 0) {
            error_log('Lỗi: Cấu hình chiều rộng mặc định không hợp lệ');
            throw new Exception('Cấu hình chiều rộng mặc định không hợp lệ');
        }
        if (!defined('DEFAULT_HEIGHT') || !is_numeric(DEFAULT_HEIGHT) || DEFAULT_HEIGHT <= 0) {
            error_log('Lỗi: Cấu hình chiều cao mặc định không hợp lệ');
            throw new Exception('Cấu hình chiều cao mặc định không hợp lệ');
        }

        // Trả về cấu hình
        $config = [
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
        ];

        error_log('Cấu hình hợp lệ: ' . json_encode($config));

        echo json_encode([
            'success' => true,
            'data' => $config
        ]);
    } catch (Exception $e) {
        error_log('Lỗi khi tải cấu hình: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => [
                'message' => $e->getMessage()
            ]
        ]);
    }
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
define('DB_NAME', 'phanbon');

// ViettelPost API Configuration
define('VIETTEL_POST_API_TOKEN', '51C1E0E74B4F78A912EEE41416247CF5');
define('VIETTEL_POST_API_URL', 'https://partner.viettelpost.vn/v2');

// Gemini API Configuration
define('GEMINI_API_KEY', 'AIzaSyA1_gVzoN0nazAhhdvRKy__9CL4kL3Cbvo');
define('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent');

// Autoload Composer dependencies
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Initialize Guzzle client
use GuzzleHttp\Client;
$client = new Client();

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/error.log');

return true;
?> 