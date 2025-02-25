
<!DOCTYPE html>
<html lang="en">

<head>
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

                    </form>
                </div>

                <div class="header-middle-right">
                    <ul class="header-middle-right-list">
                        <li class="header-middle-right-item dropdown open">

                            <div class="auth-container">

                                <div class="user-info">
                                    <h1 class="welcome"> <span id="userDisplayName"></span></h1>
                                    <a href="dk.php"><button class="logout-btn" onclick="logout()"
                                            style="font-size: 17px;">Đăng xuất</button></a>
                                </div>

                                <div class="hoadon">
                                    <span class="ravao">Giỏ hàng</span>
                                    <a href="hoadon.php">
                                        <div class="hd">Hóa đơn</div>
                                    </a>
                                    <a href="admin.php">
                                        <div class="hd">Quản lý</div>
                                    </a>

                                </div>
                            </div>
                    </ul>
                </div>
            </div>
        </div>
    </header>
    <div class="advanced-search">
        <div class="container">

        </div>
    </div>
    <div class="green-line-header"></div>

    <nav class="header-bottom">
        <div class="container">

        </div>
    </nav>


    <main class="main-wrapper">

        <div class="container" id="trangchu">


            <div class="home-service" id="home-service">

            </div>
            <div class="home-title-block" id="home-title">
                <h2 class="home-title">KẾT QUẢ TÌM KIẾM</h2>

                <div class="border-line"></div>

            </div>
            <form action="" class="form-search">
                <span class="search-btn">
                    <a href="">
                        <i class="fa-light fa-magnifying-glass"></i>
                    </a>
                </span>
                <input type="text" class="form-search-input" id="searchBox" placeholder="Tìm kiếm xe... "
                    onkeyup="searchProducts()">

            </form>


            <div class="page-nav">
                <ul class="page-nav-list">
                    <div class="filter-row">
                        <div class="filter">
                            <div class="filter-box">
                                <div class="container">
                                    <h3 class="advanced-title">Bộ lọc tìm kiếm</h3>
                                    <div class="advanced-search-container">
                                        <legend class="advanced-search-header">Theo danh mục</legend>

                                        <div class="advanced-search-category">
                                            <label class="checkbox">
                                                <div class="checkbox_box">
                                                    <input type="checkbox" name="" value="">
                                                    <span class="checkbox_label">Dòng Z (3)</span>
                                                </div>
                                                <div class="checkbox_box">
                                                    <input type="checkbox" name="" value="">
                                                    <span class="checkbox_label">Dòng KLX (2)</span>
                                                </div>
                                                <div class="checkbox_box">
                                                    <input type="checkbox" name="" value="">
                                                    <span class="checkbox_label">Dòng Z (4)</span>
                                                </div>
                                            </label>

                                        </div>

                                    </div>
                                    <div class="advanced-search-container">
                                        <legend class="advanced-search-header">Khoảng giá</legend>
                                        <div class="advanced-search-price">
                                          
                                            <input type="number" placeholder="₫ TỪ" id="min-price" onchange="searchProducts()">
                                            <span>-</span>
                                            <input type="number" placeholder="₫ ĐẾN" id="max-price" onchange="searchProducts()">
                                        </div>
                                        <button id="advanced-price-btn" style="" aria-label="">Áp dụng <i class="fa-light fa-magnifying-glass-dollar"></i></button>
                                    
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
                        </div>
                        <div class="product-right" id="product-list">

                            <?php
                            $servername = "localhost";
                            $username = "root";
                            $password = "";
                            $dbname = "admindoan";

                            $conn = new mysqli($servername, $username, $password, $dbname);
                            if ($conn->connect_error) {
                                die("Kết nối thất bại" . $conn->connect_error);
                            }

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
                                    <div class="price">' . $row["giaban"] . 'đ</div>
                                    <button type="button" class="mua" onclick="addToCart(\'' . $row["tensp"] . '\', ' . $row["giaban"] . ', \'image/' . $row["hinhanh"] . '\')">Thêm vào giỏ hàng </button>
                                    </div>';
                                }
                            }
                            ?>

                        </div>
                        </tbody>

                    </div>
                </ul>
            </div>
        </div>

    </main>
    <section class="cart">
        <button class="dong"><i class="fa-regular fa-xmark"></i></button>
        <!-- <div>Đóng</div> -->
        <div style="margin-top: 45px;margin-bottom: 20px;">Danh sách mua hàng</div>
        <form action="">
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

                    <!-- <td style="display: flex;align-items: center;"><img style ="width: 120px;"src="image/ninja-1000sx.png"alt ="">Ninja</td>
                        <td><p><span>1500</span><sup>đ</sup></p></td>
                         <td><input style="width: 40px; outline: none;" type="number"value ="1"min="1""max="2""></td>
                         <td style="cursor: pointer;">Xóa </td>
                     -->
                </tbody>
            </table>
            <div style="text-align: center;" class="price-total">
                <p style=" font-weight: bold;margin-top: 10px; margin-bottom: 20px;">Tổng tiền:<span>0</span><sup></sup>
                </p>
            </div>
            <a class="thanhtoan" href="thanhtoan.php">Thanh toán</a>
        </form>
    </section>

    <div class="green-line-header"></div>
    <footer class="footer">
        <div class="container">
            <div class="footer-top">
                <div class="footer-top-content">
                    <div class="footer-top-img">
                    </div>
                    <div class="footer-top-subbox">
                        <div class="footer-top-subs">
                            <h2 class="footer-top-subs-title">Đăng ký nhận tin</h2>
                            <p class="footer-top-subs-text">Nhận tin nhắn mới nhất từ chúng tôi</p>
                        </div>
                        <form class="form-ground">
                            <input type="email" class="form-ground-input" placeholder="Nhập email của bạn">
                            <button class="form-ground-btn">
                                <span>ĐĂNG KÝ</span>
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="widget-area">
            <div class="container">
                <div class="widget-row">
                    <div class="widget-row-col-1">
                        <h3 class="widget-title">Giới thiệu về BMTShop</h3>
                        <ul class="widget-contact">
                            <li class="widget-contact-item">
                                <a href="">
                                    <i class="fa-regular fa-arrow-right"></i>
                                    <span>Về chúng tôi</span>
                                </a>
                            </li>
                            <li class="widget-contact-item">
                                <a href="">
                                    <i class="fa-regular fa-arrow-right"></i>
                                    <span>Tư vấn mua hàng</span>
                                </a>
                            </li>
                            <li class="widget-contact-item">
                                <a href="">
                                    <i class="fa-regular fa-arrow-right"></i>
                                    <span>Tuyển dụng </span>
                                </a>
                            </li>


                        </ul>
                        <div class="widget-social">
                            <div class="widget-social-item">
                                <a href="">
                                    <i class="fab fa-facebook-f"></i>

                                </a>
                            </div>
                            <div class="widget-social-item">
                                <a href="">
                                    <i class="fab fa-twitter"></i>

                                </a>
                            </div>
                            <div class="widget-social-item">
                                <a href="">
                                    <i class="fab fa-linkedin-in"></i>

                                </a>
                            </div>
                            <div class="widget-social-item">
                                <a href="">
                                    <i class="fab fa-whatsapp"></i>

                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="widget-row-col">
                        <h3 class="widget-title">Chính sách chung</h3>
                        <ul class="widget-contact">
                            <li class="widget-contact-item">
                                <a href="">
                                    <i class="fa-regular fa-arrow-right"></i>
                                    <span>Chính sách trả góp</span>
                                </a>
                            </li>
                            <li class="widget-contact-item">
                                <a href="">
                                    <i class="fa-regular fa-arrow-right"></i>
                                    <span>Chính sách bảo mật</span>
                                </a>
                            </li>
                            <li class="widget-contact-item">
                                <a href="">
                                    <i class="fa-regular fa-arrow-right"></i>
                                    <span>Chính sách khiếu nại </span>
                                </a>
                            </li>

                            <li class="widget-contact-item">
                                <a href="">
                                    <i class="fa-regular fa-arrow-right"></i>
                                    <span>Chính sách vận chuyển</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="widget-row-col">
                        <h3 class="widget-title">Liên kết</h3>
                        <ul class="widget-contact">
                            <li class="widget-contact-item">
                                <a href="">
                                    <i class="fa-regular fa-arrow-right"></i>
                                    <span>Sản phẩm</span>

                                </a>
                            </li>
                            <li class="widget-contact-item">
                                <a href="">
                                    <i class="fa-regular fa-arrow-right"></i>
                                    <span>Tin tức</span>
                                </a>
                            </li>
                            <li class="widget-contact-item">
                                <a href="">
                                    <i class="fa-regular fa-arrow-right"></i>
                                    <span>Điều khoản</span>
                                </a>
                            </li>

                        </ul>
                    </div>
                    <div class="widget-row-col-1">
                        <h3 class="widget-title">Liên hệ</h3>
                        <div class="contact">
                            <div class="contact-item">
                                <div class="contact-item-icon">
                                    <i class="fa-regular fa-location-dot"></i>
                                </div>
                                <div class="contact-content">
                                    <span>273 An Dương Vương, Phường 3, Quận 5, TP Hồ Chí Minh</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-item-icon">
                                    <i class="fa-regular fa-phone"></i>
                                </div>
                                <div class="contact-content contact-item-phone">
                                    <span>0123 456 789</span>
                                    <br>
                                    <span>0987 654 321</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-item-icon">
                                    <i class="fa-regular fa-envelope"></i>
                                </div>
                                <div class="contact-content conatct-item-email">
                                    <span>abc@domain.com</span><br />
                                    <span>infoabc@domain.com</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <div class="copyright-wrap">
        <div class="container">
            <div class="copyright-content">
                <p>Copyright 2024 BMTShop. All Rights Reserved.</p>
            </div>
        </div>
    </div>

    <script>
        // Lấy tên người dùng từ localStorage
        const loggedInUser = localStorage.getItem('loggedInUser');

        // Kiểm tra nếu có người dùng đã đăng nhập, hiển thị tên
        if (loggedInUser) {
            document.getElementById('userDisplayName').textContent = loggedInUser;
        }

        // Hàm đăng xuất
        function logout() {
            // Xóa thông tin người dùng khỏi localStorage
            localStorage.removeItem('loggedInUser');

            // Chuyển hướng về trang đăng nhập
            window.location.href = 'index.php';
        }
    </script>
    <!-- <script src="js/hoadon.js"></script> -->
    <script src="js/giohang.js"></script>
    <script src="js/phantrang.js"></script>
    <script src="js/ssbutton.js"></script>
    <script src="js/main.js"></script>

</body>

</html>