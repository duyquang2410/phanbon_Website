-- Kiểm tra và xóa khóa chính cũ nếu có
ALTER TABLE hoan_tien DROP PRIMARY KEY;

-- Thêm các trường mới vào bảng hoan_tien nếu chưa có
ALTER TABLE hoan_tien
ADD COLUMN IF NOT EXISTS HT_MAGIAODICH varchar(50) NOT NULL AFTER HT_SOTIEN,
ADD COLUMN IF NOT EXISTS HT_GHICHU text DEFAULT NULL AFTER HT_NGAYCAPNHAT;

-- Thêm khóa chính tự động tăng
ALTER TABLE hoan_tien MODIFY COLUMN HT_MA int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY;

-- Xóa khóa ngoại cũ nếu có
ALTER TABLE hoan_tien DROP FOREIGN KEY IF EXISTS fk_hoan_tien_hoa_don;

-- Thêm khóa ngoại với bảng hoa_don
ALTER TABLE hoan_tien
ADD CONSTRAINT fk_hoan_tien_hoa_don
FOREIGN KEY (HD_STT) REFERENCES hoa_don(HD_STT)
ON DELETE RESTRICT
ON UPDATE CASCADE; 