<?php
include("../config.php");
ob_start();
$files_and_value= $_POST['field'];
$where = ' session_key = "'.$_POST['room'].'" and user_id = "'.$_POST['player'].'" ';

$sql = "select card_pull from player_gamedata where ".$where;
$query = mysql_query($sql);

$arr = mysql_fetch_array($query);
if($arr['card_pull']==1){

	$cardQuery = 'select card_pulled from game_running where session_key = "'.$_POST['room'].'" and current_player  = "'.$_POST['player'].'" ' ;
	$sqlCard = mysql_query($cardQuery);
	$arrCard = mysql_fetch_array($sqlCard);


	if(mysql_num_rows($sqlCard)>0){
		echo $arrCard['card_pulled'];
	}else{
		echo 0;
	}

}else{
	echo 0;
}

?>