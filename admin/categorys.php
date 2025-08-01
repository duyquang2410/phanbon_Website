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

<?php include "head.php"; ?>

<body class="g-sidenav-show  bg-gray-200">

<?php
    $active = 'dm'; 
    require 'aside.php';
  ?>

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
		
		<!-- <div class="position-absolute w-100 min-height-400 top-0" style="background-image: url('https://media-cdn-v2.laodong.vn/storage/newsportal/2022/9/21/1095693/Screen-Shot-2022-09-.jpg?w=660'); background-position-y: 100%;">
    	<span class="mask bg-primary opacity-5"></span>
  	</div> -->


			<div class="container-fluid py-1 px-3">
				<!-- Ten page -->
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Danh mục</li>
          </ol>
          <h6 class="font-weight-bolder mb-0">Danh mục</h6>
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
		<?php
			require 'connect.php';
		?>
		<div class="container-fluid py-4">
      <div class="row">
      	<div class="col-8">
      		<div class="card mb-4">
            <div class="card-header pb-2">
              <h6>Danh sách danh mục sản phẩm</h6>         
						</div>
						
            <div class="card-body px-3 pt-0 pb-2">
						<?php
              $sql = "select * from danh_muc";
            ?>
              <div class="table-responsive p-0">      
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-1">Mã</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tên</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
                    </tr>
                  </thead>
                	<tbody>
                            
                  <?php
                      $result = $conn->query($sql);
                      if ($result->num_rows > 0) {
                      $result = $conn->query($sql);
                      $result_all = $result -> fetch_all(MYSQLI_ASSOC);
                      foreach ($result_all as $row) {
                  ?>
                    <tr class="height-100">
                      <!-- ma sp -->
                      <td>
                        <p class="text-xs font-weight-bold mb-0"><?php echo $row["DM_MA"]; ?></p>
                      </td>
											<td>
												<div class="d-flex px-2 py-1">
                         

                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-md"><?php echo $row["DM_TEN"]; ?></h6>
                            
                          </div>
                        </div>                       
                      </td>
                                  
											<td>
                        <div>
                          <button data-id="<?php echo $row["DM_MA"]; ?>" data-name="<?php echo $row["DM_TEN"]; ?>" data-img="<?php echo $avatar_url; ?>" class="addmore-button btn btn-link text-success text-secondary font-weight-bold text-sm">
                            Sửa              
                          </button>
                        </div></td>
                        <td>
                        <div>
                          <form method="post" action="del_categorys.php">
                            <input type="hidden" name="iddm" value="<?php echo $row["DM_MA"]; ?>">
                            <button onclick="this.form.submit()" class="addmore-button btn btn-link text-warning text-secondary font-weight-bold text-sm">
                              Xóa
                            </button>
                          </form>
                        </div>
                      </td>
                      	
                    </tr>
                  <?php
											}
                  }
                  ?>
       
              		</tbody>
              	</table>
            	</div>
          	</div>
        	</div>
      	</div>
				<div class="col-4">
      		<div class="card mb-4">
            <div class="card-header pb-2">
              <h6 style="padding-left:60px;">Thêm danh mục mới</h6>         
						</div>
						
            <div class="card-body px-3 pt-0 pb-2">
							<form role="form" method="post" action="add_categorys.php" enctype="multipart/form-data">
								<div class="row">
									<div class="col-md-12">
										Tên danh mục
										<div class="input-group input-group-lg input-group-outline my-3">
											<input required type="text" name="ten" class="form-control" placeholder="Nhập tên danh mục">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										Tải ảnh danh mục
										<div class="mb-3 px-3 col-12">
                      <br>
                      <input class="mt-3" type="file" name="productImg" id="productImg" accept="image/*">
                    </div>
                    <div class="mb-3 px-3 col-3">
                        <div id="preview"></div>
                          <script>
                            var input = document.getElementById("productImg");
                            var preview = document.getElementById("preview");

                            input.addEventListener("change", function() {
                              preview.innerHTML = ""; // clear previous preview
                              var files = this.files;
                              for (var i = 0; i < files.length; i++) {
                                var file = files[i];
                                if (!file.type.startsWith("image/")){ continue } // skip non-image files
                                var reader = new FileReader();
                                reader.onload = function(e) {
                                  var img = document.createElement("img");
                                  img.src = e.target.result;
                                  img.width = 1000; // set width for preview images
                                  img.className = "avatar avatar-xxl me-3";
                                  preview.appendChild(img); // append image to preview div
                                };
                                reader.readAsDataURL(file); // read file as data url
                              }
                            });
                          </script>
                      </div>
								</div>
								<div class="row">
									<div class="col-md-12 px-8">
										<button type="submit" class="btn btn-primary mt-2" >Thêm</button>
									</div>
								</div>
							</form>
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
                <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Creative Tim</a>
                for a better web.
              </div>
            </div>
            <div class="col-lg-6">
              <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                <li class="nav-item">
                  <a href="https://www.creative-tim.com" class="nav-link text-muted" target="_blank">Creative Tim</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/presentation" class="nav-link text-muted" target="_blank">About Us</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/blog" class="nav-link text-muted" target="_blank">Blog</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/license" class="nav-link pe-0 text-muted" target="_blank">License</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </footer>
    </div>
	</main>
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
      width: 30%;
      height: 70%;
      background-color: #fff;
      border-radius: 10px;
      position: absolute;
      padding: 15px;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }
  </style>
  
  <div class="overlay" id="overlay">
    <div class="my-box">
      <h5 class="ms-3 mt-3 text-primary">Cập nhật danh mục</h5>
      <div class="row">
        <div class="col-12">
          <form action="update_categorys.php" method="post">
            <div class="row">
              <div class="col-12">

                <input type="hidden" name="temp_id" id="temp_id">
                <div class="px-3 col-lg-12 input-group input-group-outline product-name">
                  
                </div>
              </div>
              <div class="col-12">
                <div class="mb-3 mt-4 px-3 product-image">
                  
                  <input type="hidden" name="productImg" id="productImg">
                    
                    
                </div>
              </div>
            </div>
              <div class="row">
                <div class="col-12 d-flex justify-content-center align-items-center" >
                  <button onclick="this.submit()" class="btn btn-primary text-white font-weight-bold text-md ms-0 mt-4">
                    Cập nhật
                  </button>
                </div>
              </div>
            </div>
          </form>
      </div>
    </div>
  </div>
      
	<!--   Core JS Files   -->
  <script>

const productButtons = document.querySelectorAll('.addmore-button');

productButtons.forEach(button => {
  button.addEventListener('click', showProductDetails);
});

function showProductDetails(event) {
  // Lấy ID của sản phẩm được click
  const productId = event.target.getAttribute('data-id');
  const product_img = event.target.getAttribute('data-img');
  const name = event.target.getAttribute('data-name');
  
  
  document.getElementById("temp_id").value = productId;

  // Hiển thị overlay
  const overlay = document.querySelector('.overlay');
  overlay.style.display = 'block';

  // Hiển thị thông tin chi tiết của sản phẩm
  const productName = document.querySelector('.product-name');
  productName.innerHTML = '<div class="px-2 col-lg-12">Tên danh mục <input required value="' + name + '" type="text" name="name" class="form-control form-control-lg"></div>';
  const productImg = document.querySelector('.product-image');
  productImg.innerHTML = '<div class="px-2 col-lg-12">Hình ảnh</div><div class="row col-lg-6"><img src="' + product_img + '" class="avatar avatar-xxl" alt="product"></div> <div class="mb-3 px-3 col-12"><br><input class="mt-3" type="file" name="productImg" id="productImg" accept="image/*"></div>';
  
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
