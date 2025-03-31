<?php
session_start();
include('database.php');
include('toast.php');


// Xử lý đăng xuất
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}
$username = isset($_SESSION["username"]) ? $_SESSION["username"] : null;
$customer_id = isset($_SESSION["customer_id"]) ? $_SESSION["customer_id"] : null;

if (!isset($_SESSION['customer_id'])) {
    $_SESSION['error'] = "Bạn cần đăng nhập để thực hiện thao tác này!";
    header("Location: dn.php");
    exit();
}
// Nếu có username, lấy ID từ bảng customer
if ($username && !$customer_id) {
    $stmt = $conn->prepare("SELECT id FROM customer WHERE name = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($customer_id);
    if ($stmt->fetch()) {
        $_SESSION["customer_id"] = $customer_id;
    }
    $stmt->close();
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
        
                        </li>
                        <li class="menu-list-item">
                            <a href="index.php?category=Dòng Ninja" class="menu-link <?php echo $selected_category == 'Dòng Ninja' ? 'active' : ''; ?>">
                                Dòng Ninja
                            </a>
                        </li>
                        <li class="menu-list-item">
                            <a href="index.php?category=Dòng Z" class="menu-link <?php echo $selected_category == 'Dòng Z' ? 'active' : ''; ?>">
                                Dòng Z
                            </a>
                        </li>
                        <li class="menu-list-item">
                            <a href="index.php?category=Dòng KLX" class="menu-link <?php echo $selected_category == 'Dòng KLX' ? 'active' : ''; ?>">
                                Dòng KLX
                            </a>
                        </li>
                    </div>
                </div>
                <li class="menu-list-item"><a href="index.php" class="menu-link">Tin tức </a></li>
                <li class="menu-list-item"><a href="index.php" class="menu-link">Điều khoản</a></li>

            </ul>
        </div>
    </nav>
    <div class="container" id="order-history">
        <div class="main-account">
            <div class="main-account-header">
                <h3>Quản lý đơn hàng của bạn</h3>
                <p>Xem chi tiết, trạng thái của những đơn hàng đã đặt.</p>
            </div>
            <div class="main-account-body">
                <div class="order-history-section">
                    <?php
                    if (!$customer_id) {
                        echo "<p>Bạn cần đăng nhập để xem lịch sử đơn hàng!</p>";
                    } else {
                        $sql_orders = "SELECT * FROM orders WHERE customer_id = ?";
                        $stmt_orders = $conn->prepare($sql_orders);
                        $stmt_orders->bind_param("i", $customer_id);
                        $stmt_orders->execute();
                        $result_orders = $stmt_orders->get_result();

                        if ($result_orders->num_rows > 0) {
                            while ($order = $result_orders->fetch_assoc()) {
                                $order_id = $order['id'];
                    ?>
                                <div class="order-history-group">
                                    
                                    <?php
                                    // Chi tiết đơn hàng
                                    $sql_details = "SELECT od.*, p.tensp, p.hinhanh
                                                FROM order_details od
                                                JOIN products p ON od.product_id = p.id
                                                WHERE od.order_id = ?";
                                    $stmt_details = $conn->prepare($sql_details);
                                    $stmt_details->bind_param("i", $order_id);
                                    $stmt_details->execute();
                                    $result_details = $stmt_details->get_result();

                                    while ($detail = $result_details->fetch_assoc()) { ?>
                                        <div class="order-history">
                                            <div class="order-history-left">
                                                <img src="sanpham/<?= $detail['hinhanh'] ?>" alt="<?= htmlspecialchars(string: $detail['tensp']) ?>">
                                                <div class="order-history-info">
                                                    <h4><?= htmlspecialchars($detail['tensp']) ?></h4>
                                                    <p class="order-history-quantity">x<?= $detail['soluong'] ?></p>
                                                </div>
                                            </div>
                                            <div class="order-history-right">
                                                <div class="order-history-price">
                                                    <span class="order-history-current-price"><?= number_format($detail['price'], 0, ',', '.') ?>₫</span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <div class="order-history-control">
                                        <div class="order-history-status">
                                            <?php
                                            $status_class = '';
                                            $status_text = '';
                                            switch($order['status']) {
                                                case 'chuaxuly':
                                                    $status_class = 'no-complete';
                                                    $status_text = 'Chưa xử lý';
                                                    break;
                                                case 'daxuly':
                                                    $status_class = 'processing';
                                                    $status_text = 'Đã xử lý';
                                                    break;
                                                case 'chuagiao':
                                                    $status_class = 'pending';
                                                    $status_text = 'Chưa giao';
                                                    break;
                                                case 'dagiao':
                                                    $status_class = 'complete';
                                                    $status_text = 'Đã giao';
                                                    break;
                                            }
                                            ?>
                                            <span class="order-history-status-sp <?= $status_class ?>"><?= $status_text ?></span>

                                            <!-- Nút "Xem chi tiết" mới với data-* -->
                                            <button class="order-history-detail-btn" id="order-history-detail"
                                                data-created-at="<?= $order['created_at'] ?>"
                                                data-delivery-type="<?= $order['delivery_type'] ?>"
                                                data-address="<?= htmlspecialchars($order['address']) ?>"
                                                data-recipient-name="<?= htmlspecialchars($order['recipient_name']) ?>"
                                                data-recipient-phone="<?= htmlspecialchars($order['recipient_phone']) ?>"><i class="fa-regular fa-eye"></i> Xem chi tiết
                                            </button>
                                        </div>

                                    </div>
                                </div>
                    <?php
                                $stmt_details->close();
                            }
                        } else {
                            echo "<p>Bạn chưa có đơn hàng nào.</p>";
                        }
                        $stmt_orders->close();
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="modal detail-order">
        <div class="modal-container mdl-cnt">
            <h3 class="modal-container-title">Thông tin đơn hàng</h3>
            <button class="form-close" onclick="closeModal()"><i class="fa-regular fa-xmark"></i></button>
            <div class="detail-order-content">
                <ul class="detail-order-group">
                    <li class="detail-order-item">
                        <span class="detail-order-item-left"><i class="fa-light fa-calendar-days"></i> Ngày đặt hàng</span>
                        <span class="detail-order-item-right" id="modal-created-at"></span>
                    </li>
                    <li class="detail-order-item">
                        <span class="detail-order-item-left"><i class="fa-light fa-truck"></i> Hình thức giao</span>
                        <span class="detail-order-item-right" id="modal-delivery-type"></span>
                    </li>
                    <li class="detail-order-item">
                        <span class="detail-order-item-left"><i class="fa-light fa-location-dot"></i> Địa điểm nhận</span>
                        <span class="detail-order-item-right" id="modal-address"></span>
                    </li>
                    <li class="detail-order-item">
                        <span class="detail-order-item-left"><i class="fa-thin fa-person"></i> Người nhận</span>
                        <span class="detail-order-item-right" id="modal-recipient-name"></span>
                    </li>
                    <li class="detail-order-item">
                        <span class="detail-order-item-left"><i class="fa-light fa-phone"></i> Số điện thoại nhận</span>
                        <span class="detail-order-item-right" id="modal-recipient-phone"></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>





    <section class="cart">
        <button class="dong"><i class="fa-regular fa-xmark"></i></button>
        <div style="margin-top: 45px;margin-bottom: 20px;">Danh sách mua hàng</div>
        <form action="" method="POST">
            <?php
            if (isset($_POST['add_to_cart'])) {
                if (!isset($_SESSION['customer_id'])) {
                    $_SESSION['error'] = "Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!";
                    header("Location: index.php");
                    exit();
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
                    $stmt->close();
                } else {
                    $stmt = $conn->prepare("INSERT INTO giohang (customer_id, product_id, soluong, price, img) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("iiiss", $customer_id, $product_id, $quantity, $product_price, $product_img);
                    $stmt->execute();
                    $stmt->close();
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
<?php $conn->close(); ?>

</html>