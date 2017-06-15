<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "meld-card-validation-no-group"){

	try{

		$roomId = $_POST['roomId'];
		$player = $_POST['player'];
		$gameType = $_POST['gameType'];
		$sessionKey = $_POST['sessionKey'];
		$betValue = $_POST['betValue'];

		$points = $_POST['points'];
		
		/* set the points */

		if($gameType != "score"){
			
			$pointsAdd = $points;
		}else{
			
			$pointsAdd = floatVal($points * $betValue);
		}	




			/* update points */
			if(isset($_POST['offline']) && $_POST['offline']=="offline"){
				$sqlUpdate = 1;
			}else{
				$sqlUpdate = mysql_query("UPDATE player_gamedata SET points = ".$points.", total_points = total_points + ".$pointsAdd." WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND user_id = ".$player." LIMIT 1 ");
			}
				//$sqlUpdate = mysql_query("UPDATE player_gamedata SET points = ".$points.", total_points = total_points + ".$pointsAdd." WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND user_id = ".$player." LIMIT 1 ");

			if($sqlUpdate){
				
				
				$sqlGetTotScore = mysql_query("SELECT total_points FROM player_gamedata WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND user_id = '".$player."' LIMIT 1 ");

				$row = mysql_fetch_assoc($sqlGetTotScore);

				$total_points = $row['total_points'];
				echo $total_points;

			}


		//}




	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>