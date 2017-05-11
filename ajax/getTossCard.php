<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "get-toss-cards"){

	try{

		$roomId = $_POST['roomId'];

		// check for previously inserted toss of same room

		$sqlGetToss = mysql_query("SELECT * FROM toss WHERE game_id = '".$roomId."'");
		
		if($sqlGetToss){
			while($row = mysql_fetch_assoc($sqlGetToss)){

				$card = $row['card'];


			}
		}

	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>