<?php

include("../config.php");

$sql = 'update game_running set tmp_players = "'.implode(',', $_POST['players']).'" where session_key = "'.$_POST['room'].'" ';
mysql_query($sql);

?>