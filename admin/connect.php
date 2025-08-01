<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "phan_bon_test";

// Tắt error reporting để tránh lộ thông tin database
error_reporting(0);
ini_set('display_errors', 0);

try {
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
    
    // Set charset
    if (!$conn->set_charset("utf8mb4")) {
        throw new Exception("Error setting charset: " . $conn->error);
    }
    
// Check connection
if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Enable strict mode
    $conn->query("SET SESSION sql_mode = 'STRICT_ALL_TABLES'");
    
} catch (Exception $e) {
    // Log error
    error_log("Database Connection Error: " . $e->getMessage());
    
    // If it's an API request, return JSON error
    if (strpos($_SERVER['REQUEST_URI'], 'api_') !== false || 
        strpos($_SERVER['REQUEST_URI'], 'process_') !== false) {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Database connection error'
        ]);
        exit;
    }
    
    // For normal pages, show error page
    include 'error.php';
    exit;
}
?>