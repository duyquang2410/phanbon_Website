-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th7 30, 2025 lúc 06:35 PM
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
-- Cơ sở dữ liệu: `phan_bon_test`
--
CREATE DATABASE IF NOT EXISTS `phan_bon_test` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `phan_bon_test`;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitiet_gh`
--

CREATE TABLE `chitiet_gh` (
  `SP_MA` int(11) NOT NULL,
  `GH_MA` int(11) NOT NULL,
  `CTGH_KHOILUONG` float DEFAULT NULL,
  `CTGH_DONVITINH` varchar(20) NOT NULL,
  `DA_MUA` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chitiet_gh`
--

INSERT INTO `chitiet_gh` (`SP_MA`, `GH_MA`, `CTGH_KHOILUONG`, `CTGH_DONVITINH`, `DA_MUA`) VALUES
(1, 7, 7, 'cái', 1),
(2, 7, 11, 'cái', 1),
(3, 7, 10, 'cái', 1),
(4, 7, 6, 'cái', 1),
(5, 7, 2, 'cái', 1),
(6, 7, 9, 'cái', 1),
(8, 7, 6, 'cái', 1),
(11, 7, 6, 'cái', 1),
(12, 7, 8, 'cái', 1),
(2, 10, 7, 'cái', 0),
(4, 10, 2, 'cái', 1),
(5, 10, 1, 'cái', 1),
(7, 10, 1, 'cái', 1),
(2, 11, 3, 'cái', 1),
(4, 11, 1, 'cái', 0),
(5, 11, 1, 'cái', 1),
(4, 12, 4, 'cái', 1),
(6, 12, 3, 'cái', 1),
(12, 12, 4, 'cái', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitiet_pk`
--

CREATE TABLE `chitiet_pk` (
  `PK_ID` int(11) NOT NULL,
  `SP_MA` int(11) NOT NULL,
  `CTPK_SOLUONG_HT` decimal(10,2) NOT NULL COMMENT 'Số lượng hiện tại',
  `CTPK_SOLUONG_TT` decimal(10,2) NOT NULL COMMENT 'Số lượng thực tế',
  `CTPK_GHICHU` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chitiet_pk`
--

INSERT INTO `chitiet_pk` (`PK_ID`, `SP_MA`, `CTPK_SOLUONG_HT`, `CTPK_SOLUONG_TT`, `CTPK_GHICHU`) VALUES
(1, 8, 12946.00, 12945.00, '3434');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitiet_pn`
--

CREATE TABLE `chitiet_pn` (
  `SP_MA` int(11) NOT NULL,
  `PN_STT` int(11) NOT NULL,
  `NH_MA` int(11) NOT NULL,
  `CTPN_KHOILUONG` float DEFAULT NULL,
  `CTPN_DONVITINH` varchar(20) NOT NULL,
  `CTPN_DONGIA` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chitiet_pn`
--

INSERT INTO `chitiet_pn` (`SP_MA`, `PN_STT`, `NH_MA`, `CTPN_KHOILUONG`, `CTPN_DONVITINH`, `CTPN_DONGIA`) VALUES
(2, 6, 6, 20, 'g', 20000),
(2, 7, 6, 20, 'g', 20000),
(2, 11, 6, 200, 'g', 20000),
(6, 0, 1, 2, 'cái', 19000),
(6, 9, 4, 20, 'gói', 19000),
(6, 10, 4, 20, 'gói', 19000),
(7, 8, 4, 200, 'gói', 20000),
(13, 1, 2, 20, 'kg', 263000),
(13, 2, 2, 20, 'kg', 263000),
(13, 3, 2, 20, 'kg', 263000),
(13, 4, 2, 20, 'kg', 263000),
(13, 5, 2, 20, 'kg', 263000);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chi_tiet_hd`
--

CREATE TABLE `chi_tiet_hd` (
  `CTHD_ID` int(11) NOT NULL,
  `SP_MA` int(11) NOT NULL,
  `HD_STT` int(11) NOT NULL,
  `CTHD_SOLUONG` int(11) DEFAULT NULL,
  `CTHD_DONGIA` float NOT NULL,
  `CTHD_DONVITINH` varchar(20) DEFAULT NULL,
  `CTHD_GHICHU` varchar(200) DEFAULT NULL,
  `CTHD_GIAGOC` float DEFAULT NULL COMMENT 'Giá gốc sản phẩm tại thời điểm đặt hàng'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chi_tiet_hd`
--

INSERT INTO `chi_tiet_hd` (`CTHD_ID`, `SP_MA`, `HD_STT`, `CTHD_SOLUONG`, `CTHD_DONGIA`, `CTHD_DONVITINH`, `CTHD_GHICHU`, `CTHD_GIAGOC`) VALUES
(3, 12, 6, 4, 160000, NULL, NULL, NULL),
(4, 6, 7, 3, 19000, NULL, NULL, NULL),
(5, 4, 8, 4, 6000, NULL, NULL, NULL),
(6, 4, 9, 6, 6000, NULL, NULL, NULL),
(7, 1, 10, 4, 26000, NULL, NULL, NULL),
(8, 8, 11, 2, 220000, NULL, NULL, NULL),
(9, 1, 12, 1, 26000, NULL, NULL, NULL),
(10, 2, 13, 1, 35000, NULL, NULL, NULL),
(11, 2, 14, 3, 35000, NULL, NULL, NULL),
(12, 4, 15, 3, 6000, NULL, NULL, NULL),
(13, 5, 15, 3, 45000, NULL, NULL, NULL),
(14, 12, 16, 5, 160000, NULL, NULL, NULL),
(15, 4, 17, 2, 6000, NULL, NULL, NULL),
(16, 2, 18, 5, 35000, NULL, NULL, NULL),
(17, 12, 18, 6, 160000, NULL, NULL, NULL),
(19, 4, 20, 4, 6000, 'cái', NULL, NULL),
(20, 5, 21, 4, 45000, 'cái', NULL, NULL),
(21, 3, 22, 4, 22000, 'cái', NULL, NULL),
(22, 2, 23, 4, 35000, 'cái', NULL, NULL),
(23, 2, 24, 3, 35000, 'cái', NULL, NULL),
(24, 2, 25, 3, 35000, 'cái', NULL, NULL),
(25, 5, 26, 5, 45000, 'cái', NULL, NULL),
(26, 11, 27, 5, 23000, 'cái', NULL, NULL),
(27, 7, 28, 4, 10500, 'cái', NULL, NULL),
(28, 2, 29, 4, 35000, 'cái', NULL, NULL),
(29, 10, 32, 3, 43000, 'cái', NULL, NULL),
(30, 2, 33, 4, 35000, 'cái', NULL, NULL),
(31, 4, 34, 4, 6000, 'cái', NULL, NULL),
(32, 4, 35, 2, 6000, 'cái', NULL, NULL),
(33, 8, 35, 6, 220000, 'cái', NULL, NULL),
(34, 2, 36, 3, 35000, 'cái', NULL, NULL),
(35, 8, 37, 4, 220000, 'cái', NULL, NULL),
(36, 1, 38, 6, 26000, 'cái', NULL, NULL),
(37, 2, 39, 3, 35000, 'cái', NULL, NULL),
(38, 2, 40, 4, 35000, 'cái', NULL, NULL),
(39, 6, 40, 2, 19000, 'cái', NULL, NULL),
(40, 6, 52, 2, 19000, 'cái', NULL, NULL),
(41, 8, 53, 4, 220000, 'cái', NULL, NULL),
(42, 2, 54, 3, 35000, 'cái', NULL, NULL),
(43, 3, 55, 3, 22000, 'cái', NULL, NULL),
(44, 8, 56, 1, 220000, 'cái', NULL, NULL),
(45, 6, 57, 3, 19000, 'cái', NULL, NULL),
(46, 4, 58, 6, 6000, 'cái', NULL, NULL),
(49, 5, 61, 2, 45000, 'cái', NULL, NULL),
(50, 5, 61, 2, 45000, 'ml', NULL, NULL),
(51, 6, 62, 3, 19000, 'cái', NULL, NULL),
(52, 6, 62, 3, 19000, 'gói', NULL, NULL),
(53, 2, 63, 4, 35000, 'cái', NULL, NULL),
(54, 2, 63, 4, 35000, 'g', NULL, NULL),
(55, 3, 64, 4, 22000, 'cái', NULL, NULL),
(56, 3, 64, 4, 22000, 'kg', NULL, NULL),
(57, 10, 65, 4, 43000, 'cái', NULL, NULL),
(58, 10, 65, 4, 43000, 'gói', NULL, NULL),
(59, 1, 66, 4, 26000, 'cái', NULL, NULL),
(60, 1, 66, 4, 26000, 'kg', NULL, NULL),
(61, 6, 67, 5, 19000, 'cái', NULL, NULL),
(62, 6, 67, 5, 19000, 'gói', NULL, NULL),
(63, 9, 68, 3, 35000, 'cái', NULL, NULL),
(64, 9, 68, 3, 35000, 'ml', NULL, NULL),
(65, 3, 69, 3, 22000, 'cái', NULL, NULL),
(66, 3, 69, 3, 22000, 'kg', NULL, NULL),
(67, 3, 70, 4, 22000, 'cái', NULL, NULL),
(68, 3, 70, 4, 22000, 'kg', NULL, NULL),
(69, 11, 71, 3, 23000, 'cái', NULL, NULL),
(70, 11, 71, 3, 23000, 'ml', NULL, NULL),
(71, 4, 89, 6, 6000, '', '', NULL),
(72, 4, 90, 5, 6000, '', '', NULL),
(73, 12, 91, 3, 160000, '', '', NULL),
(74, 6, 92, 5, 19000, '', '', NULL),
(75, 3, 95, 7, 22000, '', '', NULL),
(76, 2, 101, 4, 35000, '', '', NULL),
(77, 6, 102, 6, 19000, '', '', NULL),
(78, 5, 107, 8, 45000, NULL, NULL, NULL),
(81, 12, 110, 5, 160000, NULL, NULL, NULL),
(82, 2, 111, 4, 35000, '', '', NULL),
(83, 4, 120, 2, 6000, '', '', NULL),
(84, 1, 121, 6, 26000, NULL, NULL, NULL),
(85, 7, 122, 8, 10500, NULL, NULL, NULL),
(86, 4, 123, 3, 6000, NULL, NULL, NULL),
(87, 10, 124, 4, 43000, NULL, NULL, NULL),
(88, 4, 125, 5, 6000, '', '', NULL),
(89, 8, 126, 3, 220000, NULL, NULL, NULL),
(90, 7, 127, 15, 10500, NULL, NULL, NULL),
(91, 4, 127, 8, 6000, NULL, NULL, NULL),
(92, 1, 127, 5, 26000, NULL, NULL, NULL),
(93, 12, 128, 7, 160000, NULL, NULL, NULL),
(94, 3, 129, 2, 22000, NULL, NULL, NULL),
(95, 3, 130, 3, 22000, NULL, NULL, NULL),
(96, 2, 131, 5, 35000, NULL, NULL, NULL),
(97, 6, 132, 8, 19000, NULL, NULL, NULL),
(98, 11, 133, 4, 23000, NULL, NULL, NULL),
(99, 6, 134, 4, 19000, '', '', NULL),
(100, 2, 135, 3, 35000, NULL, NULL, NULL),
(101, 4, 136, 5, 6000, NULL, NULL, NULL),
(102, 8, 137, 3, 220000, NULL, NULL, NULL),
(103, 2, 138, 5, 35000, NULL, NULL, NULL),
(104, 2, 139, 9, 35000, NULL, NULL, NULL),
(105, 4, 140, 4, 6000, NULL, NULL, NULL),
(106, 2, 141, 5, 35000, NULL, NULL, NULL),
(107, 2, 145, 6, 35000, NULL, NULL, NULL),
(108, 5, 146, 9, 45000, NULL, NULL, NULL),
(109, 4, 147, 6, 6000, NULL, NULL, NULL),
(110, 2, 149, 25, 35000, NULL, NULL, NULL),
(111, 2, 150, 5, 35000, NULL, NULL, NULL),
(112, 8, 151, 5, 220000, NULL, NULL, NULL),
(113, 2, 152, 220, 35000, NULL, NULL, NULL),
(114, 8, 153, 4, 220000, NULL, NULL, NULL),
(115, 2, 154, 3, 35000, NULL, NULL, NULL),
(116, 8, 155, 3, 220000, NULL, NULL, NULL),
(117, 2, 156, 4, 35000, NULL, NULL, NULL),
(118, 8, 157, 4, 220000, NULL, NULL, NULL),
(119, 8, 158, 4, 220000, NULL, NULL, NULL),
(120, 7, 159, 4, 10500, NULL, NULL, NULL),
(121, 8, 160, 4, 220000, NULL, NULL, NULL),
(122, 2, 161, 5, 35000, NULL, NULL, NULL),
(123, 4, 162, 9, 6000, NULL, NULL, NULL),
(124, 2, 163, 3, 35000, NULL, NULL, NULL),
(125, 4, 163, 6, 6000, NULL, NULL, NULL),
(126, 2, 164, 1, 35000, NULL, NULL, NULL),
(127, 2, 165, 4, 35000, NULL, NULL, NULL),
(128, 5, 166, 1, 45000, 'cái', NULL, NULL),
(129, 4, 166, 12, 6000, 'cái', NULL, NULL),
(130, 2, 166, 10, 35000, 'cái', NULL, NULL),
(131, 5, 167, 13, 45000, 'cái', NULL, NULL),
(132, 4, 167, 4, 6000, 'cái', NULL, NULL),
(133, 2, 167, 5, 35000, 'cái', NULL, NULL),
(134, 4, 168, 8, 6000, 'cái', NULL, NULL),
(135, 2, 168, 7, 35000, 'cái', NULL, NULL),
(136, 8, 169, 4, 220000, 'cái', NULL, NULL),
(137, 2, 170, 2, 35000, 'cái', NULL, NULL),
(138, 8, 171, 4, 220000, 'cái', NULL, NULL),
(139, 2, 172, 3, 35000, 'cái', NULL, NULL),
(140, 2, 173, 6, 35000, 'cái', NULL, NULL),
(141, 2, 174, 5, 35000, 'cái', NULL, NULL),
(142, 2, 175, 4, 35000, 'cái', NULL, NULL),
(143, 8, 176, 5, 220000, 'cái', NULL, NULL),
(144, 2, 177, 7, 35000, 'cái', NULL, NULL),
(145, 5, 178, 6, 45000, 'cái', NULL, NULL),
(147, 2, 180, 7, 35000, 'cái', NULL, NULL),
(148, 2, 181, 5, 35000, 'cái', NULL, NULL),
(149, 8, 182, 2, 220000, 'cái', NULL, NULL),
(150, 8, 183, 3, 220000, 'cái', NULL, NULL),
(153, 8, 186, 2, 220000, 'cái', NULL, NULL),
(154, 6, 187, 10, 19000, 'cái', NULL, NULL),
(155, 8, 188, 5, 220000, 'cái', NULL, NULL),
(156, 3, 189, 9, 22000, 'cái', NULL, NULL),
(157, 11, 190, 9, 23000, 'cái', NULL, NULL),
(158, 2, 191, 5, 35000, 'cái', NULL, NULL),
(159, 6, 192, 9, 19000, 'cái', NULL, NULL),
(160, 12, 193, 5, 160000, 'cái', NULL, NULL),
(161, 3, 194, 32, 22000, 'cái', NULL, NULL),
(165, 2, 198, 10, 35000, 'cái', NULL, NULL),
(166, 8, 199, 4, 220000, 'cái', NULL, NULL),
(167, 8, 200, 6, 220000, 'cái', NULL, NULL),
(168, 10, 201, 6, 43000, 'cái', NULL, NULL),
(169, 9, 202, 8, 35000, 'cái', NULL, NULL),
(170, 1, 203, 7, 26000, 'cái', NULL, NULL),
(172, 5, 205, 4, 45000, 'cái', NULL, NULL),
(173, 8, 206, 4, 220000, 'cái', NULL, NULL),
(175, 1, 208, 7, 26000, 'cái', NULL, 26000),
(176, 2, 209, 8, 35000, 'cái', NULL, 35000),
(177, 2, 210, 6, 35000, 'cái', NULL, 35000),
(178, 5, 211, 4, 45000, 'cái', NULL, 45000),
(179, 8, 212, 2, 220000, 'cái', NULL, 220000),
(180, 12, 213, 2, 160000, 'cái', NULL, 160000),
(181, 2, 214, 7, 35000, 'cái', NULL, 35000),
(182, 2, 215, 6, 35000, 'cái', NULL, 35000),
(183, 8, 216, 6, 220000, 'cái', NULL, 220000),
(184, 2, 217, 8, 35000, 'cái', NULL, 35000),
(185, 8, 218, 5, 220000, 'cái', NULL, 220000),
(186, 8, 219, 5, 220000, 'cái', NULL, 220000),
(187, 12, 220, 8, 160000, 'cái', NULL, 160000),
(188, 2, 221, 11, 35000, 'cái', NULL, 35000),
(189, 4, 222, 19, 6000, 'cái', NULL, 6000),
(190, 2, 223, 11, 35000, 'cái', NULL, 35000),
(191, 8, 224, 6, 220000, 'cái', NULL, 220000),
(192, 2, 225, 4, 35000, 'cái', NULL, 35000),
(193, 2, 226, 3, 35000, 'cái', NULL, 35000),
(194, 2, 227, 5, 35000, 'cái', NULL, 35000),
(195, 4, 228, 4, 6000, 'cái', NULL, 6000),
(196, 2, 229, 3, 35000, 'cái', NULL, 35000),
(197, 8, 230, 9, 220000, 'cái', NULL, 220000),
(198, 4, 231, 5, 6000, 'cái', NULL, 6000),
(199, 2, 231, 6, 35000, 'cái', NULL, 35000),
(200, 6, 231, 5, 19000, 'cái', NULL, 19000),
(201, 2, 232, 9, 35000, 'cái', NULL, 35000),
(206, 8, 239, 13, 220000, 'cái', NULL, 220000),
(207, 4, 240, 10, 6000, 'cái', NULL, 6000),
(218, 6, 248, 7, 19000, NULL, NULL, 19000),
(225, 2, 252, 8, 35000, NULL, NULL, 35000),
(226, 13, 253, 7, 263000, NULL, NULL, 263000),
(227, 6, 254, 7, 19000, NULL, NULL, 19000),
(228, 2, 255, 7, 35000, NULL, NULL, 35000),
(229, 8, 256, 6, 220000, NULL, NULL, 220000),
(230, 7, 257, 8, 10500, NULL, NULL, 10500),
(231, 4, 258, 13, 6000, NULL, NULL, 6000),
(232, 6, 259, 8, 19000, NULL, NULL, 19000),
(233, 2, 260, 5, 35000, NULL, NULL, 35000),
(234, 2, 261, 6, 35000, NULL, NULL, 35000),
(235, 4, 262, 6, 6000, NULL, NULL, 6000),
(236, 2, 263, 6, 35000, NULL, NULL, 35000),
(237, 2, 264, 6, 35000, NULL, NULL, 35000),
(238, 8, 265, 4, 220000, NULL, NULL, 220000),
(239, 4, 266, 6, 6000, NULL, NULL, 6000),
(240, 2, 266, 16, 35000, NULL, NULL, 35000),
(241, 2, 267, 5, 35000, NULL, NULL, 35000),
(242, 5, 268, 1, 45000, NULL, NULL, 45000),
(243, 1, 269, 7, 26000, NULL, NULL, 26000),
(244, 8, 270, 9, 220000, NULL, NULL, 220000),
(245, 2, 271, 4, 35000, NULL, NULL, 35000),
(246, 8, 272, 5, 220000, NULL, NULL, 220000),
(247, 8, 273, 7, 220000, NULL, NULL, 220000),
(248, 2, 274, 6, 35000, NULL, NULL, 35000),
(249, 8, 275, 6, 220000, NULL, NULL, 220000),
(250, 3, 276, 5, 22000, NULL, NULL, 22000),
(251, 6, 277, 9, 19000, NULL, NULL, 19000),
(252, 11, 278, 6, 23000, NULL, NULL, 23000),
(253, 4, 279, 6, 6000, NULL, NULL, 6000),
(254, 3, 280, 10, 22000, NULL, NULL, 22000),
(255, 12, 281, 8, 160000, NULL, NULL, 160000),
(256, 5, 282, 2, 45000, NULL, NULL, 45000),
(257, 2, 282, 11, 35000, NULL, NULL, 35000);

--
-- Bẫy `chi_tiet_hd`
--
DELIMITER $$
CREATE TRIGGER `update_available_stock` AFTER INSERT ON `chi_tiet_hd` FOR EACH ROW BEGIN
    -- Tính số lượng đang trong đơn hàng chưa hoàn thành
    SET @pending_qty = COALESCE((
        SELECT SUM(CTHD.CTHD_SOLUONG)
        FROM chi_tiet_hd CTHD
        JOIN hoa_don HD ON CTHD.HD_STT = HD.HD_STT
        WHERE CTHD.SP_MA = NEW.SP_MA
        AND HD.HD_TRANGTHAI IN ('PENDING', 'CONFIRMED', 'SHIPPING')
    ), 0);
    
    -- Cập nhật SP_AVAILABLE = SP_SOLUONGTON - số lượng đang trong đơn
    UPDATE san_pham 
    SET SP_AVAILABLE = SP_SOLUONGTON - @pending_qty,
        SP_CAPNHAT = CURRENT_TIMESTAMP
    WHERE SP_MA = NEW.SP_MA;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chuc_vu`
--

CREATE TABLE `chuc_vu` (
  `CV_MA` int(11) NOT NULL,
  `CV_TEN` varchar(100) NOT NULL,
  `CV_MOTA` text DEFAULT NULL COMMENT 'Mô tả chức vụ',
  `CV_QUYEN` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'Quyền hạn của chức vụ' CHECK (json_valid(`CV_QUYEN`)),
  `CV_TRANGTHAI` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái: 1-Kích hoạt, 0-Vô hiệu',
  `CV_NGAYTAO` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Ngày tạo',
  `CV_NGAYCAPNHAT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chuc_vu`
--

INSERT INTO `chuc_vu` (`CV_MA`, `CV_TEN`, `CV_MOTA`, `CV_QUYEN`, `CV_TRANGTHAI`, `CV_NGAYTAO`, `CV_NGAYCAPNHAT`) VALUES
(1, 'Quản lý', 'Quản lý toàn bộ hệ thống', '[\"all\"]', 1, '2025-07-10 05:14:10', '2025-07-10 05:14:10'),
(2, 'Nhân viên bán hàng', 'Nhân viên bán hàng và chăm sóc khách hàng', '[\"view_products\", \"edit_products\", \"view_orders\", \"edit_orders\", \"view_customers\"]', 1, '2025-07-10 05:14:10', '2025-07-10 05:14:10'),
(3, 'Kế toán', 'Quản lý tài chính và kế toán', '[\"view_orders\", \"view_finance\", \"edit_finance\", \"view_reports\"]', 1, '2025-07-10 05:14:10', '2025-07-10 05:14:10'),
(4, 'Thủ kho', 'Quản lý kho hàng và tồn kho', '[\"view_products\", \"edit_products\", \"view_inventory\", \"edit_inventory\"]', 1, '2025-07-10 05:14:10', '2025-07-10 05:14:10'),
(5, 'Bảo vệ', NULL, '', 1, '2025-07-10 05:14:10', '2025-07-10 05:14:10');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `crops`
--

CREATE TABLE `crops` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `crops`
--

INSERT INTO `crops` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'lúa', 'Cây lúa là cây lương thực chính của Việt Nam', '2025-07-03 10:11:32'),
(2, 'cam', 'Cây ăn quả có múi phổ biến', '2025-07-03 10:11:32'),
(3, 'cà chua', 'Cây rau ăn quả được trồng phổ biến', '2025-07-03 10:11:32'),
(4, 'rau', 'Các loại rau xanh', '2025-07-03 10:11:32'),
(5, 'cam', 'Cây cam (Citrus × sinensis) là loại cây ăn quả thuộc chi Cam chanh', '2025-07-03 11:12:43');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danh_muc`
--

CREATE TABLE `danh_muc` (
  `DM_MA` int(11) NOT NULL,
  `DM_TEN` varchar(150) NOT NULL,
  `DM_AVATAR` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `danh_muc`
--

INSERT INTO `danh_muc` (`DM_MA`, `DM_TEN`, `DM_AVATAR`) VALUES
(1, 'Phân bón hữu cơ', 'pb_hc.jpg'),
(2, 'Phân bón vô cơ', 'pb_vc.jpg'),
(3, 'Phân Bón Lá', 'phan_bon_la.jpg'),
(4, 'Thuốc Phòng Trừ Sâu Hóa Học', 'tru_sau_hoa_hoc.jpg'),
(5, 'Thuốc kích thích tăng trưởng', 'kich_re.jpg'),
(6, 'Thuốc Phòng Trừ Sâu Sinh Học', 'thuoc_phong_tru_sau_sh.jpg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `delivery_status`
--

CREATE TABLE `delivery_status` (
  `id` int(11) NOT NULL,
  `HD_STT` int(11) NOT NULL,
  `status` enum('NEW','PROCESSING','DELIVERING','DELIVERED','FAILED') NOT NULL DEFAULT 'NEW',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `estimated_delivery` timestamp NULL DEFAULT NULL,
  `tracking_info` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `delivery_status`
--

INSERT INTO `delivery_status` (`id`, `HD_STT`, `status`, `created_at`, `updated_at`, `estimated_delivery`, `tracking_info`) VALUES
(1, 260, 'DELIVERED', '2025-07-21 16:48:34', '2025-07-21 16:52:57', NULL, 'Đơn hàng mới được tạo\nĐơn hàng đang được giao - 2025-07-21 23:49:36\nĐơn hàng đã giao thành công - 2025-07-21 23:52:57'),
(2, 259, 'DELIVERED', '2025-07-21 16:59:27', '2025-07-22 04:12:21', NULL, 'Đơn hàng mới được tạo\nĐơn hàng đang được giao - 2025-07-22 11:09:16\nĐơn hàng đã giao thành công - 2025-07-22 11:12:21'),
(3, 263, 'DELIVERED', '2025-07-23 10:35:34', '2025-07-23 05:47:22', NULL, 'Đơn hàng đã được xác nhận và bắt đầu giao\n2025-07-23 12:46:22 - Đơn hàng đang được giao đến khách hàng\n2025-07-23 12:47:22 - Đơn hàng đã được giao thành công'),
(4, 253, 'DELIVERED', '2025-07-23 10:43:28', '2025-07-23 05:47:22', NULL, 'Đơn hàng đã được xác nhận và bắt đầu giao\n2025-07-23 12:46:22 - Đơn hàng đang được giao đến khách hàng\n2025-07-23 12:47:22 - Đơn hàng đã được giao thành công'),
(5, 248, 'DELIVERED', '2025-07-23 10:48:44', '2025-07-23 05:48:54', NULL, 'Đơn hàng đã được xác nhận và bắt đầu giao\n2025-07-23 12:48:44 - Đơn hàng đang được giao đến khách hàng\n2025-07-23 12:48:54 - Đơn hàng đã được giao thành công'),
(6, 240, 'DELIVERED', '2025-07-23 10:59:59', '2025-07-23 06:00:09', NULL, 'Đơn hàng đã được xác nhận và bắt đầu giao\n2025-07-23 12:59:59 - Đơn hàng đang được giao đến khách hàng\n2025-07-23 13:00:09 - Đơn hàng đã được giao thành công'),
(7, 264, 'DELIVERED', '2025-07-23 11:04:40', '2025-07-23 06:04:50', NULL, 'Đơn hàng đã được xác nhận và bắt đầu giao\n2025-07-23 13:04:40 - Đơn hàng đang được giao đến khách hàng\n2025-07-23 13:04:50 - Đơn hàng đã được giao thành công'),
(8, 265, 'DELIVERED', '2025-07-23 11:09:07', '2025-07-23 06:09:17', NULL, 'Đơn hàng đã được xác nhận và bắt đầu giao\n2025-07-23 13:09:07 - Đơn hàng đang được giao đến khách hàng\n2025-07-23 13:09:17 - Đơn hàng đã được giao thành công'),
(9, 268, 'DELIVERED', '2025-07-26 07:52:07', '2025-07-26 02:52:17', NULL, 'Đơn hàng đã được xác nhận và bắt đầu giao\n2025-07-26 09:52:07 - Đơn hàng đang được giao đến khách hàng\n2025-07-26 09:52:17 - Đơn hàng đã được giao thành công'),
(10, 267, 'DELIVERED', '2025-07-28 15:07:33', '2025-07-28 10:07:44', NULL, 'Đơn hàng đã được xác nhận và bắt đầu giao\n2025-07-28 17:07:33 - Đơn hàng đang được giao đến khách hàng\n2025-07-28 17:07:44 - Đơn hàng đã được giao thành công'),
(11, 269, 'DELIVERED', '2025-07-28 15:59:28', '2025-07-28 10:59:38', NULL, 'Đơn hàng đã được xác nhận và bắt đầu giao\n2025-07-28 17:59:28 - Đơn hàng đang được giao đến khách hàng\n2025-07-28 17:59:38 - Đơn hàng đã được giao thành công'),
(12, 281, 'DELIVERED', '2025-07-29 03:05:22', '2025-07-28 22:05:32', NULL, 'Đơn hàng đã được xác nhận và bắt đầu giao\n2025-07-29 05:05:22 - Đơn hàng đang được giao đến khách hàng\n2025-07-29 05:05:32 - Đơn hàng đã được giao thành công');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dia_chi_giao_hang`
--

CREATE TABLE `dia_chi_giao_hang` (
  `DCGH_MA` int(11) NOT NULL,
  `DH_MA` int(11) NOT NULL,
  `DCGH_TINH` varchar(100) NOT NULL,
  `DCGH_HUYEN` varchar(100) NOT NULL,
  `DCGH_XA` varchar(100) NOT NULL,
  `DCGH_DIACHI` varchar(255) NOT NULL,
  `DCGH_TENNGUOINHAN` varchar(100) NOT NULL,
  `DCGH_SDT` varchar(15) NOT NULL,
  `DCGH_EMAIL` varchar(100) DEFAULT NULL,
  `DCGH_GHICHU` text DEFAULT NULL,
  `DCGH_THOIGIANTAO` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `dia_chi_giao_hang`
--

INSERT INTO `dia_chi_giao_hang` (`DCGH_MA`, `DH_MA`, `DCGH_TINH`, `DCGH_HUYEN`, `DCGH_XA`, `DCGH_DIACHI`, `DCGH_TENNGUOINHAN`, `DCGH_SDT`, `DCGH_EMAIL`, `DCGH_GHICHU`, `DCGH_THOIGIANTAO`) VALUES
(1, 18, '63', '704', '11581', '', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-06-22 03:11:04'),
(2, 20, '19', '206', '3550', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', NULL, '2025-06-22 04:44:49'),
(3, 21, '45', '517', '9246', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', NULL, '2025-06-22 04:45:43'),
(4, 22, '4', '73', '1198', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', NULL, '2025-06-22 04:54:49'),
(5, 23, '63', '704', '11581', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', NULL, '2025-06-22 04:58:42'),
(6, 24, '62', '693', '11482', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', NULL, '2025-06-22 05:04:24'),
(7, 25, '54', '604', '10301', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', NULL, '2025-06-22 05:10:10'),
(8, 26, '48', '550', '9621', '444', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', NULL, '2025-06-22 05:17:20'),
(9, 27, '5', '82', '1285', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', NULL, '2025-06-22 05:22:15'),
(10, 28, '19', '201', '3467', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', NULL, '2025-06-22 05:25:57'),
(11, 29, '9', '115', '1644', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', NULL, '2025-06-22 05:26:59'),
(12, 32, '45', '525', '9345', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', NULL, '2025-06-22 06:09:44'),
(13, 33, '62', '693', '11482', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', NULL, '2025-06-22 06:23:09'),
(14, 34, '45', '525', '9346', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', NULL, '2025-06-22 06:27:39'),
(15, 35, '45', '512', '9167', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', NULL, '2025-06-22 06:59:23'),
(16, 36, '5', '87', '1313', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', NULL, '2025-06-22 07:00:41'),
(17, 37, '62', '698', '11527', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', NULL, '2025-06-22 07:02:16'),
(18, 38, '24', '255', '4545', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', NULL, '2025-06-22 07:05:46'),
(19, 39, '51', '579', '9952', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', NULL, '2025-06-22 07:39:25'),
(20, 40, '4', '79', '1255', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', NULL, '2025-06-22 08:05:21'),
(21, 41, '3', '59', '988', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-06-23 06:23:16'),
(22, 42, '12', '143', '2157', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-06-23 06:26:38'),
(23, 43, '5', '81', '1263', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-06-23 06:30:46'),
(24, 44, '5', '81', '1263', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-06-23 06:30:47'),
(25, 45, '18', '197', '3409', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-06-23 06:37:11'),
(27, 47, '17', '187', '3229', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-06-23 06:43:48'),
(28, 48, '4', '74', '1203', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-06-23 06:55:02'),
(29, 49, '15', '169', '2679', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-06-23 07:02:15'),
(30, 50, '17', '188', '3246', 'Cần 444', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-06-23 07:06:44'),
(31, 51, '17', '186', '3207', '444', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-06-23 07:09:27'),
(32, 52, '14', '158', '2438', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-06-23 11:10:45'),
(33, 53, '5', '83', '1291', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-06-23 11:11:49'),
(34, 54, '35', '397', '7534', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-06-23 11:15:44'),
(35, 55, '1', '2', '20', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-06-23 11:37:37'),
(36, 56, '4', '73', '1196', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-06-23 11:56:53'),
(37, 57, '41', '469', '8617', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-06-23 11:59:37'),
(38, 58, '2', '35', '677', '444', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-06-24 08:13:46'),
(41, 61, '17', '187', '3228', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-06-24 08:59:09'),
(42, 62, '15', '169', '2680', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-06-24 09:00:36'),
(43, 63, '1', '2', '21', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-06-24 09:06:52'),
(44, 64, '17', '186', '3209', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-06-24 09:13:04'),
(45, 65, '3', '57', '964', '444', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-06-24 09:15:11'),
(46, 66, '3', '59', '988', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-06-24 09:19:20'),
(47, 67, '14', '158', '2443', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-06-24 09:23:38'),
(48, 68, '3', '60', '1001', '444', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-06-24 09:29:44'),
(49, 69, '17', '187', '3228', '444', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-06-24 09:31:35'),
(50, 70, '3', '59', '988', '444', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-06-24 09:33:29'),
(51, 71, '2', '34', '661', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-06-24 09:40:28'),
(52, 166, '13', '153', '2352', '444', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-15 04:53:10'),
(53, 167, '13', '153', '2349', '333', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-15 05:39:52'),
(54, 227, '17', '186', '3205', '444', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-17 07:53:19'),
(55, 227, '17', '186', '3205', '444, XÃ YÊN TỪ, HUYỆN YÊN MÔ, Ninh Bình', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-17 07:53:19'),
(56, 228, '10', '120', '1710', '444', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-17 07:59:02'),
(57, 228, '10', '120', '1710', '444, XÃ PHÚ XUÂN, HUYỆN BÌNH XUYÊN, Vĩnh Phúc', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-17 07:59:02'),
(58, 229, '14', '158', '2443', '444', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-17 08:04:17'),
(59, 229, '14', '158', '2443', '444, XÃ ĐỒNG HÓA, HUYỆN KIM BẢNG, Hà Nam', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-17 08:04:17'),
(60, 230, '15', '166', '2595', '444, XÃ GIAO THIỆN, HUYỆN GIAO THỦY, Nam Ðịnh', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-17 08:09:10'),
(61, 230, '15', '166', '2595', '444, XÃ GIAO THIỆN, HUYỆN GIAO THỦY, Nam Ðịnh', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-17 08:09:10'),
(62, 231, '17', '184', '3155', '444, XÃ PHÚ LỘC, HUYỆN NHO QUAN, Ninh Bình', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-17 08:23:29'),
(63, 231, '17', '184', '3155', '444, XÃ PHÚ LỘC, HUYỆN NHO QUAN, Ninh Bình', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-17 08:23:29'),
(64, 232, '11', '128', '1836', '444, PHƯỜNG THỊ CẦU, THÀNH PHỐ BẮC NINH, Bắc Ninh', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-19 07:32:45'),
(65, 232, '11', '128', '1836', '444, PHƯỜNG THỊ CẦU, THÀNH PHỐ BẮC NINH, Bắc Ninh', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-19 07:32:45'),
(74, 239, '11', '127', '1821', '444, XÃ PHÚ HÒA, HUYỆN LƯƠNG TÀI, Bắc Ninh', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-19 08:32:16'),
(75, 240, '15', '166', '2597', '444, XÃ GIAO AN, HUYỆN GIAO THỦY, Nam Ðịnh', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-19 08:37:00'),
(87, 248, '14', '159', '2464', '444, XÃ NHÂN CHÍNH, HUYỆN LÝ NHÂN, Hà Nam', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-19 09:09:10'),
(94, 252, '9', '112', '1619', '444, PHƯỜNG NGHĨA TRUNG, THÀNH PHỐ GIA NGHĨA, Đăk Nông', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-19 09:23:09'),
(95, 253, '12', '141', '2104', '444, XÃ AN LÂM, HUYỆN NAM SÁCH, Hải Dương', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-19 09:29:17'),
(96, 254, '11', '129', '1855', '444, XÃ NHÂN THẮNG, HUYỆN GIA BÌNH, Bắc Ninh', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-19 09:33:30'),
(97, 255, '11', '130', '1869', '444, XÃ TRẠM LỘ, HUYỆN THUẬN THÀNH, Bắc Ninh', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-19 09:35:32'),
(98, 256, '12', '141', '2102', '444, XÃ AN BÌNH, HUYỆN NAM SÁCH, Hải Dương', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-19 09:40:01'),
(99, 257, '11', '130', '1870', '444, XÃ NGHĨA ĐẠO, HUYỆN THUẬN THÀNH, Bắc Ninh', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-19 10:00:19'),
(100, 258, '5', '83', '1293', '444, PHƯỜNG THUẬN AN, QUẬN THỐT NỐT, Cần Thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-19 10:00:50'),
(101, 259, '16', '175', '2872', '444, XÃ AN CẦU, HUYỆN QUỲNH PHỤ, Thái Bình', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-20 07:52:40'),
(102, 260, '15', '167', '2623', '444, XÃ NGHĨA PHONG, HUYỆN NGHĨA HƯNG, Nam Ðịnh', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-21 09:15:34'),
(103, 261, '11', '127', '1813', '444, XÃ QUẢNG PHÚ, HUYỆN LƯƠNG TÀI, Bắc Ninh', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-22 04:28:17'),
(104, 262, '12', '140', '2078', '444, XÃ ĐOÀN TÙNG, HUYỆN THANH MIỆN, Hải Dương', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-22 04:55:54'),
(105, 263, '14', '159', '2464', '444, XÃ NHÂN CHÍNH, HUYỆN LÝ NHÂN, Hà Nam', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-22 05:06:45'),
(106, 264, '13', '152', '2332', '44, XÃ CẨM XÁ, THỊ XÃ MỸ HÀO, Hưng Yên', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-23 11:04:25'),
(107, 265, '12', '142', '2126', '44, XÃ HIỆP HÒA, THỊ XÃ KINH MÔN, Hải Dương', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-23 11:08:54'),
(108, 266, '12', '139', '2060', '444, XÃ CẨM VĂN, HUYỆN CẨM GIÀNG, Hải Dương', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-26 02:56:26'),
(109, 267, '5', '83', '1293', '511, PHƯỜNG THUẬN AN, QUẬN THỐT NỐT, Cần Thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-26 03:11:48'),
(110, 268, '4', '73', '1198', '511, PHƯỜNG HÒA HẢI, QUẬN NGŨ HÀNH SƠN, Đà Nẵng', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-26 06:35:37'),
(111, 269, '3', '61', '1006', '444, XÃ HỮU BẰNG, HUYỆN KIẾN THỤY, Hải Phòng', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-28 15:56:19'),
(112, 270, '5', '89', '1319', '511, PHƯỜNG BA LÁNG, QUẬN CÁI RĂNG, Cần Thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-28 16:25:52'),
(113, 271, '5', '89', '1319', '511, PHƯỜNG BA LÁNG, QUẬN CÁI RĂNG, Cần Thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-28 16:29:34'),
(114, 272, '5', '89', '1319', '511, PHƯỜNG BA LÁNG, QUẬN CÁI RĂNG, Cần Thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-28 16:30:25'),
(115, 273, '5', '89', '1319', '511, PHƯỜNG BA LÁNG, QUẬN CÁI RĂNG, Cần Thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-28 16:41:55'),
(116, 274, '5', '89', '1319', '511, PHƯỜNG BA LÁNG, QUẬN CÁI RĂNG, Cần Thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-28 16:50:09'),
(117, 275, '5', '89', '1319', '511, PHƯỜNG BA LÁNG, QUẬN CÁI RĂNG, Cần Thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-28 16:54:47'),
(118, 276, '5', '89', '1319', '511, PHƯỜNG BA LÁNG, QUẬN CÁI RĂNG, Cần Thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-28 17:07:54'),
(119, 277, '5', '89', '1319', '511, PHƯỜNG BA LÁNG, QUẬN CÁI RĂNG, Cần Thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-28 17:08:11'),
(120, 278, '5', '89', '1319', '511, PHƯỜNG BA LÁNG, QUẬN CÁI RĂNG, Cần Thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-28 17:09:22'),
(121, 279, '5', '89', '1319', '511, PHƯỜNG BA LÁNG, QUẬN CÁI RĂNG, Cần Thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-28 17:15:10'),
(122, 280, '5', '89', '1319', '511, PHƯỜNG BA LÁNG, QUẬN CÁI RĂNG, Cần Thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-28 17:16:35'),
(123, 281, '5', '89', '1319', '511, PHƯỜNG BA LÁNG, QUẬN CÁI RĂNG, Cần Thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-29 03:03:29'),
(124, 282, '5', '82', '1280', '511, PHƯỜNG AN HỘI, QUẬN NINH KIỀU, Cần Thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-07-30 08:39:27');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `diseases`
--

CREATE TABLE `diseases` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `causes` text DEFAULT NULL,
  `symptoms` text DEFAULT NULL,
  `prevention` text DEFAULT NULL,
  `treatment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `diseases`
--

INSERT INTO `diseases` (`id`, `name`, `description`, `causes`, `symptoms`, `prevention`, `treatment`, `created_at`, `updated_at`) VALUES
(1, 'đạo ôn', 'Bệnh đạo ôn là một trong những bệnh nguy hiểm nhất trên cây lúa', 'Do nấm Pyricularia oryzae gây ra', 'Vết bệnh hình thoi màu nâu', 'Sử dụng giống kháng bệnh, bón phân cân đối', 'Phun thuốc đặc trị đạo ôn khi phát hiện bệnh', '2025-07-03 10:11:32', '2025-07-03 10:11:32'),
(2, 'vàng lá thiếu sắt', 'Bệnh vàng lá do thiếu sắt', 'Do thiếu dinh dưỡng sắt', 'Lá non vàng, gân lá vẫn xanh', 'Bón phân cân đối', 'Bổ sung phân bón có chứa sắt', '2025-07-03 10:11:32', '2025-07-03 10:11:32'),
(3, 'thối rễ', 'Bệnh thối rễ ảnh hưởng nghiêm trọng đến sinh trưởng', 'Do nấm và vi khuẩn gây ra', 'Rễ thối đen, cây còi cọc', 'Cải tạo đất, luân canh', 'Xử lý đất và sử dụng thuốc đặc trị', '2025-07-03 10:11:32', '2025-07-03 10:11:32'),
(4, 'vàng lá virus', 'Bệnh vàng lá do virus', 'Do virus gây ra', 'Lá vàng, sinh trưởng kém', 'Phòng trừ côn trùng môi giới', 'Tiêu hủy cây bệnh, phun thuốc phòng côn trùng', '2025-07-03 10:11:32', '2025-07-03 10:11:32');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `disease_products`
--

CREATE TABLE `disease_products` (
  `disease_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `recommendation_type` enum('prevention','treatment') NOT NULL,
  `priority` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `disease_products`
--

INSERT INTO `disease_products` (`disease_id`, `product_id`, `recommendation_type`, `priority`) VALUES
(1, 5, 'treatment', 1),
(2, 1, 'prevention', 2),
(2, 4, 'treatment', 1),
(3, 5, 'treatment', 1),
(4, 1, 'prevention', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `disease_symptoms`
--

CREATE TABLE `disease_symptoms` (
  `disease_id` int(11) NOT NULL,
  `symptom_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `disease_symptoms`
--

INSERT INTO `disease_symptoms` (`disease_id`, `symptom_id`) VALUES
(1, 4),
(2, 1),
(3, 2),
(4, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `districts`
--

CREATE TABLE `districts` (
  `id` varchar(20) NOT NULL,
  `province_id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `districts`
--

INSERT INTO `districts` (`id`, `province_id`, `name`, `type`) VALUES
('3201', '32', 'Liên Chiểu', 'Quận'),
('3202', '32', 'Thanh Khê', 'Quận'),
('3203', '32', 'Hải Châu', 'Quận'),
('3204', '32', 'Sơn Trà', 'Quận'),
('3205', '32', 'Ngũ Hành Sơn', 'Quận'),
('3206', '32', 'Cẩm Lệ', 'Quận'),
('3207', '32', 'Hòa Vang', 'Huyện'),
('3208', '32', 'Hoàng Sa', 'Huyện');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `don_van_chuyen`
--

CREATE TABLE `don_van_chuyen` (
  `DVC_MA` int(11) NOT NULL,
  `NVC_MA` int(11) NOT NULL,
  `DVC_DIACHI` varchar(250) NOT NULL,
  `DVC_TGBATDAU` datetime NOT NULL,
  `DVC_TGHOANTHANH` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `don_van_chuyen`
--

INSERT INTO `don_van_chuyen` (`DVC_MA`, `NVC_MA`, `DVC_DIACHI`, `DVC_TGBATDAU`, `DVC_TGHOANTHANH`) VALUES
(1, 1, '123 Lê Văn Sỹ, Quận 3, TP.HCM', '2025-06-18 08:30:00', '2025-06-18 11:00:00'),
(2, 2, '45 Nguyễn Trãi, Quận 1, TP.HCM', '2025-06-18 09:00:00', '2025-06-18 12:15:00'),
(3, 1, '200 Cách Mạng Tháng 8, Quận 10', '2025-06-17 14:00:00', '2025-06-17 16:30:00'),
(12, 1, 'Cần thơ', '2025-06-18 16:47:56', '2025-06-21 16:47:56'),
(13, 1, 'Cần thơ', '2025-06-18 16:48:33', '2025-06-21 16:48:33'),
(14, 1, 'Cần thơ', '2025-06-18 16:49:59', '2025-06-21 16:49:59'),
(15, 1, 'Cần thơ', '2025-06-19 09:43:24', '2025-06-22 09:43:24'),
(16, 1, 'Cần thơ', '2025-06-19 10:09:36', '2025-06-22 10:09:36'),
(17, 1, 'Cần thơ', '2025-06-19 10:43:08', '2025-06-22 10:43:08'),
(18, 1, 'Cần thơ', '2025-06-19 10:47:03', '2025-06-22 10:47:03'),
(19, 1, 'Cần thơ', '2025-06-19 11:06:36', '2025-06-22 11:06:36'),
(20, 1, 'Cần thơ', '2025-06-19 11:21:31', '2025-06-22 11:21:31'),
(21, 1, 'Cần thơ', '2025-06-19 11:41:08', '2025-06-22 11:41:08'),
(22, 1, 'Cần thơ', '2025-06-19 11:45:56', '2025-06-22 11:45:56'),
(27, 1, ', 11634, 708, 63', '2025-06-22 09:47:32', '2025-06-25 09:47:32'),
(28, 1, ', 11581, 704, 63', '2025-06-22 10:11:04', '2025-06-25 10:11:04'),
(32, 1, 'Cần thơ, 3550, 206, 19', '2025-06-22 11:44:49', '2025-06-25 11:44:49'),
(33, 1, 'Cần thơ, 9246, 517, 45', '2025-06-22 11:45:43', '2025-06-25 11:45:43'),
(34, 1, 'Cần thơ, 1198, 73, 4', '2025-06-22 11:54:49', '2025-06-25 11:54:49'),
(35, 1, 'Cần thơ, 11581, 704, 63', '2025-06-22 11:58:42', '2025-06-25 11:58:42'),
(36, 1, 'Cần thơ, 11482, 693, 62', '2025-06-22 12:04:24', '2025-06-25 12:04:24'),
(37, 1, 'Cần thơ, 10301, 604, 54', '2025-06-22 12:10:10', '2025-06-25 12:10:10'),
(38, 1, '444, 9621, 550, 48', '2025-06-22 12:17:20', '2025-06-25 12:17:20'),
(39, 1, 'Cần thơ, 1285, 82, 5', '2025-06-22 12:22:15', '2025-06-25 12:22:15'),
(40, 1, 'Cần thơ, 3467, 201, 19', '2025-06-22 12:25:57', '2025-06-25 12:25:57'),
(41, 1, 'Cần thơ, 1644, 115, 9', '2025-06-22 12:26:59', '2025-06-25 12:26:59'),
(44, 1, 'Cần thơ, 9345, 525, 45', '2025-06-22 13:09:44', '2025-06-25 13:09:44'),
(45, 1, 'Cần thơ, 11482, 693, 62', '2025-06-22 13:23:09', '2025-06-25 13:23:09'),
(46, 1, 'Cần thơ, 9346, 525, 45', '2025-06-22 13:27:39', '2025-06-25 13:27:39'),
(47, 1, 'Cần thơ, 9167, 512, 45', '2025-06-22 13:59:23', '2025-06-25 13:59:23'),
(48, 1, 'Cần thơ, 1313, 87, 5', '2025-06-22 14:00:41', '2025-06-25 14:00:41'),
(49, 1, 'Cần thơ, 11527, 698, 62', '2025-06-22 14:02:16', '2025-06-25 14:02:16'),
(50, 1, 'Cần thơ, 4545, 255, 24', '2025-06-22 14:05:46', '2025-06-25 14:05:46'),
(51, 1, 'Cần thơ, 9952, 579, 51', '2025-06-22 14:39:25', '2025-06-25 14:39:25'),
(52, 1, 'Cần thơ, 1255, 79, 4', '2025-06-22 15:05:21', '2025-06-25 15:05:21'),
(57, 1, 'Cần thơ, 988, 59, 3', '2025-06-23 13:23:16', '2025-06-26 13:23:16'),
(58, 1, 'Cần thơ, 2157, 143, 12', '2025-06-23 13:26:38', '2025-06-26 13:26:38'),
(59, 1, 'Cần thơ, 1263, 81, 5', '2025-06-23 13:30:46', '2025-06-26 13:30:46'),
(60, 1, 'Cần thơ, 1263, 81, 5', '2025-06-23 13:30:47', '2025-06-26 13:30:47'),
(61, 1, 'Cần thơ, 3409, 197, 18', '2025-06-23 13:37:11', '2025-06-26 13:37:11'),
(63, 1, 'Cần thơ, 3229, 187, 17', '2025-06-23 13:43:48', '2025-06-26 13:43:48'),
(64, 1, 'Cần thơ, 1203, 74, 4', '2025-06-23 13:55:02', '2025-06-26 13:55:02'),
(65, 1, 'Cần thơ, 2679, 169, 15', '2025-06-23 14:02:15', '2025-06-26 14:02:15'),
(66, 1, 'Cần 444, 3246, 188, 17', '2025-06-23 14:06:44', '2025-06-26 14:06:44'),
(67, 1, '444, 3207, 186, 17', '2025-06-23 14:09:27', '2025-06-26 14:09:27'),
(68, 1, 'Cần thơ, 2438, 158, 14', '2025-06-23 18:10:45', '2025-06-26 18:10:45'),
(69, 1, 'Cần thơ, 1291, 83, 5', '2025-06-23 18:11:49', '2025-06-26 18:11:49'),
(70, 1, 'Cần thơ, 7534, 397, 35', '2025-06-23 18:15:44', '2025-06-26 18:15:44'),
(71, 1, 'Cần thơ, 20, 2, 1', '2025-06-23 18:37:37', '2025-06-26 18:37:37'),
(72, 1, 'Cần thơ, 1196, 73, 4', '2025-06-23 18:56:52', '2025-06-26 18:56:52'),
(73, 1, 'Cần thơ, 8617, 469, 41', '2025-06-23 18:59:37', '2025-06-26 18:59:37'),
(74, 1, '444, 677, 35, 2', '2025-06-24 15:13:46', '2025-06-27 15:13:46'),
(77, 1, 'Cần thơ, 3228, 187, 17', '2025-06-24 15:59:09', '2025-06-27 15:59:09'),
(78, 1, 'Cần thơ, 2680, 169, 15', '2025-06-24 16:00:36', '2025-06-27 16:00:36'),
(79, 1, 'Cần thơ, 21, 2, 1', '2025-06-24 16:06:52', '2025-06-27 16:06:52'),
(80, 1, 'Cần thơ, 3209, 186, 17', '2025-06-24 16:13:04', '2025-06-27 16:13:04'),
(81, 1, '444, 964, 57, 3', '2025-06-24 16:15:11', '2025-06-27 16:15:11'),
(82, 1, 'Cần thơ, 988, 59, 3', '2025-06-24 16:19:20', '2025-06-27 16:19:20'),
(83, 1, 'Cần thơ, 2443, 158, 14', '2025-06-24 16:23:38', '2025-06-27 16:23:38'),
(84, 1, '444, 1001, 60, 3', '2025-06-24 16:29:44', '2025-06-27 16:29:44'),
(85, 1, '444, 3228, 187, 17', '2025-06-24 16:31:35', '2025-06-27 16:31:35'),
(86, 1, '444, 988, 59, 3', '2025-06-24 16:33:29', '2025-06-27 16:33:29'),
(87, 1, 'Cần thơ, 661, 34, 2', '2025-06-24 16:40:28', '2025-06-27 16:40:28'),
(88, 1, 'Cần thơ, 2909, 176, 16', '2025-06-24 16:44:10', '2025-06-27 16:44:10'),
(89, 1, 'Cần thơ, 3409, 197, 18', '2025-06-24 16:47:00', '2025-06-27 16:47:00'),
(90, 1, 'Cần thơ, 2349, 153, 13', '2025-06-24 16:49:49', '2025-06-27 16:49:49'),
(91, 1, '444, 1292, 83, 5', '2025-06-24 16:53:13', '2025-06-27 16:53:13'),
(92, 1, 'Cần thơ, 988, 59, 3', '2025-06-24 16:54:08', '2025-06-27 16:54:08'),
(93, 1, '444, 3209, 186, 17', '2025-06-24 16:54:49', '2025-06-27 16:54:49'),
(94, 1, 'Cần thơ, 645, 33, 2', '2025-06-24 17:05:05', '2025-06-27 17:05:05'),
(95, 1, 'Cần thơ, 3762, 218, 20', '2025-06-24 17:08:27', '2025-06-27 17:08:27'),
(96, 1, 'Cần thơ, 2446, 158, 14', '2025-06-24 17:09:15', '2025-06-27 17:09:15'),
(97, 1, 'Cần thơ, 966, 57, 3', '2025-06-24 17:10:11', '2025-06-27 17:10:11'),
(98, 1, 'Cần thơ, 972, 58, 3', '2025-06-24 17:13:25', '2025-06-27 17:13:25'),
(99, 1, 'Cần thơ, 2651, 168, 15', '2025-06-24 17:17:21', '2025-06-27 17:17:21'),
(100, 1, 'Cần thơ, 2651, 168, 15', '2025-06-24 17:19:42', '2025-06-27 17:19:42'),
(101, 1, 'Cần thơ, 2649, 168, 15', '2025-06-24 17:20:35', '2025-06-27 17:20:35'),
(102, 1, 'Cần thơ, 972, 58, 3', '2025-06-24 17:26:28', '2025-06-27 17:26:28'),
(103, 1, 'Cần thơ, 989, 59, 3', '2025-06-24 17:28:38', '2025-06-27 17:28:38'),
(104, 1, 'Cần thơ, 661, 34, 2', '2025-06-24 17:32:01', '2025-06-27 17:32:01'),
(105, 1, 'Cần thơ, 2675, 169, 15', '2025-06-24 17:34:58', '2025-06-27 17:34:58'),
(106, 1, 'Cần thơ, 973, 58, 3', '2025-06-24 17:36:09', '2025-06-27 17:36:09'),
(107, 1, 'Cần thơ, 2907, 176, 16', '2025-06-24 17:39:59', '2025-06-27 17:39:59'),
(108, 1, 'Cần thơ, 2620, 167, 15', '2025-06-24 17:45:11', '2025-06-27 17:45:11'),
(109, 1, 'Cần thơ, 989, 59, 3', '2025-06-24 17:49:04', '2025-06-27 17:49:04'),
(110, 1, 'Cần thơ, 988, 59, 3', '2025-06-24 17:52:52', '2025-06-27 17:52:52'),
(111, 1, 'Cần thơ, 661, 34, 2', '2025-06-24 18:05:46', '2025-06-27 18:05:46'),
(112, 1, 'Cần thơ, 2333, 152, 13', '2025-06-24 18:07:12', '2025-06-27 18:07:12'),
(113, 1, 'Cần thơ, 988, 59, 3', '2025-06-24 18:10:13', '2025-06-27 18:10:13'),
(114, 1, 'Cần thơ, 2426, 157, 14', '2025-06-24 18:11:10', '2025-06-27 18:11:10'),
(115, 1, 'Cần thơ, 1265, 81, 5', '2025-06-24 18:12:49', '2025-06-27 18:12:49'),
(116, 1, 'Cần thơ, 3206, 186, 17', '2025-06-24 18:14:44', '2025-06-27 18:14:44'),
(117, 1, 'Cần thơ, 2912, 176, 16', '2025-06-24 18:15:53', '2025-06-27 18:15:53'),
(118, 1, 'Cần thơ, 2443, 158, 14', '2025-06-24 18:19:03', '2025-06-27 18:19:03'),
(119, 1, '333, 2447, 158, 14', '2025-06-24 18:25:29', '2025-06-27 18:25:29'),
(121, 1, 'Cần thơ, 2783, 173, 16', '2025-06-24 18:30:35', '2025-06-27 18:30:35'),
(122, 1, 'Cần thơ, 2464, 159, 14', '2025-06-24 18:30:56', '2025-06-27 18:30:56'),
(123, 1, 'Cần thơ, 3186, 185, 17', '2025-06-24 19:02:02', '2025-06-27 19:02:02'),
(124, 1, 'Cần thơ, 2648, 168, 15', '2025-06-24 19:06:10', '2025-06-27 19:06:10'),
(125, 1, 'Cần thơ, 662, 34, 2', '2025-06-24 19:09:33', '2025-06-27 19:09:33'),
(126, 1, 'Cần thơ, 2332, 152, 13', '2025-06-24 19:10:33', '2025-06-27 19:10:33'),
(127, 1, 'Cần thơ, 661, 34, 2', '2025-06-24 19:14:02', '2025-06-27 19:14:02'),
(128, 1, '333, 988, 59, 3', '2025-06-24 19:14:49', '2025-06-27 19:14:49'),
(129, 1, 'Cần thơ, 1198, 73, 4', '2025-06-24 19:22:04', '2025-06-27 19:22:04'),
(130, 1, 'Cần thơ, 1202, 74, 4', '2025-06-24 19:26:50', '2025-06-27 19:26:50'),
(131, 1, 'Cần thơ, 2446, 158, 14', '2025-06-24 19:29:04', '2025-06-27 19:29:04'),
(132, 1, 'Cần thơ, 989, 59, 3', '2025-06-24 19:29:30', '2025-06-27 19:29:30'),
(133, 1, 'Cần thơ, 2445, 158, 14', '2025-06-24 19:33:21', '2025-06-27 19:33:21'),
(134, 1, 'Cần thơ, 2445, 158, 14', '2025-06-24 19:39:41', '2025-06-27 19:39:41'),
(135, 1, 'Cần thơ, 972, 58, 3', '2025-06-24 19:44:06', '2025-06-27 19:44:06'),
(136, 1, 'Cần thơ, 988, 59, 3', '2025-06-24 19:49:46', '2025-06-27 19:49:46'),
(137, 1, '444, 2127, 142, 12', '2025-06-25 15:33:53', '2025-06-28 15:33:53'),
(138, 1, 'Cần thơ, 1837, 128, 11', '2025-06-26 23:03:41', '2025-06-29 23:03:41'),
(139, 1, 'Cần thơ, 1856, 129, 11', '2025-06-27 00:02:44', '2025-06-30 00:02:44'),
(140, 1, 'Cần thơ, 3406, 197, 18', '2025-06-27 00:20:37', '2025-06-30 00:20:37'),
(141, 1, '444, 1543, 104, 8', '2025-06-28 09:28:27', '2025-07-01 09:28:27'),
(142, 1, '444, 2678, 169, 15', '2025-06-28 09:59:48', '2025-07-01 09:59:48'),
(143, 1, '444, 1291, 83, 5', '2025-07-05 14:53:41', '2025-07-08 14:53:41'),
(144, 1, 'Cần thơ, 2679, 169, 15', '2025-07-06 14:23:07', '2025-07-09 14:23:07'),
(145, 1, '444, 1001, 60, 3', '2025-07-06 16:48:04', '2025-07-09 16:48:04'),
(146, 1, 'Cần thơ, 1529, 103, 8', '2025-07-07 13:03:55', '2025-07-10 13:03:55'),
(147, 1, 'Cần thơ, 1617, 112, 9', '2025-07-07 13:05:23', '2025-07-10 13:05:23'),
(148, 1, '333, 1311, 87, 5', '2025-07-07 13:19:57', '2025-07-10 13:19:57'),
(149, 1, 'Cần thơ, 2908, 176, 16', '2025-07-07 15:51:26', '2025-07-10 15:51:26'),
(150, 1, 'Cần thơ, 2678, 169, 15', '2025-07-07 15:57:26', '2025-07-10 15:57:26'),
(151, 1, 'Cần thơ, 2371, 154, 13', '2025-07-07 15:57:53', '2025-07-10 15:57:53'),
(152, 1, 'Cần thơ, 2676, 169, 15', '2025-07-07 21:47:35', '2025-07-10 21:47:35'),
(153, 1, 'Cần thơ, 2651, 168, 15', '2025-07-07 21:48:23', '2025-07-10 21:48:23'),
(154, 1, 'Cần thơ, 2320, 151, 13', '2025-07-07 22:02:05', '2025-07-10 22:02:05'),
(155, 1, 'Cần thơ, 2834, 174, 16', '2025-07-07 22:13:43', '2025-07-10 22:13:43'),
(156, 1, '444, 9621, 550, 48', '2025-07-07 23:35:19', '2025-07-10 23:35:19'),
(157, 1, 'Cần thơ, 1264, 81, 5', '2025-07-08 12:53:21', '2025-07-11 12:53:21'),
(158, 1, 'Cần thơ', '2025-07-08 13:06:47', '2025-07-11 13:06:47'),
(162, 1, '444, PHƯỜNG TRƯỜNG LẠC, QUẬN Ô MÔN, Cần Thơ', '2025-07-08 15:26:10', '2025-07-11 15:26:10'),
(163, 1, '444, XÃ PHAN HÒA, HUYỆN BẮC BÌNH, Bình Thuận', '2025-07-09 13:20:18', '2025-07-12 13:20:18'),
(166, 1, '4444, XÃ ĐACHIA, HUYỆN BÙ GIA MẬP, Bình Phước', '2025-07-09 13:50:04', '2025-07-12 13:50:04'),
(167, 1, '444, 2352, 153, 13', '2025-07-15 11:53:10', '2025-07-18 11:53:10'),
(168, 1, '333, 2349, 153, 13', '2025-07-15 12:39:52', '2025-07-18 12:39:52');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `gio_hang`
--

CREATE TABLE `gio_hang` (
  `GH_MA` int(11) NOT NULL,
  `KH_MA` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `gio_hang`
--

INSERT INTO `gio_hang` (`GH_MA`, `KH_MA`) VALUES
(11, 1),
(1, 2),
(2, 2),
(3, 2),
(4, 2),
(5, 2),
(6, 2),
(7, 2),
(10, 3),
(12, 4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `growth_stages`
--

CREATE TABLE `growth_stages` (
  `id` int(11) NOT NULL,
  `crop_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `duration_days` int(11) DEFAULT NULL,
  `order_index` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `growth_stages`
--

INSERT INTO `growth_stages` (`id`, `crop_id`, `name`, `description`, `duration_days`, `order_index`) VALUES
(1, NULL, 'chuẩn bị', 'Giai đoạn chuẩn bị đất và gieo trồng', NULL, NULL),
(2, NULL, 'sinh trưởng', 'Giai đoạn phát triển thân lá', NULL, NULL),
(3, NULL, 'ra hoa', 'Giai đoạn ra hoa và đậu quả', NULL, NULL),
(4, NULL, 'tất cả', 'Áp dụng cho mọi giai đoạn', NULL, NULL),
(5, NULL, 'trồng mới', 'Giai đoạn mới trồng cây', NULL, NULL),
(6, NULL, 'sinh trưởng', 'Giai đoạn phát triển thân lá', NULL, NULL),
(7, NULL, 'ra hoa', 'Giai đoạn cây ra hoa', NULL, NULL),
(8, NULL, 'đậu quả', 'Giai đoạn phát triển quả', NULL, NULL),
(9, NULL, 'thu hoạch', 'Giai đoạn quả chín và thu hoạch', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hoan_tien`
--

CREATE TABLE `hoan_tien` (
  `HT_MA` int(11) NOT NULL,
  `HD_STT` int(11) NOT NULL,
  `HT_SOTIEN` decimal(15,2) NOT NULL,
  `HT_MAGIAODICH` varchar(50) NOT NULL,
  `HT_LYDO` text NOT NULL,
  `HT_TRANGTHAI` enum('PENDING','PROCESSING','COMPLETED','FAILED') NOT NULL DEFAULT 'PENDING',
  `HT_NGAYTAO` timestamp NOT NULL DEFAULT current_timestamp(),
  `HT_NGAYCAPNHAT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `HT_GHICHU` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `hoan_tien`
--

INSERT INTO `hoan_tien` (`HT_MA`, `HD_STT`, `HT_SOTIEN`, `HT_MAGIAODICH`, `HT_LYDO`, `HT_TRANGTHAI`, `HT_NGAYTAO`, `HT_NGAYCAPNHAT`, `HT_GHICHU`) VALUES
(1, 280, 220000.00, '15104487', 'Hoàn tiền do hủy đơn hàng #280 - ádasdasd', 'PROCESSING', '2025-07-28 17:18:24', '2025-07-28 17:18:40', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hoa_don`
--

CREATE TABLE `hoa_don` (
  `HD_STT` int(11) NOT NULL,
  `TT_MA` int(11) NOT NULL,
  `DVC_MA` int(11) DEFAULT NULL,
  `NV_MA` int(11) NOT NULL,
  `PTTT_MA` int(11) NOT NULL,
  `KM_MA` int(11) DEFAULT NULL,
  `GH_MA` int(11) DEFAULT NULL,
  `HD_NGAYLAP` datetime NOT NULL,
  `HD_TONGTIEN` float NOT NULL,
  `HD_PHISHIP` float DEFAULT 0,
  `HD_GIAMGIA` double DEFAULT 0,
  `HD_LIDOHUY` varchar(200) DEFAULT NULL,
  `HD_DIACHI` text DEFAULT NULL,
  `HD_TENNGUOINHAN` varchar(100) DEFAULT NULL,
  `HD_SDT` varchar(20) DEFAULT NULL,
  `HD_EMAIL` varchar(150) DEFAULT NULL,
  `HD_GHICHU` text DEFAULT NULL,
  `KH_MA` int(11) DEFAULT NULL,
  `HD_MAGIAODICH` varchar(50) DEFAULT NULL COMMENT 'Mã giao dịch từ VNPay',
  `HD_TRANGTHAI` enum('Chờ xác nhận','Đã xác nhận','Đang chuẩn bị hàng','Đang giao hàng','Đã giao hàng','Đã hủy','Hoàn thành') NOT NULL DEFAULT 'Chờ xác nhận'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `hoa_don`
--

INSERT INTO `hoa_don` (`HD_STT`, `TT_MA`, `DVC_MA`, `NV_MA`, `PTTT_MA`, `KM_MA`, `GH_MA`, `HD_NGAYLAP`, `HD_TONGTIEN`, `HD_PHISHIP`, `HD_GIAMGIA`, `HD_LIDOHUY`, `HD_DIACHI`, `HD_TENNGUOINHAN`, `HD_SDT`, `HD_EMAIL`, `HD_GHICHU`, `KH_MA`, `HD_MAGIAODICH`, `HD_TRANGTHAI`) VALUES
(6, 1, 12, 1, 1, NULL, 12, '2025-06-18 16:47:56', 640000, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 4, NULL, 'Chờ xác nhận'),
(7, 1, 13, 1, 1, NULL, 12, '2025-06-18 16:48:33', 57000, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 4, NULL, 'Chờ xác nhận'),
(8, 1, 14, 1, 1, NULL, 12, '2025-06-18 16:49:59', 24000, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 4, NULL, 'Chờ xác nhận'),
(9, 1, 15, 1, 1, NULL, 7, '2025-06-19 09:43:24', 36000, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(10, 1, 16, 1, 1, NULL, 7, '2025-06-19 10:09:36', 104000, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(11, 1, 17, 1, 1, NULL, 7, '2025-06-19 10:43:08', 440000, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(12, 1, 18, 1, 1, NULL, 7, '2025-06-19 10:47:03', 26000, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(13, 1, 19, 1, 1, NULL, 7, '2025-06-19 11:06:36', 35000, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(14, 1, 20, 1, 1, NULL, 7, '2025-06-19 11:21:31', 94500, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(15, 1, 21, 1, 1, NULL, 7, '2025-06-19 11:41:08', 153000, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(16, 1, 22, 1, 1, 1, 7, '2025-06-19 11:45:56', 720000, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(17, 1, 27, 1, 1, NULL, 7, '2025-06-22 09:47:32', 59000, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(18, 1, 28, 1, 1, NULL, 7, '2025-06-22 10:11:04', 2305000, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(20, 6, 32, 1, 2, NULL, 7, '2025-06-22 11:44:49', 59000, 35000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(21, 1, 33, 1, 3, NULL, 7, '2025-06-22 11:45:43', 215000, 35000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(22, 1, 34, 1, 1, NULL, 7, '2025-06-22 11:54:49', 123000, 35000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(23, 1, 35, 1, 1, NULL, 7, '2025-06-22 11:58:42', 175000, 35000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(24, 1, 36, 1, 1, NULL, 7, '2025-06-22 12:04:24', 139800, 34800, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(25, 1, 37, 1, 1, NULL, 7, '2025-06-22 12:10:10', 140000, 35000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(26, 1, 38, 1, 1, NULL, 7, '2025-06-22 12:17:20', 260000, 35000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(27, 1, 39, 1, 1, NULL, 7, '2025-06-22 12:22:15', 150000, 35000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(28, 6, 40, 1, 2, NULL, 7, '2025-06-22 12:25:57', 77000, 35000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(29, 1, 41, 1, 1, 1, 7, '2025-06-22 12:26:59', 161000, 35000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(32, 1, 44, 1, 1, 1, NULL, '2025-06-22 13:09:44', 129000, 52500, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(33, 1, 45, 1, 1, 1, NULL, '2025-06-22 13:23:09', 140000, 35000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(34, 1, 46, 1, 1, 1, NULL, '2025-06-22 13:27:39', 24000, 35000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(35, 6, 47, 1, 2, 1, NULL, '2025-06-22 13:59:23', 1332000, 40001, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(36, 1, 48, 1, 1, NULL, NULL, '2025-06-22 14:00:41', 105000, 16500, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(37, 1, 49, 1, 1, NULL, NULL, '2025-06-22 14:02:16', 880000, 34800, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(38, 6, 50, 1, 2, 4, NULL, '2025-06-22 14:05:46', 156000, 44000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(39, 1, 51, 1, 1, NULL, NULL, '2025-06-22 14:39:25', 105000, 33000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(40, 1, 52, 1, 1, NULL, NULL, '2025-06-22 15:05:21', 178000, 37000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(41, 1, 57, 1, 1, 1, NULL, '2025-06-23 13:23:16', 32000, 32000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(42, 1, 58, 1, 3, 1, NULL, '2025-06-23 13:26:38', 32000, 32000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(43, 1, 59, 1, 1, 1, NULL, '2025-06-23 13:30:46', 16500, 16500, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(44, 1, 60, 1, 1, 1, NULL, '2025-06-23 13:30:47', 16500, 16500, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(45, 1, 61, 1, 1, 1, NULL, '2025-06-23 13:37:11', 38400, 38400, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(47, 1, 63, 1, 1, 1, NULL, '2025-06-23 13:43:48', 216000, 216000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(48, 1, 64, 1, 1, 4, NULL, '2025-06-23 13:55:02', 30000, 30000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(49, 1, 65, 1, 1, NULL, NULL, '2025-06-23 14:02:15', 38400, 38400, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(50, 1, 66, 1, 1, NULL, NULL, '2025-06-23 14:06:44', 32000, 32000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(51, 1, 67, 1, 1, 2, NULL, '2025-06-23 14:09:27', 18400, 38400, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(52, 6, 68, 1, 2, 1, NULL, '2025-06-23 18:10:45', 70000, 32000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(53, 1, 69, 1, 1, 2, NULL, '2025-06-23 18:11:49', 896500, 16500, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(54, 1, 70, 1, 1, 2, NULL, '2025-06-23 18:15:44', 141001, 36001, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(55, 1, 71, 1, 1, 1, NULL, '2025-06-23 18:37:37', 122000, 56000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(56, 1, 72, 1, 1, 1, NULL, '2025-06-23 18:56:53', 250000, 30000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(57, 6, 73, 1, 2, NULL, NULL, '2025-06-23 18:59:37', 113000, 56000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(58, 1, 74, 1, 1, 4, NULL, '2025-06-24 15:13:46', 65000, 29000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(61, 1, 77, 1, 4, NULL, NULL, '2025-06-24 15:59:09', 128400, 38400, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(62, 6, 78, 1, 2, 1, NULL, '2025-06-24 16:00:36', 95400, 38400, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(63, 6, 79, 1, 2, 1, NULL, '2025-06-24 16:06:52', 172000, 32000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(64, 6, 80, 1, 2, 1, NULL, '2025-06-24 16:13:04', 126400, 38400, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(65, 1, 81, 1, 1, 1, NULL, '2025-06-24 16:15:11', 204000, 32000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(66, 6, 82, 1, 2, NULL, NULL, '2025-06-24 16:19:20', 136000, 32000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(67, 6, 83, 1, 2, 1, NULL, '2025-06-24 16:23:38', 127000, 32000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(68, 1, 84, 1, 4, 1, NULL, '2025-06-24 16:29:44', 149000, 44000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(69, 6, 85, 1, 2, NULL, NULL, '2025-06-24 16:31:35', 104400, 38400, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(70, 1, 86, 1, 4, NULL, NULL, '2025-06-24 16:33:29', 120000, 32000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(71, 6, 87, 1, 2, 1, NULL, '2025-06-24 16:40:28', 98000, 29000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(72, 6, 88, 1, 2, NULL, NULL, '2025-06-24 16:44:10', 672000, 32000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(74, 1, 90, 1, 4, NULL, NULL, '2025-06-24 16:49:49', 992000, 32000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(76, 6, 92, 1, 2, 1, NULL, '2025-06-24 16:54:08', 512000, 32000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(77, 6, 93, 1, 2, NULL, NULL, '2025-06-24 16:54:49', 998400, 38400, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(78, 1, 94, 1, 1, 1, NULL, '2025-06-24 17:05:05', 1309000, 29000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(79, 1, 95, 1, 1, 1, NULL, '2025-06-24 17:08:27', 1158400, 38400, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(81, 1, 97, 1, 1, NULL, NULL, '2025-06-24 17:10:11', 832000, 32000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(82, 2, 98, 1, 1, NULL, NULL, '2025-06-24 17:13:25', 56000, 32000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(83, 6, 99, 1, 2, 1, NULL, '2025-06-24 17:17:21', 742000, 32000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(85, 1, 101, 1, 1, 1, NULL, '2025-06-24 17:20:35', 74000, 32000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(86, 1, 102, 1, 1, NULL, NULL, '2025-06-24 17:26:28', 56000, 32000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(87, 1, 103, 1, 1, 1, NULL, '2025-06-24 17:28:38', 68000, 32000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(88, 1, 104, 1, 1, NULL, NULL, '2025-06-24 17:32:01', 91000, 43000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(89, 2, 105, 1, 1, NULL, NULL, '2025-06-24 17:34:58', 74400, 38400, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(90, 2, 106, 1, 1, 1, NULL, '2025-06-24 17:36:09', 62000, 32000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(91, 2, 107, 1, 1, 1, NULL, '2025-06-24 17:39:59', 512000, 32000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(92, 2, 108, 1, 1, 4, NULL, '2025-06-24 17:45:11', 133400, 38400, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(93, 6, 109, 1, 2, 4, NULL, '2025-06-24 17:49:04', 120000, 32000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(94, 6, 110, 1, 2, NULL, NULL, '2025-06-24 17:52:52', 164000, 32000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(95, 2, 111, 1, 1, 1, NULL, '2025-06-24 18:05:46', 183000, 29000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(101, 2, 117, 1, 1, 2, NULL, '2025-06-24 18:15:53', 172000, 32000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(102, 2, 118, 1, 1, 1, NULL, '2025-06-24 18:19:03', 146000, 32000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(106, 1, 123, 1, 3, NULL, NULL, '2025-06-24 19:02:02', 228400, 38400, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(107, 1, 124, 1, 3, 1, NULL, '2025-06-24 19:06:10', 392000, 32000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(110, 1, 127, 1, 3, 1, NULL, '2025-06-24 19:14:02', 829000, 29000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(111, 2, 128, 1, 1, 1, NULL, '2025-06-24 19:14:49', 172000, 32000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(116, 6, 133, 1, 2, 1, NULL, '2025-06-24 19:33:21', 120000, 32000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(120, 2, 137, 1, 1, 1, NULL, '2025-06-25 15:33:53', 44000, 32000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(121, 1, 138, 1, 3, NULL, NULL, '2025-06-26 23:03:41', 188000, 32000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(122, 1, 139, 1, 3, 1, NULL, '2025-06-27 00:02:45', 122400, 38400, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(123, 1, 140, 1, 3, 1, NULL, '2025-06-27 00:20:37', 56400, 38400, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(124, 1, 141, 1, 3, 1, NULL, '2025-06-28 09:28:27', 206800, 34800, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(125, 2, 142, 1, 1, 1, NULL, '2025-06-28 09:59:49', 68400, 38400, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(126, 1, 143, 1, 3, 5, NULL, '2025-07-05 14:53:41', 676500, 16500, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(127, 1, 144, 1, 3, 5, NULL, '2025-07-06 14:23:07', 424994, 89494, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(128, 1, 145, 1, 3, NULL, NULL, '2025-07-06 16:48:04', 1182000, 61996, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(129, 1, 146, 1, 3, 5, NULL, '2025-07-07 13:03:55', 81000, 37000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(130, 1, 147, 1, 3, 2, NULL, '2025-07-07 13:05:23', 107999, 41999, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(131, 1, 148, 1, 3, NULL, NULL, '2025-07-07 13:19:57', 226998, 51998, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(132, 1, 149, 1, 3, 2, NULL, '2025-07-07 15:51:26', 218996, 66996, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(133, 1, 150, 1, 3, 2, NULL, '2025-07-07 15:57:26', 138998, 46998, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(134, 2, 151, 1, 1, 2, NULL, '2025-07-07 15:57:53', 122998, 46998, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(135, 1, 152, 1, 3, 2, NULL, '2025-07-07 21:47:35', 146999, 41999, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(136, 1, 153, 1, 3, NULL, NULL, '2025-07-07 21:48:23', 61000, 31000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(137, 1, 154, 1, 3, 5, NULL, '2025-07-07 22:02:05', 701999, 41999, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(138, 1, 155, 1, 3, NULL, NULL, '2025-07-07 22:13:43', 175000, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(139, 1, 156, 1, 3, 2, NULL, '2025-07-07 23:35:19', 386995, 71995, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(140, 1, 157, 1, 3, NULL, NULL, '2025-07-08 12:53:21', 250974, 226974, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(141, 1, 158, 1, 3, 2, NULL, '2025-07-08 13:06:47', 251994, 76994, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(145, 1, 162, 1, 3, NULL, NULL, '2025-07-08 15:26:10', 436974, 226974, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(146, 1, 163, 1, 3, NULL, NULL, '2025-07-09 13:20:18', 653976, 248976, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(147, 1, 166, 1, 3, NULL, NULL, '2025-07-09 13:50:04', 284976, 248976, 0, NULL, ', Xã Nghĩa Hưng, Huyện Chư Păh, Tỉnh Gia Lai', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', 2, NULL, 'Chờ xác nhận'),
(149, 1, NULL, 1, 3, 2, NULL, '2025-07-09 15:46:13', 875000, 169011, 0, NULL, '4444, Xã An Bá, Huyện Sơn Động, Tỉnh Bắc Giang', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', 2, NULL, 'Chờ xác nhận'),
(150, 1, NULL, 1, 3, 5, NULL, '2025-07-09 15:47:07', 175000, 226974, 0, NULL, '444, Xã Bum Tở, Huyện Mường Tè, Tỉnh Lai Châu', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', 2, NULL, 'Chờ xác nhận'),
(151, 1, NULL, 1, 3, 2, NULL, '2025-07-09 15:48:11', 1100000, 169011, 0, NULL, '511, Xã Khe Mo, Huyện Đồng Hỷ, Tỉnh Thái Nguyên', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', 2, NULL, 'Chờ xác nhận'),
(152, 1, NULL, 1, 3, 2, NULL, '2025-07-09 15:52:40', 7700000, 117608, 0, NULL, '333, Xã Săm Khóe, Huyện Mai Châu, Tỉnh Hoà Bình', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', 2, NULL, 'Chờ xác nhận'),
(153, 1, NULL, 1, 1, 5, NULL, '2025-07-10 10:01:53', 880000, 169011, 0, NULL, '444, Xã Chiến Thắng, Huyện Bắc Sơn, Tỉnh Lạng Sơn', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', 2, NULL, 'Chờ xác nhận'),
(154, 1, NULL, 1, 1, NULL, NULL, '2025-07-10 10:07:25', 105000, 169011, 0, NULL, '444, Xã Thanh Định, Huyện Định Hóa, Tỉnh Thái Nguyên', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', 2, NULL, 'Chờ xác nhận'),
(155, 1, NULL, 1, 3, 2, NULL, '2025-07-10 10:09:37', 660000, 226974, 0, NULL, '444, Xã Bảo Toàn, Huyện Bảo Lạc, Tỉnh Cao Bằng', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', 2, NULL, 'Chờ xác nhận'),
(156, 1, NULL, 1, 1, NULL, NULL, '2025-07-10 10:11:52', 140000, 169011, 0, NULL, '333, Xã Lâu Thượng, Huyện Võ Nhai, Tỉnh Thái Nguyên', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', 2, NULL, 'Chờ xác nhận'),
(157, 1, NULL, 1, 1, NULL, NULL, '2025-07-10 10:17:10', 880000, 147010, 0, NULL, '333, Xã Mai Hạ, Huyện Mai Châu, Tỉnh Hoà Bình', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', 2, NULL, 'Chờ xác nhận'),
(158, 1, NULL, 1, 1, NULL, NULL, '2025-07-10 10:22:08', 880000, 169011, 0, NULL, '444, Xã Thanh Lâm, Huyện Ba Chẽ, Tỉnh Quảng Ninh', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', 2, NULL, 'Chờ xác nhận'),
(159, 1, NULL, 1, 1, NULL, NULL, '2025-07-10 10:33:53', 42000, 248976, 0, NULL, '444, Xã Trung Thu, Huyện Tủa Chùa, Tỉnh Điện Biên', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', 2, NULL, 'Chờ xác nhận'),
(160, 2, NULL, 1, 1, 5, NULL, '2025-07-10 10:38:05', 880000, 169011, 0, NULL, '4444, Xã Văn Xá, Thị xã Kim Bảng, Tỉnh Hà Nam', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', 2, '15065664', 'Chờ xác nhận'),
(161, 2, NULL, 1, 1, 2, NULL, '2025-07-10 11:23:32', 175000, 56001, 0, NULL, '444, Thị trấn Lăng Can, Huyện Lâm Bình, Tỉnh Tuyên Quang', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', 2, '15065786', 'Chờ xác nhận'),
(162, 1, NULL, 1, 3, 2, NULL, '2025-07-12 23:04:44', 54000, 24001, 0, NULL, '444, Xã Định Biên, Huyện Định Hóa, Tỉnh Thái Nguyên', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', 2, NULL, 'Chờ xác nhận'),
(163, 1, NULL, 1, 3, 2, NULL, '2025-07-14 14:14:44', 141000, 51998, 0, NULL, '444, Xã Xuân Nội, Huyện Trùng Khánh, Tỉnh Cao Bằng', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', 2, NULL, 'Chờ xác nhận'),
(164, 1, NULL, 1, 3, NULL, NULL, '2025-07-14 14:29:12', 35000, 34800, 0, NULL, '444, Xã Đồng Thịnh, Huyện Định Hóa, Tỉnh Thái Nguyên', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', 2, NULL, 'Chờ xác nhận'),
(165, 1, NULL, 1, 3, 2, NULL, '2025-07-14 14:36:54', 140000, 55997, 0, NULL, '444, Phường 26, Quận Bình Thạnh, Thành phố Hồ Chí Minh', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', 2, NULL, 'Chờ xác nhận'),
(166, 1, 167, 1, 1, NULL, NULL, '2025-07-15 11:53:10', 499000, 32000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(167, 6, 168, 1, 2, NULL, NULL, '2025-07-15 12:39:52', 851995, 67995, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 'Chờ xác nhận'),
(168, 6, NULL, 1, 2, NULL, NULL, '2025-07-16 15:24:33', 293000, 0, 0, NULL, '444, 2908, 176, 16', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(169, 6, NULL, 1, 2, NULL, NULL, '2025-07-16 15:31:10', 880000, 0, 0, NULL, '444, 987, 59, 3', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(170, 6, NULL, 1, 2, NULL, NULL, '2025-07-16 15:35:22', 70000, 0, 0, NULL, '444, 2369, 154, 13', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(171, 6, NULL, 1, 2, NULL, NULL, '2025-07-16 15:37:08', 880000, 0, 0, NULL, '444, 2624, 167, 15', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(172, 6, NULL, 1, 2, NULL, NULL, '2025-07-16 15:39:23', 105000, 0, 0, NULL, '444, 10834, 644, 57', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(173, 2, NULL, 1, 2, NULL, NULL, '2025-07-16 15:50:28', 210000, 0, 0, NULL, '444, 1008, 61, 3', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, '15078697', 'Chờ xác nhận'),
(174, 1, NULL, 1, 1, 1, NULL, '2025-07-16 15:59:00', 175000, 35000, 0, NULL, '444, 2129, 142, 12', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(175, 2, NULL, 1, 2, NULL, NULL, '2025-07-16 16:10:08', 140000, 0, 0, NULL, '4, 2126, 142, 12', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, '15078776', 'Chờ xác nhận'),
(176, 1, NULL, 1, 1, NULL, NULL, '2025-07-16 16:19:32', 1100000, 76794, 0, NULL, '444, 3365, 195, 18', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(177, 1, NULL, 1, 1, 5, NULL, '2025-07-16 16:25:29', 208250, 95990, 0, NULL, '444, 1008, 61, 3', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(178, 1, NULL, 1, 1, 5, NULL, '2025-07-16 16:30:33', 229500, 30000, 0, NULL, '444, 2624, 167, 15', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(180, 1, NULL, 1, 1, 5, NULL, '2025-07-16 16:31:45', 208250, 79992, 0, NULL, '444, 2444, 158, 14', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(181, 1, NULL, 1, 1, 5, NULL, '2025-07-16 16:33:23', 148750, 63995, 0, NULL, '444, 2307, 150, 13', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(182, 1, NULL, 1, 1, 5, NULL, '2025-07-16 16:37:25', 374000, 48000, 0, NULL, '44, 2623, 167, 15', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(183, 1, NULL, 1, 1, 5, NULL, '2025-07-16 16:38:43', 561000, 47998, 0, NULL, '444, 2304, 150, 13', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(186, 1, NULL, 1, 1, 5, NULL, '2025-07-16 16:47:48', 440000, 115187, 0, NULL, '444, 2623, 167, 15', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(187, 1, NULL, 1, 1, 5, NULL, '2025-07-16 16:48:11', 190000, 86004, 0, NULL, '444, 1202, 74, 4', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(188, 1, NULL, 1, 1, 5, NULL, '2025-07-16 16:50:53', 1100000, 84002, 0, NULL, '444, 1001, 60, 3', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(189, 1, NULL, 1, 1, 5, NULL, '2025-07-16 16:55:02', 198000, 95989, 0, NULL, '444, 2082, 140, 12', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(190, 1, NULL, 1, 1, 5, NULL, '2025-07-16 16:55:45', 207000, 115187, 0, NULL, '444, 3184, 185, 17', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(191, 1, NULL, 1, 1, 5, NULL, '2025-07-16 16:56:05', 175000, 76794, 0, NULL, '444, 2595, 166, 15', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(192, 1, NULL, 1, 1, 5, NULL, '2025-07-16 16:56:43', 171000, 95989, 0, NULL, '444, 2307, 150, 13', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(193, 1, NULL, 1, 1, 5, NULL, '2025-07-16 16:59:50', 800000, 63995, 0, NULL, '333, 2907, 176, 16', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(194, 1, NULL, 1, 1, 5, NULL, '2025-07-16 17:02:35', 704000, 183973, 0, NULL, '444, 2079, 140, 12', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(198, 1, NULL, 1, 1, 5, NULL, '2025-07-16 17:21:42', 350000, 103988, 0, NULL, '44, 2348, 153, 13', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(199, 1, NULL, 1, 1, 5, NULL, '2025-07-16 17:24:06', 880000, 55997, 0, NULL, '444, 2307, 150, 13', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(200, 1, NULL, 1, 1, 5, NULL, '2025-07-16 17:48:56', 1320000, 95003, 0, NULL, '444, 1001, 60, 3', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(201, 1, NULL, 1, 1, 5, NULL, '2025-07-16 17:52:29', 258000, 95003, 0, NULL, '44, 1001, 60, 3', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(202, 1, NULL, 1, 1, 5, NULL, '2025-07-16 17:53:38', 280000, 30000, 0, NULL, '444, 2646, 168, 15', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(203, 1, NULL, 1, 1, 5, NULL, '2025-07-16 17:54:49', 182000, 79992, 0, NULL, '44, 2985, 179, 16', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(205, 1, NULL, 1, 1, 5, NULL, '2025-07-16 18:14:52', 180000, 23000, 0, NULL, '44, 1202, 74, 4', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(206, 1, NULL, 1, 1, 5, NULL, '2025-07-16 18:16:23', 880000, 50001, 0, NULL, '444, 1202, 74, 4', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(208, 1, NULL, 1, 1, 5, NULL, '2025-07-16 18:25:50', 182000, 79992, 0, NULL, 'Cần thơ, 2102, 141, 12', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(209, 1, NULL, 1, 1, 5, NULL, '2025-07-16 18:27:18', 280000, 87991, 0, NULL, '444, 2156, 143, 12', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(210, 1, NULL, 1, 1, 5, NULL, '2025-07-16 18:31:25', 210000, 71994, 0, NULL, '444, 2262, 148, 13', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(211, 1, NULL, 1, 1, 5, NULL, '2025-07-16 18:45:30', 180000, 25000, 0, NULL, '444, 2281, 149, 13', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(212, 1, NULL, 1, 1, 5, NULL, '2025-07-16 18:55:14', 440000, 87000, 0, NULL, '444, 2101, 141, 12', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(213, 1, NULL, 1, 1, 5, NULL, '2025-07-16 18:59:24', 320000, 87000, 0, NULL, '444, 2649, 168, 15', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(214, 1, NULL, 1, 1, NULL, NULL, '2025-07-16 19:13:31', 245000, 95990, 0, NULL, '444, 1856, 129, 11', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(215, 1, NULL, 1, 1, 5, NULL, '2025-07-16 19:49:17', 210000, 71994, 0, NULL, '444, 52, 3, 1', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(216, 1, NULL, 1, 1, 5, NULL, '2025-07-16 19:59:29', 1320000, 71994, 0, NULL, '444, 2426, 157, 14', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(217, 1, NULL, 1, 1, 5, NULL, '2025-07-16 20:12:44', 280000, 74003, 0, NULL, '444, 1223, 76, 4', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(218, 1, NULL, 1, 1, 5, NULL, '2025-07-16 20:41:11', 1100000, 194400, 165000, NULL, '444, 1855, 129, 11', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(219, 1, NULL, 1, 1, 5, NULL, '2025-07-16 20:41:34', 1100000, 84002, 165000, NULL, '44, 1001, 60, 3', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(220, 1, NULL, 1, 1, 5, NULL, '2025-07-16 20:53:24', 1280000, 87991, 192000, NULL, '444, 2834, 174, 16', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(221, 1, NULL, 1, 1, 2, NULL, '2025-07-16 21:06:38', 385000, 73791, 20000, NULL, '44, 661, 34, 2', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(222, 1, NULL, 1, 1, 2, NULL, '2025-07-16 23:34:36', 114000, 0, 20000, NULL, '44, 2464, 159, 14', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(223, 1, NULL, 1, 1, 2, NULL, '2025-07-17 13:00:19', 385000, 35993, 20000, NULL, '444, 1701, 119, 10', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(224, 2, NULL, 1, 2, 5, NULL, '2025-07-17 14:03:53', 1320000, 11678, 198000, NULL, '444, 1648, 115, 9', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, '15080688', 'Chờ xác nhận'),
(225, 1, NULL, 1, 1, 2, NULL, '2025-07-17 14:21:45', 140000, 35997, 20000, NULL, '444, 2334, 152, 13', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(226, 1, NULL, 1, 1, 2, NULL, '2025-07-17 14:43:25', 105000, 37599, 20000, NULL, '444, 2623, 167, 15', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(227, 1, NULL, 1, 1, 5, NULL, '2025-07-17 14:53:19', 175000, 56794, 26250, NULL, '444, XÃ YÊN TỪ, HUYỆN YÊN MÔ, Ninh Bình', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(228, 1, NULL, 1, 1, NULL, NULL, '2025-07-17 14:59:02', 24000, 5000, 0, NULL, '444, XÃ PHÚ XUÂN, HUYỆN BÌNH XUYÊN, Vĩnh Phúc', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(229, 1, NULL, 1, 1, 2, NULL, '2025-07-17 15:04:17', 105000, 27998, 20000, NULL, '444, XÃ ĐỒNG HÓA, HUYỆN KIM BẢNG, Hà Nam', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(230, 1, NULL, 1, 1, 2, NULL, '2025-07-17 15:09:10', 1980000, 39993, 20000, NULL, '444', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(231, 1, NULL, 1, 1, 5, NULL, '2025-07-17 15:23:29', 335000, 49592, 50250, NULL, '444', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(232, 1, NULL, 1, 1, 2, NULL, '2025-07-19 14:32:45', 315000, 27995, 20000, NULL, '444', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(239, 1, NULL, 1, 1, NULL, NULL, '2025-07-19 15:32:16', 2860000, 56790, 0, NULL, '444', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(240, 3, NULL, 1, 1, NULL, NULL, '2025-07-19 15:37:00', 60000, 10000, 0, NULL, '444', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(248, 3, NULL, 1, 1, 2, NULL, '2025-07-19 16:09:10', 133000, 75990, 0, NULL, '444', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(252, 2, NULL, 1, 2, 5, NULL, '2025-07-19 16:23:09', 280000, 41194, 0, NULL, '444', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, '15085216', 'Chờ xác nhận'),
(253, 3, NULL, 1, 1, 5, NULL, '2025-07-19 16:29:17', 1841000, 71987, 0, NULL, '444', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(254, 2, NULL, 1, 1, 2, NULL, '2025-07-19 16:33:30', 133000, 75990, 0, NULL, '444', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(255, 2, NULL, 1, 1, 2, NULL, '2025-07-19 16:35:32', 245000, 75990, 0, NULL, '444', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(256, 4, NULL, 1, 1, 5, NULL, '2025-07-19 16:40:01', 1320000, 15997, 198000, NULL, '444', NULL, '0793994771', 'duyquang2709pp@gmail.com', 'Thay đổi phương thức thanh toán', 2, NULL, 'Chờ xác nhận'),
(257, 5, NULL, 1, 1, 2, NULL, '2025-07-19 17:00:19', 84000, 10000, 20000, 'ádasd', '444', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(258, 2, NULL, 1, 2, 2, NULL, '2025-07-19 17:00:50', 78000, 3999, 20000, NULL, '444', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, '15085267', 'Chờ xác nhận'),
(259, 4, NULL, 1, 1, 5, NULL, '2025-07-20 14:52:40', 152000, 67991, 22800, 'kkkkk', '444', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(260, 2, NULL, 1, 2, 5, NULL, '2025-07-21 16:15:34', 175000, 56794, 26250, NULL, '444', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, '15088936', 'Chờ xác nhận'),
(261, 2, NULL, 1, 2, 5, NULL, '2025-07-22 11:28:17', 210000, 66392, 31500, NULL, '444', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, '15090769', 'Chờ xác nhận'),
(262, 2, NULL, 1, 2, NULL, NULL, '2025-07-22 11:55:54', 36000, 5000, 0, NULL, '444', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, '15090839', 'Chờ xác nhận'),
(263, 3, NULL, 1, 2, 5, NULL, '2025-07-22 12:06:45', 210000, 66392, 31500, NULL, '444', NULL, '0793994771', 'duyquang2709pp@gmail.com', 'dđ', 2, NULL, 'Chờ xác nhận'),
(264, 3, NULL, 1, 1, 5, NULL, '2025-07-23 18:04:25', 210000, 51994, 31500, NULL, '44', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(265, 3, NULL, 1, 1, 5, NULL, '2025-07-23 18:08:54', 880000, 7999, 132000, NULL, '44', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(266, 1, NULL, 1, 1, 5, NULL, '2025-07-26 09:56:26', 596000, 57989, 89400, NULL, '444', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(267, 3, NULL, 1, 1, 5, NULL, '2025-07-26 10:11:48', 175000, 6501, 26250, NULL, '511', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(268, 3, NULL, 1, 1, NULL, NULL, '2025-07-26 13:35:37', 45000, 23000, 0, NULL, '511', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(269, 3, NULL, 1, 2, NULL, NULL, '2025-07-28 22:56:19', 182000, 44974, 0, NULL, '444', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, '15104419', 'Chờ xác nhận'),
(270, 1, NULL, 1, 1, 5, NULL, '2025-07-28 23:25:52', 1980000, 25595, 297000, NULL, '511', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(271, 6, NULL, 1, 2, NULL, NULL, '2025-07-28 23:29:34', 140000, 35997, 0, NULL, '511', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(272, 2, NULL, 1, 2, 5, NULL, '2025-07-28 23:30:25', 1100000, 11997, 165000, NULL, '511', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, '15104450', 'Chờ xác nhận'),
(273, 6, NULL, 1, 2, 5, NULL, '2025-07-28 23:41:55', 1540000, 17996, 231000, NULL, '511', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, '15104465', 'Chờ xác nhận'),
(274, 1, NULL, 1, 2, 5, NULL, '2025-07-28 23:50:09', 210000, 48394, 31500, NULL, '511', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(275, 4, NULL, 1, 2, NULL, NULL, '2025-07-28 23:54:47', 1320000, 14197, 0, 'đổi địa chỉ', '511', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, '15104471', 'Chờ xác nhận'),
(276, 1, NULL, 1, 1, NULL, NULL, '2025-07-29 00:07:54', 110000, 43995, 0, NULL, '511', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(277, 1, NULL, 1, 2, 5, NULL, '2025-07-29 00:08:11', 171000, 71190, 25650, NULL, '511', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(278, 1, NULL, 1, 2, NULL, NULL, '2025-07-29 00:09:22', 138000, 48394, 0, NULL, '511', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(279, 1, NULL, 1, 2, NULL, NULL, '2025-07-29 00:15:10', 36000, 25000, 0, NULL, '511', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận'),
(280, 4, NULL, 1, 2, NULL, NULL, '2025-07-29 00:16:35', 220000, 78789, 0, 'ádasdasd', '511', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, '15104487', 'Chờ xác nhận'),
(281, 3, NULL, 1, 2, NULL, NULL, '2025-07-29 10:03:29', 1280000, 21795, 0, NULL, '511', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, '15104824', 'Chờ xác nhận'),
(282, 1, NULL, 1, 2, 5, NULL, '2025-07-30 15:39:27', 475000, 29294, 71250, NULL, '511', NULL, '0793994771', 'duyquang2709pp@gmail.com', NULL, 2, NULL, 'Chờ xác nhận');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khach_hang`
--

CREATE TABLE `khach_hang` (
  `KH_MA` int(11) NOT NULL,
  `KH_TEN` varchar(100) NOT NULL,
  `KH_DIACHI` text NOT NULL,
  `KH_SDT` varchar(10) NOT NULL,
  `KH_NGAYSINH` datetime DEFAULT NULL,
  `KH_EMAIL` varchar(150) NOT NULL,
  `KH_GIOITINH` char(1) DEFAULT NULL,
  `KH_NGAYDK` datetime NOT NULL,
  `KH_TENDANGNHAP` varchar(50) NOT NULL,
  `KH_MATKHAU` varchar(50) NOT NULL,
  `KH_AVATAR` varchar(200) NOT NULL,
  `KH_GOOGLEID` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `khach_hang`
--

INSERT INTO `khach_hang` (`KH_MA`, `KH_TEN`, `KH_DIACHI`, `KH_SDT`, `KH_NGAYSINH`, `KH_EMAIL`, `KH_GIOITINH`, `KH_NGAYDK`, `KH_TENDANGNHAP`, `KH_MATKHAU`, `KH_AVATAR`, `KH_GOOGLEID`) VALUES
(1, 'Duy Quang', 'cần thơ', '2123123123', '2000-10-23 00:00:00', 'duyquang2709@gmail.com', 'M', '2025-05-24 00:00:00', 'quang123', '123', 'default-avatar.jpg', NULL),
(2, 'Quang Duy', '511, PHƯỜNG AN HỘI, QUẬN NINH KIỀU, Cần Thơ', '0793994771', '2025-06-21 00:00:00', 'duyquang2709pp@gmail.com', 'M', '2025-05-24 00:00:00', 'google_1748064450', 'b727f634ace7906ea4573711412938ee', 'default-avatar.jpg', '112253003004925133473'),
(3, 'Nguyen Nhat Duy Quang C2200016', 'Cần thơ', '0793994771', '2000-10-14 00:00:00', 'quangc2200016@student.ctu.edu.vn', 'M', '2025-06-16 00:00:00', 'google_1750061149', 'b83f5eb3138149a2251fcfe81c8d2648', 'default-avatar.jpg', '113160860558260392135'),
(4, 'Quang Duy', 'Cần thơ', '0793994771', '0000-00-00 00:00:00', 'chayspin2709@gmail.com', 'M', '2025-06-18 00:00:00', 'google_1750235816', '45cc106d201bcd2a1291113218e76e84', 'default-avatar.jpg', '117356618720922982574');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khuyen_mai`
--

CREATE TABLE `khuyen_mai` (
  `KM_MA` int(11) NOT NULL,
  `Code` varchar(50) NOT NULL,
  `KM_TGBD` datetime NOT NULL,
  `KM_TGKT` datetime NOT NULL,
  `KM_GIATRI` float NOT NULL,
  `hinh_thuc_km` enum('percent','fixed') NOT NULL DEFAULT 'fixed',
  `KM_DKSD` decimal(10,2) DEFAULT NULL COMMENT 'Giá trị đơn hàng tối thiểu',
  `KM_TRANGTHAI` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1: Kích hoạt, 0: Vô hiệu',
  `KM_MOTA` text DEFAULT NULL,
  `KM_NGAYTAO` timestamp NOT NULL DEFAULT current_timestamp(),
  `KM_NGAYCAPNHAT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `KM_SOLUONG` int(11) DEFAULT NULL COMMENT 'Số lượng mã còn lại, NULL là không giới hạn',
  `KM_SOLUONG_TOIDA` int(11) DEFAULT NULL COMMENT 'Số lượng mã tối đa ban đầu, NULL là không giới hạn'
) ;

--
-- Đang đổ dữ liệu cho bảng `khuyen_mai`
--

INSERT INTO `khuyen_mai` (`KM_MA`, `Code`, `KM_TGBD`, `KM_TGKT`, `KM_GIATRI`, `hinh_thuc_km`, `KM_DKSD`, `KM_TRANGTHAI`, `KM_MOTA`, `KM_NGAYTAO`, `KM_NGAYCAPNHAT`, `KM_SOLUONG`, `KM_SOLUONG_TOIDA`) VALUES
(1, 'PHANBON10', '2025-06-01 00:00:00', '2025-06-30 23:59:59', 10, 'percent', 100000.00, 1, 'Giảm 10% cho đơn từ 100,000đ', '2025-07-07 06:47:54', '2025-07-07 06:54:53', NULL, NULL),
(2, 'THUOC20K', '2025-06-15 00:00:00', '2025-07-19 23:59:59', 20000, 'fixed', 50000.00, 1, 'Giảm 20,000đ cho đơn từ 50,000đ', '2025-07-07 06:47:54', '2025-07-16 13:59:45', NULL, NULL),
(3, 'MUA2TANG1', '2025-06-10 00:00:00', '2025-06-25 23:59:59', 0, 'fixed', 0.00, 1, 'Mua 2 tặng 1', '2025-07-07 06:47:54', '2025-07-07 06:54:53', NULL, NULL),
(4, 'FARMER5', '2025-06-01 00:00:00', '2025-06-30 23:59:59', 5, 'percent', 200000.00, 1, 'Giảm 5% cho đơn từ 200,000đ', '2025-07-07 06:47:54', '2025-07-07 06:54:53', NULL, NULL),
(5, 'PESTCONTROL', '2025-07-01 00:00:00', '2025-07-31 23:59:59', 15, 'percent', 150000.00, 1, 'Giảm 15% cho đơn từ 150,000đ', '2025-07-07 06:47:54', '2025-07-07 06:54:53', NULL, NULL),
(6, 'TANGCHAU', '2025-06-01 00:00:00', '2025-06-30 23:59:59', 0, 'fixed', 0.00, 1, 'Tặng chậu khi mua sản phẩm', '2025-07-07 06:47:54', '2025-07-07 06:54:53', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khuyen_mai_san_pham`
--

CREATE TABLE `khuyen_mai_san_pham` (
  `KMSP_MA` int(11) NOT NULL COMMENT 'Mã khuyến mãi sản phẩm',
  `KM_MA` int(11) NOT NULL COMMENT 'Mã khuyến mãi',
  `SP_MA` int(11) NOT NULL COMMENT 'Mã sản phẩm',
  `KMSP_SOLUONG_MUA` int(11) DEFAULT 1 COMMENT 'Số lượng cần mua để được khuyến mãi',
  `KMSP_SOLUONG_TANG` int(11) DEFAULT 0 COMMENT 'Số lượng được tặng',
  `KMSP_QUATANG` varchar(255) DEFAULT NULL COMMENT 'Mô tả quà tặng kèm',
  `KMSP_NGAYTAO` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Ngày tạo',
  `KMSP_NGAYCAPNHAT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  `KMSP_TRANGTHAI` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái: 1-Kích hoạt, 0-Vô hiệu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Bảng chi tiết khuyến mãi theo sản phẩm';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lich_su_ton_kho`
--

CREATE TABLE `lich_su_ton_kho` (
  `LSTK_MA` int(11) NOT NULL,
  `SP_MA` int(11) NOT NULL,
  `LSTK_LOAI` varchar(50) NOT NULL COMMENT 'Loại điều chỉnh: NHAP/XUAT/DIEU_CHINH',
  `LSTK_SOLUONG` int(11) NOT NULL COMMENT 'Số lượng thay đổi',
  `LSTK_SOLUONG_CU` int(11) NOT NULL COMMENT 'Số lượng trước khi thay đổi',
  `LSTK_SOLUONG_MOI` int(11) NOT NULL COMMENT 'Số lượng sau khi thay đổi',
  `LSTK_GHICHU` text DEFAULT NULL COMMENT 'Lý do điều chỉnh',
  `NV_MA` int(11) DEFAULT NULL COMMENT 'Mã nhân viên thực hiện',
  `LSTK_THOIGIAN` timestamp NOT NULL DEFAULT current_timestamp(),
  `LSTK_THAMCHIEU` varchar(50) DEFAULT NULL COMMENT 'Mã tham chiếu (HD_STT nếu là đơn hàng, PN_STT nếu là phiếu nhập)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `lich_su_ton_kho`
--

INSERT INTO `lich_su_ton_kho` (`LSTK_MA`, `SP_MA`, `LSTK_LOAI`, `LSTK_SOLUONG`, `LSTK_SOLUONG_CU`, `LSTK_SOLUONG_MOI`, `LSTK_GHICHU`, `NV_MA`, `LSTK_THOIGIAN`, `LSTK_THAMCHIEU`) VALUES
(1, 13, 'NHAP', 20, 20, 40, 'thiếu hàng', 1, '2025-07-19 09:51:40', NULL),
(2, 8, 'XUAT', 20, 12988, 12968, 'hư hỏng', 1, '2025-07-19 10:18:35', NULL),
(3, 8, 'XUAT', 20, 12968, 12948, 'hư hỏng', 1, '2025-07-19 10:18:42', NULL),
(4, 8, 'XUAT', 2, 12948, 12946, 'hư', 1, '2025-07-19 10:18:58', NULL),
(5, 13, 'XUAT', 20, 40, 20, 'hàng hết hạn', 1, '2025-07-20 08:29:06', NULL),
(6, 13, 'XUAT', 10, 20, 10, 'hư ', 1, '2025-07-21 16:24:30', NULL),
(7, 13, 'NHAP', 20, 10, 30, 'thêm hàng ', 1, '2025-07-22 05:03:13', NULL),
(8, 13, 'NHAP', 40, 30, 70, 'thêm hàng ', 1, '2025-07-22 05:03:25', NULL),
(9, 13, 'XUAT', 60, 70, 10, 'hư ', 1, '2025-07-22 08:19:27', NULL),
(10, 13, 'NHAP', 10, 10, 20, 'hư ', 1, '2025-07-22 09:40:31', NULL),
(11, 13, 'NHAP', 20, 20, 40, '0', 1, '2025-07-22 10:13:17', NULL),
(12, 1, 'XUAT', 20, 989, 969, '0', 1, '2025-07-22 10:36:51', NULL),
(13, 2, 'NHAP', 20, 470, 490, 'Nhập kho từ phiếu nhập #PN20250727093931 - thêm', 1, '2025-07-27 07:39:51', 'PN20250727093931'),
(14, 2, 'NHAP', 20, 490, 510, 'Nhập kho từ phiếu nhập #PN20250727094023 - thêm', 1, '2025-07-27 07:40:37', 'PN20250727094023'),
(15, 7, 'NHAP', 200, 1496, 1696, 'Nhập kho từ phiếu nhập #PN20250727095049 - thêm hàng ', 1, '2025-07-27 07:51:17', 'PN20250727095049'),
(16, 6, 'NHAP', 20, 2006, 2026, 'Nhập kho từ phiếu nhập #PN20250727095540 - thêm', 1, '2025-07-27 07:55:57', 'PN20250727095540'),
(17, 6, 'NHAP', 20, 2026, 2046, 'Nhập kho từ phiếu nhập #PN20250727095540 - thêm', 1, '2025-07-27 07:59:34', 'PN20250727095540'),
(18, 2, 'NHAP', 200, 510, 710, 'Nhập kho từ phiếu nhập #PN20250727095938 - thêm', 1, '2025-07-27 07:59:57', 'PN20250727095938'),
(19, 1, 'XUAT', 2, 969, 967, '0', 1, '2025-07-28 05:45:37', NULL),
(20, 1, 'NHAP', 20, 967, 987, '0', 1, '2025-07-28 06:04:24', NULL),
(21, 1, 'NHAP', 2, 987, 989, 'thêm hàng', 1, '2025-07-28 06:19:41', NULL),
(22, 1, 'NHAP', 0, 989, 989, '', 1, '2025-07-28 07:56:10', NULL),
(23, 1, 'NHAP', 0, 989, 989, '', 1, '2025-07-28 07:59:33', NULL),
(24, 1, 'NHAP', 2, 989, 991, 'thêm', 1, '2025-07-28 08:01:32', NULL),
(25, 1, 'NHAP', 2, 991, 993, 'thêm', 1, '2025-07-28 08:11:09', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `loai_benh`
--

CREATE TABLE `loai_benh` (
  `LCT_MA` int(11) NOT NULL,
  `Ma_loai_benh` int(11) NOT NULL,
  `Ten_loai_benh` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `mo_ta` varchar(50) DEFAULT NULL,
  `hinh_anh` varchar(30) DEFAULT NULL,
  `cach_phong_ngua` char(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `loai_benh`
--

INSERT INTO `loai_benh` (`LCT_MA`, `Ma_loai_benh`, `Ten_loai_benh`, `mo_ta`, `hinh_anh`, `cach_phong_ngua`) VALUES
(1, 1, 'Bệnh đốm lá', 'Triệu chứng: Lá cây chuyển vàng bắt đầu từ gân chí', 'benh_dom_la.jpg', 'Phun thuốc'),
(1, 2, 'Bệnh thối rễ', 'Triệu chứng: Đốm vàng cam xuất hiện mặt dưới lá, c', 'benh_thoi_re.jpg', 'Thoát nước'),
(2, 3, 'Bệnh phấn trắng', 'Triệu chứng: Thân và cành xuất hiện lớp nấm sợi mà', 'benh_phan_trang.jpg', 'Phun thuốc'),
(2, 4, 'Bệnh gỉ sắt', 'Triệu chứng: Xuất hiện đốm đen trên lá, hoa, quả; ', 'benh_gi_sat.jpg', 'Loại bỏ lá'),
(3, 5, 'Bệnh thán thư', 'Triệu chứng: Lá xuất hiện lớp phấn trắng, co lại v', 'benh_than_thu.jpg', 'Cắt tỉa cà');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `loai_cay_trong`
--

CREATE TABLE `loai_cay_trong` (
  `LCT_MA` int(11) NOT NULL,
  `LCT_TEN` varchar(100) NOT NULL,
  `LCT_MOTA` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `loai_cay_trong`
--

INSERT INTO `loai_cay_trong` (`LCT_MA`, `LCT_TEN`, `LCT_MOTA`) VALUES
(1, 'Cây ăn quả', 'Các loại cây cho quả ăn được'),
(2, 'Hoa kiểng', 'Các loại cây cảnh và hoa'),
(3, 'Rau màu', 'Các loại rau ăn lá và củ');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguon_hang`
--

CREATE TABLE `nguon_hang` (
  `NH_MA` int(11) NOT NULL,
  `NH_TEN` varchar(150) NOT NULL,
  `NH_MOTA` text NOT NULL,
  `NH_TRANGTHAI` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1: Đang hoạt động, 0: Ngừng hoạt động'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nguon_hang`
--

INSERT INTO `nguon_hang` (`NH_MA`, `NH_TEN`, `NH_MOTA`, `NH_TRANGTHAI`) VALUES
(1, 'Công ty Phân bón Việt Nam', 'Công ty chuyên sản xuất và phân phối các loại phân bón, thuốc kích rễ và các sản phẩm nông nghiệp tại Việt Nam', 1),
(2, 'Công ty cổ phần Vi Sinh Ứng Dụng', 'Công ty chuyên về sản xuất các chế phẩm vi sinh phục vụ nông nghiệp và xử lý môi trường', 1),
(3, 'Trang trại dê chuyên nghiệp', 'Các trang trại nuôi dê lớn chuyên sản xuất phân dê chất lượng cao đã qua xử lý', 1),
(4, 'Công ty CP Cây Trồng Bình Chánh', 'Công ty chuyên sản xuất các sản phẩm kích thích sinh trưởng và phân bón cho cây trồng', 1),
(5, 'Nhà phân phối Growmore Hoa Kỳ', 'Đại lý phân phối sản phẩm Growmore có xuất xứ từ Hoa Kỳ tại Việt Nam', 1),
(6, 'Công ty Hạt Giống Việt', 'Công ty chuyên sản xuất hạt giống, phân bón hữu cơ và các sản phẩm bảo vệ thực vật', 1),
(7, 'Syngenta', 'Tập đoàn đa quốc gia chuyên về nông nghiệp, sản xuất thuốc bảo vệ thực vật và hạt giống', 1),
(8, 'Công ty Phân bón Grow More', 'Công ty chuyên sản xuất và phân phối các loại phân bón và thuốc bảo vệ thực vật', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhan_vien`
--

CREATE TABLE `nhan_vien` (
  `NV_MA` int(11) NOT NULL,
  `CV_MA` int(11) NOT NULL,
  `NV_TEN` varchar(100) NOT NULL,
  `NV_DIACHI` text NOT NULL,
  `NV_SDT` varchar(10) NOT NULL,
  `NV_EMAIL` varchar(150) NOT NULL,
  `NV_GIOITINH` char(1) NOT NULL,
  `NV_NGAYSINH` datetime NOT NULL,
  `NV_TENDANGNHAP` varchar(50) NOT NULL,
  `NV_MATKHAU` varchar(255) NOT NULL COMMENT 'Mật khẩu đã mã hóa',
  `NV_NGAYTUYEN` datetime DEFAULT NULL,
  `NV_AVATAR` varchar(200) NOT NULL,
  `NV_TRANGTHAI` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái: 1-Đang làm, 0-Đã nghỉ',
  `NV_RESET_TOKEN` varchar(100) DEFAULT NULL COMMENT 'Token đặt lại mật khẩu',
  `NV_RESET_EXPIRES` datetime DEFAULT NULL COMMENT 'Thời gian hết hạn token',
  `NV_LANHDAO` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Có phải lãnh đạo: 1-Có, 0-Không',
  `NV_NGAYTAO` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Ngày tạo tài khoản',
  `NV_NGAYCAPNHAT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  `NV_QUYEN` enum('ADMIN','NHAN_VIEN') NOT NULL DEFAULT 'NHAN_VIEN'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nhan_vien`
--

INSERT INTO `nhan_vien` (`NV_MA`, `CV_MA`, `NV_TEN`, `NV_DIACHI`, `NV_SDT`, `NV_EMAIL`, `NV_GIOITINH`, `NV_NGAYSINH`, `NV_TENDANGNHAP`, `NV_MATKHAU`, `NV_NGAYTUYEN`, `NV_AVATAR`, `NV_TRANGTHAI`, `NV_RESET_TOKEN`, `NV_RESET_EXPIRES`, `NV_LANHDAO`, `NV_NGAYTAO`, `NV_NGAYCAPNHAT`, `NV_QUYEN`) VALUES
(1, 1, 'Nguyễn Văn A', '123 Đường Lê Lợi, Quận 1, TP.HCM', '0901234567', 'a.nguyen@example.com', 'm', '1990-05-20 00:00:00', 'nguyenvana', '123', '2020-01-01 00:00:00', '', 1, NULL, NULL, 0, '2025-07-10 05:14:10', '2025-07-27 08:57:26', 'ADMIN'),
(2, 2, 'Trần Thị B', '456 Đường Hai Bà Trưng, Quận 3, TP.HCM', '0912345678', 'b.tran@example.com', 'N', '1992-08-15 00:00:00', 'tranthib', '123', '2021-03-15 00:00:00', 'b.png', 1, NULL, NULL, 0, '2025-07-10 05:14:10', '2025-07-27 09:00:23', 'NHAN_VIEN'),
(3, 1, 'Lê Minh C', '789 Đường Nguyễn Huệ, Quận 1, TP.HCM', '0923456789', 'c.le@example.com', 'N', '1988-12-10 00:00:00', 'leminhc', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2019-07-10 00:00:00', 'c.jpg', 1, NULL, NULL, 0, '2025-07-10 05:14:10', '2025-07-10 05:14:10', 'NHAN_VIEN');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhan_vien_login_history`
--

CREATE TABLE `nhan_vien_login_history` (
  `ID` int(11) NOT NULL,
  `NV_MA` int(11) NOT NULL,
  `LOGIN_TIME` datetime NOT NULL,
  `LOGIN_IP` varchar(45) NOT NULL,
  `USER_AGENT` text DEFAULT NULL,
  `LOGIN_STATUS` int(11) DEFAULT 1,
  `FAIL_REASON` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nhan_vien_login_history`
--

INSERT INTO `nhan_vien_login_history` (`ID`, `NV_MA`, `LOGIN_TIME`, `LOGIN_IP`, `USER_AGENT`, `LOGIN_STATUS`, `FAIL_REASON`) VALUES
(1, 1, '2025-07-22 12:47:44', '', NULL, 0, NULL),
(2, 1, '2025-07-23 17:21:01', '', NULL, 0, NULL),
(3, 1, '2025-07-26 13:51:28', '', NULL, 0, NULL),
(4, 1, '2025-07-27 12:56:57', '::1', NULL, 1, NULL),
(5, 2, '2025-07-27 16:00:34', '::1', NULL, 1, NULL),
(6, 1, '2025-07-27 16:00:59', '::1', NULL, 1, NULL),
(7, 2, '2025-07-27 16:01:08', '::1', NULL, 1, NULL),
(8, 1, '2025-07-27 16:01:20', '::1', NULL, 1, NULL),
(9, 1, '2025-07-27 16:03:44', '::1', NULL, 1, NULL),
(10, 2, '2025-07-27 16:03:55', '::1', NULL, 1, NULL),
(11, 1, '2025-07-27 16:09:17', '::1', NULL, 1, NULL),
(12, 1, '2025-07-27 16:13:06', '::1', NULL, 1, NULL),
(13, 2, '2025-07-27 16:13:16', '::1', NULL, 1, NULL),
(14, 2, '2025-07-27 16:19:26', '::1', NULL, 1, NULL),
(15, 1, '2025-07-27 16:19:44', '::1', NULL, 1, NULL),
(16, 2, '2025-07-27 16:19:53', '::1', NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nha_van_chuyen`
--

CREATE TABLE `nha_van_chuyen` (
  `NVC_MA` int(11) NOT NULL,
  `NVC_TEN` varchar(100) NOT NULL,
  `NVC_MOTA` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nha_van_chuyen`
--

INSERT INTO `nha_van_chuyen` (`NVC_MA`, `NVC_TEN`, `NVC_MOTA`) VALUES
(1, 'Giao Hàng Nhanh', 'Dịch vụ giao hàng nhanh trong ngày, toàn quốc'),
(2, 'Viettel Post', 'Dịch vụ giao hàng của Viettel, uy tín, phủ sóng toàn quốc'),
(3, 'J&T Express', 'Giao hàng tiết kiệm, dịch vụ tốt, thời gian linh hoạt'),
(4, 'GrabExpress', 'Giao hàng siêu tốc nội thành bằng xe máy'),
(5, 'Ninja Van', 'Giao hàng thương mại điện tử, hỗ trợ thu hộ (COD)'),
(6, '', '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phieu_kiem`
--

CREATE TABLE `phieu_kiem` (
  `PK_ID` int(11) NOT NULL,
  `PK_NGAYLAP` datetime NOT NULL DEFAULT current_timestamp(),
  `NV_MA` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `phieu_kiem`
--

INSERT INTO `phieu_kiem` (`PK_ID`, `PK_NGAYLAP`, `NV_MA`) VALUES
(1, '2025-07-21 15:51:58', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phieu_nhap`
--

CREATE TABLE `phieu_nhap` (
  `PN_STT` int(11) NOT NULL,
  `PN_MA` varchar(20) NOT NULL,
  `NV_MA` int(11) NOT NULL,
  `PN_NGAYNHAP` datetime NOT NULL,
  `PN_GHICHU` text DEFAULT NULL,
  `PN_TRANGTHAI` enum('Chờ duyệt','Đã duyệt','Đã hủy') NOT NULL DEFAULT 'Chờ duyệt',
  `PN_NGAYTAO` timestamp NOT NULL DEFAULT current_timestamp(),
  `PN_NGAYCAPNHAT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `phieu_nhap`
--

INSERT INTO `phieu_nhap` (`PN_STT`, `PN_MA`, `NV_MA`, `PN_NGAYNHAP`, `PN_GHICHU`, `PN_TRANGTHAI`, `PN_NGAYTAO`, `PN_NGAYCAPNHAT`) VALUES
(0, 'PN202507180000', 1, '2025-07-18 15:37:39', NULL, 'Chờ duyệt', '2025-07-26 09:55:36', '2025-07-26 09:55:37'),
(1, 'PN202507180001', 1, '2025-07-18 16:13:15', NULL, 'Chờ duyệt', '2025-07-26 09:55:36', '2025-07-26 09:55:37'),
(2, 'PN202507180002', 1, '2025-07-18 16:34:03', NULL, 'Chờ duyệt', '2025-07-26 09:55:36', '2025-07-26 09:55:37'),
(3, 'PN202507180003', 1, '2025-07-18 16:35:36', NULL, 'Chờ duyệt', '2025-07-26 09:55:36', '2025-07-26 09:55:37'),
(4, 'PN202507180004', 1, '2025-07-18 16:48:58', NULL, 'Chờ duyệt', '2025-07-26 09:55:36', '2025-07-26 09:55:37'),
(5, 'PN202507180005', 1, '2025-07-18 16:53:01', NULL, 'Chờ duyệt', '2025-07-26 09:55:36', '2025-07-26 09:55:37'),
(6, 'PN20250727093931', 1, '2025-07-27 00:00:00', 'thêm', 'Chờ duyệt', '2025-07-27 07:39:51', '2025-07-27 07:39:51'),
(7, 'PN20250727094023', 1, '2025-07-27 00:00:00', 'thêm', 'Chờ duyệt', '2025-07-27 07:40:37', '2025-07-27 07:40:37'),
(8, 'PN20250727095049', 1, '2025-07-27 00:00:00', 'thêm hàng ', 'Chờ duyệt', '2025-07-27 07:51:17', '2025-07-27 07:51:17'),
(9, 'PN20250727095540', 1, '2025-07-27 00:00:00', 'thêm', 'Chờ duyệt', '2025-07-27 07:55:57', '2025-07-27 07:55:57'),
(10, 'PN20250727095540', 1, '2025-07-27 00:00:00', 'thêm', 'Chờ duyệt', '2025-07-27 07:59:34', '2025-07-27 07:59:34'),
(11, 'PN20250727095938', 1, '2025-07-27 00:00:00', 'thêm', 'Chờ duyệt', '2025-07-27 07:59:57', '2025-07-27 07:59:57');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phuong_thuc_thanh_toan`
--

CREATE TABLE `phuong_thuc_thanh_toan` (
  `PTTT_MA` int(11) NOT NULL,
  `PTTT_TEN` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `phuong_thuc_thanh_toan`
--

INSERT INTO `phuong_thuc_thanh_toan` (`PTTT_MA`, `PTTT_TEN`) VALUES
(1, 'Tiền mặt'),
(2, 'Chuyển khoản ngân hàng'),
(3, 'Trả bằng tiền mặt '),
(4, 'Thanh toán qua Momo');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('phân bón','thuốc trừ sâu','thuốc trừ bệnh') NOT NULL,
  `description` text DEFAULT NULL,
  `usage_guide` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `type`, `description`, `usage_guide`, `price`, `created_at`, `updated_at`) VALUES
(1, 'NPK 20-20-15', 'phân bón', 'Phân bón NPK cân đối cho cây trồng', 'Bón lót trước khi gieo trồng', 250000.00, '2025-07-03 10:11:32', '2025-07-03 10:11:32'),
(2, 'Kali 60%', 'phân bón', 'Phân bón kali cho cây trồng', 'Bón thúc trong giai đoạn ra hoa', 180000.00, '2025-07-03 10:11:32', '2025-07-03 10:11:32'),
(3, 'Đạm Urê', 'phân bón', 'Phân đạm cho cây trồng', 'Bón thúc trong giai đoạn sinh trưởng', 150000.00, '2025-07-03 10:11:32', '2025-07-03 10:11:32'),
(4, 'Chelate Fe', 'phân bón', 'Phân bón bổ sung sắt', 'Phun khi cây thiếu sắt', 200000.00, '2025-07-03 10:11:32', '2025-07-03 10:11:32'),
(5, 'Anvil 5SC', 'thuốc trừ bệnh', 'Thuốc trừ bệnh nấm', 'Phun khi phát hiện bệnh', 180000.00, '2025-07-03 10:11:32', '2025-07-03 10:11:32'),
(6, 'NPK 16-16-8', 'phân bón', 'Phân bón NPK cho rau màu', 'Bón lót và bón thúc cho cây trồng', 220000.00, '2025-07-03 10:11:32', '2025-07-03 10:11:32'),
(7, 'NPK 13-13-13', 'phân bón', 'Phân bón NPK cân đối cho rau', 'Bón lót và bón thúc cho cây trồng', 210000.00, '2025-07-03 10:11:32', '2025-07-03 10:11:32'),
(8, 'Phân bón lá cao cấp', 'phân bón', 'Phân bón lá đa năng cho rau màu', 'Phun định kỳ 7-10 ngày/lần', 150000.00, '2025-07-03 10:11:32', '2025-07-03 10:11:32'),
(9, 'Canxi Bo', 'phân bón', 'Phân bón bổ sung Canxi và Bo', 'Phun khi cây ra hoa và đậu quả', 180000.00, '2025-07-03 10:11:32', '2025-07-03 10:11:32'),
(10, 'Phân hữu cơ vi sinh', 'phân bón', 'Phân bón hữu cơ sinh học', 'Bón lót trước khi trồng', 200000.00, '2025-07-03 10:11:32', '2025-07-03 10:11:32'),
(11, 'NPK 15-5-20', 'phân bón', 'Phân bón NPK cho cây ăn quả', 'Bón thúc giai đoạn ra hoa, đậu quả', 230000.00, '2025-07-03 10:11:32', '2025-07-03 10:11:32'),
(12, 'Phân bón lá Đầu Trâu', 'phân bón', 'Phân bón lá cao cấp cho cây ăn quả', 'Phun định kỳ 15-20 ngày/lần', 180000.00, '2025-07-03 10:11:32', '2025-07-03 10:11:32'),
(13, 'Phân hữu cơ vi sinh', 'phân bón', 'Phân bón hữu cơ vi sinh giàu dinh dưỡng', 'Bón lót khi trồng mới hoặc đầu vụ', NULL, '2025-07-03 11:12:43', '2025-07-03 11:12:43'),
(14, 'NPK 16-16-16', 'phân bón', 'Phân bón NPK cân đối cho sinh trưởng', 'Bón thúc định kỳ 3-4 tháng/lần', NULL, '2025-07-03 11:12:43', '2025-07-03 11:12:43'),
(15, 'Phân bón lá đa vi lượng', 'phân bón', 'Phân bón lá bổ sung vi lượng', 'Phun định kỳ 15-20 ngày/lần', NULL, '2025-07-03 11:12:43', '2025-07-03 11:12:43'),
(16, 'NPK 13-13-21', 'phân bón', 'Phân bón giàu Kali cho giai đoạn ra hoa', 'Bón trước khi ra hoa 15-20 ngày', NULL, '2025-07-03 11:12:43', '2025-07-03 11:12:43'),
(17, 'Phân bón lá có Bo', 'phân bón', 'Phân bón lá bổ sung Bo kích thích ra hoa', 'Phun khi cây bắt đầu ra hoa', NULL, '2025-07-03 11:12:43', '2025-07-03 11:12:43');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_crops`
--

CREATE TABLE `product_crops` (
  `product_id` int(11) NOT NULL,
  `crop_id` int(11) NOT NULL,
  `growth_stage_id` int(11) NOT NULL,
  `dosage` text DEFAULT NULL,
  `application_method` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `product_crops`
--

INSERT INTO `product_crops` (`product_id`, `crop_id`, `growth_stage_id`, `dosage`, `application_method`) VALUES
(1, 2, 1, '2-3kg/cây/năm', 'Bón lót trước khi trồng'),
(9, 2, 3, '50-100g/cây/lần', 'Phun định kỳ 7-10 ngày/lần khi cây ra hoa'),
(10, 2, 2, '20-30kg/cây/năm', 'Bón lót quanh gốc, cách gốc 30-50cm'),
(10, 2, 4, '2-3kg/cây/năm', 'Bón 2 lần/năm vào đầu và giữa mùa mưa'),
(10, 2, 5, '20-30kg/cây/năm', 'Bón lót quanh gốc, cách gốc 30-50cm'),
(10, 2, 6, '20-30kg/cây/năm', 'Bón lót quanh gốc, cách gốc 30-50cm'),
(10, 5, 2, '20-30kg/cây/năm', 'Bón lót quanh gốc, cách gốc 30-50cm'),
(10, 5, 5, '20-30kg/cây/năm', 'Bón lót quanh gốc, cách gốc 30-50cm'),
(10, 5, 6, '20-30kg/cây/năm', 'Bón lót quanh gốc, cách gốc 30-50cm'),
(11, 2, 2, '1-2kg/cây/lần', 'Bón thúc định kỳ 3-4 tháng/lần'),
(12, 2, 4, '20-30ml/bình 16L', 'Phun định kỳ 15-20 ngày/lần'),
(13, 2, 2, '20-30kg/cây/năm', 'Bón lót quanh gốc, cách gốc 30-50cm'),
(13, 2, 5, '20-30kg/cây/năm', 'Bón lót quanh gốc, cách gốc 30-50cm'),
(13, 2, 6, '20-30kg/cây/năm', 'Bón lót quanh gốc, cách gốc 30-50cm'),
(13, 5, 2, '20-30kg/cây/năm', 'Bón lót quanh gốc, cách gốc 30-50cm'),
(13, 5, 5, '20-30kg/cây/năm', 'Bón lót quanh gốc, cách gốc 30-50cm'),
(13, 5, 6, '20-30kg/cây/năm', 'Bón lót quanh gốc, cách gốc 30-50cm'),
(14, 2, 2, '1-1.5kg/cây/lần', 'Bón thúc theo rãnh quanh tán cây'),
(14, 2, 6, '1-1.5kg/cây/lần', 'Bón thúc theo rãnh quanh tán cây'),
(14, 2, 8, '1-1.5kg/cây/lần', 'Bón thúc theo rãnh quanh tán cây'),
(14, 5, 2, '1-1.5kg/cây/lần', 'Bón thúc theo rãnh quanh tán cây'),
(14, 5, 6, '1-1.5kg/cây/lần', 'Bón thúc theo rãnh quanh tán cây'),
(14, 5, 8, '1-1.5kg/cây/lần', 'Bón thúc theo rãnh quanh tán cây'),
(15, 2, 2, 'Pha 25-30ml/bình 16L', 'Phun đều lên lá vào sáng sớm hoặc chiều mát'),
(15, 2, 3, 'Pha 25-30ml/bình 16L', 'Phun đều lên lá vào sáng sớm hoặc chiều mát'),
(15, 2, 6, 'Pha 25-30ml/bình 16L', 'Phun đều lên lá vào sáng sớm hoặc chiều mát'),
(15, 2, 7, 'Pha 25-30ml/bình 16L', 'Phun đều lên lá vào sáng sớm hoặc chiều mát'),
(15, 2, 8, 'Pha 25-30ml/bình 16L', 'Phun đều lên lá vào sáng sớm hoặc chiều mát'),
(15, 5, 2, 'Pha 25-30ml/bình 16L', 'Phun đều lên lá vào sáng sớm hoặc chiều mát'),
(15, 5, 3, 'Pha 25-30ml/bình 16L', 'Phun đều lên lá vào sáng sớm hoặc chiều mát'),
(15, 5, 6, 'Pha 25-30ml/bình 16L', 'Phun đều lên lá vào sáng sớm hoặc chiều mát'),
(15, 5, 7, 'Pha 25-30ml/bình 16L', 'Phun đều lên lá vào sáng sớm hoặc chiều mát'),
(15, 5, 8, 'Pha 25-30ml/bình 16L', 'Phun đều lên lá vào sáng sớm hoặc chiều mát'),
(16, 2, 3, '1kg/cây/lần', 'Bón theo rãnh quanh tán, sau đó tưới đẫm nước'),
(16, 2, 7, '1kg/cây/lần', 'Bón theo rãnh quanh tán, sau đó tưới đẫm nước'),
(16, 2, 8, '1kg/cây/lần', 'Bón theo rãnh quanh tán, sau đó tưới đẫm nước'),
(16, 5, 3, '1kg/cây/lần', 'Bón theo rãnh quanh tán, sau đó tưới đẫm nước'),
(16, 5, 7, '1kg/cây/lần', 'Bón theo rãnh quanh tán, sau đó tưới đẫm nước'),
(16, 5, 8, '1kg/cây/lần', 'Bón theo rãnh quanh tán, sau đó tưới đẫm nước'),
(17, 2, 3, 'Pha 20-25ml/bình 16L', 'Phun đều lên lá và hoa vào sáng sớm hoặc chiều mát'),
(17, 2, 7, 'Pha 20-25ml/bình 16L', 'Phun đều lên lá và hoa vào sáng sớm hoặc chiều mát'),
(17, 5, 3, 'Pha 20-25ml/bình 16L', 'Phun đều lên lá và hoa vào sáng sớm hoặc chiều mát'),
(17, 5, 7, 'Pha 20-25ml/bình 16L', 'Phun đều lên lá và hoa vào sáng sớm hoặc chiều mát');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `provinces`
--

CREATE TABLE `provinces` (
  `id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `provinces`
--

INSERT INTO `provinces` (`id`, `name`, `type`) VALUES
('01', 'Hà Nội', 'Thành phố'),
('02', 'Hà Giang', 'Tỉnh'),
('03', 'Cao Bằng', 'Tỉnh'),
('04', 'Bắc Kạn', 'Tỉnh'),
('05', 'Tuyên Quang', 'Tỉnh'),
('06', 'Lào Cai', 'Tỉnh'),
('07', 'Điện Biên', 'Tỉnh'),
('08', 'Lai Châu', 'Tỉnh'),
('09', 'Sơn La', 'Tỉnh'),
('10', 'Yên Bái', 'Tỉnh'),
('11', 'Hòa Bình', 'Tỉnh'),
('12', 'Thái Nguyên', 'Tỉnh'),
('13', 'Lạng Sơn', 'Tỉnh'),
('14', 'Quảng Ninh', 'Tỉnh'),
('15', 'Bắc Giang', 'Tỉnh'),
('16', 'Phú Thọ', 'Tỉnh'),
('17', 'Vĩnh Phúc', 'Tỉnh'),
('18', 'Bắc Ninh', 'Tỉnh'),
('19', 'Hải Dương', 'Tỉnh'),
('20', 'Hải Phòng', 'Thành phố'),
('21', 'Hưng Yên', 'Tỉnh'),
('22', 'Thái Bình', 'Tỉnh'),
('23', 'Hà Nam', 'Tỉnh'),
('24', 'Nam Định', 'Tỉnh'),
('25', 'Ninh Bình', 'Tỉnh'),
('26', 'Thanh Hóa', 'Tỉnh'),
('27', 'Nghệ An', 'Tỉnh'),
('28', 'Hà Tĩnh', 'Tỉnh'),
('29', 'Quảng Bình', 'Tỉnh'),
('30', 'Quảng Trị', 'Tỉnh'),
('31', 'Thừa Thiên Huế', 'Tỉnh'),
('32', 'Đà Nẵng', 'Thành phố'),
('33', 'Quảng Nam', 'Tỉnh'),
('34', 'Quảng Ngãi', 'Tỉnh'),
('35', 'Bình Định', 'Tỉnh'),
('36', 'Phú Yên', 'Tỉnh'),
('37', 'Khánh Hòa', 'Tỉnh'),
('38', 'Ninh Thuận', 'Tỉnh'),
('39', 'Bình Thuận', 'Tỉnh'),
('40', 'Kon Tum', 'Tỉnh'),
('41', 'Gia Lai', 'Tỉnh'),
('42', 'Đắk Lắk', 'Tỉnh'),
('43', 'Đắk Nông', 'Tỉnh'),
('44', 'Lâm Đồng', 'Tỉnh'),
('45', 'Bình Phước', 'Tỉnh'),
('46', 'Tây Ninh', 'Tỉnh'),
('47', 'Bình Dương', 'Tỉnh'),
('48', 'Đồng Nai', 'Tỉnh'),
('49', 'Bà Rịa - Vũng Tàu', 'Tỉnh'),
('50', 'Hồ Chí Minh', 'Thành phố'),
('51', 'Long An', 'Tỉnh'),
('52', 'Tiền Giang', 'Tỉnh'),
('53', 'Bến Tre', 'Tỉnh'),
('54', 'Trà Vinh', 'Tỉnh'),
('55', 'Vĩnh Long', 'Tỉnh'),
('56', 'Đồng Tháp', 'Tỉnh'),
('57', 'An Giang', 'Tỉnh'),
('58', 'Kiên Giang', 'Tỉnh'),
('59', 'Cần Thơ', 'Thành phố'),
('60', 'Hậu Giang', 'Tỉnh'),
('61', 'Sóc Trăng', 'Tỉnh'),
('62', 'Bạc Liêu', 'Tỉnh'),
('63', 'Cà Mau', 'Tỉnh');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `refund_requests`
--

CREATE TABLE `refund_requests` (
  `REFUND_ID` int(11) NOT NULL,
  `HD_STT` int(11) NOT NULL,
  `REFUND_AMOUNT` decimal(10,2) NOT NULL,
  `REFUND_REASON` text NOT NULL,
  `REFUND_STATUS` enum('PENDING','PROCESSING','COMPLETED','FAILED') NOT NULL DEFAULT 'PENDING',
  `REFUND_DATE` timestamp NOT NULL DEFAULT current_timestamp(),
  `COMPLETED_DATE` timestamp NULL DEFAULT NULL,
  `REFUND_NOTE` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `san_pham`
--

CREATE TABLE `san_pham` (
  `SP_MA` int(11) NOT NULL,
  `NH_MA` int(11) NOT NULL,
  `DM_MA` int(11) NOT NULL,
  `SP_TEN` varchar(128) NOT NULL,
  `SP_DONGIA` float DEFAULT NULL,
  `SP_SOLUONGTON` float NOT NULL,
  `SP_HINHANH` varchar(200) DEFAULT NULL,
  `SP_MOTA` text DEFAULT NULL,
  `SP_TRANGTHAI` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1: Đang kinh doanh, 0: Ngừng kinh doanh',
  `SP_THANHPHAN` text DEFAULT NULL,
  `SP_HUONGDANSUDUNG` text DEFAULT NULL,
  `SP_DONVITINH` varchar(20) NOT NULL,
  `SP_TRONGLUONG` float DEFAULT NULL COMMENT 'Trọng lượng sản phẩm (kg)',
  `SP_NHASANXUAT` varchar(50) NOT NULL,
  `SP_AVAILABLE` decimal(10,2) DEFAULT 0.00,
  `SP_CAPNHAT` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `san_pham`
--

INSERT INTO `san_pham` (`SP_MA`, `NH_MA`, `DM_MA`, `SP_TEN`, `SP_DONGIA`, `SP_SOLUONGTON`, `SP_HINHANH`, `SP_MOTA`, `SP_TRANGTHAI`, `SP_THANHPHAN`, `SP_HUONGDANSUDUNG`, `SP_DONVITINH`, `SP_TRONGLUONG`, `SP_NHASANXUAT`, `SP_AVAILABLE`, `SP_CAPNHAT`) VALUES
(1, 1, 5, 'N3M kích rễ – Kích rễ cực mạnh cho cây ăn trái, kiểng, công nghiệp', 26000, 993, 'kich_re.jpg', 'Phân bón lá ra rễ cực mạnh N3M là sản phẩm được rất nhiều người ưa chuộng sử dụng vì giá thành rẻ, công dụng kích thích ra rễ được dùng trên nhiều loại cây trồng từ rau, cây ăn quả cho đến các loại hoa hồng – hoa kiểng.\r\n\r\n', 1, 'N 11%, P2O53%, K2O5 2,5%, B, Cu, Zn…', 'Giâm, chiết cành (20gr/L nước): nhúng cành muốn giâm vào dung dịch thuốc 5-10p, sau đó giâm vào đất; bôi trực tiếp vào vết khoanh vỏ phía trên ngọn cành khi bỏ bầu.\r\n\r\n• Tưới gốc (20gr/10L nước): tưới đều quanh gốc cây để tăng cường và phục hồi bộ rễ bị suy yếu do xử lý thuốc hoặc sau khi ngập úng hay hạn, sau đó 7 ngày phun 1 lần\r\n\r\n• Phun trên lá (10gr/10L nước): khi ra đọt, khi cây ra hoa và trái non, làm cây đâm tược mới, chống rụng hoa, tăng đậu trái, sau đó cách 7 ngày phun 1 lần.\r\n\r\n• Ngâm hạt giống (10gr/10L nước): ngâm hạt giống trong 24h, sau đó vớt ra ủ bình thường.', 'hộp', 1, 'Công ty Phân bón Việt Nam', 993.00, '2025-07-28 15:56:19'),
(2, 1, 1, 'Chế phẩm EMUNIV (200g) – Ủ phân và rác hữu cơ hiệu quả, nhanh hoai mục', 35000, 710, 'emu.jpg', 'Chế phẩm ủ phân và rác thải Emuniv là chế phẩm vi sinh EM xử lý phân gia súc gia cầm, rác thải, phế thải nông nghiệp làm phân bón hữu cơ và xử lý ô nhiễm môi trường.', 1, '• Bacillus subtillis: 10^8CFU/g.\r\n\r\n• Bacillus licheniformis: 10^7CFU/g.\r\n\r\n• Bacillus  megaterium: 10^7CFU/g.\r\n\r\n• Lactobacillus acidopphilus: 10^8CFU/g.\r\n\r\n• Lactobacillus plantarum: 10^8CFU/g.\r\n\r\n• Streptomyces sp: 10^7CFU/g.\r\n\r\n• Saccharomyces cereviseae: 10^7CFU/g.', '• Hòa 1 gói vào nước sạch, tưới cho 1 tấn nguyên liệu, đạt độ ẩm 45-50% ủ đống trong 20-30 ngày.\r\n\r\n• Xử lí nước thải: dùng từ 2-4gram chế phẩm/m3/ ngày đêm, đổ vào bể hiếm khí sục 8-10h/ngày đêm...\r\n\r\nBảo quản: để nơi khô mát trong vòng 12 tháng kể từ ngày sản xuất.', 'bao', 1, 'Công ty cổ phần Vi Sinh Ứng Dụng', 710.00, '2025-07-30 08:39:27'),
(3, 1, 1, 'Phân dê qua xử lý hàng chuẩn ( chuyên cho lan và hoa hồng ) - 1 túi', 22000, 803, 'phande.jpg', 'Phân dê là loại sản phẩm phân chuồng được thu gom từ các trang trại nuôi dê lớn. Phân dê sẽ được xử lý mầm bệnh bằng vi sinh, giảm ẩm và tiến hành đóng gói dạng thương mại để xuất đi. Phân dê có hàm lượng N-P-K khá cân đối (3%-1%-2%), cùng với đó là các khoáng trung vi lượng có hàm lượng cao phải kể đến là Canxi, Cu, Fe,... Phân dê được giới trồng hoa hồng và các loại hoa kiểng ưa chuộng vì tính tiện lợi, chất lượng các thành phần dinh dưỡng và không có mùi hôi - rất dễ sử dụng.\r\n\r\n• Trong phân dê có thành phần cải tạo kết cấu đất, giúp duy trì và làm phong phú cho khu vườn. Phân dê có khả năng cải thiện kết cấu đất để sử dụng nước hiệu quả hơn. Đặc biệt, cho phép nhiều oxy lưu thông đến bộ rễ, kết hợp với N trong phân giúp cho quá trình cố định đạm được diễn ra. Do đó, có thể làm tăng cường năng suất cây trồng lên đến 20%. ', 1, 'phân dê', '• Bón cách gốc cây kiểng 2-4cm.\r\n\r\n• Tưới đều nước khi bón phân.\r\n\r\n• Bón ít nhất 1 tháng/lần với cây đang trưởng thành và cây ra hoa, cây già cỗi.', 'gói', 1, 'Phân dê được sản xuất tại các trang trại dê lớn ở ', 793.00, '2025-07-28 17:16:35'),
(4, 2, 5, 'Bimix Super Root (20ml)  –  Phát triển mạnh bộ rễ, kích thích cành chiết, cành giâm', 6000, 575, 'bemix.jpg', 'Sản phẩm thuốc kích rễ bimix super root (20ml) được sản xuất với công nghệ tiên tiến từ các nguyên liệu chất lượng. Sản phẩm được dùng để kích thích ra rễ cây trong phương pháp giâm cành và chiết cành, phục hồi rễ sau thời kì ngập úng.', 1, '• Acid humic đậm đặc: 9%\r\n\r\n• Acid Amin: 0.12%\r\n\r\n• Nito: 6%\r\n\r\n• Photpho: 8%\r\n\r\n• Kali: 6%\r\n\r\n• Chelate Cu, Fe, Zn, B:> 1000 ppm\r\n\r\n• Vitamin và một số chất điều hòa sinh trưởng thực vật khác\r\n\r\n', 'Dùng cho cây ăn trái, cây công nghiệp, vườn ươm, cây cảnh\r\n\r\n• Lúa: Pha 10 - 20ml/ 8 lít nước (1 lít/ 600 lít nước/ ha/ lần)\r\n\r\n• Cây ăn trái, cây công nghiệp:\r\n\r\nCây con: 10 -20ml/ 16 lít nước (0.4 lít/ 600 lít nước/ ha/ lần)\r\n\r\nCây trưởng thành: 20 - 25ml/ 16 lít nước (1 lít/ 600 lít nước/ ha/ lần)\r\n\r\n• Rau màu, hoa kiểng:  10 -15ml/ 16 lít nước (0.4 lít/ 600 lít nước/ ha/ lần)\r\n\r\n• Giâm chiết cành: Pha dung dịch 20ml/ 5 lít nước: ngâm cành giâm, cành ghép, hạt giống từ 2-3 giờ (hoặc bôi chỗ ghép, gốc chiết, gốc cành giâm.', 'chai', 0.02, 'Công ty CP Cây Trồng Bình Chánh', 575.00, '2025-07-28 17:15:10'),
(5, 3, 5, 'Axit humic dạng lỏng 322 Growmore (235ml) - Kích rễ và chống ngộ độc hữu cơ cho cây', 45000, 388, 'humic.jpg', 'Axit humic dạng lỏng 322 là dòng sản phẩm hữu cơ, được sử dụng cho cây trồng để cung cấp chất hữu cơ, khoáng,.. Axit humic có chức năng chính là kích thích bộ rễ phát triển, giúp rễ khỏe mạnh, kháng phèn và chống ngộ độc hữu cơ. Bên cạnh đó, Axit humic tăng khả năng đậu quả, chống rụng bông, bông to trái lớn và tăng năng suất cây trồng.', 1, '• Axit humic: 6.3%\r\n\r\n• Axit fulvic: 1.2%\r\n\r\n• Nts: 3%\r\n\r\n• P2O5hh: 2%\r\n\r\n• K2Ohh: 2%\r\n\r\n• Fe: 1000 ppm\r\n\r\n• Zn: 500ppm\r\n\r\n• Cu: 500ppm\r\n\r\n• Mn: 500ppm\r\n\r\n• pHH2O: 5\r\n\r\n• Tỷ trọng: 1.1', '• Rau các loại: cà chua, dưa hấu, dưa leo, bầu, bí, khổ qua, ớt, bắp cải, khoai tây, dâu, xà lách, su hào, dền, dứa, gừng, bó xôi, cà rốt, khoai, lang, các loại đậu.\r\n\r\n• Cây ăn trái: thanh long, nhãn, chôm chôm, sầu riêng, mãng cầu, nho, táo, đu đủ, cam, quýt, bưởi, xoài, ổi, măng cụt, sơ ri, vải, hồng, đào, mận, na, saboche, dâu, khóm...\r\n\r\n• Cây công nghiệp: trà, cà phê, thuốc lá, cao su, bông vải, mía, bắp, tiêu, dâu tằm, điều.\r\n\r\n• Các loại bonsai, bông hoa cây cảnh: phong lan, hoa hồng, hoa lài, cúc, vạn thọ, huệ, mai, tulip, cẩm chướng...\r\n\r\n• Pha 20cc - 30cc / 10 lít nước hoặc can 1 lít / 2 phuy (phuy 200 lít). Phun định kỳ 10 - 15 ngày/ lần.\r\n\r\n• Đối với cây lúa phun định kỳ 7 ngày/ lần, vào 3 thời kỳ cơ bản lúc lúa đẻ nhánh, trước khi trổ và thời kỳ nuôi bông, nuôi hạt.', 'lọ', 0.01, 'Sản phẩm có xuất xứ từ Hoa Kỳ, đucợ phân phối bởi ', 388.00, '2025-07-30 08:39:27'),
(6, 1, 1, 'Phân bón kiểng lá viên nén hữu cơ 100% SFARM - Túi 500 gram', 19000, 2046, 'sfram.jpg', 'Phân bón hữu cơ chuyên cho cây trong nhà SFARM là dòng phân bón dạng viên tan chậm được cải tiến chuyên biệt cho cây trong nhà. Sản phẩm là sự cải tiến và kết hợp hoàn hảo giữa phân trùn quế và thành phần hữu cơ khác. Viên nén có màu nâu đen, nhẵn bóng cho thời gian sử dụng kéo dài 30 – 45 ngày.', 1, ' Phân trùn quế và thành phần hữu cơ khác', 'Bón định kỳ khoảng 1 lần/tháng với lượng 50g cho chậu đường kính 30cm.\r\nRải trực tiếp phân lên bề mặt chậu, xung quanh gốc cây theo đường kính tán. Sau đó, đảo nhẹ lớp đất mặt và tưới nước cho cây', 'gói', 1, 'Công ty Hạt Giống Việt', 2046.00, '2025-07-28 17:08:11'),
(7, 1, 3, 'ATONIK (10ml) – Kích rễ, bật mầm mạnh cho cây trồng và hoa kiểng', 10500, 1696, 'atonik.jpg', 'Thuốc kích thích sinh trưởng cây trồng và hoa kiểng ATONIK là thuốc kích thích sinh trưởng cây trồng thế hệ mới. Cũng như các loại vitamin, Atonik làm tăng khả năng sinh trưởng đồng thời giúp cây trồng tránh khỏi những ảnh hưởng xấu do những điều kiện sinh trưởng không thuận lợi gây ra.', 1, 'Sodium -  Nitrogualacolate 0,03%\r\n\r\nSodium - Nitrophenolate 0,06%\r\n\r\nSodium - P - Nitrophenolate 0,09%', '+ Ngâm hạt: Kích thích sự nảy mầm và ra rễ, phá vỡ trạng thái ngủ của hạt giống\r\n\r\n+ Phun tưới trên ruộng mạ, cây con: Làm cho cây mạ phát triển, phục hồi nhanh chóng sau cấy trồng\r\n\r\n+ Phun qua lá: Kích thích sự sinh trưởng phát triển, tạo điều kiện cho quá trình trao đổi chất của cây, giúp cây sớm thu hoạch với năng suất cao, chất lượng tốt.', 'gói', 0.01, 'Công ty Hạt Giống Việt', 1504.00, '2025-07-27 07:51:17'),
(8, 4, 4, 'COC85 (20g) - Ngừa bệnh rỉ sắt, đốm đen cho cây trồng, đặc hiệu cho hoa kiểng', 220000, 12951, 'coc85.jpg', 'Thuốc trừ bệnh COC85 được sản xuất từ ion gốc Đồng (Cu2+), dạng bột mịn, loang đều và bám dính tốt. Sản phẩm được dùng để phòng trừ nấm bệnh, rỉ sắt, đốm đen trên các loại cây trồng, cây kiểng. Đặc biệt là hoa hồng, cây mai, đào.\r\n\r\n', 1, '• Đồng Oxycloride: 85w/w.\r\n\r\n• Phụ gia: 15 w/w.', '• Pha loãng khoảng 10 -20 gram cho bình 8 - 10 lít, phun khi cây mới chớm bệnh. Mỗi 14 ngày nên phun để phòng trừ bệnh. \r\n\r\n• Thời gian cách ly: 7 ngày.', 'bao', 1, 'Syngenta', 12945.00, '2025-07-28 16:54:47'),
(9, 5, 4, 'Thuốc trừ bệnh BELLKUTE 40WP đặc trị bệnh phấn trắng trên hoa hồng - Gói 20 gram', 35000, 700, 'bellkute.jpg', 'Mô tả sản phẩm: Thuốc trừ bệnh Bellkute 40WP là thuốc trừ bệnh phổ rộng, chuyên trị bệnh phấn trắng trên hoa hồng, sương mai trên cây bầu bí, thán thư trên ớt,...Phòng trừ bệnh do nấm như: đốm vàng, đốm nâu, đốm đen, gỉ sắt, thối nhũn, héo củ, vàng lá,...trên cây hoa mai, hoa lan và cây cảnh. ', 1, ' Iminoctadine: .............40% w/w.', '• Cây trồng: Hoa Hồng, bệnh hại (phấn trắng). \r\n\r\n• Liều lượng: 0,5kg/ha (10-13 gr/ bình 16 lít, 16-21 gr/bình 25 lít). \r\n\r\n• Phun ướt đều tán lá cây trồng và phun thuốc khi bệnh chớm xuất hiện. \r\n\r\n• Phun lặp lại 7-10 ngày nếu áp lực bệnh cao. \r\n\r\n• Lượng nước phun: 600-800 lít/ha. \r\n\r\n• Thời gian cách ly: Ngưng phun thuốc 7 ngày trước khi thu hoạch. ', 'gói', 0.05, 'Công ty Phân bón Grow More', 700.00, '2025-07-19 07:48:36'),
(10, 1, 6, 'Hoạt chất sinh học Neem Chito - Phòng trừ nhện đỏ và bọ trĩ trên cây hoa hồng', 43000, 2497, 'chito.jpg', 'Hoạt chất sinh học Neem Chito phòng trừ hiệu quả, không gây kháng thuốc đối với nhện đỏ và bọ trĩ chích hút trên cây hoa hồng một cách an toàn, thân thiện nhất. Ngoài ra, Neem Chito còn phòng ngừa được rầy, rệp và sâu cuốn lá, đồng thời tăng sức đề kháng của cây hồng chống lại các tác nhân gây bệnh, kích thích cây tăng trưởng, đâm chồi, nở hoa, hoa to và bền màu.', 1, '• Potassium Linear AlkylBenzene Sulfonate: 9%\r\n\r\n• Chitosan được chiết xuất từ vỏ tôm, vỏ cua.\r\n\r\n• Tinh dầu Neem chưa hoạt chất Azadirachtin từ cây neem Ấn Độ.\r\n\r\n• Chất bám dính sinh học hữu cơ.\r\n\r\n', '• Pha 10ml - 15ml (1/2 - 1 nắp)/ bình 20 lít nước (100ml - 150ml/ phuy 200 lít nước), sử dụng cho các loại cây trồng.\r\n\r\n• Phun đều lên tán cây, cả mặt trên và mặt dưới lá.\r\n\r\n• Hòa chung phân bón lá và nông dược, phun đều lên tán cây.\r\n\r\n• Phu định kỳ 10-15 ngày/ lần hoặc phun theo kỳ phun thuốc.', 'chai', 1, 'Công ty Hạt Giống Việt', 2497.00, '2025-07-19 07:48:36'),
(11, 4, 6, 'Thuốc trừ ốc dạng phun HELIX 500WP - Chai 50 gram', 23000, 345, 'helix.jpg', 'Thuốc trừ ốc dạng phun Helix 500wp là thuốc đặc trị ốc hiệu quả cao. Đặc biệt chuyên trị ốc gây hại trên cây cảnh, lúa. ', 1, '', '• Đối với cây cảnh, rau màu, cây ăn quả: \r\n\r\n+ Ốc sên: \r\n\r\n- Pha 50g/bình 16 lít, lượng nước phun 320 lít/ha. \r\n\r\n- Lượng nước phun 320 lít/ha. \r\n\r\n- Phun lúc trời mát, theo đường di chuyển của ốc. \r\n\r\n• Đối với lúa\r\n\r\n+ Ốc bươu vàng: \r\n\r\n- Liều lượng: 1-1.2kg/ ha. \r\n\r\n- Lượng nước phun 320 lít/ha. \r\n\r\n- Phun thuốc khi ruộng có nước 1-5cm. \r\n\r\n• Thời gian cách ly: không xác định. ', 'ml', 1, 'Syngenta', 345.00, '2025-07-28 17:09:22'),
(12, 1, 6, 'NeemNim Ấn Độ (100ml) – Phòng ngừa sâu bệnh, rệp sáp, sâu xanh, bọ cánh tơ, sâu tơ', 160000, 885, 'neemim.jpg', 'NeemNim Ấn Độ là sản phẩm thuốc trừ sâu được chiết xuất hoàn toàn từ thảo mộc thiên nhiên giúp phòng trừ nhiều loại sâu hại như đục lá, rệp sáp, cánh lụa, sâu tơ, sâu xanh da láng… vô cùng an toàn cho người sử dụng và thiên nhiên.', 1, 'Azadirachtin: 0,3% khối lượng.', '• Pha 30-50ml cho bình 16 lít.\r\n\r\n• Lượng nước phun 400-600 lít/ ha.\r\n\r\n• Phun ướt đều thân lá, lượng nước phun tùy theo cây trồng và thời gian sinh trưởng. Phun phòng ngừa và trị sau khi sâu mới xuất hiện,nếu sâu hại nặng nên phun lại lần 2 sau  7 ngày.\r\n\r\n• Không phun thuốc khi cây đang ra hoa vì sẽ xua đuổi côn trùng có ích làm giảm khả năng thụ phấn của cây.', 'kg', 1, 'Công ty Phân bón Việt Nam', 885.00, '2025-07-29 03:03:29'),
(13, 2, 2, 'Phân Lân Lâm Thao (25kg) - Thúc đẩy ra hoa, đậu trái cho cây ăn quả Mã sản phẩm: PB-VC098-HN 263,000₫', 263000, 40, 'phan_lan_lam_thao-1-3.jpg', 'Phân Lân Lâm Thao có màu xám tro, dạng mịn. Phân lân đơn là một dạng phân hóa học được sử dụng nhiều để bón lót trong giai đoạn trồng cây như: trồng hoa hồng, rau và các loại cây ăn quả.', 1, '• Lân hữu hiệu P2O5hh : 16%<br><br>• Hàm lượng axit tự do (% khối lượng quy về P2O5hh): 4%<br><br>• Cadimi (Cd): 12 mg/kg<br><br>• Lưu huỳnh (S): 10%<br><br>• Độ ẩm: 12%', 'Bước 1: Trộn đất sạch với perlite và phân trùn quế theo tỉ lệ 7:1:2. Tiến hành trộn đều hỗn hợp trên.<br><br>Bước 2: Trộn hỗn hợp bước 1 với 1 ít nấm đối kháng trichoderma và trộn đều, bổ sung thêm 1% phân Lân vào và trộn đều.<br><br>Bạn có thể lót theo từng lớp lân vào hỗn hợp đất hoặc trộn tùy ý.<br><br>Bước 3: Cho hỗn hợp vào chậu và tiến hành trồng hoa hồng bình thường.', 'kg', 25, 'Công ty cổ phần Supe Phốt phát và Hóa chất Lâm Tha', 20.00, '2025-07-19 09:29:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `san_pham_cay_trong`
--

CREATE TABLE `san_pham_cay_trong` (
  `SP_MA` int(11) NOT NULL,
  `LCT_MA` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `san_pham_cay_trong`
--

INSERT INTO `san_pham_cay_trong` (`SP_MA`, `LCT_MA`) VALUES
(8, 1),
(8, 2),
(9, 2),
(10, 2),
(11, 3),
(12, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `selected_cart_items`
--

CREATE TABLE `selected_cart_items` (
  `id` int(11) NOT NULL,
  `GH_MA` int(11) NOT NULL,
  `SP_MA` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `selected_cart_items`
--

INSERT INTO `selected_cart_items` (`id`, `GH_MA`, `SP_MA`, `created_at`) VALUES
(93, 7, 2, '2025-07-09 08:43:41'),
(95, 7, 7, '2025-07-10 03:32:43'),
(97, 7, 5, '2025-07-14 07:52:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `stock_movements`
--

CREATE TABLE `stock_movements` (
  `SM_ID` int(11) NOT NULL,
  `SP_MA` int(11) DEFAULT NULL,
  `SM_SOLUONG` int(11) DEFAULT NULL,
  `SM_LOAI` enum('NHAP','XUAT','KIEM','HUY','HOAN') DEFAULT NULL,
  `SM_THAMCHIEU` int(11) DEFAULT NULL,
  `SM_SOLUONG_CU` int(11) DEFAULT NULL,
  `SM_SOLUONG_MOI` int(11) DEFAULT NULL,
  `SM_AVAILABLE_CU` int(11) DEFAULT NULL,
  `SM_AVAILABLE_MOI` int(11) DEFAULT NULL,
  `SM_GHICHU` text DEFAULT NULL,
  `SM_NHACUNGCAP` varchar(255) DEFAULT NULL,
  `SM_LYDO` varchar(50) DEFAULT NULL,
  `NV_MA` int(11) DEFAULT NULL,
  `SM_THOIGIAN` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `stock_movements`
--

INSERT INTO `stock_movements` (`SM_ID`, `SP_MA`, `SM_SOLUONG`, `SM_LOAI`, `SM_THAMCHIEU`, `SM_SOLUONG_CU`, `SM_SOLUONG_MOI`, `SM_AVAILABLE_CU`, `SM_AVAILABLE_MOI`, `SM_GHICHU`, `SM_NHACUNGCAP`, `SM_LYDO`, `NV_MA`, `SM_THOIGIAN`) VALUES
(1, 1, 0, '', NULL, 989, 989, 989, 989, 'Cập nhật SP_AVAILABLE = SP_SOLUONGTON', NULL, NULL, 1, '2025-07-19 07:48:36'),
(2, 2, 0, '', NULL, 458, 458, 0, 458, 'Cập nhật SP_AVAILABLE = SP_SOLUONGTON', NULL, NULL, 1, '2025-07-19 07:48:36'),
(3, 3, 0, '', NULL, 793, 793, 0, 793, 'Cập nhật SP_AVAILABLE = SP_SOLUONGTON', NULL, NULL, 1, '2025-07-19 07:48:36'),
(4, 4, 0, '', NULL, 575, 575, 0, 575, 'Cập nhật SP_AVAILABLE = SP_SOLUONGTON', NULL, NULL, 1, '2025-07-19 07:48:36'),
(5, 5, 0, '', NULL, 388, 388, 0, 388, 'Cập nhật SP_AVAILABLE = SP_SOLUONGTON', NULL, NULL, 1, '2025-07-19 07:48:36'),
(6, 6, 0, '', NULL, 1998, 1998, 0, 1998, 'Cập nhật SP_AVAILABLE = SP_SOLUONGTON', NULL, NULL, 1, '2025-07-19 07:48:36'),
(7, 7, 0, '', NULL, 1496, 1496, 0, 1496, 'Cập nhật SP_AVAILABLE = SP_SOLUONGTON', NULL, NULL, 1, '2025-07-19 07:48:36'),
(8, 8, 0, '', NULL, 12988, 12988, 0, 12988, 'Cập nhật SP_AVAILABLE = SP_SOLUONGTON', NULL, NULL, 1, '2025-07-19 07:48:36'),
(9, 9, 0, '', NULL, 700, 700, 0, 700, 'Cập nhật SP_AVAILABLE = SP_SOLUONGTON', NULL, NULL, 1, '2025-07-19 07:48:36'),
(10, 10, 0, '', NULL, 2497, 2497, 0, 2497, 'Cập nhật SP_AVAILABLE = SP_SOLUONGTON', NULL, NULL, 1, '2025-07-19 07:48:36'),
(11, 11, 0, '', NULL, 345, 345, 0, 345, 'Cập nhật SP_AVAILABLE = SP_SOLUONGTON', NULL, NULL, 1, '2025-07-19 07:48:36'),
(12, 12, 0, '', NULL, 885, 885, 0, 885, 'Cập nhật SP_AVAILABLE = SP_SOLUONGTON', NULL, NULL, 1, '2025-07-19 07:48:36'),
(13, 13, 0, '', NULL, 20, 20, 0, 20, 'Cập nhật SP_AVAILABLE = SP_SOLUONGTON', NULL, NULL, 1, '2025-07-19 07:48:36'),
(14, 8, 13, '', 239, 12988, 12988, 12988, 12975, 'Đặt hàng #239', NULL, NULL, 1, '2025-07-19 08:32:16'),
(15, 4, 10, '', 240, 575, 575, 575, 565, 'Đặt hàng #240', NULL, NULL, 1, '2025-07-19 08:37:00'),
(16, 6, 7, '', 248, 1998, 1998, 1998, 1991, 'Đặt hàng #248', NULL, NULL, 1, '2025-07-19 09:09:10'),
(17, 2, 8, '', 252, 458, 458, 458, 450, 'Đặt hàng #252', NULL, NULL, 1, '2025-07-19 09:23:09'),
(18, 13, 7, '', 253, 20, 20, 20, 13, 'Đặt hàng #253', NULL, NULL, 1, '2025-07-19 09:29:17'),
(19, 6, 7, '', 254, 1998, 1998, 1998, 1991, 'Đặt hàng #254', NULL, NULL, 1, '2025-07-19 09:33:30'),
(20, 2, 7, '', 255, 458, 458, 458, 451, 'Đặt hàng #255', NULL, NULL, 1, '2025-07-19 09:35:32'),
(21, 8, 6, '', 256, 12988, 12988, 12988, 12982, 'Đặt hàng #256', NULL, NULL, 1, '2025-07-19 09:40:01'),
(22, 8, 6, '', 256, NULL, NULL, NULL, NULL, 'Hoàn kho từ đơn hàng hủy #256', NULL, NULL, NULL, '2025-07-19 09:45:04'),
(23, 7, 8, '', 257, 1496, 1496, 1496, 1488, 'Đặt hàng #257', NULL, NULL, 1, '2025-07-19 10:00:19'),
(24, 4, 13, '', 258, 575, 575, 575, 562, 'Đặt hàng #258', NULL, NULL, 1, '2025-07-19 10:00:50'),
(26, 7, 8, 'HUY', 257, 1496, 1496, 1496, 1504, 'Hủy đơn hàng #257', NULL, 'ádasd', NULL, '2025-07-19 10:26:39'),
(27, 6, 8, '', 259, 1998, 1998, 1998, 1990, 'Đặt hàng #259', NULL, NULL, 1, '2025-07-20 07:52:40'),
(28, 6, 8, 'HOAN', 259, 1998, 2006, 1998, 2006, 'Hoàn lại số lượng từ đơn hàng hủy #259', NULL, NULL, 1, '2025-07-20 08:14:36'),
(29, 8, 1, 'KIEM', 1, 12946, 12945, 12994, 12993, '3434', NULL, NULL, 1, '2025-07-21 08:51:58'),
(30, 2, 5, '', 260, 458, 458, 458, 453, 'Đặt hàng #260', NULL, NULL, 1, '2025-07-21 09:15:34'),
(31, 2, 6, '', 261, 458, 458, 458, 452, 'Đặt hàng #261', NULL, NULL, 1, '2025-07-22 04:28:17'),
(32, 4, 6, '', 262, 575, 575, 575, 569, 'Đặt hàng #262', NULL, NULL, 1, '2025-07-22 04:55:54'),
(33, 2, 6, '', 263, 458, 458, 458, 452, 'Đặt hàng #263', NULL, NULL, 1, '2025-07-22 05:06:45'),
(34, 2, 6, 'HUY', 263, 464, 470, 458, 464, 'Hoàn lại số lượng từ đơn hàng hủy #263 - Lý do: h', NULL, NULL, 1, '2025-07-23 10:28:29'),
(35, 2, 6, 'HUY', 263, 470, 476, 458, 464, 'Hoàn lại số lượng từ đơn hàng hủy #263 - Lý do: dđ', NULL, NULL, 1, '2025-07-23 10:29:31'),
(36, 2, 6, '', 264, 470, 470, 458, 452, 'Đặt hàng #264', NULL, NULL, 1, '2025-07-23 11:04:25'),
(37, 8, 4, '', 265, 12945, 12945, 12993, 12989, 'Đặt hàng #265', NULL, NULL, 1, '2025-07-23 11:08:54'),
(38, 4, 6, '', 266, 575, 575, 575, 569, 'Đặt hàng #266', NULL, NULL, 1, '2025-07-26 02:56:26'),
(39, 2, 16, '', 266, 470, 470, 470, 454, 'Đặt hàng #266', NULL, NULL, 1, '2025-07-26 02:56:26'),
(40, 2, 5, '', 267, 470, 470, 470, 465, 'Đặt hàng #267', NULL, NULL, 1, '2025-07-26 03:11:48'),
(41, 5, 1, '', 268, 388, 388, 388, 387, 'Đặt hàng #268', NULL, NULL, 1, '2025-07-26 06:35:37'),
(42, 1, 7, '', 269, 993, 993, 989, 982, 'Đặt hàng #269', NULL, NULL, 1, '2025-07-28 15:56:19'),
(43, 8, 9, '', 270, 12945, 12945, 12945, 12936, 'Đặt hàng #270', NULL, NULL, 1, '2025-07-28 16:25:52'),
(44, 2, 4, '', 271, 710, 710, 470, 466, 'Đặt hàng #271', NULL, NULL, 1, '2025-07-28 16:29:34'),
(45, 8, 5, '', 272, 12945, 12945, 12945, 12940, 'Đặt hàng #272', NULL, NULL, 1, '2025-07-28 16:30:25'),
(46, 8, 7, '', 273, 12945, 12945, 12945, 12938, 'Đặt hàng #273', NULL, NULL, 1, '2025-07-28 16:41:55'),
(47, 2, 6, '', 274, 710, 710, 710, 704, 'Đặt hàng #274', NULL, NULL, 1, '2025-07-28 16:50:09'),
(48, 8, 6, '', 275, 12945, 12945, 12945, 12939, 'Đặt hàng #275', NULL, NULL, 1, '2025-07-28 16:54:47'),
(49, 8, 6, 'HOAN', 275, 12945, 12951, 12945, 12951, 'Hoàn lại số lượng từ đơn hàng hủy #275', NULL, NULL, 1, '2025-07-28 17:00:52'),
(50, 3, 5, '', 276, 793, 793, 793, 788, 'Đặt hàng #276', NULL, NULL, 1, '2025-07-28 17:07:54'),
(51, 6, 9, '', 277, 2046, 2046, 1998, 1989, 'Đặt hàng #277', NULL, NULL, 1, '2025-07-28 17:08:11'),
(52, 11, 6, '', 278, 345, 345, 345, 339, 'Đặt hàng #278', NULL, NULL, 1, '2025-07-28 17:09:22'),
(53, 4, 6, '', 279, 575, 575, 575, 569, 'Đặt hàng #279', NULL, NULL, 1, '2025-07-28 17:15:10'),
(54, 3, 10, '', 280, 793, 793, 793, 783, 'Đặt hàng #280', NULL, NULL, 1, '2025-07-28 17:16:35'),
(55, 3, 10, 'HOAN', 280, 793, 803, 793, 803, 'Hoàn lại số lượng từ đơn hàng hủy #280', NULL, NULL, 1, '2025-07-28 17:18:40'),
(56, 12, 8, '', 281, 885, 885, 885, 877, 'Đặt hàng #281', NULL, NULL, 1, '2025-07-29 03:03:29'),
(57, 5, 2, '', 282, 388, 388, 388, 386, 'Đặt hàng #282', NULL, NULL, 1, '2025-07-30 08:39:27'),
(58, 2, 11, '', 282, 710, 710, 710, 699, 'Đặt hàng #282', NULL, NULL, 1, '2025-07-30 08:39:27');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `symptoms`
--

CREATE TABLE `symptoms` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `symptoms`
--

INSERT INTO `symptoms` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Vàng lá', 'Lá cây chuyển màu vàng', '2025-07-03 10:11:32'),
(2, 'Thối rễ', 'Rễ cây bị thối, màu đen', '2025-07-03 10:11:32'),
(3, 'Héo lá', 'Lá cây bị héo, cuốn lại', '2025-07-03 10:11:32'),
(4, 'Đốm lá', 'Xuất hiện các đốm trên lá', '2025-07-03 10:11:32'),
(5, 'Lùn cây', 'Cây phát triển chậm, thấp bé', '2025-07-03 10:11:32');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thong_bao`
--

CREATE TABLE `thong_bao` (
  `TB_MA` int(11) NOT NULL,
  `TB_LOAI` varchar(50) NOT NULL COMMENT 'Loại thông báo',
  `TB_NOIDUNG` text NOT NULL COMMENT 'Nội dung thông báo',
  `TB_LINK` varchar(255) DEFAULT NULL COMMENT 'Link liên quan',
  `TB_DADOC` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Đã đọc: 1-Rồi, 0-Chưa',
  `NV_MA` int(11) DEFAULT NULL COMMENT 'Nhân viên nhận thông báo, NULL là gửi tất cả',
  `TB_NGAYTAO` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `thong_bao`
--

INSERT INTO `thong_bao` (`TB_MA`, `TB_LOAI`, `TB_NOIDUNG`, `TB_LINK`, `TB_DADOC`, `NV_MA`, `TB_NGAYTAO`) VALUES
(1, 'YEU_CAU_HUY', 'Đơn hàng #259 yêu cầu hủy. Lý do: kkkkk', 'admin/order_detail.php?id=259', 0, NULL, '2025-07-20 07:52:47'),
(2, 'YEU_CAU_HUY', 'Đơn hàng #275 yêu cầu hủy. Lý do: đổi địa chỉ', 'admin/order_detail.php?id=275', 0, NULL, '2025-07-28 17:00:36'),
(3, 'HOAN_TIEN', 'Yêu cầu hoàn tiền cho đơn hàng #280. Số tiền: 220.000đ', 'admin/refund_detail.php?id=280', 0, NULL, '2025-07-28 17:18:24'),
(4, 'YEU_CAU_HUY', 'Đơn hàng #280 yêu cầu hủy. Lý do: ádasdasd', 'admin/order_detail.php?id=280', 0, NULL, '2025-07-28 17:18:24');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `trang_thai`
--

CREATE TABLE `trang_thai` (
  `TT_MA` int(11) NOT NULL,
  `TT_TEN` varchar(100) NOT NULL,
  `ngay_cap_nhat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `trang_thai`
--

INSERT INTO `trang_thai` (`TT_MA`, `TT_TEN`, `ngay_cap_nhat`) VALUES
(1, 'Chờ xác nhận', '2025-06-18 13:44:27'),
(2, 'Đang giao', '2025-06-18 13:44:27'),
(3, 'Đã giao', '2025-06-18 13:44:27'),
(4, 'Đã hủy', '2025-06-18 13:44:27'),
(5, 'Chờ hủy', '2025-07-19 17:29:25'),
(6, 'Chờ giao', '2025-07-28 23:40:49');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `wards`
--

CREATE TABLE `wards` (
  `id` varchar(20) NOT NULL,
  `district_id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `wards`
--

INSERT INTO `wards` (`id`, `district_id`, `name`, `type`) VALUES
('320101', '3201', 'Hòa Hiệp Bắc', 'Phường'),
('320102', '3201', 'Hòa Hiệp Nam', 'Phường'),
('320103', '3201', 'Hòa Khánh Bắc', 'Phường'),
('320104', '3201', 'Hòa Khánh Nam', 'Phường'),
('320105', '3201', 'Hòa Minh', 'Phường'),
('320201', '3202', 'Tam Thuận', 'Phường'),
('320202', '3202', 'Thanh Khê Tây', 'Phường'),
('320203', '3202', 'Thanh Khê Đông', 'Phường'),
('320204', '3202', 'Xuân Hà', 'Phường'),
('320205', '3202', 'Tân Chính', 'Phường'),
('320206', '3202', 'Chính Gián', 'Phường'),
('320207', '3202', 'Vĩnh Trung', 'Phường'),
('320208', '3202', 'Thạc Gián', 'Phường'),
('320209', '3202', 'An Khê', 'Phường'),
('320210', '3202', 'Hòa Khê', 'Phường'),
('320301', '3203', 'Thanh Bình', 'Phường'),
('320302', '3203', 'Thuận Phước', 'Phường'),
('320303', '3203', 'Thạch Thang', 'Phường'),
('320304', '3203', 'Hải Châu I', 'Phường'),
('320305', '3203', 'Hải Châu II', 'Phường'),
('320306', '3203', 'Phước Ninh', 'Phường'),
('320307', '3203', 'Hòa Thuận Tây', 'Phường'),
('320308', '3203', 'Hòa Thuận Đông', 'Phường'),
('320309', '3203', 'Nam Dương', 'Phường'),
('320310', '3203', 'Bình Hiên', 'Phường'),
('320311', '3203', 'Bình Thuận', 'Phường'),
('320312', '3203', 'Hòa Cường Bắc', 'Phường'),
('320313', '3203', 'Hòa Cường Nam', 'Phường'),
('320401', '3204', 'Thọ Quang', 'Phường'),
('320402', '3204', 'Mân Thái', 'Phường'),
('320403', '3204', 'Phước Mỹ', 'Phường'),
('320404', '3204', 'An Hải Bắc', 'Phường'),
('320405', '3204', 'An Hải Tây', 'Phường'),
('320406', '3204', 'An Hải Đông', 'Phường'),
('320407', '3204', 'Nại Hiên Đông', 'Phường'),
('320501', '3205', 'Mỹ An', 'Phường'),
('320502', '3205', 'Khuê Mỹ', 'Phường'),
('320503', '3205', 'Hoà Quý', 'Phường'),
('320504', '3205', 'Hoà Hải', 'Phường'),
('320601', '3206', 'Khuê Trung', 'Phường'),
('320602', '3206', 'Hòa Phát', 'Phường'),
('320603', '3206', 'Hòa An', 'Phường'),
('320604', '3206', 'Hòa Thọ Tây', 'Phường'),
('320605', '3206', 'Hòa Thọ Đông', 'Phường'),
('320606', '3206', 'Hòa Xuân', 'Phường'),
('320701', '3207', 'Hòa Bắc', 'Xã'),
('320702', '3207', 'Hòa Liên', 'Xã'),
('320703', '3207', 'Hòa Ninh', 'Xã'),
('320704', '3207', 'Hòa Sơn', 'Xã'),
('320705', '3207', 'Hòa Nhơn', 'Xã'),
('320706', '3207', 'Hòa Phú', 'Xã'),
('320707', '3207', 'Hòa Phong', 'Xã'),
('320708', '3207', 'Hòa Châu', 'Xã'),
('320709', '3207', 'Hòa Tiến', 'Xã'),
('320710', '3207', 'Hòa Phước', 'Xã'),
('320711', '3207', 'Hòa Khương', 'Xã');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `chitiet_gh`
--
ALTER TABLE `chitiet_gh`
  ADD PRIMARY KEY (`GH_MA`,`SP_MA`),
  ADD KEY `FK_CHITIET_RELATIONS_SAN_PHA` (`SP_MA`);

--
-- Chỉ mục cho bảng `chitiet_pk`
--
ALTER TABLE `chitiet_pk`
  ADD PRIMARY KEY (`PK_ID`,`SP_MA`),
  ADD KEY `FK_CHITIETPK_SANPHAM` (`SP_MA`);

--
-- Chỉ mục cho bảng `chitiet_pn`
--
ALTER TABLE `chitiet_pn`
  ADD PRIMARY KEY (`SP_MA`,`PN_STT`,`NH_MA`),
  ADD KEY `FK_CTPN_NH` (`NH_MA`),
  ADD KEY `FK_CHITIET_CO_PHIEU_N` (`PN_STT`);

--
-- Chỉ mục cho bảng `chi_tiet_hd`
--
ALTER TABLE `chi_tiet_hd`
  ADD PRIMARY KEY (`CTHD_ID`),
  ADD KEY `idx_hd_stt` (`HD_STT`),
  ADD KEY `idx_sp_ma` (`SP_MA`);

--
-- Chỉ mục cho bảng `chuc_vu`
--
ALTER TABLE `chuc_vu`
  ADD PRIMARY KEY (`CV_MA`);

--
-- Chỉ mục cho bảng `crops`
--
ALTER TABLE `crops`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `danh_muc`
--
ALTER TABLE `danh_muc`
  ADD PRIMARY KEY (`DM_MA`);

--
-- Chỉ mục cho bảng `delivery_status`
--
ALTER TABLE `delivery_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `HD_STT` (`HD_STT`);

--
-- Chỉ mục cho bảng `dia_chi_giao_hang`
--
ALTER TABLE `dia_chi_giao_hang`
  ADD PRIMARY KEY (`DCGH_MA`),
  ADD KEY `fk_dcgh_hd` (`DH_MA`);

--
-- Chỉ mục cho bảng `diseases`
--
ALTER TABLE `diseases`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `disease_products`
--
ALTER TABLE `disease_products`
  ADD PRIMARY KEY (`disease_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `disease_symptoms`
--
ALTER TABLE `disease_symptoms`
  ADD PRIMARY KEY (`disease_id`,`symptom_id`),
  ADD KEY `symptom_id` (`symptom_id`);

--
-- Chỉ mục cho bảng `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `province_id` (`province_id`);

--
-- Chỉ mục cho bảng `don_van_chuyen`
--
ALTER TABLE `don_van_chuyen`
  ADD PRIMARY KEY (`DVC_MA`),
  ADD KEY `FK_DO` (`NVC_MA`);

--
-- Chỉ mục cho bảng `gio_hang`
--
ALTER TABLE `gio_hang`
  ADD PRIMARY KEY (`GH_MA`),
  ADD KEY `FK_COGH` (`KH_MA`);

--
-- Chỉ mục cho bảng `growth_stages`
--
ALTER TABLE `growth_stages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `crop_id` (`crop_id`);

--
-- Chỉ mục cho bảng `hoan_tien`
--
ALTER TABLE `hoan_tien`
  ADD PRIMARY KEY (`HT_MA`),
  ADD KEY `HD_STT` (`HD_STT`),
  ADD KEY `idx_hoan_tien_trangthai` (`HT_TRANGTHAI`),
  ADD KEY `idx_hoan_tien_magiaodich` (`HT_MAGIAODICH`);

--
-- Chỉ mục cho bảng `hoa_don`
--
ALTER TABLE `hoa_don`
  ADD PRIMARY KEY (`HD_STT`),
  ADD KEY `FK_HOA_DON_DO_NV_LAP_NHAN_VI` (`NV_MA`),
  ADD KEY `FK_HOA_DON_GH` (`GH_MA`),
  ADD KEY `idx_tt_ma` (`TT_MA`),
  ADD KEY `idx_dvc_ma` (`DVC_MA`),
  ADD KEY `idx_pttt_ma` (`PTTT_MA`),
  ADD KEY `idx_kh_ma` (`KH_MA`),
  ADD KEY `FK_HOA_DON_KM` (`KM_MA`);

--
-- Chỉ mục cho bảng `khach_hang`
--
ALTER TABLE `khach_hang`
  ADD PRIMARY KEY (`KH_MA`);

--
-- Chỉ mục cho bảng `khuyen_mai`
--
ALTER TABLE `khuyen_mai`
  ADD PRIMARY KEY (`KM_MA`);

--
-- Chỉ mục cho bảng `khuyen_mai_san_pham`
--
ALTER TABLE `khuyen_mai_san_pham`
  ADD PRIMARY KEY (`KMSP_MA`),
  ADD KEY `KM_MA` (`KM_MA`),
  ADD KEY `SP_MA` (`SP_MA`);

--
-- Chỉ mục cho bảng `lich_su_ton_kho`
--
ALTER TABLE `lich_su_ton_kho`
  ADD PRIMARY KEY (`LSTK_MA`),
  ADD KEY `SP_MA` (`SP_MA`),
  ADD KEY `NV_MA` (`NV_MA`);

--
-- Chỉ mục cho bảng `loai_benh`
--
ALTER TABLE `loai_benh`
  ADD PRIMARY KEY (`Ma_loai_benh`),
  ADD KEY `FK_LOAI_BEN_THUOC_LB_LOAI_CA` (`LCT_MA`);

--
-- Chỉ mục cho bảng `loai_cay_trong`
--
ALTER TABLE `loai_cay_trong`
  ADD PRIMARY KEY (`LCT_MA`);

--
-- Chỉ mục cho bảng `nguon_hang`
--
ALTER TABLE `nguon_hang`
  ADD PRIMARY KEY (`NH_MA`);

--
-- Chỉ mục cho bảng `nhan_vien`
--
ALTER TABLE `nhan_vien`
  ADD PRIMARY KEY (`NV_MA`),
  ADD KEY `FK_COCHUCVU` (`CV_MA`);

--
-- Chỉ mục cho bảng `nhan_vien_login_history`
--
ALTER TABLE `nhan_vien_login_history`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_LOGIN_HISTORY_NV` (`NV_MA`);

--
-- Chỉ mục cho bảng `nha_van_chuyen`
--
ALTER TABLE `nha_van_chuyen`
  ADD PRIMARY KEY (`NVC_MA`);

--
-- Chỉ mục cho bảng `phieu_kiem`
--
ALTER TABLE `phieu_kiem`
  ADD PRIMARY KEY (`PK_ID`),
  ADD KEY `FK_PHIEUKIEM_NHANVIEN` (`NV_MA`);

--
-- Chỉ mục cho bảng `phieu_nhap`
--
ALTER TABLE `phieu_nhap`
  ADD PRIMARY KEY (`PN_STT`),
  ADD KEY `FK_DONHANVIENLAP` (`NV_MA`);

--
-- Chỉ mục cho bảng `phuong_thuc_thanh_toan`
--
ALTER TABLE `phuong_thuc_thanh_toan`
  ADD PRIMARY KEY (`PTTT_MA`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `product_crops`
--
ALTER TABLE `product_crops`
  ADD PRIMARY KEY (`product_id`,`crop_id`,`growth_stage_id`),
  ADD KEY `crop_id` (`crop_id`),
  ADD KEY `growth_stage_id` (`growth_stage_id`);

--
-- Chỉ mục cho bảng `provinces`
--
ALTER TABLE `provinces`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `refund_requests`
--
ALTER TABLE `refund_requests`
  ADD PRIMARY KEY (`REFUND_ID`),
  ADD KEY `HD_STT` (`HD_STT`);

--
-- Chỉ mục cho bảng `san_pham`
--
ALTER TABLE `san_pham`
  ADD PRIMARY KEY (`SP_MA`),
  ADD KEY `FK_CODM` (`DM_MA`),
  ADD KEY `FK_SAN_PHA_THUOC_NGUON_H` (`NH_MA`);

--
-- Chỉ mục cho bảng `san_pham_cay_trong`
--
ALTER TABLE `san_pham_cay_trong`
  ADD PRIMARY KEY (`SP_MA`,`LCT_MA`),
  ADD KEY `FK_SPCT_LCT` (`LCT_MA`);

--
-- Chỉ mục cho bảng `selected_cart_items`
--
ALTER TABLE `selected_cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_cart_product` (`GH_MA`,`SP_MA`),
  ADD KEY `idx_cart_id` (`GH_MA`),
  ADD KEY `idx_product_id` (`SP_MA`);

--
-- Chỉ mục cho bảng `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`SM_ID`),
  ADD KEY `SP_MA` (`SP_MA`),
  ADD KEY `NV_MA` (`NV_MA`);

--
-- Chỉ mục cho bảng `symptoms`
--
ALTER TABLE `symptoms`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `thong_bao`
--
ALTER TABLE `thong_bao`
  ADD PRIMARY KEY (`TB_MA`),
  ADD KEY `FK_THONGBAO_NV` (`NV_MA`);

--
-- Chỉ mục cho bảng `trang_thai`
--
ALTER TABLE `trang_thai`
  ADD PRIMARY KEY (`TT_MA`);

--
-- Chỉ mục cho bảng `wards`
--
ALTER TABLE `wards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `district_id` (`district_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `chitiet_gh`
--
ALTER TABLE `chitiet_gh`
  MODIFY `GH_MA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `chi_tiet_hd`
--
ALTER TABLE `chi_tiet_hd`
  MODIFY `CTHD_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=258;

--
-- AUTO_INCREMENT cho bảng `crops`
--
ALTER TABLE `crops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `delivery_status`
--
ALTER TABLE `delivery_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `dia_chi_giao_hang`
--
ALTER TABLE `dia_chi_giao_hang`
  MODIFY `DCGH_MA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT cho bảng `diseases`
--
ALTER TABLE `diseases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `don_van_chuyen`
--
ALTER TABLE `don_van_chuyen`
  MODIFY `DVC_MA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=169;

--
-- AUTO_INCREMENT cho bảng `gio_hang`
--
ALTER TABLE `gio_hang`
  MODIFY `GH_MA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `growth_stages`
--
ALTER TABLE `growth_stages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `hoan_tien`
--
ALTER TABLE `hoan_tien`
  MODIFY `HT_MA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `hoa_don`
--
ALTER TABLE `hoa_don`
  MODIFY `HD_STT` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=283;

--
-- AUTO_INCREMENT cho bảng `khach_hang`
--
ALTER TABLE `khach_hang`
  MODIFY `KH_MA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `khuyen_mai`
--
ALTER TABLE `khuyen_mai`
  MODIFY `KM_MA` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `khuyen_mai_san_pham`
--
ALTER TABLE `khuyen_mai_san_pham`
  MODIFY `KMSP_MA` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Mã khuyến mãi sản phẩm';

--
-- AUTO_INCREMENT cho bảng `lich_su_ton_kho`
--
ALTER TABLE `lich_su_ton_kho`
  MODIFY `LSTK_MA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT cho bảng `nhan_vien_login_history`
--
ALTER TABLE `nhan_vien_login_history`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `phieu_kiem`
--
ALTER TABLE `phieu_kiem`
  MODIFY `PK_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `refund_requests`
--
ALTER TABLE `refund_requests`
  MODIFY `REFUND_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `selected_cart_items`
--
ALTER TABLE `selected_cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT cho bảng `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `SM_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT cho bảng `symptoms`
--
ALTER TABLE `symptoms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `thong_bao`
--
ALTER TABLE `thong_bao`
  MODIFY `TB_MA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chitiet_gh`
--
ALTER TABLE `chitiet_gh`
  ADD CONSTRAINT `FK_CHITIET_RELATIONS_SAN_PHA` FOREIGN KEY (`SP_MA`) REFERENCES `san_pham` (`SP_MA`),
  ADD CONSTRAINT `FK_CTGH_GH` FOREIGN KEY (`GH_MA`) REFERENCES `gio_hang` (`GH_MA`);

--
-- Các ràng buộc cho bảng `chitiet_pk`
--
ALTER TABLE `chitiet_pk`
  ADD CONSTRAINT `FK_CHITIETPK_PHIEUKIEM` FOREIGN KEY (`PK_ID`) REFERENCES `phieu_kiem` (`PK_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_CHITIETPK_SANPHAM` FOREIGN KEY (`SP_MA`) REFERENCES `san_pham` (`SP_MA`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `chitiet_pn`
--
ALTER TABLE `chitiet_pn`
  ADD CONSTRAINT `FK_CHITIET_CO_PHIEU_N` FOREIGN KEY (`PN_STT`) REFERENCES `phieu_nhap` (`PN_STT`),
  ADD CONSTRAINT `FK_CTPN_NH` FOREIGN KEY (`NH_MA`) REFERENCES `nguon_hang` (`NH_MA`);

--
-- Các ràng buộc cho bảng `chi_tiet_hd`
--
ALTER TABLE `chi_tiet_hd`
  ADD CONSTRAINT `fk_cthd_hd` FOREIGN KEY (`HD_STT`) REFERENCES `hoa_don` (`HD_STT`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cthd_sp` FOREIGN KEY (`SP_MA`) REFERENCES `san_pham` (`SP_MA`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `delivery_status`
--
ALTER TABLE `delivery_status`
  ADD CONSTRAINT `fk_delivery_status_hd` FOREIGN KEY (`HD_STT`) REFERENCES `hoa_don` (`HD_STT`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `dia_chi_giao_hang`
--
ALTER TABLE `dia_chi_giao_hang`
  ADD CONSTRAINT `fk_dcgh_hd` FOREIGN KEY (`DH_MA`) REFERENCES `hoa_don` (`HD_STT`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `disease_products`
--
ALTER TABLE `disease_products`
  ADD CONSTRAINT `disease_products_ibfk_1` FOREIGN KEY (`disease_id`) REFERENCES `diseases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `disease_products_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `disease_symptoms`
--
ALTER TABLE `disease_symptoms`
  ADD CONSTRAINT `disease_symptoms_ibfk_1` FOREIGN KEY (`disease_id`) REFERENCES `diseases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `disease_symptoms_ibfk_2` FOREIGN KEY (`symptom_id`) REFERENCES `symptoms` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `districts`
--
ALTER TABLE `districts`
  ADD CONSTRAINT `districts_ibfk_1` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`),
  ADD CONSTRAINT `fk_district_province` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `don_van_chuyen`
--
ALTER TABLE `don_van_chuyen`
  ADD CONSTRAINT `FK_DO` FOREIGN KEY (`NVC_MA`) REFERENCES `nha_van_chuyen` (`NVC_MA`);

--
-- Các ràng buộc cho bảng `gio_hang`
--
ALTER TABLE `gio_hang`
  ADD CONSTRAINT `FK_COGH` FOREIGN KEY (`KH_MA`) REFERENCES `khach_hang` (`KH_MA`);

--
-- Các ràng buộc cho bảng `growth_stages`
--
ALTER TABLE `growth_stages`
  ADD CONSTRAINT `growth_stages_ibfk_1` FOREIGN KEY (`crop_id`) REFERENCES `crops` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `hoan_tien`
--
ALTER TABLE `hoan_tien`
  ADD CONSTRAINT `hoan_tien_ibfk_1` FOREIGN KEY (`HD_STT`) REFERENCES `hoa_don` (`HD_STT`);

--
-- Các ràng buộc cho bảng `hoa_don`
--
ALTER TABLE `hoa_don`
  ADD CONSTRAINT `FK_HOA_DON_CO_PHUONG` FOREIGN KEY (`PTTT_MA`) REFERENCES `phuong_thuc_thanh_toan` (`PTTT_MA`),
  ADD CONSTRAINT `FK_HOA_DON_DO_NV_LAP_NHAN_VI` FOREIGN KEY (`NV_MA`) REFERENCES `nhan_vien` (`NV_MA`),
  ADD CONSTRAINT `FK_HOA_DON_GH` FOREIGN KEY (`GH_MA`) REFERENCES `gio_hang` (`GH_MA`),
  ADD CONSTRAINT `FK_HOA_DON_KM` FOREIGN KEY (`KM_MA`) REFERENCES `khuyen_mai` (`KM_MA`),
  ADD CONSTRAINT `hoa_don_ibfk_1` FOREIGN KEY (`TT_MA`) REFERENCES `trang_thai` (`TT_MA`),
  ADD CONSTRAINT `hoa_don_ibfk_7` FOREIGN KEY (`KH_MA`) REFERENCES `khach_hang` (`KH_MA`);

--
-- Các ràng buộc cho bảng `khuyen_mai_san_pham`
--
ALTER TABLE `khuyen_mai_san_pham`
  ADD CONSTRAINT `khuyen_mai_san_pham_ibfk_1` FOREIGN KEY (`KM_MA`) REFERENCES `khuyen_mai` (`KM_MA`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `khuyen_mai_san_pham_ibfk_2` FOREIGN KEY (`SP_MA`) REFERENCES `san_pham` (`SP_MA`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `lich_su_ton_kho`
--
ALTER TABLE `lich_su_ton_kho`
  ADD CONSTRAINT `lich_su_ton_kho_ibfk_1` FOREIGN KEY (`SP_MA`) REFERENCES `san_pham` (`SP_MA`),
  ADD CONSTRAINT `lich_su_ton_kho_ibfk_2` FOREIGN KEY (`NV_MA`) REFERENCES `nhan_vien` (`NV_MA`);

--
-- Các ràng buộc cho bảng `loai_benh`
--
ALTER TABLE `loai_benh`
  ADD CONSTRAINT `FK_LOAI_BEN_THUOC_LB_LOAI_CA` FOREIGN KEY (`LCT_MA`) REFERENCES `loai_cay_trong` (`LCT_MA`);

--
-- Các ràng buộc cho bảng `nhan_vien`
--
ALTER TABLE `nhan_vien`
  ADD CONSTRAINT `FK_COCHUCVU` FOREIGN KEY (`CV_MA`) REFERENCES `chuc_vu` (`CV_MA`);

--
-- Các ràng buộc cho bảng `nhan_vien_login_history`
--
ALTER TABLE `nhan_vien_login_history`
  ADD CONSTRAINT `FK_LOGIN_HISTORY_NV` FOREIGN KEY (`NV_MA`) REFERENCES `nhan_vien` (`NV_MA`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `phieu_kiem`
--
ALTER TABLE `phieu_kiem`
  ADD CONSTRAINT `FK_PHIEUKIEM_NHANVIEN` FOREIGN KEY (`NV_MA`) REFERENCES `nhan_vien` (`NV_MA`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `phieu_nhap`
--
ALTER TABLE `phieu_nhap`
  ADD CONSTRAINT `FK_DONHANVIENLAP` FOREIGN KEY (`NV_MA`) REFERENCES `nhan_vien` (`NV_MA`);

--
-- Các ràng buộc cho bảng `product_crops`
--
ALTER TABLE `product_crops`
  ADD CONSTRAINT `product_crops_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_crops_ibfk_2` FOREIGN KEY (`crop_id`) REFERENCES `crops` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_crops_ibfk_3` FOREIGN KEY (`growth_stage_id`) REFERENCES `growth_stages` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `refund_requests`
--
ALTER TABLE `refund_requests`
  ADD CONSTRAINT `refund_requests_ibfk_1` FOREIGN KEY (`HD_STT`) REFERENCES `hoa_don` (`HD_STT`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `san_pham`
--
ALTER TABLE `san_pham`
  ADD CONSTRAINT `FK_CODM` FOREIGN KEY (`DM_MA`) REFERENCES `danh_muc` (`DM_MA`),
  ADD CONSTRAINT `FK_SAN_PHA_THUOC_NGUON_H` FOREIGN KEY (`NH_MA`) REFERENCES `nguon_hang` (`NH_MA`);

--
-- Các ràng buộc cho bảng `san_pham_cay_trong`
--
ALTER TABLE `san_pham_cay_trong`
  ADD CONSTRAINT `FK_SPCT_LCT` FOREIGN KEY (`LCT_MA`) REFERENCES `loai_cay_trong` (`LCT_MA`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_SPCT_SP` FOREIGN KEY (`SP_MA`) REFERENCES `san_pham` (`SP_MA`);

--
-- Các ràng buộc cho bảng `selected_cart_items`
--
ALTER TABLE `selected_cart_items`
  ADD CONSTRAINT `selected_cart_items_ibfk_1` FOREIGN KEY (`GH_MA`) REFERENCES `gio_hang` (`GH_MA`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `selected_cart_items_ibfk_2` FOREIGN KEY (`SP_MA`) REFERENCES `san_pham` (`SP_MA`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_ibfk_1` FOREIGN KEY (`SP_MA`) REFERENCES `san_pham` (`SP_MA`),
  ADD CONSTRAINT `stock_movements_ibfk_2` FOREIGN KEY (`NV_MA`) REFERENCES `nhan_vien` (`NV_MA`);

--
-- Các ràng buộc cho bảng `thong_bao`
--
ALTER TABLE `thong_bao`
  ADD CONSTRAINT `FK_THONGBAO_NV` FOREIGN KEY (`NV_MA`) REFERENCES `nhan_vien` (`NV_MA`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `wards`
--
ALTER TABLE `wards`
  ADD CONSTRAINT `fk_ward_district` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `wards_ibfk_1` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
