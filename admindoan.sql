-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 23, 2025 at 07:02 AM
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
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `name`, `contact`, `joindate`, `status`, `password`, `email`) VALUES
(41, '123', '123', '2025-03-02', 0, '123', ''),
(42, '1234', '1234', '2025-03-02', 0, '1234', ''),
(43, 'Hữu Bình', '12345', '2025-03-02', 1, '12345', ''),
(44, '123456', '123456', '2025-03-02', 0, '123456', ''),
(45, '321', '321', '2025-03-02', 1, '321', ''),
(46, '11345134', '23462456', '2025-03-02', 0, '123', ''),
(47, 'bình', '4321', '2025-03-02', 0, '4321', ''),
(48, '234', '234', '2025-03-02', 1, '234', ''),
(50, 'maxverstappen', '1234567', '2025-03-02', 0, '123', ''),
(51, 'utf8', '2147483647', '2025-03-02', 1, '123', ''),
(52, 'tudutudu', '57824584', '2025-03-02', 0, '123321', ''),
(54, 'hahawtf', '23423434', '2025-03-02', 1, '123', ''),
(55, 'lingang', '62162126', '2025-03-02', 1, '123', ''),
(56, 'wata', '82244228', '2025-03-02', 1, '123', ''),
(57, 'kaka', '876235482', '2025-03-02', 1, '123', ''),
(60, '11111111111111111111', '123123144', '2025-03-03', 1, '1111111111111111111111111111111111', ''),
(61, 'Hồ Phạm Hữu Bình iah', '1515151', '2025-03-03', 1, '123', ''),
(62, 'omgnoway', '121212', '2025-03-07', 1, '123', ''),
(65, 'hgf', '12312312312', '2025-03-22', 1, 'hgf', ''),
(66, '22222222222222222222', '876', '2025-03-22', 1, '876', ''),
(67, 'asdadsdafadbaebgaebr', '13462364563', '2025-03-22', 1, '123', '');

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

--
-- Dumping data for table `giohang`
--

INSERT INTO `giohang` (`id`, `customer_id`, `product_id`, `soluong`, `price`, `img`) VALUES
(53, 41, 77, 1, 456456, ''),
(87, 42, 78, 1, 456, '');

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

--
-- Dumping data for table `nhanvien`
--

INSERT INTO `nhanvien` (`id`, `name`, `password`, `loainv`) VALUES
(1, 'admin', 'admin', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `delivery_type` varchar(50) DEFAULT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL,
  `recipient_name` varchar(255) NOT NULL,
  `recipient_phone` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `total`, `note`, `payment_method`, `delivery_type`, `created_at`, `status`, `recipient_name`, `recipient_phone`, `address`) VALUES
(3, 41, 5477472.00, '', 'Tiền mặt', '273 An Dương Vương', '2025-03-22', '', '', '', ''),
(4, 41, 456.00, '', 'Tiền mặt', '273 An Dương Vương', '2025-03-22', '', '', '', ''),
(5, 41, 456456.00, 'dsfsdf', 'Chuyển khoản', '273 An Dương Vương', '2025-03-22', '', '', '', ''),
(6, 41, 456456.00, '', 'Tiền mặt', '273 An Dương Vương', '2025-03-22', '', '', '', ''),
(7, 41, 6234234.00, '', 'Tiền mặt', '273 An Dương Vương', '2025-03-22', '', '', '', ''),
(8, 41, 0.00, '', 'Tiền mặt', '273 An Dương Vương', '2025-03-22', '', '', '', ''),
(9, 41, 456.00, 's', 'Tiền mặt', '105 Bà Huyện Thanh Quan', '2025-03-22', '', '', '', ''),
(10, 41, 456.00, 'sdad', 'Tiền mặt', '273 An Dương Vương', '2025-03-22', '', '', '', ''),
(11, 41, 7147146.00, '', 'Tiền mặt', '273 An Dương Vương', '2025-03-22', '', '', '', ''),
(12, 41, 0.00, '', 'Tiền mặt', '273 An Dương Vương', '2025-03-22', '', '', '', ''),
(13, 41, 6234234.00, '', 'Tiền mặt', '273 An Dương Vương', '2025-03-22', '', '', '', ''),
(14, 41, 456.00, '', 'Tiền mặt', '273 An Dương Vương', '2025-03-22', '', '', '', ''),
(15, 41, 456456.00, '123123', 'Tiền mặt', '273 An Dương Vương', '2025-03-22', '', '', '', ''),
(16, 41, 6691146.00, '', 'Tiền mặt', '273 An Dương Vương', '2025-03-22', '', '', '', ''),
(17, 41, 6691146.00, '', 'Tiền mặt', '273 An Dương Vương', '2025-03-22', '', '', '', ''),
(18, 41, 6691146.00, '', 'Tiền mặt', '105 Bà Huyện Thanh Quan', '2025-03-22', '', '', '', ''),
(19, 41, 6691146.00, '', 'Tiền mặt', '273 An Dương Vương', '2025-03-22', '', '', '', ''),
(20, 42, 2282280.00, '', 'Tiền mặt', '273 An Dương Vương', '2025-03-22', '0', '', '', ''),
(21, 42, 6234234.00, '34243', 'Tiền mặt', '273 An Dương Vương', '2025-03-22', '0', '', '', ''),
(22, 42, 6691146.00, '6246', 'Tiền mặt', '273 An Dương Vương', '2025-03-22', '0', '', '', ''),
(23, 42, 456.00, '62', 'Tiền mặt', '273 An Dương Vương', '2025-03-23', '0', '', '', ''),
(24, 42, 456456.00, '', 'Tiền mặt', 'Giao tận nơi', '2025-03-23', '0', '', '', ''),
(25, 42, 6234234.00, '567567567', 'Tiền mặt', 'Giao tận nơi', '2025-03-23', '0', '', '', ''),
(26, 42, 6234234.00, '1231313', 'Tiền mặt', 'Giao tận nơi', '2025-03-23', '0', '', '', ''),
(27, 42, 6234234.00, '3424', 'Tiền mặt', 'Giao tận nơi', '2025-03-23', '0', '', '', ''),
(28, 42, 6234234.00, '', 'Tiền mặt', 'Giao tận nơi', '2025-03-23', '0', '', '', ''),
(29, 42, 6691146.00, '23424', 'Tiền mặt', 'Giao tận nơi', '2025-03-23', '0', '', '', ''),
(30, 42, 456456.00, 'dfg', 'Tiền mặt', 'Giao tận nơi', '2025-03-23', '0', '', '', ''),
(31, 42, 456.00, '45434', 'Tiền mặt', 'Giao tận nơi', '2025-03-23', '0', '', '', ''),
(32, 42, 6234234.00, '', 'Tiền mặt', 'Giao tận nơi', '2025-03-23', '0', '', '', ''),
(33, 42, 0.00, '234234', 'Chuyển khoản', 'Giao tận nơi', '2025-03-23', '0', '', '', ''),
(34, 42, 6234234.00, '123', 'Tiền mặt', 'Giao tận nơi', '2025-03-23', '0', '', '', ''),
(35, 42, 6234234.00, '123123', 'Tiền mặt', 'Giao tận nơi', '2025-03-23', '0', '', '', ''),
(36, 42, 456456.00, '345345', 'Tiền mặt', 'Giao tận nơi', '2025-03-23', '0', '', '', ''),
(37, 42, 6234234.00, '345345345345', 'Tiền mặt', '105 Bà Huyện Thanh Quan', '2025-03-23', '0', '', '', ''),
(38, 42, 456.00, '234234', 'Chuyển khoản', '273 An Dương Vương', '2025-03-23', '0', '', '', ''),
(39, 42, 456.00, '342', 'Tiền mặt', '105 Bà Huyện Thanh Quan', '2025-03-23', '0', '', '', ''),
(40, 42, 456456.00, '243', 'Chuyển khoản', '273 An Dương Vương', '2025-03-23', '0', '', '', ''),
(41, 42, 6234234.00, '34g34g', 'Tiền mặt', '04 Tôn Đức Thắng', '2025-03-23', '0', 'bình', '345345', ''),
(42, 42, 456.00, '435345345', 'Chuyển khoản', '273 An Dương Vương', '2025-03-23', '0', 'linggang', '151515', ''),
(43, 42, 0.00, '', 'Tiền mặt', '273 An Dương Vương', '2025-03-23', '0', '', '', ''),
(44, 42, 456.00, '321', 'Chuyển khoản', '273 An Dương Vương', '2025-03-23', '0', '123', '123', ''),
(45, 42, 6234234.00, 'rober', 'Tiền mặt', '04 Tôn Đức Thắng', '2025-03-23', '0', 'kaka', '234234', '04 Tôn Đức Thắng'),
(46, 42, 456.00, 'câc', 'Chuyển khoản', '273 An Dương Vương', '2025-03-23', '0', 'hewhe', '36363', '273 An Dương Vương'),
(47, 42, 456456.00, '234234', 'Chuyển khoản', '273 An Dương Vương', '2025-03-23', '0', '234234', '234234', '273 An Dương Vương'),
(48, 42, 456456.00, '234', 'Tiền mặt', 'Mua trực tiếp', '2025-03-23', '0', '123123123', '123123123', '273 An Dương Vương'),
(49, 42, 456456.00, '345345', 'Chuyển khoản', 'Giao tận nơi', '2025-03-23', '0', '543543', '345345', '345345'),
(50, 42, 456456.00, '234234', 'Tiền mặt', 'Giao tận nơi', '2025-03-23', '0', '234234', '234234', 'vfdvdsfvdfs');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `soluong` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `soluong`, `price`) VALUES
(2, 4, 78, 1, 456.00),
(3, 6, 77, 1, 456456.00),
(4, 7, 74, 1, 6234234.00),
(5, 10, 78, 1, 456.00),
(6, 11, 74, 1, 6234234.00),
(7, 11, 77, 2, 456456.00),
(8, 13, 74, 1, 6234234.00),
(9, 14, 78, 1, 456.00),
(10, 15, 77, 1, 456456.00),
(11, 16, 74, 1, 6234234.00),
(12, 16, 77, 1, 456456.00),
(13, 16, 78, 1, 456.00),
(14, 17, 74, 1, 6234234.00),
(15, 17, 77, 1, 456456.00),
(16, 17, 78, 1, 456.00),
(17, 18, 74, 1, 6234234.00),
(18, 18, 77, 1, 456456.00),
(19, 18, 78, 1, 456.00),
(20, 19, 74, 1, 6234234.00),
(21, 19, 77, 1, 456456.00),
(22, 19, 78, 1, 456.00),
(23, 20, 77, 5, 456456.00),
(24, 21, 74, 1, 6234234.00),
(25, 22, 77, 1, 456456.00),
(27, 22, 74, 1, 6234234.00),
(28, 23, 78, 1, 456.00),
(29, 24, 77, 1, 456456.00),
(30, 25, 74, 1, 6234234.00),
(31, 26, 74, 1, 6234234.00),
(32, 27, 74, 1, 6234234.00),
(33, 28, 74, 1, 6234234.00),
(34, 29, 74, 1, 6234234.00),
(35, 29, 77, 1, 456456.00),
(36, 29, 78, 1, 456.00),
(37, 30, 77, 1, 456456.00),
(38, 31, 78, 1, 456.00),
(39, 32, 74, 1, 6234234.00),
(40, 34, 74, 1, 6234234.00),
(41, 35, 74, 1, 6234234.00),
(42, 36, 77, 1, 456456.00),
(43, 37, 74, 1, 6234234.00),
(44, 38, 78, 1, 456.00),
(45, 39, 78, 1, 456.00),
(46, 40, 77, 1, 456456.00),
(47, 41, 74, 1, 6234234.00),
(48, 42, 78, 1, 456.00),
(49, 44, 78, 1, 456.00),
(50, 45, 74, 1, 6234234.00),
(51, 46, 78, 1, 456.00),
(52, 47, 77, 1, 456456.00),
(53, 48, 77, 1, 456456.00),
(54, 49, 77, 1, 456456.00),
(55, 50, 77, 1, 456456.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `tensp` varchar(255) NOT NULL,
  `dongsp` varchar(255) DEFAULT NULL,
  `mauxe` varchar(255) DEFAULT NULL,
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

INSERT INTO `products` (`id`, `tensp`, `dongsp`, `mauxe`, `giaban`, `thongtinsp`, `thongsokt`, `hinhanh`, `hinhanh2`, `hinhanh3`) VALUES
(74, 'bruh', 'Dòng Ninja', NULL, 6234234, '234234', '235235', 'uploads/klx-230sm.png', 'uploads/klx-230.png', 'uploads/ninja-650.png'),
(77, '45645456', 'Dòng Ninja', NULL, 456456, '546', '456', '', '', ''),
(78, '456', 'Dòng Ninja', NULL, 456, '456', '456', '', '', '');

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
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `giohang`
--
ALTER TABLE `giohang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

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
