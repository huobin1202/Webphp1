<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: dnurl.php");
    exit();
}

// Database connection
include('../database.php');


// Lấy các tham số từ URL
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$detail_id = isset($_GET['detail']) ? (int)$_GET['detail'] : 0;
$sort_order = isset($_GET['sort']) ? $_GET['sort'] : 'desc';
$category = isset($_GET['category']) ? $_GET['category'] : 'Tất cả';

// Base query for product statistics
$query = "SELECT 
    p.id,
    p.tensp,
    p.hinhanh,
    p.dongsp,
    COUNT(DISTINCT o.id) as total_orders,
    SUM(od.soluong) as total_quantity,
    SUM(od.soluong * od.price) as total_revenue
FROM products p
LEFT JOIN order_details od ON p.id = od.product_id
LEFT JOIN orders o ON od.order_id = o.id
WHERE 1=1";

// Add category condition
if ($category !== 'Tất cả') {
    $category = $conn->real_escape_string($category);
    $query .= " AND p.dongsp = '$category'";
}

// Add search condition
if (!empty($search_term)) {
    $search_term = $conn->real_escape_string($search_term);
    $query .= " AND p.tensp LIKE '%$search_term%'";
}

// Add date range condition
if (!empty($start_date)) {
    $query .= " AND DATE(o.created_at) >= '$start_date'";
}
if (!empty($end_date)) {
    $query .= " AND DATE(o.created_at) <= '$end_date'";
}

$query .= " GROUP BY p.id, p.tensp, p.hinhanh, p.dongsp 
            HAVING total_revenue > 0 
            ORDER BY total_revenue " . ($sort_order === 'asc' ? 'ASC' : 'DESC');

$result = $conn->query($query);

// Calculate totals
$total_products = 0;
$total_orders = 0;
$total_revenue = 0;

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $total_products++;
        $total_orders += $row['total_orders'];
        $total_revenue += $row['total_revenue'];
    }
    $result->data_seek(0);
}

// Query for detail modal if detail_id is set
if ($detail_id > 0) {
    $detail_query = "SELECT 
        o.id as order_id,
        od.soluong as quantity,
        od.price,
        o.created_at as order_date
    FROM orders o
    JOIN order_details od ON o.id = od.order_id
    WHERE od.product_id = ?";

    if (!empty($start_date)) {
        $detail_query .= " AND DATE(o.created_at) >= '$start_date'";
    }
    if (!empty($end_date)) {
        $detail_query .= " AND DATE(o.created_at) <= '$end_date'";
    }

    $detail_query .= " ORDER BY o.created_at DESC";
    
    $stmt = $conn->prepare($detail_query);
    $stmt->bind_param("i", $detail_id);
    $stmt->execute();
    $detail_result = $stmt->get_result();
}
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
    <title>Thống kê sản phẩm</title>
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
                    <li class="sidebar-list-item tab-content active">
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
                    <div class="admin-control-left">
                        <form action="" method="GET" style="display: inline-block; margin-right: 10px;">
                            <select name="category" class="form-control" onchange="this.form.submit()">
                                <option value="Tất cả">Tất cả</option>
                                <option value="Dòng Ninja" <?php echo $category === 'Dòng Ninja' ? 'selected' : ''; ?>>Dòng Ninja</option>
                                <option value="Dòng Z" <?php echo $category === 'Dòng Z' ? 'selected' : ''; ?>>Dòng Z</option>
                                <option value="Dòng KLX" <?php echo $category === 'Dòng KLX' ? 'selected' : ''; ?>>Dòng KLX</option>
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
                                placeholder="Tìm kiếm tên xe..." 
                                value="<?php echo htmlspecialchars($search_term); ?>">
                            <input type="hidden" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
                            <input type="hidden" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
                        </form>
                    </div>
                    <div class="admin-control-right">
                        <form action="" method="GET" class="fillter-date">
                            <div>
                                <label for="start_date">Từ</label>
                                <input type="date" name="start_date" class="form-control-date"
                                    value="<?php echo htmlspecialchars($start_date); ?>">
                            </div>
                            <div>
                                <label for="end_date">Đến</label>
                                <input type="date" name="end_date" class="form-control-date"
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
                            <button><a href="thongke.php" class="btn-reset-order">
                                <i class="fa-light fa-arrow-rotate-right"></i>
                            </a></button>
                        </form>
                    </div>
                </div>

                <div class="order-statistical" id="order-statistical">
                    <div class="order-statistical-item">
                        <div class="order-statistical-item-content">
                            <p class="order-statistical-item-content-desc">Tổng sản phẩm được bán ra</p>
                            <h4 class="order-statistical-item-content-h" id="quantity-product"><?php echo $total_products; ?></h4>
                        </div>
                        <div class="order-statistical-item-icon">
                            <i class="fa-thin fa-motorcycle"></i>
                        </div>
                    </div>
                    <div class="order-statistical-item">
                        <div class="order-statistical-item-content">
                            <p class="order-statistical-item-content-desc">Tổng số lượng bán</p>
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
                                <td>STT</td>
                                <td>Tên xe</td>
                                <td>Số lượng bán</td>
                                <td>Doanh thu</td>
                                <td>Thao tác</td>
                            </tr>
                        </thead>
                        <tbody id="showTk">
                            <?php
                            $stt = 1;
                            while ($row = $result->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?php echo $stt++; ?></td>
                                <td>
                                    <div class="prod-img-title">
                                        <img class="prd-img-tbl" src="../sanpham/<?php echo htmlspecialchars($row['hinhanh']); ?>" alt="">
                                        <p><?php echo htmlspecialchars($row['tensp']); ?></p>
                                    </div>
                                </td>
                                <td><?php echo $row['total_orders']; ?></td>
                                <td><?php echo number_format($row['total_revenue'], 0, ',', '.'); ?>đ</td>
                                <td>
                                    <a href="?<?php 
                                        $params = $_GET;
                                        $params['detail'] = $row['id'];
                                        echo http_build_query($params);
                                    ?>" class="btn-detail product-order-detail">
                                        <i class="fa-regular fa-eye"></i> Chi tiết
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal -->
    <?php if ($detail_id > 0 && isset($detail_result)): ?>
    <div class="modal detail-modal open">
        <div class="modal-container">
            <div class="modal-container-title">
                <h2>Chi tiết thống kê sản phẩm</h2>
                <a href="?<?php echo http_build_query(array_diff_key($_GET, ['detail' => ''])); ?>" 
                   class="modal-close">
                    <i class="fa-light fa-xmark"></i>
                </a>
            </div>
            <div class="table">
                <table width="100%">
                    <thead>
                        <tr>
                            <td>Mã DH</td>
                            <td>Số lượng</td>
                            <td>Đơn giá</td>
                            <td>Ngày đặt</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($detail = $detail_result->fetch_assoc()): ?>
                        <tr>
                            <td>DH<?php echo $detail['order_id']; ?></td>
                            <td><?php echo $detail['quantity']; ?></td>
                            <td><?php echo number_format($detail['price'], 0, ',', '.'); ?>đ</td>
                            <td><?php echo date('d/m/Y', strtotime($detail['order_date'])); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <script src="../assets/js/admin.js"></script>

    <script>
    window.onclick = function(event) {
        const modal = document.querySelector('.modal.detail-modal');
        if (event.target == modal) {
            window.location.href = '?' + new URLSearchParams(
                Object.fromEntries(
                    Object.entries(Object.fromEntries(new URLSearchParams(window.location.search)))
                    .filter(([key]) => key !== 'detail')
                )
            ).toString();
        }
    }
    </script>
</body>
</html>