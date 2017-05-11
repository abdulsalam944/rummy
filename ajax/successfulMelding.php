<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "success-melding"){

	try{

		$roomId = $_POST['roomId'];
		$player = $_POST['player'];
		$gameType = $_POST['gameType'];
		$sessionKey = $_POST['sessionKey'];
		$betValue = $_POST['betValue'];

		/* set the points */

		$points = 0;

	


		/* update points */

		$sqlUpdate = mysql_query("UPDATE player_gamedata SET points = ".$points.", total_points = total_points + ".$points." WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND user_id = ".$player." LIMIT 1 ");

		if($sqlUpdate){

			$sqlGetTotScore = mysql_query("SELECT total_points FROM player_gamedata WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND user_id = '".$player."' LIMIT 1 ");

			$row = mysql_fetch_assoc($sqlGetTotScore);

			$total_points = $row['total_points'];

			echo $total_points;



		}




	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>