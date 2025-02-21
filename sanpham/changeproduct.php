<?php
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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = intval($_POST['product-id']);
    $tenXe = $conn->real_escape_string($_POST['ten-mon']);
    $dongXe = $conn->real_escape_string($_POST['category']);
    $mauXe = $conn->real_escape_string($_POST['mau-xe']);
    $giaBan = floatval($_POST['gia-ban']);
    $thongTinSP = $conn->real_escape_string($_POST['thong-tin-sp']);
    $thongSoKT = $conn->real_escape_string($_POST['thong-so-ky-thuat']);

    // Ask for confirmation before updating
    echo "<script>
        if (confirm('Bạn có chắc chắn muốn chỉnh sửa sản phẩm này?')) {
            document.forms[0].submit();
        } else {
            alert('Hủy chỉnh sửa sản phẩm.');
        }
    </script>";

    // Handle image upload
    $targetFile = "";
    if (!empty($_FILES['up-hinh-anh']['name'])) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES['up-hinh-anh']['name']);

        // Upload the file
        if (!move_uploaded_file($_FILES['up-hinh-anh']['tmp_name'], $targetFile)) {
            echo "Lỗi khi upload hình ảnh.";
            exit;
        }
    } else {
        // If no new image, retain the old image
        $sqlGetImage = "SELECT hinhanh FROM products WHERE id = $productId";
        $result = $conn->query($sqlGetImage);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $targetFile = $row['hinhanh'];
        } else {
            echo "Không tìm thấy sản phẩm.";
            exit;
        }
    }

    // Update product information
    $sqlUpdate = "UPDATE products SET 
                    tensp = '$tenXe', 
                    dongsp = '$dongXe', 
                    mauxe = '$mauXe', 
                    giaban = $giaBan, 
                    thongtinsp = '$thongTinSP', 
                    thongsokt = '$thongSoKT', 
                    hinhanh = '$targetFile' 
                  WHERE id = $productId";

    if ($conn->query($sqlUpdate) === TRUE) {
        echo "<script>alert('Cập nhật sản phẩm thành công!');
        window.location.href = 'sanpham.php';</script>";
        // Redirect to product list page (if necessary)
        //header("Location: sanpham.php");
    } else {
        echo "Lỗi cập nhật sản phẩm: " . $conn->error;
    }
}

// Fetch product data for editing
$product = null;
$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($productId > 0) {
    $sql = "SELECT * FROM products WHERE id = $productId";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "<script>
        alert('Không thể thêm sản phẩm! Lỗi: " . $conn->error . "');
        window.location.href = 'newproduct.php'; // Redirect to the form page
      </script>";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='img/logo.png' rel='icon' type='image/x-icon' />
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link href="../assets/font/font-awesome-pro-v6-6.2.0/css/all.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../assets/css/admin-responsive.css">
    <title>Chỉnh sửa sản phẩm</title>
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
                <a href="" class="channel-logo"><img src="../img/logo.png" alt="Channel Logo"></a>
                <div class="hidden-sidebar your-channel"><img src="" style="height: 30px;" alt="">
                </div>
            </div>
            <div class="middle-sidebar">
                <ul class="sidebar-list">
                    <li class="sidebar-list-item tab-content">
                        <a href="/admin.php" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-light fa-house"></i></div>
                            <div class="hidden-sidebar">Trang tổng quan</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item tab-content active">
                        <a href="/sanpham/sanpham.php" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-thin fa-motorcycle"></i></div>
                            <div class="hidden-sidebar">Sản phẩm</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item tab-content">
                        <a href="/khachhang/khachhang.php" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-light fa-users"></i></div>
                            <div class="hidden-sidebar">Khách hàng</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item tab-content">
                        <a href="/donhang/donhang.php" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-light fa-basket-shopping"></i></div>
                            <div class="hidden-sidebar">Đơn hàng</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item tab-content">
                        <a href="/thongkesp/thongke.php" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-light fa-chart-simple"></i></div>
                            <div class="hidden-sidebar">Thống kê sản phẩm</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item tab-content">
                        <a href="/thongkekh/thongkekh.php" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-light fa-chart-simple"></i></div>
                            <div class="hidden-sidebar">Thống kê khách hàng</div>
                        </a>
                    </li>

                    <div class="spacer" style="height:50px;width:1px"></div>
                    <li class="sidebar-list-item user-logout" style="border-top: 2px solid rgba(0,0,0,0.12);">
                        <a href="index.php" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-thin fa-circle-chevron-left"></i></div>
                            <div class="hidden-sidebar">Trang chủ</div>
                        </a>
                    </li>

                    <li class="sidebar-list-item user-logout">
                        <a href="#" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-light fa-circle-user"></i></div>
                            <div class="hidden-sidebar" id="name-acc">Admin</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item user-logout">
                        <a href="#" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-light fa-arrow-right-from-bracket"></i></div>
                            <div class="hidden-sidebar" id="logoutacc">Đăng xuất</div>
                        </a>
                    </li>
                </ul>
            </div>

        </aside>
        <main class="content">
            <!-- Product  -->
            <div class="section product-all">
                <div class="admin-control">
                    <div class="admin-control-left">
                        <select name="the-loai" id="the-loai">
                            <option>Tất cả</option>
                            <option>Dòng Ninja</option>
                            <option>Dòng Z</option>
                            <option>Dòng KLX </option>
                        </select>
                    </div>
                    <div class="admin-control-center">
                        <form action="" class="form-search">
                            <span class="search-btn"><i class="fa-light fa-magnifying-glass"></i></span>
                            <input id="form-search-product" type="text" class="form-search-input"
                                placeholder="Tìm kiếm tên xe...">
                        </form>
                    </div>
                    <div class="admin-control-right">
                        <button class="btn-control-large" id="btn-cancel-product"><i
                                class="fa-light fa-rotate-right"></i> Làm mới</button>
                        <a href="newproduct.php"><button class="btn-control-large" id="btn-add-product"><i class="fa-light fa-plus"></i> Thêm xe
                                mới</button></a>
                    </div>
                </div>
                <div id="show-product"></div>
                <?php
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

                // Fetch product data
                $sql = "SELECT id, tensp, dongsp, giaban, thongtinsp, hinhanh FROM products";
                $result = $conn->query($sql);

                // Check if there are products to display
                if ($result->num_rows > 0) {
                    // Loop through and display each product
                    while ($row = $result->fetch_assoc()) {
                        echo '
        <div class="list">
            <div class="list-left">
                <img src="' . $row["hinhanh"] . '" alt="' . '">
                <div class="list-info">
                    <h4>' . $row["tensp"] . '</h4>
                    <p class="list-note">' . $row["thongtinsp"] . '</p>
                    <span class="list-category"> ' . $row["dongsp"] . '</span>
                </div>
            </div>
            <div class="list-right">
                <div class="list-price">
                    <span class="list-current-price">' . number_format($row["giaban"], 0, ',', '.') . 'đ</span>
                </div>
                <div class="list-control">
                    <div class="list-tool">
                        <a href="changeproduct.php?id=' . $row["id"] . '">
                            <button class="btn-edit"><i class="fa-light fa-pen-to-square"></i></button>
                        </a>
                        <button class="btn-delete" onclick=""><i class="fa-regular fa-trash"></i></button>
                    </div>
                </div>
            </div>
        </div>
        ';
                    }
                } else {
                    echo "Không có sản phẩm nào!";
                }

                // Close connection
                $conn->close();
                ?>
            </div>
        </main>
    </div>

    

    <div class="modal add-product open ">
        <div class="modal-container">
            <h3 class="modal-container-title add-product-e">CHỈNH SỬa SẢN PHẨM</h3>
            <a href="sanpham.php"><button class="modal-close product-form"><i class="fa-regular fa-xmark"></i></button></a>
            <div class="modal-content">
                <form action="" method="POST" enctype="multipart/form-data" class="add-product-form">
                    <input type="hidden" name="product-id" value="<?php echo $product['id']; ?>">
                    <div class="modal-content-left">
                        <img src="<?php echo $product['hinhanh']; ?>" alt="<?php echo $product['tensp']; ?>" class="upload-image-preview">
                        <div class="form-group file">
                            <label for="up-hinh-anh" class="form-label-file"><i class="fa-regular fa-cloud-arrow-up"></i>Chọn hình ảnh</label>
                            <input accept="image/jpeg, image/png, image/jpg" id="up-hinh-anh" name="up-hinh-anh" type="file" class="form-control">
                        </div>
                    </div>
                    <div class="modal-content-right">
                        <div class="form-group">
                            <label for="ten-mon" class="form-label">Tên xe</label>
                            <input id="ten-mon" name="ten-mon" type="text" placeholder="Nhập tên xe" value="<?php echo $product['tensp']; ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="category" class="form-label">Chọn dòng</label>
                            <select name="category" id="chon-mon">
                                <option value="Dòng Ninja" <?php echo ($product['dongsp'] == 'Dòng Ninja') ? 'selected' : ''; ?>>Dòng Ninja</option>
                                <option value="Dòng Z" <?php echo ($product['dongsp'] == 'Dòng Z') ? 'selected' : ''; ?>>Dòng Z</option>
                                <option value="Dòng KLX" <?php echo ($product['dongsp'] == 'Dòng KLX') ? 'selected' : ''; ?>>Dòng KLX</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="mau-xe" class="form-label">Mẫu xe</label>
                            <input id="mau-xe" name="mau-xe" type="text" placeholder="Nhập mẫu xe" value="<?php echo $product['mauxe']; ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="gia-ban" class="form-label">Giá bán</label>
                            <input id="gia-ban" name="gia-ban" type="text" placeholder="Nhập giá bán" value="<?php echo $product['giaban']; ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="thong-tin-sp" class="form-label">Thông tin sản phẩm</label>
                            <textarea class="product-desc" name="thong-tin-sp" id="thong-tin-sp" placeholder="Nhập thông tin sản phẩm" style="width:100%;height:100%;"><?php echo $product['thongtinsp']; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="thong-so-ky-thuat" class="form-label">Thông số kỹ thuật</label>
                            <textarea class="product-desc" name="thong-so-ky-thuat" id="thong-so-ky-thuat" placeholder="Nhập thông số kỹ thuật" style="width:100%;height:185px;"><?php echo $product['thongsokt']; ?></textarea>
                        </div>
                        <button class="form-submit btn-add-product-form add-product-e" id="change-product-button">
                            <i class="fa-regular fa-plus"></i>
                            <span>CHỈNH SỬA XE</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="../assets/js/admin.js"></script>

</body>

</html>