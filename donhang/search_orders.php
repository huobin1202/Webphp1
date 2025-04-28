<?php
session_start();
include('../database.php');

// Lấy các tham số tìm kiếm từ POST
$search = isset($_POST['search']) ? $_POST['search'] : '';
$status = isset($_POST['status']) ? $_POST['status'] : '';
$city = isset($_POST['city']) ? $_POST['city'] : '';
$district = isset($_POST['district']) ? $_POST['district'] : '';
$ward = isset($_POST['ward']) ? $_POST['ward'] : '';
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';

// Xây dựng câu truy vấn SQL
$sql = "SELECT orders.*, customer.name AS customer_name 
        FROM orders 
        INNER JOIN customer ON orders.customer_id = customer.id 
        WHERE 1=1";

$params = array();
$types = '';

// Thêm điều kiện tìm kiếm
if (!empty($search)) {
    $search = '%' . $search . '%';
    $sql .= " AND (orders.id LIKE ? OR customer.id LIKE ? OR customer.name LIKE ? OR orders.recipient_name LIKE ? OR orders.recipient_phone LIKE ?)";
    $types .= 'sssss';
    array_push($params, $search, $search, $search, $search, $search);
}

if (!empty($status) && $status != '4') {
    $sql .= " AND orders.status = ?";
    $types .= 's';
    array_push($params, $status);
}

if (!empty($city)) {
    $sql .= " AND orders.city_code = ?";
    $types .= 's';
    array_push($params, $city);
}

if (!empty($district)) {
    $sql .= " AND orders.district_code = ?";
    $types .= 's';
    array_push($params, $district);
}

if (!empty($ward)) {
    $sql .= " AND orders.ward_code = ?";
    $types .= 's';
    array_push($params, $ward);
}

if (!empty($start_date)) {
    $sql .= " AND orders.created_at >= ?";
    $types .= 's';
    array_push($params, $start_date);
}

if (!empty($end_date)) {
    $sql .= " AND orders.created_at <= ?";
    $types .= 's';
    array_push($params, $end_date);
}

$sql .= " ORDER BY orders.created_at DESC";

// Chuẩn bị và thực thi câu truy vấn
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Hiển thị kết quả
if ($result->num_rows > 0) {
    while ($order = $result->fetch_assoc()) {
        // Lấy chi tiết sản phẩm
        $details_sql = "SELECT order_details.*, products.tensp, products.hinhanh 
                       FROM order_details
                       INNER JOIN products ON order_details.product_id = products.id
                       WHERE order_details.order_id = ?";
        $details_stmt = $conn->prepare($details_sql);
        $details_stmt->bind_param("i", $order['id']);
        $details_stmt->execute();
        $details_result = $details_stmt->get_result();
        $details = [];
        while ($detail = $details_result->fetch_assoc()) {
            $details[] = $detail;
        }
        $order['details'] = $details;
        
        // Hiển thị hàng trong bảng
        echo '<tr>';
        echo '<td>' . $order['id'] . '</td>';
        echo '<td>' . str_pad($order['customer_id'], 2, '0', STR_PAD_LEFT) . '</td>';
        echo '<td>' . htmlspecialchars($order['customer_name']) . '</td>';
        echo '<td>' . date('d/m/Y', strtotime($order['created_at'])) . '</td>';
        echo '<td>' . number_format($order['total'], 0, ',', '.') . 'đ</td>';
        echo '<td>';
        echo '<form method="POST" action="" class="status-form">';
        echo '<input type="hidden" name="order_id" value="' . $order['id'] . '">';
        echo '<select name="new_status" onchange="this.form.submit()">';
        echo '<option value="chuaxuly" ' . ($order['status'] == 'chuaxuly' ? 'selected' : '') . '>Chưa xử lý</option>';
        echo '<option value="daxuly" ' . ($order['status'] == 'daxuly' ? 'selected' : '') . '>Đã xử lý</option>';
        echo '<option value="dahuy" ' . ($order['status'] == 'dahuy' ? 'selected' : '') . '>Đã hủy</option>';
        echo '<option value="dagiao" ' . ($order['status'] == 'dagiao' ? 'selected' : '') . '>Đã giao</option>';
        echo '</select>';
        echo '</form>';
        echo '</td>';
        echo '<td class="control">';
        echo '<form method="GET" action="" style="display: inline;">';
        echo '<input type="hidden" name="view_order" value="' . $order['id'] . '">';
        echo '<button type="submit" class="btn-detail">';
        echo '<i class="fa-regular fa-eye"></i> Chi tiết';
        echo '</button>';
        echo '</form>';
        echo '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="7" class="no-products">Không có đơn hàng nào!</td></tr>';
}
?> 