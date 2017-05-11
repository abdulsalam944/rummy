<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "room-available"){

	try{

		$roomId = $_POST['roomId'];
		$userId = $_POST['userId'];
		//  echo $userId;exit; 


		$sqlCheck = mysql_query("SELECT * FROM room_tables WHERE game_id = '".$roomId."' AND status != 'started' ANd status != 'full' AND status != 'end' ORDER BY id DESC LIMIT 1");

		$numRows = mysql_num_rows($sqlCheck);

		if($numRows > 0){

			$row = mysql_fetch_assoc($sqlCheck);

			$sessionKey = $row['session_key'];

			$sqlsameusercheck = mysql_query("SELECT * FROM players WHERE session_key = '".$sessionKey."'");

			$allplayers = mysql_fetch_assoc($sqlsameusercheck);


			$users=explode(',',$allplayers['players']); 

			if (in_array($userId, $users))
			{
				echo 'yes';exit;
			}
			echo $sessionKey;

		}else{
			echo "no";
		}

	}catch(Exception $e){
		echo $e->getMessage();
	}

}
/*
 * if($.trim(sessionKey) != "yes"){
                                    alert('You are already in game');
                                }
                                else{
 */

?>