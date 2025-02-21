<!DOCTYPE php>
<php lang="vi">
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
            width: 100%;
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

        .register-container {
            width: 300px;
            margin: 100px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: none;
        }

        .register-container input {
            width: 100%;
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
    <!-- Form đăng nhập -->
    <div class="login-container">
        <h2 style="color:#f2f6f3;">Đăng nhập</h2>
        <input type="text" id="username" placeholder="Tên đăng nhập" required>
        <input type="password" id="password" placeholder="Mật khẩu" required>
        <button onclick="login()">Đăng nhập</button>
        <p id="error-message" class="error-message"></p>
        <p class="back-to-login" onclick="showRegister()" style="color:#f2f6f3;" >Chưa có tài khoản? Đăng ký ngay</p>
    </div>

    <!-- Form đăng ký -->
    <div class="register-container">
        <h2 style="color:#f2f6f3; text-align: center;">Đăng ký</h2>
        <input type="text" id="newUsername" placeholder="Tên đăng nhập" required>
        <input type="password" id="newPassword" placeholder="Mật khẩu" required>
        <input type="password" id="confirmPassword" placeholder="Xác nhận mật khẩu" required>
        <button onclick="register()">Đăng ký</button>
        <p class="back-to-login" onclick="showLogin()" style="color:#f2f6f3;">Quay lại trang đăng nhập</p>
    </div>

    <div id="mainPage" class="main-page" style="display: none;">
        <h1 style="color:#f2f6f3;">Chào mừng, <span id="userDisplayName"></span>!</h1>
    </div>

    <script>
        function login() {
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const errorMessage = document.getElementById('error-message');

            if (username === "" || password === "") {
                errorMessage.textContent = "Vui lòng nhập đầy đủ thông tin!";
            } else {
                const storedUsername = localStorage.getItem(username);
                const storedPassword = localStorage.getItem(username + "_password");
         
                if (storedPassword !== password) {
                    errorMessage.textContent = "Sai mật khẩu!";
                } else {
                    errorMessage.textContent = "";
                    localStorage.setItem('loggedInUser', username);

                    document.querySelector('.login-container').style.display = 'none';
                    document.getElementById('mainPage').style.display = 'block';
                    document.getElementById('userDisplayName').textContent = username;

                    setTimeout(function() {
                        window.location.href = 'index.php'; 
                    }, 1000);
                }
            }
        }

        function register() {
            const newUsername = document.getElementById('newUsername').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const errorMessage = document.getElementById('error-message');

            if (newUsername === "" || newPassword === "" || confirmPassword === "") {
                errorMessage.textContent = "Vui lòng nhập đầy đủ thông tin!";
            } else if (newPassword !== confirmPassword) {
                errorMessage.textContent = "Mật khẩu xác nhận không khớp!";
            } else {
                localStorage.setItem(newUsername, newUsername);
                localStorage.setItem(newUsername + "_password", newPassword);
                alert("Đăng ký thành công! Bạn có thể đăng nhập ngay.");
                showLogin(); // Quay lại màn hình đăng nhập
            }
        }

        function showRegister() {
            document.querySelector('.login-container').style.display = 'none';
            document.querySelector('.register-container').style.display = 'block';
        }

        function showLogin() {
            document.querySelector('.login-container').style.display = 'block';
            document.querySelector('.register-container').style.display = 'none';
        }
    </script>
</body>
</php>
