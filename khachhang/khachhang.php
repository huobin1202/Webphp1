<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: dnurl.php");
    exit();
}
include('../database.php');
include('../toast.php');

// Xử lý thêm khách hàng mới
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = htmlspecialchars($_POST['fullname'], ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

    // Kiểm tra trùng số điện thoại
    $check_phone = $conn->prepare("SELECT id FROM customer WHERE contact = ?");
    $check_phone->bind_param("s", $phone);
    $check_phone->execute();
    if ($check_phone->get_result()->num_rows > 0) {
        $_SESSION['error'] = "Số điện thoại này đã được đăng ký!";
    } else {
        // Thêm khách hàng mới
        $sql = "INSERT INTO customer (name, contact, joindate, password, status) VALUES (?, ?, NOW(), ?, 1)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $phone, $password);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Thêm khách hàng thành công!";
        } else {
            $_SESSION['error'] = "Không thể thêm khách hàng! Lỗi: " . $conn->error;
        }
    }
    header("Location: khachhang.php");
    exit();
}

// Xử lý cập nhật khách hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = $_POST['id'];
    $name = $_POST['fullname'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $status = isset($_POST['user-status']) ? 1 : 0;

    $sql = "UPDATE customer SET name=?, contact=?, password=?, status=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $name, $phone, $password, $status, $id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Cập nhật thông tin thành công!";
    } else {
        $_SESSION['error'] = "Không thể cập nhật thông tin! Lỗi: " . $conn->error;
    }
    header("Location: khachhang.php");
    exit();
}

// Lấy thông tin khách hàng cần sửa nếu có
$edit_customer = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM customer WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $edit_customer = $stmt->get_result()->fetch_assoc();
}

// Xây dựng câu truy vấn SQL với các điều kiện lọc
$sql = "SELECT id, name, contact, joindate, status FROM customer WHERE 1=1";
$params = array();

// Lọc theo tìm kiếm
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = '%' . $_GET['search'] . '%';
    $sql .= " AND (id LIKE ? OR name LIKE ? OR contact LIKE ?)";
    array_push($params, $search, $search, $search);
}

// Lọc theo trạng thái
if (isset($_GET['status']) && $_GET['status'] !== '2') {
    $sql .= " AND status = ?";
    array_push($params, $_GET['status']);
}

// Lọc theo ngày
if (isset($_GET['start_date']) && !empty($_GET['start_date'])) {
    $sql .= " AND DATE(joindate) >= ?";
    array_push($params, $_GET['start_date']);
}

if (isset($_GET['end_date']) && !empty($_GET['end_date'])) {
    $sql .= " AND DATE(joindate) <= ?";
    array_push($params, $_GET['end_date']);
}

// Chuẩn bị và thực thi truy vấn
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $types = str_repeat('s', count($params));
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE php>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='../image/logo.png' rel='icon' type='image/x-icon' />
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
                    <li class="sidebar-list-item tab-content active">
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


            <!-- Account  -->
            <div class="section">
                <div class="admin-control">
                    <form method="GET" action="" class="admin-control-wrapper">
                        <div class="admin-control-left">
                            <select name="status" id="tinh-trang-user">
                                <option value="2" <?php echo (!isset($_GET['status']) || $_GET['status'] === '2') ? 'selected' : ''; ?>>Tất cả</option>
                                <option value="1" <?php echo (isset($_GET['status']) && $_GET['status'] === '1') ? 'selected' : ''; ?>>Hoạt động</option>
                                <option value="0" <?php echo (isset($_GET['status']) && $_GET['status'] === '0') ? 'selected' : ''; ?>>Bị khóa</option>
                            </select>
                        </div>
                        <div class="admin-control-center">
                            <div class="form-search">
                                <span class="search-btn"><i class="fa-light fa-magnifying-glass"></i></span>
                                <input type="text" name="search" class="form-search-input" 
                                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                                    placeholder="Tìm kiếm mã khách hàng, tên khách hàng...">
                            </div>
                        </div>
                        <div class="admin-control-right">
                            <div class="fillter-date">
                                <div>
                                    <label for="start_date">Từ</label>
                                    <input type="date" name="start_date" class="form-control-date" 
                                        value="<?php echo isset($_GET['start_date']) ? htmlspecialchars($_GET['start_date']) : ''; ?>">
                                </div>
                                <div>
                                    <label for="end_date">Đến</label>
                                    <input type="date" name="end_date" class="form-control-date" 
                                        value="<?php echo isset($_GET['end_date']) ? htmlspecialchars($_GET['end_date']) : ''; ?>">
                                </div>
                                <button type="submit" class="btn-reset-order"><i class="fa-light fa-filter"></i></button>
                                <a href="khachhang.php" class="btn-reset-order"><i class="fa-light fa-arrow-rotate-right"></i></a>
                            </div>
                            <a href="?add=1">
                                <button type="button" id="btn-add-user" class="btn-control-large">
                                    <i class="fa-light fa-plus"></i> <span>Thêm khách hàng</span>
                                </button>
                            </a>
                        </div>
                    </form>
                </div>
                <div class="table">
                    <table width="100%">
                        <thead>
                            <tr>
                                <td>MÃ KH</td>
                                <td>Họ và tên</td>
                                <td>Liên hệ</td>
                                <td>Ngày tham gia</td>
                                <td>Tình trạng</td>
                                <td>Thao tác</td>
                            </tr>
                        </thead>
                        <tbody id="show-user">
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["id"] . "</td>";
                                    echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["contact"]) . "</td>";
                                    echo "<td>" . $row["joindate"] . "</td>";
                                    echo "<td><span class='status-" . ($row["status"] == 1 ? "complete" : "no-complete") . "'>" .
                                        ($row["status"] == 1 ? "Hoạt động" : "Bị khóa") . "</span></td>";
                                    echo "<td class='control control-table'>";
                                    echo "<a href='?edit=" . $row["id"] . "'><button class='btn-edit'>";
                                    echo "<i class='fa-light fa-pen-to-square'></i></button></a>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='no-products'>Không tìm thấy khách hàng nào!</td></tr>";
                            }
                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
    <div class="modal signup <?php echo isset($_GET['add']) ? 'open' : ''; ?>">
        <div class="modal-container">
            <h3 class="modal-container-title add-account-e">THÊM KHÁCH HÀNG MỚI</h3>
            <a href="khachhang.php"><button class="modal-close"><i class="fa-regular fa-xmark"></i></button></a>
            <div class="form-content sign-up">
                <form action="" method="POST" class="signup-form">
                    <input type="hidden" name="action" value="add">
                    <div class="form-group">
                        <label for="fullname" class="form-label">Tên đầy đủ</label>
                        <input id="fullname" name="fullname" type="text" placeholder="VD: Nguyễn Văn A" 
                            class="form-control" maxlength="20" required>
                    </div>
                    <div class="form-group">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input id="phone" name="phone" type="text" placeholder="Nhập số điện thoại" 
                            class="form-control" maxlength="11" required>
                    </div>
                    <div class="form-group">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <input id="password" name="password" type="text" placeholder="Nhập mật khẩu" 
                            class="form-control" maxlength="20" required>
                    </div>
                    <button class="form-submit add-account-e">Đăng ký</button>
                </form>
            </div>
        </div>
    </div>

    <div class="modal signup <?php echo isset($_GET['edit']) ? 'open' : ''; ?>">
        <div class="modal-container">
            <h3 class="modal-container-title add-account-e">CHỈNH SỬA KHÁCH HÀNG</h3>
            <a href="khachhang.php"><button class="modal-close"><i class="fa-regular fa-xmark"></i></button></a>
            <div class="form-content sign-up">
                <?php if ($edit_customer): ?>
                <form action="" method="POST" class="signup-form">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" value="<?php echo $edit_customer['id']; ?>">
                    <div class="form-group">
                        <label for="fullname" class="form-label">Tên đầy đủ</label>
                        <input id="fullname" name="fullname" type="text" class="form-control" 
                            value="<?php echo htmlspecialchars($edit_customer['name']); ?>" maxlength="20" required>
                    </div>
                    <div class="form-group">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input id="phone" name="phone" type="text" class="form-control" 
                            value="<?php echo htmlspecialchars($edit_customer['contact']); ?>" maxlength="11" required>
                    </div>
                    <div class="form-group">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <input id="password" name="password" type="text" class="form-control" 
                            value="<?php echo htmlspecialchars($edit_customer['password']); ?>" maxlength="20" required>
                    </div>
                    <div class="form-group edit-account-e">
                        <label for="user-status" class="form-label">Trạng thái</label>
                        <input type="checkbox" id="user-status" name="user-status" class="switch-input" 
                            value="1" <?php echo $edit_customer['status'] == 1 ? 'checked' : ''; ?>>
                        <label for="user-status" class="switch"></label>
                    </div>
                    <button class="form-submit add-account-e">Cập nhật</button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>


    <script src="../assets/js/admin.js"></script>
</body>

</html>