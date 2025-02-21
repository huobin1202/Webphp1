<!DOCTYPE php>
<php lang="vi" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="../vendor/font-awesome/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/reset.css ">
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

    h3, h4, h5 {
        font-weight: bold;
        color: #333;
        margin-top: 20px;
        margin-bottom: 10px;
    }
    .mua {
    background-color: #28a745; /* Màu xanh lá cây */
    color: white; /* Màu chữ trắng */
    border: none; /* Bỏ viền */
    padding: 10px 20px; /* Khoảng cách bên trong nút */
    border-radius: 5px; /* Bo tròn góc */
    cursor: pointer; /* Đổi biểu tượng chuột thành hình tay */
    font-size: 16px; /* Kích thước chữ */
    transition: background-color 0.3s ease; /* Hiệu ứng chuyển màu khi hover */
}

.mua:hover {
    background-color: #218838; /* Màu xanh lá cây đậm hơn khi hover */
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

    @media (max-width: 768px) {
        .wrapper {
            flex-direction: column;
        }

        .preview, .details {
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
                    <form name="frmsanphamchitiet14" id="frmsanphamchitiet14" method="post"></form>
                        
                        <div class="wrapper row">
                            <div class="preview col-md-6">
                                <img src="klx230sm/klx230sm1.jpg"style="height:400px" >
                                <img src="klx230sm/klx230sm2.jpg" style="height:400px">
                                <img src="klx230sm/klx230sm3.jpg" >

                            </div>
                            <div class="details col-md-6">
                                <div class="card page-3">
                                    <img src="image/klx-230sm.png" style="width: 1px;" alt="KLX 110 R">
                                
                                    <h3>KLX®230SM</h3>
                                    <div class="greenSpacer"></div>
                                    <div class="price"> 80.000.000đ</div>
                                    <button class ="mua">Thêm vào giỏ hàng </button>
                                </div>
                                
                                <p class="vote"> Tình trạng <strong>Mới 100%</strong> hàng <strong>Chất lượng</strong>, đảm bảo <strong>Uy tín</strong>!</p>
                                <h4>Thông số kỹ thuật</h4>
                                <h5>Động cơ</h5>
                                <ul class="product-details">
                                    <li><strong>Loại</strong> 4 thì, 1 xi-lanh, làm mát bằng không khí</li>
                                    <li><strong>Dung tích</strong> 233cc</li>
                                    <li><strong>Công suất cực đại</strong> 19.1 mã lực (14.3 kW) tại 7,600 vòng/phút</li>
                                    <li><strong>Mô-men xoắn cực đại</strong> 19.6 Nm tại 6,500 vòng/phút</li>
                                    <li><strong>Hệ thống nhiên liệu</strong> Phun xăng điện tử (EFI)</li>
                                    <li><strong>Hộp số</strong> 6 cấp</li>
                                    <li><strong>Truyền động</strong> Dây xích</li>
                                </ul>
                                
                                <h5>Kích thước & trọng lượng</h5>
                                <ul class="product-details">
                                    <li><strong>Chiều dài x rộng x cao</strong> 2,070 x 825 x 1,130 mm</li>
                                    <li><strong>Chiều cao yên</strong> 880 mm</li>
                                    <li><strong>Khoảng sáng gầm xe</strong> 280 mm</li>
                                    <li><strong>Trọng lượng ướt</strong> 134 kg</li>
                                    <li><strong>Dung tích bình xăng</strong> 7.6 lít</li>
                                </ul>
                                
                                <h5>Khung gầm & hệ thống treo</h5>
                                <ul class="product-details">
                                    <li><strong>Khung xe</strong> Khung thép ống chịu lực cao</li>
                                    <li><strong>Hệ thống treo trước</strong> Phuộc ống lồng đường kính 37mm, hành trình 220mm</li>
                                    <li><strong>Hệ thống treo sau</strong> Giảm xóc monoshock, hành trình 220mm</li>
                                    <li><strong>Phanh trước</strong> Đĩa đơn 240mm, kiểu lót sợi</li>
                                    <li><strong>Phanh sau</strong> Đĩa đơn 220mm</li>
                                    <li><strong>ABS</strong> Không có (thiết kế cho off-road và đường phố nhẹ)</li>
                                </ul>
                        
                                
                                <section class="cart">
                                    <button class= "dong" >Đóng</button>
                                    <!-- <div>Đóng</div> -->
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
                                        <div  style="text-align: center;"class="price-total">
                                            <p style=" font-weight: bold;margin-top: 10px; margin-bottom: 20px;">Tổng tiền:<span >0</span><sup></sup> </p>
                                        </div>
                                        <a class ="thanhtoan" href="thanhtoan.php">Thanh toán</a>
                                    </form>
                                </section>
                                
                                </div>
                            </div>
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