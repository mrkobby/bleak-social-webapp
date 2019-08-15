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
	$avatar_form  = '<form id="avatar_form" enctype="multipart/form-data" method="post" action="php_parsers/photo_system.php">';
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
$profile_pic = '<img class="aoh imgDms3" src="../_Users/'.$log_username.'/'.$avatar.'" alt="'.$log_username.'">';
$profile_logo = '<img class="cu mylogo" src="../_Users/'.$log_username.'/'.$avatar.'" alt="'.$log_username.'">';
$profileLogo = '<img class="img-circle" src="../_Users/'.$log_username.'/'.$avatar.'" alt="'.$log_username.'">';
if($avatar == NULL){
	$profile_pic = '<img class="aoh imgDms3" src="../assets/img/avatardefault.png" alt="'.$log_username.'">';
	$profile_logo = '<img class="cu mylogo" src="../assets/img/avatardefault.png" alt="'.$log_username.'">';
	$profileLogo = '<img class="img-circle" src="../assets/img/avatardefault.png" alt="'.$log_username.'">';
}
if($avatar == NULL && $gender == "f"){
	$sex = "Female";
	$profile_pic = '<img class="aoh imgDms3" src="../assets/img/avatardefaultF1.png" alt="'.$log_username.'">';
	$profile_logo = '<img class="cu mylogo" src="../assets/img/avatardefaultF1.png" alt="'.$log_username.'">';
	$profileLogo = '<img class="img-circle" src="../assets/img/avatardefaultF1.png" alt="'.$log_username.'">';
}else if($avatar == NULL && $gender == "m"){
	$profile_pic = '<img class="aoh imgDms3" src="../assets/img/avatardefaultM1.png" alt="'.$log_username.'">';
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
$sql = "SELECT * FROM useroptions WHERE username='$log_username'";
$useroptions_query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($useroptions_query);

while ($row = mysqli_fetch_array($useroptions_query, MYSQLI_ASSOC)) {
	$status = $row["userstatus"];
}
?><?php 
$sql = "SELECT id, background FROM useroptions WHERE username='$log_username'";
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
?><?php
$alert1 = 'style="display:none;"';
$alert2 = 'style="display:;"';
if($avatar == 'NULL' || $bg_pic == 'NULL' || $status == "Hi there! I'm a new bleaker." || $education == 'NULL' || $location == 'NULL' || $hometown == 'NULL' || $work == 'NULL' || $roleModel == 'NULL' || $photos == ""){
	$alert1 = 'style="display:;"';
	$alert2 = 'style="display:none;"';
}
?><?php
$status_ui = "";
if($log_username == $u){
	$status_ui = '<textarea id="statustext" class="form-control inputBox textarea" onkeyup="statusMax(this,500)" placeholder="Hi '.$u.', What&#39;s new?"></textarea>';
	$status_ui .= '<div class="input-group" style="margin-top:2px;margin-bottom:0px;"><div class="fj"><button type="button" class="cg fm" title="Add Mood">&nbsp;<span class="fa fa-smile-o" >';
	$status_ui .= '</span>&nbsp;</button></div><div class=""><button type="button" class="cg fm" title="Add Photo">&nbsp;<span class="fa fa-camera"></span>&nbsp;</button></div><div class="fj">';
	$status_ui .= '<button type="button" class="cg fm" title="Add Location">&nbsp;&nbsp;<span class="fa fa-map-marker" ></span>&nbsp;&nbsp;</button></div><div class="fj">';
	$status_ui .= '<button type="button" class="cg fm postBtn" id="statusBtn" onclick="submitStatus(\'status_post\',\'a\',\''.$u.'\',\'statustext\',\'post_photo\')">POST';
	$status_ui .= '</button></div></div>';
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
<?php include_once("friends.php"); ?>
<?php include_once("frnds_modal.php"); ?>
<?php include_once("enemies_modal.php"); ?>
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
					<li class="active"><a class="hand" onclick="window.location = '../user/?u=<?php echo $u; ?>';">Home</a></li>
					<li><a class="hand" onclick="window.location = '../profile/?u=<?php echo $u; ?>';">Profile</a></li>
					<li><a class="hand" onclick="window.location = '../explore/?u=<?php echo $u; ?>';">Explore</a></li>
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
		<!------------ --------------->
			<?php include_once("toggle-menu.php"); ?>
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
		<!----- profile ---->
      <div class="qv rc aog alu">
        <div class="qx" <?php echo $bg_pic ?>></div>
        <div class="qw dj">
          <a class="hand" onclick="window.location = '../profile/?u=<?php echo $log_username; ?>';">
            <?php echo $profile_pic ?>
          </a>

          <h5 class="qy">
            <a class="hand" onclick="window.location = '../profile/?u=<?php echo $log_username; ?>';"><?php echo $log_username; ?></a>
          </h5>

          <p class="alu"><?php echo $status; ?></p>
		<!----- friends and enemies ----->
          <ul class="aoi">
            <li class="aoj">
              <a href="#frndModal" class="aku" data-toggle="modal">
                Friends
                <?php echo $friends_num ?>
              </a>
            </li>

            <li class="aoj">
              <a href="#enemyModal" class="aku" data-toggle="modal">
                Enemies
                <h5 class="ali">0</h5>
              </a>
            </li>
          </ul>
        </div>
      </div>
		<!-- about you --->
      <div class="qv rc sm sp">
        <div class="qw">
          <h5 class="ald">About <small>· <a class="hand" onclick="window.location = '../profile/about.php?u=<?php echo $log_username; ?>';">Edit</a></small></h5>
          <ul class="eb tb">
            <li><span class="fa fa-graduation-cap margin-10"></span>Went to <b class="dp hand"><?php echo $education; ?></b>
            <li><span class="fa fa-star margin-10"></span>Wants to be like <b class="dp hand"><?php echo $roleModel; ?></b>
            <li><span class="fa fa-briefcase margin-10"></span>Works at <b class="dp hand"><?php echo $work; ?></b>
            <li><span class="fa fa-home margin-10"></span>Lives in <b class="dp hand"><?php echo $location; ?></b>
            <li><span class="fa fa-map-marker margin-10"></span>From <b class="dp hand"><?php echo $hometown; ?></b>
			<li><span class="fa fa-heart margin-10"></span>Relationship - <span><?php echo $relationship; ?></span>
			<li><span class="fa fa-clock-o margin-10"></span>Member since <span><?php echo $joindate; ?></span>
			<li><span class="fa fa-rss margin-10"></span>Followed by <span><?php echo $friends_num_only ?> people</span>
          </ul>
        </div>
      </div>
	  <!---- photos box -->
       <div class="qv rc sm sp">
        <div class="qw">
          <h5 class="ald">Photos <small>· <a class="hand" onclick="window.location = '../profile/photos.php?u=<?php echo $log_username; ?>';">Edit</a></small></h5>
          <div data-grid="images" data-target-height="150">
            <?php echo $photos; ?>	
          </div>
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
    </div>
  <!---------- /END OF MIDDLE SECTION ------------>
  <!---------- RIGHT SECTION ------------>
	 <div class="gn">  
		<!--- alert message ---->
		<div class="alert pv alert-dismissible ss" role="alert" <?php echo $alert1 ?>>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<a class="pr" href="../profile/?u=<?php echo $log_username ?>">Visit your profile!</a> 
			Check your self, you aren't looking too good. Add bio, photos and some info about you.
        </div>
		<div class="alert pt alert-dismissible ss" role="alert" id="hithere" <?php echo $alert2 ?>>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<!-- <b>Hi <?php echo $log_username ?>! <span class="fa fa-heart"></span></b> -->
        </div>
        <!---- explore box ------>
   <!--<div class="qv rc alu ss">
         <div class="qw">
			<h5 class="ald">Explore <small>· <a class="hand" onclick="window.location = '../explore/?u=<?php echo $u; ?>';">Go to page</a></small></h5>
				<ul class="qo anx" style="text-align: center;">
					<img src="../assets/img/banana.gif" height="70px">
				</ul>
           </div>
           <div class="qz dp">
				<b>Find your friends and family</b>
			</div>
        </div> 
  <!------------  ------------>
<!--	<div class="qv rc alu ss">
			<div class="qw">
				<h5 class="ald">Friends <small>· <a class="hand" ><?php echo $friends_view_all_link; ?></a></small></h5>
				<ul class="qo anx">
					<?php echo $friendsHTML; ?>	
				</ul>
			</div>
			<div class="qz dp"></div>
		</div>
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
<script src="../assets/js/pace.min.js"></script>
<script src="../assets/js/e-magz.js"></script>	
<script src="../assets/js/jquery.number.min.js"></script>
<script>
$(function(){
   if (window.BS&&window.BS.loader&&window.BS.loader.length) {
	  while(BS.loader.length){(BS.loader.pop())()}
    }
})
$(document).ready(function() { Pace.restart(); });
/* ---------------------------------------------------------------- */
$(document).ready(function(e){
	var u = "<?php echo $log_username ?>";
	var xmlhttp2 = new XMLHttpRequest();
	xmlhttp2.onreadystatechange = function(){
		if(xmlhttp2.readyState==4&&xmlhttp2.status==200){
			document.getElementById('statuslogs').innerHTML = xmlhttp2.responseText;
		}
	}	
	xmlhttp2.open('GET','../status/loadstatus.php?u='+u, true);
	xmlhttp2.send();
	$.ajaxSetup({cache:false});
	setInterval(function(){$('#statuslogs').load('../status/loadstatus.php?u='+u);}, 2000)
})
/* ---------------------------------------------------------------- */
function submitStatus(action,type,user,ta,pic){
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
	xmlhttp.open('GET','../status/insertstatus.php?action='+action+'&type='+type+'&user='+user+'&data='+data+'&pic='+pic, true);
	xmlhttp.send();
}
/* ---------------------------------------------------------------- */
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

var myString = 'Hi <?php echo $log_username ?>!';
var myArray = myString.split("");
var loopTimer;
function frameLooper() {
	if(myArray.length > 0) {
		document.getElementById("hithere").innerHTML += myArray.shift();
	} else {
		clearTimeout(loopTimer); 
                return false;
	}
	loopTimer = setTimeout('frameLooper()',80);
}
frameLooper();
</script>
</body>
</html>