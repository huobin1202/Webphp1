-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 01, 2025 at 03:28 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `admindoan`
--

-- --------------------------------------------------------

--
-- Table structure for table `branch_addresses`
--

CREATE TABLE `branch_addresses` (
  `id` int(11) NOT NULL,
  `branch_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city_code` varchar(10) DEFAULT NULL,
  `city_name` varchar(100) DEFAULT NULL,
  `district_code` varchar(10) DEFAULT NULL,
  `district_name` varchar(100) DEFAULT NULL,
  `ward_code` varchar(10) DEFAULT NULL,
  `ward_name` varchar(100) DEFAULT NULL,
  `street_address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branch_addresses`
--

INSERT INTO `branch_addresses` (`id`, `branch_name`, `address`, `city_code`, `city_name`, `district_code`, `district_name`, `ward_code`, `ward_name`, `street_address`) VALUES
(1, 'Chi nhánh 1', '273 An Dương Vương, Phường 3, Quận 5, Thành phố Hồ Chí Minh', '79', 'Thành phố Hồ Chí Minh', '774', 'Quận 5', '27307', 'Phường 3', '273 An Dương Vương'),
(2, 'Chi nhánh 2', '04 Tôn Đức Thắng, Phường Bến Nghé, Quận 1, Thành phố Hồ Chí Minh', '79', 'Thành phố Hồ Chí Minh', '760', 'Quận 1', '26740', 'Phường Bến Nghé', '04 Tôn Đức Thắng'),
(3, 'Chi nhánh 3', '105 Bà Huyện Thanh Quan, Phường Võ Thị Sáu, Quận 3, Thành phố Hồ Chí Minh', '79', 'Thành phố Hồ Chí Minh', '770', 'Quận 3', '27139', 'Phường Võ Thị Sáu', '105 Bà Huyện Thanh Quan');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `contact` varchar(11) NOT NULL,
  `joindate` datetime NOT NULL,
  `status` int(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `city_code` varchar(10) DEFAULT NULL,
  `city_name` varchar(100) DEFAULT NULL,
  `district_code` varchar(10) DEFAULT NULL,
  `district_name` varchar(100) DEFAULT NULL,
  `ward_code` varchar(10) DEFAULT NULL,
  `ward_name` varchar(100) DEFAULT NULL,
  `street_address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `name`, `contact`, `joindate`, `status`, `password`, `email`, `address`, `role`, `city_code`, `city_name`, `district_code`, `district_name`, `ward_code`, `ward_name`, `street_address`) VALUES
(1, 'admin', '1', '2025-05-01 17:39:49', 1, 'admin', '1@gmail.com', 'c3, Phường Phúc Xá, Quận Ba Đình, Thành phố Hà Nội', 'admin', '1', 'Thành phố Hà Nội', '1', 'Quận Ba Đình', '1', 'Phường Phúc Xá', 'c3'),
(2, 'binh1', '2', '2025-05-01 17:59:45', 1, '123', '2@gmail.com', '123, Phường Phúc Xá, Quận Ba Đình, Thành phố Hà Nội', 'user', '1', 'Thành phố Hà Nội', '1', 'Quận Ba Đình', '1', 'Phường Phúc Xá', '123'),
(3, 'binh2', '3', '2025-05-01 18:02:36', 1, '123', '3@agmail.com', '', 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'binh3', '4', '2025-05-01 18:04:17', 1, '123', '4@gmail.com', '', 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'binh4', '5', '2025-05-01 18:05:49', 1, '123', '5@gmail.com', '', 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'binh5', '6', '2025-05-01 18:07:33', 1, '123', '6@gmail.com', '', 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'binh8', '9', '2025-05-01 18:10:31', 1, '123', '9@gmail.com', '', 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `giohang`
--

CREATE TABLE `giohang` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `soluong` int(11) NOT NULL DEFAULT 1,
  `price` int(255) NOT NULL,
  `img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `total` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `delivery_type` varchar(50) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('chuaxuly','daxuly','dahuy','dagiao') NOT NULL DEFAULT 'chuaxuly',
  `recipient_name` varchar(255) NOT NULL,
  `recipient_phone` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city_code` varchar(10) DEFAULT NULL,
  `city_name` varchar(100) DEFAULT NULL,
  `district_code` varchar(10) DEFAULT NULL,
  `district_name` varchar(100) DEFAULT NULL,
  `ward_code` varchar(10) DEFAULT NULL,
  `ward_name` varchar(100) DEFAULT NULL,
  `street_address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `total`, `note`, `payment_method`, `delivery_type`, `created_at`, `status`, `recipient_name`, `recipient_phone`, `address`, `city_code`, `city_name`, `district_code`, `district_name`, `ward_code`, `ward_name`, `street_address`) VALUES
(1, 1, '1300000000', '1', 'Tiền mặt', 'Mua trực tiếp', '2025-05-01 17:53:35', 'dagiao', 'bruh', '1', '273 An Dương Vương, Phường 3, Quận 5, Thành phố Hồ Chí Minh', '79', 'Thành phố Hồ Chí Minh', '774', 'Quận 5', '27307', 'Phường 3', '273 An Dương Vương'),
(2, 2, '1170000000', '123', 'Chuyển khoản', 'Giao tận nơi', '2025-05-01 18:01:09', 'chuaxuly', '123', '123', '123, Phường Phúc Xá, Quận Ba Đình, Thành phố Hà Nội', '1', 'Thành phố Hà Nội', '1', 'Quận Ba Đình', '1', 'Phường Phúc Xá', '123'),
(3, 3, '1040000000', '123', 'Tiền mặt', 'Giao tận nơi', '2025-05-01 18:03:19', 'chuaxuly', '123', '123', '123, Xã Vũ Xá, Huyện Kim Động, Tỉnh Hưng Yên', '33', 'Tỉnh Hưng Yên', '331', 'Huyện Kim Động', '12325', 'Xã Vũ Xá', '123'),
(4, 4, '910000000', '123', 'Tiền mặt', 'Mua trực tiếp', '2025-05-01 18:04:50', 'chuaxuly', '123', '123', '105 Bà Huyện Thanh Quan, Phường Võ Thị Sáu, Quận 3, Thành phố Hồ Chí Minh', '79', 'Thành phố Hồ Chí Minh', '770', 'Quận 3', '27139', 'Phường Võ Thị Sáu', '105 Bà Huyện Thanh Quan'),
(5, 5, '130000000', '1274', 'Tiền mặt', 'Giao tận nơi', '2025-05-01 18:06:41', 'chuaxuly', 'ịwif', '81724', '123, Xã Tàm Xá, Huyện Đông Anh, Thành phố Hà Nội', '1', 'Thành phố Hà Nội', '17', 'Huyện Đông Anh', '517', 'Xã Tàm Xá', '123'),
(6, 6, '650000000', '', 'Tiền mặt', 'Mua trực tiếp', '2025-05-01 18:08:00', 'chuaxuly', '1', '1', '04 Tôn Đức Thắng, Phường Bến Nghé, Quận 1, Thành phố Hồ Chí Minh', '79', 'Thành phố Hồ Chí Minh', '760', 'Quận 1', '26740', 'Phường Bến Nghé', '04 Tôn Đức Thắng'),
(7, 7, '130000000', '', 'Tiền mặt', 'Mua trực tiếp', '2025-05-01 18:12:19', 'chuaxuly', '1235151551512', '123', '105 Bà Huyện Thanh Quan, Phường Võ Thị Sáu, Quận 3, Thành phố Hồ Chí Minh', '79', 'Thành phố Hồ Chí Minh', '770', 'Quận 3', '27139', 'Phường Võ Thị Sáu', '105 Bà Huyện Thanh Quan');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `soluong` int(11) DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `soluong`, `price`) VALUES
(1, 1, 1, 10, '130000000'),
(2, 2, 1, 9, '130000000'),
(3, 3, 1, 8, '130000000'),
(4, 4, 1, 7, '130000000'),
(5, 5, 1, 1, '130000000'),
(6, 6, 1, 5, '130000000'),
(7, 7, 1, 1, '130000000');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `tensp` varchar(255) NOT NULL,
  `dongsp` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `giaban` int(11) NOT NULL,
  `thongtinsp` text DEFAULT NULL,
  `thongsokt` text DEFAULT NULL,
  `hinhanh` varchar(255) NOT NULL DEFAULT '',
  `hinhanh2` varchar(255) NOT NULL DEFAULT '',
  `hinhanh3` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `tensp`, `dongsp`, `status`, `giaban`, `thongtinsp`, `thongsokt`, `hinhanh`, `hinhanh2`, `hinhanh3`) VALUES
(1, 'KLX®230R', 'Dòng KLX', '1', 130000000, 'Niềm vui trọn vẹn của xe đạp địa hình KLX®230R có nghĩa là đã đến lúc Ra ngoài và Vui chơi. Chinh phục địa hình với khả năng xử lý nhanh nhẹn và sức mạnh dồi dào, đồng thời làm tung một số bụi bẩn nghiêm trọng trên đường đi. Hệ thống treo hành trình dài kết hợp với khả năng kiểm soát tốc độ thấp được cải thiện mang đến cho chiếc xe đạp này cảm giác thể thao nổi bật so với những chiếc còn lại. Được hoàn thiện với kiểu dáng lấy cảm hứng từ dòng sản phẩm KX™, gói gọn nhẹ, có khả năng cao của KLX230R mang đến một liều lượng lớn niềm vui cho mọi chuyến đi địa hình.', '🛠️ Động cơ & Truyền động\r\n\r\n    Loại động cơ: 4 thì, xi-lanh đơn, SOHC, làm mát bằng không khí\r\n\r\n    Dung tích: 233 cc\r\n\r\n    Đường kính x Hành trình piston: 67.0 x 66.0 mm\r\n\r\n    Tỷ số nén: 9.4:1\r\n\r\n    Hệ thống nhiên liệu: Phun xăng điện tử DFI® với bướm ga Keihin 32 mm\r\n\r\n    Khởi động: Điện\r\n\r\n    Hộp số: 6 cấp, ly hợp ướt, côn tay\r\n\r\n🏍️ Khung sườn & Hệ thống treo\r\n\r\n    Loại khung: Thép cường lực cao, dạng hộp perimeter\r\n\r\n    Góc nghiêng / Độ trượt: 25.4° / 4.2 in\r\n\r\n    Hệ thống treo trước: Phuộc ống lồng 37 mm, hành trình 9.8 in\r\n\r\n    Hệ thống treo sau: Liên kết Uni-Trak® với giảm xóc đơn, điều chỉnh tải trước liên tục, hành trình 9.8 in​\r\n\r\n🛞 Bánh xe & Phanh\r\n\r\n    Lốp trước: 80/100-21\r\n\r\n    Lốp sau: 100/100-18\r\n\r\n    Phanh trước: Đĩa petal 240 mm với kẹp phanh hai piston\r\n\r\n    Phanh sau: Đĩa petal 220 mm với kẹp phanh một piston​\r\n\r\n📏 Kích thước & Trọng lượng\r\n\r\n    Chiều dài tổng thể: 80.5 in\r\n\r\n    Chiều rộng tổng thể: 33.3 in\r\n\r\n    Chiều cao tổng thể: 47.2 in\r\n\r\n    Chiều dài cơ sở: 54.1 in\r\n\r\n    Chiều cao yên: 35.6 in\r\n\r\n    Khoảng sáng gầm: 11.4 in\r\n\r\n    Trọng lượng ướt: 262.4 lb (phiên bản tiêu chuẩn) / 264.5 lb (phiên bản 50 bang)\r\n\r\n    Dung tích bình xăng: 2.0 gal​\r\n\r\n🔧 Tính năng nổi bật\r\n\r\n    Cân bằng sơ cấp: Trục cân bằng sơ cấp giúp giảm rung động, mang lại trải nghiệm lái mượt mà hơn\r\n\r\n    Thiết kế mới: Khung sườn và bình xăng được thiết kế lại để cải thiện khả năng điều khiển và tầm hoạt động\r\n\r\n    Yên xe cải tiến: Yên xe dày hơn và rộng hơn, tăng cường sự thoải mái cho người lái\r\n\r\n    Phong cách KX™: Thiết kế lấy cảm hứng từ dòng xe đua KX™, mang lại vẻ ngoài thể thao và năng động', 'uploads/KLX230R.jpg', 'uploads/KLX230R2.jpg', 'uploads/KLX230R3.jpg'),
(2, 'Z650RS AB', 'Dòng Z', '1', 233000000, '123', '321', 'uploads/z650rsabs.jpg', 'uploads/z650rsabs2.jpg', 'uploads/z650rsabs3.jpg'),
(3, 'NINJA 500 SE', 'Dòng Ninja', '1', 194000000, 'ss', 'ss', 'uploads/0_15.jpg', 'uploads/top.jpg', 'uploads/meter.jpg'),
(4, 'NINJA 650 ABS', 'Dòng Ninja', '0', 210000000, '', '', 'uploads/0_152.jpg', 'uploads/top2.jpg', 'uploads/meter2.jpg'),
(5, 'Z H2 SE', 'Dòng Z', '1', 789300000, '', '', 'uploads/ZH2SE.jpg', 'uploads/ZH2SE2.jpg', 'uploads/ZH2SE3.jpg'),
(6, 'NINJA ZX-4R', 'Dòng Ninja', '1', 250000000, '', '', 'uploads/0_153.jpg', 'uploads/top3.jpg', 'uploads/meter3.jpg'),
(7, 'NINJA ZX-10R ABS', 'Dòng Ninja', '1', 765700000, '', '', 'uploads/n-zx10r.jpg', 'uploads/n-zx10r2.jpg', 'uploads/n-zx10r3.jpg'),
(8, 'KLX230R', 'Dòng KLX', '1', 148700000, 'KLX230R sẽ đưa những chuyến phiêu lưu của bạn đến tầm cao mới. Với hệ thống treo hành trình dài và khoảng sáng gầm xe lớn, cùng trọng lượng nhẹ, KLX230R – mẫu xe cào cào đích thực – được sinh ra để phục vụ những tay lái mong muốn tìm kiếm niềm vui ở những nơi đầy cát-gió-bùn đất một cách tuyệt vời nhất.', '', 'uploads/KLX230R.jpg', 'uploads/KLX230R2.jpg', 'uploads/KLX230R3.jpg'),
(9, 'Z500 ABS', 'Dòng Z', '1', 170600000, '', '', 'uploads/z500abs.jpg', 'uploads/z500abs2.jpg', 'uploads/z500abs3.jpg'),
(10, 'Z650 ABS', 'Dòng Z', '1', 194000000, '', '', 'uploads/z650abs.jpg', 'uploads/z650abs2.jpg', 'uploads/z650abs3.jpg'),
(11, 'Z900RS', 'Dòng Z', '1', 480800000, '', '', 'uploads/Z900RS.jpg', 'uploads/Z900RS2.jpg', 'uploads/Z900RS3.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branch_addresses`
--
ALTER TABLE `branch_addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `contact` (`contact`);

--
-- Indexes for table `giohang`
--
ALTER TABLE `giohang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branch_addresses`
--
ALTER TABLE `branch_addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `giohang`
--
ALTER TABLE `giohang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `giohang`
--
ALTER TABLE `giohang`
  ADD CONSTRAINT `giohang_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `giohang_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
