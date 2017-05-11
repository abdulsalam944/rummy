<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "update-hand-throwcard"){

	try{

		

		$roomId = $_POST['roomId'];
		$player = $_POST['playerId'];
		$sessionKey = $_POST['sessionKey'];

		
		$cardsInHand = $_POST['cardsInHand'];

		$cardsInHand = implode(",", $cardsInHand);
		
		$sql_update_group = mysql_query("UPDATE player_gamedata SET cards_in_hand = '".$cardsInHand."' WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND user_id = '".$player."' LIMIT 1");

		if($sql_update_group){
			echo "ok";
		}


	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>