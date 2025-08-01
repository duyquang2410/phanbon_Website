<?php
include 'connect.php';

// Lấy danh sách sản phẩm có số lượng tồn thấp
$sql = "SELECT SP_MA, SP_TEN, SP_SOLUONGTON, SP_DONVITINH 
        FROM san_pham 
        WHERE SP_SOLUONGTON <= 30 
        ORDER BY SP_SOLUONGTON ASC";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    echo "<div class='list-group list-group-flush'>";
    while ($row = mysqli_fetch_assoc($result)) {
        $warningClass = $row['SP_SOLUONGTON'] <= 10 ? 'text-danger' : 'text-warning';
        $warningIcon = $row['SP_SOLUONGTON'] <= 10 ? 'error_outline' : 'warning';
        $warningText = $row['SP_SOLUONGTON'] <= 10 ? 'Sắp hết hàng' : 'Cần nhập thêm';
        
        echo "<div class='list-group-item border-0 d-flex align-items-center px-0 mb-2'>";
        echo "<div class='avatar me-3'>";
        echo "<span class='material-icons {$warningClass}'>{$warningIcon}</span>";
        echo "</div>";
        echo "<div class='d-flex align-items-start flex-column justify-content-center'>";
        echo "<h6 class='mb-0 text-sm'>" . htmlspecialchars($row['SP_TEN']) . "</h6>";
        echo "<p class='mb-0 text-xs {$warningClass}'>";
        echo "<strong>{$warningText}:</strong> Còn {$row['SP_SOLUONGTON']} {$row['SP_DONVITINH']}";
        echo "</p>";
        echo "</div>";
        echo "<a href='inventory_in.php?product_id={$row['SP_MA']}' class='btn btn-link text-success px-3 mb-0'>";
        echo "<i class='material-icons text-sm me-2'>add</i>Nhập thêm";
        echo "</a>";
        echo "</div>";
    }
    echo "</div>";
} else {
    echo "<p class='mb-0'>Không có sản phẩm nào cần cảnh báo tồn kho.</p>";
}

mysqli_close($conn);
?> 