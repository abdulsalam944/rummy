<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "player-update"){

	try{

		$roomId = $_POST['roomId'];
		$playerId = $_POST['playerId'];
		$creator = $_POST['creator'];
		$sessionKey = $_POST['sessionKey'];

		// If Creator of the game remove all data from the row
		if($creator == "true"){
			$sqlRemovePlayers = mysql_query("DELETE FROM players WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");
		}

		// check if row exist or not

		$sqlCheck = mysql_query("SELECT * FROM players WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");
		$numRows = mysql_num_rows($sqlCheck);

		$players = '';
		$playerExist = 1;

		if($numRows > 0){


			// row exists
			    $row = mysql_fetch_assoc($sqlCheck);
				
				$id = $row['id'];
				$game_id = $row['game_id'];
				$players = $row['players'];

			
				$players = explode(",", $players);

				foreach ($players as $key => $player) {


				
					if($player == $playerId){  // If that player already exists
						// unset($cards_in_hand[$key]);
						$playerExist = 1;
						break;
					}else if($player != $playerId){ // If that player doesn't exist
						$playerExist = 0;
					}
				}



				$players = implode(",", $players);

				if($playerExist == 0){
					$players = $players.','.$playerId; // concat the current player if that player doesn't exist
				}

				$players = rtrim($players, ',');

				$sqlPlayerUpdate = mysql_query("UPDATE players SET players = '".$players."' WHERE game_id = '".$game_id."' AND session_key = '".$sessionKey."' LIMIT 1");


		}else if($numRows == 0){


				$sqlPlayerUpdate = mysql_query("INSERT INTO players VALUES (null, '".$roomId."', '".$playerId."', '".$sessionKey."', '')");

		} 

		
		if($sqlPlayerUpdate){

			echo "ok";

		}

	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>