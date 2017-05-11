<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "meld-card-validation-no-group-f-call"){

	try{

		$roomId = $_POST['roomId'];
		$player = $_POST['player'];
		$gameType = $_POST['gameType'];
		$sessionKey = $_POST['sessionKey'];
		





			$sqlGetTotScore = mysql_query("SELECT total_points FROM player_gamedata WHERE game_id = '".$roomId."' AND user_id = '".$player."' AND session_key = '".$sessionKey."' LIMIT 1 ");

			$row = mysql_fetch_assoc($sqlGetTotScore);

			$total_points = $row['total_points'];
			echo $total_points;

	




	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>