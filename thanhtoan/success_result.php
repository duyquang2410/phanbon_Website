<?php
session_start();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán Thành Công - Shop Quần Áo</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome cho icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f5f5;
        }
        .navbar {
            background-color: #f8c1cc; /* Màu hồng phấn nhẹ nhàng */
        }
        .navbar-brand, .nav-link {
            color: #333 !important;
            font-family: 'Playfair Display', serif;
        }
        .navbar-brand:hover, .nav-link:hover {
            color: #e91e63 !important; /* Màu hồng đậm khi hover */
        }
        .success-container {
            margin-top: 50px;
            text-align: center;
            background-color: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .success-icon {
            font-size: 60px;
            color: #e91e63;
        }
        .success-message {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            margin-top: 20px;
            font-family: 'Playfair Display', serif;
        }
        .success-details {
            font-size: 16px;
            color: #666;
            margin-top: 10px;
        }
        .order-details {
            margin-top: 30px;
            text-align: left;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
        }
        .order-details h5 {
            font-family: 'Playfair Display', serif;
            color: #e91e63;
        }
        .btn-continue {
            margin-top: 20px;
            background-color: #e91e63;
            border: none;
            padding: 10px 30px;
            font-size: 16px;
        }
        .btn-continue:hover {
            background-color: #d81b60;
        }
        .footer {
            background-color: #333;
            color: white;
            padding: 40px 0;
            margin-top: 50px;
        }
        .footer a {
            color: #f8c1cc;
            text-decoration: none;
        }
        .footer a:hover {
            color: #e91e63;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">Shop Quần Áo</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="../index.php">Trang Chủ</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Sản Phẩm</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Danh Mục</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Liên Hệ</a></li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="#">Xin chào: <?php echo htmlspecialchars($_SESSION['username'] ?? 'Khách'); ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-shopping-cart"></i> 0</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Success Message -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="success-container">
                    <i class="fas fa-check-circle success-icon"></i>
                    <div class="success-message">Thanh Toán Thành Công</div>
                    <div class="success-details">
                        Cảm ơn bạn đã mua sắm tại Shop Quần Áo! Đơn hàng của bạn đã được xử lý thành công.
                    </div>

                    <!-- Order Details -->
                    <?php if (isset($_SESSION['order_data'])): 
                        $order_data = $_SESSION['order_data'];
                        $new_id = $order_data['new_id'];
                        $total = $order_data['hd_tongtien'];
                        $array = $order_data['array'];
                        $array_sl = $order_data['array_sl'];
                        $size_array = $order_data['size_array'];
                        $color_array = $order_data['color_array'];
                    ?>
                    <div class="order-details">
                        <h5>Chi Tiết Đơn Hàng</h5>
                        <p><strong>Mã đơn hàng:</strong> #<?php echo htmlspecialchars($new_id); ?></p>
                        <p><strong>Tổng tiền:</strong> <?php echo number_format($total, 0, ',', '.'); ?> VND</p>
                        <p><strong>Sản phẩm:</strong></p>
                        <ul>
                            <?php foreach ($array as $index => $spid): ?>
                                <li>
                                    Sản phẩm ID: <?php echo htmlspecialchars($spid); ?> 
                                    - Số lượng: <?php echo htmlspecialchars($array_sl[$index]); ?>
                                    - Kích thước: <?php echo htmlspecialchars($size_array[$index] ?? 'N/A'); ?>
                                    - Màu sắc: <?php echo htmlspecialchars($color_array[$index] ?? 'N/A'); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>

                    <a href="../index.php" class="btn btn-primary btn-continue">Tiếp tục mua sắm</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Shop Quần Áo</h5>
                    <p>Thời trang hiện đại, phong cách trẻ trung. Mua sắm dễ dàng, giao hàng nhanh chóng!</p>
                </div>
                <div class="col-md-4">
                    <h5>Liên Kết Hữu Ích</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Trang Chủ</a></li>
                        <li><a href="#">Sản Phẩm</a></li>
                        <li><a href="#">Liên Hệ</a></li>
                        <li><a href="#">Chính Sách Bảo Mật</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Liên Hệ</h5>
                    <p><i class="fas fa-map-marker-alt"></i> 123 Đường Thời Trang, TP. HCM</p>
                    <p><i class="fas fa-phone"></i> 0123 456 789</p>
                    <p><i class="fas fa-envelope"></i> shopquanao@example.com</p>
                </div>
            </div>
            <div class="text-center mt-4">
                <p>© 2025 Shop Quần Áo. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>