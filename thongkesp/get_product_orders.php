<?php
session_start();
include('../database.php');

if (!isset($_GET['product_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Product ID is required']);
    exit();
}

$product_id = intval($_GET['product_id']);
$start_date = isset($_GET['start_date']) && $_GET['start_date'] !== '' ? $_GET['start_date'] : null;
$end_date = isset($_GET['end_date']) && $_GET['end_date'] !== '' ? $_GET['end_date'] : null;

$sql = "SELECT 
    o.id as order_id,
    od.soluong as quantity,
    od.price,
    DATE_FORMAT(o.created_at, '%d/%m/%Y') as created_at
FROM orders o
INNER JOIN order_details od ON o.id = od.order_id
WHERE od.product_id = ?";

if ($start_date && $end_date) {
    $sql .= " AND o.created_at BETWEEN ? AND ?";
    $sql .= " ORDER BY o.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $product_id, $start_date, $end_date);
} else if ($start_date) {
    $sql .= " AND o.created_at >= ?";
    $sql .= " ORDER BY o.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $product_id, $start_date);
} else if ($end_date) {
    $sql .= " AND o.created_at <= ?";
    $sql .= " ORDER BY o.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $product_id, $end_date);
} else {
    $sql .= " ORDER BY o.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
}

$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

header('Content-Type: application/json');
echo json_encode($orders);
?> 