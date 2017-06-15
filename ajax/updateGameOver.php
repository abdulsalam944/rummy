<?php
	include("../config.php");

	mysql_query("update player_gamedata set status = 'over' where session_key = '".$_POST['room']."' and user_id <> '".$_POST['player']."' ");

?>