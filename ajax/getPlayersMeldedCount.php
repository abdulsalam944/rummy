<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "get-melded-status"){

	try{

		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];
		$meldedArray = array();


		$sqlGetMeldedCount = mysql_query("SELECT melded FROM player_gamedata WHERE status != 'over' AND melded = 'Y' AND game_id = '".$roomId."' AND session_key = '".$sessionKey."' ");

		$numrows = mysql_num_rows($sqlGetMeldedCount);
		echo $numrows;

		

	}catch(Exception $e){
		echo $e->getMessage();
	}	
}



?>		