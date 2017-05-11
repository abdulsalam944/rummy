<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "update-player-turn"){

    try{

        $roomId = $_POST['roomId'];
        $playerTurn = $_POST['nextPlayer'];
        $sessionKey = $_POST['sessionKey'];


        $sql_update3 = mysql_query("UPDATE game_running SET toss_winner = ".$playerTurn." WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");

         if($sql_update3){
             echo "ok";
         }


        
    }catch(Exception $e){
        echo $e->getMessage();
    }

}

?>