-- Sửa cột LOGIN_STATUS để cho phép NULL hoặc có giá trị mặc định
ALTER TABLE nhan_vien_login_history
MODIFY COLUMN LOGIN_STATUS int DEFAULT 1; 