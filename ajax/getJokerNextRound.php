<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "get-joker"){

	try{

		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];

		$sqlGetJoker = mysql_query("SELECT joker_selected FROM game_running WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1 ");
		$row = mysql_fetch_assoc($sqlGetJoker);

		$jokerCard = $row['joker_selected'];

		echo $jokerCard;



		
	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>