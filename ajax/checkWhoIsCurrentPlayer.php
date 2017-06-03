<?php
include("../config.php");
if(isset($_POST) && $_POST['room']!=""){
	$query  = mysql_query('select current_player from game_running where session_key = "'.$_POST['room'].'" ');

	$arr = mysql_fetch_assoc($query);

	if($arr['current_player']){
		echo $arr['current_player'];
	}else{
		echo 0;
	}
}
?>