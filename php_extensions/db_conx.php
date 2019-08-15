<?php

$db_conx = mysqli_connect("localhost","root","","bleak_social");
if (mysqli_connect_errno()){
		echo mysqli_connect_error();
		exit();
}/*else{
	echo "Successful connected to database!";
} */

?>