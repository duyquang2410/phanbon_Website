-- Bảng triệu chứng
CREATE TABLE IF NOT EXISTS `symptoms` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `symptom_name` varchar(255) NOT NULL,
    `description` text,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng liên kết bệnh và triệu chứng
CREATE TABLE IF NOT EXISTS `disease_symptoms` (
    `disease_id` int(11) NOT NULL,
    `symptom_id` int(11) NOT NULL,
    PRIMARY KEY (`disease_id`, `symptom_id`),
    FOREIGN KEY (`disease_id`) REFERENCES `loai_benh`(`LB_MA`),
    FOREIGN KEY (`symptom_id`) REFERENCES `symptoms`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng liên kết bệnh và sản phẩm điều trị
CREATE TABLE IF NOT EXISTS `disease_products` (
    `disease_id` int(11) NOT NULL,
    `product_id` int(11) NOT NULL,
    `recommendation_type` enum('prevention','treatment') NOT NULL,
    `priority` int(11) DEFAULT 0,
    PRIMARY KEY (`disease_id`, `product_id`),
    FOREIGN KEY (`disease_id`) REFERENCES `loai_benh`(`LB_MA`),
    FOREIGN KEY (`product_id`) REFERENCES `san_pham`(`SP_MA`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Thêm dữ liệu mẫu cho triệu chứng
INSERT INTO `symptoms` (`symptom_name`, `description`) VALUES
('Vàng lá', 'Lá cây chuyển màu vàng'),
('Thối rễ', 'Rễ cây bị thối, màu nâu hoặc đen'),
('Đốm lá', 'Xuất hiện các đốm màu nâu hoặc đen trên lá'),
('Héo rũ', 'Cây bị héo, lá rũ xuống'),
('Còi cọc', 'Cây phát triển chậm, kích thước nhỏ');

-- Thêm dữ liệu mẫu cho liên kết bệnh-triệu chứng
INSERT INTO `disease_symptoms` (`disease_id`, `symptom_id`)
SELECT lb.LB_MA, s.id
FROM loai_benh lb
CROSS JOIN symptoms s
WHERE lb.LB_TEN LIKE '%vàng lá%' AND s.symptom_name = 'Vàng lá'
   OR lb.LB_TEN LIKE '%thối%' AND s.symptom_name = 'Thối rễ'
   OR lb.LB_TEN LIKE '%đốm%' AND s.symptom_name = 'Đốm lá'
LIMIT 5;

-- Thêm dữ liệu mẫu cho liên kết bệnh-sản phẩm
INSERT INTO `disease_products` (`disease_id`, `product_id`, `recommendation_type`, `priority`)
SELECT lb.LB_MA, sp.SP_MA, 'treatment', 1
FROM loai_benh lb
CROSS JOIN san_pham sp
WHERE sp.DM_MA IN (4, 5, 6) -- Danh mục thuốc BVTV
LIMIT 5; 