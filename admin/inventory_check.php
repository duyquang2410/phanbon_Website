<?php
include 'connect.php';
$active = 'kiemkho';
include 'aside.php';
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kiểm kho sản phẩm</title>
    <?php include 'head.php'; ?>
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
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

        .stock-diff {
            font-weight: 600;
        }

        .stock-diff.positive {
            color: #4CAF50;
        }

        .stock-diff.negative {
            color: #e91e63;
        }

        .table thead th {
            font-weight: 600;
            padding: 1rem;
            background-color: #f8f9fa;
            border-bottom: none;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
        }

        .badge {
            padding: 0.5rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 0.5rem;
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
                                    <i class="material-icons opacity-10 text-primary">fact_check</i>
                        </div>
                                <div>
                                    <h6 class="mb-0 text-white">Kiểm kho sản phẩm</h6>
                                    <p class="text-sm mb-0 text-white opacity-8">Kiểm tra và điều chỉnh số lượng tồn kho thực tế</p>
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

                            <form id="inventoryCheckForm" method="POST" action="process_inventory_check.php" autocomplete="off">
                                <div class="row g-4">
                                    <!-- Chọn sản phẩm -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label d-flex align-items-center">
                                                Sản phẩm
                                                <span class="required-asterisk">*</span>
                                                <i class="material-icons ms-1 text-sm text-primary" data-bs-toggle="tooltip" title="Chọn sản phẩm cần kiểm kho">help_outline</i>
                                            </label>
                                            <select class="form-select" name="product_id" required>
                                                <option value="">Chọn sản phẩm</option>
                                                <?php
                                                    $sql = "SELECT SP_MA, SP_TEN, SP_DONVITINH, SP_SOLUONGTON FROM san_pham ORDER BY SP_TEN";
                                                    $result = mysqli_query($conn, $sql);
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        echo "<option value='" . $row['SP_MA'] . "' 
                                                                data-unit='" . $row['SP_DONVITINH'] . "'
                                                                data-stock='" . $row['SP_SOLUONGTON'] . "'>" 
                                                                . $row['SP_TEN'] . 
                                                                " (Tồn kho: " . number_format($row['SP_SOLUONGTON'], 0, ',', '.') . " " . $row['SP_DONVITINH'] . ")</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Số lượng thực tế -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label d-flex align-items-center">
                                                Số lượng thực tế
                                                <span class="required-asterisk">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" name="actual_quantity" required min="0" step="1" placeholder="Nhập số lượng thực tế">
                                                <span class="input-group-text" id="unit-display">Đơn vị</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Ghi chú -->
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">Ghi chú kiểm kho</label>
                                            <textarea class="form-control" name="note" rows="3" 
                                                placeholder="Nhập ghi chú về việc kiểm kho (nếu có)"></textarea>
                                        </div>
                                    </div>

                                    <!-- Preview thông tin -->
                                    <div class="col-12">
                                        <div class="product-info d-none" id="preview-info">
                                            <h6 class="text-primary mb-3">Thông tin kiểm kho</h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <div class="title">Sản phẩm</div>
                                                        <div class="value" id="preview-product">-</div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="title">Tồn kho hiện tại</div>
                                                        <div class="value" id="preview-current-stock">-</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                                        <div class="title">Số lượng thực tế</div>
                                                        <div class="value" id="preview-actual">-</div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="title">Chênh lệch</div>
                                                        <div class="value" id="preview-difference">-</div>
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
                                            Cập nhật tồn kho
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <!-- Lịch sử kiểm kho -->
                            <div class="mt-5">
                                <h5 class="mb-4">Lịch sử kiểm kho gần đây</h5>
                                <div class="table-responsive">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th>Thời gian</th>
                                                <th>Sản phẩm</th>
                                                <th>Số lượng cũ</th>
                                                <th>Số lượng mới</th>
                                                <th>Chênh lệch</th>
                                                <th>Nhân viên</th>
                                                <th>Ghi chú</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT 
                                                    sm.*,
                                                    sp.SP_TEN,
                                                    sp.SP_DONVITINH,
                                                    nv.NV_TEN,
                                                    sp.SP_DONVITINH as DONVITINH
                                                FROM stock_movements sm 
                                                JOIN san_pham sp ON sm.SP_MA = sp.SP_MA 
                                                JOIN nhan_vien nv ON sm.NV_MA = nv.NV_MA 
                                                WHERE sm.SM_LOAI = 'KIEM'
                                                ORDER BY sm.SM_THOIGIAN DESC 
                                                LIMIT 10";
                                            $result = mysqli_query($conn, $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $difference = $row['SM_SOLUONG_MOI'] - $row['SM_SOLUONG_CU'];
                                                $differenceClass = $difference > 0 ? 'positive' : ($difference < 0 ? 'negative' : '');
                                                echo "<tr>
                                                    <td>" . date('d/m/Y H:i', strtotime($row['SM_THOIGIAN'])) . "</td>
                                                    <td>" . $row['SP_TEN'] . " (" . $row['SP_DONVITINH'] . ")</td>
                                                    <td>" . number_format($row['SM_SOLUONG_CU'], 0) . "</td>
                                                    <td>" . number_format($row['SM_SOLUONG_MOI'], 0) . "</td>
                                                    <td><span class='stock-diff " . $differenceClass . "'>" . ($difference > 0 ? '+' : '') . number_format($difference, 0) . "</span></td>
                                                    <td>" . $row['NV_TEN'] . "</td>
                                                    <td>" . ($row['SM_GHICHU'] ?: '-') . "</td>
                                                </tr>";
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
    </div>
</main>

    <!-- Core JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>

    <!-- DataTables JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

<script>
        $(document).ready(function() {
            // Khởi tạo DataTable
            $('.table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/vi.json'
                },
                order: [[0, 'desc']], // Sắp xếp theo thời gian mới nhất
                pageLength: 10,
                responsive: true
            });

            const form = document.getElementById('inventoryCheckForm');
            const productSelect = form.querySelector('[name="product_id"]');
            const actualQuantityInput = form.querySelector('[name="actual_quantity"]');
            const unitDisplay = document.getElementById('unit-display');
            const previewInfo = document.getElementById('preview-info');

            // Khởi tạo tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Cập nhật preview
            function updatePreview() {
                const product = productSelect.options[productSelect.selectedIndex];
                const actualQuantity = parseInt(actualQuantityInput.value) || 0;
                const currentStock = product ? parseInt(product.getAttribute('data-stock')) : 0;
                const unit = product ? product.getAttribute('data-unit') : 'Đơn vị';

                if (product && product.value) {
                    document.getElementById('preview-product').textContent = product.text.split('(Tồn kho:')[0].trim();
                    document.getElementById('preview-current-stock').textContent = `Số lượng: ${currentStock.toLocaleString('vi-VN')} | Đơn vị: ${unit}`;
                    document.getElementById('preview-actual').textContent = `Số lượng: ${actualQuantity.toLocaleString('vi-VN')} | Đơn vị: ${unit}`;

                    const difference = actualQuantity - currentStock;
                    const differenceElement = document.getElementById('preview-difference');
                    differenceElement.textContent = `Số lượng: ${difference > 0 ? '+' : ''}${difference.toLocaleString('vi-VN')} | Đơn vị: ${unit}`;
                    differenceElement.className = `value stock-diff ${difference > 0 ? 'positive' : (difference < 0 ? 'negative' : '')}`;

                    previewInfo.classList.remove('d-none');
                } else {
                    previewInfo.classList.add('d-none');
                }
            }

            // Cập nhật đơn vị tính khi chọn sản phẩm
            productSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const unit = selectedOption.getAttribute('data-unit');
                unitDisplay.textContent = unit || 'Đơn vị';
                actualQuantityInput.value = ''; // Reset số lượng
                updatePreview();
            });

            // Cập nhật preview khi thay đổi số lượng
            actualQuantityInput.addEventListener('input', updatePreview);

            // Xử lý submit form
            function handleSubmit(e) {
                e.preventDefault();
                console.log('Form submit triggered');

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
                    console.log('Form validation failed');
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: 'Vui lòng điền đầy đủ thông tin bắt buộc'
                    });
                    return;
                }

                // Xác nhận trước khi submit
                const selectedOption = productSelect.options[productSelect.selectedIndex];
                const currentStock = parseInt(selectedOption.getAttribute('data-stock'));
                const actualQuantity = parseInt(actualQuantityInput.value);
                const difference = actualQuantity - currentStock;
                const unit = selectedOption.getAttribute('data-unit');

                console.log('Showing confirmation dialog');
                Swal.fire({
                    title: 'Xác nhận kiểm kho?',
                    html: `
                        <div class="text-start">
                            <p><strong>Sản phẩm:</strong> ${selectedOption.text.split('(Tồn kho:')[0].trim()}</p>
                            <p><strong>Tồn kho hiện tại:</strong> Số lượng: ${currentStock.toLocaleString('vi-VN')} | Đơn vị: ${unit}</p>
                            <p><strong>Số lượng thực tế:</strong> Số lượng: ${actualQuantity.toLocaleString('vi-VN')} | Đơn vị: ${unit}</p>
                            <p><strong>Chênh lệch:</strong> <span class="stock-diff ${difference > 0 ? 'positive' : (difference < 0 ? 'negative' : '')}">Số lượng: ${difference > 0 ? '+' : ''}${difference.toLocaleString('vi-VN')} | Đơn vị: ${unit}</span></p>
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
                        console.log('Confirmation accepted, submitting form');
                        // Tắt sự kiện submit để tránh lặp lại
                        form.removeEventListener('submit', handleSubmit);
                        // Submit form
                        form.submit();
                    } else {
                        console.log('Confirmation cancelled');
                    }
                });
            }

            // Gắn sự kiện submit
            form.addEventListener('submit', handleSubmit);
        });
</script>
</body>

</html> 