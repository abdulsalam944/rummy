<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "room-update"){

	$roomId = $_POST['roomId'];
	$sessionKey = $_POST['sessionKey'];

	$sql = mysql_query('INSERT INTO room_tables VALUES(null, "'.$roomId.'", "'.$sessionKey.'", 0, "registering")');


	if($sql){

		echo "ok";

	}

}

?>