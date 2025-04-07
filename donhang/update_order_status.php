<?php
session_start();
include('../database.php');

if (!isset($_SESSION['username'])) {
    header("Location: dnurl.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id']);
    $new_status = $_POST['new_status'];
    
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Cập nhật trạng thái thành công!";
    } else {
        $_SESSION['error'] = "Không thể cập nhật trạng thái!";
    }
    
    // Redirect back với các tham số tìm kiếm
    $redirect_url = 'donhang.php';
    if (!empty($_SERVER['HTTP_REFERER'])) {
        $parts = parse_url($_SERVER['HTTP_REFERER']);
        if (isset($parts['query'])) {
            $redirect_url .= '?' . $parts['query'];
        }
    }
    
    header("Location: " . $redirect_url);
    exit();
}
?> 