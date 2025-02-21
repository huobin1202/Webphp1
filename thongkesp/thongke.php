<!DOCTYPE php>
<php lang="en">

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
                    <div class="hidden-sidebar your-channel"><img src=""
                            style="height: 30px;" alt="">
                    </div>
                </div>
                <div class="middle-sidebar">
                    <ul class="sidebar-list">
                        <li class="sidebar-list-item tab-content ">
                            <a href="http://localhost/Webphp/admin.php" class="sidebar-link">
                                <div class="sidebar-icon"><i class="fa-light fa-house"></i></div>
                                <div class="hidden-sidebar">Trang tổng quan</div>
                            </a>
                        </li>
                        <li class="sidebar-list-item tab-content">
                            <a href="http://localhost/Webphp/sanpham/sanpham.php" class="sidebar-link">
                                <div class="sidebar-icon"><i class="fa-thin fa-motorcycle"></i></div>
                                <div class="hidden-sidebar">Sản phẩm</div>
                            </a>
                        </li>
                        <li class="sidebar-list-item tab-content ">
                            <a href="http://localhost/Webphp/khachhang/khachhang.php" class="sidebar-link">
                                <div class="sidebar-icon"><i class="fa-light fa-users"></i></div>
                                <div class="hidden-sidebar">Khách hàng</div>
                            </a>
                        </li>
                        <li class="sidebar-list-item tab-content">
                            <a href="http://localhost/Webphp/donhang/donhang.php" class="sidebar-link">
                                <div class="sidebar-icon"><i class="fa-light fa-basket-shopping"></i></div>
                                <div class="hidden-sidebar">Đơn hàng</div>
                            </a>
                        </li>
                        <li class="sidebar-list-item tab-content active">
                            <a href="http://localhost/Webphp/thongkesp/thongke.php" class="sidebar-link">
                                <div class="sidebar-icon"><i class="fa-light fa-chart-simple"></i></div>
                                <div class="hidden-sidebar">Thống kê sản phẩm</div>
                            </a>
                        </li>
                        <li class="sidebar-list-item tab-content ">
                            <a href="http://localhost/Webphp/thongkekh/thongkekh.php " class="sidebar-link">
                                <div class="sidebar-icon"><i class="fa-light fa-chart-simple"></i></div>
                                <div class="hidden-sidebar">Thống kê khách hàng</div>
                            </a>
                        </li>

                        <div class="spacer" style="height:50px;width:1px"></div>
                        <li class="sidebar-list-item user-logout" style="border-top: 2px solid rgba(0,0,0,0.12);">
                            <a href="index.php" class="sidebar-link">
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
                            <a href="#" class="sidebar-link">
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
                                placeholder="Tìm kiếm tên xe...">
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
                <div class="order-statistical" id="order-statistical">
                    <div class="order-statistical-item">
                        <div class="order-statistical-item-content">
                            <p class="order-statistical-item-content-desc">Sản phẩm được bán ra</p>
                            <h4 class="order-statistical-item-content-h" id="quantity-product">7</h4>
                        </div>
                        <div class="order-statistical-item-icon">
                            <i class="fa-thin fa-motorcycle"></i>
                        </div>

                    </div>
                    <div class="order-statistical-item">
                        <div class="order-statistical-item-content">
                            <p class="order-statistical-item-content-desc">Số lượng bán ra</p>
                            <h4 class="order-statistical-item-content-h" id="quantity-order">47</h4>
                        </div>
                        <div class="order-statistical-item-icon">
                            <i class="fa-light fa-file-lines"></i>
                        </div>

                    </div>
                    <div class="order-statistical-item">
                        <div class="order-statistical-item-content">
                            <p class="order-statistical-item-content-desc">Doanh thu</p>
                            <h4 class="order-statistical-item-content-h" id="quantity-sale">4.050.000.000đ</h4>
                        </div>
                        <div class="order-statistical-item-icon">
                            <i class="fa-light fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
                <div class="table">
                    <table width="100%">
                        <thead>
                            <tr>
                                <td>STT</td>
                                <td>Tên xe</td>
                                <td>Số lượng bán</td>
                                <td>Doanh thu</td>
                                <td>Thao tác</td>
                            </tr>
                        </thead>
                        <tbody id="showTk">
                            <tr>
                                <td>1</td>
                                <td>
                                    <div class="prod-img-title"><img class="prd-img-tbl" src="../img/klx-230.png"
                                            alt="">
                                        <p>KLX®230</p>
                                    </div>
                                </td>
                                <td>20</td>
                                <td>600.000.000đ</td>
                                <td><button class="btn-detail product-order-detail"><a href="chitietthongke1.php"><i
                                                class="fa-regular fa-eye"></i> Chi
                                            tiết</a></button></td>
                            </tr>
                        </tbody>
                        <tbody id="showTk">
                            <tr>
                                <td>2</td>
                                <td>
                                    <div class="prod-img-title"><img class="prd-img-tbl" src="../img/klx-110r.png"
                                            alt="">
                                        <p>KLX®110R</p>
                                    </div>
                                </td>
                                <td>12</td>
                                <td>600.000.000đ</td>
                                <td><button class="btn-detail product-order-detail"><a href="chitietthongke2.php"><i
                                                class="fa-regular fa-eye"></i> Chi
                                            tiết</a></button></td>
                            </tr>
                        </tbody>
                        <tbody id="showTk">
                            <tr>
                                <td>3</td>
                                <td>
                                    <div class="prod-img-title"><img class="prd-img-tbl" src="../img/ninja-zx4r.png"
                                            alt="">
                                        <p>NINJA® ZX™-4R</p>
                                    </div>
                                </td>
                                <td>6</td>
                                <td>900.000.000đ</td>
                                <td><button class="btn-detail product-order-detail"><a href="chitietthongke3.php"><i
                                                class="fa-regular fa-eye"></i> Chi
                                            tiết</a></button></td>
                            </tr>
                        </tbody>
                        <tbody id="showTk">
                            <tr>
                                <td>4</td>
                                <td>
                                    <div class="prod-img-title"><img class="prd-img-tbl" src="../img/z-h2.png" alt="">
                                        <p>Z H2</p>
                                    </div>
                                </td>
                                <td>3</td>
                                <td>630.000.000đ</td>
                                <td><button class="btn-detail product-order-detail"><a href="chitietthongke4.php"><i
                                                class="fa-regular fa-eye"></i> Chi
                                            tiết</a></button></td>
                            </tr>
                        </tbody>
                        <tbody id="showTk">
                            <tr>
                                <td>5</td>
                                <td>
                                    <div class="prod-img-title"><img class="prd-img-tbl" src="../img/z650rs.png" alt="">
                                        <p>Z650RS</p>
                                    </div>
                                </td>
                                <td>3</td>
                                <td>450.000.000đ</td>
                                <td><button class="btn-detail product-order-detail"><a href="chitietthongke5.php"><i
                                                class="fa-regular fa-eye"></i> Chi
                                            tiết</a></button></td>
                            </tr>
                        </tbody>
                        <tbody id="showTk">
                            <tr>
                                <td>6</td>
                                <td>
                                    <div class="prod-img-title"><img class="prd-img-tbl" src="../img/ninja-1000sx.png"
                                            alt="">
                                        <p>NINJA® 1000SX</p>
                                    </div>
                                </td>
                                <td>2</td>
                                <td>660.000.000đ</td>
                                <td><button class="btn-detail product-order-detail"><a href="chitietthongke6.php"><i
                                                class="fa-regular fa-eye"></i> Chi
                                            tiết</a></button></td>
                            </tr>
                        </tbody>
                        <tbody id="showTk">
                            <tr>
                                <td>7</td>
                                <td>
                                    <div class="prod-img-title"><img class="prd-img-tbl" src="../img/ninja-1000sx.png"
                                            alt="">
                                        <p>NINJA® 650</p>
                                    </div>
                                </td>
                                <td>1</td>
                                <td>210.000.000đ</td>
                                <td><button class="btn-detail product-order-detail"><a href="chitietthongke7.php"><i
                                                class="fa-regular fa-eye"></i> Chi
                                            tiết</a></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    <script src="../assets/js/admin.js"></script>
</body>

</php>