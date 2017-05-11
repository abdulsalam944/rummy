<?php include("../config.php"); ?>

<?php

      if(isset($_POST['action']) && $_POST['action'] == "fetch_count"){

      $jsonArr = array();
      $playercount=0;
               

      $sqlPoints = "SELECT * FROM room";
                $sqlNamePoints = mysql_query($sqlPoints);
                $playerarray=array();
                $j = 0;
                    
       while ($sqlNamePoints_value = mysql_fetch_array($sqlNamePoints)) { 

           $gameId=$sqlNamePoints_value['id'];

           $sqlplayer = "SELECT * FROM players WHERE game_id='$gameId' and status!='end'";
                $sqltotalplayer = mysql_query($sqlplayer);
                 while ($sqltotalplayer_value = mysql_fetch_array($sqltotalplayer)) {
                  $playerarray= explode(',', $sqltotalplayer_value['players']);
                    
                    $playercount += count($playerarray);
                     
                 }

          $jsonArr[$j]['gameId'] = $gameId;
          $jsonArr[$j]['count'] = $playercount;

         
           $playercount = 0;  
           $j++;
 
                                     
       } 

        echo json_encode($jsonArr);

       
            

   }                               

?>
