

<?php include("config.php"); ?>

<?php

    //print_r($_POST);die;

    $gametype = $_POST['gametype'];

    $player_2 = $_POST['player_2'];
    $player_6 = $_POST['player_6'];
    $player_9 = $_POST['player_9'];

    $player_deals2 = $_POST['player_deals2'];
    $player_deals3 = $_POST['player_deals3'];

    //$player_bet = $_POST['player_bet'];
    // $player_l = $_POST['player_l'];
    // $player_m = $_POST['player_m'];
    // $player_h = $_POST['player_h'];


    if($gametype == 101 || $gametype == 201)
    {
        $sql = "SELECT * FROM room WHERE gametype = '".$gametype."' ";
    }
    elseif($gametype == 'x')
    {
        
        if($player_deals2 != "" && $player_deals3 == "")
        {
            $sql = "SELECT * FROM room WHERE 1 AND gametype = 'deals2'";
        }
        elseif($player_deals3 != "" && $player_deals2 == "")
        {
            $sql = "SELECT * FROM room WHERE 1 AND gametype = 'deals3'";
        }
        else
        {
            $sql = "SELECT * FROM room WHERE gametype IN('deals2', 'deals3')";  
        }
        
    }
    elseif($gametype == 'score')
    {
        $sql = "SELECT * FROM room WHERE gametype IN('score')";
    }
    else
    {
        $sql = "SELECT * FROM room WHERE 1";  
    }


    if($player_2 != "")
    {
        $player = $player_2;
    }
    if($player_6 != "")
    {
        $player = $player_6;
    }
    if($player_9 != "")
    {
        $player = $player_9;
    }
    if($player_2 != "" && $player_6 != "" && $player_9 != "")
    {
        $player = $player_2.",".$player_6.",".$player_9;
    }
    if($player_2 != "" && $player_6 != "" && $player_9 == "")
    {
        $player = $player_2.",".$player_6;
    }
    if($player_2 != "" && $player_6 == "" && $player_9 != "")
    {
        $player = $player_2.",".$player_9;
    }
    if($player_2 == "" && $player_6 != "" && $player_9 != "")
    {
        $player = $player_6.",".$player_9;
    }

    if($player != "")
    {
        $sql .= " AND players IN (".$player.")";
    }
    


    // echo $sql;
    // die;

    $sqlName = mysql_query($sql);


?>
<table id="playtype_<?php echo $gametype;?>" width="100%" cellspacing="0" cellpadding="0" class="table-striped">
                                       <thead>     
                                        <tr>
                                            <th>Game Type</th>
                                            <th>Bet</th>
                                            <?php if($gametype == 'score'){
                                                echo '<th>Min Buyin</th>';
                                            } ?>
                                            
                                            <th>Max Player</th>
                                            <th>Total Players</th>
                                                                                   
                                            <th>Join</th>                                        
                                        </tr></thead>
                                                 <tbody>
                                                         <?php while ($sqlName_value = mysql_fetch_array($sqlName)) {

                                                            $minBuying = floatval((float)$sqlName_value['bet'] * 80);
                                                          
                                                           ?>
                                                          
                                                           <tr id="player<?php echo $sqlName_value['id']; ?>">
                                                            <td><?php echo $sqlName_value['gametype']; ?></td>  
                                                            <td><?php echo $sqlName_value['bet']; ?></td>
                                                            <?php if($gametype == 'score'){
                                                                echo '<td>'.$minBuying.'</td>';
                                                            } ?>
                                                            
                                                            <td><?php echo $sqlName_value['players']; ?></td>
                                                            <td class="totalplayercount"></td>
                                                            
                                                            <td>
                                                                <?php

                              if($gametype != 'score'){                                  
           
                            echo  '<a class="join btn btn-success" id="'.$sqlName_value['id'].'" game-players="'.$sqlName_value['players'].'" game-type="'.$sqlName_value['gametype'].'" data-sessionid="'.$sqlName_value['id'].'" game-bet="'.$sqlName_value['bet'].'">Join Game</a>';

                            }else{

                                echo  '<a class="join btn btn-success launch-modal-2" data-target="myModal" id="'.$sqlName_value['id'].'" game-players="'.$sqlName_value['players'].'" game-type="'.$sqlName_value['gametype'].'" data-sessionid="'.$sqlName_value['id'].'" game-bet="'.$sqlName_value['bet'].'">Join Game</a>';

                            }
           
           ?>
           </td></tr>
           <?php } ?>
           </tbody>
           </table>
                        
<script>
     $(document).ready(function(){
        $('.launch-modal-2').click(function(){
             $('#myModal').modal({
                 backdrop: 'static'
             });
         });

      });
       
    //    $(document).ready(function(){

    //     getTotalPlayerPerGame();

    //   setInterval(function(){
    //         getTotalPlayerPerGame();
    //   },10000);
    // });

    // function getTotalPlayerPerGame(){     
        
    //     var ajxData = 'ajax=TRUE';
    //                 $.ajax({
    //                  type: 'POST',
    //                  url: 'ajax/ajaxtableplayer.php',
    //                  cache: false,
    //                  data: ajxData,
    //                  dataType: 'JSON',
    //                  success: function(result){
                     
    //                     console.log(result);
    //                } 
    //            });

    //   }       

</script>