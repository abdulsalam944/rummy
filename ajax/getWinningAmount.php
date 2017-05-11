<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "get-winning-amount"){

	try{

		$roomId = $_POST['roomId'];
		$sessionKey = $_POST['sessionKey'];
		
		$winningAmount = 0.00;
		$totalChips = 0.00;
		

		
		$sqlCheckBets = mysql_query("SELECT lost_chips FROM real_wallet WHERE session_key = '".$sessionKey."' AND game_id = '".$roomId."' ");

		while($row = mysql_fetch_assoc($sqlCheckBets)){
			$totalChips = $totalChips + $row['lost_chips'];
		}



			/* Calculate Tax */

			// service charge 10% on winning amount

			$serviceCharge = ((10/100) * $totalChips);
			$serviceTax = ((15/100) * $serviceCharge);

			if($totalChips >= 10000){
				$tds = ((30/100) * $totalChips);

			}else{
				$tds = 0.00;
			}



			$winningAmount = floatval($totalChips - ($serviceCharge+$serviceTax+$tds));

			echo $winningAmount;
		
		

	}catch(Exception $e){
		echo $e->getMessage();
	}

}


?>