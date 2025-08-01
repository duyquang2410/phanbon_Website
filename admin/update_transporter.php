<?php

    session_start();

    require 'connect.php';

    $id = $_POST["temp_id"];
    $name = $_POST["name"];
    $des = $_POST["des"];

    $sql = "UPDATE nha_van_chuyen SET nvc_ten = '{$name}', nvc_mota = '{$des}' WHERE nvc_ma = {$id};";

    if($conn->query($sql) == true){
        $message = "Cập nhật thành công!";
        echo "<script type='text/javascript'>alert('$message');</script>";
        header('Refresh: 0;url=transporters.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();

?>
