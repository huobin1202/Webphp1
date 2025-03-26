<?php 
session_start();
include('database.php');
include('toast.php');

if (!isset($_SESSION['customer_id'])) {
    echo "<script>alert('Bạn cần đăng nhập để thanh toán!'); window.location.href='dn.php';</script>";
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
    SELECT giohang.*, products.tensp 
    FROM giohang 
    JOIN products ON giohang.product_id = products.id 
    WHERE giohang.customer_id = ?");
$cart_query->bind_param("i", $customer_id);
$cart_query->execute();
$cart_result = $cart_query->get_result();
while ($row = $cart_result->fetch_assoc()) {
    $cart[] = $row;
    $total += $row['soluong'] * $row['price'];
}

// Kiểm tra giỏ hàng trống
if (empty($cart)) {
    $_SESSION['error'] = "Vui lòng thêm sản phẩm ";
    header("Location: index.php");

    exit();
}

// Xử lý đặt hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tennguoinhan = $_POST['tennguoinhan'];
    $sdt = $_POST['sdtnhan'];
    $diachi = $_POST['diachinhan'];
    $ghichu = $_POST['ghichu'] ?? '';
    $payment = $_POST['payment_method'] ?? 'Tiền mặt';
    $delivery = $_POST['delivery_type'] ?? 'Giao tận nơi';

    // Xác định địa chỉ lưu vào orders
    if ($delivery === 'Giao tận nơi') {
        $order_address = $diachi;
    } else {
        $order_address = $delivery; // chi nhánh
    }

    $delivery_mode = $_POST['delivery_mode']; // "Mua trực tiếp" hoặc "Giao tận nơi"

    if ($delivery_mode === 'Giao tận nơi') {
        $order_address = $_POST['diachinhan'];
    } else {
        $order_address = $_POST['delivery_type']; // Chi nhánh (radio)
    }
    
    // Lưu đơn hàng
    $order_stmt = $conn->prepare("
        INSERT INTO orders (customer_id, total, note, payment_method, delivery_type, status, recipient_name, recipient_phone, address) 
        VALUES (?, ?, ?, ?, ?, 0, ?, ?, ?)
    ");
    $order_stmt->bind_param("idssssss", $customer_id, $total, $ghichu, $payment, $delivery_mode, $tennguoinhan, $sdt, $order_address);
    
    $order_stmt->execute();
    $order_id = $conn->insert_id;

    // Lưu chi tiết đơn hàng
    foreach ($cart as $item) {
        $detail_stmt = $conn->prepare("INSERT INTO order_details (order_id, product_id, soluong, price) VALUES (?, ?, ?, ?)");
        $detail_stmt->bind_param("iiid", $order_id, $item['product_id'], $item['soluong'], $item['price']);
        $detail_stmt->execute();
    }

    // Xóa giỏ hàng
    $delete_cart = $conn->prepare("DELETE FROM giohang WHERE customer_id = ?");
    $delete_cart->bind_param("i", $customer_id);
    $delete_cart->execute();

    header('Location: index.php');
    exit();
}
?>


<!DOCTYPE php>
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
                                    <input type="text" value="<?php echo htmlspecialchars($customer_address); ?>" readonly class="form-control">
                                </div>
                                <div id="new-address" class="form-group" style="display: none;">
                                    <input id="diachinhan" name="diachinhan" type="text" value=""
                                        placeholder="Địa chỉ nhận hàng" class="form-control chk-ship">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="checkout-row">
                <div class="checkout-col-title">Thông tin đơn hàng</div>
                <div class="checkout-col-content">
                    <div class="content-group  ">
                        <p class="checkout-content-label">Hình thức giao nhận</p>
                        <div class="checkout-type-order">
                        <input type="button" class="type-order-btn active " id="tudenlay" value="Tự đến lấy"  ></input>
                        <input type="button" class="type-order-btn " id="giaotannoi" value="Giao tận nơi"></input>
                        <input type="hidden" name="delivery_mode" id="delivery_mode" value="Mua trực tiếp">
                        <input type="hidden" name="delivery_mode" id="delivery_mode" value="Giao tận nơi">
                        </div>
                    </div>

                    <div class="content-group chk-ship" id="giaotannoi-group">
                        <p class="checkout-content-label">Phương thức thanh toán</p>
                        <div class="delivery-time">
                            <input type="radio" name="payment_method" value="Tiền mặt" id="giaongay" class="radio" checked>
                            <label for="giaongay">Thanh toán bằng tiền mặt</label>
                        </div>
                        <div class="delivery-time">
                            <input type="radio" name="payment_method" value="Chuyển khoản" id="deliverytime" class="radio">
                            <label for="deliverytime">Thanh toán bằng chuyển khoản</label>
                        </div>
                    </div>

                    <div class="content-group" id="tudenlay-group">
                        <p class="checkout-content-label">Lấy hàng tại chi nhánh</p>
                        <div class="delivery-time">
                            <input type="radio" name="delivery_type" value="273 An Dương Vương" id="chinhanh-1" class="radio" checked>
                            <label for="chinhanh-1">273 An Dương Vương, Phường 3, Quận 5</label>
                        </div>
                        <div class="delivery-time">
                            <input type="radio" name="delivery_type" value="04 Tôn Đức Thắng" id="chinhanh-2" class="radio">
                            <label for="chinhanh-2">04 Tôn Đức Thắng, Phường Bến Nghé, Quận 1</label>
                        </div>
                        <div class="delivery-time">
                            <input type="radio" name="delivery_type" value="105 Bà Huyện Thanh Quan" id="chinhanh-3" class="radio">
                            <label for="chinhanh-3">105 Bà Huyện Thanh Quan, Phường Võ Thị Sáu, Quận 3</label>
                        </div>
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
                <?php foreach ($cart as $item): ?>
                    <div>
                        <?php echo htmlspecialchars($item['tensp']); ?> - SL: <?php echo $item['soluong']; ?> - <?php echo number_format($item['price']); ?>₫
                    </div>
                <?php endforeach; ?>
            </div>
                <div class="total-bill-order"></div>
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
</body>
</html>
