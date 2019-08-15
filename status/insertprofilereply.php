<?php 
include_once("../php_extensions/check_login_status.php");
if($user_ok == false){
	header("location: ../index.php");
    exit();
}
?><?php 
if (isset($_REQUEST['action']) && $_REQUEST['action'] == "status_reply"){
	if(strlen($_REQUEST['data']) < 1){
		mysqli_close($db_conx);
	    echo "data_empty";
	    exit();
	}
	if(isset($_REQUEST["user"])){
	include_once("../php_extensions/db_conx.php");
	$u =  $_REQUEST['user']; 
	}
	$osid = preg_replace('#[^0-9]#', '', $_REQUEST['sid']);
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
	$sql = "INSERT INTO status(osid, account_name, author, type, data, postdate)
	        VALUES('$osid','$account_name','$log_username','b','$data',now())";
	$query = mysqli_query($db_conx, $sql);
	$id = mysqli_insert_id($db_conx);
	$sql = "SELECT author FROM status WHERE osid='$osid' AND author!='$log_username' GROUP BY author";
	$query = mysqli_query($db_conx, $sql);
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$participant = $row["author"];
		$app = "replied to your Post";
		$note = '<a href="../profile/?u='.$account_name.'#status_'.$osid.'">Tap to view conversation</a>';
		mysqli_query($db_conx, "INSERT INTO notifications(username, initiator, app, note, date_time) 
		             VALUES('$participant','$log_username','$app','$note',now())");
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
	echo $status_replies;
}
?>