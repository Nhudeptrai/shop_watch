-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 11, 2025 at 08:14 AM
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
-- Database: `shop_nhuy`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `adminId` int(11) NOT NULL,
  `adminName` varchar(255) NOT NULL,
  `adminEmail` varchar(150) NOT NULL,
  `adminPass` varchar(255) NOT NULL,
  `adminUser` varchar(255) NOT NULL,
  `level` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`adminId`, `adminName`, `adminEmail`, `adminPass`, `adminUser`, `level`) VALUES
(1, 'Như Ý', 'nhuy@gmail.com', '123', 'nhuyadmin', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_brand`
--

CREATE TABLE `tbl_brand` (
  `brandId` int(11) NOT NULL,
  `brandName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_brand`
--

INSERT INTO `tbl_brand` (`brandId`, `brandName`) VALUES
(5, 'Daniel Walington'),
(8, 'Casio'),
(10, 'G-Shock'),
(11, 'Tissot');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cart`
--

CREATE TABLE `tbl_cart` (
  `cartId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `sessionId` varchar(255) NOT NULL,
  `productName` varchar(255) NOT NULL,
  `price` varchar(200) NOT NULL,
  `quantity` int(11) NOT NULL,
  `image` varchar(200) NOT NULL,
  `customer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category`
--

CREATE TABLE `tbl_category` (
  `catId` int(11) NOT NULL,
  `catName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_category`
--

INSERT INTO `tbl_category` (`catId`, `catName`) VALUES
(158, 'Đồng hồ nam'),
(159, 'Đồng hồ nữ'),
(160, 'Đồng hồ bạc'),
(161, 'Đồng hồ mạ vàng');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer`
--

CREATE TABLE `tbl_customer` (
  `id` int(11) NOT NULL,
  `username` varchar(200) NOT NULL,
  `address` varchar(200) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(30) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `fullname` varchar(30) NOT NULL,
  `status` enum('active','locked') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_customer`
--

INSERT INTO `tbl_customer` (`id`, `username`, `address`, `email`, `password`, `phone`, `fullname`, `status`) VALUES
(11, 'Thanh', 'thạnh lộc 19 quận 12', 'a@gmail.com', '123456', '0908775783', 'Phạm Chí Thanh', 'active'),
(12, 'thanh', 'thanh dep trai', 'user@gmail.com', '123', '0908775783', 'thanh', 'active'),
(13, 'fff', 'fff', 'fff@fff.fff', '123456', '0909900099', 'fff', 'active'),
(15, 'ttsang793', '123', '123@gmail.com', '123123', '0829924731', 'Trần Tuấn Sang', ''),
(16, 'alibaba123', '123', '1234@gmail.com', '123123', '0123123123', 'Alibaba Smith', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order`
--

CREATE TABLE `tbl_order` (
  `id` int(11) NOT NULL,
  `orderDate` datetime NOT NULL,
  `address` longtext NOT NULL,
  `totalPrice` float NOT NULL,
  `status` varchar(150) NOT NULL,
  `customerId` int(11) NOT NULL,
  `payment_method` enum('money','credit-card') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_order`
--

INSERT INTO `tbl_order` (`id`, `orderDate`, `address`, `totalPrice`, `status`, `customerId`, `payment_method`) VALUES
(31, '2025-04-24 00:00:00', 'thạnh lộc 19 quận 12', 1500000, 'Đã hủy', 11, 'money'),
(32, '2025-04-26 08:15:47', 'thạnh lộc 19 quận 12', 1500000, 'Đã hoàn thành', 11, 'money'),
(33, '2025-05-02 08:18:01', 'thạnh lộc 19 quận 12', 1100000, 'Đã xác nhận', 11, 'credit-card'),
(34, '2025-05-07 09:33:23', 'fff', 9258010, 'Đã hủy', 13, 'money'),
(35, '2025-05-09 09:53:11', '123 Dã Tượng, P. Chánh Hưng, TP.HCM', 4000000, 'Đã hoàn thành', 13, 'credit-card'),
(36, '2025-05-11 13:09:15', 'fff', 11, 'Đã hoàn thành', 13, 'credit-card'),
(37, '2025-05-11 13:12:14', 'thạnh lộc 19 quận 12', 8500000, 'Đã hoàn thành', 12, 'money');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_details`
--

CREATE TABLE `tbl_order_details` (
  `id` int(11) NOT NULL,
  `orderId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `sessionId` int(11) NOT NULL,
  `customerId` int(11) NOT NULL,
  `price` float NOT NULL,
  `productName` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_order_details`
--

INSERT INTO `tbl_order_details` (`id`, `orderId`, `productId`, `quantity`, `sessionId`, `customerId`, `price`, `productName`, `image`) VALUES
(34, 31, 96, 1, 0, 11, 1500000, 'Đồng hồ Daniel Wallington nữ', '798d9bdc5e.png'),
(35, 32, 96, 1, 0, 11, 1500000, 'Đồng hồ Daniel Wallington nữ', '798d9bdc5e.png'),
(36, 33, 90, 1, 0, 11, 1100000, 'Đồng Hồ Nam Classic', '55368070fc.png'),
(37, 34, 145, 1, 0, 13, 11, 'aa', '32b5f8e2cf.png'),
(38, 34, 144, 1, 0, 13, 9258000, 'a1', '798d9bdc5e.png'),
(39, 35, 91, 1, 0, 13, 4000000, 'Đồng Hồ Nam Classic', 'c55372addc.png'),
(40, 36, 145, 1, 0, 13, 11, 'aa', '32b5f8e2cf.png'),
(41, 37, 141, 1, 0, 12, 8500000, 'Đồng hồ 141', '55c5c49857.png');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product`
--

CREATE TABLE `tbl_product` (
  `productId` int(11) NOT NULL,
  `productName` tinytext NOT NULL,
  `catId` int(11) NOT NULL,
  `brandId` int(11) NOT NULL,
  `product_desc` text NOT NULL,
  `type_pd` int(11) NOT NULL,
  `price` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_product`
--

INSERT INTO `tbl_product` (`productId`, `productName`, `catId`, `brandId`, `product_desc`, `type_pd`, `price`, `image`, `isActive`) VALUES
(85, 'Đồng hồ Daniel Wallington nữ', 159, 5, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '1000000', '30a6f02fc7.png', 1),
(86, 'Đồng hồ Daniel Wallington nữ', 159, 5, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '1500000', 'e0e8b54276.png', 1),
(87, 'Đồng hồ Daniel Wallington nữ', 159, 5, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '90000', '922af61852.png', 1),
(88, 'Đồng hồ Daniel Wallington nữ', 159, 5, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '2000000', '0bdc39349e.png', 1),
(89, 'Đồng Hồ Nam Classic', 158, 11, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 1, '1100000', 'e4b88f3eab.png', 1),
(90, 'Đồng Hồ Nam Classic', 158, 11, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 1, '1100000', '55368070fc.png', 1),
(91, 'Đồng Hồ Nam Classic', 158, 11, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 1, '4000000', 'c55372addc.png', 1),
(92, 'Đồng Hồ Nam Classic', 158, 11, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 1, '500000000', '1211a16801.png', 1),
(93, 'Đồng hồ Daniel Wallington nữ', 161, 5, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '8000', '55c5c49857.png', 1),
(95, 'Đồng hồ Daniel Wallington nữ', 161, 10, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '1000000', 'ad560ea3e4.png', 1),
(96, 'Đồng hồ Daniel Wallington nữ', 161, 10, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '1500000', '798d9bdc5e.png', 1),
(97, 'Đồng hồ 97', 160, 5, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '3570000', '30a6f02fc7.png', 1),
(98, 'Đồng hồ 98', 160, 5, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '6514000', 'e0e8b54276.png', 1),
(99, 'Đồng hồ 99', 160, 5, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '2006000', '922af61852.png', 1),
(100, 'Đồng hồ 100', 160, 5, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '8499000', '0bdc39349e.png', 1),
(101, 'Đồng hồ 101', 160, 5, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '4716000', 'e4b88f3eab.png', 1),
(102, 'Đồng hồ 102', 160, 5, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '8155000', '55368070fc.png', 1),
(103, 'Đồng hồ 103', 160, 5, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '3697000', 'c55372addc.png', 1),
(104, 'Đồng hồ 104', 160, 5, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '9413000', '1211a16801.png', 1),
(105, 'Đồng hồ 105', 160, 8, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '6659000', '55c5c49857.png', 1),
(106, 'Đồng hồ 106', 160, 8, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '3473000', '135f66e06d.png', 1),
(107, 'Đồng hồ 107', 161, 8, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '5981000', 'ad560ea3e4.png', 1),
(108, 'Đồng hồ 108', 161, 8, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '6074000', '798d9bdc5e.png', 1),
(109, 'Đồng hồ 109', 161, 8, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '4085000', '30a6f02fc7.png', 1),
(110, 'Đồng hồ 110', 161, 8, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '9678000', 'e0e8b54276.png', 1),
(111, 'Đồng hồ 111', 161, 8, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '8755000', '922af61852.png', 1),
(112, 'Đồng hồ 112', 161, 8, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '6184000', '0bdc39349e.png', 1),
(113, 'Đồng hồ 113', 161, 8, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '2942000', 'e4b88f3eab.png', 1),
(114, 'Đồng hồ 114', 161, 11, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '8396000', '55368070fc.png', 1),
(115, 'Đồng hồ 115', 161, 11, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '2872000', 'c55372addc.png', 1),
(116, 'Đồng hồ 116', 161, 11, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '3164000', '1211a16801.png', 1),
(117, 'Đồng hồ 117', 161, 11, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '6954000', '55c5c49857.png', 1),
(118, 'Đồng hồ 118', 161, 11, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '6084000', '135f66e06d.png', 1),
(119, 'Đồng hồ 119', 161, 11, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '3897000', 'ad560ea3e4.png', 1),
(120, 'Đồng hồ 120', 161, 11, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '5591000', '798d9bdc5e.png', 1),
(121, 'Đồng hồ 121', 161, 11, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '6126000', '30a6f02fc7.png', 1),
(122, 'Đồng hồ 122', 161, 11, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '6453000', 'e0e8b54276.png', 1),
(123, 'Đồng hồ 123', 161, 11, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '9988000', '922af61852.png', 1),
(124, 'Đồng hồ 124', 161, 11, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '7552000', '0bdc39349e.png', 1),
(125, 'Đồng hồ 125', 161, 11, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '9070000', 'e4b88f3eab.png', 1),
(126, 'Đồng hồ 126', 161, 11, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '9499000', '55368070fc.png', 1),
(127, 'Đồng hồ 127', 158, 5, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '9486000', 'c55372addc.png', 1),
(128, 'Đồng hồ 128', 158, 5, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '9208000', '1211a16801.png', 1),
(129, 'Đồng hồ 129', 158, 5, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '8209000', '55c5c49857.png', 1),
(130, 'Đồng hồ 130', 158, 5, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '8917000', '135f66e06d.png', 1),
(131, 'Đồng hồ 131', 158, 8, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '8385000', 'ad560ea3e4.png', 1),
(133, 'Đồng hồ 133', 158, 8, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '3001000', '30a6f02fc7.png', 1),
(134, 'Đồng hồ 134', 158, 8, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '6802000', 'e0e8b54276.png', 1),
(135, 'Đồng hồ 135', 158, 10, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '9711000', '922af61852.png', 1),
(136, 'Đồng hồ 136', 158, 10, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '6645000', '0bdc39349e.png', 1),
(137, 'Đồng hồ 137', 158, 10, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '2059000', 'e4b88f3eab.png', 1),
(138, 'Đồng hồ 138', 158, 10, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '4981000', '55368070fc.png', 1),
(139, 'Đồng hồ 139', 159, 10, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '5832000', 'c55372addc.png', 1),
(140, 'Đồng hồ 140', 159, 10, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '7826000', '1211a16801.png', 1),
(141, 'Đồng hồ 141', 159, 10, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '8500000', '55c5c49857.png', 1),
(143, 'a2', 159, 10, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '4901000', 'ad560ea3e4.png', 1),
(144, 'a1', 159, 10, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '9258000', '798d9bdc5e.png', 1),
(145, 'aa', 159, 8, 'aaa', 1, '11', '32b5f8e2cf.png', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`adminId`);

--
-- Indexes for table `tbl_brand`
--
ALTER TABLE `tbl_brand`
  ADD PRIMARY KEY (`brandId`);

--
-- Indexes for table `tbl_cart`
--
ALTER TABLE `tbl_cart`
  ADD PRIMARY KEY (`cartId`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `productId` (`productId`);

--
-- Indexes for table `tbl_category`
--
ALTER TABLE `tbl_category`
  ADD PRIMARY KEY (`catId`);

--
-- Indexes for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_order`
--
ALTER TABLE `tbl_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_order_details`
--
ALTER TABLE `tbl_order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `productId` (`productId`);

--
-- Indexes for table `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD PRIMARY KEY (`productId`),
  ADD KEY `fk_product_catId_new` (`catId`),
  ADD KEY `fk_product_brandId_new` (`brandId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `adminId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_brand`
--
ALTER TABLE `tbl_brand`
  MODIFY `brandId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_cart`
--
ALTER TABLE `tbl_cart`
  MODIFY `cartId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `catId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tbl_order`
--
ALTER TABLE `tbl_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `tbl_order_details`
--
ALTER TABLE `tbl_order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `tbl_product`
--
ALTER TABLE `tbl_product`
  MODIFY `productId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_cart`
--
ALTER TABLE `tbl_cart`
  ADD CONSTRAINT `tbl_cart_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `tbl_customer` (`id`),
  ADD CONSTRAINT `tbl_cart_ibfk_2` FOREIGN KEY (`productId`) REFERENCES `tbl_product` (`productId`);

--
-- Constraints for table `tbl_order_details`
--
ALTER TABLE `tbl_order_details`
  ADD CONSTRAINT `tbl_order_details_ibfk_1` FOREIGN KEY (`productId`) REFERENCES `tbl_product` (`productId`);

--
-- Constraints for table `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD CONSTRAINT `fk_product_brandId_new` FOREIGN KEY (`brandId`) REFERENCES `tbl_brand` (`brandId`),
  ADD CONSTRAINT `fk_product_catId_new` FOREIGN KEY (`catId`) REFERENCES `tbl_category` (`catId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
