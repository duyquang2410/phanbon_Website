<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Plants Shop - Chi Tiết Sản Phẩm</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/global.css" rel="stylesheet">
    <link href="css/detail.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <!-- jQuery UI - bắt buộc cho chức năng tự động gợi ý -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="css/autocomplete.css">
    <!-- Header CSS -->
    <link rel="stylesheet" href="css/header.css">
    <style>
    /* Cải thiện độ tương phản cho phần mô tả sản phẩm */
    .description-content {
        padding: 15px;
        border-radius: 5px;
        font-size: 16px;
        line-height: 1.6;
        color: #000000;
        background-color: #ffffff;
    }

    .tab-content {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 5px;
    }

    .tab-pane h5 {
        color: #000000;
        margin-bottom: 15px;
        font-weight: 600;
    }

    /* Cải thiện độ tương phản cho các tab khác */
    .product-details {
        color: #000000;
    }

    .table {
        color: #000000;
    }

    .bg-white {
        background-color: #ffffff !important;
        color: #000000;
    }

    /* Chỉnh màu tiêu đề */
    .nav-tabs .nav-link {
        color: #28a745;
        font-weight: bold;
        background-color: rgba(255, 255, 255, 0.1);
    }

    .nav-tabs .nav-link:hover {
        color: #1e7e34;
    }

    .nav-tabs .nav-link.active {
        color: #28a745;
        background-color: #f8f9fa;
        border-color: #dee2e6 #dee2e6 #f8f9fa;
    }

    /* Đảm bảo tất cả tiêu đề tab luôn màu đen */
    #description h5,
    #details h5,
    #ingredients h5,
    #usage h5,
    .tab-pane > h5,
    .product-details h5,
    .product-description h5 {
        color: #000000 !important;
    }

    /* Style cho danh sách thành phần */
    .ingredients-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .ingredients-list li {
        padding: 12px 15px;
        margin-bottom: 10px;
        background-color: #f8f9fa;
        border-radius: 5px;
        border-left: 4px solid #28a745;
    }

    .ingredients-list li:last-child {
        margin-bottom: 0;
    }

    /* Style cho hướng dẫn sử dụng */
    .usage-steps {
        counter-reset: step;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .usage-steps li {
        position: relative;
        padding: 15px 20px 15px 50px;
        margin-bottom: 15px;
        background-color: #f8f9fa;
        border-radius: 5px;
        counter-increment: step;
    }

    .usage-steps li::before {
        content: counter(step);
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        width: 25px;
        height: 25px;
        line-height: 25px;
        text-align: center;
        background-color: #28a745;
        color: white;
        border-radius: 50%;
        font-weight: bold;
    }

    .usage-steps li:last-child {
        margin-bottom: 0;
    }

    .button {
        background-color: #28a745 !important;
        color: #ffffff !important;
        padding: 12px 25px !important;
        border-radius: 5px !important;
        font-weight: 600 !important;
        font-size: 16px !important;
        transition: all 0.3s ease !important;
        text-decoration: none !important;
        display: inline-block !important;
        border: 2px solid #28a745 !important;
    }

    .button:hover {
        background-color: #218838 !important;
        border-color: #218838 !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important;
    }

    .button:disabled {
        background-color: #6c757d !important;
        border-color: #6c757d !important;
        cursor: not-allowed !important;
        transform: none !important;
        box-shadow: none !important;
    }

    .button i {
        margin-right: 8px !important;
    }

    /* Style cho tab menu */
    .product-tabs {
        border-bottom: 1px solid #dee2e6;
        margin-bottom: 30px;
        display: flex;
        gap: 10px;
        padding: 0;
        list-style: none;
    }

    .product-tabs .tab-item {
        margin: 0;
        position: relative;
    }

    .product-tabs .tab-link {
        display: inline-block;
        padding: 12px 25px;
        font-size: 16px;
        font-weight: 600;
        color: #666;
        text-decoration: none;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-bottom: none;
        border-radius: 6px 6px 0 0;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .product-tabs .tab-link:hover {
        color: #28a745;
        background: #fff;
    }

    .product-tabs .tab-link.active {
        color: #28a745;
        background: #fff;
        border-bottom: 2px solid #28a745;
        margin-bottom: -1px;
    }

    .product-tabs .tab-link:focus {
        outline: none;
        box-shadow: none;
    }

    .tab-content {
        padding: 20px;
        background: #fff;
        border: 1px solid #dee2e6;
        border-top: none;
        border-radius: 0 0 6px 6px;
    }

    .tab-pane {
        display: none;
    }

    .tab-pane.active {
        display: block;
    }

    /* CSS mới cho hiển thị hình ảnh sản phẩm */
    .product-image-container {
        position: relative;
        width: 100%;
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 400px;
        transition: all 0.3s ease;
    }

    .product-image-container img {
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
        object-fit: contain;
        transition: transform 0.5s ease;
    }

    .product-image-container:hover img {
        transform: scale(1.02);
    }

    /* Responsive cho container hình ảnh */
    @media (max-width: 768px) {
        .product-image-container {
            padding: 10px;
            margin-bottom: 20px;
            min-height: 300px;
        }
    }

    /* Cải thiện hiển thị hình ảnh sản phẩm liên quan */
    .list_h2i1 figure {
        border-radius: 8px;
        overflow: hidden;
    }

    .list_h2i1 img {
        transition: transform 0.5s ease;
    }

    .list_h2i1:hover img {
        transform: scale(1.02);
    }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Lấy tất cả các tab links và tab panes
            const tabLinks = document.querySelectorAll('.tab-link');
            const tabPanes = document.querySelectorAll('.tab-pane');

            // Thêm sự kiện click cho mỗi tab link
            tabLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Xóa active class từ tất cả các tab links và panes
                    tabLinks.forEach(l => l.classList.remove('active'));
                    tabPanes.forEach(p => {
                        p.classList.remove('active');
                        p.classList.remove('show');
                    });

                    // Thêm active class cho tab được click
                    this.classList.add('active');

                    // Hiển thị tab content tương ứng
                    const tabId = this.getAttribute('href');
                    const tabPane = document.querySelector(tabId);
                    if (tabPane) {
                        tabPane.classList.add('active');
                        tabPane.classList.add('show');
                    }
                });
            });

            // Xử lý tăng giảm số lượng
            window.incrementQuantity = function() {
                const quantityInput = document.getElementById('quantity');
                const maxQuantity = parseInt(quantityInput.getAttribute('max'));
                let currentValue = parseInt(quantityInput.value);
                
                if (currentValue < maxQuantity) {
                    quantityInput.value = currentValue + 1;
                }
            }

            window.decrementQuantity = function() {
                const quantityInput = document.getElementById('quantity');
                let currentValue = parseInt(quantityInput.value);
                
                if (currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                }
            }

            // Xử lý khi người dùng nhập trực tiếp vào input
            const quantityInput = document.getElementById('quantity');
            quantityInput.addEventListener('change', function() {
                let value = parseInt(this.value);
                const max = parseInt(this.getAttribute('max'));
                
                if (isNaN(value) || value < 1) {
                    this.value = 1;
                } else if (value > max) {
                    this.value = max;
                }
            });
        });
    </script>
</head>

<body>
    <?php
    // Include file cấu hình kết nối cơ sở dữ liệu
    include 'connect.php';

    // Lấy ID sản phẩm từ tham số URL
    if (isset($_GET['id'])) {
        $product_id = $_GET['id'];
        
        // Truy vấn thông tin sản phẩm
        $sql = "SELECT sp.*, dm.DM_TEN 
                FROM san_pham sp 
                LEFT JOIN danh_muc dm ON sp.DM_MA = dm.DM_MA 
                WHERE sp.SP_MA = $product_id";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
        } else {
            // Nếu không tìm thấy sản phẩm, chuyển hướng về trang shop
            header("Location: shop.php");
            exit();
        }
    } else {
        // Nếu không có ID sản phẩm, chuyển hướng về trang shop
        header("Location: shop.php");
        exit();
    }
    ?>

    <!-- Header -->
    <?php include 'header.php'; ?>

    <section id="center" class="center_o pt-5 pb-5">
        <div class="container-fluid">
            <div class="center_o1 row text-center">
                <div class="col-md-12">
                    <h1>Chi Tiết Sản Phẩm</h1>
                    <h6 class="font_14 mb-0 mt-3">
                        <a href="index.php">Trang Chủ</a>
                        <span class="text-muted mx-1 font_10 align-middle"><i class="fa fa-chevron-right"></i></span>
                        <a href="shop.php">Sản Phẩm</a>
                        <span class="text-muted mx-1 font_10 align-middle"><i class="fa fa-chevron-right"></i></span>
                        <?php echo htmlspecialchars($product['SP_TEN']); ?>
                    </h6>
                </div>
            </div>
        </div>
    </section>

    <section id="shop_dt" class="p_3 bg_light">
        <div class="container-xl">
            <div class="shop_dt1 row">
                <?php
                // Display success or error message
                if(isset($_SESSION['cart_success'])) {
                    echo '<div class="col-md-12 mb-3">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fa fa-check-circle me-2"></i>' . $_SESSION['cart_success'] . '
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                          </div>';
                    unset($_SESSION['cart_success']);
                }
                
                if(isset($_SESSION['cart_error'])) {
                    echo '<div class="col-md-12 mb-3">
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fa fa-exclamation-circle me-2"></i>' . $_SESSION['cart_error'] . '
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                          </div>';
                    unset($_SESSION['cart_error']);
                }
                ?>
                <div class="col-md-6">
                    <div class="shop_dt1l">
                        <div class="product-image-container">
                            <img src="img/<?php echo $product['SP_HINHANH']; ?>"
                                alt="<?php echo htmlspecialchars($product['SP_TEN']); ?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="shop_dt1r">
                        <h4><?php echo htmlspecialchars($product['SP_TEN']); ?></h4>
                        <span class="col_yell">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star-half-o"></i>
                        </span>
                        <h5 class="mt-3 col_green"><?php echo number_format($product['SP_DONGIA'], 0); ?> VNĐ</h5>
                        <div class="d-flex mt-3 align-items-center">
                            <h6 class="me-3 mb-0">Thương hiệu:</h6>
                            <h6 class="mb-0 bg_light p-2 font_14 d-inline-block">
                                <?php echo htmlspecialchars($product['SP_NHASANXUAT']); ?>
                            </h6>
                        </div>
                        <?php if (!empty($product['SP_SIZE'])): ?>
                        <div class="d-flex mt-3 align-items-center">
                            <h6 class="me-3 mb-0">Kích thước:</h6>
                            <h6 class="mb-0 bg_light p-2 font_14 d-inline-block">
                                <?php echo htmlspecialchars($product['SP_SIZE']); ?>
                            </h6>
                        </div>
                        <?php endif; ?>
                        <div class="d-flex mt-3 align-items-center">
                            <h6 class="me-3 mb-0">Danh mục:</h6>
                            <h6 class="mb-0 bg_light p-2 font_14 d-inline-block">
                                <?php if (!empty($product['DM_MA'])): ?>
                                <a href="category.php?id=<?php echo $product['DM_MA']; ?>">
                                    <?php echo htmlspecialchars($product['DM_TEN']); ?>
                                </a>
                                <?php else: ?>
                                Chưa phân loại
                                <?php endif; ?>
                            </h6>
                        </div>
                        <div class="d-flex mt-3 align-items-center">
                            <h6 class="me-3 mb-0">Tình trạng:</h6>
                            <h6 class="mb-0 bg_yell p-2 font_14 d-inline-block text-black">
                                <?php echo ($product['SP_SOLUONGTON'] > 0) ? 'Còn hàng' : 'Hết hàng'; ?>

                            </h6>
                        </div>

                        <h5 class="fs-6 mt-4">Số lượng</h5>
                        <div class="shop_dt1ri mt-3">
                            <form action="cart_add.php" method="post">
                                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                <div class="input-group mb-3" style="width: 150px;">
                                    <button class="btn btn-outline-secondary" type="button" onclick="decrementQuantity()">-</button>
                                    <input type="number" class="form-control text-center" name="quantity" id="quantity" value="1" min="1" max="<?php echo $product['SP_SOLUONGTON']; ?>">
                                    <button class="btn btn-outline-secondary" type="button" onclick="incrementQuantity()">+</button>
                                </div>
                                <button type="submit" class="button mt-3" <?php echo ($product['SP_SOLUONGTON'] <= 0) ? 'disabled' : ''; ?>>
                                    <i class="fa fa-shopping-cart"></i> Thêm vào giỏ hàng
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs for product details -->
            <div class="row mt-5">
                <div class="col-md-12">
                    <!-- Tab Menu -->
                    <ul class="product-tabs">
                        <li class="tab-item">
                            <a href="#description" class="tab-link active">Mô tả</a>
                        </li>
                        <li class="tab-item">
                            <a href="#details" class="tab-link">Chi tiết</a>
                        </li>
                        <li class="tab-item">
                            <a href="#ingredients" class="tab-link">Thành phần</a>
                        </li>
                        <li class="tab-item">
                            <a href="#usage" class="tab-link">Hướng dẫn sử dụng</a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="description">
                            <h5>Mô tả sản phẩm</h5>
                            <div class="description-content">
                                <?php echo $product['SP_MOTA'] ? $product['SP_MOTA'] : 'Chưa có mô tả cho sản phẩm này.'; ?>
                            </div>
                        </div>

                        <!-- Chi tiết -->
                        <div class="tab-pane fade" id="details">
                            <h5>Thông tin chi tiết</h5>
                            <table class="table">
                                <tr>
                                    <th>Thương hiệu:</th>
                                    <td><?php echo htmlspecialchars($product['SP_NHASANXUAT']); ?></td>
                                </tr>
                                <tr>
                                    <th>Trọng lượng:</th>
                                    <td><?php echo htmlspecialchars($product['SP_TRONGLUONG']); ?> <?php echo htmlspecialchars($product['SP_DONVITINH']); ?></td>
                                </tr>
                                <tr>
                                    <th>Đơn vị tính:</th>
                                    <td><?php echo htmlspecialchars($product['SP_DONVITINH']); ?></td>
                                </tr>
                            </table>
                        </div>

                        <!-- Thành phần -->
                        <div class="tab-pane fade" id="ingredients">
                            <div class="description-content">
                                <?php 
                                if ($product['SP_THANHPHAN']) {
                                    // Tách thành phần thành từng dòng
                                    $thanhphan_array = explode("<br>", $product['SP_THANHPHAN']);
                                    echo '<ul class="ingredients-list">';
                                    foreach ($thanhphan_array as $item) {
                                        if (trim($item) !== '') {
                                            echo '<li>' . trim($item) . '</li>';
                                        }
                                    }
                                    echo '</ul>';
                                } else {
                                    echo 'Chưa có thông tin về thành phần.';
                                }
                                ?>
                            </div>
                        </div>

                        <!-- Hướng dẫn sử dụng -->
                        <div class="tab-pane fade" id="usage">
                            <div class="description-content">
                                <?php 
                                if ($product['SP_HUONGDANSUDUNG']) {
                                    // Tách hướng dẫn thành từng bước
                                    $huongdan_array = explode("<br>", $product['SP_HUONGDANSUDUNG']);
                                    echo '<ul class="usage-steps">';
                                    foreach ($huongdan_array as $step) {
                                        if (trim($step) !== '') {
                                            if (strpos(strtolower($step), 'bước') === 0 || strpos(strtolower($step), 'step') === 0) {
                                                // Loại bỏ số bước và dấu : nếu có
                                                $step = preg_replace('/^(bước|step)\s*\d*\s*:\s*/i', '', $step);
                                            }
                                            echo '<li>' . trim($step) . '</li>';
                                        }
                                    }
                                    echo '</ul>';
                                } else {
                                    echo 'Chưa có hướng dẫn sử dụng.';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Tabs -->

            <!-- Sản phẩm liên quan -->
            <div class="shop_dt6 row mt-4">
                <div class="col-md-12">
                    <h3>Sản Phẩm Liên Quan</h3>
                    <hr class="line">
                </div>
            </div>

            <div class="shop_dt7 row mt-4">
                <div class="col-md-12">
                    <div class="related-products">
                        <div class="row row-cols-1 row-cols-md-4 g-4">
                            <?php
                            // Lấy các sản phẩm cùng danh mục
                            $sql_related = "SELECT SP_MA, SP_TEN, SP_DONGIA, SP_HINHANH 
                                          FROM san_pham 
                                          WHERE DM_MA = {$product['DM_MA']} 
                                          AND SP_MA != {$product_id}
                                          AND SP_SOLUONGTON > 0
                                          LIMIT 4";
                            $result_related = $conn->query($sql_related);
                            
                            if ($result_related->num_rows > 0) {
                                while ($related = $result_related->fetch_assoc()) {
                            ?>
                            <div class="col">
                                <div class="list_h2i">
                                    <div class="list_h2i1 position-relative">
                                        <div class="list_h2i1i">
                                            <div class="grid clearfix">
                                                <figure class="effect-jazz mb-0">
                                                    <a href="detail.php?id=<?php echo $related['SP_MA']; ?>">
                                                        <img src="img/<?php echo $related['SP_HINHANH']; ?>"
                                                            class="w-100"
                                                            alt="<?php echo htmlspecialchars($related['SP_TEN']); ?>">
                                                    </a>
                                                </figure>
                                            </div>
                                        </div>
                                        <div class="list_h2i1i1 position-absolute top-0 p-1">
                                            <h6
                                                class="mb-0 font_12 fw-bold d-inline-block bg_yell col_black lh-1 rounded_30 p-1 px-2">
                                                Sản phẩm mới
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="list_h2i2">
                                        <h6 class="fw-bold font_14">
                                            <a href="detail.php?id=<?php echo $related['SP_MA']; ?>">
                                                <?php echo htmlspecialchars($related['SP_TEN']); ?>
                                            </a>
                                        </h6>
                                        <span class="col_yell">
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                        </span>
                                        <h6 class="mt-2 font_14">
                                            <span class="span_1 col_green fw-bold">
                                                <?php echo number_format($related['SP_DONGIA'], 0); ?> VNĐ
                                            </span>
                                        </h6>
                                        <div class="button-container">
                                            <a class="button" href="detail.php?id=<?php echo $related['SP_MA']; ?>">
                                                Xem chi tiết
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                                }
                            } else {
                                echo '<div class="col-12"><p class="text-center">Không có sản phẩm liên quan.</p></div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <!-- Đóng kết nối database -->
    <?php $conn->close(); ?>
</body>

</html>