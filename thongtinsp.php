<?php
session_start();
include('database.php');
include('toast.php');

// Xử lý đăng xuất
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}
$username = isset($_SESSION["username"]) ? $_SESSION["username"] : null;
$customer_id = isset($_SESSION["customer_id"]) ? $_SESSION["customer_id"] : null;

// Nếu có username, lấy ID từ bảng customer
if ($username && !$customer_id) {
    $stmt = $conn->prepare("SELECT id, role FROM customer WHERE name = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($customer_id, $role);
    if ($stmt->fetch()) {
        $_SESSION["customer_id"] = $customer_id;
        $_SESSION["role"] = $role;
    }
} else {
    $role = isset($_SESSION["role"]) ? $_SESSION["role"] : null;
}
// Lấy ID sản phẩm từ URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Kiểm tra nếu ID hợp lệ
if ($id <= 0) {
    die("Sản phẩm không hợp lệ!");
}

// Truy vấn chỉ sản phẩm có ID tương ứng
$sql = "SELECT id, tensp, giaban, thongtinsp, thongsokt, hinhanh, hinhanh2, hinhanh3 FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Kiểm tra nếu sản phẩm tồn tại
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    die("Sản phẩm không tồn tại!");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/app.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="./assets/font/font-awesome-pro-v6-6.2.0/css/all.min.css">
    <script src="https://fontawesome.com/v6/search" crossorigin="anonymous"></script>
    <title>BMT </title>
</head>
<style>
    .form-inline .form-control {
        border-radius: 25px;
        padding: 8px 15px;
        border: 1px solid #ccc;
    }

    .mua.in-cart {
        background-color: #dc3545 !important;
        /* Màu đỏ */
    }

    .cart-button {
        transition: all 0.3s ease;
    }

    .card2 {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-top: 30px;
        border: none;
        border-radius: 10px;
        overflow: hidden;
    }

    .wrapper {
        display: flex;
        flex-wrap: wrap;
        padding: 20px;
    }

    .preview {
        flex: 1;
        padding: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .main-image {
        width: 100%;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
    }

    .zoom-controls {
        position: absolute;
        top: 10px;
        right: 10px;
        display: flex;
        flex-direction: column;
        gap: 5px;
        z-index: 10;
    }

    .zoom-btn {
        width: 30px;
        height: 30px;
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid #ddd;
        border-radius: 4px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #333;
        transition: all 0.3s ease;
    }

    .zoom-btn:hover {
        background: #139b3a;
        color: white;
        border-color: #139b3a;
    }

    .main-image img {
        width: 100%;
        max-height: 400px;
        object-fit: contain;
        border-radius: 8px;
        transition: transform 0.3s ease;
        transform-origin: center center;
    }

    .thumbnail-section {
        width: 100%;
        margin-top: 20px;
        border-top: 1px dashed #ccc;
        padding-top: 20px;
    }

    .thumbnail-title {
        text-align: center;
        font-size: 14px;
        color: #76767c;
        margin-bottom: 15px;
        font-weight: 500;
        position: relative;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .thumbnail-title:before,
    .thumbnail-title:after {
        content: "―";
        display: inline-block;
        position: relative;
        top: -3px;
        padding: 0 10px;
        color: #76767c;
    }

    .thumbnail-images {
        display: flex;
        gap: 15px;
        justify-content: center;
        width: 100%;
        padding: 10px;
    }

    .thumb {
        width: 120px;
        height: 80px;
        object-fit: cover;
        cursor: pointer;
        border: 1px solid #ddd;
        padding: 3px;
        background: white;
        transition: all 0.3s ease;
    }

    .thumb:hover {
        border-color: #139b3a;
    }

    .thumb.active {
        border-color: #139b3a;
        border-width: 2px;
    }

    .details {
        flex: 1;
        padding: 20px;
    }

    .product-title {
        font-size: 2.2em;
        font-weight: bold;
        color: #333;
        margin-bottom: 15px;
    }

    .product-description {
        font-size: 1.1em;
        color: #555;
        margin-bottom: 25px;
    }

    .price {
        font-size: 1.6em;
        font-weight: bold;
        color: #e74c3c;
        margin-bottom: 25px;
    }

    .vote {
        font-size: 1.1em;
        margin-bottom: 20px;
        color: #2ecc71;
    }

    h4,
    h5 {
        font-weight: bold;
        color: #333;
        margin-top: 20px;
        margin-bottom: 10px;
    }

    h3 {
        font-size: 1.8em;
        border-bottom: 2px solid #ddd;
        padding-bottom: 5px;
    }

    h4 {
        font-size: 1.5em;
        margin-top: 15px;
        color: #555;
    }

    h5 {
        font-size: 1.3em;
        margin-top: 10px;
        color: #777;
    }

    .product-details {
        list-style-type: none;
        padding: 0;
        margin: 20px 0;
    }

    .product-details li {
        font-size: 1em;
        margin: 8px 0;
    }

    .product-details li strong {
        color: #333;
    }

    .form-group {
        margin-top: 20px;
    }

    #soluong {
        width: 100px;
        padding: 8px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .action button {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 5px;
        cursor: pointer;
        margin-right: 10px;
        font-size: 1.2em;
        transition: background-color 0.3s ease;
    }

    .action button:hover {
        background-color: #218838;
    }

    .like {
        background-color: #f8f9fa;
        border: 1px solid #ccc;
        color: #333;
        padding: 12px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1.3em;
        transition: color 0.3s ease;
    }

    .like:hover {
        color: #e74c3c;
    }

    .float-right a {
        color: white;
        font-size: 1em;
    }

    .mua {
        background-color: #28a745;
        /* Màu xanh lá cây */
        color: white;
        /* Màu chữ trắng */
        border: none;
        /* Bỏ viền */
        padding: 10px 20px;
        /* Khoảng cách bên trong nút */
        border-radius: 5px;
        /* Bo tròn góc */
        cursor: pointer;
        /* Đổi biểu tượng chuột thành hình tay */
        font-size: 16px;
        /* Kích thước chữ */
        transition: background-color 0.3s ease;
        /* Hiệu ứng chuyển màu khi hover */
    }

    .mua:hover {
        background-color: #218838;
        /* Màu xanh lá cây đậm hơn khi hover */
    }

    @media (max-width: 768px) {
        .wrapper {
            flex-direction: column;
        }

        .preview,
        .details {
            width: 100%;
        }

        .product-title {
            font-size: 1.8em;
        }

        .product-description {
            font-size: 1em;
        }

        .price {
            font-size: 1.4em;
        }

        .vote {
            font-size: 1.1em;
        }

        .form-group {
            width: 100%;
        }

        .form-control {
            width: 100%;
        }

        .main-image img {
            max-height: 300px;
        }

        .thumb {
            width: 80px;
            height: 60px;
        }
    }
</style>

<body>
    <header>
        <div class="header-middle">
            <div class="container">
                <div class="header-middle-left">
                    <div class="header-logo">
                        <a href="index.php" id="logo">
                            <img src="image/logo.png" alt="BMT">
                        </a>
                    </div>
                </div>
                <div class="header-middle-center">
                    <form action="timkiem.php" method="GET" class="form-search">
                            <button type="submit" class="search-btn">
                                <i class="fa-light fa-magnifying-glass"></i>
                            </button>
                        <input type="text" name="tukhoa" class="form-search-input" id="searchBox" placeholder="Tìm kiếm xe... ">
                    </form>
                </div>
                <div class="header-middle-right">
                    <ul class="header-middle-right-list">
                        <li class="header-middle-right-item open">
                            <i class="fa-light fa-user" style="color:#139b3a"></i>

                            <div class="auth-container">
                                <div class="user-info">
                                    <div class="dropdownb">
                                        <h1 class="welcome">
                                            <div class="drip" style="display:flex;flex-direction:column">

                                                <!-- Dòng trên -->
                                                <span style="font-size:12px">
                                                    <?php
                                                    if (isset($_SESSION['customer_id'])) {
                                                        echo "Tài khoản";
                                                    } else {
                                                        echo "Đăng nhập/ Đăng ký";
                                                    }
                                                    ?>
                                                </span>

                                                <!-- Dòng dưới -->

                                                <span class="dropdownb-toggle" style="color:green;font-size:17px">


                                                    <?php
                                                    if (isset($_SESSION['customer_id'])) {
                                                        // Lấy tên khách hàng
                                                        $stmt = $conn->prepare("SELECT name FROM customer WHERE id = ?");
                                                        $stmt->bind_param("i", $_SESSION['customer_id']);
                                                        $stmt->execute();
                                                        $stmt->bind_result($name);
                                                        if ($stmt->fetch()) {
                                                            echo htmlspecialchars($name);
                                                        } else {
                                                            echo "Tài khoản";
                                                        }
                                                        $stmt->close();
                                                    } else {
                                                        echo "Tài khoản";
                                                    }
                                                    ?>
                                                    <i class="fa-sharp fa-solid fa-caret-down" style="font-size:12px;"></i>
                                                </span>
                                            </div>
                                        </h1>

                                        <!-- Dropdown -->
                                        <div class="dropdownb-menu">
                                            <?php if (isset($_SESSION['customer_id'])): ?>
                                                <a href="dnurl.php">
                                                    <div class="hd"><i class="fa-light fa-gear" style="font-size:20px"></i> Quản lý</div>
                                                </a>
                                                <a href="thongtintk.php">
                                                    <div class="hd"><i class="fa-light fa-circle-user" style="font-size:20px"></i> Tài khoản của tôi</div>
                                                </a>
                                                <a href="hoadon.php">
                                                    <div class="hd"><i class="fa-regular fa-bags-shopping" style="font-size:20px"> </i> Đơn hàng đã mua</div>
                                                </a>

                                                <a href="index.php?logout=1" onclick="return confirm('Bạn có muốn đăng xuất?')">
                                                    <div class="hd"><i class="fa-light fa-right-from-bracket" style="font-size:20px"></i> Đăng xuất</div>
                                                </a>
                                            <?php else: ?>
                                                <a href="dn.php">
                                                    <div class="hd"><i class="fa-light fa-right-to-bracket" style="font-size:20px;color:green"></i> Đăng nhập</div>
                                                </a>
                                                <a href="dk.php">
                                                    <div class="hd"><i class="fa-light fa-user-plus" style="font-size:20px;color:green"></i> Đăng ký</div>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="hoadon">
                                    <a href="giohang.php" style="text-decoration: none; color: inherit;">
                                        <span class="ravao">
                                            <i class="fa-light fa-basket-shopping"></i> Giỏ hàng
                                        </span>
                                    </a>
                                </div>

                            </div>
                        </li>
                    </ul>
                </div>


            </div>
        </div>
    </header>

    <div class="green-line-header"></div>

    <nav class="header-bottom">
        <div class="container">
            <ul class="menu-list">
                <li class="menu-list-item"><a href="index.php" class="menu-link">Trang chủ</a></li>

                <div class="dropdown">
                    <span>Sản phẩm</span>
                    <div class="dropdown-content">
                        <li class="menu-list-item"> <a href="index.php?category=Dòng Ninja" class="menu-link <?php echo $selected_category == 'Dòng Ninja' ? 'active' : ''; ?>">
                                Dòng Ninja
                            </a></li>
                        <li class="menu-list-item"> <a href="index.php?category=Dòng Z" class="menu-link <?php echo $selected_category == 'Dòng Ninja' ? 'active' : ''; ?>">
                                Dòng Z
                            </a></li>
                        <li class="menu-list-item"><a href="index.php?category=Dòng KLX" class="menu-link <?php echo $selected_category == 'Dòng KLX' ? 'active' : ''; ?>">
                                Dòng KLX
                            </a></li>
                    </div>
                </div>
                <li class="menu-list-item"><a href="index.php" class="menu-link">Tin tức </a></li>
                <li class="menu-list-item"><a href="index.php" class="menu-link">Điều khoản</a></li>

            </ul>
        </div>
    </nav>




    <main role="main">
        <div class="container mt-4">
            <div class="card2">
                <div class="container-fliud">
                    <form name="frmsanphamchitiet11" id="frmsanphamchitiet11" method="POST">
                        <div class="wrapper row">
                            <div class="preview col-md-6">
                                <div class="main-image">
                                    <div class="zoom-controls">
                                        <button type="button" onclick="zoomIn()" class="zoom-btn">
                                            <i class="fa-light fa-plus"></i>
                                        </button>
                                        <button type="button" onclick="zoomOut()" class="zoom-btn">
                                            <i class="fa-light fa-minus"></i>
                                        </button>
                                    </div>
                                    <img id="mainImage" src="sanpham/<?php echo $row['hinhanh']; ?>" alt="<?php echo $row['tensp']; ?>">
                                </div>
                                <div class="thumbnail-section">
                                    <p class="thumbnail-title">CHỌN GÓC NHÌN</p>
                                    <div class="thumbnail-images">
                                        <img class="thumb active" src="sanpham/<?php echo $row['hinhanh']; ?>" alt="<?php echo $row['tensp']; ?>" onclick="changeImage(this)">
                                        <img class="thumb" src="sanpham/<?php echo $row['hinhanh2']; ?>" alt="<?php echo $row['tensp']; ?>" onclick="changeImage(this)">
                                        <img class="thumb" src="sanpham/<?php echo $row['hinhanh3']; ?>" alt="<?php echo $row['tensp']; ?>" onclick="changeImage(this)">
                                    </div>
                                </div>
                            </div>
                            <div class="details col-md-6">
                                <h3><?php echo $row["tensp"]; ?></h3>
                                <div class="price"><?php echo number_format($row["giaban"], 0, ',', '.'); ?>đ</div>

                                <?php
                                // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
                                $in_cart = false;
                                if (isset($_SESSION['customer_id'])) {
                                    $check_cart = $conn->prepare("SELECT id FROM giohang WHERE customer_id = ? AND product_id = ?");
                                    $check_cart->bind_param("ii", $_SESSION['customer_id'], $row["id"]);
                                    $check_cart->execute();
                                    $check_result = $check_cart->get_result();
                                    $in_cart = $check_result->num_rows > 0;
                                    $check_cart->close();
                                }
                                ?>

                                <div class="display" style="display:flex;">
                                    <button type="button"
                                        class="mua cart-button <?php echo $in_cart ? 'in-cart' : ''; ?>"
                                        data-product-id="<?php echo $row["id"]; ?>"
                                        data-product-price="<?php echo $row["giaban"]; ?>"
                                        data-product-img="<?php echo $row["hinhanh"]; ?>">
                                        <?php echo $in_cart ? '- Xóa khỏi giỏ hàng' : '+ Thêm vào giỏ hàng'; ?>
                                    </button>
                                </div>

                                <p class="vote"><?php echo $row["thongtinsp"]; ?></p>
                                <h4>Thông số kỹ thuật</h4>
                                <ul class="product-details">
                                    <li><?php echo nl2br($row["thongsokt"]); ?></li>
                                </ul>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php' ?>
    <!-- <script src="js/hoadon.js"></script> -->
    <script src="js/giohang.js"></script>
    <script src="js/phantrang.js"></script>
    <script src="js/ssbutton.js"></script>
    <script>
        function changeImage(element) {
            // Cập nhật ảnh chính
            document.getElementById('mainImage').src = element.src;

            // Xóa class active từ tất cả thumbnails
            document.querySelectorAll('.thumb').forEach(thumb => {
                thumb.classList.remove('active');
            });

            // Thêm class active vào thumbnail được chọn
            element.classList.add('active');
        }
    </script>

    <script>
        let currentZoom = 1;
        const ZOOM_STEP = 0.1;
        const MAX_ZOOM = 1.5;
        const MIN_ZOOM = 1;

        function zoomIn() {
            if (currentZoom < MAX_ZOOM) {
                currentZoom = Math.min(currentZoom + ZOOM_STEP, MAX_ZOOM);
                updateZoom();
            }
        }

        function zoomOut() {
            if (currentZoom > MIN_ZOOM) {
                currentZoom = Math.max(currentZoom - ZOOM_STEP, MIN_ZOOM);
                updateZoom();
            }
        }

        function updateZoom() {
            const mainImage = document.getElementById('mainImage');
            mainImage.style.transform = `scale(${currentZoom})`;
        }
    </script>

    <script>
        const isLoggedIn = <?php echo isset($_SESSION['customer_id']) ? 'true' : 'false'; ?>;
    </script>

</body>
<?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<script>if(window.history.replaceState){window.history.replaceState(null, null, window.location.href);}</script>";
}
?>
<?php $conn->close(); ?>

</html>