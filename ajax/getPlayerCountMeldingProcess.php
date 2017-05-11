<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "player-count"){

	try{

		$roomId = $_POST['roomId'];
		$gamePlayers = $_POST['gamePlayers'];
		
		// check player count

		$sqlCheck = mysql_query("SELECT players FROM players WHERE game_id = '".$roomId."' LIMIT 1");
		$numRows = mysql_num_rows($sqlCheck);

		if($numRows > 0){
			$row = mysql_fetch_assoc($sqlCheck);
			$players = $row['players'];

			$players = explode(",", $players);

			$playerCount = count($players);

			echo $playerCount;
		}

	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>