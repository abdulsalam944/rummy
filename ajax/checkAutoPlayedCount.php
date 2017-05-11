<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "check-autoplayed-count"){

	try{

		

		$roomId = $_POST['roomId'];
		$player = $_POST['player'];
		$sessionKey = $_POST['sessionKey'];
		

		$sqlGetHandCount = mysql_query("SELECT hand_checker FROM player_gamedata WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND user_id = '".$player."' LIMIT 1");

		$row = mysql_fetch_assoc($sqlGetHandCount);
		$handChecker = $row['hand_checker'];




		if($sqlGetHandCount){
			echo $handChecker;


			$handChecker++;

			$sqlUpdateStatus = mysql_query("UPDATE player_gamedata SET hand_checker = '".$handChecker."' WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND user_id = ".$player." LIMIT 1 ");
		}


	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>