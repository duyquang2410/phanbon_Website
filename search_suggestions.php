<?php
// Include file kết nối cơ sở dữ liệu
require_once 'connect.php';

// Kiểm tra nếu có dữ liệu tìm kiếm được gửi lên
if (isset($_GET['term']) && !empty($_GET['term'])) {
    // Lấy từ khóa tìm kiếm
    $search_term = trim($_GET['term']);
    
    // Chuẩn bị câu truy vấn tìm kiếm sản phẩm theo tên
    $query = "SELECT SP_MA, SP_TEN, SP_HINHANH, SP_DONGIA 
              FROM san_pham 
              WHERE SP_TEN LIKE ? AND SP_SOLUONGTON > 0
              ORDER BY SP_TEN ASC 
              LIMIT 5";
    
    // Chuẩn bị và thực thi truy vấn
    $stmt = $conn->prepare($query);
    $search_param = "%{$search_term}%";
    $stmt->bind_param("s", $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Mảng chứa kết quả
    $products = array();
    
    // Kiểm tra kết quả và đưa vào mảng
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Định dạng tiền tệ
            $formatted_price = number_format($row['SP_DONGIA'], 0, ',', '.') . 'đ';
            
            // Đường dẫn đến hình ảnh sản phẩm
            $image_path = 'img/' . $row['SP_HINHANH'];
            
            // Thêm sản phẩm vào mảng kết quả
            $products[] = array(
                'id' => $row['SP_MA'],
                'value' => $row['SP_TEN'],
                'label' => $row['SP_TEN'],
                'image' => $image_path,
                'price' => $formatted_price,
                'link' => 'detail.php?id=' . $row['SP_MA']
            );
        }
    }
    
    // Trả về kết quả dưới dạng JSON
    header('Content-Type: application/json');
    echo json_encode($products);
    
    // Đóng statement
    $stmt->close();
} else {
    // Nếu không có dữ liệu tìm kiếm, trả về mảng rỗng
    header('Content-Type: application/json');
    echo json_encode(array());
}

// Đóng kết nối
$conn->close();
?> 