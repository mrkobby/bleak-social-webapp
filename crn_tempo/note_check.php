<?php
$friend_requests = "";
$sql = "SELECT * FROM friends WHERE user2='$log_username' AND accepted='0' ORDER BY datemade DESC";
$query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($query);
if($numrows < 1){
	$friend_requests = '<h7 style="color:rgb(22, 161, 131);font-size:14px;">No friend requests yet.</h7>';
} else {
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$reqID = $row["id"];
		$user1 = $row["user1"];
		$datemade = $row["datemade"];
		$thumbquery = mysqli_query($db_conx, "SELECT avatar FROM users WHERE username='$user1'");
		$thumbrow = mysqli_fetch_row($thumbquery);
		$user1avatar = $thumbrow[0];
		$user1pic = '<img class="qh cu" src="../_Users/'.$user1.'/'.$user1avatar.'" alt="'.$user1.'">';
		if($user1avatar == NULL){
			$user1pic = '<img class="qh cu" src="../assets/img/avatardefault.png" alt="'.$user1.'">';
		}
		$friend_requests .= '<div id="friendreq_'.$reqID.'" style="margin-bottom:12px;">';
		$friend_requests .= '<li class="qf"><a class="qj" href="../profile/?u='.$user1.'" title="'.$user1.'">'.$user1pic.'</a>';
		$friend_requests .= '<div class="qg" id="user_info_'.$reqID.'"><span style="font-size:10px;"><time class="timeago" datetime="'.$datemade.'">'.$datemade.'</time></span><p style="font-size:12px;"> Request from <a href="../profile/?u='.$user1.'" style="font-weight:bold;text-decoration:none;">'.$user1.'</a></p>';
		$friend_requests .= '<button class="cg ts fx accept" onclick="friendReqHandler(\'accept\',\''.$reqID.'\',\''.$user1.'\',\'user_info_'.$reqID.'\')">Accept</button> &nbsp;';
		$friend_requests .= '<button class="cg ts fx reject" onclick="friendReqHandler(\'reject\',\''.$reqID.'\',\''.$user1.'\',\'user_info_'.$reqID.'\')">Reject</button>';
		$friend_requests .= '</div>';
		$friend_requests .= '</div><hr>';
	}
}
?><?php
$friend_requests_box = '<div class="qv rc sm sp"><div class="qw"><h5 class="ald"><b>Friend Requests &nbsp; <span class="fa fa-user"></span></b></h5><hr><ul class="qo anx">'.$friend_requests.'</ul></div></div>';
$bubble = 'style="display:;opacity:1;"';
$bell = '<span class="fa fa-bell fa-fw"></span><div class="note-bubble"><span class="fa fa-circle fa-lg notecheck-ls"><span class="num-ls"></span></div>';
$tog_note = '<div class="tog-bubble"><span class="fa fa-circle fa-1-8x notecheck"></span><span class="num"></span></div>';
$notedot = '<span class="fa fa-circle fa-1x circle">';
if($user_ok == true) {
	$sql = "SELECT notescheck FROM users WHERE username='$log_username'";
	$query = mysqli_query($db_conx, $sql);
	$row = mysqli_fetch_row($query);
	$lastnotecheck = $row[0];
	$sql = "SELECT COUNT(id) FROM friends WHERE user2='$log_username' AND datemade > '$lastnotecheck' AND accepted = '0'";
	$query = mysqli_query($db_conx,$sql);
	$numrows = mysqli_num_rows($query);
	$query_count = mysqli_fetch_row($query);
	$note_count1 = $query_count[0];
	$mynotenum = $note_count1;
	if($numrows > 0 && $mynotenum > 0) {
		$bell = '<span class="fa fa-bell fa-fw"></span><div '.$bubble.' class="bell"><span class="badge bg-red">'.$mynotenum.'</span></div>';
		$tog_note = '<div '.$bubble.' class="bell"><span class="badge bg-red">'.$mynotenum.'</span></div>';
		$notedot = '<span '.$bubble.' class="fa fa-circle fa-1x circle">';
	}
	$sql = "SELECT COUNT(id) FROM friends WHERE user2='$log_username' AND accepted = '0'";
	$query = mysqli_query($db_conx,$sql);
	$numrows = mysqli_num_rows($query);
	$query_count = mysqli_fetch_row($query);
	$note_count1 = $query_count[0];
	if($note_count1 > 0) {
		$friend_requests_box = '<div class="qv rc"><div class="qw"><h5 class="ald"><b>Friend Requests &nbsp; <span class="fa fa-user"></span></b></h5><hr><ul class="qo anx">'.$friend_requests.'</ul></div></div>';
	}
}
?><?php
$notification_list = "";
$noteDeleteButton = "";
$clearNotes = "";
$sql = "SELECT * FROM notifications WHERE username LIKE BINARY '$log_username' ORDER BY date_time DESC";
$query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($query);
if($numrows < 1){
	$notification_list = '<li class="b aml"><h7 style="font-size:14px;">You do not have any notifications now</h7></li>';
} else {
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$noteid = $row["id"];
		$user_nm = $row["username"];
		$initiator = $row["initiator"];
		$app = $row["app"];
		$note = $row["note"];
		$date_time = $row["date_time"];
		
		$sql = "SELECT avatar,gender FROM users WHERE username='$initiator'";
		$ava_query = mysqli_query($db_conx, $sql);
		$numrows = mysqli_num_rows($ava_query);
		while ($row = mysqli_fetch_array($ava_query, MYSQLI_ASSOC)) {
		$picture1 = $row["avatar"];
		$gend1 = $row["gender"];
		}
		$image1 = '<img class="qh cu bod1" src="../_Users/'.$initiator.'/'.$picture1.'" alt="'.$initiator.'">';
		if($picture1 == NULL && $gend1 == "f"){
			$image1 = '<img class="qh cu bod1" src="../assets/img/avatardefaultF1.png">';
		}else if($picture1 == NULL && $gend1 == "m"){
			$image1 = '<img class="qh cu bod1" src="../assets/img/avatardefaultM1.png">';
		}
		if($u == $log_username ){
		$noteDeleteButton = '<span><a class="hand" onclick="return false;" onmousedown="deleteNote(\''.$noteid.'\');" title="Delete this notification"><span class="fa fa-trash cg close"></span></a>';
		$clearNotes = '<a class="b hand" onclick="return false;" onmousedown="clearNote(\''.$user_nm.'\');" title="Clear all notifications"><span class="fa fa-trash eg"></span>Clear all notifications</a>';
		}

		$notification_list .= '<li class="b qf aml"><div class="qj"><span class="fa fa-flag dp"></span></div><div class="qg">';
		$notification_list .= '<div class="qn"><a href="../profile/?u='.$initiator.'"><strong>'.$initiator.'</strong></a> '.$app.'';
		$notification_list .= ''.$noteDeleteButton.'</div><div class="qv rc alk"><div class="qw"><div class="qf"><a class="qj">'.$image1.'</a>';
		$notification_list .= '<div class="qg"><div class="aoc"><div class="qn"><small class="eg dp"><time class="timeago" datetime="'.$date_time.'">'.$date_time.'</time></small><h5 class="alf"><a href="../profile/?u='.$initiator.'">'.$initiator.'</a></h5>';
		$notification_list .= '</div>'.$note.'</div></div></div></div></div></div></li>';
	}
	$sql = "SELECT notescheck FROM users WHERE username='$log_username'";
	$query = mysqli_query($db_conx, $sql);
	$row = mysqli_fetch_row($query);
	$lastnotecheck = $row[0];
	$sql = "SELECT COUNT(id) FROM notifications WHERE username LIKE BINARY '$log_username' AND date_time > '$lastnotecheck'";
	$query = mysqli_query($db_conx, $sql);
	$query_count = mysqli_fetch_row($query);
	$note_count2 = $query_count[0];
	$mynotenum = $note_count2 + $note_count1;
	if($mynotenum > 0) {
		$bell = '<span class="fa fa-bell fa-fw"></span><div '.$bubble.' class="bell"><span class="badge bg-red">'.$mynotenum.'</span></div>';
		$tog_note = '<div '.$bubble.' class="bell"><span class="badge bg-red">'.$mynotenum.'</span></div>';
		$notedot = '<span '.$bubble.' class="fa fa-circle fa-1x circle">';
	}
}
?>


<script src="../assets/js/main.js"></script>
<script src="../assets/js/ajax.js"></script>
<script src="../assets/js/jquery.timeago.js"></script>
<script>
jQuery(document).ready(function() {
  jQuery("time.timeago").timeago();   
  jQuery.timeago.settings.strings.inPast = "time has elapsed";
  jQuery.timeago.settings.allowPast = false;
});
function deleteNote(noteid){
	var conf = confirm("Press OK to confirm deletion of this notification");
	if(conf != true){
		return false;
	}
	var ajax = ajaxObj("POST", "../php_parsers/note_deletion_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText != "delete_ok"){
				alert(ajax.responseText);
			}else{
				window.location = "../notifications/?u=<?php echo $u ?>";
			}
		}
	}
	ajax.send("action=delete_note&noteid="+noteid);
}
function clearNote(user_nm){
	var conf = confirm("Press OK to confirm deletion of this notification");
	if(conf != true){
		return false;
	}
	var ajax = ajaxObj("POST", "../php_parsers/note_deletion_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText != "delete_ok"){
				alert(ajax.responseText);
			}else{
				window.location = "../notifications/?u=<?php echo $u ?>";
			}
		}
	}
	ajax.send("action=clear_note&user_nm="+user_nm);
}

</script>