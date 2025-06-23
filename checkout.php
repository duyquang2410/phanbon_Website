<?php
require_once 'config.php';
require_once 'error_log.php';
require_once 'connect.php';
require_once 'cart_functions.php';

session_start();

$logger = ErrorLogger::getInstance('logs/checkout.log');

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
                            <!-- Hidden inputs for promo codes -->
                            <input type="hidden" name="promo_code" id="promoCodeInput" value="<?php echo isset($_POST['promo_code']) ? htmlspecialchars($_POST['promo_code']) : ''; ?>">
                            <?php if (!empty($_POST['promo_code_item'])): ?>
                                <?php foreach ($_POST['promo_code_item'] as $product_id => $code): ?>
                                    <input type="hidden" name="promo_code_item[<?php echo $product_id; ?>]" value="<?php echo htmlspecialchars($code); ?>">
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <!-- Thông tin cá nhân -->
                            <div class="shipping-section mb-4">
                                <h6 class="section-title">
                                    <i class="fa fa-user-circle me-2"></i>Thông tin cá nhân
                                </h6>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="fullName" name="fullName" 
                                                           value="<?php echo htmlspecialchars($user_info['KH_TEN']); ?>" required>
                                                    <label for="fullName">Họ và tên</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                                           value="<?php echo htmlspecialchars($user_info['KH_SDT']); ?>" required>
                                                    <label for="phone">Số điện thoại</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="email" class="form-control" id="email" name="email" 
                                                           value="<?php echo htmlspecialchars($user_info['KH_EMAIL']); ?>" required>
                                                    <label for="email">Email</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Địa chỉ giao hàng -->
                            <div class="shipping-section mb-4">
                                <h6 class="section-title">
                                    <i class="fa fa-map-marker me-2"></i>Địa chỉ giao hàng
                                </h6>
                                <div class="card">
                                    <div class="card-body">
                                        <div id="address-error" class="alert alert-danger d-none"></div>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="street_address" name="street_address" 
                                                           placeholder="Nhập số nhà, tên đường" required>
                                                    <label for="street_address">Số nhà, tên đường</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating">
                                                    <select class="form-select" id="province" name="province" required>
                                                        <option value="">Chọn Tỉnh/Thành phố</option>
                                                    </select>
                                                    <label for="province">Tỉnh/Thành phố</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating">
                                                    <select class="form-select" id="district" name="district" required>
                                                        <option value="">Chọn Quận/Huyện</option>
                                                    </select>
                                                    <label for="district">Quận/Huyện</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating">
                                                    <select class="form-select" id="ward" name="ward" required>
                                                        <option value="">Chọn Phường/Xã</option>
                                                    </select>
                                                    <label for="ward">Phường/Xã</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label text-muted mb-2">Địa chỉ đầy đủ</label>
                                                <div id="full-address-display" class="form-control-plaintext p-3 bg-light rounded border">
                                                    <i class="fa fa-info-circle me-2 text-primary"></i>
                                                    <span>Chưa có địa chỉ</span>
                                                </div>
                                                <input type="hidden" id="full_address" name="full_address">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Phương thức vận chuyển -->
                            <div class="shipping-section mb-4">
                                <h6 class="section-title">
                                    <i class="fa fa-truck me-2"></i>Phương thức vận chuyển
                                </h6>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="shipping-methods">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="radio" name="shipping_method" id="standard" value="standard" checked>
                                                <label class="form-check-label d-flex align-items-center" for="standard">
                                                    <span class="shipping-icon me-3">
                                                        <i class="fa fa-truck text-success"></i>
                                                    </span>
                                                    <span class="shipping-info flex-grow-1">
                                                        <span class="d-block fw-bold">Giao hàng tiêu chuẩn</span>
                                                        <span class="text-muted small">Thời gian giao hàng 3-5 ngày</span>
                                                    </span>
                                                    <span id="shipping-fee" class="shipping-fee text-success"></span>
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="shipping_method" id="express" value="express">
                                                <label class="form-check-label d-flex align-items-center" for="express">
                                                    <span class="shipping-icon me-3">
                                                        <i class="fa fa-shipping-fast text-primary"></i>
                                                    </span>
                                                    <span class="shipping-info flex-grow-1">
                                                        <span class="d-block fw-bold">Giao hàng nhanh</span>
                                                        <span class="text-muted small">Thời gian giao hàng 1-2 ngày</span>
                                                    </span>
                                                    <span class="shipping-fee text-primary">
                                                        <span class="small">(Phụ phí 50%)</span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Phương thức thanh toán -->
                            <div class="shipping-section mb-4">
                                <h6 class="section-title">
                                    <i class="fa fa-credit-card me-2"></i>Phương thức thanh toán
                                </h6>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="radio" name="paymentMethod" id="cod" value="1" checked>
                                            <label class="form-check-label d-flex align-items-center" for="cod">
                                                <i class="fa fa-money me-3 text-success"></i>
                                                <span>Thanh toán khi nhận hàng (COD)</span>
                                            </label>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="radio" name="paymentMethod" id="bank" value="2">
                                            <label class="form-check-label d-flex align-items-center" for="bank">
                                                <i class="fa fa-bank me-3 text-primary"></i>
                                                <span>Chuyển khoản ngân hàng</span>
                                            </label>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="radio" name="paymentMethod" id="card" value="3">
                                            <label class="form-check-label d-flex align-items-center" for="card">
                                                <i class="fa fa-credit-card me-3 text-info"></i>
                                                <span>Thẻ Visa/Mastercard/Amex</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="paymentMethod" id="momo" value="4">
                                            <label class="form-check-label d-flex align-items-center" for="momo">
                                                <i class="fa fa-mobile me-3 text-danger"></i>
                                                <span>Thanh toán qua ví MoMo</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden inputs -->
                            <input type="hidden" name="shipping_fee">
                            <input type="hidden" name="total_weight" value="1000">
                            <input type="hidden" name="total_value" value="<?php echo $total_value; ?>">
                            <input type="hidden" name="total_amount" value="<?php echo $total_value; ?>">
                            <input type="hidden" name="total_discount" value="<?php echo $total_discount; ?>">
                            <?php 
                            if (isset($_POST['selected_items']) && is_array($_POST['selected_items'])) {
                                foreach ($_POST['selected_items'] as $product_id): 
                            ?>
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
                            <?php 
                                endforeach;
                            } 
                            ?>
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
                            <?php 
                            if (isset($_POST['selected_items']) && is_array($_POST['selected_items'])) {
                                foreach ($_POST['selected_items'] as $product_id): 
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
                            <?php 
                                endforeach;
                            } else {
                                echo '<div class="alert alert-warning">Không có sản phẩm nào được chọn</div>';
                            }
                            ?>
                        </div>

                        <div class="card mb-4">
                            <div class="card-body">
                                <h6 class="section-title">
                                    <i class="fa fa-receipt me-2"></i>Tóm tắt đơn hàng
                                </h6>
                                <div class="summary-row">
                                    <span>Tổng tiền hàng</span>
                                    <span class="text-dark"><?php echo number_format($total_value, 0, ',', '.'); ?>đ</span>
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
                                    <span id="total-payment" class="text-danger fw-bold">
                                        <?php echo number_format($total_value, 0, ',', '.'); ?>đ
                                    </span>
                                </div>
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
                                    <?php 
                                    if (isset($_POST['selected_items']) && is_array($_POST['selected_items'])) {
                                        foreach ($_POST['selected_items'] as $product_id): 
                                    ?>
                                    <option value="<?php echo htmlspecialchars($product_id); ?>">
                                        <?php echo htmlspecialchars($_POST['item_name'][$product_id] ?? 'Sản phẩm không xác định'); ?>
                                    </option>
                                    <?php 
                                        endforeach;
                                    }
                                    ?>
                                </select>
                                <button class="btn btn-success" type="button" id="applyPromo">Áp dụng</button>
                            </div>
                            <div class="promo-error mt-2" style="display: none;"></div>
                            <button class="btn btn-outline-secondary w-100 mt-2" type="button" data-bs-toggle="modal" data-bs-target="#promoModal">
                                <i class="fa fa-ticket me-2"></i>Chọn mã
                            </button>
                            <?php if (isset($promo_message)): ?>
                            <div class="promo-error"><?php echo htmlspecialchars($promo_message); ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Error message container -->
                        <div id="error-message" class="alert alert-danger" style="display: none;"></div>

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

    <!-- Scripts -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/shipping.js"></script>
    <script src="js/address.js"></script>
    <script src="js/promo.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            try {
                // Initialize ShippingManager first
                if (typeof ShippingManager !== 'undefined') {
                    window.shippingManager = new ShippingManager();
                    console.log('ShippingManager initialized');
                } else {
                    console.error('ShippingManager not found');
                }

                // Initialize AddressManager
                if (typeof AddressManager !== 'undefined') {
                    window.addressManager = new AddressManager({
                        pickProvince: "1", // Hà Nội
                        pickDistrict: "1", // Ba Đình
                        pickWard: "1", // Default ward
                        pickAddress: "123 Đường Láng" // Default address
                    });
                    console.log('AddressManager initialized');
                } else {
                    console.error('AddressManager not found');
                }

                // Initialize PromoManager
                if (typeof PromoManager !== 'undefined') {
                    window.promoManager = new PromoManager();
                    console.log('PromoManager initialized');
                } else {
                    console.error('PromoManager not found');
                }

            } catch (error) {
                console.error('Error initializing components:', error);
            }
        });
    </script>
</body>

</html>