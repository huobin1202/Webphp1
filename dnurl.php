<?php
session_start();
include('database.php');
include('toast.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        echo "<script>alert('Vui lòng nhập đầy đủ thông tin!'); window.location='dnurl.php';</script>";
        exit();
    }

    $sql = "SELECT * FROM customer WHERE name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        if ($password == $user['password']) {
            if ($user['role'] == 'admin') {
                $_SESSION['username'] = $user['name'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['id'] = $user['id'];
                header("Location: admin.php");
                exit();
            } else {
                $_SESSION['error'] = "Tài khoản này không có quyền truy cập!";
            header("Location: dnurl.php");
            exit();
            }
        } else {
            $_SESSION['error'] = "Tài khoản hoặc mật khẩu không chính xác!";
            header("Location: dnurl.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Tài khoản hoặc mật khẩu không chính xác!";
        header("Location: dnurl.php");
        exit();
    }
    
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE php>
<php lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="image/logo.png" rel="icon" type="image/x-icon" />
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Đăng nhập</title>
    <style>
        /* Thêm style cho nút quay về */
        .back-button {
            position: fixed;
            top: 20px;
            left: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .back-button:hover {
            background-color: #45a049;
        }

        /* Reset một số kiểu mặc định của trình duyệt */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Đặt nền cho toàn bộ trang */
body {
    font-family: Arial, sans-serif;
    background: #f7f7f7;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* Căn giữa nội dung của trang đăng nhập */
.login-container {
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 400px;
}

/* Tiêu đề của form */
.login-container h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
}

/* Các label trong form */
form label {
    display: block;
    margin: 10px 0 5px;
    font-size: 14px;
    color: #555;
}

/* Các input trong form */
form input {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    color: #333;
}

/* Các nút bấm */
button {
    width: 100%;
    padding: 12px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}

button:hover {
    background-color: #45a049;
}

/* Đặt hiệu ứng hover cho các input */
input:focus, button:focus {
    outline: none;
    border-color: #4CAF50;
}

/* Thông báo lỗi nếu có */
.error-message {
    color: red;
    font-size: 12px;
    text-align: center;
    margin-top: 10px;
}

/* Hiệu ứng chuyển động khi tải trang */
@keyframes fadeIn {
    0% {
        opacity: 0;
        transform: translateY(-20px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

.login-container {
    animation: fadeIn 0.5s ease-in-out;
}

    </style>
</head>
<body>
    <!-- Thêm nút quay về -->
    <a href="index.php" class="back-button">← Quay về trang chủ</a>

    <div class="login-container">
        <h2>Đăng nhập vào hệ thống</h2>
        <form id="loginForm" method="POST">
            <label for="username">Tên đăng nhập:</label>
            <input type="text" id="username" name="username" maxlength="20" required>

            <label for="password">Mật khẩu:</label>
            <input type="password" id="password" name="password" maxlength="20" required>

            <button type="submit">Đăng nhập</button>
        </form>
    </div>

  
</body>
</php>