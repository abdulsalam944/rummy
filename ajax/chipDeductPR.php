<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "chip-deduct"){

	try{

		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];
		$user = $_POST['user'];
		$date = date("Y-m-d");
		
		$sqlCheckMyWallet = mysql_query("SELECT balance_chips, refer_amount, redeemable_amount FROM real_wallet WHERE user_id = '".$user."' ORDER BY id DESC LIMIT 1");

		if($sqlCheckMyWallet){

			$row = mysql_fetch_assoc($sqlCheckMyWallet);

			$balance_chips = $row['balance_chips'];
			$refer_amount = $row['refer_amount'];
			$redeemable_amount = $row['redeemable_amount'];

			$sqlCheckUserExist = mysql_query("SELECT id FROM real_wallet WHERE session_key = '".$sessionKey."' AND game_id = '".$roomId."' AND user_id = ".$user." LIMIT 1");

			$numrows = mysql_num_rows($sqlCheckUserExist);

			if($numrows == 0){

				$sqlInsert = mysql_query("INSERT INTO real_wallet VALUES (null, '".$user."', '', '', '', '', '', '', '', '".$balance_chips."', '', 1, '', '".$date."', '".$roomId."', '".$sessionKey."', '', '', '".$refer_amount."', '".$redeemable_amount."', 'user')");
		
				if($sqlInsert){
					echo "DONE============================";
				}

			}

			

			

		}
		
		

	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>