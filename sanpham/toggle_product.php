<?php
session_start();
include('../database.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Lấy trạng thái hiện tại của sản phẩm
    $stmt = $conn->prepare("SELECT status FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    
    if ($product) {
        // Đảo ngược trạng thái (ẩn -> hiện hoặc hiện -> ẩn)
        $new_status = $product['status'] == 1 ? 0 : 1;
        
        // Cập nhật trạng thái mới
        $update_stmt = $conn->prepare("UPDATE products SET status = ? WHERE id = ?");
        $update_stmt->bind_param("ii", $new_status, $id);
        
        if ($update_stmt->execute()) {
            $_SESSION['success'] = $new_status == 1 ? "Sản phẩm đã được hiển thị!" : "Sản phẩm đã được ẩn!";
        } else {
            $_SESSION['error'] = "Không thể cập nhật trạng thái sản phẩm!";
        }
    } else {
        $_SESSION['error'] = "Không tìm thấy sản phẩm!";
    }
    
    header("Location: sanpham.php");
    exit();
}
?> 