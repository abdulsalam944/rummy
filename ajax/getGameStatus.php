<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "get-status"){

	try{

		$roomId = $_POST['roomId'];
	

		$sqlGetStatus = mysql_query("SELECT status FROM room WHERE id = '".$roomId."' LIMIT 1");

		$row = mysql_fetch_assoc($sqlGetStatus);

		$gameStatus = $row['status'];

		echo $gameStatus;


	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>