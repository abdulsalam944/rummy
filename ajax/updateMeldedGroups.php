<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "update-all-groups"){

	try{

		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];
		$playerId = $_POST['player'];
		$meldedGroup1 = array();
		$meldedGroup2 = array();
		$meldedGroup3 = array();
		$meldedGroup4 = array();
	    $meldedGroup5 = array();
		// $meldedGroup6 = array();

		$meldedGroup1 = ($_POST['meldedGroup1']) ? $_POST['meldedGroup1'] : NULL;
		$meldedGroup2 = ($_POST['meldedGroup2']) ? $_POST['meldedGroup2'] : NULL;
		$meldedGroup3 = ($_POST['meldedGroup3']) ? $_POST['meldedGroup3'] : NULL;
		$meldedGroup4 = ($_POST['meldedGroup4']) ? $_POST['meldedGroup4'] : NULL;
		$meldedGroup5 = ($_POST['meldedGroup5']) ? $_POST['meldedGroup5'] : NULL;
		// $meldedGroup6 = ($_POST['meldedGroup6']) ? $_POST['meldedGroup6'] : NULL; 
		

		if(!empty($meldedGroup1)) $meldedGroup1 = implode(",", $meldedGroup1);
		if(!empty($meldedGroup2)) $meldedGroup2 = implode(",", $meldedGroup2);
		if(!empty($meldedGroup3)) $meldedGroup3 = implode(",", $meldedGroup3);
		if(!empty($meldedGroup4)) $meldedGroup4 = implode(",", $meldedGroup4);
		if(!empty($meldedGroup5)) $meldedGroup5 = implode(",", $meldedGroup5);
		// if(!empty($meldedGroup6)) $meldedGroup6 = implode(",", $meldedGroup6);



		/*  ============== Update player Gamedata  ========== */

$sqlUpdateGroups = mysql_query("UPDATE player_gamedata SET group_1 = '', group_2 = '', group_3 = '', group_4 = '', group_5 = '', group_6 = '', melded_group_1 = '".$meldedGroup1."', melded_group_2 = '".$meldedGroup2."', melded_group_3 = '".$meldedGroup3."', melded_group_4 = '".$meldedGroup4."', melded_group_5 = '".$meldedGroup5."' WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND user_id = ".$playerId." LIMIT 1 ");

		if($sqlUpdateGroups){
			echo "ok";
		}
		

	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>