<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "update-user-scores"){

    try{

        $roomId = $_POST['roomId'];
        $sessionKey = $_POST['sessionKey'];
      
        /** UPDATE PLAYER GAMEDATA **/
        $sql_update3 = mysql_query("UPDATE player_gamedata SET points = 0 WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' ");

         
         if($sql_update3){
             echo "score updated";
         }


        
    }catch(Exception $e){
        echo $e->getMessage();
    }

}

?>