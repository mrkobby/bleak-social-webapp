<?php
$msg_list = "";
$sql = "SELECT * FROM chat WHERE user2 LIKE BINARY '$log_username' OR user1 LIKE BINARY '$log_username' ORDER BY timesent DESC";
$query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($query);
if($numrows < 1){
	$msg_list = '<li class="b"><h7 style="font-size:14px;">You currently have no chats</h7></li>';
} else {
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$chatid = $row["id"];
		$user2 = $row["user2"];
		$user1 = $row["user1"];
		$msg_data = $row["msg_data"];
		$what_time = $row["timesent"];
		$msg_listDeleteButton = '';
		if($user2 == $log_username || $user1 == $log_username ){
			$msg_listDeleteButton = '<span id="sdb_'.$chatid.'"><a class="hand" onclick="return false;" onmousedown="deleteMsgList(\''.$chatid.'\',\'chat_'.$chatid.'\');" title="Delete message"><span class="fa fa-trash cg"></span></a>';
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
		$sql = "SELECT avatar,gender FROM users WHERE username='$user2'";
		$ava_query = mysqli_query($db_conx, $sql);
		$numrows = mysqli_num_rows($ava_query);
		while ($row = mysqli_fetch_array($ava_query, MYSQLI_ASSOC)) {
		$picture2 = $row["avatar"];
		$gend1 = $row["gender"];
		}
		$image2 = '<img class="qh cu bod1" src="../_Users/'.$user2.'/'.$picture2.'" alt="'.$user2.'">';
		if($picture2 == NULL && $gend1 == "f"){
			$image2 = '<img class="qh cu bod1" src="../assets/img/avatardefaultF1.png">';
		}else if($picture2 == NULL && $gend1 == "m"){
			$image2 = '<img class="qh cu bod1" src="../assets/img/avatardefaultM1.png">';
		}
		if($user1 != $log_username){
			$msg_list .= '<li class="b" id="chat_'.$chatid.'"><a class="b hand" href="../messages/conv.php?u='.$user2.'"><div class="qf"><span class="qj">';
			$msg_list .= ''.$image1.'</span><div class="qg"><strong>'.$user1.'</strong><div class="aof">'.$msg_data.'';
			$msg_list .= '</div></div></div></a><div></div></li>';
		}else{
			$msg_list .= '<li class="b" id="chat_'.$chatid.'"><a class="b hand" href="../messages/conv.php?u='.$user2.'"><div class="qf"><span class="qj">';
			$msg_list .= ''.$image2.'</span><div class="qg"><strong>'.$user2.'</strong><div class="aof">'.$msg_data.'';
			$msg_list .= '</div></div></div></a><div></div></li>';
		}
	}
/* 	$sql = "SELECT notescheck FROM users WHERE username='$log_username'";
	$query = mysqli_query($db_conx, $sql);
	$row = mysqli_fetch_row($query);
	$lastnotecheck = $row[0];
	$sql = "SELECT COUNT(id) FROM notifications WHERE username LIKE BINARY '$log_username' AND date_time > '$lastnotecheck'";
	$query = mysqli_query($db_conx, $sql);
	$query_count = mysqli_fetch_row($query);
	$note_count2 = $query_count[0];
	$mynotenum = $note_count2 + $note_count1;
	if($mynotenum > 0) {
		$bell = '<span class="fa fa-bell fa-fw"></span><div '.$bubble.'><span class="fa fa-circle fa-lg notecheck-ls"><span class="num-ls">'.$mynotenum.'</span></div>';
		$tog_note = '<div '.$bubble.'><span class="fa fa-circle fa-1-8x notecheck"></span><span class="num">'.$mynotenum.'</span></div>';
		$notedot = '<span '.$bubble.' class="fa fa-circle fa-1x circle">';
	} */
}
?>