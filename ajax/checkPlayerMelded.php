<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "check-player-melded"){

    try{

        $roomId = $_POST['roomId'];
        $sessionKey = $_POST['sessionKey'];
       
      

    $sqlGet = mysql_query("SELECT melded_count FROM game_running WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1 ");
    $row = mysql_fetch_assoc($sqlGet);

    $count = $row['melded_count'];
    


    if($sqlGet){
        echo $count;
    }



        
    }catch(Exception $e){
        echo $e->getMessage();
    }

}

?>