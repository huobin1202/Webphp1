<?php
// File: donhang/donhang.php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../dnurl.php");
    exit();
}


include('../database.php');


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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id']);
    $new_status = $_POST['new_status'];
    
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Cập nhật trạng thái thành công!";
    } else {
        $_SESSION['error'] = "Không thể cập nhật trạng thái!";
    }
    
    // Redirect back với các tham số tìm kiếm
    $redirect_url = 'donhang.php';
    if (!empty($_SERVER['HTTP_REFERER'])) {
        $parts = parse_url($_SERVER['HTTP_REFERER']);
        if (isset($parts['query'])) {
            $redirect_url .= '?' . $parts['query'];
        }
    }
    
    header("Location: " . $redirect_url);
}

// Lấy tất cả đơn hàng & chi tiết để hiển thị
$sql = "SELECT orders.*, customer.name AS customer_name, customer.city_code, customer.city_name, customer.district_code, customer.district_name, customer.ward_code, customer.ward_name
        FROM orders 
        INNER JOIN customer ON orders.customer_id = customer.id 
        WHERE 1=1";
$params = array();

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = '%' . $_GET['search'] . '%';
    $sql .= " AND (orders.id LIKE ? OR customer.id LIKE ? OR customer.name LIKE ? OR orders.recipient_name LIKE ? OR orders.recipient_phone LIKE ?)";
    array_push($params, $search, $search, $search, $search, $search);
}

if (isset($_GET['status']) && $_GET['status'] != '4') {
    $sql .= " AND orders.status = ?";
    array_push($params, $_GET['status']);
}

if (isset($_GET['city']) && !empty($_GET['city'])) {
    $sql .= " AND orders.city_code = ?";
    array_push($params, $_GET['city']);
}

if (isset($_GET['district']) && !empty($_GET['district'])) {
    $sql .= " AND orders.district_code = ?";
    array_push($params, $_GET['district']);
}

if (isset($_GET['ward']) && !empty($_GET['ward'])) {
    $sql .= " AND orders.ward_code = ?";
    array_push($params, $_GET['ward']);
}

if (isset($_GET['start_date']) && !empty($_GET['start_date'])) {
    $sql .= " AND orders.created_at >= ?";
    array_push($params, $_GET['start_date']);
}

if (isset($_GET['end_date']) && !empty($_GET['end_date'])) {
    $sql .= " AND orders.created_at <= DATE_ADD(?, INTERVAL 1 DAY)";
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
                            <select name="status" class="form-control" onchange="this.form.submit()">
                                <option value="4" <?php echo (isset($_GET['status']) && $_GET['status'] == '4') ? 'selected' : ''; ?>>Tất cả</option>
                                <option value="chuaxuly" <?php echo (isset($_GET['status']) && $_GET['status'] == 'chuaxuly') ? 'selected' : ''; ?>>Chưa xử lý</option>
                                <option value="daxuly" <?php echo (isset($_GET['status']) && $_GET['status'] == 'daxuly') ? 'selected' : ''; ?>>Đã xử lý</option>
                                <option value="dahuy" <?php echo (isset($_GET['status']) && $_GET['status'] == 'dahuy') ? 'selected' : ''; ?>>Đã hủy</option>
                                <option value="dagiao" <?php echo (isset($_GET['status']) && $_GET['status'] == 'dagiao') ? 'selected' : ''; ?>>Đã giao</option>
                            </select>
                        </div>
                        <div class="admin-control-location">
                            <select name="city" id="city"  onchange="loadDistricts(this.value)" style="margin-left:25px;">
                                <option value="">Chọn Tỉnh/Thành phố</option>
                                <?php
                                $city_query = $conn->query("SELECT DISTINCT city_code, city_name FROM customer WHERE city_code IS NOT NULL AND city_name IS NOT NULL ORDER BY city_name");
                                while ($city = $city_query->fetch_assoc()) {
                                    $selected = (isset($_GET['city']) && $_GET['city'] == $city['city_code']) ? 'selected' : '';
                                    echo "<option value='{$city['city_code']}' {$selected}>{$city['city_name']}</option>";
                                }
                                ?>
                            </select>
                            <select name="district" id="district"  onchange="loadWards(this.value)">
                                <option value="">Chọn Quận/Huyện</option>
                                <?php
                                if (isset($_GET['city'])) {
                                    $district_query = $conn->prepare("SELECT DISTINCT district_code, district_name FROM customer WHERE city_code = ? AND district_code IS NOT NULL AND district_name IS NOT NULL ORDER BY district_name");
                                    $district_query->bind_param("s", $_GET['city']);
                                    $district_query->execute();
                                    $district_result = $district_query->get_result();
                                    while ($district = $district_result->fetch_assoc()) {
                                        $selected = (isset($_GET['district']) && $_GET['district'] == $district['district_code']) ? 'selected' : '';
                                        echo "<option value='{$district['district_code']}' {$selected}>{$district['district_name']}</option>";
                                    }
                                }
                                ?>
                            </select>
                            <select name="ward" id="ward" >
                                <option value="">Chọn Phường/Xã</option>
                                <?php
                                if (isset($_GET['district'])) {
                                    $ward_query = $conn->prepare("SELECT DISTINCT ward_code, ward_name FROM customer WHERE district_code = ? AND ward_code IS NOT NULL AND ward_name IS NOT NULL ORDER BY ward_name");
                                    $ward_query->bind_param("s", $_GET['district']);
                                    $ward_query->execute();
                                    $ward_result = $ward_query->get_result();
                                    while ($ward = $ward_result->fetch_assoc()) {
                                        $selected = (isset($_GET['ward']) && $_GET['ward'] == $ward['ward_code']) ? 'selected' : '';
                                        echo "<option value='{$ward['ward_code']}' {$selected}>{$ward['ward_name']}</option>";
                                    }
                                }
                                ?>
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
                                        value="<?php echo isset($_GET['start_date']) && $_GET['start_date'] !== '0' ? $_GET['start_date'] : ''; ?>">
                                </div>
                                <div>
                                    <label for="time-end">Đến</label>
                                    <input type="date" name="end_date" class="form-control-date"
                                        value="<?php echo isset($_GET['end_date']) && $_GET['end_date'] !== '0' ? $_GET['end_date'] : ''; ?>">
                                </div>
                                <button type="submit" class="btn-reset-order"><i class="fa-light fa-filter"></i></button>
                                <button id="resetButton" class="btn-reset-order"><i class="fa-light fa-arrow-rotate-right"></i></button>
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
                                            <form method="POST" action="" class="status-form">
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
    <script>
    // API endpoint for Vietnam administrative divisions
    const API_URL = 'https://provinces.open-api.vn/api/';
    
    function loadProvinces() {
        fetch(API_URL)
            .then(response => response.json())
            .then(data => {
                const citySelect = document.getElementById('city');
                citySelect.innerHTML = '<option value="">Chọn Tỉnh/Thành phố</option>';
                data.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province.code;
                    option.textContent = province.name;
                    if (province.code === '<?php echo isset($_GET['city']) ? $_GET['city'] : ''; ?>') {
                        option.selected = true;
                    }
                    citySelect.appendChild(option);
                });
                
                // Nếu có city_code, load districts
                if ('<?php echo isset($_GET['city']) ? $_GET['city'] : ''; ?>') {
                    loadDistricts('<?php echo isset($_GET['city']) ? $_GET['city'] : ''; ?>');
                }
            })
            .catch(error => {
                console.error('Error loading provinces:', error);
            });
    }

    function loadDistricts(cityCode) {
        fetch(API_URL + 'p/' + cityCode + '?depth=2')
            .then(response => response.json())
            .then(data => {
                const districtSelect = document.getElementById('district');
                districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
                
                data.districts.forEach(district => {
                    const option = document.createElement('option');
                    option.value = district.code;
                    option.textContent = district.name;
                    if (district.code === '<?php echo isset($_GET['district']) ? $_GET['district'] : ''; ?>') {
                        option.selected = true;
                    }
                    districtSelect.appendChild(option);
                });
                
                // Nếu có district_code, load wards
                if ('<?php echo isset($_GET['district']) ? $_GET['district'] : ''; ?>') {
                    loadWards('<?php echo isset($_GET['district']) ? $_GET['district'] : ''; ?>');
                }
            })
            .catch(error => {
                console.error('Error loading districts:', error);
            });
    }

    function loadWards(districtCode) {
        fetch(API_URL + 'd/' + districtCode + '?depth=2')
            .then(response => response.json())
            .then(data => {
                const wardSelect = document.getElementById('ward');
                wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
                
                if (data.wards && data.wards.length > 0) {
                    data.wards.forEach(ward => {
                        const option = document.createElement('option');
                        option.value = ward.code;
                        option.textContent = ward.name;
                        if (ward.code === '<?php echo isset($_GET['ward']) ? $_GET['ward'] : ''; ?>') {
                            option.selected = true;
                        }
                        wardSelect.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error('Error loading wards:', error);
            });
    }

    // Load provinces when page loads
    document.addEventListener('DOMContentLoaded', function() {
        loadProvinces();

        // Thêm sự kiện cho nút reset
        document.getElementById('resetButton').addEventListener('click', function(e) {
            e.preventDefault();
            // Xóa localStorage
            localStorage.removeItem('orderSearchState');
            // Reset form values
            document.querySelector('select[name="status"]').value = '4';
            document.querySelector('input[name="search"]').value = '';
            document.querySelector('input[name="start_date"]').value = '';
            document.querySelector('input[name="end_date"]').value = '';
            document.getElementById('city').value = '';
            document.getElementById('district').innerHTML = '<option value="">Chọn Quận/Huyện</option>';
            document.getElementById('ward').innerHTML = '<option value="">Chọn Phường/Xã</option>';
            // Chuyển hướng về trang chính
            window.location.href = 'donhang.php';
        });

        // Lưu trạng thái tìm kiếm vào localStorage
        function saveSearchState() {
            const searchState = {
                city: document.getElementById('city').value,
                district: document.getElementById('district').value,
                ward: document.getElementById('ward').value,
                status: document.querySelector('select[name="status"]').value,
                search: document.querySelector('input[name="search"]').value,
                start_date: document.querySelector('input[name="start_date"]').value,
                end_date: document.querySelector('input[name="end_date"]').value
            };
            localStorage.setItem('orderSearchState', JSON.stringify(searchState));
        }

        // Khôi phục trạng thái tìm kiếm từ localStorage
        function restoreSearchState() {
            const savedState = localStorage.getItem('orderSearchState');
            if (savedState) {
                const state = JSON.parse(savedState);
                if (state.city) {
                    document.getElementById('city').value = state.city;
                    loadDistricts(state.city);
                }
                if (state.district) {
                    document.getElementById('district').value = state.district;
                    loadWards(state.district);
                }
                if (state.ward) {
                    document.getElementById('ward').value = state.ward;
                }
                document.querySelector('select[name="status"]').value = state.status;
                document.querySelector('input[name="search"]').value = state.search;
                document.querySelector('input[name="start_date"]').value = state.start_date;
                document.querySelector('input[name="end_date"]').value = state.end_date;
            }
        }

        // Khôi phục trạng thái khi trang được tải
        restoreSearchState();

        // Thêm sự kiện onchange cho select tỉnh/thành phố
        document.getElementById('city').addEventListener('change', function() {
            const cityCode = this.value;
            // Reset district và ward khi thay đổi province
            document.getElementById('district').innerHTML = '<option value="">Chọn Quận/Huyện</option>';
            document.getElementById('ward').innerHTML = '<option value="">Chọn Phường/Xã</option>';
            loadDistricts(cityCode);
            if (cityCode) {
                searchOrders();
            }
            saveSearchState();
        });

        // Thêm sự kiện onchange cho select quận/huyện
        document.getElementById('district').addEventListener('change', function() {
            const districtCode = this.value;
            // Reset ward khi thay đổi district
            document.getElementById('ward').innerHTML = '<option value="">Chọn Phường/Xã</option>';
            loadWards(districtCode);
            if (districtCode) {
                searchOrders();
            }
            saveSearchState();
        });

        // Thêm sự kiện onchange cho select phường/xã
        document.getElementById('ward').addEventListener('change', function() {
            const wardCode = this.value;
            if (wardCode) {
                searchOrders();
            }
            saveSearchState();
        });

        // Thêm sự kiện cho các trường tìm kiếm khác
        document.querySelector('select[name="status"]').addEventListener('change', saveSearchState);
        document.querySelector('input[name="search"]').addEventListener('input', saveSearchState);
        document.querySelector('input[name="start_date"]').addEventListener('change', saveSearchState);
        document.querySelector('input[name="end_date"]').addEventListener('change', saveSearchState);

        // Sửa lại nút chi tiết để giữ nguyên tham số tìm kiếm
        document.querySelectorAll('.btn-detail').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const orderId = this.closest('form').querySelector('input[name="view_order"]').value;
                const currentUrl = new URL(window.location.href);
                const searchParams = new URLSearchParams(currentUrl.search);
                searchParams.set('view_order', orderId);
                window.location.href = currentUrl.pathname + '?' + searchParams.toString();
            });
        });
    });

    function searchOrders() {
        const form = document.querySelector('form');
        const formData = new FormData(form);
        
        // Hiển thị loading
        const tbody = document.getElementById('showOrder');
        tbody.innerHTML = '<tr><td colspan="7" class="loading">Đang tải dữ liệu...</td></tr>';

        fetch('search_orders.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(html => {
            tbody.innerHTML = html;
            updateURL(formData);
        })
        .catch(error => {
            console.error('Error searching orders:', error);
            tbody.innerHTML = '<tr><td colspan="7" class="error">Có lỗi xảy ra khi tải dữ liệu</td></tr>';
        });
    }

    function updateURL(formData) {
        const params = new URLSearchParams();
        for (const [key, value] of formData.entries()) {
            if (value) {
                params.set(key, value);
            }
        }
        const newUrl = window.location.pathname + '?' + params.toString();
        window.history.pushState({}, '', newUrl);
    }
    </script>
</body>

</html>