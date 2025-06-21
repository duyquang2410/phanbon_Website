<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Plants Shop - Bài Viết</title>
	<link href="css/bootstrap.min.css" rel="stylesheet" >
	<link href="css/font-awesome.min.css" rel="stylesheet" >
	<link href="css/global.css" rel="stylesheet">
	<link href="css/blog.css" rel="stylesheet">
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
<nav class="navbar navbar-expand-md navbar-light pt-2 pb-2 bg_lighto">
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

<section id="center" class="center_blog p_3">
 <div class="container-fluid">
   <div class="center_blog_1 row">
     <div class="col-md-7">
	   <div class="center_blog_1l position-relative">
	     <div class="center_blog_1li">
	       <div class="grid clearfix">
				  <figure class="effect-jazz mb-0">
					<a href="blog_detail.html"><img src="img/57.jpg" class="w-100" alt="abc"></a>
				  </figure>
			  </div>
	   </div>
	   <div class="center_blog_1li1 bg_backo px-5 position-absolute w-100 h-100 top-0">
	   <h6 class="text-white-50">By <a class="text-white" href="blog_detail.html">Ipsum Porta</a> <span class="col_grey mx-1 font_8 align-middle"><i class="fa fa-circle"></i></span> January 14, 2024</h6>
	   <h3 class="mt-3"><a class="text-white" href="blog_detail.html">Container Gardening For Beginners In India: Step by Step Guide</a></h3>
	   <ul class="mb-0 font_14 mt-4">
				<li class="d-inline-block"><a class="d-inline-block rounded-3 p-1 px-2" href="blog_detail.html">Blog</a></li>
				<li class="d-inline-block ms-1"><a class="d-inline-block rounded-3 p-1 px-2" href="blog_detail.html">Gardening Advice</a></li>
				</ul>
	   </div>
	   </div>
	 </div>
	 <div class="col-md-5">
	   <div class="center_blog_1r">
	     <div class="center_blog_1ri row">
	       <div class="col-md-7">
		     <div class="center_blog_1ril">
			    <h6 class="font_14">By <a href="blog_detail.html">Ipsum Porta</a> <span class="col_grey mx-1 font_8 align-middle"><i class="fa fa-circle"></i></span> January 14, 2024</h6>
				<h5 class="mt-3 mb-3"><a href="blog_detail.html">Vertical Gardening: A Simple Guide to Growing Plant Upwards</a></h5>
				<ul class="mb-0 font_14"><li class="d-inline-block"><a class="bg_grey d-inline-block rounded-3 p-1 px-2" href="blog_detail.html">Blog</a></li></ul>
			 </div>
		   </div>
		   <div class="col-md-5">
		     <div class="center_blog_1rir">
			    <div class="grid clearfix">
				  <figure class="effect-jazz mb-0">
					<a href="blog_detail.html"><img src="img/54.jpg" class="w-100" alt="abc"></a>
				  </figure>
			  </div>
			 </div>
		   </div>
	     </div><hr>
		 <div class="center_blog_1ri row">
	       <div class="col-md-7">
		     <div class="center_blog_1ril">
			    <h6 class="font_14">By <a href="blog_detail.html">Nulla Quis</a> <span class="col_grey mx-1 font_8 align-middle"><i class="fa fa-circle"></i></span> January 14, 2024</h6>
				<h5 class="mt-3 mb-3"><a href="blog_detail.html">Vertical Gardening: A Simple Guide to Growing Plant Upwards</a></h5>
				<ul class="mb-0 font_14">
				<li class="d-inline-block"><a class="bg_grey d-inline-block rounded-3 p-1 px-2" href="blog_detail.html">Blog</a></li>
				<li class="d-inline-block ms-1"><a class="bg_grey d-inline-block rounded-3 p-1 px-2" href="blog_detail.html">Gardening Advice</a></li>
				</ul>
			 </div>
		   </div>
		   <div class="col-md-5">
		     <div class="center_blog_1rir">
			    <div class="grid clearfix">
				  <figure class="effect-jazz mb-0">
					<a href="blog_detail.html"><img src="img/56.jpg" class="w-100" alt="abc"></a>
				  </figure>
			  </div>
			 </div>
		   </div>
	     </div><hr>
		 <div class="center_blog_1ri row">
	       <div class="col-md-7">
		     <div class="center_blog_1ril">
			    <h6 class="font_14">By <a href="blog_detail.html">Lorem Amet</a> <span class="col_grey mx-1 font_8 align-middle"><i class="fa fa-circle"></i></span> January 14, 2024</h6>
				<h5 class="mt-3 mb-3"><a href="blog_detail.html">Vertical Gardening: A Simple Guide to Growing Plant Upwards</a></h5>
				<ul class="mb-0 font_14">
				<li class="d-inline-block"><a class="bg_grey d-inline-block rounded-3 p-1 px-2" href="blog_detail.html">Blog</a></li>
				<li class="d-inline-block ms-1"><a class="bg_grey d-inline-block rounded-3 p-1 px-2" href="blog_detail.html">Gardening Advice</a></li>
				</ul>
			 </div>
		   </div>
		   <div class="col-md-5">
		     <div class="center_blog_1rir">
			    <div class="grid clearfix">
				  <figure class="effect-jazz mb-0">
					<a href="blog_detail.html"><img src="img/55.jpg" class="w-100" alt="abc"></a>
				  </figure>
			  </div>
			 </div>
		   </div>
	     </div>
	   </div>
	 </div>
   </div>
 </div>
</section><hr class="m-0">

<section id="blog_pg" class="p_3">
 <div class="container-fluid">
   <div class="blog_pg1 row">
     <div class="col-md-8">
	  <div class="blog_pg1l">
	    <div class="blog_pg1li row">
		  <div class="col-md-6">
		    <div class="blog_pg1lil">
			  <div class="grid clearfix">
				  <figure class="effect-jazz mb-0">
					<a href="blog_detail.html"><img src="img/59.jpg" class="w-100" alt="abc"></a>
				  </figure>
			  </div>
			</div>
		  </div>
		  <div class="col-md-6">
		    <div class="blog_pg1lir">
			  <h6>By <a href="blog_detail.html">Nulla Quis</a> <span class="col_grey mx-1 font_8 align-middle"><i class="fa fa-circle"></i></span> January 14, 2024</h6>
			  <h4 class="mt-3"><a href="blog_detail.html">How to start a compost pile in your backyard</a></h4>
			  <p class="mt-3 fs-6">Setting up a Compost Pile in the backyard of your home can prove to be a good decision…</p>
			  <ul class="mb-0 font_14">
				<li class="d-inline-block"><a class="bg_grey d-inline-block rounded-3 p-1 px-2" href="blog_detail.html">Blog</a></li>
				<li class="d-inline-block ms-1"><a class="bg_grey d-inline-block rounded-3 p-1 px-2" href="blog_detail.html">Fertilizers & Soil</a></li>
				</ul>
			</div>
		  </div>
		</div><hr class="mt-4 mb-4">
		<div class="blog_pg1li row">
		  <div class="col-md-6">
		    <div class="blog_pg1lil">
			  <div class="grid clearfix">
				  <figure class="effect-jazz mb-0">
					<a href="blog_detail.html"><img src="img/60.jpg" class="w-100" alt="abc"></a>
				  </figure>
			  </div>
			</div>
		  </div>
		  <div class="col-md-6">
		    <div class="blog_pg1lir">
			  <h6>By <a href="blog_detail.html">Nulla Quis</a> <span class="col_grey mx-1 font_8 align-middle"><i class="fa fa-circle"></i></span> January 14, 2024</h6>
			  <h4 class="mt-3"><a href="blog_detail.html">How to start a compost pile in your backyard</a></h4>
			  <p class="mt-3 fs-6">Setting up a Compost Pile in the backyard of your home can prove to be a good decision…</p>
			  <ul class="mb-0 font_14">
				<li class="d-inline-block"><a class="bg_grey d-inline-block rounded-3 p-1 px-2" href="blog_detail.html">Blog</a></li>
				<li class="d-inline-block ms-1"><a class="bg_grey d-inline-block rounded-3 p-1 px-2" href="blog_detail.html">Fertilizers & Soil</a></li>
				</ul>
			</div>
		  </div>
		</div><hr class="mt-4 mb-4">
		<div class="blog_pg1li row">
		  <div class="col-md-6">
		    <div class="blog_pg1lil">
			  <div class="grid clearfix">
				  <figure class="effect-jazz mb-0">
					<a href="blog_detail.html"><img src="img/61.jpg" class="w-100" alt="abc"></a>
				  </figure>
			  </div>
			</div>
		  </div>
		  <div class="col-md-6">
		    <div class="blog_pg1lir">
			  <h6>By <a href="blog_detail.html">Nulla Quis</a> <span class="col_grey mx-1 font_8 align-middle"><i class="fa fa-circle"></i></span> January 14, 2024</h6>
			  <h4 class="mt-3"><a href="blog_detail.html">How to start a compost pile in your backyard</a></h4>
			  <p class="mt-3 fs-6">Setting up a Compost Pile in the backyard of your home can prove to be a good decision…</p>
			  <ul class="mb-0 font_14">
				<li class="d-inline-block"><a class="bg_grey d-inline-block rounded-3 p-1 px-2" href="blog_detail.html">Blog</a></li>
				<li class="d-inline-block ms-1"><a class="bg_grey d-inline-block rounded-3 p-1 px-2" href="blog_detail.html">Fertilizers & Soil</a></li>
				</ul>
			</div>
		  </div>
		</div><hr class="mt-4 mb-4">
		<div class="blog_pg1li row">
		  <div class="col-md-6">
		    <div class="blog_pg1lil">
			  <div class="grid clearfix">
				  <figure class="effect-jazz mb-0">
					<a href="blog_detail.html"><img src="img/62.jpg" class="w-100" alt="abc"></a>
				  </figure>
			  </div>
			</div>
		  </div>
		  <div class="col-md-6">
		    <div class="blog_pg1lir">
			  <h6>By <a href="blog_detail.html">Nulla Quis</a> <span class="col_grey mx-1 font_8 align-middle"><i class="fa fa-circle"></i></span> January 14, 2024</h6>
			  <h4 class="mt-3"><a href="blog_detail.html">How to start a compost pile in your backyard</a></h4>
			  <p class="mt-3 fs-6">Setting up a Compost Pile in the backyard of your home can prove to be a good decision…</p>
			  <ul class="mb-0 font_14">
				<li class="d-inline-block"><a class="bg_grey d-inline-block rounded-3 p-1 px-2" href="blog_detail.html">Blog</a></li>
				<li class="d-inline-block ms-1"><a class="bg_grey d-inline-block rounded-3 p-1 px-2" href="blog_detail.html">Fertilizers & Soil</a></li>
				</ul>
			</div>
		  </div>
		</div><hr class="mt-4 mb-4">
		<div class="blog_pg1li row">
		  <div class="col-md-6">
		    <div class="blog_pg1lil">
			  <div class="grid clearfix">
				  <figure class="effect-jazz mb-0">
					<a href="blog_detail.html"><img src="img/63.jpg" class="w-100" alt="abc"></a>
				  </figure>
			  </div>
			</div>
		  </div>
		  <div class="col-md-6">
		    <div class="blog_pg1lir">
			  <h6>By <a href="blog_detail.html">Nulla Quis</a> <span class="col_grey mx-1 font_8 align-middle"><i class="fa fa-circle"></i></span> January 14, 2024</h6>
			  <h4 class="mt-3"><a href="blog_detail.html">How to start a compost pile in your backyard</a></h4>
			  <p class="mt-3 fs-6">Setting up a Compost Pile in the backyard of your home can prove to be a good decision…</p>
			  <ul class="mb-0 font_14">
				<li class="d-inline-block"><a class="bg_grey d-inline-block rounded-3 p-1 px-2" href="blog_detail.html">Blog</a></li>
				<li class="d-inline-block ms-1"><a class="bg_grey d-inline-block rounded-3 p-1 px-2" href="blog_detail.html">Fertilizers & Soil</a></li>
				</ul>
			</div>
		  </div>
		</div><hr class="mt-4 mb-4">
		<div class="blog_pg1li row">
		  <div class="col-md-6">
		    <div class="blog_pg1lil">
			  <div class="grid clearfix">
				  <figure class="effect-jazz mb-0">
					<a href="blog_detail.html"><img src="img/64.jpg" class="w-100" alt="abc"></a>
				  </figure>
			  </div>
			</div>
		  </div>
		  <div class="col-md-6">
		    <div class="blog_pg1lir">
			  <h6>By <a href="blog_detail.html">Nulla Quis</a> <span class="col_grey mx-1 font_8 align-middle"><i class="fa fa-circle"></i></span> January 14, 2024</h6>
			  <h4 class="mt-3"><a href="blog_detail.html">How to start a compost pile in your backyard</a></h4>
			  <p class="mt-3 fs-6">Setting up a Compost Pile in the backyard of your home can prove to be a good decision…</p>
			  <ul class="mb-0 font_14">
				<li class="d-inline-block"><a class="bg_grey d-inline-block rounded-3 p-1 px-2" href="blog_detail.html">Blog</a></li>
				<li class="d-inline-block ms-1"><a class="bg_grey d-inline-block rounded-3 p-1 px-2" href="blog_detail.html">Fertilizers & Soil</a></li>
				</ul>
			</div>
		  </div>
		</div>
		<div class="pages mt-4 row text-center">
		 <div class="col-md-12">
		   <ul class="mb-0">
			<li><a href="blog_detail.html"><i class="fa fa-chevron-left"></i></a></li>
			<li><a class="act" href="blog_detail.html">1</a></li>
			<li><a href="blog_detail.html">2</a></li>
			<li><a href="blog_detail.html">3</a></li>
			<li><a href="blog_detail.html">4</a></li>
			<li><a href="blog_detail.html">5</a></li>
			<li><a href="blog_detail.html">6</a></li>
			<li><a href="blog_detail.html"><i class="fa fa-chevron-right"></i></a></li>
		   </ul>
		 </div>
	 </div>
	  </div>
	 </div>
	 <div class="col-md-4">
	  <div class="blog_pg1r">
	    <div class="blog_pg1r1 bg_lighto p-5 px-5 rounded-3">
		  <p class="fs-6">Welcome to your ultimate hub for all things organic gardening! Explore our carefully crafted content that's both informative and fun, designed to captivate garden enthusiasts worldwide.</p>
		  <ul class="mb-0 fs-4">
		   <li class="d-inline-block"><a href="blog_detail.html"><i class="fa fa-facebook"></i></a></li>
		   <li class="d-inline-block ms-2"><a href="blog_detail.html"><i class="fa fa-instagram"></i></a></li>
		   <li class="d-inline-block ms-2"><a href="blog_detail.html"><i class="fa fa-pinterest"></i></a></li>
		   <li class="d-inline-block ms-2"><a href="blog_detail.html"><i class="fa fa-linkedin-square"></i></a></li>
		  </ul>
		</div><hr class="mt-4 mb-4">
		<div class="blog_pg1r2">
		 <h5 class="mb-4">Explore Categories</h5>
		 <ul class="mb-0 font_14 tags">
		   <li class="d-inline-block"><a class="bg_lighto d-block p-2 rounded-3" href="blog_detail.html">Blog <span class="bg-white rounded-3 p-1 ms-1 d-inline-block">1,540</span></a></li>
		   <li class="d-inline-block"><a class="bg_lighto d-block p-2 rounded-3" href="blog_detail.html">Fertilizers & Soil <span class="bg-white rounded-3 p-1 ms-1 d-inline-block">138</span></a></li>
		   <li class="d-inline-block"><a class="bg_lighto d-block p-2 rounded-3" href="blog_detail.html">Gardening Advice <span class="bg-white rounded-3 p-1 ms-1 d-inline-block">228</span></a></li>
		   <li class="d-inline-block"><a class="bg_lighto d-block p-2 rounded-3" href="blog_detail.html">Gardening Products <span class="bg-white rounded-3 p-1 ms-1 d-inline-block">199</span></a></li>
		   <li class="d-inline-block"><a class="bg_lighto d-block p-2 rounded-3" href="blog_detail.html">How to grow <span class="bg-white rounded-3 p-1 ms-1 d-inline-block">212</span></a></li>
		   <li class="d-inline-block"><a class="bg_lighto d-block p-2 rounded-3" href="blog_detail.html">Pest & Diseases Control <span class="bg-white rounded-3 p-1 ms-1 d-inline-block">87</span></a></li>
		   <li class="d-inline-block"><a class="bg_lighto d-block p-2 rounded-3" href="blog_detail.html">Plant Care Tips <span class="bg-white rounded-3 p-1 ms-1 d-inline-block">96</span></a></li>
		   <li class="d-inline-block"><a class="bg_lighto d-block p-2 rounded-3" href="blog_detail.html">Planting Calendar <span class="bg-white rounded-3 p-1 ms-1 d-inline-block">169</span></a></li>
		   <li class="d-inline-block"><a class="bg_lighto d-block p-2 rounded-3" href="blog_detail.html">Uncategorized <span class="bg-white rounded-3 p-1 ms-1 d-inline-block">3</span></a></li>
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
		 <h6 class="mb-0"><a class="button_2 text-uppercase" href="blog_detail.html">Subscribe</a></h6>
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
// Xóa toàn bộ code liên quan đến scroll
document.addEventListener('DOMContentLoaded', function() {
    // Chỉ giữ lại các chức năng cần thiết khác nếu có
});
</script>

</body>
</html>