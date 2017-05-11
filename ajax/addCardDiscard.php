<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "add-card-discard"){

	try{

		

		$roomId = $_POST['roomId'];
		$player = $_POST['playerId'];
		$sessionKey = $_POST['sessionKey'];
		$card = $_POST['card'];
		$cardDiscardArr = array();
		$cardDiscardArrAll = array();
		$count = 0;
		$allDiscardedCards = '';

		

		 $sqlGetAllDiscarded = mysql_query("SELECT discarded FROM player_gamedata WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' ");
		 while($rowAll = mysql_fetch_assoc($sqlGetAllDiscarded)){

		 	if(!empty($rowAll['discarded'])){
		 		$allDiscardedCards .= $rowAll['discarded'].',';

		 	}	

		 }


		 $allDiscardedCards = rtrim($allDiscardedCards, ',');

		 $cardDiscardArrAll = explode(",", $allDiscardedCards);
		 


		

		
		// /* get the cards from my discarded section */

		$sqlGet = mysql_query("SELECT discarded FROM player_gamedata WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND user_id = ".$player." LIMIT 1");

		$row = mysql_fetch_assoc($sqlGet);

		$cardGroup = $row['discarded'];


		if(!empty($allDiscardedCards)){


			foreach ($cardDiscardArrAll as $singleCard) {

				if($singleCard == $card){
					$count++;
				}
				
			}

			if($count >= 2){
				$cardGroupNew = $cardGroup;
			}else{
				$cardGroupNew = $cardGroup.','.$card;

			}


			
		}else{
			$cardGroupNew = $card;
		}

		$cardGroupNew = trim($cardGroupNew, ",");




	
		$sql_update_group = mysql_query("UPDATE player_gamedata SET discarded = '".$cardGroupNew."' WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND user_id = '".$player."' LIMIT 1");

		if($sql_update_group){
			 echo "ok";

		}

		 

	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>