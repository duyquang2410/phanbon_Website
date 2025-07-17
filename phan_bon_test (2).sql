-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th7 09, 2025 lúc 09:40 AM
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
-- Cấu trúc bảng cho bảng `chatbot_context`
--

CREATE TABLE `chatbot_context` (
  `id` int(11) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `context_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`context_data`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chatbot_conversations`
--

CREATE TABLE `chatbot_conversations` (
  `id` int(11) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `user_message` text NOT NULL,
  `bot_response` text NOT NULL,
  `intent` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chatbot_crop_products`
--

CREATE TABLE `chatbot_crop_products` (
  `id` int(11) NOT NULL,
  `crop_type` varchar(255) NOT NULL,
  `growth_stage` varchar(100) DEFAULT NULL,
  `recommended_products` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chatbot_crop_products`
--

INSERT INTO `chatbot_crop_products` (`id`, `crop_type`, `growth_stage`, `recommended_products`, `created_at`) VALUES
(1, 'lúa', 'gieo sa', 'Phân bón lá ATONIK PRO: Kích thích nảy mầm, phát triển rễ\nPhân bón NPK 20-20-15: Cung cấp dinh dưỡng đầy đủ', '2025-07-03 06:13:52'),
(2, 'lúa', 'de nhanh', 'Phân bón NPK 16-16-8: Thúc đẩy phát triển chồi\nPhân bón lá KOMIX: Tăng cường dinh dưỡng qua lá', '2025-07-03 06:13:52'),
(3, 'cà phê', 'ra hoa', 'Phân bón NPK 15-15-15: Cân bằng dinh dưỡng\nPhân bón lá COFFEE-MAX: Kích thích ra hoa, đậu trái', '2025-07-03 06:13:52'),
(4, 'tiêu', 'sinh truong', 'Phân bón hữu cơ vi sinh: Cải tạo đất, phát triển rễ\nPhân bón NPK 13-13-13: Dinh dưỡng cân đối', '2025-07-03 06:13:52'),
(5, 'lúa', 'gieo sa', 'Phân bón lá ATONIK PRO: Kích thích nảy mầm, phát triển rễ\nPhân bón NPK 20-20-15: Cung cấp dinh dưỡng đầy đủ', '2025-07-03 06:14:02'),
(6, 'lúa', 'de nhanh', 'Phân bón NPK 16-16-8: Thúc đẩy phát triển chồi\nPhân bón lá KOMIX: Tăng cường dinh dưỡng qua lá', '2025-07-03 06:14:02'),
(7, 'cà phê', 'ra hoa', 'Phân bón NPK 15-15-15: Cân bằng dinh dưỡng\nPhân bón lá COFFEE-MAX: Kích thích ra hoa, đậu trái', '2025-07-03 06:14:02'),
(8, 'tiêu', 'sinh truong', 'Phân bón hữu cơ vi sinh: Cải tạo đất, phát triển rễ\nPhân bón NPK 13-13-13: Dinh dưỡng cân đối', '2025-07-03 06:14:02'),
(9, 'lúa', 'gieo sa', 'Phân bón lá ATONIK PRO: Kích thích nảy mầm, phát triển rễ\nPhân bón NPK 20-20-15: Cung cấp dinh dưỡng đầy đủ', '2025-07-03 06:17:29'),
(10, 'lúa', 'de nhanh', 'Phân bón NPK 16-16-8: Thúc đẩy phát triển chồi\nPhân bón lá KOMIX: Tăng cường dinh dưỡng qua lá', '2025-07-03 06:17:29'),
(11, 'cà phê', 'ra hoa', 'Phân bón NPK 15-15-15: Cân bằng dinh dưỡng\nPhân bón lá COFFEE-MAX: Kích thích ra hoa, đậu trái', '2025-07-03 06:17:29'),
(12, 'tiêu', 'sinh truong', 'Phân bón hữu cơ vi sinh: Cải tạo đất, phát triển rễ\nPhân bón NPK 13-13-13: Dinh dưỡng cân đối', '2025-07-03 06:17:29');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chatbot_disease_products`
--

CREATE TABLE `chatbot_disease_products` (
  `id` int(11) NOT NULL,
  `disease_name` varchar(255) NOT NULL,
  `symptoms` text NOT NULL,
  `recommended_products` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chatbot_disease_products`
--

INSERT INTO `chatbot_disease_products` (`id`, `disease_name`, `symptoms`, `recommended_products`, `created_at`) VALUES
(1, 'vang_la', 'Lá cây chuyển màu vàng, sinh trưởng kém', 'Phân bón lá CHELATE Fe: Bổ sung sắt\nThuốc bổ sung vi lượng MICROMAX', '2025-07-03 06:13:53'),
(2, 'thoi_re', 'Rễ cây bị thối, cây héo dần', 'Thuốc trừ bệnh ANVIL: Phòng trị nấm gây hại\nThuốc sinh học TRICHODERMA: Xử lý đất', '2025-07-03 06:13:53'),
(3, 'dom_la', 'Trên lá xuất hiện các đốm nâu, đen', 'Thuốc trừ bệnh SCORE: Trị bệnh đốm lá\nThuốc đồng COPPER B: Phòng trị nấm', '2025-07-03 06:13:53'),
(4, 'vang_la', 'Lá cây chuyển màu vàng, sinh trưởng kém', 'Phân bón lá CHELATE Fe: Bổ sung sắt\nThuốc bổ sung vi lượng MICROMAX', '2025-07-03 06:14:02'),
(5, 'thoi_re', 'Rễ cây bị thối, cây héo dần', 'Thuốc trừ bệnh ANVIL: Phòng trị nấm gây hại\nThuốc sinh học TRICHODERMA: Xử lý đất', '2025-07-03 06:14:02'),
(6, 'dom_la', 'Trên lá xuất hiện các đốm nâu, đen', 'Thuốc trừ bệnh SCORE: Trị bệnh đốm lá\nThuốc đồng COPPER B: Phòng trị nấm', '2025-07-03 06:14:02'),
(7, 'vang_la', 'Lá cây chuyển màu vàng, sinh trưởng kém', 'Phân bón lá CHELATE Fe: Bổ sung sắt\nThuốc bổ sung vi lượng MICROMAX', '2025-07-03 06:17:40'),
(8, 'thoi_re', 'Rễ cây bị thối, cây héo dần', 'Thuốc trừ bệnh ANVIL: Phòng trị nấm gây hại\nThuốc sinh học TRICHODERMA: Xử lý đất', '2025-07-03 06:17:40'),
(9, 'dom_la', 'Trên lá xuất hiện các đốm nâu, đen', 'Thuốc trừ bệnh SCORE: Trị bệnh đốm lá\nThuốc đồng COPPER B: Phòng trị nấm', '2025-07-03 06:17:40');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chatbot_responses`
--

CREATE TABLE `chatbot_responses` (
  `id` int(11) NOT NULL,
  `intent` varchar(100) NOT NULL,
  `keywords` text DEFAULT NULL,
  `response_template` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chatbot_responses`
--

INSERT INTO `chatbot_responses` (`id`, `intent`, `keywords`, `response_template`, `created_at`, `updated_at`) VALUES
(1, 'product_recommendation', 'phân bón,cây trồng', 'Dựa trên loại cây {crop_type} và giai đoạn {growth_stage}, tôi đề xuất sử dụng:\n{recommended_products}', '2025-07-03 10:04:51', '2025-07-03 10:04:51'),
(2, 'disease_diagnosis', 'bệnh,triệu chứng', 'Với triệu chứng {symptoms} trên cây {crop_type}, có thể là bệnh {disease_name}.\nGiải pháp đề xuất:\n{recommended_products}', '2025-07-03 10:04:51', '2025-07-03 10:04:51'),
(3, 'product_search', 'thông tin,sản phẩm', 'Thông tin về sản phẩm {product_name}:\nThành phần: {ingredients}\nCông dụng: {benefits}\nGiá bán: {price}', '2025-07-03 10:04:51', '2025-07-03 10:04:51'),
(4, 'usage_guide', 'hướng dẫn,sử dụng', 'Hướng dẫn sử dụng {product_name}:\nLiều lượng: {dosage}\nCách dùng: {usage_instructions}\nLưu ý: {notes}', '2025-07-03 10:04:51', '2025-07-03 10:04:51');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chat_history`
--

CREATE TABLE `chat_history` (
  `id` int(11) NOT NULL,
  `session_id` varchar(100) NOT NULL,
  `user_message` text DEFAULT NULL,
  `bot_message` text DEFAULT NULL,
  `intent` varchar(100) DEFAULT NULL,
  `parameters` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chat_history`
--

INSERT INTO `chat_history` (`id`, `session_id`, `user_message`, `bot_message`, `intent`, `parameters`, `created_at`) VALUES
(1, '68665836d8e86', 'Tôi muốn tìm phân bón cho cây lúa', 'Xin lỗi, đã có lỗi xảy ra khi tìm kiếm sản phẩm. Vui lòng thử lại sau.', 'product_recommendation', '{\"crop_type\":\"l\\u00faa\",\"growth_stage\":\"C\\u00e2y \\u0111ang \\u1edf giai \\u0111o\\u1ea1n sinh tr\\u01b0\\u1edfng n\\u00e0o?\"}', '2025-07-03 10:15:18'),
(2, '6866585be1136', 'Phân bón nào tốt cho cây cam?', 'Xin lỗi, đã có lỗi xảy ra khi tìm kiếm sản phẩm. Vui lòng thử lại sau.', 'product_recommendation', '{\"crop_type\":\"cam\",\"growth_stage\":\"C\\u00e2y \\u0111ang \\u1edf giai \\u0111o\\u1ea1n sinh tr\\u01b0\\u1edfng n\\u00e0o?\"}', '2025-07-03 10:15:55'),
(3, '686659c6826c5', 'phân bón nào tốt cho cây cam', 'Xin lỗi, đã có lỗi xảy ra khi tìm kiếm sản phẩm. Vui lòng thử lại sau.', 'product_recommendation', '{\"crop_type\":\"cam\",\"growth_stage\":\"C\\u00e2y \\u0111ang \\u1edf giai \\u0111o\\u1ea1n sinh tr\\u01b0\\u1edfng n\\u00e0o?\"}', '2025-07-03 10:21:58'),
(4, '68665cdee0566', 'phân bón nào tốt cho cây cam?', 'Xin lỗi, đã có lỗi xảy ra khi tìm kiếm sản phẩm. Vui lòng thử lại sau.', 'product_recommendation', '{\"crop_type\":\"cam\",\"growth_stage\":\"C\\u00e2y \\u0111ang \\u1edf giai \\u0111o\\u1ea1n sinh tr\\u01b0\\u1edfng n\\u00e0o?\"}', '2025-07-03 10:35:10'),
(5, '68665e0d8d8b0', 'phân bón nào tốt cho cây cam?', 'Xin lỗi, đã có lỗi xảy ra khi tìm kiếm sản phẩm. Vui lòng thử lại sau.', 'product_recommendation', '{\"crop_type\":\"cam\",\"growth_stage\":\"C\\u00e2y \\u0111ang \\u1edf giai \\u0111o\\u1ea1n sinh tr\\u01b0\\u1edfng n\\u00e0o?\"}', '2025-07-03 10:40:13'),
(6, '68665e34e6a7c', 'phân bón nào tốt cho cây cam?', 'Xin lỗi, đã có lỗi xảy ra khi tìm kiếm sản phẩm. Vui lòng thử lại sau.', 'product_recommendation', '{\"crop_type\":\"cam\",\"growth_stage\":\"C\\u00e2y \\u0111ang \\u1edf giai \\u0111o\\u1ea1n sinh tr\\u01b0\\u1edfng n\\u00e0o?\"}', '2025-07-03 10:40:52'),
(7, '68666080b2b75', 'Tôi muốn tìm phân bón cho cây lúa', 'Xin lỗi, đã có lỗi xảy ra khi tìm kiếm sản phẩm. Vui lòng thử lại sau.', 'product_recommendation', '{\"crop_type\":\"l\\u00faa\",\"growth_stage\":\"C\\u00e2y \\u0111ang \\u1edf giai \\u0111o\\u1ea1n sinh tr\\u01b0\\u1edfng n\\u00e0o?\"}', '2025-07-03 10:50:40'),
(8, '6866608d00a1b', 'Cây lúa bị vàng lá', 'Xin lỗi, tôi chưa hiểu rõ yêu cầu của bạn. Bạn có thể hỏi về:\n1. Thông tin về bệnh cây trồng\n2. Thông tin về sản phẩm\n3. Hướng dẫn sử dụng sản phẩm', 'disease_diagnosis', '{\"symptom\":\"v\\u00e0ng l\\u00e1\",\"crop\":\"\"}', '2025-07-03 10:50:53');

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
(2, 7, 10, 'cái', 0),
(4, 7, 6, 'cái', 1),
(5, 7, 9, 'cái', 1),
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
(39, 6, 40, 2, 19000, 'cái', NULL),
(40, 6, 52, 2, 19000, 'cái', NULL),
(41, 8, 53, 4, 220000, 'cái', NULL),
(42, 2, 54, 3, 35000, 'cái', NULL),
(43, 3, 55, 3, 22000, 'cái', NULL),
(44, 8, 56, 1, 220000, 'cái', NULL),
(45, 6, 57, 3, 19000, 'cái', NULL),
(46, 4, 58, 6, 6000, 'cái', NULL),
(49, 5, 61, 2, 45000, 'cái', NULL),
(50, 5, 61, 2, 45000, 'ml', NULL),
(51, 6, 62, 3, 19000, 'cái', NULL),
(52, 6, 62, 3, 19000, 'gói', NULL),
(53, 2, 63, 4, 35000, 'cái', NULL),
(54, 2, 63, 4, 35000, 'g', NULL),
(55, 3, 64, 4, 22000, 'cái', NULL),
(56, 3, 64, 4, 22000, 'kg', NULL),
(57, 10, 65, 4, 43000, 'cái', NULL),
(58, 10, 65, 4, 43000, 'gói', NULL),
(59, 1, 66, 4, 26000, 'cái', NULL),
(60, 1, 66, 4, 26000, 'kg', NULL),
(61, 6, 67, 5, 19000, 'cái', NULL),
(62, 6, 67, 5, 19000, 'gói', NULL),
(63, 9, 68, 3, 35000, 'cái', NULL),
(64, 9, 68, 3, 35000, 'ml', NULL),
(65, 3, 69, 3, 22000, 'cái', NULL),
(66, 3, 69, 3, 22000, 'kg', NULL),
(67, 3, 70, 4, 22000, 'cái', NULL),
(68, 3, 70, 4, 22000, 'kg', NULL),
(69, 11, 71, 3, 23000, 'cái', NULL),
(70, 11, 71, 3, 23000, 'ml', NULL),
(71, 4, 89, 6, 6000, '', ''),
(72, 4, 90, 5, 6000, '', ''),
(73, 12, 91, 3, 160000, '', ''),
(74, 6, 92, 5, 19000, '', ''),
(75, 3, 95, 7, 22000, '', ''),
(76, 2, 101, 4, 35000, '', ''),
(77, 6, 102, 6, 19000, '', ''),
(78, 5, 107, 8, 45000, NULL, NULL),
(81, 12, 110, 5, 160000, NULL, NULL),
(82, 2, 111, 4, 35000, '', ''),
(83, 4, 120, 2, 6000, '', ''),
(84, 1, 121, 6, 26000, NULL, NULL),
(85, 7, 122, 8, 10500, NULL, NULL),
(86, 4, 123, 3, 6000, NULL, NULL),
(87, 10, 124, 4, 43000, NULL, NULL),
(88, 4, 125, 5, 6000, '', ''),
(89, 8, 126, 3, 220000, NULL, NULL),
(90, 7, 127, 15, 10500, NULL, NULL),
(91, 4, 127, 8, 6000, NULL, NULL),
(92, 1, 127, 5, 26000, NULL, NULL),
(93, 12, 128, 7, 160000, NULL, NULL),
(94, 3, 129, 2, 22000, NULL, NULL),
(95, 3, 130, 3, 22000, NULL, NULL),
(96, 2, 131, 5, 35000, NULL, NULL),
(97, 6, 132, 8, 19000, NULL, NULL),
(98, 11, 133, 4, 23000, NULL, NULL),
(99, 6, 134, 4, 19000, '', ''),
(100, 2, 135, 3, 35000, NULL, NULL),
(101, 4, 136, 5, 6000, NULL, NULL),
(102, 8, 137, 3, 220000, NULL, NULL),
(103, 2, 138, 5, 35000, NULL, NULL),
(104, 2, 139, 9, 35000, NULL, NULL),
(105, 4, 140, 4, 6000, NULL, NULL),
(106, 2, 141, 5, 35000, NULL, NULL),
(107, 2, 145, 6, 35000, NULL, NULL),
(108, 5, 146, 9, 45000, NULL, NULL),
(109, 4, 147, 6, 6000, NULL, NULL);

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
(51, 71, '2', '34', '661', 'Cần thơ', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-06-24 09:40:28');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dia_chi_giao_hang_moi`
--

CREATE TABLE `dia_chi_giao_hang_moi` (
  `DC_MA` int(11) NOT NULL,
  `KH_MA` int(11) NOT NULL,
  `DC_DIACHI` varchar(255) NOT NULL,
  `DC_TINH` varchar(100) NOT NULL,
  `DC_TINH_ID` varchar(20) NOT NULL,
  `DC_HUYEN` varchar(100) NOT NULL,
  `DC_HUYEN_ID` varchar(20) NOT NULL,
  `DC_XA` varchar(100) NOT NULL,
  `DC_XA_ID` varchar(20) NOT NULL,
  `DC_MACDINH` tinyint(1) DEFAULT 0,
  `DC_TENNGUOINHAN` varchar(100) NOT NULL,
  `DC_SDT` varchar(15) NOT NULL,
  `DC_EMAIL` varchar(100) DEFAULT NULL,
  `DC_GHICHU` text DEFAULT NULL,
  `DC_NGAYTAO` timestamp NOT NULL DEFAULT current_timestamp(),
  `DC_NGAYCAPNHAT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `dia_chi_giao_hang_moi`
--

INSERT INTO `dia_chi_giao_hang_moi` (`DC_MA`, `KH_MA`, `DC_DIACHI`, `DC_TINH`, `DC_TINH_ID`, `DC_HUYEN`, `DC_HUYEN_ID`, `DC_XA`, `DC_XA_ID`, `DC_MACDINH`, `DC_TENNGUOINHAN`, `DC_SDT`, `DC_EMAIL`, `DC_GHICHU`, `DC_NGAYTAO`, `DC_NGAYCAPNHAT`) VALUES
(1, 2, 'Cần 444', 'Tỉnh Hòa Bình', '17', 'Huyện Lạc Sơn', '188', 'Xã Tự Do', '3246', 0, 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-06-23 07:06:44', '2025-07-08 05:43:57'),
(2, 2, '444', 'Tỉnh Đắk Nông', '48', 'Huyện Đắk Glong', '550', 'Xã Quảng Sơn', '9621', 0, 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', NULL, '2025-06-22 05:17:20', '2025-07-08 07:56:52'),
(3, 2, 'Cần thơ', 'Tỉnh Thái Nguyên', '19', 'Huyện Phú Bình', '206', 'Xã Tân Đức', '3550', 0, 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', NULL, '2025-06-22 04:44:49', '2025-07-08 05:43:57'),
(4, 2, '', 'Tỉnh Gia Lai', '63', 'Huyện Chư Păh', '704', 'Xã Nghĩa Hưng', '11581', 0, 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', '2025-06-22 03:11:04', '2025-07-08 05:43:57');

--
-- Bẫy `dia_chi_giao_hang_moi`
--
DELIMITER $$
CREATE TRIGGER `before_insert_address` BEFORE INSERT ON `dia_chi_giao_hang_moi` FOR EACH ROW BEGIN
    IF NEW.DC_MACDINH = 1 THEN
        SET @old_default = NULL;
        SELECT DC_MA INTO @old_default 
        FROM dia_chi_giao_hang_moi 
        WHERE KH_MA = NEW.KH_MA AND DC_MACDINH = 1 
        LIMIT 1;
        
        IF @old_default IS NOT NULL THEN
            UPDATE dia_chi_giao_hang_moi 
            SET DC_MACDINH = 0 
            WHERE DC_MA = @old_default;
        END IF;
    END IF;
END
$$
DELIMITER ;

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
('158', '14', 'Huyện Vân Đồn', 'Huyện'),
('159', '14', 'Huyện Tiên Yên', 'Huyện'),
('160', '14', 'Thành phố Hạ Long', 'Thành phố'),
('161', '14', 'Thành phố Cẩm Phả', 'Thành phố'),
('188', '17', 'Huyện Lạc Sơn', 'Huyện'),
('206', '19', 'Huyện Phú Bình', 'Huyện'),
('550', '48', 'Huyện Đắk Glong', 'Huyện'),
('704', '63', 'Huyện Chư Păh', 'Huyện');

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
(166, 1, '4444, XÃ ĐACHIA, HUYỆN BÙ GIA MẬP, Bình Phước', '2025-07-09 13:50:04', '2025-07-12 13:50:04');

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
  `HD_DIACHI` text DEFAULT NULL,
  `HD_TENNGUOINHAN` varchar(100) DEFAULT NULL,
  `HD_SDT` varchar(20) DEFAULT NULL,
  `HD_EMAIL` varchar(150) DEFAULT NULL,
  `HD_GHICHU` text DEFAULT NULL,
  `KH_MA` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `hoa_don`
--

INSERT INTO `hoa_don` (`HD_STT`, `TT_MA`, `DVC_MA`, `NV_MA`, `PTTT_MA`, `KM_MA`, `GH_MA`, `HD_NGAYLAP`, `HD_TONGTIEN`, `HD_PHISHIP`, `HD_LIDOHUY`, `HD_DIACHI`, `HD_TENNGUOINHAN`, `HD_SDT`, `HD_EMAIL`, `HD_GHICHU`, `KH_MA`) VALUES
(6, 1, 12, 1, 1, NULL, 12, '2025-06-18 16:47:56', 640000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 4),
(7, 1, 13, 1, 1, NULL, 12, '2025-06-18 16:48:33', 57000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 4),
(8, 1, 14, 1, 1, NULL, 12, '2025-06-18 16:49:59', 24000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 4),
(9, 1, 15, 1, 1, NULL, 7, '2025-06-19 09:43:24', 36000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(10, 1, 16, 1, 1, NULL, 7, '2025-06-19 10:09:36', 104000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(11, 1, 17, 1, 1, NULL, 7, '2025-06-19 10:43:08', 440000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(12, 1, 18, 1, 1, NULL, 7, '2025-06-19 10:47:03', 26000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(13, 1, 19, 1, 1, NULL, 7, '2025-06-19 11:06:36', 35000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(14, 1, 20, 1, 1, NULL, 7, '2025-06-19 11:21:31', 94500, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(15, 1, 21, 1, 1, NULL, 7, '2025-06-19 11:41:08', 153000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(16, 1, 22, 1, 1, 1, 7, '2025-06-19 11:45:56', 720000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(17, 1, 27, 1, 1, NULL, 7, '2025-06-22 09:47:32', 59000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(18, 1, 28, 1, 1, NULL, 7, '2025-06-22 10:11:04', 2305000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(20, 1, 32, 1, 2, NULL, 7, '2025-06-22 11:44:49', 59000, 35000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(21, 1, 33, 1, 3, NULL, 7, '2025-06-22 11:45:43', 215000, 35000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(22, 1, 34, 1, 1, NULL, 7, '2025-06-22 11:54:49', 123000, 35000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(23, 1, 35, 1, 1, NULL, 7, '2025-06-22 11:58:42', 175000, 35000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(24, 1, 36, 1, 1, NULL, 7, '2025-06-22 12:04:24', 139800, 34800, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(25, 1, 37, 1, 1, NULL, 7, '2025-06-22 12:10:10', 140000, 35000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(26, 1, 38, 1, 1, NULL, 7, '2025-06-22 12:17:20', 260000, 35000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(27, 1, 39, 1, 1, NULL, 7, '2025-06-22 12:22:15', 150000, 35000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(28, 1, 40, 1, 2, NULL, 7, '2025-06-22 12:25:57', 77000, 35000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(29, 1, 41, 1, 1, 1, 7, '2025-06-22 12:26:59', 161000, 35000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(32, 1, 44, 1, 1, 1, NULL, '2025-06-22 13:09:44', 129000, 52500, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(33, 1, 45, 1, 1, 1, NULL, '2025-06-22 13:23:09', 140000, 35000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(34, 1, 46, 1, 1, 1, NULL, '2025-06-22 13:27:39', 24000, 35000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(35, 1, 47, 1, 2, 1, NULL, '2025-06-22 13:59:23', 1332000, 40001, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(36, 1, 48, 1, 1, NULL, NULL, '2025-06-22 14:00:41', 105000, 16500, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(37, 1, 49, 1, 1, NULL, NULL, '2025-06-22 14:02:16', 880000, 34800, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(38, 1, 50, 1, 2, 4, NULL, '2025-06-22 14:05:46', 156000, 44000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(39, 1, 51, 1, 1, NULL, NULL, '2025-06-22 14:39:25', 105000, 33000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(40, 1, 52, 1, 1, NULL, NULL, '2025-06-22 15:05:21', 178000, 37000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(41, 1, 57, 1, 1, 1, NULL, '2025-06-23 13:23:16', 32000, 32000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(42, 1, 58, 1, 3, 1, NULL, '2025-06-23 13:26:38', 32000, 32000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(43, 1, 59, 1, 1, 1, NULL, '2025-06-23 13:30:46', 16500, 16500, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(44, 1, 60, 1, 1, 1, NULL, '2025-06-23 13:30:47', 16500, 16500, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(45, 1, 61, 1, 1, 1, NULL, '2025-06-23 13:37:11', 38400, 38400, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(47, 1, 63, 1, 1, 1, NULL, '2025-06-23 13:43:48', 216000, 216000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(48, 1, 64, 1, 1, 4, NULL, '2025-06-23 13:55:02', 30000, 30000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(49, 1, 65, 1, 1, NULL, NULL, '2025-06-23 14:02:15', 38400, 38400, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(50, 1, 66, 1, 1, NULL, NULL, '2025-06-23 14:06:44', 32000, 32000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(51, 1, 67, 1, 1, 2, NULL, '2025-06-23 14:09:27', 18400, 38400, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(52, 1, 68, 1, 2, 1, NULL, '2025-06-23 18:10:45', 70000, 32000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(53, 1, 69, 1, 1, 2, NULL, '2025-06-23 18:11:49', 896500, 16500, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(54, 1, 70, 1, 1, 2, NULL, '2025-06-23 18:15:44', 141001, 36001, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(55, 1, 71, 1, 1, 1, NULL, '2025-06-23 18:37:37', 122000, 56000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(56, 1, 72, 1, 1, 1, NULL, '2025-06-23 18:56:53', 250000, 30000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(57, 1, 73, 1, 2, NULL, NULL, '2025-06-23 18:59:37', 113000, 56000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(58, 1, 74, 1, 1, 4, NULL, '2025-06-24 15:13:46', 65000, 29000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(61, 1, 77, 1, 4, NULL, NULL, '2025-06-24 15:59:09', 128400, 38400, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(62, 1, 78, 1, 2, 1, NULL, '2025-06-24 16:00:36', 95400, 38400, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(63, 1, 79, 1, 2, 1, NULL, '2025-06-24 16:06:52', 172000, 32000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(64, 1, 80, 1, 2, 1, NULL, '2025-06-24 16:13:04', 126400, 38400, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(65, 1, 81, 1, 1, 1, NULL, '2025-06-24 16:15:11', 204000, 32000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(66, 1, 82, 1, 2, NULL, NULL, '2025-06-24 16:19:20', 136000, 32000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(67, 1, 83, 1, 2, 1, NULL, '2025-06-24 16:23:38', 127000, 32000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(68, 1, 84, 1, 4, 1, NULL, '2025-06-24 16:29:44', 149000, 44000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(69, 1, 85, 1, 2, NULL, NULL, '2025-06-24 16:31:35', 104400, 38400, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(70, 1, 86, 1, 4, NULL, NULL, '2025-06-24 16:33:29', 120000, 32000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(71, 1, 87, 1, 2, 1, NULL, '2025-06-24 16:40:28', 98000, 29000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(72, 1, 88, 1, 2, NULL, NULL, '2025-06-24 16:44:10', 672000, 32000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(74, 1, 90, 1, 4, NULL, NULL, '2025-06-24 16:49:49', 992000, 32000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(76, 1, 92, 1, 2, 1, NULL, '2025-06-24 16:54:08', 512000, 32000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(77, 1, 93, 1, 2, NULL, NULL, '2025-06-24 16:54:49', 998400, 38400, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(78, 1, 94, 1, 1, 1, NULL, '2025-06-24 17:05:05', 1309000, 29000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(79, 1, 95, 1, 1, 1, NULL, '2025-06-24 17:08:27', 1158400, 38400, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(81, 1, 97, 1, 1, NULL, NULL, '2025-06-24 17:10:11', 832000, 32000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(82, 2, 98, 1, 1, NULL, NULL, '2025-06-24 17:13:25', 56000, 32000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(83, 1, 99, 1, 2, 1, NULL, '2025-06-24 17:17:21', 742000, 32000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(85, 1, 101, 1, 1, 1, NULL, '2025-06-24 17:20:35', 74000, 32000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(86, 1, 102, 1, 1, NULL, NULL, '2025-06-24 17:26:28', 56000, 32000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(87, 1, 103, 1, 1, 1, NULL, '2025-06-24 17:28:38', 68000, 32000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(88, 1, 104, 1, 1, NULL, NULL, '2025-06-24 17:32:01', 91000, 43000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(89, 2, 105, 1, 1, NULL, NULL, '2025-06-24 17:34:58', 74400, 38400, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(90, 2, 106, 1, 1, 1, NULL, '2025-06-24 17:36:09', 62000, 32000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(91, 2, 107, 1, 1, 1, NULL, '2025-06-24 17:39:59', 512000, 32000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(92, 2, 108, 1, 1, 4, NULL, '2025-06-24 17:45:11', 133400, 38400, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(93, 1, 109, 1, 2, 4, NULL, '2025-06-24 17:49:04', 120000, 32000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(94, 1, 110, 1, 2, NULL, NULL, '2025-06-24 17:52:52', 164000, 32000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(95, 2, 111, 1, 1, 1, NULL, '2025-06-24 18:05:46', 183000, 29000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(101, 2, 117, 1, 1, 2, NULL, '2025-06-24 18:15:53', 172000, 32000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(102, 2, 118, 1, 1, 1, NULL, '2025-06-24 18:19:03', 146000, 32000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(106, 1, 123, 1, 3, NULL, NULL, '2025-06-24 19:02:02', 228400, 38400, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(107, 1, 124, 1, 3, 1, NULL, '2025-06-24 19:06:10', 392000, 32000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(110, 1, 127, 1, 3, 1, NULL, '2025-06-24 19:14:02', 829000, 29000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(111, 2, 128, 1, 1, 1, NULL, '2025-06-24 19:14:49', 172000, 32000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(116, 1, 133, 1, 2, 1, NULL, '2025-06-24 19:33:21', 120000, 32000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(120, 2, 137, 1, 1, 1, NULL, '2025-06-25 15:33:53', 44000, 32000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(121, 1, 138, 1, 3, NULL, NULL, '2025-06-26 23:03:41', 188000, 32000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(122, 1, 139, 1, 3, 1, NULL, '2025-06-27 00:02:45', 122400, 38400, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(123, 1, 140, 1, 3, 1, NULL, '2025-06-27 00:20:37', 56400, 38400, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(124, 1, 141, 1, 3, 1, NULL, '2025-06-28 09:28:27', 206800, 34800, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(125, 2, 142, 1, 1, 1, NULL, '2025-06-28 09:59:49', 68400, 38400, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(126, 1, 143, 1, 3, 5, NULL, '2025-07-05 14:53:41', 676500, 16500, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(127, 1, 144, 1, 3, 5, NULL, '2025-07-06 14:23:07', 424994, 89494, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(128, 1, 145, 1, 3, NULL, NULL, '2025-07-06 16:48:04', 1182000, 61996, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(129, 1, 146, 1, 3, 5, NULL, '2025-07-07 13:03:55', 81000, 37000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(130, 1, 147, 1, 3, 2, NULL, '2025-07-07 13:05:23', 107999, 41999, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(131, 1, 148, 1, 3, NULL, NULL, '2025-07-07 13:19:57', 226998, 51998, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(132, 1, 149, 1, 3, 2, NULL, '2025-07-07 15:51:26', 218996, 66996, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(133, 1, 150, 1, 3, 2, NULL, '2025-07-07 15:57:26', 138998, 46998, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(134, 2, 151, 1, 1, 2, NULL, '2025-07-07 15:57:53', 122998, 46998, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(135, 1, 152, 1, 3, 2, NULL, '2025-07-07 21:47:35', 146999, 41999, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(136, 1, 153, 1, 3, NULL, NULL, '2025-07-07 21:48:23', 61000, 31000, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(137, 1, 154, 1, 3, 5, NULL, '2025-07-07 22:02:05', 701999, 41999, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(138, 1, 155, 1, 3, NULL, NULL, '2025-07-07 22:13:43', 175000, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(139, 1, 156, 1, 3, 2, NULL, '2025-07-07 23:35:19', 386995, 71995, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(140, 1, 157, 1, 3, NULL, NULL, '2025-07-08 12:53:21', 250974, 226974, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(141, 1, 158, 1, 3, 2, NULL, '2025-07-08 13:06:47', 251994, 76994, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(145, 1, 162, 1, 3, NULL, NULL, '2025-07-08 15:26:10', 436974, 226974, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(146, 1, 163, 1, 3, NULL, NULL, '2025-07-09 13:20:18', 653976, 248976, NULL, NULL, NULL, NULL, NULL, NULL, 2),
(147, 1, 166, 1, 3, NULL, NULL, '2025-07-09 13:50:04', 284976, 248976, NULL, ', Xã Nghĩa Hưng, Huyện Chư Păh, Tỉnh Gia Lai', 'Quang Duy', '0793994771', 'duyquang2709pp@gmail.com', '', 2);

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
  `Code` varchar(50) NOT NULL,
  `KM_TGBD` datetime NOT NULL,
  `KM_TGKT` datetime NOT NULL,
  `KM_GIATRI` float NOT NULL,
  `hinh_thuc_km` enum('percent','fixed') NOT NULL DEFAULT 'fixed',
  `KM_DKSD` decimal(10,2) DEFAULT NULL COMMENT 'Giá trị đơn hàng tối thiểu',
  `KM_TRANGTHAI` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1: Kích hoạt, 0: Vô hiệu',
  `KM_MOTA` text DEFAULT NULL,
  `KM_NGAYTAO` timestamp NOT NULL DEFAULT current_timestamp(),
  `KM_NGAYCAPNHAT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `khuyen_mai`
--

INSERT INTO `khuyen_mai` (`KM_MA`, `Code`, `KM_TGBD`, `KM_TGKT`, `KM_GIATRI`, `hinh_thuc_km`, `KM_DKSD`, `KM_TRANGTHAI`, `KM_MOTA`, `KM_NGAYTAO`, `KM_NGAYCAPNHAT`) VALUES
(1, 'PHANBON10', '2025-06-01 00:00:00', '2025-06-30 23:59:59', 10, 'percent', 100000.00, 1, 'Giảm 10% cho đơn từ 100,000đ', '2025-07-07 06:47:54', '2025-07-07 06:54:53'),
(2, 'THUOC20K', '2025-06-15 00:00:00', '2025-07-15 23:59:59', 20000, 'fixed', 50000.00, 1, 'Giảm 20,000đ cho đơn từ 50,000đ', '2025-07-07 06:47:54', '2025-07-07 06:54:53'),
(3, 'MUA2TANG1', '2025-06-10 00:00:00', '2025-06-25 23:59:59', 0, 'fixed', 0.00, 1, 'Mua 2 tặng 1', '2025-07-07 06:47:54', '2025-07-07 06:54:53'),
(4, 'FARMER5', '2025-06-01 00:00:00', '2025-06-30 23:59:59', 5, 'percent', 200000.00, 1, 'Giảm 5% cho đơn từ 200,000đ', '2025-07-07 06:47:54', '2025-07-07 06:54:53'),
(5, 'PESTCONTROL', '2025-07-01 00:00:00', '2025-07-31 23:59:59', 15, 'percent', 150000.00, 1, 'Giảm 15% cho đơn từ 150,000đ', '2025-07-07 06:47:54', '2025-07-07 06:54:53'),
(6, 'TANGCHAU', '2025-06-01 00:00:00', '2025-06-30 23:59:59', 0, 'fixed', 0.00, 1, 'Tặng chậu khi mua sản phẩm', '2025-07-07 06:47:54', '2025-07-07 06:54:53');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khuyen_mai_backup`
--

CREATE TABLE `khuyen_mai_backup` (
  `KM_MA` int(11) NOT NULL DEFAULT 0,
  `Code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `KM_TGBD` datetime NOT NULL,
  `KM_TGKT` datetime NOT NULL,
  `KM_GIATRI` float NOT NULL,
  `hinh_thuc_km` enum('percent','fixed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fixed',
  `KM_DKSD` decimal(10,2) DEFAULT NULL COMMENT 'Giá trị đơn hàng tối thiểu',
  `KM_TRANGTHAI` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1: Kích hoạt, 0: Vô hiệu',
  `KM_MOTA` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `KM_NGAYTAO` timestamp NOT NULL DEFAULT current_timestamp(),
  `KM_NGAYCAPNHAT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `khuyen_mai_backup`
--

INSERT INTO `khuyen_mai_backup` (`KM_MA`, `Code`, `KM_TGBD`, `KM_TGKT`, `KM_GIATRI`, `hinh_thuc_km`, `KM_DKSD`, `KM_TRANGTHAI`, `KM_MOTA`, `KM_NGAYTAO`, `KM_NGAYCAPNHAT`) VALUES
(1, 'PHANBON10', '2025-06-01 00:00:00', '2025-06-30 23:59:59', 10, '', NULL, 1, NULL, '2025-07-07 06:47:54', '2025-07-07 06:47:54'),
(2, 'THUOC20K', '2025-06-15 00:00:00', '2025-07-15 23:59:59', 20000, '', NULL, 1, NULL, '2025-07-07 06:47:54', '2025-07-07 06:47:54'),
(3, 'MUA2TANG1', '2025-06-10 00:00:00', '2025-06-25 23:59:59', 0, '', NULL, 1, NULL, '2025-07-07 06:47:54', '2025-07-07 06:47:54'),
(4, 'FARMER5', '2025-06-01 00:00:00', '2025-06-30 23:59:59', 5, '', NULL, 1, NULL, '2025-07-07 06:47:54', '2025-07-07 06:47:54'),
(5, 'PESTCONTROL', '2025-07-01 00:00:00', '2025-07-31 23:59:59', 15, '', NULL, 1, NULL, '2025-07-07 06:47:54', '2025-07-07 06:47:54'),
(6, 'TANGCHAU', '2025-06-01 00:00:00', '2025-06-30 23:59:59', 0, '', NULL, 1, NULL, '2025-07-07 06:47:54', '2025-07-07 06:47:54');

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
-- Cấu trúc bảng cho bảng `khuyen_mai_san_pham_backup`
--

CREATE TABLE `khuyen_mai_san_pham_backup` (
  `KMSP_MA` int(11) NOT NULL DEFAULT 0 COMMENT 'Mã khuyến mãi sản phẩm',
  `KM_MA` int(11) NOT NULL COMMENT 'Mã khuyến mãi',
  `SP_MA` int(11) NOT NULL COMMENT 'Mã sản phẩm',
  `KMSP_SOLUONG_MUA` int(11) DEFAULT 1 COMMENT 'Số lượng cần mua để được khuyến mãi',
  `KMSP_SOLUONG_TANG` int(11) DEFAULT 0 COMMENT 'Số lượng được tặng',
  `KMSP_QUATANG` varchar(255) DEFAULT NULL COMMENT 'Mô tả quà tặng kèm',
  `KMSP_NGAYTAO` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Ngày tạo',
  `KMSP_NGAYCAPNHAT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật',
  `KMSP_TRANGTHAI` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái: 1-Kích hoạt, 0-Vô hiệu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `khuyen_mai_san_pham_backup`
--

INSERT INTO `khuyen_mai_san_pham_backup` (`KMSP_MA`, `KM_MA`, `SP_MA`, `KMSP_SOLUONG_MUA`, `KMSP_SOLUONG_TANG`, `KMSP_QUATANG`, `KMSP_NGAYTAO`, `KMSP_NGAYCAPNHAT`, `KMSP_TRANGTHAI`) VALUES
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
('14', 'Quảng Ninh', 'Tỉnh'),
('15', 'Yên Bái', 'Tỉnh'),
('16', 'Hải Phòng', 'Thành phố'),
('17', 'Hòa Bình', 'Tỉnh'),
('18', 'Nam Định', 'Tỉnh'),
('19', 'Thái Nguyên', 'Tỉnh'),
('48', 'Đắk Nông', 'Tỉnh'),
('63', 'Gia Lai', 'Tỉnh');

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
  `SP_TRONGLUONG` float DEFAULT NULL COMMENT 'Trọng lượng sản phẩm (kg)',
  `SP_NHASANXUAT` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `san_pham`
--

INSERT INTO `san_pham` (`SP_MA`, `NH_MA`, `DM_MA`, `SP_TEN`, `SP_DONGIA`, `SP_SOLUONGTON`, `SP_HINHANH`, `SP_MOTA`, `SP_THANHPHAN`, `SP_HUONGDANSUDUNG`, `SP_DONVITINH`, `SP_TRONGLUONG`, `SP_NHASANXUAT`) VALUES
(1, 1, 5, 'N3M kích rễ – Kích rễ cực mạnh cho cây ăn trái, kiểng, công nghiệp', 26000, 989, 'kich_re.jpg', 'Phân bón lá ra rễ cực mạnh N3M là sản phẩm được rất nhiều người ưa chuộng sử dụng vì giá thành rẻ, công dụng kích thích ra rễ được dùng trên nhiều loại cây trồng từ rau, cây ăn quả cho đến các loại hoa hồng – hoa kiểng.\r\n\r\n', 'N 11%, P2O53%, K2O5 2,5%, B, Cu, Zn…', 'Giâm, chiết cành (20gr/L nước): nhúng cành muốn giâm vào dung dịch thuốc 5-10p, sau đó giâm vào đất; bôi trực tiếp vào vết khoanh vỏ phía trên ngọn cành khi bỏ bầu.\r\n\r\n• Tưới gốc (20gr/10L nước): tưới đều quanh gốc cây để tăng cường và phục hồi bộ rễ bị suy yếu do xử lý thuốc hoặc sau khi ngập úng hay hạn, sau đó 7 ngày phun 1 lần\r\n\r\n• Phun trên lá (10gr/10L nước): khi ra đọt, khi cây ra hoa và trái non, làm cây đâm tược mới, chống rụng hoa, tăng đậu trái, sau đó cách 7 ngày phun 1 lần.\r\n\r\n• Ngâm hạt giống (10gr/10L nước): ngâm hạt giống trong 24h, sau đó vớt ra ủ bình thường.', 'kg', 1, 'Công ty Phân bón Việt Nam'),
(2, 1, 1, 'Chế phẩm EMUNIV (200g) – Ủ phân và rác hữu cơ hiệu quả, nhanh hoai mục', 35000, 458, 'emu.jpg', 'Chế phẩm ủ phân và rác thải Emuniv là chế phẩm vi sinh EM xử lý phân gia súc gia cầm, rác thải, phế thải nông nghiệp làm phân bón hữu cơ và xử lý ô nhiễm môi trường.', '• Bacillus subtillis: 10^8CFU/g.\r\n\r\n• Bacillus licheniformis: 10^7CFU/g.\r\n\r\n• Bacillus  megaterium: 10^7CFU/g.\r\n\r\n• Lactobacillus acidopphilus: 10^8CFU/g.\r\n\r\n• Lactobacillus plantarum: 10^8CFU/g.\r\n\r\n• Streptomyces sp: 10^7CFU/g.\r\n\r\n• Saccharomyces cereviseae: 10^7CFU/g.', '• Hòa 1 gói vào nước sạch, tưới cho 1 tấn nguyên liệu, đạt độ ẩm 45-50% ủ đống trong 20-30 ngày.\r\n\r\n• Xử lí nước thải: dùng từ 2-4gram chế phẩm/m3/ ngày đêm, đổ vào bể hiếm khí sục 8-10h/ngày đêm...\r\n\r\nBảo quản: để nơi khô mát trong vòng 12 tháng kể từ ngày sản xuất.', 'g', 1, 'Công ty cổ phần Vi Sinh Ứng Dụng'),
(3, 1, 1, 'Phân dê qua xử lý hàng chuẩn ( chuyên cho lan và hoa hồng ) - 1 túi', 22000, 793, 'phande.jpg', 'Phân dê là loại sản phẩm phân chuồng được thu gom từ các trang trại nuôi dê lớn. Phân dê sẽ được xử lý mầm bệnh bằng vi sinh, giảm ẩm và tiến hành đóng gói dạng thương mại để xuất đi. Phân dê có hàm lượng N-P-K khá cân đối (3%-1%-2%), cùng với đó là các khoáng trung vi lượng có hàm lượng cao phải kể đến là Canxi, Cu, Fe,... Phân dê được giới trồng hoa hồng và các loại hoa kiểng ưa chuộng vì tính tiện lợi, chất lượng các thành phần dinh dưỡng và không có mùi hôi - rất dễ sử dụng.\r\n\r\n• Trong phân dê có thành phần cải tạo kết cấu đất, giúp duy trì và làm phong phú cho khu vườn. Phân dê có khả năng cải thiện kết cấu đất để sử dụng nước hiệu quả hơn. Đặc biệt, cho phép nhiều oxy lưu thông đến bộ rễ, kết hợp với N trong phân giúp cho quá trình cố định đạm được diễn ra. Do đó, có thể làm tăng cường năng suất cây trồng lên đến 20%. ', 'phân dê', '• Bón cách gốc cây kiểng 2-4cm.\r\n\r\n• Tưới đều nước khi bón phân.\r\n\r\n• Bón ít nhất 1 tháng/lần với cây đang trưởng thành và cây ra hoa, cây già cỗi.', 'kg', 1, 'Phân dê được sản xuất tại các trang trại dê lớn ở '),
(4, 2, 5, 'Bimix Super Root (20ml)  –  Phát triển mạnh bộ rễ, kích thích cành chiết, cành giâm', 6000, 575, 'bemix.jpg', 'Sản phẩm thuốc kích rễ bimix super root (20ml) được sản xuất với công nghệ tiên tiến từ các nguyên liệu chất lượng. Sản phẩm được dùng để kích thích ra rễ cây trong phương pháp giâm cành và chiết cành, phục hồi rễ sau thời kì ngập úng.', '• Acid humic đậm đặc: 9%\r\n\r\n• Acid Amin: 0.12%\r\n\r\n• Nito: 6%\r\n\r\n• Photpho: 8%\r\n\r\n• Kali: 6%\r\n\r\n• Chelate Cu, Fe, Zn, B:> 1000 ppm\r\n\r\n• Vitamin và một số chất điều hòa sinh trưởng thực vật khác\r\n\r\n', 'Dùng cho cây ăn trái, cây công nghiệp, vườn ươm, cây cảnh\r\n\r\n• Lúa: Pha 10 - 20ml/ 8 lít nước (1 lít/ 600 lít nước/ ha/ lần)\r\n\r\n• Cây ăn trái, cây công nghiệp:\r\n\r\nCây con: 10 -20ml/ 16 lít nước (0.4 lít/ 600 lít nước/ ha/ lần)\r\n\r\nCây trưởng thành: 20 - 25ml/ 16 lít nước (1 lít/ 600 lít nước/ ha/ lần)\r\n\r\n• Rau màu, hoa kiểng:  10 -15ml/ 16 lít nước (0.4 lít/ 600 lít nước/ ha/ lần)\r\n\r\n• Giâm chiết cành: Pha dung dịch 20ml/ 5 lít nước: ngâm cành giâm, cành ghép, hạt giống từ 2-3 giờ (hoặc bôi chỗ ghép, gốc chiết, gốc cành giâm.', 'kg', 0.02, 'Công ty CP Cây Trồng Bình Chánh'),
(5, 3, 5, 'Axit humic dạng lỏng 322 Growmore (235ml) - Kích rễ và chống ngộ độc hữu cơ cho cây', 45000, 388, 'humic.jpg', 'Axit humic dạng lỏng 322 là dòng sản phẩm hữu cơ, được sử dụng cho cây trồng để cung cấp chất hữu cơ, khoáng,.. Axit humic có chức năng chính là kích thích bộ rễ phát triển, giúp rễ khỏe mạnh, kháng phèn và chống ngộ độc hữu cơ. Bên cạnh đó, Axit humic tăng khả năng đậu quả, chống rụng bông, bông to trái lớn và tăng năng suất cây trồng.', '• Axit humic: 6.3%\r\n\r\n• Axit fulvic: 1.2%\r\n\r\n• Nts: 3%\r\n\r\n• P2O5hh: 2%\r\n\r\n• K2Ohh: 2%\r\n\r\n• Fe: 1000 ppm\r\n\r\n• Zn: 500ppm\r\n\r\n• Cu: 500ppm\r\n\r\n• Mn: 500ppm\r\n\r\n• pHH2O: 5\r\n\r\n• Tỷ trọng: 1.1', '• Rau các loại: cà chua, dưa hấu, dưa leo, bầu, bí, khổ qua, ớt, bắp cải, khoai tây, dâu, xà lách, su hào, dền, dứa, gừng, bó xôi, cà rốt, khoai, lang, các loại đậu.\r\n\r\n• Cây ăn trái: thanh long, nhãn, chôm chôm, sầu riêng, mãng cầu, nho, táo, đu đủ, cam, quýt, bưởi, xoài, ổi, măng cụt, sơ ri, vải, hồng, đào, mận, na, saboche, dâu, khóm...\r\n\r\n• Cây công nghiệp: trà, cà phê, thuốc lá, cao su, bông vải, mía, bắp, tiêu, dâu tằm, điều.\r\n\r\n• Các loại bonsai, bông hoa cây cảnh: phong lan, hoa hồng, hoa lài, cúc, vạn thọ, huệ, mai, tulip, cẩm chướng...\r\n\r\n• Pha 20cc - 30cc / 10 lít nước hoặc can 1 lít / 2 phuy (phuy 200 lít). Phun định kỳ 10 - 15 ngày/ lần.\r\n\r\n• Đối với cây lúa phun định kỳ 7 ngày/ lần, vào 3 thời kỳ cơ bản lúc lúa đẻ nhánh, trước khi trổ và thời kỳ nuôi bông, nuôi hạt.', 'ml', 0.01, 'Sản phẩm có xuất xứ từ Hoa Kỳ, đucợ phân phối bởi '),
(6, 1, 1, 'Phân bón kiểng lá viên nén hữu cơ 100% SFARM - Túi 500 gram', 19000, 1995, 'sfram.jpg', 'Phân bón hữu cơ chuyên cho cây trong nhà SFARM là dòng phân bón dạng viên tan chậm được cải tiến chuyên biệt cho cây trong nhà. Sản phẩm là sự cải tiến và kết hợp hoàn hảo giữa phân trùn quế và thành phần hữu cơ khác. Viên nén có màu nâu đen, nhẵn bóng cho thời gian sử dụng kéo dài 30 – 45 ngày.', ' Phân trùn quế và thành phần hữu cơ khác', 'Bón định kỳ khoảng 1 lần/tháng với lượng 50g cho chậu đường kính 30cm.\r\nRải trực tiếp phân lên bề mặt chậu, xung quanh gốc cây theo đường kính tán. Sau đó, đảo nhẹ lớp đất mặt và tưới nước cho cây', 'gói', 1, 'Công ty Hạt Giống Việt'),
(7, 1, 3, 'ATONIK (10ml) – Kích rễ, bật mầm mạnh cho cây trồng và hoa kiểng', 10500, 1496, 'atonik.jpg', 'Thuốc kích thích sinh trưởng cây trồng và hoa kiểng ATONIK là thuốc kích thích sinh trưởng cây trồng thế hệ mới. Cũng như các loại vitamin, Atonik làm tăng khả năng sinh trưởng đồng thời giúp cây trồng tránh khỏi những ảnh hưởng xấu do những điều kiện sinh trưởng không thuận lợi gây ra.', 'Sodium -  Nitrogualacolate 0,03%\r\n\r\nSodium - Nitrophenolate 0,06%\r\n\r\nSodium - P - Nitrophenolate 0,09%', '+ Ngâm hạt: Kích thích sự nảy mầm và ra rễ, phá vỡ trạng thái ngủ của hạt giống\r\n\r\n+ Phun tưới trên ruộng mạ, cây con: Làm cho cây mạ phát triển, phục hồi nhanh chóng sau cấy trồng\r\n\r\n+ Phun qua lá: Kích thích sự sinh trưởng phát triển, tạo điều kiện cho quá trình trao đổi chất của cây, giúp cây sớm thu hoạch với năng suất cao, chất lượng tốt.', 'gói', 0.01, 'Công ty Hạt Giống Việt'),
(8, 4, 4, 'COC85 (20g) - Ngừa bệnh rỉ sắt, đốm đen cho cây trồng, đặc hiệu cho hoa kiểng', 220000, 12988, 'coc85.jpg', 'Thuốc trừ bệnh COC85 được sản xuất từ ion gốc Đồng (Cu2+), dạng bột mịn, loang đều và bám dính tốt. Sản phẩm được dùng để phòng trừ nấm bệnh, rỉ sắt, đốm đen trên các loại cây trồng, cây kiểng. Đặc biệt là hoa hồng, cây mai, đào.\r\n\r\n', '• Đồng Oxycloride: 85w/w.\r\n\r\n• Phụ gia: 15 w/w.', '• Pha loãng khoảng 10 -20 gram cho bình 8 - 10 lít, phun khi cây mới chớm bệnh. Mỗi 14 ngày nên phun để phòng trừ bệnh. \r\n\r\n• Thời gian cách ly: 7 ngày.', 'g', 1, 'Syngenta'),
(9, 5, 4, 'Thuốc trừ bệnh BELLKUTE 40WP đặc trị bệnh phấn trắng trên hoa hồng - Gói 20 gram', 35000, 700, 'bellkute.jpg', 'Mô tả sản phẩm: Thuốc trừ bệnh Bellkute 40WP là thuốc trừ bệnh phổ rộng, chuyên trị bệnh phấn trắng trên hoa hồng, sương mai trên cây bầu bí, thán thư trên ớt,...Phòng trừ bệnh do nấm như: đốm vàng, đốm nâu, đốm đen, gỉ sắt, thối nhũn, héo củ, vàng lá,...trên cây hoa mai, hoa lan và cây cảnh. ', ' Iminoctadine: .............40% w/w.', '• Cây trồng: Hoa Hồng, bệnh hại (phấn trắng). \r\n\r\n• Liều lượng: 0,5kg/ha (10-13 gr/ bình 16 lít, 16-21 gr/bình 25 lít). \r\n\r\n• Phun ướt đều tán lá cây trồng và phun thuốc khi bệnh chớm xuất hiện. \r\n\r\n• Phun lặp lại 7-10 ngày nếu áp lực bệnh cao. \r\n\r\n• Lượng nước phun: 600-800 lít/ha. \r\n\r\n• Thời gian cách ly: Ngưng phun thuốc 7 ngày trước khi thu hoạch. ', 'ml', 0.05, 'Công ty Phân bón Grow More'),
(10, 1, 6, 'Hoạt chất sinh học Neem Chito - Phòng trừ nhện đỏ và bọ trĩ trên cây hoa hồng', 43000, 2497, 'chito.jpg', 'Hoạt chất sinh học Neem Chito phòng trừ hiệu quả, không gây kháng thuốc đối với nhện đỏ và bọ trĩ chích hút trên cây hoa hồng một cách an toàn, thân thiện nhất. Ngoài ra, Neem Chito còn phòng ngừa được rầy, rệp và sâu cuốn lá, đồng thời tăng sức đề kháng của cây hồng chống lại các tác nhân gây bệnh, kích thích cây tăng trưởng, đâm chồi, nở hoa, hoa to và bền màu.', '• Potassium Linear AlkylBenzene Sulfonate: 9%\r\n\r\n• Chitosan được chiết xuất từ vỏ tôm, vỏ cua.\r\n\r\n• Tinh dầu Neem chưa hoạt chất Azadirachtin từ cây neem Ấn Độ.\r\n\r\n• Chất bám dính sinh học hữu cơ.\r\n\r\n', '• Pha 10ml - 15ml (1/2 - 1 nắp)/ bình 20 lít nước (100ml - 150ml/ phuy 200 lít nước), sử dụng cho các loại cây trồng.\r\n\r\n• Phun đều lên tán cây, cả mặt trên và mặt dưới lá.\r\n\r\n• Hòa chung phân bón lá và nông dược, phun đều lên tán cây.\r\n\r\n• Phu định kỳ 10-15 ngày/ lần hoặc phun theo kỳ phun thuốc.', 'gói', 1, 'Công ty Hạt Giống Việt'),
(11, 4, 6, 'Thuốc trừ ốc dạng phun HELIX 500WP - Chai 50 gram', 23000, 345, 'helix.jpg', 'Thuốc trừ ốc dạng phun Helix 500wp là thuốc đặc trị ốc hiệu quả cao. Đặc biệt chuyên trị ốc gây hại trên cây cảnh, lúa. ', '• Metaldehyde...500g/kg.\r\n\r\n• Phụ gia đặc biệt vừa đủ 1kg. \r\n\r\n', '• Đối với cây cảnh, rau màu, cây ăn quả: \r\n\r\n+ Ốc sên: \r\n\r\n- Pha 50g/bình 16 lít, lượng nước phun 320 lít/ha. \r\n\r\n- Lượng nước phun 320 lít/ha. \r\n\r\n- Phun lúc trời mát, theo đường di chuyển của ốc. \r\n\r\n• Đối với lúa\r\n\r\n+ Ốc bươu vàng: \r\n\r\n- Liều lượng: 1-1.2kg/ ha. \r\n\r\n- Lượng nước phun 320 lít/ha. \r\n\r\n- Phun thuốc khi ruộng có nước 1-5cm. \r\n\r\n• Thời gian cách ly: không xác định. ', 'ml', 1, 'Syngenta'),
(12, 1, 6, 'NeemNim Ấn Độ (100ml) – Phòng ngừa sâu bệnh, rệp sáp, sâu xanh, bọ cánh tơ, sâu tơ', 160000, 885, 'neemim.jpg', 'NeemNim Ấn Độ là sản phẩm thuốc trừ sâu được chiết xuất hoàn toàn từ thảo mộc thiên nhiên giúp phòng trừ nhiều loại sâu hại như đục lá, rệp sáp, cánh lụa, sâu tơ, sâu xanh da láng… vô cùng an toàn cho người sử dụng và thiên nhiên.', 'Azadirachtin: 0,3% khối lượng.', '• Pha 30-50ml cho bình 16 lít.\r\n\r\n• Lượng nước phun 400-600 lít/ ha.\r\n\r\n• Phun ướt đều thân lá, lượng nước phun tùy theo cây trồng và thời gian sinh trưởng. Phun phòng ngừa và trị sau khi sâu mới xuất hiện,nếu sâu hại nặng nên phun lại lần 2 sau  7 ngày.\r\n\r\n• Không phun thuốc khi cây đang ra hoa vì sẽ xua đuổi côn trùng có ích làm giảm khả năng thụ phấn của cây.', 'kg', 1, 'Công ty Phân bón Việt Nam');

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
('11581', '704', 'Xã Nghĩa Hưng', 'Xã'),
('2445', '158', 'Xã Đông Xá', 'Xã'),
('2446', '158', 'Xã Bình Dân', 'Xã'),
('2447', '158', 'Xã Vạn Yên', 'Xã'),
('2448', '158', 'Xã Minh Châu', 'Xã'),
('3246', '188', 'Xã Tự Do', 'Xã'),
('3550', '206', 'Xã Tân Đức', 'Xã'),
('9621', '550', 'Xã Quảng Sơn', 'Xã');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `chatbot_context`
--
ALTER TABLE `chatbot_context`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_session` (`session_id`);

--
-- Chỉ mục cho bảng `chatbot_conversations`
--
ALTER TABLE `chatbot_conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_session` (`session_id`);

--
-- Chỉ mục cho bảng `chatbot_crop_products`
--
ALTER TABLE `chatbot_crop_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_crop` (`crop_type`,`growth_stage`);

--
-- Chỉ mục cho bảng `chatbot_disease_products`
--
ALTER TABLE `chatbot_disease_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_disease` (`disease_name`);

--
-- Chỉ mục cho bảng `chatbot_responses`
--
ALTER TABLE `chatbot_responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_intent` (`intent`);

--
-- Chỉ mục cho bảng `chat_history`
--
ALTER TABLE `chat_history`
  ADD PRIMARY KEY (`id`);

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
-- Chỉ mục cho bảng `dia_chi_giao_hang`
--
ALTER TABLE `dia_chi_giao_hang`
  ADD PRIMARY KEY (`DCGH_MA`),
  ADD KEY `fk_dcgh_hd` (`DH_MA`);

--
-- Chỉ mục cho bảng `dia_chi_giao_hang_moi`
--
ALTER TABLE `dia_chi_giao_hang_moi`
  ADD PRIMARY KEY (`DC_MA`),
  ADD KEY `FK_DCGH_KH` (`KH_MA`),
  ADD KEY `idx_dc_macdinh` (`DC_MACDINH`),
  ADD KEY `idx_dc_ngaytao` (`DC_NGAYTAO`);

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
  ADD KEY `fk_district_province` (`province_id`);

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
-- Chỉ mục cho bảng `symptoms`
--
ALTER TABLE `symptoms`
  ADD PRIMARY KEY (`id`);

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
-- Chỉ mục cho bảng `wards`
--
ALTER TABLE `wards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ward_district` (`district_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `chatbot_context`
--
ALTER TABLE `chatbot_context`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `chatbot_conversations`
--
ALTER TABLE `chatbot_conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `chatbot_crop_products`
--
ALTER TABLE `chatbot_crop_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `chatbot_disease_products`
--
ALTER TABLE `chatbot_disease_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `chatbot_responses`
--
ALTER TABLE `chatbot_responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `chat_history`
--
ALTER TABLE `chat_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `chitiet_gh`
--
ALTER TABLE `chitiet_gh`
  MODIFY `GH_MA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `chi_tiet_hd`
--
ALTER TABLE `chi_tiet_hd`
  MODIFY `CTHD_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT cho bảng `crops`
--
ALTER TABLE `crops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `dia_chi_giao_hang`
--
ALTER TABLE `dia_chi_giao_hang`
  MODIFY `DCGH_MA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT cho bảng `dia_chi_giao_hang_moi`
--
ALTER TABLE `dia_chi_giao_hang_moi`
  MODIFY `DC_MA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `diseases`
--
ALTER TABLE `diseases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `don_van_chuyen`
--
ALTER TABLE `don_van_chuyen`
  MODIFY `DVC_MA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;

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
-- AUTO_INCREMENT cho bảng `hoa_don`
--
ALTER TABLE `hoa_don`
  MODIFY `HD_STT` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT cho bảng `khach_hang`
--
ALTER TABLE `khach_hang`
  MODIFY `KH_MA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `khuyen_mai`
--
ALTER TABLE `khuyen_mai`
  MODIFY `KM_MA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `khuyen_mai_san_pham`
--
ALTER TABLE `khuyen_mai_san_pham`
  MODIFY `KMSP_MA` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Mã khuyến mãi sản phẩm';

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `selected_cart_items`
--
ALTER TABLE `selected_cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT cho bảng `symptoms`
--
ALTER TABLE `symptoms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
-- Các ràng buộc cho bảng `dia_chi_giao_hang_moi`
--
ALTER TABLE `dia_chi_giao_hang_moi`
  ADD CONSTRAINT `FK_DCGH_KH` FOREIGN KEY (`KH_MA`) REFERENCES `khach_hang` (`KH_MA`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Các ràng buộc cho bảng `product_crops`
--
ALTER TABLE `product_crops`
  ADD CONSTRAINT `product_crops_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_crops_ibfk_2` FOREIGN KEY (`crop_id`) REFERENCES `crops` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_crops_ibfk_3` FOREIGN KEY (`growth_stage_id`) REFERENCES `growth_stages` (`id`) ON DELETE CASCADE;

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
