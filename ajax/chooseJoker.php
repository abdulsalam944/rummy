<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "choose-joker"){

	try{

		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];

		$shuffled_cards = array();

		/* Check if same user exists in the same room */

		$sqlCheck1 = mysql_query("SELECT name FROM cards WHERE players = 0 LIMIT 1");

		$row1 = mysql_fetch_assoc($sqlCheck1);
		$cards = $row1['name'];

		$shuffled_cards = explode(",", $cards);

		/* card shuffled */
		shuffle($shuffled_cards);

		$flag = 0;

		while($flag = 0){
			if($shuffled_cards[0] == "Joker"){
				$flag = 0;
		  		shuffle($shuffled_cards);	
			}else{
				$flag = 1;
				break;
			}

		}

		

		/* insert joker selected */


		$sql_update2 = mysql_query("UPDATE game_running SET joker_selected = '".$shuffled_cards[0]."' WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");

		if($sql_update2){

			echo $shuffled_cards[0];

		}




		
	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>