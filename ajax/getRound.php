<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "get-round"){

	try{

		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];
		
		$sqlGetRound = mysql_query("SELECT round FROM game_running WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");
		$row = mysql_fetch_assoc($sqlGetRound);
		$round = $row['round'];


		if($sqlGetRound){
			echo $round;
		}

	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>