<?php
include('../database.php');
header('Content-Type: application/json');

if (isset($_GET['district_code'])) {
    $district_code = $_GET['district_code'];
    
    // Lấy danh sách xã/phường từ API
    $url = "https://open.oapi.vn/location/wards/{$district_code}?page=0&size=30";
    $api_response = file_get_contents($url);
    $api_data = json_decode($api_response, true);
    
    // Lấy danh sách xã/phường từ database để lọc
    $query = $conn->prepare("SELECT DISTINCT ward_code, ward_name FROM customer WHERE district_code = ? AND ward_code IS NOT NULL AND ward_name IS NOT NULL");
    $query->bind_param("s", $district_code);
    $query->execute();
    $result = $query->get_result();
    
    $db_wards = [];
    while ($row = $result->fetch_assoc()) {
        $db_wards[$row['ward_code']] = $row['ward_name'];
    }
    
    // Lọc và chuyển đổi dữ liệu
    $wards = array_map(function($ward) {
        return [
            'code' => $ward['id'],
            'name' => $ward['name']
        ];
    }, $api_data['data']);
    
    $response = [
        'wards' => $wards
    ];
    
    echo json_encode($response);
}
?> 