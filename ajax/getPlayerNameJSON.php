<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "get-player-name-json"){

	try{

		$arr = array();

		$playerId = $_POST['player'];

		$sqlCheck = mysql_query("SELECT * FROM users WHERE id = '".$playerId."' LIMIT 1");
		$numRows = mysql_num_rows($sqlCheck);

		if($numRows > 0){
			
			$row = mysql_fetch_assoc($sqlCheck);
			$id = $row['id'];
			$name = $row['username'];

			$arr['id'] = $id;
			$arr['name'] = $name;

			echo json_encode($arr);
		}

	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>