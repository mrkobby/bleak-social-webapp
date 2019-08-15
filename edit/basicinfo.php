<?php 
include_once("../php_extensions/check_login_status.php");
if($user_ok == false){
	header("location: ../index.php");
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
$sql = "SELECT * FROM userbasic WHERE username='$u'";
$userbasic_query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($userbasic_query);

while ($row = mysqli_fetch_array($userbasic_query, MYSQLI_ASSOC)) {
	$nickname = $row["nickname"];
	$relationship = $row["relationship"];
	$crush = $row["crush"];
	$tv = $row["tv"];
	$book = $row["book"];
	
	if($nickname == "NULL" || $nickname == NULL){
		$nickname =  '';
	}if($relationship == "NULL" || $relationship == NULL){
		$relationship =  '';
	}if($crush == "NULL" || $crush == NULL){
		$crush =  '';
	}if($tv == "NULL" || $tv == NULL){
		$tv =  '';
	}if($book == "NULL" || $book == NULL){
		$book =  '';
	}
}
?><?php
$edit_profile = 'style="display:;margin-top: 10px;"';
if($u != $log_username){
	$dp = "SELECT avatar FROM users WHERE username = '$log_username'";
	$query = mysqli_query($db_conx, $dp);
	while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$result = $row["avatar"];
		$profile_logo = '<img class="cu mylogo" src="../_Users/'.$log_username.'/'.$result.'" alt="'.$log_username.'">';
		if($result == NULL){
			$profile_logo = '<img class="cu mylogo" src="../assets/img/avatardefault.png" alt="'.$log_username.'">';
		}
		if($result == NULL && $gender == "f"){
			$profile_logo = '<img class="cu mylogo" src="../assets/img/avatardefaultF1.png" alt="'.$log_username.'">';
		}else if($result == NULL && $gender == "m"){
			$profile_logo = '<img class="cu mylogo" src="../assets/img/avatardefaultM1.png" alt="'.$log_username.'">';
		}
	}
	$edit_profile = 'style="display:none;margin-top: 10px;"';
	$editBtn = 'display:none;';
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
		  <div class="qv rc EditBx">
			<div class="qw">
			  <h5 class="ald"> Edit Basic Info</h5>
			  <form role="form" method="post" id="editInfo" onSubmit="return false;">
				  <hr class="dp">
				  <div class="qn">
					<div class="qn">
						<h5 class="dp" style="padding: 5px;">Nickname &nbsp;<span class="fa fa-user"></h5>	
						<input type="text" name="nickname" placeholder="What's your nickname?" value="<?php echo $nickname; ?>" id="nickname" class="form-control inputBox" onfocus="emptyElement('status')" autofocus>
					</div>
				  </div>
				  <hr class="dp">
				  <div class="qn">
					<div class="qn">
						<h5 class="dp" style="padding: 5px;">Relationship &nbsp;<span class="fa fa-flag-o"></h5>	
						<input type="text" name="relationship" placeholder="Are you in a relationship? Yes / No" value="<?php echo $relationship; ?>" id="relationship" class="form-control inputBox" onfocus="emptyElement('status')" autofocus>
					</div>
				  </div>
				  <hr class="dp">
				  <div class="qn">
					<div class="qn">
						<h5 class="dp" style="padding: 5px;">Crushing on &nbsp;<span class="fa fa-heart"></h5>
						<input type="text" name="crush" placeholder="Who you crushing on?" value="<?php echo $crush; ?>" id="crush" class="form-control inputBox" onfocus="emptyElement('status')" autofocus>
					</div>
				  </div>
				  <hr class="dp">
				  <div class="qn">
					<div class="qn">
						<h5 class="dp" style="padding: 5px;">Favorite TV series &nbsp;<span class="fa fa-video-camera"></h5>
						<input type="text" name="tv" placeholder="What's your favourite TV series?" value="<?php echo $tv; ?>" id="tv" class="form-control inputBox" onfocus="emptyElement('status')" autofocus>
					</div>
				  </div>
				  <hr class="dp">
				  <div class="qn">
					<div class="qn">
						<h5 class="dp" style="padding: 5px;">Best book/novel read &nbsp;<span class="fa fa-book"></h5>
						<input type="text" name="book" placeholder="Any favourite books or novels?" value="<?php echo $book; ?>" id="book" class="form-control inputBox" onfocus="emptyElement('status')" autofocus>
					</div>
				  </div>
				  <hr class="dp">
				  <div id="editInfoBtn">
					<button class="cg fm" style="border:1px solid grey;" onclick="window.location = '../profile/about.php?u=<?php echo $u; ?>';">
						<span class="fa fa-chevron-left"></span> &nbsp;Back
					 </button>
				    <button class="cg fm " style="border:1px solid grey;" onclick="editInfo()">Save changes &nbsp;<span class="fa fa-save"></span></button>
					<span id="status"></span>
				 </div>
				  <hr class="dp">
			  </form>
			</div>
          </div> 
        </li>
		<!---------------------------------------------------------->

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
    $('.ajax').click(function(){
        $.ajax({url: '#', success: function(result){
            $('.ajax-content').html('<hr>Ajax Request Completed !');
    }});
});
/* $(document).ready(function(){
	 window.scroll(0,350);
 }) */
function emptyElement(x){
	_(x).innerHTML = "";
}
function editInfo(){
	var nik = _("nickname").value;
	var rela = _("relationship").value;
	var crh = _("crush").value;
	var tv = _("tv").value;
	var book = _("book").value;
	var status = _("status");
	if(nik == "" || rela == "" || crh == "" || tv == "" || book == ""){
		status.innerHTML = '<h7 style="color:rgb(173, 6, 6);">Error mtc85: What are you trying to do.</h7>';
	} else {
		_("editInfoBtn").style.display = "block";
		status.innerHTML = '<h7 style="color:rgb(10, 85, 128);"> please wait...</h7>';
		var ajax = ajaxObj("POST", "basicinfo.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            if(ajax.responseText != "info_success"){
					status.innerHTML = ajax.responseText;
					_("editInfoBtn").style.display = "block";
				} else {
					status.innerHTML = '<h7 style="color:rgb(10, 85, 128);"> please wait...</h7>';
					window.location = "../profile/about.php?u=<?php echo $log_username ?>";
				}
	        }
        }
        ajax.send("nik="+nik+"&rela="+rela+"&crh="+crh+"&tv="+tv+"&book="+book);
	}
}
</script>
</body>
</html>
