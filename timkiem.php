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

// Khởi tạo biến category
$selected_category = isset($_GET['category']) ? $_GET['category'] : '';

// Xây dựng câu truy vấn SQL dựa trên category được chọn
$sql = "SELECT id, tensp, giaban, hinhanh, dongsp FROM products WHERE status = 1";
if ($selected_category != '') {
    $sql .= " AND dongsp = '" . mysqli_real_escape_string($conn, $selected_category) . "'";
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

        .mua.in-cart {
            background-color: #dc3545 !important;
            /* Màu đỏ */
        }

        .cart-button {
            transition: all 0.3s ease;
        }

        .product-pag {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        .filter-controls {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-bottom: 30px;
        }

        .sort-controls {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .sort-btn {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 8px 16px;
            background: #f5f5f5;
            border: 1px solid #ddd;
            border-radius: 4px;
            color: #333;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .sort-btn:hover {
            background: #e9e9e9;
        }

        .sort-btn.active {
            background: #139b3a;
            color: white;
            border-color: #139b3a;
        }

        .reset-btn {
            background: #dc3545;
            color: white;
            border-color: #dc3545;
        }

        .reset-btn:hover {
            background: #c82333;
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
                        <input type="text" name="tukhoa" class="form-search-input" id="searchBox" placeholder="Tìm kiếm">
                            
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
                                    <a href="giohang.php" style="text-decoration: none; color: inherit;">
                                        <span class="ravao">
                                            <i class="fa-light fa-basket-shopping"></i> Giỏ hàng
                                        </span>
                                    </a>
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
        <div class="container" id="trangchu">
            <div class="home-service" id="home-service">
            </div>
            <?php
            // Nhận dữ liệu từ GET
            $tukhoa = isset($_GET['tukhoa']) ? trim($_GET['tukhoa']) : '';
            $brandchecked = isset($_GET['brands']) ? $_GET['brands'] : [];
            $min_price = isset($_GET['min_price']) && is_numeric($_GET['min_price']) ? (int)$_GET['min_price'] : 0;
            $max_price = isset($_GET['max_price']) && is_numeric($_GET['max_price']) ? (int)$_GET['max_price'] : PHP_INT_MAX;
            $sort = isset($_GET['sort']) ? $_GET['sort'] : '';

            // Bắt đầu xây dựng câu truy vấn
            $conditions = [];
            if (!empty($tukhoa)) {
                $safe_keyword = $conn->real_escape_string($tukhoa);
                $conditions[] = "(tensp LIKE '%$safe_keyword%' OR dongsp LIKE '%$safe_keyword%')";
            }

            if (!empty($brandchecked)) {
                $escaped_brands = array_map(function ($brand) use ($conn) {
                    return "'" . $conn->real_escape_string($brand) . "'";
                }, $brandchecked);
                $brand_list = implode(",", $escaped_brands);
                $conditions[] = "dongsp IN ($brand_list)";
            }

            // Điều kiện lọc theo giá
            $conditions[] = "giaban BETWEEN $min_price AND $max_price";

            // Gộp các điều kiện lại
            $where_sql = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

            // Thêm sắp xếp
            $order_sql = "";
            if ($sort === 'price_asc') {
                $order_sql = "ORDER BY giaban ASC";
            } elseif ($sort === 'price_desc') {
                $order_sql = "ORDER BY giaban DESC";
            } else {
                $order_sql = "ORDER BY tensp";
            }

            // Câu truy vấn cuối cùng
            $sql = "SELECT * FROM products $where_sql $order_sql";

            // Thực thi truy vấn
            $result = $conn->query($sql);
            ?>
            <!-- Hiển thị tiêu đề kết quả -->
            <div class="home-title-block" id="home-title">
                <h2 class="home-title">CÓ <span><?= $result ? $result->num_rows : 0 ?></span> KẾT QUẢ TÌM KIẾM</h2>
                <div class="border-line"></div>
            </div>

            <div class="filter-controls">
                <form action="" method="GET" class="form-search">
                    <button type="submit" class="search-btn" style="height:80px;">
                        <i class="fa-light fa-magnifying-glass"></i>
                    </button>
                    <input type="text" name="tukhoa" class="form-search-input" id="searchBox" style="height:80px; width:100%;font-size:23px;"
                        value="<?php echo isset($_GET['tukhoa']) ? htmlspecialchars($_GET['tukhoa']) : ''; ?>"
                        placeholder="TÌM KIẾM">
                </form>

                <div class="sort-controls">
                    <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => 'price_asc'])); ?>" 
                       class="sort-btn <?php echo $sort === 'price_asc' ? 'active' : ''; ?>">
                        <i class="fa-regular fa-arrow-up-short-wide"></i> Giá tăng dần
                    </a>
                    <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => 'price_desc'])); ?>" 
                       class="sort-btn <?php echo $sort === 'price_desc' ? 'active' : ''; ?>">
                        <i class="fa-regular fa-arrow-down-wide-short"></i> Giá giảm dần
                    </a>
                    <a href="timkiem.php" class="sort-btn reset-btn">
                        <i class="fa-light fa-arrow-rotate-right"></i> Làm mới
                    </a>
                </div>
            </div>

            <div class="page-nav">
                <ul class="page-nav-list">
                    <div class="filter-row">
                        <div class="filter">
                            <div class="filter-box">
                                <div class="container">
                                    <form action="" method="GET" id="filterForm">
                                        <h3 class="advanced-title">Bộ lọc tìm kiếm</h3>
                                        <div class="advanced-search-container">
                                            <!--Lọc theo danh mục sản phẩm-->
                                            <legend class="advanced-search-header">Theo danh mục</legend>
                                            <div class="advanced-search-category">
                                                <?php
                                                // Truy vấn danh mục sản phẩm
                                                $brand_query = "SELECT DISTINCT dongsp FROM products";
                                                $brand_query_run = mysqli_query($conn, $brand_query);

                                                // Lưu giá trị đã chọn (nếu có)
                                                $checked = [];
                                                if (isset($_GET['brands'])) {
                                                    $checked = $_GET['brands'];
                                                }

                                                if (mysqli_num_rows($brand_query_run) > 0) {
                                                    while ($brandlist = mysqli_fetch_assoc($brand_query_run)) {
                                                        $brandName = $brandlist['dongsp'];
                                                        $isChecked = in_array($brandName, $checked) ? 'checked' : '';
                                                ?>
                                                        <div>
                                                            <input type="checkbox" name="brands[]" value="<?= htmlspecialchars($brandName) ?>" <?= $isChecked ?> />
                                                            <?= htmlspecialchars($brandName) ?>
                                                        </div>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="advanced-search-container">
                                            <!--Lọc theo khoảng giá-->
                                            <legend class="advanced-search-header">Khoảng giá</legend>
                                            <div class="advanced-search-price">
                                                <input type="number" placeholder="₫ TỪ" name="min_price" id="min-price" 
                                                    value="<?php echo isset($_GET['min_price']) ? $_GET['min_price'] : ''; ?>"
                                                    min="0" max="10000000000">
                                                <input type="number" placeholder="₫ ĐẾN" name="max_price" id="max-price" 
                                                    value="<?php echo isset($_GET['max_price']) ? $_GET['max_price'] : ''; ?>"
                                                    min="0" max="10000000000">
                                            </div>
                                        </div>
                                        <!-- Giữ lại các tham số GET khác -->
                                        <?php if (isset($_GET['tukhoa'])): ?>
                                            <input type="hidden" name="tukhoa" value="<?= htmlspecialchars($_GET['tukhoa']) ?>">
                                        <?php endif; ?>
                                        <?php if (isset($_GET['sort'])): ?>
                                            <input type="hidden" name="sort" value="<?= htmlspecialchars($_GET['sort']) ?>">
                                        <?php endif; ?>
                                        <div class="filter-actions">
                                            <button type="button" class="filter-btn" onclick="applyFilters()">
                                                <i class="fa-light fa-filter"></i> Áp dụng bộ lọc
                                            </button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="product-pag">
                            <div class="grid-container" id="product-list">

                                <?php
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
                                        $in_cart = false;
                                        if (isset($_SESSION['customer_id'])) {
                                            $check_cart = $conn->prepare("SELECT id FROM giohang WHERE customer_id = ? AND product_id = ?");
                                            $check_cart->bind_param("ii", $_SESSION['customer_id'], $row["id"]);
                                            $check_cart->execute();
                                            $check_result = $check_cart->get_result();
                                            $in_cart = $check_result->num_rows > 0;
                                            $check_cart->close();
                                        }

                                        echo '
                                <div class="card page-1">
                                        <a href="thongtinsp.php?id=' . $row["id"] . '">
                                            <img src="sanpham/' . $row["hinhanh"] . '" alt="' . $row["tensp"] . '">
                                        </a>
                                        <h3>' . $row["tensp"] . '</h3>
                                        <div class="greenSpacer"></div>
                                        <div class="price">' . number_format($row["giaban"], 0, ',', '.') . 'đ</div>

                                    <div class="display" style="display:flex;">
                                        <button type="button" 
                                                class="mua cart-button ' . ($in_cart ? 'in-cart' : '') . '" 
                                                data-product-id="' . $row["id"] . '"
                                                data-product-price="' . $row["giaban"] . '"
                                                data-product-img="' . $row["hinhanh"] . '">
                                            ' . ($in_cart ? '- Xóa khỏi giỏ hàng' : '+ Thêm vào giỏ hàng') . '
                                        </button>
                                        <a href="thongtinsp.php?id=' . $row['id'] . '" class="mua" style="text-decoration: none; text-align: center; display: inline-block;color:white;">Xem chi tiết</a>
                                        </div>
                                </div>';
                                    }
                                } else {
                                    echo '<p class="no-products">Không có sản phẩm nào trong danh mục này</p>';
                                }
                                ?>
                              
                            </div>
                            <div class="pagination">
                                    <button id="prevBtn" onclick="changePage(-1)" disabled>&#10094;</button>
                                    <div id="pageNumbers" class="page-numbers"></div>
                                    <button id="nextBtn" onclick="changePage(1)">&#10095;</button>
                                </div>
                        </div>
                    </div>

                </ul>
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
        const isLoggedIn = <?php echo isset($_SESSION['customer_id']) ? 'true' : 'false'; ?>;
    </script>
    <script>
    function applyFilters() {
        const form = document.getElementById('filterForm');
        const formData = new FormData(form);
        const params = new URLSearchParams();
        
        // Thêm các tham số từ form
        for (const [key, value] of formData.entries()) {
            if (value) {
                if (key === 'brands[]') {
                    params.append('brands[]', value);
                } else {
                    params.append(key, value);
                }
            }
        }
        
        // Thêm các tham số GET hiện tại
        const currentParams = new URLSearchParams(window.location.search);
        for (const [key, value] of currentParams.entries()) {
            if (!formData.has(key)) {
                params.append(key, value);
            }
        }
        
        // Chuyển hướng với các tham số đã lọc
        window.location.href = 'timkiem.php?' + params.toString();
    }

    // Thêm sự kiện click cho nút áp dụng bộ lọc
    document.querySelector('.filter-btn').addEventListener('click', applyFilters);
    </script>
</body>
<?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<script>if(window.history.replaceState){window.history.replaceState(null, null, window.location.href);}</script>";
}
?>
<?php $conn->close(); ?>

</html>