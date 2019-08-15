<?php
include_once("../php_extensions/check_login_status.php");
if($user_ok == false){
	header("location: ../index.php");
    exit();
}
?><?php
/* if(isset($_POST["p"])){
	include_once("php_extensions/db_conx.php");
	$u = mysqli_real_escape_string($db_conx, $_POST['u']);
	$p = md5($_POST['p']);
	$ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
	
	if($e == "" || $p == ""){
		echo "login_failed";
        exit();
	} else {
		$sql = "SELECT id, username, password FROM users WHERE username='$u'";
        $query = mysqli_query($db_conx, $sql);
        $row = mysqli_fetch_row($query);
		$db_id = $row[0];
		$db_username = $row[1];
        $db_pass_str = $row[2];
		if($p != $db_pass_str){
			echo "login_failed";
            exit();
		} else {
			$_SESSION['userid'] = $db_id;
			$_SESSION['username'] = $db_username;
			$_SESSION['password'] = $db_pass_str;
			
			$sql = "UPDATE users SET ip='$ip', lastlogin=now() WHERE username='$db_username'";
            $query = mysqli_query($db_conx, $sql);
			echo $db_username;
		    exit();
		}
	}
	exit();
} */
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
$profile_logo = '<img src="../_Users/'.$log_username.'/'.$avatar.'" alt="'.$log_username.'">';
if($avatar == NULL){
	$profile_logo = '<img src="../assets/img/avatardefault.png" alt="'.$log_username.'">';
}
if($avatar == NULL && $gender == "f"){
	$sex = "Female";
	$profile_logo = '<img src="../assets/img/avatardefaultF1.png" alt="'.$log_username.'">';
}else if($avatar == NULL && $gender == "m"){
	$profile_logo = '<img src="../assets/img/avatardefaultM1.png" alt="'.$log_username.'">';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
	
	<link href="../assets/img/bleak_icon.png" rel="icon" />
    <title>Bleak</title>
	
    <link href="../assets/css/luci.1.0.1.css" rel="stylesheet">  
    <link href="../assets/css/luci.1.0.2.css" rel="stylesheet">
	<link href="../assets/css/pace.min.css" rel="stylesheet">
	<link href="../assets/css/lockscreen.css" rel="stylesheet">
	<link href="../assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>
<body class="ang" class="hold-transition lockscreen">
	<div class="anq" id="app-growl"></div>	

<?php include_once("../messages/messages.php"); ?>
<!-------- MAIN BODY ------------>
<div class="lockscreen-wrapper">
	<div class="lockscreen-logo">
		<a href="#" onclick="window.location = '../lockscreen/?u=<?php echo $log_username; ?>';"><b class="dp">B</b>leak</a>
	</div>
	<div class="lockscreen-name"><?php echo $log_username ?></div>
	<div class="lockscreen-item">
		<div class="lockscreen-image hand" onclick="window.location = '../user/?u=<?php echo $log_username; ?>';">
			<?php echo $profile_logo ?>
		</div>
		<form class="lockscreen-credentials" role="form" id="logon" onSubmit="return false;">
			<input type="password" class="form-control inputBox" id="password-in" onfocus="emptyElement('status')" placeholder="Password" disabled>
			  <button style="display:none;" class="cg fp" onclick="logon()">Log In</button>
		</form>
		<span id="status"></span>
	</div>
	<div class="help-block text-center">
		Enter your password to retrieve your session
	</div>
	<div class="text-center">
		<a class="hand" onclick="window.location = '../user/logout.php';">Or sign in as a different user</a>
	</div>
	<div class="lockscreen-footer text-center">
		<b class="dp">Powered by R A V E N </b>
	</div>
</div>
<?php include_once("../footer.php");?>
<!-------- / END OF MAIN BODY ------------>

<script src="../assets/js/jquery-1.12.3.min.js"></script>
<script src="../assets/js/jquery.avgrund.js"></script>
<script src="../assets/js/luci.1.0.1.js"></script>
<script src="../assets/js/luci.1.0.2.js"></script>
<script src="../assets/js/main.js"></script>
<script src="../assets/js/ajax.js"></script>
<script src="../assets/js/pace.min.js"></script>
<script>
$(function(){
   if (window.BS&&window.BS.loader&&window.BS.loader.length) {
	  while(BS.loader.length){(BS.loader.pop())()}
    }
})
$(document).ready(function() { Pace.restart(); });
/* ---------------------------------------------------------------- */
function emptyElement(x){
	_(x).innerHTML = "";
}
function logon(){
	/* var u = "<?php echo $log_username ?>";
	var p = _("password-in").value;
	if(u == "" || p == ""){
		_("status").innerHTML = '<h7 style="color:rgb(192, 41, 41);margin-left: 70px;" > Please enter your password</h7>';
	} else {
		_("status").innerHTML = '<h7 style="color:rgb(10, 85, 128);margin-left: 70px;">please wait ...</h7>';
		var ajax = ajaxObj("POST", "index.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            if(ajax.responseText == "login_failed"){
					_("status").innerHTML = '<h7 style="color:rgb(192, 41, 41);margin-left: 70px;">Login unsuccessful, please try again.</h7>';
				} else {
					window.location = "../user/?u="+ajax.responseText;
				}
	        }
        }
        ajax.send("u="+u+"&p="+p);
	} */
}
</script>
</body>
</html>