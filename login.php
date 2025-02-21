<!DOCTYPE php>
<php lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chính</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }

        .user-info {
            font-size: 20px;
            margin-bottom: 20px;
        }

        .welcome {
            font-size: 24px;
            color: #28a745;
        }

        .logout-btn {
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="user-info">
        <h1 class="welcome">Chào mừng, <span id="userDisplayName"></span>!</h1>
        <button class="logout-btn" onclick="logout()">Đăng xuất</button>
    </div>

    <script>
        // Lấy tên người dùng từ localStorage
        const loggedInUser = localStorage.getItem('loggedInUser');

        // Kiểm tra nếu có người dùng đã đăng nhập, hiển thị tên
        if (loggedInUser) {
            document.getElementById('userDisplayName').textContent = loggedInUser;
        } else {
            // Nếu không có người dùng, chuyển hướng về trang đăng nhập
            window.location.href = 'login.php';
        }

        // Hàm đăng xuất
        function logout() {
            // Xóa thông tin người dùng khỏi localStorage
            localStorage.removeItem('loggedInUser');

            // Chuyển hướng về trang đăng nhập
            window.location.href = 'login.php';
        }
    </script>
</body>
</php>
