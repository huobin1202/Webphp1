<!DOCTYPE php>
<php lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kawakaki</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="invoice-container">
        <header>
            <h1>Hóa Đơn </h1>
            <p>Cửa hàng BMTShop</p>
        </header>
        <section class="customer-info">
            <h2>Thông Tin Khách Hàng</h2>
            <p><strong>Họ và Tên:</strong> Nguyễn Văn A</p>
            <p><strong>Số Điện Thoại:</strong> 0901234567</p>
            <p><strong>Địa Chỉ:</strong> 123 Đường Moto, Quận 5, TP.HCM</p>
        </section>
        <section class="vehicle-info">
            <h2>Thông Tin Xe</h2>
            <p><strong>Loại Xe:</strong> Ninja H2R</p>
            <p><strong>Biển Số:</strong> 59A-12345</p>
            <p><strong>Màu Sắc:</strong> Đen</p>
            <p><strong>Giá Bán:</strong> 735,000,000 VND</p>
        </section>
        <section class="invoice-details">
            <h2>Chi Tiết Hóa Đơn</h2>
            <table>
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Nội Dung</th>
                        <th>Số Lượng</th>
                        <th>Đơn Giá (VND)</th>
                        <th>Thành Tiền (VND)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Ninja H2R</td>
                        <td>1</td>
                        <td>75,000,000</td>
                        <td>75,000,000</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Bảo Hiểm</td>
                        <td>1</td>
                        <td>2,000,000</td>
                        <td>2,000,000</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4"><strong>Tổng Cộng</strong></td>
                        <td><strong>77,000,000</strong></td>
                    </tr>
                </tfoot>
            </table>
        </section>
        <footer>
            <p>Cảm ơn quý khách đã mua hàng!</p>
            <a href="/admin.php" class="back-button">Trở về trang chủ</a>
        </footer>
    </div>

</body>
</php>
