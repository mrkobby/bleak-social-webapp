<?php 
include_once("../php_extensions/check_login_status.php");
if($user_ok == false){
	header("location: ../index.php");
    exit();
}
?><?php
 if(isset($_REQUEST["u"])){
	include_once("../php_extensions/db_conx.php");
	$u =  $_REQUEST['u']; 	
}
?><?php
$statuslist = "";
$avatar = "";
$gender = "";

$sql = "SELECT COUNT(id) FROM friends WHERE user1='$u' AND accepted='1' OR user2='$u' AND accepted='1'";
$query = mysqli_query($db_conx, $sql);
$query_count = mysqli_fetch_row($query);
$friend_count = $query_count[0];

	$all_friends = array();
	$sql = "SELECT user1 FROM friends WHERE user2='$u' AND accepted='1' ORDER BY RAND()";
	$query = mysqli_query($db_conx, $sql);
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		array_push($all_friends, $row["user1"]);
	}
	$sql = "SELECT user2 FROM friends WHERE user1='$u' AND accepted='1' ORDER BY RAND()";
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
$sql = "SELECT * FROM status WHERE $friendquery type='a' AND author='$u' ORDER BY postdate DESC LIMIT 100";
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
<script src="../assets/js/e-magz.js"></script>	
<script src="../assets/js/jquery.number.min.js"></script>
<!-- <script src="../assets/js/jquery.timeago.js"></script>
<script>
jQuery(document).ready(function() {
  jQuery("time.timeago").timeago();   
  jQuery.timeago.settings.strings.inPast = "time has elapsed";
  jQuery.timeago.settings.allowPast = false;
});
</script> -->