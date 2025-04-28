<?php
include('../database.php');
header('Content-Type: application/json');

// Lấy danh sách tỉnh/thành phố từ API
$url = 'https://open.oapi.vn/location/provinces?page=0&size=30';
$api_response = file_get_contents($url);
$api_data = json_decode($api_response, true);

// Lấy danh sách tỉnh/thành phố từ database để lọc
$query = $conn->query("SELECT DISTINCT city_code, city_name FROM customer WHERE city_code IS NOT NULL AND city_name IS NOT NULL");
$db_provinces = [];
while ($row = $query->fetch_assoc()) {
    $db_provinces[$row['city_code']] = $row['city_name'];
}

// Lọc và chuyển đổi dữ liệu
$provinces = array_map(function($province) {
    return [
        'code' => $province['id'],
        'name' => $province['name']
    ];
}, $api_data['data']);

echo json_encode($provinces);
?> 