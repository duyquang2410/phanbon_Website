<?php
include 'connect.php';
$active = 'xuatkho';
include 'aside.php';
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xuất kho sản phẩm</title>
    <?php include 'head.php'; ?>
    <style>
        .main-content {
            margin-left: 17rem;
            padding: 2rem;
            min-height: 100vh;
            transition: margin-left 0.3s ease-in-out;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
        }

        .card {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-radius: 1rem;
        }

        .card-header {
            border-radius: 1rem 1rem 0 0;
            padding: 1.5rem;
        }

        .form-control, .form-select {
            border-radius: 0.5rem;
            border: 1px solid #d2d6da;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            transition: all 0.2s ease-in-out;
        }

        .form-control:focus, .form-select:focus {
            border-color: #e91e63;
            box-shadow: 0 0 0 2px rgba(233, 30, 99, 0.1);
        }

        .input-group-text {
            border-radius: 0 0.5rem 0.5rem 0;
            background-color: #f8f9fa;
            border: 1px solid #d2d6da;
            border-left: none;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            color: #344767;
        }

        .required-asterisk {
            color: #e91e63;
            margin-left: 2px;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: all 0.2s ease-in-out;
        }

        .btn-primary {
            box-shadow: 0 4px 6px -1px rgba(233, 30, 99, 0.1), 0 2px 4px -1px rgba(233, 30, 99, 0.06);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px -1px rgba(233, 30, 99, 0.2), 0 2px 6px -1px rgba(233, 30, 99, 0.1);
        }

        .btn-secondary {
            background-color: #8392ab;
            border-color: #8392ab;
        }

        .btn-secondary:hover {
            background-color: #96a2b8;
            border-color: #96a2b8;
        }

        .alert {
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .product-info {
            background-color: #f8f9fa;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .product-info .title {
            font-size: 0.875rem;
            color: #8392ab;
            margin-bottom: 0.5rem;
        }

        .product-info .value {
            font-size: 1rem;
            color: #344767;
            font-weight: 600;
        }

        .stock-warning {
            font-size: 0.875rem;
            color: #e91e63;
            margin-top: 0.5rem;
        }
    </style>
</head>

<body class="g-sidenav-show bg-gray-100">
    <main class="main-content">
        <div class="container-fluid">
            <div class="row mb-4">
            <div class="col-12">
                    <div class="card">
                        <div class="card-header pb-0 bg-gradient-primary">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape bg-white shadow text-center border-radius-md me-3">
                                    <i class="material-icons opacity-10 text-primary">remove_shopping_cart</i>
                        </div>
                                <div>
                                    <h6 class="mb-0 text-white">Xuất kho sản phẩm</h6>
                                    <p class="text-sm mb-0 text-white opacity-8">Xuất sản phẩm ra khỏi kho</p>
                    </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <?php if (isset($_SESSION['success_message'])): ?>
                                <div class="alert alert-success" role="alert">
                                    <i class="material-icons me-2">check_circle</i>
                                    <?php 
                                        echo $_SESSION['success_message'];
                                        unset($_SESSION['success_message']);
                                    ?>
                            </div>
                        <?php endif; ?>

                            <?php if (isset($_SESSION['error_message'])): ?>
                                <div class="alert alert-danger" role="alert">
                                    <i class="material-icons me-2">error</i>
                                    <?php 
                                        echo $_SESSION['error_message'];
                                        unset($_SESSION['error_message']);
                                    ?>
                            </div>
                        <?php endif; ?>

                            <form id="inventoryOutForm" method="POST" action="process_inventory_out.php">
                                <div class="row g-4">
                                    <!-- Chọn sản phẩm -->
                                <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label d-flex align-items-center">
                                                Sản phẩm
                                                <span class="required-asterisk">*</span>
                                                <i class="material-icons ms-1 text-sm text-primary" data-bs-toggle="tooltip" title="Chọn sản phẩm cần xuất kho">help_outline</i>
                                            </label>
                                            <select class="form-select" name="product_id" required>
                                            <option value="">Chọn sản phẩm</option>
                                            <?php
                                                $sql = "SELECT SP_MA, SP_TEN, SP_DONVITINH, SP_SOLUONGTON FROM san_pham WHERE SP_SOLUONGTON > 0 ORDER BY SP_TEN";
                                            $result = mysqli_query($conn, $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo "<option value='" . $row['SP_MA'] . "' 
                                                        data-unit='" . $row['SP_DONVITINH'] . "'
                                                        data-stock='" . $row['SP_SOLUONGTON'] . "'>" 
                                                        . $row['SP_TEN'] . 
                                                        " (Tồn: " . $row['SP_SOLUONGTON'] . " " . $row['SP_DONVITINH'] . ")</option>";
                                            }
                                            ?>
                                        </select>
                                        </div>
                                    </div>

                                    <!-- Số lượng -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label d-flex align-items-center">
                                                Số lượng
                                                <span class="required-asterisk">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" name="quantity" required min="1" step="1" placeholder="Nhập số lượng">
                                                <span class="input-group-text" id="unit-display">Đơn vị</span>
                                            </div>
                                            <div class="stock-warning" id="stock-warning"></div>
                                        </div>
                                    </div>

                                    <!-- Lý do xuất -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label d-flex align-items-center">
                                                Lý do xuất
                                                <span class="required-asterisk">*</span>
                                            </label>
                                            <select class="form-select" name="reason_type" required>
                                                <option value="">Chọn lý do xuất</option>
                                                <option value="order">Xuất cho đơn hàng</option>
                                                <option value="damage">Hàng hỏng/lỗi</option>
                                                <option value="return">Trả về nhà cung cấp</option>
                                                <option value="other">Lý do khác</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Ghi chú -->
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">Ghi chú chi tiết</label>
                                            <textarea class="form-control" name="note" rows="3" 
                                                placeholder="Nhập ghi chú chi tiết về việc xuất kho (nếu có)"></textarea>
                                    </div>
                                </div>

                                    <!-- Preview thông tin -->
                                    <div class="col-12">
                                        <div class="product-info d-none" id="preview-info">
                                            <h6 class="text-primary mb-3">Xác nhận thông tin xuất kho</h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <div class="title">Sản phẩm</div>
                                                        <div class="value" id="preview-product">-</div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="title">Số lượng xuất</div>
                                                        <div class="value" id="preview-quantity">-</div>
                                                    </div>
                                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                                        <div class="title">Tồn kho hiện tại</div>
                                                        <div class="value" id="preview-current-stock">-</div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="title">Tồn kho sau xuất</div>
                                                        <div class="value text-primary" id="preview-remaining">-</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Nút submit -->
                                    <div class="col-12 text-end">
                                        <button type="button" class="btn btn-secondary me-2" onclick="window.history.back()">
                                            <i class="material-icons me-2">arrow_back</i>
                                            Quay lại
                                        </button>
                                        <button type="submit" class="btn bg-gradient-primary">
                                            <i class="material-icons me-2">save</i>
                                            Xuất kho
                                        </button>
                                    </div>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

    <?php include 'billing_scripts.php'; ?>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('inventoryOutForm');
            const productSelect = form.querySelector('[name="product_id"]');
            const quantityInput = form.querySelector('[name="quantity"]');
            const reasonSelect = form.querySelector('[name="reason_type"]');
            const unitDisplay = document.getElementById('unit-display');
            const stockWarning = document.getElementById('stock-warning');
            const previewInfo = document.getElementById('preview-info');

            // Khởi tạo tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Cập nhật preview
            function updatePreview() {
                const product = productSelect.options[productSelect.selectedIndex];
                const quantity = parseInt(quantityInput.value) || 0;
                const currentStock = product ? parseInt(product.getAttribute('data-stock')) : 0;
                const unit = product ? product.getAttribute('data-unit') : 'Đơn vị';

                if (product && product.value && quantity) {
                    document.getElementById('preview-product').textContent = product.text;
                    document.getElementById('preview-quantity').textContent = `${quantity} ${unit}`;
                    document.getElementById('preview-current-stock').textContent = `${currentStock} ${unit}`;
                    document.getElementById('preview-remaining').textContent = `${currentStock - quantity} ${unit}`;
                    previewInfo.classList.remove('d-none');

                    // Kiểm tra và hiển thị cảnh báo
                    if (quantity > currentStock) {
                        stockWarning.textContent = `Số lượng xuất vượt quá tồn kho (${currentStock} ${unit})`;
                        stockWarning.classList.add('text-danger');
                        quantityInput.classList.add('is-invalid');
                    } else {
                        stockWarning.textContent = '';
                        stockWarning.classList.remove('text-danger');
                        quantityInput.classList.remove('is-invalid');
                    }
    } else {
                    previewInfo.classList.add('d-none');
                }
            }

            // Cập nhật đơn vị tính khi chọn sản phẩm
            productSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const unit = selectedOption.getAttribute('data-unit');
                unitDisplay.textContent = unit || 'Đơn vị';
                quantityInput.value = ''; // Reset số lượng
                updatePreview();
            });

            // Cập nhật preview khi thay đổi số lượng
            quantityInput.addEventListener('input', updatePreview);

            // Validate form trước khi submit
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Kiểm tra các trường bắt buộc
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('is-invalid');
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                if (!isValid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: 'Vui lòng điền đầy đủ thông tin bắt buộc'
                    });
                    return;
                }

                // Kiểm tra số lượng xuất
                const selectedOption = productSelect.options[productSelect.selectedIndex];
                const currentStock = parseInt(selectedOption.getAttribute('data-stock'));
                const quantity = parseInt(quantityInput.value);

                if (quantity > currentStock) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: `Số lượng xuất không được vượt quá tồn kho (${currentStock} ${selectedOption.getAttribute('data-unit')})`
                    });
                    return;
                }

                // Xác nhận trước khi submit
                Swal.fire({
                    title: 'Xác nhận xuất kho?',
                    html: `
                        <div class="text-start">
                            <p><strong>Sản phẩm:</strong> ${selectedOption.text}</p>
                            <p><strong>Số lượng xuất:</strong> ${quantity} ${selectedOption.getAttribute('data-unit')}</p>
                            <p><strong>Tồn kho sau xuất:</strong> ${currentStock - quantity} ${selectedOption.getAttribute('data-unit')}</p>
                            <p><strong>Lý do:</strong> ${reasonSelect.options[reasonSelect.selectedIndex].text}</p>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Xác nhận',
                    cancelButtonText: 'Hủy',
                    confirmButtonColor: '#e91e63',
                    cancelButtonColor: '#8392ab'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
</script>
</body>

</html> 