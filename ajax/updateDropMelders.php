<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "update-drop-melders"){

	try{

		$roomId = $_POST['roomId'];
		$playerId = $_POST['player'];
		$sessionKey = $_POST['sessionKey'];
		

	

		// check if row exist or not

		$sqlCheck = mysql_query("SELECT drop_melders FROM game_running WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");
		$numRows = mysql_num_rows($sqlCheck);

		$totalMelders = '';
	

		



			// row exists
			    $row = mysql_fetch_assoc($sqlCheck);
				
				
				$wrongMelders = $row['drop_melders'];


				if($wrongMelders != ""){
			
					

					
						$totalMelders = $wrongMelders.','.$playerId; // concat the current player if that player doesn't exist
				

					    $totalMelders = rtrim($totalMelders, ',');

					$sqlPlayerUpdate = mysql_query("UPDATE game_running SET drop_melders = '".$totalMelders."' WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");




				}else{
					$sqlPlayerUpdate = mysql_query("UPDATE game_running SET drop_melders = ".$playerId." WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");
				}


		if($sqlPlayerUpdate){

			echo "ok";

		}

	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>