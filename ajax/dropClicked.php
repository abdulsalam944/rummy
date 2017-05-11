<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "drop-clicked"){

	try{

		$roomId = $_POST['roomId'];
		$player = $_POST['player'];
		$sessionKey = $_POST['sessionKey'];
		
		$sqlUpdate = mysql_query("UPDATE player_gamedata SET drop_clicked = 1 WHERE user_id = '".$player."' AND game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");

		if($sqlUpdate){
			echo "ok";
		}
			
	
		

	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>