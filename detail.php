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
    </style>
</head>

<body>
    <?php
    // Start the session
    session_start();

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
                            <img src="img/<?php echo $product['SP_HINHANH']; ?>" class="d-block w-100"
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
                            <?php if(isset($_SESSION['user_id'])): ?>
                            <form method="post" action="cart_add.php">
                                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                <input type="number" min="1" max="<?php echo $product['SP_SOLUONGTON']; ?>" value="1"
                                    name="quantity" class="form-control float-start me-3 rounded-3"
                                    placeholder="Số lượng" style="width:80px; height:50px;">
                                <button type="submit" class="button border-0"
                                    <?php echo ($product['SP_SOLUONGTON'] <= 0) ? 'disabled' : ''; ?>>
                                    <i class="fa fa-shopping-bag me-1"></i> Thêm vào giỏ hàng
                                </button>
                            </form>
                            <?php else: ?>
                            <div class="alert alert-warning" role="alert">
                                <i class="fa fa-exclamation-circle me-1"></i> Vui lòng <a href="login.php"
                                    class="alert-link">đăng nhập</a> để thêm sản phẩm vào giỏ hàng.
                            </div>
                            <a href="login.php" class="button">
                                <i class="fa fa-sign-in me-1"></i> Đăng nhập để mua hàng
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="shop_dt3 row mt-4">
                <div class="col-md-12">
                    <ul class="nav nav-tabs mb-0 border-0">
                        <li class="nav-item">
                            <a href="#description" data-bs-toggle="tab" class="nav-link active">Mô tả sản phẩm</a>
                        </li>
                        <li class="nav-item">
                            <a href="#details" data-bs-toggle="tab" class="nav-link">Thông tin chi tiết</a>
                        </li>
                        <?php if (!empty($product['SP_THANHPHAN'])): ?>
                        <li class="nav-item">
                            <a href="#ingredients" data-bs-toggle="tab" class="nav-link">Thành phần</a>
                        </li>
                        <?php endif; ?>
                        <?php if (!empty($product['SP_HUONGDANSUDUNG'])): ?>
                        <li class="nav-item">
                            <a href="#usage" data-bs-toggle="tab" class="nav-link">Hướng dẫn sử dụng</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <div class="shop_dt4 row mt-4">
                <div class="col-md-12">
                    <div class="tab-content">
                        <div class="tab-pane active" id="description">
                            <div class="product-description">
                                <?php if (!empty($product['SP_MOTA'])): ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 class="mb-3">Mô tả sản phẩm</h5>
                                        <div class="description-content">
                                            <?php echo nl2br(htmlspecialchars($product['SP_MOTA'])); ?>
                                        </div>
                                    </div>
                                </div>
                                <?php else: ?>
                                <p class="text-center py-3">Chưa có mô tả cho sản phẩm này.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="tab-pane" id="details">
                            <div class="product-details">
                                <h5 class="mb-3">Thông tin chi tiết sản phẩm</h5>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td width="30%"><strong>Mã sản phẩm</strong></td>
                                            <td><?php echo $product['SP_MA']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tên sản phẩm</strong></td>
                                            <td><?php echo htmlspecialchars($product['SP_TEN']); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Thương hiệu</strong></td>
                                            <td><?php echo htmlspecialchars($product['SP_NHASANXUAT']); ?></td>
                                        </tr>
                                        <?php if (!empty($product['SP_SIZE'])): ?>
                                        <tr>
                                            <td><strong>Kích thước</strong></td>
                                            <td><?php echo htmlspecialchars($product['SP_SIZE']); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <tr>
                                            <td><strong>Danh mục</strong></td>
                                            <td>
                                                <?php if (!empty($product['DM_MA'])): ?>
                                                <a href="category.php?id=<?php echo $product['DM_MA']; ?>">
                                                    <?php echo htmlspecialchars($product['DM_TEN']); ?>
                                                </a>
                                                <?php else: ?>
                                                Chưa phân loại
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Đơn giá</strong></td>
                                            <td><?php echo number_format($product['SP_DONGIA'], 0); ?> VNĐ</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Đơn vị tính</strong></td>
                                            <td><?php echo htmlspecialchars($product['SP_DONVITINH']); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Số lượng tồn</strong></td>
                                            <td><?php echo $product['SP_SOLUONGTON']; ?> sản phẩm</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <?php if (!empty($product['SP_THANHPHAN'])): ?>
                        <div class="tab-pane" id="ingredients">
                            <div class="product-details">
                                <h5 class="mb-3">Thành phần sản phẩm</h5>
                                <div class="p-3 bg-white rounded">
                                    <?php echo nl2br(htmlspecialchars($product['SP_THANHPHAN'])); ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($product['SP_HUONGDANSUDUNG'])): ?>
                        <div class="tab-pane" id="usage">
                            <div class="product-details">
                                <h5 class="mb-3">Hướng dẫn sử dụng</h5>
                                <div class="p-3 bg-white rounded">
                                    <?php echo nl2br(htmlspecialchars($product['SP_HUONGDANSUDUNG'])); ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

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