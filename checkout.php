<?php
require_once 'error_log.php';
require_once 'config.php';
require_once 'connect.php';
require_once 'cart_functions.php';

$logger = ErrorLogger::getInstance('logs/checkout.log');

session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    $_SESSION['login_required_message'] = "Vui lòng đăng nhập để thanh toán.";
    header("Location: login.php");
    exit();
}

// Lấy thông tin giỏ hàng
$user_id = $_SESSION['user_id'];
$cart_id = getCurrentCart($conn, $user_id);
$cart_data = getCartItems($conn, $cart_id);

// Lấy thông tin khách hàng
$user_sql = "SELECT * FROM khach_hang WHERE KH_MA = ?";
$stmt = $conn->prepare($user_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user_info = $user_result->fetch_assoc();

// Xử lý mã khuyến mãi
$promo_discount = 0;
$promo_message = '';
$total_discount = 0;

// Xử lý mã khuyến mãi chung
if (isset($_POST['promo_code']) && !empty($_POST['promo_code'])) {
    $promo = applyPromoCode($conn, trim($_POST['promo_code']));
    if ($promo['valid']) {
        $promo_discount = ($_POST['total_amount'] ?? 0) * ($promo['value'] / 100);
        $total_discount += $promo_discount;
    } else {
        $promo_message = $promo['message'] ?? 'Mã khuyến mãi chung không hợp lệ';
    }
}

// Xử lý mã khuyến mãi theo sản phẩm
$item_discounts = [];
if (!empty($_POST['selected_items'])) {
    foreach ($_POST['selected_items'] as $product_id) {
        $promo_code = isset($_POST['promo_code_item'][$product_id]) ? trim($_POST['promo_code_item'][$product_id]) : '';
        if ($promo_code) {
            $promo = applyPromoCode($conn, $promo_code);
            if ($promo['valid']) {
                $item_price = isset($_POST['item_price'][$product_id]) ? (float)$_POST['item_price'][$product_id] : 0;
                $item_quantity = isset($_POST['item_quantity'][$product_id]) ? (int)$_POST['item_quantity'][$product_id] : 1;
                $item_subtotal = $item_price * $item_quantity;
                $discount = $item_subtotal * ($promo['value'] / 100);
                $item_discounts[$product_id] = $discount;
                $total_discount += $discount;
            } else {
                $item_discounts[$product_id] = 0;
                $promo_message = $promo['message'] ?? 'Mã khuyến mãi cho sản phẩm không hợp lệ';
            }
        }
    }
}

// Calculate total weight and order value
$total_weight = 0;
$total_value = 0;
if (!empty($_POST['selected_items'])) {
    foreach ($_POST['selected_items'] as $product_id) {
        $item_price = isset($_POST['item_price'][$product_id]) ? (float)$_POST['item_price'][$product_id] : 0;
        $item_quantity = isset($_POST['item_quantity'][$product_id]) ? (int)$_POST['item_quantity'][$product_id] : 1;
        $item_subtotal = $item_price * $item_quantity;
        $total_value += $item_subtotal;
        // Giả định mỗi sản phẩm có trọng lượng 1kg (1000g)
        $total_weight += 1000 * $item_quantity;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/checkout.css">
    <style>
    .product-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    .total-row {
        font-size: 18px;
        font-weight: 600;
        border-top: 2px solid #ddd;
        padding-top: 10px;
    }
    .btn-checkout {
        background: #27ae60;
        color: white;
        padding: 15px 30px;
        border-radius: 4px;
        border: none;
        width: 100%;
        font-size: 16px;
        font-weight: 600;
        margin-top: 20px;
    }
    .btn-checkout:hover {
        background: #219a52;
    }
    .promo-error {
        color: #e74c3c;
        font-size: 14px;
        margin-top: 5px;
    }
    .promo-code {
        margin-top: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 4px;
    }
    .promo-item {
        text-decoration: none;
        color: inherit;
    }
    .promo-item:hover {
        background-color: #f8f9fa;
    }
    .promo-item small.text-success {
        font-weight: 600;
    }
    </style>
</head>

<body class="bg-light">
    <?php include 'header.php'; ?>
    <div class="container py-5">
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Thông tin giao hàng</h5>
                        <form id="checkoutForm" method="POST" action="process_order.php">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="fullName" class="form-label">Họ và tên</label>
                                    <input type="text" class="form-control" id="fullName" name="fullName" 
                                           value="<?php echo htmlspecialchars($user_info['KH_TEN']); ?>" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="<?php echo htmlspecialchars($user_info['KH_SDT']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($user_info['KH_EMAIL']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="street_address" class="form-label">Số nhà, tên đường</label>
                                <input type="text" class="form-control" id="street_address" name="street_address" 
                                       placeholder="Nhập số nhà, tên đường" required>
                            </div>
                            <div class="mb-3">
                                <label for="province" class="form-label">Tỉnh/Thành phố</label>
                                <select class="form-select" id="province" name="province" data-placeholder="Tỉnh/Thành phố" required>
                                    <option value="">Chọn Tỉnh/Thành phố</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="district" class="form-label">Quận/Huyện</label>
                                <select class="form-select" id="district" name="district" data-placeholder="Quận/Huyện" required>
                                    <option value="">Chọn Quận/Huyện</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="ward" class="form-label">Phường/Xã</label>
                                <select class="form-select" id="ward" name="ward" data-placeholder="Phường/Xã" required>
                                    <option value="">Chọn Phường/Xã</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Địa chỉ đầy đủ</label>
                                <input type="text" class="form-control" id="address" name="address" readonly>
                            </div>

                            <!-- Hidden pickup information -->
                            <input type="hidden" name="pick_province" value="1">
                            <input type="hidden" name="pick_district" value="1">
                            <input type="hidden" name="pick_ward" value="1">
                            <input type="hidden" name="pick_address" value="123 Đường Láng, Quận Ba Đình, Hà Nội">

                            <!-- Phí vận chuyển -->
                            <div class="mb-3">
                                <label>Phí vận chuyển: </label>
                                <span id="shipping-fee" style="font-weight:bold;color:#27ae60;">Đang tính...</span>
                                <input type="hidden" name="shipping_fee" value="0">
                                <div id="error-message" class="text-danger" style="display: none;"></div>
                            </div>

                            <!-- Ghi chú -->
                            <div class="mb-3">
                                <label for="note" class="form-label">Ghi chú đơn hàng</label>
                                <textarea class="form-control" id="note" name="note" rows="3" 
                                          placeholder="Ghi chú về đơn hàng, ví dụ: thời gian hay chỉ dẫn địa điểm giao hàng chi tiết hơn."></textarea>
                            </div>

                            <!-- Phương thức thanh toán -->
                            <div class="mb-4">
                                <h5 class="card-title mb-3">Phương thức thanh toán</h5>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="cod" value="1" checked>
                                    <label class="form-check-label" for="cod">
                                        <i class="fa fa-money me-2"></i>Thanh toán khi giao hàng (COD)
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="bank" value="2">
                                    <label class="form-check-label" for="bank">
                                        <i class="fa fa-bank me-2"></i>Chuyển khoản ngân hàng
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="card" value="3">
                                    <label class="form-check-label" for="card">
                                        <i class="fa fa-credit-card me-2"></i>Thẻ Visa/Mastercard/Amex
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="momo" value="4">
                                    <label class="form-check-label" for="momo">
                                        <i class="fa fa-mobile me-2"></i>Thanh toán qua ví MoMo
                                    </label>
                                </div>
                            </div>

                            <!-- Hidden inputs -->
                            <?php foreach ($_POST['selected_items'] as $product_id): ?>
                            <input type="hidden" name="selected_items[]" value="<?php echo htmlspecialchars($product_id); ?>">
                            <input type="hidden" name="item_quantity[<?php echo htmlspecialchars($product_id); ?>]" 
                                   value="<?php echo htmlspecialchars($_POST['item_quantity'][$product_id] ?? 1); ?>">
                            <input type="hidden" name="item_price[<?php echo htmlspecialchars($product_id); ?>]"
                                   value="<?php echo htmlspecialchars($_POST['item_price'][$product_id] ?? 0); ?>">
                            <input type="hidden" name="item_name[<?php echo htmlspecialchars($product_id); ?>]"
                                   value="<?php echo htmlspecialchars($_POST['item_name'][$product_id] ?? ''); ?>">
                            <input type="hidden" name="item_unit[<?php echo htmlspecialchars($product_id); ?>]"
                                   value="<?php echo htmlspecialchars($_POST['item_unit'][$product_id] ?? ''); ?>">
                            <input type="hidden" name="item_image[<?php echo htmlspecialchars($product_id); ?>]"
                                   value="<?php echo htmlspecialchars($_POST['item_image'][$product_id] ?? ''); ?>">
                            <?php endforeach; ?>
                            
                            <input type="hidden" name="total_amount" value="<?php echo htmlspecialchars($_POST['total_amount'] ?? 0); ?>">
                            <input type="hidden" name="total_weight" value="<?php echo $total_weight; ?>">
                            <input type="hidden" name="total_value" value="<?php echo $total_value; ?>">
                        </form>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Đơn hàng của bạn</h5>
                        <div class="order-items mb-4">
                            <?php foreach ($_POST['selected_items'] as $product_id): 
                                $image_path = isset($_POST['item_image'][$product_id]) && !empty($_POST['item_image'][$product_id])
                                    ? 'img/' . htmlspecialchars($_POST['item_image'][$product_id])
                                    : 'img/default-product.jpg';
                                $item_discount = $item_discounts[$product_id] ?? 0;
                            ?>
                            <div class="product-item d-flex align-items-center mb-3">
                                <img src="<?php echo $image_path; ?>" alt="" class="product-image me-3">
                                <div class="product-info flex-grow-1">
                                    <h6 class="mb-1"><?php echo htmlspecialchars($_POST['item_name'][$product_id]); ?></h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted">SL: <?php echo htmlspecialchars($_POST['item_quantity'][$product_id]); ?></span>
                                        <span class="text-danger"><?php echo number_format($_POST['item_price'][$product_id], 0, ',', '.'); ?>đ</span>
                                    </div>
                                    <?php if ($item_discount > 0): ?>
                                    <div class="text-danger small mt-1">Giảm: -<?php echo number_format($item_discount, 0, ',', '.'); ?>đ</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="order-summary">
                            <h6 class="mb-3">Tóm tắt đơn hàng</h6>
                            <div class="summary-row">
                                <span>Tổng tiền hàng</span>
                                <span class="text-dark"><?php echo number_format($_POST['total_amount'] ?? 0, 0, ',', '.'); ?>đ</span>
                            </div>
                            <div class="summary-row">
                                <span>Phí vận chuyển</span>
                                <span id="shipping-fee-summary" class="text-success">Đang tính...</span>
                            </div>
                            <div class="summary-row" id="discount-row" style="display: none;">
                                <span>Giảm giá</span>
                                <span class="text-danger">
                                    -<span id="discount-amount">0</span>đ
                                    <small id="discount-percent-text" style="display: none;">
                                        (<span id="discount-percent">0</span>%)
                                    </small>
                                </span>
                            </div>
                            <div class="summary-row total-row">
                                <span class="fw-bold">Tổng thanh toán</span>
                                <span id="total-payment" class="text-danger fw-bold"></span>
                            </div>
                        </div>

                        <!-- Phần khuyến mãi -->
                        <div class="promo-code mb-3">
                            <h6 class="mb-3">Mã khuyến mãi</h6>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Nhập mã khuyến mãi" id="promoInput"
                                    value="<?php echo htmlspecialchars($_POST['promo_code'] ?? ''); ?>">
                                <select class="form-select" id="promoType" style="max-width: 200px;">
                                    <option value="all">Áp dụng cho tất cả</option>
                                    <?php foreach ($_POST['selected_items'] ?? [] as $product_id): ?>
                                    <option value="<?php echo htmlspecialchars($product_id); ?>">
                                        <?php echo htmlspecialchars($_POST['item_name'][$product_id]); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <button class="btn btn-success" type="button" id="applyPromo">Áp dụng</button>
                            </div>
                            <div class="promo-error mt-2" style="display: none;"></div>
                            <input type="hidden" name="promo_code" id="promoCodeInput">
                            <button class="btn btn-outline-secondary w-100 mt-2" type="button" data-bs-toggle="modal" data-bs-target="#promoModal">
                                <i class="fa fa-ticket me-2"></i>Chọn mã
                            </button>
                            <?php if (isset($promo_message)): ?>
                            <div class="promo-error"><?php echo htmlspecialchars($promo_message); ?></div>
                            <?php endif; ?>
                        </div>

                        <button type="submit" form="checkoutForm" class="btn-checkout">
                            <i class="fa fa-check me-2"></i>Đặt hàng
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal khuyến mãi -->
    <div class="modal fade" id="promoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chọn mã khuyến mãi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="list-group">
                        <?php
                        $promo_sql = "SELECT * FROM khuyen_mai WHERE KM_TGKT >= CURDATE()";
                        $promo_result = $conn->query($promo_sql);
                        while ($promo = $promo_result->fetch_assoc()):
                        ?>
                        <a href="#" class="list-group-item list-group-item-action promo-item"
                            data-code="<?php echo htmlspecialchars($promo['Code']); ?>"
                            data-discount="<?php echo $promo['KM_GIATRI']; ?>">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><?php echo htmlspecialchars($promo['Code']); ?></h6>
                                <small class="text-success">-<?php echo $promo['KM_GIATRI']; ?>%</small>
                            </div>
                            <?php if (!empty($promo['hinh_thuc_km'])): ?>
                            <p class="mb-1 small">Hình thức: <?php echo htmlspecialchars($promo['hinh_thuc_km']); ?></p>
                            <?php endif; ?>
                            <small class="text-muted">HSD: <?php echo date('d/m/Y', strtotime($promo['KM_TGKT'])); ?></small>
                        </a>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/address.js"></script>
    <script src="js/shipping.js"></script>
    <script src="js/promo.js"></script>
    <script>
        // Đảm bảo các script được load theo đúng thứ tự
        document.addEventListener('DOMContentLoaded', async function() {
            try {
                // Khởi tạo AddressSelector trước
                window.addressSelector = new AddressSelector();
                await window.addressSelector.init();
                console.log('AddressSelector initialized');

                // Sau đó khởi tạo ShippingCalculator
                window.shippingCalculator = new ShippingCalculator();
                console.log('ShippingCalculator initialized');

                // Cuối cùng khởi tạo ShippingManager
                window.shippingManager = new ShippingManager();
                console.log('ShippingManager initialized');
            } catch (error) {
                console.error('Error initializing components:', error);
                const errorContainer = document.getElementById('error-message');
                if (errorContainer) {
                    errorContainer.textContent = 'Lỗi khởi tạo hệ thống. Vui lòng tải lại trang.';
                    errorContainer.style.display = 'block';
                }
            }
        });
    </script>
</body>

</html>