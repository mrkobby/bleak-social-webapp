<?php 
include_once("../php_extensions/check_login_status.php");
if($user_ok == false){
	header("location: ../index.php");
    exit();
}
?><?php 
if (isset($_REQUEST['action']) && $_REQUEST['action'] == "status_post"){

	if(strlen($_REQUEST['data']) < 1){
		mysqli_close($db_conx);
	    echo "data_empty";
	    exit();
	}

	if($_REQUEST['type'] != "a" && $_REQUEST['type'] != "c"){
		mysqli_close($db_conx);
	    echo "type_unknown";
	    exit();
	}
	if(isset($_REQUEST["user"])){
	include_once("../php_extensions/db_conx.php");
	$u =  $_REQUEST['user']; 
	}
	$type = preg_replace('#[^a-z]#', '', $_REQUEST['type']);
	$account_name = preg_replace('#[^a-z0-9]#i', '', $_REQUEST['user']);
	$data = htmlentities($_REQUEST['data']);
	$data = mysqli_real_escape_string($db_conx, $data);

	$sql = "SELECT COUNT(id) FROM users WHERE username='$account_name'";
	$query = mysqli_query($db_conx, $sql);
	$row = mysqli_fetch_row($query);
	if($row[0] < 1){
		mysqli_close($db_conx);
		echo "$account_no_exist";
		exit();
	}
	$sql = "INSERT INTO status(account_name, author, type, data, postdate) 
			VALUES('$account_name','$log_username','$type','$data',now())";
	$query = mysqli_query($db_conx, $sql);
	$id = mysqli_insert_id($db_conx);
	mysqli_query($db_conx, "UPDATE status SET osid='$id' WHERE id='$id'");
	$sql = "SELECT COUNT(id) FROM status WHERE author='$log_username' AND type='a'";
    $query = mysqli_query($db_conx, $sql); 
	$row = mysqli_fetch_row($query);
	if ($row[0] > 9) { 
		$sql = "SELECT id FROM status WHERE author='$log_username' AND type='a' ORDER BY id ASC";
    	$query = mysqli_query($db_conx, $sql); 
		$row = mysqli_fetch_row($query);
		$oldest = $row[0];
		mysqli_query($db_conx, "DELETE FROM status WHERE osid='$oldest'");
	}
	$friends = array();
	$query = mysqli_query($db_conx, "SELECT user1 FROM friends WHERE user2='$log_username' AND accepted='1'");
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) { array_push($friends, $row["user1"]); }
	$query = mysqli_query($db_conx, "SELECT user2 FROM friends WHERE user1='$log_username' AND accepted='1'");
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) { array_push($friends, $row["user2"]); }
	for($i = 0; $i < count($friends); $i++){
		$friend = $friends[$i];
		$app = 'posted on your <a href="../profile/?u='.$account_name.'"><b>'.$account_name.'&#39;s</b></a> profile';
		$note = '<a href="../profile/?u='.$account_name.'#status_'.$id.'">Tap to view</a>';		
		mysqli_query($db_conx, "INSERT INTO notifications(username, initiator, app, note, date_time) VALUES('$friend','$log_username','$app','$note',now())");
	}
}
?><?php
$isFriend = false;
$ownerBlockViewer = false;
$viewerBlockOwner = false;
if($u != $log_username && $user_ok == true){
	$friend_check = "SELECT id FROM friends WHERE user1='$log_username' AND user2='$u' AND accepted='1' OR user1='$u' AND user2='$log_username' AND accepted='1'";
	if(mysqli_num_rows(mysqli_query($db_conx, $friend_check)) > 0){
        $isFriend = true;
    }
	$block_check1 = "SELECT id FROM blockedusers WHERE blocker='$u' AND blockee='$log_username'";
	if(mysqli_num_rows(mysqli_query($db_conx, $block_check1)) > 0){
        $ownerBlockViewer = true;
    }
	$block_check2 = "SELECT id FROM blockedusers WHERE blocker='$log_username' AND blockee='$u'";
	if(mysqli_num_rows(mysqli_query($db_conx, $block_check2)) > 0){
        $viewerBlockOwner = true;
    }
}
?><?php 
$author = "";
$replyauthor = "";
$picture1 = "";
$picture2 = "";
$statuslist = "";
$sql = "SELECT * FROM status WHERE account_name='$u' AND type='a' OR account_name='$u' AND type='c' ORDER BY postdate DESC LIMIT 50";
$query = mysqli_query($db_conx, $sql);
$statusnumrows = mysqli_num_rows($query);
while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
	$statusid = $row["id"];
	$account_name = $row["account_name"];
	$author = $row["author"];
	$postdate = $row["postdate"];
	$data = $row["data"];
	$data = nl2br($data);
	$data = str_replace("&amp;","&",$data);
	$data = stripslashes($data);
	$statusDeleteButton = '';
	if($author == $log_username || $account_name == $log_username ){
		$statusDeleteButton = '<span id="sdb_'.$statusid.'"><a class="hand" onclick="return false;" onmousedown="deleteStatus(\''.$statusid.'\',\'status_'.$statusid.'\');" title="DELETE ENTIRE STATUS"><span class="fa fa-trash cg close"></span></a>';
	}
	$sql = "SELECT avatar,gender FROM users WHERE username='$author'";
	$ava_query = mysqli_query($db_conx, $sql);
	$numrows = mysqli_num_rows($ava_query);
	while ($row = mysqli_fetch_array($ava_query, MYSQLI_ASSOC)) {
		$picture1 = $row["avatar"];
		$gend1 = $row["gender"];
	}
	$image1 = '<img class="qh cu bod1" src="../_Users/'.$author.'/'.$picture1.'" alt="'.$author.'">';
	if($picture1 == NULL && $gend1 == "f"){
		$image1 = '<img class="qh cu bod1" src="../assets/img/avatardefaultF1.png">';
	}else if($picture1 == NULL && $gend1 == "m"){
		$image1 = '<img class="qh cu bod1" src="../assets/img/avatardefaultM1.png">';
	}
	$status_replies = "";
	$query_replies = mysqli_query($db_conx, "SELECT * FROM status WHERE osid='$statusid' AND type='b' ORDER BY postdate ASC");
	$replynumrows = mysqli_num_rows($query_replies);
    if($replynumrows > 0){
        while ($row2 = mysqli_fetch_array($query_replies, MYSQLI_ASSOC)) {
			$statusreplyid = $row2["id"];
			$replyauthor = $row2["author"];
			$replydata = $row2["data"];
			$replydata = nl2br($replydata);
			$replypostdate = $row2["postdate"];
			$replydata = str_replace("&amp;","&",$replydata);
			$replydata = stripslashes($replydata);
			$replyDeleteButton = '';
			if($replyauthor == $log_username || $account_name == $log_username ){
				$replyDeleteButton = '<span id="srdb_'.$statusreplyid.'"><a class="hand" onclick="return false;" onmousedown="deleteReply(\''.$statusreplyid.'\',\'reply_'.$statusreplyid.'\');" title="DELETE THIS COMMENT"><span class="fa fa-trash cg close"></span></a>';
			}
			$sql = "SELECT avatar,gender FROM users WHERE username='$replyauthor'";
			$ava_query = mysqli_query($db_conx, $sql);
			$numrows = mysqli_num_rows($ava_query);
			while ($row = mysqli_fetch_array($ava_query, MYSQLI_ASSOC)) {
			$picture2 = $row["avatar"];
			$gend2 = $row["gender"];
			}
			$image2 = '<img class="qh cu bod1" src="../_Users/'.$replyauthor.'/'.$picture2.'" alt="'.$replyauthor.'">';
			if($picture2 == NULL && $gend2 == "f"){
				$image2 = '<img class="qh cu bod1" src="../assets/img/avatardefaultF1.png">';
			}else if($picture2 == NULL && $gend2 == "m"){
				$image2 = '<img class="qh cu bod1" src="../assets/img/avatardefaultM1.png">';
			}
			$status_replies .= '<div id="reply_'.$statusreplyid.'"><div class="qv rc replyBx" style="margin:10px 0px 0px 0px;"><div class="qf" style="padding: 10px;">';
			$status_replies .= '<a class="qj">'.$image2.'</a><div class="qg"><div class="aoc">';
			$status_replies .= '<div class="qn"><small class="eg dp"><time class="timeago" datetime="'.$replypostdate.'">'.$replypostdate.'</time></small><h5><a href="../profile/?u='.$replyauthor.'">'.$replyauthor.'</a></h5></div><div>'.$replydata.'</div>'.$replyDeleteButton.'';
			$status_replies .= '</div></div></div></div></div>';
        }
	}
	$statuslist .= '<div id="status_'.$statusid.'"  class="qf b aml">';
	$statuslist .= '<a class="qj">'.$image1.'</a><div class="qg"><div class="aoc">';
	$statuslist .= '<div class="qn"><small class="eg dp"><time class="timeago" datetime="'.$postdate.'">'.$postdate.'</time></small><h5><a href="../profile/?u='.$author.'">'.$author.'</a></h5></div><p>'.$data.'</p>';
	$statuslist .= '<span class="fa fa-thumbs-up fm cg margin-5l lidi"></span><span class="fa fa-thumbs-down fm cg margin-5l lidi"></span>'.$statusDeleteButton.'';
	$statuslist .= '</div></div>'.$status_replies.'</div>';
	
/*	if($isFriend == true || $log_username == $u){
	    $statuslist .= '<input id="replytext_'.$statusid.'" class="form-control inputBox" onkeyup="statusMax(this,250)" style="margin-top: 2px;" placeholder="Write a comment here"></input>';	
		$statuslist .= '<button class="cg fm replyBtn " id="replyBtn_'.$statusid.'" onclick="replyToStatus('.$statusid.',\''.$u.'\',\'replytext_'.$statusid.'\',this)">REPLY</button>';	
	}
*/
}
	echo $statuslist;
?>