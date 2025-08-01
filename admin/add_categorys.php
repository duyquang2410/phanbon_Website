<?php

session_start();

require 'connect.php';


$file_name = basename($_FILES["productImg"]["name"]);
$target_dir = "../assets/img/product_img/";
$target_file = $target_dir . basename($_FILES["productImg"]["name"]);
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
if ($_FILES["productImg"]["size"] > 30000000) {
  echo "Dung lượng file quá lớn";
  $uploadOk = 0;
}

if($file_name != null){
  if(($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg")) {
    echo "Chỉ chấp nhận file JPG, JPEG & PNG <br>".$file_name;
    $uploadOk = 0;
  }
} else {
  $target_file = $target_dir . "default.jpg";
  $file_name = "default.png";
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES["productImg"]["tmp_name"], $target_file)) {
    $filename = $file_name;
  } else {
    $filename = "default.png";
  }

    $sql = "select max(DM_MA) as maxid from danh_muc;";
    if ($conn->query($sql)==true){
        $rs = $conn->query($sql);
        $row = mysqli_fetch_assoc($rs);
        $id = $row["maxid"] + 1;
    } else {
        echo "<br>Error: " . $sql . "<br>" . $conn->error;
    }


    $ten = $_POST["ten"];
		$img = $filename;

    $sql = "insert into danh_muc values ($id, '".$ten."', '".$img."');";

    if ($conn->query($sql)==true){
        $message = "Thêm danh mục ".$ten." thành công!";
        echo "<br><script type='text/javascript'>alert('$message');</script>";
        header('Refresh: 0;url=categorys.php');
    } else {
        echo "<br>Error: " . $sql . "<br>" . $conn->error;
    }
	}
?>
