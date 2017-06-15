<?php
include("../config.php");
$gameSession = $_POST['gameSession'];
$player = $_POST['playerId'];
$query = mysql_query('select scoreboard_status, status from player_gamedata where session_key = "'.$gameSession.'" and user_id = "'.$player.'" ');
$arr = mysql_fetch_assoc($query);
echo json_encode($arr);
?>