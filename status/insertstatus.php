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
	}
}
?><?php
$sql = "SELECT COUNT(id) FROM friends WHERE user1='$account_name' AND accepted='1' OR user2='$account_name' AND accepted='1'";
$query = mysqli_query($db_conx, $sql);
$query_count = mysqli_fetch_row($query);
$friend_count = $query_count[0];

	$all_friends = array();
	$sql = "SELECT user1 FROM friends WHERE user2='$account_name' AND accepted='1' ORDER BY RAND()";
	$query = mysqli_query($db_conx, $sql);
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		array_push($all_friends, $row["user1"]);
	}
	$sql = "SELECT user2 FROM friends WHERE user1='$account_name' AND accepted='1' ORDER BY RAND()";
	$query = mysqli_query($db_conx, $sql);
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		array_push($all_friends, $row["user2"]);
	}
	$friendArrayCount = count($all_friends);
	$orLogic = '';
	foreach($all_friends as $key => $user){
			$orLogic .= "author='$user' OR ";
	}
	$orLogic = chop($orLogic, "OR ");
		$friendquery = "type='a' AND $orLogic OR";
		if($orLogic == NULL){
			$friendquery = '';
		}
?><?php	
$u = "";
$avatar = "";
$gender = "";
$statuslist = "";

$sql = "SELECT * FROM status WHERE $friendquery type='a' AND author='$account_name' ORDER BY postdate DESC LIMIT 100";
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
		$statusDeleteButton = '<span id="sdb_'.$statusid.'"><a class="hand" onclick="return false;" onmousedown="deleteStatus(\''.$statusid.'\',\'status_'.$statusid.'\');" title="DELETE ENTIRE STATUS"><span class="fa fa-trash cg close margin-10"></span></a>';
	}
	$sql = "SELECT avatar,gender FROM users WHERE username='$author'";
	$ava_query = mysqli_query($db_conx, $sql);
	$numrows = mysqli_num_rows($ava_query);

	while ($row = mysqli_fetch_array($ava_query, MYSQLI_ASSOC)) {
		$picture = $row["avatar"];
		$gend = $row["gender"];
	}
	$image = '<img class="qh cu bod1" src="../_Users/'.$author.'/'.$picture.'" alt="'.$author.'">';
	if($picture == NULL && $gend == "f"){
		$image = '<img class="qh cu bod1" src="../assets/img/avatardefaultF1.png">';
	}else if($picture == NULL && $gend == "m"){
		$image = '<img class="qh cu bod1" src="../assets/img/avatardefaultM1.png">';
	}
		
	$statuslist .= '<div id="status_'.$statusid.'"><li class="qf b aml" style="margin-top:7px;">';
	$statuslist .= '<a class="qj">'.$image.'</a><div class="qg"><div class="aoc">';
	$statuslist .= '<div class="qn"><small class="eg dp"><time class="timeago" datetime="'.$postdate.'">'.$postdate.'</time></small><h5><a href="../profile/?u='.$author.'">'.$author.'</a></h5></div><p>'.$data.'</p>';
	$statuslist .= '<a class="love hand"><i class="ion-android-favorite-outline"></i> <div>0</div></a>';
	$statuslist .= '</div></div></div></li>';
	
/*	<div class="any" data-grid="images">
       <img style="display: none" data-width="640" data-height="640" data-action="zoom" src="../assets/img/gallery/instagram_3.jpg">
    </div>  */
}
echo $statuslist;
?>