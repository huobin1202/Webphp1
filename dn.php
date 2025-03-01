

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
    $servername="localhost";
    $username="root";
    $password="";
    $dbname="admindoan";

    $conn=new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Kết nối thất bại" . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['login'])) {
            $name = trim($_POST['name']);
            $input_password = trim($_POST['password']); // Đổi tên biến
            
            $sql = "SELECT id, password FROM customer WHERE name = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $name);
            $stmt->execute();
            $stmt->store_result();
    
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id, $hashed_password);
                $stmt->fetch();
                if (password_verify($input_password, $hashed_password)) { // Sử dụng biến mới
                    // Xóa session cũ nếu có
                    session_unset();
                    session_destroy();
                    session_start(); // Bắt đầu session mới
                    
                    $_SESSION['user_id'] = $id;
                    $_SESSION['username'] = $name;
                    
                    header("Location: index.php");
                    exit();
                } else {
                    echo "<script>alert('Sai mật khẩu!');</script>";
                }
            } else {
                echo "<script>alert('Tài khoản không tồn tại!');</script>";
            }
            $stmt->close();
        }
    }
    
    ?>


        <body>
        <form method="post">

            <div class="login-container">
                    <h2 style="">Đăng nhập</h2>
                    <input type="text" id="username" name="name" placeholder="Tên đăng nhập" required>
                    <input type="password" id="password" name="password" placeholder="Mật khẩu" required>
                    <button type="submit" name="login">Đăng nhập</button>
                    <p id="error-message" class="error-message"></p>
                    <p class="back-to-login"style="color:#28a745; text-align:center" >Chưa có tài khoản? <a href=dk.php style="color:#28a745;  " > Đăng ký ngay!</a></p>

            </div>
        </form>



            <div id="mainPage" class="main-page" style="display: none;">
                <h1 style="color:#f2f6f3;">Chào mừng, <span id="userDisplayName"></span>!</h1>
            </div>

        </body>
</html>
