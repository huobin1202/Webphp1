<?php
session_start();
include('../database.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Kiểm tra xem sản phẩm có trong đơn hàng không
    $check_sold = $conn->prepare("SELECT COUNT(*) as count FROM order_details WHERE product_id = ?");
    $check_sold->bind_param("i", $id);
    $check_sold->execute();
    $result = $check_sold->get_result();
    $row = $result->fetch_assoc();
    
    // Lấy trạng thái sản phẩm
    $get_status = $conn->prepare("SELECT status FROM products WHERE id = ?");
    $get_status->bind_param("i", $id);
    $get_status->execute();
    $status_result = $get_status->get_result();
    $status_row = $status_result->fetch_assoc();
    
    // Trả về kết quả dạng JSON
    header('Content-Type: application/json');
    echo json_encode([
        'sold' => $row['count'] > 0,
        'status' => $status_row['status']
    ]);
}
?> 