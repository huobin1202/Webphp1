-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 26, 2025 at 05:18 PM
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
  `city_code` varchar(10) DEFAULT NULL,
  `city_name` varchar(100) DEFAULT NULL,
  `district_code` varchar(10) DEFAULT NULL,
  `district_name` varchar(100) DEFAULT NULL,
  `ward_code` varchar(10) DEFAULT NULL,
  `ward_name` varchar(100) DEFAULT NULL,
  `street_address` varchar(255) DEFAULT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `name`, `contact`, `joindate`, `status`, `password`, `email`, `address`, `city_code`, `city_name`, `district_code`, `district_name`, `ward_code`, `ward_name`, `street_address`, `role`) VALUES
(72, '1234', '123', '2025-03-28', 1, '123', 'anvabinh123@gmail.com', 'C5/10B, Huyện Chiêm Hóa, Tỉnh Tuyên Quang', '8', 'Tỉnh Tuyên Quang', '73', 'Huyện Chiêm Hóa', '2347', '', 'C5/10B', 'admin'),
(73, 'kakas', '521123', '2025-03-29', 1, '321', '123@da.com', '123', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user'),
(74, '124', '124', '2025-03-30', 1, '124', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, ''),
(75, '53463', '116', '2025-03-30', 1, '123', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, ''),
(76, '3214', '51512', '2025-03-30', 1, '123', '123@dsa.com', 'dfdvdvf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user'),
(77, '532525', '21352135', '2025-03-30', 1, '4321', '52135@gmail.com', '423235', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user'),
(78, 'jaja', '123456', '2025-03-31', 1, '123', 'ikik@gmail.com', 'c5', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user'),
(79, 'haha', '56116', '2025-03-31', 1, '123', '1515@dsa.com', 'kaka', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user'),
(80, '543', '15151', '2025-03-31', 1, '123', 'anvabinh123@gmail.com', 'dsds', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user'),
(81, 'dada', '52352', '2025-04-01', 1, '123', '123@dsa.com', '12515', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user'),
(82, 'kdfjgid', '124124', '2025-04-03', 1, '123', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, ''),
(83, 'jajaja', '346346', '2025-04-16', 1, '321', 'dsadsa@gmail.com', '141f1f', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user'),
(84, 'mizu', '456456', '2025-04-16', 1, '123', '123@gmail.com', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user'),
(85, 'uytuty', '525235', '2025-04-16', 1, '123', 'koka@gmail.com', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user');

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
  `status` enum('chuaxuly','daxuly','dagiao','dahuy') NOT NULL DEFAULT 'chuaxuly',
  `recipient_name` varchar(255) NOT NULL,
  `recipient_phone` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `total`, `note`, `payment_method`, `delivery_type`, `created_at`, `status`, `recipient_name`, `recipient_phone`, `address`) VALUES
(70, 72, '123.00', '321', 'Tiền mặt', 'Giao tận nơi', '2025-03-28', '', '321', '321', ''),
(71, 72, '861.00', '321', 'Tiền mặt', 'Giao tận nơi', '2025-03-29', 'dahuy', '321', '321', ''),
(72, 73, '10692.00', '124', 'Tiền mặt', 'Giao tận nơi', '2025-03-29', 'dagiao', '421', '421', ''),
(73, 72, '2481.00', '321', 'Tiền mặt', 'Giao tận nơi', '2025-03-30', 'daxuly', '312', '123', '123123'),
(74, 72, '99999999.99', '', 'Tiền mặt', 'Giao tận nơi', '2025-03-31', 'dahuy', '123', '123', ''),
(75, 72, '99999999.99', '123', 'Tiền mặt', 'Mua trực tiếp', '2025-03-31', 'dahuy', 'ronaldo', '1245113', '273 An Dương Vương'),
(76, 72, '1875000000', '421', 'Chuyển khoản', 'Giao tận nơi', '2025-03-31', 'dahuy', '5252525235235', '421', ''),
(77, 72, '1875000000', '', 'Tiền mặt', 'Mua trực tiếp', '2025-03-31', '', '1235151551512', '12421412', '105 Bà Huyện Thanh Quan'),
(78, 78, '570900000', '123', 'Chuyển khoản', 'Mua trực tiếp', '2025-03-31', '', 'kakas', '5252', '273 An Dương Vương'),
(79, 78, '765700000', '123', 'Tiền mặt', 'Mua trực tiếp', '2025-04-02', 'dahuy', '123', '123', '273 An Dương Vương'),
(80, 78, '233000000', '321', 'Tiền mặt', 'Mua trực tiếp', '2025-04-02', 'dahuy', '321', '321', '273 An Dương Vương'),
(81, 72, '320900000', '234', 'Tiền mặt', 'Mua trực tiếp', '2025-04-03', 'dahuy', '234', '234', '273 An Dương Vương'),
(82, 72, '1531400000', '123', 'Tiền mặt', 'Mua trực tiếp', '2025-04-03', 'dahuy', '123', '321', '273 An Dương Vương'),
(83, 72, '148700000', '', 'Tiền mặt', 'Mua trực tiếp', '2025-04-05', 'daxuly', '432', '532', '273 An Dương Vương'),
(84, 72, '320900000', '123', 'Tiền mặt', 'Giao tận nơi', '2025-04-05', 'daxuly', '321', '123', ''),
(85, 72, '320900000', '124', 'Tiền mặt', 'Mua trực tiếp', '2025-04-05', 'dahuy', '321', '412', '273 An Dương Vương'),
(86, 72, '320900000', '632', 'Tiền mặt', 'Giao tận nơi', '2025-04-05', 'dahuy', '632', '632', ''),
(87, 72, '320900000', '125125', 'Tiền mặt', 'Mua trực tiếp', '2025-04-05', 'daxuly', '512512', '12515', '273 An Dương Vương'),
(88, 72, '233000000', '124', 'Tiền mặt', 'Mua trực tiếp', '2025-04-05', 'dagiao', 'siu nhan gao', '4242424242', '105 Bà Huyện Thanh Quan'),
(89, 72, '250000000', '42114', 'Chuyển khoản', 'Giao tận nơi', '2025-04-05', 'dahuy', 'hahaha', '421312312412414124', ''),
(90, 72, '233000000', '421', 'Tiền mặt', 'Giao tận nơi', '2025-04-05', 'chuaxuly', '421', '421', ''),
(91, 72, '194000000', '', 'Tiền mặt', '273 An Dương Vương', '2025-04-05', 'dahuy', '424124', '4214144', '273 An Dương Vương'),
(92, 72, '320900000', '123', 'Tiền mặt', '273 An Dương Vương', '2025-04-05', 'daxuly', '123', '123', '273 An Dương Vương'),
(93, 72, '233000000', '123123', 'Chuyển khoản', '273 An Dương Vương', '2025-04-05', 'daxuly', '1231', '123', '273 An Dương Vương'),
(94, 72, '233000000', '124124', 'Tiền mặt', '04 Tôn Đức Thắng', '2025-04-05', 'dahuy', '123123', '412412414', '04 Tôn Đức Thắng'),
(95, 72, '150000000', '', 'Tiền mặt', 'Giao tận nơi', '2025-04-05', 'daxuly', '123', '123', ''),
(96, 72, '233000000', '543', 'Tiền mặt', 'Mua trực tiếp', '2025-04-05', 'daxuly', '543', '345', '105 Bà Huyện Thanh Quan'),
(97, 72, '194000000', '321', 'Chuyển khoản', 'Giao tận nơi', '2025-04-05', 'dahuy', '123123', '123123', ''),
(98, 72, '320900000', '231', 'Tiền mặt', 'Mua trực tiếp', '2025-04-05', 'dahuy', '2321', '321', '04 Tôn Đức Thắng'),
(99, 72, '320900000', '123', 'Tiền mặt', 'Mua trực tiếp', '2025-04-05', 'dahuy', 'kaka', '123', '105 Bà Huyện Thanh Quan'),
(100, 72, '194000000', '321', 'Chuyển khoản', 'Giao tận nơi', '2025-04-05', 'dahuy', '321', '321', ''),
(101, 72, '194000000', 'ada', 'Chuyển khoản', 'Giao tận nơi', '2025-04-05', 'daxuly', 'ád', '123', ''),
(102, 72, '194000000', '123', 'Tiền mặt', 'Giao tận nơi', '2025-04-05', 'dahuy', '321', '321', ''),
(103, 72, '320900000', '124124', 'Tiền mặt', 'Giao tận nơi', '2025-04-05', 'chuaxuly', '123', '123', 'kaka'),
(104, 72, '320900000', '151515', 'Tiền mặt', 'Giao tận nơi', '2025-04-05', 'chuaxuly', '4214124', '12312312', 'kaka'),
(105, 72, '320900000', '234', 'Tiền mặt', 'Giao tận nơi', '2025-04-05', 'chuaxuly', '342234', '234234', 'kaka'),
(106, 72, '320900000', '123', 'Tiền mặt', 'Giao tận nơi', '2025-04-05', 'chuaxuly', '123', '123', 'kaka'),
(107, 72, '194000000', '234234', 'Chuyển khoản', 'Giao tận nơi', '2025-04-05', 'chuaxuly', '234234', '234234', 'kaka'),
(108, 72, '250000000', '123123', 'Tiền mặt', 'Giao tận nơi', '2025-04-05', 'chuaxuly', '123', '123', 'kaka'),
(109, 72, '194000000', '123', 'Chuyển khoản', 'Giao tận nơi', '2025-04-05', 'chuaxuly', '123', '123', 'kaka'),
(110, 72, '250000000', '123123', 'Tiền mặt', 'Giao tận nơi', '2025-04-05', 'chuaxuly', '123123', '123', 'kaka'),
(111, 72, '194000000', '11111111111111111111111111', 'Chuyển khoản', 'Giao tận nơi', '2025-04-05', 'chuaxuly', '11111111111111111111', '11111111111111111111', 'kaka'),
(112, 72, '765700000', '34534535', 'Tiền mặt', 'Giao tận nơi', '2025-04-05', 'chuaxuly', '5435345', '45345345', 'kaka'),
(113, 72, '194000000', '234234', 'Tiền mặt', 'Giao tận nơi', '2025-04-05', 'chuaxuly', '234234', '234234', '123'),
(114, 72, '75000000', 'agag', 'Tiền mặt', 'Giao tận nơi', '2025-04-05', 'chuaxuly', '123', '123', 'agagagag'),
(115, 72, '75000000', '123', 'Tiền mặt', 'Mua trực tiếp', '2025-04-05', 'chuaxuly', 'haha do thái', '123', 'Mua trực tiếp'),
(116, 72, '320900000', '', 'Tiền mặt', 'Mua trực tiếp', '2025-04-05', 'chuaxuly', '123', '421', 'Mua trực tiếp'),
(117, 72, '320900000', '234', 'Tiền mặt', 'Giao tận nơi', '2025-04-05', 'chuaxuly', '234', '234', 'kaka'),
(118, 72, '250000000', '234234', 'Chuyển khoản', 'Giao tận nơi', '2025-04-05', 'chuaxuly', '342234', '234', '23424'),
(119, 72, '194000000', '123', 'Tiền mặt', 'Mua trực tiếp', '2025-04-05', 'chuaxuly', 'haha', '321', 'Mua trực tiếp'),
(120, 72, '233000000', '123', 'Tiền mặt', 'Giao tận nơi', '2025-04-05', 'chuaxuly', 'haha', '123', 'haha'),
(121, 72, '75000000', '123', 'Tiền mặt', 'Giao tận nơi', '2025-04-05', 'chuaxuly', '123', '123', 'kaka'),
(122, 72, '765700000', '123', 'Tiền mặt', 'Mua trực tiếp', '2025-04-05', 'chuaxuly', '321', '321', 'Mua trực tiếp'),
(123, 72, '194000000', '321', 'Tiền mặt', 'Mua trực tiếp', '2025-04-05', 'chuaxuly', '123', '123', '04 Tôn Đức Thắng'),
(124, 83, '233000000', '', 'Tiền mặt', 'Giao tận nơi', '2025-04-16', 'daxuly', 'bình', '124124', 'haha'),
(125, 79, '308000000', '', 'Tiền mặt', 'Mua trực tiếp', '2025-04-16', 'dahuy', '321', '123', '273 An Dương Vương'),
(126, 79, '320900000', '', 'Tiền mặt', 'Mua trực tiếp', '2025-04-26', 'dahuy', '321', '123', '273 An Dương Vương');

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
(92, 78, 97, 1, '320900000'),
(93, 79, 104, 1, '765700000'),
(94, 80, 88, 1, '233000000'),
(95, 81, 97, 1, '320900000'),
(96, 82, 104, 2, '765700000'),
(97, 83, 112, 1, '148700000'),
(98, 84, 97, 1, '320900000'),
(99, 85, 97, 1, '320900000'),
(100, 86, 97, 1, '320900000'),
(101, 87, 97, 1, '320900000'),
(102, 88, 88, 1, '233000000'),
(103, 89, 103, 1, '250000000'),
(104, 90, 88, 1, '233000000'),
(105, 91, 101, 1, '194000000'),
(106, 92, 97, 1, '320900000'),
(107, 93, 88, 1, '233000000'),
(108, 94, 88, 1, '233000000'),
(109, 95, 113, 2, '75000000'),
(110, 96, 88, 1, '233000000'),
(111, 97, 101, 1, '194000000'),
(112, 98, 97, 1, '320900000'),
(113, 99, 97, 1, '320900000'),
(114, 100, 101, 1, '194000000'),
(115, 101, 101, 1, '194000000'),
(116, 102, 101, 1, '194000000'),
(117, 103, 97, 1, '320900000'),
(118, 104, 97, 1, '320900000'),
(119, 105, 97, 1, '320900000'),
(120, 106, 97, 1, '320900000'),
(121, 107, 101, 1, '194000000'),
(122, 108, 103, 1, '250000000'),
(123, 109, 101, 1, '194000000'),
(124, 110, 103, 1, '250000000'),
(125, 111, 101, 1, '194000000'),
(126, 112, 104, 1, '765700000'),
(127, 113, 101, 1, '194000000'),
(128, 114, 113, 1, '75000000'),
(129, 115, 113, 1, '75000000'),
(130, 116, 97, 1, '320900000'),
(131, 117, 97, 1, '320900000'),
(132, 118, 103, 1, '250000000'),
(133, 119, 101, 1, '194000000'),
(134, 120, 88, 1, '233000000'),
(135, 121, 113, 1, '75000000'),
(136, 122, 104, 1, '765700000'),
(137, 123, 101, 1, '194000000'),
(138, 124, 88, 1, '233000000'),
(139, 125, 113, 1, '75000000'),
(140, 125, 88, 1, '233000000'),
(141, 126, 97, 1, '320900000');

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

-- --------------------------------------------------------

--
-- Table structure for table `provinces`
--

CREATE TABLE `provinces` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(10) NOT NULL,
  `province_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `wards`
--

CREATE TABLE `wards` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(10) NOT NULL,
  `district_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for table `provinces`
--
ALTER TABLE `provinces`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `province_id` (`province_id`);

--
-- Indexes for table `wards`
--
ALTER TABLE `wards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `district_id` (`district_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `provinces`
--
ALTER TABLE `provinces`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wards`
--
ALTER TABLE `wards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `districts`
--
ALTER TABLE `districts`
  ADD CONSTRAINT `districts_ibfk_1` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`);

--
-- Constraints for table `wards`
--
ALTER TABLE `wards`
  ADD CONSTRAINT `wards_ibfk_1` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`);

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
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `giohang`
--
ALTER TABLE `giohang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=254;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

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
