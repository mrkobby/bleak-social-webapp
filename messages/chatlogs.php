<?php 
include_once("../php_extensions/check_login_status.php");
if($user_ok == false){
	header("location: ../index.php");
    exit();
}
?><?php 
$user1 = "";
$user2 = "";
$picture1 = "";
$picture2 = "";
$msglist = "";
$sql = "SELECT * FROM chat ORDER BY timesent DESC LIMIT 20";
$query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($query);
while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
	$msg_id = $row["id"];
	$user1 = $row["user1"];
	$user2 = $row["user2"];
	$timesent = $row["timesent"];
	$msg_data = $row["msg_data"];
	$msg_data = nl2br($msg_data);
	$msg_data = str_replace("&amp;","&",$msg_data);
	$msg_data = stripslashes($msg_data);
	$msgDeleteButton = '';
	if($user1 == $log_username || $user2 == $log_username ){
		$msgDeleteButton = '<span id="sdb_'.$msg_id.'"><a class="hand" onclick="return false;" onmousedown="deleteMsg(\''.$msg_id.'\',\'msg_'.$msg_id.'\');" title="Delete message"><span class="fa fa-trash cg close"></span></a>';
	}
	$sql = "SELECT avatar,gender FROM users WHERE username='$user1'";
	$ava_query = mysqli_query($db_conx, $sql);
	$numrows = mysqli_num_rows($ava_query);
	while ($row = mysqli_fetch_array($ava_query, MYSQLI_ASSOC)) {
		$picture1 = $row["avatar"];
		$gend1 = $row["gender"];
	}
	$image1 = '<img class="qh cu bod1" src="../_Users/'.$user1.'/'.$picture1.'" alt="'.$user1.'">';
	if($picture1 == NULL && $gend1 == "f"){
		$image1 = '<img class="qh cu bod1" src="../assets/img/avatardefaultF1.png">';
	}else if($picture1 == NULL && $gend1 == "m"){
		$image1 = '<img class="qh cu bod1" src="../assets/img/avatardefaultM1.png">';
	}
	
	if($user1 == $log_username ){
	$msglist .= '<li class="qf alu" id="msg_'.$msg_id.'" ><a class="qj" href="../profile/?u='.$user1.'">'.$image1.'</a><div class="qg"><div class="aoc">'.$msg_data.'</div>';
	$msglist .= '<div class="aod"><small class="dp"><a href="../profile/?u='.$user1.'">'.$user1.'</a>&nbsp; <time class="timeago" datetime="'.$timesent.'">'.$timesent.'</time></small></div></div></li>';
	}else{
	$msglist .= '<li class="qf aoe alu" id="reply_'.$msg_id.'"><div class="qg"><div class="aoc">'.$msg_data.'</div><div class="aod"><small class="dp">';
	$msglist .= '<a href="../profile/?u='.$user1.'">'.$user1.'</a>&nbsp; <time class="timeago" datetime="'.$timesent.'">'.$timesent.'</time></small></div>';
	$msglist .= '</div><a class="qi" href="../profile/?u='.$user1.'">'.$image1.'</a></li>';
	}
}
echo $msglist;
?>