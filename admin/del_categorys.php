<?php
require 'connect.php';
  $iddm = $_POST["iddm"];
  $sql = "select dm_ten from danh_muc where dm_ma = {$iddm}";
  if($conn->query($sql)==TRUE){
    $row = mysqli_fetch_assoc($conn->query($sql)); 
    $tendm = $row["dm_ten"];
    $sql1 = "delete from danh_muc where dm_ma = {$iddm};";
       
    if($conn->query($sql1)==TRUE){
      $message = "Xoá danh mục ".$tendm." thành công";
      echo "<script type='text/javascript'>alert('$message');</script>";
      header('Refresh: 0;url=categorys.php');
    } else {
      echo "Error: " . $sql1 ."<br>";
    }
  } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
  }


?>
