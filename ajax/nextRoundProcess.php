<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "next-round-process"){

    try{

        $roomId = $_POST['roomId'];
        $sessionKey = $_POST['sessionKey'];
        $playerTurn = $_POST['playerTurn'];







        // get cards from cards table, shuffle them enter into shuffle cards table



        /* Get normal deck */

        $sqlCheck1 = mysql_query("SELECT name FROM cards WHERE players = 2 LIMIT 1");
                
        $row1 = mysql_fetch_assoc($sqlCheck1);
        $cards = $row1['name'];

        $cards = explode(",", $cards);


        /* card shuffled */
        shuffle($cards);

        $cards = implode(",", $cards);
        $cards = rtrim($cards, ",");

        // echo $cards;

        /* update the shuffled cards into db */

        $sqlUpdate1 = mysql_query("UPDATE shuffled_card SET shuffled_cards = '".$cards."', shuffled = 1 WHERE game_id = ".$roomId." AND session_key = '".$sessionKey."' LIMIT 1");



        // /** Choose Joker **/

       
        $cards = explode(",", $cards);
        shuffle($cards);

        $flag = 0;

        while($flag = 0){
            if($cards[0] == "Joker"){
                $flag = 0;
                shuffle($cards);   
            }else{
                $flag = 1;
                break;
            }

        }

        /* insert joker selected && update current player who will play */


        $sql_update2 = mysql_query("UPDATE game_running SET joker_selected = '".$cards[0]."', toss_winner = ".$playerTurn.", joker_pulled = 0 WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");





        /** UPDATE PLAYER GAMEDATA **/
        $sql_update3 = mysql_query("UPDATE player_gamedata SET cards_in_hand = '', group_1 = '', group_2 = '', group_3 = '', group_4 = '', group_5 = '', group_6 = '', melded_group_1 = '', melded_group_2 = '', melded_group_3 = '', melded_group_4 = '', melded_group_5 = '', melded_group_6 = '', discarded = '', melded = 'N', drop_checker = 0, scoreboard_status = '', deal_me_out = 0, drop_and_go = 0, drop_clicked = 0, hand_checker = 0 WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND status != 'over' ");

        /** Update game_running **/

        $sql_update4 = mysql_query("UPDATE game_running SET melded_count = 0 , player_won = 0, wrong_melders = '' WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1 ");


        

         if($sql_update4){
             echo "ok";
         }


        
    }catch(Exception $e){
        echo $e->getMessage();
    }

}

?>