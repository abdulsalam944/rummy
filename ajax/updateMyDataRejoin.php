<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "update-my-data"){

    try{

        $roomId = $_POST['roomId'];
        $sessionKey = $_POST['sessionKey'];
        $playerId = $_POST['userId'];
        $rejoinScore = floatval($_POST['rejoinScore']);
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

        // /* Update cards in deck */

        $sql_update_deck = mysql_query("UPDATE shuffled_card SET shuffled_cards = '".$shuffledCardsString."' WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");

        $shuffled_cards = rtrim($shuffled_cards, ",");

      
            $sqlUpdate = mysql_query("UPDATE player_gamedata SET cards_in_hand = '".$shuffled_cards."', total_points = '".$rejoinScore."', status = '', match_status = '', scoreboard_status = '' WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND user_id = ".$playerId." LIMIT 1");


            if($sqlUpdate){
                echo "ok";
            }

            // echo $sql;
       
        
    }catch(Exception $e){
        echo $e->getMessage();
    }

}

?>