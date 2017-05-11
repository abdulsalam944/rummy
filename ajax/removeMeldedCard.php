<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "remove-meld-card"){

	try{

		$roomId = $_POST['roomId'];
		$playerId = $_POST['player'];
		$sessionKey = $_POST['sessionKey'];

		$meldedGroupNos = $_POST['meldedGroupNos'];


		/*  ============== Delete Melded Group from Gamedata  ========== */



		$sqlUpdateMeldCards = mysql_query("UPDATE player_gamedata SET melded_group_".$meldedGroupNos." = '' WHERE game_id = ".$roomId." AND session_key = '".$sessionKey."' AND user_id = ".$playerId." LIMIT 1");

		if($sqlUpdateMeldCards){
			echo "ok";
		}
		

	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>