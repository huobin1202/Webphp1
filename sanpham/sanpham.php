<?php
session_start();
include("../database.php");
include("../toast.php");

// Lấy thông tin sản phẩm cần sửa nếu có
$edit_product = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_product = $result->fetch_assoc();
}

// Thêm xử lý tìm kiếm và lọc vào đầu file, sau phần kết nối database
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? $_GET['category'] : 'Tất cả';

// Xử lý thêm sản phẩm mới
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        try {
            // Lấy dữ liệu từ form
            $tenXe = trim($_POST['ten-mon']);
            $dongXe = trim($_POST['category']);
            $giaBan = floatval($_POST['gia-ban']);
            $thongTinSP = trim($_POST['thong-tin-sp']);
            $thongSoKT = trim($_POST['thong-so-ky-thuat']);
            $status = 1;

            // Xử lý upload hình ảnh
            $uploadDir = "uploads/";
            $mainImage = '';
            $image2 = '';
            $image3 = '';

            // Upload hình ảnh chính
            if (!empty($_FILES['up-hinh-anh']['name'])) {
                $mainImage = $uploadDir . basename($_FILES['up-hinh-anh']['name']);
                move_uploaded_file($_FILES['up-hinh-anh']['tmp_name'], $mainImage);
            }

            // Upload hình ảnh 2
            if (!empty($_FILES['up-hinh-anh2']['name'])) {
                $image2 = $uploadDir . basename($_FILES['up-hinh-anh2']['name']);
                move_uploaded_file($_FILES['up-hinh-anh2']['tmp_name'], $image2);
            }

            // Upload hình ảnh 3
            if (!empty($_FILES['up-hinh-anh3']['name'])) {
                $image3 = $uploadDir . basename($_FILES['up-hinh-anh3']['name']);
                move_uploaded_file($_FILES['up-hinh-anh3']['tmp_name'], $image3);
            }

            // Debug: In ra các giá trị để kiểm tra
            /*
            echo "<pre>";
            var_dump([
                'tenXe' => $tenXe,
                'dongXe' => $dongXe,
                'giaBan' => $giaBan,
                'thongTinSP' => $thongTinSP,
                'thongSoKT' => $thongSoKT,
                'mainImage' => $mainImage,
                'image2' => $image2,
                'image3' => $image3,
                'status' => $status
            ]);
            echo "</pre>";
            die();
            */

            // Chuẩn bị câu lệnh SQL
            $sql = "INSERT INTO products (tensp, dongsp, giaban, thongtinsp, thongsokt, hinhanh, hinhanh2, hinhanh3, status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            if (!($stmt = $conn->prepare($sql))) {
                throw new Exception("Lỗi prepare statement: " . $conn->error);
            }

            // Bind các tham số - đảm bảo số lượng type indicators khớp với số tham số
            $types = "ssdsssssi"; // 9 ký tự cho 9 tham số
            if (!$stmt->bind_param(
                $types,
                $tenXe,      // string (s)
                $dongXe,     // string (s)
                $giaBan,     // double (d)
                $thongTinSP, // string (s)
                $thongSoKT,  // string (s)
                $mainImage,  // string (s)
                $image2,     // string (s)
                $image3,     // string (s)
                $status      // integer (i)
            )) {
                throw new Exception("Lỗi bind_param: " . $stmt->error);
            }

            // Thực thi câu lệnh
            if (!$stmt->execute()) {
                throw new Exception("Lỗi execute: " . $stmt->error);
            }

            $_SESSION['success'] = "Thêm sản phẩm thành công!";
            $stmt->close();
        } catch (Exception $e) {
            $_SESSION['error'] = "Lỗi: " . $e->getMessage();
        }

        header("Location: sanpham.php");
        exit();
    } elseif ($_POST['action'] === 'edit') {
        try {
            $productId = intval($_POST['product-id']);
            $tenXe = trim($_POST['ten-mon']);
            $dongXe = trim($_POST['category']);
            $giaBan = floatval($_POST['gia-ban']);
            $thongTinSP = trim($_POST['thong-tin-sp']);
            $thongSoKT = trim($_POST['thong-so-ky-thuat']);

            // Lấy thông tin hình ảnh hiện tại
            $stmt = $conn->prepare("SELECT hinhanh, hinhanh2, hinhanh3 FROM products WHERE id = ?");
            $stmt->bind_param("i", $productId);
            $stmt->execute();
            $result = $stmt->get_result();
            $currentImages = $result->fetch_assoc();
            $stmt->close();

            // Xử lý upload hình ảnh mới
            $uploadDir = "uploads/";
            $mainImage = !empty($_FILES['up-hinh-anh']['name']) ?
                $uploadDir . basename($_FILES['up-hinh-anh']['name']) :
                $currentImages['hinhanh'];
            $image2 = !empty($_FILES['up-hinh-anh2']['name']) ?
                $uploadDir . basename($_FILES['up-hinh-anh2']['name']) :
                $currentImages['hinhanh2'];
            $image3 = !empty($_FILES['up-hinh-anh3']['name']) ?
                $uploadDir . basename($_FILES['up-hinh-anh3']['name']) :
                $currentImages['hinhanh3'];

            // Upload các file mới nếu có
            if (!empty($_FILES['up-hinh-anh']['name'])) {
                move_uploaded_file($_FILES['up-hinh-anh']['tmp_name'], $mainImage);
            }
            if (!empty($_FILES['up-hinh-anh2']['name'])) {
                move_uploaded_file($_FILES['up-hinh-anh2']['tmp_name'], $image2);
            }
            if (!empty($_FILES['up-hinh-anh3']['name'])) {
                move_uploaded_file($_FILES['up-hinh-anh3']['tmp_name'], $image3);
            }

            // Cập nhật thông tin sản phẩm
            $sql = "UPDATE products SET 
                    tensp = ?, 
                    dongsp = ?, 
                    giaban = ?, 
                    thongtinsp = ?, 
                    thongsokt = ?, 
                    hinhanh = ?, 
                    hinhanh2 = ?, 
                    hinhanh3 = ? 
                    WHERE id = ?";

            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Lỗi prepare statement: " . $conn->error);
            }

            $stmt->bind_param(
                "ssdsssssi",
                $tenXe,      // string (s)
                $dongXe,     // string (s)
                $giaBan,     // double (d)
                $thongTinSP, // string (s)
                $thongSoKT,  // string (s)
                $mainImage,  // string (s)
                $image2,     // string (s)
                $image3,     // string (s)
                $productId   // integer (i)
            );

            if (!$stmt->execute()) {
                throw new Exception("Lỗi khi cập nhật sản phẩm: " . $stmt->error);
            }

            $_SESSION['success'] = "Cập nhật sản phẩm thành công!";
            $stmt->close();
        } catch (Exception $e) {
            $_SESSION['error'] = "Lỗi: " . $e->getMessage();
        }

        header("Location: sanpham.php");
        exit();
    }
}

// Thêm xử lý cho các action
if (isset($_GET['action'])) {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    switch ($_GET['action']) {
        case 'delete':
            // Kiểm tra xem sản phẩm đã được bán ra chưa
            $check_sold = $conn->prepare("SELECT COUNT(*) as count FROM order_details WHERE product_id = ?");
            $check_sold->bind_param("i", $id);
            $check_sold->execute();
            $result = $check_sold->get_result();
            $row = $result->fetch_assoc();

            if ($row['count'] > 0) {
                $_SESSION['error'] = "Không thể xóa sản phẩm đã được bán!";
            } else {
                $sql = "DELETE FROM products WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id);

                if ($stmt->execute()) {
                    $_SESSION['success'] = "Sản phẩm đã được xóa thành công!";
                } else {
                    $_SESSION['error'] = "Không thể xóa sản phẩm!";
                }
            }
            break;

        case 'toggle':
            // Lấy trạng thái hiện tại của sản phẩm
            $stmt = $conn->prepare("SELECT status FROM products WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();

            if ($product) {
                $new_status = $product['status'] == 1 ? 0 : 1;
                $update_stmt = $conn->prepare("UPDATE products SET status = ? WHERE id = ?");
                $update_stmt->bind_param("ii", $new_status, $id);

                if ($update_stmt->execute()) {
                    $_SESSION['success'] = $new_status == 1 ? "Sản phẩm đã được hiển thị!" : "Sản phẩm đã được ẩn!";
                } else {
                    $_SESSION['error'] = "Không thể cập nhật trạng thái sản phẩm!";
                }
            }
            break;
    }
    header("Location: sanpham.php");
    exit();
}

// Xây dựng câu truy vấn SQL với điều kiện tìm kiếm
$sql = "SELECT * FROM products WHERE 1=1";
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND (tensp LIKE '%$search%')";
}
if ($category !== 'Tất cả') {
    $category = $conn->real_escape_string($category);
    $sql .= " AND dongsp = '$category'";
}
$sql .= " ORDER BY id DESC";

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
                        <a class="sidebar-link" style="cursor:pointer">
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
                        <form method="GET" action="" style="display: inline;">
                            <select name="category" onchange="this.form.submit()">
                                <option value="Tất cả" <?php echo $category === 'Tất cả' ? 'selected' : ''; ?>>Tất cả</option>
                                <option value="Dòng Ninja" <?php echo $category === 'Dòng Ninja' ? 'selected' : ''; ?>>Dòng Ninja</option>
                                <option value="Dòng Z" <?php echo $category === 'Dòng Z' ? 'selected' : ''; ?>>Dòng Z</option>
                                <option value="Dòng KLX" <?php echo $category === 'Dòng KLX' ? 'selected' : ''; ?>>Dòng KLX</option>
                            </select>
                        </form>
                    </div>
                    <div class="admin-control-center">
                        <form action="" method="GET" class="form-search">
                            <span class="search-btn"><i class="fa-light fa-magnifying-glass"></i></span>
                            <input type="text" name="search" class="form-search-input"
                                placeholder="Tìm kiếm tên xe..."
                                value="<?php echo htmlspecialchars($search); ?>">
                            <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
                        </form>
                    </div>
                    <div class="admin-control-right">
                        <a href="sanpham.php" class="btn-control-large">
                            <i class="fa-light fa-rotate-right"></i> Làm mới
                        </a>
                        <a href="?add=1" class="btn-control-large">
                            <i class="fa-light fa-plus"></i> Thêm xe mới
                        </a>
                    </div>
                </div>
                <div id="show-product"></div>
                <?php
                // Fetch product data
                $result = $conn->query($sql);

                // Check if there are products to display
                if ($result->num_rows > 0) {
                    // Loop through and display each product
                    while ($row = $result->fetch_assoc()) {
                        // Kiểm tra xem sản phẩm đã được bán chưa
                        $check_sold = $conn->prepare("SELECT COUNT(*) as count FROM order_details WHERE product_id = ?");
                        $check_sold->bind_param("i", $row["id"]);
                        $check_sold->execute();
                        $sold_result = $check_sold->get_result();
                        $sold_row = $sold_result->fetch_assoc();

                        $delete_or_toggle_url = $sold_row['count'] > 0
                            ? "?action=toggle&id=" . $row["id"]
                            : "?action=delete&id=" . $row["id"];

                        $confirm_message = $sold_row['count'] > 0
                            ? ($row["status"] == 1 ? "Sản phẩm đã được bán, ẩn sản phẩm này?" : "Bạn có muốn hiển thị sản phẩm này?")
                            : "Bạn có muốn xóa sản phẩm này?";

                        echo '
                        <div class="list">
                            <div class="list-left">
                                <img src="' . $row["hinhanh"] . '" alt="' . $row["tensp"] . '">
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
                <div class="list-status" style="margin-top: 10px;">
                    <span class="status-' . ($row["status"] == 1 ? "complete" : "no-complete") . '">' .
                            ($row["status"] == 1 ? "Đang hiển thị" : "Đã ẩn") . '</span>
                </div>
                <div class="list-control">
                    <div class="list-tool">
                                        <a href="?edit=' . $row["id"] . '">
                            <button class="btn-edit"><i class="fa-light fa-pen-to-square"></i></button>
                        </a>
                                        <a href="' . $delete_or_toggle_url . '" onclick="return confirm(\'' . $confirm_message . '\');">
                                            <button class="btn-delete"><i class="fa-regular fa-trash"></i></button>
                                        </a>
                    </div>
                </div>
            </div>
                        </div>
                        ';
                    }
                } else {
                    echo "<div class='no-products'>Không có sản phẩm nào!</div>";
                }
                ?>
            </div>
        </main>
    </div>
    <script src="../assets/js/admin.js"></script>


    <!-- Modal thêm mới sản phẩm -->
    <div class="modal add-product <?php echo isset($_GET['add']) ? 'open' : ''; ?>">
        <div class="modal-container">
            <h3 class="modal-container-title add-product-e">THÊM MỚI SẢN PHẨM</h3>
            <a href="sanpham.php"><button class="modal-close"><i class="fa-regular fa-xmark"></i></button></a>
            <div class="modal-content">
                <form action="" method="POST" enctype="multipart/form-data" class="add-product-form">
                    <input type="hidden" name="action" value="add">
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
                            <textarea name="thong-tin-sp" class="product-desc" id="thong-tin-sp" placeholder="Nhập thông tin sản phẩm" style="width:100%;height:185px;"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="thong-so-ky-thuat" class="form-label">Thông số kỹ thuật</label>
                            <textarea name="thong-so-ky-thuat" class="product-desc" id="thong-so-ky-thuat" placeholder="Nhập thông số kỹ thuật" style="width:100%;height:185px;"></textarea>
                        </div>
                        <button class="form-submit btn-add-product-form add-product-e">
                            <i class="fa-regular fa-plus"></i>
                            <span>THÊM XE</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal chỉnh sửa sản phẩm -->
    <div class="modal add-product <?php echo isset($_GET['edit']) ? 'open' : ''; ?>">
        <div class="modal-container">
            <h3 class="modal-container-title add-product-e">CHỈNH SỬA SẢN PHẨM</h3>
            <a href="sanpham.php"><button class="modal-close"><i class="fa-regular fa-xmark"></i></button></a>
            <div class="modal-content">
                <?php if ($edit_product): ?>
                    <form action="" method="POST" enctype="multipart/form-data" class="add-product-form">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="product-id" value="<?php echo $edit_product['id']; ?>">
                        <div class="modal-content-left">
                            <img src="<?php echo $edit_product['hinhanh'] ?: '../image/blank-image.png'; ?>" alt="" class="upload-image-preview" id="edit-preview-1">
                            <div class="form-group file">
                                <label for="edit-hinh-anh" class="form-label-file"><i class="fa-regular fa-cloud-arrow-up"></i>Chọn hình ảnh</label>
                                <input accept="image/jpeg, image/png, image/jpg" id="edit-hinh-anh" name="up-hinh-anh" type="file" class="form-control" onchange="previewImage(this, 'edit-preview-1')">
                            </div>

                            <img src="<?php echo $edit_product['hinhanh2'] ?: '../image/blank-image.png'; ?>" alt="" class="upload-image-preview" id="edit-preview-2">
                            <div class="form-group file">
                                <label for="edit-hinh-anh2" class="form-label-file"><i class="fa-regular fa-cloud-arrow-up"></i>Chọn hình ảnh</label>
                                <input accept="image/jpeg, image/png, image/jpg" id="edit-hinh-anh2" name="up-hinh-anh2" type="file" class="form-control" onchange="previewImage(this, 'edit-preview-2')">
                            </div>

                            <img src="<?php echo $edit_product['hinhanh3'] ?: '../image/blank-image.png'; ?>" alt="" class="upload-image-preview" id="edit-preview-3">
                            <div class="form-group file">
                                <label for="edit-hinh-anh3" class="form-label-file"><i class="fa-regular fa-cloud-arrow-up"></i>Chọn hình ảnh</label>
                                <input accept="image/jpeg, image/png, image/jpg" id="edit-hinh-anh3" name="up-hinh-anh3" type="file" class="form-control" onchange="previewImage(this, 'edit-preview-3')">
                            </div>
                        </div>
                        <div class="modal-content-right">
                            <div class="form-group">
                                <label for="edit-ten-mon" class="form-label">Tên xe</label>
                                <input id="edit-ten-mon" name="ten-mon" type="text" value="<?php echo htmlspecialchars($edit_product['tensp']); ?>" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="edit-category" class="form-label">Chọn dòng</label>
                                <select name="category" id="edit-category" required>
                                    <option value="Dòng Ninja" <?php echo ($edit_product['dongsp'] == 'Dòng Ninja') ? 'selected' : ''; ?>>Dòng Ninja</option>
                                    <option value="Dòng Z" <?php echo ($edit_product['dongsp'] == 'Dòng Z') ? 'selected' : ''; ?>>Dòng Z</option>
                                    <option value="Dòng KLX" <?php echo ($edit_product['dongsp'] == 'Dòng KLX') ? 'selected' : ''; ?>>Dòng KLX</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit-gia-ban" class="form-label">Giá bán</label>
                                <input id="edit-gia-ban" name="gia-ban" type="text" value="<?php echo htmlspecialchars($edit_product['giaban']); ?>" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="edit-thong-tin-sp" class="form-label">Thông tin sản phẩm</label>
                                <textarea name="thong-tin-sp" class="product-desc" id="edit-thong-tin-sp" style="width:100%;height:185px;"><?php echo htmlspecialchars($edit_product['thongtinsp']); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="edit-thong-so-ky-thuat" class="form-label">Thông số kỹ thuật</label>
                                <textarea name="thong-so-ky-thuat" class="product-desc" id="edit-thong-so-ky-thuat" style="width:100%;height:185px;"><?php echo htmlspecialchars($edit_product['thongsokt']); ?></textarea>
                            </div>
                            <button class="form-submit btn-add-product-form add-product-e">
                                <i class="fa-regular fa-plus"></i>
                                <span>CẬP NHẬT</span>
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>