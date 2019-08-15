<?php
mysqli_query($db_conx, "UPDATE users SET notescheck=now() WHERE username='$log_username'");
mysqli_query($db_conx, "UPDATE notifications SET did_read='1' WHERE username='$log_username'");
mysqli_query($db_conx, "DELETE FROM notifications WHERE date_time < DATE_ADD(NOW(),INTERVAL -3 DAYS) AND did_read='1'");
?>

<?php
/* require_once("../php_extensions/db_conx.php");
$sql = "SELECT notescheck FROM users WHERE username='$log_username'";
$query = mysqli_query($db_conx, $sql);
$row = mysqli_fetch_row($query);
$notecheck = $row[0];
$sql = "SELECT * FROM notifications WHERE date_time<=CURRENT_DATE - INTERVAL 1 DAY AND did_read='1'";
$query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($query);
if($numrows > 0){
	while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
	  $id = $row['id'];
	  $username = $row['username'];
	  mysqli_query($db_conx, "DELETE FROM notifications WHERE id='$id' AND username='$username' LIMIT 1");
    }
} */
?>