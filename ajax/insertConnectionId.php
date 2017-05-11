<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "connection-id-update"){

	try{

		$roomId = $_POST['roomId'];
		$connectionId = $_POST['connectionId'];
		$player = $_POST['player'];
		$sessionKey = $_POST['sessionKey'];

		$sqlInsert = mysql_query("INSERT INTO user_connection VALUES (null, ".$player.", '".$connectionId."', '".$roomId."', '".$sessionKey."')");
		
		
		if($sqlInsert){
			echo "ok";
		}
		
		

	}catch(Exception $e){
		echo $e->getMessage();
		// mysql_query("ROLLBACK");
	}

}

?>