<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "update-group"){

	try{

		

		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];
		$player = $_POST['playerId'];
		$group1 = ($_POST['group1']) ? $_POST['group1'] : null;
		$group2 = ($_POST['group2']) ? $_POST['group2'] : null;
		$group3 = ($_POST['group3']) ? $_POST['group3'] : null;
		$group4 = ($_POST['group4']) ? $_POST['group4'] : null;
		$group5 = ($_POST['group5']) ? $_POST['group5'] : null;
		$group6 = ($_POST['group6']) ? $_POST['group6'] : null;


		if(is_array($group1)) $group1 = implode(",", $group1);
		if(is_array($group2)) $group2 = implode(",", $group2);
		if(is_array($group3)) $group3 = implode(",", $group3);
		if(is_array($group4)) $group4 = implode(",", $group4);
		if(is_array($group5)) $group5 = implode(",", $group5);
		if(is_array($group6)) $group6 = implode(",", $group6);
	

		$sql_update_group = mysql_query("UPDATE player_gamedata SET group_1 = '".$group1."', group_2 = '".$group2."', group_3 = '".$group3."', group_4 = '".$group4."', group_5 = '".$group5."', group_6 = '".$group6."' WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND user_id = '".$player."' LIMIT 1");

		if($sql_update_group){
			echo "ok";
		}


	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>