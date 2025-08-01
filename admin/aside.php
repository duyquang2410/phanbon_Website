<?php
// Khởi tạo các biến menu nếu chưa được set
if (!isset($active)) {
    $active = '';
}

    $db = "";
    $hd = "";
    $dh = "";
    $kh = "";
    $nv = "";
    $tnv = "";
    $nh = "";
    $dvvc = "";
    $dvc = "";
    $sp = "";
    $tsp = "";
    $dg = "";
    $tt = "";
    $tttk = "";
    $dm = "";
    $ddg = "";
$vc = "";
    // Add new variables for inventory management
    $tonkho = "";
    $nhapkho = "";
    $xuatkho = "";
    $kiemkho = "";
    $baocaokho = "";
    $delivery_tracking = ""; // Thêm biến cho menu theo dõi giao hàng

    switch ($active) {
        case 'db':
            $db = "active bg-gradient-primary";
            break;
        case 'hd':
            $hd = "active bg-gradient-primary";
            break;
        case 'dh':
            $dh = "active bg-gradient-primary";
            break;
        case 'kh':
            $kh = "active bg-gradient-primary";
            break;
        case 'nv':
            $nv = "active bg-gradient-primary";
            break;
        case 'tnv':
            $tnv = "active bg-gradient-primary";
            break;
        case 'nh':
            $nh = "active bg-gradient-primary";
            break;
        case 'dvvc':
            $dvvc = "active bg-gradient-primary";
            break;
        case 'dvc':
            $dvc = "active bg-gradient-primary";
            break;
        case 'sp':
            $sp = "active bg-gradient-primary";
            break;
        case 'tsp':
            $tsp = "active bg-gradient-primary";
            break;
        case 'dg':
            $dg = "active bg-gradient-primary";
            break;
        case 'tt':
            $tt = "active bg-gradient-primary";
            break;
        case 'tttk':
            $tttk = "active bg-gradient-primary";
            break;
        case 'dm':
            $dm = "active bg-gradient-primary";
            break;
        case 'ddg':
            $ddg = "active bg-gradient-primary";
            break;
    case 'vc':
        $vc = "active bg-gradient-primary";
        break;
        // Add new cases for inventory management
        case 'tonkho':
            $tonkho = "active bg-gradient-primary";
            break;
        case 'nhapkho':
            $nhapkho = "active bg-gradient-primary";
            break;
        case 'xuatkho':
            $xuatkho = "active bg-gradient-primary";
            break;
        case 'kiemkho':
            $kiemkho = "active bg-gradient-primary";
            break;
        case 'baocaokho':
            $baocaokho = "active bg-gradient-primary";
            break;
        case 'delivery_tracking': // Thêm case cho menu theo dõi giao hàng
            $delivery_tracking = "active bg-gradient-primary";
            break;
    }

    // Kiểm tra quyền người dùng
    $is_admin = isset($_SESSION['NV_QUYEN']) && $_SESSION['NV_QUYEN'] === 'ADMIN';
?>

<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="dashboard.php">
            <img src="../asset_admin/img/logo-ct.png" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold text-white">Phân bón</span>
        </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto max-height-vh-100" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <!-- TỔNG QUAN -->
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">TỔNG QUAN</h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $db; ?>" href="dashboard.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">dashboard</i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white <?php echo $kh; ?>" href="custommer.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">person</i>
                    </div>
                    <span class="nav-link-text ms-1">Khách hàng</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white <?php echo $dh; ?>" href="product_waits.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">shopping_cart</i>
                    </div>
                    <span class="nav-link-text ms-1">Đơn hàng</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white <?php echo $dvc; ?>" href="delivery_orders.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">local_shipping</i>
                    </div>
                    <span class="nav-link-text ms-1">Đơn đang giao</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white <?php echo $delivery_tracking; ?>" href="delivery_tracking.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">track_changes</i>
                    </div>
                    <span class="nav-link-text ms-1">Theo dõi giao hàng</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white <?php echo $hd; ?>" href="billing.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">receipt</i>
                    </div>
                    <span class="nav-link-text ms-1">Hóa đơn</span>
                </a>
            </li>

            <!-- QUẢN LÝ TỒN KHO -->
            <?php if ($is_admin): ?>
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">QUẢN LÝ TỒN KHO</h6>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white <?php echo $tonkho; ?>" href="inventory.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">inventory</i>
                    </div>
                    <span class="nav-link-text ms-1">Tồn kho</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white <?php echo $nhapkho; ?>" href="inventory_in.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">input</i>
                    </div>
                    <span class="nav-link-text ms-1">Nhập kho</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white <?php echo $xuatkho; ?>" href="inventory_out.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">output</i>
                    </div>
                    <span class="nav-link-text ms-1">Xuất kho</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white <?php echo $kiemkho; ?>" href="inventory_check.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">fact_check</i>
                    </div>
                    <span class="nav-link-text ms-1">Kiểm kho</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white <?php echo $baocaokho; ?>" href="inventory_reports.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">assessment</i>
                    </div>
                    <span class="nav-link-text ms-1">Báo cáo kho</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- QUẢN LÝ SẢN PHẨM -->
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">QUẢN LÝ SẢN PHẨM</h6>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white <?php echo $sp; ?>" href="products.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">store</i>
                    </div>
                    <span class="nav-link-text ms-1">Sản phẩm</span>
                </a>
            </li>

            <?php if ($is_admin): ?>
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $tsp; ?>" href="add_products.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">add_shopping_cart</i>
                    </div>
                    <span class="nav-link-text ms-1">Thêm sản phẩm</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white <?php echo $dm; ?>" href="categorys.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">category</i>
                    </div>
                    <span class="nav-link-text ms-1">Danh mục</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white <?php echo $vc; ?>" href="transporters.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">description</i>
                    </div>
                    <span class="nav-link-text ms-1">Đơn vận chuyển</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- QUẢN LÝ NHÂN VIÊN -->
            <?php if ($is_admin): ?>
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">QUẢN LÝ NHÂN VIÊN</h6>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white <?php echo $nv; ?>" href="staffs.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">group</i>
                    </div>
                    <span class="nav-link-text ms-1">Nhân viên</span>
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</aside>

  
