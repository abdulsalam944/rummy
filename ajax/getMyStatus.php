<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "get-my-status"){

    try{

        $roomId = $_POST['roomId'];
        $player = $_POST['player'];
        $sessionKey = $_POST['sessionKey'];
      

        $sqlGet = mysql_query("SELECT match_status FROM player_gamedata WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND user_id = ".$player." AND status = 'over' LIMIT 1");

        $row = mysql_fetch_assoc($sqlGet);

        $match_status = $row['match_status'];
      

        if($sqlGet){
          echo $match_status;
        }



        
    }catch(Exception $e){
        echo $e->getMessage();
    }

}

?>