<?php 
include_once("php_extensions/check_login_status.php");
if($user_ok == true){
	header("location: user/?u=".$_SESSION["username"]);
    exit();
}
?><?php
if(isset($_POST["e"])){
	include_once("php_extensions/db_conx.php");
	$e = mysqli_real_escape_string($db_conx, $_POST['e']);
	$p = md5($_POST['p']);
    $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));

	if($e == "" || $p == ""){
		echo "login_failed";
        exit();
	} else {
		$sql = "SELECT id, username, password FROM users WHERE email='$e' OR username='$e'";
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
			/* setcookie("id", $db_id, strtotime( '+30 days' ), "/", "", "", TRUE);
			setcookie("user", $db_username, strtotime( '+30 days' ), "/", "", "", TRUE);
    		setcookie("pass", $db_pass_str, strtotime( '+30 days' ), "/", "", "", TRUE);  */

			$sql = "UPDATE users SET ip='$ip', lastlogin=now(), status='online' WHERE username='$db_username'";
            $query = mysqli_query($db_conx, $sql);
			echo $db_username;
		    exit();
		}
	}
	exit();
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
	
	<link href="assets/img/bleak_icon.png" rel="icon" />
    <title>Bleak</title>
	
    <link href="assets/css/luci.1.0.1.css" rel="stylesheet">  
    <link href="assets/css/luci.1.0.2.css" rel="stylesheet">
	<link href="assets/css/pace.min.css" rel="stylesheet">
	<link href="assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  </head>
<body>
<nav class="ck pc os app-navbar">
	<div class="by">
		<div class="or">	
			<a class="e" href="index.php">
				<img src="assets/img/bleak_logo.png" alt="bleakLOGO">
			</a>
		</div>
	</div>
</nav>	
<!--------------------- LOGIN FORM ------------------------>
<!--<div class="by amt">
  <div class="gc"> -->
<div class="by amt">
	<div class="gb loginbox">
	  <div class="uv">
		<form role="form" class="alq dj j" id="login" onSubmit="return false;">
		  <h3 class="l amb">Sign In</h3>
		  <div class="et">
			<input type="text" class="form-control inputBox" id="email-in" onfocus="emptyElement('status')" onkeyup="restrict('email-in')" placeholder="ID" title="Email or Username" autofocus>
		  </div>
		  <div class="et alu">
			<input type="password" class="form-control inputBox" id="password-in" onfocus="emptyElement('status')" placeholder="Password">
		  </div>
		  <span id="status"></span>
		  <div class="amb" id="loginbtn" style="margin-top: 14px;">
			<button class="cg fp" onclick="login()">Log In</button> &nbsp;Or&nbsp;
			<a href="signup.php" class="cg fm">Sign up</a>
		  </div>
		  <footer class="apd">
			<a href="reset.php" class="dp">Forgot password?</a>
		  </footer>
		</form>
	  </div>
  </div>
</div>
<?php include_once("footer.php"); ?>

<script src="assets/js/jquery-1.12.3.min.js"></script>
<script src="assets/js/main.js"></script>
<script src="assets/js/ajax.js"></script>
<script src="assets/js/fadeEffects.js"></script>
<script src="assets/js/pace.min.js"></script>  
<script type="text/javascript">
 // execute/clear BS loaders for docs
$(function(){
  if (window.BS&&window.BS.loader&&window.BS.loader.length) {
       while(BS.loader.length){(BS.loader.pop())()}
    }
})
$(document).ready(function() { Pace.restart(); });

function restrict(elem){
	var tf = _(elem);
	var rx = new RegExp;
	if(elem == "email"){
		rx = /[' "]/gi;
	} else if(elem == "username"){
		rx = /[^a-z0-9]/gi;
	}
	tf.value = tf.value.replace(rx, "");
}
function emptyElement(x){
	_(x).innerHTML = "";
}
function login(){
	var e = _("email-in").value;
	var p = _("password-in").value;
	if(e == "" || p == ""){
		_("status").innerHTML = '<h7 style="color:rgb(192, 41, 41);" > Fill out all of the form data</h7>';
	} else {
		_("loginbtn").style.display = "block";
		_("status").innerHTML = '<h7 style="color:rgb(10, 85, 128);">please wait ...</h7>';
		var ajax = ajaxObj("POST", "login.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            if(ajax.responseText == "login_failed"){
					_("status").innerHTML = '<h7 style="color:rgb(192, 41, 41);">Login unsuccessful, please try again.</h7>';
					_("loginbtn").style.display = "block";
				} else {
					window.location = "user/?u="+ajax.responseText;
				}
	        }
        }
        ajax.send("e="+e+"&p="+p);
	}
}
</script>

</body>
</html>