<?php
include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "update-wrong-melders"){
	try{
		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];

		$sqlPlayerUpdate = mysql_query("update game_running set wrong_melders = '' WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");
				
		if($sqlPlayerUpdate){
			echo "ok";
		}

	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>