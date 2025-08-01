-- Tạo bảng nhà cung cấp
CREATE TABLE IF NOT EXISTS `nha_cung_cap` (
  `NCC_MA` int(11) NOT NULL AUTO_INCREMENT,
  `NCC_TEN` varchar(100) NOT NULL,
  `NCC_DIACHI` text DEFAULT NULL,
  `NCC_SDT` varchar(20) DEFAULT NULL,
  `NCC_EMAIL` varchar(100) DEFAULT NULL,
  `NCC_TRANGTHAI` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1: Đang hoạt động, 0: Ngưng hoạt động',
  `NCC_NGAYTAO` timestamp NOT NULL DEFAULT current_timestamp(),
  `NCC_NGAYCAPNHAT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`NCC_MA`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Thêm dữ liệu mẫu cho nhà cung cấp
INSERT INTO `nha_cung_cap` (`NCC_TEN`, `NCC_DIACHI`, `NCC_SDT`, `NCC_EMAIL`, `NCC_TRANGTHAI`) VALUES
('Công ty TNHH Phân bón Việt Nam', 'Số 123 Đường ABC, Quận 1, TP.HCM', '0901234567', 'contact@phanbon.vn', 1),
('Công ty CP Phân bón Miền Nam', '456 Đường XYZ, Quận 2, TP.HCM', '0912345678', 'info@phanbonmn.vn', 1),
('Công ty TNHH Nông nghiệp Xanh', '789 Đường DEF, Quận 3, TP.HCM', '0923456789', 'sales@nongnghiepxanh.vn', 1);

-- Tạo bảng phiếu nhập
CREATE TABLE IF NOT EXISTS `phieu_nhap` (
  `PN_MA` varchar(20) NOT NULL COMMENT 'Mã phiếu nhập, format: PNYYYYMMDDHHmmss',
  `PN_NGAYLAP` date NOT NULL,
  `NCC_MA` int(11) NOT NULL,
  `NV_MA` int(11) NOT NULL,
  `PN_GHICHU` text DEFAULT NULL,
  `PN_TRANGTHAI` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1: Hoàn thành, 0: Hủy',
  `PN_NGAYTAO` timestamp NOT NULL DEFAULT current_timestamp(),
  `PN_NGAYCAPNHAT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`PN_MA`),
  KEY `NCC_MA` (`NCC_MA`),
  KEY `NV_MA` (`NV_MA`),
  CONSTRAINT `phieu_nhap_ibfk_1` FOREIGN KEY (`NCC_MA`) REFERENCES `nha_cung_cap` (`NCC_MA`),
  CONSTRAINT `phieu_nhap_ibfk_2` FOREIGN KEY (`NV_MA`) REFERENCES `nhan_vien` (`NV_MA`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tạo bảng chi tiết phiếu nhập
CREATE TABLE IF NOT EXISTS `chi_tiet_phieu_nhap` (
  `PN_MA` varchar(20) NOT NULL,
  `SP_MA` int(11) NOT NULL,
  `CTPN_SOLUONG` decimal(10,2) NOT NULL,
  `CTPN_DONGIA` decimal(10,2) NOT NULL,
  `CTPN_THANHTIEN` decimal(10,2) GENERATED ALWAYS AS (`CTPN_SOLUONG` * `CTPN_DONGIA`) STORED,
  PRIMARY KEY (`PN_MA`,`SP_MA`),
  KEY `SP_MA` (`SP_MA`),
  CONSTRAINT `chi_tiet_phieu_nhap_ibfk_1` FOREIGN KEY (`PN_MA`) REFERENCES `phieu_nhap` (`PN_MA`),
  CONSTRAINT `chi_tiet_phieu_nhap_ibfk_2` FOREIGN KEY (`SP_MA`) REFERENCES `san_pham` (`SP_MA`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 