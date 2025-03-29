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

// Base query for customer statistics
$query = "SELECT 
    c.id,
    c.name as tenkh,
    COUNT(o.id) as total_orders,
    SUM(od.soluong) as total_quantity,
    SUM(od.soluong * od.price) as total_revenue
FROM customer c
LEFT JOIN orders o ON c.id = o.customer_id
LEFT JOIN order_details od ON o.id = od.order_id
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
    $query .= " AND (c.name LIKE ? OR c.id LIKE ?)";
    $params[] = "%$search_term%";
    $params[] = "%$search_term%";
    $types .= "ss";
}

$query .= " GROUP BY c.id, c.name ORDER BY total_revenue DESC";

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
$total_customers = 0;
$total_quantity = 0;
$total_revenue = 0;

while ($row = $result->fetch_assoc()) {
    $total_customers++;
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
                                placeholder="Tìm kiếm tên khách hàng..." value="<?php echo htmlspecialchars($search_term); ?>">
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
                            <button type="button" class="btn-reset-order" onclick="sortTable('asc')"><i class="fa-regular fa-arrow-up-short-wide"></i></button>
                            <button type="button" class="btn-reset-order" onclick="sortTable('desc')"><i class="fa-regular fa-arrow-down-wide-short"></i></button>
                            <button><a href="thongkekh.php" class="btn-reset-order"><i class="fa-light fa-arrow-rotate-right"></i></a></button>
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
                            <h4 class="order-statistical-item-content-h" id="quantity-order"><?php echo $total_quantity; ?></h4>
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
                        <tbody id="showTk">
                            <?php
                            while ($row = $result->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['tenkh']); ?></td>
                                <td><?php echo $row['total_orders']; ?></td>
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
                <h2>Chi tiết thống kê khách hàng</h2>
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
                            <td>Trạng thái</td>
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
        function openDetailModal(customerId) {
            // Show loading state
            const modal = document.querySelector('.modal.detail-modal');
            modal.classList.add('open');
            document.getElementById('modal-sales-history').innerHTML = '<tr><td colspan="5" class="loading">Đang tải...</td></tr>';

            // Lấy giá trị ngày từ form
            const startDate = document.getElementById('time-start-tk').value;
            const endDate = document.getElementById('time-end-tk').value;

            // Tạo URL với tham số ngày nếu có
            let url = 'get_customer_stats.php?id=' + customerId;
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
                    // Hiển thị lịch sử đơn hàng
                    if (data.sales_history && data.sales_history.length > 0) {
                        const historyHtml = data.sales_history.map(sale => `
                            <tr>
                                <td>DH${sale.order_id}</td>
                                <td>${sale.quantity}</td>
                                <td>${new Intl.NumberFormat('vi-VN').format(sale.price)}đ</td>
                                <td>${sale.order_date}</td>
                                <td>${getStatusText(sale.status)}</td>
                            </tr>
                        `).join('');
                        document.getElementById('modal-sales-history').innerHTML = historyHtml;
                    } else {
                        document.getElementById('modal-sales-history').innerHTML = 
                            '<tr><td colspan="5" class="no-data">Không có dữ liệu đơn hàng trong khoảng thời gian này</td></tr>';
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

        // Hàm chuyển đổi trạng thái đơn hàng
        function getStatusText(status) {
            switch(status) {
                case 'chuaxuly':
                    return 'Chưa xử lý';
                case 'daxuly':
                    return 'Đã xử lý';
                case 'chuagiao':
                    return 'Chưa giao';
                case 'dagiao':
                    return 'Đã giao';
                default:
                    return status;
            }
        }

        // Xử lý tìm kiếm
        document.getElementById('form-search-tk').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const table = document.getElementById('showTk');
            const rows = table.getElementsByTagName('tr');

            for (let row of rows) {
                const customerId = row.cells[0]?.textContent.toLowerCase() || '';
                const customerName = row.cells[1]?.textContent.toLowerCase() || '';
                const orders = row.cells[2]?.textContent.toLowerCase() || '';
                const revenue = row.cells[3]?.textContent.toLowerCase() || '';

                if (customerId.includes(searchTerm) || 
                    customerName.includes(searchTerm) || 
                    orders.includes(searchTerm) || 
                    revenue.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }

            // Cập nhật tổng số khách hàng hiển thị
            updateTotalStats();
        });

        // Hàm cập nhật tổng thống kê
        function updateTotalStats() {
            const table = document.getElementById('showTk');
            const visibleRows = table.querySelectorAll('tr:not([style*="display: none"])');
            
            let totalCustomers = 0;
            let totalOrders = 0;
            let totalRevenue = 0;

            visibleRows.forEach(row => {
                totalCustomers++;
                totalOrders += parseInt(row.cells[2].textContent) || 0;
                totalRevenue += parseInt(row.cells[3].textContent.replace(/[^0-9]/g, '')) || 0;
            });

            document.getElementById('quantity-product').textContent = totalCustomers;
            document.getElementById('quantity-order').textContent = totalOrders;
            document.getElementById('quantity-sale').textContent = new Intl.NumberFormat('vi-VN').format(totalRevenue) + 'đ';
        }

        // Hàm sắp xếp bảng theo doanh thu
        function sortTable(order) {
            const table = document.getElementById('showTk');
            const rows = Array.from(table.getElementsByTagName('tr'));
            
            rows.sort((a, b) => {
                const revenueA = parseInt(a.cells[3].textContent.replace(/[^0-9]/g, '')) || 0;
                const revenueB = parseInt(b.cells[3].textContent.replace(/[^0-9]/g, '')) || 0;
                return order === 'asc' ? revenueA - revenueB : revenueB - revenueA;
            });

            // Xóa các dòng hiện tại
            rows.forEach(row => row.remove());
            
            // Thêm lại các dòng đã sắp xếp
            rows.forEach(row => table.appendChild(row));
        }
    </script>
</body>
</html> 