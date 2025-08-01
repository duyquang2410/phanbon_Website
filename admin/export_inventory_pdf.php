<?php
require 'connect.php';
require_once('../vendor/tecnickcom/tcpdf/tcpdf.php');

// Tạo PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('Admin System');
$pdf->SetAuthor('Admin');
$pdf->SetTitle('Báo Cáo Tồn Kho');

// Set default header data
$pdf->SetHeaderData('', 0, 'BÁO CÁO TỒN KHO THEO DANH MỤC', 'Ngày xuất: ' . date('d/m/Y'));

// Set header and footer fonts
$pdf->setHeaderFont(Array('dejavusans', '', 14));
$pdf->setFooterFont(Array('dejavusans', '', 8));

// Set default monospaced font
$pdf->SetDefaultMonospacedFont('courier');

// Set margins
$pdf->SetMargins(15, 27, 15);
$pdf->SetHeaderMargin(5);
$pdf->SetFooterMargin(10);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 25);

// Add a page
$pdf->AddPage();

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

// Create the table content
$html = '
<style>
    table {
        border-collapse: collapse;
        width: 100%;
        margin-bottom: 20px;
    }
    th {
        background-color: #E2EFDA;
        font-weight: bold;
        text-align: center;
        padding: 5px;
    }
    td {
        padding: 5px;
        border: 1px solid #000;
    }
    .text-right {
        text-align: right;
    }
    .warning {
        color: #ff0000;
    }
</style>
<table border="1">
    <tr>
        <th>DANH MỤC</th>
        <th>SỐ LƯỢNG TỒN</th>
        <th>GIÁ TRỊ TỒN</th>
        <th>TỶ LỆ</th>
        <th>TRẠNG THÁI</th>
    </tr>';

foreach ($data as $row) {
    $percentage = ($row['total_value'] / $total_value) * 100;
    $status = ($row['total_quantity'] <= 10) ? '<span class="warning">SẮP HẾT HÀNG</span>' : 'ĐỦ HÀNG';
    
    $html .= '<tr>
        <td>' . $row['DM_TEN'] . '</td>
        <td class="text-right">' . number_format($row['total_quantity'], 0, ',', '.') . '</td>
        <td class="text-right">' . number_format($row['total_value'], 0, ',', '.') . 'đ</td>
        <td class="text-right">' . number_format($percentage, 1) . '%</td>
        <td align="center">' . $status . '</td>
    </tr>';
}

$html .= '</table>';

// Print text using writeHTMLCell()
$pdf->writeHTML($html, true, false, true, false, '');

// Close and output PDF document
$pdf->Output('bao_cao_ton_kho_' . date('Y-m-d') . '.pdf', 'D'); 