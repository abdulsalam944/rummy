<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "get-player-won"){

    try{

        $roomId = $_POST['roomId'];
        $sessionKey = $_POST['sessionKey'];
      

        $sqlGet = mysql_query("SELECT player_won FROM game_running WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");

        $row = mysql_fetch_assoc($sqlGet);

        $playerWon = $row['player_won'];
      

        if($sqlGet){
          echo $playerWon;
        }



        
    }catch(Exception $e){
        echo $e->getMessage();
    }

}

?>