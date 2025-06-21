<?php
// Đường dẫn đến thư mục logs
$logDir = __DIR__ . '/logs';

// Tạo thư mục logs nếu chưa tồn tại
if (!file_exists($logDir)) {
    mkdir($logDir, 0777, true);
}

class ErrorLogger {
    protected $logFile;

    protected function __construct($logFile) {
        $this->logFile = $logFile;
        $logDir = dirname($logFile);
        
        if (!file_exists($logDir)) {
            mkdir($logDir, 0777, true);
        }
        
        if (!file_exists($this->logFile)) {
            touch($this->logFile);
            chmod($this->logFile, 0666);
        }
    }

    protected function log($level, $message, $context = []) {
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = [
            'timestamp' => $timestamp,
            'level' => $level,
            'message' => $message,
            'context' => $context
        ];

        $logMessage = json_encode($logEntry, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($this->logFile, $logMessage . "\n---\n", FILE_APPEND);
    }
}

class Logger {
    private $logFile;
    private $logDir = 'logs';

    public function __construct($type = 'general') {
        if (!file_exists($this->logDir)) {
            mkdir($this->logDir, 0777, true);
        }
        $this->logFile = $this->logDir . '/' . $type . '.log';
    }

    public function log($level, $message, $context = []) {
        // Ensure log directory exists
        if (!file_exists($this->logDir)) {
            mkdir($this->logDir, 0777, true);
        }

        // Format the log entry
        $timestamp = date('Y-m-d H:i:s');
        $contextJson = json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $logEntry = "[$timestamp] [$level] $message $contextJson" . PHP_EOL;

        // Write to log file
        file_put_contents($this->logFile, $logEntry, FILE_APPEND);

        // If it's an error, also log to error.log
        if (strtoupper($level) === 'ERROR') {
            $errorLogFile = $this->logDir . '/error.log';
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            $caller = isset($backtrace[1]) ? $backtrace[1] : $backtrace[0];
            
            $errorContext = array_merge($context, [
                'file' => $caller['file'] ?? 'unknown',
                'line' => $caller['line'] ?? 'unknown',
                'function' => $caller['function'] ?? 'unknown',
                'timestamp' => $timestamp,
                'request_uri' => $_SERVER['REQUEST_URI'] ?? 'unknown',
                'request_method' => $_SERVER['REQUEST_METHOD'] ?? 'unknown',
                'client_ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
            ]);

            $errorJson = json_encode($errorContext, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $errorEntry = "[$timestamp] [$level] $message $errorJson" . PHP_EOL;
            file_put_contents($errorLogFile, $errorEntry, FILE_APPEND);
        }
    }

    public function getLogPath() {
        return $this->logFile;
    }

    public function clearLog() {
        file_put_contents($this->logFile, '');
    }
}

class ViettelPostLogger extends ErrorLogger {
    private static $instance = null;
    protected $logFile;

    protected function __construct($logFile) {
        parent::__construct($logFile);
        $this->logFile = $logFile;
    }

    public static function getInstance($logFile = 'logs/viettelpost.log') {
        if (self::$instance === null) {
            self::$instance = new ViettelPostLogger($logFile);
        }
        return self::$instance;
    }

    public function logApiCall($endpoint, $params, $response = null, $error = null) {
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'type' => 'API_CALL',
            'message' => $endpoint,
            'data' => [
                'request' => $params,
                'response' => $response,
                'error' => $error
            ]
        ];

        $logMessage = json_encode($logEntry, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($this->logFile, $logMessage . "\n---\n", FILE_APPEND);
    }

    public function logShippingCalculation($addressData, $weight, $price, $result = null, $error = null) {
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'type' => 'shipping_calculation',
            'data' => [
                'address_data' => $addressData,
                'weight' => $weight,
                'price' => $price,
                'result' => $result,
                'error' => $error
            ]
        ];

        $logMessage = json_encode($logEntry, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($this->logFile, $logMessage . "\n---\n", FILE_APPEND);
    }

    public function logAddressSelection($type, $id, $result = null, $error = null) {
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'type' => 'address_selection',
            'data' => [
                'selection_type' => $type,
                'selected_id' => $id,
                'result' => $result,
                'error' => $error
            ]
        ];

        $logMessage = json_encode($logEntry, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($this->logFile, $logMessage . "\n---\n", FILE_APPEND);
    }
}

// Khởi tạo logger cho các loại log khác nhau
$shippingLogger = new Logger('shipping');
$errorLogger = new Logger('error');
$apiLogger = new Logger('api');
$checkoutLogger = new Logger('checkout');
$orderLogger = new Logger('order_processing');
$clientLogger = new Logger('client');
$promoLogger = new Logger('promo_errors');

// Function để ghi log API
function logApiCall($endpoint, $request, $response, $error = null) {
    global $apiLogger;
    $apiLogger->log('API_CALL', $endpoint, [
        'request' => $request,
        'response' => $response,
        'error' => $error
    ]);
}

// Function để ghi log shipping
function logShipping($action, $data, $error = null) {
    global $shippingLogger;
    $shippingLogger->log($action, isset($error) ? 'Error: ' . $error : 'Success', $data);
}

// Function để ghi log lỗi
function logError($source, $message, $data = null) {
    global $errorLogger;
    $errorLogger->log($source, $message, $data);
}

// Function để ghi log checkout
function logCheckout($action, $data) {
    global $checkoutLogger;
    $checkoutLogger->log($action, '', $data);
}

// Function để ghi log xử lý đơn hàng
function logOrderProcessing($orderId, $status, $data = null) {
    global $orderLogger;
    $orderLogger->log($status, "Order ID: $orderId", $data);
}

// Function để ghi log lỗi client
function logClientError($message, $data = null) {
    global $clientLogger;
    $clientLogger->log('CLIENT_ERROR', $message, $data);
}

// Function để ghi log lỗi promo
function logPromoError($code, $message, $data = null) {
    global $promoLogger;
    $promoLogger->log('PROMO_ERROR', "Code: $code - $message", $data);
}

// Set proper permissions
chmod($logDir, 0777);

// Return true to indicate success
return true;
?> 