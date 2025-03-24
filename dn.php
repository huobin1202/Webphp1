<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-size: cover;
            background-position-y: -170px;
            background-image: url(image/bg.jpeg);
        }

        .login-container {
            width: 300px;
            margin: 100px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background: white;
        }

        .login-container h2 {
            text-align: center;
        }

        .login-container input {
            width: 93%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .login-container button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .login-container button:hover {
            background-color: #218838;
        }

        .error-message {
            color: red;
            text-align: center;
        }

        .main-page {
            text-align: center;
            margin-top: 50px;
        }
    </style>
</head>
<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $password = trim($_POST['password']);

    $servername = "localhost";
    $username = "root";
    $password_db = "";
    $dbname = "admindoan";

    $conn = new mysqli($servername, $username, $password_db, $dbname);

    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    $error_message = "";

    // Prepared statement to get user id, status
    $stmt = $conn->prepare("SELECT id, status FROM customer WHERE name = ? AND password = ?");
    $stmt->bind_param("ss", $name, $password);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($user_id, $status);
        $stmt->fetch();

        if ($status == 0) {
            $error_message = "Tài khoản của bạn đã bị khóa!";
        } else {
            session_unset();
            session_destroy();
            session_start();

            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $name;
            header("Location: index.php");
            exit();
        }
    } else {
        $error_message = "Tên đăng nhập hoặc mật khẩu không đúng!";
    }

    $stmt->close();
    $conn->close();
}
?>


<body>
    <form method="post">

        <div class="login-container">
            <h2 style="">Đăng nhập</h2>
            <?php if (!empty($error_message)): ?>
                <p style="color: red; text-align:center;"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <input type="text" id="username" name="name" placeholder="Tên đăng nhập" required maxlength="20">
            <input type="password" id="password" name="password" placeholder="Mật khẩu" maxlength="20" required>
            <button type="submit" name="login">Đăng nhập</button>
            <p id="error-message" class="error-message"></p>
            <p class="back-to-login" style="color:#28a745; text-align:center">Chưa có tài khoản? <a href=dk.php style="color:#28a745;  "> Đăng ký ngay!</a></p>

        </div>
    </form>
</body>

</html>