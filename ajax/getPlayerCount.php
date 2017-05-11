<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "player-count"){

	try{

		$roomId = $_POST['roomId'];
		$gamePlayers = $_POST['gamePlayers'];
		$sessionKey = $_POST['sessionKey'];
		
		// check player count

		$sqlCheck = mysql_query("SELECT players FROM players WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");
		
		$numRows = mysql_num_rows($sqlCheck);

		if($numRows > 0){
			$row = mysql_fetch_assoc($sqlCheck);
			$players = $row['players'];

			$players = explode(",", $players);

			$playerCount = count($players);

			if($gamePlayers == 2){

				if($playerCount == $gamePlayers){
					echo "ok";
				}else if($playerCount < $gamePlayers){
					echo "less";
				}

			}else if($gamePlayers == 6){

				if($playerCount < $gamePlayers){
					echo "wait";
				}


			}
		}

	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>