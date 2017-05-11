<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "update-deal-and-drop"){

	try{

		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];
		$user = $_POST['user'];
		$value = $_POST['value'];

		if($value == "dealmeout"){
			$sqlUpdate = mysql_query("UPDATE player_gamedata SET deal_me_out = 1, drop_and_go = 0 WHERE session_key = '".$sessionKey."' AND game_id = '".$roomId."' AND user_id = ".$user." LIMIT 1 ");
		
		}else if($value == "dropandgo"){
			$sqlUpdate = mysql_query("UPDATE player_gamedata SET deal_me_out = 0, drop_and_go = 1 WHERE session_key = '".$sessionKey."' AND game_id = '".$roomId."' AND user_id = ".$user." LIMIT 1 ");
		}


		if($sqlUpdate){
			echo "ok  =====  deal and dropgo updated ==========";
		}
		
		

	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>