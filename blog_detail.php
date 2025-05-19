<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Plants Shop - Chi Tiết Bài Viết</title>
	<link href="css/bootstrap.min.css" rel="stylesheet" >
	<link href="css/font-awesome.min.css" rel="stylesheet" >
	<link href="css/global.css" rel="stylesheet">
	<link href="css/blog_detail.css" rel="stylesheet">
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
          <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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

<section id="center" class="center_blog_dt p_3">
 <div class="container-fluid">
   <div class="blog_pg1 row">
     <div class="col-md-8">
	  <div class="blog_dt">
	    <div class="blog_dt1">
		  <h6 class="font_14"><a href="#">Home </a> <span class="text-muted mx-1 font_10 align-middle"><i class="fa fa-chevron-right"></i></span>  Vertical Gardening: A Simple Guide to Growing Plant Upwards</h6>
		  <h2 class="mt-3">Vertical Gardening: A Simple Guide to Growing Plant Upwards</h2>
		  <p class="fs-6 mt-3">In today's bustling Indian cities, where space is as precious as gold, gardening has taken a creative turn. Welcome to the world of vertical gardening – a green solution for those who dream big but have small spaces. This method isn't just about growing plants; it's about embracing nature in our urban lives. It's perfect for city dwellers, from Mumbai's high rises to Delhi's compact apartments.</p>
		  <h3 class="mb-3">What is Vertical Gardening?</h3>
		  <div class="grid clearfix">
				  <figure class="effect-jazz mb-0">
					<a href="blog_detail.html"><img src="img/65.jpg" class="w-100" alt="abc"></a>
				  </figure>
			  </div>
		 <p class="fs-6 mt-3">Vertical gardening is a method where plants are grown upwards using trellises, stakes, walls, or other vertical supports, rather than spreading out horizontally on the ground. This technique is especially beneficial in urban areas or spaces where horizontal gardening space is limited. Vertical gardens can include a variety of plants, such as flowers, vegetables, and herbs, and can be implemented indoors or outdoors.</p>
		 <p class="fs-6">This approach not only saves space but also adds an aesthetic element to buildings and homes. Additionally, it can improve air quality and reduce heat in urban environments. For your business, OrganicBazar, incorporating vertical gardening products and tips could be an attractive offering to customers who have limited space but are enthusiastic about gardening</p>
		 <h3 class="mb-3">How to Start Vertical Gardening?</h3>
		 <p class="fs-6">Have you heard about 'Vertical Gardening'? This innovative approach is perfect for small spaces like balconies and terraces, common in our bustling Indian cities.</p>
		 <div class="grid clearfix">
				  <figure class="effect-jazz mb-0">
					<a href="blog_detail.html"><img src="img/66.jpg" class="w-100" alt="abc"></a>
				  </figure>
			  </div>
		<h5 class="mt-3">Read More: <a href="#">How to Start a Terrace Garden in India – A Beginner Guide</a></h5>
		<div class="list_h2 row mt-3">
    <div class="col-md-3 col-sm-6">
     <div class="list_h2i">
	    <div class="list_h2i1 position-relative">
	        <div class="list_h2i1i">
	          <div class="grid clearfix">
				  <figure class="effect-jazz mb-0">
					<a href="blog_detail.html"><img src="img/13.jpg" class="w-100" alt="abc"></a>
				  </figure>
			  </div>
	       </div>
		    <div class="list_h2i1i1 position-absolute top-0 p-1">
	         <h6 class="mb-0 font_12 fw-bold d-inline-block bg_yell col_black lh-1 rounded_30 p-1 px-2">ON SALE</h6>
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
		 <h6 class="mt-2 font_14"><span class="span_1 col_green fw-bold">$ 189.00</span> <span class="span_2 ms-2 text-decoration-line-through">$ 430.00</span></h6>
		 <h6 class="mb-0 mt-4 text-center"><a class="button" href="#">Add to Cart</a></h6>
	   </div>
	 </div>
	</div>
	<div class="col-md-3 col-sm-6">
     <div class="list_h2i">
	    <div class="list_h2i1 position-relative">
	        <div class="list_h2i1i">
	          <div class="grid clearfix">
				  <figure class="effect-jazz mb-0">
					<a href="blog_detail.html"><img src="img/14.jpg" class="w-100" alt="abc"></a>
				  </figure>
			  </div>
	       </div>
		    <div class="list_h2i1i1 position-absolute top-0 p-1">
	         <h6 class="mb-0 font_12 fw-bold d-inline-block bg_yell col_black lh-1 rounded_30 p-1 px-2">ON SALE</h6>
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
		 <h6 class="mt-2 font_14"><span class="span_1 col_green fw-bold">$ 489.00</span> <span class="span_2 ms-2 text-decoration-line-through">$ 990.00</span></h6>
		 <h6 class="mb-0 mt-4 text-center"><a class="button" href="#">Add to Cart</a></h6>
	   </div>
	 </div>
	</div>
	<div class="col-md-3 col-sm-6">
     <div class="list_h2i">
	    <div class="list_h2i1 position-relative">
	        <div class="list_h2i1i">
	          <div class="grid clearfix">
				  <figure class="effect-jazz mb-0">
					<a href="blog_detail.html"><img src="img/15.jpg" class="w-100" alt="abc"></a>
				  </figure>
			  </div>
	       </div>
		    <div class="list_h2i1i1 position-absolute top-0 p-1">
	         <h6 class="mb-0 font_12 fw-bold d-inline-block bg_yell col_black lh-1 rounded_30 p-1 px-2">ON SALE</h6>
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
		 <h6 class="mt-2 font_14"><span class="span_1 col_green fw-bold">$ 389.00</span> <span class="span_2 ms-2 text-decoration-line-through">$ 680.00</span></h6>
		 <h6 class="mb-0 mt-4 text-center"><a class="button" href="#">Add to Cart</a></h6>
	   </div>
	 </div>
	</div>
	<div class="col-md-3 col-sm-6">
     <div class="list_h2i">
	    <div class="list_h2i1 position-relative">
	        <div class="list_h2i1i">
	          <div class="grid clearfix">
				  <figure class="effect-jazz mb-0">
					<a href="blog_detail.html"><img src="img/16.jpg" class="w-100" alt="abc"></a>
				  </figure>
			  </div>
	       </div>
		    <div class="list_h2i1i1 position-absolute top-0 p-1">
	         <h6 class="mb-0 font_12 fw-bold d-inline-block bg_yell col_black lh-1 rounded_30 p-1 px-2">ON SALE</h6>
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
		 <h6 class="mt-2 font_14"><span class="span_1 col_green fw-bold">$ 289.00</span> <span class="span_2 ms-2 text-decoration-line-through">$ 699.00</span></h6>
		 <h6 class="mb-0 mt-4 text-center"><a class="button" href="#">Add to Cart</a></h6>
	   </div>
	 </div>
	</div>
   </div>
       <h3 class="mt-3 mb-3">Conclusion</h3>
	   <p class="fs-6">Vertical gardening in India isn't just a trend; it's a lifestyle choice for the eco-conscious Indian. It lets you enjoy fresh, home-grown produce and brings a slice of nature into your urban home. It's simple, sustainable, and stylish. So, why not start your vertical garden today and join the green revolution?</p>
	   <div class="blog_1dt1i1 mt-4 row">
		   <div class="col-md-6">
		     <div class="blog_1dt1i1l">
			  <h5 class="mb-3">Tags:</h5>
			  <ul class="mb-0 font_14 tags">
		   <li class="d-inline-block"><a class="bg_lighto d-block p-2 rounded-3" href="#">Blog <span class="bg-white rounded-3 p-1 ms-1 d-inline-block">1,540</span></a></li>
		   <li class="d-inline-block"><a class="bg_lighto d-block p-2 rounded-3" href="#">Fertilizers &amp; Soil <span class="bg-white rounded-3 p-1 ms-1 d-inline-block">138</span></a></li>
		
		  
		 </ul>
			 </div>
		   </div>
		   <div class="col-md-6">
		     <div class="blog_1dt1i1r text-end">
			   <h5 class="mb-3">Social:</h5>
			   <ul class="social_tag mb-0">
	 <li class="d-inline-block"><a href="#"><i class="fa fa-facebook"></i></a></li>
	 <li class="d-inline-block"><a href="#"><i class="fa fa-twitter"></i></a></li>
	 <li class="d-inline-block"><a href="#"><i class="fa fa-youtube-play"></i></a></li>
	 <li class="d-inline-block"><a href="#"><i class="fa fa-instagram"></i></a></li>
	 <li class="d-inline-block"><a href="#"><i class="fa fa-linkedin"></i></a></li>
	</ul>
			 </div>
		   </div>
		 </div>
		</div>
		<div class="blog_dt2 mt-4">
          <h3>3 Comments</h3>
	 <hr class="line mb-4">
	 <div class="blog_1dt2i row">
	     <div class="col-md-2 col-sm-2">
		  <div class="blog_1dt2il">
		   <img src="img/67.jpg" class="w-100 rounded-circle" alt="abc">
		  </div>
		 </div>
		 <div class="col-md-10 col-sm-10">
		  <div class="blog_1dt2ir">
		    <h5><a href="#">Admin</a></h5>
			<h6 class="text-muted font_14">December 7, 2020 at 9:30 am</h6>
			<p>Aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est</p>
			<h6 class="mb-0 mt-3"><a class="button p-2 px-4" href="#">Reply</a></h6>
		  </div>
		 </div>
	   </div><hr>
	   <div class="blog_1dt2i row">
	     <div class="col-md-2 col-sm-2">
		  <div class="blog_1dt2il">
		   <img src="img/68.jpg" class="w-100 rounded-circle" alt="abc">
		  </div>
		 </div>
		 <div class="col-md-10 col-sm-10">
		  <div class="blog_1dt2ir">
		    <h5><a href="#">Admin</a></h5>
			<h6 class="text-muted font_14">December 9, 2020 at 9:30 am</h6>
			<p>Aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est</p>
			<h6 class="mb-0 mt-3"><a class="button p-2 px-4" href="#">Reply</a></h6>
		  </div>
		 </div>
	   </div><hr>
	   <div class="blog_1dt2i row">
	     <div class="col-md-2 col-sm-2">
		  <div class="blog_1dt2il">
		   <img src="img/69.jpg" class="w-100 rounded-circle" alt="abc">
		  </div>
		 </div>
		 <div class="col-md-10 col-sm-10">
		  <div class="blog_1dt2ir">
		    <h5><a href="#">Admin</a></h5>
			<h6 class="text-muted font_14">December 12, 2020 at 9:30 am</h6>
			<p>Aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est</p>
			<h6 class="mb-0 mt-3"><a class="button p-2 px-4" href="#">Reply</a></h6>
		  </div>
		 </div>
	   </div>
        </div>
		<div class="blog_dt3 mt-4 bg_lighto p-4">
          <h3>Leave a Reply</h3>
	 <p class="mt-3 fs-6 mb-4">Your email address will not be published. Required fields are marked <span class="text-danger">*</span></p>
	 <h6 class="mb-3">Comment <span class="text-danger">*</span></h6>
	 <textarea  class="form-control rounded-3 border-0 form_text"></textarea>
	 <div class="blog_dt3i row mt-4">
	  <div class="col-md-4">
	    <div class="blog_dt3il">
		 <h6 class="mb-3">Your Name  <span class="text-danger">*</span></h6>
		  <input class="form-control rounded-3 border-0" type="text">
		</div>
	  </div>
	  <div class="col-md-4">
	    <div class="blog_dt3il">
		 <h6 class="mb-3">Email Address  <span class="text-danger">*</span></h6>
		  <input class="form-control rounded-3 border-0" type="text">
		</div>
	  </div>
	  <div class="col-md-4">
	    <div class="blog_dt3il">
		 <h6 class="mb-3">Website</h6>
		  <input class="form-control rounded-3 border-0" type="text">
		</div>
	  </div>
	 </div>
	 <div class="form-check mt-3">
        <input type="checkbox" class="form-check-input" id="customCheck1">
        <label class="form-check-label" for="customCheck1">Save my name, email, and website in this browser for the next time I comment.</label>
    </div>
	<h6 class="mb-0 mt-4"><a class="button" href="#">SUBMIT COMMENT</a></h6>
        </div>
	  </div>
	 </div>
	 <div class="col-md-4">
	  <div class="blog_pg1r">
	    <div class="blog_pg1r1 bg_lighto p-5 px-5 rounded-3">
		  <p class="fs-6">Welcome to your ultimate hub for all things organic gardening! Explore our carefully crafted content that's both informative and fun, designed to captivate garden enthusiasts worldwide.</p>
		  <ul class="mb-0 fs-4">
		   <li class="d-inline-block"><a href="#"><i class="fa fa-facebook"></i></a></li>
		   <li class="d-inline-block ms-2"><a href="#"><i class="fa fa-instagram"></i></a></li>
		   <li class="d-inline-block ms-2"><a href="#"><i class="fa fa-pinterest"></i></a></li>
		   <li class="d-inline-block ms-2"><a href="#"><i class="fa fa-linkedin-square"></i></a></li>
		  </ul>
		</div><hr class="mt-4 mb-4">
		<div class="blog_pg1r2">
		 <h5 class="mb-4">Explore Categories</h5>
		 <ul class="mb-0 font_14 tags">
		   <li class="d-inline-block"><a class="bg_lighto d-block p-2 rounded-3" href="#">Blog <span class="bg-white rounded-3 p-1 ms-1 d-inline-block">1,540</span></a></li>
		   <li class="d-inline-block"><a class="bg_lighto d-block p-2 rounded-3" href="#">Fertilizers & Soil <span class="bg-white rounded-3 p-1 ms-1 d-inline-block">138</span></a></li>
		   <li class="d-inline-block"><a class="bg_lighto d-block p-2 rounded-3" href="#">Gardening Advice <span class="bg-white rounded-3 p-1 ms-1 d-inline-block">228</span></a></li>
		   <li class="d-inline-block"><a class="bg_lighto d-block p-2 rounded-3" href="#">Gardening Products <span class="bg-white rounded-3 p-1 ms-1 d-inline-block">199</span></a></li>
		   <li class="d-inline-block"><a class="bg_lighto d-block p-2 rounded-3" href="#">How to grow <span class="bg-white rounded-3 p-1 ms-1 d-inline-block">212</span></a></li>
		   <li class="d-inline-block"><a class="bg_lighto d-block p-2 rounded-3" href="#">Pest & Diseases Control <span class="bg-white rounded-3 p-1 ms-1 d-inline-block">87</span></a></li>
		   <li class="d-inline-block"><a class="bg_lighto d-block p-2 rounded-3" href="#">Plant Care Tips <span class="bg-white rounded-3 p-1 ms-1 d-inline-block">96</span></a></li>
		   <li class="d-inline-block"><a class="bg_lighto d-block p-2 rounded-3" href="#">Planting Calendar <span class="bg-white rounded-3 p-1 ms-1 d-inline-block">169</span></a></li>
		   <li class="d-inline-block"><a class="bg_lighto d-block p-2 rounded-3" href="#">Uncategorized <span class="bg-white rounded-3 p-1 ms-1 d-inline-block">3</span></a></li>
		 </ul>
		</div><hr class="mt-3 mb-4">
		<div class="blog_pg1r3">
		 <h5 class="mb-4">Featured Posts</h5>
		  <div class="blog_pg1r3i row">
		    <div class="col-md-3 pe-0 col-sm-3">
			  <div class="blog_pg1r3il">
			    <div class="grid clearfix">
				  <figure class="effect-jazz mb-0">
					<a href="blog_detail.html"><img src="img/7.jpg" class="w-100" alt="abc"></a>
				  </figure>
			  </div>
			  </div>
			</div>
			<div class="col-md-9 col-sm-9">
			  <div class="blog_pg1r3ir">
			    <h6 class="font_14">By <a href="#">Ipsum Porta</a> <span class="col_grey mx-1 font_8 align-middle"><i class="fa fa-circle"></i></span> January 14, 2024</h6>
				<h5 class="mt-3 mb-3 fs-6 mb-0"><a href="#">Vertical Gardening: A Simple Guide to Growing Plant Upwards</a></h5>
			  </div>
			</div>
		  </div><hr>
		  <div class="blog_pg1r3i row">
		    <div class="col-md-3 pe-0 col-sm-3">
			  <div class="blog_pg1r3il">
			    <div class="grid clearfix">
				  <figure class="effect-jazz mb-0">
					<a href="blog_detail.html"><img src="img/8.jpg" class="w-100" alt="abc"></a>
				  </figure>
			  </div>
			  </div>
			</div>
			<div class="col-md-9 col-sm-9">
			  <div class="blog_pg1r3ir">
			    <h6 class="font_14">By <a href="#">Nulla Quis</a> <span class="col_grey mx-1 font_8 align-middle"><i class="fa fa-circle"></i></span> January 14, 2024</h6>
				<h5 class="mt-3 mb-3 fs-6 mb-0"><a href="#">Vertical Gardening: A Simple Guide to Growing Plant Upwards</a></h5>
			  </div>
			</div>
		  </div><hr>
		  <div class="blog_pg1r3i row">
		    <div class="col-md-3 pe-0 col-sm-3">
			  <div class="blog_pg1r3il">
			    <div class="grid clearfix">
				  <figure class="effect-jazz mb-0">
					<a href="blog_detail.html"><img src="img/14.jpg" class="w-100" alt="abc"></a>
				  </figure>
			  </div>
			  </div>
			</div>
			<div class="col-md-9 col-sm-9">
			  <div class="blog_pg1r3ir">
			    <h6 class="font_14">By <a href="#">Sed Augue</a> <span class="col_grey mx-1 font_8 align-middle"><i class="fa fa-circle"></i></span> January 14, 2024</h6>
				<h5 class="mt-3 mb-3 fs-6 mb-0"><a href="#">Vertical Gardening: A Simple Guide to Growing Plant Upwards</a></h5>
			  </div>
			</div>
		  </div><hr>
		  <div class="blog_pg1r3i row">
		    <div class="col-md-3 pe-0 col-sm-3">
			  <div class="blog_pg1r3il">
			    <div class="grid clearfix">
				  <figure class="effect-jazz mb-0">
					<a href="blog_detail.html"><img src="img/13.jpg" class="w-100" alt="abc"></a>
				  </figure>
			  </div>
			  </div>
			</div>
			<div class="col-md-9 col-sm-9">
			  <div class="blog_pg1r3ir">
			    <h6 class="font_14">By <a href="#">Lorem Amet</a> <span class="col_grey mx-1 font_8 align-middle"><i class="fa fa-circle"></i></span> January 14, 2024</h6>
				<h5 class="mt-3 mb-3 fs-6 mb-0"><a href="#">Vertical Gardening: A Simple Guide to Growing Plant Upwards</a></h5>
			  </div>
			</div>
		  </div>
		</div><hr class="mt-4 mb-4">
		<div class="blog_pg1r4">
          <div class="blog_pg1r4i position-relative">
	     <div class="blog_pg1r4i1">
	       <div class="grid clearfix">
				  <figure class="effect-jazz mb-0">
					<a href="blog_detail.html"><img src="img/58.jpg" class="w-100" alt="abc"></a>
				  </figure>
			  </div>
	   </div>
	   <div class="blog_pg1r4i2 bg_backo px-5 position-absolute w-100 h-100 top-0">
	     <h4 class="text-white">Learn with Us</h4>
		 <p class="text-light mt-3">Are You New to Gardening? Elevate Your Skills and Become a PRO—Don't Miss This Chance!</p>
		 <h6 class="mb-0"><a class="button_2 text-uppercase" href="#">Subscribe</a></h6>
	   </div>
	   </div>
		</div>
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