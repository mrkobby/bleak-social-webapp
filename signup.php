<?php
if(isset($_POST["usernamecheck"])){
	include_once("php_extensions/db_conx.php");
	$username = preg_replace('#[^a-z0-9]#i', '', $_POST['usernamecheck']);
	$sql = "SELECT id FROM users WHERE username='$username'";
    $query = mysqli_query($db_conx, $sql); 
    $uname_check = mysqli_num_rows($query);
    if (strlen($username) < 3 || strlen($username) > 16) {
	    echo '<h7 style="color:rgb(192, 41, 41);">&deg; 3 - 16 characters please </h7>';
	    exit();
    }
	if (is_numeric($username[0])) {
	    echo '<h7 style="color:rgb(192, 41, 41);">&deg; Username must begin with a letter</h7>';
	    exit();
    }
    if ($uname_check < 1) {
	    echo '<b>' . $username . '</b><h7 style="color:#009900;"> is <b>Cool!</b></h7>';
	    exit();
    } else {
	    echo '<b>' . $username . '</b><h7 style="color:rgb(192, 41, 41);"> already exists</h7>';
	    exit();
    }
}

if(isset($_POST["u"])){
	
	include_once("php_extensions/db_conx.php");
	
	$_SESSION['u'] = $u = preg_replace('#[^a-z0-9]#i', '', $_POST['u']);
	$_SESSION['e'] = $e = mysqli_real_escape_string($db_conx, $_POST['e']);
	$_SESSION['p'] = $p = $_POST['p'];
	$_SESSION['g'] = $g = preg_replace('#[^a-z]#', '', $_POST['g']);
	$_SESSION['c'] = $c = preg_replace('#[^a-z ]#i', '', $_POST['c']);
	
    $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
	
	$sql = "SELECT id FROM users WHERE username='$u'";
    $query = mysqli_query($db_conx, $sql); 
	$u_check = mysqli_num_rows($query);
	
	// -------------------------------------------
	$sql = "SELECT id FROM users WHERE email='$e'";
    $query = mysqli_query($db_conx, $sql); 
	$e_check = mysqli_num_rows($query);

	if($u == "" || $e == "" || $p == ""){
		echo '<h7 style="color:rgb(192, 41, 41);">&deg;The form submission is missing values.</h7> ';
        exit();
	} else if ($u_check > 0){ 
        echo '<h7 style="color:rgb(192, 41, 41);">&deg;The username already exists.</h7>';
        exit();
	} else if ($e_check > 0){ 
        echo '<h7 style="color:rgb(192, 41, 41);">&deg;That email address is already in use.</h7> ';
        exit();
	} else if (strlen($u) < 3 || strlen($u) > 10) {
        echo '<h7 style="color:rgb(192, 41, 41);">&deg;Username must be between 3 and 10 characters. </h7>';
        exit(); 
    } else if (is_numeric($u[0])) {
        echo '<h7 style="color:rgb(192, 41, 41);">&deg;Username cannot begin with a number.</h7>';
        exit();
    }/* else if(!filter_var($e_check,
		FILTER_VALIDATE_EMAIL)){
		echo '<h7 style="color:rgb(192, 41, 41);">&deg;Invalid email format.</h7>';
		exit();
	}*/ else {
	
		$p_hash = md5($p);		
		$sql = "INSERT INTO users (username, email, password, gender, country, ip, userlevel, signup)       
		        VALUES('$u','$e','$p_hash','$g','$c','$ip','1',now())";
		$query = mysqli_query($db_conx, $sql); 
		$uid = mysqli_insert_id($db_conx);
		
		$sql = "INSERT INTO useroptions (id, username, userstatus, theme) VALUES ('$uid','$u','Hi there! I\'m a new bleaker.','default')";
		$query = mysqli_query($db_conx, $sql);
		
		$sql = "INSERT INTO userinfo (id, username, education, location, hometown, work, roleModel, mobile, email) VALUES ('$uid','$u','NULL','NULL','NULL','NULL','NULL','NULL','NULL')";
		$query = mysqli_query($db_conx, $sql);
		
		$sql = "INSERT INTO userbasic (id, username, nickname, relationship, crush, tv, book) VALUES ('$uid','$u','NULL','NULL','NULL','NULL','NULL')";
		$query = mysqli_query($db_conx, $sql);
		
		if (!file_exists("_Users/$u")) {
			mkdir("_Users/$u", 0755);
		}
		echo "signup_success";
		exit();
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
	<meta http-equiv="refresh" content="60">
	
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
<!--------------------- SIGNUP FORM ------------------------>
<div class="gb signupbox" id="form1" style="display:;">
  <div class="uv">
    <form role="form" class="alq dj j" method="post" action="processing.php" id="signupform" onSubmit="return false;">
      <h3 class="l amb">Sign Up</h3>
      <div class="et">
        <input type="text" name="username" id="username" class="form-control inputBox" onfocus="emptyElement('unamestatus')" onblur="checkusername()" onkeyup="restrict('username')" maxlength="16" placeholder="Username" autofocus>
		<span id="unamestatus"></span>
      </div>
	  <div class="et">
        <input type="email" name="email" id="email" class="form-control inputBox" onfocus="emptyElement('status')" onkeyup="restrict('email')" placeholder="Email">
      </div>
      <div class="et">
        <input type="password" name="pass1" id="pass1" class="form-control inputBox" onfocus="emptyElement('status')" maxlength="40" placeholder="Password">
      </div>
	  <div class="et">
        <input type="password" name="pass2" id="pass2" class="form-control inputBox" onfocus="emptyElement('status')" maxlength="40" placeholder="Confirm Password">
      </div>
	  <div class="form-group col-default">
         <label>Gender:</label>
		 <label class="radio-inline margin-l-20">
			<input type="radio" name="gender" id="male" value="m" onfocus="emptyElement('status')" Checked> Male &nbsp;
			<input type="radio" name="gender" id="female" value="f"onfocus="emptyElement('status')"> Female
		 </label>
	  </div>
	  <div class="form-group">
		 <select class="form-control inputBox" name="country" id="country" onfocus="emptyElement('status')">
			<?php include_once("crn_tempo/country_list.php"); ?>
		</select>
	  </div> 
	  <hr>
      <div class="amb" id="signupbtn">
        <button class="cg fm pad-45" onclick="signup()">Next <span class="fa fa-chevron-right"></span></button>
		<br><br><span id="status"></span>
	  </div>
    </form>
  </div>
</div>
<?php include_once("footer.php");?>

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
function checkusername(){
	var u = _("username").value;
	if(u != ""){
		_("unamestatus").innerHTML = ' checking ...';
		var ajax = ajaxObj("POST", "signup.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            _("unamestatus").innerHTML = ajax.responseText;
	        }
        }
        ajax.send("usernamecheck="+u);
	}
}
function signup(){
	var u = _("username").value;
	var e = _("email").value;
	var p1 = _("pass1").value;
	var p2 = _("pass2").value;
	var g = document.querySelector('input[name = "gender"]:checked').value;
	var c = _("country").value;
	var status = _("status");
	if(u == "" || e == "" || p1 == "" || p2 == "" || g == "" || c == ""){
		status.innerHTML = '<h7 style="color:rgb(192, 41, 41);">Fill out all of the form data</h7>';
	} else if(p1 != p2){
		status.innerHTML = '<h7 style="color:rgb(192, 41, 41);">Your passwords do not match.</h7>';
	} else {
		_("signupbtn").style.display = "none";
		status.innerHTML = '<h7 style="color:rgb(10, 85, 128);"> please wait ...</h7>';
		var ajax = ajaxObj("POST", "signup.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            if(ajax.responseText != "signup_success"){
					status.innerHTML = ajax.responseText;
					_("signupbtn").style.display = "block";
				} else {
					window.scrollTo(0,0);
					_("signupform").innerHTML = "<h7 style='align:center;color:green;'>Hi there! <b>"+u+"</b>, your bleak account " + 
												"has been created successfully!. Login to your account and lets finish up a few things buddy!.</h7><br><br>" +
												"<a class='cg fm pad-45' href='index.php'>Get Started <span class='fa fa-chevron-right'></span></a> ";
				}
	        }
        }
        ajax.send("u="+u+"&e="+e+"&p="+p1+"&c="+c+"&g="+g);
	}
}
</script>
</body>
</html>