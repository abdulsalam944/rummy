<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "remove-gamedata"){

	try{

		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];

		$sqlRemove = mysql_query("DELETE FROM player_gamedata WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' ");
		$sqlRemove2 = mysql_query("DELETE FROM game_running WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");
		$sqlRemove3 = mysql_query("DELETE FROM user_connection WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' ");

		echo "ok";
		
	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>