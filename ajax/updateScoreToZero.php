<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "update-score"){

	try{

		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];
		$player = $_POST['player'];
		$gameType = $_POST['gameType'];
		

		$sqlUpdateScore = mysql_query("UPDATE player_gamedata SET points = 0, total_points = total_points + 0 WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND user_id = '".$player."' LIMIT 1");

		if($sqlUpdateScore){
			echo "ok";
		}
		

	}catch(Exception $e){
		echo $e->getMessage();
		
	}

}

?>