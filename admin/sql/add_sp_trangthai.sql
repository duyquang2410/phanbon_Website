-- Thêm trường SP_TRANGTHAI vào bảng san_pham
ALTER TABLE san_pham 
ADD COLUMN SP_TRANGTHAI tinyint(1) NOT NULL DEFAULT 1 COMMENT '1: Đang kinh doanh, 0: Ngừng kinh doanh' AFTER SP_MOTA;
 
-- Cập nhật trạng thái cho các sản phẩm hiện có
UPDATE san_pham SET SP_TRANGTHAI = 1; 