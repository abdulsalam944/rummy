<?php
include("../config.php");
$old = $_POST['old'];
$new = $_POST['new'];
if($old && $new){
	$u = mysql_query('update user_connection set connection_id =  "'.$new.'" where connection_id = "'.$old.'" ');
}
?>