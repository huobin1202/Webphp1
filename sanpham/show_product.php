<?php
session_start();
include('../database.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $sql = "UPDATE products SET status = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Sản phẩm đã được hiển thị lại!";
    } else {
        $_SESSION['error'] = "Không thể hiển thị sản phẩm!";
    }
    
    header("Location: sanpham.php");
    exit();
}
?> 