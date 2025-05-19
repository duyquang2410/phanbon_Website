<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Plants Shop - Thanh Toán</title>
	<link href="css/bootstrap.min.css" rel="stylesheet" >
	<link href="css/font-awesome.min.css" rel="stylesheet" >
	<link href="css/global.css" rel="stylesheet">
	<link href="css/checkout.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
	<script src="js/bootstrap.bundle.min.js"></script>
</head>
<body>
<?php
// Start the session
session_start();
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
	    <h1>Checkout</h1>
		<h6 class="font_14 mb-0 mt-3"><a href="#">Home </a> <span class="text-muted mx-1 font_10 align-middle"><i class="fa fa-chevron-right"></i></span>  Checkout</h6>
	  </div>
   </div>
 </div>
</section>

<section id="checkout" class="p_3 bg_light">
 <div class="container-fluid">
  <div class="checkout_1 row">
   <div class="col-md-8">
    <div class="checkout_1l shadow_box bg-white p-4">
	  <div class="checkout_1l1">
	   <h4 class="mb-4">Billing Details</h4>
	  </div>
	  <div class="checkout_1l2">
	    <div class="checkout_1l2i row">
		 <div class="col-md-6">
		  <div class="checkout_1l2il">
		   <input class="form-control font_14 border-0 bg_light" placeholder="First Name" type="text">
		  </div>
		 </div>
		 <div class="col-md-6">
		  <div class="checkout_1l2il">
		   <input class="form-control font_14 border-0 bg_light" placeholder="Last Name" type="text">
		  </div>
		 </div>
		</div>
		<div class="checkout_1l2i row mt-4">
		 <div class="col-md-6">
		  <div class="checkout_1l2il">
		   <input class="form-control font_14 border-0 bg_light" placeholder="Company Name" type="text">
		  </div>
		 </div>
		 <div class="col-md-6">
		  <div class="checkout_1l2il">
		   <input class="form-control font_14 border-0 bg_light" placeholder="Country" type="text">
		  </div>
		 </div>
		</div>
		<div class="checkout_1l2i row mt-4">
		 <div class="col-md-6">
		  <div class="checkout_1l2il">
		   <input class="form-control font_14 border-0 bg_light" placeholder="City" type="text">
		  </div>
		 </div>
		 <div class="col-md-6">
		  <div class="checkout_1l2il">
		   <input class="form-control font_14 border-0 bg_light" placeholder="State / Province" type="text">
		  </div>
		 </div>
		</div>
		<div class="checkout_1l2i row mt-4">
		 <div class="col-md-6">
		  <div class="checkout_1l2il">
		   <input class="form-control font_14 border-0 bg_light" placeholder="Street" type="text">
		  </div>
		 </div>
		 <div class="col-md-6">
		  <div class="checkout_1l2il">
		   <input class="form-control font_14 border-0 bg_light" placeholder="Postcode" type="text">
		  </div>
		 </div>
		</div>
		<div class="checkout_1l2i row mt-4">
		 <div class="col-md-6">
		  <div class="checkout_1l2il">
		   <input class="form-control font_14 border-0 bg_light" placeholder="Phone" type="text">
		  </div>
		 </div>
		 <div class="col-md-6">
		  <div class="checkout_1l2il">
		   <input class="form-control font_14 border-0 bg_light" placeholder="Email" type="text">
		  </div>
		 </div>
		</div>
	  </div>
	  <div class="checkout_1l1 mt-4">
	   <h4 class="mb-4">Additional Information</h4>
	  </div>
	  <div class="checkout_1l2">
		<div class="checkout_1l2i row">
		 <div class="col-md-12">
		  <div class="checkout_1l2il">
		   <textarea placeholder="Other Notes" class="form-control font_14 form_text border-0 bg_light"></textarea>
		  </div>
		 </div>
		</div>
	  </div>
	  <div class="checkout_1l1 mt-4">
	   <h4 class="mb-4">Payment Method</h4>
	   
<div class="form-check">
  <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked="">
  <label class="form-check-label" for="flexRadioDefault2">
   Direct bank transfer
  </label>
</div>
<div class="form-check mt-3">
  <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
  <label class="form-check-label" for="flexRadioDefault1">
    Check payments
  </label>
</div>
<div class="form-check mt-3">
  <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault3">
  <label class="form-check-label" for="flexRadioDefault3">
    Cash on delivery
  </label>
</div>
<h6 class="mb-0 mt-4"><a class="button" href="#"><i class="fa fa-long-arrow-right me-1"></i> Place Order</a></h6>
	  </div>
	</div>
   </div>
   <div class="col-md-4">
    <div class="checkout_1r">
	  <ul class="drop_cart">
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
							 <li class="d-inline-block mx-1"><a class="button px-4 pt-2 pb-2 font_14" href="#">View Order</a></li>
							 <li class="d-inline-block mx-1"><a class="button_1 px-4 pt-2 pb-2 font_14" href="#">Checkout</a></li>
							</ul>
						  </div>
						 </div>
						</li>
					  </ul>
	</div>
   </div>
  </div>  
 </div>
</section>

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

</body>
</html>