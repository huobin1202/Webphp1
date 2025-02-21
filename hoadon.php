<!DOCTYPE php>
<php lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa Đơn</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
            height: 100vh;
            background-image: url(https://gstatic.gvn360.com/2023/05/ZX10R_-1-1920x1080.jpg);
            background-size: cover;
            background-position-y: -100px;
        }
        .container {
            max-width: 650px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h1 {
            color: #333;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .total {
            margin-top: 20px;
            font-weight: bold;
            font-size: 18px;
            text-align: right;
        }
        .btn-home, .btn-clear-cart {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }
        .btn-home:hover, .btn-clear-cart:hover {
            background-color: #45a049;
        }
        img.product-image {
            width: 50px;
            height: auto;
            margin-right: 10px;
            vertical-align: middle;
        }
        .btn-delete {
            background-color: rgb(10, 0, 0);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-delete:hover {
            background-color: rgb(20, 19, 19);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hóa Đơn</h1>

        <table>
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Tổng cộng</th>
                    <th>Yêu cầu</th>
                </tr>
            </thead>
            <tbody id="cartItems">
                <!-- Sản phẩm sẽ được hiển thị bằng JavaScript -->
            </tbody>
        </table>

        <div class="total">
            Tổng tiền: <span id="totalAmount">0đ</span>
        </div>

        <a href="index.php" class="btn-home">Quay về trang chủ</a>
    </div>

    <script>
        // Hàm hiển thị giỏ hàng
        function displayCart() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const cartItemsContainer = document.getElementById('cartItems');
            const totalAmount = document.getElementById('totalAmount');
            let total = 0;

            // Xóa các sản phẩm cũ trước khi hiển thị lại
            cartItemsContainer.innerHTML = '';

            cart.forEach((item, index) => {
                const itemTotal = item.price * item.quantity;
                total += itemTotal;

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <img src="${item.image}" class="product-image" alt="${item.name}"> ${item.name}
                    </td>
                    <td>${item.price} đ</td>
                    <td>${item.quantity}</td>
                    <td>${itemTotal} đ</td>
                    <td><button class="btn-delete" onclick="deleteItem(${index})">Hủy đơn</button></td>
                `;
                cartItemsContainer.appendChild(row);
            });

            totalAmount.innerText = total.toLocaleString() + ' đ';
        }

        // Hàm xóa một sản phẩm khỏi giỏ hàng
        function deleteItem(index) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];

            // Xóa sản phẩm tại chỉ mục index
            cart.splice(index, 1);

            // Cập nhật lại giỏ hàng trong localStorage
            localStorage.setItem('cart', JSON.stringify(cart));

            // Hiển thị lại giỏ hàng sau khi xóa sản phẩm
            displayCart();
        }

        // Gọi hàm hiển thị giỏ hàng khi trang được tải
        window.onload = displayCart;
    </script>
</body>
</php>
