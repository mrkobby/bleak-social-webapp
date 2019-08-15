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
#----------------------------------------------------------------------------------------#
$i = "BleakTeam";
$sql = "SELECT COUNT(id) FROM friends WHERE user1='$log_username' OR user2='$log_username' AND user2='$i' OR user1='$i' AND accepted='1'";
$query = mysqli_query($db_conx, $sql);
$query_count = mysqli_fetch_row($query);
$friend_count = $query_count[0];
if($friend_count > 0 || $u == $i){
	$meh = 'display:none;';
}
$sql = "SELECT avatar FROM users WHERE username='BleakTeam'";
$query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($query);
while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
	$result = $row["avatar"];
}
$BleakTeam = '<img class="aoh imgDms3" src="../_Users/BleakTeam/'.$result.'" alt="BleakTeam">';
#-----------------------------------------------------------------------------------------#

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
$friend_button = '<button class="cg ts fx" id="friendBtn_"><span class="fa fa-user vc"></span>&nbsp; Friend</button>';
$block_button = '<button disabled id="blockBtn_">Block User</button>';

if($isFriend == true){
	$friend_button = '<button class="cg ts fx" id="friendBtn_" onclick="friendToggle(\'unfriend\',\''.$u.'\',\'friendBtn\')"><span class="fa fa-user vc"></span>&nbsp; Unfriend</button>';
} else if($user_ok == true && $ownerBlockViewer == false){
	$friend_button = '<button disabled class="cg ts fx" id="friendBtn_" onclick="friendToggle(\'friend\',\''.$u.'\',\'friendBtn\')"><span class="fa fa-user vc"></span>&nbsp; Friend</button>';
}

if($viewerBlockOwner == true){
	$block_button = '<button id="blockBtn_" onclick="blockToggle(\'unblock\',\''.$u.'\',\'blockBtn\')">Unblock User</button>';
} else if($user_ok == true && $u != $log_username){
	$block_button = '<button id="blockBtn_" onclick="blockToggle(\'block\',\''.$u.'\',\'blockBtn\')">Block User</button>';
}
?><?php
$sql = "SELECT * FROM useroptions WHERE username='BleakTeam'";
$useroptions_query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($useroptions_query);

while ($row = mysqli_fetch_array($useroptions_query, MYSQLI_ASSOC)) {
	$status = $row["userstatus"];
}
?><?php 
$sql = "SELECT id, background FROM useroptions WHERE username='BleakTeam'";
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
$bg_pic = 'style="background-image: url(../_Users/BleakTeam/'.$background.');"';
if($background == NULL){
	$bg_pic = 'style="background-image: url(../assets/img/cover.png);"';
}
?><?php include_once("../crn_tempo/theme.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php include_once("../crn_tempo/html_head.php");?>
</head>
<body class="ang">
<?php include_once("../crn_tempo/note_check.php"); ?>
<?php include_once("rand_ppl.php"); ?>
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
					<li><a class="hand" onclick="window.location = '../user/?u=<?php echo $log_username; ?>';">Home</a></li>
					<li><a class="hand" onclick="window.location = '../profile/?u=<?php echo $log_username; ?>';">Profile</a></li>
					<li  class="active"><a class="hand" onclick="window.location = '../explore/?u=<?php echo $log_username; ?>';">Explore</a></li>
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
	<!------------ ------------------>
				<?php include_once("../user/toggle-menu.php"); ?>
			</div>
		</div>
	</nav>
<!------------------------ /END OF NOTE NAV ----------------------->
<?php include_once("../messages/messages.php"); ?>
<!-------- MAIN BODY ------------>
<div class="by amt">
  <div class="gc">
	<!---------- LEFT SECTION ------------>
	<div class="gn">
	  <!---------------- sponsored ---------------->
		<div class="qv rc alu ss">
			<div class="qw">
			  <h5 class="ald">Sponsored</h5>
			  <hr>
			  <div data-grid="images">
				<img class="qh" src="../assets/img/sponsored.png">
			  </div>
		<!--	  <p><strong>It is time to visit the Dak Side.</strong> 
				Dak Incorporation is an experienced group of designers, developers and artists. Every client we work with becomes a part of the team.</p>
			  <button disabled class="cg ts fx">Get started</button> -->
			</div>
        </div>
	<!----- trend tags ------>
	  <div class="qv rc alu aog">
        <div class="qw">
			<h5 class="ald">Bleakers you may know  <small> · <a class="more hand">View All</a></small></h5>
			<hr>
				<ul class="qo anx">
					<?php echo $userlist; ?>
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
			<h3 class="alc">Random Posts</h3>
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
	  <div class="qv rc aog alu" style="<?php echo $meh ?>" >
        <div class="qx" <?php echo $bg_pic ?>></div>
        <div class="qw dj">
			<a class="hand" onclick="window.location = '../profile/?u=BleakTeam';"><?php echo $BleakTeam ?></a>
			<h5 class="qy"><a class="hand" onclick="window.location = '../profile/?u=BleakTeam';">BleakTeam</a> <b>@---</b></h5>
			<p class="alu"><?php echo $status ?></p>
			<span id="friendBtn"><?php echo $friend_button; ?></span>
        </div>
      </div>
	  <!--------------  ------------------>
	  <div class="qv rc sm sp ss">
         <div class="qw">
           <h5 class="ald"><b>Trending Tags</b> <small>· <a href="#">Clear</a></small></h5>
		  <hr>
          <ul class="eb tb">
            <li><a href="#">#3tos3m</a>
            <li><a href="#">#ndiguo</a>
            <li><a href="#">#cuntiana</a>
            <li><a href="#">#luci</a>
          </ul>
        </div>
      </div>
	<!---------- footer ------------->	
	<?php include_once("../user/user-footer.php"); ?>
    </div>
	<!---------- /END OF RIGHT SECTION ------------>
  </div>
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
$(document).ready(function() { Pace.restart(); });

$(document).ready(function(e){
	$.ajaxSetup({cache:false});
	setInterval(function(){$('#statuslogs').load('loadglobalpost.php');}, 2000)
})

function friendToggle(type,user,elem){
	_(elem).innerHTML = 'please wait..';
	var ajax = ajaxObj("POST", "../php_parsers/friend_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "friend_request_sent"){
				_(elem).innerHTML = '<button class="cg ts fx green" disabled><span class="fa fa-check vc"></span>&nbsp; Sent!</button>';
			} else if(ajax.responseText == "unfriend_ok"){
				_(elem).innerHTML = '<button class="cg ts fx" onclick="friendToggle(\'friend\',\'<?php echo $u; ?>\',\'friendBtn\')"><span class="fa fa-user vc"></span>&nbsp; Friend</button>';
			} else {
				alert(ajax.responseText);
				_(elem).innerHTML = '<h7 style="color:red;font-size:0.9em;">Something went wrong.</h7>';
			}
		}
	}
	ajax.send("type="+type+"&user="+user);
}
</script>
</body>
</html>