<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "get-first-melder"){

	try{

		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];

		 /*get player won */

		 $sqlGetPlayerWon = mysql_query("SELECT player_won FROM game_running WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");
		 
		 $row2 = mysql_fetch_assoc($sqlGetPlayerWon);
		 $player_won = $row2['player_won'];


		echo $player_won;


	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>