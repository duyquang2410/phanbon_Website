-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th6 23, 2025 lúc 08:16 AM
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
(8, 7, 4, 'cái', 0),
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
  `CTHD_GHICHU` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chi_tiet_hd`
--

INSERT INTO `chi_tiet_hd` (`CTHD_ID`, `SP_MA`, `HD_STT`, `CTHD_SOLUONG`, `CTHD_DONGIA`, `CTHD_DONVITINH`, `CTHD_GHICHU`) VALUES
(3, 12, 6, 4, 160000, NULL, NULL),
(4, 6, 7, 3, 19000, NULL, NULL),
(5, 4, 8, 4, 6000, NULL, NULL),
(6, 4, 9, 6, 6000, NULL, NULL),
(7, 1, 10, 4, 26000, NULL, NULL),
(8, 8, 11, 2, 220000, NULL, NULL),
(9, 1, 12, 1, 26000, NULL, NULL),
(10, 2, 13, 1, 35000, NULL, NULL),
(11, 2, 14, 3, 35000, NULL, NULL),
(12, 4, 15, 3, 6000, NULL, NULL),
(13, 5, 15, 3, 45000, NULL, NULL),
(14, 12, 16, 5, 160000, NULL, NULL),
(15, 4, 17, 2, 6000, NULL, NULL),
(16, 2, 18, 5, 35000, NULL, NULL),
(17, 12, 18, 6, 160000, NULL, NULL),
(19, 4, 20, 4, 6000, 'cái', NULL),
(20, 5, 21, 4, 45000, 'cái', NULL),
(21, 3, 22, 4, 22000, 'cái', NULL),
(22, 2, 23, 4, 35000, 'cái', NULL),
(23, 2, 24, 3, 35000, 'cái', NULL),
(24, 2, 25, 3, 35000, 'cái', NULL),
(25, 5, 26, 5, 45000, 'cái', NULL),
(26, 11, 27, 5, 23000, 'cái', NULL),
(27, 7, 28, 4, 10500, 'cái', NULL),
(28, 2, 29, 4, 35000, 'cái', NULL),
(29, 10, 32, 3, 43000, 'cái', NULL),
(30, 2, 33, 4, 35000, 'cái', NULL),
(31, 4, 34, 4, 6000, 'cái', NULL),
(32, 4, 35, 2, 6000, 'cái', NULL),
(33, 8, 35, 6, 220000, 'cái', NULL),
(34, 2, 36, 3, 35000, 'cái', NULL),
(35, 8, 37, 4, 220000, 'cái', NULL),
(36, 1, 38, 6, 26000, 'cái', NULL),
(37, 2, 39, 3, 35000, 'cái', NULL),
(38, 2, 40, 4, 35000, 'cái', NULL),
(39, 6, 40, 2, 19000, 'cái', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chuc_vu`
--

CREATE TABLE `chuc_vu` (
  `CV_MA` int(11) NOT NULL,
  `CV_TEN` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chuc_vu`
--

INSERT INTO `chuc_vu` (`CV_MA`, `CV_TEN`) VALUES
(1, 'Quản lý'),
(2, 'Nhân viên bán hàng'),
(3, 'Kế toán'),
(4, 'Thủ kho'),
(5, 'Bảo vệ');

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
(20, 40, '4', '79', '1255', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', NULL, '2025-06-22 08:05:21');

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
(52, 1, 'Cần thơ, 1255, 79, 4', '2025-06-22 15:05:21', '2025-06-25 15:05:21');

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
-- Cấu trúc bảng cho bảng `hoa_don`
--

CREATE TABLE `hoa_don` (
  `HD_STT` int(11) NOT NULL,
  `TT_MA` int(11) NOT NULL,
  `DVC_MA` int(11) NOT NULL,
  `NV_MA` int(11) NOT NULL,
  `PTTT_MA` int(11) NOT NULL,
  `KM_MA` int(11) DEFAULT NULL,
  `GH_MA` int(11) DEFAULT NULL,
  `HD_NGAYLAP` datetime NOT NULL,
  `HD_TONGTIEN` float NOT NULL,
  `HD_PHISHIP` float DEFAULT 0,
  `HD_LIDOHUY` varchar(200) DEFAULT NULL,
  `KH_MA` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `hoa_don`
--

INSERT INTO `hoa_don` (`HD_STT`, `TT_MA`, `DVC_MA`, `NV_MA`, `PTTT_MA`, `KM_MA`, `GH_MA`, `HD_NGAYLAP`, `HD_TONGTIEN`, `HD_PHISHIP`, `HD_LIDOHUY`, `KH_MA`) VALUES
(6, 1, 12, 1, 1, NULL, 12, '2025-06-18 16:47:56', 640000, 0, NULL, 4),
(7, 1, 13, 1, 1, NULL, 12, '2025-06-18 16:48:33', 57000, 0, NULL, 4),
(8, 1, 14, 1, 1, NULL, 12, '2025-06-18 16:49:59', 24000, 0, NULL, 4),
(9, 1, 15, 1, 1, NULL, 7, '2025-06-19 09:43:24', 36000, 0, NULL, 2),
(10, 1, 16, 1, 1, NULL, 7, '2025-06-19 10:09:36', 104000, 0, NULL, 2),
(11, 1, 17, 1, 1, NULL, 7, '2025-06-19 10:43:08', 440000, 0, NULL, 2),
(12, 1, 18, 1, 1, NULL, 7, '2025-06-19 10:47:03', 26000, 0, NULL, 2),
(13, 1, 19, 1, 1, NULL, 7, '2025-06-19 11:06:36', 35000, 0, NULL, 2),
(14, 1, 20, 1, 1, NULL, 7, '2025-06-19 11:21:31', 94500, 0, NULL, 2),
(15, 1, 21, 1, 1, NULL, 7, '2025-06-19 11:41:08', 153000, 0, NULL, 2),
(16, 1, 22, 1, 1, 1, 7, '2025-06-19 11:45:56', 720000, 0, NULL, 2),
(17, 1, 27, 1, 1, NULL, 7, '2025-06-22 09:47:32', 59000, 0, NULL, 2),
(18, 1, 28, 1, 1, NULL, 7, '2025-06-22 10:11:04', 2305000, 0, NULL, 2),
(20, 1, 32, 1, 2, NULL, 7, '2025-06-22 11:44:49', 59000, 35000, NULL, 2),
(21, 1, 33, 1, 3, NULL, 7, '2025-06-22 11:45:43', 215000, 35000, NULL, 2),
(22, 1, 34, 1, 1, NULL, 7, '2025-06-22 11:54:49', 123000, 35000, NULL, 2),
(23, 1, 35, 1, 1, NULL, 7, '2025-06-22 11:58:42', 175000, 35000, NULL, 2),
(24, 1, 36, 1, 1, NULL, 7, '2025-06-22 12:04:24', 139800, 34800, NULL, 2),
(25, 1, 37, 1, 1, NULL, 7, '2025-06-22 12:10:10', 140000, 35000, NULL, 2),
(26, 1, 38, 1, 1, NULL, 7, '2025-06-22 12:17:20', 260000, 35000, NULL, 2),
(27, 1, 39, 1, 1, NULL, 7, '2025-06-22 12:22:15', 150000, 35000, NULL, 2),
(28, 1, 40, 1, 2, NULL, 7, '2025-06-22 12:25:57', 77000, 35000, NULL, 2),
(29, 1, 41, 1, 1, 1, 7, '2025-06-22 12:26:59', 161000, 35000, NULL, 2),
(32, 1, 44, 1, 1, 1, NULL, '2025-06-22 13:09:44', 129000, 52500, NULL, 2),
(33, 1, 45, 1, 1, 1, NULL, '2025-06-22 13:23:09', 140000, 35000, NULL, 2),
(34, 1, 46, 1, 1, 1, NULL, '2025-06-22 13:27:39', 24000, 35000, NULL, 2),
(35, 1, 47, 1, 2, 1, NULL, '2025-06-22 13:59:23', 1332000, 40001, NULL, 2),
(36, 1, 48, 1, 1, NULL, NULL, '2025-06-22 14:00:41', 105000, 16500, NULL, 2),
(37, 1, 49, 1, 1, NULL, NULL, '2025-06-22 14:02:16', 880000, 34800, NULL, 2),
(38, 1, 50, 1, 2, 4, NULL, '2025-06-22 14:05:46', 156000, 44000, NULL, 2),
(39, 1, 51, 1, 1, NULL, NULL, '2025-06-22 14:39:25', 105000, 33000, NULL, 2),
(40, 1, 52, 1, 1, NULL, NULL, '2025-06-22 15:05:21', 178000, 37000, NULL, 2);

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
(2, 'Quang Duy', 'Cần thơ', '0793994771', '2025-06-21 00:00:00', 'duyquang2709pp@gmail.com', 'M', '2025-05-24 00:00:00', 'google_1748064450', 'b727f634ace7906ea4573711412938ee', 'default-avatar.jpg', '112253003004925133473'),
(3, 'Nguyen Nhat Duy Quang C2200016', 'Cần thơ', '0793994771', '2000-10-14 00:00:00', 'quangc2200016@student.ctu.edu.vn', 'M', '2025-06-16 00:00:00', 'google_1750061149', 'b83f5eb3138149a2251fcfe81c8d2648', 'default-avatar.jpg', '113160860558260392135'),
(4, 'Quang Duy', 'Cần thơ', '0793994771', '0000-00-00 00:00:00', 'chayspin2709@gmail.com', 'M', '2025-06-18 00:00:00', 'google_1750235816', '45cc106d201bcd2a1291113218e76e84', 'default-avatar.jpg', '117356618720922982574');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khuyen_mai`
--

CREATE TABLE `khuyen_mai` (
  `KM_MA` int(11) NOT NULL,
  `Code` varchar(50) DEFAULT NULL,
  `KM_TGBD` datetime DEFAULT NULL,
  `KM_TGKT` datetime DEFAULT NULL,
  `KM_GIATRI` float DEFAULT NULL,
  `hinh_thuc_km` enum('Giảm phần trăm','Giảm trực tiếp','Mua X tặng Y','Tặng quà') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `khuyen_mai`
--

INSERT INTO `khuyen_mai` (`KM_MA`, `Code`, `KM_TGBD`, `KM_TGKT`, `KM_GIATRI`, `hinh_thuc_km`) VALUES
(1, 'PHANBON10', '2025-06-01 00:00:00', '2025-06-30 23:59:59', 10, 'Giảm phần trăm'),
(2, 'THUOC20K', '2025-06-15 00:00:00', '2025-07-15 23:59:59', 20000, 'Giảm trực tiếp'),
(3, 'MUA2TANG1', '2025-06-10 00:00:00', '2025-06-25 23:59:59', 0, ''),
(4, 'FARMER5', '2025-06-01 00:00:00', '2025-06-30 23:59:59', 5, 'Giảm phần trăm'),
(5, 'PESTCONTROL', '2025-07-01 00:00:00', '2025-07-31 23:59:59', 15, 'Giảm phần trăm'),
(6, 'TANGCHAU', '2025-06-01 00:00:00', '2025-06-30 23:59:59', 0, 'Tặng quà');

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

--
-- Đang đổ dữ liệu cho bảng `khuyen_mai_san_pham`
--

INSERT INTO `khuyen_mai_san_pham` (`KMSP_MA`, `KM_MA`, `SP_MA`, `KMSP_SOLUONG_MUA`, `KMSP_SOLUONG_TANG`, `KMSP_QUATANG`, `KMSP_NGAYTAO`, `KMSP_NGAYCAPNHAT`, `KMSP_TRANGTHAI`) VALUES
(1, 1, 1, 2, 1, NULL, '2025-06-22 07:46:19', '2025-06-22 07:46:19', 1),
(2, 2, 2, 1, 0, 'Chậu cây cảnh mini', '2025-06-22 07:46:19', '2025-06-22 07:46:19', 1);

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

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `loai_cay_trong`
--

CREATE TABLE `loai_cay_trong` (
  `LCT_MA` int(11) NOT NULL,
  `LCT_TEN` varchar(100) NOT NULL,
  `LCT_MOTA` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguon_hang`
--

CREATE TABLE `nguon_hang` (
  `NH_MA` int(11) NOT NULL,
  `NH_TEN` varchar(150) NOT NULL,
  `NH_MOTA` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nguon_hang`
--

INSERT INTO `nguon_hang` (`NH_MA`, `NH_TEN`, `NH_MOTA`) VALUES
(1, 'Công ty Phân bón Việt Nam', 'Công ty chuyên sản xuất và phân phối các loại phân bón, thuốc kích rễ và các sản phẩm nông nghiệp tại Việt Nam'),
(2, 'Công ty cổ phần Vi Sinh Ứng Dụng', 'Công ty chuyên về sản xuất các chế phẩm vi sinh phục vụ nông nghiệp và xử lý môi trường'),
(3, 'Trang trại dê chuyên nghiệp', 'Các trang trại nuôi dê lớn chuyên sản xuất phân dê chất lượng cao đã qua xử lý'),
(4, 'Công ty CP Cây Trồng Bình Chánh', 'Công ty chuyên sản xuất các sản phẩm kích thích sinh trưởng và phân bón cho cây trồng'),
(5, 'Nhà phân phối Growmore Hoa Kỳ', 'Đại lý phân phối sản phẩm Growmore có xuất xứ từ Hoa Kỳ tại Việt Nam'),
(6, 'Công ty Hạt Giống Việt', 'Công ty chuyên sản xuất hạt giống, phân bón hữu cơ và các sản phẩm bảo vệ thực vật'),
(7, 'Syngenta', 'Tập đoàn đa quốc gia chuyên về nông nghiệp, sản xuất thuốc bảo vệ thực vật và hạt giống'),
(8, 'Công ty Phân bón Grow More', 'Công ty chuyên sản xuất và phân phối các loại phân bón và thuốc bảo vệ thực vật');

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
  `NV_MATKHAU` varchar(50) NOT NULL,
  `NV_NGAYTUYEN` datetime DEFAULT NULL,
  `NV_AVATAR` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nhan_vien`
--

INSERT INTO `nhan_vien` (`NV_MA`, `CV_MA`, `NV_TEN`, `NV_DIACHI`, `NV_SDT`, `NV_EMAIL`, `NV_GIOITINH`, `NV_NGAYSINH`, `NV_TENDANGNHAP`, `NV_MATKHAU`, `NV_NGAYTUYEN`, `NV_AVATAR`) VALUES
(1, 1, 'Nguyễn Văn A', '123 Đường Lê Lợi, Quận 1, TP.HCM', '0901234567', 'a.nguyen@example.com', 'N', '1990-05-20 00:00:00', 'nguyenvana', 'matkhau123', '2020-01-01 00:00:00', 'a.jpg'),
(2, 2, 'Trần Thị B', '456 Đường Hai Bà Trưng, Quận 3, TP.HCM', '0912345678', 'b.tran@example.com', 'N', '1992-08-15 00:00:00', 'tranthib', 'matkhau456', '2021-03-15 00:00:00', 'b.png'),
(3, 1, 'Lê Minh C', '789 Đường Nguyễn Huệ, Quận 1, TP.HCM', '0923456789', 'c.le@example.com', 'N', '1988-12-10 00:00:00', 'leminhc', 'matkhau789', '2019-07-10 00:00:00', 'c.jpg');

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
(5, 'Ninja Van', 'Giao hàng thương mại điện tử, hỗ trợ thu hộ (COD)');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phieu_nhap`
--

CREATE TABLE `phieu_nhap` (
  `PN_STT` int(11) NOT NULL,
  `NV_MA` int(11) NOT NULL,
  `PN_NGAYNHAP` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(3, 'Thẻ Visa/Mastercard/Amex'),
(4, 'Thanh toán qua Momo');

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
  `SP_THANHPHAN` text DEFAULT NULL,
  `SP_HUONGDANSUDUNG` text DEFAULT NULL,
  `SP_DONVITINH` varchar(20) NOT NULL,
  `SP_NHASANXUAT` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `san_pham`
--

INSERT INTO `san_pham` (`SP_MA`, `NH_MA`, `DM_MA`, `SP_TEN`, `SP_DONGIA`, `SP_SOLUONGTON`, `SP_HINHANH`, `SP_MOTA`, `SP_THANHPHAN`, `SP_HUONGDANSUDUNG`, `SP_DONVITINH`, `SP_NHASANXUAT`) VALUES
(1, 1, 5, 'N3M kích rễ – Kích rễ cực mạnh cho cây ăn trái, kiểng, công nghiệp', 26000, 989, 'kich_re.jpg', 'Phân bón lá ra rễ cực mạnh N3M là sản phẩm được rất nhiều người ưa chuộng sử dụng vì giá thành rẻ, công dụng kích thích ra rễ được dùng trên nhiều loại cây trồng từ rau, cây ăn quả cho đến các loại hoa hồng – hoa kiểng.\r\n\r\n', 'N 11%, P2O53%, K2O5 2,5%, B, Cu, Zn…', 'Giâm, chiết cành (20gr/L nước): nhúng cành muốn giâm vào dung dịch thuốc 5-10p, sau đó giâm vào đất; bôi trực tiếp vào vết khoanh vỏ phía trên ngọn cành khi bỏ bầu.\r\n\r\n• Tưới gốc (20gr/10L nước): tưới đều quanh gốc cây để tăng cường và phục hồi bộ rễ bị suy yếu do xử lý thuốc hoặc sau khi ngập úng hay hạn, sau đó 7 ngày phun 1 lần\r\n\r\n• Phun trên lá (10gr/10L nước): khi ra đọt, khi cây ra hoa và trái non, làm cây đâm tược mới, chống rụng hoa, tăng đậu trái, sau đó cách 7 ngày phun 1 lần.\r\n\r\n• Ngâm hạt giống (10gr/10L nước): ngâm hạt giống trong 24h, sau đó vớt ra ủ bình thường.', 'kg', 'Công ty Phân bón Việt Nam'),
(2, 1, 1, 'Chế phẩm EMUNIV (200g) – Ủ phân và rác hữu cơ hiệu quả, nhanh hoai mục', 35000, 458, 'emu.jpg', 'Chế phẩm ủ phân và rác thải Emuniv là chế phẩm vi sinh EM xử lý phân gia súc gia cầm, rác thải, phế thải nông nghiệp làm phân bón hữu cơ và xử lý ô nhiễm môi trường.', '• Bacillus subtillis: 10^8CFU/g.\r\n\r\n• Bacillus licheniformis: 10^7CFU/g.\r\n\r\n• Bacillus  megaterium: 10^7CFU/g.\r\n\r\n• Lactobacillus acidopphilus: 10^8CFU/g.\r\n\r\n• Lactobacillus plantarum: 10^8CFU/g.\r\n\r\n• Streptomyces sp: 10^7CFU/g.\r\n\r\n• Saccharomyces cereviseae: 10^7CFU/g.', '• Hòa 1 gói vào nước sạch, tưới cho 1 tấn nguyên liệu, đạt độ ẩm 45-50% ủ đống trong 20-30 ngày.\r\n\r\n• Xử lí nước thải: dùng từ 2-4gram chế phẩm/m3/ ngày đêm, đổ vào bể hiếm khí sục 8-10h/ngày đêm...\r\n\r\nBảo quản: để nơi khô mát trong vòng 12 tháng kể từ ngày sản xuất.', 'g', 'Công ty cổ phần Vi Sinh Ứng Dụng'),
(3, 1, 1, 'Phân dê qua xử lý hàng chuẩn ( chuyên cho lan và hoa hồng ) - 1 túi', 22000, 793, 'phande.jpg', 'Phân dê là loại sản phẩm phân chuồng được thu gom từ các trang trại nuôi dê lớn. Phân dê sẽ được xử lý mầm bệnh bằng vi sinh, giảm ẩm và tiến hành đóng gói dạng thương mại để xuất đi. Phân dê có hàm lượng N-P-K khá cân đối (3%-1%-2%), cùng với đó là các khoáng trung vi lượng có hàm lượng cao phải kể đến là Canxi, Cu, Fe,... Phân dê được giới trồng hoa hồng và các loại hoa kiểng ưa chuộng vì tính tiện lợi, chất lượng các thành phần dinh dưỡng và không có mùi hôi - rất dễ sử dụng.\r\n\r\n• Trong phân dê có thành phần cải tạo kết cấu đất, giúp duy trì và làm phong phú cho khu vườn. Phân dê có khả năng cải thiện kết cấu đất để sử dụng nước hiệu quả hơn. Đặc biệt, cho phép nhiều oxy lưu thông đến bộ rễ, kết hợp với N trong phân giúp cho quá trình cố định đạm được diễn ra. Do đó, có thể làm tăng cường năng suất cây trồng lên đến 20%. ', 'phân dê', '• Bón cách gốc cây kiểng 2-4cm.\r\n\r\n• Tưới đều nước khi bón phân.\r\n\r\n• Bón ít nhất 1 tháng/lần với cây đang trưởng thành và cây ra hoa, cây già cỗi.', 'kg', 'Phân dê được sản xuất tại các trang trại dê lớn ở '),
(4, 2, 5, 'Bimix Super Root (20ml)  –  Phát triển mạnh bộ rễ, kích thích cành chiết, cành giâm', 6000, 575, 'bemix.jpg', 'Sản phẩm thuốc kích rễ bimix super root (20ml) được sản xuất với công nghệ tiên tiến từ các nguyên liệu chất lượng. Sản phẩm được dùng để kích thích ra rễ cây trong phương pháp giâm cành và chiết cành, phục hồi rễ sau thời kì ngập úng.', '• Acid humic đậm đặc: 9%\r\n\r\n• Acid Amin: 0.12%\r\n\r\n• Nito: 6%\r\n\r\n• Photpho: 8%\r\n\r\n• Kali: 6%\r\n\r\n• Chelate Cu, Fe, Zn, B:> 1000 ppm\r\n\r\n• Vitamin và một số chất điều hòa sinh trưởng thực vật khác\r\n\r\n', 'Dùng cho cây ăn trái, cây công nghiệp, vườn ươm, cây cảnh\r\n\r\n• Lúa: Pha 10 - 20ml/ 8 lít nước (1 lít/ 600 lít nước/ ha/ lần)\r\n\r\n• Cây ăn trái, cây công nghiệp:\r\n\r\nCây con: 10 -20ml/ 16 lít nước (0.4 lít/ 600 lít nước/ ha/ lần)\r\n\r\nCây trưởng thành: 20 - 25ml/ 16 lít nước (1 lít/ 600 lít nước/ ha/ lần)\r\n\r\n• Rau màu, hoa kiểng:  10 -15ml/ 16 lít nước (0.4 lít/ 600 lít nước/ ha/ lần)\r\n\r\n• Giâm chiết cành: Pha dung dịch 20ml/ 5 lít nước: ngâm cành giâm, cành ghép, hạt giống từ 2-3 giờ (hoặc bôi chỗ ghép, gốc chiết, gốc cành giâm.', 'kg', 'Công ty CP Cây Trồng Bình Chánh'),
(5, 3, 5, 'Axit humic dạng lỏng 322 Growmore (235ml) - Kích rễ và chống ngộ độc hữu cơ cho cây', 45000, 388, 'humic.jpg', 'Axit humic dạng lỏng 322 là dòng sản phẩm hữu cơ, được sử dụng cho cây trồng để cung cấp chất hữu cơ, khoáng,.. Axit humic có chức năng chính là kích thích bộ rễ phát triển, giúp rễ khỏe mạnh, kháng phèn và chống ngộ độc hữu cơ. Bên cạnh đó, Axit humic tăng khả năng đậu quả, chống rụng bông, bông to trái lớn và tăng năng suất cây trồng.', '• Axit humic: 6.3%\r\n\r\n• Axit fulvic: 1.2%\r\n\r\n• Nts: 3%\r\n\r\n• P2O5hh: 2%\r\n\r\n• K2Ohh: 2%\r\n\r\n• Fe: 1000 ppm\r\n\r\n• Zn: 500ppm\r\n\r\n• Cu: 500ppm\r\n\r\n• Mn: 500ppm\r\n\r\n• pHH2O: 5\r\n\r\n• Tỷ trọng: 1.1', '• Rau các loại: cà chua, dưa hấu, dưa leo, bầu, bí, khổ qua, ớt, bắp cải, khoai tây, dâu, xà lách, su hào, dền, dứa, gừng, bó xôi, cà rốt, khoai, lang, các loại đậu.\r\n\r\n• Cây ăn trái: thanh long, nhãn, chôm chôm, sầu riêng, mãng cầu, nho, táo, đu đủ, cam, quýt, bưởi, xoài, ổi, măng cụt, sơ ri, vải, hồng, đào, mận, na, saboche, dâu, khóm...\r\n\r\n• Cây công nghiệp: trà, cà phê, thuốc lá, cao su, bông vải, mía, bắp, tiêu, dâu tằm, điều.\r\n\r\n• Các loại bonsai, bông hoa cây cảnh: phong lan, hoa hồng, hoa lài, cúc, vạn thọ, huệ, mai, tulip, cẩm chướng...\r\n\r\n• Pha 20cc - 30cc / 10 lít nước hoặc can 1 lít / 2 phuy (phuy 200 lít). Phun định kỳ 10 - 15 ngày/ lần.\r\n\r\n• Đối với cây lúa phun định kỳ 7 ngày/ lần, vào 3 thời kỳ cơ bản lúc lúa đẻ nhánh, trước khi trổ và thời kỳ nuôi bông, nuôi hạt.', 'ml', 'Sản phẩm có xuất xứ từ Hoa Kỳ, đucợ phân phối bởi '),
(6, 1, 1, 'Phân bón kiểng lá viên nén hữu cơ 100% SFARM - Túi 500 gram', 19000, 1995, 'sfram.jpg', 'Phân bón hữu cơ chuyên cho cây trong nhà SFARM là dòng phân bón dạng viên tan chậm được cải tiến chuyên biệt cho cây trong nhà. Sản phẩm là sự cải tiến và kết hợp hoàn hảo giữa phân trùn quế và thành phần hữu cơ khác. Viên nén có màu nâu đen, nhẵn bóng cho thời gian sử dụng kéo dài 30 – 45 ngày.', ' Phân trùn quế và thành phần hữu cơ khác', 'Bón định kỳ khoảng 1 lần/tháng với lượng 50g cho chậu đường kính 30cm.\r\nRải trực tiếp phân lên bề mặt chậu, xung quanh gốc cây theo đường kính tán. Sau đó, đảo nhẹ lớp đất mặt và tưới nước cho cây', 'gói', 'Công ty Hạt Giống Việt'),
(7, 1, 3, 'ATONIK (10ml) – Kích rễ, bật mầm mạnh cho cây trồng và hoa kiểng', 10500, 1496, 'atonik.jpg', 'Thuốc kích thích sinh trưởng cây trồng và hoa kiểng ATONIK là thuốc kích thích sinh trưởng cây trồng thế hệ mới. Cũng như các loại vitamin, Atonik làm tăng khả năng sinh trưởng đồng thời giúp cây trồng tránh khỏi những ảnh hưởng xấu do những điều kiện sinh trưởng không thuận lợi gây ra.', 'Sodium -  Nitrogualacolate 0,03%\r\n\r\nSodium - Nitrophenolate 0,06%\r\n\r\nSodium - P - Nitrophenolate 0,09%', '+ Ngâm hạt: Kích thích sự nảy mầm và ra rễ, phá vỡ trạng thái ngủ của hạt giống\r\n\r\n+ Phun tưới trên ruộng mạ, cây con: Làm cho cây mạ phát triển, phục hồi nhanh chóng sau cấy trồng\r\n\r\n+ Phun qua lá: Kích thích sự sinh trưởng phát triển, tạo điều kiện cho quá trình trao đổi chất của cây, giúp cây sớm thu hoạch với năng suất cao, chất lượng tốt.', 'gói', 'Công ty Hạt Giống Việt'),
(8, 4, 4, 'COC85 (20g) - Ngừa bệnh rỉ sắt, đốm đen cho cây trồng, đặc hiệu cho hoa kiểng', 220000, 12988, 'coc85.jpg', 'Thuốc trừ bệnh COC85 được sản xuất từ ion gốc Đồng (Cu2+), dạng bột mịn, loang đều và bám dính tốt. Sản phẩm được dùng để phòng trừ nấm bệnh, rỉ sắt, đốm đen trên các loại cây trồng, cây kiểng. Đặc biệt là hoa hồng, cây mai, đào.\r\n\r\n', '• Đồng Oxycloride: 85w/w.\r\n\r\n• Phụ gia: 15 w/w.', '• Pha loãng khoảng 10 -20 gram cho bình 8 - 10 lít, phun khi cây mới chớm bệnh. Mỗi 14 ngày nên phun để phòng trừ bệnh. \r\n\r\n• Thời gian cách ly: 7 ngày.', 'g', 'Syngenta'),
(9, 5, 4, 'Thuốc trừ bệnh BELLKUTE 40WP đặc trị bệnh phấn trắng trên hoa hồng - Gói 20 gram', 35000, 700, 'bellkute.jpg', 'Mô tả sản phẩm: Thuốc trừ bệnh Bellkute 40WP là thuốc trừ bệnh phổ rộng, chuyên trị bệnh phấn trắng trên hoa hồng, sương mai trên cây bầu bí, thán thư trên ớt,...Phòng trừ bệnh do nấm như: đốm vàng, đốm nâu, đốm đen, gỉ sắt, thối nhũn, héo củ, vàng lá,...trên cây hoa mai, hoa lan và cây cảnh. ', ' Iminoctadine: .............40% w/w.', '• Cây trồng: Hoa Hồng, bệnh hại (phấn trắng). \r\n\r\n• Liều lượng: 0,5kg/ha (10-13 gr/ bình 16 lít, 16-21 gr/bình 25 lít). \r\n\r\n• Phun ướt đều tán lá cây trồng và phun thuốc khi bệnh chớm xuất hiện. \r\n\r\n• Phun lặp lại 7-10 ngày nếu áp lực bệnh cao. \r\n\r\n• Lượng nước phun: 600-800 lít/ha. \r\n\r\n• Thời gian cách ly: Ngưng phun thuốc 7 ngày trước khi thu hoạch. ', 'ml', 'Công ty Phân bón Grow More'),
(10, 1, 6, 'Hoạt chất sinh học Neem Chito - Phòng trừ nhện đỏ và bọ trĩ trên cây hoa hồng', 43000, 2497, 'chito.jpg', 'Hoạt chất sinh học Neem Chito phòng trừ hiệu quả, không gây kháng thuốc đối với nhện đỏ và bọ trĩ chích hút trên cây hoa hồng một cách an toàn, thân thiện nhất. Ngoài ra, Neem Chito còn phòng ngừa được rầy, rệp và sâu cuốn lá, đồng thời tăng sức đề kháng của cây hồng chống lại các tác nhân gây bệnh, kích thích cây tăng trưởng, đâm chồi, nở hoa, hoa to và bền màu.', '• Potassium Linear AlkylBenzene Sulfonate: 9%\r\n\r\n• Chitosan được chiết xuất từ vỏ tôm, vỏ cua.\r\n\r\n• Tinh dầu Neem chưa hoạt chất Azadirachtin từ cây neem Ấn Độ.\r\n\r\n• Chất bám dính sinh học hữu cơ.\r\n\r\n', '• Pha 10ml - 15ml (1/2 - 1 nắp)/ bình 20 lít nước (100ml - 150ml/ phuy 200 lít nước), sử dụng cho các loại cây trồng.\r\n\r\n• Phun đều lên tán cây, cả mặt trên và mặt dưới lá.\r\n\r\n• Hòa chung phân bón lá và nông dược, phun đều lên tán cây.\r\n\r\n• Phu định kỳ 10-15 ngày/ lần hoặc phun theo kỳ phun thuốc.', 'gói', 'Công ty Hạt Giống Việt'),
(11, 4, 6, 'Thuốc trừ ốc dạng phun HELIX 500WP - Chai 50 gram', 23000, 345, 'helix.jpg', 'Thuốc trừ ốc dạng phun Helix 500wp là thuốc đặc trị ốc hiệu quả cao. Đặc biệt chuyên trị ốc gây hại trên cây cảnh, lúa. ', '• Metaldehyde...500g/kg.\r\n\r\n• Phụ gia đặc biệt vừa đủ 1kg. \r\n\r\n', '• Đối với cây cảnh, rau màu, cây ăn quả: \r\n\r\n+ Ốc sên: \r\n\r\n- Pha 50g/bình 16 lít, lượng nước phun 320 lít/ha. \r\n\r\n- Lượng nước phun 320 lít/ha. \r\n\r\n- Phun lúc trời mát, theo đường di chuyển của ốc. \r\n\r\n• Đối với lúa\r\n\r\n+ Ốc bươu vàng: \r\n\r\n- Liều lượng: 1-1.2kg/ ha. \r\n\r\n- Lượng nước phun 320 lít/ha. \r\n\r\n- Phun thuốc khi ruộng có nước 1-5cm. \r\n\r\n• Thời gian cách ly: không xác định. ', 'ml', 'Syngenta'),
(12, 1, 6, 'NeemNim Ấn Độ (100ml) – Phòng ngừa sâu bệnh, rệp sáp, sâu xanh, bọ cánh tơ, sâu tơ', 160000, 885, 'neemim.jpg', 'NeemNim Ấn Độ là sản phẩm thuốc trừ sâu được chiết xuất hoàn toàn từ thảo mộc thiên nhiên giúp phòng trừ nhiều loại sâu hại như đục lá, rệp sáp, cánh lụa, sâu tơ, sâu xanh da láng… vô cùng an toàn cho người sử dụng và thiên nhiên.', 'Azadirachtin: 0,3% khối lượng.', '• Pha 30-50ml cho bình 16 lít.\r\n\r\n• Lượng nước phun 400-600 lít/ ha.\r\n\r\n• Phun ướt đều thân lá, lượng nước phun tùy theo cây trồng và thời gian sinh trưởng. Phun phòng ngừa và trị sau khi sâu mới xuất hiện,nếu sâu hại nặng nên phun lại lần 2 sau  7 ngày.\r\n\r\n• Không phun thuốc khi cây đang ra hoa vì sẽ xua đuổi côn trùng có ích làm giảm khả năng thụ phấn của cây.', 'kg', 'Công ty Phân bón Việt Nam');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `san_pham_cay_trong`
--

CREATE TABLE `san_pham_cay_trong` (
  `SP_MA` int(11) NOT NULL,
  `LCT_MA` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thu_vien_anh`
--

CREATE TABLE `thu_vien_anh` (
  `SP_MA` int(11) NOT NULL,
  `id_anh` int(11) NOT NULL,
  `ten_hinh` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(4, 'Đã hủy', '2025-06-18 13:44:27');

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
-- Chỉ mục cho bảng `danh_muc`
--
ALTER TABLE `danh_muc`
  ADD PRIMARY KEY (`DM_MA`);

--
-- Chỉ mục cho bảng `dia_chi_giao_hang`
--
ALTER TABLE `dia_chi_giao_hang`
  ADD PRIMARY KEY (`DCGH_MA`),
  ADD KEY `fk_dcgh_hd` (`DH_MA`);

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
-- Chỉ mục cho bảng `hoa_don`
--
ALTER TABLE `hoa_don`
  ADD PRIMARY KEY (`HD_STT`),
  ADD KEY `FK_HOA_DON_DO_NV_LAP_NHAN_VI` (`NV_MA`),
  ADD KEY `FK_HOA_DON_KM` (`KM_MA`),
  ADD KEY `FK_HOA_DON_GH` (`GH_MA`),
  ADD KEY `idx_tt_ma` (`TT_MA`),
  ADD KEY `idx_dvc_ma` (`DVC_MA`),
  ADD KEY `idx_pttt_ma` (`PTTT_MA`),
  ADD KEY `idx_kh_ma` (`KH_MA`);

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
-- Chỉ mục cho bảng `nha_van_chuyen`
--
ALTER TABLE `nha_van_chuyen`
  ADD PRIMARY KEY (`NVC_MA`);

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
-- Chỉ mục cho bảng `thu_vien_anh`
--
ALTER TABLE `thu_vien_anh`
  ADD PRIMARY KEY (`SP_MA`,`id_anh`);

--
-- Chỉ mục cho bảng `trang_thai`
--
ALTER TABLE `trang_thai`
  ADD PRIMARY KEY (`TT_MA`);

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
  MODIFY `CTHD_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT cho bảng `dia_chi_giao_hang`
--
ALTER TABLE `dia_chi_giao_hang`
  MODIFY `DCGH_MA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT cho bảng `don_van_chuyen`
--
ALTER TABLE `don_van_chuyen`
  MODIFY `DVC_MA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT cho bảng `gio_hang`
--
ALTER TABLE `gio_hang`
  MODIFY `GH_MA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `hoa_don`
--
ALTER TABLE `hoa_don`
  MODIFY `HD_STT` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT cho bảng `khach_hang`
--
ALTER TABLE `khach_hang`
  MODIFY `KH_MA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `khuyen_mai_san_pham`
--
ALTER TABLE `khuyen_mai_san_pham`
  MODIFY `KMSP_MA` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Mã khuyến mãi sản phẩm', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `selected_cart_items`
--
ALTER TABLE `selected_cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

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
-- Các ràng buộc cho bảng `dia_chi_giao_hang`
--
ALTER TABLE `dia_chi_giao_hang`
  ADD CONSTRAINT `fk_dcgh_hd` FOREIGN KEY (`DH_MA`) REFERENCES `hoa_don` (`HD_STT`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Các ràng buộc cho bảng `phieu_nhap`
--
ALTER TABLE `phieu_nhap`
  ADD CONSTRAINT `FK_DONHANVIENLAP` FOREIGN KEY (`NV_MA`) REFERENCES `nhan_vien` (`NV_MA`);

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
-- Các ràng buộc cho bảng `thu_vien_anh`
--
ALTER TABLE `thu_vien_anh`
  ADD CONSTRAINT `FK_THU_VIEN_RELATIONS_SAN_PHA` FOREIGN KEY (`SP_MA`) REFERENCES `san_pham` (`SP_MA`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
