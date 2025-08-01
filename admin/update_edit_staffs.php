<?php

// $tkid = $_POST["tk_id"];
$tkavt = $_POST["old_staffImg"];
$nvid = $_POST["staff_id"];
$nvname = $_POST["staff_name"];
$nvaddress = $_POST["staff_address"];
$nvsdt = $_POST["staff_phone"];
$nvemail = $_POST["staff_email"];
$nvus = strtolower($_POST["staff_usname"]);
$nvpass = $_POST["staff_pass"];
$nvrepass = $_POST["staff_repass"];
$nvsex = $_POST["staff_sex"];
$nvvt = $_POST["staff_vaitro"];
$nvbirth = $_POST["staff_birth"];

if($nvpass != $nvrepass){
  $message = "Mật khẩu nhập lại không đúng!";
  echo "<script type='text/javascript'>alert('$message');</script>";
  header('Refresh: 0;url=add_staffs.php'); 
} else {

  require 'connect.php';
  
    $file_name = basename($_FILES["staffImg"]["name"]);
    $target_dir = "../assets/img/staff_img/";
    $target_file = $target_dir . $file_name;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $uploadOk = 1;

    
    
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
      $check = getimagesize($_FILES["staffImg"]["tmp_name"]);
      if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
      } else {
        echo "File is not an image.";
        $uploadOk = 0;
      }
    }
    
    // Check if file already exists
    $new_name = basename($file_name);
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
    if ($_FILES["staffImg"]["size"] > 50000000) {
      echo "Dung lượng file quá lớn";
      $uploadOk = 0;
    }
    
     // Allow certain file formats
      if($file_name != null){
        if(($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg")) {
          echo "Chỉ chấp nhận file JPG, JPEG & PNG <br>".$file_name;
          $uploadOk = 0;
          $message = "Lỗi định dạng";
          echo "<br><script type='text/javascript'>alert('$message');</script>";
        }
      }
    
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
      $message = "Sorry, your file was not uploaded.";
      echo "<br><script type='text/javascript'>alert('$message');</script>";
      header('Refresh: 0;url=staffs.php');

    // if everything is ok, try to upload file
    } else {
      if (move_uploaded_file($_FILES["staffImg"]["tmp_name"], $target_file)) {
      }
      else {
        echo "Sorry, there was an error uploading your file.";
      }

      if ($new_name!="-1."){
        $tkavt = $new_name;
      }

      $sql = "update nhan_vien set
                  nv_ten = '".$nvname."',
                  nv_diachi = '".$nvaddress."',
                  nv_sdt = '".$nvsdt."',
                  nv_email = '".$nvemail."',
                  nv_gioitinh = '".$nvsex."',
                  nv_ngaysinh = '".$nvbirth."',
                  nv_tendangnhap = '".$nvus."',
                  nv_matkhau = '".$nvpass."',
                  nv_avatar = '".$tkavt."',
                  cv_ma = '".$nvvt."'
                where nv_ma = ".$nvid."";
      
      if ($conn->query($sql) == TRUE) {

          $_SESSION["avt"] = $tkavt;
          $_SESSION["name"] = $nvname;
          
          $message = "Cập nhật nhân viên ".$nvname." thành công!";
          echo "<script type='text/javascript'>alert('$message');</script>";
          header('Refresh: 0;url=staffs.php');
      } else {
        echo "<br>Error: " . $sql . "<br>" . $conn->error;
      }
      
      $conn->close();
    } 
  } 
?>