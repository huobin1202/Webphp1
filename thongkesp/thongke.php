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

// Get date range from request
$start_date = isset($_GET['start_date']) && $_GET['start_date'] !== '' ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) && $_GET['end_date'] !== '' ? $_GET['end_date'] : '';

// Validate date range
if ($start_date && $end_date && $start_date > $end_date) {
    $temp = $start_date;
    $start_date = $end_date;
    $end_date = $temp;
}

// Get search term
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Base query for product statistics
$query = "SELECT 
    p.id,
    p.tensp,
    p.hinhanh,
    COUNT(od.product_id) as total_sold,
    SUM(od.soluong) as total_quantity,
    SUM(od.soluong * od.price) as total_revenue
FROM products p
LEFT JOIN order_details od ON p.id = od.product_id
LEFT JOIN orders o ON od.order_id = o.id
WHERE 1=1";

$params = [];
$types = "";

// Add date range condition if dates are provided
if (!empty($start_date)) {
    $query .= " AND o.created_at >= ?";
    $params[] = $start_date;
    $types .= "s";
}
if (!empty($end_date)) {
    $query .= " AND o.created_at <= ?";
    $params[] = $end_date;
    $types .= "s";
}

// Add search condition if search term exists
if (!empty($search_term)) {
    $query .= " AND p.tensp LIKE ?";
    $params[] = "%$search_term%";
    $types .= "s";
}

$query .= " GROUP BY p.id, p.tensp, p.hinhanh ORDER BY total_revenue DESC";

// Prepare and execute query
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Error preparing query: " . $conn->error);
}

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
}

$result = $stmt->get_result();

// Calculate total statistics
$total_products = 0;
$total_quantity = 0;
$total_revenue = 0;

while ($row = $result->fetch_assoc()) {
    $total_products++;
    $total_quantity += $row['total_quantity'];
    $total_revenue += $row['total_revenue'];
}

// Reset result pointer
$result->data_seek(0);
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
                        <a href="../index.php" class="sidebar-link">
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
                    <div class="admin-control-center">
                        <form action="" method="GET" class="form-search">
                            <span class="search-btn"><i class="fa-light fa-magnifying-glass"></i></span>
                            <input id="form-search-tk" name="search" type="text" class="form-search-input"
                                placeholder="Tìm kiếm tên xe..." value="<?php echo htmlspecialchars($search_term); ?>">
                        </form>
                    </div>
                    <div class="admin-control-right">
                        <form action="" method="GET" class="fillter-date">
                            <div>
                                <label for="time-start">Từ</label>
                                <input type="date" class="form-control-date" id="time-start-tk" name="start_date"
                                    value="<?php echo $start_date; ?>">
                            </div>
                            <div>
                                <label for="time-end">Đến</label>
                                <input type="date" class="form-control-date" id="time-end-tk" name="end_date"
                                    value="<?php echo $end_date; ?>">
                            </div>
                            <button type="submit" class="btn-reset-order"><i class="fa-light fa-filter"></i></button>
                            <button><a href="thongke.php" class="btn-reset-order"><i class="fa-light fa-arrow-rotate-right"></i></a></button>
                        </form>
                    </div>
                </div>

                <div class="order-statistical" id="order-statistical">
                    <div class="order-statistical-item">
                        <div class="order-statistical-item-content">
                            <p class="order-statistical-item-content-desc">Sản phẩm được bán ra</p>
                            <h4 class="order-statistical-item-content-h" id="quantity-product"><?php echo $total_products; ?></h4>
                        </div>
                        <div class="order-statistical-item-icon">
                            <i class="fa-thin fa-motorcycle"></i>
                        </div>
                    </div>
                    <div class="order-statistical-item">
                        <div class="order-statistical-item-content">
                            <p class="order-statistical-item-content-desc">Số lượng bán ra</p>
                            <h4 class="order-statistical-item-content-h" id="quantity-order"><?php echo $total_quantity; ?></h4>
                        </div>
                        <div class="order-statistical-item-icon">
                            <i class="fa-light fa-file-lines"></i>
                        </div>
                    </div>
                    <div class="order-statistical-item">
                        <div class="order-statistical-item-content">
                            <p class="order-statistical-item-content-desc">Doanh thu</p>
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
                                <td><?php echo $row['total_quantity']; ?></td>
                                <td><?php echo number_format($row['total_revenue'], 0, ',', '.'); ?>đ</td>
                                <td>
                                    <button class="btn-detail product-order-detail" onclick="openDetailModal(<?php echo $row['id']; ?>)">
                                        <i class="fa-regular fa-eye"></i> Chi tiết
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Chi Tiết Thống Kê -->
    <div class="modal detail-modal">
        <div class="modal-container">
            <div class="modal-container-title">
                <h2>Chi tiết thống kê sản phẩm</h2>
                <button class="modal-close" onclick="closeModal()">
                    <i class="fa-light fa-xmark"></i>
                </button>
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
                    <tbody id="modal-sales-history">
                        <tr>
                            <td colspan="5" class="loading">Đang tải...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Hàm mở modal
        function openDetailModal(productId) {
            // Show loading state
            const modal = document.querySelector('.modal.detail-modal');
            modal.classList.add('open');
            document.getElementById('modal-sales-history').innerHTML = '<tr><td colspan="5" class="loading">Đang tải...</td></tr>';

            // Lấy giá trị ngày từ form
            const startDate = document.getElementById('time-start-tk').value;
            const endDate = document.getElementById('time-end-tk').value;

            // Tạo URL với tham số ngày nếu có
            let url = 'get_product_stats.php?id=' + productId;
            if (startDate) {
                url += '&start_date=' + startDate;
            }
            if (endDate) {
                url += '&end_date=' + endDate;
            }

            // Gửi AJAX request để lấy chi tiết thống kê
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Hiển thị lịch sử bán hàng
                    if (data.sales_history && data.sales_history.length > 0) {
                        const historyHtml = data.sales_history.map(sale => `
                            <tr>
                                <td>DH${sale.order_id}</td>
                                <td>${sale.quantity}</td>
                                <td>${new Intl.NumberFormat('vi-VN').format(sale.price)}đ</td>
                                <td>${sale.order_date}</td>
                            </tr>
                        `).join('');
                        document.getElementById('modal-sales-history').innerHTML = historyHtml;
                    } else {
                        document.getElementById('modal-sales-history').innerHTML = 
                            '<tr><td colspan="5" class="no-data">Không có dữ liệu bán hàng trong khoảng thời gian này</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('modal-sales-history').innerHTML = 
                        '<tr><td colspan="5" class="error">Có lỗi xảy ra khi tải thông tin chi tiết!</td></tr>';
                });
        }

        // Hàm đóng modal
        function closeModal() {
            document.querySelector('.modal.detail-modal').classList.remove('open');
        }

        // Đóng modal khi click bên ngoài
        window.onclick = function(event) {
            const modal = document.querySelector('.modal.detail-modal');
            if (event.target == modal) {
                closeModal();
            }
        }

        // Xử lý tìm kiếm
        document.getElementById('form-search-tk').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const table = document.getElementById('showTk');
            const rows = table.getElementsByTagName('tr');

            for (let row of rows) {
                const productName = row.querySelector('.prod-img-title p')?.textContent.toLowerCase() || '';
                const quantity = row.cells[2]?.textContent.toLowerCase() || '';
                const revenue = row.cells[3]?.textContent.toLowerCase() || '';

                if (productName.includes(searchTerm) || 
                    quantity.includes(searchTerm) || 
                    revenue.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }

            // Cập nhật tổng số sản phẩm hiển thị
            updateTotalStats();
        });

        // Hàm cập nhật tổng thống kê
        function updateTotalStats() {
            const table = document.getElementById('showTk');
            const visibleRows = table.querySelectorAll('tr:not([style*="display: none"])');
            
            let totalProducts = 0;
            let totalQuantity = 0;
            let totalRevenue = 0;

            visibleRows.forEach(row => {
                totalProducts++;
                totalQuantity += parseInt(row.cells[2].textContent) || 0;
                totalRevenue += parseInt(row.cells[3].textContent.replace(/[^0-9]/g, '')) || 0;
            });

            document.getElementById('quantity-product').textContent = totalProducts;
            document.getElementById('quantity-order').textContent = totalQuantity;
            document.getElementById('quantity-sale').textContent = new Intl.NumberFormat('vi-VN').format(totalRevenue) + 'đ';
        }
    </script>
</body>
</html>