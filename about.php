<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giới thiệu - Cửa hàng Phân bón</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/global.css" rel="stylesheet">
    <style>
        .about-header {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('img/banner chinh 10.jpg');
            background-size: cover;
            background-position: center;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            margin-bottom: 50px;
        }

        .about-header h1 {
            font-size: 3.5rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }

        .about-section {
            padding: 60px 0;
        }

        .about-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 30px;
            transition: transform 0.3s ease;
        }

        .about-card:hover {
            transform: translateY(-10px);
        }

        .about-icon {
            font-size: 3rem;
            color: #28a745;
            margin-bottom: 20px;
        }

        .feature-box {
            text-align: center;
            padding: 30px;
            background: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            line-height: 80px;
            font-size: 2rem;
            background: #28a745;
            color: white;
            border-radius: 50%;
            margin: 0 auto 20px;
        }

        .team-section {
            background: #f8f9fa;
            padding: 60px 0;
        }

        .team-member {
            text-align: center;
            margin-bottom: 30px;
        }

        .team-member img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-bottom: 20px;
            border: 5px solid white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .stats-section {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            padding: 60px 0;
        }

        .stat-box {
            text-align: center;
            padding: 20px;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .testimonial {
            background: white;
            padding: 30px;
            border-radius: 10px;
            margin: 20px 0;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .testimonial img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 15px;
        }

        .quote {
            font-style: italic;
            color: #6c757d;
        }
    </style>
</head>
<body>

<!-- Header Banner -->
<div class="about-header">
    <div class="container">
        <h1>Về Chúng Tôi</h1>
        <p class="lead">Đồng hành cùng nhà nông - Phát triển nông nghiệp bền vững</p>
    </div>
</div>

<!-- Giới thiệu chung -->
<section class="about-section">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="mb-4">Câu Chuyện Của Chúng Tôi</h2>
                <p class="lead">Được thành lập từ năm 2010, chúng tôi tự hào là đơn vị tiên phong trong lĩnh vực cung cấp phân bón và các giải pháp dinh dưỡng cho cây trồng tại Việt Nam.</p>
                <p>Với hơn 10 năm kinh nghiệm, chúng tôi không ngừng nghiên cứu và phát triển để mang đến những sản phẩm chất lượng cao, góp phần nâng cao năng suất và chất lượng nông sản Việt.</p>
            </div>
            <div class="col-md-6">
                <div class="about-card">
                    <div class="about-icon">
                        <i class="fa fa-leaf"></i>
                    </div>
                    <h3>Tầm Nhìn</h3>
                    <p>Trở thành đơn vị hàng đầu trong lĩnh vực cung cấp phân bón và giải pháp dinh dưỡng cho cây trồng, góp phần phát triển nền nông nghiệp Việt Nam theo hướng hiện đại và bền vững.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Giá trị cốt lõi -->
<section class="about-section bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Giá Trị Cốt Lõi</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="feature-box">
                    <div class="feature-icon">
                        <i class="fa fa-check"></i>
                    </div>
                    <h3>Chất Lượng</h3>
                    <p>Cam kết cung cấp sản phẩm đạt tiêu chuẩn chất lượng cao nhất</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <div class="feature-icon">
                        <i class="fa fa-heart"></i>
                    </div>
                    <h3>Tận Tâm</h3>
                    <p>Luôn đặt lợi ích của khách hàng lên hàng đầu</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <div class="feature-icon">
                        <i class="fa fa-lightbulb-o"></i>
                    </div>
                    <h3>Sáng Tạo</h3>
                    <p>Không ngừng đổi mới và phát triển sản phẩm</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Thống kê -->
<section class="stats-section">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-number">10+</div>
                    <div>Năm Kinh Nghiệm</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-number">1000+</div>
                    <div>Khách Hàng</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-number">50+</div>
                    <div>Sản Phẩm</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-number">20+</div>
                    <div>Tỉnh Thành</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Đội ngũ -->
<section class="team-section">
    <div class="container">
        <h2 class="text-center mb-5">Đội Ngũ Chuyên Gia</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="team-member">
                    <img src="img/avatars/default-avatar.jpg" alt="Chuyên gia nông nghiệp">
                    <h4>TS. Nguyễn Văn A</h4>
                    <p>Giám đốc Kỹ thuật</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="team-member">
                    <img src="img/avatars/default-avatar.jpg" alt="Chuyên gia dinh dưỡng">
                    <h4>ThS. Trần Thị B</h4>
                    <p>Trưởng phòng R&D</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="team-member">
                    <img src="img/avatars/default-avatar.jpg" alt="Chuyên gia tư vấn">
                    <h4>KS. Lê Văn C</h4>
                    <p>Trưởng phòng Tư vấn</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Phản hồi khách hàng -->
<section class="about-section">
    <div class="container">
        <h2 class="text-center mb-5">Khách Hàng Nói Gì Về Chúng Tôi</h2>
        <div class="row">
            <div class="col-md-6">
                <div class="testimonial">
                    <img src="img/avatars/default-avatar.jpg" alt="Khách hàng">
                    <h4>Anh Nguyễn Văn X</h4>
                    <p class="quote">"Sản phẩm chất lượng, đội ngũ tư vấn nhiệt tình. Từ ngày sử dụng phân bón của công ty, năng suất cây trồng của tôi tăng rõ rệt."</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="testimonial">
                    <img src="img/avatars/default-avatar.jpg" alt="Khách hàng">
                    <h4>Chị Trần Thị Y</h4>
                    <p class="quote">"Dịch vụ chăm sóc khách hàng tuyệt vời. Các chuyên gia luôn sẵn sàng tư vấn và hỗ trợ khi cần thiết."</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html> 