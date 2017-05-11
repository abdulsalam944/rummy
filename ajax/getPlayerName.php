<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "get-player-name"){

	try{

		$playerId = $_POST['player'];

		$sqlCheck = mysql_query("SELECT username FROM users WHERE id = '".$playerId."' LIMIT 1");
		$numRows = mysql_num_rows($sqlCheck);

		if($numRows > 0){
			
			$row = mysql_fetch_assoc($sqlCheck);
			$name = $row['username'];

			echo $name;
		}

	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>