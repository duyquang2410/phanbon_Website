<?php

    session_start();

    require 'connect.php';
    $file_name = basename($_FILES["productImg"]["name"]);
    $target_dir = "../assets/img/product_img/";
    $target_file = $target_dir . $file_name;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $uploadOk = 1;
    
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
      $check = getimagesize($_FILES["productImg"]["tmp_name"]);
      if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
      } else {
        echo "File is not an image.";
        $uploadOk = 0;
      }
    }
    
    // Check if file already exists
    $new_name = basename($_FILES["productImg"]["name"]);
    if (file_exists($target_file)){
        $count=1;
        $name = strtolower(pathinfo($new_name,PATHINFO_FILENAME));
        while(file_exists($target_file)){
            $new_name = "";
            $new_name = $name."-".$count.".".$imageFileType;
            $target_file = $target_dir.$new_name; 
            $count++;
            echo $count;
        }
    }
    
    // Check file size
    if ($_FILES["productImg"]["size"] > 500000) {
      echo "Dung lượng file quá lớn";
      $uploadOk = 0;
    }
    
    if($file_name != null){
      if(($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg")) {
        echo "Chỉ chấp nhận file JPG, JPEG & PNG <br>".$file_name;
        $uploadOk = 0;
        $message = "Loi dinh dang";
        echo "<br><script type='text/javascript'>alert('$message');</script>";
      }
    }
    
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
      echo "Sorry, your file was not uploaded.";
      echo "<br><script type='text/javascript'>alert('$message');</script>";
          header('Refresh: 0;url=products.php');
    // if everything is ok, try to upload file
    } else {
      
      if (move_uploaded_file($_FILES["productImg"]["tmp_name"], $target_file)) {
    
      } 
    
      if ($new_name!="-1."){
        $anhsp = $new_name;
      }
    }

    $id = $_POST["temp_id"];
    $name = $_POST["name"];
    // $des = $_POST["des"];

    $sql = "UPDATE danh_muc SET dm_ten = '{$name}' WHERE dm_ma = {$id};";

    if($conn->query($sql) == true){
        $message = "Cập nhật thành công!";
        echo "<script type='text/javascript'>alert('$message');</script>";
        header('Refresh: 0;url=categorys.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }



    $conn->close();

?>
