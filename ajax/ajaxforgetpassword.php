<?php

include("../config.php");

if(isset($_POST['ajax']) && $_POST['ajax'] == "TRUE"){

	try{

		$email = $_POST['email'];
        // $password = md5($_POST['password']);
//echo $email;exit;
		$sql = mysql_query("select * from users where email='$email' and status='1'");
		$row = mysql_fetch_array($sql);
		if(!empty($row)){
			$pass = 'RUMMY' . rand(9999, 100000);
			$password = md5($pass);
			$result = mysql_query("UPDATE users SET password='$password' WHERE email='$email'");
			if ($result === TRUE) {
			$email_from = 'info@1strummy.com';
            $email_to = $email;
            $form_name = '1strummy';
            $email_subject = 'Forgot Password';
            $email_body = '<div style="width:675px;margin:0 auto;border:10px solid #ff5a5f;">
            <div style="border: 1px solid #ccc;float: left;font-size: 16px;font-weight: bold;line-height: 22px;margin: 30px;padding: 20px;text-align: center;width: 40%;">
                <a target="_blank" href="#" style="color: #15c;text-decoration: none;"><img src="images/logo_2.png" alt="" /></a>
                
            </div>
            <div style="float:right;padding:20px;width:15%">
                <h3 style="font:Arial,Helvetica,sans-serif;font-size:16px;line-height:18px">Dated</h3>
                <p style="font:Arial,Helvetica,sans-serif;font-size:12px;line-height:0px">' . date('d M, Y') . '</p>
            </div>
            <div style="clear: both;padding: 0;margin: 0;"></div>
            <div style="background: #ccc;height: 1px;width: 100%;"></div>
            <div style="width:93%;margin:0 auto;padding:10px 20px">
                <h1 style="font:Arial,Helvetica,sans-serif;font-size:18px;line-height:26px">
                    Hi,
                </h1>
              <div style="width: 100%;font:Arial,Helvetica,sans-serif;font-size:14px; margin-bottom: 10px;">
                 <div style="width:100%;margin-bottom: 10px;">
                        <div style="width: 45%; float:left; font:Arial,Helvetica,sans-serif;font-size:13px;font-weight:bold;">Your new password is
                        </div>
                        <div style="width: 45%;float: right; font:Arial,Helvetica,sans-serif;font-size:13px;">' . $pass . '</div>
                        <div style="clear: both;padding: 0;margin: 0;"></div>
                    </div>   
                </div>
              
              
              
              
            </div>                  
                
            <div style="margin: 30px;width: 80%;">
                <p style="font:Arial,Helvetica,sans-serif;font-size:14px;">Sincerely,<br/>
                    1strummy</p>
            </div>';
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: $form_name ".'<'."$email_from".'>'."  \r\n";
            $headers .= "Reply-To: $email_to \r\n";

            mail($email_to, $email_subject, $email_body, $headers);
			    echo json_encode(array('SUCCESS'=>'YES','MSG' => 'Please check your email..'));
			} else {
			    echo json_encode(array('SUCCESS'=>'NO','MSG' => 'Error occurred.. '));
			}
			 
		}else{
			echo json_encode(array('SUCCESS'=>'NO','MSG' => 'Please enter proper email')); 
		}


	}catch(Exception $e){
		echo $e->getMessage();
	}

}

?>