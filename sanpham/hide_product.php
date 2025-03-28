<?php
session_start();
include('../database.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Lấy trạng thái hiện tại của sản phẩm
    $check_status = $conn->prepare("SELECT status FROM products WHERE id = ?");
    $check_status->bind_param("i", $id);
    $check_status->execute();
    $result = $check_status->get_result();
    $row = $result->fetch_assoc();
    
    // Đảo ngược trạng thái (1 -> 0 hoặc 0 -> 1)
    $new_status = $row['status'] == 1 ? 0 : 1;
    
    // Cập nhật trạng thái mới
    $sql = "UPDATE products SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $new_status, $id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = $new_status == 1 ? "Sản phẩm đã được hiển thị!" : "Sản phẩm đã được ẩn!";
    } else {
        $_SESSION['error'] = "Không thể thay đổi trạng thái sản phẩm!";
    }
    
    header("Location: sanpham.php");
    exit();
}
?> 