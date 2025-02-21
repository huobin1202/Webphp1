<!DOCTYPE php>
<php lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán Thành Công</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1>Thanh Toán Thành Công!</h1>
        <p class="message">Cảm ơn bạn đã mua hàng! Đơn hàng của bạn đã đặt.</p>
        
        <div class="order-info">
            <p><strong>Mã đơn hàng:</strong> </p>
            <p><strong>Ngày đặt hàng:</strong> </p>
            <p><strong>Tổng thanh toán:</strong></p>
        </div>

        <div class="actions">
            <a href="index.php" class="btn btn-primary">Quay lại trang chủ</a>
            <a href="hoadon.php" class="btn btn-secondary">Xem chi tiết đơn hàng</a>
        </div>
    </div>

    <!-- Thư viện Font Awesome cho biểu tượng -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Roboto', sans-serif;
    background-color: #f5f5f5;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.container {
    background-color: #fff;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 100%;
    max-width: 500px;
}

.success-icon {
    font-size: 50px;
    color: #28a745; /* Màu xanh thành công */
    margin-bottom: 20px;
}

h1 {
    font-size: 28px;
    font-weight: 500;
    color: #333;
    margin-bottom: 10px;
}

.message {
    font-size: 16px;
    color: #666;
    margin-bottom: 30px;
}

.order-info p {
    font-size: 16px;
    color: #333;
    margin-bottom: 10px;
}

.actions {
    margin-top: 30px;
}

.actions .btn {
    display: inline-block;
    padding: 12px 25px;
    border-radius: 5px;
    font-size: 16px;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.actions .btn-primary {
    background-color: #28a745;
    color: #fff;
    margin-right: 10px;
}

.actions .btn-primary:hover {
    background-color: #218838;
}

.actions .btn-secondary {
    background-color: #6c757d;
    color: #fff;
}

.actions .btn-secondary:hover {
    background-color: #5a6268;
}

    </style>
</body>
</php>
