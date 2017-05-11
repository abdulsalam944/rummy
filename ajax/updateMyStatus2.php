<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "update-my-status-2"){

	try{

		$roomId = $_POST['roomId'];
		$playerId = $_POST['player'];
		$sessionKey = $_POST['sessionKey'];


		$sqlUpdateStatus = mysql_query("UPDATE player_gamedata SET match_status = 'out' WHERE user_id = ".$playerId." AND game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1 ");

		if($sqlUpdateStatus){
			echo "ok";
		}

	}catch(Exception $e){
		echo $e->getMessage();
	}	
}



?>		