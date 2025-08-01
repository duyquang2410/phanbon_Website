-- Thêm các cột mới vào bảng phieu_nhap
ALTER TABLE phieu_nhap
ADD COLUMN PN_MA varchar(20) NOT NULL AFTER PN_STT,
ADD COLUMN PN_GHICHU text DEFAULT NULL AFTER PN_NGAYNHAP,
ADD COLUMN PN_TRANGTHAI enum('Chờ duyệt','Đã duyệt','Đã hủy') NOT NULL DEFAULT 'Chờ duyệt' AFTER PN_GHICHU,
ADD COLUMN PN_NGAYTAO timestamp NOT NULL DEFAULT current_timestamp() AFTER PN_TRANGTHAI,
ADD COLUMN PN_NGAYCAPNHAT timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() AFTER PN_NGAYTAO;

-- Cập nhật dữ liệu cho cột PN_MA cho các bản ghi cũ
UPDATE phieu_nhap 
SET PN_MA = CONCAT('PN', DATE_FORMAT(PN_NGAYNHAP, '%Y%m%d'), LPAD(PN_STT, 4, '0')); 