<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "get-throw-card"){

	try{

		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];
	

		$json_data = array();

	/* get throw card and toss winner */



		$sqlSelect = mysql_query("SELECT throw_card, toss_winner FROM game_running WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");

		$row = mysql_fetch_assoc($sqlSelect);
		$throwCard = $row['throw_card'];
		$tossWinner = $row['toss_winner'];



		$json_data['throw_card'] = $throwCard;
		$json_data['toss_winner'] = $tossWinner;

		echo json_encode($json_data);



	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>