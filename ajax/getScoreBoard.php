<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "get-scoreboard"){

	try{

		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];
		$player = $_POST['player'];
		


		$jsonArr = array();
	

		/* Get score */
		
		 $sqlGetMyScore = mysql_query("SELECT * FROM player_gamedata WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND user_id = '".$player."' AND melded = 'Y' LIMIT 1");

		 $row1 = mysql_fetch_assoc($sqlGetMyScore);

		 $points = $row1['points'];
		 $totalPoints = $row1['total_points'];
		 $scoreboard_status = $row1['scoreboard_status'];
		 $dealMeOut = $row1['deal_me_out'];

		 /*get player won */

		 $sqlGetPlayerWon = mysql_query("SELECT player_won FROM game_running WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");
		 $row2 = mysql_fetch_assoc($sqlGetPlayerWon);

		 $player_won = $row2['player_won'];


		 $jsonArr['points'] = $points;
		 $jsonArr['total_points'] = $totalPoints;
		 $jsonArr['player_won'] = $player_won;
		 $jsonArr['scoreboard_status'] = $scoreboard_status;
		 $jsonArr['dealMeOut'] = $dealMeOut;

		 echo json_encode($jsonArr);


	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>