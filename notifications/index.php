<?php 
include_once("../php_extensions/check_login_status.php");
if($user_ok == false){
	header("location: ../index.php");
    exit();
} 
?><?php
$u = "";
$sex = "Male";
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
if($u != $log_username){
	$dp = "SELECT avatar FROM users WHERE username = '$log_username'";
	$query = mysqli_query($db_conx, $dp);
	while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$result = $row["avatar"];
		$profile_logo = '<img class="cu mylogo" src="../_Users/'.$log_username.'/'.$result.'" alt="'.$log_username.'">';
	}
}
?><?php include_once("../crn_tempo/theme.php"); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include_once("../crn_tempo/html_head.php");?>
  </head>
<body class="ang">
<?php include_once("../crn_tempo/note_check.php"); ?>
<?php include_once("note_checked.php"); ?>
	<div class="anq" id="app-growl"></div>
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
			  <li><a class="hand" onclick="window.location = '../user/?u=<?php echo $u; ?>';">Home</a></li>
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
				<a class="g hand" onclick="window.location = '../notifications/?u=<?php echo $u; ?>';">
				  <span class="fa fa-bell-o fa-fw"></span>
				</a>
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
	<!----- account info ---->
      <div class="ca alu">
        <a href="#" class="b">
          <span class="eg"><span class="fa fa-star-half-o"></span> <b><?php echo $level ?></b></span>
          User Ratings
        </a>
        <?php echo $clearNotes; ?>
      </div>
    <!---- request box -->
      <?php echo $friend_requests_box; ?>
    </div>
<!---------- /END OF LEFT SECTION ------------>
<!----------------- MIDDLE SECTION ------------------->
    <div class="gz">
      <ul class="ca qo anx">
        <li class="b aml">
          <h3 class="alc"><b>Notifications</b></h3>
        </li>
		<?php echo $notification_list; ?>
      </ul>
    </div>
	<!--- activity 9 ----->	
    <div class="gn">
      <div class="qv rc alu ss">
        <div class="qw">
        <h5 class="ald"><b>Updates &nbsp;<span class="fa fa-spinner"></span></b></h5>
        <ul class="qo anx" style="text-align:center;margin-bottom:0;">
          <li class="qf"> 
			  <div class="qn b" style="font-size:13px;"><span class="dp">New Themes will be available</span></div>
			  <div class="qn b" style="font-size:13px;"><span class="dp">Get alerts about unrecognized logins</span></div>
			  <div class="qn b" style="font-size:13px;"><span class="dp">Use two-way factor authentication</span></div>
			  <div class="qn b" style="font-size:13px;"><span class="dp">Quick slider settings</span></div>
			  <div class="qn b" style="font-size:13px;"><span class="dp">Fast and easy chats</span></div>
			  <div class="qn b" style="font-size:13px;"><span class="dp">New look for growls and tips </span></div>
			  <div class="qn b" style="font-size:13px;"><span class="dp">Live emoji posts</span></div>
          </li>
        </ul>
        </div>
        <div class="qz">7 upcoming updates and more ..</div>
      </div>
  <!------------  ------------>
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
<script type="text/javascript">
jQuery(document).ready(function() {
  jQuery("time.timeago").timeago();   
  /* jQuery.timeago.settings.strings.inPast = "time has elapsed";
  jQuery.timeago.settings.allowPast = false; */
});
$(document).ajaxStart(function() { Pace.restart(); });

 // execute/clear BS loaders for docs
   $(function(){
     if (window.BS&&window.BS.loader&&window.BS.loader.length) {
       while(BS.loader.length){(BS.loader.pop())()}
    }
 })

function friendReqHandler(action,reqid,user1,elem){
	/* var conf = confirm("Press OK to '"+action+"' this friend request.");
	if(conf != true){
		return false;
	} */
	_(elem).innerHTML = '<span style="color:#00f8dd;font-size:12px;">processing..</span>';
	var ajax = ajaxObj("POST", "../php_parsers/friend_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "accept_ok"){
			_(elem).innerHTML = "<h7 style='color:green;font-size: 12px;'>Request Accepted</h7><br/><span style='font-size: 12px;'>You are now friends</span>";
		}else if(ajax.responseText == "reject_ok"){
			_(elem).innerHTML = "<h7 style='color:rgb(192, 41, 41);font-size: 12px;'>Request Rejected</h7>";
		}else {
			_(elem).innerHTML = ajax.responseText;
		}
	}
}
ajax.send("action="+action+"&reqid="+reqid+"&user1="+user1);
}			
</script>
</body>
</html>

