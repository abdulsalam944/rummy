<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "get-wrong-melders"){

	try{

		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];
		

		$jsonArr = array();
		
		// check if row exist or not

		$sqlCheck = mysql_query("SELECT wrong_melders FROM game_running WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");
		$row = mysql_fetch_assoc($sqlCheck);
				
				
		$wrongMelders = $row['wrong_melders'];


		$wrongMelders = explode(",", $wrongMelders);
		
		$jsonArr['wrongMelders'] = $wrongMelders;
		
		
		echo json_encode($jsonArr);
	


	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>