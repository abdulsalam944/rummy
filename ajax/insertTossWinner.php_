<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "toss-done"){

	try{

		$roomId = $_POST['roomId'];
		$playerId = $_POST['player'];
		$sessionKey = $_POST['sessionKey'];

		$shuffledCardsArray = array();
		$shuffled_cards = '';

		/* Check if same user exists in the same room */

		$sql_check1 = mysql_query("SELECT * FROM toss WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");
		if(mysql_num_rows($sql_check1) > 0){
			$sql_update1 = mysql_query("UPDATE toss SET player_id = '".$playerId."' WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");

			if($sql_update1){
				
					/* Game has started. Update needed in the game_running table */

					$sql_check2 = mysql_query("SELECT * FROM game_running WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");
					
					if(mysql_num_rows($sql_check2) > 0){


						$sql_update3 = mysql_query("UPDATE game_running SET deck = '', joker_selected = '', round = 1, show_button = 0, deck = '', throw_card = '', current_player = 0, melded_count = 0, player_won = 0, wrong_melders = '', joker_pulled = 0, toss_winner = '".$playerId."' WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");



						if($sql_update3){
							echo "ok";
						}

					}else{



						$sql_insert2 = mysql_query("INSERT INTO game_running VALUES (null, '".$roomId."', '".$sessionKey."', '', 1, 0, '".$playerId."', '', '', 0, 0, 0, '', 0,'','','')");

						if($sql_insert2){
							echo "ok";
						}

					}


			}

		}else if(mysql_num_rows($sql_check1) == 0){

			$sqlInsert1 = mysql_query("INSERT INTO toss VALUES (null, '".$roomId."', '".$sessionKey."', '".$playerId."')");

			if($sqlInsert1){
				
					/* Game has started. Update needed in the game_running table */

					$sql_check2 = mysql_query("SELECT * FROM game_running WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");
					
					if(mysql_num_rows($sql_check2) > 0){

						$sql_update3 = mysql_query("UPDATE game_running SET deck = '', joker_selected = '', round = 1, show_button = 0, deck = '', throw_card = '', current_player = 0, melded_count = 0, player_won = 0, wrong_melders = '', joker_pulled = 0, toss_winner = '".$playerId."' WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");

						if($sql_update3){
							echo "ok";
						}

					}else{

					
						$sql_insert2 = mysql_query("INSERT INTO game_running VALUES (null, '".$roomId."', '".$sessionKey."', '', 1, 0, '".$playerId."', '', '', 0, 0, 0, '', 0,'','','')");

						if($sql_insert2){
							echo "ok";
						}

					}





			}

		}

		
	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>