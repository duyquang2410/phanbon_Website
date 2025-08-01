-- Thêm trường NH_TRANGTHAI vào bảng nguon_hang
ALTER TABLE nguon_hang 
ADD COLUMN NH_TRANGTHAI tinyint(1) NOT NULL DEFAULT 1 COMMENT '1: Đang hoạt động, 0: Ngừng hoạt động' AFTER NH_MOTA;
 
-- Cập nhật trạng thái cho các nhà cung cấp hiện có
UPDATE nguon_hang SET NH_TRANGTHAI = 1; 