-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 10, 2025 lúc 04:22 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `shop_nhuy`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `adminId` int(11) NOT NULL,
  `adminName` varchar(255) NOT NULL,
  `adminEmail` varchar(150) NOT NULL,
  `adminPass` varchar(255) NOT NULL,
  `adminUser` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_admin`
--

INSERT INTO `tbl_admin` (`adminId`, `adminName`, `adminEmail`, `adminPass`, `adminUser`) VALUES
(1, 'Như Ý', 'nhuy@gmail.com', '123', 'nhuyadmin');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_brand`
--

CREATE TABLE `tbl_brand` (
  `brandId` int(11) NOT NULL,
  `brandName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_brand`
--

INSERT INTO `tbl_brand` (`brandId`, `brandName`) VALUES
(5, 'Daniel Walington'),
(8, 'Casio'),
(10, 'G-Shock'),
(11, 'Tissot');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_cart`
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
-- Cấu trúc bảng cho bảng `tbl_category`
--

CREATE TABLE `tbl_category` (
  `catId` int(11) NOT NULL,
  `catName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_category`
--

INSERT INTO `tbl_category` (`catId`, `catName`) VALUES
(158, 'Đồng hồ nam'),
(159, 'Đồng hồ nữ'),
(160, 'Đồng hồ bạc'),
(161, 'Đồng hồ vàng'),
(167, 'Đồng hồ mạ vàng');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_customer`
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
-- Đang đổ dữ liệu cho bảng `tbl_customer`
--

INSERT INTO `tbl_customer` (`id`, `username`, `address`, `email`, `password`, `phone`, `fullname`, `status`) VALUES
(11, 'Thanh', 'thạnh lộc 19 quận 12', 'a@gmail.com', '123456', '0908775783', 'Phạm Chí Thanh', 'active'),
(12, 'thanh', 'thanh dep trai', 'user@gmail.com', '123', '0908775783', 'thanh', 'active'),
(13, 'fff', 'fff', 'fff@fff.fff', '123456', '0909900099', 'fff', 'active');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_order`
--

CREATE TABLE `tbl_order` (
  `id` int(11) NOT NULL,
  `orderDate` datetime NOT NULL,
  `totalPrice` float NOT NULL,
  `status` varchar(150) NOT NULL,
  `customerId` int(11) NOT NULL,
  `payment_method` enum('money','credit-card') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_order_details`
--

CREATE TABLE `tbl_order_details` (
  `id` int(11) NOT NULL,
  `orderId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `sessionId` varchar(255) NOT NULL,
  `customerId` int(11) NOT NULL,
  `price` float NOT NULL,
  `productName` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_product`
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
  `isActive` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_product`
--

INSERT INTO `tbl_product` (`productId`, `productName`, `catId`, `brandId`, `product_desc`, `type_pd`, `price`, `image`, `isActive`) VALUES
(85, 'Đồng hồ Daniel Wallington nữ', 159, 5, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '1000000', '30a6f02fc7.png', 0),
(86, 'Đồng hồ Daniel Wallington nữ', 159, 5, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '1500000', 'e0e8b54276.png', 0),
(87, 'Đồng hồ Daniel Wallington nữ', 159, 5, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '90000', '922af61852.png', 0),
(88, 'Đồng hồ Daniel Wallington nữ', 159, 5, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '2000000', '0bdc39349e.png', 0),
(89, 'Đồng Hồ Nam Classic', 158, 11, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 1, '1100000', 'e4b88f3eab.png', 0),
(90, 'Đồng Hồ Nam Classic', 158, 11, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 1, '1100000', '55368070fc.png', 0),
(91, 'Đồng Hồ Nam Classic', 158, 11, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 1, '4000000', 'c55372addc.png', 0),
(92, 'Đồng Hồ Nam Classic', 158, 11, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 1, '500000000', '1211a16801.png', 0),
(93, 'Đồng hồ Daniel Wallington nữ', 161, 5, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '8000', '55c5c49857.png', 0),
(94, 'Đồng hồ Daniel Wallington nữ', 161, 10, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 1, '12000000', '135f66e06d.png', 0),
(95, 'Đồng hồ Daniel Wallington nữ', 161, 10, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '1000000', 'ad560ea3e4.png', 0),
(96, 'Đồng hồ Daniel Wallington nữ', 161, 10, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '1500000', '798d9bdc5e.png', 0),
(97, 'Đồng hồ 97', 160, 5, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '3570000', '30a6f02fc7.png', 0),
(98, 'Đồng hồ 98', 160, 5, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '6514000', 'e0e8b54276.png', 0),
(99, 'Đồng hồ 99', 160, 5, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '2006000', '922af61852.png', 0),
(100, 'Đồng hồ 100', 160, 5, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '8499000', '0bdc39349e.png', 0),
(101, 'Đồng hồ 101', 160, 5, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '4716000', 'e4b88f3eab.png', 0),
(102, 'Đồng hồ 102', 160, 5, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '8155000', '55368070fc.png', 0),
(103, 'Đồng hồ 103', 160, 5, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '3697000', 'c55372addc.png', 0),
(104, 'Đồng hồ 104', 160, 5, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '9413000', '1211a16801.png', 0),
(105, 'Đồng hồ 105', 160, 8, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '6659000', '55c5c49857.png', 0),
(106, 'Đồng hồ 106', 160, 8, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '3473000', '135f66e06d.png', 0),
(107, 'Đồng hồ 107', 161, 8, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '5981000', 'ad560ea3e4.png', 0),
(108, 'Đồng hồ 108', 161, 8, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '6074000', '798d9bdc5e.png', 0),
(109, 'Đồng hồ 109', 161, 8, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '4085000', '30a6f02fc7.png', 0),
(110, 'Đồng hồ 110', 161, 8, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '9678000', 'e0e8b54276.png', 0),
(111, 'Đồng hồ 111', 161, 8, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '8755000', '922af61852.png', 0),
(112, 'Đồng hồ 112', 161, 8, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '6184000', '0bdc39349e.png', 0),
(113, 'Đồng hồ 113', 161, 8, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '2942000', 'e4b88f3eab.png', 0),
(114, 'Đồng hồ 114', 161, 11, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '8396000', '55368070fc.png', 0),
(115, 'Đồng hồ 115', 161, 11, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '2872000', 'c55372addc.png', 0),
(116, 'Đồng hồ 116', 161, 11, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '3164000', '1211a16801.png', 0),
(117, 'Đồng hồ 117', 161, 11, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '6954000', '55c5c49857.png', 0),
(118, 'Đồng hồ 118', 161, 11, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '6084000', '135f66e06d.png', 0),
(119, 'Đồng hồ 119', 161, 11, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '3897000', 'ad560ea3e4.png', 0),
(120, 'Đồng hồ 120', 161, 11, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '5591000', '798d9bdc5e.png', 0),
(121, 'Đồng hồ 121', 161, 11, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '6126000', '30a6f02fc7.png', 0),
(122, 'Đồng hồ 122', 161, 11, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '6453000', 'e0e8b54276.png', 0),
(123, 'Đồng hồ 123', 161, 11, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '9988000', '922af61852.png', 0),
(124, 'Đồng hồ 124', 161, 11, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '7552000', '0bdc39349e.png', 0),
(125, 'Đồng hồ 125', 161, 11, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '9070000', 'e4b88f3eab.png', 0),
(126, 'Đồng hồ 126', 161, 11, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '9499000', '55368070fc.png', 0),
(127, 'Đồng hồ 127', 158, 5, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '9486000', 'c55372addc.png', 0),
(128, 'Đồng hồ 128', 158, 5, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '9208000', '1211a16801.png', 0),
(129, 'Đồng hồ 129', 158, 5, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '8209000', '55c5c49857.png', 0),
(130, 'Đồng hồ 130', 158, 5, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '8917000', '135f66e06d.png', 0),
(131, 'Đồng hồ 131', 158, 8, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '8385000', 'ad560ea3e4.png', 0),
(132, 'Đồng hồ 132', 158, 8, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '3540000', '798d9bdc5e.png', 0),
(133, 'Đồng hồ 133', 158, 8, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '3001000', '30a6f02fc7.png', 0),
(134, 'Đồng hồ 134', 158, 8, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '6802000', 'e0e8b54276.png', 0),
(135, 'Đồng hồ 135', 158, 10, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '9711000', '922af61852.png', 0),
(136, 'Đồng hồ 136', 158, 10, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '6645000', '0bdc39349e.png', 0),
(137, 'Đồng hồ 137', 158, 10, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '2059000', 'e4b88f3eab.png', 0),
(138, 'Đồng hồ 138', 158, 10, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '4981000', '55368070fc.png', 0),
(139, 'Đồng hồ 139', 159, 10, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '5832000', 'c55372addc.png', 0),
(140, 'Đồng hồ 140', 159, 10, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '7826000', '1211a16801.png', 0),
(141, 'Đồng hồ 141', 159, 10, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '8500000', '55c5c49857.png', 1),
(142, 'Đồng hồ 142', 159, 10, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '4036000', '135f66e06d.png', 1),
(143, 'a2', 159, 10, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '4901000', 'ad560ea3e4.png', 1),
(144, 'a1', 159, 10, 'Wristwatches evolved in the 17th century, starting with spring-powered clocks, which appeared as early as the 14th century. For most of its history, the wristwatch was a mechanical device, driven by a clockwork mechanism, wound around a mainspring, and kept time with an oscillating balance wheel.', 0, '9258000', '798d9bdc5e.png', 1),
(145, 'aa', 159, 8, 'aaa', 1, '11', '32b5f8e2cf.png', 1);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`adminId`);

--
-- Chỉ mục cho bảng `tbl_brand`
--
ALTER TABLE `tbl_brand`
  ADD PRIMARY KEY (`brandId`);

--
-- Chỉ mục cho bảng `tbl_cart`
--
ALTER TABLE `tbl_cart`
  ADD PRIMARY KEY (`cartId`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `productId` (`productId`);

--
-- Chỉ mục cho bảng `tbl_category`
--
ALTER TABLE `tbl_category`
  ADD PRIMARY KEY (`catId`);

--
-- Chỉ mục cho bảng `tbl_customer`
--
ALTER TABLE `tbl_customer`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tbl_order`
--
ALTER TABLE `tbl_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_customerId` (`customerId`);

--
-- Chỉ mục cho bảng `tbl_order_details`
--
ALTER TABLE `tbl_order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `productId` (`productId`),
  ADD KEY `tbl_order_details_ibfk_1` (`orderId`);

--
-- Chỉ mục cho bảng `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD PRIMARY KEY (`productId`),
  ADD KEY `fk_product_catId_new` (`catId`),
  ADD KEY `fk_product_brandId_new` (`brandId`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `adminId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `tbl_brand`
--
ALTER TABLE `tbl_brand`
  MODIFY `brandId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `tbl_cart`
--
ALTER TABLE `tbl_cart`
  MODIFY `cartId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT cho bảng `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `catId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=168;

--
-- AUTO_INCREMENT cho bảng `tbl_customer`
--
ALTER TABLE `tbl_customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `tbl_order`
--
ALTER TABLE `tbl_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT cho bảng `tbl_order_details`
--
ALTER TABLE `tbl_order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT cho bảng `tbl_product`
--
ALTER TABLE `tbl_product`
  MODIFY `productId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `tbl_cart`
--
ALTER TABLE `tbl_cart`
  ADD CONSTRAINT `tbl_cart_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `tbl_customer` (`id`),
  ADD CONSTRAINT `tbl_cart_ibfk_2` FOREIGN KEY (`productId`) REFERENCES `tbl_product` (`productId`);

--
-- Các ràng buộc cho bảng `tbl_order`
--
ALTER TABLE `tbl_order`
  ADD CONSTRAINT `fk_order_customerId` FOREIGN KEY (`customerId`) REFERENCES `tbl_customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `tbl_order_details`
--
ALTER TABLE `tbl_order_details`
  ADD CONSTRAINT `tbl_order_details_ibfk_1` FOREIGN KEY (`orderId`) REFERENCES `tbl_order` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD CONSTRAINT `fk_product_brandId_new` FOREIGN KEY (`brandId`) REFERENCES `tbl_brand` (`brandId`),
  ADD CONSTRAINT `fk_product_catId_new` FOREIGN KEY (`catId`) REFERENCES `tbl_category` (`catId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
