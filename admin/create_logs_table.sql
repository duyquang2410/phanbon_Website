-- Tạo bảng logs theo quy ước đặt tên của hệ thống
CREATE TABLE `lich_su_ton_kho` (
  `LSTK_MA` int(11) NOT NULL AUTO_INCREMENT,
  `SP_MA` int(11) NOT NULL,
  `LSTK_LOAI` varchar(50) NOT NULL COMMENT 'Loại điều chỉnh: NHAP/XUAT/DIEU_CHINH',
  `LSTK_SOLUONG` int(11) NOT NULL COMMENT 'Số lượng thay đổi',
  `LSTK_SOLUONG_CU` int(11) NOT NULL COMMENT 'Số lượng trước khi thay đổi',
  `LSTK_SOLUONG_MOI` int(11) NOT NULL COMMENT 'Số lượng sau khi thay đổi',
  `LSTK_GHICHU` text DEFAULT NULL COMMENT 'Lý do điều chỉnh',
  `NV_MA` int(11) DEFAULT NULL COMMENT 'Mã nhân viên thực hiện',
  `LSTK_THOIGIAN` timestamp NOT NULL DEFAULT current_timestamp(),
  `LSTK_THAMCHIEU` varchar(50) DEFAULT NULL COMMENT 'Mã tham chiếu (HD_STT nếu là đơn hàng, PN_STT nếu là phiếu nhập)',
  PRIMARY KEY (`LSTK_MA`),
  KEY `SP_MA` (`SP_MA`),
  KEY `NV_MA` (`NV_MA`),
  CONSTRAINT `lich_su_ton_kho_ibfk_1` FOREIGN KEY (`SP_MA`) REFERENCES `san_pham` (`SP_MA`),
  CONSTRAINT `lich_su_ton_kho_ibfk_2` FOREIGN KEY (`NV_MA`) REFERENCES `nhan_vien` (`NV_MA`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

-- Tạo bảng stock_movements để theo dõi thay đổi tồn kho
CREATE TABLE `stock_movements` (
  `SM_MA` int(11) NOT NULL AUTO_INCREMENT,
  `SP_MA` int(11) NOT NULL,
  `SM_LOAI` varchar(50) NOT NULL COMMENT 'Loại điều chỉnh: NHAP/XUAT/HUY',
  `SM_SOLUONG` int(11) NOT NULL COMMENT 'Số lượng thay đổi',
  `SM_SOLUONG_CU` int(11) NOT NULL COMMENT 'Số lượng tồn trước khi thay đổi',
  `SM_SOLUONG_MOI` int(11) NOT NULL COMMENT 'Số lượng tồn sau khi thay đổi',
  `SM_AVAILABLE_CU` int(11) NOT NULL COMMENT 'Số lượng khả dụng trước khi thay đổi',
  `SM_AVAILABLE_MOI` int(11) NOT NULL COMMENT 'Số lượng khả dụng sau khi thay đổi',
  `SM_DONGIA` decimal(10,2) NOT NULL COMMENT 'Đơn giá tại thời điểm thay đổi',
  `SM_GHICHU` text DEFAULT NULL COMMENT 'Lý do điều chỉnh',
  `SM_THAMCHIEU` varchar(50) DEFAULT NULL COMMENT 'Mã tham chiếu (HD_STT nếu là đơn hàng)',
  `SM_THOIGIAN` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`SM_MA`),
  KEY `SP_MA` (`SP_MA`),
  CONSTRAINT `stock_movements_ibfk_1` FOREIGN KEY (`SP_MA`) REFERENCES `san_pham` (`SP_MA`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 