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
if(isset($_GET["u"])){
	$u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
} else {
    header("location: ../messages/index.php?u=$log_username");
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
$join = "SELECT signup FROM users WHERE username = '$log_username'";
$query = mysqli_query($db_conx, $join);
while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
	$signup = $row["signup"];
	$member = strftime("%b %d, %Y", strtotime($signup));
}
?><?php
$sql = "SELECT gender FROM users WHERE username='$log_username'";
$gender_query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($gender_query);
$chat_title = '<h3 class="alc" id="title"><b>Chats</b></h3>';
$user_chatlist = 'style="display:block;"';
$user_chat = 'style="display:none;"';
$active = 'class="active"';
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
	$chat_title = '<h3 span class="alc" id="title"><button class="cg fm" onclick="backToMsgs()"><span class="fa fa-chevron-left"></span></button>&nbsp; <b>'.$u.'</b></h3>';
	$user_chatlist = 'style="display:none;"';
	$user_chat = 'style="display:block;"';
	$active = 'class=""';
}
?><?php
$msg_ui = "";
if($log_username != $u){
	$msg_ui = '<form name="chatform"><li><div class="input-group"><input type="text" id="msg" name="msg" class="form-control inputBox" onkeyup="textMax(this,500)" placeholder="Type a message">';
	$msg_ui .= '<div class="fj""><button type="button" id="msgBtn" class="cg fm" onclick="submitChat()">';
	$msg_ui .= 'Send <span class="fa fa-chevron-right" ></span></button></div></div></li>';	
}
?><?php include_once("../crn_tempo/theme.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php include_once("../crn_tempo/html_head.php");?>
</head>
<body class="ang">
<?php include_once("../crn_tempo/note_check.php"); ?>
<?php include_once("friends_list.php"); ?>
<?php include_once("frnds_list.php"); ?>
<?php include_once("message_note.php"); ?>
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
				<a class="e" href="../user">
					<?php include_once("../crn_tempo/bleak_logo.php");?>
				</a>
			</div>
			<div class="f collapse" id="navbar-collapse-main">
				<ul class="nav navbar-nav ss">
					<li><a class="hand" onclick="window.location = '../user/?u=<?php echo $u; ?>';">Home</a></li>
					<li><a class="hand" onclick="window.location = '../profile/?u=<?php echo $u; ?>';">Profile</a></li>
					<li><a class="hand" onclick="window.location = '../explore/?u=<?php echo $u; ?>';">Explore</a></li>
					<li <?php echo $active ?>><a class="hand" onclick="window.location = '../messages/?u=<?php echo $log_username; ?>';">Chats</a></li>
				</ul>

				<ul class="nav navbar-nav og ale ss">
				   <li>
					<a class="g pad-15 hand" onclick="window.location = '../messages/?u=<?php echo $log_username; ?>';">
					  <span class="fa fa-envelope-o fa-fw"></span>
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
		<!------------ --------------->
			<?php include_once("../user/toggle-menu.php"); ?>
			</div>
		</div>
	</nav>
<!------------------------ END OF NOTE NAV ----------------------->
<?php include_once("../messages/messages.php"); ?>
<!-------- MAIN BODY ------------>
<div class="by amt">
  <div class="gc">
  <!---------- LEFT SECTION ------------>			
    <div class="gn">
		<div class="qv rc alu ss">
			 <div class="qw">
				<h5 class="ald">All Friends</h5>
				  <ul class="qo anx">
					<?php echo $friendsHTML; ?>	
				  </ul>
			   </div>
			<div class="qz dp"></div>
		</div>
		<div class="qv rc frnd">
			 <div class="qw">
				  <ul class="qo anx">
					<a class="b ald" href="#frndModal" data-toggle="modal"><h5 style="text-align:center;">View friends list</h5></a>
				  </ul>
			   </div>
			<div class="qz dp"></div>
		</div>
    </div>
   <!---------- /END OF LEFT SECTION ------------>
   <!----------------- MIDDLE SECTION ------------------->
    <div class="gz">
      <ul class="ca qo anx">
		 <li class="b aml">
            <?php echo $chat_title ?>
         </li>
		  <div id="msgGroup" <?php echo $user_chatlist ?>>			
			 <?php echo $msg_list ?>
		  </div>
		</ul>
		<div class="alj" id="msgcontent" <?php echo $user_chat ?>>
            <ul class="qo aob">
			<?php echo $msg_ui ?>
			<div id="chatlogs" class="qf alu"> 
				<div class="dp" style="text-align:center;"> 
					<!--<img src="../assets/img/preloader.gif" style="padding:10px;"> -->
					<img src="../assets/img/preloader2.gif" style="width:12px;"></img>
				</div>			
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

<script src="../assets/js/jquery-1.12.3.min.js"></script>
<script src="../assets/js/luci.1.0.1.js"></script>
<script src="../assets/js/luci.1.0.2.js"></script>
<script src="../assets/js/main.js"></script>
<script src="../assets/js/ajax.js"></script>
<script src="../assets/js/jquery.timeago.js"></script>
<script src="../assets/js/pace.min.js"></script>
<script>
jQuery(document).ready(function() {
  jQuery("time.timeago").timeago();   
  /* jQuery.timeago.settings.strings.inPast = "time has elapsed";
  jQuery.timeago.settings.allowPast = false; */
});
$(document).ready(function() { Pace.restart(); });

$(function(){
   if (window.BS&&window.BS.loader&&window.BS.loader.length) {
	  while(BS.loader.length){(BS.loader.pop())()}
    }
 })
function showmsg(){
	_("msgGroup").style.display = "none";
	_("title").innerHTML = '<button class="cg fm" onclick="backToMsgs()"><span class="fa fa-chevron-left"></span></button>&nbsp;otherUser';
	_("msgcontent").style.display = "block";
}
function backToMsgs(){
	window.location = "../messages/?u=<?php echo $log_username ?>";
}
function submitChat(){
	if(chatform.msg.value == ''){
		alert("ERROR! You need to type a message first!.");
		return;
	}
	var user2 = "<?php echo $u ?>";
	var msg = chatform.msg.value;
	var xmlhttp = new XMLHttpRequest();
	chatform.msg.value = "";
	xmlhttp.onreadystatechange = function(){
		if(xmlhttp.readyState==4&&xmlhttp.status==200){
			document.getElementById('chatlogs').innerHTML = xmlhttp.responseText;
		}
	}
	xmlhttp.open('GET','insertchat.php?msg='+msg+'&user2='+user2,true);
	xmlhttp.send();
}
$(document).ready(function(e){
	$.ajaxSetup({cache:false});
	setInterval(function(){$('#chatlogs').load('chatlogs.php');}, 2000)
})
function textMax(field, maxlimit) {
	if (field.value.length > maxlimit){
		alert(maxlimit+" maximum character limit reached");
		field.value = field.value.substring(0, maxlimit);
	}
}
$('#msg').bind('keypress', function(e){
	if(e.keyCode == 13){
		return false;
	}
})
</script>
</body>
</html>