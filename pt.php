<!DOCTYPE php>
<php lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phương Thức Thanh Toán</title>
    <style>
        /* Định dạng trang */
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            margin-left: 400px;
            margin-right: 400px;
        }

        h1 {
            text-align: center;
        }

        .form-section {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .form-section label {
            display: block;
            margin-bottom: 10px;
        }

        .form-section input, .form-section select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn-submit {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-submit:hover {
            background-color: #45a049;
        }

        /* Ẩn các phương thức thanh toán ban đầu */
        .payment-method {
            display: none;
        }
    </style>
</head>
<body>
    <h1>Chọn Phương Thức Thanh Toán</h1>

    <div class="form-section">
        <label for="paymentOption">Chọn phương thức thanh toán:</label>
        <select id="paymentOption" onchange="togglePaymentMethod()">
            <option value="cash">Thanh toán bằng tiền mặt</option>
            <option value="bankTransfer">Chuyển khoản ngân hàng</option>
            <option value="creditCard">Thanh toán bằng thẻ tín dụng</option>
        </select>
    </div>

    <!-- Phương thức thanh toán bằng tiền mặt -->
    <div id="cashMethod" class="form-section payment-method">
        <h3>Thanh Toán Bằng Tiền Mặt</h3>
        <p>Bạn sẽ thanh toán trực tiếp khi nhận hàng. Vui lòng chuẩn bị số tiền chính xác khi giao hàng.</p>
    </div>

    <!-- Phương thức thanh toán chuyển khoản -->
    <div id="bankTransferMethod" class="form-section payment-method">
        <h3>Chuyển Khoản Ngân Hàng</h3>
        <p>Thông tin tài khoản chuyển khoản:</p>
        <label for="accountName">Tên tài khoản:</label>
        <input type="text" id="accountName" value="Le Pham Huu Binh" disabled>

        <label for="accountNumber">Số tài khoản:</label>
        <input type="text" id="accountNumber" value="1234567890" disabled>

        <label for="bankName">Ngân hàng:</label>
        <input type="text" id="bankName" value="Ngân hàng Sacombank" disabled>

        <p>Vui lòng ghi rõ mã đơn hàng trong nội dung chuyển khoản để chúng tôi xử lý đơn hàng nhanh chóng.</p>
    </div>

    <!-- Phương thức thanh toán bằng thẻ tín dụng -->
    <div id="creditCardMethod" class="form-section payment-method">
        <h3>Thanh Toán Bằng Thẻ Tín Dụng</h3>
        <label for="cardNumber">Số thẻ:</label>
        <input type="text" id="cardNumber" placeholder="Nhập số thẻ tín dụng">

        <label for="cardHolder">Tên chủ thẻ:</label>
        <input type="text" id="cardHolder" placeholder="Nhập tên chủ thẻ">

        <label for="expiryDate">Ngày hết hạn:</label>
        <input type="month" id="expiryDate">

        <label for="cvv">CVV:</label>
        <input type="text" id="cvv" placeholder="Nhập mã CVV (3 chữ số phía sau thẻ)">
    </div>

    <a href="thanhtoantc.php"></a> <button class="btn-submit" onclick="confirmPayment()">Xác nhận thanh toán</button></a>

    <script>
        // Hiển thị phương thức thanh toán tương ứng
        function togglePaymentMethod() {
            const paymentOption = document.getElementById('paymentOption').value;

            // Ẩn tất cả các phương thức thanh toán trước khi hiển thị phương thức được chọn
            document.getElementById('cashMethod').style.display = 'none';
            document.getElementById('bankTransferMethod').style.display = 'none';
            document.getElementById('creditCardMethod').style.display = 'none';

            if (paymentOption === 'cash') {
                document.getElementById('cashMethod').style.display = 'block';
            } else if (paymentOption === 'bankTransfer') {
                document.getElementById('bankTransferMethod').style.display = 'block';
            } else if (paymentOption === 'creditCard') {
                document.getElementById('creditCardMethod').style.display = 'block';
            }
        }

        // Xử lý khi xác nhận thanh toán
        function confirmPayment() {
            const paymentOption = document.getElementById('paymentOption').value;

            if (paymentOption === 'cash') {
                alert("Bạn đã chọn thanh toán bằng tiền mặt khi nhận hàng.");
            } else if (paymentOption === 'bankTransfer') {
                alert("Vui lòng chuyển khoản theo thông tin chúng tôi đã cung cấp.");
            } else if (paymentOption === 'creditCard') {
                const cardNumber = document.getElementById('cardNumber').value;
                const cardHolder = document.getElementById('cardHolder').value;
                const expiryDate = document.getElementById('expiryDate').value;
                const cvv = document.getElementById('cvv').value;

                if (cardNumber && cardHolder && expiryDate && cvv) {
                    alert("Thanh toán thẻ tín dụng thành công!");
                } else {
                    alert("Vui lòng điền đầy đủ thông tin thẻ tín dụng.");
                }
            }
        }

        // Hiển thị mặc định phương thức thanh toán đầu tiên (tiền mặt)
        window.onload = function() {
            togglePaymentMethod();
        };
    </script>
</body>
</php>
