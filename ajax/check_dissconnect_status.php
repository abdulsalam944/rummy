<?php
include("../config.php");
$gameSession = $_POST['room'];
$dissconnectedUser = $_POST['user'];
if($gameSession && $dissconnectedUser){
	// getting user id from socket id
	$userId_query = mysql_query('select user_id from user_connection where session_key = "'.$gameSession.'" and connection_id = "'.$dissconnectedUser.'"');
	$userIdResult = mysql_fetch_array($userId_query);	
	$usersDissconnected = mysql_query('select dissconnected_user from game_running where session_key = "'.$gameSession.'"');
	$userRes = mysql_fetch_array($usersDissconnected);
	if($userRes['dissconnected_user']){
		$users = explode(',',trim($userRes['dissconnected_user']));
		if(in_array($userIdResult['user_id'], $users)){
			echo '1';
		}else{
			echo '0';
		}
	}else{
		echo '2';
	}
}
?>