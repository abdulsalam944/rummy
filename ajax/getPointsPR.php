<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "get-points"){

	try{

		$roomId = $_POST['roomId'];
		$player = $_POST['player'];
		$sessionKey = $_POST['sessionKey'];
		

		/* Get points */
		
		 $sqlGetMyScore = mysql_query("SELECT chips_taken FROM player_gamedata WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND user_id = '".$player."' LIMIT 1");

		 $row1 = mysql_fetch_assoc($sqlGetMyScore);


		 $chips_taken = $row1['chips_taken'];


		 echo $chips_taken;


	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>