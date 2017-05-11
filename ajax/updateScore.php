<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "update-score"){

	try{

		$roomId = $_POST['roomId'];
		$player = $_POST['player'];
		$gameType = $_POST['gameType'];
		$score = $_POST['score'];
		$sessionKey = $_POST['sessionKey'];
		$betValue = $_POST['betValue'];




		if($gameType != "score"){
			if($score > 80){
				$score = 80;
				$pointsAdd = 80;
			}else{
				$pointsAdd = $score;
			}
		}else{
			
			if($score > 80){
				$score = 80;
				$pointsAdd = floatVal(80 * $betValue);
			}else{
				$pointsAdd = floatVal($score * $betValue);
			}
			
		}	


		

		$sqlUpdateScore = mysql_query("UPDATE player_gamedata SET points = ".$score.", total_points = total_points + ".$pointsAdd." WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND user_id = '".$player."' LIMIT 1");

		if($sqlUpdateScore){

			// get total score

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