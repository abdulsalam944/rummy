<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "remove-card-discard"){

	try{

		

		$roomId = $_POST['roomId'];
		$player = $_POST['playerId'];
		$sessionKey = $_POST['sessionKey'];
		$netSpeed = $_POST['netSpeed'];

		
		$cardGroup = $_POST['cardGroup'];
		$groupNos = $_POST['groupNos'];
		

		$cardGroup = implode(",", $cardGroup);

		// if($netSpeed <= 1){

		// 	$sqlGetHandChecker = mysql_query("SELECT hand_checker FROM player_gamedata WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND user_id = '".$player."' LIMIT 1");

		// 	$sqlGetRow = mysql_fetch_assoc($sqlGetHandChecker);
		// 	$handChecker = $sqlGetRow['hand_checker'];

		// 	if($handChecker < 2){
		// 		$handChecker++;
		// 	}else if($handChecker >= 2){
		// 		$handChecker = 0;
		// 	}

		// }else{
		// 	$handChecker = 0;
		// }

		

		$sql_update_group = mysql_query("UPDATE player_gamedata SET group_".$groupNos." = '".$cardGroup."', drop_checker = 1 WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND user_id = '".$player."' LIMIT 1");

		if($sql_update_group){
			echo "ok";
		}


	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>