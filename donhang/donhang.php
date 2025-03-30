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
$sql = "SELECT 
            orders.id AS order_id, 
            orders.customer_id, 
            orders.total, 
            orders.created_at, 
            orders.status, 
            orders.recipient_name,
            orders.recipient_phone,
            orders.delivery_type,
            orders.address,
            orders.note,
            customer.name AS customer_name
        FROM orders
        INNER JOIN customer ON orders.customer_id = customer.id
        ORDER BY orders.created_at DESC";
$result = $conn->query($sql);

// Lưu tất cả đơn hàng + chi tiết vào mảng
$orders = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orderId = $row['order_id'];
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
                    <div class="admin-control-left">
                        <select name="tinh-trang" id="tinh-trang">
                            <option value="4">Tất cả</option>
                            <option value="0">Chưa xử lý</option>
                            <option value="1">Đã xử lý</option>
                            <option value="2">Chưa giao</option>
                            <option value="3">Đã giao</option>

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
                            <button><a href="donhang.php" class="btn-reset-order"><i class="fa-light fa-arrow-rotate-right"></i></a></button>
                        </form>
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
                        <tbody id="showOrder">
                            <?php foreach ($orders as $order):
                                $orderId = $order['order_id'];
                                $customerId = str_pad($order['customer_id'], 2, '0', STR_PAD_LEFT);
                                $customerName = htmlspecialchars($order['customer_name']);
                                $createdDate = date('d/m/Y', strtotime($order['created_at']));
                                $total = number_format($order['total'], 0, ',', '.') . 'đ';
                                $statusText = '';
                                switch ($order['status']) {
                                    case 'chuaxuly':
                                        $statusText = '<span class="status-no-complete">Chưa xử lý</span>';
                                        break;
                                    case 'daxuly':
                                        $statusText = '<span class="status-processing">Đã xử lý</span>';
                                        break;
                                    case 'dagiao':
                                        $statusText = '<span class="status-complete">Đã giao</span>';
                                        break;
                                    case 'chuagiao':
                                        $statusText = '<span class="status-pending">Chưa giao</span>';
                                        break;
                                }
                                $details_json = htmlspecialchars(json_encode($order['details']), ENT_QUOTES, 'UTF-8');
                            ?>
                                <tr>
                                    <td><?= $orderId ?></td>
                                    <td><?= $customerId ?></td>
                                    <td><?= $customerName ?></td>
                                    <td><?= $createdDate ?></td>
                                    <td><?= $total ?></td>
                                    <td>
                                        <select class="order-status-select" onchange="updateOrderStatus(<?php echo $orderId; ?>, this.value)">
                                            <option value="chuaxuly" <?php echo $order['status'] == 'chuaxuly' ? 'selected' : ''; ?>>
                                                <i class="fa-regular fa-clock"></i> Chưa xử lý
                                            </option>
                                            <option value="daxuly" <?php echo $order['status'] == 'daxuly' ? 'selected' : ''; ?>>
                                                <i class="fa-regular fa-check"></i> Đã xử lý
                                            </option>
                                            <option value="chuagiao" <?php echo $order['status'] == 'chuagiao' ? 'selected' : ''; ?>>
                                                <i class="fa-regular fa-truck"></i> Chưa giao
                                            </option>
                                            <option value="dagiao" <?php echo $order['status'] == 'dagiao' ? 'selected' : ''; ?>>
                                                <i class="fa-regular fa-circle-check"></i> Đã giao
                                            </option>
                                        </select>
                                    </td>
                                    <td class="control">
                                        <button class="btn-detail view-order-btn"
                                            data-order-id="<?= $orderId ?>"
                                            data-customer-name="<?= $customerName ?>"
                                            data-created-date="<?= $createdDate ?>"
                                            data-total="<?= $total ?>"
                                            data-status="<?= $order['status'] ?>"
                                            data-recipient-name="<?= htmlspecialchars($order['recipient_name']) ?>"
                                            data-recipient-phone="<?= htmlspecialchars($order['recipient_phone']) ?>"
                                            data-delivery-type="<?= htmlspecialchars($order['delivery_type']) ?>"
                                            data-address="<?= htmlspecialchars($order['address']) ?>"
                                            data-note="<?= htmlspecialchars($order['note']) ?: 'Không có' ?>"
                                            data-products='<?= $details_json ?>'>
                                            <i class="fa-regular fa-eye"></i> Chi tiết
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (count($orders) == 0): ?>
                                <tr>
                                    <td colspan="7">Không có đơn hàng nào.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

        <!-- MODAL -->
        <div class="modal detail-order">
            <div class="modal-container">
                <h3 class="modal-container-title">CHI TIẾT ĐƠN HÀNG</h3>
                <button class="modal-close"><i class="fa-regular fa-xmark"></i></button>
                <div class="modal-detail-order">
                    <div class="modal-detail-left">
                        <div class="order-item-group">
                            <!-- Products injected here -->
                        </div>
                    </div>
                    <div class="modal-detail-right">
                        <ul class="detail-order-group">
                            <li class="detail-order-item">
                                <span class="detail-order-item-left"><i class="fa-light fa-calendar-days"></i> Ngày đặt</span>
                                <span class="detail-order-item-right"></span>
                            </li>
                            <li class="detail-order-item">
                                <span class="detail-order-item-left"><i class="fa-light fa-truck"></i> Hình thức giao</span>
                                <span class="detail-order-item-right"></span>
                            </li>
                            <li class="detail-order-item">
                                <span class="detail-order-item-left"><i class="fa-thin fa-person"></i> Người nhận</span>
                                <span class="detail-order-item-right"></span>
                            </li>
                            <li class="detail-order-item">
                                <span class="detail-order-item-left"><i class="fa-light fa-phone"></i> Số điện thoại</span>
                                <span class="detail-order-item-right"></span>
                            </li>
                            <li class="detail-order-item tb">
                                <span class="detail-order-item-t"><i class="fa-light fa-location-dot"></i> Địa chỉ</span>
                                <p class="detail-order-item-b"></p>
                            </li>
                            <li class="detail-order-item tb">
                                <span class="detail-order-item-t"><i class="fa-light fa-note-sticky"></i> Ghi chú</span>
                                <p class="detail-order-item-b"></p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="modal-detail-bottom">
                    <div class="modal-detail-bottom-left">
                        <div class="price-total">
                            <span class="thanhtien">Thành tiền</span>
                            <span class="price"></span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <script src="../assets/js/admin.js"></script>
        <script>
        document.addEventListener("DOMContentLoaded", function () {
            const searchInput = document.getElementById("form-search-order");
            const statusFilter = document.getElementById("tinh-trang");
            const startDate = document.getElementById('time-start-tk');
            const endDate = document.getElementById('time-end-tk');
            const tableRows = document.querySelectorAll("#showOrder tr");
            
            function normalizeString(str) {
                return str.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            }

            function isDateInRange(dateStr, startDate, endDate) {
                if (!startDate && !endDate) return true;
                
                // Chuyển đổi định dạng ngày từ dd/mm/yyyy sang yyyy-mm-dd
                const [day, month, year] = dateStr.split('/');
                const date = new Date(year, month - 1, day);
                
                const start = startDate ? new Date(startDate) : null;
                const end = endDate ? new Date(endDate) : null;
                
                if (start && end) {
                    return date >= start && date <= end;
                } else if (start) {
                    return date >= start;
                } else if (end) {
                    return date <= end;
                }
                return true;
            }

            function filterTable() {
                const searchTerm = normalizeString(searchInput.value);
                const selectedStatus = statusFilter.value;
                const startValue = startDate.value;
                const endValue = endDate.value;
                let hasVisibleRows = false;

                tableRows.forEach(row => {
                    const cells = row.querySelectorAll("td");
                    if (cells.length === 0) return; // Bỏ qua hàng không có ô

                    const orderId = cells[0].textContent.toLowerCase().trim();
                    const customerId = cells[1].textContent.toLowerCase().trim();
                    const customerName = cells[2].textContent.toLowerCase().trim();
                    const orderDate = cells[3].textContent.trim();
                    const statusSelect = cells[5].querySelector('select');
                    const currentStatus = statusSelect ? statusSelect.value : '';

                    // Kiểm tra điều kiện tìm kiếm
                    const matchesSearch = orderId.includes(searchTerm) ||
                        customerId.includes(searchTerm) ||
                        customerName.includes(searchTerm);

                    // Kiểm tra trạng thái
                    let matchesStatus = true;
                    if (selectedStatus !== "4") {
                        switch(selectedStatus) {
                            case "0": matchesStatus = currentStatus === "chuaxuly"; break;
                            case "1": matchesStatus = currentStatus === "daxuly"; break;
                            case "2": matchesStatus = currentStatus === "chuagiao"; break;
                            case "3": matchesStatus = currentStatus === "dagiao"; break;
                        }
                    }

                    // Kiểm tra ngày tháng
                    const matchesDate = isDateInRange(orderDate, startValue, endValue);

                    // Hiển thị/Ẩn dòng dựa trên điều kiện phù hợp
                    const isVisible = matchesSearch && matchesStatus && matchesDate;
                    row.style.display = isVisible ? '' : 'none';
                    
                    if (isVisible) {
                        hasVisibleRows = true;
                    }
                });

                // Hiển thị thông báo nếu không có kết quả
                const noResultsRow = document.querySelector('.no-results');
                if (!hasVisibleRows) {
                    if (!noResultsRow) {
                        const tbody = document.getElementById('showOrder');
                        const newRow = document.createElement('tr');
                        newRow.className = 'no-results';
                        newRow.innerHTML = '<td colspan="7" class="no-products">Không tìm thấy đơn hàng nào!</td>';
                        tbody.appendChild(newRow);
                    }
                } else if (noResultsRow) {
                    noResultsRow.remove();
                }
            }

            // Gắn sự kiện cho các elements
            if (searchInput) {
                searchInput.addEventListener("input", filterTable);
            }
            if (statusFilter) {
                statusFilter.addEventListener("change", filterTable);
            }
            if (startDate) {
                startDate.addEventListener("change", filterTable);
            }
            if (endDate) {
                endDate.addEventListener("change", filterTable);
            }

            // Hàm reset bộ lọc
            window.resetSearch = function() {
                searchInput.value = '';
                statusFilter.value = '4';
                startDate.value = '';
                endDate.value = '';
                filterTable();
            }
        });
        </script>
    </div>

</body>

</html>