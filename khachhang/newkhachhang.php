<?php

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: dnurl.php");
    exit();
}

// Database configuration
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
include('../toast.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture form data
    $tenMon = htmlspecialchars($_POST['fullname'], ENT_QUOTES, 'UTF-8');
    $category = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');
    $mauXe = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

    // Sanitize inputs to prevent SQL injection
    $tenMon = $conn->real_escape_string($tenMon);
    $category = $conn->real_escape_string($category);
    $mauXe = $conn->real_escape_string($mauXe);


    // Insert data into the database
    $sql = "INSERT INTO customer (name, contact, joindate, password, status)
            VALUES ('$tenMon', '$category',NOW(), '$mauXe', 1)";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Thêm khách hàng thành công!";
        header("Location: khachhang.php");
        exit();
    } else {
        $_SESSION['error'] = "Không thể thêm khách hàng! Lỗi: " . $conn->error;
        header("Location: khachhang.php");
        exit();
    }
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
                        <a href="../sanpham.php" class="sidebar-link">
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
                    <div class="admin-control-left">
                        <select name="tinh-trang-user" id="tinh-trang-user">
                            <option value="2">Tất cả</option>
                            <option value="1">Hoạt động</option>
                            <option value="0">Bị khóa</option>
                        </select>
                    </div>
                    <div class="admin-control-center">
                        <form action="" class="form-search">
                            <span class="search-btn"><i class="fa-light fa-magnifying-glass"></i></span>
                            <input id="form-search-user" type="text" class="form-search-input"
                                placeholder="Tìm kiếm mã khách hàng, tên khách hàng...">
                        </form>
                    </div>
                    <div class="admin-control-right">
                        <form action="" class="fillter-date">
                            <div>
                                <label for="time-start">Từ</label>
                                <input type="date" class="form-control-date" id="time-start-user" value="">
                            </div>
                            <div>
                                <label for="time-end">Đến</label>
                                <input type="date" class="form-control-date" id="time-end-user" value="">
                            </div>
                        </form>
                        <button class="btn-reset-order"><i
                                class="fa-light fa-arrow-rotate-right"></i></button>
                        <a href="newkhachhang.php"><button id="btn-add-user" class="btn-control-large"><i
                                    class="fa-light fa-plus"></i> <span>Thêm khách hàng</span></button></a>
                    </div>
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
                                <td></td>
                            </tr>
                        </thead>
                        <tbody id="show-user">
                            <?php
                            $servername = "localhost";
                            $username = "root";
                            $password = "";
                            $dbname = "admindoan";

                            $conn = new mysqli($servername, $username, $password, $dbname);

                            if ($conn->connect_error) {
                                die("Kết nối thất bại: " . $conn->connect_error);
                            }


                            $sql = "SELECT id, name, contact, joindate, status FROM customer";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {

                                    echo "<tr>";
                                    echo "<td>" . $row["id"] . "</td>";
                                    echo "<td>" . $row["name"] . "</td>";
                                    echo "<td>" . $row["contact"] . "</td>";
                                    echo "<td>" . $row["joindate"] . "</td>";
                                    echo "<td><span class='status-" . ($row["status"] == 1 ? "complete" : "no-complete") . "'>" . ($row["status"] == 1 ? "Hoạt động" : "Bị khóa") . "</span></td>";
                                    echo "<td class='control control-table'>";
                                    echo "<a href='changekhachhang.php?id=" . $row["id"] . "'><button class='btn-edit' id='edit-account'  >";
                                    echo "<i class='fa-light fa-pen-to-square'></i></button></a>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            }

                            ?>




                        </tbody>
                    </table>
                </div>
                <!-- </div> -->
            </div>

        </main>
    </div>
    <div class="modal signup open">
        <div class="modal-container">
            <h3 class="modal-container-title add-account-e ">THÊM KHÁCH HÀNG MỚI</h3>
            <a href="khachhang.php"><button class="modal-close"><i class="fa-regular fa-xmark"></i></button></a>
            <div class="form-content sign-up">
                <form action="" class="signup-form" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="fullname" class="form-label">Tên đầy đủ</label>
                        <input id="fullname" name="fullname" type="text" placeholder="VD: Nguyễn Văn A" class="form-control" maxlength="20" required> 
                    </div>
                    <div class="form-group">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input id="phone" name="phone" type="text" placeholder="Nhập số điện thoại" class="form-control" maxlength="11" required>
                    </div>
                    <div class="form-group">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <input id="password" name="password" type="text" placeholder="Nhập mật khẩu" class="form-control" maxlength="20" required>
                    </div>

                    <button class="form-submit add-account-e" id="signup-button">Đăng ký</button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>