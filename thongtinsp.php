<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "admindoan";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy ID sản phẩm từ URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Kiểm tra nếu ID hợp lệ
if ($id <= 0) {
    die("Sản phẩm không hợp lệ!");
}

// Truy vấn chỉ sản phẩm có ID tương ứng
$sql = "SELECT id, tensp, giaban, thongtinsp, thongsokt, hinhanh, hinhanh2, hinhanh3 FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Kiểm tra nếu sản phẩm tồn tại
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    die("Sản phẩm không tồn tại!");
}
?>

<!DOCTYPE php>
<php lang="vi">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title><?php echo $row["tensp"]; ?></title>
        <link rel="stylesheet" href="css/app.css">
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                background-color: #f1f1f1;
                margin: 0;
                padding: 0;
            }

            .form-inline .form-control {
                border-radius: 25px;
                padding: 8px 15px;
                border: 1px solid #ccc;
            }

            .container {
                max-width: 1200px;
                margin: 30px auto;
            }

            .card {
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                margin-top: 30px;
                border: none;
                border-radius: 10px;
                overflow: hidden;
            }

            .wrapper {
                display: flex;
                flex-wrap: wrap;
                padding: 20px;
            }

            .preview {
                flex: 1;
                padding: 20px;
            }

            .preview img {
                width: 100%;
                border-radius: 8px;
                object-fit: cover;
            }

            .details {
                flex: 1;
                padding: 20px;
            }

            .product-title {
                font-size: 2.2em;
                font-weight: bold;
                color: #333;
                margin-bottom: 15px;
            }

            .product-description {
                font-size: 1.1em;
                color: #555;
                margin-bottom: 25px;
            }

            .price {
                font-size: 1.6em;
                font-weight: bold;
                color: #e74c3c;
                margin-bottom: 25px;
            }

            .vote {
                font-size: 1.1em;
                margin-bottom: 20px;
                color: #2ecc71;
            }

            h4,
            h5 {
                font-weight: bold;
                color: #333;
                margin-top: 20px;
                margin-bottom: 10px;
            }

            h3 {
                font-size: 1.8em;
                border-bottom: 2px solid #ddd;
                padding-bottom: 5px;
            }

            h4 {
                font-size: 1.5em;
                margin-top: 15px;
                color: #555;
            }

            h5 {
                font-size: 1.3em;
                margin-top: 10px;
                color: #777;
            }

            .product-details {
                list-style-type: none;
                padding: 0;
                margin: 20px 0;
            }

            .product-details li {
                font-size: 1em;
                margin: 8px 0;
            }

            .product-details li strong {
                color: #333;
            }

            .form-group {
                margin-top: 20px;
            }

            #soluong {
                width: 100px;
                padding: 8px;
                border-radius: 5px;
                border: 1px solid #ccc;
            }

            .action button {
                background-color: #28a745;
                color: white;
                border: none;
                padding: 12px 25px;
                border-radius: 5px;
                cursor: pointer;
                margin-right: 10px;
                font-size: 1.2em;
                transition: background-color 0.3s ease;
            }

            .action button:hover {
                background-color: #218838;
            }

            .like {
                background-color: #f8f9fa;
                border: 1px solid #ccc;
                color: #333;
                padding: 12px 20px;
                border-radius: 5px;
                cursor: pointer;
                font-size: 1.3em;
                transition: color 0.3s ease;
            }

            .like:hover {
                color: #e74c3c;
            }

            .footer {
                background-color: #343a40;
                color: white;
                padding: 20px 0;
            }

            .footer .container {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .footer .container span {
                font-size: 1em;
            }

            .footer a {
                color: #28a745;
                text-decoration: none;
            }

            .footer a:hover {
                text-decoration: underline;
            }

            .float-right a {
                color: white;
                font-size: 1em;
            }

            .mua {
                background-color: #28a745;
                /* Màu xanh lá cây */
                color: white;
                /* Màu chữ trắng */
                border: none;
                /* Bỏ viền */
                padding: 10px 20px;
                /* Khoảng cách bên trong nút */
                border-radius: 5px;
                /* Bo tròn góc */
                cursor: pointer;
                /* Đổi biểu tượng chuột thành hình tay */
                font-size: 16px;
                /* Kích thước chữ */
                transition: background-color 0.3s ease;
                /* Hiệu ứng chuyển màu khi hover */
            }

            .mua:hover {
                background-color: #218838;
                /* Màu xanh lá cây đậm hơn khi hover */
            }

            .tc {
                top: 20px;
                right: 20px;
                background-color: green;
                color: white;
                border: none;
                padding: 10px 15px;
                cursor: pointer;
                border-radius: 5px;
                transition: background-color 0.3s ease;
            }


            @media (max-width: 768px) {
                .wrapper {
                    flex-direction: column;
                }

                .preview,
                .details {
                    width: 100%;
                }

                .product-title {
                    font-size: 1.8em;
                }

                .product-description {
                    font-size: 1em;
                }

                .price {
                    font-size: 1.4em;
                }

                .vote {
                    font-size: 1.1em;
                }

                .form-group {
                    width: 100%;
                }

                .form-control {
                    width: 100%;
                }

            }
        </style>
    </head>

    <body>
        <div class="container" style="display: flex; justify-content: space-between;margin-top: 0px">
            <a class="tc" href="index.php" style="margin-left: 200px;  text-decoration: none;">Trang chủ</a>
            <span class="ravao" style="margin-right: 200px;">Giỏ hàng</span>
        </div>
        <main role="main">
            <div class="container mt-4">
                <div class="card">
                    <div class="container-fliud">
                        <form name="frmsanphamchitiet11" id="frmsanphamchitiet11" method="POST">
                            <div class="wrapper row">
                                <div class="preview col-md-6">
                                    <img src="sanpham/<?php echo $row['hinhanh']; ?>" alt="<?php echo $row['tensp']; ?>">
                                    <img src="sanpham/<?php echo $row['hinhanh2']; ?>" alt="<?php echo $row['tensp']; ?>">
                                    <img src="sanpham/<?php echo $row['hinhanh3']; ?>" alt="<?php echo $row['tensp']; ?>">
                                </div>
                                <div class="details col-md-6">
                                    <h3><?php echo $row["tensp"]; ?></h3>
                                    <div class="price"><?php echo number_format($row["giaban"], 0, ',', '.'); ?>đ</div>
                                    <button class="mua" onclick="addToCart('<?php echo $row['tensp']; ?>', <?php echo $row['giaban']; ?>, 'sanpham/<?php echo $row['hinhanh']; ?>')">
                                        Thêm vào giỏ hàng
                                    </button>
                                    <p class="vote"><?php echo $row["thongtinsp"]; ?></p>
                                    <h4>Thông số kỹ thuật</h4>
                                    <ul class="product-details">
                                        <li><?php echo nl2br($row["thongsokt"]); ?></li>
                                    </ul>
                                </div>

                                <section class="cart">
                                    <button class="dong">Đóng</button>

                                    <div style="margin-top: 45px;margin-bottom: 20px;">Danh sách mua hàng</div>
                                    <form action="">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>
                                                        Sản phẩm
                                                    </th>
                                                    <th>Giá</th>
                                                    <th>Số lượng</th>
                                                    <th>Chọn</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <!-- <td style="display: flex;align-items: center;"><img style ="width: 120px;"src="image/ninja-1000sx.png"alt ="">Ninja</td>
                                                    <td><p><span>1500</span><sup>đ</sup></p></td>
                                                     <td><input style="width: 40px; outline: none;" type="number"value ="1"min="1""max="2""></td>
                                                     <td style="cursor: pointer;">Xóa </td>
                                                 -->
                                            </tbody>
                                        </table>
                                        <div style="text-align: center;" class="price-total">
                                            <p style=" font-weight: bold;margin-top: 10px; margin-bottom: 20px;">Tổng tiền:<span>0</span><sup></sup> </p>
                                        </div>
                                        <a class="thanhtoan" href="thanhtoan.php">Thanh toán</a>
                                    </form>
                                </section>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>


        <footer class="footer bg-dark text-white mt-4">
            <div class="container">
                <span>© 2024 BMT Shop</span>
                <div class="float-right">
                    <a href="index.php" class="text-white">Quay lại đầu trang</a>
                </div>
            </div>
        </footer>
        <script src="js/giohang.js"></script>
    </body>

</php>