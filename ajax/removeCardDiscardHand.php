<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "remove-card-discard-hand"){

	try{

		

		$roomId = $_POST['roomId'];
		$player = $_POST['playerId'];
		$sessionKey = $_POST['sessionKey'];
		$netSpeed = $_POST['netSpeed'];

		
		$cardsInHand= $_POST['cardsInHand'];
		

		$cardsInHand = implode(",", $cardsInHand);

		// if($netSpeed <= 1){


		// 	$sqlGetHandChecker = mysql_query("SELECT hand_checker FROM player_gamedata WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND user_id = '".$player."' LIMIT 1");

		// 	$sqlGetRow = mysql_fetch_assoc($sqlGetHandChecker);
		// 	$handChecker = $sqlGetRow['hand_checker'];

		// 	if($handChecker < 2){
		// 		$handChecker++;
		// 	}else if($handChecker >= 2){
		// 		$handChecker = 0;
		// 	}

		// }else{
		// 	$handChecker = 0;
		// }
	

	
		$sql_update_group = mysql_query("UPDATE player_gamedata SET cards_in_hand = '".$cardsInHand."', drop_checker = 1 WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND user_id = '".$player."' LIMIT 1");

		if($sql_update_group){
			echo "ok";
		}


	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>