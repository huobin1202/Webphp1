<?php 
session_start();
include('database.php');
include('toast.php');
include('logout.php');

if (!isset($_SESSION['customer_id'])) {
    $_SESSION['error'] = "Đăng nhập để thanh toán!";
    header("Location: index.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

// Lấy thông tin địa chỉ của khách hàng
$address_query = $conn->prepare("SELECT address FROM customer WHERE id = ?");
$address_query->bind_param("i", $customer_id);
$address_query->execute();
$address_result = $address_query->get_result();
$customer_address = $address_result->fetch_assoc()['address'] ?? '';

// Lấy giỏ hàng
$cart = [];
$total = 0;
$cart_query = $conn->prepare("
    SELECT giohang.*, products.tensp, products.status 
    FROM giohang 
    JOIN products ON giohang.product_id = products.id 
    WHERE giohang.customer_id = ?");
$cart_query->bind_param("i", $customer_id);
$cart_query->execute();
$cart_result = $cart_query->get_result();
while ($row = $cart_result->fetch_assoc()) {
    // Chỉ thêm sản phẩm còn hoạt động vào giỏ hàng
    if ($row['status'] == 1) {
        $cart[] = $row;
        $total += $row['soluong'] * $row['price'];
    }
}

// Kiểm tra giỏ hàng trống
if (empty($cart)) {
    $_SESSION['error'] = "Vui lòng thêm sản phẩm có sẵn vào giỏ hàng";
    header("Location: index.php");
    exit();
}

// Xử lý đặt hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tennguoinhan = $_POST['tennguoinhan'];
    $sdt = $_POST['sdtnhan'];
    $ghichu = $_POST['ghichu'] ?? '';
    $delivery_mode = $_POST['delivery_mode']; // "Mua trực tiếp" hoặc "Giao tận nơi"
    
    // Xử lý phương thức thanh toán
    if ($delivery_mode === 'Giao tận nơi') {
        $payment = $_POST['payment_method'] ?? 'Tiền mặt';
    } else {
        $payment = $_POST['payment_method_store'] ?? 'Tiền mặt';
    }

    // Initialize address components
    $city_code = $_POST['city_new'] ?? '';
    $district_code = $_POST['district_new'] ?? '';
    $ward_code = $_POST['ward_new'] ?? '';
    $street_address = $_POST['street_address_new'] ?? '';
    $order_address = '';

    // Handle address based on delivery mode
    if ($delivery_mode === 'Giao tận nơi') {
        if (isset($_POST['address_type']) && $_POST['address_type'] === 'saved') {
            $order_address = $_POST['diachinhan'] ?? '';
        } else {
            $order_address = $_POST['diachinhan_new'] ?? '';
            $city_code = $_POST['city_new'] ?? '';
            $district_code = $_POST['district_new'] ?? '';
            $ward_code = $_POST['ward_new'] ?? '';
            $street_address = $_POST['street_address_new'] ?? '';
        }
    } else {
        // Khi chọn "Tự đến lấy", lấy địa chỉ chi nhánh được chọn
        $branch_address = $_POST['delivery_type'] ?? '';
        if (!empty($branch_address)) {
            // Lấy thông tin chi nhánh từ database
            $branch_query = $conn->prepare("SELECT * FROM branch_addresses WHERE address = ?");
            $branch_query->bind_param("s", $branch_address);
            $branch_query->execute();
            $branch_result = $branch_query->get_result();
            $branch = $branch_result->fetch_assoc();
            
            if ($branch) {
                $order_address = $branch['address'];
                $city_code = $branch['city_code'];
                $city_name = $branch['city_name'];
                $district_code = $branch['district_code'];
                $district_name = $branch['district_name'];
                $ward_code = $branch['ward_code'];
                $ward_name = $branch['ward_name'];
                $street_address = $branch['street_address'];
            }
        }
    }

    // Log the address information for debugging
    error_log("Order address information: " . print_r([
        'delivery_mode' => $delivery_mode,
        'order_address' => $order_address,
        'city_code' => $city_code,
        'city_name' => $city_name,
        'district_code' => $district_code,
        'district_name' => $district_name,
        'ward_code' => $ward_code,
        'ward_name' => $ward_name,
        'street_address' => $street_address
    ], true));

    // Lưu đơn hàng
    $order_stmt = $conn->prepare("
        INSERT INTO orders (
            customer_id, total, note, payment_method, delivery_type, 
            status, recipient_name, recipient_phone, address, 
            city_code, city_name, district_code, district_name, 
            ward_code, ward_name, street_address
        ) VALUES (?, ?, ?, ?, ?, 'chuaxuly', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $order_stmt->bind_param("idsssssssssssss", 
        $customer_id, 
        $total, 
        $ghichu, 
        $payment, 
        $delivery_mode, 
        $tennguoinhan, 
        $sdt, 
        $order_address,
        $city_code,
        $city_name,
        $district_code,
        $district_name,
        $ward_code,
        $ward_name,
        $street_address
    );
    
    if (!$order_stmt->execute()) {
        error_log("Error executing order statement: " . $order_stmt->error);
        throw new Exception("Lỗi khi lưu đơn hàng: " . $order_stmt->error);
    }

    $order_id = $conn->insert_id;
    error_log("Order created successfully with ID: " . $order_id);

    // Verify the inserted data
    $verify_query = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $verify_query->bind_param("i", $order_id);
    $verify_query->execute();
    $verify_result = $verify_query->get_result();
    $inserted_order = $verify_result->fetch_assoc();
    
    error_log("Inserted order data: " . print_r($inserted_order, true));

    // Lưu chi tiết đơn hàng
    foreach ($cart as $item) {
        // Chỉ lưu sản phẩm còn hoạt động
        if ($item['status'] == 1) {
            $detail_stmt = $conn->prepare("INSERT INTO order_details (order_id, product_id, soluong, price) VALUES (?, ?, ?, ?)");
            $detail_stmt->bind_param("iiid", $order_id, $item['product_id'], $item['soluong'], $item['price']);
            $detail_stmt->execute();
        }
    }

    // Xóa giỏ hàng
    $delete_cart = $conn->prepare("DELETE FROM giohang WHERE customer_id = ?");
    $delete_cart->bind_param("i", $customer_id);
    $delete_cart->execute();

    header('Location: mua_thanhcong.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán</title>
    <link rel="stylesheet" href="css/app.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="./assets/font/font-awesome-pro-v6-6.2.0/css/all.min.css">
</head>

<body>
<div class="checkout-page">
    <div class="checkout-header">
        <div class="checkout-return">
            <a href="index.php"><button ><i class="fa-regular fa-chevron-left"></i></button></a>
        </div>
        <h2 class="checkout-title">Thanh toán</h2>
    </div>
    <main class="checkout-section container">
        <div class="checkout-col-left">
            <form action="" method="POST" class="info-nhan-hang">
            <div class="checkout-row">
                <div class="checkout-col-title">Thông tin người nhận</div>
                <div class="checkout-col-content">
                    <div class="content-group">
                        <div class="form-group">
                            <input id="tennguoinhan" name="tennguoinhan" type="text" value=""
                                placeholder="Tên người nhận" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <input id="sdtnhan" name="sdtnhan" type="text" value="" 
                                placeholder="Số điện thoại" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <div class="address-selection" id="address-selection" style="display: none;">
                                <div class="address-options">
                                    <label>
                                        <input type="radio" name="address_type" value="saved" checked> Sử dụng địa chỉ đã lưu
                                    </label>
                                    <label>
                                        <input type="radio" name="address_type" value="new"> Nhập địa chỉ mới
                                    </label>
                                </div>
                                <div id="saved-address" class="form-group">
                                    <input type="text" name="diachinhan" value="<?php echo htmlspecialchars($customer_address); ?>" readonly class="form-control">
                                </div>
                                <div id="new-address" class="form-group" style="display: none;">
                                    <div class="address-inputs">
                                        <select class="form-control" name="city_new" id="city_new" style="margin-bottom:10px;" data-selected="<?php echo htmlspecialchars($city_code); ?>">
                                            <option value="">Chọn Tỉnh/Thành phố</option>
                                        </select>
                                        <select class="form-control" name="district_new" id="district_new" style="margin-bottom:10px;" data-selected="<?php echo htmlspecialchars($district_code); ?>">
                                            <option value="">Chọn Quận/Huyện</option>
                                        </select>
                                        <select class="form-control" name="ward_new" id="ward_new" style="margin-bottom:10px;" data-selected="<?php echo htmlspecialchars($ward_code); ?>">
                                            <option value="">Chọn Phường/Xã</option>
                                        </select>
                                        <input type="text" name="street_address_new" id="street_address_new" value="<?php echo htmlspecialchars($street_address); ?>" placeholder="Số nhà, tên đường..." class="form-control chk-ship">
                                    </div>
                                </div>
                                <input type="hidden" name="final_address" id="final_address" value="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="checkout-row">
                <div class="checkout-col-title">Thông tin đơn hàng</div>
                <div class="checkout-col-content">
                    <div class="content-group">
                        <p class="checkout-content-label">Hình thức giao nhận</p>
                        <div class="checkout-type-order">
                            <button type="button" class="type-order-btn active" id="tudenlay" onclick="switchDeliveryMode('tudenlay')">
                                <i class="fa-regular fa-shop"></i>Tự đến lấy
                            </button>
                            <button type="button" class="type-order-btn" id="giaotannoi" onclick="switchDeliveryMode('giaotannoi')">
                                <i class="fa-regular fa-truck"></i>Giao tận nơi
                            </button>
                            <input type="hidden" name="delivery_mode" id="delivery_mode" value="Mua trực tiếp">
                        </div>
                    </div>

                    <div class="content-group" id="giaotannoi-group" style="display: none;">
                        <p class="checkout-content-label">Phương thức thanh toán</p>
                        <div class="delivery-time">
                            <input type="radio" name="payment_method" value="Tiền mặt" id="giaongay" class="radio" checked>
                            <label for="giaongay">Thanh toán bằng tiền mặt</label>
                        </div>
                        <div class="delivery-time">
                            <input type="radio" name="payment_method" value="Chuyển khoản" id="deliverytime" class="radio">
                            <label for="deliverytime">Thanh toán bằng chuyển khoản</label>
                        </div>
                        
                        <div id="bank-info" style="display: none; margin-top: 15px; padding: 15px; background: #f8f8f8; border-radius: 5px; border: 1px solid #e0e0e0;">
                            <p style="font-weight: 600; color: #139b3a; margin-bottom: 10px;">Thông tin chuyển khoản:</p>
                            <div style="margin-bottom: 15px;">
                                <p style="margin: 5px 0;"><strong>Ngân hàng:</strong> Vietcombank</p>
                                <p style="margin: 5px 0;"><strong>Số tài khoản:</strong> 1234567890</p>
                                <p style="margin: 5px 0;"><strong>Chủ tài khoản:</strong> CÔNG TY TNHH BMT</p>
                                <p style="margin: 5px 0;"><strong>Chi nhánh:</strong> TP.HCM</p>
                            </div>
                            <div style="background: #fff3cd; padding: 10px; border-radius: 4px; border: 1px solid #ffeeba;">
                                <p style="color: #856404; margin: 0;">
                                    <i class="fa-light fa-triangle-exclamation" style="margin-right: 5px;"></i>
                                    Vui lòng ghi rõ nội dung chuyển khoản: "Tên người mua + Số điện thoại"
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="content-group" id="tudenlay-group">
                        <p class="checkout-content-label">Phương thức thanh toán</p>
                        <div class="delivery-time">
                            <input type="radio" name="payment_method_store" value="Tiền mặt" id="tienmat_store" class="radio" checked>
                            <label for="tienmat_store">Thanh toán bằng tiền mặt</label>
                        </div>
                        <div class="delivery-time">
                            <input type="radio" name="payment_method_store" value="Chuyển khoản" id="chuyenkhoan_store" class="radio">
                            <label for="chuyenkhoan_store">Thanh toán bằng chuyển khoản</label>
                        </div>

                        <div id="bank-info-store" style="display: none; margin: 15px 0 25px; padding: 15px; background: #f8f8f8; border-radius: 5px; border: 1px solid #e0e0e0;">
                            <p style="font-weight: 600; color: #139b3a; margin-bottom: 10px;">Thông tin chuyển khoản:</p>
                            <div style="margin-bottom: 15px;">
                                <p style="margin: 5px 0;"><strong>Ngân hàng:</strong> Vietcombank</p>
                                <p style="margin: 5px 0;"><strong>Số tài khoản:</strong> 1234567890</p>
                                <p style="margin: 5px 0;"><strong>Chủ tài khoản:</strong> CÔNG TY TNHH BMT</p>
                                <p style="margin: 5px 0;"><strong>Chi nhánh:</strong> TP.HCM</p>
                            </div>
                            <div style="background: #fff3cd; padding: 10px; border-radius: 4px; border: 1px solid #ffeeba;">
                                <p style="color: #856404; margin: 0;">
                                    <i class="fa-light fa-triangle-exclamation" style="margin-right: 5px;"></i>
                                    Vui lòng ghi rõ nội dung chuyển khoản: "Tên người mua + Số điện thoại"
                                </p>
                            </div>
                        </div>

                        <p class="checkout-content-label">Lấy hàng tại chi nhánh</p>
                        <?php
                        // Lấy danh sách chi nhánh từ database
                        $branch_query = $conn->query("SELECT * FROM branch_addresses");
                        while ($branch = $branch_query->fetch_assoc()) {
                            echo '<div class="delivery-time">';
                            echo '<input type="radio" name="delivery_type" value="' . htmlspecialchars($branch['address']) . '" id="chinhanh-' . $branch['id'] . '" class="radio"' . ($branch['id'] == 1 ? ' checked' : '') . '>';
                            echo '<label for="chinhanh-' . $branch['id'] . '">' . htmlspecialchars($branch['address']) . '</label>';
                            echo '</div>';
                        }
                        ?>
                    </div>

                    <div class="content-group">
                        <p class="checkout-content-label">Ghi chú đơn hàng</p>
                        <textarea type="text" name="ghichu" class="note-order" placeholder="Nhập ghi chú"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="checkout-col-right">
            <p class="checkout-content-label">Đơn hàng</p>
          
            <div class="bill-payment">
                <div class="bill-total" id="list-order-checkout">
                    <?php 
                    $total_items = 0;
                    foreach ($cart as $item): 
                        if ($item['status'] == 1):
                            $total_items += $item['soluong'];
                    ?>
                        <div>
                            <?php echo htmlspecialchars($item['tensp']); ?> - SL: <?php echo $item['soluong']; ?> - <?php echo number_format($item['price']); ?>₫
                        </div>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </div>
                <div class="total-bill-order">
                    <div class="text">Tổng số lượng: <?php echo $total_items; ?> sản phẩm</div>
                </div>
            </div>
            <div class="total-checkout">
                <div class="text">Tổng tiền</div>
                <div class="price-bill">
                    <div class="price-final" id="checkout-cart-price-final"><?php echo number_format($total); ?>₫</div>
                </div>
            </div>
            <button type="submit" class="complete-checkout-btn">Đặt hàng</button>
        </div>
        </form>
    </main>
</div>

<script src="js/checkout.js"></script>

<script>
    // Truyền giá trị đã chọn từ PHP ra JS
    const selectedCity = "<?php echo isset($city_code) ? htmlspecialchars($city_code, ENT_QUOTES) : ''; ?>";
    const selectedDistrict = "<?php echo isset($district_code) ? htmlspecialchars($district_code, ENT_QUOTES) : ''; ?>";
    const selectedWard = "<?php echo isset($ward_code) ? htmlspecialchars($ward_code, ENT_QUOTES) : ''; ?>";
    const selectedStreet = "<?php echo isset($street_address) ? htmlspecialchars($street_address, ENT_QUOTES) : ''; ?>";
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const API_URL = 'https://provinces.open-api.vn/api/';
    const citySelect = document.getElementById('city_new');
    const districtSelect = document.getElementById('district_new');
    const wardSelect = document.getElementById('ward_new');
    const streetInput = document.getElementById('street_address_new');

    // Fetch provinces
    fetch(API_URL)
        .then(response => response.json())
        .then(data => {
            citySelect.innerHTML = '<option value="">Chọn Tỉnh/Thành phố</option>';
            data.forEach(province => {
                const option = document.createElement('option');
                option.value = province.code;
                option.textContent = province.name;
                citySelect.appendChild(option);
            });
            // Nếu có city đã chọn thì set value và load districts
            if (selectedCity) {
                citySelect.value = selectedCity;
                loadDistricts(selectedCity, selectedDistrict, selectedWard);
            }
        });

    // Khi chọn tỉnh/thành
    citySelect.addEventListener('change', function() {
        loadDistricts(this.value, '', '');
    });

    // Khi chọn quận/huyện
    districtSelect.addEventListener('change', function() {
        loadWards(this.value, '');
    });

    // Hàm load quận/huyện
    function loadDistricts(cityCode, districtCode, wardCode) {
        fetch(API_URL + 'p/' + cityCode + '?depth=2')
            .then(response => response.json())
            .then(data => {
                districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
                data.districts.forEach(district => {
                    const option = document.createElement('option');
                    option.value = district.code;
                    option.textContent = district.name;
                    districtSelect.appendChild(option);
                });
                if (districtCode) {
                    districtSelect.value = districtCode;
                    loadWards(districtCode, wardCode);
                }
            });
    }

    // Hàm load phường/xã
    function loadWards(districtCode, wardCode) {
        fetch(API_URL + 'd/' + districtCode + '?depth=2')
            .then(response => response.json())
            .then(data => {
                wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
                if (data.wards && data.wards.length > 0) {
                    data.wards.forEach(ward => {
                        const option = document.createElement('option');
                        option.value = ward.code;
                        option.textContent = ward.name;
                        wardSelect.appendChild(option);
                    });
                }
                if (wardCode) {
                    wardSelect.value = wardCode;
                }
            });
    }

    // Set lại giá trị street nếu có
    if (selectedStreet) {
        streetInput.value = selectedStreet;
    }
});
</script>

</body>
</html>


