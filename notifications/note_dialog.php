<?php
$highlight1 = '';
$sql = "SELECT COUNT(id) FROM friends WHERE user2='$log_username' AND accepted = '0'";
	$query = mysqli_query($db_conx,$sql);
	$numrows = mysqli_num_rows($query);
	$query_count = mysqli_fetch_row($query);
	$friend_count = $query_count[0];
	if($numrows > 0 && $friend_count > 0) {
		$friend_requests_box = '<div class="qv rc"><div class="qw"><h5 class="ald"><b>Friend Requests</b></h5><hr><ul class="qo anx">'.$friend_requests.'</ul></div></div>';
		$bell = '<span class="fa fa-bell fa-fw"></span><div '.$bubble.'><span class="fa fa-circle fa-lg notecheck-ls"><span class="num-ls">'.$friend_count.'</span></div>';
		$tog_note = '<div '.$bubble.'><span class="fa fa-circle fa-1-8x notecheck"></span><span class="num">'.$friend_count.'</span></div>';
		$notedot = '<span '.$bubble.' class="fa fa-circle fa-1x circle">';
		$highlight1 = 'class="noteLi"';
	}
	if($friend_count < 1){
		$friend_count = '--';
	}
?><?php 
$highlight2 = '';
$sql = "SELECT COUNT(id) FROM notifications WHERE username LIKE BINARY '$log_username' AND did_read='0'";
	$query = mysqli_query($db_conx, $sql);
	$query_count = mysqli_fetch_row($query);
	$note_count2 = $query_count[0];
	$postcount = $note_count2;
if($postcount > 0) {
	$bell = '<span class="fa fa-bell fa-fw"></span><div '.$bubble.'><span class="fa fa-circle fa-lg notecheck-ls"><span class="num-ls">'.$postcount.'</span></div>';
	$tog_note = '<div '.$bubble.'><span class="fa fa-circle fa-1-8x notecheck"></span><span class="num">'.$postcount.'</span></div>';
	$notedot = '<span '.$bubble.' class="fa fa-circle fa-1x circle">';
	$highlight2 = 'class="noteLi"';
}
if($postcount < 1){
	$postcount = '--';
}
/* $sql = "SELECT notescheck FROM users WHERE username='$log_username'";
$query = mysqli_query($db_conx, $sql);
$row = mysqli_fetch_row($query);
$notecheck = $row[0];
$sql = "SELECT COUNT(id) FROM notifications WHERE date_time < '$notecheck' AND username='$log_username' LIMIT 1";
$query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($query);
$count_ = mysqli_fetch_row($query);
$count_n = $count_[0];
if($numrows === true && $count_n === true){
	$highlight2 = 'style="background-color: #fff;"';
}else{
	$highlight2 = 'style="background-color: #e4fcf8;"';
} */
?>
				<ul class="dropdown-menu dropdown-messages">
                        <span class="act"></span>
						<li <?php echo $highlight2 ?>>
                            <a class="">
                                <div>
                                    <i class="fa fa-flag fa-fw"></i> Profile posts
                                    <span class="pull-right text-muted small"><?php echo $postcount ?></span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li <?php echo $highlight1 ?>>
                            <a class="">
                                <div>
                                    <i class="fa fa-user fa-fw"></i> Friend requests
                                    <span class="pull-right text-muted small"><?php echo $friend_count ?></span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li class="">
                            <a class="">
                                <div>
                                    <i class="fa fa-spinner fa-fw"></i> Upcoming updates
                                    <span class="pull-right text-muted small">16</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a class="hand text-center" onclick="window.location = '../notifications/?u=<?php echo $log_username; ?>';">
                                <strong>View all Alerts</strong>
                            </a>
                        </li>
					</ul>