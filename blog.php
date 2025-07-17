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

include_once 'header.php';
include_once 'connect.php';

// Thêm CSS
echo '<link rel="stylesheet" href="asset_user/css/blog.css">';

// Lấy danh sách các loại cây trồng
$query_cay_trong = "SELECT * FROM loai_cay_trong";
$result_cay_trong = $conn->query($query_cay_trong);

// Lấy danh sách các loại bệnh và thông tin liên quan
$query_benh = "
    SELECT lb.*, lct.LCT_TEN, sp.SP_MA, sp.SP_TEN, sp.SP_MOTA, sp.SP_HINHANH
    FROM loai_benh lb
    LEFT JOIN san_pham_cay_trong spct ON lb.LCT_MA = spct.LCT_MA
    LEFT JOIN loai_cay_trong lct ON lb.LCT_MA = lct.LCT_MA
    LEFT JOIN san_pham sp ON spct.SP_MA = sp.SP_MA
    ORDER BY lb.Ma_loai_benh
";
$result_benh = $conn->query($query_benh);

if (!$result_benh) {
    die("Lỗi truy vấn: " . $conn->error);
}

// Tạo mảng để lưu trữ thông tin bệnh theo cây trồng
$benh_theo_cay = array();
while ($row = $result_benh->fetch_assoc()) {
    if (!isset($benh_theo_cay[$row['Ma_loai_benh']])) {
        $benh_theo_cay[$row['Ma_loai_benh']] = array(
            'ten_benh' => $row['Ten_loai_benh'],
            'mo_ta' => $row['mo_ta'],
            'hinh_anh' => $row['hinh_anh'],
            'cach_phong_ngua' => $row['cach_phong_ngua'],
            'cay_trong' => array(),
            'san_pham' => array()
        );
    }
    
    if ($row['LCT_TEN'] && !in_array($row['LCT_TEN'], $benh_theo_cay[$row['Ma_loai_benh']]['cay_trong'])) {
        $benh_theo_cay[$row['Ma_loai_benh']]['cay_trong'][] = $row['LCT_TEN'];
    }
    
    if ($row['SP_MA'] && !isset($benh_theo_cay[$row['Ma_loai_benh']]['san_pham'][$row['SP_MA']])) {
        $benh_theo_cay[$row['Ma_loai_benh']]['san_pham'][$row['SP_MA']] = array(
            'ten' => $row['SP_TEN'],
            'mo_ta' => $row['SP_MOTA'],
            'hinh_anh' => $row['SP_HINHANH']
        );
    }
}
?>

<main class="blog-page py-5">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Trang chủ</a></li>
                <li class="breadcrumb-item active">Cẩm nang bệnh hại cây trồng</li>
            </ol>
        </nav>

        <!-- Page Title -->
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold text-success">CÁC LOẠI BỆNH HẠI TRÊN CÂY TRỒNG</h1>
            <p class="lead text-muted">Thông tin chi tiết về các loại bệnh thường gặp và cách phòng ngừa hiệu quả</p>
        </div>

        <!-- Disease Grid -->
        <div class="row g-4">
            <?php if (!empty($benh_theo_cay)): ?>
                <?php foreach ($benh_theo_cay as $ma_benh => $benh): ?>
                    <div class="col-md-6">
                        <article class="disease-card h-100 bg-white rounded-3 shadow-sm overflow-hidden">
                            <?php if ($benh['hinh_anh']): ?>
                                <div class="disease-image-wrapper">
                                    <img src="img/benh/<?php echo htmlspecialchars($benh['hinh_anh']); ?>" 
                                         class="disease-image w-100" 
                                         alt="<?php echo htmlspecialchars($benh['ten_benh']); ?>">
                                </div>
                            <?php endif; ?>
                            
                            <div class="disease-content p-4">
                                <h2 class="h4 mb-3 text-success">
                                    <?php echo htmlspecialchars($benh['ten_benh']); ?>
                                </h2>
                                
                                <div class="mb-4">
                                    <h3 class="h6 fw-bold mb-2">Dấu hiệu nhận biết:</h3>
                                    <p class="text-muted mb-0"><?php 
                                        $mo_ta_ngan = strlen($benh['mo_ta']) > 150 ? 
                                            substr($benh['mo_ta'], 0, 150) . '...' : 
                                            $benh['mo_ta'];
                                        echo nl2br(htmlspecialchars($mo_ta_ngan)); 
                                    ?></p>
                                </div>

                                <?php if (!empty($benh['cay_trong'])): ?>
                                    <div class="affected-plants">
                                        <h3 class="h6 fw-bold mb-2">Cây trồng thường bị ảnh hưởng:</h3>
                                        <div class="plant-tags">
                                            <?php foreach ($benh['cay_trong'] as $cay): ?>
                                                <span class="badge bg-success-subtle text-success me-2 mb-2 px-3 py-2 rounded-pill">
                                                    <?php echo htmlspecialchars($cay); ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="mt-4 text-end">
                                    <a href="disease_detail.php?id=<?php echo $ma_benh; ?>" 
                                       class="btn btn-outline-success rounded-pill px-4">
                                        Xem chi tiết
                                        <i class="fa fa-arrow-right ms-2"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">
                        Chưa có thông tin về bệnh cây trồng. Vui lòng thêm dữ liệu vào cơ sở dữ liệu.
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Additional Information -->
        <section class="prevention-tips mt-5 p-5 bg-light rounded-3">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="h3 fw-bold text-success mb-4">
                        Cách phòng ngừa các loại bệnh hại cây trồng hiệu quả
                    </h2>
                    <div class="prevention-list">
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge bg-success rounded-circle p-2 me-3">
                                <i class="fa fa-check"></i>
                            </span>
                            <p class="mb-0">Chọn giống cây khỏe mạnh, có sức đề kháng tốt</p>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge bg-success rounded-circle p-2 me-3">
                                <i class="fa fa-check"></i>
                            </span>
                            <p class="mb-0">Thực hiện đúng quy trình chăm sóc và bón phân</p>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge bg-success rounded-circle p-2 me-3">
                                <i class="fa fa-check"></i>
                            </span>
                            <p class="mb-0">Thường xuyên kiểm tra và phát hiện bệnh sớm</p>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge bg-success rounded-circle p-2 me-3">
                                <i class="fa fa-check"></i>
                            </span>
                            <p class="mb-0">Sử dụng thuốc phòng trừ bệnh đúng cách và đúng liều lượng</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-success rounded-circle p-2 me-3">
                                <i class="fa fa-check"></i>
                            </span>
                            <p class="mb-0">Vệ sinh vườn sạch sẽ, loại bỏ cây bệnh kịp thời</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <img src="img/prevention-tips.jpg" alt="Prevention Tips" class="img-fluid rounded-3">
                </div>
            </div>
        </section>
    </div>
</main>

<?php include_once 'footer.php'; ?>

<script>
// Xóa toàn bộ code liên quan đến scroll
document.addEventListener('DOMContentLoaded', function() {
    // Chỉ giữ lại các chức năng cần thiết khác nếu có
});
</script>

</body>
</html>