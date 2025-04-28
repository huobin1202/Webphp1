<?php
session_start();
include('../database.php');

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Product ID is required']);
    exit();
}

$product_id = intval($_GET['id']);
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;

// Get product details and statistics
$sql = "SELECT 
    p.id,
    p.tensp,
    p.hinhanh,
    COUNT(od.product_id) as total_sold,
    SUM(od.soluong) as total_quantity,
    SUM(od.soluong * od.price) as total_revenue,
    MIN(o.created_at) as first_sale_date,
    MAX(o.created_at) as last_sale_date
FROM products p
LEFT JOIN order_details od ON p.id = od.product_id
LEFT JOIN orders o ON od.order_id = o.id
WHERE p.id = ?
GROUP BY p.id, p.tensp, p.hinhanh";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['error' => 'Product not found']);
    exit();
}

$product_stats = $result->fetch_assoc();

// Get sales history with date filtering
$history_sql = "SELECT 
    o.id as order_id,
    od.soluong as quantity,
    od.price,
    DATE_FORMAT(o.created_at, '%d/%m/%Y') as order_date
FROM orders o
INNER JOIN order_details od ON o.id = od.order_id
WHERE od.product_id = ?";

$params = [$product_id];
$types = "i";

if ($start_date) {
    $history_sql .= " AND o.created_at >= ?";
    $params[] = $start_date;
    $types .= "s";
}

if ($end_date) {
    $history_sql .= " AND o.created_at <= ?";
    $params[] = $end_date;
    $types .= "s";
}

$history_sql .= " ORDER BY o.created_at DESC";

$stmt = $conn->prepare($history_sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$history_result = $stmt->get_result();

$sales_history = [];
while ($row = $history_result->fetch_assoc()) {
    $sales_history[] = $row;
}

// Format dates
if ($product_stats['first_sale_date']) {
    $product_stats['first_sale_date'] = date('d/m/Y', strtotime($product_stats['first_sale_date']));
}
if ($product_stats['last_sale_date']) {
    $product_stats['last_sale_date'] = date('d/m/Y', strtotime($product_stats['last_sale_date']));
}

$response = [
    'product_stats' => $product_stats,
    'sales_history' => $sales_history
];

header('Content-Type: application/json');
echo json_encode($response);
?> 