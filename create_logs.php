<?php
// Đường dẫn đến thư mục logs
$logDir = __DIR__ . '/logs';

// Tạo thư mục logs nếu chưa tồn tại
if (!file_exists($logDir)) {
    mkdir($logDir, 0777, true);
}

class Logger {
    private $logFile;
    private static $instances = [];

    public function __construct($logFile) {
        $this->logFile = $logFile;
        $this->ensureLogFileExists();
    }

    private function ensureLogFileExists() {
        $logDir = dirname($this->logFile);
        if (!file_exists($logDir)) {
            mkdir($logDir, 0777, true);
        }
        if (!file_exists($this->logFile)) {
            touch($this->logFile);
            chmod($this->logFile, 0666);
        }
    }

    public static function getInstance($logFile = 'logs/app.log') {
        $logFile = rtrim($logFile, '/\\');
        if (!isset(self::$instances[$logFile])) {
            self::$instances[$logFile] = new self($logFile);
        }
        return self::$instances[$logFile];
    }

    public function log($message, $data = null, $type = 'INFO') {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp][$type] $message";
        
        if ($data !== null) {
            if (is_array($data) || is_object($data)) {
                $logMessage .= "\nData: " . print_r($data, true);
            } else {
                $logMessage .= "\nData: $data";
            }
        }
        
        $logMessage .= "\n" . str_repeat('-', 80) . "\n";
        
        file_put_contents($this->logFile, $logMessage, FILE_APPEND);
    }

    public function error($message, $data = null) {
        $this->log($message, $data, 'ERROR');
    }

    public function info($message, $data = null) {
        $this->log($message, $data, 'INFO');
    }

    public function warning($message, $data = null) {
        $this->log($message, $data, 'WARNING');
    }
}

function getViettelPostLogger() {
    static $logger = null;
    if ($logger === null) {
        $logger = Logger::getInstance(__DIR__ . '/logs/viettelpost.log');
    }
    return $logger;
}

function logViettelPostApi($endpoint, $request = null, $response = null, $error = null) {
    $logger = getViettelPostLogger();
    $logData = [
        'endpoint' => $endpoint,
        'request' => $request,
        'response' => $response,
        'error' => $error
    ];
    
    if ($error) {
        $logger->error("API Call Error: $endpoint", $logData);
    } else {
        $logger->info("API Call: $endpoint", $logData);
    }
}

// Khởi tạo logger cho các loại log khác nhau
$shippingLogger = Logger::getInstance(__DIR__ . '/logs/shipping.log');
$errorLogger = Logger::getInstance(__DIR__ . '/logs/error.log');
$apiLogger = Logger::getInstance(__DIR__ . '/logs/api.log');
$checkoutLogger = Logger::getInstance(__DIR__ . '/logs/checkout.log');
$orderLogger = Logger::getInstance(__DIR__ . '/logs/order_processing.log');
$clientLogger = Logger::getInstance(__DIR__ . '/logs/client.log');
$promoLogger = Logger::getInstance(__DIR__ . '/logs/promo_errors.log');

// Function để ghi log API
function logApiCall($endpoint, $request, $response, $error = null) {
    global $apiLogger;
    $apiLogger->log('API_CALL', [
        'endpoint' => $endpoint,
        'request' => $request,
        'response' => $response,
        'error' => $error
    ]);
}

// Function để ghi log shipping
function logShipping($action, $data, $error = null) {
    global $shippingLogger;
    $shippingLogger->log($action, [
        'data' => $data,
        'error' => $error
    ]);
}

// Function để ghi log lỗi
function logError($source, $message, $data = null) {
    global $errorLogger;
    $errorLogger->log($message, [
        'source' => $source,
        'data' => $data
    ], 'ERROR');
}

// Function để ghi log checkout
function logCheckout($action, $data) {
    global $checkoutLogger;
    $checkoutLogger->log($action, $data);
}

// Function để ghi log xử lý đơn hàng
function logOrderProcessing($orderId, $status, $data = null) {
    global $orderLogger;
    $orderLogger->log($status, [
        'order_id' => $orderId,
        'data' => $data
    ]);
}

// Function để ghi log lỗi client
function logClientError($message, $data = null) {
    global $clientLogger;
    $clientLogger->log($message, $data, 'ERROR');
}

// Function để ghi log lỗi promo
function logPromoError($code, $message, $data = null) {
    global $promoLogger;
    $promoLogger->log($message, [
        'code' => $code,
        'data' => $data
    ], 'ERROR');
}

function writeOrderProcessingLog($message, $data = []) {
    $log_file = __DIR__ . '/logs/order_processing.log';
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = [
        'timestamp' => $timestamp,
        'message' => $message,
        'data' => $data
    ];
    
    // Format log entry
    $formatted_log = json_encode($log_entry, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
    // Add separator for better readability
    $log_content = "\n=== Order Processing Log Entry ===\n" . $formatted_log . "\n=== End Log Entry ===\n";
    
    // Write to log file
    file_put_contents($log_file, $log_content, FILE_APPEND);
}

// Set proper permissions
chmod($logDir, 0777);

// Return true to indicate success
return true;
?> 