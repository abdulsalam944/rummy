<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "get-players"){

	try{

		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];

		$sqlCheck = mysql_query("SELECT players FROM players WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");
		$numRows = mysql_num_rows($sqlCheck);

		if($numRows > 0){
			
			$row = mysql_fetch_assoc($sqlCheck);
			$players = $row['players'];



			$players = explode(",", $players);

			echo json_encode($players);
		
		}

	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>