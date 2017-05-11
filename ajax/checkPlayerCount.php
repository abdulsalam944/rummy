<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "check-player-count"){

	try{

		

		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];


		$sqlCheck = mysql_query("SELECT * FROM players WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");
		
	
		$row = mysql_fetch_assoc($sqlCheck);
		
		$players = $row['players'];
		$players = explode(",", $players);
		$playerCount = count($players); /* player count */

		echo $playerCount;

		


	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>