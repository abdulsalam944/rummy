<?php 
	include("../config.php");

	$resp = array();

	$room = $_POST['room'];
	$player = $_POST['player'];

	$sql = " select cards_in_hand, group_1, group_2, group_3, group_4, group_5, group_6 from player_gamedata where session_key = '".$room."' and user_id = '".$player."' ";

	$query = mysql_query($sql);

	if(mysql_num_rows($query)==1){

		$resp['code'] = 1;

		$arr = mysql_fetch_array($query);

		if(
			$arr['group_1']!="" &&
			$arr['group_2']!="" &&
			$arr['group_3']!="" &&
			$arr['group_4']!="" &&
			$arr['group_5']!="" &&
			$arr['group_6']!=""
		){

			$resp['hand_type'] = "group";
			$resp['groups'] = array(
				explode(',', $arr['group_1']),
				explode(',', $arr['group_2']),
				explode(',', $arr['group_3']),
				explode(',', $arr['group_4']),
				explode(',', $arr['group_5']),
				explode(',', $arr['group_6'])
			);
			$resp['msg'] = "Cards found in group";

		}else{

			$resp['hand_type'] = "in_hand";
			$resp['cards_in_hand'] = explode(',', $arr['cards_in_hand']);
			$resp['msg'] = "Not found in hand";

		}

	}else{
		$resp['code'] = 0;
		$resp['msg'] = "Not found in table";
	}

	echo json_encode($resp);

?>