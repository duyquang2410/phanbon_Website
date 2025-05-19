<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Plants Shop - Giỏ Hàng</title>
	<link href="css/bootstrap.min.css" rel="stylesheet" >
	<link href="css/font-awesome.min.css" rel="stylesheet" >
	<link href="css/global.css" rel="stylesheet">
	<link href="css/cart.css" rel="stylesheet">
	<link href="css/dark-mode.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
	<script src="js/bootstrap.bundle.min.js"></script>
</head>
<body>
<?php
// Start the session
session_start();

// Include file kết nối và các hàm giỏ hàng
include 'connect.php';
include 'cart_functions.php';

// Chỉ cho phép người dùng đã đăng nhập xem giỏ hàng
if (!isset($_SESSION['user_id'])) {
    $_SESSION['login_required_message'] = "Vui lòng đăng nhập để xem giỏ hàng.";
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$cart_id = getCurrentCart($conn, $user_id);
$cart_data = getCartItems($conn, $cart_id);
$cart_items = $cart_data['items'];
$cart_total = $cart_data['total'];
$cart_count = $cart_data['count'];

// Xử lý áp dụng mã giảm giá
$discount = 0;
$promo_code = '';
$promo_error = '';
$promo_success = '';

if (isset($_POST['apply_promo']) && isset($_POST['promo_code'])) {
    $promo_code = trim($_POST['promo_code']);
    $promo_result = applyPromoCode($conn, $promo_code);
    
    if ($promo_result['valid']) {
        $discount = $cart_total * ($promo_result['value'] / 100);
        $_SESSION['promo_code'] = $promo_code;
        $_SESSION['promo_id'] = $promo_result['id'];
        $_SESSION['promo_value'] = $promo_result['value'];
        $promo_success = "Đã áp dụng mã giảm giá " . $promo_result['value'] . "%.";
    } else {
        $promo_error = "Mã giảm giá không hợp lệ hoặc đã hết hạn.";
    }
} elseif (isset($_SESSION['promo_code'])) {
    $promo_code = $_SESSION['promo_code'];
    $discount = $cart_total * ($_SESSION['promo_value'] / 100);
}

$final_total = $cart_total - $discount;
?>

<!-- Header -->
<?php include 'header.php'; ?>

<section id="top" class="bg_black">
 <div class="container-fluid">
  <div class="row top_1">
 <div class="col-md-4">
   <div class="top_1l pt-1">
      <ul class="mb-0">
	   <li class="d-inline-block"><a class="text-white" href="#"><i class="fa fa-facebook"></i></a></li>
	   <li class="d-inline-block ms-2"><a class="text-white" href="#"><i class="fa fa-instagram"></i></a></li>
	   <li class="d-inline-block ms-2"><a class="text-white" href="#"><i class="fa fa-twitter"></i></a></li>
	    <li class="d-inline-block ms-2"><a class="text-white" href="#"><i class="fa fa-linkedin-square"></i></a></li>
	   <li class="d-inline-block ms-2"><a class="text-white" href="#"><i class="fa fa-youtube-play"></i></a></li>
	    <li class="d-inline-block ms-2"><a class="text-white" href="#"><i class="fa fa-whatsapp"></i></a></li>
	  </ul>
   </div>
 </div>
 <div class="col-md-4">
   <div class="top_1m text-center pt-1">
      <p class="mb-0 text-light font_14">Free shipping on all orders above $490</p>
   </div>
 </div>
 <div class="col-md-4">
   <div class="top_1r float-end">
      <ul class="mb-0">
		<li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle font_14 text-white p-0" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Language
          </a>
          <ul class="dropdown-menu drop_1 rounded-3" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="#"> French</a></li>
            <li><a class="dropdown-item border-0" href="#"> Spanish</a></li>
          </ul>
        </li>

      </ul>
   </div>
 </div>
</div>
 </div>
</section>

 <section id="header">
<nav class="navbar navbar-expand-md navbar-light pt-2 pb-2 bg_lighto" id="navbar_sticky">
  <div class="container-fluid">
    <a class="text-black p-0 navbar-brand fw-bold" href="index.html">Plants <i class="fa fa-leaf col_green me-1 align-middle"></i> <span style="color:#e3ae03">Shop</span></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
	   <ul class="navbar-nav mb-0 ms-auto">
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="index.html">Home</a>
        </li>
		
		<li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Product
          </a>
          <ul class="dropdown-menu drop_1" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="shop.html"> Product</a></li>
            <li><a class="dropdown-item border-0" href="detail.html"> Product Detail</a></li>
          </ul>
        </li>
		
		<li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Blog
          </a>
          <ul class="dropdown-menu drop_1" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="blog.html"> Blog</a></li>
            <li><a class="dropdown-item border-0" href="blog_detail.html"> Blog Detail</a></li>
          </ul>
        </li>
		
		<li class="nav-item">
          <a class="nav-link" href="faq.html">Faq </a>
        </li>
		
		<li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Pages
          </a>
          <ul class="dropdown-menu drop_1" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="cart.html"> Shopping Cart</a></li>
			<li><a class="dropdown-item border-0" href="checkout.html"> Checkout</a></li>
          </ul>
        </li>
		
		<li class="nav-item">
          <a class="nav-link" href="contact.html">Contact </a>
        </li>
      </ul>
	   <ul class="navbar-nav mb-0 ms-auto nav_1">
	   <li class="nav-item me-3">
          <div class="input-group border_1 bg-white mt-1">
				<input type="text" class="form-control border-0 bg-transparent font_14" placeholder="Search Products">
				<span class="input-group-btn">
					<button class="btn btn-primary text-black fs-5 bg-transparent rounded-0 p-1 px-3 border-0" type="button">
						<i class="fa fa-search"></i> </button>
				</span>
		</div>
        </li>
	   <li class="nav-item">
          <a class="nav-link fs-5 lh-1" href="#"><i class="fa fa-user"></i> </a>
        </li>
     <li class="nav-item">
          <a class="nav-link fs-5 lh-1" href="#"><i class="fa fa-star-o"></i> </a>
        </li>
		<li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle drop_togn nav_hide fs-5 lh-1" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-shopping-cart"></i>
          </a>
          <ul class="dropdown-menu drop_cart rounded-0 border-0" aria-labelledby="navbarDropdown" style="">
						<li>
						 <div class="drop_1i row">
						  <div class="col-md-6 col-6">
						   <div class="drop_1il"><h5 class="font_14">2 ITEMS</h5></div>
						  </div>
						  <div class="col-md-6 col-6">
						   <div class="drop_1il text-end"><h5 class="font_14"><a href="cart.html">VIEW CART</a></h5></div>
						  </div>
						 </div>
						 <div class="drop_1i1 row">
						  <div class="col-md-6 col-6">
						   <div class="drop_1i1l"><h6 class="font_14"><a href="#">Nulla Quis</a> <br> <span class="fw-normal d-inline-block mt-1 font_12">1x - $89.00</span></h6></div>
						  </div>
						  <div class="col-md-4 col-4">
						   <div class="drop_1i1r"><a href="#"><img src="img/1.jpg" class="w-100" alt="abc"></a></div>
						  </div>
						  <div class="col-md-2 col-2">
						   <div class="drop_1i1l text-end"><h6> <span><i class="fa fa-trash"></i></span></h6></div>
						  </div>
						 </div>
						 <div class="drop_1i1 row">
						  <div class="col-md-6 col-6">
						   <div class="drop_1i1l"><h6 class="font_14"><a href="#">Eget Nulla</a> <br> <span class="fw-normal d-inline-block mt-1 font_12">1x - $49.00</span></h6></div>
						  </div>
						  <div class="col-md-4 col-4">
						   <div class="drop_1i1r"><a href="#"><img src="img/2.jpg" class="w-100" alt="abc"></a></div>
						  </div>
						  <div class="col-md-2 col-2">
						   <div class="drop_1i1l text-end"><h6> <span><i class="fa fa-trash"></i></span></h6></div>
						  </div>
						 </div>
						 <div class="drop_1i2 row">
						  <div class="col-md-6 col-6">
						   <div class="drop_1il"><h5 class="font_14">TOTAL</h5></div>
						  </div>
						  <div class="col-md-6 col-6">
						   <div class="drop_1il text-end"><h5 class="font_14">$142.00</h5></div>
						  </div>
						 </div>
						 <div class="drop_1i3 text-center row">
						  <div class="col-md-12 col-12">
						    <ul class="mb-0">
							 <li class="d-inline-block mx-1"><a class="button px-3 pt-2 pb-2 font_14" href="#">View Order</a></li>
							 <li class="d-inline-block mx-1"><a class="button_1 px-3 pb-2 pt-2 font_14" href="#">Checkout</a></li>
							</ul>
						  </div>
						 </div>
						</li>
					  </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
</section>

<section id="center" class="center_o pt-5 pb-5">
 <div class="container-fluid">
   <div class="center_o1 row text-center">
      <div class="col-md-12">
	    <h1>Giỏ Hàng</h1>
		<h6 class="font_14 mb-0 mt-3"><a href="index.php">Trang Chủ </a> <span class="text-muted mx-1 font_10 align-middle"><i class="fa fa-chevron-right"></i></span> Giỏ
            Hàng</h6>
	  </div>
   </div>
 </div>
</section>

<section id="cart" class="cart_page pt-4 pb-5 bg_light">
 <div class="container">
  <div class="row">
   <div class="col-md-8">
    <div class="cart_l bg-white p-4">
     <h2 class="mb-4">Giỏ Hàng Của Bạn</h2>
     <?php if (count($cart_items) > 0): ?>
     <div class="table-responsive cart_1">
      <table class="table table-bordered">
       <thead>
        <tr>
         <th>Sản Phẩm</th>
         <th>Giá</th>
         <th>Số Lượng</th>
         <th>Tổng Tiền</th>
         <th>Xóa</th>
        </tr>
       </thead>
       <tbody>
        <?php foreach ($cart_items as $item): ?>
        <tr class="cart-item">
         <td>
          <div class="d-flex cart_1i1">
           <div class="cart_1i1l">
            <img src="<?php echo $item['product_image']; ?>" alt="<?php echo $item['product_name']; ?>" class="img-fluid">
           </div>
           <div class="cart_1i1r">
            <h5><a href="product_detail.php?id=<?php echo $item['product_id']; ?>"><?php echo $item['product_name']; ?></a></h5>
            <h6><?php echo substr($item['product_description'], 0, 50) . '...'; ?></h6>
           </div>
          </div>
         </td>
         <td class="price"><?php echo number_format($item['price'], 0, ',', '.'); ?>đ</td>
         <td>
          <div class="quantity d-flex align-items-center justify-content-center">
           <form action="update_cart.php" method="post" class="d-flex">
            <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
            <input type="hidden" name="action" value="decrease">
            <button type="submit" class="btn">-</button>
            <input type="text" class="form-control mx-2" value="<?php echo $item['quantity']; ?>" readonly>
            <input type="hidden" name="action" value="increase">
            <button type="submit" class="btn" formaction="update_cart.php?item_id=<?php echo $item['id']; ?>&action=increase">+</button>
           </form>
          </div>
         </td>
         <td class="subtotal"><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?>đ</td>
         <td class="text-center btn_cross">
          <a href="remove_item.php?item_id=<?php echo $item['id']; ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
           <i class="fa fa-times"></i>
          </a>
         </td>
        </tr>
        <?php endforeach; ?>
       </tbody>
      </table>
     </div>
     <div class="cart_2 row mt-4">
      <div class="col-md-6 cart_2ril">
       <a href="index.php" class="button_1"><i class="fa fa-arrow-left me-2"></i>Tiếp Tục Mua Sắm</a>
      </div>
      <div class="col-md-6 text-end cart_2rir">
       <a href="update_cart.php?action=clear" class="button_1" onclick="return confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?');">Xóa Giỏ Hàng</a>
      </div>
     </div>
     <?php else: ?>
     <div class="text-center py-5">
      <h4>Giỏ hàng của bạn đang trống</h4>
      <p class="mt-3">Hãy thêm sản phẩm vào giỏ hàng để tiếp tục.</p>
      <a href="index.php" class="button mt-3">Tiếp Tục Mua Sắm</a>
     </div>
     <?php endif; ?>
    </div>
   </div>
   
   <div class="col-md-4">
    <div class="cart-summary p-4">
     <h3 class="mb-4">Tổng Giỏ Hàng</h3>
     <div class="cart_r1i row">
      <div class="col-6">
       <p><strong>Tổng đơn hàng</strong></p>
      </div>
      <div class="col-6 text-end">
       <p><?php echo number_format($cart_total, 0, ',', '.'); ?>đ</p>
      </div>
     </div>
     
     <?php if ($discount > 0): ?>
     <div class="cart_r1i row">
      <div class="col-6">
       <p><strong>Giảm giá</strong></p>
      </div>
      <div class="col-6 text-end">
       <p>-<?php echo number_format($discount, 0, ',', '.'); ?>đ</p>
      </div>
     </div>
     <?php endif; ?>
     
     <hr>
     
     <div class="cart_r1i row">
      <div class="col-6">
       <p><strong>Tổng cộng</strong></p>
      </div>
      <div class="col-6 text-end">
       <p class="fw-bold"><?php echo number_format($final_total, 0, ',', '.'); ?>đ</p>
      </div>
     </div>
     
     <?php if ($promo_error): ?>
     <div class="alert alert-danger mt-3"><?php echo $promo_error; ?></div>
     <?php endif; ?>
     
     <?php if ($promo_success): ?>
     <div class="alert alert-success mt-3"><?php echo $promo_success; ?></div>
     <?php endif; ?>
     
     <form action="" method="post" class="mt-3">
      <div class="input-group">
       <input type="text" name="promo_code" class="form-control" placeholder="Mã giảm giá" value="<?php echo $promo_code; ?>">
       <button type="submit" name="apply_promo" class="btn btn-light">Áp Dụng</button>
      </div>
     </form>
     
     <a href="checkout.php" class="button d-block text-center mt-4">Tiến Hành Thanh Toán</a>
    </div>
   </div>
  </div>
 </div>
</section>

<!-- CSS cho trang giỏ hàng -->
<style>
 .cart_1i1l {
  width: 80px;
  height: 80px;
  margin-right: 15px;
 }
 
 .cart_1i1l img {
  width: 100%;
  height: 100%;
  object-fit: contain;
 }
 
 .cart_1i1r {
  flex: 1;
 }
 
 .cart_1i1r h5 {
  font-size: 16px;
  margin-bottom: 5px;
 }
 
 .cart_1i1r h6 {
  font-size: 13px;
  color: #777;
 }
 
 .cart_r1 {
  border-radius: 5px;
  box-shadow: 0 0 10px rgba(0,0,0,0.1);
 }
</style>

<!-- Footer -->
<?php include 'footer.php'; ?>

<script>
window.onscroll = function() {myFunction()};
var navbar_sticky = document.getElementById("navbar_sticky");
var sticky = navbar_sticky.offsetTop;
var navbar_height = document.querySelector('.navbar').offsetHeight;
function myFunction() {
  if (window.pageYOffset >= sticky + navbar_height) {
    navbar_sticky.classList.add("sticky")
	document.body.style.paddingTop = navbar_height + 'px';
  } else {
    navbar_sticky.classList.remove("sticky");
	document.body.style.paddingTop = '0'
  }
}
</script>

<script src="js/dark-mode.js"></script>
</body>
</html>