<?php
include("../config.php");
if( isset($_POST['sessionId']) && $_POST['sessionId']!=""){

	$query = mysql_query('select game_type, (select players from room where id = game_running.game_id ) as gamePlayersCookie from game_running where session_key = "'.$_POST['sessionId'].'"  ');
	$arr = mysql_fetch_assoc($query);
	$data = array();
	$data['type'] = $arr['game_type'];
	$data['gamePlayersCookie'] = $arr['gamePlayersCookie'];
	echo json_encode($data);
}
?>