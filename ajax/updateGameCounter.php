<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "update-game-counter"){

	try{

		$roomId = $_POST['roomId'];
		$counter = $_POST['counter'];
		$sessionKey = $_POST['sessionKey'];

		$sqlUpdate = mysql_query("UPDATE room_tables SET game_start_counter = '".$counter."' WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");
	
		if($sqlUpdate){
			echo "updated counter";
		}
		
		
		

	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>