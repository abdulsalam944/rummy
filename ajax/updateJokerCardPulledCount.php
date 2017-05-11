<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "update-joker-pulled"){

	try{

		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];
		

		/* Get score */
		
		 $sqlGetCard = mysql_query("UPDATE game_running SET joker_pulled = 1 WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");

		 if($sqlGetCard){
		 	 echo $count;

		 }

		
		

	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>