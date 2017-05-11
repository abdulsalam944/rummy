<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "check-room-status"){

	try{

		

		$roomId = $_POST['roomId'];
		$player = $_POST['player'];
		$sessionKey = $_POST['sessionKey'];

		$jsonArr = array();
		

		$sqlCheckRoomStatus = mysql_query("SELECT status FROM room_tables WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");

		$row = mysql_fetch_assoc($sqlCheckRoomStatus);
		$status = $row['status'];

		//if($status != "started"){
			
			/* remove player from players table */

			$sqlCheck = mysql_query("SELECT * FROM players WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");
			
		
			$players = '';
			$playerExist = 1;

			$row = mysql_fetch_assoc($sqlCheck);
			
			$id = $row['id'];
			$game_id = $row['game_id'];
			$players = $row['players'];

		
			$players = explode(",", $players);

			for($i = 0; $i < count($players); $i++){

				if (($key = array_search($player, $players)) !== false) {
    			  unset($players[$key]);
			    }

			}

			$playerCount = count($players); /* player count */

			$players = implode(",", $players);
			$players = rtrim($players, ",");





			$sqlPlayerUpdate = mysql_query("UPDATE players SET players = '".$players."' WHERE game_id = '".$game_id."' AND session_key = '".$sessionKey."' LIMIT 1");

		
			if($sqlPlayerUpdate){

				$jsonArr['playerCount'] = $playerCount;
				$jsonArr['roomStatus'] = $status;
				echo json_encode($jsonArr);
			}

		//}


	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>