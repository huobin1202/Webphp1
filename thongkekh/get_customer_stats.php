<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: dnurl.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "admindoan";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if customer ID is provided
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Customer ID is required']);
    exit();
}

$customer_id = $_GET['id'];
$start_date = isset($_GET['start_date']) && $_GET['start_date'] !== '' ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) && $_GET['end_date'] !== '' ? $_GET['end_date'] : '';

// Get customer details and statistics
$query = "SELECT 
    c.id,
    c.name as tenkh,
    COUNT(o.id) as total_orders,
    SUM(od.soluong) as total_quantity,
    SUM(od.soluong * od.price) as total_revenue,
    MIN(o.created_at) as first_order_date,
    MAX(o.created_at) as last_order_date
FROM customer c
LEFT JOIN orders o ON c.id = o.customer_id
LEFT JOIN order_details od ON o.id = od.order_id
WHERE c.id = ?";

$params = [$customer_id];
$types = "i";

if (!empty($start_date)) {
    $query .= " AND o.created_at >= ?";
    $params[] = $start_date;
    $types .= "s";
}
if (!empty($end_date)) {
    $query .= " AND o.created_at <= ?";
    $params[] = $end_date;
    $types .= "s";
}

$query .= " GROUP BY c.id, c.name";

$stmt = $conn->prepare($query);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Error preparing query: ' . $conn->error]);
    exit();
}

$stmt->bind_param($types, ...$params);
if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(['error' => 'Error executing query: ' . $stmt->error]);
    exit();
}

$result = $stmt->get_result();
$customer_stats = $result->fetch_assoc();

if (!$customer_stats) {
    http_response_code(404);
    echo json_encode(['error' => 'Customer not found']);
    exit();
}

// Get sales history
$history_query = "SELECT 
    o.id as order_id,
    od.soluong as quantity,
    od.price,
    o.created_at as order_date,
    o.status
FROM orders o
JOIN order_details od ON o.id = od.order_id
WHERE o.customer_id = ?";

$params = [$customer_id];
$types = "i";

if (!empty($start_date)) {
    $history_query .= " AND o.created_at >= ?";
    $params[] = $start_date;
    $types .= "s";
}
if (!empty($end_date)) {
    $history_query .= " AND o.created_at <= ?";
    $params[] = $end_date;
    $types .= "s";
}

$history_query .= " ORDER BY o.created_at DESC";

$stmt = $conn->prepare($history_query);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Error preparing history query: ' . $conn->error]);
    exit();
}

$stmt->bind_param($types, ...$params);
if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(['error' => 'Error executing history query: ' . $stmt->error]);
    exit();
}

$history_result = $stmt->get_result();
$sales_history = [];

while ($row = $history_result->fetch_assoc()) {
    $row['order_date'] = date('d/m/Y', strtotime($row['order_date']));
    $sales_history[] = $row;
}

// Format dates
$customer_stats['first_order_date'] = $customer_stats['first_order_date'] ? date('d/m/Y', strtotime($customer_stats['first_order_date'])) : null;
$customer_stats['last_order_date'] = $customer_stats['last_order_date'] ? date('d/m/Y', strtotime($customer_stats['last_order_date'])) : null;

// Return combined response
echo json_encode([
    'customer_stats' => $customer_stats,
    'sales_history' => $sales_history
]); 