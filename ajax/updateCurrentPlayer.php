<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "current-player"){

    try{

        $roomId = $_POST['roomId'];
        $playerId = $_POST['player'];
        $sessionKey = $_POST['sessionKey'];


        /* Update current player */

        $sqlUpdate = mysql_query("UPDATE game_running SET current_player = ".$playerId." WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");

        


        if($sqlUpdate){
            echo "ok";
        }

        
    }catch(Exception $e){
        echo $e->getMessage();
    }

}

?>