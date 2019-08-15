<?php
$friendsHTML = '';
$friends_view_all_link = '';
$friends_num = '<h5 class="ali">0</h5>';
$friends_num_only = '0';
$sql = "SELECT COUNT(id) FROM friends WHERE user1='$u' AND accepted='1' OR user2='$u' AND accepted='1'";
$query = mysqli_query($db_conx, $sql);
$query_count = mysqli_fetch_row($query);
$friend_count = $query_count[0];
if($friend_count < 1){
	$friendsHTML = '<span style="color: rgb(45, 137, 180);"> You have no friends yet. </span>';
} else {
	$max = 16;
	$all_friends = array();
	$sql = "SELECT user1 FROM friends WHERE user2='$u' AND accepted='1' ORDER BY RAND() LIMIT $max";
	$query = mysqli_query($db_conx, $sql);
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		array_push($all_friends, $row["user1"]);
	}
	$sql = "SELECT user2 FROM friends WHERE user1='$u' AND accepted='1' ORDER BY RAND() LIMIT $max";
	$query = mysqli_query($db_conx, $sql);
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		array_push($all_friends, $row["user2"]);
	}
	$friendArrayCount = count($all_friends);
	if($friendArrayCount > $max){
		array_splice($all_friends, $max);
	}
	if($friend_count > $max){
		$friends_view_all_link = '<a class="hand" title="'.$friend_count.'">View all</a>';
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
		$friendsHTML .= '<li class="qf alm"><a class="qj" href="../profile/?u='.$friend_username.'">';
		$friendsHTML .= '<img class="qh cu" style="border: 1px solid #000;" src="'.$friend_pic.'" alt="'.$friend_username.'" title="'.$friend_username.'"></a>';
		$friendsHTML .= '<div class="qg"><strong><a href="../profile/?u='.$friend_username.'">'.$friend_username.'</a></strong> <b>@---</b>';
		$friendsHTML .= '<div class="aoa"><a href="../messages/?u='.$friend_username.'" class="cg ts fx"><span class="fa fa-envelope vc"></span> Message</a></div></div></li>';
		$friends_num = '<h5 class="ali">'.$friend_count.'</h5>';
		$friends_num_only = $friend_count;
	}
}
?>
							