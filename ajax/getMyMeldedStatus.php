<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "get-my-melded-status"){

    try{

        $roomId = $_POST['roomId'];
        $sessionKey = $_POST['sessionKey'];
        $player = $_POST['player'];
      

        $sqlGet = mysql_query("SELECT melded FROM player_gamedata WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND user_id = ".$player." LIMIT 1");

        $row = mysql_fetch_assoc($sqlGet);

        $melded = $row['melded'];
      

        if($sqlGet){
          echo $melded;
        }



        
    }catch(Exception $e){
        echo $e->getMessage();
    }

}

?>