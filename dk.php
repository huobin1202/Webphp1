<?php
session_name('user_session');

session_start(); // Bắt đầu session

include('database.php');

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['register'])) {
        $name = trim($_POST['name']);
        $contact = trim($_POST['contact']);
        $email = trim($_POST['email']);
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
                $sql = "INSERT INTO customer (name, contact, joindate, status, password, email, role) 
                VALUES (?, ?, NOW(), '1', ?, ?, 'user')";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssss", $name, $contact, $password, $email);


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
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kawakaki </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #e5f6fa, #008e2c);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .nav-buttons {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
        }

        .nav-buttons a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            color: #139b3a;
            text-decoration: none;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .nav-buttons a:hover {
            transform: translateX(-3px);
            background: #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .register-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 450px;
            position: relative;
            overflow: hidden;
            animation: fadeIn 0.5s ease-out;
        }

        .register-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #139b3a, #0d6e2b);
        }

        .register-container h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-weight: 600;
            position: relative;
        }

        .register-container h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: #139b3a;
            border-radius: 3px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #f9f9f9;
        }

        .form-group input:focus {
            border-color: #139b3a;
            box-shadow: 0 0 0 3px rgba(19, 155, 58, 0.1);
            outline: none;
        }

        .form-group i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-group i:hover {
            color: #139b3a;
        }

        .form-group .toggle-password {
            right: 40px;
        }

        button[name="register"] {
            width: 100%;
            padding: 12px;
            background: #139b3a;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        button[name="register"]:hover {
            background: #0d6e2b;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(19, 155, 58, 0.3);
        }

        .back-to-login {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }

        .back-to-login a {
            color: #139b3a;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .back-to-login a:hover {
            color: #0d6e2b;
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .error-message {
            background: #ffebee;
            color: #c62828;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .password-requirements {
            margin-top: 5px;
            font-size: 12px;
            color: #666;
            padding: 5px;
            background: #f5f5f5;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="nav-buttons">
        <a href="index.php"><i class="fas fa-arrow-left"></i></a>
    </div>
    <?php include('toast.php'); ?>
    <div class="register-container">
        <h2>Đăng ký</h2>
        <form method="post">
            <div class="form-group">
                <input type="text" id="newUsername" placeholder="Tên đăng nhập" name="name" maxlength="20" required>
                <i class="fas fa-user"></i>
            </div>
            <div class="form-group">
                <input type="text" id="newContact" placeholder="Số điện thoại" name="contact" maxlength="11" required>
                <i class="fas fa-phone"></i>
            </div>
            <div class="form-group">
                <input type="email" id="newEmail" placeholder="Email" name="email" maxlength="30" required>
                <i class="fas fa-envelope"></i>
            </div>
            <div class="form-group">
                <input type="password" id="newPassword" placeholder="Mật khẩu" name="password" maxlength="20" required>
                <i class="fas fa-lock"></i>
                <i class="fas fa-eye toggle-password" onclick="togglePassword('newPassword')"></i>
            </div>
            <div class="form-group">
                <input type="password" id="confirmPassword" placeholder="Xác nhận mật khẩu" name="password_confirm" maxlength="20" required>
                <i class="fas fa-lock"></i>
                <i class="fas fa-eye toggle-password" onclick="togglePassword('confirmPassword')"></i>
            </div>
            <button name="register">Đăng ký</button>
        </form>
        <p class="back-to-login">Đã có tài khoản? <a href="dn.php">Đăng nhập ngay!</a></p>
    </div>

    <script>
        function togglePassword(inputId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = passwordInput.parentElement.querySelector('.toggle-password');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>