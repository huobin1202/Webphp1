<!DOCTYPE php>
<php lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="img/logo.png" rel="icon" type="image/x-icon" />
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Đăng nhập</title>
</head>
<body>
    <style>
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
    <div class="login-container">
        <h2>Đăng nhập vào hệ thống</h2>
        <form id="loginForm" onsubmit="login(event)">
            <label for="username">Tên đăng nhập:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Mật khẩu:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Đăng nhập</button>
        </form>
    </div>

    <script>
        function login(event) {
            event.preventDefault();

            const username = document.getElementById("username").value;
            const password = document.getElementById("password").value;// Kiểm tra thông tin đăng nhập (ở đây chỉ kiểm tra với một tài khoản giả)
            if (username === "admin" && password === "admin123") {
                // Lưu thông tin đăng nhập vào localStorage
                localStorage.setItem("isLoggedIn", "true");
                localStorage.setItem("username", username);
                window.location.href = "admin.php";  // Chuyển hướng đến trang admin
            } else {
                alert("Tên đăng nhập hoặc mật khẩu sai.");
            }
        }
    </script>
</body>
</php>