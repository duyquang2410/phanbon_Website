<?php
session_start();
$message = isset($_GET['message']) ? $_GET['message'] : 'Có lỗi xảy ra';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Lỗi - Quản lý cửa hàng phân bón</title>
    
    <!-- Fonts and icons -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    
    <!-- CSS Files -->
    <link href="../asset_admin/css/material-dashboard.css" rel="stylesheet" />
    
    <style>
        .error-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }
        
        .error-card {
            max-width: 500px;
            width: 100%;
            text-align: center;
            padding: 2rem;
        }
        
        .error-icon {
            font-size: 4rem;
            color: #f44335;
            margin-bottom: 1rem;
        }
        
        .error-title {
            font-size: 1.5rem;
            color: #344767;
            margin-bottom: 1rem;
        }
        
        .error-message {
            color: #7b809a;
            margin-bottom: 2rem;
        }
        
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
    </style>
</head>

<body>
    <div class="error-page">
        <div class="card error-card">
            <div class="card-body">
                <i class="material-icons error-icon">error_outline</i>
                <h5 class="error-title">Lỗi truy cập</h5>
                <p class="error-message"><?php echo htmlspecialchars($message); ?></p>
                <a href="index.php" class="btn bg-gradient-primary back-button">
                    <i class="material-icons">arrow_back</i>
                    <span>Quay lại trang chủ</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Core JS Files -->
    <script src="../asset_admin/js/core/popper.min.js"></script>
    <script src="../asset_admin/js/core/bootstrap.min.js"></script>
</body>
</html> 