<?php

include("../config.php");

$sql = mysql_query('select user_id from user_connection where session_key = "'.$_POST['room'].'" and connection_id = "'.$_POST['user'].'"');

$data = array('SQL'=>'select user_id from user_connection where session_key = "'.$_POST['room'].'" and connection_id = "'.$_POST['user'].'"');

if(mysql_num_rows($sql) > 0){
	$arr = mysql_fetch_assoc($sql);
	$data['res'] = 1;
	$data['msg'] = "This user is from your room.";
	$data['data'] = $arr['user_id'];
}else{
	$data['res'] = 0;
	$data['msg'] = "This user not in your room.";
}

echo json_encode($data);

?>