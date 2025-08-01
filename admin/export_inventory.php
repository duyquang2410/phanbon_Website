<?php
require 'connect.php';

// Set headers for Excel file
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="bao_cao_ton_kho_' . date('Y-m-d') . '.xls"');
header('Cache-Control: max-age=0');

// Lấy dữ liệu từ database
$sql = "SELECT 
    dm.DM_TEN,
    COUNT(sp.SP_MA) as total_products,
    SUM(sp.SP_SOLUONGTON) as total_quantity,
    SUM(sp.SP_SOLUONGTON * sp.SP_DONGIA) as total_value
FROM danh_muc dm
LEFT JOIN san_pham sp ON dm.DM_MA = sp.DM_MA
GROUP BY dm.DM_MA, dm.DM_TEN
ORDER BY total_value DESC";

$result = $conn->query($sql);

// Calculate total value for percentage
$total_value = 0;
$data = array();
while ($row = $result->fetch_assoc()) {
    $total_value += $row['total_value'];
    $data[] = $row;
}

// Output Excel content
echo '
<table border="1">
    <tr>
        <th colspan="5" style="text-align: center; font-size: 14pt; font-weight: bold;">BÁO CÁO TỒN KHO THEO DANH MỤC</th>
    </tr>
    <tr>
        <th style="background-color: #E2EFDA; font-weight: bold;">DANH MỤC</th>
        <th style="background-color: #E2EFDA; font-weight: bold;">SỐ LƯỢNG TỒN</th>
        <th style="background-color: #E2EFDA; font-weight: bold;">GIÁ TRỊ TỒN</th>
        <th style="background-color: #E2EFDA; font-weight: bold;">TỶ LỆ</th>
        <th style="background-color: #E2EFDA; font-weight: bold;">TRẠNG THÁI</th>
    </tr>';

foreach ($data as $row) {
    $percentage = ($row['total_value'] / $total_value) * 100;
    $status = ($row['total_quantity'] <= 10) ? 'SẮP HẾT HÀNG' : 'ĐỦ HÀNG';
    
    echo '<tr>
        <td>' . $row['DM_TEN'] . '</td>
        <td style="text-align: right;">' . number_format($row['total_quantity'], 0, ',', '.') . '</td>
        <td style="text-align: right;">' . number_format($row['total_value'], 0, ',', '.') . 'đ</td>
        <td style="text-align: right;">' . number_format($percentage, 1) . '%</td>
        <td>' . $status . '</td>
    </tr>';
}

echo '</table>'; 