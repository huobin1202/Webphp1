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
                            <a href="/admin.php" class="sidebar-link">
                                <div class="sidebar-icon"><i class="fa-light fa-house"></i></div>
                                <div class="hidden-sidebar">Trang tổng quan</div>
                            </a>
                        </li>
                        <li class="sidebar-list-item tab-content">
                            <a href="/sanpham/sanpham.php" class="sidebar-link">
                                <div class="sidebar-icon"><i class="fa-thin fa-motorcycle"></i></div>
                                <div class="hidden-sidebar">Sản phẩm</div>
                            </a>
                        </li>
                        <li class="sidebar-list-item tab-content ">
                            <a href="/khachhang/khachhang.php" class="sidebar-link">
                                <div class="sidebar-icon"><i class="fa-light fa-users"></i></div>
                                <div class="hidden-sidebar">Khách hàng</div>
                            </a>
                        </li>
                        <li class="sidebar-list-item tab-content active">
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
                        <li class="sidebar-list-item tab-content">
                            <a href="/thongkekh/thongkekh.php" class="sidebar-link">
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
            <!-- Order  -->
            <div class="section">
                <div class="admin-control">
                    <div class="admin-control-left">
                        <select name="tinh-trang" id="tinh-trang">
                            <option value="2">Tất cả</option>
                            <option value="1">Đã xử lý</option>
                            <option value="0">Chưa xử lý</option>
                        </select>
                    </div>
                    <div class="admin-control-center">
                        <form action="" class="form-search">
                            <span class="search-btn"><i class="fa-light fa-magnifying-glass"></i></span>
                            <input id="form-search-order" type="text" class="form-search-input"
                                placeholder="Tìm kiếm mã đơn, mã khách hàng, tên khách hàng...">
                        </form>
                    </div>
                    <div class="admin-control-right">
                        <form action="" class="fillter-date">
                            <div>
                                <label for="time-start">Từ</label>
                                <input type="date" class="form-control-date" id="time-start" value="2024-02-27">
                            </div>
                            <div>
                                <label for="time-end">Đến</label>
                                <input type="date" class="form-control-date" id="time-end" value="2024-12-30">
                            </div>
                        </form>
                        <button class="btn-reset-order"><i class="fa-light fa-arrow-rotate-right"></i></button>
                    </div>
                </div>
                <div class="table">
                    <table width="100%">
                        <thead>
                            <tr>
                                <td>Mã DH</td>
                                <td>Mã KH</td>

                                <td>Tên khách hàng</td>
                                <td>Ngày đặt</td>   
                                <td>Tổng tiền</td>
                                <td>Trạng thái</td>
                                <td>Thao tác</td>
                            </tr>
                        </thead>
                        <tbody id="showOrder">
                            <tr>
                                <td>DH1</td>
                                <td>KH003</td>
                                <td>Nhật Tiến</td>
                                <td>02/06/2024</td>
                                <td>420.000.000đ</td>
                                <td><span class="status-complete">Đã xử lý</span></td>
                                <td class="control">
                                    <a href="chitietdonhang.php"><button class="btn-detail" id=""><i
                                                class="fa-regular fa-eye"></i> Chi tiết</button></a>
                                </td>
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