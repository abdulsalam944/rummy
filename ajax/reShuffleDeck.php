<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "reShuffleDeck"){

	try{

		

		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];
		$allDiscardedCards = '';
		
		$cardDeckArr = array();
		
		

		 $sqlGetShuffledCards = mysql_query("SELECT shuffled_cards FROM shuffled_card WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");
		
		$rowAll = mysql_fetch_assoc($sqlGetShuffledCards);

		$shuffled_cards = $rowAll['shuffled_cards'];


		/* Get discarded cards */

		 $sqlGetAllDiscarded = mysql_query("SELECT discarded FROM player_gamedata WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' ");
		 while($rowAll = mysql_fetch_assoc($sqlGetAllDiscarded)){

		 	if(!empty($rowAll['discarded'])){
		 		$allDiscardedCards .= $rowAll['discarded'].',';

		 	}	

		 }


		 $allDiscardedCards = rtrim($allDiscardedCards, ',');
		 $newDeck = $shuffled_cards.','.$allDiscardedCards;

		 $newDeck = trim($newDeck, ",");

		 $cardDeckArr = explode(",", $newDeck);
		 shuffle($cardDeckArr);

		 $newDeck = implode(",", $cardDeckArr);
		 $newDeck = rtrim($newDeck, ",");


		 /* Update shuffle cards */

		 $sql_update_deck = mysql_query("UPDATE shuffled_card SET shuffled_cards = '".$newDeck."' WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");

		 if($sql_update_deck){
		 	/* remove card discarded from player's hands */
		 	mysql_query("UPDATE player_gamedata SET discarded = '' WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' ");

		 }
		



	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>