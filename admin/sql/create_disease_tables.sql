-- Bảng loại bệnh
CREATE TABLE IF NOT EXISTS `loai_benh` (
  `Ma_loai_benh` int(11) NOT NULL AUTO_INCREMENT,
  `LCT_MA` int(11) NOT NULL,
  `Ten_loai_benh` varchar(255) NOT NULL,
  `mo_ta` text DEFAULT NULL,
  `hinh_anh` varchar(255) DEFAULT NULL,
  `cach_phong_ngua` text DEFAULT NULL,
  PRIMARY KEY (`Ma_loai_benh`),
  KEY `LCT_MA` (`LCT_MA`),
  CONSTRAINT `loai_benh_ibfk_1` FOREIGN KEY (`LCT_MA`) REFERENCES `loai_cay_trong` (`LCT_MA`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Thêm dữ liệu mẫu
INSERT INTO `loai_benh` (`LCT_MA`, `Ten_loai_benh`, `mo_ta`, `hinh_anh`, `cach_phong_ngua`) VALUES
(1, 'Bệnh đốm lá', 'Triệu chứng: Lá cây chuyển vàng bắt đầu từ gân chí', 'benh_dom_la.jpg', 'Phun thuốc'),
(1, 'Bệnh thối rễ', 'Triệu chứng: Đốm vàng cam xuất hiện mặt dưới lá, c', 'benh_thoi_re.jpg', 'Thoát nước'),
(2, 'Bệnh phấn trắng', 'Triệu chứng: Thân và cành xuất hiện lớp nấm sợi mà', 'benh_phan_trang.jpg', 'Phun thuốc'),
(2, 'Bệnh gỉ sắt', 'Triệu chứng: Xuất hiện đốm đen trên lá, hoa, quả;', 'benh_gi_sat.jpg', 'Loại bỏ lá'),
(3, 'Bệnh thán thư', 'Triệu chứng: Lá xuất hiện lớp phấn trắng, co lại v', 'benh_than_thu.jpg', 'Cắt tỉa cà'); 