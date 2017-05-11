<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "get-players-out"){

    try{

        $roomId = $_POST['roomId'];
        $sessionKey = $_POST['sessionKey'];
        $jsonArray = array();   
        $playersWhoLost = array();
        $playersWhoLost1 = "";
        
        /* Update current player */

        $sqlGet = mysql_query("SELECT user_id FROM player_gamedata WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND status = 'over' ");
        $numrows = mysql_num_rows($sqlGet);

        if($numrows > 0){

        while($row = mysql_fetch_assoc($sqlGet)){
            $playersWhoLost1 .= $row['user_id'].',';
        }

         $playersWhoLost1 = rtrim($playersWhoLost1, ",");
         $playersWhoLost = explode(",", $playersWhoLost1);
       

        }else{
            $playersWhoLost = 'none';
        }  


        if($sqlGet){
           $jsonArray['playersWhoLost'] = $playersWhoLost;
           echo json_encode($jsonArray);
        }



        
    }catch(Exception $e){
        echo $e->getMessage();
    }

}

?>