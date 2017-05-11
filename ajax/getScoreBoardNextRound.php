<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "get-scoreboard"){

	try{

		$roomId = $_POST['roomId'];
		$player = $_POST['player'];
		$sessionKey = $_POST['sessionKey'];
		


		$jsonArr = array();
	

		/* Get score */
		
		 $sqlGetMyScore = mysql_query("SELECT * FROM player_gamedata WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND user_id = '".$player."' LIMIT 1");

		 $row1 = mysql_fetch_assoc($sqlGetMyScore);

		 $points = $row1['points'];
		 $totalPoints = $row1['total_points'];

		

		 $jsonArr['points'] = $points;
		 $jsonArr['total_points'] = $totalPoints;
		

		 echo json_encode($jsonArr);


	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>