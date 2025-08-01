<?php

session_start();

  require 'connect.php';

    $sql = "select max(DVC_MA) as maxid from don_van_chuyen;";
    if ($conn->query($sql)==true){
        $rs = $conn->query($sql);
        $row = mysqli_fetch_assoc($rs);
        $id = $row["maxid"] + 1;
    } else {
        echo "<br>Error: " . $sql . "<br>" . $conn->error;
    }


    $ctvc = $_POST["ctvc"];
    $giaoden = $_POST["giaoden"];
    $start = $_POST["start_date"];
    $finish = $_POST["finish_date"];


    $sql = "insert into don_van_chuyen values ($id, $ctvc, '".$giaoden."', '".$start."', '".$finish."');";

    if ($conn->query($sql)==true){
        $message = "Thêm đơn vận chuyển thành công!";
        echo "<br><script type='text/javascript'>alert('$message');</script>";
        header('Refresh: 0;url=trans_bill.php');
    } else {
        echo "<br>Error: " . $sql . "<br>" . $conn->error;
    }
		$conn->close();
?>