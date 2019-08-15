<?php
include_once("php_extensions/check_login_status.php");
if($user_ok == true){
	header("location: user/?u=".$_SESSION["username"]);
    exit();
}
?>

<?php
if(isset($_POST["e"])){
	$e = mysqli_real_escape_string($db_conx, $_POST['e']);
	$sql = "SELECT id, username FROM users WHERE email='$e'";
	$query = mysqli_query($db_conx, $sql);
	$numrows = mysqli_num_rows($query);
	if($numrows > 0){
		while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
			$id = $row["id"];
			$u = $row["username"];
		}
		$emailcut = substr($e, 0, 4);
		$randNum = rand(10000,99999);
		$tempPass = "$emailcut$randNum";
		$hashTempPass = md5($tempPass);
		$sql = "UPDATE useroptions SET temp_pass='$hashTempPass' WHERE username='$u'";
	    $query = mysqli_query($db_conx, $sql);
		$to = "$e";
		$from = "auto_responder@gmail.com";
		$headers ="From: $from\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1 \n";
		$subject ="Bleak Temporary Password";
		$msg = '<h2>Hello '.$u.'</h2><p>This is an automated message from Bleak. If you did not recently initiate the Forgot/Reset Password process, please disregard this email.</p><p>You indicated that you forgot your login password. We can generate a temporary password for you to log in with, then once logged in you can change your password to anything you like.</p><p>After you click the link below your password to login will be:<br /><b>'.$tempPass.'</b></p><p><a href="reset.php?u='.$u.'&p='.$hashTempPass.'">Click here now to apply the temporary password shown below to your account</a></p><p>If you do not click the link in this email, no changes will be made to your account. In order to set your login password to the temporary password you must click the link above.</p>';
		if(mail($to,$subject,$msg,$headers)) {
			echo "success";
			exit();
		} else {
			echo "email_send_failed";
			exit();
		}
    } else {
        echo "no_exist";
    }
    exit();
}
?>

<?php
if(isset($_GET['u']) && isset($_GET['p'])){
	$u = preg_replace('#[^a-z0-9-]#i', '', $_GET['u']);
	$temppasshash = preg_replace('#[^a-z0-9]#i', '', $_GET['p']);
	if(strlen($temppasshash) < 10){
		exit();
	}
	$sql = "SELECT id FROM useroptions WHERE username='$u' AND temp_pass='$temppasshash'";
	$query = mysqli_query($db_conx, $sql);
	$numrows = mysqli_num_rows($query);
	if($numrows == 0){
		header("location: ###.php?msg=There is no match for that username with that temporary password in the system. We cannot proceed.");
    	exit();
	} else {
		$row = mysqli_fetch_row($query);
		$id = $row[0];
		$sql = "UPDATE users SET password='$temppasshash' WHERE id='$id' AND username='$u'";
	    $query = mysqli_query($db_conx, $sql);
		$sql = "UPDATE useroptions SET temp_pass='' WHERE username='$u'";
	    $query = mysqli_query($db_conx, $sql);
	    header("location: index.php");
        exit();
    }
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
<!--------------------- RESET FORM ------------------------>
<div class="gb resetbox">
  <div class="uv">
    <form role="form" class="alq dj j" id="forgotpassform" onSubmit="return false;">
      <h3 class="amb">Reset password</h3>
	  <span class="dp">Step 1: Enter your email address</span><br /><br />
      <div class="et">
        <input type="email" class="form-control inputBox" id="email" onfocus="emptyElement('status')" placeholder="Email" autofocus>
      </div>
      <div class="amb" id="forgotpassbtn">
        <button class="cg fm pad-45" onclick="forgotpass()">Next <span class="fa fa-chevron-right"></span></button>
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

function emptyElement(x){
	_(x).innerHTML = "";
}
function forgotpass(){
	var e = _("email").value;
	if(e == ""){
		_("status").innerHTML = "<h7 style='color:rgb(192, 41, 41);'>Email address please</h7>";
	} else {
		_("forgotpassbtn").style.display = "block";
		_("status").innerHTML = 'please wait ...';
		var ajax = ajaxObj("POST", "reset.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
				var response = ajax.responseText;
				if(response == "success"){
					_("forgotpassform").innerHTML = '<h6>Step 2. Check your email inbox in a few minutes</h6><p>You can close this window or tab if you like.</p>';
				} else if (response == "no_exist"){
					_("status").innerHTML = "Sorry that email address is not in our system";
				} else if(response == "email_send_failed"){
					_("status").innerHTML = "Mail function failed to execute";
				} else {
					_("status").innerHTML = "<b>An unknown error occurred!</b>";
				}
	        }
        }
        ajax.send("e="+e);
	}
}
</script>
</body>
</html>