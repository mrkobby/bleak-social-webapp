<?php
$friendsList = '';
$sql = "SELECT COUNT(id) FROM friends WHERE user1='$log_username' AND accepted='1' OR user2='$log_username' AND accepted='1'";
$query = mysqli_query($db_conx, $sql);
$query_count = mysqli_fetch_row($query);
$friend_count = $query_count[0];
if($friend_count < 1 && $u == $log_username){
	$friendsList = '<span style="color: rgb(45, 137, 180);margin-left:38%;"> You have no friends yet </span>';
}else {
	$max = 16;
	$all_friends = array();
	$sql = "SELECT user1 FROM friends WHERE user2='$log_username' AND accepted='1' ORDER BY RAND() LIMIT $max";
	$query = mysqli_query($db_conx, $sql);
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		array_push($all_friends, $row["user1"]);
	}
	$sql = "SELECT user2 FROM friends WHERE user1='$log_username' AND accepted='1' ORDER BY RAND() LIMIT $max";
	$query = mysqli_query($db_conx, $sql);
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		array_push($all_friends, $row["user2"]);
	}
	$friendArrayCount = count($all_friends);
	if($friendArrayCount > $max){
		array_splice($all_friends, $max);
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
		$friendsList .= '<span class="b" href="../messages/?u='.$friend_username.'">';
		$friendsList .= '<div class="qf">';
		$friendsList .= '<span class="qj">';
		$friendsList .= '<a href="../messages/?u='.$friend_username.'">';
		$friendsList .= '<img class="cu qh" style="border: 1px solid #000;" src="'.$friend_pic.'" alt="'.$friend_username.'" title="'.$friend_username.'"></a></span>';
		$friendsList .= '<div class="qg">';
		$friendsList .= '<strong><a href="../messages/?u='.$friend_username.'">'.$friend_username.'</a></strong>';
		$friendsList .= '<div class="aof">'.$friendstatus.'</div>';
		$friendsList .= '</div></div></span>';
	}
}
?>

<div class="cd fade" id="frndModal" tabindex="-1" role="dialog" aria-labelledby="frndModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="d">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="">Your Friends&nbsp; | &nbsp;&nbsp;<a class="hand" onclick="window.location = '../messages/?u=<?php echo $log_username; ?>';">Chats</a></h4>
      </div>

      <div class="modal-body amf">
		<div class="input-group">
			<input type="text" class="form-control" placeholder="Search">
			<div class="fj"">
				<button type="button" class="cg fm">
					<span class="fa fa-search" ></span>
				</button>
			</div>
		</div>
        <div class="uq">
          <div class="qo cj ca">
			<?php echo $friendsList ?>
          </div>
        </div>
      </div>
	  <hr>
    </div>
  </div>
</div>