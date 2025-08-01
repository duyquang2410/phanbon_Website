<?php
  require 'connect.php';
?>

<?php

    $sql = "select max(NH_MA) as maxid from nguon_hang;";
    if ($conn->query($sql)==true){
        $rs = $conn->query($sql);
        $row = mysqli_fetch_assoc($rs);
        $id = $row["maxid"] + 1;
    } else {
        echo "<br>Error: " . $sql . "<br>" . $conn->error;
    }


    $ten = $_POST["ten"];
    $mota = $_POST["mota"];

    $sql = "insert into nguon_hang values ($id, '".$ten."', '".$mota."');";

    if ($conn->query($sql)==true){
        $message = "Thêm nguồn hàng ".$ten." thành công!";
        echo "<br><script type='text/javascript'>alert('$message');</script>";
        header('Refresh: 0;url=source.php');
    } else {
        echo "<br>Error: " . $sql . "<br>" . $conn->error;
    }

?>
