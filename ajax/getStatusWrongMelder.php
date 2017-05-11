<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "get-scoreboard"){

	try{

		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];
		$player = $_POST['player'];
		


		
		 $sqlGetMyScore = mysql_query("SELECT status FROM player_gamedata WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND user_id = '".$player."' LIMIT 1");

		 $row1 = mysql_fetch_assoc($sqlGetMyScore);

		 $status = $row1['status'];

		 echo $status;

		

	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>