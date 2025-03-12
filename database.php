<?php 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "admindoan";

// Kết nối CSDL
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
if (!isset($_SESSION)) {
    session_start();
}
?>