<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Plants Shop - Liên Hệ</title>
	<link href="css/bootstrap.min.css" rel="stylesheet" >
	<link href="css/font-awesome.min.css" rel="stylesheet" >
	<link href="css/global.css" rel="stylesheet">
	<link href="css/contact.css" rel="stylesheet">
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
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Pages
          </a>
          <ul class="dropdown-menu drop_1" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="cart.html"> Shopping Cart</a></li>
			<li><a class="dropdown-item border-0" href="checkout.html"> Checkout</a></li>
          </ul>
        </li>
		
		<li class="nav-item">
          <a class="nav-link active" href="contact.html">Contact </a>
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
	    <h1>Contact Us</h1>
		<h6 class="font_14 mb-0 mt-3"><a href="#">Home </a> <span class="text-muted mx-1 font_10 align-middle"><i class="fa fa-chevron-right"></i></span>  Contact Us</h6>
	  </div>
   </div>
 </div>
</section>

<section id="contact" class="bg_light p_3">
 <div class="container-fluid">
   <div class="row contact_1">
    <div class="col-md-12">
	  <p>Lorem Ipsum Is Simply Dummy Text Of The Printing And Typesetting Industry. Lorem Ipsum Has Been The Industry's Standard Dummy Text Ever Since The 1500s, When An Unknown Printer Took A Galley Of Type And Scrambled It To Make A Type Specimen Book. It Has Survived Not Only Five Centuries, But Also The Leap Into Electronic Typesetting, Remaining Essentially Unchanged. It Was Popularised In The 1960s With The Release Of Letraset Sheets Containing.</p>
	  <p>Lorem Ipsum Is Simply Dummy Text Of The Printing And Typesetting Industry. Lorem Ipsum Has Been The Industry's Standard Dummy Text Ever Since The 1500s, When An Unknown Printer Took A Galley Of Type And Scrambled It To Make A Type Specimen Book.</p>
	  <p>It Has Survived Not Only Five Centuries, But Also The Leap Into Electronic Typesetting, Remaining Essentially Unchanged. It Was Popularised In The 1960s With The Release Of Letraset Sheets Containing.</p>
	</div>
   </div>
   <div class="contact_2 row">
    <div class="col-md-8">
      <div class="contact_2l">
	    <div class="contact_2li">
		<div class="row quote_2">
       <div class="col-md-6">
	    <div class="quote_2l">
		 <input placeholder="Name" class="form-control border-0" type="text">
		</div>
	   </div>
	   <div class="col-md-6">
	    <div class="quote_2l">
		 <input placeholder="Email Address" class="form-control border-0" type="text">
		</div>
	   </div>
     </div>
	    <div class="row quote_2 mt-4">
       <div class="col-md-6">
	    <div class="quote_2l">
		 <input placeholder="Phone Number" class="form-control border-0" type="text">
		</div>
	   </div>
	   <div class="col-md-6">
	    <div class="quote_2l">
		 <input placeholder="Website" class="form-control border-0" type="text">
		</div>
	   </div>
     </div>
	    <div class="row quote_2 mt-4">
	   <div class="col-md-12">
	    <div class="quote_2l">
		 <textarea placeholder="Write a Message" class="form-control border-0 form_text"></textarea>
		</div>
	   </div>
     </div>
	    <div class="row quote_2 mt-4">
	   <div class="col-md-12">
	    <div class="quote_2l">
		  <h6 class="d-inline-block mt-2 mb-0"><a class="button" href="#"> Post Comment <i class="fa fa-paper-plane ms-1"></i> </a></h6>
		</div>
	   </div>
     </div>
	   </div>
	  </div>
	</div>
	<div class="col-md-4">
      <div class="contact_2r row">
	    <div class="col-md-2">
		 <div class="contact_2rl">
		  <span class="col_green font_60 lh-1"><i class="fa fa-map-marker"></i></span>
		 </div>
		</div>
		<div class="col-md-10">
		 <div class="contact_2rr">
		  <h4>Location</h4>
		  <hr class="line">
		  <p>Lorem ipsum dolor sit amet, consectetur isicing elit. Recusandae repudiandae dolores...</p>
		  <hr>
		 </div>
		</div>
	  </div>
	  <div class="contact_2r row">
	    <div class="col-md-2">
		 <div class="contact_2rl">
		  <span class="col_green font_60 lh-1"><i class="fa fa-phone"></i></span>
		 </div>
		</div>
		<div class="col-md-10">
		 <div class="contact_2rr">
		  <h4>Phone | Email</h4>
		  <hr class="line">
		  <p><span class="fw-bold">IND - </span> +123-456 7899</p>
		  <p><span class="fw-bold">USA -</span>  +123-456 7899</p>
		  <p><span class="fw-bold">E-Mail -</span> <a href="#">info@gmail.com</a></p>
		  <hr>
		 </div>
		</div>
	  </div>
	  <div class="contact_2r row">
	    <div class="col-md-2">
		 <div class="contact_2rl">
		  <span class="col_green font_60 lh-1"><i class="fa fa-clock-o"></i></span>
		 </div>
		</div>
		<div class="col-md-10">
		 <div class="contact_2rr ">
		  <h4>Working Hours</h4>
		  <hr class="line">
		  <p class="mb-0"><span class="fw-bold">Mon - Sat -</span>  10am - 5pm</p>
		 </div>
		</div>
	  </div>
	</div>
   </div>
   <div class="row contact_3 mt-4">
    <div class="col-md-12">
	  <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d114964.53925916665!2d-80.29949920266738!3d25.782390733064336!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88d9b0a20ec8c111%3A0xff96f271ddad4f65!2sMiami%2C+FL%2C+USA!5e0!3m2!1sen!2sin!4v1530774403788" height="450" style="border:0; width:100%;" allowfullscreen=""></iframe>
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