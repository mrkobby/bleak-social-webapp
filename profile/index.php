<?php 
include_once("../php_extensions/check_login_status.php");
if($user_ok == false){
	header("location: ../index.php");
    exit();
}
?><?php
$u = "";
$sex = "Male";
$profile_pic = "";
$profile_pic_btn = "";
$avatar_form = "";
$country = "";
$level = "";
$joindate = "";
$lastsession = "";
$stat = "";
if(isset($_GET["u"])){
	$u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
} else {
    header("location: ../index.php");
    exit();	
}
$sql = "SELECT * FROM users WHERE username='$u'";
$user_query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($user_query);
if($numrows < 1 ){
	echo "That user does not exist, press back";
    exit();	
}
while ($row = mysqli_fetch_array($user_query, MYSQLI_ASSOC)) {
	$profile_id = $row["id"];
	$gender = $row["gender"];
	$country = $row["country"];
	$level = $row["userlevel"];
	$avatar = $row["avatar"];
	$signup = $row["signup"];
	$lastlogin = $row["lastlogin"];
	$joindate = strftime("%b %d, %Y", strtotime($signup));
	$lastsession = strftime("%b %d, %Y", strtotime($lastlogin));
	$stat = $row["status"];
}
if($stat == "online"){
	$status_check = '<span class="fa fa-circle" style="color:#00db00;"></span>';
}else{
	$status_check = '';
}

$profile_pic = '<img class="cu qh imgDms2" data-width="640" data-height="640" data-action="zoom" src="../_Users/'.$u.'/'.$avatar.'" alt="'.$u.'">';
$profile_logo = '<img class="cu mylogo" src="../_Users/'.$u.'/'.$avatar.'" alt="'.$log_username.'">';
$profileLogo = '<img class="img-circle" src="../_Users/'.$u.'/'.$avatar.'" alt="'.$log_username.'">';
if($avatar == NULL){
	$profile_pic = '<img class="cu qh imgDms2" src="../assets/img/avatardefault.png" alt="'.$u.'">';
	$profile_logo = '<img class="cu mylogo" src="../assets/img/avatardefault.png" alt="'.$log_username.'">';
	$profileLogo = '<img class="img-circle" src="../assets/img/avatardefault.png" alt="'.$log_username.'">';
}
if($avatar == NULL && $gender == "f"){
	$sex = "Female";
	$profile_pic = '<img class="cu qh imgDms2" src="../assets/img/avatardefaultF1.png" alt="'.$u.'">';
	$profile_logo = '<img class="cu mylogo" src="../assets/img/avatardefaultF1.png" alt="'.$log_username.'">';
	$profileLogo = '<img class="img-circle" src="../assets/img/avatardefaultF1.png" alt="'.$log_username.'">';
}else if($avatar == NULL && $gender == "m"){
	$profile_pic = '<img class="cu qh imgDms2" src="../assets/img/avatardefaultM1.png" alt="'.$u.'">';
	$profile_logo = '<img class="cu mylogo" src="../assets/img/avatardefaultM1.png" alt="'.$log_username.'">';
	$profileLogo = '<img class="img-circle" src="../assets/img/avatardefaultM1.png" alt="'.$log_username.'">';
}
?><?php
$sql = "SELECT gender FROM users WHERE username='$log_username'";
$gender_query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($gender_query);
$edit_profile = 'style="display:;margin-top: 10px;"';
$quickcheck = 'style="display:;"';
if($u != $log_username){
	$dp = "SELECT avatar, gender FROM users WHERE username = '$log_username'";
	$query = mysqli_query($db_conx, $dp);
	while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$result = $row["avatar"];
		$result2 = $row["gender"];
		$profile_logo = '<img class="cu mylogo" src="../_Users/'.$log_username.'/'.$result.'" alt="'.$log_username.'">';
		$profileLogo = '<img class="img-circle" src="../_Users/'.$log_username.'/'.$result.'" alt="'.$log_username.'">';
		if($result == NULL){
			$profile_logo = '<img class="cu mylogo" src="../assets/img/avatardefault.png" alt="'.$log_username.'">';
			$profileLogo = '<img class="img-circle" src="../assets/img/avatardefault.png" alt="'.$log_username.'">';
		}
		if($result == NULL && $result2 == "f"){
			$profile_logo = '<img class="cu mylogo" src="../assets/img/avatardefaultF1.png" alt="'.$log_username.'">';
			$profileLogo = '<img class="img-circle" src="../assets/img/avatardefaultF1.png" alt="'.$log_username.'">';
		}else if($result == NULL && $result2 == "m"){
			$profile_logo = '<img class="cu mylogo" src="../assets/img/avatardefaultM1.png" alt="'.$log_username.'">';
			$profileLogo = '<img class="img-circle" src="../assets/img/avatardefaultM1.png" alt="'.$log_username.'">';
		}
	}
	$edit_profile = 'style="display:none;margin-top: 10px;"';
	$quickcheck = 'style="display:none;"';
}
$join = "SELECT signup FROM users WHERE username = '$log_username'";
$query = mysqli_query($db_conx, $join);
while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
	$signup = $row["signup"];
	$member = strftime("%b %d, %Y", strtotime($signup));
}
?><?php
$sql = "SELECT * FROM useroptions WHERE username='$u'";
$useroptions_query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($useroptions_query);

while ($row = mysqli_fetch_array($useroptions_query, MYSQLI_ASSOC)) {
	$status = $row["userstatus"];
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
$friend_btn_hide = 'style="display:;"';
$block_btn_hide = 'style="display:;"';
$num_frnds_enem = 'style="display:;margin-top:20px;"';
$no_display = 'style="display:;margin-top: 15px;margin-bottom: 30px;"';
if($u == $log_username && $user_ok == true){
	$friend_btn_hide = 'style="display:none;"';
	$block_btn_hide = 'style="display:none;"';
	$num_frnds_enem = 'style="display:none;margin-top:0px;"';
	$no_display = 'style="display:none;margin-top: 0px;margin-bottom: 0px;"';
}
$block_button = '<button '.$block_btn_hide.' class="cg but fx" id="blockBtn_" onclick="blockToggle(\'block\',\''.$u.'\',\'blockBtn\')"><span class="fa fa-ban vc"></span>&nbsp; Block User</button>';
$friend_button = '<button '.$friend_btn_hide.' class="cg but fx" id="friendBtn_" onclick="friendToggle(\'friend\',\''.$u.'\',\'friendBtn\')"><span class="fa fa-user vc"></span>&nbsp; Friend</button>';	

if($isFriend == true){
	$friend_button = '<button '.$friend_btn_hide.' class="cg but fx" id="friendBtn_" onclick="friendToggle(\'unfriend\',\''.$u.'\',\'friendBtn\')"><span class="fa fa-times-circle vc"></span>&nbsp; Unfriend</button>';
} else if($user_ok == true && $ownerBlockViewer == false){
	$friend_button = '<button '.$friend_btn_hide.' class="cg but fx" id="friendBtn_" onclick="friendToggle(\'friend\',\''.$u.'\',\'friendBtn\')"><span class="fa fa-user vc"></span>&nbsp; Friend</button>';
}

if($viewerBlockOwner == true){
	$block_button = '<button '.$block_btn_hide.' class="cg unblock fx" id="blockBtn_" onclick="blockToggle(\'unblock\',\''.$u.'\',\'blockBtn\')"><span class="fa fa-minus-circle vc"></span>&nbsp; Unblock User</button>';
} else if($user_ok == true && $u != $log_username){
	$block_button = '<button '.$block_btn_hide.' class="cg but fx" id="blockBtn_" onclick="blockToggle(\'block\',\''.$u.'\',\'blockBtn\')"><span class="fa fa-ban vc"></span>&nbsp; Block User</button>';
}
?><?php 
$sql = "SELECT id, background FROM useroptions WHERE username='$u'";
$bg_query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($bg_query);
if($numrows < 1 ){
	echo "That user does not exist, press back";
    exit();	
}
while ($row = mysqli_fetch_array($bg_query, MYSQLI_ASSOC)) {
	$profile_id = $row["id"];
	$background = $row["background"];
}
$bg_pic = 'style="background-image: url(../_Users/'.$u.'/'.$background.');"';
if($background == NULL){
	$bg_pic = 'style="background-image: url(../assets/img/cover.png);"';
}
?><?php
$status_ui = "";
if($log_username == $u){
	$status_ui = '<textarea id="statustext" class="form-control inputBox textarea" onkeyup="statusMax(this,500)" placeholder="Hi '.$u.', What&#39;s new?"></textarea>';
	$status_ui .= '<div class="input-group" style="margin-top:2px;margin-bottom:0px;"><div class="fj"><button type="button" class="cg fm" title="Add Mood">&nbsp;<span class="fa fa-smile-o" >';
	$status_ui .= '</span>&nbsp;</button></div><div class=""><button type="button" class="cg fm" title="Add Photo">&nbsp;<span class="fa fa-camera"></span>&nbsp;</button></div><div class="fj">';
	$status_ui .= '<button type="button" class="cg fm" title="Add Location">&nbsp;&nbsp;<span class="fa fa-map-marker" ></span>&nbsp;&nbsp;</button></div><div class="fj">';
	$status_ui .= '<button type="button" class="cg fm postBtn" id="statusBtn" onclick="postToStatus(\'status_post\',\'a\',\''.$u.'\',\'statustext\')">POST';
	$status_ui .= '</button></div></div>';
} else if( $log_username != $u && $isFriend == true){
	$status_ui = '<textarea id="statustext" class="form-control inputBox textarea" onkeyup="statusMax(this,500)" placeholder="Hi '.$log_username.', say something to '.$u.'"></textarea>';
	$status_ui .= '<div class="input-group" style="margin-top:2px;margin-bottom:0px;"><div class="fj"><button type="button" class="cg fm" title="Add Mood">&nbsp;<span class="fa fa-smile-o" >';
	$status_ui .= '</span>&nbsp;</button></div><div class=""><button type="button" class="cg fm" title="Add Photo">&nbsp;<span class="fa fa-camera"></span>&nbsp;</button></div><div class="fj">';
	$status_ui .= '<button type="button" class="cg fm" title="Add Location">&nbsp;&nbsp;<span class="fa fa-map-marker" ></span>&nbsp;&nbsp;</button></div><div class="fj">';
	$status_ui .= '<button type="button" class="cg fm postBtn" id="statusBtn" onclick="postToStatus(\'status_post\',\'c\',\''.$u.'\',\'statustext\')">POST';
	$status_ui .= '</button></div></div>';
}
?><?php include_once("../crn_tempo/theme.php"); ?>

<!DOCTYPE html>
<html lang="en">
  <head>
   <?php include_once("../crn_tempo/html_head.php");?>
  </head>
<body class="ang">
<?php include_once("../messages/messages.php"); ?>
<?php include_once("../crn_tempo/note_check.php"); ?>
<?php include_once("../user/friends.php"); ?>
<?php include_once("../user/frnds_modal.php"); ?>
<?php include_once("../user/enemies_modal.php"); ?>
	<div class="anq" id="app-growl"></div>
<!------------ MAIN NAV-------------------->
	<nav class="ck pc os app-navbar">
	  <div class="by">
		<div class="or">
		  <button type="button" class="ou collapsed" data-toggle="collapse" data-target="#navbar-collapse-main">
			<?php echo $tog_note ?>
			<span class="cv">Toggle navigation</span>
			<span class="ov"></span>
			<span class="ov"></span>
			<span class="ov"></span>
		  </button>
		  <a class="e" href="../user/?u=<?php echo $log_username;?>">
			<?php include_once("../crn_tempo/bleak_logo.php");?>
		  </a>
		</div>
		<div class="f collapse" id="navbar-collapse-main">
			<ul class="nav navbar-nav ss">
			  <li><a class="hand" onclick="window.location = '../user/?u=<?php echo $log_username; ?>';">Home</a></li>
			  <li class="active"><a class="hand" onclick="window.location = '../profile/?u=<?php echo $log_username; ?>';">Profile</a></li>
			  <li><a class="hand" onclick="window.location = '../explore/?u=<?php echo $log_username; ?>';">Explore</a></li>
			</ul>

			<ul class="nav navbar-nav og ale ss">
			    <li>
				  <a class="g pad-15" data-toggle="modal" href="#msgModal">
					  <span class="fa fa-envelope fa-fw"></span>
					  <div class="msg-bubble">
						<span class="fa fa-circle fa-lg notecheck-ls">
						<span class="num-ls">75</span>
					  </div>
					</a>
				 </li>
				 <li>
					<a class="g" href="#" data-toggle="dropdown">
					  <?php echo $bell ?>
					</a>
					<?php include_once("../notifications/note_dialog.php"); ?>			
				  </li>
				 <?php include_once("../user/userbox.php"); ?>	
				</ul>
			<!-- Search -->
			<form class="ow og i" role="search">
				<div class="et input-group">
					<input type="text" class="form-control" data-action="grow" placeholder="Search">
					<div class="fj">
					  <button type="button" class="cg fm fm-color">
						<span class="fa fa-search" ></span>
					  </button>
					</div>
				</div>
			</form>
	    <!-------------- ----------------->
			<?php include_once("../user/toggle-menu.php"); ?>
		  </div>
	  </div>
	</nav>
<!------------------------ END OF NOTE NAV ----------------------->
<!---------------- MAIN BODY ------------------>
<div class="ans dj" <?php echo $bg_pic ?>>
  <div class="by">
    <div class="ant">
      <?php echo $profile_pic ?>
      <h4 class="anv"><?php echo $u; ?>&nbsp;<?php echo $status_check; ?></h4>
     <!-- <p class="anu" style="color: #7be7e7;"><?php echo $status; ?></p> -->
	  <p><a onclick="window.location = '../edit/editprofile.php?u=<?php echo $u; ?>';" <?php echo $edit_profile ?> class="cg editP" >Edit profile <span class="fa fa-edit"></span></a></p>
	    <ul class="aoi" <?php echo $num_frnds_enem ?> >
            <li class="aoj">
              <a href="#frndModal" class="aku white" data-toggle="modal">
                Friends
                <?php echo $friends_num ?>
              </a>
            </li>
            <li class="aoj">
              <a href="#enemyModal" class="aku white" data-toggle="modal">
                Enemies
                <h5 class="ali">0</h5>
              </a>
            </li>
          </ul>
	  <div <?php echo $no_display ?> >
		<span id="friendBtn"><?php echo $friend_button; ?></span>&nbsp;&nbsp;&nbsp;<span id="blockBtn"><?php echo $block_button; ?></span>
	 </div>
    </div>
  </div>
  <!--- profile options --->
  <nav class="anw profile_nav">
    <ul class="nav ol">
      <li class="active hand"><a onclick="window.location = '../profile/?u=<?php echo $u; ?>';">Profile</a></li>
      <li class="hand"><a onclick="window.location = '../profile/photos.php?u=<?php echo $u; ?>';">Gallery</a></li>
      <li class="hand"><a onclick="window.location = '../profile/about.php?u=<?php echo $u; ?>';">About</a></li>
    </ul>
  </nav>
</div>
<!---------------- / END OF MAIN BODY ------------------>
<!-------  POSTS ----------->
<div class="by amt">
  <div class="gc">
	<!---------- LEFT SECTION ------------>
	<div class="gn">
	  <!----------------  ---------------->
	  <div class="ca alu ss">
        <a class="b ald"><b>Quick Info</b> </a>
		<div class="ca alu">
        <a class="b"><span class="eg"><span class="fa fa-star-half-o"></span> <b><?php echo $level?></b></span>User Ratings</a>
		<a class="b"><span class="eg"><span class="fa fa-heart"></span> NULL</span>Relationship</a>
		<a class="b"><span class="eg"><span class="fa fa-clock-o"></span> <?php echo $joindate; ?></span>Member since</a>
		<a class="b"><span class="eg"><span class="fa fa-rss"></span> <?php echo $friends_num_only ?> people</span>Followed by</a>
      </div>
      </div>
	</div>
	<!---------- /END OF LEFT SECTION ------------>
	<!----------------- MIDDLE SECTION ------------------->
    <div class="gz">
      <ul class="ca qo anx">
        <li class="qf b aml">
			<?php echo $status_ui ?> 
        </li>
		<div id="statuslogs"> 
			<div class="dp" style="text-align:center;"> 
				<!--<img src="../assets/img/preloader.gif" style="padding:10px;"> -->
				<img src="../assets/img/preloader2.gif" style="width:12px;"></img>
			</div>			
		</div>
      </ul>
    </div>
	<!---------- /END OF MIDDLE SECTION ------------>
	<!---------- RIGHT SECTION ------------>
	 <div class="gn">  
		<!---------- footer ------------->	
	   <?php include_once("../user/user-footer.php"); ?>
    </div>
  </div>
</div>
<!-------- / END OF MAIN BODY ------------>

<!------- /END OF POSTS ----------->

<script src="../assets/js/jquery-1.12.3.min.js"></script>
<script src="../assets/js/luci.1.0.1.js"></script>
<script src="../assets/js/luci.1.0.2.js"></script>
<script src="../assets/js/main.js"></script>
<script src="../assets/js/ajax.js"></script>
<script src="../assets/js/jquery.timeago.js"></script>
<script src="../assets/js/pace.min.js"></script>
<script src="../assets/js/e-magz.js"></script>	
<script src="../assets/js/jquery.number.min.js"></script>
<script type="text/javascript">
 // execute/clear BS loaders for docs
$(function(){
	if (window.BS&&window.BS.loader&&window.BS.loader.length) {
    while(BS.loader.length){(BS.loader.pop())()}
   }
})
$(document).ready(function() { Pace.restart(); });
//---------------------------------------------------------------------

//----------------------------------------------------------------------
function postToStatus(action,type,user,ta){
	var data = _(ta).value;
	if(data == ""){
		alert("ERROR! You need to type a message first!.");
		return false;
	}
	_(ta).value = "";
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function(){
		if(xmlhttp.readyState==4&&xmlhttp.status==200){
			document.getElementById('statuslogs').innerHTML = xmlhttp.responseText;
		}
	}	
	xmlhttp.open('GET','../status/insertprofilepost.php?action='+action+'&type='+type+'&user='+user+'&data='+data, true);
	xmlhttp.send();
	
}
$(document).ready(function(e){
	var me = "<?php echo $u ?>";
	var xmlhttp2 = new XMLHttpRequest();
	xmlhttp2.onreadystatechange = function(){
		if(xmlhttp2.readyState==4&&xmlhttp2.status==200){
			document.getElementById('statuslogs').innerHTML = xmlhttp2.responseText;
		}
	}	
	xmlhttp2.open('GET','../status/loadprofilepost.php?me='+me, true);
	xmlhttp2.send();
	
	$.ajaxSetup({cache:false});
	setInterval(function(){$('#statuslogs').load('../status/loadprofilepost.php?me='+me);}, 2000)
})
function replyToStatus(sid,user,ta,btn){
	var data = _(ta).value;
	if(data == ""){
		alert("ERROR! You need to type a message first!.");
		return false;
	}
	_(ta).value = "";
	var xmlhttp3 = new XMLHttpRequest();
	xmlhttp3.onreadystatechange = function(){
		if(xmlhttp3.readyState==4&&xmlhttp3.status==200){
			document.getElementById('statuslogs').innerHTML = xmlhttp3.responseText;
		}
	}	
	xmlhttp3.open('GET','../status/insertprofilereply.php?action=status_reply&sid='+sid+'&user='+user+'&data='+data, true);
	xmlhttp3.send();
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

//------------------------------------------------------------------
function friendToggle(type,user,elem){
	_(elem).innerHTML = '<span style="color:#00f8dd;">please wait..</span>';
	var ajax = ajaxObj("POST", "../php_parsers/friend_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "friend_request_sent"){
				_(elem).innerHTML = '<button class="cg sent fx" style="opacity:1;" disabled><span class="fa fa-check vc"></span>&nbsp; Sent!</button>';
			} else if(ajax.responseText == "unfriend_ok"){
				_(elem).innerHTML = '<button class="cg but fx" onclick="friendToggle(\'friend\',\'<?php echo $u; ?>\',\'friendBtn\')"><span class="fa fa-user vc"></span>&nbsp; Friend</button>';
			} else {
				alert(ajax.responseText);
				_(elem).innerHTML = '<h7 style="color:rgb(230, 236, 0);">Error mtc236: Something went wrong.</h7>';
			}
		}
	}
	ajax.send("type="+type+"&user="+user);
}

function blockToggle(type,blockee,elem){
	var conf = confirm("Press OK to confirm the '"+type+"' action on user <?php echo $u; ?>.");
	if(conf != true){
		return false;
	}
	var elem = document.getElementById(elem);
	elem.innerHTML = '<span style="color:#00f8dd;">please wait..</span>';
	var ajax = ajaxObj("POST", "../php_parsers/block_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "blocked_ok"){
				elem.innerHTML = '<button class="cg unblock fx" onclick="blockToggle(\'unblock\',\'<?php echo $u; ?>\',\'blockBtn\')"><span class="fa fa-minus-circle vc"></span>&nbsp; Unblock User</button>';
			} else if(ajax.responseText == "unblocked_ok"){
				elem.innerHTML = '<button class="cg but fx" onclick="blockToggle(\'block\',\'<?php echo $u; ?>\',\'blockBtn\')"><span class="fa fa-ban vc"></span>&nbsp; Block User</button>';
			} else {
				alert(ajax.responseText);
				elem.innerHTML = '<h7 style="color:rgb(230, 236, 0);">Error mtc276: Try again later. </h7>';
			}
		}
	}
	ajax.send("type="+type+"&blockee="+blockee);
}
</script>
</body>
</html>
