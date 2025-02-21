<!DOCTYPE php>
<php lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Địa chỉ giao hàng</title>
    <style>
        /* Đặt khung chính giữa màn hình */
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            margin-left: 500px;
            margin-right: 500px;
        }
        .form-section {
            margin-top: 20px;
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
        .form-section input[type="radio"] {
            width: auto;
        }
        .btn-submit, .btn-delete {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none; 
            border-radius: 5px;
            cursor: pointer;
        }
         .btn-delete:hover {
            background-color: #d50404;
        }
        .btn-submit:hover{
            background-color:  #05a920;
        }
        .btn-delete {
            margin-top: 10px;
            background-color: #0e0d0d;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Địa chỉ giao hàng</h1>

    <div>
        <label>
            <input type="radio" name="addressOption" value="saved" checked onclick="toggleAddressForm('saved')">
            Chọn địa chỉ đã lưu
        </label>

        <label>
            <input type="radio" name="addressOption" value="new" onclick="toggleAddressForm('new')">
            Nhập địa chỉ giao hàng mới
        </label>
    </div>

    <!-- Phần chọn địa chỉ đã lưu -->
    <div id="savedAddressSection" class="form-section">
        <label for="savedAddress">Chọn địa chỉ từ tài khoản của bạn:</label>
        <select id="savedAddress">
            <!-- Các địa chỉ đã lưu sẽ hiển thị ở đây -->
        </select>
        <button class="btn-delete" onclick="deleteSelectedAddress()" style="margin-top: 50px; margin-bottom: 10px;">Xóa địa chỉ</button>
    </div>

    <!-- Phần nhập địa chỉ mới -->
    <div id="newAddressSection" class="form-section" style="display: none;">
        <label for="fullName">Họ và tên:</label>
        <input type="text" id="fullName" placeholder="Nhập họ và tên">

        <label for="phone">Số điện thoại:</label>
        <input type="text" id="phone" placeholder="Nhập số điện thoại">

        <label for="address">Địa chỉ:</label>
        <input type="text" id="address" placeholder="Nhập địa chỉ">

        <label for="province">Tỉnh/Thành:</label>
        <input type="text" id="province" placeholder="Nhập tỉnh/thành">
    </div>

    <button class="btn-submit" onclick="submitAddress()">Xác nhận địa chỉ</button>

    <h1 style="text-align: center;"> Phương Thức Thanh Toán</h1>

    <div class="form-section">
        <label for="paymentOption">Phương thức thanh toán:</label>
        <select id="paymentOption" onchange="togglePaymentMethod()">
            <option value="cash" style="color: #2305a9; ">Thanh toán bằng tiền mặt</option>
            <option value="bankTransfer" style="color: #0606aa;">Chuyển khoản ngân hàng</option>
            <option value="creditCard" style="color: #1005a9;">Thanh toán bằng thẻ tín dụng</option>
        </select>
    </div>

    <!-- Phương thức thanh toán bằng tiền mặt -->
    <div id="cashMethod" class="form-section payment-method">
        <h3 style="color: #05a920;">Thanh Toán Bằng Tiền Mặt</h3>
        <p>Bạn sẽ thanh toán trực tiếp khi nhận hàng. Vui lòng chuẩn bị số tiền chính xác khi giao hàng.</p>
    </div>

    <!-- Phương thức thanh toán chuyển khoản -->
    <div id="bankTransferMethod" class="form-section payment-method">
        <h3 >Chuyển Khoản Ngân Hàng</h3>
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
        <h3 style="color: #05a920;">Thanh Toán Bằng Thẻ Tín Dụng</h3>
        <label for="cardNumber">Số thẻ:</label>
        <input type="text" id="cardNumber" placeholder="Nhập số thẻ tín dụng">

        <label for="cardHolder">Tên chủ thẻ:</label>
        <input type="text" id="cardHolder" placeholder="Nhập tên chủ thẻ">

        <label for="expiryDate">Ngày hết hạn:</label>
        <input type="month" id="expiryDate">

        <label for="cvv">CVV:</label>
        <input type="text" id="cvv" placeholder="Nhập mã CVV (3 chữ số phía sau thẻ)">
    </div>

    <a href="thanhtoantc.php"> <button class="btn-submit" onclick="confirmPayment()">Xác nhận thanh toán</button></a>

    <script>
        // Hàm chuyển đổi giữa địa chỉ đã lưu và địa chỉ mới
        function toggleAddressForm(option) {
            const savedAddressSection = document.getElementById('savedAddressSection');
            const newAddressSection = document.getElementById('newAddressSection');
            
            if (option === 'saved') {
                savedAddressSection.style.display = 'block';
                newAddressSection.style.display = 'none';
            } else {
                savedAddressSection.style.display = 'none';
                newAddressSection.style.display = 'block';
            }
        }

        // Hàm tải danh sách địa chỉ đã lưu từ localStorage và hiển thị trong select
        function loadSavedAddresses() {
            const savedAddresses = JSON.parse(localStorage.getItem('addresses')) || [];
            const savedAddressDropdown = document.getElementById('savedAddress');
            savedAddressDropdown.innerHTML = ''; // Xóa các tùy chọn cũ

            savedAddresses.forEach((address, index) => {
                const option = document.createElement('option');
                option.value = index;
                option.textContent = address.fullName + ' - ' +address.phone+','+ address.address + ', ' + address.province;
                savedAddressDropdown.appendChild(option);
            });
        }

        // Hàm lưu địa chỉ mới vào localStorage
        function saveNewAddress(fullName, phone, address, province) {
            const savedAddresses = JSON.parse(localStorage.getItem('addresses')) || [];

            const newAddress = {
                fullName,
                phone,
                address,
                province
            };

            savedAddresses.push(newAddress);
            localStorage.setItem('addresses', JSON.stringify(savedAddresses));

            loadSavedAddresses(); // Tải lại danh sách địa chỉ sau khi thêm mới
        }

        // Hàm xử lý khi người dùng nhấn "Xác nhận địa chỉ"
        function submitAddress() {
            const selectedOption = document.querySelector('input[name="addressOption"]:checked').value;

            if (selectedOption === 'saved') {
                const savedAddressIndex = document.getElementById('savedAddress').value;
                const savedAddresses = JSON.parse(localStorage.getItem('addresses')) || [];
                const selectedAddress = savedAddresses[savedAddressIndex];
                
                alert('Bạn đã chọn địa chỉ:\n' +
                    'Họ và tên: ' + selectedAddress.fullName + '\n' +
                    'Số điện thoại: ' + selectedAddress.phone + '\n' +
                    'Địa chỉ: ' + selectedAddress.address + '\n' +
                    'Tỉnh/Thành: ' + selectedAddress.province);
            } else {
                const fullName = document.getElementById('fullName').value;
                const phone = document.getElementById('phone').value;
                const address = document.getElementById('address').value;
                const province = document.getElementById('province').value;

                if (fullName && phone && address && province) {
                    saveNewAddress(fullName, phone, address, province);

                    alert('Địa chỉ mới đã được lưu:\n' +
                        'Họ và tên: ' + fullName + '\n' +
                        'Số điện thoại: ' + phone + '\n' +
                        'Địa chỉ: ' + address + '\n' )
                        
                } else {
                    alert('Vui lòng nhập đầy đủ thông tin.');
                }
            }
        }

        // Hàm xóa địa chỉ đã lưu
        function deleteSelectedAddress() {
            const savedAddressIndex = document.getElementById('savedAddress').value;
            let savedAddresses = JSON.parse(localStorage.getItem('addresses')) || [];

            // Xóa địa chỉ được chọn
            savedAddresses.splice(savedAddressIndex, 1);

            // Cập nhật lại localStorage
            localStorage.setItem('addresses', JSON.stringify(savedAddresses));

            // Tải lại danh sách sau khi xóa
            loadSavedAddresses();
        }
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

        
        // Gọi hàm loadSavedAddresses khi trang được tải
        window.onload = loadSavedAddresses;
        //Hiển thị mặc định phương thức thanh toán đầu tiên (tiền mặt)
        window.onload = function() {
            togglePaymentMethod();
        };
    </script>
</body>
</php>
