<?php
session_start();
include('database.php');
include('toast.php');

// Kiểm tra đăng nhập
if (!isset($_SESSION['customer_id'])) {
    $_SESSION['error'] = "Bạn cần đăng nhập để thực hiện thao tác này!";
    header("Location: dn.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

// Lấy thông tin khách hàng
$stmt = $conn->prepare("SELECT name, contact, email, address, password FROM customer WHERE id = ?");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$stmt->bind_result($tenUser, $sdtUser, $emailUser, $diachiUser, $current_password);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tenUser = trim($_POST['infoname']);
    $sdtUser = trim($_POST['infophone']);
    $emailUser = trim($_POST['infoemail']);
    $diachiUser = trim($_POST['infoaddress']);
    $mkHientai = trim($_POST['current-pass']);
    $mkMoi = trim($_POST['new-pass']);
    $xnMkMoi = trim($_POST['confirm-new-pass']);

    // Kiểm tra email hợp lệ
    if (!filter_var($emailUser, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Email không hợp lệ!";
    } elseif (!ctype_digit($sdtUser)) {
        $_SESSION['error'] = "Số điện thoại phải là số!";
    } else {
        // Kiểm tra trùng số điện thoại hoặc tên với tài khoản khác
        $stmt = $conn->prepare("SELECT id FROM customer WHERE (contact = ? OR name = ?) AND id != ?");
        $stmt->bind_param("ssi", $sdtUser, $tenUser, $customer_id);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $_SESSION['error'] = "Tên hoặc số điện thoại đã tồn tại!";
        } else {
            if (!empty($mkMoi)) {
                if (empty($mkHientai)) {
                    $_SESSION['error'] = "Bạn cần nhập mật khẩu hiện tại để đổi mật khẩu!";
                } elseif ($mkHientai !== $current_password) {
                    $_SESSION['error'] = "Mật khẩu hiện tại không đúng!";
                } elseif ($mkMoi === $current_password) {
                    $_SESSION['error'] = "Mật khẩu mới không được trùng với mật khẩu hiện tại!";
                } elseif ($mkMoi !== $xnMkMoi) {
                    $_SESSION['error'] = "Xác nhận mật khẩu mới không khớp!";
                } else {
                    $passwordToUpdate = $mkMoi;
                }
            } else {
                $passwordToUpdate = $current_password;
            }

            if (!isset($_SESSION['error'])) {
                $stmt = $conn->prepare("UPDATE customer SET name=?, contact=?, email=?, address=?, password=? WHERE id=?");
                $stmt->bind_param("sssssi", $tenUser, $sdtUser, $emailUser, $diachiUser, $passwordToUpdate, $customer_id);

                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        $_SESSION['success'] = "Cập nhật thông tin thành công!";
                    } else {
                        $_SESSION['info'] = "Không có thông tin nào thay đổi!";
                    }
                } else {
                    $_SESSION['error'] = "Lỗi: " . $conn->error;
                }
            }
        }
    }
    header("Location: thongtintk.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/app.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="./assets/font/font-awesome-pro-v6-6.2.0/css/all.min.css">
    <script src="https://fontawesome.com/v6/search" crossorigin="anonymous"></script>
    <title>BMT </title>
</head>

<body>
    <header>
        <div class="header-middle">
            <div class="container">
                <div class="header-middle-left">
                    <div class="header-logo">
                        <a href="" id="logo">
                            <img src="image/logo.png" alt="BMT">
                        </a>
                    </div>
                </div>
                <div class="header-middle-center">
                    <form action="" class="form-search">
                        <span class="search-btn">
                            <a href="">
                                <i class="fa-light fa-magnifying-glass"></i>
                            </a>
                        </span>
                        <input type="text" class="form-search-input" id="searchBox" placeholder="Tìm kiếm xe... "
                            onkeyup="searchProducts()">
                        <button class="filter-btn">
                            <i class="fa-light fa-filter-list"></i>
                            <span style="font-size: 14px;">Lọc</span>
                        </button>
                    </form>
                </div>
                <div class="header-middle-right">
                    <ul class="header-middle-right-list">
                        <li class="header-middle-right-item open">
                            <i class="fa-light fa-user" style="color:#139b3a"></i>

                            <div class="auth-container">
                                <div class="user-info">
                                    <div class="dropdownb">
                                        <h1 class="welcome">
                                            <div class="drip" style="display:flex;flex-direction:column">

                                                <!-- Dòng trên -->
                                                <span style="font-size:12px">
                                                    <?php
                                                    if (isset($_SESSION['customer_id'])) {
                                                        echo "Tài khoản";
                                                    } else {
                                                        echo "Đăng nhập/ Đăng ký";
                                                    }
                                                    ?>
                                                </span>

                                                <!-- Dòng dưới -->

                                                <span class="dropdownb-toggle" style="color:green;font-size:17px">


                                                    <?php
                                                    if (isset($_SESSION['customer_id'])) {
                                                        // Lấy tên khách hàng
                                                        $stmt = $conn->prepare("SELECT name FROM customer WHERE id = ?");
                                                        $stmt->bind_param("i", $_SESSION['customer_id']);
                                                        $stmt->execute();
                                                        $stmt->bind_result($name);
                                                        if ($stmt->fetch()) {
                                                            echo htmlspecialchars($name);
                                                        } else {
                                                            echo "Tài khoản";
                                                        }
                                                        $stmt->close();
                                                    } else {
                                                        echo "Tài khoản";
                                                    }
                                                    ?>
                                                    <i class="fa-sharp fa-solid fa-caret-down" style="font-size:12px;"></i>
                                                </span>
                                            </div>
                                        </h1>

                                        <!-- Dropdown -->
                                        <div class="dropdownb-menu">
                                            <?php if (isset($_SESSION['customer_id'])): ?>
                                               
                                                <a href="thongtintk.php">
                                                    <div class="hd"><i class="fa-light fa-circle-user" style="font-size:20px"></i> Tài khoản của tôi</div>
                                                </a>
                                                <a href="hoadon.php">
                                                    <div class="hd"><i class="fa-regular fa-bags-shopping" style="font-size:20px"> </i> Đơn hàng đã mua</div>
                                                </a>

                                                <a href="index.php?logout=1" onclick="return confirm('Bạn có muốn đăng xuất?')">
                                                    <div class="hd"><i class="fa-light fa-right-from-bracket" style="font-size:20px"></i> Đăng xuất</div>
                                                </a>
                                                <a href="dnurl.php">
                                                    <div class="hd"><i class="fa-light fa-gear" style="font-size:20px"></i> Quản lý</div>
                                                </a>
                                            <?php else: ?>
                                                <a href="dn.php">
                                                    <div class="hd"><i class="fa-light fa-right-to-bracket" style="font-size:20px;color:green"></i> Đăng nhập</div>
                                                </a>
                                                <a href="dk.php">
                                                    <div class="hd"><i class="fa-light fa-user-plus" style="font-size:20px;color:green"></i> Đăng ký</div>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="hoadon">
                                    <span class="ravao">
                                        <i class="fa-light fa-basket-shopping"></i> Giỏ hàng
                                    </span>
                                </div>

                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
    <div class="advanced-search">
        <div class="container">
            <div class="advanced-search-category">
                <span>Phân loại </span>
                <select name="" id="advanced-search-category-select" onchange="searchProducts()">
                    <option>Tất cả</option>
                    <option>Dòng Ninja</option>
                    <option>Dòng Z</option>
                    <option>Dòng KLX</option>
                </select>
            </div>
            <div class="advanced-search-price">
                <span>Giá từ</span>
                <input type="number" placeholder="tối thiểu" id="min-price" onchange="searchProducts()">
                <span>đến</span>
                <input type="number" placeholder="tối đa" id="max-price" onchange="searchProducts()">
                <button id="advanced-search-price-btn"><a href=""><i class="fa-light fa-magnifying-glass-dollar"></i></a></button>
            </div>
            <div class="advanced-search-control">
                <button id="sort-ascending" onclick="searchProducts(1)"><i
                        class="fa-regular fa-arrow-up-short-wide"></i></button>
                <button id="sort-descending" onclick="searchProducts(2)"><i
                        class="fa-regular fa-arrow-down-wide-short"></i></button>
                <button id="reset-search" onclick="searchProducts(0)"><i
                        class="fa-light fa-arrow-rotate-right"></i></button>
                <button onclick="closeSearchAdvanced()"><i class="fa-light fa-xmark"></i></button>
            </div>
        </div>
    </div>
    <div class="green-line-header"></div>

    <nav class="header-bottom">
        <div class="container">
            <ul class="menu-list">
                <li class="menu-list-item"><a href="index.php" class="menu-link">Trang chủ</a></li>

                <div class="dropdown">
                    <span>Sản phẩm</span>
                    <div class="dropdown-content">
                        <li class="menu-list-item"><a href="NINJA.php" class="menu-link">Dòng Ninja</a></li>
                        <li class="menu-list-item"><a href="Z.php" class="menu-link">Dòng Z</a></li>
                        <li class="menu-list-item"><a href="KLX.php" class="menu-link">Dòng KLX</a></li>
                    </div>
                </div>
                <li class="menu-list-item"><a href="index.php" class="menu-link">Tin tức </a></li>
                <li class="menu-list-item"><a href="index.php" class="menu-link">Điều khoản</a></li>

            </ul>
        </div>
    </nav>
    <div class="container" id="account-user">
        <div class="main-account">
            <div class="main-account-header">
                <h3>Thông tin tài khoản của bạn</h3>
                <p>Quản lý thông tin để bảo mật tài khoản</p>
            </div>
            <form class="main-account-body" method="POST">

                <div class="main-account-body-col">
                    <div class="form-group">
                        <label for="infoname" class="form-label">Tên tài khoản</label>
                        <input class="form-control" type="text" name="infoname" value="<?php echo htmlspecialchars($tenUser); ?>" required><br>
                    </div>
                    <div class="form-group">
                        <label for="infophone" class="form-label">Số điện thoại</label>
                        <input class="form-control" type="text" name="infophone" value="<?php echo htmlspecialchars(string: $sdtUser); ?>" required><br>
                    </div>
                    <div class="form-group">
                        <label for="infoemail" class="form-label">Email</label>
                            <input class="form-control" type="email" name="infoemail" id="infoemail" value="<?php echo htmlspecialchars(string: $emailUser); ?>" required><br>
                        <span class="inforemail-error form-message"></span>
                    </div>
                    <div class="form-group">
                        <label for="infoaddress" class="form-label">Địa chỉ</label>
                            <input class="form-control" type="text" name="infoaddress" id="infoaddress" value="<?php echo htmlspecialchars(string: $diachiUser); ?>" required><br>

                            
                    </div>
                </div>
                <div class="main-account-body-col">
                    <div class="form-group">
                        <label for="" class="form-label w60">Mật khẩu hiện tại</label>
                        <input class="form-control" type="password" name="current-pass" id="password-cur-info"
                            placeholder="Nhập mật khẩu hiện tại">
                        <span class="password-cur-info-error form-message"></span>
                    </div>
                    <div class="form-group">
                        <label for="" class="form-label w60">Mật khẩu mới </label>
                        <input class="form-control" type="password" name="new-pass" id="password-after-info"
                            placeholder="Nhập mật khẩu mới">
                        <span class="password-after-info-error form-message"></span>
                    </div>
                    <div class="form-group">
                        <label for="" class="form-label w60">Xác nhận mật khẩu mới</label>
                        <input class="form-control" type="password" name="confirm-new-pass" id="password-comfirm-info"
                            placeholder="Nhập lại mật khẩu mới">
                        <span class="password-after-comfirm-error form-message"></span>
                    </div>
                </div>
                <div class="main-account-body-row">
                    <div>
                        <button id="save-info-user" type="submit"><i
                                class="fa-regular fa-floppy-disk"></i> Lưu thay đổi
                        </button>
                    </div>
                    <div>
                        <button id="save-password" type="submit"><i class="fa-regular fa-key"></i> Đổi
                            mật khẩu
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <section class="cart">
        <button class="dong"><i class="fa-regular fa-xmark"></i></button>
        <div style="margin-top: 45px;margin-bottom: 20px;">Danh sách mua hàng</div>
        <form action="" method="POST">
            <?php
            if (isset($_POST['add_to_cart'])) {
                if (!$customer_id) {
                    die("Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng!");
                }

                $product_id = $_POST['product_id'];
                $product_price = $_POST['product_price'];
                $product_img = $_POST['product_img'];
                $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

                // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng chưa
                $stmt = $conn->prepare("SELECT soluong FROM giohang WHERE product_id = ? AND customer_id = ?");
                $stmt->bind_param("ii", $product_id, $customer_id);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                } else {
                    $stmt = $conn->prepare("INSERT INTO giohang (customer_id, product_id, soluong, price, img) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("iiiss", $customer_id, $product_id, $quantity, $product_price, $product_img);
                    $stmt->execute();
                }
            }


            ?>

            <table>
                <thead>
                    <tr>
                        <th>
                            Sản phẩm
                        </th>
                        <th>Tên</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Chọn</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Kiểm tra xem người dùng đã đăng nhập chưa
                    if (!isset($_SESSION['customer_id'])) {
                        echo "<tr><td colspan='4'>Bạn cần đăng nhập để xem giỏ hàng!</td></tr>";
                    } else {
                        $customer_id = $_SESSION['customer_id'];

                        // Truy vấn lấy sản phẩm từ bảng giỏ hàng
                        $sql = "SELECT g.id, g.product_id, g.soluong, g.price, g.img, p.tensp 
                                FROM giohang g
                                JOIN products p ON g.product_id = p.id
                                WHERE g.customer_id = ?";

                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $customer_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        $total_price = 0;

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $subtotal = $row["soluong"] * $row["price"];
                                $total_price += $subtotal;
                                echo '
                                <tr>
                                    <td style="display: flex; align-items: center;">
                                        <img style="width: 90px;" src="sanpham/' . $row["img"] . '" alt="' . htmlspecialchars($row["tensp"]) . '">
                                    </td>
                                    <td><span>' . htmlspecialchars($row["tensp"]) . '</span></td>
                                    <td>
                                        <p><span>' . number_format($row["price"], 0, ',', '.') . '</span><sup>đ</sup></p>
                                    </td>
                                    <td>
                                        <input style="width: 40px; outline: none;" type="number" value="' . $row["soluong"] . '" min="1" class="cart-quantity" data-cart-id="' . $row["id"] . '">
                                    </td>
                                    <td style="cursor: pointer;" class="delete-item" data-cart-id="' . $row["id"] . '">Xóa</td>
                                </tr>';
                            }
                        } else {
                            echo "<tr><td colspan='4'>Giỏ hàng của bạn đang trống!</td></tr>";
                        }
                    }
                    ?>
                </tbody>

            </table>
            <div style="text-align: center;" class="price-total">
                <p style="font-weight: bold; margin-top: 10px; margin-bottom: 20px;">
                    Tổng tiền: <span><?php echo number_format($total_price, 0, ',', '.'); ?></span>đ
                </p>
            </div>
            <a class="thanhtoan" href="thanhtoan.php">Thanh toán</a>
        </form>
    </section>

    <div class="green-line-header"></div>

    <?php include 'footer.php' ?>
    <script src="js/hoadon.js"></script>
    <script src="js/giohang.js"></script>
    <script src="js/phantrang.js"></script>
    <script src="js/ssbutton.js"></script>





</body>
<?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<script>if(window.history.replaceState){window.history.replaceState(null, null, window.location.href);}</script>";
}
?>

</html>