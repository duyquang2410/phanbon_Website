<?php
// Require Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Tắt hiển thị lỗi
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(0);

// Import Guzzle
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

// Đảm bảo không có output buffer
while (ob_get_level()) ob_end_clean();

// Đảm bảo output là JSON
header_remove();
header('Content-Type: application/json; charset=utf-8');

// Thêm CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Bắt tất cả các lỗi và chuyển thành JSON
set_error_handler(function($errno, $errstr) {
    http_response_code(500);
    die(json_encode([
        'success' => false,
        'error' => 'PHP Error: ' . $errstr
    ]));
});

set_exception_handler(function($e) {
    http_response_code(500);
    die(json_encode([
        'success' => false,
        'error' => 'PHP Exception: ' . $e->getMessage()
    ]));
});

register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
        http_response_code(500);
        die(json_encode([
            'success' => false,
            'error' => 'Fatal Error: ' . $error['message']
        ]));
    }
});

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    die(json_encode(['success' => true]));
}

function writeLog($message) {
    $logFile = __DIR__ . '/chatbot.log';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

// Hàm để đảm bảo trả về JSON
function sendJsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit();
}

// Hàm để phân tích response và tìm sản phẩm được đề xuất
function extractRecommendedProducts($aiResponse, $products) {
    writeLog("Starting product extraction from AI response");
    writeLog("AI Response to analyze: " . $aiResponse);
    
    $recommendedProducts = [];
    $baseImageUrl = 'http://localhost/web/phanbon_Website/img/'; // Base URL cho hình ảnh
    
    // Chuẩn hóa response để dễ tìm kiếm
    $normalizedResponse = mb_strtolower(trim($aiResponse));
    writeLog("Normalized response: " . $normalizedResponse);

    foreach ($products as $product) {
        writeLog("Checking product: " . $product['SP_TEN']);
        
        // Tạo các biến thể tên sản phẩm để tìm kiếm
        $productVariants = [
            mb_strtolower($product['SP_TEN']), // Tên đầy đủ
            mb_strtolower(explode('–', $product['SP_TEN'])[0]), // Phần trước dấu –
            mb_strtolower(explode('-', $product['SP_TEN'])[0]), // Phần trước dấu -
            mb_strtolower(explode('(', $product['SP_TEN'])[0]), // Phần trước dấu (
        ];
        $productVariants = array_map('trim', $productVariants);
        writeLog("Product variants to check: " . json_encode($productVariants, JSON_UNESCAPED_UNICODE));
        
        $found = false;
        foreach ($productVariants as $variant) {
            if (!empty($variant) && mb_stripos($normalizedResponse, $variant) !== false) {
                $found = true;
                writeLog("Found match with variant: " . $variant);
                break;
            }
        }

        if ($found) {
            writeLog("Found match for product: " . $product['SP_TEN']);
            
            // Chuẩn hóa đường dẫn hình ảnh
            $imagePath = basename($product['SP_HINHANH']); // Lấy tên file từ đường dẫn
            if (empty($imagePath)) {
                $imagePath = 'default-product.jpg';
            }
            
            // Kiểm tra xem file có tồn tại không
            $fullImagePath = __DIR__ . '/img/' . $imagePath;
            writeLog("Checking image path: " . $fullImagePath);
            
            if (!file_exists($fullImagePath)) {
                $imagePath = 'default-product.jpg';
                writeLog("Image not found, using default: " . $imagePath);
            }
            
            // Tạo URL đầy đủ
            $imageUrl = $baseImageUrl . $imagePath;
            writeLog("Generated image URL: " . $imageUrl);
            
            $productInfo = [
                'SP_MA' => $product['SP_MA'],
                'SP_TEN' => $product['SP_TEN'],
                'SP_MOTA' => $product['SP_MOTA'],
                'SP_DONGIA' => $product['SP_DONGIA'],
                'SP_HINHANH' => $imageUrl, // Trả về URL đầy đủ
                'danh_muc' => $product['danh_muc']
            ];
            
            writeLog("Adding product to recommendations: " . json_encode($productInfo, JSON_UNESCAPED_UNICODE));
            $recommendedProducts[] = $productInfo;
        } else {
            writeLog("No match found for product: " . $product['SP_TEN']);
        }
    }
    
    writeLog("Product extraction completed. Found " . count($recommendedProducts) . " products");
    writeLog("Recommended products: " . json_encode($recommendedProducts, JSON_UNESCAPED_UNICODE));
    
    return $recommendedProducts;
}

try {
    writeLog("=== Starting new chatbot request ===");
    writeLog("Request Method: " . $_SERVER['REQUEST_METHOD']);
    writeLog("Request Headers: " . json_encode(getallheaders()));
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        sendJsonResponse([
            'success' => false,
            'error' => 'Method not allowed'
        ], 405);
    }

    // Log request data
    $rawData = file_get_contents('php://input');
    writeLog("Raw request data: " . $rawData);

    // Lấy input từ user
    $data = json_decode($rawData, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        sendJsonResponse([
            'success' => false,
            'error' => 'Invalid JSON data: ' . json_last_error_msg()
        ], 400);
    }

    writeLog("JSON decoded successfully");

    $userMessage = $data['message'] ?? '';
    if (empty($userMessage)) {
        sendJsonResponse([
            'success' => false,
            'error' => 'Message is required'
        ], 400);
    }

    writeLog("User message: " . $userMessage);

    // Load dependencies
    try {
        require_once 'config.php';
        writeLog("Loaded config.php");
        
        require_once 'connect.php';
        writeLog("Loaded connect.php");
        
        if (!$conn) {
            throw new Exception('Database connection failed: ' . mysqli_connect_error());
        }
        writeLog("Database connected successfully");
    } catch (Exception $e) {
        writeLog("Dependency error: " . $e->getMessage());
        sendJsonResponse([
            'success' => false,
            'error' => 'Server configuration error'
        ], 500);
    }

    // Lấy thông tin sản phẩm
    writeLog("Querying products...");
    $query_products = "SELECT sp.SP_MA, sp.SP_TEN, sp.SP_MOTA, sp.SP_DONGIA, sp.SP_HINHANH,
                         dm.DM_TEN as danh_muc,
                         GROUP_CONCAT(DISTINCT lct.LCT_TEN) as cay_trong,
                         GROUP_CONCAT(DISTINCT lb.Ten_loai_benh) as benh
                  FROM san_pham sp 
                  LEFT JOIN danh_muc dm ON sp.DM_MA = dm.DM_MA
                  LEFT JOIN san_pham_cay_trong spct ON sp.SP_MA = spct.SP_MA
                  LEFT JOIN loai_cay_trong lct ON spct.LCT_MA = lct.LCT_MA
                  LEFT JOIN disease_products dp ON sp.SP_MA = dp.product_id
                  LEFT JOIN loai_benh lb ON dp.disease_id = lb.Ma_loai_benh
                  WHERE sp.SP_TRANGTHAI = 1
                  GROUP BY sp.SP_MA";
    
    writeLog("Products query: " . $query_products);
    
    $result_products = mysqli_query($conn, $query_products);
    if (!$result_products) {
        writeLog("Products query error: " . mysqli_error($conn));
        sendJsonResponse([
            'success' => false,
            'error' => 'Database error'
        ], 500);
    }

    $products = [];
    while ($row = mysqli_fetch_assoc($result_products)) {
        writeLog("Found product: " . json_encode($row, JSON_UNESCAPED_UNICODE));
        $products[] = $row;
    }
    writeLog("Found " . count($products) . " products total");

    // Lấy thông tin bệnh và triệu chứng
    writeLog("Querying diseases...");
    $query_diseases = "SELECT lb.Ma_loai_benh, lb.Ten_loai_benh, lb.mo_ta, 
                             GROUP_CONCAT(DISTINCT s.name) as trieu_chung,
                             GROUP_CONCAT(DISTINCT s.description) as mo_ta_trieu_chung,
                             GROUP_CONCAT(DISTINCT sp.SP_TEN) as san_pham_dieu_tri
                      FROM loai_benh lb
                      LEFT JOIN disease_symptoms ds ON lb.Ma_loai_benh = ds.disease_id
                      LEFT JOIN symptoms s ON ds.symptom_id = s.id
                      LEFT JOIN disease_products dp ON lb.Ma_loai_benh = dp.disease_id
                      LEFT JOIN san_pham sp ON dp.product_id = sp.SP_MA
                      GROUP BY lb.Ma_loai_benh";

    writeLog("Diseases query: " . $query_diseases);
    
    $result_diseases = mysqli_query($conn, $query_diseases);
    if (!$result_diseases) {
        writeLog("Diseases query error: " . mysqli_error($conn));
        sendJsonResponse([
            'success' => false,
            'error' => 'Database error'
        ], 500);
    }

    $diseases = [];
    while ($row = mysqli_fetch_assoc($result_diseases)) {
        $diseases[] = $row;
    }
    writeLog("Found " . count($diseases) . " diseases");

    // Chuẩn bị context cho Gemini
    writeLog("Preparing context for Gemini API...");
    $context = "Bạn là một chuyên gia tư vấn về phân bón và thuốc bảo vệ thực vật. Hãy trả lời dựa trên thông tin sau:\n\n";
    $context .= "DANH SÁCH SẢN PHẨM:\n";
    foreach ($products as $product) {
        $context .= "- " . $product['SP_TEN'] . "\n";
        $context .= "  Mô tả: " . $product['SP_MOTA'] . "\n";
        $context .= "  Danh mục: " . $product['danh_muc'] . "\n";
        $context .= "  Phù hợp với: " . $product['cay_trong'] . "\n";
        $context .= "  Điều trị bệnh: " . $product['benh'] . "\n";
        $context .= "  Giá: " . number_format($product['SP_DONGIA'], 0, ',', '.') . " VNĐ\n\n";
    }

    $context .= "\nDANH SÁCH BỆNH:\n";
    foreach ($diseases as $disease) {
        $context .= "- " . $disease['Ten_loai_benh'] . "\n";
        $context .= "  Mô tả: " . $disease['mo_ta'] . "\n";
        $context .= "  Triệu chứng: " . $disease['trieu_chung'] . "\n";
        $context .= "  Chi tiết triệu chứng: " . $disease['mo_ta_trieu_chung'] . "\n";
        $context .= "  Sản phẩm điều trị: " . $disease['san_pham_dieu_tri'] . "\n\n";
    }

    $context .= "\nHãy trả lời câu hỏi sau một cách ngắn gọn, chính xác và chuyên nghiệp: " . $userMessage;

    writeLog("Context prepared, length: " . strlen($context) . " characters");
    writeLog("Sending request to Gemini API...");

    // Gửi request đến Gemini API sử dụng Guzzle
    try {
        $client = new Client();
        writeLog("Created Guzzle client");
        
        $requestData = [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $context]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'topP' => 0.8,
                    'topK' => 40,
                    'maxOutputTokens' => 1000
                ]
            ]
        ];
        writeLog("Request data prepared: " . json_encode($requestData));
        writeLog("Sending request to URL: " . GEMINI_API_URL . '?key=' . substr(GEMINI_API_KEY, 0, 10) . '...');
        
        try {
            $response = $client->post(GEMINI_API_URL . '?key=' . GEMINI_API_KEY, $requestData);
            writeLog("Gemini API response status: " . $response->getStatusCode());
            writeLog("Gemini API response headers: " . json_encode($response->getHeaders()));
            
            $responseBody = $response->getBody()->getContents();
            writeLog("Gemini API raw response: " . $responseBody);
            
            $result = json_decode($responseBody, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Error decoding Gemini response: ' . json_last_error_msg());
            }
            
            if (!isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                throw new Exception('Invalid response format from Gemini API');
            }
            
            $aiResponse = $result['candidates'][0]['content']['parts'][0]['text'];
            writeLog("AI Response extracted successfully");
            writeLog("AI Response: " . $aiResponse);

            // Tìm các sản phẩm được đề xuất
            writeLog("Starting product recommendation process...");
            $recommendedProducts = extractRecommendedProducts($aiResponse, $products);
            writeLog("Product recommendation completed");
            writeLog("Number of recommended products: " . count($recommendedProducts));
            writeLog("Final response data: " . json_encode([
                'success' => true,
                'response' => $aiResponse,
                'products' => $recommendedProducts
            ], JSON_UNESCAPED_UNICODE));

            sendJsonResponse([
                'success' => true,
                'response' => $aiResponse,
                'products' => $recommendedProducts
            ]);
            
        } catch (RequestException $e) {
            writeLog("Gemini API request error: " . $e->getMessage());
            if ($e->hasResponse()) {
                writeLog("Error response body: " . $e->getResponse()->getBody());
            }
            throw $e;
        }
        
    } catch (Exception $e) {
        writeLog("Gemini API error: " . $e->getMessage());
        writeLog("Stack trace: " . $e->getTraceAsString());
        sendJsonResponse([
            'success' => false,
            'error' => 'AI service error: ' . $e->getMessage()
        ], 500);
    }

} catch (Exception $e) {
    writeLog("Error occurred: " . $e->getMessage());
    writeLog("Stack trace: " . $e->getTraceAsString());
    sendJsonResponse([
        'success' => false,
        'error' => 'Server error: ' . $e->getMessage()
    ], 500);
} 