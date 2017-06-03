<?php
include("../config.php");
$gameSession = $_POST['room'];
$user = $_POST['user'];
if($gameSession && $user){

	$data = array();
	$u = mysql_query('select user_id from user_connection where connection_id =  "'.$user.'" and session_key = "'.$gameSession.'" ');
	$d = mysql_fetch_array($u);
	if(mysql_num_rows($u)>0){		
		$data['user_id'] = $d['user_id'];

		$disUsers = mysql_query('select dissconnected_user from game_running where session_key = "'.$gameSession.'" ');
		$disUserArray = mysql_fetch_assoc($disUsers);

		$dUser = explode(',', $disUserArray['dissconnected_user']);

		if(in_array($d['user_id'], $dUser)){
			$data['self_dis'] = 1;
			mysql_query('update room_tables set status = "end" where session_key = "'.$gameSession.'"');
		}else{
			$data['self_dis'] = 0;
		}

	}else{
		$data['self_dis'] = 0;
		$data['user_id'] = 0;

	

	}
}
echo json_encode($data);
?>