<?php
session_start();
include('database.php');
include('toast.php');
include('logout.php');

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
    <title>Kawakaki </title>
</head>
<style>
    :root {
        --primary-color: #139b3a;
        --secondary-color: #28a745;
        --text-color: #333;
        --light-gray: #f8f9fa;
        --border-color: #ddd;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: var(--text-color);
        line-height: 1.6;
    }

    .card2 {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        margin-top: 30px;
        border: none;
        border-radius: 15px;
        overflow: hidden;
        background: white;
        transition: transform 0.3s ease;
    }

    .card2:hover {
        transform: translateY(-5px);
    }

    .wrapper {
        display: flex;
        flex-wrap: wrap;
        padding: 30px;
        gap: 20px;
    }

    .preview {
        flex: 1;
        min-width: 300px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        background: var(--light-gray);
        border-radius: 10px;
    }

    .main-image {
        width: 100%;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .main-image img {
        width: 100%;
        max-height: 400px;
        object-fit: contain;
        border-radius: 8px;
        transition: transform 0.3s ease;
    }

    .thumbnail-section {
        width: 100%;
        margin-top: 20px;
        border-top: 1px solid var(--border-color);
        padding-top: 20px;
    }

    .thumbnail-title {
        text-align: center;
        font-size: 14px;
        color: #76767c;
        margin-bottom: 15px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .thumbnail-images {
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .thumb {
        width: 100px;
        height: 70px;
        object-fit: cover;
        cursor: pointer;
        border: 2px solid var(--border-color);
        border-radius: 5px;
        transition: all 0.3s ease;
    }

    .thumb:hover {
        border-color: var(--primary-color);
        transform: scale(1.05);
    }

    .thumb.active {
        border-color: var(--primary-color);
        border-width: 2px;
    }

    .details {
        flex: 1;
        padding: 20px;
        min-width: 300px;
    }

    .product-title {
        font-size: 2.2em;
        font-weight: 700;
        color: var(--text-color);
        margin-bottom: 15px;
        line-height: 1.2;
    }

    .price {
        font-size: 1.8em;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 25px;
    }

    .product-description {
        font-size: 1.1em;
        color: #555;
        margin-bottom: 25px;
        line-height: 1.6;
    }

    .mua {
        background-color: var(--primary-color);
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .mua:hover {
        background-color: var(--secondary-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .mua.in-cart {
        background-color: #dc3545;
    }

    .mua.in-cart:hover {
        background-color: #c82333;
    }

    .product-details {
        list-style-type: none;
        padding: 0;
        margin: 20px 0;
    }

    .product-details li {
        font-size: 1em;
        margin: 12px 0;
        padding-left: 20px;
        position: relative;
    }

    .product-details li:before {
        content: "•";
        color: var(--primary-color);
        position: absolute;
        left: 0;
    }

    .zoom-controls {
        position: absolute;
        top: 15px;
        right: 15px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        z-index: 10;
    }
    h4,
    h5 {
        font-weight: bold;
        color: #333;
        margin-top: 20px;
        margin-bottom: 10px;
        font-size: 25px;
    }
    .zoom-btn {
        width: 35px;
        height: 35px;
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid var(--border-color);
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-color);
        transition: all 0.3s ease;
    }

    .zoom-btn:hover {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    @media (max-width: 768px) {
        .wrapper {
            flex-direction: column;
            padding: 15px;
        }

        .preview, .details {
            width: 100%;
            padding: 15px;
        }

        .product-title {
            font-size: 1.8em;
        }

        .price {
            font-size: 1.5em;
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
                                <h4>Giới thiệu sản phẩm</h4>

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