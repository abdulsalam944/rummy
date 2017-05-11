<?php

include("../config.php");

$gameSession = $_GET['sessionId'];
$dissconnectedUser = $_GET['userId'];


if($gameSession && $dissconnectedUser){

	$getPreviousUser = mysql_query('select dissconnected_user from game_running where session_key = "'.$gameSession.'"');

	$res = mysql_fetch_array($getPreviousUser);

	if($res['dissconnected_user']!=""){
		$users = explode(',',$res['dissconnected_user']);
		$users = array_filter($users);
		array_push($users, $dissconnectedUser);
		mysql_query('update game_running set dissconnected_user = "'.implode(',',$users).'" where session_key = "'.$gameSession.'"');
	}else{
		mysql_query('update game_running set dissconnected_user = "'.$dissconnectedUser.'" where session_key = "'.$gameSession.'" ');
	}

}

?>