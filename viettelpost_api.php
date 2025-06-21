<?php
require_once 'create_logs.php';
require_once 'config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type, Accept');

$logger = ViettelPostLogger::getInstance();

// Get the endpoint from the request
$endpoint = $_GET['endpoint'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

// ViettelPost API configuration
$baseUrl = rtrim(VIETTEL_POST_API_URL, '/');
$token = VIETTEL_POST_API_TOKEN;

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

function sendErrorResponse($code, $message, $details = null) {
    global $logger;
    http_response_code($code);
    $response = [
        'status' => 'error',
        'error' => true,
        'message' => $message
    ];
    if ($details) {
        $response['details'] = $details;
    }
    $logger->logApiCall('error', ['message' => $message], $response);
    echo json_encode($response);
    exit;
}

// Clean the endpoint
$endpoint = trim($endpoint, '/');

// Handle different endpoints
switch ($endpoint) {
    case 'categories/listProvince':
        $url = $baseUrl . '/categories/listProvince';
        break;
        
    case 'categories/listDistrict':
        $provinceId = $_GET['provinceId'] ?? '';
        if (empty($provinceId)) {
            sendErrorResponse(400, 'Province ID is required');
        }
        $url = $baseUrl . '/categories/listDistrict?provinceId=' . urlencode($provinceId);
        break;
        
    case 'categories/listWards':
        $districtId = $_GET['districtId'] ?? '';
        if (empty($districtId)) {
            sendErrorResponse(400, 'District ID is required');
        }
        $url = $baseUrl . '/categories/listWards?districtId=' . urlencode($districtId);
        break;
        
    case 'order/getPriceAll':
        if ($method !== 'POST') {
            sendErrorResponse(405, 'Method not allowed');
        }
        
        $url = $baseUrl . '/order/getPriceAll';
        $postData = file_get_contents('php://input');
        
        // Validate JSON data
        $decodedData = json_decode($postData, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            sendErrorResponse(400, 'Invalid JSON data', json_last_error_msg());
        }
        
        // Validate required fields
        $requiredFields = ['SENDER_PROVINCE', 'SENDER_DISTRICT', 'RECEIVER_PROVINCE', 'RECEIVER_DISTRICT', 'PRODUCT_WEIGHT'];
        $missingFields = array_filter($requiredFields, function($field) use ($decodedData) {
            return !isset($decodedData[$field]) || $decodedData[$field] === '';
        });
        
        if (!empty($missingFields)) {
            sendErrorResponse(400, 'Missing required fields', ['fields' => $missingFields]);
        }

        // Set default values if not provided
        $decodedData['PRODUCT_PRICE'] = $decodedData['PRODUCT_PRICE'] ?? 0;
        $decodedData['MONEY_COLLECTION'] = $decodedData['MONEY_COLLECTION'] ?? 0;
        $decodedData['PRODUCT_LENGTH'] = $decodedData['PRODUCT_LENGTH'] ?? 15;
        $decodedData['PRODUCT_WIDTH'] = $decodedData['PRODUCT_WIDTH'] ?? 15;
        $decodedData['PRODUCT_HEIGHT'] = $decodedData['PRODUCT_HEIGHT'] ?? 15;
        
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($decodedData));
        break;
        
    default:
        sendErrorResponse(404, 'Invalid endpoint: ' . $endpoint);
}

curl_setopt($ch, CURLOPT_URL, $url);

// Execute the request
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
$curlError = curl_error($ch);

// Handle cURL errors
if ($curlError) {
    $logger->logApiCall($endpoint, ['url' => $url], null, $curlError);
    sendErrorResponse(500, 'Failed to connect to ViettelPost API', $curlError);
}

// Check content type
if (!$contentType || strpos($contentType, 'application/json') === false) {
    $logger->logApiCall($endpoint, ['url' => $url], $response, 'Invalid content type: ' . $contentType);
    sendErrorResponse(500, 'Invalid response type from ViettelPost API', substr($response, 0, 1000));
}

// Parse response
$responseData = json_decode($response, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    $logger->logApiCall($endpoint, ['url' => $url], $response, 'Invalid JSON response');
    sendErrorResponse(500, 'Invalid JSON response from ViettelPost API', substr($response, 0, 1000));
}

// Log the API call
$logger->logApiCall(
    $endpoint,
    [
        'method' => $method,
        'url' => $url,
        'data' => $method === 'POST' ? json_decode($postData ?? '{}', true) : null
    ],
    $responseData,
    null
);

// Close cURL
curl_close($ch);

// Handle API errors
if ($httpCode >= 400) {
    $errorMessage = $responseData['message'] ?? 'Unknown API error';
    sendErrorResponse($httpCode, $errorMessage, $responseData);
}

// Normalize successful response
$normalizedResponse = [
    'status' => 'success',
    'error' => false,
    'data' => $responseData['data'] ?? $responseData,
    'http_code' => $httpCode
];

// Send response
echo json_encode($normalizedResponse, JSON_UNESCAPED_UNICODE); 