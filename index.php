<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chợ Cây Trồng</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/global.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
    <style>
    /* Chỉnh màu cho tiêu đề "Dịch vụ" */
    .services h2,
    .services h3,
    .services .heading {
        color: #000000 !important;
    }

    /* Đảm bảo màu chữ cho các tiêu đề khác */
    .section-heading h2,
    .section-heading h3 {
        color: #000000 !important;
    }
    </style>
</head>
<?php
// Start the session
session_start();

// Include file kết nối cơ sở dữ liệu
include 'connect.php';

// Truy vấn danh sách danh mục
$sql = "SELECT DM_MA, DM_TEN, DM_AVATAR FROM danh_muc"; // Updated to include DM_MA
$result = $conn->query($sql);

// Debug: Kiểm tra lỗi SQL
if (!$result) {
    error_log("Lỗi truy vấn danh mục: " . $conn->error);
    echo "Có lỗi xảy ra khi truy vấn danh mục. Vui lòng kiểm tra error log.";
}

// Debug: Kiểm tra số lượng danh mục
if ($result) {
    error_log("Số lượng danh mục tìm thấy: " . $result->num_rows);
}
?>

<body>
    <!-- header -->
    <?php include 'header.php'; ?>

    <?php
    // Display success or error message
    if(isset($_SESSION['cart_success'])) {
        echo '<div class="container mt-3">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fa fa-check-circle me-2"></i>' . $_SESSION['cart_success'] . '
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
              </div>';
        unset($_SESSION['cart_success']);
    }
    
    if(isset($_SESSION['cart_error'])) {
        echo '<div class="container mt-3">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fa fa-exclamation-circle me-2"></i>' . $_SESSION['cart_error'] . '
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
              </div>';
        unset($_SESSION['cart_error']);
    }
    ?>

    <!-- Hero Banner Section -->
    <section id="hero-banner" class="position-relative">
        <div class="container-fluid px-0">
            <div class="hero-slider">
                <div class="hero-slide position-relative">
                    <img src="img/banner chinh 10.jpg" alt="Banner" class="w-100 parallax-bg" data-speed="0.3">
                    <div class="hero-content position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6 animate-on-scroll">
                                    <h1 class="display-4 fw-bold text-white">Chào mừng đến với<br><span
                                            class="col_green">Chợ Cây Trồng</span></h1>
                                    <p class="text-white my-4">Cung cấp hạt giống, phân bón chất lượng cao và dụng cụ
                                        làm vườn chuyên nghiệp.</p>
                                    <a href="shop.php" class="button btn-lg">Khám phá ngay</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Category Section -->
    <section class="category-section py-5">
        <div class="container">
            <div class="section-title text-center mb-5">
                <h2 class="mb-3"><span class="col_green">Danh mục sản phẩm</span></h2>
                <p class="text-muted">Khám phá các danh mục sản phẩm đa dạng của chúng tôi</p>
            </div>
            <div class="category-grid">
                <?php
                // Debug: Kiểm tra kết nối và truy vấn
                if (!$conn) {
                    error_log("Lỗi kết nối database");
                    die("Lỗi kết nối database");
                }

                $sql = "SELECT DM_MA, DM_TEN, DM_AVATAR FROM danh_muc";
                $result = $conn->query($sql);

                if (!$result) {
                    error_log("Lỗi truy vấn: " . $conn->error);
                    die("Lỗi truy vấn database");
                }

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $category_id = $row['DM_MA'];
                        $ten_danhmuc = htmlspecialchars($row['DM_TEN']);
                        $hinh_anh = htmlspecialchars($row['DM_AVATAR']);
                        
                        // Debug: Kiểm tra thông tin danh mục
                        error_log("Danh mục: ID={$category_id}, Tên={$ten_danhmuc}, Hình={$hinh_anh}");
                        
                        // Kiểm tra file hình ảnh tồn tại
                        $image_path = "img/" . $hinh_anh;
                        if (!file_exists($image_path)) {
                            error_log("Không tìm thấy file hình ảnh: {$image_path}");
                            $hinh_anh = 'default.jpg'; // Sử dụng hình mặc định nếu không tìm thấy
                        }
                        
                        echo '<div class="category-card">
                                <a href="category.php?id='.$category_id.'" class="category-link">
                                    <div class="category-image">
                                        <img src="img/'.$hinh_anh.'" alt="'.$ten_danhmuc.'">
                                    </div>
                                    <h3 class="category-title">'.$ten_danhmuc.'</h3>
                                </a>
                            </div>';
                    }
                } else {
                    echo '<div class="alert alert-info text-center">Không có danh mục nào.</div>';
                    error_log("Không tìm thấy danh mục nào trong database");
                }
                ?>
            </div>
        </div>
    </section>
    <!-- Featured Products Section -->
    <section id="list_h" class="p_3">
        <div class="container-fluid">
            <div class="list_h1 text-center mb-4 row">
                <div class="col-md-12 animate-on-scroll">
                    <h2 class="mb-0"><span class="col_green">Sản phẩm Nổi bật</span></h2>
                </div>
            </div>
            <div class="list_h2 row">
                <?php
            // Query to fetch featured products
            $sql = "SELECT SP_MA, SP_TEN, SP_DONGIA, SP_HINHANH, SP_DONVITINH 
                    FROM san_pham 
                    ORDER BY SP_DONGIA DESC 
                    LIMIT 6";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $product_id = $row['SP_MA'];
                    $ten_sanpham = htmlspecialchars($row['SP_TEN']);
                    $dongia = number_format($row['SP_DONGIA'], 0, ',', '.');
                    $hinh_anh = htmlspecialchars($row['SP_HINHANH']);
                    $discount = 20;
                    $original_price = $row['SP_DONGIA'] / (1 - $discount / 100);
                    $original_price = number_format($original_price, 0, ',', '.');

                    echo '
                    <div class="col-md-2 col-sm-6">
                        <div class="list_h2i">
                            <div class="list_h2i1 position-relative">
                                <div class="list_h2i1i">
                                    <div class="grid clearfix">
                                        <figure class="effect-jazz mb-0">
                                            <a href="product.php?id=' . $product_id . '"><img src="img/' . $hinh_anh . '" class="w-100" alt="' . $ten_sanpham . '"></a>
                                        </figure>
                                    </div>
                                </div>
                                <div class="list_h2i1i1 position-absolute top-0 p-1">
                                    <h6 class="mb-0 font_12 fw-bold d-inline-block bg_yell col_black lh-1 rounded_30 p-1 px-2">-' . $discount . '%</h6>
                                </div>
                            </div>
                            <div class="list_h2i2">
                                <h6 class="fw-bold font_14"><a href="product.php?id=' . $product_id . '">' . $ten_sanpham . '</a></h6>
                                <span class="col_yell">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star-half-o"></i>
                                </span>
                                <h6 class="mt-2 font_14"><span class="span_1 col_green fw-bold">' . $dongia . 'đ</span> <span class="span_2 ms-2 text-decoration-line-through">' . $original_price . 'đ</span></h6>
                                <h6 class="mb-0 mt-4 text-center"><a class="button" href="add_to_cart.php?id=' . $product_id . '">Chọn mua</a></h6>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                echo '<div class="col-12"><p class="text-center">Không có sản phẩm nổi bật nào.</p></div>';
            }
            ?>
            </div>
            <div class="list_h3 text-center mt-5 row">
                <div class="col-md-12">
                    <h6 class="mb-0"><a class="button_2" href="shop.php">Xem thêm sản phẩm</a></h6>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section id="stats" class="p-5 bg_green text-white">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 mb-4 mb-md-0">
                    <div class="stat-item">
                        <i class="fa fa-users fa-3x mb-3"></i>
                        <h2 class="counter" data-target="1000">0</h2>
                        <p>Khách hàng hài lòng</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <div class="stat-item">
                        <i class="fa fa-leaf fa-3x mb-3"></i>
                        <h2 class="counter" data-target="500">0</h2>
                        <p>Loại sản phẩm</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <div class="stat-item">
                        <i class="fa fa-shopping-bag fa-3x mb-3"></i>
                        <h2 class="counter" data-target="2500">0</h2>
                        <p>Đơn hàng thành công</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <i class="fa fa-trophy fa-3x mb-3"></i>
                        <h2 class="counter" data-target="10">0</h2>
                        <p>Năm kinh nghiệm</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Best Sellers Section -->
    <!-- Best Sellers Section -->
    <section id="sale" class="p_3 bg_lighto">
        <div class="container-fluid">
            <div class="list_h1 text-center mb-4 row">
                <div class="col-md-12 animate-on-scroll">
                    <h2 class="mb-0"><span class="col_green">Bán chạy nhất</span></h2>
                </div>
            </div>
            <div class="list_h2 row">
                <?php
                $sql = "SELECT sp.SP_MA, sp.SP_TEN, sp.SP_DONGIA, sp.SP_HINHANH, sp.SP_DONVITINH, COALESCE(SUM(cthd.CTHD_SOLUONG), 0) as total_sold FROM san_pham sp LEFT JOIN chi_tiet_hd cthd ON sp.SP_MA = cthd.SP_MA GROUP BY sp.SP_MA, sp.SP_TEN, sp.SP_DONGIA, sp.SP_HINHANH, sp.SP_DONVITINH ORDER BY total_sold DESC LIMIT 6;";
                $result = $conn->query($sql);
                if (!$result) {
                    die("Truy vấn sản phẩm bán chạy thất bại: " . $conn->error);
                }

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $product_id = $row['SP_MA'];
                        $ten_sanpham = htmlspecialchars($row['SP_TEN']);
                        $dongia = number_format($row['SP_DONGIA'] ?? 0, 0, ',', '.');
                        $hinh_anh = htmlspecialchars($row['SP_HINHANH'] ?? 'default.jpg'); // Fallback image if NULL
                        $discount = 20;
                        $original_price = ($row['SP_DONGIA'] ?? 0) / (1 - $discount / 100);
                        $original_price = number_format($original_price, 0, ',', '.');

                        echo '
                        <div class="col-md-2 col-sm-6">
                            <div class="list_h2i">
                                <div class="list_h2i1 position-relative">
                                    <div class="list_h2i1i">
                                        <div class="grid clearfix">
                                            <figure class="effect-jazz mb-0">
                                                <a href="product.php?id=' . $product_id . '"><img src="img/' . $hinh_anh . '" class="w-100" alt="' . $ten_sanpham . '"></a>
                                            </figure>
                                        </div>
                                    </div>
                                    <div class="list_h2i1i1 position-absolute top-0 p-1">
                                        <h6 class="mb-0 font_12 fw-bold d-inline-block bg_yell col_black lh-1 rounded_30 p-1 px-2">-' . $discount . '%</h6>
                                    </div>
                                </div>
                                <div class="list_h2i2">
                                    <h6 class="fw-bold font_14"><a href="product.php?id=' . $product_id . '">' . $ten_sanpham . '</a></h6>
                                    <span class="col_yell">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-half-o"></i>
                                    </span>
                                    <h6 class="mt-2 font_14"><span class="span_1 col_green fw-bold">' . $dongia . 'đ</span> <span class="span_2 ms-2 text-decoration-line-through">' . $original_price . 'đ</span></h6>
                                    <h6 class="mb-0 mt-4 text-center"><a class="button" href="add_to_cart.php?id=' . $product_id . '">Chọn mua</a></h6>
                                </div>
                            </div>
                        </div>';
                    }
                } else {
                    echo '<div class="col-12"><p class="text-center">Không có sản phẩm bán chạy nào.</p></div>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="serv_n" class="p_3 pb-0">
        <div class="container-fluid">
            <div class="list_h1 text-center mb-4 row">
                <div class="col-md-12 animate-on-scroll">
                    <h2 class="mb-0"><span class="col_green">Dịch vụ của chúng tôi</span> </h2>
                </div>
            </div>
            <div class="serv_n row">
                <div class="col-md-3">
                    <div class="serv_nml">
                        <div class="serv_nl border_1 position-relative">
                            <div class="serv_nli">
                                <h5>Mua sắm</h5>
                                <hr class="line_1">
                                <p class="mb-0">Cung cấp quy trình giỏ hàng đơn giản cho người dùng và các dịch vụ khác.
                                </p>
                            </div>
                            <div class="serv_nli1 position-absolute">
                                <span class="d-inline-block bg_yell text-black text-center rounded-circle"><i
                                        class="fa fa-shopping-bag"></i></span>
                            </div>
                        </div>
                        <div class="serv_nl border_1 position-relative mt-4">
                            <div class="serv_nli">
                                <h5>Thư giãn</h5>
                                <hr class="line_1">
                                <p class="mb-0">Mang thiên nhiên vào cuộc sống của bạn, tăng năng suất và thư giãn.</p>
                            </div>
                            <div class="serv_nli1 position-absolute">
                                <span class="d-inline-block bg_yell text-black text-center rounded-circle"><i
                                        class="fa fa-dollar"></i></span>
                            </div>
                        </div>
                        <div class="serv_nl border_1 position-relative mt-4">
                            <div class="serv_nli">
                                <h5>Giao hàng</h5>
                                <hr class="line_1">
                                <p class="mb-0">Giao hàng tận nơi, cải thiện sức khỏe tinh thần và hạnh phúc.</p>
                            </div>
                            <div class="serv_nli1 position-absolute">
                                <span class="d-inline-block bg_yell text-black text-center rounded-circle"><i
                                        class="fa fa-truck"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="serv_nm text-center" style="margin-top: 150px;">
                        <img src="img/service.jpg" class="img-fluid" alt="Dịch vụ">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="serv_nml">
                        <div class="serv_nl border_1 position-relative">
                            <div class="serv_nli text-end serv_nlio">
                                <h5>Chất lượng</h5>
                                <hr class="line_1 ms-auto">
                                <p class="mb-0">Cung cấp cây chất lượng cho người làm vườn. Trang trí ngôi nhà của bạn
                                    với cây xanh.</p>
                            </div>
                            <div class="serv_nli1 position-absolute serv_nli1o">
                                <span class="d-inline-block bg_yell text-black text-center rounded-circle"><i
                                        class="fa fa-trademark"></i></span>
                            </div>
                        </div>
                        <div class="serv_nl border_1 position-relative mt-4">
                            <div class="serv_nli text-end serv_nlio">
                                <h5>Chuyên gia vườn ươm</h5>
                                <hr class="line_1 ms-auto">
                                <p class="mb-0">Nhận mẹo từ chuyên gia về cách chăm sóc cây tại nhà.</p>
                            </div>
                            <div class="serv_nli1 position-absolute serv_nli1o">
                                <span class="d-inline-block bg_yell text-black text-center rounded-circle"><i
                                        class="fa fa-user-plus"></i></span>
                            </div>
                        </div>
                        <div class="serv_nl border_1 position-relative mt-4">
                            <div class="serv_nli text-end serv_nlio">
                                <h5>Trung tâm hỗ trợ 24/7</h5>
                                <hr class="line_1 ms-auto">
                                <p class="mb-0">Hỗ trợ liên tục, giải đáp mọi thắc mắc của bạn bất kỳ lúc nào.</p>
                            </div>
                            <div class="serv_nli1 position-absolute serv_nli1o">
                                <span class="d-inline-block bg_yell text-black text-center rounded-circle"><i
                                        class="fa fa-phone"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
$conn->close();
?>
    <!-- Back to Top Button -->
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="fa fa-arrow-up"></i>
    </a>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <!-- Include our custom JS file -->
    <script src="js/custom.js"></script>
</body>

</html>