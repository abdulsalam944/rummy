<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "update-room-started"){

	$roomId = $_POST['roomId'];
	$sessionKey = $_POST['sessionKey'];

	$sql = mysql_query("UPDATE room_tables SET status = 'started' WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");


	if($sql){
		echo "ok";
	}

}

?>