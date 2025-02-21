<!DOCTYPE php>
<php lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Địa chỉ giao hàng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .addresses {
            margin-top: 20px;
            text-align: left;
        }
        .address-item {
            padding: 10px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Nhập địa chỉ giao hàng</h1>
        <input type="text" id="addressInput" placeholder="Nhập địa chỉ mới">
        <button class="btn" onclick="saveAddress()">Lưu địa chỉ</button>

        <h2>Các địa chỉ đã lưu</h2>
        <div class="addresses" id="addressList">
            <!-- Danh sách địa chỉ sẽ được hiển thị tại đây -->
        </div>
    </div>

    <script>
        // Hàm lưu địa chỉ vào localStorage
        function saveAddress() {
            const addressInput = document.getElementById('addressInput').value;
            if (addressInput.trim() === "") {
                alert("Vui lòng nhập địa chỉ!");
                return;
            }

            // Lấy danh sách địa chỉ đã lưu trong localStorage hoặc tạo mảng rỗng nếu chưa có
            let savedAddresses = JSON.parse(localStorage.getItem('addresses')) || [];

            // Thêm địa chỉ mới vào mảng
            savedAddresses.push(addressInput);

            // Lưu lại vào localStorage
            localStorage.setItem('addresses', JSON.stringify(savedAddresses));

            // Xóa nội dung trong ô nhập liệu
            document.getElementById('addressInput').value = '';

            // Cập nhật danh sách địa chỉ
            displayAddresses();
        }

        // Hàm hiển thị danh sách địa chỉ đã lưu
        function displayAddresses() {
            const addressListContainer = document.getElementById('addressList');
            const savedAddresses = JSON.parse(localStorage.getItem('addresses')) || [];

            // Xóa danh sách cũ trước khi hiển thị
            addressListContainer.innerHTML = '';

            savedAddresses.forEach((address, index) => {
                const addressItem = document.createElement('div');
                addressItem.classList.add('address-item');
                addressItem.innerHTML = `
                    ${address} 
                    <button class="btn" style="background-color: red; float: right;" onclick="deleteAddress(${index})">Xóa</button>
                `;
                addressListContainer.appendChild(addressItem);
            });
        }

        // Hàm xóa một địa chỉ khỏi danh sách
        function deleteAddress(index) {
            let savedAddresses = JSON.parse(localStorage.getItem('addresses')) || [];
            
            // Xóa địa chỉ tại vị trí index
            savedAddresses.splice(index, 1);

            // Lưu lại danh sách địa chỉ sau khi xóa
            localStorage.setItem('addresses', JSON.stringify(savedAddresses));

            // Cập nhật lại danh sách địa chỉ hiển thị
            displayAddresses();
        }

        // Gọi hàm hiển thị danh sách địa chỉ khi trang được tải
        window.onload = displayAddresses;
    </script>
</body>
</php>
