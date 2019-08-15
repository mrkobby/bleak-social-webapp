<?php
include_once("../php_extensions/check_login_status.php");
if($user_ok == false){
	header("location: ../index.php");
    exit();
}
?><?php 
if(isset($_POST["sch"])){
	
	include_once("../php_extensions/db_conx.php");
	
	$_SESSION['sch'] = $sch = preg_replace('#[^a-z .]#i', '', $_POST['sch']);
	$_SESSION['loc'] = $loc = preg_replace('#[^a-z .]#i', '', $_POST['loc']);
	$_SESSION['ht'] = $ht = preg_replace('#[^a-z .]#i', '', $_POST['ht']);
	$_SESSION['wrk'] = $wrk = preg_replace('/[^\w#& ]/', '', $_POST['wrk']);
	$_SESSION['rm'] = $rm = preg_replace('#[^a-z .]#i', '', $_POST['rm']);

	$sql = "UPDATE userinfo SET education='$sch', location='$loc', hometown='$ht', work='$wrk', roleModel='$rm', updatedate=now() WHERE username='$log_username'";
	$query = mysqli_query($db_conx, $sql);
	
	echo "about_success";
	exit();
}
?><?php 
if(isset($_POST["num"])){
	
	include_once("../php_extensions/db_conx.php");
	
	$_SESSION['num'] = $num = preg_replace('#[^0-9]#i', '', $_POST['num']);
	$_SESSION['alt_e'] = $alt_e = mysqli_real_escape_string($db_conx, $_POST['alt_e']);

	$sql = "UPDATE userinfo SET mobile='$num', email='$alt_e' WHERE username='$log_username'";
	$query = mysqli_query($db_conx, $sql);
	
	echo "contact_success";
	exit();
}
?><?php 
if(isset($_POST["nik"])){
	
	include_once("../php_extensions/db_conx.php");
	
	$_SESSION['nik'] = $nik = preg_replace('#[^a-z .]#i', '', $_POST['nik']);
	$_SESSION['rela'] = $rela = preg_replace('#[^a-z .]#i', '', $_POST['rela']);
	$_SESSION['crh'] = $crh = preg_replace('#[^a-z .]#i', '', $_POST['crh']);
	$_SESSION['tv'] = $tv = preg_replace('/[^\w#& ]/', '', $_POST['tv']);
	$_SESSION['book'] = $book = preg_replace('#[^a-z .]#i', '', $_POST['book']);

	$sql = "UPDATE userbasic SET book='$book', nickname='$nik', relationship='$rela', crush='$crh', tv='$tv', update_date=now() WHERE username='$log_username'";
	$query = mysqli_query($db_conx, $sql);
	
	echo "info_success";
	exit();
}
?><?php 
if(isset($_POST["t"])){	
	include_once("../php_extensions/db_conx.php");
	$_SESSION['t'] = $t = preg_replace('#[^a-z .]#i', '', $_POST['t']);
	$sql = "UPDATE useroptions SET theme='$t' WHERE username='$log_username'";
	$query = mysqli_query($db_conx, $sql);
	echo "theme_changed";
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
?><?php include_once("../crn_tempo/theme.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php include_once("../crn_tempo/html_head.php");?>
	<link href="../assets/css/material-kit.css" rel="stylesheet"/>
</head>
<body class="ang">
<?php include_once("../crn_tempo/note_check.php"); ?>
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
	  <!----------------  ---------------->
     <div class="ca alu">
        <a class="b ald"><b>Quick Settings &nbsp;<span class="fa fa-gear"></b></a>
		<span class="b ald dp"><div class="togglebutton eg"><label><input type="checkbox"></label></div>Private account</span>
        <span class="b dp"><div class="togglebutton eg"><label><input type="checkbox"></label></div>Public posts</span>
		<span class="b ald dp"><div class="togglebutton eg"><label><input type="checkbox"></label></div>Allow comments</span>
		<span class="b dp"><div class="togglebutton eg"><label><input type="checkbox"></label></div>Filter posts</span>
		
      </div>
<!--  <div class="ca alu">
        <a class="b ald"><b>Search History &nbsp;<span class="fa fa-history"></b></a>
		<a class="b hand"><span class="fa fa-eye eg"></span><b class="dp">View history</b></a>
		 <a class="b hand"><span class="fa fa-trash eg"></span><b class="dp">Clear all search history</b></a>
      </div> -->
	</div> 
	<!---------- /END OF LEFT SECTION ------------>
	<!----------------- MIDDLE SECTION ------------------->
    <div class="gz">
      <ul class="ca qo anx">
		<li class="b aml">
          <h3 class="alc"><b>Settings</b></h3>
        </li>
		 <li class="qf b aml">
		  <div class="rc">
			<div class="qw">
			  <h5 class="ald"> General &nbsp;<span class="fa fa-user"></span>&nbsp;<small>· <a class="hand" onclick="">Edit</a></small></h5>
			  <hr class="dp">
			  <div class="qn b">
				<h5 class="eg dp"><b><i>coming soon</i></b></h5>
				<h5 class="dp">Name</h5>
			  </div>
			  <hr class="dp">
			  <div class="qn b">
				<h5 class="eg"><b><?php echo $u; ?></b></h5>
				<h5 class="dp">Username</h5>
			  </div>
			  <hr class="dp">
			</div>
          </div> 
        </li>
		<!---------------------------------->
		<li class="qf b aml">
		  <div class="rc">
			<div class="qw">
			  <h5 class="ald"> Security &nbsp;<span class="fa fa-lock"></span>&nbsp;<small>· <a class="hand" onclick="">Edit</a></small></h5>
			  <hr class="dp">
			  <div class="qn b">
				<h5 class="eg"><b style="font-size: 10px;"><span class="fa fa-circle"></span> <span class="fa fa-circle"></span> <span class="fa fa-circle"></span></b></h5>
				<h5 class="dp">Change password</h5>
			  </div>
			  <hr class="dp">
			  <div class="qn b">
				<h5 class="eg dp"><b><i>coming soon</i></b></h5>
				<h5 class="dp">Get alerts about unrecognized logins</h5>
			  </div>
			  <hr class="dp">
			  <div class="qn b">
				<h5 class="eg dp"><b><i>coming soon</i></b></h5>
				<h5 class="dp">Use two-way factor authentication</h5>
			  </div>
			  <hr class="dp">
			</div>
          </div> 
        </li>
	  </ul>
	  <div id="overlay"></div>
    </div>
	<!---------- RIGHT SECTION ------------>			
    <div class="gn">  
	  <div class="qv rc alu">
		 <div class="qw">
			<a class="b ald"><b>Themes &nbsp;<span class="fa fa-adjust"></b></a>
			<form role="form" method="post">
				<span class="b ald"><input type="radio" name="theme" onclick="changeTheme()" id="default" value="default" class="eg" <?php echo $check1 ?>>Default</span>
				<span class="b"><input type="radio" name="theme" onclick="changeTheme()" id="darkS" value="dark" class="eg" <?php echo $check2 ?>>Dark World</span>
				<span class="b dp"><span class="eg"><b><i>coming soon</i></b></span>Nitro</span>
				<span class="b dp"><span class="eg"><b><i>coming soon</i></b></span>Pinky Perk</span>
			</form>
		  </div>
		</div>
	</div>
	<!---------- /END OF MIDDLE SECTION ------------>
  </div>
</div>
<!-------- / END OF MAIN BODY ------------>

<script src="../assets/js/jquery-1.12.3.min.js"></script>
<script src="../assets/js/luci.1.0.1.js"></script>
<script src="../assets/js/luci.1.0.2.js"></script>
<script src="../assets/js/main.js"></script>
<script src="../assets/js/ajax.js"></script>
<script src="../assets/js/material-kit.js"></script>
<script src="../assets/js/material.min.js"></script>
<script src="../assets/js/pace.min.js"></script>
<script type="text/javascript">
$(function(){
     if (window.BS&&window.BS.loader&&window.BS.loader.length) {
       while(BS.loader.length){(BS.loader.pop())()}
    }
 })
$(document).ajaxStart(function() { Pace.restart(); });

$("#aboutedit").click(function(){
	 $("#aboutLi1").hide(500);
	 $("#aboutLi2").show(700);
	 $("#contactLi2").hide(500);
	 $("#contactLi1").show(700);
	 $("#basicLi2").hide(500);
	 $("#basicLi1").show(700);
 })
$("#contactedit").click(function(){
	 $("#contactLi1").hide(500);
	 $("#contactLi2").show(700);
	 $("#aboutLi2").hide(500);
	 $("#aboutLi1").show(700);
	 $("#basicLi2").hide(500);
	 $("#basicLi1").show(700);
 })
$("#basicedit").click(function(){
	 $("#aboutLi1").show(700);
	 $("#contactLi1").show(700);
	 $("#basicLi1").hide(500);
	 $("#aboutLi2").hide(500);
	 $("#contactLi2").hide(500);
	 $("#basicLi2").show(700);
	 
 })
$("#back1").click(function(){
	 $("#aboutLi2").hide(500);
	 $("#contactLi2").hide(500);
	 $("#aboutLi1").show(700);
	 $("#contactLi1").show(700);
	 $("#basicLi2").hide(500);
	 $("#basicLi1").show(700);
 })
$("#back2").click(function(){
	 $("#aboutLi2").hide(500);
	 $("#contactLi2").hide(500);
	 $("#aboutLi1").show(700);
	 $("#contactLi1").show(700);
	 $("#basicLi2").hide(500);
	 $("#basicLi1").show(700);
 })
$("#back3").click(function(){
	 $("#aboutLi2").hide(500);
	 $("#contactLi2").hide(500);
	 $("#basicLi2").hide(500);
	 $("#aboutLi1").show(700);
	 $("#contactLi1").show(700);
	 $("#basicLi1").show(700);
 })
function emptyElement(x){
	_(x).innerHTML = "";
}
function editAbout(){
	var sch = _("school").value;
	var loc = _("location").value;
	var ht = _("hometown").value;
	var wrk = _("work").value;
	var rm = _("role").value;
	var status = _("status");
	if(sch == "" || loc == "" || ht == "" || wrk == "" || rm == ""){
		status.innerHTML = '<h7 style="color:rgb(173, 6, 6);">Error mtc96: *sigh*.</h7>';
	} else {
		_("editAboutBtn").style.display = "block";
		status.innerHTML = '<h7 style="color:rgb(10, 85, 128);"> please wait ...</h7>';
		var ajax = ajaxObj("POST", "index.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            if(ajax.responseText != "about_success"){
					status.innerHTML = ajax.responseText;
					_("editAboutBtn").style.display = "block";
				} else {
					status.innerHTML = '<h7 style="color:rgb(10, 85, 128);"> please wait...</h7>';
					window.location = "../settings/?u=<?php echo $log_username ?>";
				}
	        }
        }
        ajax.send("sch="+sch+"&loc="+loc+"&ht="+ht+"&wrk="+wrk+"&rm="+rm);
	}
}
function contactAbout(){
	var num = _("number").value;
	var alt_e = _("email").value;
	var status = _("status2");
	if(num == "" || alt_e == ""){
		status.innerHTML = '<h7 style="color:rgb(173, 6, 6);">Error mtc145: *hmmmm..*.</h7>';
	} else {
		_("editContactBtn").style.display = "block";
		status.innerHTML = '<h7 style="color:rgb(10, 85, 128);"> please wait ...</h7>';
		var ajax = ajaxObj("POST", "index.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            if(ajax.responseText != "contact_success"){
					status.innerHTML = ajax.responseText;
					_("editContactBtn").style.display = "block";
				} else {
					status.innerHTML = '<h7 style="color:rgb(10, 85, 128);"> please wait...</h7>';
					window.location = "../settings/?u=<?php echo $log_username ?>";
				}
	        }
        }
        ajax.send("num="+num+"&alt_e="+alt_e);
	}
}
function editInfo(){
	var nik = _("nickname").value;
	var rela = _("relationship").value;
	var crh = _("crush").value;
	var tv = _("tv").value;
	var book = _("book").value;
	var status = _("status3");
	if(nik == "" || rela == "" || crh == "" || tv == "" || book == ""){
		status.innerHTML = '<h7 style="color:rgb(173, 6, 6);">Error mtc85: *chaley*.</h7>';
	} else {
		_("editInfoBtn").style.display = "block";
		status.innerHTML = '<h7 style="color:rgb(10, 85, 128);"> please wait...</h7>';
		var ajax = ajaxObj("POST", "index.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            if(ajax.responseText != "info_success"){
					status.innerHTML = ajax.responseText;
					_("editInfoBtn").style.display = "block";
				} else {
					status.innerHTML = '<h7 style="color:rgb(10, 85, 128);"> please wait...</h7>';
					window.location = "../settings/?u=<?php echo $log_username ?>";
				}
	        }
        }
        ajax.send("nik="+nik+"&rela="+rela+"&crh="+crh+"&tv="+tv+"&book="+book);
	}
}
function changeTheme(){
	var t = document.querySelector('input[name = "theme"]:checked').value;
	status.innerHTML = '<h7 style="color:rgb(10, 85, 128);"> please wait ...</h7>';
	var ajax = ajaxObj("POST", "index.php");
    ajax.onreadystatechange = function() {
	   if(ajaxReturn(ajax) == true) {
	       if(ajax.responseText != "theme_changed"){
				status.innerHTML = ajax.responseText;
				} else {
					var pagetheme = document.getElementById('pagetheme');
					if(t == "default"){
						pagetheme.setAttribute('href', '../assets/css/luci.1.0.1.css');
						mode = "default";
					} else {
						pagetheme.setAttribute('href', '../assets/theme/luci.dark.css');
						mode = "dark";
					}
				}
	        }
        }
        ajax.send("t="+t);
}
</script>
</body>
</html>