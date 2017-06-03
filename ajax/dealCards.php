<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "deal-cards"){

	try{

		$roomId = $_POST['roomId'];
		$playerId = $_POST['playerId'];
		$sessionKey = $_POST['sessionKey'];
		$point = $_POST['point'];
		$chipsTaken = $_POST['chipsTaken'];

		$shuffledCardsArray = array();
		$shuffled_cards = '';
		$shuffledCardsString = '';


		/* Check if cards have already been shuffled for this room */

		$sqlGetShuffled = mysql_query("SELECT shuffled_cards FROM shuffled_card WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");

		$row1 = mysql_fetch_assoc($sqlGetShuffled);

		$cards = $row1['shuffled_cards'];

		$shuffledCardsArray = explode(",", $cards);

		for($i = 0; $i < count($shuffledCardsArray); $i++){

			$shuffled_cards .= $shuffledCardsArray[$i].',';

			/* remove the card */

			if (($key = array_search($shuffledCardsArray[$i], $shuffledCardsArray)) !== false) {
    			unset($shuffledCardsArray[$key]);
			}

			if($i == 12) break;



		}


		$shuffledCardsString = implode(",", $shuffledCardsArray);
		$shuffledCardsString = rtrim($shuffledCardsString, ",");

		/* Update cards in deck */

		$sql_update_deck = mysql_query("UPDATE shuffled_card SET shuffled_cards = '".$shuffledCardsString."' WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");

		$shuffled_cards = rtrim($shuffled_cards, ",");


		/*  insert into player's table */

		$sqlCheckPlayerExist = mysql_query("SELECT id FROM player_gamedata WHERE user_id = '".$playerId."' AND game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1 ");

		if(mysql_num_rows($sqlCheckPlayerExist) > 0){
			$sqlUpdate = mysql_query("UPDATE player_gamedata SET cards_in_hand = '".$shuffled_cards."' WHERE user_id = '".$playerId."' AND game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");

			if($sqlUpdate){
				echo "ok";
			}
			
		}else if(mysql_num_rows($sqlCheckPlayerExist) == 0){

			$sqlInsert = mysql_query("INSERT INTO player_gamedata VALUES (null, '".$playerId."', '".$roomId."', '".$sessionKey."', '".$shuffled_cards."', '', '', '', '', '', '', '', '', '', '', '', '', '', '".$point."', '".$point."', '".$chipsTaken."', 0, '', '', '', 'N', 0, 0, 0, 0, 0, 0)");

			if($sqlInsert){
				echo "ok";
			}


		}

		

	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>