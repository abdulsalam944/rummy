<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "update-group-drag"){

	try{

		

		$roomId = $_POST['roomId'];
		$player = $_POST['playerId'];
		$sessionKey = $_POST['sessionKey'];

		$groupRemoval = $_POST['groupRemoval'];
		$groupAdded = $_POST['groupAdded'];

		$groupRemNos = $_POST['groupRemNos'];
		$groupAddNos = $_POST['groupAddNos'];

		$groupRemoval = implode(",", $groupRemoval);
		$groupAdded = implode(",", $groupAdded);


	
		$sql_update_group = mysql_query("UPDATE player_gamedata SET group_".$groupRemNos." = '".$groupRemoval."', group_".$groupAddNos." = '".$groupAdded."' WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND user_id = '".$player."' LIMIT 1");

		if($sql_update_group){
			echo "ok";
		}


	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>