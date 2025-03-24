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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<script>if (!confirm('Bạn có muốn thêm sản phẩm này?')) { window.location.href = 'sanpham.php'; }</script>";
    $tenXe = $conn->real_escape_string(htmlspecialchars($_POST['ten-mon'], ENT_QUOTES, 'UTF-8'));
    $dongXe = $conn->real_escape_string(htmlspecialchars($_POST['category'], ENT_QUOTES, 'UTF-8'));
    $giaBan = floatval($_POST['gia-ban']);
    $thongTinSP = $conn->real_escape_string(htmlspecialchars($_POST['thong-tin-sp'], ENT_QUOTES, 'UTF-8'));
    $thongSoKT = $conn->real_escape_string(htmlspecialchars($_POST['thong-so-ky-thuat'], ENT_QUOTES, 'UTF-8'));


    // Handle image uploads
    $uploadDir = "uploads/";
    $mainImage = isset($_FILES['up-hinh-anh']['name']) && $_FILES['up-hinh-anh']['name'] !== '' ? $uploadDir . basename($_FILES['up-hinh-anh']['name']) : '';
    $image2 = isset($_FILES['up-hinh-anh2']['name']) && $_FILES['up-hinh-anh2']['name'] !== '' ? $uploadDir . basename($_FILES['up-hinh-anh2']['name']) : '';
    $image3 = isset($_FILES['up-hinh-anh3']['name']) && $_FILES['up-hinh-anh3']['name'] !== '' ? $uploadDir . basename($_FILES['up-hinh-anh3']['name']) : '';

    // Move uploaded files
    if ($mainImage) move_uploaded_file($_FILES['up-hinh-anh']['tmp_name'], $mainImage);
    if ($image2) move_uploaded_file($_FILES['up-hinh-anh2']['tmp_name'], $image2);
    if ($image3) move_uploaded_file($_FILES['up-hinh-anh3']['tmp_name'], $image3);

    // Insert data into the database
    $sql = "INSERT INTO products (tensp, dongsp, giaban, thongtinsp, thongsokt, hinhanh, hinhanh2, hinhanh3) 
            VALUES ('$tenXe', '$dongXe', '$giaBan', '$thongTinSP', '$thongSoKT', '$mainImage', '$image2', '$image3')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Thêm thông tin sản phẩm thành công!');
                window.location.href = 'sanpham.php';
              </script>";
    } else {
        echo "<script>
                alert('Không thể thêm sản phẩm! Lỗi: " . $conn->error . "');
                window.location.href = 'newproduct.php';
              </script>";
    }

    // Close connection
    $conn->close();
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
                <a href="" class="channel-logo"><img src="../image/logo.png" alt="Channel Logo"></a>
                <div class="hidden-sidebar your-channel"><img src="" style="height: 30px;" alt="">
                </div>
            </div>
            <div class="middle-sidebar">
                <ul class="sidebar-list">
                    <li class="sidebar-list-item tab-content">
                        <a href="../admin.php" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-light fa-house"></i></div>
                            <div class="hidden-sidebar">Trang tổng quan</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item tab-content active">
                        <a href="../sanpham/sanpham.php" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-thin fa-motorcycle"></i></div>
                            <div class="hidden-sidebar">Sản phẩm</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item tab-content">
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

    <div class="modal add-product open">
        <div class="modal-container">
            <h3 class="modal-container-title add-product-e">THÊM MỚI SẢN PHẨM</h3>
            <a href="sanpham.php"><button class="modal-close product-form"><i class="fa-regular fa-xmark"></i></button></a>
            <div class="modal-content">
                <form action="" method="POST" enctype="multipart/form-data" class="add-product-form" onsubmit="return confirmSubmit()">
                    <div class="modal-content-left">
                        <img src="../image/blank-image.png" alt="" class="upload-image-preview" id="preview-image-1">
                        <div class="form-group file">
                            <label for="up-hinh-anh" class="form-label-file"><i class="fa-regular fa-cloud-arrow-up"></i>Chọn hình ảnh</label>
                            <input accept="image/jpeg, image/png, image/jpg" id="up-hinh-anh" name="up-hinh-anh" type="file" class="form-control" onchange="previewImage(this, 'preview-image-1')">
                        </div>

                        <img src="../image/blank-image.png" alt="" class="upload-image-preview" id="preview-image-2">
                        <div class="form-group file">
                            <label for="up-hinh-anh2" class="form-label-file"><i class="fa-regular fa-cloud-arrow-up"></i>Chọn hình ảnh</label>
                            <input accept="image/jpeg, image/png, image/jpg" id="up-hinh-anh2" name="up-hinh-anh2" type="file" class="form-control" onchange="previewImage(this, 'preview-image-2')">
                        </div>

                        <img src="../image/blank-image.png" alt="" class="upload-image-preview" id="preview-image-3">
                        <div class="form-group file">
                            <label for="up-hinh-anh3" class="form-label-file"><i class="fa-regular fa-cloud-arrow-up"></i>Chọn hình ảnh</label>
                            <input accept="image/jpeg, image/png, image/jpg" id="up-hinh-anh3" name="up-hinh-anh3" type="file" class="form-control" onchange="previewImage(this, 'preview-image-3')">
                        </div>

                    </div>
                    <div class="modal-content-right">
                        <div class="form-group">
                            <label for="ten-mon" class="form-label">Tên xe</label>
                            <input id="ten-mon" name="ten-mon" type="text" placeholder="Nhập tên xe" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="category" class="form-label">Chọn dòng</label>
                            <select name="category" id="chon-mon" required>
                                <option value="Dòng Ninja">Dòng Ninja</option>
                                <option value="Dòng Z">Dòng Z</option>
                                <option value="Dòng KLX">Dòng KLX</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="gia-ban" class="form-label">Giá bán</label>
                            <input id="gia-ban" name="gia-ban" type="text" placeholder="Nhập giá bán" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="thong-tin-sp" class="form-label">Thông tin sản phẩm</label>
                            <textarea name="thong-tin-sp" class="product-desc" id="thong-tin-sp" placeholder="Nhập thông tin sản phẩm" style="width:100%;height:100%;"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="thong-so-ky-thuat" class="form-label">Thông số kỹ thuật</label>
                            <textarea name="thong-so-ky-thuat" class="product-desc" id="thong-so-ky-thuat" placeholder="Nhập thông số kỹ thuật" style="width:100%;height:185px;"></textarea>
                        </div>
                        <button class="form-submit btn-add-product-form add-product-e" id="add-product-button">
                            <i class="fa-regular fa-plus"></i>
                            <span>THÊM XE</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function previewImage(input, previewId) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(previewId).src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
    <script src="../assets/js/admin.js"></script>

</body>

</html>