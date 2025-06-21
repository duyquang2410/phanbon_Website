<?php
require_once 'create_logs.php';

header('Content-Type: application/json');

// Allow CORS for local development
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

try {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        throw new Exception('Invalid JSON input');
    }

    // Log based on the type of error
    switch ($input['type']) {
        case 'API_CALL':
            logApiCall(
                $input['endpoint'] ?? 'unknown',
                $input['request'] ?? [],
                $input['response'] ?? null,
                $input['error'] ?? null
            );
            break;

        case 'SHIPPING_REQUEST':
        case 'SHIPPING_RESPONSE':
        case 'SHIPPING_SUCCESS':
        case 'SHIPPING_ERROR':
            logShipping(
                $input['type'],
                $input['data'] ?? [],
                $input['message'] ?? null
            );
            break;

        case 'CLIENT_ERROR':
            logClientError(
                $input['message'] ?? 'Unknown client error',
                $input['data'] ?? null
            );
            break;

        default:
            logError(
                'UNKNOWN',
                $input['message'] ?? 'Unknown error type',
                $input
            );
    }
    
    http_response_code(200);
    echo json_encode(['status' => 'success']);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Failed to log error',
        'message' => $e->getMessage()
    ]);
}
?> 