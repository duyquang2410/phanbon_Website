<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chi tiết bệnh cây trồng</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/global.css" rel="stylesheet">
    <link href="css/blog.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <style>
        .disease-detail {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
            padding: 30px;
        }
        .disease-image {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .disease-image img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .disease-image:hover img {
            transform: scale(1.05);
        }
        .section-title {
            color: #2c5282;
            font-size: 1.5rem;
            font-weight: 600;
            margin: 30px 0 20px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
            clear: both;
        }
        .content-box {
            background: #f8fafc;
            border-radius: 10px;
            border-left: 4px solid #48bb78;
            line-height: 1.6;
            padding: 15px 20px;
            margin-bottom: 20px;
            clear: both;
        }
        .plant-types {
            background: #f0fff4;
            border-radius: 8px;
            padding: 12px;
            margin: 10px 0 20px 0;
            width: 100%;
            display: block;
            min-height: 50px;
        }
        .plant-types .badge {
            font-size: 0.85rem;
            padding: 6px 12px;
            background-color: #48bb78;
            transition: all 0.3s ease;
            margin: 3px;
            display: inline-flex;
            align-items: center;
            white-space: normal;
            line-height: 1.2;
            height: auto;
            border-radius: 4px;
        }
        .plant-types .badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .plant-types .badge i {
            font-size: 0.8rem;
            margin-right: 5px;
            width: auto;
        }
        .plant-types .badge span {
            display: inline;
            vertical-align: middle;
        }
        .product-card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .product-card img {
            height: 120px;
            object-fit: cover;
        }
        .additional-info {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
            border-radius: 15px;
            padding: 25px !important;
        }
        .additional-info .section-title {
            color: white;
            border-bottom-color: rgba(255,255,255,0.2);
        }
        .additional-info ul li {
            position: relative;
            padding-left: 25px;
            margin-bottom: 15px;
            font-size: 0.95rem;
        }
        .additional-info ul li:before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #fff;
            font-weight: bold;
        }
        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 2rem;
        }
        .breadcrumb-item a {
            color: #48bb78;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .breadcrumb-item a:hover {
            color: #2f855a;
        }
        .breadcrumb-item.active {
            color: #4a5568;
        }
        .disease-content {
            margin-top: 20px;
        }
        .disease-content section {
            margin-bottom: 30px;
            clear: both;
            width: 100%;
            float: none;
            display: block;
        }
        .disease-content p {
            margin-bottom: 10px;
            line-height: 1.6;
        }
        .disease-content p:last-child {
            margin-bottom: 0;
        }
        /* Điều chỉnh phần hiển thị các badge */
        .plant-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin: 0;
            align-items: center;
        }
        .badge span {
            display: inline-block;
            vertical-align: middle;
            max-width: calc(100% - 25px); /* Trừ đi width của icon */
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .badge i {
            width: 20px;
            text-align: center;
            margin-right: 5px;
        }
        /* Style cho triệu chứng và biện pháp */
        .symptom-item, .prevention-item {
            position: relative;
            padding-left: 20px;
            margin-bottom: 10px;
        }
        .symptom-item:before, .prevention-item:before {
            content: "•";
            position: absolute;
            left: 0;
            color: #48bb78;
            font-weight: bold;
        }
        /* Điều chỉnh kích thước background */
        .plant-types-section {
            margin-bottom: 20px;
            background: #f0fff4;
            border-radius: 8px;
            padding: 15px;
        }
        .plant-types-section .section-title {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 1.2rem;
            border-bottom: none;
            color: #2f855a;
        }
        @media (max-width: 768px) {
            .disease-image img {
                height: 300px;
            }
            .disease-detail {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<?php
include_once 'header.php';
include_once 'connect.php';

// Lấy ID bệnh từ URL
$ma_benh = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Truy vấn thông tin chi tiết bệnh
$query = "
    SELECT lb.*, lct.LCT_TEN, sp.SP_MA, sp.SP_TEN, sp.SP_MOTA, sp.SP_HINHANH, sp.SP_DONGIA
    FROM loai_benh lb
    LEFT JOIN san_pham_cay_trong spct ON lb.LCT_MA = spct.LCT_MA
    LEFT JOIN loai_cay_trong lct ON lb.LCT_MA = lct.LCT_MA
    LEFT JOIN san_pham sp ON spct.SP_MA = sp.SP_MA
    WHERE lb.Ma_loai_benh = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $ma_benh);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='container mt-5'><div class='alert alert-warning'>Không tìm thấy thông tin bệnh</div></div>";
    include_once 'footer.php';
    exit();
}

// Lấy thông tin cơ bản của bệnh
$benh = $result->fetch_assoc();
$ten_benh = $benh['Ten_loai_benh'];
$mo_ta = $benh['mo_ta'];
$cach_phong_ngua = $benh['cach_phong_ngua'];
$hinh_anh = $benh['hinh_anh'];

// Lấy danh sách cây trồng và sản phẩm
$cay_trong = array();
$san_pham = array();
$result->data_seek(0);
while ($row = $result->fetch_assoc()) {
    if ($row['LCT_TEN'] && !in_array($row['LCT_TEN'], $cay_trong)) {
        $cay_trong[] = $row['LCT_TEN'];
    }
    if ($row['SP_MA'] && !isset($san_pham[$row['SP_MA']])) {
        $san_pham[$row['SP_MA']] = array(
            'ten' => $row['SP_TEN'],
            'mo_ta' => $row['SP_MOTA'],
            'hinh_anh' => $row['SP_HINHANH'],
            'gia' => $row['SP_DONGIA']
        );
    }
}
?>

<div class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="blog.php">Cẩm nang bệnh hại cây trồng</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($ten_benh); ?></li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Cột chính -->
        <div class="col-lg-8">
            <article class="disease-detail">
                <!-- Tiêu đề -->
                <h1 class="display-5 fw-bold mb-4 text-success"><?php echo htmlspecialchars($ten_benh); ?></h1>
                
                <!-- Hình ảnh -->
                <?php if ($hinh_anh): ?>
                <div class="disease-image mb-4">
                    <img src="img/benh/<?php echo htmlspecialchars($hinh_anh); ?>" 
                         alt="<?php echo htmlspecialchars($ten_benh); ?>"
                         class="img-fluid">
                </div>
                <?php endif; ?>

                <!-- Nội dung chi tiết -->
                <div class="disease-content mt-5">
                    <!-- Dấu hiệu nhận biết -->
                    <section class="mb-5">
                        <h2 class="section-title">
                            <i class="fa fa-search"></i>
                            Dấu hiệu nhận biết
                        </h2>
                        <div class="content-box">
                            <?php 
                            $trieu_chung = explode("\n", $mo_ta);
                            foreach($trieu_chung as $tc) {
                                if(trim($tc) != "") {
                                    echo '<div class="symptom-item">' . htmlspecialchars(trim($tc)) . '</div>';
                                }
                            }
                            ?>
                        </div>
                    </section>

                    <!-- Cây trồng bị ảnh hưởng -->
                    <?php if (!empty($cay_trong)): ?>
                    <section class="plant-types-section">
                        <h2 class="section-title">
                            <i class="fa fa-leaf"></i>
                            Cây trồng thường bị ảnh hưởng
                        </h2>
                        <div class="plant-types">
                            <div class="plant-tags">
                                <?php foreach ($cay_trong as $cay): ?>
                                <span class="badge">
                                    <i class="fa fa-seedling"></i>
                                    <span><?php echo htmlspecialchars($cay); ?></span>
                                </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </section>
                    <?php endif; ?>

                    <!-- Biện pháp phòng ngừa -->
                    <section class="mb-5">
                        <h2 class="section-title">
                            <i class="fa fa-shield-alt"></i>
                            Biện pháp phòng ngừa
                        </h2>
                        <div class="content-box">
                            <?php 
                            $bien_phap = explode("\n", $cach_phong_ngua);
                            foreach($bien_phap as $bp) {
                                if(trim($bp) != "") {
                                    echo '<div class="prevention-item">' . htmlspecialchars(trim($bp)) . '</div>';
                                }
                            }
                            ?>
                        </div>
                    </section>
                </div>
            </article>
        </div>

        <!-- Cột phụ -->
        <div class="col-lg-4">
            <!-- Sản phẩm đề xuất -->
            <?php if (!empty($san_pham)): ?>
            <aside class="recommended-products mb-4">
                <h2 class="section-title">
                    <i class="fa fa-shopping-basket me-2"></i>
                    Sản phẩm đề xuất
                </h2>
                <?php foreach ($san_pham as $sp_ma => $sp): ?>
                <div class="card mb-3 product-card">
                    <div class="row g-0">
                        <div class="col-4">
                            <img src="img/<?php echo htmlspecialchars($sp['hinh_anh']); ?>" 
                                 class="img-fluid rounded-start" 
                                 alt="<?php echo htmlspecialchars($sp['ten']); ?>">
                        </div>
                        <div class="col-8">
                            <div class="card-body">
                                <h3 class="card-title h6">
                                    <a href="detail.php?id=<?php echo $sp_ma; ?>" 
                                       class="text-decoration-none text-dark">
                                        <?php echo htmlspecialchars($sp['ten']); ?>
                                    </a>
                                </h3>
                                <?php if ($sp['gia']): ?>
                                <p class="card-text text-danger fw-bold mb-2">
                                    <?php echo number_format($sp['gia'], 0, ',', '.'); ?>đ
                                </p>
                                <?php endif; ?>
                                <a href="detail.php?id=<?php echo $sp_ma; ?>" 
                                   class="btn btn-outline-success btn-sm">
                                    <i class="fa fa-info-circle me-1"></i>
                                    Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </aside>
            <?php endif; ?>

            <!-- Thông tin bổ sung -->
            <aside class="additional-info">
                <h2 class="section-title h5">
                    <i class="fa fa-exclamation-circle me-2"></i>
                    Lưu ý quan trọng
                </h2>
                <ul class="list-unstyled mb-0">
                    <li>Theo dõi cây trồng thường xuyên</li>
                    <li>Xử lý kịp thời khi phát hiện bệnh</li>
                    <li>Tuân thủ liều lượng thuốc khuyến cáo</li>
                    <li>Tham khảo ý kiến chuyên gia khi cần</li>
                </ul>
            </aside>
        </div>
    </div>
</div>

<?php include_once 'footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hiệu ứng fade-in cho các phần tử khi scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
            }
        });
    });

    // Áp dụng hiệu ứng cho các phần tử
    document.querySelectorAll('.section-title, .content-box, .plant-types, .product-card').forEach((el) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'all 0.5s ease';
        observer.observe(el);
    });

    // Class CSS cho hiệu ứng fade-in
    const style = document.createElement('style');
    style.textContent = `
        .fade-in {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }
    `;
    document.head.appendChild(style);
});
</script>

</body>
</html> 