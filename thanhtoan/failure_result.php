<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FastFood Store - Thanh toán thất bại</title>
    <link rel="shortcut icon" type="image" href="../images/logo.png">
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="../css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="../css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="../css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="../css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="../css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="../css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="../css/style.css" type="text/css">
    <link rel="stylesheet" href="../scss/css/style.css" type="text/css">
    <link rel="stylesheet" href="../plugins/select2/select2.min.css">
</head>
<body>
<?php
require '../connect.php'; // Đường dẫn đến connect.php ở thư mục Eshopper

// Lấy lý do thất bại từ tham số URL
$reason = isset($_GET['reason']) ? htmlspecialchars(urldecode($_GET['reason'])) : "Đã có lỗi xảy ra trong quá trình thanh toán.";
?>

<div class="home-section" style="background: 000000;">
    <nav class="navbar navbar-expand-lg" id="navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="#" id="logo"><img src="../images/logo.png" alt="" width="30px">Fast<span>Food</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span><i class="fa-solid fa-bars"></i></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../index.php" id="first-child">Trang Chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../product.php">Món Ăn</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="../category.php" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Danh mục
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown" style="background-color: #ffc800;">
                            <?php
                            $sql = "select DM_MA as madm, DM_TEN from danh_muc";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                $result_all = $result->fetch_all(MYSQLI_ASSOC);
                                foreach ($result_all as $row) {
                            ?>
                                <li><a class="dropdown-item" href="../category.php?madm=<?php echo $row["madm"] ?>"><?php echo $row["DM_TEN"]; ?></a></li>
                            <?php 
                                }
                            }
                            ?>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../contact.php">Liên Hệ</a>
                    </li>
                </ul>                <form class="d-flex">
                    <div class="icons">
                        <?php
                        if (!isset($_SESSION["user"])) {
                        ?>
                            <a href="../login.php"><i class="fa-regular fa-user"></i></a>
                        <?php
                        } else {
                        ?>
                            <i><label for="">Xin chào: </label><?php echo $_SESSION["user"]; ?></i>
                            <a href='../logout.php'><i class="fa-solid fa-right-from-bracket"></i></a>
                        <?php
                        }
                        ?>
                        <a href="../cart.php"><i class="fa-solid fa-cart-shopping"></i><span style="height: 13px;width: 13px;background: #ffc800;font-size: 10px;color: #ffffff;line-height: 13px;text-align: center;font-weight: 700;display: inline-block;border-radius: 50%;position: absolute;">
                            <?php 
                            $khid = $_SESSION['khid'];
                            $sql = "select count(ct.GH_MA) as soluong 
                                    from chitiet_gh ct
                                    join gio_hang gh on ct.GH_MA=gh.GH_MA
                                    where gh.KH_MA={$khid}";
                            $result = $conn->query($sql);
                            $row = mysqli_fetch_assoc($result);
                            $slsp_gh = $row["soluong"];
                            echo "$slsp_gh";
                            ?>
                        </span></a>
                    </div>
                </form>
            </div>
        </div>
    </nav>
</div>

<section class="checkout spad" style="margin-top: -600px;">
    <div class="container text-center">
        <h1 style="text-align: center; font-weight: bold; font-family: 'Dancing Script', cursive; border-bottom: 2px solid #ffc800; text-shadow: 1px 1px 1px black; margin-bottom:20px;">Thanh toán thất bại</h1>
        <p><?php echo $reason; ?> Vui lòng thử lại hoặc liên hệ với chúng tôi để được hỗ trợ.</p>
        <a class="btn btn-primary my-5" href="../index.php">Quay lại trang chủ</a>
        <a class="btn btn-secondary my-5" href="../cart.php">Quay lại giỏ hàng</a>
    </div>
</section>

<footer id="footer" style="margin-top: 10px;">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 footer-contact">
                    <a class="navbar-brand" href="#" id="logo2"><img src="../images/logo.png" alt="" width="30px">Burger</a>
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Perspiciatis, libero.</p><br>
                    <p>Karachi <br><br> Sindh <br><br> Pakistan <br><br></p>
                    <strong><i class="fa-solid fa-phone"></i> Phone: <strong>+0000000000000000</strong></strong><br>
                    <strong><i class="fa-solid fa-envelope"></i> Email: <strong>example@gmail.com</strong></strong>
                </div>
                <div class="col-lg-3 col-md-6 footer-links">
                    <h4>Useful Links</h4>
                    <ul>
                        <li><a href="#">Home</a></li>
                        <li><a href="#">About</a></li>
                        <li><a href="#">Contact</a></li>
                        <li><a href="#">Services</a></li>
                        <li><a href="#">Privacy policy</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 footer-links">
                    <h4>Our Services</h4>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas, aperiam.</p>
                    <ul>
                        <li><a href="#">Pizza</a></li>
                        <li><a href="#">Fried chicken</a></li>
                        <li><a href="#">Fries</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 footer-links">
                    <h4>Our Social Links</h4>
                    <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Perferendis, minus.</p>
                    <div class="social-links mt-3">
                        <a href="#"><i class="fa-brands fa-twitter"></i></a>
                        <a href="#"><i class="fa-brands fa-facebook"></i></a>
                        <a href="#"><i class="fa-brands fa-google-plus"></i></a>
                        <a href="#"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="container py-1">
        <div class="copyright">
            © Copyright <strong>Burger</strong>. All Rights reserved
        </div>
    </div>
</footer>

<a href="#" class="arrow"><i class="fa-solid fa-arrow-up"></i></a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>
<script src="../js/jquery-3.3.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/jquery.nice-select.min.js"></script>
<script src="../js/jquery-ui.min.js"></script>
<script src="../js/jquery.slicknav.js"></script>
<script src="../js/mixitup.min.js"></script>
<script src="../js/owl.carousel.min.js"></script>
<script src="../js/main.js"></script>
</body>
</html>