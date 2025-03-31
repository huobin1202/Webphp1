<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: dnurl.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "admindoan";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Lấy các tham số tìm kiếm từ URL
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$sort_order = isset($_GET['sort']) ? $_GET['sort'] : 'desc';
$limit = isset($_GET['limit']) ? ($_GET['limit'] === 'all' ? null : (int)$_GET['limit']) : 5; // Mặc định hiển thị top 5

// Validate date range
if ($start_date && $end_date && $start_date > $end_date) {
    $temp = $start_date;
    $start_date = $end_date;
    $end_date = $temp;
}

// Base query
$query = "SELECT 
    c.id,
    c.name as tenkh,
    COUNT(DISTINCT o.id) as total_orders,
    SUM(od.soluong) as total_quantity,
    SUM(od.soluong * od.price) as total_revenue
FROM customer c
LEFT JOIN orders o ON c.id = o.customer_id
LEFT JOIN order_details od ON o.id = od.order_id
WHERE 1=1";

// Thêm điều kiện tìm kiếm
if (!empty($search_term)) {
    $search_term = $conn->real_escape_string($search_term);
    $query .= " AND (c.name LIKE '%$search_term%' OR c.id LIKE '%$search_term%')";
}

// Thêm điều kiện ngày
if (!empty($start_date)) {
    $query .= " AND DATE(o.created_at) >= '$start_date'";
}
if (!empty($end_date)) {
    $query .= " AND DATE(o.created_at) <= '$end_date'";
}

// Group by và having để lọc ra khách hàng có doanh thu
$query .= " GROUP BY c.id, c.name
HAVING total_revenue > 0
ORDER BY total_revenue " . ($sort_order === 'asc' ? 'ASC' : 'DESC');

if ($limit !== null) {
    $query .= " LIMIT " . $limit;
}

// Thực thi truy vấn
$result = $conn->query($query);

// Tính toán tổng thống kê chỉ cho các khách hàng được hiển thị
$total_customers = 0;
$total_orders = 0;  // Đếm tổng số đơn hàng
$total_revenue = 0;

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $total_customers++;
        $total_orders += $row['total_orders'] ?? 0;  // Đếm tổng số đơn hàng
        $total_revenue += $row['total_revenue'] ?? 0;
    }
    $result->data_seek(0); // Reset con trỏ kết quả
}


// Phần xử lý chi tiết khách hàng khi click vào nút chi tiết
if (isset($_GET['detail'])) {
    $customer_id = intval($_GET['detail']);
    $detail_query = "SELECT 
        o.id as order_id,
        o.created_at,
        o.status,
        o.total as order_total,
        GROUP_CONCAT(p.tensp SEPARATOR ', ') as products,
        SUM(od.soluong) as total_items
    FROM orders o
    LEFT JOIN order_details od ON o.id = od.order_id
    LEFT JOIN products p ON od.product_id = p.id
    WHERE o.customer_id = ?";

    // Thêm điều kiện lọc theo ngày
    if (!empty($start_date)) {
        $detail_query .= " AND DATE(o.created_at) >= ?";
    }
    if (!empty($end_date)) {
        $detail_query .= " AND DATE(o.created_at) <= ?";
    }

    $detail_query .= " GROUP BY o.id ORDER BY o.created_at DESC";
    
    $stmt = $conn->prepare($detail_query);

    // Xử lý bind_param động dựa trên số lượng điều kiện
    $types = "i"; // Bắt đầu với customer_id
    $params = [$customer_id];
    
    if (!empty($start_date)) {
        $types .= "s";
        $params[] = $start_date;
    }
    if (!empty($end_date)) {
        $types .= "s";
        $params[] = $end_date;
    }

    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $customer_details = $stmt->get_result();
}
// Tính toán tổng thống kê chỉ cho các khách hàng được hiển thị

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='../image/logo.png' rel='icon' type='image/x-icon' />
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link href="../assets/font/font-awesome-pro-v6-6.2.0/css/all.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../assets/css/admin-responsive.css">
    <title>Thống kê khách hàng</title>
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
                <div class="hidden-sidebar your-channel"><img src="" style="height: 30px;" alt=""></div>
            </div>
            <div class="middle-sidebar">
                <ul class="sidebar-list">
                    <li class="sidebar-list-item tab-content">
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
                    <li class="sidebar-list-item tab-content active">
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
                        <a href="" class="sidebar-link">
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
                    <div class="admin-control-left">
                        <form action="" method="GET" style="display: inline-block;">
                            <select name="limit" onchange="this.form.submit()" class="form-control">
                                <option value="all" <?php echo !isset($_GET['limit']) || $_GET['limit'] === 'all' ? 'selected' : ''; ?>>Tất cả khách hàng</option>
                                <option value="5" <?php echo isset($_GET['limit']) && $_GET['limit'] === '5' ? 'selected' : ''; ?>>Top 5 khách hàng</option>
                                <option value="10" <?php echo isset($_GET['limit']) && $_GET['limit'] === '10' ? 'selected' : ''; ?>>Top 10 khách hàng</option>
                            </select>
                            <input type="hidden" name="search" value="<?php echo htmlspecialchars($search_term); ?>">
                            <input type="hidden" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
                            <input type="hidden" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
                            <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort_order); ?>">
                        </form>
                    </div>
                    <div class="admin-control-center">
                        <form action="" method="GET" class="form-search">
                            <span class="search-btn"><i class="fa-light fa-magnifying-glass"></i></span>
                            <input name="search" type="text" class="form-search-input"
                                placeholder="Tìm kiếm tên khách hàng..." 
                                value="<?php echo htmlspecialchars($search_term); ?>">
                            <input type="hidden" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
                            <input type="hidden" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
                            <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort_order); ?>">
                            <button type="submit" style="display: none;"></button>
                        </form>
                    </div>
                    <div class="admin-control-right">
                        <form action="" method="GET" class="fillter-date">
                            <div>
                                <label for="start_date">Từ</label>
                                <input type="date" class="form-control-date" id="start_date" name="start_date"
                                    value="<?php echo htmlspecialchars($start_date); ?>">
                            </div>
                            <div>
                                <label for="end_date">Đến</label>
                                <input type="date" class="form-control-date" id="end_date" name="end_date"
                                    value="<?php echo htmlspecialchars($end_date); ?>">
                            </div>
                            <input type="hidden" name="search" value="<?php echo htmlspecialchars($search_term); ?>">
                            <button type="submit" class="btn-reset-order">
                                <i class="fa-light fa-filter"></i>
                            </button>
                            
                            <button><a href="?<?php 
                                $params = $_GET;
                                $params['sort'] = 'asc';
                                echo http_build_query($params);
                            ?>" class="btn-reset-order">
                                <i class="fa-regular fa-arrow-up-short-wide"></i>
                            </a></button>
                            <button><a href="?<?php 
                                $params = $_GET;
                                $params['sort'] = 'desc';
                                echo http_build_query($params);
                            ?>" class="btn-reset-order">
                                <i class="fa-regular fa-arrow-down-wide-short"></i>
                            </button>
                            <button><a href="thongkekh.php" class="btn-reset-order">
                                <i class="fa-light fa-arrow-rotate-right"></i>
                            </a></button>
                        </form>
                    </div>
                </div>

                <div class="order-statistical" id="order-statistical">
                    <div class="order-statistical-item">
                        <div class="order-statistical-item-content">
                            <p class="order-statistical-item-content-desc">Tổng số khách hàng</p>
                            <h4 class="order-statistical-item-content-h" id="quantity-product"><?php echo $total_customers; ?></h4>
                        </div>
                        <div class="order-statistical-item-icon">
                            <i class="fa-light fa-users"></i>
                        </div>
                    </div>
                    <div class="order-statistical-item">
    <div class="order-statistical-item-content">
        <p class="order-statistical-item-content-desc">Tổng số đơn hàng</p>
        <h4 class="order-statistical-item-content-h" id="quantity-order"><?php echo $total_orders; ?></h4>
    </div>
    <div class="order-statistical-item-icon">
        <i class="fa-light fa-file-lines"></i>
    </div>
</div>

                    <div class="order-statistical-item">
                        <div class="order-statistical-item-content">
                            <p class="order-statistical-item-content-desc">Tổng doanh thu</p>
                            <h4 class="order-statistical-item-content-h" id="quantity-sale"><?php echo number_format($total_revenue, 0, ',', '.'); ?>đ</h4>
                        </div>
                        <div class="order-statistical-item-icon">
                            <i class="fa-light fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>

                <div class="table">
                    <table width="100%">
                        <thead>
                            <tr>
                                <td>Mã KH</td>
                                <td>Tên khách hàng</td>
                                <td>Số đơn hàng</td>
                                <td>Số tiền đã chi</td>
                                <td>Thao tác</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result && $result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['tenkh']); ?></td>
                                        <td><?php echo $row['total_orders']; ?></td>
                                        <td><?php echo number_format($row['total_revenue'], 0, ',', '.'); ?>đ</td>
                                        <td>
                                            <a href="?<?php echo http_build_query(array_merge($_GET, ['detail' => $row['id']])); ?>" 
                                               class="btn-detail product-order-detail">
                                                <i class="fa-regular fa-eye"></i> Chi tiết
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="no-data">Không tìm thấy dữ liệu</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Thay thế modal bằng form chi tiết PHP -->
    <?php if (isset($_GET['detail']) && $customer_details && $customer_details->num_rows > 0): ?>
    <div class="modal detail-modal open">
        <div class="modal-container">
            <div class="modal-container-title">
                <h2>Chi tiết thống kê khách hàng #<?php echo htmlspecialchars($_GET['detail']); ?></h2>
                <a href="?<?php echo http_build_query(array_diff_key($_GET, ['detail' => ""])); ?>" class="modal-close">
                    <i class="fa-light fa-xmark"></i>
                </a>
            </div>
            <div class="table">
                <table width="100%">
                    <thead>
                        <tr>
                            <td>Mã ĐH</td>
                            <td>Sản phẩm</td>
                            <td>Số lượng</td>
                            <td>Tổng tiền</td>
                            <td>Ngày đặt</td>
                            <td>Trạng thái</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($detail = $customer_details->fetch_assoc()): ?>
                        <tr>
                            <td>DH<?php echo $detail['order_id']; ?></td>
                            <td><?php echo htmlspecialchars($detail['products']); ?></td>
                            <td><?php echo $detail['total_items']; ?></td>
                            <td><?php echo number_format($detail['order_total'], 0, ',', '.'); ?>đ</td>
                            <td><?php echo date('d/m/Y', strtotime($detail['created_at'])); ?></td>
                            <td>
                                <span class="status-<?php echo $detail['status']; ?>">
                                    <?php
                                    switch($detail['status']) {
                                        case 'chuaxuly': echo 'Chưa xử lý'; break;
                                        case 'daxuly': echo 'Đã xử lý'; break;
                                        case 'chuagiao': echo 'Chưa giao'; break;
                                        case 'dagiao': echo 'Đã giao'; break;
                                        default: echo $detail['status'];
                                    }
                                    ?>
                                </span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script src="../assets/js/admin.js"></script>
      

</body>
</html> 