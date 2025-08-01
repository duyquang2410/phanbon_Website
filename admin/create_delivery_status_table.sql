-- Tạo bảng delivery_status nếu chưa tồn tại
CREATE TABLE IF NOT EXISTS delivery_status (
    id INT AUTO_INCREMENT PRIMARY KEY,
    HD_STT INT NOT NULL,
    status ENUM('NEW', 'DELIVERING', 'DELIVERED', 'FAILED') NOT NULL DEFAULT 'NEW',
    tracking_info TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (HD_STT) REFERENCES hoa_don(HD_STT)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tạo trigger để tự động thêm bản ghi vào delivery_status khi đơn hàng chuyển sang trạng thái đang giao
DELIMITER //
CREATE TRIGGER IF NOT EXISTS tr_create_delivery_status
AFTER UPDATE ON hoa_don
FOR EACH ROW
BEGIN
    IF NEW.TT_MA = 2 AND OLD.TT_MA != 2 THEN
        INSERT INTO delivery_status (HD_STT, status, tracking_info)
        VALUES (NEW.HD_STT, 'NEW', CONCAT('Đơn hàng #', NEW.HD_STT, ' bắt đầu giao - ', NOW()));
    END IF;
END //
DELIMITER ; 