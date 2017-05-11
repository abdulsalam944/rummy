<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "get-toss-winner"){

	try{

		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];

		// check for previously inserted toss of same room

		$sqlGetTossWinner = mysql_query("SELECT toss_winner FROM game_running WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1 ");
		
		if($sqlGetTossWinner){
			$row = mysql_fetch_assoc($sqlGetTossWinner);

				$toss_winner = $row['toss_winner'];

				echo $toss_winner;

			
		}

	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>