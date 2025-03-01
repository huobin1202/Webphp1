<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='../img/logo.png' rel='icon' type='image/x-icon' />
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link href="../assets/font/font-awesome-pro-v6-6.2.0/css/all.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../assets/css/admin-responsive.css">
    <title>Quản lý cửa hàng</title>
</head>

<body>
    <header class="header">
        <button class="menu-icon-btn">
            <div class="menu-icon">
                <i class="fa-regular fa-bars"></i>
            </div>
        </button>
    </header>
    <div class="container">
        <aside class="sidebar open">
            <div class="top-sidebar">
                <a href="#" class="channel-logo"><img src="../img/logo.png" alt="Channel Logo"></a>
                <div class="hidden-sidebar your-channel"><img src="" style="height: 30px;" alt="">
                </div>
            </div>
            <div class="middle-sidebar">
                <ul class="sidebar-list">
                    <li class="sidebar-list-item tab-content ">
                        <a href="/admin.html" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-light fa-house"></i></div>
                            <div class="hidden-sidebar">Trang tổng quan</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item tab-content">
                        <a href="/sanpham/sanpham.html" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-thin fa-motorcycle"></i></div>
                            <div class="hidden-sidebar">Sản phẩm</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item tab-content">
                        <a href="/khachhang/khachhang.html" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-light fa-users"></i></div>
                            <div class="hidden-sidebar">Khách hàng</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item tab-content">
                        <a href="/donhang/donhang.php" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-light fa-basket-shopping"></i></div>
                            <div class="hidden-sidebar">Đơn hàng</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item tab-content">
                        <a href="/thongkesp/thongke.php" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-light fa-chart-simple"></i></div>
                            <div class="hidden-sidebar">Thống kê sản phẩm</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item tab-content active">
                        <a href="/thongkekh/thongkekh.php" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-light fa-chart-simple"></i></div>
                            <div class="hidden-sidebar">Thống kê khách hàng</div>
                        </a>
                    </li>
                    <div class="spacer" style="height:50px;width:1px"></div>
                    <li class="sidebar-list-item user-logout" style="border-top: 2px solid rgba(0,0,0,0.12);">
                        <a href="index.html" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-thin fa-circle-chevron-left"></i></div>
                            <div class="hidden-sidebar">Trang chủ</div>
                        </a>
                    </li>

                    <li class="sidebar-list-item user-logout">
                        <a href="#" class="sidebar-link">
                            <div class="sidebar-icon"><i class="fa-light fa-circle-user"></i></div>
                            <div class="hidden-sidebar" id="name-acc">Admin</div>
                        </a>
                    </li>
                    <li class="sidebar-list-item user-logout">
                        <a href="#" class="sidebar-link" >
                            <div class="sidebar-icon"><i class="fa-light fa-arrow-right-from-bracket"></i></div>
                            <div class="hidden-sidebar" id="logoutacc">Đăng xuất</div>
                        </a>
                    </li>
                </ul>
            </div>

        </aside>
        <main class="content">
            <div class="section">
                <div class="admin-control">
                    
                    <div class="admin-control-center">
                        <form action="" class="form-search">
                            <span class="search-btn"><i class="fa-light fa-magnifying-glass"></i></span>
                            <input id="form-search-tk" type="text" class="form-search-input"
                                placeholder="Tìm kiếm tên mã, tên khách hàng...">
                        </form>
                    </div>
                    <div class="admin-control-right">
                        <form action="" class="fillter-date">
                            <div>
                                <label for="time-start">Từ</label>
                                <input type="date" class="form-control-date" id="time-start-tk" value="2024-01-19">
                            </div>
                            <div>
                                <label for="time-end">Đến</label>
                                <input type="date" class="form-control-date" id="time-end-tk" value="2024-12-30">
                            </div>
                        </form>
                        <button class="btn-reset-order"><i class="fa-regular fa-arrow-up-short-wide"></i></i></button>
                        <button class="btn-reset-order"><i class="fa-regular fa-arrow-down-wide-short"></i></button>
                        <button class="btn-reset-order"><i class="fa-light fa-arrow-rotate-right"></i></button>
                    </div>
                </div>
     
                <div class="table">
                    <table width="100%">
                        <thead>
                            <tr>
                                <td>Mã KH</td>
                                <td>Tên KH</td>
                                <td>Số lượng mua</td>
                                <td>Doanh thu</td>
                                <td>Thao tác</td>
                            </tr>
                        </thead>
                        <tbody id="showTk">
                            <tr>
                                <td>KH003</td>
                                <td>
                                    <p>Nhật Tiến</p>
                                </td>
                                <td>10</td>
                                <td>980.000.000đ</td>
                                <td><button class="btn-detail product-order-detail"><a href="chitietthongkekh1.html"><i
                                                class="fa-regular fa-eye"></i> Chi tiết</a></button></td>
                            </tr>
                        </tbody>
                        <tbody id="showTk">
                            <tr>
                                <td>KH006</td>
                                <td>
                                    <p>Nguyễn Văn A</p>
                                </td>
                                <td>7</td>
                                <td>960.000.000đ</td>
                                <td><button class="btn-detail product-order-detail"><a href="chitietthongkekh2.html"><i
                                    class="fa-regular fa-eye"></i> Chi tiết</a></button></td>
                            </tr>
                        </tbody>
                        <tbody id="showTk">
                            <tr>
                                <td>KH008</td>
                                <td>
                                    <p>Nguyễn Thị Bánh Bèo</p>
                                </td>
                                <td>5</td>
                                <td>840.000.000đ</td>
                                <td><button class="btn-detail product-order-detail"><a href="chitietthongkekh3.html"><i
                                    class="fa-regular fa-eye"></i> Chi tiết</a></button></td>
                            </tr>
                        </tbody>
                        <tbody id="showTk">
                            <tr>
                                <td>KH004</td>
                                <td>
                                    <p>Nguyễn Minh</p>
                                </td>
                                <td>3</td>
                                <td>560.000.000đ</td>
                                <td><button class="btn-detail product-order-detail"><a href="chitietthongkekh4.html"><i
                                    class="fa-regular fa-eye"></i> Chi tiết</a></button></td>
                            </tr>
                        </tbody>
                        <tbody id="showTk">
                            <tr>
                                <td>KH002</td>
                                <td>
                                    <p>Huỳnh Thành</p>
                                </td>
                                <td>2</td>
                                <td>490.000.000đ</td>
                                <td><button class="btn-detail product-order-detail"><a href="chitietthongkekh5.html"><i
                                    class="fa-regular fa-eye"></i> Chi tiết</a></button></td>
                            </tr>
                        </tbody>
                       
                    </table>
                </div>
            </div>
        </main>
    </div>
    <div class="modal detail-order-product open">
        <div class="modal-container">
            <a href="thongkekh.html"><button class="modal-close"><i class="fa-regular fa-xmark"></i></button></a>
            <div class="table">
                <table width="100%">
                    <thead>
                        <tr>
                            <td>Mã DH</td>
                            <td>Số lượng</td>
                            <td>Đơn giá</td>
                            <td>Ngày đặt</td>
                            <td>Thao tác</td>
                   
                        </tr>
                    </thead>
                    <tbody id="show-product-order-detail">
                        <tr>
                            <td>DH1</td>
                            <td>4</td>
                            <td>420.000.000đ</td>
                            <td>02/06/2024</td>
                              <td><button class="btn-detail product-order-detail"><a href="hoadon.php"><i
                                                class="fa-regular fa-eye"></i> Chi tiết</a></button></td>
                          
                        </tr>
                    </tbody>
                 
                   
                    <tbody id="show-product-order-detail">
                        <tr>
                            <td>DH6</td>
                            <td>6</td>
                            <td>560.000.000đ</td>
                            <td>25/12/2024</td>
                            <td><button class="btn-detail product-order-detail"><a href="hoadon.php"><i
                                class="fa-regular fa-eye"></i> Chi tiết</a></button></td>
                  
                        </tr>
                    </tbody>
                </table>
            </div>
            </form>
        </div>
    </div>

    <script src="../assets/js/admin.js"></script>
</body>

</html>