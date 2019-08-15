<?php
include_once("../php_extensions/check_login_status.php");
$sql = "SELECT username, avatar FROM users WHERE avatar IS NOT NULL  AND username != '$log_username' ORDER BY RAND() LIMIT 3";
$query = mysqli_query($db_conx, $sql);
$userlist = "";
while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
	$u = $row["username"];
	$avatar = $row["avatar"];
	$profile_pic = '../_Users/'.$u.'/'.$avatar;
	$userlist .= '<li class="qf"><a class="qj" href="../profile/?u='.$u.'" title="'.$u.'"><img class="qh cu" style="border: 1px solid #000;" src="'.$profile_pic.'" alt="'.$u.'"></a>';
	$userlist .= '<div class="qg"><a href="../profile/?u='.$u.'"><strong>'.$u.'</strong></a> <b>@---</b> <div class="aoa">';
	$userlist .= '<span id="friendBtn">'.$friend_button.'</span></div></div></li>';
}									
$sql = "SELECT COUNT(id) FROM users";
$query = mysqli_query($db_conx, $sql);
$row = mysqli_fetch_row($query);
$usercount = $row[0];
?>