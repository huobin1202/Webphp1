<!DOCTYPE php>
<php lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán Bằng Thẻ Tín Dụng</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="payment-form">
    <h2>Thanh Toán Thẻ Tín Dụng</h2>
    <form action="/submit-payment" method="post">
        <div class="input-group">
            <label for="card-name">Tên trên thẻ:</label>
            <input type="text" id="card-name" name="card-name" required placeholder="Nguyễn Văn A">
        </div>
        <div class="input-group">
            <label for="card-number">Số thẻ tín dụng:</label>
            <input type="number" id="card-number" name="card-number" required placeholder="1234 5678 9101 1121">
        </div>
        <div class="input-group">
            <div class="exp-date">
                <label for="exp-date">Ngày hết hạn:</label>
                <input type="text" id="exp-date" name="exp-date" required placeholder="MM/YY">
            </div>
            <div class="cvv">
                <label for="cvv">CVV:</label>
                <input type="number" id="cvv" name="cvv" required placeholder="123">
            </div>
        </div>
        <div class="input-group">
            <label for="billing-address">Địa chỉ thanh toán:</label>
            <input type="text" id="billing-address" name="billing-address" >
        </div>
        <a type="submit" class="btn" href="thanhtoantc.php">Thanh Toán</a>
    </form>
</div>
<style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0; /* Nền xám nhạt */
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.payment-form {
    background-color: white; /* Nền trắng */
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    max-width: 400px;
    width: 100%;
}

h2 {
    text-align: center;
    color: #4CAF50; /* Màu xanh lá */
    margin-bottom: 20px;
}

.input-group {
    margin-bottom: 15px;
}

.input-group label {
    display: block;
    margin-bottom: 5px;
    color: #333; /* Màu chữ đậm */
}

.input-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    font-size: 16px;
}

.input-group input[type="text"],
.input-group input[type="number"] {
    font-size: 16px;
}

.input-group .cvv {
    width: 30%;
    display: inline-block;
}

.input-group .exp-date {
    width: 65%;
    display: inline-block;
    margin-right: 5%;
}

.btn {
    width: 100%;
    padding: 10px;
    background-color: #4CAF50; /* Màu nền xanh lá */
    border: none;
    color: white; /* Màu chữ trắng */
    font-size: 16px;
    cursor: pointer;
    border-radius: 5px;
    text-align: center;
}

.btn:hover {
    background-color: #45a049; /* Xanh lá đậm hơn khi hover */
}

input:focus {
    outline: none;
    border-color: #4CAF50; /* Khi focus vào input sẽ có viền xanh lá */
}

</style>

</body>
</php>
