<?php
$check1 = '';
$check2 = '';
$luci_1 = '';
$luci_2 = '';
$logo = '';
$icon = '';
$sql = "SELECT * FROM useroptions WHERE username='$log_username'";
$query = mysqli_query($db_conx, $sql);
while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
	$user = $row["username"];
	$theme = $row["theme"];
}
if($theme == 'dark'){
	$check2 = 'Checked';
	$luci_1 = '<link id="pagetheme" href="../assets/theme/luci.dark.css" rel="stylesheet">';
	$luci_2 = '<link id="pagetheme" href="../assets/theme/luci.dark-o.css" rel="stylesheet">';
	$logo = '<img src="../assets/img/bleak_logo_dark.png" alt="bleakLOGO">';
	$icon = '<link href="../assets/img/bleak_icon_dark.png" rel="icon" />';
}else{
	$check1 = 'Checked';
	$luci_1 = '<link id="pagetheme" href="../assets/css/luci.1.0.1.css" rel="stylesheet">';
	$luci_2 = '<link id="pagetheme" href="../assets/css/luci.1.0.2.css" rel="stylesheet">';
	$logo = '<img src="../assets/img/bleak_logo.png" alt="bleakLOGO">';
	$icon = '<link href="../assets/img/bleak_icon.png" rel="icon" />';
}
?>