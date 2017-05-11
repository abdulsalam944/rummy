<?php include("../config.php");  ob_start(); ?>

<?php

    
        //// Pritam for total table and total player.////
                $sqltotaltable = mysql_query("SELECT * FROM room_tables WHERE  status !='end'");
                $totaltablenumber= mysql_num_rows($sqltotaltable);
                $playerarray=array();
                    $playercount=0;
                
                while($totaltablearray = mysql_fetch_assoc($sqltotaltable)){
                  
                       $table=$totaltablearray['session_key'];
                  
                    $sqltotalplayer = mysql_query("SELECT * FROM players WHERE  session_key ='$table'"); 
                  
                    $sqltotalplayerarray = mysql_fetch_assoc($sqltotalplayer); 
                 
                    $playerarray= explode(',', $sqltotalplayerarray['players']);
                    
                    $playercount += count($playerarray);
                    
                }
                
               //// Pritam for total table and total player.////
               
            

?>
<li><?php echo @$playercount;?> Players</li>
<li><?php echo @$totaltablenumber;?> TABLES</li>