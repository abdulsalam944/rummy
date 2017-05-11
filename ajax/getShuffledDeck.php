<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "get-shuffled-deck"){

	try{

		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];

		$shuffled_cards = array();
		$error = array();
	
		/* Check if cards have already been shuffled for this room */

		$sqlCheckShuffled = mysql_query("SELECT * FROM shuffled_card WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");
		if(mysql_num_rows($sqlCheckShuffled) > 0){

			$row = mysql_fetch_assoc($sqlCheckShuffled);

			$shuffled = $row['shuffled'];

			if($shuffled == 0){

				$error['error'] = "card not shuffled yet!";
				echo json_encode($error); exit;
				
			}else if($shuffled > 0){

				 /* Get shuffled cards */

				  $shuffled_card = $row['shuffled_cards'];

				  $shuffled_cards = explode(",", $shuffled_card);

				  echo json_encode($shuffled_cards);

			}



		}else if(mysql_num_rows($sqlCheckShuffled) == 0){

			
			$error['error'] = "shuffled card for room not found";
			echo json_encode($error); exit;
	

		}

	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>