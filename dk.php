<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "admindoan";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['register'])) {
        $name = htmlspecialchars($_POST['name']);
        $contact = htmlspecialchars($_POST['contact']);
        $password = htmlspecialchars($_POST['password']);
        $password_confirm = htmlspecialchars($_POST['password_confirm']);

        if ($password !== $password_confirm) {
            $error_message = "Mật khẩu xác nhận không khớp!";
        } else {
            // Kiểm tra xem tài khoản hoặc số điện thoại đã tồn tại chưa
            $check_sql = "SELECT * FROM customer WHERE name = ? OR contact = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("ss", $name, $contact);
            $check_stmt->execute();
            $result = $check_stmt->get_result();
            
            if ($result->num_rows > 0) {
                $error_message = "Tài khoản hoặc số điện thoại đã tồn tại, vui lòng chọn thông tin khác!";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO customer (name, contact, joindate, status, password) VALUES (?, ?, NOW(), 'active', ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $name, $contact, $hashedPassword);
                
                if ($stmt->execute()) {
                    header("Location: index.php"); // Chuyển hướng đến trang index.php
                    exit();
                } else {
                    $error_message = "Lỗi: " . $stmt->error;
                }
                $stmt->close();
            }
            $check_stmt->close();
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-size: cover;
            background-position-y: -170px;
            background-image: url(image/bg.jpeg);
        }
        .register-container {
            width: 300px;
            margin: 100px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .error-message {
            color: red;
            text-align: center;
        }
        .register-container input {
            width: 92%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .register-container button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .register-container button:hover {
            background-color: #218838;
        }
        .back-to-login {
            display: block;
            text-align: center;
            margin-top: 10px;
            cursor: pointer;
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2 style="text-align: center;">Đăng ký</h2>
        <?php if ($error_message): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form method="post">
            <input type="text" id="newUsername" placeholder="Tên đăng nhập" name="name" required>
            <input type="text" id="newContact" placeholder="Số điện thoại" name="contact" required>
            <input type="password" id="newPassword" placeholder="Mật khẩu" name="password" required>
            <input type="password" id="confirmPassword" placeholder="Xác nhận mật khẩu" name="password_confirm" required>
            <button name="register">Đăng ký</button>
        </form>
        <p class="back-to-login" onclick="window.location.href='dn.php'">Quay lại trang đăng nhập</p>
    </div>
</body>
</html>
