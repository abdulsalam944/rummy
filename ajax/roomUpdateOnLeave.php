<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "room-update"){

	$roomId = $_POST['roomId'];

	$sql = mysql_query('UPDATE room SET created = "N", status = "open", game_start_counter = 0 WHERE id = "'.$roomId.'" LIMIT 1');


	if($sql){

		echo "ok";

	}

}

?>