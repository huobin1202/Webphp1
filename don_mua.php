<?php 
session_start();
include('database.php');

$customer_id = $_SESSION['customer_id'];

// Lấy đơn hàng mới nhất của khách hàng
$order_query = $conn->prepare("
    SELECT * FROM orders 
    WHERE customer_id = ? 
    ORDER BY id DESC LIMIT 1
");
$order_query->bind_param("i", $customer_id);
$order_query->execute();
$order_result = $order_query->get_result();
$order = $order_result->fetch_assoc();

// Debug information
error_log("Order ID: " . ($order['id'] ?? 'not found'));
error_log("Delivery Type: " . ($order['delivery_type'] ?? 'not found'));
error_log("Address: " . ($order['address'] ?? 'not found'));
error_log("Full order data: " . print_r($order, true));

// Check if the address field exists in the database
$check_address_field = $conn->query("SHOW COLUMNS FROM orders LIKE 'address'");
if ($check_address_field->num_rows == 0) {
    error_log("WARNING: 'address' column does not exist in the orders table!");
    // Add the address column if it doesn't exist
    $conn->query("ALTER TABLE orders ADD COLUMN address VARCHAR(255)");
    error_log("Added 'address' column to orders table");
}

if (!$order) {
    echo "<script>alert('Không tìm thấy hóa đơn!'); window.location.href='index.php';</script>";
    exit();
}

$order_id = $order['id'];
$total_price = $order['total'];

// Lấy danh sách sản phẩm trong hóa đơn
$details_query = $conn->prepare("
    SELECT order_details.*, products.tensp 
    FROM order_details 
    JOIN products ON order_details.product_id = products.id 
    WHERE order_details.order_id = ?
");
$details_query->bind_param("i", $order_id);
$details_query->execute();
$details_result = $details_query->get_result();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Hóa Đơn Mua Hàng</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        text-align: center;
        background-color: #f5f5f5;
    }

    .container {
        width: 90%;
        max-width: 500px;
        margin: 50px auto;
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
    }

    .title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .info {
        text-align: left;
        margin: 10px 0;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .table th,
    .table td {
        border: 1px solid #ddd;
        padding: 10px;
    }

    .table th {
        background: #28a745;
        color: white;
    }

    .total {
        font-size: 18px;
        font-weight: bold;
        margin-top: 10px;
    }

    .btn-group {
        margin-top: 20px;
    }

    .btn {
        display: inline-block;
        background: #28a745;
        color: white;
        padding: 10px 20px;
        margin: 5px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
    }

    .btn:hover {
        background: #1e7e34;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="title">Hóa Đơn Mua Hàng</h2>
        <div class="info">
            <h2>Thông tin người đặt</h2>
            <p><b>Tên người nhận:</b> <?php echo htmlspecialchars($order['recipient_name']); ?></p> 
            <p><b>Số điện thoại:</b> <?php echo htmlspecialchars($order['recipient_phone']); ?></p>
            <p><b>Địa chỉ nhận:</b> <?php 
                // Debug information
                echo "<!-- Address value: " . htmlspecialchars($order['address']) . " -->";
                echo "<!-- Delivery type: " . htmlspecialchars($order['delivery_type']) . " -->";
                echo htmlspecialchars($order['address'] ?? 'Không có địa chỉ'); 
            ?></p>
            <h2>Thông tin thanh toán</h2>
            <p><b>Hình thức giao hàng:</b> <?php echo htmlspecialchars($order['delivery_type']); ?></p>
            <p><b>Phương thức thanh toán:</b> <?php echo htmlspecialchars($order['payment_method']); ?></p>
            <h2>Danh sách đơn mua</h2>
        </div>

        <table class="table">
            <tr>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Giá</th>
            </tr>
            <?php while ($item = $details_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['tensp']); ?></td>
                <td><?php echo $item['soluong']; ?></td>
                <td><?php echo number_format($item['price']); ?>₫</td>
            </tr>
            <?php endwhile; ?>
        </table>
        <p><b>Ghi chú:</b><?php echo htmlspecialchars($order['note']); ?></p>
        <p class="total">Tổng tiền: <?php echo number_format($total_price); ?>₫</p>

        <div class="btn-group">
            <a href="index.php" class="btn">Trở về trang chủ</a>
        </div>
    </div>
</body>

</html>