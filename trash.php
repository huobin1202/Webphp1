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
                <a href="#" class="channel-logo"><img src="../img/logo.png" alt="Channel Logo"></a>
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
                    <a href="#" class="channel-logo"><img src="../img/logo.png" alt="Channel Logo"></a>
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
                                        <img src="../img/<?= $detail['hinhanh'] ?>" alt="">
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

