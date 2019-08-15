<?php
include_once("../php_extensions/check_login_status.php");
if($user_ok != true || $log_username == "") {
	exit();
}
?><?php 
if (isset($_POST['action']) && $_POST['action'] == "delete_note"){
	if(!isset($_POST['noteid']) || $_POST['noteid'] == ""){
		mysqli_close($db_conx);
		exit();
	}
	$noteid = preg_replace('#[^0-9]#', '', $_POST['noteid']);
	$query = mysqli_query($db_conx, "SELECT * FROM notifications WHERE id='$noteid'");
	mysqli_query($db_conx, "DELETE FROM notifications WHERE id='$noteid'");
	mysqli_close($db_conx);
	echo "delete_ok";
	exit();

}
?><?php 
if (isset($_POST['action']) && $_POST['action'] == "clear_note"){
	if(!isset($_POST['user_nm']) || $_POST['user_nm'] == ""){
		mysqli_close($db_conx);
		exit();
	}
	$user_nm = preg_replace('#[^a-z0-9]#i', '', $_POST['user_nm']);
	$query = mysqli_query($db_conx, "SELECT * FROM notifications WHERE username='$user_nm'");
	mysqli_query($db_conx, "DELETE FROM notifications WHERE username='$user_nm'");
	mysqli_close($db_conx);
	echo "delete_ok";
	exit();

}
?>