<?php
session_name('user_session');

session_start();
include('database.php');
include('toast.php');
include('logout.php');

// Kiểm tra đăng nhập
if (!isset($_SESSION['customer_id'])) {
    $_SESSION['error'] = "Bạn cần đăng nhập để thực hiện thao tác này!";
    header("Location: dn.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];
$role = null;
$total_price=0;
$fullAddress = ''; // Khởi tạo biến $fullAddress

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
// Lấy thông tin khách hàng
$stmt = $conn->prepare("SELECT name, contact, email, city_code, city_name, district_code, district_name, ward_code, ward_name, street_address, address, password FROM customer WHERE id = ?");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$stmt->bind_result($tenUser, $sdtUser, $emailUser, $cityCode, $cityName, $districtCode, $districtName, $wardCode, $wardName, $streetAddress, $fullAddress, $current_password);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tenUser = trim($_POST['infoname']);
    $sdtUser = trim($_POST['infophone']);
    $emailUser = trim($_POST['infoemail']);
    $cityCode = trim($_POST['city']);
    $districtCode = trim($_POST['district']);
    $wardCode = trim($_POST['ward']);
    $streetAddress = trim($_POST['infoaddress']);
    $mkHientai = trim($_POST['current-pass']);
    $mkMoi = trim($_POST['new-pass']);
    $xnMkMoi = trim($_POST['confirm-new-pass']);

    // Lấy tên địa chỉ từ API
    $cityName = '';
    $districtName = '';
    $wardName = '';
    
    if ($cityCode) {
        $response = file_get_contents("https://provinces.open-api.vn/api/p/" . $cityCode);
        if ($response !== false) {
            $cityData = json_decode($response, true);
            $cityName = $cityData['name'];
        }
    }
    
    if ($districtCode) {
        $response = file_get_contents("https://provinces.open-api.vn/api/d/" . $districtCode);
        if ($response !== false) {
            $districtData = json_decode($response, true);
            $districtName = $districtData['name'];
        }
    }
    
    if ($wardCode) {
        $response = file_get_contents("https://open.oapi.vn/location/wards/" . $districtCode . "?page=0&size=30");
        if ($response !== false) {
            $wardData = json_decode($response, true);
            if (isset($wardData['data']) && is_array($wardData['data'])) {
                foreach ($wardData['data'] as $ward) {
                    if ($ward['id'] === $wardCode) {
                        $wardName = $ward['name'];
                        break;
                    }
                }
            }
        }
    }
    

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
                // Tạo địa chỉ đầy đủ
                $fullAddress = $streetAddress;
                if ($wardName && $wardName !== '') {
                    $fullAddress .= ", " . $wardName;
                }
                if ($districtName && $districtName !== '') {
                    $fullAddress .= ", " . $districtName;
                }
                if ($cityName && $cityName !== '') {
                    $fullAddress .= ", " . $cityName;
                }

       

                $stmt = $conn->prepare("UPDATE customer SET name=?, contact=?, email=?, city_code=?, city_name=?, district_code=?, district_name=?, ward_code=?, ward_name=?, street_address=?, address=?, password=? WHERE id=?");
                $stmt->bind_param("ssssssssssssi", $tenUser, $sdtUser, $emailUser, $cityCode, $cityName, $districtCode, $districtName, $wardCode, $wardName, $streetAddress, $fullAddress, $passwordToUpdate, $customer_id);

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
    <title>Kawakaki </title>
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
                        <div class="address-inputs">
                            <select class="form-control" name="city" id="city" required>
                                <option value="">Chọn Tỉnh/Thành phố</option>
                            </select>
                            <select class="form-control" name="district" id="district" required>
                                <option value="">Chọn Quận/Huyện</option>
                            </select>
                            <select class="form-control" name="ward" id="ward" required>
                                <option value="">Chọn Phường/Xã</option>
                            </select>
                            <input class="form-control" type="text" name="infoaddress" id="infoaddress" placeholder="Số nhà, tên đường..." value="<?php echo htmlspecialchars($streetAddress); ?>" required>
                        </div>
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

   


    <?php include 'footer.php' ?>
    <script src="js/hoadon.js"></script>
    <script src="js/giohang.js"></script>
    <script src="js/phantrang.js"></script>
    <script src="js/ssbutton.js"></script>

    <style>
        .role-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 14px;
        }
        .admin-role {
            background-color: #e3f2fd;
            color: #1976d2;
            border: 1px solid #90caf9;
        }
        .user-role {
            background-color: #e3f2fd;
            color: #1976d2;
            border: 1px solid #e0e0e0;
        }
        .address-inputs {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .address-inputs select,
        .address-inputs input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .full-address-display {
            margin-top: 10px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }
        .address-value {
            font-weight: 500;
            color: #333;
            margin-top: 5px;
        }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // API endpoint for Vietnam administrative divisions
        const API_URL = 'https://provinces.open-api.vn/api/';
        
        // Fetch provinces
        fetch(API_URL)
            .then(response => response.json())
            .then(data => {
                const citySelect = document.getElementById('city');
                citySelect.innerHTML = '<option value="">Chọn Tỉnh/Thành phố</option>';
                data.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province.code;
                    option.textContent = province.name;
                    if (option.value === "<?php echo htmlspecialchars($cityCode, ENT_QUOTES); ?>") {
                        option.selected = true;
                    }
                    citySelect.appendChild(option);
                });
                // Nếu có city_code, load districts
                if ("<?php echo htmlspecialchars($cityCode, ENT_QUOTES); ?>") {
                    loadDistricts("<?php echo htmlspecialchars($cityCode, ENT_QUOTES); ?>");
                }
            })
            .catch(error => {
                console.error('Error loading provinces:', error);
            });

        // Handle city change
        document.getElementById('city').addEventListener('change', function() {
            const cityCode = this.value;
            const districtSelect = document.getElementById('district');
            const wardSelect = document.getElementById('ward');
            
            // Clear existing options
            districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
            wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
            
            if (cityCode) {
                loadDistricts(cityCode);
            }
        });

        // Handle district change
        document.getElementById('district').addEventListener('change', function() {
            const districtCode = this.value;
            const wardSelect = document.getElementById('ward');
            
            // Clear existing options
            wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
            
            if (districtCode) {
                loadWards(districtCode);
            }
        });

        function loadDistricts(cityCode) {
            fetch(API_URL + 'p/' + cityCode + '?depth=2')
                .then(response => response.json())
                .then(data => {
                    const districtSelect = document.getElementById('district');
                    districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
                    data.districts.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district.code;
                        option.textContent = district.name;
                        if (option.value === "<?php echo htmlspecialchars($districtCode, ENT_QUOTES); ?>") {
                            option.selected = true;
                        }
                        districtSelect.appendChild(option);
                    });
                    // Nếu có district_code, load wards
                    if ("<?php echo htmlspecialchars($districtCode, ENT_QUOTES); ?>") {
                        loadWards("<?php echo htmlspecialchars($districtCode, ENT_QUOTES); ?>");
                    }
                })
                .catch(error => {
                    console.error('Error loading districts:', error);
                });
        }

        function loadWards(districtCode) {
            fetch(API_URL + 'd/' + districtCode + '?depth=2')
                .then(response => response.json())
                .then(data => {
                    const wardSelect = document.getElementById('ward');
                    wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
                    if (data.wards && data.wards.length > 0) {
                        data.wards.forEach(ward => {
                            const option = document.createElement('option');
                            option.value = ward.code;
                            option.textContent = ward.name;
                            if (option.value === "<?php echo htmlspecialchars($wardCode, ENT_QUOTES); ?>") {
                                option.selected = true;
                            }
                            wardSelect.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading wards:', error);
                });
        }
    });
    </script>

</body>
<?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<script>if(window.history.replaceState){window.history.replaceState(null, null, window.location.href);}</script>";
}
?>

</html>