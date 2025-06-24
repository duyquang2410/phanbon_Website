<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Liên hệ - Cửa hàng Phân bón</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/global.css" rel="stylesheet">
	<style>
		.page-header {
			background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('img/banner chinh 10.jpg');
			background-size: cover;
			background-position: center;
			padding: 60px 0;
			margin-bottom: 0;
		}

		.page-header h1 {
			color: white;
			font-size: 2.5rem;
			font-weight: 600;
		}

		.breadcrumb {
			background: transparent;
			padding: 0;
			margin: 0;
		}

		.breadcrumb-item, .breadcrumb-item a {
			color: rgba(255, 255, 255, 0.8);
		}

		.breadcrumb-item.active {
			color: white;
		}

		.contact-info {
			background: #f8f9fa;
			padding: 60px 0;
		}

		.info-box {
			background: white;
			padding: 30px;
			border-radius: 10px;
			box-shadow: 0 0 15px rgba(0,0,0,0.05);
			height: 100%;
			transition: transform 0.3s;
		}

		.info-box:hover {
			transform: translateY(-5px);
		}

		.info-icon {
			width: 50px;
			height: 50px;
			line-height: 50px;
			text-align: center;
			background: #28a745;
			color: white;
			border-radius: 50%;
			margin-bottom: 20px;
			font-size: 20px;
		}

		.contact-form-section {
			padding: 60px 0;
			background: white;
		}

		.form-container {
			background: #f8f9fa;
			padding: 40px;
			border-radius: 10px;
		}

		.form-control {
			padding: 12px;
			border: 1px solid #dee2e6;
			border-radius: 5px;
			margin-bottom: 20px;
		}

		.form-control:focus {
			border-color: #28a745;
			box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
		}

		.btn-submit {
			background: #28a745;
			color: white;
			padding: 12px 30px;
			border: none;
			border-radius: 5px;
			font-weight: 500;
			transition: all 0.3s;
		}

		.btn-submit:hover {
			background: #218838;
			transform: translateY(-2px);
		}

		.map-section {
			padding: 60px 0;
			background: #f8f9fa;
		}

		.map-container {
			height: 400px;
			border-radius: 10px;
			overflow: hidden;
			box-shadow: 0 0 20px rgba(0,0,0,0.1);
		}

		.business-hours {
			padding: 15px;
			background: white;
			border-radius: 5px;
			margin-top: 15px;
		}

		.business-hours i {
			color: #28a745;
			margin-right: 10px;
		}

		.commitment-box {
			margin-bottom: 30px;
		}

		.commitment-icon {
			margin-right: 20px;
			flex-shrink: 0;
		}

		@media (max-width: 768px) {
			.page-header {
				padding: 40px 0;
			}
			.info-box {
				margin-bottom: 20px;
			}
			.form-container {
				padding: 20px;
			}
		}
	</style>
</head>
<body>

<!-- Header Banner -->
<section class="page-header">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<h1 class="text-center mb-3">Liên Hệ Với Chúng Tôi</h1>
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb justify-content-center">
						<li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
						<li class="breadcrumb-item active" aria-current="page">Liên hệ</li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
</section>

<!-- Thông tin liên hệ -->
<section class="contact-info">
	<div class="container">
		<div class="row g-4">
			<div class="col-md-4">
				<div class="info-box text-center">
					<div class="info-icon mx-auto">
						<i class="fa fa-map-marker"></i>
					</div>
					<h4>Địa Chỉ</h4>
					<p class="mb-0">82 Nguyễn Minh Hoàng, P.12<br>Q. Tân Bình, TP.HCM</p>
				</div>
			</div>
			<div class="col-md-4">
				<div class="info-box text-center">
					<div class="info-icon mx-auto">
						<i class="fa fa-phone"></i>
					</div>
					<h4>Điện Thoại</h4>
					<p class="mb-2">Hotline: 0865 399 086<br>Góp ý: 0906 800 386</p>
					<div class="business-hours">
						<i class="fa fa-clock-o"></i>Thứ 2 - Chủ Nhật: 7:30 - 18:00
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="info-box text-center">
					<div class="info-icon mx-auto">
						<i class="fa fa-envelope"></i>
					</div>
					<h4>Email</h4>
					<p class="mb-0">info@phanbon.com<br>support@phanbon.com</p>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Form liên hệ và Cam kết -->
<section class="contact-form-section">
	<div class="container">
		<div class="row">
			<div class="col-lg-7 mb-4 mb-lg-0">
				<div class="form-container">
					<h3 class="mb-4">Gửi Tin Nhắn</h3>
					<form>
						<div class="row">
							<div class="col-md-6">
								<input type="text" class="form-control" placeholder="Họ và tên">
							</div>
							<div class="col-md-6">
								<input type="email" class="form-control" placeholder="Email">
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<input type="tel" class="form-control" placeholder="Số điện thoại">
							</div>
							<div class="col-md-6">
								<input type="text" class="form-control" placeholder="Chủ đề">
							</div>
						</div>
						<textarea class="form-control" rows="5" placeholder="Nội dung tin nhắn"></textarea>
						<button type="submit" class="btn btn-submit">Gửi Tin Nhắn</button>
					</form>
				</div>
			</div>
			<div class="col-lg-5">
				<h3 class="mb-4">Cam Kết Của Chúng Tôi</h3>
				<div class="commitment-box d-flex align-items-start">
					<div class="info-icon commitment-icon">
						<i class="fa fa-check"></i>
					</div>
					<div>
						<h5>Sản Phẩm Chất Lượng</h5>
						<p>Cam kết cung cấp sản phẩm chính hãng, chất lượng cao, đảm bảo hiệu quả sử dụng.</p>
					</div>
				</div>
				<div class="commitment-box d-flex align-items-start">
					<div class="info-icon commitment-icon">
						<i class="fa fa-users"></i>
					</div>
					<div>
						<h5>Tư Vấn Chuyên Nghiệp</h5>
						<p>Đội ngũ chuyên viên giàu kinh nghiệm, tận tâm hỗ trợ khách hàng.</p>
					</div>
				</div>
				<div class="commitment-box d-flex align-items-start">
					<div class="info-icon commitment-icon">
						<i class="fa fa-truck"></i>
					</div>
					<div>
						<h5>Giao Hàng Nhanh Chóng</h5>
						<p>Dịch vụ giao hàng nhanh chóng, đảm bảo trong ngày tại TP.HCM.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Bản đồ -->
<section class="map-section">
	<div class="container">
		<div class="map-container">
			<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.2887350396577!2d106.64037937465353!3d10.79140358931455!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752937141da889%3A0xd28d47b33da93594!2zODIgTmd1eeG7hW4gTWluaCBIb8OgbmcsIFBoxrDhu51uZyAxMiwgVMOibiBCw6xuaCwgVGjDoG5oIHBo4buRIEjhu5MgQ2jDrSBNaW5oLCBWaeG7h3QgTmFt!5e0!3m2!1svi!2s!4v1709704844315!5m2!1svi!2s" 
				width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
		</div>
	</div>
</section>

<?php include 'footer.php'; ?>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>