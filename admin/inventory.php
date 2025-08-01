<?php
include 'connect.php';

// Kiểm tra đăng nhập và quyền admin (giữ nguyên phần này)
if (!isset($_SESSION['NV_MA'])) {
    header('Location: sign_in.php');
    exit;
}

// Kiểm tra quyền admin
$nv_ma = $_SESSION['NV_MA'];
$sql = "SELECT nv.NV_QUYEN, cv.CV_QUYEN 
        FROM nhan_vien nv 
        JOIN chuc_vu cv ON nv.CV_MA = cv.CV_MA 
        WHERE nv.NV_MA = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $nv_ma);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $nv_quyen = $row['NV_QUYEN'];
    $cv_quyen = json_decode($row['CV_QUYEN'], true);
    
    // Kiểm tra nếu không phải ADMIN hoặc không có quyền "all" trong CV_QUYEN
    if ($nv_quyen !== 'ADMIN' && !in_array("all", $cv_quyen)) {
        header('Location: dashboard.php');
        exit;
    }
} else {
    header('Location: sign_in.php');
    exit;
}

$active = 'tonkho';
include 'head.php';
?>

<style>
/* CSS để fix layout */
.sidenav {
    z-index: 1000;
}

.main-content {
    margin-left: 17.125rem;
    min-height: 100vh;
    padding-top: 1rem;
}

.table-container {
    overflow-x: auto;
    margin: 0 1rem;
}

.card {
    margin: 1rem;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
}

.card .table {
    margin-bottom: 0;
}

.table td, .table th {
    white-space: nowrap;
    padding: 1rem;
}

.product-name {
    max-width: 300px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.action-buttons .btn {
    padding: 0.5rem;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.action-buttons .btn i {
    font-size: 1rem;
}

@media (max-width: 991.98px) {
    .main-content {
        margin-left: 0;
    }
}
</style>

<div class="main-content position-relative max-height-vh-100 h-100">
    <?php include 'aside.php'; ?>
    
    <!-- Main container -->
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <!-- Header buttons -->
                <div class="d-flex justify-content-end mb-3">
                    <a href="inventory_in.php" class="btn btn-success btn-sm me-2" title="Nhập hàng từ nhà cung cấp">
                        <i class="material-icons">local_shipping</i> Nhập hàng
                    </a>
                    <a href="inventory_out.php" class="btn btn-warning btn-sm me-2" title="Xuất hàng">
                        <i class="material-icons">remove</i> Xuất kho
                    </a>
                    <a href="inventory_check.php" class="btn btn-info btn-sm" title="Kiểm kê tồn kho">
                        <i class="material-icons">fact_check</i> Kiểm kho
                    </a>
                </div>

                <!-- Main card -->
                <div class="card">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3 mb-0">Quản lý tồn kho</h6>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Search and filter section -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="input-group input-group-outline">
                                    <label class="form-label">Tìm kiếm sản phẩm...</label>
                                    <input type="text" class="form-control" id="searchInput">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="filterStock">
                                    <option value="all">Tất cả</option>
                                    <option value="low">Sắp hết hàng (≤10)</option>
                                    <option value="medium">Cần nhập thêm (≤30)</option>
                                    <option value="high">Đủ hàng (>30)</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="sortBy">
                                    <option value="name_asc">Tên A-Z</option>
                                    <option value="name_desc">Tên Z-A</option>
                                    <option value="stock_asc">Tồn kho tăng dần</option>
                                    <option value="stock_desc">Tồn kho giảm dần</option>
                                </select>
                            </div>
                        </div>

                        <!-- Stock warnings -->
                        <div class="alert alert-warning mb-4" role="alert">
                            <h4 class="alert-heading mb-2">Cảnh báo tồn kho</h4>
                            <div id="stockWarnings">
                                <!-- Sẽ được cập nhật bằng JavaScript -->
                            </div>
                        </div>

                        <!-- Products table -->
                        <div class="table-container">
                            <table class="table align-items-center mb-0" id="inventoryTable">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Mã SP</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Sản phẩm</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Số lượng tồn</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Đơn vị tính</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Trạng thái</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Sửa lại câu SQL lấy danh sách sản phẩm
                                    $sql = "SELECT 
    sp.SP_MA, 
    sp.SP_TEN, 
    sp.SP_SOLUONGTON as soluong, 
    sp.SP_DONVITINH, 
    sp.SP_HINHANH,
    COALESCE(SUM(ct.CTHD_SOLUONG), 0) as da_ban
                                            FROM san_pham sp 
LEFT JOIN chi_tiet_hd ct ON sp.SP_MA = ct.SP_MA
LEFT JOIN hoa_don hd ON ct.HD_STT = hd.HD_STT AND hd.TT_MA = 4
GROUP BY sp.SP_MA, sp.SP_TEN, sp.SP_SOLUONGTON, sp.SP_DONVITINH, sp.SP_HINHANH
                                            ORDER BY sp.SP_SOLUONGTON ASC";

                                    $result = mysqli_query($conn, $sql);
                                    
                                    if ($result && mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $stockStatus = '';
                                            $stockClass = '';
        $badgeClass = '';
        
        if ($row['soluong'] <= 10) {
                                                $stockStatus = 'Sắp hết hàng';
                                                $stockClass = 'text-danger';
                                                $badgeClass = 'bg-gradient-danger';
        } elseif ($row['soluong'] <= 30) {
                                                $stockStatus = 'Cần nhập thêm';
                                                $stockClass = 'text-warning';
                                                $badgeClass = 'bg-gradient-warning';
                                            } else {
                                                $stockStatus = 'Đủ hàng';
                                                $stockClass = 'text-success';
                                                $badgeClass = 'bg-gradient-success';
                                            }
                                            ?>
                                            <tr data-product-id="<?php echo $row['SP_MA']; ?>">
                                                <td class="align-middle">
                                                    <span class="text-secondary text-xs font-weight-bold"><?php echo $row['SP_MA']; ?></span>
                                                </td>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="avatar avatar-sm me-3">
                        <img src="../img/<?php echo $row['SP_HINHANH']; ?>" 
                             class="rounded-circle product-image" 
                             alt="<?php echo htmlspecialchars($row['SP_TEN']); ?>"
                             onerror="this.src='../img/service.jpg'">
                                                        </div>
                                                        <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm product-name"><?php echo htmlspecialchars($row['SP_TEN']); ?></h6>
                        <p class="text-xs text-secondary mb-0">Đã bán: <?php echo number_format($row['da_ban']); ?></p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                <span class="text-xs font-weight-bold <?php echo $stockClass; ?> product-stock">
                    <?php echo number_format($row['soluong']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="text-secondary text-xs font-weight-bold">
                                                        <?php echo $row['SP_DONVITINH']; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-sm <?php echo $badgeClass; ?>">
                                                        <?php echo $stockStatus; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="action-buttons">
                    <button type="button" class="btn btn-info btn-sm" 
                            data-bs-toggle="modal" 
                            data-bs-target="#adjustStockModal"
                            onclick="adjustStock('<?php echo $row['SP_MA']; ?>', 
                                              '<?php echo htmlspecialchars($row['SP_TEN']); ?>', 
                                              '<?php echo $row['soluong']; ?>', 
                                              '<?php echo $row['SP_HINHANH']; ?>',
                                              '<?php echo $row['SP_DONVITINH']; ?>')"
                            title="Điều chỉnh số lượng tồn kho">
                        <i class="material-icons">tune</i>
                        <span>Điều chỉnh tồn kho</span>
                    </button>
                    <a href="inventory_history.php?product_id=<?php echo $row['SP_MA']; ?>" 
                       class="btn btn-primary btn-sm"
                       title="Xem lịch sử thay đổi">
                        <i class="material-icons">history</i>
                        <span>Lịch sử</span>
                    </a>
                </div>
            </td>
        </tr>
        <?php
    }
} else {
    echo "<tr><td colspan='6' class='text-center'>Không có dữ liệu</td></tr>";
}
?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>

<!-- Modal điều chỉnh tồn kho -->
<div class="modal fade" id="adjustStockModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
                <h5 class="modal-title text-white">
                    <i class="material-icons opacity-10">tune</i>
                    Điều chỉnh số lượng tồn kho
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="adjustStockForm">
                    <input type="hidden" id="productId" name="productId">
                    
                    <!-- Thông tin sản phẩm -->
                    <div class="product-info mb-4">
                        <div class="d-flex align-items-center">
                            <div class="product-image me-3">
                                <img src="" id="productImage" class="rounded-3" alt="Product Image">
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="product-name mb-1" id="productName"></h6>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-gradient-info me-2" id="productCode"></span>
                                    <span class="text-sm">Tồn kho: <strong id="currentStock"></strong></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Loại điều chỉnh -->
                    <div class="mb-3">
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="adjustmentType" id="typeIncrease" value="increase" checked>
                            <label class="btn btn-outline-success" for="typeIncrease">
                                <i class="material-icons">add</i> Tăng số lượng
                            </label>
                            
                            <input type="radio" class="btn-check" name="adjustmentType" id="typeDecrease" value="decrease">
                            <label class="btn btn-outline-danger" for="typeDecrease">
                                <i class="material-icons">remove</i> Giảm số lượng
                            </label>
                        </div>
                    </div>

                    <!-- Số lượng và giá -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label required">Số lượng điều chỉnh</label>
                            <div class="input-group input-group-outline">
                                <input type="number" class="form-control" id="adjustmentQuantity" 
                                       name="adjustmentQuantity" min="0.1" step="0.1" required>
                                <span class="input-group-text unit-label">kg</span>
                            </div>
                        </div>
                        <div class="col-md-6" id="priceGroup">
                            <label class="form-label">Giá nhập (nếu có)</label>
                            <div class="input-group input-group-outline">
                                <input type="number" class="form-control" id="purchasePrice" 
                                       name="purchasePrice" min="0" step="1000">
                                <span class="input-group-text">đ/<span class="unit-label">kg</span></span>
                            </div>
                            <small class="text-muted">Bỏ trống nếu không áp dụng</small>
                        </div>
                        <div class="col-12" id="totalGroup">
                            <label class="form-label">Tổng tiền</label>
                            <div class="input-group input-group-outline">
                                <input type="text" class="form-control bg-light" id="totalAmount" readonly>
                                <span class="input-group-text">đ</span>
                            </div>
                        </div>
                    </div>

                    <!-- Lý do điều chỉnh -->
                    <div class="mb-3">
                        <label class="form-label required">Lý do điều chỉnh</label>
                        <textarea class="form-control" id="adjustmentReason" name="adjustmentReason" 
                                rows="2" required placeholder="Ví dụ: Điều chỉnh sau kiểm kê, Ghi nhận hao hụt..."></textarea>
                    </div>

                    <!-- Thông báo -->
                    <div class="alert alert-info py-2 mb-0">
                        <div class="d-flex align-items-center">
                            <i class="material-icons me-2">info</i>
                            <small>Điều chỉnh này sẽ được ghi vào lịch sử tồn kho và không thể hoàn tác.</small>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="material-icons">close</i>
                    <span>Đóng</span>
                </button>
                <button type="button" class="btn bg-gradient-primary" id="saveAdjustment">
                    <i class="material-icons">save</i>
                    <span>Lưu thay đổi</span>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* CSS cho form điều chỉnh tồn kho */
.modal-dialog {
    max-width: 500px;
}

.product-image {
    width: 60px;
    height: 60px;
    min-width: 60px;
    border-radius: 8px;
    overflow: hidden;
    border: 2px solid #eee;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-name {
    font-size: 1rem;
    font-weight: 600;
    color: #344767;
    margin: 0;
}

.btn-check:checked + .btn-outline-success,
.btn-check:checked + .btn-outline-danger {
    color: #fff;
}

.btn-check:checked + .btn-outline-success {
    background: linear-gradient(195deg, #66BB6A, #43A047);
    border: none;
}

.btn-check:checked + .btn-outline-danger {
    background: linear-gradient(195deg, #EF5350, #E53935);
    border: none;
}

.form-label.required::after {
    content: " *";
    color: #f44335;
}

.input-group-outline {
    border: 1px solid #d2d6da;
    border-radius: 0.375rem;
}

.input-group-outline .form-control {
    border: none;
    border-radius: 0.375rem 0 0 0.375rem;
}

.input-group-outline .input-group-text {
    border: none;
    background: transparent;
}

.alert {
    border: none;
    background: linear-gradient(195deg, #49a3f1, #1A73E8);
    color: #fff;
}

.alert .material-icons {
    font-size: 1rem;
}

.modal-footer {
    border-top: 1px solid #eee;
    padding: 1rem;
}

.modal-footer .btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
}

.modal-footer .btn .material-icons {
    font-size: 1.1rem;
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .product-info {
        flex-direction: column;
        text-align: center;
    }
    
    .product-image {
        margin-bottom: 1rem;
    }
}
</style>

<!-- Thêm vào phần head -->
<!-- DataTables -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css">

<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- Thêm vào cuối file, trước </body> -->
<!-- DataTables JS -->
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Script xử lý -->
<script>
$(document).ready(function() {
    // Khởi tạo DataTable với bản dịch tiếng Việt inline
    const table = $('#inventoryTable').DataTable({
        language: {
            "sProcessing":   "Đang xử lý...",
            "sLengthMenu":   "Xem _MENU_ mục",
            "sZeroRecords":  "Không tìm thấy dòng nào phù hợp",
            "sInfo":         "Đang xem _START_ đến _END_ trong tổng số _TOTAL_ mục",
            "sInfoEmpty":    "Đang xem 0 đến 0 trong tổng số 0 mục",
            "sInfoFiltered": "(được lọc từ _MAX_ mục)",
            "sInfoPostFix":  "",
            "sSearch":       "Tìm:",
            "sUrl":          "",
            "oPaginate": {
                "sFirst":    "Đầu",
                "sPrevious": "Trước",
                "sNext":     "Tiếp",
                "sLast":     "Cuối"
            }
        },
        dom: '<"d-flex justify-content-between align-items-center mb-3"Bf>rt<"d-flex justify-content-between align-items-center mt-3"lip>',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="material-icons">file_download</i> Excel',
                className: 'btn btn-success btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4] // Chỉ xuất 5 cột đầu tiên, bỏ cột thao tác
                }
            },
            {
                extend: 'pdf',
                text: '<i class="material-icons">picture_as_pdf</i> PDF',
                className: 'btn btn-danger btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4] // Chỉ xuất 5 cột đầu tiên, bỏ cột thao tác
                }
            }
        ],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Tất cả"]],
        order: [[0, 'asc']],
        responsive: true
    });

    // Xử lý tìm kiếm
    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Xử lý lọc theo tồn kho
    $('#filterStock').on('change', function() {
        const value = $(this).val();
        
        $.fn.dataTable.ext.search.pop(); // Xóa bộ lọc cũ nếu có
        
        if (value !== 'all') {
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                const stock = parseInt(data[2].replace(/,/g, '')); // Lấy số lượng tồn từ cột thứ 3
                
                switch(value) {
                    case 'low':
                        return stock <= 10;
                    case 'medium':
                        return stock > 10 && stock <= 30;
                    case 'high':
                        return stock > 30;
                    default:
                        return true;
                }
            });
        }
        
        table.draw();
    });

    // Xử lý sắp xếp
    $('#sortBy').on('change', function() {
        const value = $(this).val();
        
        switch(value) {
            case 'name_asc':
                table.order([1, 'asc']).draw();
                break;
            case 'name_desc':
                table.order([1, 'desc']).draw();
                break;
            case 'stock_asc':
                table.order([2, 'asc']).draw();
                break;
            case 'stock_desc':
                table.order([2, 'desc']).draw();
                break;
        }
    });

    // Handle adjustment type change
    $('input[name="adjustmentType"]').change(function() {
        const isIncrease = $(this).val() === 'increase';
        $('#priceGroup, #totalGroup').toggle(isIncrease);
        $('#purchasePrice').prop('required', false); // Make price optional
    });

    // Xử lý form điều chỉnh tồn kho
    $('#adjustStockForm').on('submit', function(e) {
        e.preventDefault();
        
        const isIncrease = $('input[name="adjustmentType"]:checked').val() === 'increase';
        
        // Bỏ qua validation giá nhập khi giảm số lượng
        if (!isIncrease) {
            $('#purchasePrice').prop('required', false);
        }
        
        // Validate form
        if (!this.checkValidity()) {
            this.reportValidity();
            return;
        }

        // Lấy dữ liệu form
        const formData = new FormData(this);
        
        // Hiển thị loading
        Swal.fire({
            title: 'Đang xử lý...',
            text: 'Vui lòng chờ trong giây lát',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Gửi request
        $.ajax({
            url: 'process_inventory_check.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                try {
                    const result = JSON.parse(response);
                    if (result.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công!',
                            text: result.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            $('#adjustStockModal').modal('hide');
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: result.message
                        });
                    }
                } catch (e) {
                    console.error('Error parsing response:', e);
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: 'Có lỗi xảy ra khi xử lý phản hồi từ server'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Ajax error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Không thể kết nối đến server'
                });
            }
        });
    });

    // Xử lý nút Lưu thay đổi
    $('#saveAdjustment').click(function() {
        $('#adjustStockForm').submit();
    });

    // Calculate total only when price is provided
    $('#adjustmentQuantity, #purchasePrice').on('input', function() {
        const quantity = parseFloat($('#adjustmentQuantity').val()) || 0;
        const price = parseFloat($('#purchasePrice').val()) || 0;
        
        if (price > 0) {
            const total = quantity * price;
            $('#totalAmount').val(new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(total));
        } else {
            $('#totalAmount').val('');
        }
    });
});

function adjustStock(productId, productName, currentStock, productImage, productUnit) {
    // Cập nhật thông tin vào modal
    $('#productId').val(productId);
    $('#productName').text(productName);
    $('#productCode').text('#' + productId);
    $('#currentStock').text(currentStock + ' ' + productUnit);
    
    // Cập nhật đơn vị tính trong các input
    $('.unit-label').text(productUnit);
    
    // Xử lý đường dẫn ảnh
    const imagePath = productImage ? '../img/' + productImage : '../img/service.jpg';
    $('#productImage').attr('src', imagePath);

    // Reset form và radio button
    $('#adjustStockForm')[0].reset();
    $('#typeIncrease').prop('checked', true).trigger('change');
}
</script>

<?php include 'billing_scripts.php'; ?> 