<?php

include("../config.php");

	echo $sql = "select cards_in_hand, group_1, group_2, group_3, group_4, group_5, group_6 from player_gamedata where session_key = '".$_POST['room']."' and user_id = '".$_POST['player']."'";

	$query = mysql_query($sql);
	if(mysql_num_rows($query)>0){
		$arr = mysql_fetch_assoc($query);

		$cards = explode(',',$arr['cards_in_hand']);
		print_r($cards);
		$newCard = array();
		foreach($cards as $c){
			if($c!=$_POST['card']){
				array_push($newCard, $c);
			}
		} 
		print_r($newCard);


		//groupp1
		if($arr['group_1']){
			$cards = explode(',',$arr['group_1']);
			print_r($cards);
			$group_1 = array();
			foreach($cards as $c){
				if($c!=$_POST['card']){
					array_push($group_1, $c);
				}
			} 
			$group_1 = implode(',', $group_1);
		}else{
			$group_1 = "";
		}

		//groupp2
		if($arr['group_2']){
			$cards = explode(',',$arr['group_2']);
			print_r($cards);
			$group_2 = array();
			foreach($cards as $c){
				if($c!=$_POST['card']){
					array_push($group_2, $c);
				}
			}
			$group_2 = implode(',', $group_2); 
		}else{
			$group_2 = "";
		}

		//groupp1
		if($arr['group_3']){
			$cards = explode(',',$arr['group_3']);
			print_r($cards);
			$group_3 = array();
			foreach($cards as $c){
				if($c!=$_POST['card']){
					array_push($group_3, $c);
				}
			} 
			$group_3 = implode(',', $group_3);
		}else{
			$group_3 = "";
		}

		//groupp1
		if($arr['group_4']){
			$cards = explode(',',$arr['group_4']);
			print_r($cards);
			$group_4 = array();
			foreach($cards as $c){
				if($c!=$_POST['card']){
					array_push($group_4, $c);
				}
			} 
			$group_4 = implode(',', $group_4);
		}else{
			$group_4 = "";
		}

		//groupp1
		if($arr['group_5']){
			$cards = explode(',',$arr['group_5']);
			print_r($cards);
			$group_5 = array();
			foreach($cards as $c){
				if($c!=$_POST['card']){
					array_push($group_5, $c);
				}
			}
			$group_5 = implode(',', $group_5);
		}else{
			$group_5 = "";
		}

		//groupp1
		if($arr['group_6']){
			$cards = explode(',',$arr['group_6']);
			print_r($cards);
			$group_6 = array();
			foreach($cards as $c){
				if($c!=$_POST['card']){
					array_push($group_6, $c);
				}
			} 
			$group_6 = implode(',', $group_6);
		}else{
			$group_6 = "";
		}


		mysql_query("update player_gamedata set 
				cards_in_hand = '".implode(',',$newCard)."',
				
				group_1 = '".$group_1."',
				group_2 = '".$group_2."',
				group_3 = '".$group_3."',
				group_4 = '".$group_4."',
				group_5 = '".$group_5."',
				group_6 = '".$group_6."'

				where  session_key = '".$_POST['room']."' and user_id = '".$_POST['player']."' ");

	}

?>