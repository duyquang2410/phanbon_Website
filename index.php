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
    <section class="category-section">
        <div class="container">
            <div class="section-title">
                <h2> <span class="col_green">Danh mục sản phẩm</span></h2>
                <p class="text-muted">Khám phá các danh mục sản phẩm đa dạng của chúng tôi</p>
            </div>
            <div class="category-grid">
                <?php
                if ($result->num_rows > 0) {
                    $delay = 0;
                    while ($row = $result->fetch_assoc()) {
                        $category_id = $row['DM_MA'];
                        $ten_danhmuc = htmlspecialchars($row['DM_TEN']);
                        $hinh_anh = htmlspecialchars($row['DM_AVATAR']);
                        echo '
                        <div class="category-card animate-on-scroll" data-aos="fade-up" data-aos-delay="'.$delay.'">
                            <a href="category.php?id='.$category_id.'" class="category-link">
                                <div class="category-image">
                                    <img src="img/'.$hinh_anh.'" alt="'.$ten_danhmuc.'">
                                </div>
                                <h3 class="category-title">'.$ten_danhmuc.'</h3>
                            </a>
                        </div>';
                        $delay += 100;
                    }
                } else {
                    echo '<div class="col-12"><p class="text-center">Không có danh mục nào.</p></div>';
                }
                ?>
            </div>
        </div>
    </section>

    <?php
    // Đóng kết nối
    $conn->close();
    ?>

    <!-- Featured Products Section -->
    <section id="list_h" class="p_3">
        <div class="container-fluid">
            <div class="list_h1 text-center mb-4 row">
                <div class="col-md-12 animate-on-scroll">
                    <h2 class="mb-0"><span class="col_green">Sản phẩm Nổi bật</span> </h2>
                </div>
            </div>
            <div class="list_h2 row">
                <div class="col-md-2 col-sm-6">
                    <div class="list_h2i">
                        <div class="list_h2i1 position-relative">
                            <div class="list_h2i1i">
                                <div class="grid clearfix">
                                    <figure class="effect-jazz mb-0">
                                        <a href="#"><img src="img/7.jpg" class="w-100"
                                                alt="Hạt giống rau củ quanh năm"></a>
                                    </figure>
                                </div>
                            </div>
                            <div class="list_h2i1i1 position-absolute top-0 p-1">
                                <h6
                                    class="mb-0 font_12 fw-bold d-inline-block bg_yell col_black lh-1 rounded_30 p-1 px-2">
                                    -20%</h6>
                            </div>
                        </div>
                        <div class="list_h2i2">
                            <h6 class="fw-bold font_14"><a href="#">Hạt giống rau củ quanh năm</a></h6>
                            <span class="col_yell">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star-half-o"></i>
                            </span>
                            <h6 class="mt-2 font_14"><span class="span_1 col_green fw-bold">189.000đ</span> <span
                                    class="span_2 ms-2 text-decoration-line-through">430.000đ</span></h6>
                            <h6 class="mb-0 mt-4 text-center"><a class="button" href="#">Chọn mua</a></h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <div class="list_h2i">
                        <div class="list_h2i1 position-relative">
                            <div class="list_h2i1i">
                                <div class="grid clearfix">
                                    <figure class="effect-jazz mb-0">
                                        <a href="#"><img src="img/8.jpg" class="w-100"
                                                alt="Hạt giống cà chua F1 lai"></a>
                                    </figure>
                                </div>
                            </div>
                            <div class="list_h2i1i1 position-absolute top-0 p-1">
                                <h6
                                    class="mb-0 font_12 fw-bold d-inline-block bg_yell col_black lh-1 rounded_30 p-1 px-2">
                                    -30%</h6>
                            </div>
                        </div>
                        <div class="list_h2i2">
                            <h6 class="fw-bold font_14"><a href="#">Hạt giống cà chua F1 lai</a></h6>
                            <span class="col_yell">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star-half-o"></i>
                            </span>
                            <h6 class="mt-2 font_14"><span class="span_1 col_green fw-bold">489.000đ</span> <span
                                    class="span_2 ms-2 text-decoration-line-through">990.000đ</span></h6>
                            <h6 class="mb-0 mt-4 text-center"><a class="button" href="#">Chọn mua</a></h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <div class="list_h2i">
                        <div class="list_h2i1 position-relative">
                            <div class="list_h2i1i">
                                <div class="grid clearfix">
                                    <figure class="effect-jazz mb-0">
                                        <a href="#"><img src="img/9.jpg" class="w-100" alt="Đậu bắp"></a>
                                    </figure>
                                </div>
                            </div>
                            <div class="list_h2i1i1 position-absolute top-0 p-1">
                                <h6
                                    class="mb-0 font_12 fw-bold d-inline-block bg_yell col_black lh-1 rounded_30 p-1 px-2">
                                    -40%</h6>
                            </div>
                        </div>
                        <div class="list_h2i2">
                            <h6 class="fw-bold font_14"><a href="#">Đậu bắp</a></h6>
                            <span class="col_yell">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star-half-o"></i>
                            </span>
                            <h6 class="mt-2 font_14"><span class="span_1 col_green fw-bold">389.000đ</span> <span
                                    class="span_2 ms-2 text-decoration-line-through">680.000đ</span></h6>
                            <h6 class="mb-0 mt-4 text-center"><a class="button" href="#">Chọn mua</a></h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <div class="list_h2i">
                        <div class="list_h2i1 position-relative">
                            <div class="list_h2i1i">
                                <div class="grid clearfix">
                                    <figure class="effect-jazz mb-0">
                                        <a href="#"><img src="img/10.jpg" class="w-100" alt="Dưa leo"></a>
                                    </figure>
                                </div>
                            </div>
                            <div class="list_h2i1i1 position-absolute top-0 p-1">
                                <h6
                                    class="mb-0 font_12 fw-bold d-inline-block bg_yell col_black lh-1 rounded_30 p-1 px-2">
                                    -25%</h6>
                            </div>
                        </div>
                        <div class="list_h2i2">
                            <h6 class="fw-bold font_14"><a href="#">Dưa leo</a></h6>
                            <span class="col_yell">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star-half-o"></i>
                            </span>
                            <h6 class="mt-2 font_14"><span class="span_1 col_green fw-bold">289.000đ</span> <span
                                    class="span_2 ms-2 text-decoration-line-through">699.000đ</span></h6>
                            <h6 class="mb-0 mt-4 text-center"><a class="button" href="#">Chọn mua</a></h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <div class="list_h2i">
                        <div class="list_h2i1 position-relative">
                            <div class="list_h2i1i">
                                <div class="grid clearfix">
                                    <figure class="effect-jazz mb-0">
                                        <a href="#"><img src="img/11.jpg" class="w-100" alt="Hạt giống rau mùa hè"></a>
                                    </figure>
                                </div>
                            </div>
                            <div class="list_h2i1i1 position-absolute top-0 p-1">
                                <h6
                                    class="mb-0 font_12 fw-bold d-inline-block bg_yell col_black lh-1 rounded_30 p-1 px-2">
                                    -20%</h6>
                            </div>
                        </div>
                        <div class="list_h2i2">
                            <h6 class="fw-bold font_14"><a href="#">Hạt giống rau mùa hè</a></h6>
                            <span class="col_yell">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star-half-o"></i>
                            </span>
                            <h6 class="mt-2 font_14"><span class="span_1 col_green fw-bold">269.000đ</span> <span
                                    class="span_2 ms-2 text-decoration-line-through">799.000đ</span></h6>
                            <h6 class="mb-0 mt-4 text-center"><a class="button" href="#">Chọn mua</a></h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <div class="list_h2i">
                        <div class="list_h2i1 position-relative">
                            <div class="list_h2i1i">
                                <div class="grid clearfix">
                                    <figure class="effect-jazz mb-0">
                                        <a href="#"><img src="img/12.jpg" class="w-100"
                                                alt="HDPE 12x12 Túi trồng cây"></a>
                                    </figure>
                                </div>
                            </div>
                            <div class="list_h2i1i1 position-absolute top-0 p-1">
                                <h6
                                    class="mb-0 font_12 fw-bold d-inline-block bg_yell col_black lh-1 rounded_30 p-1 px-2">
                                    -15%</h6>
                            </div>
                        </div>
                        <div class="list_h2i2">
                            <h6 class="fw-bold font_14"><a href="#">HDPE 12x12 Túi trồng cây</a></h6>
                            <span class="col_yell">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star-half-o"></i>
                            </span>
                            <h6 class="mt-2 font_14"><span class="span_1 col_green fw-bold">189.000đ</span> <span
                                    class="span_2 ms-2 text-decoration-line-through">430.000đ</span></h6>
                            <h6 class="mb-0 mt-4 text-center"><a class="button" href="#">Chọn mua</a></h6>
                        </div>
                    </div>
                </div>
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
    <section id="sale" class="p_3 bg_lighto">
        <div class="container-fluid">
            <div class="list_h1 text-center mb-4 row">
                <div class="col-md-12 animate-on-scroll">
                    <h2 class="mb-0"><span class="col_green">Bán chạy nhất</span></h2>
                </div>
            </div>
            <div class="list_h2 row">
                <div class="col-md-2 col-sm-6">
                    <div class="list_h2i">
                        <div class="list_h2i1 position-relative">
                            <div class="list_h2i1i">
                                <div class="grid clearfix">
                                    <figure class="effect-jazz mb-0">
                                        <a href="#"><img src="img/13.jpg" class="w-100" alt="abc"></a>
                                    </figure>
                                </div>
                            </div>
                            <div class="list_h2i1i1 position-absolute top-0 p-1">
                                <h6
                                    class="mb-0 font_12 fw-bold d-inline-block bg_yell col_black lh-1 rounded_30 p-1 px-2">
                                    -35%</h6>
                            </div>
                        </div>
                        <div class="list_h2i2">
                            <h6 class="fw-bold font_14"><a href="#">HDPE 12x12 Grow Bags for</a></h6>
                            <span class="col_yell">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star-half-o"></i>
                            </span>
                            <h6 class="mt-2 font_14"><span class="span_1 col_green fw-bold">$ 189.00</span> <span
                                    class="span_2 ms-2 text-decoration-line-through">$ 430.00</span></h6>
                            <h6 class="mb-0 mt-4 text-center"><a class="button" href="#">Add to Cart</a></h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <div class="list_h2i">
                        <div class="list_h2i1 position-relative">
                            <div class="list_h2i1i">
                                <div class="grid clearfix">
                                    <figure class="effect-jazz mb-0">
                                        <a href="#"><img src="img/14.jpg" class="w-100" alt="abc"></a>
                                    </figure>
                                </div>
                            </div>
                            <div class="list_h2i1i1 position-absolute top-0 p-1">
                                <h6
                                    class="mb-0 font_12 fw-bold d-inline-block bg_yell col_black lh-1 rounded_30 p-1 px-2">
                                    -40%</h6>
                            </div>
                        </div>
                        <div class="list_h2i2">
                            <h6 class="fw-bold font_14"><a href="#">Tomato F1 Hybrid Seeds</a></h6>
                            <span class="col_yell">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star-half-o"></i>
                            </span>
                            <h6 class="mt-2 font_14"><span class="span_1 col_green fw-bold">$ 489.00</span> <span
                                    class="span_2 ms-2 text-decoration-line-through">$ 990.00</span></h6>
                            <h6 class="mb-0 mt-4 text-center"><a class="button" href="#">Add to Cart</a></h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <div class="list_h2i">
                        <div class="list_h2i1 position-relative">
                            <div class="list_h2i1i">
                                <div class="grid clearfix">
                                    <figure class="effect-jazz mb-0">
                                        <a href="#"><img src="img/15.jpg" class="w-100" alt="abc"></a>
                                    </figure>
                                </div>
                            </div>
                            <div class="list_h2i1i1 position-absolute top-0 p-1">
                                <h6
                                    class="mb-0 font_12 fw-bold d-inline-block bg_yell col_black lh-1 rounded_30 p-1 px-2">
                                    -23%</h6>
                            </div>
                        </div>
                        <div class="list_h2i2">
                            <h6 class="fw-bold font_14"><a href="#">Okra or Lady Finger</a></h6>
                            <span class="col_yell">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star-half-o"></i>
                            </span>
                            <h6 class="mt-2 font_14"><span class="span_1 col_green fw-bold">$ 389.00</span> <span
                                    class="span_2 ms-2 text-decoration-line-through">$ 680.00</span></h6>
                            <h6 class="mb-0 mt-4 text-center"><a class="button" href="#">Add to Cart</a></h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <div class="list_h2i">
                        <div class="list_h2i1 position-relative">
                            <div class="list_h2i1i">
                                <div class="grid clearfix">
                                    <figure class="effect-jazz mb-0">
                                        <a href="#"><img src="img/16.jpg" class="w-100" alt="abc"></a>
                                    </figure>
                                </div>
                            </div>
                            <div class="list_h2i1i1 position-absolute top-0 p-1">
                                <h6
                                    class="mb-0 font_12 fw-bold d-inline-block bg_yell col_black lh-1 rounded_30 p-1 px-2">
                                    -5%</h6>
                            </div>
                        </div>
                        <div class="list_h2i2">
                            <h6 class="fw-bold font_14"><a href="#">Cucumber (Kheera) </a></h6>
                            <span class="col_yell">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star-half-o"></i>
                            </span>
                            <h6 class="mt-2 font_14"><span class="span_1 col_green fw-bold">$ 289.00</span> <span
                                    class="span_2 ms-2 text-decoration-line-through">$ 699.00</span></h6>
                            <h6 class="mb-0 mt-4 text-center"><a class="button" href="#">Add to Cart</a></h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <div class="list_h2i">
                        <div class="list_h2i1 position-relative">
                            <div class="list_h2i1i">
                                <div class="grid clearfix">
                                    <figure class="effect-jazz mb-0">
                                        <a href="#"><img src="img/17.jpg" class="w-100" alt="abc"></a>
                                    </figure>
                                </div>
                            </div>
                            <div class="list_h2i1i1 position-absolute top-0 p-1">
                                <h6
                                    class="mb-0 font_12 fw-bold d-inline-block bg_yell col_black lh-1 rounded_30 p-1 px-2">
                                    -20%</h6>
                            </div>
                        </div>
                        <div class="list_h2i2">
                            <h6 class="fw-bold font_14"><a href="#">Capsicum (Hari Shimla</a></h6>
                            <span class="col_yell">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star-half-o"></i>
                            </span>
                            <h6 class="mt-2 font_14"><span class="span_1 col_green fw-bold">$ 269.00</span> <span
                                    class="span_2 ms-2 text-decoration-line-through">$ 799.00</span></h6>
                            <h6 class="mb-0 mt-4 text-center"><a class="button" href="#">Add to Cart</a></h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <div class="list_h2i">
                        <div class="list_h2i1 position-relative">
                            <div class="list_h2i1i">
                                <div class="grid clearfix">
                                    <figure class="effect-jazz mb-0">
                                        <a href="#"><img src="img/18.jpg" class="w-100" alt="abc"></a>
                                    </figure>
                                </div>
                            </div>
                            <div class="list_h2i1i1 position-absolute top-0 p-1">
                                <h6
                                    class="mb-0 font_12 fw-bold d-inline-block bg_yell col_black lh-1 rounded_30 p-1 px-2">
                                    -30%</h6>
                            </div>
                        </div>
                        <div class="list_h2i2">
                            <h6 class="fw-bold font_14"><a href="#">Brinjal Seeds Black Hybrid</a></h6>
                            <span class="col_yell">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star-half-o"></i>
                            </span>
                            <h6 class="mt-2 font_14"><span class="span_1 col_green fw-bold">$ 1189.00</span> <span
                                    class="span_2 ms-2 text-decoration-line-through">$ 1990.00</span></h6>
                            <h6 class="mb-0 mt-4 text-center"><a class="button" href="#">Add to Cart</a></h6>
                        </div>
                    </div>
                </div>
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