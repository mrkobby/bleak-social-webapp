<?php
include_once("../php_extensions/check_login_status.php");
if($user_ok != true || $log_username == "") {
	exit();
}
?><?php
if (isset($_POST['action']) && $_POST['action'] == "msg_send"){

	if(strlen($_POST['data']) < 1){
		mysqli_close($db_conx);
	    echo "data_empty";
	    exit();
	}

	if($_POST['type'] != "a"){
		mysqli_close($db_conx);
	    echo "type_unknown";
	    exit();
	}

	$type = preg_replace('#[^a-z]#', '', $_POST['type']);
	$reply_user = preg_replace('#[^a-z0-9]#i', '', $_POST['user']);
	$msg_data = htmlentities($_POST['data']);
	$msg_data = mysqli_real_escape_string($db_conx, $msg_data);

	$sql = "SELECT COUNT(id) FROM users WHERE username='$reply_user'";
	$query = mysqli_query($db_conx, $sql);
	$row = mysqli_fetch_row($query);
	if($row[0] < 1){
		mysqli_close($db_conx);
		echo "$account_no_exist";
		exit();
	}
	$sql = "INSERT INTO private_msg(to_user, from_user, type, msg_data, sentdate) 
			VALUES('$reply_user','$log_username','$type','$msg_data',now())";
	$query = mysqli_query($db_conx, $sql);
	$id = mysqli_insert_id($db_conx);
	mysqli_query($db_conx, "UPDATE private_msg SET osid='$id' WHERE id='$id'");
	$sql = "SELECT COUNT(id) FROM private_msg WHERE from_user='$log_username' AND type='a'";
    $query = mysqli_query($db_conx, $sql); 
	$row = mysqli_fetch_row($query);
	if ($row[0] > 40) { 
		$sql = "SELECT id FROM private_msg WHERE from_user='$log_username' AND type='a' ORDER BY id ASC";
    	$query = mysqli_query($db_conx, $sql); 
		$row = mysqli_fetch_row($query);
		$oldest = $row[0];
		mysqli_query($db_conx, "DELETE FROM private_msg WHERE osid='$oldest'");
	}
	$chat_peep = ''.$msg_data.'';
	mysqli_query($db_conx, "INSERT INTO private_chatlist(username, sender, type, chat_peep, what_time) VALUES('$reply_user','$log_username','$type','$chat_peep',now())");	
	
	mysqli_close($db_conx);
	echo "msg_ok|$id";
	exit();
}
?>
<?php 
if (isset($_POST['action']) && $_POST['action'] == "status_reply"){
	if(strlen($_POST['data']) < 1){
		mysqli_close($db_conx);
	    echo "data_empty";
	    exit();
	}
	$osid = preg_replace('#[^0-9]#', '', $_POST['sid']);
	$account_name = preg_replace('#[^a-z0-9]#i', '', $_POST['user']);
	$data = htmlentities($_POST['data']);
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
	mysqli_close($db_conx);
	echo "reply_ok|$id";
	exit();
}
?>
<?php 
if (isset($_POST['action']) && $_POST['action'] == "delete_msg"){
	if(!isset($_POST['msg_id']) || $_POST['msg_id'] == ""){
		mysqli_close($db_conx);
		echo "status id is missing";
		exit();
	}
	$msg_id = preg_replace('#[^0-9]#', '', $_POST['msg_id']);
	$query = mysqli_query($db_conx, "SELECT to_user, from_user FROM private_msg WHERE id='$msg_id'");
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$reply_user = $row["to_user"]; 
		$from_user = $row["from_user"];
	}
    if ($from_user == $log_username || $reply_user == $log_username) {
		mysqli_query($db_conx, "DELETE FROM private_msg WHERE osid='$msg_id'");
		mysqli_close($db_conx);
	    echo "delete_ok";
		exit();
	}
}
?>
<?php 
if (isset($_POST['action']) && $_POST['action'] == "delete_chat"){
	if(!isset($_POST['chatid']) || $_POST['chatid'] == ""){
		mysqli_close($db_conx);
		echo "chat id is missing";
		exit();
	}
	$chatid = preg_replace('#[^0-9]#', '', $_POST['chatid']);
	$query = mysqli_query($db_conx, "SELECT id, username FROM private_chatlist WHERE id='$chatid'");
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$id = $row["id"]; 
		$username = $row["username"];
	}
    if ($username == $log_username) {
		mysqli_query($db_conx, "DELETE FROM private_chatlist WHERE id='$chatid'");
		mysqli_close($db_conx);
	    echo "delete_ok1";
		exit();
	}
}
?>
<?php 
if (isset($_POST['action']) && $_POST['action'] == "delete_reply"){
	if(!isset($_POST['replyid']) || $_POST['replyid'] == ""){
		mysqli_close($db_conx);
		exit();
	}
	$replyid = preg_replace('#[^0-9]#', '', $_POST['replyid']);
	$query = mysqli_query($db_conx, "SELECT osid, account_name, author FROM status WHERE id='$replyid'");
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$osid = $row["osid"];
		$account_name = $row["account_name"];
		$author = $row["author"];
	}
    if ($author == $log_username || $account_name == $log_username) {
		mysqli_query($db_conx, "DELETE FROM status WHERE id='$replyid'");
		mysqli_close($db_conx);
	    echo "delete_ok";
		exit();
	}
}
?>