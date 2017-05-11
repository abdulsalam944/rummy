<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "check-wrong-melder"){

	try{

		$roomId = $_POST['roomId'];
		$player = $_POST['player'];
		$sessionKey = $_POST['sessionKey'];

		$flag = 0;
		

		$jsonArr = array();
		
		// check if row exist or not

		$sqlCheck = mysql_query("SELECT wrong_melders FROM game_running WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");
		$row = mysql_fetch_assoc($sqlCheck);
				
				
		$wrongMelders = $row['wrong_melders'];

		if($wrongMelders != ""){
				$wrongMelders = explode(",", $wrongMelders);
		
				foreach ($wrongMelders as $wrongMelder) {
					if($player == $wrongMelder){
						$flag = 1;
						break;
					}
				}
		
		}


	
		
		echo $flag;
	


	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>