<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "update-round"){

	try{

		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];
		
		$sqlUpdateRound = mysql_query("UPDATE game_running SET round = round + 1 WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1 ");

		if($sqlUpdateRound){
			echo "ok";
		}

	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>