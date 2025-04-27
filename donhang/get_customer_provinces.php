<?php
session_start();
include('../database.php');

// Lấy danh sách tỉnh/thành phố từ bảng customer
$sql = "SELECT DISTINCT city_code, city_name 
        FROM customer 
        WHERE city_code IS NOT NULL 
        AND city_name IS NOT NULL 
        AND city_code != '' 
        AND city_name != ''
        ORDER BY city_name";

$result = $conn->query($sql);
$provinces = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $provinces[] = array(
            'city_code' => $row['city_code'],
            'city_name' => $row['city_name']
        );
    }
}

// Trả về dữ liệu dưới dạng JSON
header('Content-Type: application/json');
echo json_encode($provinces);
?> 