<?php
require_once 'create_logs.php';

header('Content-Type: application/json');

// Get POST data
$rawData = file_get_contents('php://input');
$data = json_decode($rawData, true);

if (!$data || !isset($data['action'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

$logger = ViettelPostLogger::getInstance();

switch ($data['action']) {
    case 'log_api_call':
        $logger->logApiCall(
            $data['endpoint'] ?? '',
            $data['params'] ?? [],
            $data['response'] ?? null,
            $data['error'] ?? null
        );
        break;

    case 'log_shipping_calculation':
        $logger->logShippingCalculation(
            $data['address_data'] ?? [],
            $data['weight'] ?? 0,
            $data['price'] ?? 0,
            $data['result'] ?? null,
            $data['error'] ?? null
        );
        break;

    case 'log_address_selection':
        $logger->logAddressSelection(
            $data['selection_type'] ?? '',
            $data['selected_id'] ?? '',
            $data['result'] ?? null,
            $data['error'] ?? null
        );
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
        exit;
}

echo json_encode(['success' => true]); 