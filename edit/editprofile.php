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

if(isset($_POST["s"])){
	include_once("../php_extensions/db_conx.php");
	$s =  $_POST['s'];
	$sql = "UPDATE useroptions SET userstatus='$s', editdate=now() WHERE username='$log_username'";
    $query = mysqli_query($db_conx, $sql); 
	echo "update_success";
	exit();
}
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
$isOwner = false;
if($u == $log_username && $user_ok == true){
	$isOwner = true;
	$avatar_form  = '<form id="avatar_form" enctype="multipart/form-data" method="post" action="../php_parsers/photo_system.php">';
	$avatar_form .=   '<label for="files" class="cg fm photocam"><span class="fa fa-camera"></span></label>';
	$avatar_form .=   '<input id="files" style="width:0px;display:inline-block;visibility:hidden;" type="file" name="avatar" required>&nbsp;';
	$avatar_form .=   '&nbsp;&nbsp;<input class="cg fm editP black" style="display:inline-block;" type="submit" value="Upload">';
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
$profile_pic = '<img class="qh imgDms3" data-width="640" data-height="640" data-action="zoom" style="width:100%;height: 25%;" src="../_Users/'.$u.'/'.$avatar.'" alt="'.$u.'">';
$profile_logo = '<img class="cu mylogo" src="../_Users/'.$u.'/'.$avatar.'" alt="'.$u.'">';
$profileLogo = '<img class="cu mylogo" src="../_Users/'.$u.'/'.$avatar.'" alt="'.$u.'">';
if($avatar == NULL){
	$profile_pic = '<img class="qh" style="width:100%;height: 25%;" src="../assets/img/avatardefault.png" alt="'.$u.'">';
	$profile_logo = '<img class="cu mylogo" src="../assets/img/avatardefault.png" alt="'.$u.'">';
	$profileLogo = '<img class="cu mylogo" src="../assets/img/avatardefault.png" alt="'.$u.'">';
}
if($avatar == NULL && $gender == "f"){
	$sex = "Female";
	$profile_pic = '<img class="qh" style="width:100%;height: 25%;" src="../assets/img/avatardefaultF1.png" alt="'.$u.'">';
	$profile_logo = '<img class="cu mylogo" src="../assets/img/avatardefaultF1.png" alt="'.$u.'">';
	$profileLogo = '<img class="cu mylogo" src="../assets/img/avatardefaultF1.png" alt="'.$u.'">';
}else if($avatar == NULL && $gender == "m"){
	$profile_pic = '<img class="qh" style="width:100%;height: 25%;" src="../assets/img/avatardefaultM1.png" alt="'.$u.'">';
	$profile_logo = '<img class="cu mylogo" src="../assets/img/avatardefaultM1.png" alt="'.$u.'">';
	$profileLogo = '<img class="cu mylogo" src="../assets/img/avatardefaultM1.png" alt="'.$u.'">';
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
$isOwner = false;
if($u == $log_username && $user_ok == true){
	$isOwner = true;
	$bg_form  = '<form id="bg_form" enctype="multipart/form-data" method="post" action="../php_parsers/background_photo_system.php">';
	$bg_form .=   '<span>Select a picture by clicking the camera button, then click upload.</span>&nbsp;&nbsp;';
	$bg_form .=   '<label for="file" class="cg fm photocam"><span class="fa fa-camera"></span></label>';
	$bg_form .=   '<input id="file" style="width:0px;display:inline-block;visibility:hidden;" type="file" name="background" required>&nbsp;';
	$bg_form .=   '&nbsp;&nbsp;&nbsp;<input style="display:inline-block;" class="cg fm editP black" type="submit" value="Upload">';
	$bg_form .= '</form>';
}
while ($row = mysqli_fetch_array($bg_query, MYSQLI_ASSOC)) {
	$profile_id = $row["id"];
	$background = $row["background"];
}
$bg_pic = 'style="background-image: url(../_Users/'.$u.'/'.$background.');border: 5px solid rgb(245, 248, 250);"';
if($background == NULL){
	$bg_pic = 'style="background-image: url(../assets/img/cover.png);border: 5px solid rgb(245, 248, 250);"';
}
?><?php include_once("../crn_tempo/theme.php"); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
   <?php include_once("../crn_tempo/html_head.php");?>
  </head>
<body class="ang">
<?php include_once("../crn_tempo/note_check.php"); ?>
<?php include_once("../messages/messages.php"); ?>
	<div class="anq" id="app-growl"></div>
<!------------ MAIN NAV-------------------->
	<nav class="ck pc os app-navbar">
	  <div class="by">
		<div class="or">
		  <button type="button" class="ou collapsed" data-toggle="collapse" data-target="#navbar-collapse-main">
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
				  <a class="g hand">
					  <span class="fa fa-envelope fa-fw" style="color: #ababab;"></span>
					</a>
				 </li>
				 <li>
					<a class="g hand">
					  <span class="fa fa-bell fa-fw" style="color: #ababab;" ></span>
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
<div class="by amt">
  <div class="gc">
	<ul class="ca qo anx">
        <li class="b aml">
          <h3 class="alc text-center"><b>Edit profile</b></h3>
        </li>
	</ul>
	<!----------------- MIDDLE SECTION ------------------->
	<div class="hl">
		<div class="">
			<a class="b ald"><b>Bio &nbsp;<span class="fa fa-user"></span></b> </a>
			  <form role="form" id="update" method="post" action="processing.php" onSubmit="return false;">
					<div class="input-group et" style="margin-top:2px;">
						<input class="form-control fm-color" name="statusupdate" id="statusupdate" onfocus="emptyElement('status')" 
						onKeyUp="textCounter(this.form.statusupdate,this.form.countDisplay);" onKeyDown="textCounter(this.form.statusupdate,this.form.countDisplay);"
						type="text" placeholder="A little something about yourself" value="<?php echo $status; ?>" autofocus ></input>
						<div class="fj">
							<input type="text" readonly class="cg fm" name="countDisplay" value="150" style="width: 50px;color:rgb(156, 156, 156);cursor:none;border: 0px;pointer-events: none;"></input>
						</div>
					</div>
			 </form>
			<div class="qz"></div>
		</div>
		<div class="">
			<a class="b ald"><b>Cover Picture &nbsp;<span class="fa fa-photo"></span></b> </a>
			<div class="ans dj" <?php echo $bg_pic ?>></div>
			<div class="qz">
				<?php echo $bg_form ?>
			</div>
		</div>
	</div>
	<!---------- /END OF MIDDLE SECTION ------------>
	<div class="gn">
	 	<!---------- RIGHT SECTION ------------>
     <div class="ca alu">
        <a class="b ald"><b>Profile Picture &nbsp;<span class="fa fa-camera"></span></b> </a>
		<div class="dj"><?php echo $profile_pic ?></div>
		<div class="qz">
			<?php echo $avatar_form ?>
		</div>
      </div>
	  <div class="ca alu">
        <a class="b ald" style="text-align:center">
			<button class="cg fm saveS" id="button1" onclick="updatestatus()" style="width:100%;">Save Changes &nbsp;<span class="fa fa-save"></span></button>
			<span id="status"></span>
		</a>
		<a class="b ald" style="text-align:center">
			<button class="cg fm saveS" id="button1" onclick="window.location = '../profile/?u=<?php echo $log_username; ?>';" style="width:100%;">Cancel &nbsp;<span class="fa fa-times"></span></button>
		</a>
		<div class="qz"></div>
      </div>
	</div>
  </div>
  <br><br><br><br>
</div>
<!-------- / END OF MAIN BODY ------------>

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
    $('.ajax').click(function(){
        $.ajax({url: '#', success: function(result){
            $('.ajax-content').html('<hr>Ajax Request Completed !');
    }});
});
function emptyElement(x){
	_(x).innerHTML = "";
}
function updatestatus(){
	var s = _("statusupdate").value;
	if(s != ""){
		_("status").innerHTML = 'checking ...';
		var ajax = ajaxObj("POST", "editprofile.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
				if(ajax.responseText != "update_success"){
					 _("status").innerHTML = ajax.responseText;
				}else {
					window.location = "../profile/?u=<?php echo $log_username ?>";
				}
	        }
        }
        ajax.send("s="+s);
	}else{
		window.location = "../profile/?u=<?php echo $log_username ?>";
	}
}
var maxAmount = 150;
function textCounter(textField, showCountField) {
    if (textField.value.length > maxAmount) {
        textField.value = textField.value.substring(0, maxAmount);
	} else { 
        showCountField.value = maxAmount - textField.value.length;
	}
}	
</script>
</body>
</html>
