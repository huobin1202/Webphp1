<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: dnurl.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "admindoan";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
include("../toast.php");

// Check if `id` is passed via POST (Handle deletion)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $id = intval($_POST['id']);
        
        // Kiểm tra xem sản phẩm đã được bán ra chưa
        $check_sold = $conn->prepare("SELECT COUNT(*) as count FROM order_details WHERE product_id = ?");
        $check_sold->bind_param("i", $id);
        $check_sold->execute();
        $result = $check_sold->get_result();
        $row = $result->fetch_assoc();
        
        if ($row['count'] > 0) {
            // Sản phẩm đã được bán ra, chuyển hướng đến trang ẩn sản phẩm
            header("Location: toggle_product.php?id=" . $id);
        } else {
            // Sản phẩm chưa được bán ra, chuyển hướng đến trang xóa sản phẩm
            header("Location: delete_product.php?id=" . $id);
        }
        exit();
    }
}
?>



<!DOCTYPE php>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='../image/logo.png' rel='icon' type='image/x-icon' />
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link href="../assets/font/font-awesome-pro-v6-6.2.0/css/all.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../assets/css/admin-responsive.css">
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
                <a href="" class="channel-logo"><img src="../image/logo.png" alt="Channel Logo"></a>
                <div class="hidden-sidebar your-channel"><img src="" style="height: 30px;" alt="">
                </div>
            </div>
            <div class="middle-sidebar">
                <ul class="sidebar-list">
                    <li class="sidebar-list-item tab-content">
                        <a href="../admin.php" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-light fa-house"></i></div>
                            <div class="hidden-sidebar">Trang tổng quan</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item tab-content active">
                        <a href="../sanpham/sanpham.php" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-thin fa-motorcycle"></i></div>
                            <div class="hidden-sidebar">Sản phẩm</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item tab-content">
                        <a href="../khachhang/khachhang.php" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-light fa-users"></i></div>
                            <div class="hidden-sidebar">Khách hàng</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item tab-content">
                        <a href="../donhang/donhang.php" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-light fa-basket-shopping"></i></div>
                            <div class="hidden-sidebar">Đơn hàng</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item tab-content">
                        <a href="../thongkesp/thongke.php" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-light fa-chart-simple"></i></div>
                            <div class="hidden-sidebar">Thống kê sản phẩm</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item tab-content">
                        <a href="../thongkekh/thongkekh.php" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-light fa-chart-simple"></i></div>
                            <div class="hidden-sidebar">Thống kê khách hàng</div>
                        </a>
                    </li>

                    <div class="spacer" style="height:50px;width:1px"></div>
                    <li class="sidebar-list-item user-logout" style="border-top: 2px solid rgba(0,0,0,0.12);">
                        <a href="#" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-light fa-circle-user"></i></div>
                            <div class="hidden-sidebar" id="name-acc">
                                <?php echo $_SESSION['username']; ?>
                            </div>
                        </a>
                    </li>
                    <li class="sidebar-list-item user-logout">
                        <a href="../index.php" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-light fa-arrow-right-from-bracket"></i></div>
                            <div class="hidden-sidebar" id="logoutacc">Đăng xuất</div>
                        </a>
                    </li>
                </ul>
            </div>

        </aside>
        <main class="content">
            <!-- Product  -->
            <div class="section product-all">
                <div class="admin-control">
                    <div class="admin-control-left">
                        <select name="the-loai" id="the-loai">
                            <option>Tất cả</option>
                            <option>Dòng Ninja</option>
                            <option>Dòng Z</option>
                            <option>Dòng KLX </option>
                        </select>
                    </div>
                    <div class="admin-control-center">
                        <form action="" class="form-search">
                            <span class="search-btn"><i class="fa-light fa-magnifying-glass"></i></span>
                            <input id="form-search-product" type="text" class="form-search-input"
                                placeholder="Tìm kiếm tên xe...">
                        </form>
                    </div>
                    <div class="admin-control-right">
                        <button class="btn-control-large" id="btn-cancel-product"><i
                                class="fa-light fa-rotate-right"></i> Làm mới</button>
                        <a href="newproduct.php"><button class="btn-control-large" id="btn-add-product"><i class="fa-light fa-plus"></i> Thêm xe
                                mới</button></a>
                    </div>
                </div>
                <div id="show-product"></div>
                <?php
                // Fetch product data
                $sql = "SELECT * FROM products ORDER BY id DESC";
                $result = $conn->query($sql);

                // Check if there are products to display
                if ($result->num_rows > 0) {
                    // Loop through and display each product
                    while ($row = $result->fetch_assoc()) {
                        echo '
                        <div class="list">
                            <div class="list-left">
                                <img src="' . $row["hinhanh"] . '" alt="' . $row["tensp"] . '">
                                <div class="list-info">
                                    <h4>' . $row["tensp"] . '</h4>
                                    <p class="list-note">' . $row["thongtinsp"] . '</p>
                                    <span class="list-category"> ' . $row["dongsp"] . '</span>
                                </div>
                            </div>  
                                   <div class="list-right">
                <div class="list-price">
                    <span class="list-current-price">' . number_format($row["giaban"], 0, ',', '.') . 'đ</span>
                </div>
                <div class="list-status" style="margin-top: 10px;">
                    <span class="status-' . ($row["status"] == 1 ? "complete" : "no-complete") . '">' . 
                    ($row["status"] == 1 ? "Đang hiển thị" : "Đã ẩn") . '</span>
                </div>
                <div class="list-control">
                    <div class="list-tool">
                    
                        <a href="changeproduct.php?id=' . $row["id"] . '">
                            <button class="btn-edit"><i class="fa-light fa-pen-to-square"></i></button>
                        </a>
                        <button type="button" class="btn-delete" onclick="checkProductStatus(' . $row["id"] . ');">
                            <i class="fa-regular fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
                        </div>
                        ';
                    }
                } else {
                    echo "<div class='no-products'>Không có sản phẩm nào!</div>";
                }
                ?>
            </div>
        </main>
    </div>
    <script src="../assets/js/admin.js"></script>
    <script src="check_product.js"></script>
</body>

</html>