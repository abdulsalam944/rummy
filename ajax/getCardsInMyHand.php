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
			$arr['group_1']!="" ||
			$arr['group_2']!="" ||
			$arr['group_3']!="" ||
			$arr['group_4']!="" ||
			$arr['group_5']!="" ||
			$arr['group_6']!=""
		){

			$resp['hand_type'] = "group";
			$resp['groups'] = array();

			if($arr['group_1']!=""){
				array_push($resp['groups'], explode(',', $arr['group_1']));
			}
			if($arr['group_2']!=""){
				array_push($resp['groups'], explode(',', $arr['group_2']));
			}
			if($arr['group_3']!=""){
				array_push($resp['groups'], explode(',', $arr['group_3']));
			}
			if($arr['group_4']!=""){
				array_push($resp['groups'], explode(',', $arr['group_4']));
			}
			if($arr['group_5']!=""){
				array_push($resp['groups'], explode(',', $arr['group_5']));
			}
			if($arr['group_6']!=""){
				array_push($resp['groups'], explode(',', $arr['group_6']));
			}	

			
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