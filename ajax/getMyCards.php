<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "get-cards"){

	try{

		$roomId = $_POST['roomId'];
		$playerId = $_POST['playerId'];
		$sessionKey = $_POST['sessionKey'];

		$myCards = array();


		/* Check if cards have already been shuffled for this room */

		$sqlGetCards = mysql_query("SELECT cards_in_hand FROM player_gamedata WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND user_id = '".$playerId."' LIMIT 1");

		$row = mysql_fetch_assoc($sqlGetCards);

		$cards = $row['cards_in_hand'];

		$myCards = explode(",", $cards);

		echo json_encode($myCards);

		

	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>