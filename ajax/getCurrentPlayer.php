<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "get-player-turn"){

	try{

		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];
	

		$sqlGetCurrentPlayer = mysql_query("SELECT current_player FROM game_running WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");

		$row = mysql_fetch_assoc($sqlGetCurrentPlayer);

		$player = $row['current_player'];

		echo $player;


	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>