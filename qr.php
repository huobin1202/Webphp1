<!DOCTYPE php>
<php lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Payment Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .payment-form {
            background-color: #f9f9f9;
            border: 2px solid #4CAF50;
            border-radius: 10px;
            padding: 20px;
            width: 600px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            height:500px;
        }
        .payment-form h2 {
            color: #4CAF50;
            text-align: center;
        }
        .qr-code {
            text-align: center;
            margin: 20px 0;
        }
        .qr-code img {
            width: 150px;
            height: 150px;
        }
        .input-group {
            margin-bottom: 15px;
        }
        .input-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .btn-submit {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-submit:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="payment-form">
    <h2>QR Code Payment</h2>

    <div class="qr-code">
        <img src="image/qr.jpg" alt="QR Code"style="height:400px;width: 400px;">
    </div>

    <!-- <div class="input-group">
        <label for="amount">Amount</label>
        <input type="text" id="amount" name="amount" placeholder="Enter amount">
    </div>

    <div class="input-group">
        <label for="payment-method">Payment Method</label>
        <input type="text" id="payment-method" name="payment-method" placeholder="Payment method">
    </div> -->

    <a class="btn-submit" href="thanhtoantc.php">Thanh toán</a>
</div>

</body>
</php>
