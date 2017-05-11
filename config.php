<?php
error_reporting(0);
	session_start();
	/*$host="localhost"; // Host name
	$username="root"; // Mysql username
	$password=""; // Mysql password
	$db_name="rummydb"; // Database name
	//$tbl_name="swabhumi"; // Table name
*/
	$host= 'localhost';
	$username ='root';
	$password ='1234';
	$db_name = 'rummydb';
	
	// Connect to server and select databse.
	$con=mysql_connect("$host", "$username", "$password")or die("cannot connect");
	mysql_select_db($db_name,$con)or die("cannot select DB");

?>	
