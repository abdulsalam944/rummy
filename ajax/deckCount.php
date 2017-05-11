<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "deckCount"){

	try{

		

		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];
		
		$cardGetArr = array();
		
		

		 $sqlGetShuffledCards = mysql_query("SELECT shuffled_cards FROM shuffled_card WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");
		
		$rowAll = mysql_fetch_assoc($sqlGetShuffledCards);

		$shuffled_cards = $rowAll['shuffled_cards'];
		$cardGetArr = explode(",", $shuffled_cards);

		echo count($cardGetArr);



	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>