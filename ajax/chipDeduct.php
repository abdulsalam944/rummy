<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "chip-deduct"){

	try{

		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];
		$user = $_POST['user'];
		$chip = $_POST['chip'];
		$rejoin = $_POST['rejoin'];


		$date = date("Y-m-d");
		$extraDeduction = 0.00;

		$sqlCheckMyWallet = mysql_query("SELECT balance_chips, refer_amount, redeemable_amount FROM real_wallet WHERE user_id = '".$user."' ORDER BY id DESC LIMIT 1");

		if($sqlCheckMyWallet){

			$row = mysql_fetch_assoc($sqlCheckMyWallet);

			$balance_chips = $row['balance_chips'];
			$refer_amount = $row['refer_amount'];
			$redeemable_amount = $row['redeemable_amount'];

			if($refer_amount >= $chip){
				$refer_amount = $refer_amount - $chip;
			}else if($refer_amount < $chip && $refer_amount != 0.00){
				$extraDeduction = $chip - $refer_amount;
				$refer_amount = 0;

				if($balance_chips <= $redeemable_amount){

					$redeemable_amount = $redeemable_amount - $chip;

				}
			}


			$balance_chips = $balance_chips - $chip;

			$sqlCheckUserExist = mysql_query("SELECT id FROM real_wallet WHERE session_key = '".$sessionKey."' AND game_id = '".$roomId."' AND user_id = ".$user." LIMIT 1");

			$numrows = mysql_num_rows($sqlCheckUserExist);

			if($numrows == 0){

				if($rejoin == 0){


					$sqlInsert = mysql_query("INSERT INTO real_wallet VALUES (null, '".$user."', '', '', '', '".$chip."', '', '', '', '".$balance_chips."', '', 1, '', '".$date."', '".$roomId."', '".$sessionKey."', '', '', '".$refer_amount."', '".$redeemable_amount."', 'user','')");

				}
			
				if($sqlInsert){
					echo "DONE============================";
				}



			}else{
				if($rejoin == 1){

					$sqlUpdateMyDataRejoin = mysql_query("UPDATE real_wallet SET game_result = 'lost' WHERE game_id = '".$roomId."' AND session_key = '".$sessionKey."' AND user_id = '".$user."' ORDER BY id DESC LIMIT 1");

					if($sqlUpdateMyDataRejoin){

						$sqlInsert = mysql_query("INSERT INTO real_wallet VALUES (null, '".$user."', 'rejoin', '', '', '".$chip."', '', '', '', '".$balance_chips."', '', 1, '', '".$date."', '".$roomId."', '".$sessionKey."', '', '', '".$refer_amount."', '".$redeemable_amount."', 'user','')");

						if($sqlInsert){
							echo "DONE============================";
						}


					}

					

				}
			}

		}
		
		

	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>