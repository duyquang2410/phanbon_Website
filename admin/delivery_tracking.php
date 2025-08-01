<?php
session_start();
include 'connect.php';
$active = 'delivery_tracking';
include 'head.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['NV_MA'])) {
    header('Location: sign_in.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theo dõi giao hàng</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8em;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .status-NEW { 
            background-color: #e3e3e3; 
            color: #000; 
        }
        .status-DELIVERING { 
            background-color: #ffd700; 
            color: #000;
            animation: pulse 1.5s infinite;
        }
        .status-DELIVERED { 
            background-color: #90EE90; 
            color: #000; 
        }
        .status-FAILED { 
            background-color: #ffcccb; 
            color: #000; 
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .delivery-track {
            position: relative;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            margin: 15px 0;
        }

        .delivery-progress {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            margin-bottom: 30px;
        }

        .progress-step {
            position: relative;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #fff;
            border: 2px solid #e91e63;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .progress-step.active {
            background: #e91e63;
            color: #fff;
        }

        .progress-step.completed {
            background: #4CAF50;
            border-color: #4CAF50;
            color: #fff;
        }

        .progress-line {
            position: absolute;
            top: 15px;
            left: 0;
            right: 0;
            height: 2px;
            background: #e0e0e0;
            z-index: 1;
        }

        .progress-line-fill {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            background: #e91e63;
            transition: width 0.5s ease;
        }

        .delivery-vehicle {
            position: absolute;
            top: -10px;
            left: 0;
            transform: translateX(-50%);
            z-index: 3;
            transition: left 0.5s ease;
        }

        .delivery-vehicle i {
            font-size: 24px;
            color: #e91e63;
            animation: bounce 1s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        .step-label {
            position: absolute;
            top: 40px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 12px;
            white-space: nowrap;
            color: #666;
        }

        .delivery-info {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-top: 20px;
        }

        .delivery-info-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 8px;
            border-bottom: 1px solid #eee;
        }

        .delivery-info-item i {
            margin-right: 10px;
            color: #e91e63;
        }

        .delivery-info-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .tracking-timeline {
            position: relative;
            padding: 20px 0;
        }

        .tracking-item {
            position: relative;
            padding-left: 30px;
            margin-bottom: 15px;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .tracking-item:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #e91e63;
            animation: ping 1s cubic-bezier(0, 0, 0.2, 1) infinite;
        }

        @keyframes ping {
            75%, 100% {
                transform: scale(2);
                opacity: 0;
            }
        }

        .tracking-item:after {
            content: '';
            position: absolute;
            left: 5px;
            top: 12px;
            width: 2px;
            height: calc(100% + 3px);
            background: #e91e63;
        }

        .tracking-item:last-child:after {
            display: none;
        }

        .tracking-date {
            font-size: 0.8em;
            color: #666;
            margin-bottom: 5px;
        }

        .tracking-status {
            font-weight: bold;
            margin-bottom: 5px;
            color: #e91e63;
        }

        .tracking-info {
            color: #666;
            line-height: 1.4;
        }

        .order-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="g-sidenav-show bg-gray-200">
    <?php include 'aside.php'; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                                <h6 class="text-white text-capitalize ps-3">
                                    <i class="fas fa-truck me-2"></i>Theo dõi đơn hàng
                                </h6>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Mã đơn</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Khách hàng</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Trạng thái</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Thời gian</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Theo dõi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="orderTableBody">
                                        <?php
                                        $sql = "SELECT ds.*, hd.HD_STT, kh.KH_TEN 
                                               FROM delivery_status ds
                                               JOIN hoa_don hd ON ds.HD_STT = hd.HD_STT
                                               JOIN khach_hang kh ON hd.KH_MA = kh.KH_MA
                                               ORDER BY ds.updated_at DESC";
                                        $result = $conn->query($sql);
                                        
                                        while ($row = $result->fetch_assoc()) {
                                            echo '<tr id="order-'.$row['HD_STT'].'" class="order-card">';
                                            echo '<td><div class="d-flex px-2 py-1"><div class="d-flex flex-column justify-content-center"><h6 class="mb-0 text-sm">'.$row['HD_STT'].'</h6></div></div></td>';
                                            echo '<td><p class="text-xs font-weight-bold mb-0">'.htmlspecialchars($row['KH_TEN']).'</p></td>';
                                            echo '<td class="align-middle text-center text-sm">';
                                            echo '<span class="status-badge status-'.$row['status'].'">';
                                            
                                            // Thêm icon cho từng trạng thái
                                            switch($row['status']) {
                                                case 'NEW':
                                                    echo '<i class="fas fa-box"></i> ';
                                                    break;
                                                case 'DELIVERING':
                                                    echo '<i class="fas fa-truck"></i> ';
                                                    break;
                                                case 'DELIVERED':
                                                    echo '<i class="fas fa-check-circle"></i> ';
                                                    break;
                                                case 'FAILED':
                                                    echo '<i class="fas fa-times-circle"></i> ';
                                                    break;
                                            }
                                            
                                            echo $row['status'].'</span></td>';
                                            echo '<td class="align-middle text-center"><span class="text-secondary text-xs font-weight-bold">'.date('d/m/Y H:i:s', strtotime($row['updated_at'])).'</span></td>';
                                            echo '<td class="align-middle">';
                                            
                                            // Thêm thanh tiến trình giao hàng
                                            echo '<div class="delivery-track">';
                                            echo '<div class="delivery-progress">';
                                            echo '<div class="progress-line">';
                                            
                                            // Tính phần trăm hoàn thành
                                            $progress = 0;
                                            switch($row['status']) {
                                                case 'NEW': $progress = 0; break;
                                                case 'DELIVERING': $progress = 50; break;
                                                case 'DELIVERED': $progress = 100; break;
                                            }
                                            
                                            echo '<div class="progress-line-fill" style="width: '.$progress.'%"></div>';
                                            echo '</div>';
                                            
                                            // Các bước trong quá trình
                                            $steps = [
                                                ['icon' => 'box', 'label' => 'Đã xác nhận', 'completed' => true],
                                                ['icon' => 'truck', 'label' => 'Đang giao', 'completed' => in_array($row['status'], ['DELIVERING', 'DELIVERED'])],
                                                ['icon' => 'check-circle', 'label' => 'Đã giao', 'completed' => $row['status'] === 'DELIVERED']
                                            ];
                                            
                                            foreach($steps as $index => $step) {
                                                $stepClass = $step['completed'] ? 'completed' : ($row['status'] === 'DELIVERING' && $index === 1 ? 'active' : '');
                                                echo '<div class="progress-step '.$stepClass.'">';
                                                echo '<i class="fas fa-'.$step['icon'].'"></i>';
                                                echo '<span class="step-label">'.$step['label'].'</span>';
                                                echo '</div>';
                                            }
                                            
                                            // Hiệu ứng xe giao hàng
                                            if ($row['status'] === 'DELIVERING') {
                                                echo '<div class="delivery-vehicle" style="left: '.$progress.'%">';
                                                echo '<i class="fas fa-truck"></i>';
                                                echo '</div>';
                                            }
                                            
                                            echo '</div>';
                                            
                                            // Thông tin chi tiết
                                            echo '<div class="delivery-info">';
                                            $tracking_info_array = explode("\n", $row['tracking_info']);
                                            foreach ($tracking_info_array as $info) {
                                                if (!empty($info)) {
                                                    echo '<div class="delivery-info-item">';
                                                    echo '<i class="fas fa-info-circle"></i>';
                                                    echo '<span>'.htmlspecialchars($info).'</span>';
                                                    echo '</div>';
                                                }
                                            }
                                            echo '</div>';
                                            echo '</div>';
                                            
                                            echo '</td>';
                                            echo '</tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function updateDeliveryStatus() {
            $.ajax({
                url: 'update_delivery_status.php',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.updated_orders && response.updated_orders.length > 0) {
                        response.updated_orders.forEach(function(order) {
                            var row = $('#order-' + order.order_id);
                            if (row.length) {
                                // Cập nhật trạng thái
                                var statusIcon = '';
                                switch(order.status) {
                                    case 'NEW': statusIcon = '<i class="fas fa-box"></i> '; break;
                                    case 'DELIVERING': statusIcon = '<i class="fas fa-truck"></i> '; break;
                                    case 'DELIVERED': statusIcon = '<i class="fas fa-check-circle"></i> '; break;
                                    case 'FAILED': statusIcon = '<i class="fas fa-times-circle"></i> '; break;
                                }
                                
                                row.find('.status-badge')
                                   .removeClass('status-NEW status-DELIVERING status-DELIVERED status-FAILED')
                                   .addClass('status-' + order.status)
                                   .html(statusIcon + order.status);

                                // Cập nhật tiến trình
                                var progress = 0;
                                switch(order.status) {
                                    case 'NEW': progress = 0; break;
                                    case 'DELIVERING': progress = 50; break;
                                    case 'DELIVERED': progress = 100; break;
                                }

                                var track = row.find('.delivery-track');
                                track.find('.progress-line-fill').css('width', progress + '%');
                                
                                // Cập nhật các bước
                                track.find('.progress-step').removeClass('completed active');
                                track.find('.progress-step').each(function(index) {
                                    if (index === 0 || 
                                        (index === 1 && ['DELIVERING', 'DELIVERED'].includes(order.status)) ||
                                        (index === 2 && order.status === 'DELIVERED')) {
                                        $(this).addClass('completed');
                                    }
                                    if (order.status === 'DELIVERING' && index === 1) {
                                        $(this).addClass('active');
                                    }
                                });

                                // Cập nhật xe giao hàng
                                track.find('.delivery-vehicle').remove();
                                if (order.status === 'DELIVERING') {
                                    var vehicle = $('<div class="delivery-vehicle" style="left: ' + progress + '%"><i class="fas fa-truck"></i></div>');
                                    track.find('.delivery-progress').append(vehicle);
                                }

                                // Cập nhật thông tin chi tiết
                                var infoHtml = '';
                                order.tracking_info.split('\n').forEach(function(info) {
                                    if (info.trim()) {
                                        infoHtml += '<div class="delivery-info-item">';
                                        infoHtml += '<i class="fas fa-info-circle"></i>';
                                        infoHtml += '<span>' + info + '</span>';
                                        infoHtml += '</div>';
                                    }
                                });
                                track.find('.delivery-info').html(infoHtml);

                                // Hiệu ứng khi cập nhật
                                row.addClass('highlight');
                                setTimeout(function() {
                                    row.removeClass('highlight');
                                }, 1000);

                                // Thông báo khi giao hàng thành công
                                if (order.status === 'DELIVERED') {
                                    Swal.fire({
                                        title: 'Giao hàng thành công!',
                                        text: 'Đơn hàng #' + order.order_id + ' đã được giao thành công',
                                        icon: 'success',
                                        timer: 3000,
                                        showConfirmButton: false
                                    });
                                }
                            }
                        });
                    }
                }
            });
        }

        // Cập nhật mỗi 5 giây
        setInterval(updateDeliveryStatus, 5000);

        // Chạy lần đầu khi tải trang
        $(document).ready(function() {
        updateDeliveryStatus();
        });
    </script>

    <?php include 'billing_scripts.php'; ?>
</body>
</html> 