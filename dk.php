<?php
session_start(); // Bắt đầu session

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
        $name = trim($_POST['name']);
        $contact = trim($_POST['contact']);
        $email = trim($_POST['email']);
        $address = trim($_POST['address']);
        $password = trim($_POST['password']);
        $password_confirm = trim($_POST['password_confirm']);

        if ($password !== $password_confirm) {
            $_SESSION['error'] = "Mật khẩu xác nhận không khớp!";
        } else {
            // Kiểm tra xem tài khoản hoặc số điện thoại đã tồn tại chưa
            $check_sql = "SELECT id FROM customer WHERE name = ? OR contact = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("ss", $name, $contact);
            $check_stmt->execute();
            $result = $check_stmt->get_result();

            if ($result->num_rows > 0) {
                $_SESSION['error'] = "Tài khoản hoặc số điện thoại đã tồn tại!";
            } else {
                // Thêm tài khoản mới vào CSDL
                $sql = "INSERT INTO customer (name, contact, joindate, status, password, email, address, role) 
                VALUES (?, ?, NOW(), '1', ?, ?, ?, 'user')";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssss", $name, $contact, $password, $email, $address);


                if ($stmt->execute()) {
                    // Lấy ID vừa tạo
                    $user_id = $stmt->insert_id;

                    // Lưu thông tin vào session
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['username'] = $name;
                    $_SESSION['success'] = "Tạo tài khoản thành công!";


                    // Chuyển hướng về trang index.php
                    header("Location: index.php");
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
            background: white;
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
        .nav-buttons {
            margin-top: 10px;
        }
        .nav-buttons a {
            display: flex;
            padding: 10px;
            margin: 5px;
            text-decoration: none;
            color: black;
            background-color: #139b3a;
            color:white;
            border-radius: 5px;
            position: sticky;
            right:100%;
            height:30px;
            width:50px;
            text-align: center;
            align-items: center;
            justify-content: center;

        }
        .nav-buttons a:hover {
            background-color:darkgreen;
            color:white
        }
    </style>
</head>

<body>

    <?php include('toast.php'); ?>
    <div class="nav-buttons">
                <a href="index.php">&#10094;</a>
            </div>
    <div class="register-container">
        <h2 style="text-align: center;">Đăng ký</h2>
        <form method="post">
            <input type="text" id="newUsername" placeholder="Tên đăng nhập" name="name" maxlength="20" required>
            <input type="text" id="newContact" placeholder="Số điện thoại" name="contact" maxlength="11" required>
            <input type="email" id="newEmail" placeholder="Email" name="email" maxlength="40" required>
            <input type="text" id="newAddress" placeholder="Địa chỉ" name="address" maxlength="40" required>

            <input type="password" id="newPassword" placeholder="Mật khẩu" name="password" maxlength="20" required>
            <input type="password" id="confirmPassword" placeholder="Xác nhận mật khẩu" name="password_confirm" maxlength="20" required>
            <button name="register">Đăng ký</button>
        </form>
        <p class="back-to-login" style="color:#28a745; text-align:center">Quay lại trang <a href=dn.php style="color:#28a745;  "> đăng nhập!</a></p>
    </div>
</body>

</html>