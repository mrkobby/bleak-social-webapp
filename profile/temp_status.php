<?php
$status_ui = "";
$statuslist = "";
if($log_username == $u){
	$status_ui = '<textarea id="statustext" class="form-control inputBox textarea" onkeyup="statusMax(this,500)" placeholder="Hi '.$u.', What&#39;s new?"></textarea>';
	$status_ui .= '<div class="input-group" style="margin-top:2px;margin-bottom:30px;"><div class="fj"><button type="button" class="cg fm"><span class="fa fa-smile-o" ></span></button>';
	$status_ui .= '</div><div class=""><button type="button" class="cg fm">Add photo &nbsp;<span class="fa fa-camera"></span></button></div><div class="fj">';
	$status_ui .= '<button type="button" class="cg fm">Add Location &nbsp;<span class="fa fa-map-marker" ></span></button></div><div class="fj">';
	$status_ui .= '<button type="button" class="cg fm postBtn" id="statusBtn" onclick="postToStatus(\'status_post\',\'a\',\''.$u.'\',\'statustext\')">Post &nbsp;';
	$status_ui .= '<span class="fa fa-chevron-right" ></span> </button></div></div>';
} else if( $log_username != $u && $isFriend == true){
	$status_ui = '<textarea id="statustext" class="form-control inputBox textarea" onkeyup="statusMax(this,500)" placeholder="Hi '.$log_username.', say something to '.$u.'"></textarea>';
	$status_ui .= '<div class="input-group" style="margin-top:2px;margin-bottom:30px;"><div class="fj"><button type="button" class="cg fm"><span class="fa fa-smile-o" ></span></button>';
	$status_ui .= '</div><div class=""><button type="button" class="cg fm">Add photo &nbsp;<span class="fa fa-camera"></span></button></div><div class="fj">';
	$status_ui .= '<button type="button" class="cg fm">Add Location &nbsp;<span class="fa fa-map-marker" ></span></button></div><div class="fj">';
	$status_ui .= '<button type="button" class="cg fm postBtn" id="statusBtn" onclick="postToStatus(\'status_post\',\'c\',\''.$u.'\',\'statustext\')">Post &nbsp;';
	$status_ui .= '<span class="fa fa-chevron-right" ></span> </button></div></div>';
}
?><?php 
$author = "";
$replyauthor = "";
$picture1 = "";
$picture2 = "";
$sql = "SELECT * FROM status WHERE account_name='$u' AND type='a' OR account_name='$u' AND type='c' ORDER BY postdate DESC LIMIT 20";
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
	
	if($isFriend == true || $log_username == $u){
	    $statuslist .= '<input id="replytext_'.$statusid.'" class="form-control inputBox" onkeyup="statusMax(this,250)" style="margin-top: 2px;" placeholder="Write a comment here"></input>';	
		$statuslist .= '<button class="cg fm replyBtn " id="replyBtn_'.$statusid.'" onclick="replyToStatus('.$statusid.',\''.$u.'\',\'replytext_'.$statusid.'\',this)"><span class="fa fa-mail-reply"></span>&nbsp;Reply</button>';	
	}
}
?>
<script src="../assets/js/jquery-1.12.3.min.js"></script>
<script src="../assets/js/main.js"></script>
<script src="../assets/js/ajax.js"></script>
<script src="../assets/js/jquery.timeago.js"></script>
<script>
function postToStatus(action,type,user,ta){
	var data = _(ta).value;
	if(data == ""){
		alert("Please add an event first.");
		return false;
	}
	_("statusBtn").disabled = true;
	var ajax = ajaxObj("POST", "../php_parsers/status_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			var datArray = ajax.responseText.split("|");
			if(datArray[0] == "post_ok"){
				var sid = datArray[1];
				data = data.replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/\n/g,"<br />").replace(/\r/g,"<br />");
				_("statusBtn").disabled = false;
				_(ta).value = "";
				window.location = "../profile/?u=<?php echo $u ?>";
			} else {
				alert(ajax.responseText);
			}
		}
	}
	ajax.send("action="+action+"&type="+type+"&user="+user+"&data="+data);
}
function replyToStatus(sid,user,ta,btn){
	var data = _(ta).value;
	if(data == ""){
		alert("Type something first");
		return false;
	}
	_("replyBtn_"+sid).disabled = true;
	var ajax = ajaxObj("POST", "../php_parsers/status_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			var datArray = ajax.responseText.split("|");
			if(datArray[0] == "reply_ok"){
				var rid = datArray[1];
				data = data.replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/\n/g,"<br />").replace(/\r/g,"<br />");
				_("replyBtn_"+sid).disabled = false;
				_(ta).value = "";
				window.location = "../profile/?u=<?php echo $u ?>";
			} else {
				alert(ajax.responseText);
			}
		}
	}
	ajax.send("action=status_reply&sid="+sid+"&user="+user+"&data="+data);
}
function deleteStatus(statusid,statusbox){
	var conf = confirm("Press OK to confirm deletion of this status and its content");
	if(conf != true){
		return false;
	}
	var ajax = ajaxObj("POST", "../php_parsers/status_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "delete_ok"){
				_(statusbox).style.display = 'none';
				_("replytext_"+statusid).style.display = 'none';
				_("replyBtn_"+statusid).style.display = 'none';
			} else {
				alert(ajax.responseText);
			}
		}
	}
	ajax.send("action=delete_status&statusid="+statusid);
}
function deleteReply(replyid,replybox){
	var conf = confirm("Press OK to confirm deletion of this reply");
	if(conf != true){
		return false;
	}
	var ajax = ajaxObj("POST", "../php_parsers/status_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "delete_ok"){
				_(replybox).style.display = 'none';
			} else {
				alert(ajax.responseText);
			}
		}
	}
	ajax.send("action=delete_reply&replyid="+replyid);
}
function statusMax(field, maxlimit) {
	if (field.value.length > maxlimit){
		alert(maxlimit+" maximum character limit reached");
		field.value = field.value.substring(0, maxlimit);
	}
}

jQuery(document).ready(function() {
  jQuery("time.timeago").timeago();   
  /* jQuery.timeago.settings.strings.inPast = "time has elapsed";
  jQuery.timeago.settings.allowPast = false; */
});
</script>
  <?php echo $status_ui; ?>
<div id="statusarea">
  <?php echo $statuslist; ?>
</div>