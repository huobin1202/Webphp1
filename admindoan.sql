-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 28, 2025 at 05:43 PM
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
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact` int(255) NOT NULL,
  `joindate` date NOT NULL,
  `status` int(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `ghichu` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `name`, `contact`, `joindate`, `status`, `password`, `ghichu`) VALUES
(27, '65745674567456', 2147483647, '2025-02-26', 0, '123', NULL),
(28, '123', 123, '2025-02-26', 0, '$2y$10$L2HhFVm3hY2HxVpjUWWq4O1wGlanl6i8CDLq81F5HplnDFDrBpfaq', NULL),
(29, '1234', 1234, '2025-02-26', 0, '$2y$10$tj/HRe1FJfJuzKhb8cApM.slOhtY1xIzepHJbRdovDP.VzTdzI0sO', NULL),
(31, '12345', 12345, '2025-02-28', 0, '$2y$10$W1LDNKdC7E925X6Os4Zypur337kN4b/baPgmwkeafMPIJNsrewcuO', NULL),
(32, '5454', 5454, '2025-02-28', 0, '$2y$10$rRgk82isoTbQCIbgLZIvxuzQaxddwN1B2lwvXzUISbCLGr0c3v7ay', NULL),
(33, 'haha', 34563456, '2025-02-28', 0, '$2y$10$J3zIUdhQ67hE2hkivQ8D0encIXS96ynOXkOiEHSgo1OCbpZxf/ou.', NULL),
(34, '4214124', 123124, '2025-02-28', 0, '123', NULL),
(35, '8762847', 562456, '2025-02-28', 0, '876', NULL),
(36, 'binhho', 52645256, '2025-02-28', 0, '123', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `gio_hang`
--

CREATE TABLE `gio_hang` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `soluong` int(11) NOT NULL DEFAULT 1
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
(71, 'BÌnh', 'Dòng Ninja', '', 34444, 'sfbfb', 'sdfbsdfb', 'uploads/klx-230sm.png', 'uploads/ninja-650.png', 'uploads/z-h2.png'),
(72, 'nà', 'Dòng Z', '', 234234, 'jfiheiv', 'kjenrvjre', 'uploads/ninja-650.png', 'uploads/z-h2.png', 'uploads/klx-230sm.png'),
(73, 'kilian mpappe', 'Dòng Ninja', NULL, 634853, 'evrfvwev', 'ưverwverv', 'uploads/z7-hybrid.png', 'uploads/klx-110r.png', 'uploads/klx-230.png'),
(74, 'bruh', 'Dòng Ninja', NULL, 234234, '234234', '235235', 'uploads/klx-230sm.png', 'uploads/klx-230.png', 'uploads/ninja-650.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `contact` (`contact`);

--
-- Indexes for table `gio_hang`
--
ALTER TABLE `gio_hang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `loaiproducts`
--
ALTER TABLE `loaiproducts`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `gio_hang`
--
ALTER TABLE `gio_hang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loaiproducts`
--
ALTER TABLE `loaiproducts`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `gio_hang`
--
ALTER TABLE `gio_hang`
  ADD CONSTRAINT `gio_hang_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gio_hang_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
