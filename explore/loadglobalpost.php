<?php 
include_once("../php_extensions/check_login_status.php");
if($user_ok == false){
	header("location: ../index.php");
    exit();
}
?><?php	
$u = "";
$avatar = "";
$gender = "";
$statuslist = "";

$sql = "SELECT * FROM status WHERE type='a' ORDER BY postdate DESC";
$query = mysqli_query($db_conx, $sql);
$statusnumrows = mysqli_num_rows($query);
if($statusnumrows < 1){
	$statuslist = '<li class="b aml dp" style="text-align:center;"><h4 class="alc">Server timeout!</h4></li>';
} else {
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
		$imageScript = '<img class="qh cu bod1" src="../_Users/'.$u.'/'.$avatar.'" alt="'.$u.'">';
		if($picture == NULL && $gend == "f"){
			$image = '<img class="qh cu bod1" src="../assets/img/avatardefaultF1.png">';
		}else if($picture == NULL && $gend == "m"){
			$image = '<img class="qh cu bod1" src="../assets/img/avatardefaultM1.png">';
		}
		if($avatar == NULL && $gender == "f"){
		$imageScript = '<img class="qh cu bod1" src="../assets/img/avatardefaultF1.png" alt="'.$u.'">';
		}else if($avatar == NULL && $gender == "m"){
			$imageScript = '<img class="qh cu bod1" src="../assets/img/avatardefaultM1.png" alt="'.$u.'">';
		}
			
		$statuslist .= '<div id="status_'.$statusid.'"><li class="qf b aml" style="margin-top:7px;">';
		$statuslist .= '<a class="qj">'.$image.'</a><div class="qg"><div class="aoc">';
		$statuslist .= '<div class="qn"><small class="eg dp"><time class="timeago" datetime="'.$postdate.'">'.$postdate.'</time></small><h5><a href="../profile/?u='.$author.'">'.$author.'</a></h5></div><p>'.$data.'</p>';
	/*	$statuslist .= '<span class="fa fa-thumbs-up fm cg margin-5l lidi"></span><span class="fa fa-thumbs-down fm cg margin-5l lidi"></span>'; */
		$statuslist .= '</div></div></div></li>';
	}
}
echo $statuslist;
?>
<!-- <script src="../assets/js/jquery.timeago.js"></script>
<script>
jQuery(document).ready(function() {
  jQuery("time.timeago").timeago();   
  jQuery.timeago.settings.strings.inPast = "time has elapsed";
  jQuery.timeago.settings.allowPast = false;
});
</script> -->