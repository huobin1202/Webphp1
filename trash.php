<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='../img/logo.png' rel='icon' type='image/x-icon' />
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
                <a href="#" class="channel-logo"><img src="../image/logo.png" alt="Channel Logo"></a>
                <div class="hidden-sidebar your-channel"><img src=""
                        style="height: 30px;" alt="">
                </div>
            </div>
            <div class="middle-sidebar">
                <ul class="sidebar-list">
                    <li class="sidebar-list-item tab-content ">
                        <a href="/admin.php" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-light fa-house"></i></div>
                            <div class="hidden-sidebar">Trang tổng quan</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item tab-content">
                        <a href="/sanpham/sanpham.php" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-thin fa-motorcycle"></i></div>
                            <div class="hidden-sidebar">Sản phẩm</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item tab-content ">
                        <a href="/khachhang/khachhang.php" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-light fa-users"></i></div>
                            <div class="hidden-sidebar">Khách hàng</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item tab-content active">
                        <a href="/donhang/donhang.php" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-light fa-basket-shopping"></i></div>
                            <div class="hidden-sidebar">Đơn hàng</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item tab-content">
                        <a href="/thongkesp/thongke.php" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-light fa-chart-simple"></i></div>
                            <div class="hidden-sidebar">Thống kê sản phẩm</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item tab-content">
                        <a href="/thongkekh/thongkekh.php" class="sidebar-link">
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
                        <a href="#" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-light fa-arrow-right-from-bracket"></i></div>
                            <div class="hidden-sidebar" id="logoutacc">Đăng xuất</div>
                        </a>
                    </li>
                </ul>
            </div>

        </aside>
        <main class="content">
            <!-- Order  -->
            <div class="section">
                <div class="admin-control">
                    <div class="admin-control-left">
                        <select name="tinh-trang" id="tinh-trang">
                            <option value="2">Tất cả</option>
                            <option value="1">Đã xử lý</option>
                            <option value="0">Chưa xử lý</option>
                        </select>
                    </div>
                    <div class="admin-control-center">
                        <form action="" class="form-search">
                            <span class="search-btn"><i class="fa-light fa-magnifying-glass"></i></span>
                            <input id="form-search-order" type="text" class="form-search-input"
                                placeholder="Tìm kiếm mã đơn, mã khách hàng, tên khách hàng...">
                        </form>
                    </div>
                    <div class="admin-control-right">
                        <form action="" class="fillter-date">
                            <div>
                                <label for="time-start">Từ</label>
                                <input type="date" class="form-control-date" id="time-start" >
                            </div>
                            <div>
                                <label for="time-end">Đến</label>
                                <input type="date" class="form-control-date" id="time-end" >
                            </div>
                        </form>
                        <button class="btn-reset-order"><i class="fa-light fa-arrow-rotate-right"></i></button>
                    </div>
                </div>
                <div class="table">
                    <table width="100%">
                        <thead>
                            <tr>
                                <td>Mã DH</td>
                                <td>Mã KH</td>

                                <td>Tên khách hàng</td>
                                <td>Ngày đặt</td>
                                <td>Tổng tiền</td>
                                <td>Trạng thái</td>
                                <td>Thao tác</td>
                            </tr>
                        </thead>
                        <?php
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

                        $sql = "SELECT 
                            orders.id AS order_id, 
                            orders.customer_id, 
                            orders.total, 
                            orders.created_at, 
                            orders.status, 
                            customer.name AS customer_name
                        FROM orders
                        INNER JOIN customer ON orders.customer_id = customer.id
                        ORDER BY orders.created_at DESC";

                        $result = $conn->query($sql);
                        ?>

                        <tbody id="showOrder">
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $orderId = $row['order_id'];
                                    $customerId = str_pad($row['customer_id'], 2, '0', STR_PAD_LEFT);
                                    $customerName = htmlspecialchars($row['customer_name']);
                                    $createdDate = date('d/m/Y', strtotime($row['created_at']));
                                    $total = number_format($row['total'], 0, ',', '.') . 'đ';
                                    $status = $row['status'] == 1
                                        ? '<span class="status-complete">Đã xử lý</span>'
                                        : '<span class="status-no-complete">Chưa xử lý</span>';
                                        $detailLink = "chitietdonhang.php?order_id=" . $row['order_id'];
                                        ?>
                                    <tr>
                                        <td><?= $orderId ?></td>
                                        <td><?= $customerId ?></td>
                                        <td><?= $customerName ?></td>
                                        <td><?= $createdDate ?></td>
                                        <td><?= $total ?></td>
                                        <td><?= $status ?></td>
                                        <td class="control">
                                            <a href="<?= $detailLink ?>"><button class="btn-detail"><i class="fa-regular fa-eye"></i> Chi tiết</button></a>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='7'>Không có đơn hàng nào.</td></tr>";
                            }
                            ?>
                        </tbody>

                        <?php
                        $conn->close();
                        ?>

                    </table>
                </div>
            </div>

        </main>
    </div>
    

    <script src="../assets/js/admin.js"></script>
</body>

</html>






















<?php
// File: donhang/chitietdonhang.php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "admindoan";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy order_id
if (!isset($_GET['order_id'])) {
    die("Thiếu mã đơn hàng");
}
$order_id = intval($_GET['order_id']);

// Nếu có submit form cập nhật
if (isset($_POST['mark_processed'])) {
    $update = $conn->prepare("UPDATE orders SET status = 1 WHERE id = ?");
    $update->bind_param("i", $order_id);
    $update->execute();
}

// Lấy thông tin đơn hàng (cập nhật lại dữ liệu mới nhất)
$sql_order = "SELECT orders.*, customer.name AS customer_name
              FROM orders
              INNER JOIN customer ON orders.customer_id = customer.id
              WHERE orders.id = ?";
$stmt = $conn->prepare($sql_order);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();

if ($order_result->num_rows == 0) {
    die("Đơn hàng không tồn tại");
}
$order = $order_result->fetch_assoc();

// Lấy chi tiết sản phẩm
$sql_details = "SELECT order_details.*, products.tensp, products.hinhanh 
                FROM order_details
                INNER JOIN products ON order_details.product_id = products.id
                WHERE order_details.order_id = ?";
$stmt_details = $conn->prepare($sql_details);
$stmt_details->bind_param("i", $order_id);
$stmt_details->execute();
$details_result = $stmt_details->get_result();
?>
<!DOCTYPE php>
<php lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href='../img/logo.png' rel='icon' type='image/x-icon' />
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
                    <a href="#" class="channel-logo"><img src="../image/logo.png" alt="Channel Logo"></a>
                    <div class="hidden-sidebar your-channel"><img src=""
                            style="height: 30px;" alt="">
                    </div>
                </div>
                <div class="middle-sidebar">
                    <ul class="sidebar-list">
                        <li class="sidebar-list-item tab-content ">
                            <a href="/admin.php" class="sidebar-link">
                                <div class="sidebar-icon"><i class="fa-light fa-house"></i></div>
                                <div class="hidden-sidebar">Trang tổng quan</div>
                            </a>
                        </li>
                        <li class="sidebar-list-item tab-content">
                            <a href="/sanpham/sanpham.php" class="sidebar-link">
                                <div class="sidebar-icon"><i class="fa-thin fa-motorcycle"></i></div>
                                <div class="hidden-sidebar">Sản phẩm</div>
                            </a>
                        </li>
                        <li class="sidebar-list-item tab-content ">
                            <a href="/khachhang/khachhang.php" class="sidebar-link">
                                <div class="sidebar-icon"><i class="fa-light fa-users"></i></div>
                                <div class="hidden-sidebar">Khách hàng</div>
                            </a>
                        </li>
                        <li class="sidebar-list-item tab-content active">
                            <a href="/donhang/donhang.php" class="sidebar-link">
                                <div class="sidebar-icon"><i class="fa-light fa-basket-shopping"></i></div>
                                <div class="hidden-sidebar">Đơn hàng</div>
                            </a>
                        </li>
                        <li class="sidebar-list-item tab-content">
                            <a href="/thongkesp/thongke.php" class="sidebar-link">
                                <div class="sidebar-icon"><i class="fa-light fa-chart-simple"></i></div>
                                <div class="hidden-sidebar">Thống kê sản phẩm</div>
                            </a>
                        </li>
                        <li class="sidebar-list-item tab-content">
                            <a href="/thongkekh/thongkekh.php" class="sidebar-link">
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
                            <a href="#" class="sidebar-link">
                                <div class="sidebar-icon"><i class="fa-light fa-arrow-right-from-bracket"></i></div>
                                <div class="hidden-sidebar" id="logoutacc">Đăng xuất</div>
                            </a>
                        </li>
                    </ul>
                </div>

            </aside>
            <main class="content">
                <!-- Order  -->
                <div class="section">
                    <div class="admin-control">
                        <div class="admin-control-left">
                            <select name="tinh-trang" id="tinh-trang">
                                <option value="2">Tất cả</option>
                                <option value="1">Đã xử lý</option>
                                <option value="0">Chưa xử lý</option>
                            </select>
                        </div>
                        <div class="admin-control-center">
                            <form action="" class="form-search">
                                <span class="search-btn"><i class="fa-light fa-magnifying-glass"></i></span>
                                <input id="form-search-order" type="text" class="form-search-input"
                                    placeholder="Tìm kiếm mã đơn, mã khách hàng, tên khách hàng...">
                            </form>
                        </div>
                        <div class="admin-control-right">
                            <form action="" class="fillter-date">
                                <div>
                                    <label for="time-start">Từ</label>
                                    <input type="date" class="form-control-date" id="time-start">
                                </div>
                                <div>
                                    <label for="time-end">Đến</label>
                                    <input type="date" class="form-control-date" id="time-end">
                                </div>
                            </form>
                            <button class="btn-reset-order"><i class="fa-light fa-arrow-rotate-right"></i></button>
                        </div>
                    </div>
                    <div class="table">
                        <table width="100%">
                            <thead>
                                <tr>
                                    <td>Mã DH</td>
                                    <td>Mã KH</td>

                                    <td>Tên khách hàng</td>
                                    <td>Ngày đặt</td>
                                    <td>Tổng tiền</td>
                                    <td>Trạng thái</td>
                                    <td>Thao tác</td>
                                </tr>
                            </thead>
                            <?php
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

                            $sql = "SELECT 
                            orders.id AS order_id, 
                            orders.customer_id, 
                            orders.total, 
                            orders.created_at, 
                            orders.status, 
                            customer.name AS customer_name
                        FROM orders
                        INNER JOIN customer ON orders.customer_id = customer.id
                        ORDER BY orders.created_at DESC";

                            $result = $conn->query($sql);
                            ?>

                            <tbody id="showOrder">
                                <?php
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $orderId = $row['order_id'];
                                        $customerId = str_pad($row['customer_id'], 2, '0', STR_PAD_LEFT);
                                        $customerName = htmlspecialchars($row['customer_name']);
                                        $createdDate = date('d/m/Y', strtotime($row['created_at']));
                                        $total = number_format($row['total'], 0, ',', '.') . 'đ';
                                        $status = $row['status'] == 1
                                            ? '<span class="status-complete">Đã xử lý</span>'
                                            : '<span class="status-no-complete">Chưa xử lý</span>';
                                        $detailLink = "chitietdonhang.php?order_id=" . $row['order_id'];
                                ?>
                                        <tr>
                                            <td><?= $orderId ?></td>
                                            <td><?= $customerId ?></td>
                                            <td><?= $customerName ?></td>
                                            <td><?= $createdDate ?></td>
                                            <td><?= $total ?></td>
                                            <td><?= $status ?></td>
                                            <td class="control">
                                                <a href="<?= $detailLink ?>"><button class="btn-detail"><i class="fa-regular fa-eye"></i> Chi tiết</button></a>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='7'>Không có đơn hàng nào.</td></tr>";
                                }
                                ?>
                            </tbody>

                            <?php
                            $conn->close();
                            ?>

                        </table>
                    </div>
                </div>

            </main>
        </div>


        <div class="modal detail-order open">
            <div class="modal-container">
                <h3 class="modal-container-title">CHI TIẾT ĐƠN HÀNG #<?= $order_id ?></h3>
                <a href="donhang.php"><button class="modal-close"><i class="fa-regular fa-xmark"></i></button></a>

                <div class="modal-detail-order">
                    <!-- LEFT: Danh sách sản phẩm -->
                    <div class="modal-detail-left">
                        <div class="order-item-group">
                            <?php while ($detail = $details_result->fetch_assoc()): ?>
                                <div class="order-product">
                                    <div class="order-product-left">
                                        <img src="../image/<?= $detail['hinhanh'] ?>" alt="">
                                        <div class="order-product-info">
                                            <h4><?= htmlspecialchars($detail['tensp']) ?></h4>
                                            <p class="order-product-quantity">SL: <?= $detail['soluong'] ?></p>
                                        </div>
                                    </div>
                                    <div class="order-product-right">
                                        <div class="order-product-price">
                                            <span class="order-product-current-price"><?= number_format($detail['price'], 0, ',', '.') ?>đ</span>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>

                    <!-- RIGHT: Thông tin khách hàng -->
                    <div class="modal-detail-right">
                        <ul class="detail-order-group">
                            <li class="detail-order-item">
                                <span class="detail-order-item-left"><i class="fa-light fa-calendar-days"></i> Ngày đặt hàng</span>
                                <span class="detail-order-item-right"><?= date('d/m/Y', strtotime($order['created_at'])) ?></span>
                            </li>
                            <li class="detail-order-item">
                                <span class="detail-order-item-left"><i class="fa-light fa-truck"></i> Hình thức giao</span>
                                <span class="detail-order-item-right"><?= htmlspecialchars($order['delivery_type']) ?></span>
                            </li>
                            <li class="detail-order-item">
                                <span class="detail-order-item-left"><i class="fa-thin fa-person"></i> Người nhận</span>
                                <span class="detail-order-item-right"><?= htmlspecialchars($order['recipient_name']) ?></span>
                            </li>
                            <li class="detail-order-item">
                                <span class="detail-order-item-left"><i class="fa-light fa-phone"></i> Số điện thoại</span>
                                <span class="detail-order-item-right"><?= htmlspecialchars($order['recipient_phone']) ?></span>
                            </li>
                            <li class="detail-order-item tb">
                                <span class="detail-order-item-t"><i class="fa-light fa-location-dot"></i> Địa chỉ nhận</span>
                                <p class="detail-order-item-b"><?= htmlspecialchars($order['address']) ?></p>
                            </li>
                            <li class="detail-order-item tb">
                                <span class="detail-order-item-t"><i class="fa-light fa-note-sticky"></i> Ghi chú</span>
                                <p class="detail-order-item-b"><?= htmlspecialchars($order['note']) ?: 'Không có' ?></p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="modal-detail-bottom">
        <div class="modal-detail-bottom-left">
            <div class="price-total">
                <span class="thanhtien">Thành tiền</span>
                <span class="price"><?= number_format($order['total'], 0, ',', '.') ?>đ</span>
            </div>
        </div>
        <div class="modal-detail-bottom-right">
            <form method="post">
                <?php if ($order['status'] == 0): ?>
                    <button name="mark_processed" class="modal-detail-btn btn-chuaxuly">Chưa xử lý</button>
                <?php else: ?>
                    <button disabled class="modal-detail-btn btn-daxuly">Đã xử lý</button>
                <?php endif; ?>
            </form>
        </div>
    </div>
            </div>
        </div>      
    </body>
    
</php>

<?php
session_start();
include('database.php');

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
    $stmt = $conn->prepare("SELECT id FROM customer WHERE name = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($customer_id);
    if ($stmt->fetch()) {
        $_SESSION["customer_id"] = $customer_id;
    }
    $stmt->close();
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
                                <a href="hoadon.php">
                                    <div class="hd">Đơn hàng đã mua</div>
                                </a>
                                <a href="dnurl.php">
                                    <div class="hd"><i class="fa-light fa-gear" style="font-size:20px"></i> Quản lý</div>
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
                    <span class="ravao">
                        <i class="fa-light fa-basket-shopping"></i> Giỏ hàng
                    </span>
                </div>

            </div>
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
                                <form method="POST" action="">
                                    <div class="card page-1" id="invoiceModal">
                                        <a href="thongtinsp.php?id=' . $row["id"] . '">
                                            <img src="sanpham/' . $row["hinhanh"] . '" alt="' . $row["tensp"] . '">
                                        </a>
                                        <h3>' . $row["tensp"] . '</h3>
                                        <div class="greenSpacer"></div>
                                        <div class="price">' . number_format($row["giaban"], 0, ',', '.') . 'đ</div>

                                        <input type="hidden" name="product_id" value="' . $row["id"] . '">
                                        <input type="hidden" name="product_name" value="' . htmlspecialchars($row["tensp"]) . '">
                                        <input type="hidden" name="product_price" value="' . $row["giaban"] . '">
                                        <input type="hidden" name="product_img" value="' . $row["hinhanh"] . '">
                                        <div class=display style="display:flex;">
                                        <button type="submit" class="mua" name="add_to_cart">Thêm vào giỏ hàng</button>
                                        <input type="number" min="1" value="1" name="quantity" class="num-input"></input>
                                        </div>
                                    
                                    </div>
                                </form>';
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
            <?php
            if (isset($_POST['add_to_cart'])) {
                if (!$customer_id) {
                    die("Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng!");
                }

                $product_id = $_POST['product_id'];
                $product_price = $_POST['product_price'];
                $product_img = $_POST['product_img'];
                $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

                // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng chưa
                $stmt = $conn->prepare("SELECT soluong FROM giohang WHERE product_id = ? AND customer_id = ?");
                $stmt->bind_param("ii", $product_id, $customer_id);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $stmt->close();
                } else {
                    $stmt = $conn->prepare("INSERT INTO giohang (customer_id, product_id, soluong, price, img) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("iiiss", $customer_id, $product_id, $quantity, $product_price, $product_img);
                    $stmt->execute();
                    $stmt->close();
                }
            }


            ?>

            <table>
                <thead>
                    <tr>
                        <th>
                            Sản phẩm
                        </th>
                        <th>Tên</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Chọn</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Kiểm tra xem người dùng đã đăng nhập chưa
                    if (!isset($_SESSION['customer_id'])) {
                        echo "<tr><td colspan='4'>Bạn cần đăng nhập để xem giỏ hàng!</td></tr>";
                    } else {
                        $customer_id = $_SESSION['customer_id'];

                        // Truy vấn lấy sản phẩm từ bảng giỏ hàng
                        $sql = "SELECT g.id, g.product_id, g.soluong, g.price, g.img, p.tensp 
                                FROM giohang g
                                JOIN products p ON g.product_id = p.id
                                WHERE g.customer_id = ?";

                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $customer_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        $total_price = 0;

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $subtotal = $row["soluong"] * $row["price"];
                                $total_price += $subtotal;
                                echo '
                                <tr>
                                    <td style="display: flex; align-items: center;">
                                        <img style="width: 90px;" src="sanpham/' . $row["img"] . '" alt="' . htmlspecialchars($row["tensp"]) . '">
                                    </td>
                                    <td><span>' . htmlspecialchars($row["tensp"]) . '</span></td>
                                    <td>
                                        <p><span>' . number_format($row["price"], 0, ',', '.') . '</span><sup>đ</sup></p>
                                    </td>
                                    <td>
                                        <input style="width: 40px; outline: none;" type="number" value="' . $row["soluong"] . '" min="1" class="cart-quantity" data-cart-id="' . $row["id"] . '">
                                    </td>
                                    <td style="cursor: pointer;" class="delete-item" data-cart-id="' . $row["id"] . '">Xóa</td>
                                </tr>';
                            }
                        } else {
                            echo "<tr><td colspan='4'>Giỏ hàng của bạn đang trống!</td></tr>";
                        }
                    }
                    ?>
                </tbody>

            </table>
            <div style="text-align: center;" class="price-total">
                <p style="font-weight: bold; margin-top: 10px; margin-bottom: 20px;">
                    Tổng tiền: <span><?php echo number_format($total_price, 0, ',', '.'); ?></span>đ
                </p>
            </div>
            <a class="thanhtoan" href="thanhtoan.php">Thanh toán</a>
        </form>
    </section>

    <div class="green-line-header"></div>
    <?php include 'footer.php' ?>
    <!-- <script src="js/hoadon.js"></script> -->
    <script src="js/giohang.js"></script>
    <script src="js/phantrang.js"></script>
    <script src="js/ssbutton.js"></script>




</body>
<?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<script>if(window.history.replaceState){window.history.replaceState(null, null, window.location.href);}</script>";
}
?>
<?php $conn->close(); ?>

</html>