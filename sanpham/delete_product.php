<?php
session_start();
include('../database.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Kiểm tra xem sản phẩm đã được bán ra chưa
    $check_sold = $conn->prepare("SELECT COUNT(*) as count FROM order_details WHERE product_id = ?");
    $check_sold->bind_param("i", $id);
    $check_sold->execute();
    $result = $check_sold->get_result();
    $row = $result->fetch_assoc();
    
    if ($row['count'] > 0) {
        // Sản phẩm đã được bán ra, không thể xóa
        $_SESSION['error'] = "Không thể xóa sản phẩm đã được bán!";
        header("Location: sanpham.php");
        exit();
    } else {
        // Sản phẩm chưa được bán ra, xóa sản phẩm
        $sql = "DELETE FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Sản phẩm đã được xóa thành công!";
        } else {
            $_SESSION['error'] = "Không thể xóa sản phẩm!";
        }
        
        header("Location: sanpham.php");
        exit();
    }
}
?> 