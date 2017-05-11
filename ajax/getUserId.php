<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "get-user-id"){

	try{

		$roomId = $_POST['roomId'];
		$player = $_POST['playerId'];
		$sessionKey = $_POST['sessionKey'];

		$sqlCheckUser = mysql_query("SELECT user_id FROM user_connection WHERE connection_id = '".$player."' AND game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");
	
		$numRows = mysql_num_rows($sqlCheckUser);

		if($numRows > 0){
			
			$row = mysql_fetch_assoc($sqlCheckUser);
			$userId = $row['user_id'];

			if($sqlCheckUser){
				echo $userId;
			}
		
		}

	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>