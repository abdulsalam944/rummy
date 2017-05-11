<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "get-pl-score"){

	try{

		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];
		$me = $_POST['playerId'];
		//$j = 0;

	
		$jsonArr = array();

		$sqlGetHighestPoint = mysql_query("SELECT MAX(total_points) AS highest_point FROM player_gamedata WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND user_id != '".$me."' LIMIT 1 ");

		$row1 = mysql_fetch_assoc($sqlGetHighestPoint);
		$highestPoint = $row1['highest_point'];

		$jsonArr['highestPoint'] = $highestPoint;



		echo json_encode($jsonArr);


	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>
