<?php
session_start();
require 'connect.php';

// Lấy thống kê thanh toán cho các đơn đã hoàn thành
$sql_stats = "SELECT 
    pttt.PTTT_TEN,
    SUM(hd.HD_TONGTIEN) as total_amount,
    COUNT(*) as total_orders
FROM hoa_don hd
JOIN phuong_thuc_thanh_toan pttt ON hd.PTTT_MA = pttt.PTTT_MA
JOIN trang_thai tt ON hd.TT_MA = tt.TT_MA
WHERE tt.TT_TEN = 'Đã giao'
GROUP BY pttt.PTTT_MA, pttt.PTTT_TEN";

$result_stats = $conn->query($sql_stats);
$payment_stats = [];
while ($row = $result_stats->fetch_assoc()) {
    $payment_stats[] = $row;
}

// Lấy danh sách hóa đơn đã hoàn thành
$sql_orders = "SELECT 
    hd.HD_STT,
    DATE_FORMAT(hd.HD_NGAYLAP, '%d/%m/%Y') as ngay_lap,
    hd.HD_TONGTIEN,
    hd.DVC_MA,
    pttt.PTTT_TEN as phuong_thuc,
    COUNT(DISTINCT cthd.SP_MA) as so_luong_sp,
    tt.TT_TEN as trang_thai,
    kh.KH_TEN as ten_khach_hang,
    kh.KH_SDT as sdt_khach_hang,
    nvc.NVC_TEN as ten_dvc,
    dvc.DVC_DIACHI as dia_chi_dvc,
    dvc.DVC_TGBATDAU as ngay_bat_dau,
    dvc.DVC_TGHOANTHANH as ngay_ket_thuc
FROM hoa_don hd
JOIN phuong_thuc_thanh_toan pttt ON hd.PTTT_MA = pttt.PTTT_MA
JOIN trang_thai tt ON hd.TT_MA = tt.TT_MA
LEFT JOIN chi_tiet_hd cthd ON hd.HD_STT = cthd.HD_STT
LEFT JOIN khach_hang kh ON hd.KH_MA = kh.KH_MA
LEFT JOIN don_van_chuyen dvc ON hd.DVC_MA = dvc.DVC_MA
LEFT JOIN nha_van_chuyen nvc ON dvc.NVC_MA = nvc.NVC_MA
WHERE tt.TT_TEN = 'Đã giao'
GROUP BY hd.HD_STT, hd.HD_NGAYLAP, hd.HD_TONGTIEN, pttt.PTTT_TEN, tt.TT_TEN, kh.KH_TEN, kh.KH_SDT, 
         nvc.NVC_TEN, dvc.DVC_DIACHI, dvc.DVC_TGBATDAU, dvc.DVC_TGHOANTHANH
ORDER BY hd.HD_NGAYLAP DESC";

$result_orders = $conn->query($sql_orders);
?> 