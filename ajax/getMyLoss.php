<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "get-my-loss"){

	try{

		$roomId = $_POST['roomId'];
		$player = $_POST['player'];
		$sessionKey = $_POST['sessionKey'];

		$jsonArr = array();
		

		/* Get points */
		
		 $sqlGetMyScore = mysql_query("SELECT lost_chips, balance_chips FROM real_wallet WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND user_id = '".$player."' ORDER BY id DESC LIMIT 1");

		 $row1 = mysql_fetch_assoc($sqlGetMyScore);


		 $lost_chips = $row1['lost_chips'];
		 $balance_chips = $row1['balance_chips'];

		 $jsonArr['lost_chips'] = $lost_chips;
		 $jsonArr['balance_chips'] = $balance_chips;


		 echo json_encode($jsonArr);


	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>