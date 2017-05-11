<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "update-melded-count"){

	try{

		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];

		$sqlUpdateStatus = mysql_query("UPDATE game_running SET melded_count = melded_count+1 WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1 ");

		if($sqlUpdateStatus){
			echo "ok";
		}

	}catch(Exception $e){
		echo $e->getMessage();
	}	

}



?>		