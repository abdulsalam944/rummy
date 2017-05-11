<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "get-players"){

	try{

		$player = $_POST['player'];
		

		$arr = array();
		
			$sqlGetMyName = mysql_query("SELECT id, username FROM users WHERE id = '".$player."' LIMIT 1");
			
			$numRows = mysql_num_rows($sqlGetMyName);

			if($numRows > 0){
				
				$row = mysql_fetch_assoc($sqlGetMyName);
				$id = $row['id'];
				$name = $row['username'];

				$arr['id'] = $id;
				$arr['name'] = $name;


				echo json_encode($arr);

			}
	
		

	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>