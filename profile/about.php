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
$joindate = "";
$lastsession = "";
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
	$avatar = $row["avatar"];
	$signup = $row["signup"];
	$lastlogin = $row["lastlogin"];
	$joindate = strftime("%b %d, %Y", strtotime($signup));
	$lastsession = strftime("%b %d, %Y", strtotime($lastlogin));
}

$profile_pic = '<img class="cu qh imgDms2" src="../_Users/'.$u.'/'.$avatar.'" alt="'.$u.'">';
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
$sql = "SELECT * FROM userinfo WHERE username='$u'";
$userinfo_query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($userinfo_query);

while ($row = mysqli_fetch_array($userinfo_query, MYSQLI_ASSOC)) {
	$education = $row["education"];
	$location = $row["location"];
	$hometown = $row["hometown"];
	$work = $row["work"];
	$roleModel = $row["roleModel"];
	$mobile = $row["mobile"];
	$alt_email = $row["email"];
}
if($education == NULL){
	$education =  'NULL';
}if($location == NULL){
	$location =  'NULL';
}if($hometown == NULL){
	$hometown =  'NULL';
}if($work == NULL){
	$work =  'NULL';
}if($roleModel == NULL){
	$roleModel =  'NULL';
}
?><?php 
$sql = "SELECT * FROM userbasic WHERE username='$u'";
$userbasic_query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($userbasic_query);

while ($row = mysqli_fetch_array($userbasic_query, MYSQLI_ASSOC)) {
	$nickname = $row["nickname"];
	$relationship = $row["relationship"];
	$crush = $row["crush"];
	$tv = $row["tv"];
	$book = $row["book"];
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
$edit_profile = 'style="display:;margin-top: 10px;"';
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
	$editBtn = 'display:none;';
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
$bg_pic = 'style="background-image: url(../_Users/'.$u.'/'.$background.');height:100px;"';
if($background == NULL){
	$bg_pic = 'style="background-image: url(../assets/img/cover.png);height:100px;"';
}
?><?php 
$photos = "";
$max = 12;
$sql = "SELECT filename FROM photos WHERE user='$u' ORDER BY RAND() LIMIT $max";
$query = mysqli_query($db_conx, $sql);
if(mysqli_num_rows($query) < 1){
	$gallery_list = '<h7 style="color:rgb(188, 83, 83);">You have not uploaded any photos yet.</h7>';
} else {
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$file = $row["filename"];
		$photos .= '<div><img class="photo_box" data-width="540" data-height="540" data-action="zoom" src="../_Users/'.$u.'/'.$file.'"></div>';
    }
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
		  <a class="e" href="../user/">
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
  <!--- profile options --->
  <nav class="anw profile_nav">
    <ul class="nav ol">
      <li class="hand"><a onclick="window.location = '../profile/?u=<?php echo $u; ?>';">Profile</a></li>
      <li class="hand"><a onclick="window.location = '../profile/photos.php?u=<?php echo $u; ?>';">Gallery</a></li>
      <li class="active hand" id="about"><a onclick="window.location = '../profile/about.php?u=<?php echo $u; ?>';">About</a></li>
    </ul>
  </nav>
</div>
<!---------------- / END OF MAIN BODY ------------------>
    <div class="zzz">
      <ul class="ca qo anx">
        <li class="qf b aml">
		  <div class="qv rc">
			<div class="qw">
			  <h5 class="ald"> About <?php echo $u ?> <small>· <a class="hand" onclick="window.location = '../edit/aboutinfo.php?u=<?php echo $u; ?>';" style="<?php echo $editBtn ?>">Edit</a></small></h5>
			  <hr class="dp">
			  <div class="qn">
				<h5 class="eg"><b><?php echo $education; ?></b></h5>
				<h5 class="dp">Went to &nbsp;<span class="fa fa-graduation-cap"></h5>
			  </div>
			  <hr class="dp">
			  <div class="qn">
				<h5 class="eg"><b><?php echo $location; ?></b></h5>
				<h5 class="dp">Lives in &nbsp;<span class="fa fa-home"></h5>
			  </div>
			  <hr class="dp">
			  <div class="qn">
				<h5 class="eg"><b><?php echo $hometown; ?></b></h5>
				<h5 class="dp">Home Town &nbsp;<span class="fa fa-map-marker"></h5>
			  </div>
			  <hr class="dp">
			  <div class="qn">
			    <h5 class="eg"><b><?php echo $work; ?></b></h5>
			    <h5 class="dp">Works at &nbsp;<span class="fa fa-briefcase"></h5>
			  </div>
			  <hr class="dp">
			  <div class="qn">
			    <h5 class="eg"><b><?php echo $roleModel; ?></b></h5>
			    <h5 class="dp">Wants to be like &nbsp;<span class="fa fa-star"></h5>
			  </div>
			  <hr class="dp">
			</div>
          </div> 
        </li>
		<!---------------------------------------------------------->
		<li class="qf b aml">
		  <div class="qv rc">
			<div class="qw">
			  <h5 class="ald"> Contact Info <small>· <a class="hand" onclick="window.location = '../edit/contactinfo.php?u=<?php echo $u; ?>';" style="<?php echo $editBtn ?>">Edit</a></small></h5>
			  <hr class="dp">
			  <div class="qn">
			    <h5 class="eg"><b><?php echo $mobile; ?></b></h5>
				<h5 class="dp">Mobile number &nbsp;<span class="fa fa-phone"></h5>	
			  </div>
			  <hr class="dp">
			  <div class="qn">
				<h5 class="eg"><b><?php echo $alt_email; ?></b></h5>
				<h5 class="dp">Alternative Email &nbsp;<span class="fa fa-envelope-o"></h5>
			  </div>
			  <hr class="dp">
			</div>
          </div>          
        </li>	
		<!------------------------------------------------------------------->
		<li class="qf b aml" id="basicLi1" style="display:;">
		  <div class="qv rc">
			<div class="qw">
			  <h5 class="ald"> Basic Info <small>· <a class="hand" onclick="window.location = '../edit/basicinfo.php?u=<?php echo $u; ?>';" style="<?php echo $editBtn ?>">Edit</a></small></h5>
			  <hr class="dp">
			  <div class="qn">
			    <h5 class="eg"><b><?php echo $nickname; ?></b></h5>
				<h5 class="dp">Nickname &nbsp;<span class="fa fa-user"></h5>	
			  </div>
			  <hr class="dp">
			  <div class="qn">
			    <h5 class="eg"><b><?php echo $relationship; ?></b></h5>
				<h5 class="dp">Relationship &nbsp;<span class="fa fa-flag-o"></h5>	
			  </div>
			  <hr class="dp">
			  <div class="qn">
				<h5 class="eg"><b><?php echo $crush; ?></b></h5>
				<h5 class="dp">Crushing on &nbsp;<span class="fa fa-heart"></h5>
			  </div>
			  <hr class="dp">
			  <div class="qn">
				<h5 class="eg"><b><?php echo $tv; ?></b></h5>
				<h5 class="dp">Favorite TV series &nbsp;<span class="fa fa-video-camera"></h5>
			  </div>
			  <hr class="dp">
			  <div class="qn">
				<h5 class="eg"><b><?php echo $book; ?></b></h5>
				<h5 class="dp">Best book/novel read &nbsp;<span class="fa fa-book"></h5>
			  </div>
			  <hr class="dp">
			</div>
          </div>          
        </li>
		<!-------------------------------------------------------------------->
        <li class="qf b aml">
		  <div class="qv rc lip" id="myPhotos">
			<div class="qw">
			  <h5 class="ald">Photos <small class="hand">· <a onclick="window.location = '../profile/photos.php?u=<?php echo $u; ?>';" style="<?php echo $editBtn ?>">Add <span class="fa fa-plus-circle"></a></small></h5>
			  <hr class="dp">
			  <div data-grid="images" data-target-height="150">
				<?php echo $photos; ?>	
			  </div>
			</div>
          </div> 
        </li>
      </ul>
    </div>

<script src="../assets/js/jquery-1.12.3.min.js"></script>
<script src="../assets/js/luci.1.0.1.js"></script>
<script src="../assets/js/luci.1.0.2.js"></script>
<script src="../assets/js/main.js"></script>
<script src="../assets/js/ajax.js"></script>
<script src="../assets/js/pace.min.js"></script>
<script type="text/javascript">
 // execute/clear BS loaders for docs
   $(function(){
     if (window.BS&&window.BS.loader&&window.BS.loader.length) {
       while(BS.loader.length){(BS.loader.pop())()}
    }
 })
$(document).ajaxStart(function() { Pace.restart(); });

/* $(document).ready(function(){
	 window.scroll(0,350);
 }) */
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
