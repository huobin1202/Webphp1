<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "admindoan";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to count users
$userCountQuery = "SELECT COUNT(*) as user_count FROM customer";
$userCountResult = $conn->query($userCountQuery);
$userCount = $userCountResult->fetch_assoc()['user_count'];

// Query to count products
$productCountQuery = "SELECT COUNT(*) as product_count FROM products";
$productCountResult = $conn->query($productCountQuery);
$productCount = $productCountResult->fetch_assoc()['product_count'];
/*
// Query to count orders
$orderCountQuery = "SELECT COUNT(*) as order_count FROM orders";
$orderCountResult = $conn->query($orderCountQuery);
$orderCount = $orderCountResult->fetch_assoc()['order_count'];

// Query to calculate total revenue
$revenueQuery = "SELECT SUM(total_amount) as total_revenue FROM orders";
$revenueResult = $conn->query($revenueQuery);
$revenue = $revenueResult->fetch_assoc()['total_revenue'];
*/
// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='img/logo.png' rel='icon' type='image/x-icon' />
    <link rel="stylesheet" href="assets/css/admin.css">
    <link href="./assets/font/font-awesome-pro-v6-6.2.0/css/all.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="./assets/css/admin-responsive.css">
    <title>Quản lý cửa hàng</title>
</head>

<body>
    <header class="header">
        <button class="menu-icon-btn">
            <div class="menu-icon">
                <i class="fa-regular fa-bars"></i>
            </div>
        </button>
    </header>
    <div class="container">
        <aside class="sidebar open">
            <div class="top-sidebar">
                <a href="admin.php" class="channel-logo"><img src="img/logo.png" alt="Channel Logo"></a>
                <div class="hidden-sidebar your-channel"><img src="" style="height: 30px;" alt="">
                </div>
            </div>
            <div class="middle-sidebar">
                <ul class="sidebar-list">
                    <li class="sidebar-list-item tab-content active">
                        <a href="admin.php" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-light fa-house"></i></div>
                            <div class="hidden-sidebar">Trang tổng quan</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item tab-content">
                        <a href="./sanpham/sanpham.php" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-thin fa-motorcycle"></i></div>
                            <div class="hidden-sidebar">Sản phẩm</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item tab-content">
                        <a href="./khachhang/khachhang.php" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-light fa-users"></i></div>
                            <div class="hidden-sidebar">Khách hàng</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item tab-content">
                        <a href="./donhang/donhang.php" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-light fa-basket-shopping"></i></div>
                            <div class="hidden-sidebar">Đơn hàng</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item tab-content" >
                        <a href="./thongkesp/thongke.php" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-light fa-chart-simple"></i></div>
                            <div class="hidden-sidebar">Thống kê sản phẩm</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item tab-content">
                        
                        <a href="./thongkekh/thongkekh.php" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-light fa-chart-simple"></i></div>
                            <div class="hidden-sidebar">Thống kê khách hàng</div>
                        </a>
                    </li>
                    <div class="spacer" style="height:50px;width:1px"></div>
                    <li class="sidebar-list-item user-logout" style="border-top: 2px solid rgba(0,0,0,0.12);">
                        <a href="index.php" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-thin fa-circle-chevron-left"></i></div>
                            <div class="hidden-sidebar">Trang chủ</div>
                        </a>
                    </li>

                    <li class="sidebar-list-item user-logout">
                        <a href="#" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-light fa-circle-user"></i></div>
                            <div class="hidden-sidebar" id="name-acc">Admin</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item user-logout">
                        <a href="#" class="sidebar-link" >
                            <div class="sidebar-icon"><i class="fa-light fa-arrow-right-from-bracket"></i></div>
                            <div class="hidden-sidebar" id="logoutacc">Đăng xuất</div>
                        </a>
                    </li>
                </ul>
            </div>

        </aside>
        <main class="content">
    <div class="section active">
        <h1 class="page-title">Trang tổng quát của cửa hàng BMTShop</h1>
        <div class="cards">
            <div class="card-single">
                <div class="box">
                    <h2 id="amount-user"><?php echo $userCount; ?></h2>
                    <div class="on-box">
                        <h3>Khách hàng</h3>
                    </div>
                    <div class="order-statistical-item-icon">
                        <i class="fa-light fa-users" style="position: absolute;top: 12px;right: -1px;"></i>
                    </div>
                </div>
            </div>

            <div class="card-single">
                <div class="box">
                    <div class="on-box">
                        <h2 id="amount-product"><?php echo $productCount; ?></h2>
                        <h3>Sản phẩm</h3>
                    </div>
                    <div class="order-statistical-item-icon">
                        <i class="fa-thin fa-motorcycle" style="position: absolute;top: 12px;right: -1px;"></i>
                    </div>
                </div>
            </div>

            <div class="card-single">
                <div class="box">
                    <div class="on-box">
                        <h2 id="amount-order">0</h2>
                        <h3>Đơn hàng</h3>
                    </div>
                    <div class="order-statistical-item-icon">
                        <i class="fa-light fa-file-lines" style="position: absolute;top: 12px;right: -1px;"></i>
                    </div>
                </div>
            </div>

            <div class="card-single">
                <div class="box">
                    <h2 id="doanh-thu">0đ</h2>
                    <div class="on-box">
                        <h3>Doanh thu</h3>
                    </div>
                    <div class="order-statistical-item-icon">
                        <i class="fa-light fa-dollar-sign" style="position: absolute;top: 12px;right: -1px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
    </div>
    <script src="./assets/js/admin.js"></script>
    <script>
        // Thực hiện đăng xuất
        document.getElementById("logoutacc").onclick = function () {
            localStorage.removeItem("isLoggedIn");
            localStorage.removeItem("username");
            window.location.href = "dnurl.php";  // Chuyển hướng về trang đăng nhập khi đăng xuất
        }
    </script>
</body>

    </html>