-- Thêm trường NV_TRANGTHAI vào bảng nhan_vien
ALTER TABLE nhan_vien 
ADD COLUMN NV_TRANGTHAI tinyint(1) NOT NULL DEFAULT 1 COMMENT '1: Đang làm việc, 0: Đã nghỉ việc' AFTER NV_MATKHAU;

-- Cập nhật trạng thái cho các nhân viên hiện có
UPDATE nhan_vien SET NV_TRANGTHAI = 1;

-- Thêm cột LOGIN_IP vào bảng nhan_vien_login_history nếu chưa có
ALTER TABLE nhan_vien_login_history
ADD COLUMN LOGIN_IP varchar(45) DEFAULT NULL COMMENT 'IP address of the login attempt' AFTER LOGIN_STATUS; 