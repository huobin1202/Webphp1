-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 31, 2025 at 05:34 PM
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
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `contact` varchar(11) NOT NULL,
  `joindate` date NOT NULL,
  `status` int(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `name`, `contact`, `joindate`, `status`, `password`, `email`, `address`, `role`) VALUES
(72, '1234', '123', '2025-03-28', 1, '123', 'anvabinh123@gmail.com', 'kaka', 'admin'),
(73, 'kakas', '521123', '2025-03-29', 1, '321', '123@da.com', '123', 'user'),
(74, '124', '124', '2025-03-30', 1, '124', '', '', ''),
(75, '53463', '116', '2025-03-30', 1, '123', '', '', ''),
(76, '3214', '51512', '2025-03-30', 1, '123', '123@dsa.com', 'dfdvdvf', 'user'),
(77, '532525', '21352135', '2025-03-30', 1, '4321', '52135@gmail.com', '423235', 'user'),
(78, 'jaja', '123456', '2025-03-31', 1, '123', 'ikik@gmail.com', 'c5', 'user'),
(79, 'haha', '56116', '2025-03-31', 1, '123', '1515@dsa.com', 'kaka', 'user'),
(80, '543', '15151', '2025-03-31', 1, '123', 'anvabinh123@gmail.com', 'dsds', 'user');

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
-- Table structure for table `loaiproducts`
--

CREATE TABLE `loaiproducts` (
  `id` int(255) NOT NULL,
  `namelsp` varchar(255) NOT NULL,
  `dongsp` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nhanvien`
--

CREATE TABLE `nhanvien` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `loainv` int(20) NOT NULL
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
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `status` enum('chuaxuly','daxuly','dagiao','chuagiao') NOT NULL DEFAULT 'chuaxuly',
  `recipient_name` varchar(255) NOT NULL,
  `recipient_phone` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `total`, `note`, `payment_method`, `delivery_type`, `created_at`, `status`, `recipient_name`, `recipient_phone`, `address`) VALUES
(70, 72, '123.00', '321', 'Tiền mặt', 'Giao tận nơi', '2025-03-28', 'chuaxuly', '321', '321', ''),
(71, 72, '861.00', '321', 'Tiền mặt', 'Giao tận nơi', '2025-03-29', 'dagiao', '321', '321', ''),
(72, 73, '10692.00', '124', 'Tiền mặt', 'Giao tận nơi', '2025-03-29', 'dagiao', '421', '421', ''),
(73, 72, '2481.00', '321', 'Tiền mặt', 'Giao tận nơi', '2025-03-30', 'daxuly', '312', '123', '123123'),
(74, 72, '99999999.99', '', 'Tiền mặt', 'Giao tận nơi', '2025-03-31', 'chuaxuly', '123', '123', ''),
(75, 72, '99999999.99', '123', 'Tiền mặt', 'Mua trực tiếp', '2025-03-31', 'chuaxuly', 'ronaldo', '1245113', '273 An Dương Vương'),
(76, 72, '1875000000', '421', 'Chuyển khoản', 'Giao tận nơi', '2025-03-31', 'daxuly', '5252525235235', '421', ''),
(77, 72, '1875000000', '', 'Tiền mặt', 'Mua trực tiếp', '2025-03-31', 'chuaxuly', '1235151551512', '12421412', '105 Bà Huyện Thanh Quan'),
(78, 78, '570900000', '123', 'Chuyển khoản', 'Mua trực tiếp', '2025-03-31', 'chuaxuly', 'kakas', '5252', '273 An Dương Vương');

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
(81, 70, 88, 1, '123.00'),
(82, 71, 88, 7, '123.00'),
(83, 72, 97, 33, '324.00'),
(84, 73, 88, 7, '123.00'),
(85, 73, 97, 5, '324.00'),
(86, 74, 97, 1, '99999999.99'),
(87, 74, 88, 1, '99999999.99'),
(88, 75, 97, 3, '99999999.99'),
(89, 76, 105, 1, '99999999.99'),
(90, 77, 105, 1, '1875000000'),
(91, 78, 103, 1, '250000000'),
(92, 78, 97, 1, '320900000');

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
(88, 'Z650RS ABS', 'Dòng Z', '1', 233000000, '123', '321', 'uploads/z650rsabs.jpg', 'uploads/z650rsabs2.jpg', 'uploads/z650rsabs3.jpg'),
(97, 'Z900 ABS', 'Dòng Z', '1', 320900000, '124', '124', 'uploads/z900abs.png', '', ''),
(101, 'NINJA 500 SE', 'Dòng Ninja', '1', 194000000, 'ss', 'ss', 'uploads/0_15.jpg', 'uploads/top.jpg', 'uploads/meter.jpg'),
(102, 'NINJA 650 ABS', 'Dòng Ninja', '1', 210000000, '', '', 'uploads/0_152.jpg', 'uploads/top2.jpg', 'uploads/meter2.jpg'),
(103, 'NINJA ZX-4R', 'Dòng Ninja', '1', 250000000, '', '', 'uploads/0_153.jpg', 'uploads/top3.jpg', 'uploads/meter3.jpg'),
(104, 'NINJA ZX-10R ABS', 'Dòng Ninja', '1', 765700000, '', '', 'uploads/n-zx10r.jpg', 'uploads/n-zx10r2.jpg', 'uploads/n-zx10r3.jpg'),
(105, 'NINJA H2R', 'Dòng Ninja', '1', 1875000000, '', '', 'uploads/n-h2rc.png', '', ''),
(106, 'Z500 ABS', 'Dòng Z', '1', 170600000, '', '', 'uploads/z500abs.jpg', 'uploads/z500abs2.jpg', 'uploads/z500abs3.jpg'),
(107, 'Z650 ABS', 'Dòng Z', '1', 194000000, '', '', 'uploads/z650abs.jpg', 'uploads/z650abs2.jpg', 'uploads/z650abs3.jpg'),
(108, 'Z900RS', 'Dòng Z', '1', 480800000, '', '', 'uploads/Z900RS.jpg', 'uploads/Z900RS2.jpg', 'uploads/Z900RS3.jpg'),
(109, 'Z H2 SE', 'Dòng Z', '1', 789300000, '', '', 'uploads/ZH2SE.jpg', 'uploads/ZH2SE2.jpg', 'uploads/ZH2SE3.jpg'),
(110, 'KLX230S', 'Dòng KLX', '1', 151000000, '', '', 'uploads/KLX230S.png', '', ''),
(111, 'KLX230SM', 'Dòng KLX', '1', 151000000, '', '', 'uploads/KLX230SM.png', '', ''),
(112, 'KLX230R', 'Dòng KLX', '1', 148700000, 'KLX230R sẽ đưa những chuyến phiêu lưu của bạn đến tầm cao mới. Với hệ thống treo hành trình dài và khoảng sáng gầm xe lớn, cùng trọng lượng nhẹ, KLX230R – mẫu xe cào cào đích thực – được sinh ra để phục vụ những tay lái mong muốn tìm kiếm niềm vui ở những nơi đầy cát-gió-bùn đất một cách tuyệt vời nhất.', '', 'uploads/KLX230R.jpg', 'uploads/KLX230R2.jpg', 'uploads/KLX230R3.jpg'),
(113, 'KLX110R', 'Dòng KLX', '1', 75000000, 'Dù là dành cho người mới bắt đầu hay đơn thuần chỉ là công cụ giải trí, KLX110R L luôn sẵn sàng cho nhiệm vụ. Động cơ 112 cm³ vui tươi và khung gầm nhỏ gọn đủ linh hoạt để xử lý và thú vị cho bất kỳ tay đua trẻ nào.', '', 'uploads/KLX110R.jpg', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

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
-- Indexes for table `loaiproducts`
--
ALTER TABLE `loaiproducts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `giohang`
--
ALTER TABLE `giohang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT for table `loaiproducts`
--
ALTER TABLE `loaiproducts`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nhanvien`
--
ALTER TABLE `nhanvien`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`);

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
