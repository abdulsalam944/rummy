<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "check-dropandgo"){

	try{

		$roomId = $_POST['roomId'];
		$player = $_POST['player'];
		$sessionKey = $_POST['sessionKey'];
		
		$sqlGet = mysql_query("SELECT drop_and_go FROM player_gamedata WHERE user_id = '".$player."' AND game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");
		$row = mysql_fetch_assoc($sqlGet);
		$count = $row['drop_and_go'];

		if($sqlGet){
			echo $count;
		}
			
	
		

	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>