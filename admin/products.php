<!--
=========================================================
* Material Dashboard 2 - v3.1.0
=========================================================

* Product Page: https://www.creative-tim.com/product/material-dashboard
* Copyright 2023 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<?php
  session_start();
?>
<?php
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'delete_success') {
        echo "<script>alert('✅ Xóa sản phẩm thành công!');</script>";
    } elseif ($_GET['action'] == 'delete_error') {
        $msg = isset($_GET['msg']) ? urldecode($_GET['msg']) : 'Đã xảy ra lỗi.';
        echo "<script>alert('❌ Lỗi khi xóa sản phẩm: $msg');</script>";
    }
}
?>

<?php include "head.php"; ?>

<body class="g-sidenav-show  bg-gray-200">

    <?php
    $active = 'sp'; 
    require 'aside.php';
  ?>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
            data-scroll="true">

            <!-- <div class="position-absolute w-100 min-height-400 top-0" style="background-image: url('https://media-cdn-v2.laodong.vn/storage/newsportal/2022/9/21/1095693/Screen-Shot-2022-09-.jpg?w=660'); background-position-y: 100%;">
    	<span class="mask bg-primary opacity-5"></span>
  	</div> -->


            <div class="container-fluid py-1 px-3">
                <!-- Ten page -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a>
                        </li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Sản phẩm</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">Sản phẩm</h6>
                </nav>
                <!-- search -->
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                        <div class="input-group input-group-outline">
                            <label class="form-label">Type here...</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>

                    <ul class="navbar-nav  justify-content-end">
                        <li class="nav-item d-flex align-items-center">
                            <div class="d-flex align-items-center">
                                <img src="../asset_admin/img/staff_img/team-4.jpg" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
                                <div class="ms-3">
                                    <span class="text-muted text-xs mb-0">Xin chào,</span>
                                    <h6 class="mb-0 text-sm"><?php echo $_SESSION["NV_TEN"]; ?></h6>
                                </div>
                                <a href="log_out.php" class="btn btn-link text-dark px-3 mb-0">
                                    <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                                </a>
                            </div>
                        </li>

                        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                                <div class="sidenav-toggler-inner">
                                    <i class="sidenav-toggler-line bg-white"></i>
                                    <i class="sidenav-toggler-line bg-white"></i>
                                    <i class="sidenav-toggler-line bg-white"></i>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- End Navbar -->

        <div class="container-fluid py-4">
            <div class="row">
                <?php
					require 'connect.php';
				?>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="row px-2 bg-dark-light bg-gray">
                            <form action="#" method="get">
                                <div class="px-3 col-12 pb-2 d-flex align-items-center">
                                    <div class="col-1 mt-2 font-weight-bold d-flex align-items-center">
                                        Lọc:
                                    </div>
                                    <div class="input-group input-group-outline my-2">
                                        <br>
                                        <select class="form-control form-control-md" name="source" id="source">
                                            <option value="" selected disabled hidden>- Nguồn hàng -</option>
                                            <?php
                                            $sql = "SELECT * FROM nguon_hang";
                                            $result = $conn->query($sql);
                                            if ($result->num_rows > 0) {
                                                $result_all = $result->fetch_all(MYSQLI_ASSOC);
                                                foreach ($result_all as $row) {
                                                    echo "<option value='" . $row["NH_MA"] . "'" .
                                                        (isset($_GET['source']) && $_GET['source'] == $row["NH_MA"] ? " selected" : "") .
                                                        ">" . $row["NH_TEN"] . "</option>";
                                                }
                                            } else {
                                                echo "<option value=''>Không có dữ liệu</option>";
                                            }
                                        ?>
                                        </select>
                                    </div>
                                    <div class="px-1 mt-1 col-1 font-weight-bold body">
                                        <button type="submit"
                                            class="btn btn-primary text-white font-weight-bold text-md ms-0 mt-3">
                                            Lọc
                                        </button>
                                    </div>
                                    <div class="px-2 mt-n3 col-2"></div>
                                    <div class="px-2 mt-n3 col-1 font-weight-bold"></div>
                                    <div class="col-5 mt-2 d-flex align-items-center justify-content-end">
                                        <div class="input-group input-group-outline w-70 me-3">
                                            <span class="input-group-text text-body"><i class="fas fa-search"
                                                    aria-hidden="true"></i></span>
                                            <input type="text" name="timkiem" class="form-control"
                                                placeholder="Nhập tên sản phẩm cần tìm..">
                                        </div>
                                        <button type="submit"
                                            class="btn btn-primary text-white font-weight-bold text-md ms-0 mt-3">
                                            Tìm
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
                <a href="add_products.php" class="btn btn-link text-red text-uppercase mt-n3">+ Thêm sản phẩm mới</a>
            </div>

            <?php
$sql = "SELECT * FROM DANH_MUC";
$result = $conn->query($sql);

if ($result === false) {
    echo "Lỗi truy vấn: " . $conn->error;
    exit;
}

if ($result->num_rows > 0) {
    $result_all = $result->fetch_all(MYSQLI_ASSOC);
    foreach ($result_all as $rowdm) {
        $dmid = $rowdm["DM_MA"];
        
        // Truy vấn cơ bản với join tới chitiet_pn để lấy NH_MA
        // SELECT DISTINCT sp.*  nghĩa là "chọn tất cả các cột từ bảng san_pham (sp), nhưng chỉ lấy các hàng duy nhất, không trùng lặp".
        $sql = "SELECT DISTINCT sp.* 
                FROM san_pham sp 
                LEFT JOIN chitiet_pn ct ON sp.SP_MA = ct.SP_MA 
                WHERE sp.DM_MA = {$dmid}";

        // Thêm điều kiện lọc theo nguồn hàng
        if (isset($_GET["source"]) && !empty($_GET["source"])) {
            $source = $conn->real_escape_string($_GET["source"]);
            $sql .= " AND ct.NH_MA = '$source'";
        }

        // Thêm điều kiện tìm kiếm
        if (isset($_GET["timkiem"]) && !empty($_GET["timkiem"])) {
            $search = $conn->real_escape_string($_GET["timkiem"]);
            $sql .= " AND sp.SP_TEN LIKE '%$search%'";
        }

        $sql .= " ORDER BY sp.SP_MA DESC";

        // Kiểm tra lỗi truy vấn
        $result = $conn->query($sql);
        if ($result === false) {
            echo "Lỗi truy vấn sản phẩm: " . $conn->error;
            exit;
        }
?>
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-2">
                            <?php echo "<h6>" . $rowdm["DM_TEN"] . "</h6>"; ?>
                        </div>
                        <div class="card-body px-4 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-1">
                                                Mã</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Sản phẩm</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Số lượng</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Giá</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Mô tả</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Ngày nhập</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                $result_all = $result->fetch_all(MYSQLI_ASSOC);
                                foreach ($result_all as $row) {
                                  $pdid = $row["SP_MA"];
                                  $soluong = $row["SP_SOLUONGTON"];
                                  $dmsp = $rowdm["DM_TEN"];
                                  $mdmsp = $rowdm["DM_MA"];
                              
                                  $query = "SELECT pn.PN_NGAYNHAP as ngaynhap, pn.PN_STT as stt_pn, nh.NH_MA as manh, nh.NH_TEN as tennh
                                            FROM chitiet_pn ct
                                            JOIN phieu_nhap pn ON ct.PN_STT = pn.PN_STT
                                            JOIN san_pham sp ON ct.SP_MA = sp.SP_MA
                                            JOIN nguon_hang nh ON ct.NH_MA = nh.NH_MA
                                            WHERE sp.SP_MA = {$pdid}";
                                  
                                  $rs = $conn->query($query);
                                  $row1 = mysqli_fetch_assoc($rs);
                              
                                  // Kiểm tra và gán giá trị mặc định
                                  if ($row1 && isset($row1["ngaynhap"]) && strtotime($row1["ngaynhap"]) !== false) {
                                      $ngaynhap = date('d/m/Y', strtotime($row1["ngaynhap"]));
                                      $stt_pn = $row1["stt_pn"];
                                      $tennh = $row1["tennh"];
                                      $manh = $row1["manh"];
                                  } else {
                                      $ngaynhap = 'Chưa nhập';
                                      $stt_pn = '';
                                      $tennh = 'Chưa có nguồn hàng';
                                      $manh = '';
                                  }
                              ?>
                                        <tr class="height-100">
                                            <!-- mã sp -->
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?php echo $row["SP_MA"]; ?>
                                                </p>
                                            </td>
                                            <td class="w-30">
                                                <div class="d-flex px-2 py-1">
                                                    <!-- hình ảnh sản phẩm -->
                                                    <div>
                                                        <?php
                                              if ($row["SP_HINHANH"] == null) {
                                                  $file = "default.jpg";
                                              } else {
                                                  $file = $row["SP_HINHANH"];
                                              }
                                              $avatar_url = "../img/" . $file;
                                              echo "<img src='{$avatar_url}' class='avatar avatar-xl me-3' alt='user1'>";
                                              ?>
                                                    </div>
                                                    <!-- tên sản phẩm -->
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-md"><?php echo $row["SP_TEN"]; ?></h6>
                                                        <p class='text-xs text-secondary mb-0'>
                                                            <?php echo "Nguồn hàng: " . $tennh; ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <!-- số lượng sp -->
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    <?php echo $row["SP_SOLUONGTON"]; ?></p>
                                            </td>
                                            <!-- giá sp -->
                                            <td>
                                                <p class="text-s font-weight-bold mb-0">
                                                    <?php echo number_format($row["SP_DONGIA"], 0); ?> VNĐ</p>
                                            </td>
                                            <!-- mô tả -->
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?php echo $row["SP_MOTA"]; ?>
                                                </p>
                                            </td>
                                            <!-- ngày nhập -->
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?php echo $ngaynhap; ?></p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <!-- Các nút hành động -->
                                                <div class="mt-3 d-flex col-sm-12">
                                                    <div class="me-n1 align-middle col-4">
                                                        <button data-id="<?php echo $row["SP_MA"]; ?>"
                                                            data-name="<?php echo $row["SP_TEN"]; ?>"
                                                            data-img="<?php echo $avatar_url; ?>"
                                                            class="addmore-button btn btn-link text-success text-secondary font-weight-bold text-sm">
                                                            Nhập thêm
                                                        </button>
                                                    </div>
                                                    <div class="me-n0 align-middle col-4">
    <form method="post" action="edit_products.php">
        <input type="hidden" name="pdid" value="<?php echo $row["SP_MA"]; ?>">
        <input type="hidden" name="tensp" value="<?php echo $row["SP_TEN"]; ?>">
        <input type="hidden" name="tennh" value="<?php echo $tennh; ?>">
        <input type="hidden" name="manh" value="<?php echo $manh; ?>">
        <input type="hidden" name="dmsp" value="<?php echo $dmsp; ?>">
        <input type="hidden" name="mdmsp" value="<?php echo $mdmsp; ?>">
        <input type="hidden" name="stt_pn" value="<?php echo $stt_pn; ?>">
        <input type="hidden" name="mota" value="<?php echo $row["SP_MOTA"]; ?>">
        <input type="hidden" name="anhsp" value="<?php echo $row["SP_HINHANH"]; ?>">
        <input type="hidden" name="slsp" value="<?php echo $row["SP_SOLUONGTON"]; ?>">
        <input type="hidden" name="giasp" value="<?php echo $row["SP_DONGIA"]; ?>">
        <button type="submit" name="submit" value="edit" class="btn btn-link text-primary font-weight-bold text-sm">
            Sửa
        </button>
    </form>
</div>
                                                    <div class="align-middle col-1">
                                                        <form method="post" action="del_products.php">
                                                            <input type="hidden" name="pdid"
                                                                value="<?php echo $row["SP_MA"]; ?>">
                                                            <button type="submit"
                                                                class="addmore-button btn btn-link text-warning text-secondary font-weight-bold text-sm"
                                                                onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?');">
                                                                Xóa
                                                            </button>
                                                        </form>
                                                    </div>

                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                              }
                            } else {
                                echo "<tr><td colspan='7'>Không có sản phẩm nào</td></tr>";
                            }
                            ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
    }
} else {
    echo "<div>Không có danh mục nào</div>";
}
?>

            <div class="overlay" id="overlay">
                <div class="my-box">
                    <h5 class="ms-3 mt-3 text-primary">Nhập thêm sản phẩm</h5>
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-6 d-flex justify-content-center align-items-center">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="product-image">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="ms-2 product-name">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <form action="update_quantity.php" method="post">
                                        <div class="mb-3 mt-4 px-3">
                                            Số lượng nhập thêm
                                            <input type="hidden" name="temp_id" id="temp_id">
                                            <input min="1" max="10000" step="1" type="number" name="quantity"
                                                class="form-control form-control-lg mt-3"
                                                placeholder="Nhập số lượng sản phẩm nhập thêm">
                                            <div class="row">
                                                <div class="col-12 d-flex justify-content-center align-items-center">
                                                    <button type="submit"
                                                        class="btn btn-primary text-white font-weight-bold text-md ms-0 mt-4">
                                                        Cập nhật
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <hr class="horizontal dark my-1">
        <div class="card-body pt-sm-3 pt-0 overflow-auto">


            <footer class="footer py-4  ">
                <div class="container-fluid">
                    <div class="row align-items-center justify-content-lg-between">
                        <div class="col-lg-6 mb-lg-0 mb-4">
                            <div class="copyright text-center text-sm text-muted text-lg-start">
                                © <script>
                                document.write(new Date().getFullYear())
                                </script>,
                                made with <i class="fa fa-heart"></i> by
                                <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Creative
                                    Tim</a>
                                for a better web.
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                                <li class="nav-item">
                                    <a href="https://www.creative-tim.com" class="nav-link text-muted"
                                        target="_blank">Creative Tim</a>
                                </li>
                                <li class="nav-item">
                                    <a href="https://www.creative-tim.com/presentation" class="nav-link text-muted"
                                        target="_blank">About Us</a>
                                </li>
                                <li class="nav-item">
                                    <a href="https://www.creative-tim.com/blog" class="nav-link text-muted"
                                        target="_blank">Blog</a>
                                </li>
                                <li class="nav-item">
                                    <a href="https://www.creative-tim.com/license" class="nav-link pe-0 text-muted"
                                        target="_blank">License</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </main>
    <!--   Core JS Files   -->
    <style>
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 99999;
        background: rgba(0, 0, 0, 0.5);
        display: none;
    }

    .my-box {
        width: 50%;
        height: 50%;
        background-color: #fff;
        border-radius: 10px;
        position: absolute;
        padding: 15px;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    </style>
    <script>
    const productButtons = document.querySelectorAll('.addmore-button');

    productButtons.forEach(button => {
        button.addEventListener('click', showProductDetails);
    });

    function showProductDetails(event) {
        // Lấy ID của sản phẩm được click
        const productId = event.target.getAttribute('data-id');
        const product_img = event.target.getAttribute('data-img');
        const product_name = event.target.getAttribute('data-name');


        document.getElementById("temp_id").value = productId;

        // Hiển thị overlay
        const overlay = document.querySelector('.overlay');
        overlay.style.display = 'block';

        // Hiển thị thông tin chi tiết của sản phẩm
        const productName = document.querySelector('.product-name');
        productName.innerHTML = '<h6>' + product_name + '</h6>';
        const productImg = document.querySelector('.product-image');
        productImg.innerHTML = '<img src="' + product_img + '" class="avatar avatar-xxl" alt="product">';

    }


    //Tắt overlay
    const overlay = document.getElementById("overlay");
    overlay.addEventListener("click", function(event) {
        if (event.target === overlay) {
            overlay.style.display = "none";
        }
    });
    </script>
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/chartjs.min.js"></script>
    <script>
    var ctx = document.getElementById("chart-bars").getContext("2d");

    new Chart(ctx, {
        type: "bar",
        data: {
            labels: ["M", "T", "W", "T", "F", "S", "S"],
            datasets: [{
                label: "Sales",
                tension: 0.4,
                borderWidth: 0,
                borderRadius: 4,
                borderSkipped: false,
                backgroundColor: "rgba(255, 255, 255, .8)",
                data: [50, 20, 10, 22, 50, 10, 40],
                maxBarThickness: 6
            }, ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
            scales: {
                y: {
                    grid: {
                        drawBorder: false,
                        display: true,
                        drawOnChartArea: true,
                        drawTicks: false,
                        borderDash: [5, 5],
                        color: 'rgba(255, 255, 255, .2)'
                    },
                    ticks: {
                        suggestedMin: 0,
                        suggestedMax: 500,
                        beginAtZero: true,
                        padding: 10,
                        font: {
                            size: 14,
                            weight: 300,
                            family: "Roboto",
                            style: 'normal',
                            lineHeight: 2
                        },
                        color: "#fff"
                    },
                },
                x: {
                    grid: {
                        drawBorder: false,
                        display: true,
                        drawOnChartArea: true,
                        drawTicks: false,
                        borderDash: [5, 5],
                        color: 'rgba(255, 255, 255, .2)'
                    },
                    ticks: {
                        display: true,
                        color: '#f8f9fa',
                        padding: 10,
                        font: {
                            size: 14,
                            weight: 300,
                            family: "Roboto",
                            style: 'normal',
                            lineHeight: 2
                        },
                    }
                },
            },
        },
    });


    var ctx2 = document.getElementById("chart-line").getContext("2d");

    new Chart(ctx2, {
        type: "line",
        data: {
            labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
                label: "Mobile apps",
                tension: 0,
                borderWidth: 0,
                pointRadius: 5,
                pointBackgroundColor: "rgba(255, 255, 255, .8)",
                pointBorderColor: "transparent",
                borderColor: "rgba(255, 255, 255, .8)",
                borderColor: "rgba(255, 255, 255, .8)",
                borderWidth: 4,
                backgroundColor: "transparent",
                fill: true,
                data: [50, 40, 300, 320, 500, 350, 200, 230, 500],
                maxBarThickness: 6

            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
            scales: {
                y: {
                    grid: {
                        drawBorder: false,
                        display: true,
                        drawOnChartArea: true,
                        drawTicks: false,
                        borderDash: [5, 5],
                        color: 'rgba(255, 255, 255, .2)'
                    },
                    ticks: {
                        display: true,
                        color: '#f8f9fa',
                        padding: 10,
                        font: {
                            size: 14,
                            weight: 300,
                            family: "Roboto",
                            style: 'normal',
                            lineHeight: 2
                        },
                    }
                },
                x: {
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false,
                        borderDash: [5, 5]
                    },
                    ticks: {
                        display: true,
                        color: '#f8f9fa',
                        padding: 10,
                        font: {
                            size: 14,
                            weight: 300,
                            family: "Roboto",
                            style: 'normal',
                            lineHeight: 2
                        },
                    }
                },
            },
        },
    });

    var ctx3 = document.getElementById("chart-line-tasks").getContext("2d");

    new Chart(ctx3, {
        type: "line",
        data: {
            labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
                label: "Mobile apps",
                tension: 0,
                borderWidth: 0,
                pointRadius: 5,
                pointBackgroundColor: "rgba(255, 255, 255, .8)",
                pointBorderColor: "transparent",
                borderColor: "rgba(255, 255, 255, .8)",
                borderWidth: 4,
                backgroundColor: "transparent",
                fill: true,
                data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
                maxBarThickness: 6

            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
            scales: {
                y: {
                    grid: {
                        drawBorder: false,
                        display: true,
                        drawOnChartArea: true,
                        drawTicks: false,
                        borderDash: [5, 5],
                        color: 'rgba(255, 255, 255, .2)'
                    },
                    ticks: {
                        display: true,
                        padding: 10,
                        color: '#f8f9fa',
                        font: {
                            size: 14,
                            weight: 300,
                            family: "Roboto",
                            style: 'normal',
                            lineHeight: 2
                        },
                    }
                },
                x: {
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false,
                        borderDash: [5, 5]
                    },
                    ticks: {
                        display: true,
                        color: '#f8f9fa',
                        padding: 10,
                        font: {
                            size: 14,
                            weight: 300,
                            family: "Roboto",
                            style: 'normal',
                            lineHeight: 2
                        },
                    }
                },
            },
        },
    });
    </script>
    <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="../assets/js/material-dashboard.min.js?v=3.1.0"></script>
    <?php 
    $conn->close();
  ?>
</body>

</html>