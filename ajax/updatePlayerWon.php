<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "update-player-won"){

	try{

		$roomId = $_POST['roomId'];
		$player = $_POST['player'];
		$sessionKey = $_POST['sessionKey'];

		$sqlUpdateStatus = mysql_query("UPDATE game_running SET player_won = ".$player." WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1 ");

		if($sqlUpdateStatus){
			echo "ok";
		}

	}catch(Exception $e){
		echo $e->getMessage();
	}	

}



?>		