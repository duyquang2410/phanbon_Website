<?php
require_once 'vendor/autoload.php';
require_once 'config.php';
require_once 'connect.php';
require_once 'create_logs.php';

// Bắt đầu output buffering
ob_start();

// Khởi tạo logger
$logger = Logger::getInstance('logs/invoice.log');

try {
    // Kiểm tra đăng nhập và quyền truy cập
    session_start();
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Unauthorized access');
    }

    // Lấy order ID từ request
    $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
    if (!$order_id) {
        throw new Exception('Invalid order ID');
    }

    // Log thông tin request
    $logger->info('Generating invoice', [
        'user_id' => $_SESSION['user_id'],
        'order_id' => $order_id
    ]);

    // Lấy thông tin đơn hàng
    $sql = "SELECT 
                hd.HD_STT,
                hd.HD_NGAYLAP,
                hd.HD_TONGTIEN,
                hd.HD_PHISHIP,
                kh.KH_TEN,
                kh.KH_SDT,
                kh.KH_EMAIL,
                hd.HD_DIACHI,
                hd.HD_TENNGUOINHAN,
                hd.HD_SDT as HD_SDT_NHAN,
                tt.TT_TEN as TRANGTHAI,
                pttt.PTTT_TEN as PHUONGTHUC,
                nvc.NVC_TEN as NHAVANCHUYEN,
                km.KM_MA,
                km.Code as KM_CODE,
                km.KM_GIATRI,
                km.hinh_thuc_km,
                km.KM_MOTA
            FROM hoa_don hd
            LEFT JOIN khach_hang kh ON hd.KH_MA = kh.KH_MA
            LEFT JOIN trang_thai tt ON hd.TT_MA = tt.TT_MA
            LEFT JOIN phuong_thuc_thanh_toan pttt ON hd.PTTT_MA = pttt.PTTT_MA
            LEFT JOIN don_van_chuyen dvc ON hd.DVC_MA = dvc.DVC_MA
            LEFT JOIN nha_van_chuyen nvc ON dvc.NVC_MA = nvc.NVC_MA
            LEFT JOIN khuyen_mai km ON hd.KM_MA = km.KM_MA
            WHERE hd.HD_STT = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();

    if (!$order) {
        throw new Exception('Order not found');
    }

    // Lấy chi tiết sản phẩm
    $sql = "SELECT 
                sp.SP_MA,
                sp.SP_TEN,
                sp.SP_DONGIA,
                cthd.CTHD_SOLUONG,
                cthd.CTHD_DONGIA,
                cthd.CTHD_DONVITINH,
                (cthd.CTHD_SOLUONG * cthd.CTHD_DONGIA) as THANH_TIEN
            FROM chi_tiet_hd cthd
            JOIN san_pham sp ON cthd.SP_MA = sp.SP_MA
            WHERE cthd.HD_STT = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Tạo PDF
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Thiết lập thông tin PDF
    $pdf->SetCreator('Your Company');
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('Hóa đơn #' . $order['HD_STT']);

    // Xóa header và footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // Thêm trang mới
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('dejavusans', '', 10);

    // Thiết lập margins
    $pdf->SetMargins(15, 15, 15);

    // Thêm logo công ty (nếu có file logo.png trong img/)
    $logoPath = __DIR__ . '/img/logo.png';
    if (file_exists($logoPath)) {
        $pdf->Image($logoPath, 15, 10, 30, 0, '', '', '', false, 300, '', false, false, 0, false, false, false);
        $pdf->SetY(15);
    } else {
        $pdf->SetY(20);
    }

    // Tiêu đề căn giữa
    $pdf->SetFont('dejavusans', 'B', 16);
    $pdf->Cell(0, 15, 'HÓA ĐƠN BÁN HÀNG', 0, 1, 'C');
    $pdf->SetFont('dejavusans', '', 10);

    // Thông tin shop (Cần Thơ)
    $pdf->SetFont('dejavusans', '', 10);
    $pdf->Cell(0, 6, 'CÔNG TY TNHH PHÂN BÓN XANH', 0, 1, 'C');
    $pdf->Cell(0, 6, 'Địa chỉ: 222 Nguyễn Văn Cừ, Ninh Kiều, Cần Thơ', 0, 1, 'C');
    $pdf->Cell(0, 6, 'Điện thoại: 0292 123 4567 | Email: info@phanboncantho.vn', 0, 1, 'C');
    $pdf->Ln(2);
    $pdf->Line(15, $pdf->GetY(), $pdf->GetPageWidth() - 15, $pdf->GetY());
    $pdf->Ln(2);

    // Hiển thị thông tin đơn hàng
    $pdf->SetFont('dejavusans', 'B', 12);
    $pdf->Cell(0, 12, 'THÔNG TIN ĐƠN HÀNG', 0, 1, 'C');
    $pdf->SetFont('dejavusans', '', 10);
    $pdf->Ln(2);

    // Tăng khoảng cách giữa các dòng
    $lineHeight = 8;

    $pdf->Cell(45, $lineHeight, 'Mã đơn hàng:', 0, 0);
    $pdf->Cell(0, $lineHeight, '#' . $order['HD_STT'], 0, 1);

    $pdf->Cell(45, $lineHeight, 'Ngày đặt:', 0, 0);
    $pdf->Cell(0, $lineHeight, date('d/m/Y H:i', strtotime($order['HD_NGAYLAP'])), 0, 1);

    $pdf->Cell(45, $lineHeight, 'Trạng thái:', 0, 0);
    $pdf->Cell(0, $lineHeight, $order['TRANGTHAI'], 0, 1);

    $pdf->Cell(45, $lineHeight, 'Thanh toán:', 0, 0);
    $pdf->Cell(0, $lineHeight, $order['PHUONGTHUC'], 0, 1);

    $pdf->Cell(45, $lineHeight, 'Vận chuyển:', 0, 0);
    $pdf->Cell(0, $lineHeight, $order['NHAVANCHUYEN'], 0, 1);

    // Thông tin giao hàng
    $pdf->Ln(8);
    $pdf->SetFont('dejavusans', 'B', 12);
    $pdf->Cell(0, 12, 'THÔNG TIN GIAO HÀNG', 0, 1, 'C');
    $pdf->SetFont('dejavusans', '', 10);
    $pdf->Ln(2);

    $pdf->Cell(45, $lineHeight, 'Người nhận:', 0, 0);
    $pdf->Cell(0, $lineHeight, $order['HD_TENNGUOINHAN'] ?? $order['KH_TEN'], 0, 1);

    $pdf->Cell(45, $lineHeight, 'Số điện thoại:', 0, 0);
    $pdf->Cell(0, $lineHeight, $order['HD_SDT_NHAN'] ?? $order['KH_SDT'], 0, 1);

    // Lấy địa chỉ giao hàng từ bảng dia_chi_giao_hang
    $sql = "SELECT * FROM dia_chi_giao_hang WHERE DH_MA = ? ORDER BY DCGH_MA DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $address = $stmt->get_result()->fetch_assoc();

    // Sử dụng địa chỉ đầy đủ từ bảng dia_chi_giao_hang hoặc fallback về địa chỉ từ hóa đơn
    $diachi_full = $address ? $address['DCGH_DIACHI'] : $order['HD_DIACHI'];

    $pdf->Cell(45, $lineHeight, 'Địa chỉ:', 0, 0);
    $pdf->MultiCell(0, $lineHeight, $diachi_full, 0, 'L');

    // Chi tiết sản phẩm
    $pdf->Ln(8);
    $pdf->SetFont('dejavusans', 'B', 12);
    $pdf->Cell(0, 12, 'CHI TIẾT SẢN PHẨM', 0, 1, 'C');
    $pdf->SetFont('dejavusans', '', 10);
    $pdf->Ln(2);

    // Header của bảng sản phẩm - điều chỉnh độ rộng các cột
    $pdf->SetFillColor(240, 240, 240);
    
    // Tính toán độ rộng trang và các cột
    $pageWidth = $pdf->GetPageWidth() - 30; // Trừ đi margins
    $col1 = 15;  // STT
    $col2 = $pageWidth - ($col1 + 25 + 35 + 35);  // Tên sản phẩm (phần còn lại)
    $col3 = 25;  // Số lượng
    $col4 = 35;  // Đơn giá
    $col5 = 35;  // Thành tiền

    // Header của bảng
    $pdf->Cell($col1, 10, 'STT', 1, 0, 'C', true);
    $pdf->Cell($col2, 10, 'Tên sản phẩm', 1, 0, 'C', true);
    $pdf->Cell($col3, 10, 'SL', 1, 0, 'C', true);
    $pdf->Cell($col4, 10, 'Đơn giá', 1, 0, 'C', true);
    $pdf->Cell($col5, 10, 'Thành tiền', 1, 1, 'C', true);

    // Chi tiết sản phẩm
    $stt = 1;
    $subtotal = 0;
    foreach ($products as $item) {
        // Tính toán chiều cao cần thiết cho tên sản phẩm
        $pdf->SetFont('dejavusans', '', 10);
        
        // Lưu vị trí Y hiện tại
        $startY = $pdf->GetY();
        
        // Tính toán chiều cao cần thiết cho tên sản phẩm
        $productName = $item['SP_TEN'];
        $pdf->startTransaction();
        $pdf->MultiCell($col2, 6, $productName, 0, 'L');
        $productHeight = $pdf->GetY() - $startY;
        $pdf->rollbackTransaction(true);
        
        // Đảm bảo chiều cao tối thiểu
        $rowHeight = max($productHeight, 8);
        
        // In STT
        $pdf->Cell($col1, $rowHeight, $stt++, 1, 0, 'C');
        
        // In tên sản phẩm với MultiCell
        $currentX = $pdf->GetX();
        $currentY = $pdf->GetY();
        $pdf->MultiCell($col2, $rowHeight, $productName, 1, 'L');
        
        // Di chuyển về đúng vị trí cho các cột tiếp theo
        $pdf->SetXY($currentX + $col2, $currentY);
        
        // In các thông tin còn lại
        $pdf->Cell($col3, $rowHeight, number_format($item['CTHD_SOLUONG'], 0), 1, 0, 'C');
        $pdf->Cell($col4, $rowHeight, number_format($item['CTHD_DONGIA'], 0) . 'đ', 1, 0, 'R');
        $pdf->Cell($col5, $rowHeight, number_format($item['THANH_TIEN'], 0) . 'đ', 1, 1, 'R');
        
        $subtotal += $item['THANH_TIEN'];
    }

    // Tổng cộng
    $pdf->Ln(5);
    $summaryX = $pdf->GetX() + $col1 + $col2;
    $pdf->SetX($summaryX);
    
    // Căn chỉnh các dòng tổng
    $summaryCol1 = $col3 + $col4;  // Cột label
    $summaryCol2 = $col5;          // Cột giá trị
    
    $pdf->Cell($summaryCol1, 8, 'Tạm tính:', 0, 0, 'R');
    $pdf->Cell($summaryCol2, 8, number_format($subtotal, 0) . 'đ', 0, 1, 'R');

    $pdf->SetX($summaryX);
    $pdf->Cell($summaryCol1, 8, 'Phí vận chuyển:', 0, 0, 'R');
    $pdf->Cell($summaryCol2, 8, number_format($order['HD_PHISHIP'], 0) . 'đ', 0, 1, 'R');

    // Hiển thị giảm giá nếu có
    if (!empty($order['KM_MA'])) {
        $pdf->SetX($summaryX);
        $pdf->Cell($summaryCol1, 8, 'Mã giảm giá:', 0, 0, 'R');
        $pdf->Cell($summaryCol2, 8, $order['KM_CODE'], 0, 1, 'R');
        
        $pdf->SetX($summaryX);
        $pdf->Cell($summaryCol1, 8, 'Giảm giá:', 0, 0, 'R');
        $discount_amount = 0;
        if ($order['hinh_thuc_km'] == 'percent') {
            $discount_amount = $subtotal * ($order['KM_GIATRI'] / 100);
            $pdf->Cell($summaryCol2, 8, '-' . $order['KM_GIATRI'] . '% (' . number_format($discount_amount, 0) . 'đ)', 0, 1, 'R');
        } else {
            $discount_amount = $order['KM_GIATRI'];
            $pdf->Cell($summaryCol2, 8, '-' . number_format($discount_amount, 0) . 'đ', 0, 1, 'R');
        }
        $total = $subtotal + $order['HD_PHISHIP'] - $discount_amount;
    } else {
        $total = $subtotal + $order['HD_PHISHIP'];
    }

    // Tổng cộng cuối cùng
    $pdf->SetFont('dejavusans', 'B', 11);
    $pdf->SetX($summaryX);
    $pdf->Cell($summaryCol1, 10, 'Tổng cộng:', 'T', 0, 'R');
    $pdf->Cell($summaryCol2, 10, number_format($total, 0) . 'đ', 'T', 1, 'R');

    // Sau phần tổng kết, thêm dòng cảm ơn
    $pdf->Ln(10);
    $pdf->SetFont('dejavusans', 'I', 11);
    $pdf->Cell(0, 8, 'Cảm ơn quý khách đã mua hàng tại Phân Bón Xanh!', 0, 1, 'C');
    $pdf->SetFont('dejavusans', '', 10);

    // Đậm và căn giữa dòng tổng cộng
    

    // Tạo file name cho PDF
    $filename = 'hoa-don-' . $order['HD_STT'] . '.pdf';

    // Xóa output buffer và gửi PDF
    ob_end_clean();
    
    // Gửi PDF
    $pdf->Output($filename, 'I');

} catch (Exception $e) {
    $logger->error('Error generating invoice', [
        'error' => $e->getMessage(),
        'order_id' => $order_id ?? null
    ]);
    ob_end_clean();
    die('Error generating invoice: ' . $e->getMessage());
} 