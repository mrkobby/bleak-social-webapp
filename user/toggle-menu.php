<ul class="nav navbar-nav st su sv">
	<li>
		<a class="hand" onclick="window.location = '../user/?u=<?php echo $log_username; ?>';">Home
			<span class="fa fa-circle fa-1x circle">					
		</a>
	</li>
	<li><a class="hand" onclick="window.location = '../profile/?u=<?php echo $log_username; ?>';">Profile</a></li>
	<li>
		<a class="hand" onclick="window.location = '../notifications/?u=<?php echo $log_username; ?>';">Notifications
			<?php echo $notedot ?>
		</a>
	</li>
	<li>
		<a data-toggle="modal" href="#msgModal">Messages
			<span class="fa fa-circle fa-1x circle">
		</a>
	</li>
	<li><a class="hand" onclick="window.location = '../explore/?u=<?php echo $log_username; ?>';">Explore</a></li>
	<li><a class="hand" onclick="window.location = '../settings/?u=<?php echo $log_username; ?>';">Settings</a></li>
	<li><a class="hand" onclick="window.location = '../lockscreen/?u=<?php echo $log_username; ?>';">Lockscreen</a></li>
	<li><a href="../user/logout.php">Logout</a></li>
</ul>
