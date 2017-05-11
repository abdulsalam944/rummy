<?php
error_reporting(0);
	session_start();
	/*$host="localhost"; // Host name
	$username="root"; // Mysql username
	$password=""; // Mysql password
	$db_name="rummydb"; // Database name
	//$tbl_name="swabhumi"; // Table name
*/
	$host= '172.16.16.35';
	$username ='web-db_dev-3';
	$password ='MNSVTYer#$*&^%';
	$db_name = 'DEV_LAB3_DB_P732_RUMMY';
	
	// Connect to server and select databse.
	$con=mysql_connect("$host", "$username", "$password")or die("cannot connect");
	mysql_select_db($db_name,$con)or die("cannot select DB");

?>	
