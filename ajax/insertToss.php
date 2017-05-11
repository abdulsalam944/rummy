<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "insert-toss"){

	try{

		$roomId = $_POST['roomId'];
		$card = $_POST['card'];
		$player = $_POST['player'];
		$sessionKey = $_POST['sessionKey'];

		// check for previously inserted toss of same room

		// mysql_query("START TRANSACTION");

		$sqlCheck = mysql_query("SELECT id FROM toss WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND player_id = '".$player."' ");

			while($row = mysql_fetch_assoc($sqlCheck)){
				$id = $row['id'];
				$sqlDelete = mysql_query("DELETE FROM toss WHERE id = '".$id."' ");


			}



		$sqlInsertToss = mysql_query("INSERT INTO toss VALUES (null, '".$roomId."', '".$sessionKey."', '".$player."')");

		if($sqlInsertToss){
			echo "ok";
		}



		
		// mysql_query("COMMIT");

	}catch(Exception $e){
		echo $e->getMessage();
		// mysql_query("ROLLBACK");
	}

}

?>