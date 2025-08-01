<?php

session_start();

  require 'connect.php';


    $id = $_POST["temp_id"];
    $giaoden = $_POST["giaoden"];
    $start = $_POST["start_date"];
    $finish = $_POST["finish_date"];


    $sql = "update don_van_chuyen set 
            DVC_DIACHI = '{$giaoden}',
            DVC_TGBATDAU = '{$start}',
            DVC_TGHOANTHANH = '{$finish}'
            where DVC_MA = {$id}";

    if ($conn->query($sql)==true){
        $message = "Cập nhật đơn vận chuyển thành công!";
        echo "<br><script type='text/javascript'>alert('$message');</script>";
        header('Refresh: 0;url=trans_bill.php');
    } else {
        echo "<br>Error: " . $sql . "<br>" . $conn->error;
    }
	$conn->close();
?>