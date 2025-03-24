<?php
session_start();
include('database.php');

// Xử lý đăng xuất
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}
$username = isset($_SESSION["username"]) ? $_SESSION["username"] : null;
$customer_id = isset($_SESSION["customer_id"]) ? $_SESSION["customer_id"] : null;

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
// Lấy ID sản phẩm từ URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Kiểm tra nếu ID hợp lệ
if ($id <= 0) {
    die("Sản phẩm không hợp lệ!");
}

// Truy vấn chỉ sản phẩm có ID tương ứng
$sql = "SELECT id, tensp, giaban, thongtinsp, thongsokt, hinhanh, hinhanh2, hinhanh3 FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Kiểm tra nếu sản phẩm tồn tại
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    die("Sản phẩm không tồn tại!");
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
<style>
    .form-inline .form-control {
        border-radius: 25px;
        padding: 8px 15px;
        border: 1px solid #ccc;
    }



    .card2 {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-top: 30px;
        border: none;
        border-radius: 10px;
        overflow: hidden;
    }

    .wrapper {
        display: flex;
        flex-wrap: wrap;
        padding: 20px;
    }

    .preview {
        flex: 1;
        padding: 20px;
    }

    .preview img {
        width: 50%;
        border-radius: 8px;
        object-fit: cover;
    }

    .details {
        flex: 1;
        padding: 20px;
    }

    .product-title {
        font-size: 2.2em;
        font-weight: bold;
        color: #333;
        margin-bottom: 15px;
    }

    .product-description {
        font-size: 1.1em;
        color: #555;
        margin-bottom: 25px;
    }

    .price {
        font-size: 1.6em;
        font-weight: bold;
        color: #e74c3c;
        margin-bottom: 25px;
    }

    .vote {
        font-size: 1.1em;
        margin-bottom: 20px;
        color: #2ecc71;
    }

    h4,
    h5 {
        font-weight: bold;
        color: #333;
        margin-top: 20px;
        margin-bottom: 10px;
    }

    h3 {
        font-size: 1.8em;
        border-bottom: 2px solid #ddd;
        padding-bottom: 5px;
    }

    h4 {
        font-size: 1.5em;
        margin-top: 15px;
        color: #555;
    }

    h5 {
        font-size: 1.3em;
        margin-top: 10px;
        color: #777;
    }

    .product-details {
        list-style-type: none;
        padding: 0;
        margin: 20px 0;
    }

    .product-details li {
        font-size: 1em;
        margin: 8px 0;
    }

    .product-details li strong {
        color: #333;
    }

    .form-group {
        margin-top: 20px;
    }

    #soluong {
        width: 100px;
        padding: 8px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .action button {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 5px;
        cursor: pointer;
        margin-right: 10px;
        font-size: 1.2em;
        transition: background-color 0.3s ease;
    }

    .action button:hover {
        background-color: #218838;
    }

    .like {
        background-color: #f8f9fa;
        border: 1px solid #ccc;
        color: #333;
        padding: 12px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1.3em;
        transition: color 0.3s ease;
    }

    .like:hover {
        color: #e74c3c;
    }



    .float-right a {
        color: white;
        font-size: 1em;
    }

    .mua {
        background-color: #28a745;
        /* Màu xanh lá cây */
        color: white;
        /* Màu chữ trắng */
        border: none;
        /* Bỏ viền */
        padding: 10px 20px;
        /* Khoảng cách bên trong nút */
        border-radius: 5px;
        /* Bo tròn góc */
        cursor: pointer;
        /* Đổi biểu tượng chuột thành hình tay */
        font-size: 16px;
        /* Kích thước chữ */
        transition: background-color 0.3s ease;
        /* Hiệu ứng chuyển màu khi hover */
    }

    .mua:hover {
        background-color: #218838;
        /* Màu xanh lá cây đậm hơn khi hover */
    }




    @media (max-width: 768px) {
        .wrapper {
            flex-direction: column;
        }

        .preview,
        .details {
            width: 100%;
        }

        .product-title {
            font-size: 1.8em;
        }

        .product-description {
            font-size: 1em;
        }

        .price {
            font-size: 1.4em;
        }

        .vote {
            font-size: 1.1em;
        }

        .form-group {
            width: 100%;
        }

        .form-control {
            width: 100%;
        }

    }
</style>

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
                                                <a href="hoadon.php">
                                                    <div class="hd">Đơn hàng đã mua</div>
                                                </a>
                                                <a href="dnurl.php">
                                                    <div class="hd"><i class="fa-light fa-gear" style="font-size:20px"></i> Quản lý</div>
                                                </a>
                                                <a href="index.php?logout=1" onclick="return confirm('Bạn có muốn đăng xuất?')">
                                                    <div class="hd"><i class="fa-light fa-right-from-bracket" style="font-size:20px"></i> Đăng xuất</div>
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




    <main role="main">
        <div class="container mt-4">
            <div class="card2">
                <div class="container-fliud">
                    <form name="frmsanphamchitiet11" id="frmsanphamchitiet11" method="POST">
                        <div class="wrapper row">
                            <div class="preview col-md-6">
                                <img src="sanpham/<?php echo $row['hinhanh']; ?>" alt="<?php echo $row['tensp']; ?>">
                                <img src="sanpham/<?php echo $row['hinhanh2']; ?>" alt="<?php echo $row['tensp']; ?>">
                                <img src="sanpham/<?php echo $row['hinhanh3']; ?>" alt="<?php echo $row['tensp']; ?>">
                            </div>
                            <div class="details col-md-6">
                                <h3><?php echo $row["tensp"]; ?></h3>
                                <div class="price"><?php echo number_format($row["giaban"], 0, ',', '.'); ?>đ</div>

                                <form action="" method="POST">
                                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="product_price" value="<?php echo $row['giaban']; ?>">
                                    <input type="hidden" name="product_img" value="<?php echo $row['hinhanh']; ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" name="add_to_cart" class="mua">Thêm vào giỏ hàng</button>
                                </form>

                                <p class="vote"><?php echo $row["thongtinsp"]; ?></p>
                                <h4>Thông số kỹ thuật</h4>
                                <ul class="product-details">
                                    <li><?php echo nl2br($row["thongsokt"]); ?></li>
                                </ul>
                            </div>


                            <section class="cart">
                                <button class="dong"><i class="fa-regular fa-xmark"></i></button>
                                <!-- <div>Đóng</div> -->
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

    // Kiểm tra sản phẩm đã tồn tại chưa
    $stmt = $conn->prepare("SELECT soluong FROM giohang WHERE product_id = ? AND customer_id = ?");
    $stmt->bind_param("ii", $product_id, $customer_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Sản phẩm đã tồn tại, cập nhật số lượng
        $stmt->bind_result($current_quantity);
        $stmt->fetch();
        $new_quantity = $current_quantity + $quantity;
        $stmt->close();

        $update_stmt = $conn->prepare("UPDATE giohang SET soluong = ? WHERE product_id = ? AND customer_id = ?");
        $update_stmt->bind_param("iii", $new_quantity, $product_id, $customer_id);
        $update_stmt->execute();
        $update_stmt->close();
    } else {
        // Sản phẩm chưa có, thêm mới
        $stmt->close();
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
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <div class="green-line-header"></div>
    <?php include 'footer.php' ?>
    <!-- <script src="js/hoadon.js"></script> -->
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