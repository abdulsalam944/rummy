<?php

include("../config.php");

if(isset($_POST['ajax']) && $_POST['ajax'] == "TRUE"){

	try{

		$subscriber_code = $_POST['username'];
        $password = md5($_POST['password']);

		$sql = mysql_query("select * from users where username='$subscriber_code' and password='$password' and status='1'");
		$row = mysql_fetch_array($sql);
		if(!empty($row)){
			// session_start();
                    /********** last login **************/
                    $user_id =$row['id'];
                    $ip_address = $_SERVER['REMOTE_ADDR'];
                    $login_date =date('Y-m-d H:i:s');
                     $logout_date ="";
                    $status ="1";
                  $sqlInsert = mysql_query("INSERT INTO userlogin_details VALUES (null, ".$user_id.", '".$ip_address."', '".$login_date."', '".$logout_date."', '".$status."')");
		
		
		if($sqlInsert){
			
                        $lastloginid = mysql_insert_id();
                         $_SESSION['login_id'] =  $lastloginid;
		}
		
                 
                     /**********end last login **************/
			$_SESSION['user_id'] =  $row['id'];
                       
			 echo json_encode(array('SUCCESS'=>'YES'));
		}else{
			echo json_encode(array('SUCCESS'=>'NO')); 
		}


	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>