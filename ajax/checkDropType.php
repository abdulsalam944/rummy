<?php
include("../config.php");
if(isset($_POST['action']) && $_POST['action'] == "check-drop-type"){
	try{
		$roomId = $_POST['roomId'];
		$player = $_POST['player'];
		$sessionKey = $_POST['sessionKey'];
		$sqlGetDropCount = mysql_query("SELECT drop_checker FROM player_gamedata WHERE  session_key = '".$sessionKey."' AND user_id = '".$player."' LIMIT 1");
		$row = mysql_fetch_assoc($sqlGetDropCount);
		$dropCount = $row['drop_checker'];
		if($sqlGetDropCount){
			echo $dropCount;
		}
	}catch(Exception $e){
		echo $e->getMessage();
	}
}
?>