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
  require 'connect.php';
?>

<?php include "head.php"; ?>

<body class="g-sidenav-show  bg-gray-200">

<?php
    $active = 'nv'; 
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
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Nhân viên</li>
          </ol>
          <h6 class="font-weight-bolder mb-0">Nhân viên</h6>
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
                  <div class="px-2 mt-n3 col-2 font-weight-bold">
                    <br>
                    <select class="form-control form-control-md" name="source" id="source">
                      <option value="" selected disabled hidden>-- Chức vụ --</option>
                      <?php
                        $sql = "SELECT * FROM chuc_vu";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                          $result = $conn->query($sql);
                          $result_all = $result -> fetch_all(MYSQLI_ASSOC);
                          foreach ($result_all as $row) {
                            echo "<option value=" .$row["CV_MA"]. ">".$row["CV_TEN"]. "</option>";
                          }                          
                        } else {
                          echo "<option value=''>Không có dữ liệu</option>";
                        }
                      ?>
                    </select>
                  </div>
                  <div class="px-1 mt-1 col-1 font-weight-bold body">
                    <button type="submit" class="btn btn-primary text-white font-weight-bold text-md ms-0 mt-3">
                      Lọc
                    </button>
                  </div>
                  <div class="px-2 mt-n3 col-2"></div>
                  <div class="px-2 mt-n3 col-1 font-weight-bold"></div>
                  <div class="col-5 mt-2 d-flex align-items-center justify-content-end">
                    <div class="input-group input-group-outline w-70 me-3">
                      <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                      <input  type="text" name="timkiem" class="form-control" placeholder="Nhập tên nhân viên cần tìm..">
                    </div>
                    <button type="submit" class="btn btn-primary text-white font-weight-bold text-md ms-0 mt-3">
                      Tìm
                    </button>
                  </div>
                </div>
              </form>
              
            </div>
          </div>
        </div>
        <a href="add_staffs.php" class="btn btn-link text-red text-uppercase mt-n3">+ Thêm nhân viên mới</a>
      </div>
        
        <?php
          $sql = "SELECT * FROM NHAN_VIEN";
                if(isset($_GET["timkiem"])){
                  $search = $_GET["timkiem"];
                  if ($search != null) {
                    $sql = "SELECT * FROM nhan_vien WHERE NV_TEN LIKE '%".$search."%';";
                  }
                }
        ?>
              <div class="row"> 
                <div class="col-12">
                  <div class="card mb-4"> 
                    <div class="card-body px-4 pt-0 pb-2">
                      <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                          <thead>
                            <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-1">Mã</th>
                              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nhân viên</th>
                              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Chức vụ</th>
                              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Số điện thoại</th>
                              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
                              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Địa chỉ</th>
                              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ngày tuyển</th>
                              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Hành động</th>
                            </tr>
                          </thead>
                          <tbody>
                            
                            <?php
                              
                              $result = $conn->query($sql);
                              if ($result->num_rows > 0) {
                                $result = $conn->query($sql);
                                $result_all = $result -> fetch_all(MYSQLI_ASSOC);
                                foreach ($result_all as $row) {
                                  $nvid = $row["NV_MA"];
                                  ?>
                                  <tr class="height-100">
                                     <!-- ma sp -->
                                     <td>
                                      <p class="text-xs font-weight-bold mb-0"><?php echo $row["NV_MA"]; ?></p>
                                    </td>
                                  
                                    <td class="w-30" >
                                      <div class="d-flex px-2 py-1">
                                          <!-- hinh anh san pham -->
                                        <div>
                                          <?php
                                            if($row["NV_AVATAR"]==null){
                                              $file = "macdinh.jpg";
                                            } else {
                                              $file = $row["NV_AVATAR"];
                                            } 
                                            $avatar_url = "../assets/img/staff_img/" . $file;
                                            echo "<img src='{$avatar_url}' class='avatar avatar-xl me-3' alt='user1'>";
                                          ?> 
                                          
                                        </div>
                                        <!-- ten nhan vien -->
                                        <div class="d-flex flex-column justify-content-center">
                                          <h6 class="mb-0 text-md"><?php echo $row["NV_TEN"]; ?></h6>
                                          <p class='text-xs text-secondary mb-0'>Ngày sinh: <?php echo date('d/m/Y', strtotime($row["NV_NGAYSINH"])); ?></p>
                                        </div>
                                      </div>
                                    </td>
                                    <!-- chuc vu -->
                                    <td>
                                        <?php
                                            if($row["CV_MA"] == "2") {
                                              ?>
                                                <p class="text-sm font-weight-bold mb-0">Nhân viên bán hàng</p>
                                              <?php
                                            } else if($row["CV_MA"] == 3) {
                                              ?>
                                                <p class="text-sm font-weight-bold mb-0">Nhân viên kế toán</p>
                                              <?php
                                            } else if($row["CV_MA"] == 4) {
                                              ?>
                                                <p class="text-sm font-weight-bold mb-0">Nhân viên giao hàng</p>
                                              <?php
                                            } else{
                                              ?>
                                                <p class="text-sm font-weight-bold mb-0 text-success">Quản lý</p>
                                              <?php
                                            }
                                          ?>
                                      </td>
                                      <!-- so dien thoai -->
                                      <td>
                                        <p class="text-sm font-weight-bold mb-0"><?php echo $row["NV_SDT"]; ?></p>
                                      </td>
                                      <!-- email-->
                                      <td>
                                        <p class="text-sm font-weight-bold mb-0"><?php echo $row["NV_EMAIL"]; ?></p>
                                      </td>
                                      <!-- dia chi -->
                                      <td>
                                        <p class="text-sm font-weight-bold mb-0"><?php echo $row["NV_DIACHI"]; ?></p>
                                      </td>
                                      <!-- ngay tuyen -->
                                      <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold"><?php echo date('d/m/Y', strtotime($row["NV_NGAYTUYEN"])); ?></span>
                                      </td>
                                      
                                      <td class="align-middle">
                                      <form method="post" action="edit_staffs.php">
                                          <input type="hidden" name="nvid" value="<?php echo $row["NV_MA"]; ?>">
                                          <button onclick="this.form.submit()" class="mt-3 btn btn-link text-primary font-weight-bold text-sm">
                                            Sửa
                                          </button>
                                        </form>
                                      </td>
                                      <td class="align-middle">
                                        <form method="post" action="del_staffs.php">
                                          <input type="hidden" name="nvid" value="<?php echo $row["NV_MA"]; ?>">
                                          <?php
                                            if($row["NV_MA"] == $_SESSION["nvid"]) {
                                              ?>
                                                <a class="mt-3 ms-n4 text-secondary font-weight-bold text-sm">
                                                  Xoá
                                                </a>
                                              <?php
                                            } else {
                                              ?>
                                                <button onclick="this.form.submit()" class="mt-3 ms-n5 btn btn-link text-warning text-secondary font-weight-bold text-sm">
                                                  Xoá
                                                </button>
                                              <?php
                                            }
                                          ?>
                                        </form>
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
              </div>
            

      </div>




    <!-- End Navbar -->
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
	  <!--   Core JS Files   -->
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
