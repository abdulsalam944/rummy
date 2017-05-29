<?php
include("../config.php");
ob_start();
$files_and_value= $_POST['field'];
$where = ' session_key = "'.$_POST['room'].'" and user_id = "'.$_POST['player'].'" ';
$sql = 'select '.$files_and_value.' from player_gamedata  where '.$where;
$qr = mysql_query($sql);
$arr = mysql_fetch_assoc($qr);
echo $arr[$files_and_value];
?>