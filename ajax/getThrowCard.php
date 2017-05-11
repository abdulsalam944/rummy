<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "get-throw-card"){

	try{

		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];
		$shuffledCardsArray = array();
		$shuffled_card = '';
		$shuffledCardsString = '';

		$json_data = array();



		/* create a throw card */

		$sqlGetShuffled = mysql_query("SELECT shuffled_cards FROM shuffled_card WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");

		$row = mysql_fetch_assoc($sqlGetShuffled);
		
		$cards = $row['shuffled_cards'];

		$shuffledCardsArray = explode(",", $cards);

		for($i = 0; $i < count($shuffledCardsArray); $i++){

			

			$shuffled_card = $shuffledCardsArray[$i];

			/* remove the card */

			if (($key = array_search($shuffledCardsArray[$i], $shuffledCardsArray)) !== false) {
    			unset($shuffledCardsArray[$key]);
			}

			if($i == 1) break;

		}	

		$shuffledCardsString = implode(",", $shuffledCardsArray);
		$shuffledCardsString = rtrim($shuffledCardsString, ",");

			/* Update cards in deck */

		$sql_update_deck = mysql_query("UPDATE shuffled_card SET shuffled_cards = '".$shuffledCardsString."' WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");

		$sql_update_throwCard = mysql_query("UPDATE game_running SET throw_card = '".$shuffled_card."' WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");


		/* get toss winner */

		$sqlTossWinner = mysql_query("SELECT toss_winner FROM game_running WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");

		$row2 = mysql_fetch_assoc($sqlTossWinner);
		$tossWinner = $row2['toss_winner'];

		$json_data['throw_card'] = $shuffled_card;
		$json_data['toss_winner'] = $tossWinner;

		echo json_encode($json_data);



	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>