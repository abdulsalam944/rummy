<?php

include("../config.php");

$data =  array();

$sql = mysql_query('select user_id, discarded, points from player_gamedata where session_key = "'.$_POST['room'].'" ');

while($arr = mysql_fetch_assoc($sql)){
	$tmp = array();
	$tmp['user'] = $arr['user_id'];
	$tmp['discarded_cards'] = explode(',',$arr['discarded']);
	$tmp['points'] = $arr['points'];
	array_push($data, $tmp);
}
echo json_encode($data);

?>