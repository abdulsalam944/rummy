<?php
include("../config.php");
$gameSession = $_POST['room'];
$user = $_POST['user'];
if($gameSession && $user){
	$u = mysql_query('select user_id from user_connection where connection_id =  "'.$user.'" and session_key = "'.$gameSession.'" ');
	$d = mysql_fetch_array($u);
	if(mysql_num_rows($u)>0){
		echo $d['user_id'];
	}else{
		echo 0;
	}
}
?>