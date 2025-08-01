-- Thêm các cột đăng nhập và phân quyền vào bảng nhan_vien
ALTER TABLE nhan_vien
ADD COLUMN NV_USERNAME varchar(50) NOT NULL AFTER NV_TEN,
ADD COLUMN NV_PASSWORD varchar(255) NOT NULL AFTER NV_USERNAME,
ADD COLUMN NV_QUYEN ENUM('ADMIN', 'NHAN_VIEN') NOT NULL DEFAULT 'NHAN_VIEN' AFTER NV_PASSWORD,
ADD UNIQUE (NV_USERNAME);

-- Cập nhật tài khoản mặc định cho admin
UPDATE nhan_vien 
SET NV_USERNAME = 'admin',
    NV_PASSWORD = '123456',
    NV_QUYEN = 'ADMIN'
WHERE NV_MA = 1;

-- Cập nhật tài khoản mặc định cho nhân viên
UPDATE nhan_vien 
SET NV_USERNAME = CONCAT('nv', NV_MA),
    NV_PASSWORD = '123456',
    NV_QUYEN = 'NHAN_VIEN'
WHERE NV_MA > 1; 