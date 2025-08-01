<?php
session_start();
include 'connect.php';

// Kiểm tra đăng nhập và quyền admin
if (!isset($_SESSION['NV_MA'])) {
    header('Location: sign_in.php');
    exit;
}

$active = 'tonkho';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Nhập kho sản phẩm</title>
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    
    <?php include 'head.php'; ?>

    <style>
        /* Select2 Customization */
        .select2-container {
            width: 100% !important;
            margin-bottom: 0 !important;
        }

        .input-group-outline .select2-container {
            margin-top: 0.5rem !important;
        }
        
        .input-group-outline .select2-container .select2-selection--single {
            height: calc(1.5em + 1.25rem + 2px);
            border: 1px solid #d9dee3;
            border-radius: 0.375rem;
            background-color: #fff;
        }
        
        .input-group-outline .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: calc(1.5em + 1.25rem);
            padding-left: 0.75rem;
            padding-right: 2rem;
            color: #495057;
        }
        
        .input-group-outline .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: calc(1.5em + 1.25rem + 2px);
            width: 2rem;
            right: 0;
        }

        .input-group-outline label.select2-label {
            position: absolute;
            top: -0.75rem;
            left: 0.75rem;
            background: white;
            padding: 0 0.5rem;
            font-size: 0.875rem;
            color: #566a7f;
            z-index: 2;
            margin: 0;
            line-height: 1.5;
        }
        
        .select2-dropdown {
            border: 1px solid #d9dee3;
            border-radius: 0.375rem;
            box-shadow: 0 2px 6px rgba(67, 89, 113, 0.12);
        }
        
        .select2-search__field {
            padding: 6px !important;
            border: 1px solid #d9dee3 !important;
            border-radius: 0.375rem;
        }
        
        .select2-results__option {
            padding: 6px 12px;
        }
        
        .select2-results__option--highlighted[aria-selected] {
            background-color: #696cff !important;
        }

        /* Layout */
        .main-content {
            margin-left: 250px;
            padding: 1.5rem;
            min-height: 100vh;
            background-color: #f8f9fa;
            transition: margin-left .3s ease-in-out;
        }

        @media (max-width: 991.98px) {
            .main-content {
                margin-left: 0;
            }
        }

        /* Card Styling */
        .card {
            background: #fff;
            border: none;
            border-radius: 1rem;
            box-shadow: 0 2px 6px 0 rgba(67, 89, 113, 0.12);
            margin-bottom: 1.5rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Form Elements */
        .input-group-outline {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-group-outline label {
            position: absolute;
            top: -0.75rem;
            left: 0.75rem;
            background: white;
            padding: 0 0.5rem;
            font-size: 0.875rem;
            color: #566a7f;
            z-index: 1;
            margin: 0;
            line-height: 1.5;
        }

        .input-group-outline .form-control,
        .input-group-outline .form-select {
            padding: 0.625rem 0.75rem;
            border: 1px solid #d9dee3;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            line-height: 1.5;
            min-height: calc(1.5em + 1.25rem + 2px);
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .input-group-outline .form-control:focus,
        .input-group-outline .form-select:focus {
            border-color: #696cff;
            box-shadow: 0 0 0 0.2rem rgba(105, 108, 255, 0.25);
        }

        .input-group-outline .select2-container .select2-selection--single {
            height: calc(1.5em + 1.25rem + 2px);
            border: 1px solid #d9dee3;
            border-radius: 0.375rem;
        }

        .input-group-outline .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: calc(1.5em + 1.25rem);
            padding-left: 0.75rem;
            padding-right: 0.75rem;
            color: #495057;
        }

        .input-group-outline .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: calc(1.5em + 1.25rem + 2px);
            width: 2rem;
        }

        /* Table Styling */
        .table {
            margin-bottom: 0;
        }

        .table th {
            font-weight: 600;
            background-color: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
        }

        .table td {
            vertical-align: middle;
        }

        /* Button Styling */
        .btn {
            font-size: 0.875rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn i {
            font-size: 1.25rem;
        }

        /* Loading Overlay */
        .loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loading-content {
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            text-align: center;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .loading-spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #696cff;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Scanner Modal */
        .barcode-scanner {
            width: 100%;
            max-width: 640px;
            margin: 1.25rem auto;
            border-radius: 0.5rem;
            overflow: hidden;
        }

        #reader {
            width: 100%;
            background: #fff;
            border-radius: 0.5rem;
        }

        /* Product History */
        .product-history {
            font-size: 0.875rem;
            color: #566a7f;
            margin-top: 0.5rem;
            padding: 0.5rem;
            background-color: #f8f9fa;
            border-radius: 0.375rem;
        }

        /* Breadcrumb */
        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 1rem;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: "›";
        }

        /* Page Header */
        .page-header {
            margin-bottom: 1.5rem;
        }

        .page-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
            color: #566a7f;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        /* Responsive Table */
        @media (max-width: 767.98px) {
            .table-responsive {
                margin-bottom: 1rem;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: stretch;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body class="g-sidenav-show bg-gray-200">
    <?php include 'aside.php'; ?>
    
    <div class="loading">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <p class="mb-0">Đang xử lý...</p>
        </div>
    </div>
    
    <main class="main-content">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="inventory.php">Quản lý kho</a></li>
                <li class="breadcrumb-item active" aria-current="page">Nhập kho</li>
            </ol>
        </nav>

        <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h5 class="page-title">Nhập kho sản phẩm</h5>
            <div class="action-buttons">
                <button type="button" class="btn btn-outline-primary" id="btnScanBarcode">
                    <i class="material-icons">qr_code_scanner</i>
                    <span>Quét mã vạch</span>
                </button>
                <button type="button" class="btn btn-outline-secondary" id="btnSaveDraft">
                    <i class="material-icons">save</i>
                    <span>Lưu nháp</span>
                </button>
                <button type="button" class="btn btn-outline-info" id="btnLoadDraft">
                    <i class="material-icons">restore</i>
                    <span>Tải bản nháp</span>
                </button>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form id="inventoryInForm">
                    <div class="row g-4 mb-4">
                        <div class="col-12 col-md-3">
                            <div class="input-group-outline">
                                <label>Số phiếu nhập</label>
                                <input type="text" class="form-control" name="maphieunhap" readonly 
                                       value="PN<?php echo date('YmdHis'); ?>">
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="input-group-outline">
                                <label>Ngày nhập</label>
                                <input type="date" class="form-control" name="ngayNhap" 
                                       value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="input-group-outline">
                                <label class="select2-label">Nhà cung cấp</label>
                                <select class="form-select select2" name="nhaCungCap" id="nhaCungCap" required>
                                    <option value="">Chọn nhà cung cấp</option>
                                    <?php
                                    $sql = "SELECT NH_MA, NH_TEN FROM nguon_hang WHERE NH_TRANGTHAI = 1 ORDER BY NH_TEN";
                                    $result = $conn->query($sql);
                                    if ($result && $result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row['NH_MA'] . "'>" . htmlspecialchars($row['NH_TEN']) . "</option>";
                                        }
                                    } else {
                                        echo "<option value='' disabled>Không có nhà cung cấp nào</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="productTable">
                            <thead>
                                <tr>
                                    <th style="width: 35%;">Sản phẩm</th>
                                    <th style="width: 15%;">Số lượng</th>
                                    <th style="width: 15%;">Đơn vị tính</th>
                                    <th style="width: 15%;">Giá nhập</th>
                                    <th style="width: 15%;">Thành tiền</th>
                                    <th style="width: 5%;" class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select class="form-select product-select select2" name="products[]" required>
                                            <option value="">Chọn sản phẩm</option>
                                            <?php
                                            $sql = "SELECT sp.SP_MA, sp.SP_TEN, sp.SP_DONVITINH, sp.SP_SOLUONGTON, dm.DM_TEN 
                                                    FROM san_pham sp 
                                                    LEFT JOIN danh_muc dm ON sp.DM_MA = dm.DM_MA 
                                                    WHERE sp.SP_TRANGTHAI = 1 
                                                    ORDER BY dm.DM_TEN, sp.SP_TEN";
                                            $result = $conn->query($sql);
                                            if ($result && $result->num_rows > 0) {
                                                $current_category = '';
                                                while ($row = $result->fetch_assoc()) {
                                                    if ($current_category != $row['DM_TEN']) {
                                                        if ($current_category != '') {
                                                            echo "</optgroup>";
                                                        }
                                                        $current_category = $row['DM_TEN'];
                                                        echo "<optgroup label='" . htmlspecialchars($row['DM_TEN']) . "'>";
                                                    }
                                                    echo "<option value='" . $row['SP_MA'] . "' 
                                                          data-unit='" . htmlspecialchars($row['SP_DONVITINH']) . "'
                                                          data-stock='" . $row['SP_SOLUONGTON'] . "'>" 
                                                        . htmlspecialchars($row['SP_TEN']) 
                                                        . " (Tồn: " . number_format($row['SP_SOLUONGTON'], 1) . " " . $row['SP_DONVITINH'] . ")"
                                                        . "</option>";
                                                }
                                                if ($current_category != '') {
                                                    echo "</optgroup>";
                                                }
                                            } else {
                                                echo "<option value='' disabled>Không có sản phẩm nào</option>";
                                            }
                                            ?>
                                        </select>
                                        <div class="product-history"></div>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control quantity" name="quantities[]" 
                                               min="0.1" step="0.1" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control unit" readonly>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control price" name="prices[]" 
                                               min="0" step="1000" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control subtotal" readonly>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm remove-row">
                                            <i class="material-icons">delete</i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Tổng tiền:</strong></td>
                                    <td>
                                        <input type="text" class="form-control" id="totalAmount" readonly>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-success btn-sm" id="addProductRow">
                                            <i class="material-icons">add</i>
                                        </button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="input-group-outline">
                                <label>Ghi chú</label>
                                <textarea class="form-control" name="ghiChu" rows="3" 
                                          placeholder="Ghi chú phiếu nhập..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" onclick="window.history.back();">
                                <i class="material-icons">arrow_back</i>
                                <span>Quay lại</span>
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="material-icons">save</i>
                                <span>Lưu phiếu nhập</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="scannerModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Quét mã vạch sản phẩm</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div id="reader" class="barcode-scanner"></div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Core JS Files -->
    <script src="../asset_admin/js/core/popper.min.js"></script>
    <script src="../asset_admin/js/core/bootstrap.min.js"></script>
    <script src="../asset_admin/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../asset_admin/js/plugins/smooth-scrollbar.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- HTML5 QR Code Scanner -->
    <script src="https://unpkg.com/html5-qrcode"></script>

    <script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap-5'
        });

        // Format number to currency
        function formatCurrency(number) {
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(number);
        }

        // Parse currency string to number
        function parseCurrency(string) {
            return parseFloat(string.replace(/[^\d]/g, '')) || 0;
        }

        // Get last import price
        function getLastPrice(productId, row) {
            $.get('api_get_last_price.php', { product_id: productId })
                .done(function(response) {
                    if (response.status === 'success' && response.data) {
                        const data = response.data;
                        row.find('.price').val(data.last_price);
                        row.find('.product-history').html(
                            `Lần nhập gần nhất: ${formatCurrency(data.last_price)} - ${data.supplier_name} - ${new Date(data.import_date).toLocaleDateString('vi-VN')}`
                        );
                        calculateSubtotal(row);
                    }
                });
        }

        // Handle product selection
        function handleProductSelect() {
            $(document).on('change', '.product-select', function() {
                const row = $(this).closest('tr');
                const productId = $(this).val();
                const unit = $(this).find(':selected').data('unit') || '';
                
                row.find('.unit').val(unit);
                row.find('.product-history').empty();
                
                if (productId) {
                    getLastPrice(productId, row);
                }
                
                calculateSubtotal(row);
            });
        }

        // Add new product row
        $('#addProductRow').click(function() {
            const newRow = $('#productTable tbody tr:first').clone();
            
            // Clear values
            newRow.find('input').val('');
            newRow.find('select').val(null);
            newRow.find('.product-history').empty();
            
            // Remove Select2
            newRow.find('.select2').remove();
            newRow.find('.product-select').removeClass('select2-hidden-accessible');
            
            // Add new row
            $('#productTable tbody').append(newRow);
            
            // Initialize Select2 on new row
            newRow.find('.product-select').select2({
                theme: 'bootstrap-5'
            });
        });

        // Remove product row
        $(document).on('click', '.remove-row', function() {
            if ($('#productTable tbody tr').length > 1) {
                $(this).closest('tr').remove();
                calculateTotal();
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Không thể xóa',
                    text: 'Phải có ít nhất một sản phẩm'
                });
            }
        });

        // Calculate subtotal
        function calculateSubtotal(row) {
            const quantity = parseFloat(row.find('.quantity').val()) || 0;
            const price = parseFloat(row.find('.price').val()) || 0;
            const subtotal = quantity * price;
            row.find('.subtotal').val(formatCurrency(subtotal));
            calculateTotal();
        }

        // Calculate total
        function calculateTotal() {
            let total = 0;
            $('.subtotal').each(function() {
                total += parseCurrency($(this).val());
            });
            $('#totalAmount').val(formatCurrency(total));
        }

        // Handle quantity and price changes
        $(document).on('input', '.quantity, .price', function() {
            calculateSubtotal($(this).closest('tr'));
        });

        // Save draft
        $('#btnSaveDraft').click(function() {
            const formData = {
                ngayNhap: $('input[name="ngayNhap"]').val(),
                nhaCungCap: $('#nhaCungCap').val(),
                ghiChu: $('textarea[name="ghiChu"]').val(),
                products: [],
                maphieunhap: $('input[name="maphieunhap"]').val()
            };

            $('#productTable tbody tr').each(function() {
                const row = $(this);
                formData.products.push({
                    product_id: row.find('.product-select').val(),
                    quantity: row.find('.quantity').val(),
                    price: row.find('.price').val()
                });
            });

            localStorage.setItem('inventory_draft', JSON.stringify(formData));
            
            Swal.fire({
                icon: 'success',
                title: 'Đã lưu nháp',
                showConfirmButton: false,
                timer: 1500
            });
        });

        // Load draft
        $('#btnLoadDraft').click(function() {
            const draft = localStorage.getItem('inventory_draft');
            if (!draft) {
                Swal.fire({
                    icon: 'info',
                    title: 'Không có bản nháp',
                    text: 'Chưa có bản nháp nào được lưu'
                });
                return;
            }

            Swal.fire({
                title: 'Tải bản nháp?',
                text: 'Dữ liệu hiện tại sẽ bị mất',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    const data = JSON.parse(draft);
                    
                    $('input[name="ngayNhap"]').val(data.ngayNhap);
                    $('#nhaCungCap').val(data.nhaCungCap).trigger('change');
                    $('textarea[name="ghiChu"]').val(data.ghiChu);
                    $('input[name="maphieunhap"]').val(data.maphieunhap);

                    // Clear existing rows except first
                    $('#productTable tbody tr:not(:first)').remove();
                    
                    // Fill first row and add new rows for remaining products
                    data.products.forEach((product, index) => {
                        const row = index === 0 ? 
                            $('#productTable tbody tr:first') : 
                            $('#productTable tbody tr:first').clone();
                        
                        if (index > 0) {
                            row.find('.select2').remove();
                            row.find('.product-select').removeClass('select2-hidden-accessible');
                            $('#productTable tbody').append(row);
                            row.find('.product-select').select2({
                                theme: 'bootstrap-5'
                            });
                        }
                        
                        row.find('.product-select').val(product.product_id).trigger('change');
                        row.find('.quantity').val(product.quantity);
                        row.find('.price').val(product.price);
                    });
                }
            });
        });

        // Initialize barcode scanner
        let html5QrcodeScanner = null;
        
        $('#btnScanBarcode').click(function() {
            $('#scannerModal').modal('show');
            
            if (!html5QrcodeScanner) {
                html5QrcodeScanner = new Html5QrcodeScanner(
                    "reader", { fps: 10, qrbox: 250 }
                );
                
                html5QrcodeScanner.render((decodedText) => {
                    // Handle barcode scan result
                    $('#scannerModal').modal('hide');
                    html5QrcodeScanner.clear();
                    
                    // Find product by barcode and add to table
                    const productSelect = $('#productTable tbody tr:last .product-select');
                    const option = productSelect.find(`option[data-barcode="${decodedText}"]`);
                    
                    if (option.length) {
                        productSelect.val(option.val()).trigger('change');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Không tìm thấy sản phẩm',
                            text: 'Mã vạch không khớp với sản phẩm nào'
                        });
                    }
                });
            }
        });

        $('#scannerModal').on('hidden.bs.modal', function() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.clear();
            }
        });

        // Form submission
        $('#inventoryInForm').on('submit', function(e) {
            e.preventDefault();

            // Validate supplier
            if (!$('#nhaCungCap').val()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Vui lòng chọn nhà cung cấp'
                });
                return;
            }

            // Check for duplicate products
            const products = [];
            let hasDuplicate = false;
            $('.product-select').each(function() {
                const productId = $(this).val();
                if (productId) {
                    if (products.includes(productId)) {
                        hasDuplicate = true;
                        return false;
                    }
                    products.push(productId);
                }
            });

            if (hasDuplicate) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Không được chọn trùng sản phẩm'
                });
                return;
            }

            // Show loading overlay
            $('.loading').css('display', 'flex');

            // Submit form
            const formData = new FormData(this);
            
            $.ajax({
                url: 'process_inventory_in.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('.loading').hide();
                    
                    try {
                        // Ensure response is a string before parsing
                        const result = typeof response === 'string' ? JSON.parse(response) : response;
                        
                        if (result.status === 'success') {
                            // Clear draft after successful submission
                            localStorage.removeItem('inventory_draft');
                            
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công!',
                                text: result.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.href = 'inventory.php';
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi!',
                                text: result.message || 'Có lỗi xảy ra'
                            });
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e, response);
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: 'Có lỗi xảy ra khi xử lý phản hồi từ server'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    $('.loading').hide();
                    console.error('Ajax error:', {xhr, status, error});
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: 'Không thể kết nối đến server'
                    });
                }
            });
        });

        // Initialize handlers
        handleProductSelect();
    });
    </script>
</body>
</html> 