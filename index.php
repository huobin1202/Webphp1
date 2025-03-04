<?php
session_start();

// Ngăn chặn cache để tránh hiển thị thông tin cũ
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "admindoan";

// Kết nối CSDL
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý đăng xuất
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}

// Kiểm tra nếu có tài khoản đăng nhập
$username = isset($_SESSION["username"]) ? $_SESSION["username"] : null;
?>
<!DOCTYPE html>
<html lang="en">

<head> hello
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reset.css ">
    <link rel="stylesheet" href="css/app.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="./assets/font/font-awesome-pro-v6-6.2.0/css/all.min.css">
    <script src="https://fontawesome.com/v6/search" crossorigin="anonymous"></script>
    <title>BMT </title>
</head>

<body>
    <header>

        <div class="header-middle">
            <div class="container">
                <div class="header-middle-left">
                    <div class="header-logo">
                        <a href="" id="logo">
                            <img src="image/logo.png" alt="BMT">
                        </a>

                    </div>
                </div>
                <div class="header-middle-center">
                    <form action="" class="form-search">
                        <span class="search-btn">
                            <a href="">
                                <i class="fa-light fa-magnifying-glass"></i>
                            </a>
                        </span>
                        <input type="text" class="form-search-input" id="searchBox" placeholder="Tìm kiếm xe... "
                            onkeyup="searchProducts()">
                        <button class="filter-btn">
                            <i class="fa-light fa-filter-list"></i>
                            <span style="font-size: 14px;">Lọc</span>
                        </button>
                    </form>
                </div>

                <div class="header-middle-right">
                    <ul class="header-middle-right-list">
                        <li class="header-middle-right-item dropdown open">
                        <li class="header-middle-right-item open">

                            <div class="auth-container" method="POST">
                                <?php if ($username): ?>
                                    <?php
                                    $stmt = $conn->prepare("SELECT id, name FROM customer WHERE name = ?");
                                    $stmt->bind_param("s", $username);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    if ($result->num_rows > 0) {
                                        $row = $result->fetch_assoc();
                                    ?>
                                        <div class="user-info">
                                            <div class="dropdownb">
                                                <h1 class="welcome">
                                                    <i class="fa-light fa-user"></i>

                                                    <span class="dropdownb-toggle" style="color:green;"><?php echo htmlspecialchars($row["name"]); ?></span>
                                                </h1>
                                                <div class="dropdownb-menu">

                                                    <a href="<?php echo $username ? 'hoadon.php' : 'javascript:void(0);' ?>" onclick="<?php echo $username ? '' : 'requireLogin()' ?>">
                                                        <div class="hd">Hóa đơn</div>
                                                        <a href="dnurl.php">
                                                            <div class="hd">Quản lý</div>
                                                        </a>
                                                        <a href="index.php?logout=true">
                                                            <div class="hd">Đăng xuất</div>
                                                        </a>


                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php $stmt->close(); ?>
                                <?php else: ?>
                                    <a href="dn.php"><button class="login-btn" style="font-size: 17px;">Đăng nhập</button></a>
                                <?php endif; ?>

                                <div class="hoadon">

                                    <span class="ravao" onclick="<?php echo $username ? 'openCart()' : 'requireLogin()' ?>"> 
                                        <i class="fa-light fa-basket-shopping"></i>
                                        Giỏ hàng</span>
                                </div>
                            </div>
                        </li>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </header>
    <div class="advanced-search">
        <div class="container">
            <div class="advanced-search-category">
                <span>Phân loại </span>
                <select name="" id="advanced-search-category-select" onchange="searchProducts()">
                    <option>Tất cả</option>
                    <option>Dòng Ninja</option>
                    <option>Dòng Z</option>
                    <option>Dòng KLX</option>
                </select>
            </div>
            <div class="advanced-search-price">
                <span>Giá từ</span>
                <input type="number" placeholder="tối thiểu" id="min-price" onchange="searchProducts()">
                <span>đến</span>
                <input type="number" placeholder="tối đa" id="max-price" onchange="searchProducts()">
                <button id="advanced-search-price-btn"><a href=""><i class="fa-light fa-magnifying-glass-dollar"></i></a></button>
            </div>
            <div class="advanced-search-control">
                <button id="sort-ascending" onclick="searchProducts(1)"><i
                        class="fa-regular fa-arrow-up-short-wide"></i></button>
                <button id="sort-descending" onclick="searchProducts(2)"><i
                        class="fa-regular fa-arrow-down-wide-short"></i></button>
                <button id="reset-search" onclick="searchProducts(0)"><i
                        class="fa-light fa-arrow-rotate-right"></i></button>
                <button onclick="closeSearchAdvanced()"><i class="fa-light fa-xmark"></i></button>
            </div>
        </div>
    </div>
    <div class="green-line-header"></div>

    <nav class="header-bottom">
        <div class="container">
            <ul class="menu-list">
                <li class="menu-list-item"><a href="index.php" class="menu-link">Trang chủ</a></li>

                <div class="dropdown">
                    <span>Sản phẩm</span>
                    <div class="dropdown-content">
                        <li class="menu-list-item"><a href="NINJA.php" class="menu-link">Dòng Ninja</a></li>
                        <li class="menu-list-item"><a href="Z.php" class="menu-link">Dòng Z</a></li>
                        <li class="menu-list-item"><a href="KLX.php" class="menu-link">Dòng KLX</a></li>
                    </div>
                </div>
                <li class="menu-list-item"><a href="index.php" class="menu-link">Tin tức </a></li>
                <li class="menu-list-item"><a href="index.php" class="menu-link">Điều khoản</a></li>

            </ul>
        </div>
    </nav>


    <main class="main-wrapper">
        <div class="slideshow-container">
            <div class="mySlides fade">

                <img src="image/web1.jpg">

            </div>
            <div class="mySlides fade">

                <img src="image/web2.jpg">

            </div>
            <div class="mySlides fade">

                <img src="image/web3.png">

            </div>
            <div class="mySlides fade">

                <img src="image/web4.jpg">

            </div>
            <div class="dot-wrapper">

                <span class="dot"></span>
                <span class="dot"></span>
                <span class="dot"></span>
                <span class="dot"></span>

            </div>
            <a class="prev" onclick="prevSlide()">&#10094;</a>
            <a class="next" onclick="showSlides()">&#10095;</a>
        </div>
        <div class="container" id="trangchu">


            <div class="home-service" id="home-service">
                <div class="home-service-item">
                    <div class="home-service-item-icon">
                        <i class="fa-light fa-person-carry-box"></i>
                    </div>
                    <div class="home-service-item-content">
                        <h4 class="home-service-item-content-h">GIAO HÀNG NHANH</h4>
                        <p class="home-service-item-content-desc">Cho tất cả đơn hàng</p>
                    </div>
                </div>
                <div class="home-service-item">
                    <div class="home-service-item-icon">
                        <i class="fa-light fa-shield-heart"></i>
                    </div>
                    <div class="home-service-item-content">
                        <h4 class="home-service-item-content-h">SẢN PHẨM AN TOÀN</h4>
                        <p class="home-service-item-content-desc">Cam kết chất lượng</p>
                    </div>
                </div>
                <div class="home-service-item">
                    <div class="home-service-item-icon">
                        <i class="fa-light fa-headset"></i>
                    </div>
                    <div class="home-service-item-content">
                        <h4 class="home-service-item-content-h">HỖ TRỢ 24/7</h4>
                        <p class="home-service-item-content-desc">Tất cả ngày trong tuần</p>
                    </div>
                </div>
                <div class="home-service-item">
                    <div class="home-service-item-icon">
                        <i class="fa-light fa-circle-dollar"></i>
                    </div>
                    <div class="home-service-item-content">
                        <h4 class="home-service-item-content-h">HOÀN LẠI TIỀN</h4>
                        <p class="home-service-item-content-desc">Nếu không hài lòng</p>
                    </div>
                </div>
            </div>
            <div class="home-title-block" id="home-title">
                <h2 class="home-title">MẪU XE ĐẶC TRƯNG</h2>
                <div class="border-line"></div>
            </div>


            <div class="page-nav">
                <ul class="page-nav-list">
                    <div class="grid-container" id="product-list">

                        <?php


                        $sql = "SELECT id, tensp, giaban, hinhanh FROM products";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '
                                <div class="card page-1" id="invoiceModal">
                                <a href="thongtinsp.php?id=' . $row["id"] . '">                                
                                <img src="sanpham/' . $row["hinhanh"] . '" alt="' . $row["tensp"] . '">
                                </a>
                                <h3>' . $row["tensp"] . '</h3>
                                <div class="greenSpacer"></div>
                                <div class="price">' . number_format($row["giaban"], 0, ',', '.') . 'đ</div>
                                <button type="button" class="mua" onclick="addToCart(\'' . $row["tensp"] . '\', ' . $row["giaban"] . ', \'image/' . $row["hinhanh"] . '\')">Thêm vào giỏ hàng </button>
                                </div>';
                            }
                        }
                        ?>

                    </div>
                    </tbody>
                    <div class="pagination">
                        <button id="prevBtn" onclick="changePage(-1)" disabled>&#10094;</button>
                        <div id="pageNumbers" class="page-numbers"></div>
                        <button id="nextBtn" onclick="changePage(1)">&#10095;</button>
                    </div>
                </ul>
            </div>
        </div>

    </main>
    <section class="cart">
        <button class="dong"><i class="fa-regular fa-xmark"></i></button>
        <!-- <div>Đóng</div> -->
        <div style="margin-top: 45px;margin-bottom: 20px;">Danh sách mua hàng</div>
        <form action="" method="POST">
            <table>
                <thead>
                    <tr>
                        <th>
                            Sản phẩm
                        </th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Chọn</th>
                    </tr>
                </thead>
                <tbody>



                </tbody>
            </table>
            <div style="text-align: center;" class="price-total">
                <p style=" font-weight: bold;margin-top: 10px; margin-bottom: 20px;">Tổng tiền:<span>0</span><sup></sup>
                </p>
            </div>
            <a class="thanhtoan" href="<?php echo $username ? 'thanhtoan.php' : 'javascript:void(0);' ?>" onclick="<?php echo $username ? '' : 'requireLogin()' ?>">Thanh toán</a>
        </form>
    </section>

    <div class="green-line-header"></div>
    <?php include 'footer.php' ?>
    <!-- <script src="js/hoadon.js"></script> -->
    <script src="js/giohang.js"></script>
    <script src="js/phantrang.js"></script>
    <script src="js/ssbutton.js"></script>
    <script src="js/main.js"></script>
    <script>
        function requireLogin() {
            alert("Bạn cần đăng nhập để sử dụng chức năng này!");
            return false; // Ngăn chặn hành động
        }

        function openCart() {
            document.querySelector('.cart').style.display = 'block';
        }
        window.onload = function() {
            const username = localStorage.getItem('username'); // Kiểm tra đăng nhập
            const cartSection = document.querySelector('.cart'); // Lấy phần giỏ hàng

            if (!username) {
                cartSection.style.display = "none"; // Ẩn giỏ hàng nếu chưa đăng nhập
            } else {
                cartSection.style.display = "block"; // Hiển thị nếu đã đăng nhập
                displayCart(); // Hiển thị giỏ hàng
            }
        };
    </script>



</body>
<?php $conn->close(); ?>

</html>