<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "get-my-cards"){

	try{

		$player = $_POST['player'];
		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];

		$arr = array();

		$sql = mysql_query("SELECT melded_group_1, melded_group_2, melded_group_3, melded_group_4, melded_group_5 FROM player_gamedata WHERE user_id = ".$player." AND game_id = '".$roomId."' AND session_key = '".$sessionKey."' LIMIT 1");


		$row = mysql_fetch_assoc($sql);

		$mg1 = $row['melded_group_1'];
		$mg2 = $row['melded_group_2'];
		$mg3 = $row['melded_group_3'];
		$mg4 = $row['melded_group_4'];
		$mg5 = $row['melded_group_5'];


		if(!empty($mg1)){
			$mg1 = explode(",", $mg1);
		}

		if(!empty($mg2)){
			$mg2 = explode(",", $mg2);
		}

		if(!empty($mg3)){
			$mg3 = explode(",", $mg3);
		}

		if(!empty($mg4)){
			$mg4 = explode(",", $mg4);
		}

		if(!empty($mg5)){
			$mg5 = explode(",", $mg5);
		}

		$arr['g1'] = $mg1;
		$arr['g2'] = $mg2;
		$arr['g3'] = $mg3;
		$arr['g4'] = $mg4;
		$arr['g5'] = $mg5;

			


		echo json_encode($arr);

	}catch(Exception $e){
		echo $e->getMessage();
	}
	


}

?>