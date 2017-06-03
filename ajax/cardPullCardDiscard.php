<?php include("../config.php"); 

	$files_and_value= $_POST['field'].' = '.$_POST['value'];
	$where = ' session_key = "'.$_POST['room'].'" and user_id = "'.$_POST['player'].'" ';
	$sql = 'update player_gamedata set '.$files_and_value.' where '.$where;
	$qr = mysql_query($sql);
	if($qr){
		echo 1;
	}else{
		echo 0;
	}

	if($_POST['field']=="card_pull" && $_POST['value']==1){
		mysql_query('update game_running set card_pulled = "'.$_POST['cardPulled'].'" where session_key = "'.$_POST['room'].'" ');
	}

?>