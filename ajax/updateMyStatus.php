<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "update-my-status"){

	try{

		$roomId = $_POST['roomId'];
		$playerId = $_POST['player'];
		$sessionKey = $_POST['sessionKey'];


		$sqlUpdateStatus = mysql_query("UPDATE player_gamedata SET status = 'over' WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND user_id = ".$playerId." LIMIT 1 ");

		if($sqlUpdateStatus){
			echo "ok";
		}

	}catch(Exception $e){
		echo $e->getMessage();
	}	
}



?>		