<?php

include("../config.php");

if(isset($_POST['action']) && $_POST['action'] == "id-generate"){

    try{

        $numbers = NULL;
        $sql = mysql_query("SELECT * FROM room_tables order by id desc");
        $row = mysql_fetch_assoc($sql);
        if(empty($row)){
            $number=1;
            $numbers=str_pad($number, 6, '0', STR_PAD_LEFT); 
        }else{
            $len=strlen($row['session_key']);
            if($len>6){
                $nowlength=($len+1);
                
            }
            else{
               $nowlength=6; 
            }

            $number= $row['session_key'] +1; 
            $numbers=str_pad($number, $nowlength, '0', STR_PAD_LEFT);
        }
                    
            echo $numbers;

        }catch(Exception $e){
        
            echo $e->getMessage();
        }

}    


?>