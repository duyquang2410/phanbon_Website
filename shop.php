<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Plants Shop - Sản Phẩm</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/global.css" rel="stylesheet">
    <link href="css/shop.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <?php
// Start the session
session_start();

// Include file cấu hình kết nối cơ sở dữ liệu
include 'connect.php';
?>

    <!-- Header -->
    <?php include 'header.php'; ?>

    <section id="center" class="center_o pt-5 pb-5">
        <div class="container-fluid">
            <div class="center_o1 row text-center">
                <div class="col-md-12">
                    <?php
                    // Hiển thị tiêu đề dựa trên kết quả tìm kiếm
                    if (isset($_GET['search']) && !empty($_GET['search'])) {
                        $search_term = htmlspecialchars($_GET['search']);
                        echo '<h1>Kết quả tìm kiếm cho: "' . $search_term . '"</h1>';
                    } else {
                        echo '<h1>Sản Phẩm</h1>';
                    }
                    ?>
                    <h6 class="font_14 mb-0 mt-3"><a href="index.php">Trang Chủ </a> <span
                            class="text-muted mx-1 font_10 align-middle"><i class="fa fa-chevron-right"></i></span> Sản
                        Phẩm</h6>
                </div>
            </div>
        </div>
    </section>

    <section id="shop" class="p_3 bg_light">
        <div class="container-fluid">
            <div class="shop_1 row">
                <div class="col-md-12">
                    <select class="form-select border-0 font_14 text-black" style="width:150px"
                        aria-label="Default select example">
                        <option selected="">Best Selling</option>
                        <option value="1">By Price</option>
                        <option value="2">By Popularity</option>
                        <option value="3">By Trending</option>
                    </select>
                </div>
            </div>

            <?php
   // Truy vấn lấy danh sách sản phẩm từ bảng
   $sql = "SELECT SP_MA, SP_TEN, SP_DONGIA, SP_HINHANH FROM san_pham WHERE SP_SOLUONGTON > 0";
   $params = array();
   $param_types = "";
   
   // Thêm điều kiện tìm kiếm nếu có
   if (isset($_GET['search']) && !empty($_GET['search'])) {
       $search_term = trim($_GET['search']);
       $sql .= " AND SP_TEN LIKE ?";
       $params[] = "%" . $search_term . "%";
       $param_types .= "s";
   }
   
   $sql .= " ORDER BY SP_TEN ASC";
   
   // Chuẩn bị và thực thi truy vấn
   $stmt = $conn->prepare($sql);
   
   // Bind parameters nếu có
   if (!empty($params)) {
       $stmt->bind_param($param_types, ...$params);
   }
   
   $stmt->execute();
   $result = $stmt->get_result();

   if ($result->num_rows > 0) {
       // Bắt đầu một row grid với 5 cột trên desktop
       echo '<div class="row row-cols-1 row-cols-md-3 row-cols-lg-5 g-4 mt-4">';
       while ($row = $result->fetch_assoc()) {
   ?>
            <div class="col">
                <div class="list_h2i">
                    <div class="list_h2i1 position-relative">
                        <div class="list_h2i1i">
                            <div class="grid clearfix">
                                <figure class="effect-jazz mb-0">
                                    <a href="detail.php?id=<?php echo $row['SP_MA']; ?>"><img
                                            src="img/<?php echo $row['SP_HINHANH']; ?>"
                                            alt="<?php echo htmlspecialchars($row['SP_TEN']); ?>"></a>
                                </figure>
                            </div>
                        </div>
                        <div class="list_h2i1i1 position-absolute top-0 p-1">
                            <h6 class="mb-0 font_12 fw-bold d-inline-block bg_yell col_black lh-1 rounded_30 p-1 px-2">
                                Sản phẩm mới</h6>
                        </div>
                    </div>
                    <div class="list_h2i2">
                        <h6 class="fw-bold font_14"><a
                                href="detail.php?id=<?php echo $row['SP_MA']; ?>"><?php echo htmlspecialchars($row['SP_TEN']); ?></a>
                        </h6>
                        <span class="col_yell">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star-half-o"></i>
                        </span>
                        <h6 class="mt-2 font_14"><span
                                class="span_1 col_green fw-bold"><?php echo number_format($row['SP_DONGIA'], 0); ?>
                                VNĐ</span></h6>
                        <div class="button-container">
                            <a class="button" href="detail.php?id=<?php echo $row['SP_MA']; ?>">Xem Sản Phẩm</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
       }
       // Đóng thẻ row
       echo '</div>';
       
       // Display success or error message
       if(isset($_SESSION['cart_success'])) {
           echo '<div class="alert alert-success mt-3">'.$_SESSION['cart_success'].'</div>';
           unset($_SESSION['cart_success']);
       }
       
       if(isset($_SESSION['cart_error'])) {
           echo '<div class="alert alert-danger mt-3">'.$_SESSION['cart_error'].'</div>';
           unset($_SESSION['cart_error']);
       }
   } else {
       if (isset($_GET['search']) && !empty($_GET['search'])) {
           echo '<div class="text-center mt-5 mb-5">';
           echo '<p>Không tìm thấy sản phẩm nào phù hợp với từ khóa <strong>"' . htmlspecialchars($_GET['search']) . '"</strong>.</p>';
           echo '<p><a href="shop.php" class="btn btn-primary">Xem tất cả sản phẩm</a></p>';
           echo '</div>';
       } else {
           echo '<p class="text-center">Không có sản phẩm nào để hiển thị.</p>';
       }
   }

   // Đóng statement và kết nối cơ sở dữ liệu
   $stmt->close();
   $conn->close();
   ?>

            <div class="pages text-center mt-4 row">
                <div class="col-md-12">
                    <ul class="mb-0">
                        <li><a href="#"><i class="fa fa-chevron-left"></i></a></li>
                        <li><a class="act" href="#">1</a></li>
                        <li><a href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">4</a></li>
                        <li><a href="#">5</a></li>
                        <li><a href="#">6</a></li>
                        <li><a href="#"><i class="fa fa-chevron-right"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

</body>

</html>