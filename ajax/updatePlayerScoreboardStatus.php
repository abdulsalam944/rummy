<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "update-player-scoreboard-status"){

	try{

		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];
		$player = $_POST['player'];
		$status = $_POST['status'];

		$checkScoreboardStatus = mysql_query("SELECT scoreboard_status FROM player_gamedata WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND user_id = ".$player." LIMIT 1");

		$row = mysql_fetch_assoc($checkScoreboardStatus);
		$scoreboardStatus = $row['scoreboard_status'];

		if(empty($scoreboardStatus)){
			$sqlUpdateStatus = mysql_query("UPDATE player_gamedata SET scoreboard_status = '".$status."' WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND user_id = ".$player." LIMIT 1 ");

			if($sqlUpdateStatus){
				echo "ok";
			}

		}

	

	}catch(Exception $e){
		echo $e->getMessage();
	}	

}



?>		