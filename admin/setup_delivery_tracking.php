<?php
require_once 'connect.php';

try {
    // Đọc nội dung file SQL
    $sql = file_get_contents('create_delivery_status_table.sql');

    // Tách các câu lệnh SQL
    $queries = array_filter(array_map('trim', explode(';', $sql)));

    // Thực thi từng câu lệnh
    foreach ($queries as $query) {
        if (!empty($query)) {
            if ($conn->query($query)) {
                echo "Thực thi thành công: " . substr($query, 0, 50) . "...<br>";
            } else {
                echo "Lỗi khi thực thi: " . $conn->error . "<br>";
                echo "Query: " . $query . "<br>";
            }
        }
    }

    echo "<br>Hoàn tất cài đặt hệ thống theo dõi giao hàng!";

} catch (Exception $e) {
    echo "Lỗi: " . $e->getMessage();
}
?> 