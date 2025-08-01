<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
include_once 'connect.php';

// Get categories for dropdown menu
$category_query = "SELECT DM_MA, DM_TEN FROM danh_muc ORDER BY DM_TEN ASC";
$category_result = $conn->query($category_query);
$categories = [];
if ($category_result && $category_result->num_rows > 0) {
    while ($row = $category_result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Get cart information if user is logged in
$cart_items = [];
$cart_total = 0;
$cart_count = 0;

if (isset($_SESSION['user_id'])) {
    include_once 'cart_functions.php';
    $user_id = $_SESSION['user_id'];
    $cart_id = getCurrentCart($conn, $user_id);
    $cart_data = getCartItems($conn, $cart_id);
    $cart_items = $cart_data['items'];
    $cart_total = $cart_data['total'];
    $cart_count = $cart_data['count'];
}

// jQuery UI - bắt buộc cho chức năng tự động gợi ý
?>

<!-- jQuery UI - bắt buộc cho chức năng tự động gợi ý -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="css/autocomplete.css">
<!-- Header CSS -->
<link rel="stylesheet" href="css/header.css">
<!-- Chatbot CSS -->
<link rel="stylesheet" href="css/chatbot.css">
<style>
    /* Header styling */
    #header {
        background: #fff;
        padding: 15px 0;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    /* Logo styling */
    .logo a {
        color: #333;
        text-decoration: none;
        font-size: 24px !important;
        font-weight: 700;
        display: inline-block;
    }

    .logo span {
        color: #e3ae03;
    }

    /* Navigation menu styling */
    .navbar-nav {
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .nav-item {
        position: relative;
    }

    .nav-link {
        color: #333;
        font-weight: 500;
        padding: 0.5rem 0;
        transition: color 0.3s ease;
        font-size: 16px;
    }

    .nav-link:hover, 
    .nav-link.active {
        color: #28a745 !important;
    }

    /* Search bar styling */
    .search-container {
        position: relative;
        max-width: 100%;
    }

    .search-container .input-group {
        border-radius: 25px;
        overflow: hidden;
    }

    .search-container input {
        border-right: none;
        padding: 10px 15px;
    }

    .search-container .btn {
        border-radius: 0 25px 25px 0;
        padding: 10px 20px;
    }

    /* User menu and cart styling */
    .user-menu {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .user-greeting,
    .login-link,
    .cart-icon {
        color: #333;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .user-greeting:hover,
    .login-link:hover,
    .cart-icon:hover {
        color: #28a745;
    }

    /* Cart icon styling */
    .cart-icon {
        font-size: 20px;
        position: relative;
        display: inline-block;
        padding: 5px;
        color: #333;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .cart-icon:hover {
        color: #28a745;
    }

    .cart-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background-color: #dc3545;
        color: white;
        border-radius: 50%;
        padding: 0.25rem 0.5rem;
        font-size: 12px;
        min-width: 18px;
        height: 18px;
        line-height: 14px;
        text-align: center;
        font-weight: bold;
        border: 2px solid #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    @media (max-width: 768px) {
        .cart-icon {
            font-size: 18px;
        }
        .cart-badge {
            font-size: 10px;
            min-width: 16px;
            height: 16px;
            line-height: 12px;
            top: -6px;
            right: -6px;
        }
    }

    /* Mobile menu styling */
    @media (max-width: 768px) {
        .mobile-menu-container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-nav.mobile-nav {
            gap: 1rem;
        }

        .nav-item.mb-2 {
            margin-bottom: 0.5rem !important;
        }
    }
</style>

<!-- Main Header -->
<header id="header">
    <div class="container">
        <div class="row align-items-center">
            <!-- Logo -->
            <div class="col-md-2 col-6">
                <div class="logo">
                    <a href="index.php" class="text-decoration-none">
                        Cây Trồng <i class="fa fa-leaf align-middle" style="color: green;"></i> <span>Shop</span>
                    </a>
                </div>
            </div>

            <!-- Navigation Menu -->
            <div class="col-md-6 d-none d-md-block">
                <ul class="navbar-nav flex-row justify-content-start gap-4 mb-0">
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active text-success' : ''; ?>"
                            href="index.php">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'shop.php') ? 'active text-success' : ''; ?>"
                            href="shop.php">Sản phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'about.php') ? 'active text-success' : ''; ?>"
                            href="about.php">Giới thiệu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'contact.php') ? 'active text-success' : ''; ?>"
                            href="contact.php">Liên hệ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'blog.php') ? 'active text-success' : ''; ?>"
                            href="blog.php">Cẩm nang</a>
                    </li>
                </ul>
            </div>

            <!-- Search Bar -->
            <div class="col-md-2 d-none d-md-block">
                <form class="d-flex" action="shop.php" method="GET">
                    <div class="search-container">
                        <div class="input-group">
                            <input type="text" 
                                class="form-control" 
                                id="searchInput"
                                name="search" 
                                placeholder="Tìm kiếm..."
                                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                                autocomplete="off">
                            <button class="btn btn-success" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                        <div class="search-suggestions" id="searchSuggestions"></div>
                    </div>
                </form>
            </div>

            <!-- User Menu and Cart -->
            <div class="col-md-2 d-none d-md-block">
                <div class="user-menu">
                    <!-- Login/User Account -->
                    <?php if(isset($_SESSION['user_id'])): ?>
                    <div class="dropdown">
                        <a class="dropdown-toggle user-greeting" href="#" role="button" id="userDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-user me-1"></i>
                            <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="profile.php">Tài khoản</a></li>
                            <li><a class="dropdown-item" href="my_orders.php">Theo Dõi Đơn Hàng</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Đăng xuất</a></li>
                        </ul>
                    </div>
                    <?php else: ?>
                    <a href="login.php" class="login-link">
                        <i class="fa fa-sign-in"></i> Đăng nhập
                    </a>
                    <?php endif; ?>

                    <!-- Cart -->
                    <a href="cart.php" class="cart-icon">
                        <i class="fa fa-shopping-cart"></i>
                        <?php if($cart_count > 0): ?>
                        <span class="cart-badge">
                            <?php echo $cart_count; ?>
                        </span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>

            <!-- Mobile Menu Toggle -->
            <div class="col-6 d-md-none text-end">
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarContent">
                    <i class="fa fa-bars fs-4"></i>
                </button>
            </div>

            <!-- Mobile Collapsible Content -->
            <div class="col-12 d-md-none">
                <div class="collapse navbar-collapse mt-3" id="navbarContent">
                    <div class="mobile-menu-container p-3">
                        <!-- Mobile Navigation Menu -->
                        <ul class="navbar-nav mobile-nav">
                            <li class="nav-item mb-2">
                                <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active text-success' : ''; ?>"
                                    href="index.php">Trang chủ</a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'shop.php') ? 'active text-success' : ''; ?>"
                                    href="shop.php">Sản phẩm</a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'about.php') ? 'active text-success' : ''; ?>"
                                    href="about.php">Giới thiệu</a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'contact.php') ? 'active text-success' : ''; ?>"
                                    href="contact.php">Liên hệ</a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'blog.php') ? 'active text-success' : ''; ?>"
                                    href="blog.php">Cẩm nang</a>
                            </li>
                        </ul>

                        <!-- Mobile Search Bar -->
                        <form class="d-flex mb-3" action="shop.php" method="GET">
                            <div class="search-container">
                                <div class="input-group">
                                    <input type="text" 
                                        class="form-control" 
                                        id="mobileSearchInput"
                                        name="search" 
                                        placeholder="Tìm kiếm..."
                                        value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                                        autocomplete="off">
                                    <button class="btn btn-success" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                                <div class="search-suggestions" id="mobileSearchSuggestions"></div>
                            </div>
                        </form>

                        <!-- Mobile User Menu and Cart (Reordered) -->
                        <div class="d-flex justify-content-between align-items-center">
                            <?php if(isset($_SESSION['user_id'])): ?>
                            <div class="dropdown">
                                <button class="btn btn-outline-success dropdown-toggle" type="button"
                                    id="mobileUserDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-user"></i> <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="mobileUserDropdown">

                                </ul>
                            </div>
                            <?php else: ?>
                            <a href="login.php" class="btn btn-success me-2">
                                <i class="fa fa-sign-in"></i> Đăng nhập
                            </a>
                            <?php endif; ?>

                            <!-- Mobile Cart (moved to end) -->
                            <a href="cart.php" class="btn btn-outline-success">
                                <i class="fa fa-shopping-cart"></i> Giỏ hàng
                                <?php if($cart_count > 0): ?>
                                <span class="badge bg-danger"><?php echo $cart_count; ?></span>
                                <?php endif; ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<script src="js/chatbot.js"></script>
<script>
function initializeSearchSuggestions(inputId, suggestionsId) {
    const searchInput = document.getElementById(inputId);
    const searchSuggestions = document.getElementById(suggestionsId);
    let timeoutId;

    if (!searchInput || !searchSuggestions) return;

    searchInput.addEventListener('input', function() {
        clearTimeout(timeoutId);
        const searchTerm = this.value.trim();

        if (searchTerm.length > 0) {
            timeoutId = setTimeout(() => {
                fetch('search_suggestions.php?term=' + encodeURIComponent(searchTerm))
                    .then(response => response.json())
                    .then(data => {
                        searchSuggestions.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(product => {
                                const div = document.createElement('div');
                                div.className = 'suggestion-item';
                                div.innerHTML = `
                                    <img src="${product.image}" alt="${product.value}">
                                    <div class="product-info">
                                        <div class="product-name">${product.value}</div>
                                        <div class="product-price">${product.price}</div>
                                    </div>
                                `;
                                div.addEventListener('click', () => {
                                    window.location.href = product.link;
                                });
                                searchSuggestions.appendChild(div);
                            });
                            searchSuggestions.style.display = 'block';
                        } else {
                            searchSuggestions.style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }, 300);
        } else {
            searchSuggestions.style.display = 'none';
        }
    });

    // Ẩn suggestions khi click ra ngoài
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
            searchSuggestions.style.display = 'none';
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo cho desktop search
    initializeSearchSuggestions('searchInput', 'searchSuggestions');
    // Khởi tạo cho mobile search
    initializeSearchSuggestions('mobileSearchInput', 'mobileSearchSuggestions');
});
</script>

<style>
.search-container {
    position: relative;
    width: 100%;
}

.search-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    max-height: 400px;
    overflow-y: auto;
    z-index: 1000;
    display: none;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.suggestion-item {
    padding: 10px;
    display: flex;
    align-items: center;
    border-bottom: 1px solid #eee;
    cursor: pointer;
    transition: background-color 0.2s;
}

.suggestion-item:hover {
    background-color: #f8f9fa;
}

.suggestion-item img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    margin-right: 10px;
    border-radius: 4px;
}

.suggestion-item .product-info {
    flex-grow: 1;
}

.suggestion-item .product-name {
    font-weight: bold;
    margin-bottom: 5px;
    color: #333;
}

.suggestion-item .product-price {
    color: #28a745;
    font-weight: 600;
}

/* Dark mode styles */
@media (prefers-color-scheme: dark) {
    .search-suggestions {
        background: #2d2d2d;
        border-color: #444;
    }

    .suggestion-item {
        border-bottom-color: #444;
    }

    .suggestion-item:hover {
        background-color: #3d3d3d;
    }

    .suggestion-item .product-name {
        color: #fff;
    }
}
</style>