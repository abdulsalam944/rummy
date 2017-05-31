<?php

include("../config.php");

	echo $sql = "select cards_in_hand from player_gamedata where session_key = '".$_POST['room']."' and user_id = '".$_POST['player']."'";

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
		mysql_query("update player_gamedata set cards_in_hand = '".implode(',',$newCard)."' where  session_key = '".$_POST['room']."' and user_id = '".$_POST['player']."' ");

	}

?>