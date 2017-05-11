<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "shuffle-deck"){

	try{

		// $players = $_POST['players'];
		$roomId = $_POST['roomId'];
		$players = $_POST['players'];
		$sessionKey = $_POST['sessionKey'];

		$tossCardsArray = array();
		$shuffledCardsArray = array();
		$tossShuffledCards = '';
		$shuffled_cards = '';



		/* Check if cards have already been shuffled for this room */

		$sqlCheckShuffled = mysql_query("SELECT * FROM shuffled_card WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");
		if(mysql_num_rows($sqlCheckShuffled) > 0){

			$row = mysql_fetch_assoc($sqlCheckShuffled);

				$shuffled = $row['shuffled'];


				/* Card has not been shuffled yet. It needs to be shuffled */


				/* Getting the normal deck */

				$sqlCheck1 = mysql_query("SELECT name FROM cards WHERE players = 0 LIMIT 1");
				
					$row1 = mysql_fetch_assoc($sqlCheck1);
					$cards1 = $row1['name'];

					$tossCardsArray = explode(",", $cards1);


					/* card shuffled */
					shuffle($tossCardsArray);

					$tossShuffledCards = implode(",", $tossCardsArray);
					$tossShuffledCards = rtrim($tossShuffledCards, ",");

					/* insert the shuffled cards into db */

					$sqlUpdate1 = mysql_query("UPDATE shuffled_card SET toss_shuffled_cards = '".$tossShuffledCards."', shuffled = '".$shuffled."' + 1 WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");



					if($sqlUpdate1){
						

						/*  Lets shuffle the playing cards */

						$sqlCheck2 = mysql_query("SELECT name FROM cards WHERE players = 2 LIMIT 1");
				
						$row2 = mysql_fetch_assoc($sqlCheck2);
						$cards2 = $row2['name'];

						$shuffledCardsArray = explode(",", $cards2);

						/* card shuffled */
						shuffle($shuffledCardsArray);


						$shuffled_cards = implode(",", $shuffledCardsArray);
						$shuffled_cards = rtrim($shuffled_cards, ",");

						/* insert the shuffled cards into db */

						$sqlUpdate2 = mysql_query("UPDATE shuffled_card SET shuffled_cards = '".$shuffled_cards."' WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");

						if($sqlUpdate2){
							echo "ok";
						}

					}
					

			

		}else if(mysql_num_rows($sqlCheckShuffled) == 0){

			/*  Card has not been shuffled yet. Needs to be shuffled. */

				/* Getting the normal deck */

					$sqlCheck1 = mysql_query("SELECT name FROM cards WHERE players = 0 LIMIT 1");
				
					$row1 = mysql_fetch_assoc($sqlCheck1);
					$cards1 = $row1['name'];

					$tossCardsArray = explode(",", $cards1);


					/* card shuffled */
					shuffle($tossCardsArray);

					$tossShuffledCards = implode(",", $tossCardsArray);
					$tossShuffledCards = rtrim($tossShuffledCards, ",");

					/* insert the shuffled cards into db */

					$sqlInsert1 = mysql_query("INSERT INTO shuffled_card VALUES (null, '".$roomId."', '".$sessionKey."', '', '".$tossShuffledCards."', 1)");

					if($sqlInsert1){
					/*  Lets shuffle the playing cards */


						$sqlCheck2 = mysql_query("SELECT name FROM cards WHERE players = ".$players." LIMIT 1");
				
						$row3 = mysql_fetch_assoc($sqlCheck2);
						$cards2 = $row3['name'];

						$shuffledCardsArray = explode(",", $cards2);

						/* card shuffled */
						shuffle($shuffledCardsArray);


						$shuffled_cards = implode(",", $shuffledCardsArray);
						$shuffled_cards = rtrim($shuffled_cards, ",");

						/* insert the shuffled cards into db */

						$sqlUpdate2 = mysql_query("UPDATE shuffled_card SET shuffled_cards = '".$shuffled_cards."' WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");

						if($sqlUpdate2){
							echo "ok";
						}




					}
					




		}


	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>