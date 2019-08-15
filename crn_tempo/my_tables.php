<?php
include_once("../php_extensions/db_conx.php");

$tbl_users = "CREATE TABLE IF NOT EXISTS users (
              id INT(11) NOT NULL AUTO_INCREMENT,
			  username VARCHAR(16) NOT NULL,
			  email VARCHAR(255) NOT NULL,
			  password VARCHAR(255) NOT NULL,
			  gender ENUM('m','f') NOT NULL,
			  country VARCHAR(255) NULL,
			  avatar VARCHAR(255) NULL,  
			  ip VARCHAR(255) NOT NULL,
			  userlevel VARCHAR(255) NOT NULL,
			  signup DATETIME NOT NULL,
			  lastlogin DATETIME NOT NULL,
			  notescheck DATETIME NOT NULL,
			  status ENUM('offline','online') NOT NULL DEFAULT 'offline',
              PRIMARY KEY (id),
			  UNIQUE KEY username (username,email)
             )";
$query = mysqli_query($db_conx, $tbl_users);
if ($query === TRUE) {
	echo "<h3>user table created OK :) </h3>"; 
} else {
	echo "<h3 style='color:red;'>user table NOT created :( </h3>"; 
}
$tbl_userinfo = "CREATE TABLE IF NOT EXISTS userinfo (
              id INT(11) NOT NULL,
			  username VARCHAR(16) NOT NULL,
			  education VARCHAR(255) NULL,
			  location VARCHAR(255) NULL,
			  hometown VARCHAR(255) NULL,
			  work VARCHAR(255) NULL,
			  roleModel VARCHAR(255) NULL,
			  mobile VARCHAR(255) NULL,
			  email VARCHAR(255) NULL,
			  at VARCHAR(16) NULL,
			  updatedate DATETIME NOT NULL,
              PRIMARY KEY (id),
			  UNIQUE KEY username (username)
             )";
$query = mysqli_query($db_conx, $tbl_userinfo);
if ($query === TRUE) {
	echo "<h3>userinfo table created OK :) </h3>"; 
} else {
	echo "<h3 style='color:red;'>userinfo table NOT created :( </h3>"; 
}
$tbl_userbasic = "CREATE TABLE IF NOT EXISTS userbasic (
              id INT(11) NOT NULL,
			  username VARCHAR(16) NOT NULL,
			  nickname VARCHAR(255) NULL,
			  relationship VARCHAR(255) NULL,
			  crush VARCHAR(255) NULL,
			  tv VARCHAR(255) NULL,
			  book VARCHAR(255) NULL,
			  update_date DATETIME NOT NULL,
              PRIMARY KEY (id),
			  UNIQUE KEY username (username)
             )";
$query = mysqli_query($db_conx, $tbl_userbasic);
if ($query === TRUE) {
	echo "<h3>userbasic table created OK :) </h3>"; 
} else {
	echo "<h3 style='color:red;'>userbasic table NOT created :( </h3>"; 
}
$tbl_quicksettings = "CREATE TABLE IF NOT EXISTS quicksettings (
              id INT(11) NOT NULL,
			  username VARCHAR(16) NOT NULL,
			  private_acc ENUM('0','1') NOT NULL DEFAULT '0',
			  public_post ENUM('0','1') NOT NULL DEFAULT '1',
              PRIMARY KEY (id),
			  UNIQUE KEY username (username)
             )";
$query = mysqli_query($db_conx, $tbl_quicksettings);
if ($query === TRUE) {
	echo "<h3>quicksettings table created OK :) </h3>"; 
} else {
	echo "<h3 style='color:red;'>quicksettings table NOT created :( </h3>"; 
}
$tbl_accountsettings = "CREATE TABLE IF NOT EXISTS accountsettings (
              id INT(11) NOT NULL,
			  username VARCHAR(16) NOT NULL,
			  general DATETIME NOT NULL,
			  security DATETIME NOT NULL,
			  privacy DATETIME NOT NULL,
              PRIMARY KEY (id),
			  UNIQUE KEY username (username)
             )";
$query = mysqli_query($db_conx, $tbl_accountsettings);
if ($query === TRUE) {
	echo "<h3>accountsettings table created OK :) </h3>"; 
} else {
	echo "<h3 style='color:red;'>accountsettings table NOT created :( </h3>"; 
}
$tbl_useroptions = "CREATE TABLE IF NOT EXISTS useroptions ( 
                id INT(11) NOT NULL,
                username VARCHAR(16) NOT NULL,
				fullname VARCHAR(255) NOT NULL,
				background VARCHAR(255) NULL,
				userstatus TEXT NOT NULL,
				theme VARCHAR(255) NOT NULL,
				editdate DATETIME NOT NULL,
                PRIMARY KEY (id),
                UNIQUE KEY username (username) 
                )"; 
$query = mysqli_query($db_conx, $tbl_useroptions); 
if ($query === TRUE) {
	echo "<h3>useroptions table created OK :) </h3>"; 
} else {
	echo "<h3 style='color:red;'>useroptions table NOT created :( </h3>"; 
}
$tbl_friends = "CREATE TABLE IF NOT EXISTS friends ( 
                id INT(11) NOT NULL AUTO_INCREMENT,
                user1 VARCHAR(16) NOT NULL,
                user2 VARCHAR(16) NOT NULL,
                datemade DATETIME NOT NULL,
                accepted ENUM('0','1') NOT NULL DEFAULT '0',
                PRIMARY KEY (id)
                )"; 
$query = mysqli_query($db_conx, $tbl_friends); 
if ($query === TRUE) {
	echo "<h3>friends table created OK :) </h3>"; 
} else {
	echo "<h3 style='color:red;'>friends table NOT created :( </h3>"; 
}
$tbl_enemies = "CREATE TABLE IF NOT EXISTS enemies ( 
                id INT(11) NOT NULL AUTO_INCREMENT,
                user1 VARCHAR(16) NOT NULL,
                user2 VARCHAR(16) NOT NULL,
                date_made DATETIME NOT NULL,
                PRIMARY KEY (id)
                )"; 
$query = mysqli_query($db_conx, $tbl_enemies); 
if ($query === TRUE) {
	echo "<h3>enemies table created OK :) </h3>"; 
} else {
	echo "<h3 style='color:red;'>enemies table NOT created :( </h3>"; 
}
$tbl_blockedusers = "CREATE TABLE IF NOT EXISTS blockedusers ( 
                id INT(11) NOT NULL AUTO_INCREMENT,
                blocker VARCHAR(16) NOT NULL,
                blockee VARCHAR(16) NOT NULL,
                blockdate DATETIME NOT NULL,
                PRIMARY KEY (id) 
                )"; 
$query = mysqli_query($db_conx, $tbl_blockedusers); 
if ($query === TRUE) {
	echo "<h3>blockedusers table created OK :) </h3>"; 
} else {
	echo "<h3>blockedusers table NOT created :( </h3>"; 
}
$tbl_status = "CREATE TABLE IF NOT EXISTS status ( 
                id INT(11) NOT NULL AUTO_INCREMENT,
                osid INT(11) NOT NULL,
                account_name VARCHAR(16) NOT NULL,
                author VARCHAR(16) NOT NULL,
                type ENUM('a','b','c') NOT NULL,
                data TEXT NOT NULL,
				photo VARCHAR(255) NULL,
                postdate DATETIME NOT NULL,
                PRIMARY KEY (id) 
                )"; 
$query = mysqli_query($db_conx, $tbl_status); 
if ($query === TRUE) {
	echo "<h3>status table created OK :) </h3>"; 
} else {
	echo "<h3 style='color:red;'>status table NOT created :( </h3>"; 
}
$tbl_chat = "CREATE TABLE IF NOT EXISTS chat ( 
                id INT(11) NOT NULL AUTO_INCREMENT,
				osid INT(11) NOT NULL,
                user1 VARCHAR(16) NOT NULL,
				user2 VARCHAR(16) NOT NULL,
                msg_data TEXT NOT NULL,
                timesent DATETIME NOT NULL,
                PRIMARY KEY (id) 
                )"; 
$query = mysqli_query($db_conx, $tbl_chat); 
if ($query === TRUE) {
	echo "<h3>chat table created OK :) </h3>"; 
} else {
	echo "<h3 style='color:red;'>chat table NOT created :( </h3>"; 
}
$tbl_photos = "CREATE TABLE IF NOT EXISTS photos ( 
                id INT(11) NOT NULL AUTO_INCREMENT,
				osid INT(11) NOT NULL,
                user VARCHAR(16) NOT NULL,
                gallery VARCHAR(16) NOT NULL,
				filename VARCHAR(255) NOT NULL,
                description VARCHAR(255) NULL,
                uploaddate DATETIME NOT NULL,
                PRIMARY KEY (id) 
                )"; 
$query = mysqli_query($db_conx, $tbl_photos); 
if ($query === TRUE) {
	echo "<h3>photos table created OK :) </h3>"; 
} else {
	echo "<h3 style='color:red;'>photos table NOT created :( </h3>"; 
}
$tbl_notifications = "CREATE TABLE IF NOT EXISTS notifications ( 
                id INT(11) NOT NULL AUTO_INCREMENT,
                username VARCHAR(16) NOT NULL,
                initiator VARCHAR(16) NOT NULL,
                app VARCHAR(255) NOT NULL,
                note VARCHAR(255) NOT NULL,
                did_read ENUM('0','1') NOT NULL DEFAULT '0',
                date_time DATETIME NOT NULL,
                PRIMARY KEY (id) 
                )"; 
$query = mysqli_query($db_conx, $tbl_notifications); 
if ($query === TRUE) {
	echo "<h3>notifications table created OK :) </h3>"; 
} else {
	echo "<h3 style='color:red;'>notifications table NOT created :( </h3>"; 
}
?>