<?php include("../config.php");  ob_start(); 

	$files_and_value= $_POST['field'].' = '.$_POST['value'];
	$where = ' session_key = "'.$_POST['room'].'" and user_id = "'.$_POST['player'].'" ';
	$sql = 'update player_gamedata set '.$files_and_value.' where '.$where;
	$qr = mysl_query($sql);
	if($qr){
		echo 1;
	}else{
		echo 0;
	}

?>