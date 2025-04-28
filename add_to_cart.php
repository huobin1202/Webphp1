<?php
session_start();
include('database.php');

if (!isset($_SESSION['customer_id'])) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $product_price = $_POST['product_price'];
    $product_img = $_POST['product_img'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng chưa
    $stmt = $conn->prepare("SELECT soluong FROM giohang WHERE product_id = ? AND customer_id = ?");
    $stmt->bind_param("ii", $product_id, $_SESSION['customer_id']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        echo json_encode(['success' => true, 'message' => 'Sản phẩm đã tồn tại trong giỏ hàng!']);
    } else {
        $stmt = $conn->prepare("INSERT INTO giohang (customer_id, product_id, soluong, price, img) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiss", $_SESSION['customer_id'], $product_id, $quantity, $product_price, $product_img);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Sản phẩm đã được thêm vào giỏ hàng!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng!']);
        }
        $stmt->close();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ!']);
}

$conn->close();
?> 