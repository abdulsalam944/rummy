<?php

include("../config.php");

if(isset($_POST['ajax']) && $_POST['ajax'] == "TRUE"){

	try{
             /********** last login **************/
            $user_id =$_SESSION['user_id']; 
                    $login_id =$_SESSION['login_id'];                   
                    $logout_date =date('Y-m-d H:i:s');                   
                    $status ="0";
                $sqlUpdate = mysql_query("UPDATE userlogin_details SET logout_date = '".$logout_date."',status = '".$status."' WHERE id = '".$login_id."' ");
	 $sqlUpdate = mysql_query("UPDATE userlogin_details SET status = '".$status."' WHERE user_id = '".$user_id."' ");
	
                     /**********end last login **************/
		session_destroy();
		echo json_encode(array('SUCCESS'=>'YES'));

	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>