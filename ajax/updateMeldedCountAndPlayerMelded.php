<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "update-melded-count-player-melded"){

	try{

		$roomId = $_POST['roomId'];
		$player = $_POST['player'];
		$sessionKey = $_POST['sessionKey'];
		


		
		$sqlUpdate1 = mysql_query("UPDATE game_running SET melded_count = 0 WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");


		

	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>