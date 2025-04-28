<?php
session_start();
include('../database.php');

// Kiểm tra và lấy mã tỉnh/thành phố từ tham số
$province_code = isset($_GET['province_code']) ? $_GET['province_code'] : '';

if (empty($province_code)) {
    echo json_encode(array('districts' => array()));
    exit();
}

// Lấy danh sách quận/huyện từ bảng customer
$sql = "SELECT DISTINCT district_code, district_name 
        FROM customer 
        WHERE city_code = ? 
        AND district_code IS NOT NULL 
        AND district_name IS NOT NULL 
        AND district_code != '' 
        AND district_name != ''
        ORDER BY district_name";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $province_code);
$stmt->execute();
$result = $stmt->get_result();

$districts = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $districts[] = array(
            'code' => $row['district_code'],
            'name' => $row['district_name']
        );
    }
}

// Trả về dữ liệu dưới dạng JSON
header('Content-Type: application/json');
echo json_encode(array('districts' => $districts));
?> 