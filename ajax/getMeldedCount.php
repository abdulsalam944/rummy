<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "get-melded-count"){

	try{

		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];


		$sqlGetMeldedCount = mysql_query("SELECT melded_count FROM game_running WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1 ");

		$row = mysql_fetch_assoc($sqlGetMeldedCount);

		$melded_count = $row['melded_count'];

		if($sqlGetMeldedCount){
			echo $melded_count;
		}

	}catch(Exception $e){
		echo $e->getMessage();
	}	
}



?>		