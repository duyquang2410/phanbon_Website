<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Plants Shop - Giỏ Hàng</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/global.css" rel="stylesheet">
    <link href="css/cart.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <?php
// Start the session
session_start();

// Include file kết nối và các hàm giỏ hàng
include 'connect.php';
include 'cart_functions.php';

// Chỉ cho phép người dùng đã đăng nhập xem giỏ hàng
if (!isset($_SESSION['user_id'])) {
    $_SESSION['login_required_message'] = "Vui lòng đăng nhập để xem giỏ hàng.";
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$cart_id = getCurrentCart($conn, $user_id);
$cart_data = getCartItems($conn, $cart_id);
$cart_items = $cart_data['items'];
$cart_total = $cart_data['total'];
$cart_count = $cart_data['count'];

// Xử lý áp dụng mã giảm giá
$discount = 0;
$promo_code = '';
$promo_error = '';
$promo_success = '';

if (isset($_POST['apply_promo']) && isset($_POST['promo_code'])) {
    $promo_code = trim($_POST['promo_code']);
    $promo_result = applyPromoCode($conn, $promo_code);
    
    if ($promo_result['valid']) {
        $discount = $cart_total * ($promo_result['value'] / 100);
        $_SESSION['promo_code'] = $promo_code;
        $_SESSION['promo_id'] = $promo_result['id'];
        $_SESSION['promo_value'] = $promo_result['value'];
        $promo_success = "Đã áp dụng mã giảm giá " . $promo_result['value'] . "%.";
    } else {
        $promo_error = "Mã giảm giá không hợp lệ hoặc đã hết hạn.";
    }
} elseif (isset($_SESSION['promo_code'])) {
    $promo_code = $_SESSION['promo_code'];
    $discount = $cart_total * ($_SESSION['promo_value'] / 100);
}

$final_total = $cart_total - $discount;
?>

    <!-- Header -->
    <?php include 'header.php'; ?>



    <section id="center" class="center_o pt-5 pb-5">
        <div class="container-fluid">
            <div class="center_o1 row text-center">
                <div class="col-md-12">
                    <h1>Giỏ Hàng</h1>
                    <h6 class="font_14 mb-0 mt-3"><a href="index.php">Trang Chủ </a> <span
                            class="text-muted mx-1 font_10 align-middle"><i class="fa fa-chevron-right"></i></span> Giỏ
                        Hàng</h6>
                </div>
            </div>
        </div>
    </section>

    <section id="cart" class="cart_page pt-4 pb-5 bg_light">
        <div class="container">
            <?php if (count($cart_items) > 0): ?>
            <form id="checkoutForm" action="checkout.php" method="POST">
                <div class="row">
                    <div class="col-md-8">
                        <div class="cart_l bg-white p-4">
                            <h2 class="mb-4">Giỏ Hàng Của Bạn</h2>
                            <div class="table-responsive cart_1">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="5%">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="checkAll">
                                                </div>
                                            </th>
                                            <th>Sản Phẩm</th>
                                            <th>Giá</th>
                                            <th>Số Lượng</th>
                                            <th>Đơn Vị</th>
                                            <th>Tổng Tiền</th>
                                            <th>Xóa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($cart_items as $item): ?>
                                        <tr class="cart-item">
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input item-checkbox" type="checkbox"
                                                        name="selected_items[]" value="<?php echo $item['id']; ?>"
                                                        data-price="<?php echo $item['price']; ?>"
                                                        data-quantity="<?php echo $item['quantity']; ?>"
                                                        <?php echo $item['is_selected'] == 1 ? 'checked' : ''; ?>>
                                                    <input type="hidden"
                                                        name="item_quantity[<?php echo $item['id']; ?>]"
                                                        value="<?php echo $item['quantity']; ?>"
                                                        class="hidden-quantity">
                                                    <input type="hidden" name="item_price[<?php echo $item['id']; ?>]"
                                                        value="<?php echo $item['price']; ?>">
                                                    <input type="hidden" name="item_name[<?php echo $item['id']; ?>]"
                                                        value="<?php echo htmlspecialchars($item['name']); ?>">
                                                    <input type="hidden" name="item_unit[<?php echo $item['id']; ?>]"
                                                        value="<?php echo htmlspecialchars($item['unit']); ?>">
                                                    <input type="hidden" name="item_image[<?php echo $item['id']; ?>]"
                                                        value="<?php echo htmlspecialchars($item['image']); ?>">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex cart_1i1">
                                                    <div class="cart_1i1l">
                                                        <img src="img/<?php echo htmlspecialchars($item['image']); ?>"
                                                            alt="<?php echo htmlspecialchars($item['name']); ?>"
                                                            class="img-fluid">
                                                    </div>
                                                    <div class="cart_1i1r">
                                                        <h5>
                                                            <a href="detail.php?id=<?php echo $item['id']; ?>">
                                                                <?php echo htmlspecialchars($item['name']); ?>
                                                            </a>
                                                        </h5>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="price" data-price="<?php echo $item['price']; ?>">
                                                <?php echo number_format($item['price'], 0, ',', '.'); ?>đ
                                            </td>
                                            <td>
                                                <div
                                                    class="quantity-control d-flex align-items-center justify-content-center">
                                                    <button type="button" class="btn btn-outline-secondary btn-decrease"
                                                        data-product-id="<?php echo $item['id']; ?>"
                                                        data-unit="<?php echo htmlspecialchars($item['unit']); ?>">
                                                        <i class="fa fa-minus"></i>
                                                    </button>
                                                    <input type="number" class="form-control quantity-input mx-2"
                                                        value="<?php echo (int)$item['quantity']; ?>"
                                                        data-product-id="<?php echo $item['id']; ?>"
                                                        data-price="<?php echo $item['price']; ?>"
                                                        data-unit="<?php echo htmlspecialchars($item['unit']); ?>"
                                                        min="1" step="1">
                                                    <button type="button" class="btn btn-outline-secondary btn-increase"
                                                        data-product-id="<?php echo $item['id']; ?>"
                                                        data-unit="<?php echo htmlspecialchars($item['unit']); ?>">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="text-center"><?php echo htmlspecialchars($item['unit']); ?></td>
                                            <td class="subtotal" data-product-id="<?php echo $item['id']; ?>">
                                                <?php echo number_format($item['subtotal'], 0, ',', '.'); ?>đ
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-danger btn-sm delete-item"
                                                    data-product-id="<?php echo $item['id']; ?>">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="cart_1 mt-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <a href="shop.php" class="btn btn-outline-secondary">
                                            <i class="fa fa-arrow-left me-2"></i>Tiếp tục mua sắm
                                        </a>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <a href="cart_clear.php" class="btn btn-danger"
                                            onclick="return confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?');">
                                            <i class="fa fa-trash me-2"></i>Xóa giỏ hàng
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="cart_r bg-white p-4">
                            <h4>Tổng Giỏ Hàng</h4>
                            <div class="cart_r1 p-3">
                                <div class="cart_r1i row">
                                    <div class="col-6">
                                        <h6>Số sản phẩm đã chọn:</h6>
                                    </div>
                                    <div class="col-6 text-end">
                                        <h6 id="selectedCount">0</h6>
                                    </div>
                                </div>
                                <div class="cart_r1i row">
                                    <div class="col-6">
                                        <h6>Tổng tiền hàng:</h6>
                                    </div>
                                    <div class="col-6 text-end">
                                        <h6 id="selectedTotal">0đ</h6>
                                    </div>
                                </div>
                                <hr>
                                <div class="cart_r1i row">
                                    <div class="col-6">
                                        <h5>Tổng thanh toán:</h5>
                                    </div>
                                    <div class="col-6 text-end">
                                        <h5 class="col_green" id="finalTotal">0đ</h5>
                                    </div>
                                </div>
                                <hr>
                                <div class="cart_r1i text-center">
                                    <button type="submit" class="btn btn-success w-100" id="checkoutBtn" disabled>
                                        <i class="fa fa-credit-card me-2"></i>Tiến hành thanh toán
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="total_amount" id="totalAmountInput" value="0">
                <input type="hidden" name="total_items" id="totalItemsInput" value="0">
            </form>
            <?php else: ?>
            <div class="text-center py-4">
                <p>Giỏ hàng của bạn đang trống.</p>
                <a href="shop.php" class="btn btn-primary mt-3">
                    <i class="fa fa-shopping-cart me-2"></i>Mua sắm ngay
                </a>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- CSS cho trang giỏ hàng -->
    <style>
    .cart_1i1l {
        width: 80px;
        height: 80px;
        margin-right: 15px;
    }

    .cart_1i1l img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .cart_1i1r {
        flex: 1;
    }

    .cart_1i1r h5 {
        font-size: 16px;
        margin-bottom: 5px;
    }

    .cart_1i1r h6 {
        font-size: 13px;
        color: #777;
    }

    .cart_r1 {
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        background-color: #27ae60;
        color: white;
        border-radius: 4px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        display: none;
        z-index: 1000;
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    .cart-summary {
        position: relative;
        top: 0;
        right: 0;
        width: 100%;
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    </style>

    <!-- Notification element -->
    <div id="notification" class="notification"></div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
        const errorMessage = document.getElementById('errorMessage');

        function showError(message) {
            errorMessage.textContent = message;
            errorModal.show();
            setTimeout(() => errorModal.hide(), 3000);
        }

        function formatCurrency(amount) {
            if (isNaN(amount)) amount = 0;
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(amount);
        }

        function toggleHiddenInputs(checkbox) {
            const row = checkbox.closest('tr');
            const inputs = row.querySelectorAll('input[name^="item_"]');
            inputs.forEach(input => {
                if (input.type === 'hidden') {
                    if (checkbox.checked) {
                        input.removeAttribute('disabled');
                    } else {
                        input.setAttribute('disabled', 'disabled');
                    }
                }
            });
        }

        function updateProductTotal(productId, quantity) {
            const row = document.querySelector(`.quantity-input[data-product-id="${productId}"]`).closest('tr');
            if (row) {
                const priceCell = row.querySelector('.price');
                const subtotalCell = row.querySelector(`.subtotal[data-product-id="${productId}"]`);
                const price = parseFloat(priceCell.dataset.price) || 0;
                const newSubtotal = price * quantity;

                subtotalCell.textContent = formatCurrency(newSubtotal);
                subtotalCell.dataset.subtotal = newSubtotal;
                const checkbox = row.querySelector('.item-checkbox');
                checkbox.dataset.quantity = quantity;
                updateCartTotal();
            }
        }

        function updateCartTotal() {
            let total = 0;
            let itemCount = 0;

            document.querySelectorAll('.item-checkbox:checked').forEach(checkbox => {
                const row = checkbox.closest('tr');
                const subtotalCell = row.querySelector('.subtotal');
                const subtotal = parseFloat(subtotalCell.dataset.subtotal || 0);
                total += subtotal;
                itemCount++;
            });

            document.getElementById('selectedCount').textContent = itemCount;
            document.getElementById('selectedTotal').textContent = formatCurrency(total);
            document.getElementById('finalTotal').textContent = formatCurrency(total);
            document.getElementById('checkoutBtn').disabled = itemCount === 0;

            document.getElementById('totalAmountInput').value = total;
            document.getElementById('totalItemsInput').value = itemCount;
        }

        async function updateQuantity(productId, newQuantity) {
            const row = document.querySelector(`.quantity-input[data-product-id="${productId}"]`).closest(
                'tr');
            const quantityInput = row.querySelector('.quantity-input');
            const hiddenQuantityInput = row.querySelector(`input[name="item_quantity[${productId}]"]`);
            const decreaseBtn = row.querySelector('.btn-decrease');
            const increaseBtn = row.querySelector('.btn-increase');

            newQuantity = Math.max(1, Math.round(newQuantity));
            quantityInput.disabled = true;
            decreaseBtn.disabled = true;
            increaseBtn.disabled = true;

            try {
                const response = await fetch('cart_update.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `product_id=${productId}&quantity=${newQuantity}`
                });

                const data = await response.json();
                if (data.success) {
                    quantityInput.value = newQuantity;
                    quantityInput.dataset.lastValue = newQuantity;
                    hiddenQuantityInput.value = newQuantity;
                    updateProductTotal(productId, newQuantity);
                } else {
                    showError(data.message || 'Có lỗi xảy ra khi cập nhật số lượng.');
                    quantityInput.value = quantityInput.dataset.lastValue;
                    hiddenQuantityInput.value = quantityInput.dataset.lastValue;
                    updateProductTotal(productId, parseInt(quantityInput.dataset.lastValue));
                }
            } catch (error) {
                console.error('Error:', error);
                showError('Có lỗi xảy ra khi cập nhật số lượng.');
                quantityInput.value = quantityInput.dataset.lastValue;
                hiddenQuantityInput.value = quantityInput.dataset.lastValue;
                updateProductTotal(productId, parseInt(quantityInput.dataset.lastValue));
            } finally {
                quantityInput.disabled = false;
                decreaseBtn.disabled = false;
                increaseBtn.disabled = false;
            }
        }

        document.querySelectorAll('.btn-decrease').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.dataset.productId;
                const row = this.closest('tr');
                const input = row.querySelector('.quantity-input');
                const currentValue = parseInt(input.value);

                if (currentValue > 1) {
                    updateQuantity(productId, currentValue - 1);
                }
            });
        });

        document.querySelectorAll('.btn-increase').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.dataset.productId;
                const row = this.closest('tr');
                const input = row.querySelector('.quantity-input');
                const currentValue = parseInt(input.value);

                updateQuantity(productId, currentValue + 1);
            });
        });

        let debounceTimeout;
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.dataset.lastValue = input.value;

            input.addEventListener('input', function() {
                clearTimeout(debounceTimeout);
                const productId = this.dataset.productId;

                debounceTimeout = setTimeout(() => {
                    let newValue = parseInt(this.value);
                    if (isNaN(newValue) || newValue < 1) newValue = 1;
                    this.value = newValue;
                    updateQuantity(productId, newValue);
                }, 500);
            });

            input.addEventListener('keypress', function(e) {
                if (!/[0-9]/.test(e.key)) e.preventDefault();
            });
        });

        document.querySelectorAll('.item-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', async function() {
                const productId = this.value;
                const isSelected = this.checked;
                
                try {
                    const response = await fetch('update_cart_selection.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `product_id=${productId}&is_selected=${isSelected ? 1 : 0}`
                    });

                    const data = await response.json();
                    if (data.success) {
                        toggleHiddenInputs(this);
                        updateCartTotal();
                        showNotification(isSelected ? 'Đã chọn sản phẩm' : 'Đã bỏ chọn sản phẩm');
                    } else {
                        showError(data.message || 'Có lỗi xảy ra khi cập nhật trạng thái sản phẩm.');
                        this.checked = !isSelected; // Revert checkbox state
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showError('Có lỗi xảy ra khi cập nhật trạng thái sản phẩm.');
                    this.checked = !isSelected; // Revert checkbox state
                }
            });
        });

        const checkAll = document.getElementById('checkAll');
        if (checkAll) {
            checkAll.addEventListener('change', async function() {
                const checkboxes = document.querySelectorAll('.item-checkbox');
                const isSelected = this.checked;
                
                for (const checkbox of checkboxes) {
                    try {
                        const response = await fetch('update_cart_selection.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: `product_id=${checkbox.value}&is_selected=${isSelected ? 1 : 0}`
                        });

                        const data = await response.json();
                        if (data.success) {
                            checkbox.checked = isSelected;
                            toggleHiddenInputs(checkbox);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                    }
                }
                
                updateCartTotal();
                showNotification(isSelected ? 'Đã chọn tất cả sản phẩm' : 'Đã bỏ chọn tất cả sản phẩm');
            });
        }

        const checkoutForm = document.getElementById('checkoutForm');
        if (checkoutForm) {
            checkoutForm.addEventListener('submit', function(e) {
                const selectedItems = document.querySelectorAll('.item-checkbox:checked');
                if (selectedItems.length === 0) {
                    e.preventDefault();
                    showError('Vui lòng chọn ít nhất một sản phẩm để thanh toán.');
                } else {
                    // Đảm bảo tất cả dữ liệu được đồng bộ trước khi gửi
                    selectedItems.forEach(checkbox => {
                        const row = checkbox.closest('tr');
                        const quantityInput = row.querySelector('.quantity-input');
                        const hiddenQuantityInput = row.querySelector(
                            `input[name="item_quantity[${checkbox.value}]"]`);
                        hiddenQuantityInput.value = quantityInput.value;
                    });
                }
            });
        }

        updateCartTotal();
    });

    function showNotification(message, type = 'success') {
        const notification = document.getElementById('notification');
        notification.textContent = message;
        notification.style.backgroundColor = type === 'success' ? '#27ae60' : '#e74c3c';
        notification.style.display = 'block';
        
        setTimeout(() => {
            notification.style.display = 'none';
        }, 3000);
    }

    // Add notification for cart updates
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            const productId = this.dataset.productId;
            const newValue = parseInt(this.value);
            if (!isNaN(newValue) && newValue > 0) {
                showNotification('Giỏ hàng đã được cập nhật');
            }
        });
    });

    // Show notification when items are selected/deselected
    document.querySelectorAll('.item-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const count = document.querySelectorAll('.item-checkbox:checked').length;
            if (count > 0) {
                showNotification(`Đã chọn ${count} sản phẩm để thanh toán`);
            }
        });
    });

    // Xử lý xóa sản phẩm
    document.querySelectorAll('.delete-item').forEach(button => {
        button.addEventListener('click', async function() {
            if (confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')) {
                const productId = this.dataset.productId;
                const row = this.closest('tr');
                
                try {
                    const response = await fetch(`cart_remove.php?id=${productId}`, {
                        method: 'GET'
                    });
                    
                    if (response.ok) {
                        row.remove();
                        updateCartTotal();
                        showNotification('Đã xóa sản phẩm khỏi giỏ hàng');
                        
                        // Kiểm tra nếu giỏ hàng trống
                        if (document.querySelectorAll('.cart-item').length === 0) {
                            location.reload(); // Tải lại trang để hiển thị thông báo giỏ hàng trống
                        }
                    } else {
                        showNotification('Không thể xóa sản phẩm khỏi giỏ hàng', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showNotification('Có lỗi xảy ra khi xóa sản phẩm', 'error');
                }
            }
        });
    });
    </script>

    <!-- Thêm modal thông báo -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thông Báo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="errorMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>