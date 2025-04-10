<?php
// File: donhang/donhang.php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: dnurl.php");
    exit();
}
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "admindoan";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}


// XỬ LÝ CẬP NHẬT TRẠNG THÁI NẾU POST
if (isset($_POST['process_order'])) {
    $order_id = intval($_POST['order_id']);
    $new_status = $_POST['new_status'];
    $update = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $update->bind_param("si", $new_status, $order_id);
    $update->execute();
    // Redirect để tránh resubmit form
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}


// Lấy tất cả đơn hàng & chi tiết để hiển thị
$sql = "SELECT orders.*, customer.name AS customer_name 
        FROM orders 
        INNER JOIN customer ON orders.customer_id = customer.id 
        WHERE 1=1";
$params = array();

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = '%' . $_GET['search'] . '%';
    $sql .= " AND (orders.id LIKE ? OR customer.id LIKE ? OR customer.name LIKE ?)";
    array_push($params, $search, $search, $search);
}

if (isset($_GET['status']) && $_GET['status'] != '4') {
    $sql .= " AND orders.status = ?";
    array_push($params, $_GET['status']);
}

if (isset($_GET['start_date']) && !empty($_GET['start_date'])) {
    $sql .= " AND orders.created_at >= ?";
    array_push($params, $_GET['start_date']);
}

if (isset($_GET['end_date']) && !empty($_GET['end_date'])) {
    $sql .= " AND orders.created_at <= ?";
    array_push($params, $_GET['end_date']);
}

$sql .= " ORDER BY orders.created_at DESC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $types = str_repeat('s', count($params));
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Lưu tất cả đơn hàng + chi tiết vào mảng
$orders = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orderId = $row['id'];
        // Lấy chi tiết sản phẩm
        $sql_details = "SELECT order_details.*, products.tensp, products.hinhanh 
                        FROM order_details
                        INNER JOIN products ON order_details.product_id = products.id
                        WHERE order_details.order_id = $orderId";
        $details_result = $conn->query($sql_details);
        $details = [];
        while ($detail = $details_result->fetch_assoc()) {
            $details[] = $detail;
        }
        $row['details'] = $details;
        $orders[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Quản lý đơn hàng</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link href="../assets/font/font-awesome-pro-v6-6.2.0/css/all.min.css" rel="stylesheet" />
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
                        <a href="../admin.php" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-light fa-house"></i></div>
                            <div class="hidden-sidebar">Trang tổng quan</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item tab-content">
                        <a href="../sanpham/sanpham.php" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-thin fa-motorcycle"></i></div>
                            <div class="hidden-sidebar">Sản phẩm</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item tab-content ">
                        <a href="../khachhang/khachhang.php" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-light fa-users"></i></div>
                            <div class="hidden-sidebar">Khách hàng</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item tab-content active">
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
                        <a class="sidebar-link" style="cursor:pointer">
                            <div class="sidebar-icon"><i class="fa-light fa-arrow-right-from-bracket"></i></div>
                            <div class="hidden-sidebar" id="logoutacc">Đăng xuất</div>
                        </a>
                    </li>
                </ul>
            </div>

        </aside>
        <main class="content">
            <div class="section">
                <div class="admin-control">
                    <form method="GET" action="" class="admin-control-wrapper" style="width: 100%;display: flex;justify-content: space-between;">
                        <div class="admin-control-left">
                            <select name="status"  class="form-control" onchange="this.form.submit()">
                                <option value="4" <?php echo (isset($_GET['status']) && $_GET['status'] == '4') ? 'selected' : ''; ?>>Tất cả</option>
                                <option value="chuaxuly" <?php echo (isset($_GET['status']) && $_GET['status'] == 'chuaxuly') ? 'selected' : ''; ?>>Chưa xử lý</option>
                                <option value="daxuly" <?php echo (isset($_GET['status']) && $_GET['status'] == 'daxuly') ? 'selected' : ''; ?>>Đã xử lý</option>
                                <option value="dahuy" <?php echo (isset($_GET['status']) && $_GET['status'] == 'dahuy') ? 'selected' : ''; ?>>Đã hủy</option>
                                <option value="dagiao" <?php echo (isset($_GET['status']) && $_GET['status'] == 'dagiao') ? 'selected' : ''; ?>>Đã giao</option>
                            </select>
                        </div>
                        <div class="admin-control-center">
                            <div class="form-search">
                                <span class="search-btn"><i class="fa-light fa-magnifying-glass"></i></span>
                                <input type="text" name="search" class="form-search-input"
                                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                                    placeholder="Tìm kiếm mã đơn, mã khách hàng, tên khách hàng...">
                            </div>
                        </div>
                        <div class="admin-control-right">
                            <div class="fillter-date">
                                <div>
                                    <label for="time-start">Từ</label>
                                    <input type="date" name="start_date" class="form-control-date"
                                        value="<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>">
                                </div>
                                <div>
                                    <label for="time-end">Đến</label>
                                    <input type="date" name="end_date" class="form-control-date"
                                        value="<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>">
                                </div>
                                <button type="submit" class="btn-reset-order"><i class="fa-light fa-filter"></i></button>
                                <button><a href="donhang.php" class="btn-reset-order"><i class="fa-light fa-arrow-rotate-right"></i></a></button>
                            </div>
                        </div>
                    </form>
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
                        <tbody id="showOrder">
                            <?php if (count($orders) > 0): ?>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><?php echo $order['id']; ?></td>
                                        <td><?php echo str_pad($order['customer_id'], 2, '0', STR_PAD_LEFT); ?></td>
                                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($order['created_at'])); ?></td>
                                        <td><?php echo number_format($order['total'], 0, ',', '.') . 'đ'; ?></td>
                                        <td>
                                            <form method="POST" action="update_order_status.php" class="status-form">
                                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                                <select name="new_status" onchange="this.form.submit()">
                                                    <option value="chuaxuly" <?php echo $order['status'] == 'chuaxuly' ? 'selected' : ''; ?>>Chưa xử lý</option>
                                                    <option value="daxuly" <?php echo $order['status'] == 'daxuly' ? 'selected' : ''; ?>>Đã xử lý</option>
                                                    <option value="dahuy" <?php echo $order['status'] == 'dahuy' ? 'selected' : ''; ?>>Đã hủy</option>
                                                    <option value="dagiao" <?php echo $order['status'] == 'dagiao' ? 'selected' : ''; ?>>Đã giao</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td class="control">
                                            <form method="GET" action="" style="display: inline;">
                                                <input type="hidden" name="view_order" value="<?php echo $order['id']; ?>">
                                                <button type="submit" class="btn-detail">
                                                    <i class="fa-regular fa-eye"></i> Chi tiết
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="no-products">Không có đơn hàng nào!</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

        <!-- MODAL -->
        <?php if (isset($_GET['view_order'])): ?>
            <?php
            $order_id = $_GET['view_order'];
            $current_order = null;
            foreach ($orders as $order) {
                if ($order['id'] == $order_id) {
                    $current_order = $order;
                    break;
                }
            }
            if ($current_order):
            ?>
                <div class="modal detail-order open">
                    <div class="modal-container">
                        <h3 class="modal-container-title">CHI TIẾT ĐƠN HÀNG #<?php echo $order_id; ?></h3>
                        <a href="donhang.php" class="modal-close"><i class="fa-regular fa-xmark"></i></a>
                        <div class="modal-detail-order">
                            <div class="modal-detail-left">
                                <div class="order-item-group">
                                    <?php foreach ($current_order['details'] as $detail): ?>
                                        <div class="order-product">
                                            <div class="order-product-left">
                                                <img src="../sanpham/<?php echo htmlspecialchars($detail['hinhanh']); ?>" alt="">
                                                <div class="order-product-info">
                                                    <h4><?php echo htmlspecialchars($detail['tensp']); ?></h4>
                                                    <p class="order-product-quantity">SL: <?php echo $detail['soluong']; ?></p>
                                                </div>
                                            </div>
                                            <div class="order-product-right">
                                                <div class="order-product-price">
                                                    <span class="order-product-current-price">
                                                        <?php echo number_format($detail['price'], 0, ',', '.') . 'đ'; ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="modal-detail-right">
                                <ul class="detail-order-group">
                                    <li class="detail-order-item">
                                        <span class="detail-order-item-left"><i class="fa-light fa-calendar-days"></i> Ngày đặt</span>
                                        <span class="detail-order-item-right"><?php echo date('d/m/Y', strtotime($current_order['created_at'])); ?></span>
                                    </li>
                                    <li class="detail-order-item">
                                        <span class="detail-order-item-left"><i class="fa-light fa-truck"></i> Hình thức giao</span>
                                        <span class="detail-order-item-right"><?php echo htmlspecialchars($current_order['delivery_type']); ?></span>
                                    </li>
                                    <li class="detail-order-item">
                                        <span class="detail-order-item-left"><i class="fa-thin fa-person"></i> Người nhận</span>
                                        <span class="detail-order-item-right"><?php echo htmlspecialchars($current_order['recipient_name']); ?></span>
                                    </li>
                                    <li class="detail-order-item">
                                        <span class="detail-order-item-left"><i class="fa-light fa-phone"></i> Số điện thoại</span>
                                        <span class="detail-order-item-right"><?php echo htmlspecialchars($current_order['recipient_phone']); ?></span>
                                    </li>
                                    <li class="detail-order-item tb">
                                        <span class="detail-order-item-left"><i class="fa-light fa-clock"></i> Thời gian giao</span>
                                        <p class="detail-order-item-b"><?php echo date('d/m/Y', strtotime($current_order['created_at'])); ?></p>
                                    </li>
                                    <li class="detail-order-item tb">
                                        <span class="detail-order-item-t"><i class="fa-light fa-location-dot"></i> Địa chỉ</span>
                                        <p class="detail-order-item-b"><?php echo htmlspecialchars($current_order['address']); ?></p>
                                    </li>
                                    <li class="detail-order-item tb">
                                        <span class="detail-order-item-t"><i class="fa-light fa-note-sticky"></i> Ghi chú</span>
                                        <p class="detail-order-item-b"><?php echo htmlspecialchars($current_order['note']) ?: 'Không có'; ?></p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="modal-detail-bottom">
                            <div class="modal-detail-bottom-left">
                                <div class="price-total">
                                    <span class="thanhtien">Thành tiền</span>
                                    <span class="price"><?php echo number_format($current_order['total'], 0, ',', '.') . 'đ'; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <script src="../assets/js/admin.js"></script>

</body>

</html>