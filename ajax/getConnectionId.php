<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "get-connection-id"){

	try{

		$roomId = $_POST['roomId'];
		$player = $_POST['playerId'];
		$sessionKey = $_POST['sessionKey'];

	$sqlCheck = mysql_query("SELECT connection_id FROM user_connection WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");
	$numRows = mysql_num_rows($sqlCheck);

		if($numRows > 0){
			
			$row = mysql_fetch_assoc($sqlCheck);
			$connectionId = $row['connection_id'];

			if($sqlCheck){
				echo $connectionId;
			}
		
		}

	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>