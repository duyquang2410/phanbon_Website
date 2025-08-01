-- Thêm trạng thái "Chờ giao" vào bảng trang_thai
INSERT INTO trang_thai (TT_MA, TT_TEN, ngay_cap_nhat) 
VALUES (6, 'Chờ giao', NOW());

-- Cập nhật các đơn hàng thanh toán VNPay sang trạng thái chờ giao
UPDATE hoa_don 
SET TT_MA = 6 
WHERE PTTT_MA = 2 -- Giả sử PTTT_MA = 2 là VNPay
AND TT_MA = 1; -- Chỉ cập nhật các đơn đang ở trạng thái chờ xác nhận 