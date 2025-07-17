<?php
session_start();
require_once '../connect.php';

// Lấy order_id từ URL
$order_id = $_GET['order_id'] ?? null;

if (!$order_id) {
    header('Location: ../index.php');
    exit();
}

// Lấy thông tin đơn hàng và thông tin giao hàng
$sql = "SELECT h.*, k.KH_TEN, k.KH_SDT, k.KH_DIACHI 
        FROM hoa_don h 
        LEFT JOIN khach_hang k ON h.KH_MA = k.KH_MA 
        WHERE h.HD_STT = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

// Thêm xử lý khi không tìm thấy thông tin
if (!$order) {
    header('Location: ../index.php');
    exit();
}

// Xử lý giá trị null
$order['KH_TEN'] = $order['KH_TEN'] ?? 'Không có thông tin';
$order['KH_SDT'] = $order['KH_SDT'] ?? 'Không có thông tin';
$order['KH_DIACHI'] = $order['KH_DIACHI'] ?? 'Không có thông tin';
$order['HD_TONGTIEN'] = $order['HD_TONGTIEN'] ?? 0;

// Lấy chi tiết đơn hàng
$sql_details = "SELECT c.*, s.SP_TEN 
               FROM chi_tiet_hd c 
               JOIN san_pham s ON c.SP_MA = s.SP_MA 
               WHERE c.HD_STT = ?";
$stmt_details = $conn->prepare($sql_details);
$stmt_details->bind_param("i", $order_id);
$stmt_details->execute();
$details = $stmt_details->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán Thành Công - Phân Bón & Thuốc BVTV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2E7D32;
            --secondary-color: #4CAF50;
            --accent-color: #8BC34A;
            --text-color: #333;
            --light-bg: #F1F8E9;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            color: var(--text-color);
        }

        .success-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        .success-icon {
            width: 100px;
            height: 100px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            animation: scaleIn 0.5s ease-out;
        }

        .success-icon i {
            color: white;
            font-size: 50px;
        }

        .order-details {
            background: var(--light-bg);
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }

        .order-item {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            transition: all 0.3s ease;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .order-item:hover {
            background: rgba(139, 195, 74, 0.1);
        }

        .btn-custom {
            background: var(--primary-color);
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .payment-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px;
            background: #fff;
            border-radius: 10px;
            margin: 10px 0;
            border: 1px solid #e0e0e0;
        }

        .payment-info i {
            font-size: 24px;
            color: var(--primary-color);
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
        }

        .status-badge {
            background: var(--accent-color);
            color: white;
            padding: 5px 15px;
            border-radius: 15px;
            font-size: 14px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="container success-container">
        <div class="text-center mb-4">
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            <h2 class="mb-3">Thanh Toán Thành Công!</h2>
            <p class="text-muted">Cảm ơn bạn đã mua hàng tại cửa hàng chúng tôi</p>
            <div class="status-badge">
                <i class="fas fa-clock me-1"></i>
                Đơn hàng đang được xử lý
            </div>
        </div>

       

        <div class="text-center mt-4">
            <a href="../my_orders.php" class="btn btn-custom me-2">
                <i class="fas fa-list me-2"></i>
                Xem đơn hàng của tôi
            </a>
            <a href="../index.php" class="btn btn-custom">
                <i class="fas fa-home me-2"></i>
                Về trang chủ
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>