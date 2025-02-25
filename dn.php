

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

    $servername="localhost" ;
    $username="root" ;
    $password="" ;
    $dbname="admindoan" ;

    $conn=new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
    die("Kết nối thất bại" . $conn->connect_error);
    }

   
    if($_SERVER["REQUEST_METHOD"] == "POST"){

    if (isset($_POST['login'])) {
        $name = htmlspecialchars($_POST['name']);
        $password = htmlspecialchars($_POST['password']);
        
        $sql = "SELECT id, password FROM customer WHERE name = ?";
        $result = $conn->prepare($sql);
        $result->bind_param("s", $name);
        $result->execute();
        $result->store_result();
        
        if ($result->num_rows > 0) {
            $result->bind_result($id, $hashed_password);
            $result->fetch();
            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $name;
                header("Location: index.php?name");
                exit();
            } else {
                echo "Sai mật khẩu!";
            }
        } else {
            echo "Tài khoản không tồn tại!";
        }
        $result->close();
    }
}
    ?>

    <body>
    <form method="post"

        <div class="login-container">
                <h2 style="color:#f2f6f3;">Đăng nhập</h2>
                <input type="text" id="username" name="name" placeholder="Tên đăng nhập" required>
                <input type="password" id="password" name="password" placeholder="Mật khẩu" required>
                <button type="submit" name="login">Đăng nhập</button>
                <p id="error-message" class="error-message"></p>
                <p class="back-to-login"style="color:#f2f6f3;">Chưa có tài khoản? <a href=dk.php> Đăng ký ngay!</a></p>

        </div>
        </form>



        <div id="mainPage" class="main-page" style="display: none;">
            <h1 style="color:#f2f6f3;">Chào mừng, <span id="userDisplayName"></span>!</h1>
        </div>

    </body>

    </html>