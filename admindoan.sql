-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 26, 2025 at 08:52 AM
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
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `contact` text NOT NULL,
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
(41, '123', '532', '2025-03-02', 1, '321', 'anvabinh@dnai.com2', 'C5/10AB321', 'admin'),
(42, '1234', '1234', '2025-03-02', 0, '1234', '', '', ''),
(43, 'Hữu Bình', '12345', '2025-03-02', 1, '12345', '', '', ''),
(44, '123456', '123456', '2025-03-02', 0, '123456', '', '', ''),
(45, '321', '321', '2025-03-02', 1, '321', '', '', ''),
(46, '11345134', '23462456', '2025-03-02', 0, '123', '', '', ''),
(47, 'bình', '4321', '2025-03-02', 0, '4321', '', '', ''),
(48, '234', '234', '2025-03-02', 1, '234', '', '', ''),
(50, 'maxverstappen', '1234567', '2025-03-02', 0, '123', '', '', ''),
(51, 'utf8', '2147483647', '2025-03-02', 1, '123', '', '', ''),
(52, 'tudutudu', '57824584', '2025-03-02', 0, '123321', '', '', ''),
(54, 'hahawtf', '23423434', '2025-03-02', 1, '123', '', '', ''),
(55, 'lingang', '62162126', '2025-03-02', 1, '123', '', '', ''),
(56, 'wata', '82244228', '2025-03-02', 1, '123', '', '', ''),
(57, 'kaka', '876235482', '2025-03-02', 0, '123', '', '', ''),
(60, '11111111111111111111', '123123144', '2025-03-03', 1, '1111111111111111111111111111111111', '', '', ''),
(61, 'Hồ Phạm Hữu Bình iah', '1515151', '2025-03-03', 1, '123', '', '', ''),
(62, 'omgnoway', '121212', '2025-03-07', 1, '123', '', '', ''),
(65, 'hgf', '12312312312', '2025-03-22', 1, 'hgf', '', '', ''),
(66, '22222222222222222222', '876', '2025-03-22', 0, '876', '', '', ''),
(67, 'asdadsdafadbaebgaebr', '13462364563', '2025-03-22', 1, '123', '', '', ''),
(68, '32132131', '12312315415', '2025-03-24', 1, '123', '', '', ''),
(70, '12355', '54353', '2025-03-24', 1, 'beckham', '', '', ''),
(71, '', '', '0000-00-00', 0, '', '', '', ''),
(72, 'neymar', '654456', '2025-03-25', 1, '4321', 'WBF@GMAIL.COM', 'WEF', ''),
(73, 'lingka', '34534536', '2025-03-25', 1, '123', '', '', ''),
(74, 'jaja', '465378', '2025-03-25', 1, '321', 'anvabinh123@gmail.com', '7324', ''),
(75, '6646456', '35235', '2025-03-25', 1, '123', '', '', ''),
(76, '75674', '2646246', '2025-03-25', 1, '123', '', '', ''),
(77, '3252', '532424', '2025-03-25', 1, '123', '', '', ''),
(78, '5345234', '235626', '2025-03-25', 1, '234', '', '', ''),
(79, '64564', '324', '2025-03-25', 1, '5325', '', '', ''),
(80, '543', '235', '2025-03-25', 1, '235', '', '', ''),
(81, '476568', 'sdf', '2025-03-25', 0, 'fgdfg', '', '', ''),
(83, '534534', '12512515', '2025-03-25', 1, '321', '', '', 'user'),
(84, 'asd123', '654234', '2025-03-26', 1, '123', '', '', 'user'),
(85, 'liuliu', '2345262', '2025-03-26', 1, '123', 'haha@gmail.com', 'c5/10 a kk', 'user'),
(86, '235234', '5152546', '2025-03-26', 1, '123', 'kk@gmail.com', 'c5 10 a', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `giohang`
--

CREATE TABLE `giohang` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `soluong` int(255) NOT NULL DEFAULT 1,
  `price` int(255) NOT NULL,
  `img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `giohang`
--

INSERT INTO `giohang` (`id`, `customer_id`, `product_id`, `soluong`, `price`, `img`) VALUES
(116, 74, 83, 16, 123123123, '');

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
(1, 'admin', 'admin', 1),
(2, 'huobin', 'admin', 1);

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
(51, 41, 6691146.00, 'qưeqwe', 'Chuyển khoản', 'Giao tận nơi', '2025-03-23', 'daxuly', 'qưeqwe', 'qưeqwe', 'ưqeqwe'),
(52, 41, 6691146.00, '234', 'Tiền mặt', 'Giao tận nơi', '2025-03-23', '1', '324', '324234', ''),
(53, 41, 6691146.00, '234234', 'Tiền mặt', 'Giao tận nơi', '2025-03-23', '1', '234234', '23424', ''),
(54, 41, 6691146.00, '345', 'Tiền mặt', 'Giao tận nơi', '2025-03-23', '1', '5345345', '345345', '345345'),
(55, 41, 6691146.00, '2343', 'Tiền mặt', 'Giao tận nơi', '2025-03-23', '1', '342234', '234', ''),
(56, 41, 12925380.00, '345345', 'Tiền mặt', 'Giao tận nơi', '2025-03-23', '1', '34534534', '5345345', '345345'),
(57, 41, 6234234.00, '234234', 'Tiền mặt', 'Giao tận nơi', '2025-03-23', '1', '34234', '234234', ''),
(58, 41, 6234234.00, '234243', 'Tiền mặt', 'Giao tận nơi', '2025-03-23', '1', '324234', '234234', ''),
(59, 41, 6234234.00, '', 'Tiền mặt', 'Giao tận nơi', '2025-03-23', '1', '543543', '345345', ''),
(60, 41, 6234234.00, '45', 'Tiền mặt', 'Giao tận nơi', '2025-03-23', '1', '34234', '342234', '34'),
(61, 41, 6234234.00, 'qưe', 'Tiền mặt', 'Giao tận nơi', '2025-03-23', 'daxuly', 'ưqe', 'qưe', ''),
(69, 41, 912912.00, '23626', 'Tiền mặt', 'Giao tận nơi', '2025-03-26', '1', '5325', '23626', '');

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
(56, 51, 77, 1, 456456.00),
(57, 51, 74, 1, 6234234.00),
(58, 51, 78, 1, 456.00),
(59, 52, 74, 1, 6234234.00),
(60, 52, 77, 1, 456456.00),
(61, 52, 78, 1, 456.00),
(62, 53, 74, 1, 6234234.00),
(63, 53, 77, 1, 456456.00),
(64, 53, 78, 1, 456.00),
(65, 54, 74, 1, 6234234.00),
(66, 54, 77, 1, 456456.00),
(67, 54, 78, 1, 456.00),
(68, 55, 74, 1, 6234234.00),
(69, 55, 77, 1, 456456.00),
(70, 55, 78, 1, 456.00),
(71, 56, 74, 2, 6234234.00),
(72, 56, 77, 1, 456456.00),
(73, 56, 78, 1, 456.00),
(74, 57, 74, 1, 6234234.00),
(75, 58, 74, 1, 6234234.00),
(76, 59, 74, 1, 6234234.00),
(77, 60, 74, 1, 6234234.00),
(78, 61, 74, 1, 6234234.00),
(79, 69, 77, 2, 456456.00);

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
(74, 'bruh', 'Dòng Ninja', NULL, 6234234, '234234432', '235235', 'uploads/klx-230sm.png', 'uploads/klx-230.png', 'uploads/ninja-650.png'),
(77, '45645456', 'Dòng Ninja', NULL, 456456, '546', '456', '', '', ''),
(78, '4563', 'Dòng Ninja', NULL, 456, '456', '456', '', '', ''),
(79, '435', 'Dòng Ninja', NULL, 345, '345', '345', 'uploads/klx-300sm.png', '', ''),
(83, '321', 'Dòng Ninja', NULL, 123123123, '123', '321', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `contact` (`contact`) USING HASH;

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
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `giohang`
--
ALTER TABLE `giohang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `loaiproducts`
--
ALTER TABLE `loaiproducts`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nhanvien`
--
ALTER TABLE `nhanvien`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

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
