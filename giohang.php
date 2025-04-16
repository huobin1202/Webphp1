<?php
session_start();
include('database.php');
include('toast.php');
if (isset($_SESSION['success'])) {
    echo '<script>alert("' . $_SESSION['success'] . '");</script>';
    unset($_SESSION['success']); // Xóa thông báo sau khi hiển thị
}

// Xử lý đăng xuất
if (isset($_GET['logout'])) {

    session_unset();
    session_destroy();

    header("Location: index.php");
    exit;
}
$username = isset($_SESSION["username"]) ? $_SESSION["username"] : null;
$customer_id = isset($_SESSION["customer_id"]) ? $_SESSION["customer_id"] : null;
$total_price = 0;
$role = null;


if ($username && !$customer_id) {
    $stmt = $conn->prepare("SELECT id, role FROM customer WHERE name = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($customer_id, $role);
    if ($stmt->fetch()) {
        $_SESSION["customer_id"] = $customer_id;
        $_SESSION["role"] = $role;
    }
    $stmt->close();
} else {
    $role = isset($_SESSION["role"]) ? $_SESSION["role"] : null;
}


// Kiểm tra đăng nhập
if (!isset($_SESSION['customer_id'])) {
    header("Location: dn.php");
    exit;
}

// Lấy thông tin giỏ hàng từ session
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
$total = 0;

// Xử lý cập nhật số lượng
if (isset($_POST['update_quantity']) && isset($_SESSION['customer_id'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $customer_id = $_SESSION['customer_id'];

    if ($quantity > 0 && $quantity <= 10) {
        $stmt = $conn->prepare("UPDATE giohang SET soluong = ? WHERE product_id = ? AND customer_id = ?");
        $stmt->bind_param("iii", $quantity, $product_id, $customer_id);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Cập nhật số lượng thành công!";
        } else {
            $_SESSION['error'] = "Không thể cập nhật số lượng!";
        }
        $stmt->close();
    }
    header("Location: giohang.php");
    exit;
}

// Xử lý xóa sản phẩm
if (isset($_GET['remove']) && isset($_SESSION['customer_id'])) {
    $product_id = $_GET['remove'];
    $customer_id = $_SESSION['customer_id'];

    $stmt = $conn->prepare("DELETE FROM giohang WHERE product_id = ? AND customer_id = ?");
    $stmt->bind_param("ii", $product_id, $customer_id);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Đã xóa sản phẩm khỏi giỏ hàng!";
    } else {
        $_SESSION['error'] = "Không thể xóa sản phẩm!";
    }
    $stmt->close();
    header("Location: giohang.php");
    exit;
}

// Xử lý thêm sản phẩm vào giỏ hàng
if (isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['customer_id'])) {
        header("Location: dn.php");
        exit;
    }

    $customer_id = $_SESSION['customer_id'];
    $product_id = $_POST['product_id'];
    $product_price = $_POST['product_price'];
    $product_img = $_POST['product_img'];
    $quantity = 1; // Mặc định số lượng là 1

    // Kiểm tra sản phẩm đã tồn tại trong giỏ hàng chưa
    $stmt = $conn->prepare("SELECT soluong FROM giohang WHERE product_id = ? AND customer_id = ?");
    $stmt->bind_param("ii", $product_id, $customer_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Nếu sản phẩm đã tồn tại, tăng số lượng lên 1
        $stmt->bind_result($current_quantity);
        $stmt->fetch();
        $new_quantity = $current_quantity + 1;
        $stmt->close();

        $update_stmt = $conn->prepare("UPDATE giohang SET soluong = ? WHERE product_id = ? AND customer_id = ?");
        $update_stmt->bind_param("iii", $new_quantity, $product_id, $customer_id);
        $update_stmt->execute();
        $update_stmt->close();
    } else {
        // Nếu sản phẩm chưa tồn tại, thêm mới vào giỏ hàng
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO giohang (customer_id, product_id, soluong, price, img) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiss", $customer_id, $product_id, $quantity, $product_price, $product_img);
        $stmt->execute();
        $stmt->close();
    }

    $_SESSION['success'] = "Đã thêm sản phẩm vào giỏ hàng!";
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
    <style>
        .display {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .menu-link.active {
            color: #139b3a !important;
            font-weight: bold;
        }

        .menu-link.active::after {
            width: 100%;
        }

        .no-products {
            text-align: center;
            width: 100%;
            padding: 20px;
            color: #666;
            font-style: italic;
        }

        /* Thêm hiệu ứng transition cho menu links */
        .menu-link {
            transition: color 0.3s ease;
        }

        .menu-link:hover {
            color: #139b3a !important;
        }

        /* CSS cho phần điều khiển số lượng */
        .quantity-controls {
            display: flex;
            align-items: center;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            overflow: hidden;
            width: fit-content;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .quantity-btn {
            width: 35px;
            height: 35px;
            border: none;
            background: white;
            color: #139b3a;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .quantity-btn:hover {
            background: #f5f5f5;
            color: #0f7c2e;
        }

        .quantity-btn:active {
            background: #e8e8e8;
        }

        .quantity-input {
            width: 45px;
            height: 35px;
            text-align: center;
            border: none;
            border-left: 1px solid #e0e0e0;
            border-right: 1px solid #e0e0e0;
            font-size: 14px;
            font-weight: 500;
            background: white;
            -moz-appearance: textfield;
            /* Firefox */
        }

        .quantity-input::-webkit-outer-spin-button,
        .quantity-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .product-price {
            font-size: 18px;
            font-weight: 600;
            color: #139b3a;
            min-width: 150px;
            text-align: right;
        }

        .button-container {
            display: flex;
            align-items: center;
            gap: 15px;
            justify-content: space-between;
            width: 100%;
        }

        .controls-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* CSS cho nút cập nhật */
        .update-btn {
            height: 35px;
            padding: 0 15px;
            background-color: #139b3a;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .update-btn:hover {
            background-color: #0f7c2e;
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        }

        .update-btn:active {
            transform: translateY(0);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .update-btn i {
            font-size: 12px;
        }

        /* CSS cho nút xóa */
        .delete-btn {
            height: 35px;
            padding: 0 15px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .delete-btn:hover {
            background-color: #c82333;
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        }

        .delete-btn:active {
            transform: translateY(0);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .delete-btn i {
            font-size: 12px;
        }
    </style>
    <title>BMT </title>
</head>

<body>
    <header>
        <div class="header-middle">
            <div class="container">
                <div class="header-middle-left">
                    <div class="header-logo">
                        <a href="index.php" id="logo">
                            <img src="image/logo.png" alt="BMT">
                        </a>
                    </div>
                </div>
                <div class="header-middle-center">
                    <form action="timkiem.php" method="GET" class="form-search">
                            <button type="submit" class="search-btn">
                                <i class="fa-light fa-magnifying-glass"></i>
                            </button>
                        <input type="text" name="tukhoa" class="form-search-input" id="searchBox" placeholder="Tìm kiếm xe... ">
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
                                                    <div class="hd"><i class="fa-regular fa-bags-shopping" style="font-size:20px"></i> Đơn hàng đã mua</div>
                                                </a>
                                                <a href="index.php?logout=1" onclick="return confirm('Bạn có muốn đăng xuất?')">
                                                    <div class="hd"><i class="fa-light fa-right-from-bracket" style="font-size:20px"></i> Đăng xuất</div>
                                                </a>
                                                <?php if ($role === 'admin'): ?>
                                                    <a href="dnurl.php">
                                                        <div class="hd"><i class="fa-light fa-gear" style="font-size:20px"></i> Quản lý</div>
                                                    </a>
                                                <?php endif; ?>
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



    <main class="main-wrapper">
        <div class="container" style="margin: 30px auto;">
            <div class="home-title-block" id="home-title">
                <h2 class="home-title">GIỎ HÀNG</h2>
                <div class="border-line"></div>
            </div>


            <div style="display: flex; gap: 30px;">
                <!-- Phần danh sách sản phẩm -->
                <div style="flex: 1;">
                    <?php
                    // Lấy sản phẩm từ giỏ hàng trong database
                    $sql = "SELECT g.*, p.tensp, p.giaban, p.hinhanh 
                           FROM giohang g 
                           JOIN products p ON g.product_id = p.id 
                           WHERE g.customer_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $customer_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $total = 0;

                    if ($result->num_rows == 0):
                    ?>
                        <div style="text-align: center; padding: 20px;">
                            <p>Giỏ hàng trống</p>
                            <a href="index.php" class="mua" style="display: inline-block; margin-top: 20px;">Tiếp tục mua sắm</a>
                        </div>
                        <?php else:
                        while ($item = $result->fetch_assoc()):
                            $subtotal = $item['giaban'] * $item['soluong'];
                            $total += $subtotal;
                        ?>
                            <div style="display: flex; padding: 20px; border: 1px solid #eee; margin-bottom: 20px; border-radius: 8px;">
                                <div style="width: 150px; margin-right: 20px;">
                                    <img src="sanpham/<?php echo htmlspecialchars($item['hinhanh']); ?>"
                                        alt="<?php echo htmlspecialchars($item['tensp']); ?>"
                                        style="width: 100%; height: auto;">
                                </div>
                                <div style="flex: 1;">
                                    <h3 style="margin-bottom: 10px;"><?php echo htmlspecialchars($item['tensp']); ?></h3>
                                    <p style="color: #666; margin-bottom: 5px;">Mã sản phẩm# <?php echo $item['product_id']; ?></p>
                                    <div class="button-container">
                                        <div class="controls-group">
                                            <div class="quantity-controls">
                                                <button type="button" class="quantity-btn minus" onclick="updateQuantity(this, -1)">−</button>
                                                <input type="number" name="quantity" value="<?php echo $item['soluong']; ?>"
                                                    min="1" max="10" class="quantity-input"
                                                    data-product-id="<?php echo $item['product_id']; ?>"
                                                    data-price="<?php echo $item['giaban']; ?>"
                                                    readonly>
                                                <button type="button" class="quantity-btn plus" onclick="updateQuantity(this, 1)">+</button>
                                            </div>

                                            <form method="POST" action="giohang.php" style="display: inline;">
                                                <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                                <input type="hidden" name="quantity" class="quantity-hidden" value="<?php echo $item['soluong']; ?>">
                                                <button type="submit" name="update_quantity" class="update-btn" onclick="return prepareUpdate(this)">
                                                    <i class="fa-light fa-check"></i>Cập nhật
                                                </button>
                                            </form>

                                            <a href="giohang.php?remove=<?php echo $item['product_id']; ?>"
                                                class="delete-btn"
                                                onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                                <i class="fa-light fa-trash"></i>Xóa
                                            </a>
                                        </div>

                                        <div class="product-price">
                                            <?php
                                            $subtotal = $item['giaban'] * $item['soluong'];
                                            echo number_format($subtotal, 0, ',', '.') . 'đ';
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        endwhile;
                    endif;
                    ?>
                </div>

                <!-- Phần tổng kết đơn hàng -->
                <div style="width: 450px;">
                    <div style="background: #f5f5f5; padding: 20px; border-radius: 8px;">
                        <h3 style="margin-bottom: 20px;">TÓM TẮT HÓA ĐƠN</h3>


                        <!-- Phần tổng tiền -->
                        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span>Tổng cộng (<?php echo $result->num_rows; ?> Sản phẩm)</span>
                                <span><?php echo number_format($total, 0, ',', '.'); ?>đ</span>
                            </div>

                            <a href="thanhtoan.php" class="mua" style="width: 100%; margin-top: 20px; display: block; text-align: center; text-decoration: none; color: white;">Thanh toán</a>

                            <div style="margin-top: 20px; text-align: center;">
                                <a href="index.php" style="color: #139b3a; margin-right: 15px;">Tiếp tục mua hàng</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>



    <?php include 'footer.php' ?>
    <!-- <script src="js/hoadon.js"></script> -->
    <script src="js/giohang.js"></script>
    <script src="js/phantrang.js"></script>
    <script src="js/ssbutton.js"></script>
    <script src="js/filter.js"></script>

    <script>
        function updateQuantity(btn, change) {
            const input = btn.parentElement.querySelector('.quantity-input');
            const currentValue = parseInt(input.value);
            let newValue = currentValue + change;

            // Giới hạn giá trị từ 1 đến 10
            newValue = Math.max(1, Math.min(10, newValue));

            // Cập nhật giá trị cho input
            input.value = newValue;

            // Cập nhật giá trị cho hidden input trong form
            const form = btn.closest('.button-container').querySelector('form');
            const hiddenInput = form.querySelector('.quantity-hidden');
            hiddenInput.value = newValue;

            // Cập nhật hiển thị giá tiền
            const unitPrice = parseInt(input.getAttribute('data-price'));
            const newTotal = unitPrice * newValue;
            const priceElement = btn.closest('.button-container').querySelector('.product-price');
            priceElement.textContent = new Intl.NumberFormat('vi-VN').format(newTotal) + 'đ';
        }

        function prepareUpdate(btn) {
            const form = btn.closest('form');
            const quantityInput = form.previousElementSibling.querySelector('.quantity-input');
            const hiddenInput = form.querySelector('.quantity-hidden');
            hiddenInput.value = quantityInput.value;
            return true;
        }

        // Vô hiệu hóa việc nhập trực tiếp vào input số lượng
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('keydown', (e) => {
                e.preventDefault();
                return false;
            });
        });

        // Hiển thị thông báo nếu có
        <?php if (isset($_SESSION['success'])): ?>
            alert("<?php echo $_SESSION['success']; ?>");
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            alert("<?php echo $_SESSION['error']; ?>");
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </script>

</body>
<?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<script>if(window.history.replaceState){window.history.replaceState(null, null, window.location.href);}</script>";
}
?>
<?php $conn->close(); ?>

</html>