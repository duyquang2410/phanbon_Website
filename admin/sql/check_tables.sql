-- Kiểm tra và sửa cấu trúc bảng phieu_nhap
ALTER TABLE phieu_nhap
MODIFY COLUMN PN_STT int(11) NOT NULL AUTO_INCREMENT,
ADD PRIMARY KEY (PN_STT) IF NOT EXISTS,
MODIFY COLUMN PN_MA varchar(20) NOT NULL AFTER PN_STT,
ADD UNIQUE INDEX idx_pn_ma (PN_MA) IF NOT EXISTS;

-- Kiểm tra và sửa cấu trúc bảng chitiet_pn
ALTER TABLE chitiet_pn
DROP PRIMARY KEY IF EXISTS,
ADD PRIMARY KEY (SP_MA, PN_STT),
ADD FOREIGN KEY (PN_STT) REFERENCES phieu_nhap(PN_STT) IF NOT EXISTS,
ADD FOREIGN KEY (SP_MA) REFERENCES san_pham(SP_MA) IF NOT EXISTS,
ADD FOREIGN KEY (NH_MA) REFERENCES nguon_hang(NH_MA) IF NOT EXISTS;

-- Kiểm tra và sửa cấu trúc bảng san_pham
ALTER TABLE san_pham
MODIFY COLUMN SP_TRANGTHAI tinyint(1) NOT NULL DEFAULT 1 COMMENT '1: Đang kinh doanh, 0: Ngừng kinh doanh' AFTER SP_MOTA;

-- Kiểm tra và sửa cấu trúc bảng nguon_hang
ALTER TABLE nguon_hang
ADD COLUMN NH_TRANGTHAI tinyint(1) NOT NULL DEFAULT 1 COMMENT '1: Đang hoạt động, 0: Ngừng hoạt động' AFTER NH_MOTA IF NOT EXISTS; 