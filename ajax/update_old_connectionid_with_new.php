<?php
include("../config.php");
$old = $_POST['old'];
$new = $_POST['new'];
if($old && $new){
	$u = mysql_query('update user_connection set connection_id =  "'.$new.'" where connection_id = "'.$old.'" ');
	$sql_q = mysql_query('select user_id from user_connection where connection_id = "'.$new.'" ');
	$sql_arr = mysql_fetch_array($sql_q);
	echo $sql_arr['user_id'];
}
?>