<?php
include 'connect.php';
require_once('../vendor/tecnickcom/tcpdf/tcpdf.php');

// Kiểm tra tham số
$reportType = isset($_GET['type']) ? $_GET['type'] : 'revenue';
$format = isset($_GET['format']) ? $_GET['format'] : 'pdf';
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : date('Y-m-01');
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : date('Y-m-d');

// Tạo tên file
$filename = "bao_cao_{$reportType}_" . date('Ymd_His');

if ($format === 'excel') {
    // Xuất file Excel
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
    
    echo '<table border="1">';
    
    switch ($reportType) {
        case 'revenue':
            // Báo cáo doanh thu
            $query = "SELECT 
                        DATE(HD_NGAYLAP) as date,
                        COUNT(*) as total_orders,
                        SUM(HD_TONGTIEN) as total_revenue,
                        AVG(HD_TONGTIEN) as avg_order_value
                     FROM hoa_don
                     WHERE HD_NGAYLAP BETWEEN '$startDate' AND '$endDate'
                        AND HD_TRANGTHAI = 'Hoàn thành'
                     GROUP BY DATE(HD_NGAYLAP)
                     ORDER BY date";
            
            echo '<tr>';
            echo '<th>Ngày</th>';
            echo '<th>Số đơn hàng</th>';
            echo '<th>Doanh thu</th>';
            echo '<th>Giá trị trung bình</th>';
            echo '</tr>';
            
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $row['date'] . '</td>';
                echo '<td>' . $row['total_orders'] . '</td>';
                echo '<td>' . number_format($row['total_revenue']) . '</td>';
                echo '<td>' . number_format($row['avg_order_value']) . '</td>';
                echo '</tr>';
            }
            break;
            
        case 'products':
            // Báo cáo sản phẩm
            $query = "SELECT 
                        sp.SP_MA,
                        sp.SP_TEN,
                        dm.DM_TEN as category_name,
                        COUNT(DISTINCT hd.HD_STT) as total_orders,
                        SUM(ct.CTHD_SOLUONG) as total_quantity,
                        SUM(ct.CTHD_SOLUONG * ct.CTHD_DONGIA) as total_revenue
                     FROM san_pham sp
                     LEFT JOIN danh_muc dm ON sp.DM_MA = dm.DM_MA
                     LEFT JOIN chi_tiet_hd ct ON sp.SP_MA = ct.SP_MA
                     LEFT JOIN hoa_don hd ON ct.HD_STT = hd.HD_STT
                        AND hd.HD_TRANGTHAI = 'Hoàn thành'
                        AND hd.HD_NGAYLAP BETWEEN '$startDate' AND '$endDate'
                     GROUP BY sp.SP_MA, sp.SP_TEN, dm.DM_TEN
                     ORDER BY total_revenue DESC";
            
            echo '<tr>';
            echo '<th>Mã SP</th>';
            echo '<th>Tên sản phẩm</th>';
            echo '<th>Danh mục</th>';
            echo '<th>Số đơn</th>';
            echo '<th>Số lượng bán</th>';
            echo '<th>Doanh thu</th>';
            echo '</tr>';
            
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $row['SP_MA'] . '</td>';
                echo '<td>' . $row['SP_TEN'] . '</td>';
                echo '<td>' . $row['category_name'] . '</td>';
                echo '<td>' . $row['total_orders'] . '</td>';
                echo '<td>' . $row['total_quantity'] . '</td>';
                echo '<td>' . number_format($row['total_revenue']) . '</td>';
                echo '</tr>';
            }
            break;
            
        case 'customers':
            // Báo cáo khách hàng
            $query = "SELECT 
                        kh.KH_MA,
                        kh.KH_TEN,
                        kh.KH_EMAIL,
                        COUNT(DISTINCT hd.HD_STT) as total_orders,
                        SUM(hd.HD_TONGTIEN) as total_spent,
                        MAX(hd.HD_NGAYLAP) as last_order_date
                     FROM khach_hang kh
                     LEFT JOIN hoa_don hd ON kh.KH_MA = hd.KH_MA
                        AND hd.HD_TRANGTHAI = 'Hoàn thành'
                        AND hd.HD_NGAYLAP BETWEEN '$startDate' AND '$endDate'
                     GROUP BY kh.KH_MA, kh.KH_TEN, kh.KH_EMAIL
                     HAVING total_orders > 0
                     ORDER BY total_spent DESC";
            
            echo '<tr>';
            echo '<th>Mã KH</th>';
            echo '<th>Tên khách hàng</th>';
            echo '<th>Email</th>';
            echo '<th>Số đơn hàng</th>';
            echo '<th>Tổng chi tiêu</th>';
            echo '<th>Đơn hàng gần nhất</th>';
            echo '</tr>';
            
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $row['KH_MA'] . '</td>';
                echo '<td>' . $row['KH_TEN'] . '</td>';
                echo '<td>' . $row['KH_EMAIL'] . '</td>';
                echo '<td>' . $row['total_orders'] . '</td>';
                echo '<td>' . number_format($row['total_spent']) . '</td>';
                echo '<td>' . $row['last_order_date'] . '</td>';
                echo '</tr>';
            }
            break;
    }
    
    echo '</table>';
    
} else {
    // Xuất file PDF
    class MYPDF extends TCPDF {
        public function Header() {
            $this->SetFont('dejavusans', 'B', 15);
            $this->Cell(0, 15, 'BÁO CÁO THỐNG KÊ', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        }
        
        public function Footer() {
            $this->SetY(-15);
            $this->SetFont('dejavusans', 'I', 8);
            $this->Cell(0, 10, 'Trang '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        }
    }
    
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    $pdf->SetCreator('Phân bón Website');
    $pdf->SetAuthor('Admin');
    $pdf->SetTitle('Báo cáo thống kê');
    
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
    
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    
    $pdf->SetFont('dejavusans', '', 10);
    
    $pdf->AddPage();
    
    switch ($reportType) {
        case 'revenue':
            // Báo cáo doanh thu
            $pdf->SetFont('dejavusans', 'B', 12);
            $pdf->Cell(0, 10, 'BÁO CÁO DOANH THU', 0, 1, 'C');
            $pdf->Cell(0, 10, "Từ ngày $startDate đến ngày $endDate", 0, 1, 'C');
            
            $pdf->SetFont('dejavusans', '', 10);
            
            $query = "SELECT 
                        DATE(HD_NGAYLAP) as date,
                        COUNT(*) as total_orders,
                        SUM(HD_TONGTIEN) as total_revenue,
                        AVG(HD_TONGTIEN) as avg_order_value
                     FROM hoa_don
                     WHERE HD_NGAYLAP BETWEEN '$startDate' AND '$endDate'
                        AND HD_TRANGTHAI = 'Hoàn thành'
                     GROUP BY DATE(HD_NGAYLAP)
                     ORDER BY date";
            
            $result = mysqli_query($conn, $query);
            
            $header = array('Ngày', 'Số đơn hàng', 'Doanh thu', 'Giá trị TB');
            $w = array(40, 30, 60, 60);
            
            // Header
            for($i = 0; $i < count($header); $i++)
                $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C');
            $pdf->Ln();
            
            // Data
            while($row = mysqli_fetch_array($result)) {
                $pdf->Cell($w[0], 6, $row['date'], 1);
                $pdf->Cell($w[1], 6, $row['total_orders'], 1, 0, 'R');
                $pdf->Cell($w[2], 6, number_format($row['total_revenue']), 1, 0, 'R');
                $pdf->Cell($w[3], 6, number_format($row['avg_order_value']), 1, 0, 'R');
                $pdf->Ln();
            }
            break;
            
        case 'products':
            // Báo cáo sản phẩm
            $pdf->SetFont('dejavusans', 'B', 12);
            $pdf->Cell(0, 10, 'BÁO CÁO SẢN PHẨM', 0, 1, 'C');
            $pdf->Cell(0, 10, "Từ ngày $startDate đến ngày $endDate", 0, 1, 'C');
            
            $pdf->SetFont('dejavusans', '', 10);
            
            $query = "SELECT 
                        sp.SP_MA,
                        sp.SP_TEN,
                        dm.DM_TEN as category_name,
                        COUNT(DISTINCT hd.HD_STT) as total_orders,
                        SUM(ct.CTHD_SOLUONG) as total_quantity,
                        SUM(ct.CTHD_SOLUONG * ct.CTHD_DONGIA) as total_revenue
                     FROM san_pham sp
                     LEFT JOIN danh_muc dm ON sp.DM_MA = dm.DM_MA
                     LEFT JOIN chi_tiet_hd ct ON sp.SP_MA = ct.SP_MA
                     LEFT JOIN hoa_don hd ON ct.HD_STT = hd.HD_STT
                        AND hd.HD_TRANGTHAI = 'Hoàn thành'
                        AND hd.HD_NGAYLAP BETWEEN '$startDate' AND '$endDate'
                     GROUP BY sp.SP_MA, sp.SP_TEN, dm.DM_TEN
                     ORDER BY total_revenue DESC";
            
            $result = mysqli_query($conn, $query);
            
            $header = array('Mã SP', 'Tên SP', 'Danh mục', 'Số đơn', 'SL bán', 'Doanh thu');
            $w = array(20, 50, 30, 20, 20, 40);
            
            // Header
            for($i = 0; $i < count($header); $i++)
                $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C');
            $pdf->Ln();
            
            // Data
            while($row = mysqli_fetch_array($result)) {
                $pdf->Cell($w[0], 6, $row['SP_MA'], 1);
                $pdf->Cell($w[1], 6, $row['SP_TEN'], 1);
                $pdf->Cell($w[2], 6, $row['category_name'], 1);
                $pdf->Cell($w[3], 6, $row['total_orders'], 1, 0, 'R');
                $pdf->Cell($w[4], 6, $row['total_quantity'], 1, 0, 'R');
                $pdf->Cell($w[5], 6, number_format($row['total_revenue']), 1, 0, 'R');
                $pdf->Ln();
            }
            break;
            
        case 'customers':
            // Báo cáo khách hàng
            $pdf->SetFont('dejavusans', 'B', 12);
            $pdf->Cell(0, 10, 'BÁO CÁO KHÁCH HÀNG', 0, 1, 'C');
            $pdf->Cell(0, 10, "Từ ngày $startDate đến ngày $endDate", 0, 1, 'C');
            
            $pdf->SetFont('dejavusans', '', 10);
            
            $query = "SELECT 
                        kh.KH_MA,
                        kh.KH_TEN,
                        kh.KH_EMAIL,
                        COUNT(DISTINCT hd.HD_STT) as total_orders,
                        SUM(hd.HD_TONGTIEN) as total_spent,
                        MAX(hd.HD_NGAYLAP) as last_order_date
                     FROM khach_hang kh
                     LEFT JOIN hoa_don hd ON kh.KH_MA = hd.KH_MA
                        AND hd.HD_TRANGTHAI = 'Hoàn thành'
                        AND hd.HD_NGAYLAP BETWEEN '$startDate' AND '$endDate'
                     GROUP BY kh.KH_MA, kh.KH_TEN, kh.KH_EMAIL
                     HAVING total_orders > 0
                     ORDER BY total_spent DESC";
            
            $result = mysqli_query($conn, $query);
            
            $header = array('Mã KH', 'Tên KH', 'Email', 'Số đơn', 'Tổng chi', 'Đơn gần nhất');
            $w = array(20, 40, 50, 20, 30, 30);
            
            // Header
            for($i = 0; $i < count($header); $i++)
                $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C');
            $pdf->Ln();
            
            // Data
            while($row = mysqli_fetch_array($result)) {
                $pdf->Cell($w[0], 6, $row['KH_MA'], 1);
                $pdf->Cell($w[1], 6, $row['KH_TEN'], 1);
                $pdf->Cell($w[2], 6, $row['KH_EMAIL'], 1);
                $pdf->Cell($w[3], 6, $row['total_orders'], 1, 0, 'R');
                $pdf->Cell($w[4], 6, number_format($row['total_spent']), 1, 0, 'R');
                $pdf->Cell($w[5], 6, $row['last_order_date'], 1);
                $pdf->Ln();
            }
            break;
    }
    
    $pdf->Output($filename . '.pdf', 'D');
}
?> 