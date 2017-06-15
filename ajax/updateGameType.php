<?php

include("../config.php");
if( isset($_POST['roomId']) && $_POST['roomId']!="" && isset($_POST['gameType']) && $_POST['gameType']!="" ){
	echo mysql_query('update game_running set  game_type = "'.$_POST['gameType'].'" where session_key =  "'.$_POST['roomId'].'"  ');
	echo 'update game_running set  game_type = "'.$_POST['gameType'].'" where session_key =  "'.$_POST['roomId'].'"  ';
}


?>