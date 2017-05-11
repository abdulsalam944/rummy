<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "get-game-counter"){

	try{

		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];

		$sqlCheck = mysql_query("SELECT game_start_counter FROM room_tables WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."'  LIMIT 1");
	
		$row = mysql_fetch_assoc($sqlCheck);
		$counter = $row['game_start_counter'];
		
		echo $counter;
		
		

	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>