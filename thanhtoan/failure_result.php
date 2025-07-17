<?php
session_start();
require_once '../connect.php';
require_once '../error_log.php';

$logger = ErrorLogger::getInstance('../logs/error.log');
$payment_type = isset($_GET['payment_type']) ? $_GET['payment_type'] : '';
$reason = isset($_GET['reason']) ? $_GET['reason'] : 'Lỗi không xác định';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán thất bại</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .failure-container {
            min-height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }
        .failure-card {
            max-width: 500px;
            width: 100%;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            background-color: white;
        }
        .failure-icon {
            font-size: 5rem;
            color: #dc3545;
            margin-bottom: 1.5rem;
        }
        .reason-text {
            color: #6c757d;
            margin: 1rem 0;
        }
        .action-buttons {
            margin-top: 2rem;
        }
        .action-buttons .btn {
            margin: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
        }
    </style>
</head>
<body>
    <?php include '../header.php'; ?>

    <div class="failure-container">
        <div class="failure-card">
            <div class="failure-icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <h2 class="mb-4">Thanh toán thất bại</h2>
            
            <?php if ($payment_type == 'vnpay'): ?>
                <p class="reason-text">
                    <strong>Lỗi từ VNPAY:</strong><br>
                    <?php echo htmlspecialchars($reason); ?>
                </p>
            <?php elseif ($payment_type == 'momo'): ?>
                <p class="reason-text">
                    <strong>Lỗi từ MOMO:</strong><br>
                    <?php echo htmlspecialchars($reason); ?>
                </p>
            <?php else: ?>
                <p class="reason-text">
                    <strong>Lý do:</strong><br>
                    <?php echo htmlspecialchars($reason); ?>
                </p>
            <?php endif; ?>

            <div class="action-buttons">
                <a href="../cart.php" class="btn btn-primary">
                    <i class="fas fa-shopping-cart me-2"></i>Quay lại giỏ hàng
                </a>
                <a href="../checkout.php" class="btn btn-success">
                    <i class="fas fa-sync-alt me-2"></i>Thử lại thanh toán
                </a>
            </div>

            <div class="mt-4">
                <p class="text-muted">
                    Nếu bạn cần hỗ trợ, vui lòng liên hệ:
                    <br>
                    <i class="fas fa-phone me-2"></i>Hotline: 1900 xxxx
                    <br>
                    <i class="fas fa-envelope me-2"></i>Email: support@example.com
                </p>
            </div>
        </div>
    </div>

    <?php include '../footer.php'; ?>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>