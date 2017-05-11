<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "update-player-melded"){

	try{

		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];
		$player = $_POST['player'];

		$sqlUpdateStatus = mysql_query("UPDATE player_gamedata SET melded = 'Y' WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND user_id = ".$player." LIMIT 1 ");

		if($sqlUpdateStatus){
			echo "ok";
		}

	}catch(Exception $e){
		echo $e->getMessage();
	}	

}



?>		