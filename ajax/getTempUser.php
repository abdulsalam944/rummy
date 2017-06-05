<?php
include("../config.php");
$sql = "select tmp_players from game_running where session_key = '".$_POST['roomId']."' ";
$query = mysql_query($sql);
$arr = mysql_fetch_assoc($query);
echo $arr['tmp_players'];
?>