<?php
include("../config.php");


$ponit = $_POST['points'];
$ponitAdd = $_POST['points'];

if($_POST['gameTypeCookie']=="score"){
	$query = mysql_query('select bet from room where id = (select game_id from game_running where session_key = "'.$_POST['sessionKey'].'" )');
	$arr = mysql_fetch_assoc($query);

	$ponitAdd = floatVal($ponit * $arr['bet']); 
}


echo $query = "update player_gamedata set points = '".$ponit."', total_points = total_points + '".$ponitAdd."' where  session_key = '".$_POST['sessionKey']."' and user_id = '".$_POST['player']."' ";
mysql_query($query);

?>