<?php
require_once 'config.php';
require_once 'create_logs.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Clear log file
file_put_contents('logs/viettelpost.log', '');

// Log request details
$request_data = [
    'GET' => $_GET,
    'POST' => file_get_contents('php://input'),
    'SERVER' => [
        'REQUEST_METHOD' => $_SERVER['REQUEST_METHOD'],
        'CONTENT_TYPE' => $_SERVER['CONTENT_TYPE'] ?? 'Not set',
        'HTTP_ACCEPT' => $_SERVER['HTTP_ACCEPT'] ?? 'Not set'
    ]
];
error_log('Request data: ' . print_r($request_data, true));
file_put_contents('logs/viettelpost.log', 'Request data: ' . print_r($request_data, true) . "\n", FILE_APPEND);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type, Accept');

try {
    // Get the endpoint from the request
    $endpoint = $_GET['endpoint'] ?? '';
    $method = $_SERVER['REQUEST_METHOD'];

    error_log('Nhận request đến endpoint: ' . $endpoint . ' với method: ' . $method);
    file_put_contents('logs/viettelpost.log', 'Nhận request đến endpoint: ' . $endpoint . ' với method: ' . $method . "\n", FILE_APPEND);

    // ViettelPost API configuration
    $baseUrl = rtrim(VIETTEL_POST_API_URL, '/');
    $token = VIETTEL_POST_API_TOKEN;

    error_log('Cấu hình API: baseUrl=' . $baseUrl . ', token=' . substr($token, 0, 10) . '...');
    file_put_contents('logs/viettelpost.log', 'Cấu hình API: baseUrl=' . $baseUrl . ', token=' . substr($token, 0, 10) . "...\n", FILE_APPEND);

    // Initialize cURL
    $ch = curl_init();

    // Set common cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Token: ' . $token,
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 30 seconds timeout
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_ENCODING, ''); // Accept all encodings
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); // 5 seconds connection timeout

    // Add retry logic
    $maxRetries = 3;
    $retryCount = 0;
    $success = false;

    function sendErrorResponse($code, $message, $details = null) {
        error_log('Gửi phản hồi lỗi: code=' . $code . ', message=' . $message . ', details=' . json_encode($details));
        file_put_contents('logs/viettelpost.log', 'Gửi phản hồi lỗi: code=' . $code . ', message=' . $message . ', details=' . json_encode($details) . "\n", FILE_APPEND);
        http_response_code($code);
        $response = [
            'error' => [
                'code' => $code,
                'message' => $message,
                'details' => $details
            ]
        ];
        logViettelPostApi('error', ['message' => $message, 'details' => $details], $response);
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Clean the endpoint
    $endpoint = trim($endpoint, '/');

    // Handle different endpoints
    switch ($endpoint) {
        case 'categories/listProvince':
            $url = $baseUrl . '/categories/listProvince';
            error_log('Gọi API lấy danh sách tỉnh/thành phố: ' . $url);
            file_put_contents('logs/viettelpost.log', 'Gọi API lấy danh sách tỉnh/thành phố: ' . $url . "\n", FILE_APPEND);
            
            // Log request details
            logViettelPostApi('categories/listProvince', [
                'url' => $url,
                'method' => 'GET'
            ]);
            break;
            
        case 'categories/listDistrict':
            $provinceId = $_GET['provinceId'] ?? '';
            if (empty($provinceId)) {
                sendErrorResponse(400, 'Province ID is required');
            }
            $url = $baseUrl . '/categories/listDistrict?provinceId=' . urlencode($provinceId);
            error_log('Gọi API lấy danh sách quận/huyện: ' . $url);
            file_put_contents('logs/viettelpost.log', 'Gọi API lấy danh sách quận/huyện: ' . $url . "\n", FILE_APPEND);
            
            // Log request details
            logViettelPostApi('categories/listDistrict', [
                'provinceId' => $provinceId,
                'url' => $url,
                'method' => 'GET'
            ]);
            break;
            
        case 'categories/listWards':
            $districtId = $_GET['districtId'] ?? '';
            if (empty($districtId)) {
                sendErrorResponse(400, 'District ID is required');
            }
            $url = $baseUrl . '/categories/listWards?districtId=' . urlencode($districtId);
            error_log('Gọi API lấy danh sách phường/xã: ' . $url);
            file_put_contents('logs/viettelpost.log', 'Gọi API lấy danh sách phường/xã: ' . $url . "\n", FILE_APPEND);
            
            // Log request details
            logViettelPostApi('categories/listWards', [
                'districtId' => $districtId,
                'url' => $url
            ]);
            break;
            
        case 'order/getPriceAll':
            if ($method !== 'POST') {
                sendErrorResponse(405, 'Method not allowed');
            }
            
            $url = $baseUrl . '/order/getPriceAll';
            $postData = file_get_contents('php://input');
            
            error_log('Gọi API tính phí vận chuyển: ' . $url);
            error_log('Dữ liệu gửi đi: ' . $postData);
            file_put_contents('logs/viettelpost.log', 'Gọi API tính phí vận chuyển: ' . $url . "\n", FILE_APPEND);
            file_put_contents('logs/viettelpost.log', 'Dữ liệu gửi đi: ' . $postData . "\n", FILE_APPEND);
            
            // Validate JSON data
            $decodedData = json_decode($postData, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                sendErrorResponse(400, 'Invalid JSON data', json_last_error_msg());
            }
            
            // Validate required fields
            $requiredFields = [
                'SENDER_PROVINCE', 'SENDER_DISTRICT', 'RECEIVER_PROVINCE', 
                'RECEIVER_DISTRICT', 'PRODUCT_WEIGHT', 'PRODUCT_TYPE'
            ];
            $missingFields = array_filter($requiredFields, function($field) use ($decodedData) {
                return !isset($decodedData[$field]) || $decodedData[$field] === '';
            });
            
            if (!empty($missingFields)) {
                sendErrorResponse(400, 'Missing required fields', ['fields' => $missingFields]);
            }

            // Set default values if not provided
            $defaultValues = [
                'PRODUCT_PRICE' => 0,
                'MONEY_COLLECTION' => 0,
                'PRODUCT_LENGTH' => DEFAULT_LENGTH,
                'PRODUCT_WIDTH' => DEFAULT_WIDTH,
                'PRODUCT_HEIGHT' => DEFAULT_HEIGHT,
                'PRODUCT_QUANTITY' => 1,
                'TYPE' => 2, // Giao hàng tiêu chuẩn
                'NATIONAL_TYPE' => 1, // Giao trong nước
                'SERVICE_ADD_ON' => ''
            ];

            foreach ($defaultValues as $key => $value) {
                if (!isset($decodedData[$key]) || $decodedData[$key] === '') {
                    $decodedData[$key] = $value;
                }
            }

            // Ensure sender info matches config
            $decodedData['SENDER_PROVINCE'] = PICK_PROVINCE;
            $decodedData['SENDER_DISTRICT'] = PICK_DISTRICT;
            $decodedData['SENDER_WARD'] = PICK_WARD;

            error_log('Dữ liệu sau khi xử lý: ' . json_encode($decodedData));
            file_put_contents('logs/viettelpost.log', 'Dữ liệu sau khi xử lý: ' . json_encode($decodedData) . "\n", FILE_APPEND);

            // Log request data
            logViettelPostApi('order/getPriceAll', [
                'request' => $decodedData,
                'url' => $url
            ]);
            
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($decodedData));
            break;
            
        default:
            sendErrorResponse(404, 'Invalid endpoint: ' . $endpoint);
    }

    curl_setopt($ch, CURLOPT_URL, $url);

    while (!$success && $retryCount < $maxRetries) {
        if ($retryCount > 0) {
            error_log('Thử lại lần ' . $retryCount . '...');
            file_put_contents('logs/viettelpost.log', 'Thử lại lần ' . $retryCount . "...\n", FILE_APPEND);
            sleep(1); // Wait 1 second before retrying
        }

        // Execute the request
        $startTime = microtime(true);
        $response = curl_exec($ch);
        $endTime = microtime(true);
        $executionTime = round(($endTime - $startTime) * 1000);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        $curlError = curl_error($ch);
        $curlInfo = curl_getinfo($ch);

        error_log('Kết quả gọi API: httpCode=' . $httpCode . ', executionTime=' . $executionTime . 'ms');
        error_log('Phản hồi từ API: ' . $response);
        file_put_contents('logs/viettelpost.log', 'Kết quả gọi API: httpCode=' . $httpCode . ', executionTime=' . $executionTime . "ms\n", FILE_APPEND);
        file_put_contents('logs/viettelpost.log', 'Phản hồi từ API: ' . $response . "\n", FILE_APPEND);

        if ($curlError) {
            error_log('Lỗi cURL: ' . $curlError);
            file_put_contents('logs/viettelpost.log', 'Lỗi cURL: ' . $curlError . "\n", FILE_APPEND);
        }

        // Log request details
        $requestDetails = [
            'method' => $method,
            'url' => $url,
            'execution_time_ms' => $executionTime,
            'http_code' => $httpCode,
            'content_type' => $contentType,
            'curl_info' => $curlInfo,
            'retry_count' => $retryCount
        ];

        if ($method === 'POST') {
            $requestDetails['data'] = json_decode($postData ?? '{}', true);
        }

        // Handle cURL errors
        if ($curlError) {
            logViettelPostApi($endpoint, $requestDetails, null, [
                'type' => 'curl_error',
                'message' => $curlError,
                'retry_count' => $retryCount
            ]);

            if ($retryCount < $maxRetries - 1) {
                $retryCount++;
                continue;
            }

            sendErrorResponse(500, 'Lỗi kết nối đến ViettelPost API', [
                'error' => $curlError,
                'retry_count' => $retryCount
            ]);
        }

        // Handle non-200 responses
        if ($httpCode !== 200) {
            logViettelPostApi($endpoint, $requestDetails, $response, [
                'type' => 'http_error',
                'code' => $httpCode,
                'retry_count' => $retryCount
            ]);

            if ($retryCount < $maxRetries - 1) {
                $retryCount++;
                continue;
            }

            sendErrorResponse($httpCode, 'ViettelPost API returned error', [
                'http_code' => $httpCode,
                'response' => $response,
                'retry_count' => $retryCount
            ]);
        }

        // Validate JSON response
        $decodedResponse = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            logViettelPostApi($endpoint, $requestDetails, $response, [
                'type' => 'json_error',
                'message' => json_last_error_msg(),
                'retry_count' => $retryCount
            ]);

            if ($retryCount < $maxRetries - 1) {
                $retryCount++;
                continue;
            }

            sendErrorResponse(500, 'Invalid JSON response from ViettelPost API', [
                'error' => json_last_error_msg(),
                'response' => $response,
                'retry_count' => $retryCount
            ]);
        }

        // Check for API errors in response
        if (isset($decodedResponse['error']) && $decodedResponse['error'] !== false) {
            logViettelPostApi($endpoint, $requestDetails, $decodedResponse, [
                'type' => 'api_error',
                'error' => $decodedResponse['error'],
                'retry_count' => $retryCount
            ]);

            if ($retryCount < $maxRetries - 1) {
                $retryCount++;
                continue;
            }

            sendErrorResponse(400, 'ViettelPost API error', [
                'api_error' => $decodedResponse['error'],
                'retry_count' => $retryCount
            ]);
        }

        // Success!
        $success = true;
        logViettelPostApi($endpoint, $requestDetails, $decodedResponse);
        echo $response;
    }

} catch (Exception $e) {
    error_log('Lỗi không mong muốn: ' . $e->getMessage());
    file_put_contents('logs/viettelpost.log', 'Lỗi không mong muốn: ' . $e->getMessage() . "\n", FILE_APPEND);
    sendErrorResponse(500, 'Internal Server Error', [
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
} finally {
    if (isset($ch)) {
        curl_close($ch);
    }
}
?> 