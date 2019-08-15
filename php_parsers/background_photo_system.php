<?php
include_once("../php_extensions/check_login_status.php");
if($user_ok != true || $log_username == "") {
	exit();
}
?><?php 
if (isset($_FILES["background"]["name"]) && $_FILES["background"]["tmp_name"] != ""){
	$fileName = $_FILES["background"]["name"];
    $fileTmpLoc = $_FILES["background"]["tmp_name"];
	$fileType = $_FILES["background"]["type"];
	$fileSize = $_FILES["background"]["size"];
	$fileErrorMsg = $_FILES["background"]["error"];
	$kaboom = explode(".", $fileName);
	$fileExt = end($kaboom);
	list($width, $height) = getimagesize($fileTmpLoc);
	
	$db_file_name = rand(100000000000,999999999999).".".$fileExt;
	if($fileSize > 5048576) {
		header("location: ../user/message.php?msg=ERROR: Your image file was larger than 5mb");
		exit();	
	} else if (!preg_match("/\.(gif|jpg|png)$/i", $fileName) ) {
		header("location: ../user/message.php?msg=ERROR: Your image file was not jpg, gif or png type");
		exit();
	} else if ($fileErrorMsg == 1) {
		header("location: ../user/message.php?msg=ERROR: An unknown error occurred");
		exit();
	}
	$sql = "SELECT background FROM useroptions WHERE username='$log_username'";
	$query = mysqli_query($db_conx, $sql);
	$row = mysqli_fetch_row($query);
	$background = $row[0];
	if($background != ""){
		$picurl = "../_Users/$log_username/$background"; 
	    if (file_exists($picurl)) { unlink($picurl); }
	}
	$moveResult = move_uploaded_file($fileTmpLoc, "../_Users/$log_username/$db_file_name");
	if ($moveResult != true) {
		header("location: ../user/message.php?msg=ERROR: File upload failed");
		exit();
	}
	include_once("../php_extensions/image_resize.php");
	$target_file = "../_Users/$log_username/$db_file_name";
	$resized_file = "../_Users/$log_username/$db_file_name";
	
	$sql = "UPDATE useroptions SET background='$db_file_name', editdate=now() WHERE username='$log_username'";
	$query = mysqli_query($db_conx, $sql);
	mysqli_close($db_conx);
	header("location: ../edit/editprofile.php?u=$log_username");
	exit();
}
?>
