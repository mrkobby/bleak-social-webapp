<?php
$stat = "";
$sql = "SELECT status FROM users WHERE username='$log_username'";
$user_query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($user_query);
while ($row = mysqli_fetch_array($user_query, MYSQLI_ASSOC)) {
	$stat = $row["status"];
}
?>
<li class="dropdown user user-menu">
	<button class="dropdown-toggle cg fm ox anl" data-toggle="dropdown">
		<?php echo $profile_logo ?>
	</button>
	<ul class="dropdown-menu">
		<li class="user-header">
			<?php echo $profileLogo ?>
			<p><?php echo $log_username ?> . <small style="font-size:10px;color:white;"><?php echo $stat; ?></small>
			<br /><small style="font-size:12px;">Member since <?php echo $member ?></small>
		</li>
		<li class="user-footer">
			<div class="row">
				<div class="col-xs-4 text-center">
					<button type="button" class="cg fm" title="Lock screen" onclick="window.location = '../lockscreen/?u=<?php echo $log_username; ?>';">
						&nbsp;&nbsp;<span class="fa fa-lock" ></span>&nbsp;&nbsp;
					</button>
				</div>
				<div class="col-xs-4 text-center">
					<button type="button" class="cg fm" title="Settings" onclick="window.location = '../settings/?u=<?php echo $log_username; ?>';">
						&nbsp;&nbsp;<span class="fa fa-gears" ></span>&nbsp;&nbsp;
					</button>
				</div>
				<div class="col-xs-4 text-center">
					<button type="button" class="cg fm" title="Logout" onclick="window.location = '../user/logout.php';">
						&nbsp;&nbsp;<span class="fa fa-sign-out" ></span>&nbsp;&nbsp;
					</button>
				</div>
			</div>
		</li>
	</ul>
</li>