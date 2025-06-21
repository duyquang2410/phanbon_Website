-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th6 18, 2025 lúc 09:30 AM
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
(3, 7, 3, 'cái', 1),
(12, 7, 3, 'cái', 0),
(4, 10, 2, 'cái', 1),
(5, 10, 1, 'cái', 1),
(7, 10, 1, 'cái', 1),
(2, 11, 3, 'cái', 1),
(4, 11, 1, 'cái', 0),
(5, 11, 1, 'cái', 1);

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
(0, 3, 0, 3, 22000, 'cái', NULL);

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
(3, 1, '200 Cách Mạng Tháng 8, Quận 10', '2025-06-17 14:00:00', '2025-06-17 16:30:00');

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
(10, 3);

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
  `HD_LIDOHUY` varchar(200) DEFAULT NULL,
  `KH_MA` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `hoa_don`
--

INSERT INTO `hoa_don` (`HD_STT`, `TT_MA`, `DVC_MA`, `NV_MA`, `PTTT_MA`, `KM_MA`, `GH_MA`, `HD_NGAYLAP`, `HD_TONGTIEN`, `HD_LIDOHUY`, `KH_MA`) VALUES
(1, 1, 1, 1, 1, NULL, 7, '2025-06-18 14:07:11', 66000, NULL, 2);

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
(2, 'Quang Duy', 'Cần thơ', '0793994771', '0000-00-00 00:00:00', 'duyquang2709pp@gmail.com', '', '2025-05-24 00:00:00', 'google_1748064450', 'b727f634ace7906ea4573711412938ee', 'default-avatar.jpg', '112253003004925133473'),
(3, 'Nguyen Nhat Duy Quang C2200016', 'Cần thơ', '0793994771', '2000-10-14 00:00:00', 'quangc2200016@student.ctu.edu.vn', 'M', '2025-06-16 00:00:00', 'google_1750061149', 'b83f5eb3138149a2251fcfe81c8d2648', 'default-avatar.jpg', '113160860558260392135');

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
  `hinh_thuc_km` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `SP_NHASANXUAT` varchar(50) NOT NULL,
  `SP_TRONGLUONG` float DEFAULT 0 COMMENT 'Trọng lượng sản phẩm (gram)',
  `SP_CHIEUDAI` float DEFAULT 0 COMMENT 'Chiều dài sản phẩm (cm)',
  `SP_CHIEURONG` float DEFAULT 0 COMMENT 'Chiều rộng sản phẩm (cm)',
  `SP_CHIEUCAO` float DEFAULT 0 COMMENT 'Chiều cao sản phẩm (cm)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `san_pham`
--

INSERT INTO `san_pham` (`SP_MA`, `NH_MA`, `DM_MA`, `SP_TEN`, `SP_DONGIA`, `SP_SOLUONGTON`, `SP_HINHANH`, `SP_MOTA`, `SP_THANHPHAN`, `SP_HUONGDANSUDUNG`, `SP_DONVITINH`, `SP_NHASANXUAT`, `SP_TRONGLUONG`, `SP_CHIEUDAI`, `SP_CHIEURONG`, `SP_CHIEUCAO`) VALUES
(1, 1, 5, 'N3M kích rễ – Kích rễ cực mạnh cho cây ăn trái, kiểng, công nghiệp', 26000, 1000, 'kich_re.jpg', 'Phân bón lá ra rễ cực mạnh N3M là sản phẩm được rất nhiều người ưa chuộng sử dụng vì giá thành rẻ, công dụng kích thích ra rễ được dùng trên nhiều loại cây trồng từ rau, cây ăn quả cho đến các loại hoa hồng – hoa kiểng.\r\n\r\n', 'N 11%, P2O53%, K2O5 2,5%, B, Cu, Zn…', 'Giâm, chiết cành (20gr/L nước): nhúng cành muốn giâm vào dung dịch thuốc 5-10p, sau đó giâm vào đất; bôi trực tiếp vào vết khoanh vỏ phía trên ngọn cành khi bỏ bầu.\r\n\r\n• Tưới gốc (20gr/10L nước): tưới đều quanh gốc cây để tăng cường và phục hồi bộ rễ bị suy yếu do xử lý thuốc hoặc sau khi ngập úng hay hạn, sau đó 7 ngày phun 1 lần\r\n\r\n• Phun trên lá (10gr/10L nước): khi ra đọt, khi cây ra hoa và trái non, làm cây đâm tược mới, chống rụng hoa, tăng đậu trái, sau đó cách 7 ngày phun 1 lần.\r\n\r\n• Ngâm hạt giống (10gr/10L nước): ngâm hạt giống trong 24h, sau đó vớt ra ủ bình thường.', 'kg', 'Công ty Phân bón Việt Nam', 0, 0, 0, 0),
(2, 1, 1, 'Chế phẩm EMUNIV (200g) – Ủ phân và rác hữu cơ hiệu quả, nhanh hoai mục', 35000, 500, 'emu.jpg', 'Chế phẩm ủ phân và rác thải Emuniv là chế phẩm vi sinh EM xử lý phân gia súc gia cầm, rác thải, phế thải nông nghiệp làm phân bón hữu cơ và xử lý ô nhiễm môi trường.', '• Bacillus subtillis: 10^8CFU/g.\r\n\r\n• Bacillus licheniformis: 10^7CFU/g.\r\n\r\n• Bacillus  megaterium: 10^7CFU/g.\r\n\r\n• Lactobacillus acidopphilus: 10^8CFU/g.\r\n\r\n• Lactobacillus plantarum: 10^8CFU/g.\r\n\r\n• Streptomyces sp: 10^7CFU/g.', '• Hòa 1 gói vào nước sạch, tưới cho 1 tấn nguyên liệu, đạt độ ẩm 45-50% ủ đống trong 20-30 ngày.\r\n\r\n• Xử lí nước thải: dùng từ 2-4gram chế phẩm/m3/ ngày đêm, đổ vào bể hiếm khí sục 8-10h/ngày đêm...\r\n\r\nBảo quản: để nơi khô mát trong vòng 12 tháng kể từ ngày sản xuất.', 'g', 'Công ty cổ phần Vi Sinh Ứng Dụng', 0, 0, 0, 0),
(3, 1, 1, 'Phân dê qua xử lý hàng chuẩn ( chuyên cho lan và hoa hồng ) - 1 túi', 22000, 797, 'phande.jpg', 'Phân dê là loại sản phẩm phân chuồng được thu gom từ các trang trại nuôi dê lớn. Phân dê sẽ được xử lý mầm bệnh bằng vi sinh, giảm ẩm và tiến hành đóng gói dạng thương mại để xuất đi. Phân dê có hàm lượng N-P-K khá cân đối (3%-1%-2%), cùng với đó là các khoáng trung vi lượng có hàm lượng cao phải kể đến là Canxi, Cu, Fe,... Phân dê được giới trồng hoa hồng và các loại hoa kiểng ưa chuộng vì tính tiện lợi, chất lượng các thành phần dinh dưỡng và không có mùi hôi - rất dễ sử dụng.\r\n\r\n• Trong phân dê có thành phần cải tạo kết cấu đất, giúp duy trì và làm phong phú cho khu vườn. Phân dê có khả năng cải thiện kết cấu đất để sử dụng nước hiệu quả hơn. Đặc biệt, cho phép nhiều oxy lưu thông đến bộ rễ, kết hợp với N trong phân giúp cho quá trình cố định đạm được diễn ra. Do đó, có thể làm tăng cường năng suất cây trồng lên đến 20%. ', 'phân dê', '• Bón cách gốc cây kiểng 2-4cm.\r\n\r\n• Tưới đều nước khi bón phân.\r\n\r\n• Bón ít nhất 1 tháng/lần với cây đang trưởng thành và cây ra hoa, cây già cỗi.', 'kg', 'Phân dê được sản xuất tại các trang trại dê lớn ở ', 0, 0, 0, 0),
(4, 2, 5, 'Bimix Super Root (20ml)  –  Phát triển mạnh bộ rễ, kích thích cành chiết, cành giâm', 6000, 600, 'bemix.jpg', 'Sản phẩm thuốc kích rễ bimix super root (20ml) được sản xuất với công nghệ tiên tiến từ các nguyên liệu chất lượng. Sản phẩm được dùng để kích thích ra rễ cây trong phương pháp giâm cành và chiết cành, phục hồi rễ sau thời kì ngập úng.', '• Acid humic đậm đặc: 9%\r\n\r\n• Acid Amin: 0.12%\r\n\r\n• Nito: 6%\r\n\r\n• Photpho: 8%\r\n\r\n• Kali: 6%\r\n\r\n• Chelate Cu, Fe, Zn, B:> 1000 ppm\r\n\r\n• Vitamin và một số chất điều hòa sinh trưởng thực vật khác\r\n\r\n', 'Dùng cho cây ăn trái, cây công nghiệp, vườn ươm, cây cảnh\r\n\r\n• Lúa: Pha 10 - 20ml/ 8 lít nước (1 lít/ 600 lít nước/ ha/ lần)\r\n\r\n• Cây ăn trái, cây công nghiệp:\r\n\r\nCây con: 10 -20ml/ 16 lít nước (0.4 lít/ 600 lít nước/ ha/ lần)\r\n\r\nCây trưởng thành: 20 - 25ml/ 16 lít nước (1 lít/ 600 lít nước/ ha/ lần)\r\n\r\n• Rau màu, hoa kiểng:  10 -15ml/ 16 lít nước (0.4 lít/ 600 lít nước/ ha/ lần)\r\n\r\n• Giâm chiết cành: Pha dung dịch 20ml/ 5 lít nước: ngâm cành giâm, cành ghép, hạt giống từ 2-3 giờ (hoặc bôi chỗ ghép, gốc chiết, gốc cành giâm.', 'kg', 'Công ty CP Cây Trồng Bình Chánh', 0, 0, 0, 0),
(5, 3, 5, 'Axit humic dạng lỏng 322 Growmore (235ml) - Kích rễ và chống ngộ độc hữu cơ cho cây', 45000, 400, 'humic.jpg', 'Axit humic dạng lỏng 322 là dòng sản phẩm hữu cơ, được sử dụng cho cây trồng để cung cấp chất hữu cơ, khoáng,.. Axit humic có chức năng chính là kích thích bộ rễ phát triển, giúp rễ khỏe mạnh, kháng phèn và chống ngộ độc hữu cơ. Bên cạnh đó, Axit humic tăng khả năng đậu quả, chống rụng bông, bông to trái lớn và tăng năng suất cây trồng.', '• Axit humic: 6.3%\r\n\r\n• Axit fulvic: 1.2%\r\n\r\n• Nts: 3%\r\n\r\n• P2O5hh: 2%\r\n\r\n• K2Ohh: 2%\r\n\r\n• Fe: 1000 ppm\r\n\r\n• Zn: 500ppm\r\n\r\n• Cu: 500ppm\r\n\r\n• Mn: 500ppm\r\n\r\n• pHH2O: 5\r\n\r\n• Tỷ trọng: 1.1', '• Rau các loại: cà chua, dưa hấu, dưa leo, bầu, bí, khổ qua, ớt, bắp cải, khoai tây, dâu, xà lách, su hào, dền, dứa, gừng, bó xôi, cà rốt, khoai, lang, các loại đậu.\r\n\r\n• Cây ăn trái: thanh long, nhãn, chôm chôm, sầu riêng, mãng cầu, nho, táo, đu đủ, cam, quýt, bưởi, xoài, ổi, măng cụt, sơ ri, vải, hồng, đào, mận, na, saboche, dâu, khóm...\r\n\r\n• Cây công nghiệp: trà, cà phê, thuốc lá, cao su, bông vải, mía, bắp, tiêu, dâu tằm, điều.\r\n\r\n• Các loại bonsai, bông hoa cây cảnh: phong lan, hoa hồng, hoa lài, cúc, vạn thọ, huệ, mai, tulip, cẩm chướng...\r\n\r\n• Pha 20cc - 30cc / 10 lít nước hoặc can 1 lít / 2 phuy (phuy 200 lít). Phun định kỳ 10 - 15 ngày/ lần.\r\n\r\n• Đối với cây lúa phun định kỳ 7 ngày/ lần, vào 3 thời kỳ cơ bản lúc lúa đẻ nhánh, trước khi trổ và thời kỳ nuôi bông, nuôi hạt.', 'ml', 'Sản phẩm có xuất xứ từ Hoa Kỳ, đucợ phân phối bởi ', 0, 0, 0, 0),
(6, 1, 1, 'Phân bón kiểng lá viên nén hữu cơ 100% SFARM - Túi 500 gram', 19000, 2000, 'sfram.jpg', 'Phân bón hữu cơ chuyên cho cây trong nhà SFARM là dòng phân bón dạng viên tan chậm được cải tiến chuyên biệt cho cây trong nhà. Sản phẩm là sự cải tiến và kết hợp hoàn hảo giữa phân trùn quế và thành phần hữu cơ khác. Viên nén có màu nâu đen, nhẵn bóng cho thời gian sử dụng kéo dài 30 – 45 ngày.', ' Phân trùn quế và thành phần hữu cơ khác', 'Bón định kỳ khoảng 1 lần/tháng với lượng 50g cho chậu đường kính 30cm.\r\nRải trực tiếp phân lên bề mặt chậu, xung quanh gốc cây theo đường kính tán. Sau đó, đảo nhẹ lớp đất mặt và tưới nước cho cây', 'gói', 'Công ty Hạt Giống Việt', 0, 0, 0, 0),
(7, 1, 3, 'ATONIK (10ml) – Kích rễ, bật mầm mạnh cho cây trồng và hoa kiểng', 10500, 1500, 'atonik.jpg', 'Thuốc kích thích sinh trưởng cây trồng và hoa kiểng ATONIK là thuốc kích thích sinh trưởng cây trồng thế hệ mới. Cũng như các loại vitamin, Atonik làm tăng khả năng sinh trưởng đồng thời giúp cây trồng tránh khỏi những ảnh hưởng xấu do những điều kiện sinh trưởng không thuận lợi gây ra.', 'Sodium -  Nitrogualacolate 0,03%\r\n\r\nSodium - Nitrophenolate 0,06%\r\n\r\nSodium - P - Nitrophenolate 0,09%', '+ Ngâm hạt: Kích thích sự nảy mầm và ra rễ, phá vỡ trạng thái ngủ của hạt giống\r\n\r\n+ Phun tưới trên ruộng mạ, cây con: Làm cho cây mạ phát triển, phục hồi nhanh chóng sau cấy trồng\r\n\r\n+ Phun qua lá: Kích thích sự sinh trưởng phát triển, tạo điều kiện cho quá trình trao đổi chất của cây, giúp cây sớm thu hoạch với năng suất cao, chất lượng tốt.', 'gói', 'Công ty Hạt Giống Việt', 0, 0, 0, 0),
(8, 4, 4, 'COC85 (20g) - Ngừa bệnh rỉ sắt, đốm đen cho cây trồng, đặc hiệu cho hoa kiểng', 220000, 13000, 'coc85.jpg', 'Thuốc trừ bệnh COC85 được sản xuất từ ion gốc Đồng (Cu2+), dạng bột mịn, loang đều và bám dính tốt. Sản phẩm được dùng để phòng trừ nấm bệnh, rỉ sắt, đốm đen trên các loại cây trồng, cây kiểng. Đặc biệt là hoa hồng, cây mai, đào.\r\n\r\n', '• Đồng Oxycloride: 85w/w.\r\n\r\n• Phụ gia: 15 w/w.', '• Pha loãng khoảng 10 -20 gram cho bình 8 - 10 lít, phun khi cây mới chớm bệnh. Mỗi 14 ngày nên phun để phòng trừ bệnh. \r\n\r\n• Thời gian cách ly: 7 ngày.', 'g', 'Syngenta', 0, 0, 0, 0),
(9, 5, 4, 'Thuốc trừ bệnh BELLKUTE 40WP đặc trị bệnh phấn trắng trên hoa hồng - Gói 20 gram', 35000, 700, 'bellkute.jpg', 'Mô tả sản phẩm: Thuốc trừ bệnh Bellkute 40WP là thuốc trừ bệnh phổ rộng, chuyên trị bệnh phấn trắng trên hoa hồng, sương mai trên cây bầu bí, thán thư trên ớt,...Phòng trừ bệnh do nấm như: đốm vàng, đốm nâu, đốm đen, gỉ sắt, thối nhũn, héo củ, vàng lá,...trên cây hoa mai, hoa lan và cây cảnh. ', ' Iminoctadine: .............40% w/w.', '• Cây trồng: Hoa Hồng, bệnh hại (phấn trắng). \r\n\r\n• Liều lượng: 0,5kg/ha (10-13 gr/ bình 16 lít, 16-21 gr/bình 25 lít). \r\n\r\n• Phun ướt đều tán lá cây trồng và phun thuốc khi bệnh chớm xuất hiện. \r\n\r\n• Phun lặp lại 7-10 ngày nếu áp lực bệnh cao. \r\n\r\n• Lượng nước phun: 600-800 lít/ha. \r\n\r\n• Thời gian cách ly: Ngưng phun thuốc 7 ngày trước khi thu hoạch. ', 'ml', 'Công ty Phân bón Grow More', 0, 0, 0, 0),
(10, 1, 6, 'Hoạt chất sinh học Neem Chito - Phòng trừ nhện đỏ và bọ trĩ trên cây hoa hồng', 43000, 2500, 'chito.jpg', 'Hoạt chất sinh học Neem Chito phòng trừ hiệu quả, không gây kháng thuốc đối với nhện đỏ và bọ trĩ chích hút trên cây hoa hồng một cách an toàn, thân thiện nhất. Ngoài ra, Neem Chito còn phòng ngừa được rầy, rệp và sâu cuốn lá, đồng thời tăng sức đề kháng của cây hồng chống lại các tác nhân gây bệnh, kích thích cây tăng trưởng, đâm chồi, nở hoa, hoa to và bền màu.', '• Potassium Linear AlkylBenzene Sulfonate: 9%\r\n\r\n• Chitosan được chiết xuất từ vỏ tôm, vỏ cua.\r\n\r\n• Tinh dầu Neem chưa hoạt chất Azadirachtin từ cây neem Ấn Độ.\r\n\r\n• Chất bám dính sinh học hữu cơ.\r\n\r\n', '• Pha 10ml - 15ml (1/2 - 1 nắp)/ bình 20 lít nước (100ml - 150ml/ phuy 200 lít nước), sử dụng cho các loại cây trồng.\r\n\r\n• Phun đều lên tán cây, cả mặt trên và mặt dưới lá.\r\n\r\n• Hòa chung phân bón lá và nông dược, phun đều lên tán cây.\r\n\r\n• Phu định kỳ 10-15 ngày/ lần hoặc phun theo kỳ phun thuốc.', 'gói', 'Công ty Hạt Giống Việt', 0, 0, 0, 0),
(11, 4, 6, 'Thuốc trừ ốc dạng phun HELIX 500WP - Chai 50 gram', 23000, 350, 'helix.jpg', 'Thuốc trừ ốc dạng phun Helix 500wp là thuốc đặc trị ốc hiệu quả cao. Đặc biệt chuyên trị ốc gây hại trên cây cảnh, lúa. ', '• Metaldehyde...500g/kg.\r\n\r\n• Phụ gia đặc biệt vừa đủ 1kg. \r\n\r\n', '• Đối với cây cảnh, rau màu, cây ăn quả: \r\n\r\n+ Ốc sên: \r\n\r\n- Pha 50g/bình 16 lít, lượng nước phun 320 lít/ha. \r\n\r\n- Lượng nước phun 320 lít/ha. \r\n\r\n- Phun lúc trời mát, theo đường di chuyển của ốc. \r\n\r\n• Đối với lúa\r\n\r\n+ Ốc bươu vàng: \r\n\r\n- Liều lượng: 1-1.2kg/ ha. \r\n\r\n- Lượng nước phun 320 lít/ha. \r\n\r\n- Phun thuốc khi ruộng có nước 1-5cm. \r\n\r\n• Thời gian cách ly: không xác định. ', 'ml', 'Syngenta', 0, 0, 0, 0),
(12, 1, 6, 'NeemNim Ấn Độ (100ml) – Phòng ngừa sâu bệnh, rệp sáp, sâu xanh, bọ cánh tơ', 160000, 900, 'neemim.jpg', 'NeemNim Ấn Độ là sản phẩm thuốc trừ sâu được chiết xuất hoàn toàn từ thảo mộc thiên nhiên giúp phòng trừ nhiều loại sâu hại như đục lá, rệp sáp, cánh lụa, sâu tơ, sâu xanh da láng… vô cùng an toàn cho người sử dụng và thiên nhiên.', 'Azadirachtin: 0,3% khối lượng.', '• Pha 30-50ml cho bình 16 lít.\r\n\r\n• Lượng nước phun 400-600 lít/ ha.\r\n\r\n• Phun ướt đều thân lá, lượng nước phun tùy theo cây trồng và thời gian sinh trưởng. Phun phòng ngừa và trị sau khi sâu mới xuất hiện,nếu sâu hại nặng nên phun lại lần 2 sau  7 ngày.\r\n\r\n• Không phun thuốc khi cây đang ra hoa vì sẽ xua đuổi côn trùng có ích làm giảm khả năng thụ phấn của cây.', 'kg', 'Công ty Phân bón Việt Nam', 0, 0, 0, 0);

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

--
-- Đang đổ dữ liệu cho bảng `selected_cart_items`
--

INSERT INTO `selected_cart_items` (`id`, `GH_MA`, `SP_MA`, `created_at`) VALUES
(22, 7, 12, '2025-06-18 07:26:48');

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
  ADD KEY `FK_CHI_TIE_RELATIONS_SAN_PHA` (`SP_MA`),
  ADD KEY `chi_tiet_hd_ibfk_1` (`HD_STT`);

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
  ADD KEY `hoa_don_ibfk_1` (`TT_MA`),
  ADD KEY `FK_HOA_DON_CO_PHUONG` (`PTTT_MA`),
  ADD KEY `FK_HOA_DON_DO_DON_VAN` (`DVC_MA`),
  ADD KEY `FK_HOA_DON_DO_NV_LAP_NHAN_VI` (`NV_MA`),
  ADD KEY `hoa_don_ibfk_7` (`KH_MA`),
  ADD KEY `FK_HOA_DON_KM` (`KM_MA`),
  ADD KEY `FK_HOA_DON_GH` (`GH_MA`);

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
  MODIFY `GH_MA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `gio_hang`
--
ALTER TABLE `gio_hang`
  MODIFY `GH_MA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `hoa_don`
--
ALTER TABLE `hoa_don`
  MODIFY `HD_STT` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `khach_hang`
--
ALTER TABLE `khach_hang`
  MODIFY `KH_MA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `selected_cart_items`
--
ALTER TABLE `selected_cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

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
  ADD CONSTRAINT `FK_CHI_TIE_RELATIONS_SAN_PHA` FOREIGN KEY (`SP_MA`) REFERENCES `san_pham` (`SP_MA`),
  ADD CONSTRAINT `chi_tiet_hd_ibfk_1` FOREIGN KEY (`HD_STT`) REFERENCES `hoa_don` (`HD_STT`);

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
  ADD CONSTRAINT `FK_HOA_DON_DO_DON_VAN` FOREIGN KEY (`DVC_MA`) REFERENCES `don_van_chuyen` (`DVC_MA`),
  ADD CONSTRAINT `FK_HOA_DON_DO_NV_LAP_NHAN_VI` FOREIGN KEY (`NV_MA`) REFERENCES `nhan_vien` (`NV_MA`),
  ADD CONSTRAINT `FK_HOA_DON_GH` FOREIGN KEY (`GH_MA`) REFERENCES `gio_hang` (`GH_MA`),
  ADD CONSTRAINT `FK_HOA_DON_KM` FOREIGN KEY (`KM_MA`) REFERENCES `khuyen_mai` (`KM_MA`),
  ADD CONSTRAINT `hoa_don_ibfk_1` FOREIGN KEY (`TT_MA`) REFERENCES `trang_thai` (`TT_MA`),
  ADD CONSTRAINT `hoa_don_ibfk_7` FOREIGN KEY (`KH_MA`) REFERENCES `khach_hang` (`KH_MA`);

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

-- Cập nhật thông tin kích thước và trọng lượng cho sản phẩm
UPDATE `san_pham` SET 
  `SP_TRONGLUONG` = CASE 
    WHEN `SP_MA` = 1 THEN 1000 -- N3M kích rễ
    WHEN `SP_MA` = 2 THEN 200  -- EMUNIV
    WHEN `SP_MA` = 3 THEN 1000 -- Phân dê
    WHEN `SP_MA` = 4 THEN 20   -- Bimix Super Root
    WHEN `SP_MA` = 5 THEN 235  -- Axit humic
    WHEN `SP_MA` = 6 THEN 500  -- SFARM
    WHEN `SP_MA` = 7 THEN 10   -- ATONIK
    WHEN `SP_MA` = 8 THEN 20   -- COC85
    WHEN `SP_MA` = 9 THEN 20   -- BELLKUTE
    WHEN `SP_MA` = 10 THEN 100 -- Neem Chito
    WHEN `SP_MA` = 11 THEN 50  -- HELIX
    WHEN `SP_MA` = 12 THEN 100 -- NeemNim
  END,
  `SP_CHIEUDAI` = CASE 
    WHEN `SP_MA` IN (1,2,3) THEN 20 -- Sản phẩm dạng bột/phân
    WHEN `SP_MA` IN (4,5,7,10,11,12) THEN 15 -- Sản phẩm dạng lỏng
    WHEN `SP_MA` IN (6,8,9) THEN 12 -- Sản phẩm dạng viên/gói nhỏ
  END,
  `SP_CHIEURONG` = CASE 
    WHEN `SP_MA` IN (1,2,3) THEN 15 -- Sản phẩm dạng bột/phân
    WHEN `SP_MA` IN (4,5,7,10,11,12) THEN 8 -- Sản phẩm dạng lỏng
    WHEN `SP_MA` IN (6,8,9) THEN 8 -- Sản phẩm dạng viên/gói nhỏ
  END,
  `SP_CHIEUCAO` = CASE 
    WHEN `SP_MA` IN (1,2,3) THEN 25 -- Sản phẩm dạng bột/phân
    WHEN `SP_MA` IN (4,5,7,10,11,12) THEN 20 -- Sản phẩm dạng lỏng
    WHEN `SP_MA` IN (6,8,9) THEN 5 -- Sản phẩm dạng viên/gói nhỏ
  END;
