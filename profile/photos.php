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
$isOwner;
if($u == $log_username && $user_ok == true){
	$profile_pic_btn = '<a href="#" onclick="return false;" onmousedown="toggleElement(\'avatar_form\')">Swap Avatar Form</a>';
	$avatar_form  = '<form id="avatar_form" enctype="multipart/form-data" method="post" action="../php_parsers/photo_system.php">';
	$avatar_form .=   '<h4>Change Profile Picture </h4>';
	$avatar_form .=   '<input style="width:180px;" type="file" name="avatar" required>';
	$avatar_form .=   '<p><input type="submit" value="Upload"></p>';
	$avatar_form .= '</form>';
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
$add_photos = 'style="display:;margin-top: 0px;"';
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
	$add_photos = 'style="display:none;margin-top:0px;"';
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
$bg_pic = 'style="background-image: url(../_Users/'.$u.'/'.$background.');height:150px;"';
if($background == NULL){
	$bg_pic = 'style="background-image: url(../assets/img/cover.png);height:150px;"';
}
?><?php
$photo_form = "";
$isOwner = "no";
if($u == $log_username && $user_ok == true){
	$isOwner = "yes";
	$photo_form  = '<form id="photo_form" class="qf b aml" enctype="multipart/form-data" method="post" action="../php_parsers/photo_system.php">';
	$photo_form .=   '<span id="photo_form_txt">Select gallery and add photos.</span>';
	$photo_form .=   '<button type="button" id="close" class="close"><span>×</span></button><br /><br />';
	$photo_form .=   '<p><select style="width:100%;margin: auto;border:1px solid rgb(156, 156, 156);" id="choose_gallery" class="form-control inputBox" name="gallery" required>';
	$photo_form .=     '<option value="" style="color:rgb(91, 91, 91);">choose gallery:</option>';
	$photo_form .=     '<option value="Selfie"> Selfie </option>';
	$photo_form .=     '<option value="Family"> Family </option>';
	$photo_form .=     '<option value="Pets"> Pets</option>';
	$photo_form .=     '<option value="Friends"> Friends </option>';
	$photo_form .=     '<option value="Random"> Random </option>';
	$photo_form .=   '</select></p>';
	$photo_form .=   '<p><label for="choose_photo" class="cg fm photocam" style="font-size: 1.5em;" title="Select photo"><span class="fa fa-photo"></span></label>';
	$photo_form .=   '<input id="choose_photo" style="width:0px;display:inline-block;visibility:hidden;" type="file" name="photo" accept="image/*" required></p>';
	$photo_form .=   '<button id="upload_button" class="cg fm editP" style="border-color: #76BCBF;" type="submit">Upload Photo</button>';
	$photo_form .= '</form>';
}

$gallery_list = "";
$sql = "SELECT DISTINCT gallery FROM photos WHERE user='$u'";
$query = mysqli_query($db_conx, $sql);
if(mysqli_num_rows($query) < 1){
	$gallery_list = '<h7 style="color:rgb(10, 85, 128);">No photos to display.</h7>';
} else {
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$gallery = $row["gallery"];
		$countquery = mysqli_query($db_conx, "SELECT COUNT(id) FROM photos WHERE user='$u' AND gallery='$gallery'");
		$countrow = mysqli_fetch_row($countquery);
		$count = $countrow[0];
		$filequery = mysqli_query($db_conx, "SELECT filename FROM photos WHERE user='$u' AND gallery='$gallery' ORDER BY RAND()");
		$filerow = mysqli_fetch_row($filequery);
		$file = $filerow[0];
		$gallery_list .= '<div>';
		$gallery_list .=   '<div onclick="showGallery(\''.$gallery.'\',\''.$u.'\')">';
		$gallery_list .=     '<img src="../_Users/'.$u.'/'.$file.'" alt="cover photo">';
		$gallery_list .=   '</div><br>';
		$gallery_list .=   '<b>'.$gallery.'</b> ('.$count.')';
		$gallery_list .= '</div>';
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
  <div class="by">
    <div class="ant">
	  <p><a id="gallery_toggle" <?php echo $add_photos ?> class="cg editP" >Add Photos <span class="fa fa-plus-circle"></span></a></p>

    </div>
  </div>
  <!--- profile options --->
  <nav class="anw profile_nav">
    <ul class="nav ol">
      <li class="hand"><a onclick="window.location = '../profile/?u=<?php echo $u; ?>';">Profile</a></li>
      <li class="active hand"><a onclick="window.location = '../profile/photos.php?u=<?php echo $u; ?>';">Gallery</a></li>
      <li class="hand"><a onclick="window.location = '../profile/about.php?u=<?php echo $u; ?>';">About</a></li>
    </ul>
  </nav>
</div>
<!---------------- / END OF MAIN BODY ------------------>
<!-------  PHOTOS ----------->
<div class="by alx" id="gallery" style="text-align: center;display:none;">
	<span id="photo_form_txt">Hi <?php echo $u ?>, add a new photo to one of your galleries!</span>
	<div id="photo_form" class="zzz" style="text-align: center;">
		<div style="width:50%;margin:auto;box-shadow: 0px 0px 8px 3px;"><?php echo $photo_form; ?></div>
	</div>
</div>
<div class="by" id="gallery">
	<div id="photo_form" class="zzz" style="text-align: center;">
		  <span id="section_title" style="font-size:1.1em;"><?php echo $u; ?>&#39;s Photo Galleries</span>	
		  <div id="galleries" data-grid="images"><?php echo $gallery_list; ?></div>
		  <div id="photos"></div>
	</div>
	<div id="picbox"></div>	
</div>
<div class="by" id="gallery" style="text-align:center;margin-top: 15px;">
	 <p style="">This Photo Gallery belongs to <a href="../profile/?u=<?php echo $u; ?>"><?php echo $u; ?></a></p>
</div>
<!------- /END OF PHOTOS ----------->

<script src="../assets/js/jquery-1.12.3.min.js"></script>
<script src="../assets/js/luci.1.0.1.js"></script>
<script src="../assets/js/luci.1.0.2.js"></script>
<script src="../assets/js/main.js"></script>
<script src="../assets/js/ajax.js"></script>
<script src="../assets/js/pace.min.js"></script>
<script>
 // execute/clear BS loaders for docs
   $(function(){
     if (window.BS&&window.BS.loader&&window.BS.loader.length) {
       while(BS.loader.length){(BS.loader.pop())()}
    }
 })
$(document).ajaxStart(function() { Pace.restart(); });

 $(document).ready(function(){
	 /* window.scroll(0,350); */
	 $("#gallery_toggle").click(function(){
		 $("#gallery").show(500);
		 $("#gallery_toggle").animate({opacity: 0.15},800)
	 })
	 $("#close").click(function(){
		 $("#gallery").hide(500);
		 $("#gallery_toggle").animate({opacity: 1},500)
	 })
 })
function showGallery(gallery,user){
	_("galleries").style.display = "none";
	_("section_title").innerHTML ='<button class="cg fm" style="border:1px solid grey;" title="Back to Galleries" onclick="backToGalleries()">'+
								'<span class="fa fa-chevron-left"></span></button> &nbsp;'+user+'&#39;s "'+gallery+'" Gallery &nbsp;';
								/*'<button class="cg fm" style="border:1px solid grey;" onclick="nextGalleries()">'+
								'<span class="fa fa-chevron-right"></span></button>';*/
	_("photos").style.display = "block";
	_("photos").innerHTML = 'loading photos..';
	var ajax = ajaxObj("POST", "../php_parsers/photo_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			_("photos").innerHTML = '';
			var pics = ajax.responseText.split("|||");
			for (var i = 0; i < pics.length; i++){
				var pic = pics[i].split("|");
				_("photos").innerHTML += '<div><img onclick="photoShowcase(\''+pics[i]+'\')" src="../_Users/'+user+'/'+pic[1]+'" alt="pic"></div>';
			}
			_("photos").innerHTML += '<p style="clear:left;"></p>';
		}
	}
	ajax.send("show=galpics&gallery="+gallery+"&user="+user);
}
function backToGalleries(){
	_("photos").style.display = "none";
	_("section_title").innerHTML = "<?php echo $u; ?>&#39;s Photo Galleries";
	_("galleries").style.display = "block";
}
function photoShowcase(picdata){
	var data = picdata.split("|");
	_("photo_form").style.display = "none";
	_("section_title").style.display = "none";
	_("photos").style.display = "none";
	_("picbox").style.display = "block";
	_("picbox").innerHTML = '<button  class="cg fm" onclick="closePhoto()">x</button>';
	_("picbox").innerHTML += '<img class="photo_box" data-action="zoom" src="../_Users/<?php echo $u; ?>/'+data[1]+'" alt="photo">';
	if("<?php echo $isOwner ?>" == "yes"){
		_("picbox").innerHTML += '<span id="deletelink"><a href="#" onclick="return false;" style="text-decoration:none;font-weight:bold;" onmousedown="deletePhoto(\''+data[0]+'\')">Delete <span class="fa fa-trash"></a></span>';
	}
}
function closePhoto(){
	_("photo_form").style.display = "block";
	_("picbox").innerHTML = '';
	_("picbox").style.display = "none";
	_("photos").style.display = "block";
	_("section_title").style.display = "block";
}
function deletePhoto(id){
	var conf = confirm("Press OK to confirm the delete action on this photo.");
	if(conf != true){
		return false;
	}
	_("deletelink").style.visibility = "hidden";
	var ajax = ajaxObj("POST", "../php_parsers/photo_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "deleted_ok"){
				alert("The picture has been deleted.");
				window.location = "photos.php?u=<?php echo $u; ?>";
			}
		}
	}
	ajax.send("delete=photo&id="+id);
}
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
