<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "delete-room"){

	try{

		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];
		
		/* Check if cards have already been shuffled for this room */

		$sqlDeleteRoom = mysql_query("DELETE FROM room_tables WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");

		$sqlDeletePlayer = mysql_query("DELETE FROM players WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");

		if($sqlDeletePlayer){
			echo "ok";
		}
		

		

	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>