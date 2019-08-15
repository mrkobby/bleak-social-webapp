<?php
$friendsHTML = '';
$sql = "SELECT COUNT(id) FROM friends WHERE user1='$log_username' AND accepted='1' OR user2='$log_username' AND accepted='1'";
$query = mysqli_query($db_conx, $sql);
$query_count = mysqli_fetch_row($query);
$friend_count = $query_count[0];
if($friend_count < 1){
	$friendsHTML = '<span style="color: rgb(45, 137, 180);"> You have no friends yet. </span>';
} else {
	$all_friends = array();
	$sql = "SELECT user1 FROM friends WHERE user2='$log_username' AND accepted='1' ORDER BY RAND()";
	$query = mysqli_query($db_conx, $sql);
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		array_push($all_friends, $row["user1"]);
	}
	$sql = "SELECT user2 FROM friends WHERE user1='$log_username' AND accepted='1' ORDER BY RAND()";
	$query = mysqli_query($db_conx, $sql);
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		array_push($all_friends, $row["user2"]);
	}

	$orLogic = '';
	foreach($all_friends as $key => $user){
			$orLogic .= "username='$user' OR ";
	}
	$orLogic = chop($orLogic, "OR ");
	$sql = "SELECT username, avatar FROM users WHERE $orLogic";
	$query = mysqli_query($db_conx, $sql);
	while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$friend_username = $row["username"];
		$friend_avatar = $row["avatar"];
		if($friend_avatar != ""){
			$friend_pic = '../_Users/'.$friend_username.'/'.$friend_avatar.'';
		} else {
			$friend_pic = '../assets/img/avatardefault.png';
		}
	$sql = "SELECT * FROM useroptions WHERE username='$friend_username'";
	$useroptions_query = mysqli_query($db_conx, $sql);
	$numrows = mysqli_num_rows($useroptions_query);
	while ($row = mysqli_fetch_array($useroptions_query, MYSQLI_ASSOC)) {
		$friendstatus = $row["userstatus"];
	}
		$friendsHTML .= '<li class="qf alm"><a class="qj" href="../messages/?u='.$friend_username.'">';
		$friendsHTML .= '<img class="qh cu" style="border: 1px solid #000;" src="'.$friend_pic.'" alt="'.$friend_username.'" title="'.$friend_username.'"></a>';
		$friendsHTML .= '<div class="qg"><strong><a href="../messages/?u='.$friend_username.'">'.$friend_username.'</a></strong> ';
		$friendsHTML .= '<a href="../profile/?u='.$friend_username.'" class="cg fm font-size-10" title="Profile"><span class="fa fa-user vc"></span></a>';
		$friendsHTML .= '<div class="statusC aoa" style="font-size:10px;">'.$friendstatus.'</div></div></li><hr>';
	}
}
?>
<!-- style="overflow: hidden !important;text-overflow: ellipsis;width: 80%;white-space: nowrap;" -->							