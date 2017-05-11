<?php include("config.php"); ob_start(); ?>
<!DOCTYPE html>
<html>
<?php

if($_REQUEST['id']!='')
{
     if(!isset($_SESSION['user_id'])) {

        $_SESSION['user_id'] =  $_REQUEST['id'];

     }
}
?>
<head>
    <meta charset="utf-8" />
    <title>Rummy</title>
    <meta name="description" content="" />
    <meta name="keywords" content="" />    
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />    
     <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
     <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">     
    <link rel="stylesheet" type="text/css" href="css/responsive.css" />
    <link href="css/animations.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/mega_menu.min.css">
    <link rel="stylesheet" href="css/flexslider.css">
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link href="css/tab.css" rel="stylesheet" type="text/css" />
    <link href="css/tab_1.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="css/style.css" />
   <link rel="stylesheet" href="css/card.css">
   <link rel="stylesheet" href="css/cardDiscard.css">  

   <!-- custom scrollbar stylesheet -->
   <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
   <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>

   <script>
    $(document).ready(function(){

        var rejoinCookie = $.cookie("rejoin");
        
        if(!rejoinCookie){
            $.cookie("rejoin", "0");
        }
        
        if($.trim(rejoinCookie) == "0"){
              $('.room1').css({ display: 'block'});
              $('.room2').css({ display: 'none'});   
        }else if($.trim(rejoinCookie) == "1"){

           $('.room1').css({ display: 'none'});
           $('.room2').css({ display: 'block'});
        }  

    });

   </script>



</head>
<?php

    if(isset($_SESSION['user_id'])) 
    {
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
               
               

                $user_id = $_SESSION['user_id'];

                $sqlName = mysql_query('SELECT * FROM users WHERE id = '.$user_id.' LIMIT 1');
                $rowName = mysql_fetch_array($sqlName);
                $displayName = $rowName['username'];


                $sqlChips = "SELECT * FROM real_wallet WHERE user_id =".$user_id." AND payment_status ='1' ORDER BY id DESC limit 1";
                $sqlUserChips = mysql_query($sqlChips);


                $sqlDemoChips = "SELECT * FROM demo_wallet WHERE 1 AND user_id =".$user_id." ORDER BY id DESC limit 1";
                $sqlUserDemoChips = mysql_query($sqlDemoChips);


                $sqlPlayer = "SELECT * FROM players WHERE 1 ";
                $sqlAllPlayer = mysql_query($sqlPlayer);
                $newPlayer = array();
                $countPlayer = "";
                while ($rowsqlAllPlayer = mysql_fetch_array($sqlAllPlayer)) { 
                     $getPlayer = $rowsqlAllPlayer['players'].",".$getPlayer;
                }
                $expPlayer = explode(",", $getPlayer);
                $countPlayer = count(array_filter(array_unique($expPlayer)));

                /************ bonus amt***************/
                  $sqlbonusamt = "SELECT SUM(bonus_amount) as bonusamt FROM bonus_details WHERE user_id =".$user_id." AND staus = '1' ";
                $sqlUserbonusamt = mysql_query($sqlbonusamt);
                
                $rowbonusamt = mysql_fetch_array($sqlUserbonusamt);
                
                
             //  echo "<pre>"; print_r($rowbonusamt['bonusamt']);
               if(isset($rowbonusamt['bonusamt'])){
                   $bonusamt = $rowbonusamt['bonusamt'];
               }else{
                    $bonusamt =' 0.00';
               }
                    /********** end bonus amt*****************/
              
               
                

    }

?>
<body style="background: #a72c2f;">

    <input type="hidden" id="roomIdHidden">
    <input type="hidden" id="gameTypeHidden">
    <input type="hidden" id="gamePlayersHidden">
    <input type="hidden" id="betValueHidden">

       <div class="room1" style="display: none;">

      <div class="modal fade login_modal in" id="myModal">
        <div class="modal-backdrop fade in"></div>
        <div class="modal-dialog  modal-sm">
        
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">×</button>
              <h4 class="modal-title"><img src="images/diamond.png" alt=""> Buy In</h4>
            </div>
            <div class="modal-body">
                <div class="buy_in_inner">
                        <table>
                            <tbody>
                                <tr>
                                    <td>Bet</td>
                                    <td id="bet_amount"></td>
                                </tr>
                                <tr>
                                    <td>Min BuyIn</td>
                                    <td id="min_buying"></td>
                                </tr>
                                <!--<tr>
                                    <td>Max BuyIn</td>
                                    <td>:8000.00</td>
                                </tr>-->
                                <tr>
                                    <td>Current Balance</td>
                                    <td id="current_balance"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Take <input type="text" id="chips_to_table"> Chips to the table</td>                            
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-center"><input type="checkbox"> Auto Rebuy <a href="#"><i class="fa fa-question-circle"></i></a></td>                            
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-center">
                                        <button type="button" class="joinPR">Ok</button> 
                                        <a href="javascript:;" data-dismiss="modal">Cancel</a>
                                    </td>                            
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            
          </div>
          
        </div>
  </div>
        
            <header class="header_inner padding_bottom_7" style="padding:0;">
        <div class="container">
            <div class="row margin_top_9">
            <div class="col-xs-12 col-sm-4 col-md-4">
                <?php $actual_link = "http://$_SERVER[HTTP_HOST]/index.php/index/link"; ?>
                
                
            </div>
            <div class="col-xs-6 col-sm-4 col-md-4">
                <div class="red_bg top_player">
                    <ul id="total_player">
                        <li><?php if(!empty($playercount)){ echo $playercount." PLAYERS"; }else{ echo "0 PLAYER"; }  ?> </li>
                        <li><?php echo @$totaltablenumber;?> TABLE</li>
                    </ul>
                </div>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-4">
                <div class="top_time pull-right"><?php echo date('jS M Y  H:i');?></div>
                <div class="menu_2 pull-right">
                    <div class="dropdown">
                        <i class="fa fa-navicon" data-toggle="dropdown"></i>
                         <ul class="dropdown-menu">
                            <li><a href="#">Watch Demo</a></li>
                            <li><a href="<?php echo $actual_link; ?>?id=referinvite">Refer &amp; Earn</a></li>
                            <li><a href="<?php echo $actual_link; ?>?id=rummy-rules">Game Rules</a></li>
<!--                            <li><a href="#">Message</a></li>-->
                        </ul>
                    </div>
                </div> 
                 
            </div>
            </div>

            <div class="row">
                
            <div class="header_bottom_sec margin_top_9 margin_bottom_9">
                <div class="col-xs-5 col-sm-3 col-md-2">
                    <div class="logo_2"><a href="<?php echo $actual_link; ?>?id=myaccount" target="_blank"><img src="images/logo_2.png" alt=""></a></div>
                </div>
                
                

                <div class="col-xs-7 col-sm-9 col-md-3 col-md-push-7">
                  
                <div class="pull-right">
                     <div class="login_btn pull-right">
                        <?php if(!isset($_SESSION['user_id'])){ ?>
                            <input value="Login" type="button" class="launch-modal">
                        <?php }else{ ?>
                            <input value="Logout" id="logout" type="button" class="">
                        <?php } ?>
                    </div> 
                   <div class="modal fade login_modal" id="myModal1" role="dialog" style="left:22%;">
                        <div class="modal-dialog">
                        
                          <!-- Modal content-->
                          <div class="modal-content" style="width: 60%;">
                            <div class="modal-header">
                              <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                              <h4 class="modal-title">Log In</h4>
                            </div>
                            <div class="modal-body">
                                <input type="text" value="" id="username" name="username" placeholder="Username">
                                <input type="password" value="" id="password" name="password" placeholder="Password">
                                <input type="submit" id="modal_login" value="login"> 
                                <span class="pull-right margin_top_9"><input type="checkbox"> Remember me</span>
                                <ul class="margin_top_8">
                                    <li><a id="forget_password" onclick="openlogin1();" href="javascript:void(0);">Forgot Password?</a></li>
                                    <!--<li class="pull-right"><a href="#">Sign Up</a></li>-->
                                </ul>
                            </div>
                            
                          </div>
                          
                        </div>
                 </div>
                 <!-- forget password modal star -->
                 <div class="modal fade login_modal" id="myModal2" role="dialog" style="left:22%;">
                        <div class="modal-dialog">
                        
                          <!-- Modal content-->
                          <div class="modal-content" style="width: 60%;">
                            <div class="modal-header">
                              <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                              <h4 class="modal-title">Forgot Password</h4>
                            </div>
                            <div class="modal-body">
                                <input type="text" value="" id="email" name="email" placeholder="Email">
                               <center> <input type="submit"  id="modal_forget_password" value="Submit"> 
                                <input value="Login" type="button" onclick="openlogin();" ></center>
                                <!-- <span class="pull-right margin_top_9"><input type="checkbox"> Remember me</span> -->
                                <!-- <ul class="margin_top_8">
                                    <li><a href="#">Forget Password?</a></li>
                                    <li class="pull-right"><a href="#">Sign Up</a></li>
                                </ul> -->
                            </div>
                            
                          </div>
                          
                        </div>
                 </div>
                 <!-- forget password modal end -->
                    <div class="top_time-user pull-right"><div class="dropdown"><h4 data-toggle="dropdown"><i class="fa fa-user"></i> <?php if(!empty($displayName)){ echo $displayName; } ?>
                                </h4>
                        
   
    <ul class="dropdown-menu">
      <li><a href="<?php echo $actual_link; ?>?id=myaccount" target="_blank">My Account</a></li>
      <li><a href="<?php echo $actual_link; ?>?id=support" target="_blank">Support</a></li>
      <li><a href="<?php echo $actual_link; ?>?id=chngpass" target="_blank">Change Password</a></li>
    </ul>
  </div>
                        
                    </div>
                </div> 
                </div>
                
                <div class="col-xs-12 col-sm-12 col-md-7 col-md-pull-3">
                    <div class="red_bg header_bottom_sec_blog">
                        <div class="chips_count_sec">
                            <div class="pull-left">
                                
                        <h2>Bonus</h2>
                        <p><?php echo $bonusamt; ?><!--No bonus pending--></p>
                     
                            </div>
                            <a href="<?php echo $actual_link; ?>?id=buychips" target="_blank">
                            <div class="bye_real_chips">
                                <div class="bye_real_chips_inner">
                                    <span>
                                        
                                        BUY REAL<br>
                                    CHIPS
                                        
                                    </span>
                                </div>
                            </div>
                            </a>
                            <div class="pull-right real-chips-wallet">
                                <h2>Real Chips</h2>
                                <?php  while ($rowUserChips = mysql_fetch_array($sqlUserChips)) { ?>
                                <div class="count"><?php if(!empty($rowUserChips['balance_chips'])){ echo $rowUserChips['balance_chips'];} ?>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            </div>
            
        </div>
    </header>

    <?php



    if(isset($_SESSION['user_id'])) 
    {

                
                $sqlName = mysql_query('SELECT * FROM room WHERE 1');
                $rowName = mysql_fetch_array($sqlName);

                $sql201 = "SELECT * FROM room WHERE gametype = 201 ";
                $sqlName201 = mysql_query($sql201);

                $sql101 = "SELECT * FROM room WHERE gametype = 101 ";
                $sqlName101 = mysql_query($sql101);

                $sqlall = "SELECT * FROM room WHERE gametype IN('deals2', 'deals3')";
                $sqlNameall = mysql_query($sqlall);

                $sqlPoints = "SELECT * FROM room WHERE gametype = 'score' ";
                $sqlNamePoints = mysql_query($sqlPoints);


                

   

    }

    ?>
    <?php

    if(isset($_SESSION['user_id'])) 
    {


    $gametype = $_POST['gametype'];

    $player_2 = $_POST['player_2'];
    $player_6 = $_POST['player_6'];
    $player_9 = $_POST['player_9'];
    $player_bet = $_POST['player_bet'];
    $player_l = $_POST['player_l'];
    $player_m = $_POST['player_m'];
    $player_h = $_POST['player_h'];


    if($gametype == 101 || $gametype == 201)
    {
        $sql = "SELECT * FROM room WHERE gametype = '".$gametype."' ";
    }
    elseif($gametype == 'x')
    {
        $sql = "SELECT * FROM room WHERE gametype IN('deals2', 'deals3')";
    }
    elseif($gametype == 'points')
    {
        $sql = "SELECT * FROM room WHERE gametype IN('points')";
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
    


    //echo $sql;die;

    $sqlName = mysql_query($sql);

    }
?>

    <div class="body_sec padding_top_10  padding_bottom_6">
        <div class="inner_page_cont">
            <div class="game_table_sec">
                <div class="container">
                   <div class="o-section">
      <div id="tabs" class="c-tabs no-js">
        
        
       <div class="row">
       
        <div class="col-xs-12 col-sm-2 col-md-2">
            <div class="c-tabs-nav">
            <a href="#" class="c-tabs-nav__link  is-active">
            
            <span>PLAY FOR CASH</span>
          </a>
<!--          <a href="#" class="c-tabs-nav__link">
            
            <span>TOURNAMENTS</span>
          </a>-->
                                      
<!--          <a href="#" class="c-tabs-nav__link">
            
            <span>PLAY FOR CASH</span>
          </a>-->
<!--          <a href="#" class="c-tabs-nav__link">
            
            <span>PRIVATE TABLES</span>
          </a>-->
          
        </div>
                        </div>
                        <div class="col-xs-12 col-sm-8 col-md-8">
                            <div class="c-tab is-active">
          <div class="c-tab__content">
            <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="game_table_sec_middle">
                                            <div id="tabsholder1">

        <ul class="tabs">
                                <li id="tab4" class="gametype" data-value="score">Score Game</li>
                                <li id="tab2" class="gametype" data-value="201">201 Pool</li>
                                <li id="tab3" class="gametype" data-value="x">Deal Game</li>
                                <li id="tab1" class="gametype" data-value="101">101 Pool</li>
                            </ul>
        <div class="contents marginbot">

            <div id="content4" class="tabscontent">
                <div class="table-responsive table-list">
                        <div class="filter_sec">
                        
                            <ul>
                                <li>Filter</li>
                               
                                <li>Players</li>
                                <li>2 <input type="checkbox" name="player_2" id="player_2" value="2"></li>
                                <li>6 <input type="checkbox" name="player_6" id="player_6" value="6"></li>
                                <li>9 <input type="checkbox" name="player_9" id="player_9" value="9"></li>
<!--                                <li>Bet <input type="checkbox" name="player_bet" id="player_bet" value="bet"></li>
                                <li>L <input type="checkbox" name="player_l" id="player_l" value="l"></li>
                                <li>M <input type="checkbox" name="player_m" id="player_m" value="m"></li>
                                <li>H <input type="checkbox" name="player_h" id="player_h" value="h"></li>-->
                                
                            </ul>
                          
                        </div>
                                    <table id="playtype_points" width="100%" cellspacing="0" cellpadding="0" class="table-striped">
                                        <thead><tr>
                                            <th>Game Type</th>
                                            <th>Bet</th>
                                            <th>Min Buyin</th>
                                            <th>Max Player</th>
                                            <th>Total Plyrs</th>                                   
                                            <th>Join</th>                                        
                                        </tr></thead>
                                        <tbody>
                                         <?php while ($sqlNamePoints_value = mysql_fetch_array($sqlNamePoints)) {

                                            $minBuying = floatval((float)$sqlNamePoints_value['bet'] * 80);
                                          
                                           ?>
                                           
                                           <tr id="player<?php echo $sqlNamePoints['id']; ?>">
                                            <td><?php echo $sqlNamePoints_value['gametype']; ?></td>  
                                            <td><?php echo $sqlNamePoints_value['bet']; ?></td>
                                            <td><?php echo $minBuying; ?></td>
                                            <td><?php echo $sqlNamePoints_value['players']; ?></td>
                                            <td class="totalplayercount"></td>
                                            <td>
                                                <?php

            echo  '<a class="join btn btn-success launch-modal-2" id="'.$sqlNamePoints_value['id'].'" game-players="'.$sqlNamePoints_value['players'].'" game-type="'.$sqlNamePoints_value['gametype'].'" data-sessionid="'.$sqlNamePoints_value['id'].'" game-bet="'.$sqlNamePoints_value['bet'].'">Join Game</a>';

                                                ?>
                                            </td>
                                        </tr>
                                           <?php
                                        } ?>
                                        </tbody>
                                    </table>
                 </div>
            </div>
            <div id="content2" class="tabscontent">
                <div class="table-responsive table-list">
           <div class="filter_sec">
                <ul>
                    <li>Filter</li>
                              
                    <li>Players</li>
                    <li>2 <input type="checkbox" name="player_2" id="player_2" value="2"></li>
                    <li>6 <input type="checkbox" name="player_6" id="player_6" value="6"></li>
                    <li>9 <input type="checkbox" name="player_9" id="player_9" value="9"></li>
<!--                    <li>Bet <input type="checkbox" name="player_bet" id="player_bet" value="bet"></li>
                    <li>L <input type="checkbox" name="player_l" id="player_l" value="l"></li>
                    <li>M <input type="checkbox" name="player_m" id="player_m" value="m"></li>
                    <li>H <input type="checkbox" name="player_h" id="player_h" value="h"></li>-->
                </ul>
            </div>
           <table id="playtype_201" width="100%" cellspacing="0" cellpadding="0" class="table-striped">
                                                        <thead><tr>
                                                            <th>Game Type</th>
                                                            <th>Bet</th>
                                                            
                                                            <th>Max Player</th>
                                                            <th>Total Plyrs</th>
                                                                                
                                                            <th>Join</th>                                        
                                                        </tr></thead>
                                                         <tbody>
                                                        <?php while ($rowName201_value = mysql_fetch_array($sqlName201)) {
                                                          
                                                           ?>
                                                          
                                                           <tr id="player<?php echo $rowName201_value['id']; ?>">
                                                            <td><?php echo $rowName201_value['gametype']; ?></td>  
                                                            <td><?php echo $rowName201_value['bet']; ?></td>
                                                           
                                                            <td><?php echo $rowName201_value['players']; ?></td>
                                                            <td class="totalplayercount"></td>
                                                           
                                                            <td>
                                                                 <?php
           
                     echo  '<a class="join btn btn-success" id="'.$rowName201_value['id'].'" game-players="'.$rowName201_value['players'].'" game-type="'.$rowName201_value['gametype'].'" data-sessionid="'.$rowName201_value['id'].'" game-bet="'.$rowName201_value['bet'].'">Join Game</a>';
           
                                                                ?>
                                                            </td>
                                                        </tr>
                                                           <?php
                                                        } ?>
                                                        
                                                        </tbody>
                                                    </table>
                    </div>
            </div>
            <div id="content3" class="tabscontent">
            <div class="table-responsive table-list">
           <div class="filter_sec">
                 <ul>
                    <li>Filter</li>
                                
                    <li>Game Type : </li>
                    <li>deals2 <input type="checkbox" name="player_deals2" id="player_deals2" value="deals2"></li>
                    <li>deals3 <input type="checkbox" name="player_deals3" id="player_deals3" value="deals3"></li>
                </ul>
                                                    </div>
           <table id="playtype_x" width="100%" cellspacing="0" cellpadding="0" class="table-striped">
                                                        <thead><tr>
                                                            <th>Game Type</th>
                                                            <th>Bet</th>
                                                           
                                                            <th>Max Player</th>
                                                            <th>Total Plyrs</th>
                                                                                                    
                                                            <th>Join</th>                                        
                                                        </tr></thead>
                                                        <tbody>
                                                        <?php while ($rowNameall_value = mysql_fetch_array($sqlNameall)) {
                                                          
                                                           ?>
                                                           <tr id="player<?php echo $rowNameall_value['id']; ?>">
                                                            <td><?php echo $rowNameall_value['gametype']; ?></td>  
                                                            <td><?php echo $rowNameall_value['bet']; ?></td>
                                                            
                                                            <td><?php echo $rowNameall_value['players']; ?></td>
                                                            <td class="totalplayercount"></td>
                                                            
                                                            <td>
                                                                <?php
          
                echo  '<a class="join btn btn-success" id="'.$rowNameall_value['id'].'" game-players="'.$rowNameall_value['players'].'" game-type="'.$rowNameall_value['gametype'].'" data-sessionid="'.$rowNameall_value['id'].'" game-bet="'.$rowNameall_value['bet'].'">Join Game</a>';
           
                                                                ?>
                                                            </td>
                                                        </tr>
                                                           <?php
                                                        } ?>
                                                        </tbody>
                                                    </table>
                    </div>
                    
            </div>
            <div id="content1" class="tabscontent">

            <div class="table-responsive table-list">
           <div class="filter_sec">
                           
            <ul>
                <li>Filter</li>
                               
                    <li>Players</li>
                    <li>2 <input type="checkbox" name="player_2" id="player_2" value="2"></li>
                    <li>6 <input type="checkbox" name="player_6" id="player_6" value="6"></li>
                    <li>9 <input type="checkbox" name="player_9" id="player_9" value="9"></li>
                   <!-- <li>Bet <input type="checkbox" name="player_bet" id="player_bet" value="bet"></li>
                    <li>L <input type="checkbox" name="player_l" id="player_l" value="l"></li>
                    <li>M <input type="checkbox" name="player_m" id="player_m" value="m"></li>
                    <li>H <input type="checkbox" name="player_h" id="player_h" value="h"></li> -->
            </ul>
                           
            </div>
           <table id="playtype_101" width="100%" cellspacing="0" cellpadding="0" class="table-striped">
                                                        <thead>                
                                                        <tr>
                                                            <th>Game Type</th>
                                                            <th>Bet</th>
                                                           
                                                            <th>Max Player</th>
                                                            <th>Total Plyrs</th>
                                                                                                   
                                                            <th>Join</th>                                        
                                                        </tr></thead>

                                                        <tbody>
                                                         <?php while ($rowName101_value = mysql_fetch_array($sqlName101)) {
                                                          
                                                           ?>
                                                           <tr id="player<?php echo $rowName101_value['id']; ?>">
                                                            <td><?php echo $rowName101_value['gametype']; ?></td>  
                                                            <td><?php echo $rowName101_value['bet']; ?></td>
                                                            
                                                            <td><?php echo $rowName101_value['players']; ?></td>
                                                            <td class="totalplayercount"></td>
                                                            
                                                            <td>
                                                                <?php
           
                echo  '<a class="join btn btn-success" id="'.$rowName101_value['id'].'" game-players="'.$rowName101_value['players'].'" game-type="'.$rowName101_value['gametype'].'" data-sessionid="'.$rowName101_value['id'].'" game-bet="'.$rowName101_value['bet'].'">Join Game</a>';
           
                                                                ?>
                                                            </td>
                                                        </tr>
                                                           <?php
                                                        } ?>
                                                        </tbody>
                                                    </table>
                    </div>
            </div>
        </div>
    </div>
                                           </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-3 col-md-3" style="display: none;">
                                            <div class="game_table_sec_right">
                                                <h4>Game Information</h4>
                                                <ul>
                                                    <li>Bet</li>
                                                    <li>Player</li>
                                                </ul>
                                                <div class="player_box margin_top_1  margin_bottom_5">
                                                    <h4>Players</h4>
                                                    <ul>
                                                        <li></li>
                                                        <li></li>
                                                        <li></li>
                                                    </ul>
                                                </div>
                                                <a href="#">Join Now</a>
                                                <a href="#">Watch</a>
                                                <a href="#" class="game_rules">Game Rules</a>
                                            </div>
                                        </div>
                                    </div>
          </div>
        </div>
        <div class="c-tab">
          <div class="c-tab__content">
              <div class="row">
              <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="game_table_sec_middle">
              <div id="tabsholder2">

        <ul class="tabs">
            <li id="tabb1">Points Rummy</li>
            <li id="tabb2">201 Pool</li>
            <li id="tabb3">Best of X</li>
            <li id="tabb4">101 Pool</li>
        </ul>
        <div class="contents marginbot">

            <div id="contentb1" class="tabscontent">
            <div class="table-responsive">
                                                    <div class="filter_sec">
                                                        <ul>
                                                            <li>Filter</li>
                                                           
                                                            <li>Players </li>
                                                            <li>2 <input type="checkbox"></li>
                                                            <li>6 <input type="checkbox"></li>
                                                            <li>9 <input type="checkbox"></li>
                                                            <li>Bet <input type="checkbox"></li>
                                                            <li>L <input type="checkbox"></li>
                                                            <li>M <input type="checkbox"></li>
                                                            <li>H <input type="checkbox"></li>
                                                        </ul>
                                                    </div>
                                                    <table width="100%" cellspacing="0" cellpadding="0">
                                                        <tbody><tr>
                                                            <th>Game Type</th>
                                                            <th>Bet</th>
                                                            <th>Min Buyin</th>
                                                            <th>Max Player</th>
                                                            <th>Total Plyrs</th>
                                                            <th>Status</th>
                                                            <th>Reg Table</th>                                        
                                                            <th>Join</th>                                        
                                                        </tr>
                                                        <tr>
                                                            <td>PR - Jokar</td>                                        
                                                            <td>0.05</td>
                                                            <td>4</td>
                                                            <td>2</td>
                                                            <td>80</td>
                                                            <td>Open</td>
                                                            <td>0/9</td>
                                                            <td><a href="#" class="join">Join</a></td>
                                                        </tr>
                                                        
                                                    </tbody></table>
                 </div>
            </div>
            <div id="contentb2" class="tabscontent">
            <div class="table-responsive">
                                                    <div class="filter_sec">
                                                        <ul>
                                                            <li>Filter</li>
                                                            
                                                            <li>Players</li>
                                                            <li>2 <input type="checkbox"></li>
                                                            <li>6 <input type="checkbox"></li>
                                                            <li>9 <input type="checkbox"></li>
                                                            <li>Bet <input type="checkbox"></li>
                                                            <li>L <input type="checkbox"></li>
                                                            <li>M <input type="checkbox"></li>
                                                            <li>H <input type="checkbox"></li>
                                                        </ul>
                                                    </div>
                                                    <table width="100%" cellspacing="0" cellpadding="0">
                                                        <tbody><tr>
                                                            <th>Game Type</th>
                                                            <th>Bet</th>
                                                            <th>Min Buyin</th>
                                                            <th>Max Player</th>
                                                            <th>Total Plyrs</th>
                                                            <th>Status</th>
                                                            <th>Reg Table</th>                                        
                                                            <th>Join</th>                                        
                                                        </tr>
                                                        <tr>
                                                            <td>PR - Jokar</td>                                        
                                                            <td>0.05</td>
                                                            <td>4</td>
                                                            <td>2</td>
                                                            <td>80</td>
                                                            <td>Open</td>
                                                            <td>0/9</td>
                                                            <td><a href="#" class="join">Join</a></td>
                                                        </tr>
                                                        
                                                    </tbody></table>
                 </div>
            </div>
            <div id="contentb3" class="tabscontent">
                    <div class="table-responsive">
                                                    <div class="filter_sec">
                                                        <ul>
                                                            <li>Filter</li>
                                                           
                                                            <li>Players</li>
                                                            <li>2 <input type="checkbox"></li>
                                                            <li>6 <input type="checkbox"></li>
                                                            <li>9 <input type="checkbox"></li>
                                                            <li>Bet <input type="checkbox"></li>
                                                            <li>L <input type="checkbox"></li>
                                                            <li>M <input type="checkbox"></li>
                                                            <li>H <input type="checkbox"></li>
                                                        </ul>
                                                    </div>
                                                    <table width="100%" cellspacing="0" cellpadding="0">
                                                        <tbody><tr>
                                                            <th>Game Type</th>
                                                            <th>Bet</th>
                                                            <th>Min Buyin</th>
                                                            <th>Max Player</th>
                                                            <th>Total Plyrs</th>
                                                            <th>Status</th>
                                                            <th>Reg Table</th>                                        
                                                            <th>Join</th>                                        
                                                        </tr>
                                                        <tr>
                                                            <td>PR - Jokar</td>                                        
                                                            <td>0.05</td>
                                                            <td>4</td>
                                                            <td>2</td>
                                                            <td>80</td>
                                                            <td>Open</td>
                                                            <td>0/9</td>
                                                            <td><a href="#" class="join">Join</a></td>
                                                        </tr>
                                                        
                                                    </tbody></table>
                 </div>
            </div>
            <div id="contentb4" class="tabscontent">
 <div class="table-responsive">
                                                    <div class="filter_sec">
                                                        <ul>
                                                            <li>Filter</li>
                                                           
                                                            <li>Players </li>
                                                            <li>2 <input type="checkbox"></li>
                                                            <li>6 <input type="checkbox"></li>
                                                            <li>9 <input type="checkbox"></li>
                                                            <li>Bet <input type="checkbox"></li>
                                                            <li>L <input type="checkbox"></li>
                                                            <li>M <input type="checkbox"></li>
                                                            <li>H <input type="checkbox"></li>
                                                        </ul>
                                                    </div>
                                                    <table width="100%" cellspacing="0" cellpadding="0">
                                                        <tbody><tr>
                                                            <th>Game Type</th>
                                                            <th>Bet</th>
                                                            <th>Min Buyin</th>
                                                            <th>Max Player</th>
                                                            <th>Total Plyrs</th>
                                                            <th>Status</th>
                                                            <th>Reg Table</th>                                        
                                                            <th>Join</th>                                        
                                                        </tr>
                                                        <tr>
                                                            <td>PR - Jokar</td>                                        
                                                            <td>0.05</td>
                                                            <td>4</td>
                                                            <td>2</td>
                                                            <td>80</td>
                                                            <td>Open</td>
                                                            <td>0/9</td>
                                                            <td><a href="#" class="join">Join</a></td>
                                                        </tr>
                                                        
                                                    </tbody></table>
                 </div>
            </div>
        </div>
    </div>
                      </div>
                  </div>
           <div class="col-xs-12 col-sm-3 col-md-3">
                                            <div class="game_table_sec_right">
                                                <h4>Game Information</h4>
                                                <ul>
                                                    <li>Bet</li>
                                                    <li>Player</li>
                                                </ul>
                                                <div class="player_box margin_top_1  margin_bottom_5">
                                                    <h4>Players</h4>
                                                    <ul>
                                                        <li></li>
                                                        <li></li>
                                                        <li></li>
                                                    </ul>
                                                </div>
                                                <a href="#">Join Now</a>
                                                <a href="#">Watch</a>
                                                <a href="#" class="game_rules">Game Rules</a>
                                            </div>
                                        </div>
                  </div>
          </div>
        </div>
         
       <div class="c-tab">
          <div class="c-tab__content">
              <div class="row">
              <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="game_table_sec_middle">
              <div id="tabsholder3">

        <ul class="tabs">
            <li id="tabc1">Points Rummy</li>
            <li id="tabc2">201 Pool</li>
            <li id="tabc3">Best of X</li>
            <li id="tabc4">101 Pool</li>
        </ul>
        <div class="contents marginbot">

            <div id="contentc1" class="tabscontent">
            <div class="table-responsive">
                                                    <div class="filter_sec">
                                                        <ul>
                                                            <li>Filter</li>
                                                           
                                                            <li>Players </li>
                                                            <li>2 <input type="checkbox"></li>
                                                            <li>6 <input type="checkbox"></li>
                                                            <li>9 <input type="checkbox"></li>
                                                            <li>Bet <input type="checkbox"></li>
                                                            <li>L <input type="checkbox"></li>
                                                            <li>M <input type="checkbox"></li>
                                                            <li>H <input type="checkbox"></li>
                                                        </ul>
                                                    </div>
                                                    <table width="100%" cellspacing="0" cellpadding="0">
                                                        <tbody><tr>
                                                            <th>Game Type</th>
                                                            <th>Bet</th>
                                                            <th>Min Buyin</th>
                                                            <th>Max Player</th>
                                                            <th>Total Plyrs</th>
                                                            <th>Status</th>
                                                            <th>Reg Table</th>                                        
                                                            <th>Join</th>                                        
                                                        </tr>
                                                        <tr>
                                                            <td>PR - Jokar</td>                                        
                                                            <td>0.05</td>
                                                            <td>4</td>
                                                            <td>2</td>
                                                            <td>80</td>
                                                            <td>Open</td>
                                                            <td>0/9</td>
                                                            <td><a href="#" class="join">Join</a></td>
                                                        </tr>
                                                       
                                                    </tbody></table>
                 </div>
            </div>
            <div id="contentc2" class="tabscontent">
            <div class="table-responsive">
                                                    <div class="filter_sec">
                                                        <ul>
                                                            <li>Filter</li>
                                                           
                                                            <li>Players </li>
                                                            <li>2 <input type="checkbox"></li>
                                                            <li>6 <input type="checkbox"></li>
                                                            <li>9 <input type="checkbox"></li>
                                                            <li>Bet <input type="checkbox"></li>
                                                            <li>L <input type="checkbox"></li>
                                                            <li>M <input type="checkbox"></li>
                                                            <li>H <input type="checkbox"></li>
                                                        </ul>
                                                    </div>
                                                    <table width="100%" cellspacing="0" cellpadding="0">
                                                        <tbody><tr>
                                                            <th>Game Type</th>
                                                            <th>Bet</th>
                                                            <th>Min Buyin</th>
                                                            <th>Max Player</th>
                                                            <th>Total Plyrs</th>
                                                            <th>Status</th>
                                                            <th>Reg Table</th>                                        
                                                            <th>Join</th>                                        
                                                        </tr>
                                                        <tr>
                                                            <td>PR - Jokar</td>                                        
                                                            <td>0.05</td>
                                                            <td>4</td>
                                                            <td>2</td>
                                                            <td>80</td>
                                                            <td>Open</td>
                                                            <td>0/9</td>
                                                            <td><a href="#" class="join">Join</a></td>
                                                        </tr>
                                                       
                                                    </tbody></table>
                 </div>
            </div>
            <div id="contentc3" class="tabscontent">
                    <div class="table-responsive">
                                                    <div class="filter_sec">
                                                        <ul>
                                                            <li>Filter</li>
                                                           
                                                            <li>Players </li>
                                                            <li>2 <input type="checkbox"></li>
                                                            <li>6 <input type="checkbox"></li>
                                                            <li>9 <input type="checkbox"></li>
                                                            <li>Bet <input type="checkbox"></li>
                                                            <li>L <input type="checkbox"></li>
                                                            <li>M <input type="checkbox"></li>
                                                            <li>H <input type="checkbox"></li>
                                                        </ul>
                                                    </div>
                                                    <table width="100%" cellspacing="0" cellpadding="0">
                                                        <tbody><tr>
                                                            <th>Game Type</th>
                                                            <th>Bet</th>
                                                            <th>Min Buyin</th>
                                                            <th>Max Player</th>
                                                            <th>Total Plyrs</th>
                                                            <th>Status</th>
                                                            <th>Reg Table</th>                                        
                                                            <th>Join</th>                                        
                                                        </tr>
                                                        <tr>
                                                            <td>PR - Jokar</td>                                        
                                                            <td>0.05</td>
                                                            <td>4</td>
                                                            <td>2</td>
                                                            <td>80</td>
                                                            <td>Open</td>
                                                            <td>0/9</td>
                                                            <td><a href="#" class="join">Join</a></td>
                                                        </tr>
                                                       
                                                    </tbody></table>
                 </div>
            </div>
            <div id="contentc4" class="tabscontent">

            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam ante arcu, lacinia quis varius vel, pharetra eu sapien. Nulla dictum tincidunt nunc gravida congue. Pellentesque quis leo at leo porttitor aliquam sed id eros. Pellentesque id feugiat dui. Aliquam elementum arcu eu tellus commodo pellentesque. Curabitur porttitor egestas odio et malesuada. Integer ut sapien commodo nisi vulputate egestas. Vivamus porttitor cursus aliquam. Quisque vitae commodo est. Vestibulum eget lectus quis mi volutpat lobortis a et massa. Quisque scelerisque, nulla ut sollicitudin lobortis, urna neque dapibus sem, quis congue tellus purus id nibh. Pellentesque congue massa ac quam rhoncus posuere. Curabitur eleifend suscipit luctus. Sed eu risus nisi. Sed eu molestie tortor. Aenean dictum justo ac turpis feugiat ultricies. Morbi sed dictum sapien.
            </div>
        </div>
    </div>
                      </div>
                  </div>
           <div class="col-xs-12 col-sm-3 col-md-3">
                                            <div class="game_table_sec_right">
                                                <h4>Game Information</h4>
                                                <ul>
                                                    <li>Bet</li>
                                                    <li>Player</li>
                                                </ul>
                                                <div class="player_box margin_top_1  margin_bottom_5">
                                                    <h4>Players</h4>
                                                    <ul>
                                                        <li></li>
                                                        <li></li>
                                                        <li></li>
                                                    </ul>
                                                </div>
                                                <a href="#">Join Now</a>
                                                <a href="#">Watch</a>
                                                <a href="#" class="game_rules">Game Rules</a>
                                            </div>
                                        </div>
                  </div>
          </div>
        </div> 
       
                            
       <div class="c-tab">
          <div class="c-tab__content">
              <div class="row">
              <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="game_table_sec_middle">
              <div id="tabsholder4">

        <ul class="tabs">
            <li id="tabd1">Points Rummy</li>
            <li id="tabd2">201 Pool</li>
            <li id="tabd3">Best of X</li>
            <li id="tabd4">101 Pool</li>
        </ul>
        <div class="contents marginbot">

            <div id="contentd1" class="tabscontent">
            <div class="table-responsive">
                                                    <div class="filter_sec">
                                                        <ul>
                                                            <li>Filter</li>
                                                          
                                                            <li>Players</li>
                                                            <li>2 <input type="checkbox"></li>
                                                            <li>6 <input type="checkbox"></li>
                                                            <li>9 <input type="checkbox"></li>
                                                            <li>Bet <input type="checkbox"></li>
                                                            <li>L <input type="checkbox"></li>
                                                            <li>M <input type="checkbox"></li>
                                                            <li>H <input type="checkbox"></li>
                                                        </ul>
                                                    </div>
                                                    <table width="100%" cellspacing="0" cellpadding="0">
                                                        <tbody><tr>
                                                            <th>Game Type</th>
                                                            <th>Bet</th>
                                                            <th>Min Buyin</th>
                                                            <th>Max Player</th>
                                                            <th>Total Plyrs</th>
                                                            <th>Status</th>
                                                            <th>Reg Table</th>                                        
                                                            <th>Join</th>                                        
                                                        </tr>
                                                        <tr>
                                                            <td>PR - Jokar</td>                                        
                                                            <td>0.05</td>
                                                            <td>4</td>
                                                            <td>2</td>
                                                            <td>80</td>
                                                            <td>Open</td>
                                                            <td>0/9</td>
                                                            <td><a href="#" class="join">Join</a></td>
                                                        </tr>
                                                       
                                                    </tbody></table>
                 </div>
            </div>
            <div id="contentd2" class="tabscontent">
            <div class="table-responsive">
                                                    <div class="filter_sec">
                                                        <ul>
                                                            <li>Filter</li>
                                                            
                                                            <li>Players</li>
                                                            <li>2 <input type="checkbox"></li>
                                                            <li>6 <input type="checkbox"></li>
                                                            <li>9 <input type="checkbox"></li>
                                                            <li>Bet <input type="checkbox"></li>
                                                            <li>L <input type="checkbox"></li>
                                                            <li>M <input type="checkbox"></li>
                                                            <li>H <input type="checkbox"></li>
                                                        </ul>
                                                    </div>
                                                    <table width="100%" cellspacing="0" cellpadding="0">
                                                        <tbody><tr>
                                                            <th>Game Type</th>
                                                            <th>Bet</th>
                                                            <th>Min Buyin</th>
                                                            <th>Max Player</th>
                                                            <th>Total Plyrs</th>
                                                            <th>Status</th>
                                                            <th>Reg Table</th>                                        
                                                            <th>Join</th>                                        
                                                        </tr>
                                                        <tr>
                                                            <td>PR - Jokar</td>                                        
                                                            <td>0.05</td>
                                                            <td>4</td>
                                                            <td>2</td>
                                                            <td>80</td>
                                                            <td>Open</td>
                                                            <td>0/9</td>
                                                            <td><a href="#" class="join">Join</a></td>
                                                        </tr>
                                                       
                                                    </tbody></table>
                 </div>
            </div>
            <div id="contentd3" class="tabscontent">
                    <div class="table-responsive">
                                                    <div class="filter_sec">
                                                        <ul>
                                                            <li>Filter</li>
                                                           
                                                            <li>Players</li>
                                                            <li>2 <input type="checkbox"></li>
                                                            <li>6 <input type="checkbox"></li>
                                                            <li>9 <input type="checkbox"></li>
                                                            <li>Bet <input type="checkbox"></li>
                                                            <li>L <input type="checkbox"></li>
                                                            <li>M <input type="checkbox"></li>
                                                            <li>H <input type="checkbox"></li>
                                                        </ul>
                                                    </div>
                                                    <table width="100%" cellspacing="0" cellpadding="0">
                                                        <tbody><tr>
                                                            <th>Game Type</th>
                                                            <th>Bet</th>
                                                            <th>Min Buyin</th>
                                                            <th>Max Player</th>
                                                            <th>Total Plyrs</th>
                                                            <th>Status</th>
                                                            <th>Reg Table</th>                                        
                                                            <th>Join</th>                                        
                                                        </tr>
                                                        <tr>
                                                            <td>PR - Jokar</td>                                        
                                                            <td>0.05</td>
                                                            <td>4</td>
                                                            <td>2</td>
                                                            <td>80</td>
                                                            <td>Open</td>
                                                            <td>0/9</td>
                                                            <td><a href="#" class="join">Join</a></td>
                                                        </tr>
                                                        
                                                    </tbody></table>
                 </div>
            </div>
            <div id="contentd4" class="tabscontent">
                 <div class="table-responsive">
                                                    <div class="filter_sec">
                                                        <ul>
                                                            <li>Filter</li>
                                                          
                                                            <li>Players</li>
                                                            <li>2 <input type="checkbox"></li>
                                                            <li>6 <input type="checkbox"></li>
                                                            <li>9 <input type="checkbox"></li>
                                                            <li>Bet <input type="checkbox"></li>
                                                            <li>L <input type="checkbox"></li>
                                                            <li>M <input type="checkbox"></li>
                                                            <li>H <input type="checkbox"></li>
                                                        </ul>
                                                    </div>
                                                    <table width="100%" cellspacing="0" cellpadding="0">
                                                        <tbody><tr>
                                                            <th>Game Type</th>
                                                            <th>Bet</th>
                                                            <th>Min Buyin</th>
                                                            <th>Max Player</th>
                                                            <th>Total Plyrs</th>
                                                            <th>Status</th>
                                                            <th>Reg Table</th>                                        
                                                            <th>Join</th>                                        
                                                        </tr>
                                                        <tr>
                                                            <td>PR - Jokar</td>                                        
                                                            <td>0.05</td>
                                                            <td>4</td>
                                                            <td>2</td>
                                                            <td>80</td>
                                                            <td>Open</td>
                                                            <td>0/9</td>
                                                            <td><a href="#" class="join">Join</a></td>
                                                        </tr>
                                                       
                                                    </tbody></table>
                 </div>
            </div>
        </div>
    </div>
                      </div>
                  </div>
           <div class="col-xs-12 col-sm-3 col-md-3">
                                            <div class="game_table_sec_right">
                                                <h4>Game Information</h4>
                                                <ul>
                                                    <li>Bet</li>
                                                    <li>Player</li>
                                                </ul>
                                                <div class="player_box margin_top_1  margin_bottom_5">
                                                    <h4>Players</h4>
                                                    <ul>
                                                        <li></li>
                                                        <li></li>
                                                        <li></li>
                                                    </ul>
                                                </div>
                                                <a href="#">Join Now</a>
                                                <a href="#">Watch</a>
                                                <a href="#" class="game_rules">Game Rules</a>
                                            </div>
                                        </div>
                  </div>
          </div>
        </div>                      
        
                                
                                    
                                  
                                
                                
                           
                                                      
                        </div>
           
           <div class="col-xs-12 col-sm-2 col-md-2">
               <div class="inner_text_banner">
                   <div class="inner_text_banner_text">
                       
                   
                   Show your skill
and enjoy in
seamless <br>playing <br>
experience
</div>
               </div>
                   
           </div>     
           
                        
                    </div>
      </div>
    </div>
                    
                        
                </div>
                
            </div>
            <div class="container text-center">
                <div class="margin_top_7"><a href="play_game.html" class="black_btn margin_right_2">LOBBY</a></div>
                  
            </div>
        </div>
    </div>
            
    
 </div>  
 
<div class="room2 body_sec" style="display:none;">
            
            <div class="padding_top_9">
            <div class="container">



                <div class="game_top game_info">
                    <a href="#" class="pull-left" id="game_name"></a>
                    <div class="dollar" id="game_bet">
                        <div><img src="images/dollar_icon.png" alt=""> <span></span></div>
                        <div id="game_prize_money"></div>
                    </div>
                   
                    <ul class="game_top_link pull-right">
                        <li><a href="<?php echo $actual_link; ?>?id=referinvite" target="_blank"><img src="images/reffer.png" alt=""></a></li>
                        <li><a href="<?php echo $actual_link; ?>?id=support" target="_blank"><img src="images/how_to_play.png" alt=""></a></li>
                        <!--<li><a href="#"><img src="images/home.png" alt=""></a></li>-->
                    </ul>
                </div>
                <div class="play_board">

                <!-- Scoreboard section -->

                <div class="result_sec">
                    <div class="result_sec_heading">Result</div>
                    <div class="result_sec_inner">
                        <table  width="100%" cellspacing="0">
                            <tr>
                                <th>Name</th>
                                <th>Result</th>
                                <th>Cards</th>
                                <th>Game Score</th>
                                <th>Total Score</th>
                            </tr>
                            <tbody id="score_reports"></tbody>
                            
                        </table> 
                    </div>
                    <h5 class="result_bottom"></h5>
                </div> 

               

               


                    <div class="board_center">
                        <div class="card_1 joker-assign" style="top: -18px;">
                            <div class="playingCards">
                              
                            </div>
                        </div>
                        
                        <div class="card_2 card-throw" style="top: -18px;">
                            <div class="playingCards">
                              

                            </div>
                        </div>
                        <div class="playingCards deck-middle" style="display: none;" style="margin-top: 21px;">
                            <a href="javascript:;" id="cardDeckSelect<?php echo $user_id; ?>" class="cardDeckSelect noSelect">
                                <div class="card card_2 back board_center_back"></div>
                            </a>
                        </div>
                        <div class="game_message"></div>
                    </div>
                    <div class="player_1 current-player" data-user="">
                      
                            <div class="player_name name_1" style="display: none;">
                                <div class="card_icon"><img src="images/card_icon.png" alt="">
                                    <div class="card_show_sec">
                                        <div class="card_show_sec_top">heading</div>
                                        <div id="demo" class="showcase">
                                            <section id="examples">
                                                
                                                
                                                
                                                <!-- content -->
                                                <div class="content-1 content horizontal-images">
                                                    <div style="width: 300px;">
                                                                     
                                                        <div class="card_show_sec_bottom">
                                                            <div class="playingCardsDiscard ">
                                                                <ul class="hand">
                                                                   
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                
                                                
                                            </section>
                                        </div>
                                       
                                    </div>
                                </div>                    
                                <div class="player_name_top" id="score"></div>
                                <span class="player_name_1"></span>
                            </div>



                        <div class="toss_1 toss">
                            <div class="playingCards">
                              
                            </div>
                        </div>
                        <!-- <div class="player_name_3"></div> -->
                        <div class="player_pic_1"><img src="images/player_m_1.png" alt="" style="display: none;"></div>
                        
                        <div class="playingCards player_card_1">
                            <ul class="deck deck_3">

                            </ul>
                        </div>
                        
                </div>
                    <div class="player_2 current-player" data-user="">
                      
                            <div class="player_name name_2" style="display: none;">
                                <div class="card_icon"><img src="images/card_icon.png" alt="">
                                    <div class="card_show_sec">
                                        <div class="card_show_sec_top">heading</div>
                                        <div id="demo" class="showcase">
                                            <section id="examples">
                                                
                                                
                                                
                                                <!-- content -->
                                                <div class="content-2 content horizontal-images">
                                                    <div style="width: 300px;">
                                                                     
                                                        <div class="card_show_sec_bottom">
                                                            <div class="playingCardsDiscard ">
                                                                <ul class="hand">
                                                                   
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                
                                                
                                            </section>
                                        </div>
                                       
                                    </div>
                                </div>                    
                                <div class="player_name_top" id="score"></div>
                                <span class="player_name_2"></span>
                            </div>



                        <div class="toss_2 toss">
                            <div class="playingCards">
                              
                            </div>
                        </div>
                        <!-- <div class="player_name_3"></div> -->
                        <div class="player_pic_2"><img src="images/player_m_2.png" alt="" style="display: none;"></div>
                        
                        <div class="playingCards player_card_2">
                            <ul class="deck deck_3">

                            </ul>
                        </div>
                        
                    </div>
                    <div class="player_3 current-player" data-user="">
                      
                            <div class="player_name name_3" style="display: none;">
                                <div class="card_icon"><img src="images/card_icon.png" alt="">
                                    <div class="card_show_sec">
                                        <div class="card_show_sec_top">heading</div>
                                        <div id="demo" class="showcase">
                                            <section id="examples">
                                                
                                                
                                                
                                                <!-- content -->
                                                <div class="content-3 content horizontal-images">
                                                    <div style="width: 300px;">
                                                                     
                                                        <div class="card_show_sec_bottom">
                                                            <div class="playingCardsDiscard ">
                                                                <ul class="hand">
                                                                   
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                
                                                
                                            </section>
                                        </div>
                                       
                                    </div>
                                </div>                    
                                <div class="player_name_top" id="score"></div>
                                <span class="player_name_3"></span>
                            </div>



                        <div class="toss_3 toss">
                            <div class="playingCards">
                              
                            </div>
                        </div>
                        <!-- <div class="player_name_3"></div> -->
                        <div class="player_pic_3"><img src="images/player_m_3.png" alt="" style="display: none;"></div>
                        
                        <div class="playingCards player_card_3">
                            <ul class="deck deck_3">

                            </ul>
                        </div>
                        
                </div>
                    <div class="player_4 current-player" data-user="">
                      
                            <div class="player_name name_4" style="display: none;">
                                <div class="card_icon"><img src="images/card_icon.png" alt="">
                                    <div class="card_show_sec">
                                        <div class="card_show_sec_top">heading</div>
                                        <div id="demo" class="showcase">
                                            <section id="examples">
                                                
                                                
                                                
                                                <!-- content -->
                                                <div class="content-4 content horizontal-images">
                                                    <div style="width: 300px;">
                                                                     
                                                        <div class="card_show_sec_bottom">
                                                            <div class="playingCardsDiscard ">
                                                                <ul class="hand">
                                                                   
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                
                                                
                                            </section>
                                        </div>
                                       
                                    </div>
                                </div>                    
                                <div class="player_name_top" id="score"></div>
                                <span class="player_name_4"></span>
                            </div>



                        <div class="toss_4 toss">
                            <div class="playingCards">
                              
                            </div>
                        </div>
                        <!-- <div class="player_name_3"></div> -->
                        <div class="player_pic_4"><img src="images/player_m_4.png" alt="" style="display: none;"></div>
                        
                        <div class="playingCards player_card_4">
                            <ul class="deck deck_3">

                            </ul>
                        </div>
                        
                </div>
                    <div class="player_5 current-player" data-user="">
                      
                            <div class="player_name name_5" style="display: none;">
                                <div class="card_icon"><img src="images/card_icon.png" alt="">
                                    <div class="card_show_sec">
                                        <div class="card_show_sec_top">heading</div>
                                        <div id="demo" class="showcase">
                                            <section id="examples">
                                                
                                                
                                                
                                                <!-- content -->
                                                <div class="content-5 content horizontal-images">
                                                    <div style="width: 300px;">
                                                                     
                                                        <div class="card_show_sec_bottom">
                                                            <div class="playingCardsDiscard ">
                                                                <ul class="hand">
                                                                   
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                
                                                
                                            </section>
                                        </div>
                                       
                                    </div>
                                </div>                    
                                <div class="player_name_top" id="score"></div>
                                <span class="player_name_5"></span>
                            </div>



                        <div class="toss_5 toss">
                            <div class="playingCards">
                              
                            </div>
                        </div>
                        <!-- <div class="player_name_3"></div> -->
                        <div class="player_pic_5"><img src="images/player_m_5.png" alt="" style="display: none;"></div>
                        
                        <div class="playingCards player_card_5">
                            <ul class="deck deck_3">

                            </ul>
                        </div>
                        
                </div>


                     <div class="sort" id="sort<?php echo $user_id; ?>" style="display:none;"><button type="button">Sort</button></div>
                     <div class="discard" id="discard" style="display:none;"> 
                        <button type="button" disabled>Discard</button> 
                     </div>
                    <!--  <div class="auto_drop" id="auto_drop<?php echo $user_id; ?>" style="display:none;"> <button type="button" disabled>Auto<br>Drop</button> </div> -->
                      <div class="drop" id="drop" style="display: none;"> 
                        <button type="button" disabled>Drop</button>
                      </div>

                       <div class="meld" id="meld<?php echo $user_id; ?>" style="display: none;"> 
                        <button type="button">Show</button>
                       </div>

                       <div class="meldAll" id="meldAll<?php echo $user_id; ?>" style="display:none;"> 
                        <button type="button">Meld All</button>
                       </div>


                      <div class="group_btn" style="display: none;"><button type="button" class="group_cards">Group</button></div>

                      <div class="group"></div>




                    <!--    <div class="player_name" style="display: none;">
                                <div class="player_name_top" id="score"></div>
                                <span class="player_name_me"></span>
                        </div>
                        <div class="toss_me toss">
                            <div class="playingCards"></div>
                        </div>
                        <div class="me_pic"><img src="images/me.png" alt="" style="display: none;"></div>
                        
                        <div class="playingCards player_card_me">
                                <ul class="hand"></ul>
                        </div>
                    </div>-->

                    <div class="me current-player" data-user="<?php echo $user_id; ?>">
                      
                            <div class="player_name" style="display: none;">
                                <div class="card_icon"><img src="images/card_icon.png" alt="">
                                    <div class="card_show_sec">
                                        <div class="card_show_sec_top">heading</div>
                                        <div id="demo" class="showcase">
                                            <section id="examples">
                                                
                                                
                                                
                                                <!-- content -->
                                                <div class="content-me content horizontal-images">
                                                    <div style="width: 300px;">
                                                                     
                                                        <div class="card_show_sec_bottom">
                                                            <div class="playingCardsDiscard">
                                                                <ul class="hand"></ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                
                                                
                                            </section>
                                        </div>
                                       
                                    </div>
                                </div>                    
                                <div class="player_name_top" id="score"></div>
                                <span class="player_name_me"></span>
                            </div>



                        <div class="toss_me toss">
                            <div class="playingCards">
                              
                            </div>
                        </div>
                        <!-- <div class="player_name_3"></div> -->
                        <div class="me_pic"><img src="images/me.png" alt="" style="display: none;"></div>
                        
                        <div class="playingCards player_card_me">
                            <ul class="hand">

                            </ul>
                        </div>
                        
                </div>


                    <div class="show_your_card_sec">
                            <div class="show_your_card_sec_heading">Your Show Cards</div>
                            <div class="show_your_card_sec_inner">
                                <div class="show_your_card_blog" data-group="1">
                                    
                                    <div class="playingCards">
                                        <ul class="hand"></ul>
                                    </div>
                                </div>
                                 <div class="show_your_card_blog" data-group="2">
                                    
                                    <div class="playingCards">
                                        <ul class="hand"></ul>
                                    </div>
                                </div>
                                 <div class="show_your_card_blog" data-group="3">
                                    
                                    <div class="playingCards">
                                        <ul class="hand"></ul>
                                    </div>
                                </div>
                                 <div class="show_your_card_blog" data-group="4">
                                    <!-- <a href="#"><i class="fa fa-close"></i></a> -->
                                    <div class="playingCards">
                                        <ul class="hand"></ul>
                                    </div>
                                </div>
                                
                                <div class="show_your_card_bottom">
                                    <ul>
                                        <li>Meld Your Cards</li>
                                        <li class="pull-right"><button type="button" id="validateCards<?php echo $user_id; ?>">Send</button></li>
                                    </ul>
                                </div>
                            </div>
                        </div>



                </div>
                <div class="game_bottom game_info2">            
                    <!--<ul class="game_top_link pull-left">
                        <li><a href="#"><img src="images/chat.png" alt=""></a></li>
                        <li><a href="#"><img src="images/table_message.png" alt=""></a></li>
                        <li><a href="#"><img src="images/report.png" alt=""></a></li>
                        <li><a href="#"><img src="images/delet.png" alt=""></a></li>
                    </ul>-->
                    
                    
                    <div class="pull-right">
                        <a href="#" class="pull-left"><img src="images/network.png" alt=""></a>                
                    </div>
                    <!--<div class="pull-right">
                        <a href="#">Last Hand</a>
                        <a href="#">History</a>
                    </div>-->
                    <!--<div class="pull-right">
                        <a href="#">Lobby</a>                
                    </div>-->
                    <div class="pull-right" id="table_id">
                        <a href="#"></a>                
                    </div>
                </div>
<!--                <div class="game_bottom"> 
                    
                    <div class="pull-right">
                        <a href="#" style="background: none;"><img src="images/logo_2.png" width="150" height="auto" alt=""></a>                
                    </div>
                </div>-->



            </div>
            </div>


          <!-- Modal -->
          <div class="modal fade" id="rummyModal" role="dialog" style="">
            <div class="modal-dialog modal-sm">
              <div class="modal-content" style="background: black;">
                <div class="modal-header">
                  <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                  <h4 class="modal-title">Rummy Game</h4>
                </div>
                <div class="modal-body">
                  <p></p>
                </div>
                <!-- <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div> -->
              </div>
            </div>
          </div>


         <!-- Scoardboard Modal --> 

       


    </div> <!-- Room2 -->
    
     <div class="popup_bg">
        <div class="popup_with_button">
            <div class="popup_with_button_cont text-center">
                <p></p>
            </div>
            <div class="popup_with_button_footer">
            <button id="confirmBtn">Ok</button>
            <button class="cancelBtn">Cancel</button>
            </div>
        </div>
   </div>

   <div class="popup_rejoin">
        <div class="popup_with_button">
            <div class="popup_with_button_cont text-center">
                <p>Do you want to rejoin?</p>
            </div>
            <div class="popup_with_button_footer">
            <button id="rejoinBtn">Rejoin</button>
            <button class="goToLobbyBtn">Go to Lobby</button>
            </div>
        </div>
   </div>

    <div class="loading_container">
        <div class="popup">
            <div class="popup_cont text-center"></div>
        </div>
    </div>
   

    

   

   

   
 

    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>

    <script src="webrtc/RTCMulticonnection.js"></script>
 
  
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.js"></script>
   
    
    <script>
          
         $(document).ready(function(){
                $('.gametype').click(function(){
                    var dataValue = $(this).attr('data-value');
                    //alert(dataValue);

                    var gametype = $(".current").attr('data-value');
                    var player_2 = '';
                    var player_6 = '';
                    var player_9 = '';

                    var player_deals2 = '';
                    var player_deals3 = '';

                    
                    $('input:checkbox').removeAttr('checked');

                    $.ajax({
                        type: "POST",
                        url: 'playersubmit.php',
                        data: {'gametype': gametype, 'player_2': player_2, 'player_6': player_6, 'player_9': player_9,'player_deals2':player_deals2,'player_deals3':player_deals3},
                                success: function(data){
                                //alert(data);
                                if(gametype == '101')
                                {
                                    $('#content1 .table-responsive #playtype_101').html(data);
                                }
                                if(gametype == '201')
                                {
                                    $('#content2 .table-responsive #playtype_201').html(data);
                                }
                                if(gametype == 'x')
                                {
                                    $('#content3 .table-responsive #playtype_x').html(data);
                                }
                                if(gametype == 'points')
                                {
                                    $('#content4 .table-responsive #playtype_points').html(data);
                                }
                                
                                

                                

                            },
                    });


                });
            });

            $(document).ready(function(){
                $("input[type=checkbox]").click(function(){
                    //alert("scscscsc");

                    var gametype = $(".current").attr('data-value');
                    //alert(gametype);


                    var player_2 = $('#player_2:checked').val();
                    var player_6 = $('#player_6:checked').val();
                    var player_9 = $('#player_9:checked').val();

                    var player_deals2 = $('#player_deals2:checked').val();
                    var player_deals3 = $('#player_deals3:checked').val();

                    //alert(player_deals2);
                    //alert(player_deals3);

                    

                        $.ajax({
                        type: "POST",
                        url: 'playersubmit.php',
                        data: {'gametype': gametype, 'player_2': player_2, 'player_6': player_6, 'player_9': player_9, 'player_deals2': player_deals2, 'player_deals3': player_deals3 },
                                success: function(data){
                                //alert(data);
                                if(gametype == '101')
                                {
                                    $('#content1 .table-responsive #playtype_101').html(data);
                                }
                                if(gametype == '201')
                                {
                                    $('#content2 .table-responsive #playtype_201').html(data);
                                }
                                if(gametype == 'x')
                                {
                                    $('#content3 .table-responsive #playtype_x').html(data);
                                }
                                if(gametype == 'score')
                                {
                                    $('#content4 .table-responsive #playtype_points').html(data);
                                }
                                
                                

                                

                            },
                    });


                });
            });

            

    </script>

   <script type="text/javascript" src="js/tytabs.jquery.min.js"></script>
   <script type="text/javascript">
<!--
$(document).ready(function(){
    $("#tabsholder1").tytabs({
        tabinit:"1",
        fadespeed:"fast"
    });
                       
});
-->
</script>

<script src="js/tabs.js"></script>
<script>
  var myTabs = tabs({
    el: '#tabs',
    tabNavigationLinks: '.c-tabs-nav__link',
    tabContentContainers: '.c-tab'
  });

  myTabs.init();
</script>
<script>




    function asdfgh(pno){
        var loadUrl = "gamestart.php";

        //var formdata = $("#subsFrmnew").serialize();
        if(gametype != "")
        {
            $.ajax({
            type: "POST",
            url: loadUrl,
            dataType: "html",
            data: {'gametype': gametype, 'bonus': bonus, 'dollarRate': dollarRate},
                success: function (responseText)
                {
                    var response = responseText.split('|');

                    if (response != '') 
                    {
                        alert("fffff");

                    } 
                    
                },
            
            });
        }
        
        

    }
</script>
<script>

           



            $(document).ready(function(){
                 var rejoinCookie = $.cookie("rejoin");

                 if($.trim(rejoinCookie) == "0"){
                    $.cookie("room", null);
                    $.removeCookie("room");
                    $.cookie("game-players", null);
                    $.removeCookie("game-players");
                    $.cookie("creator", null);
                    $.removeCookie("creator");
                    $.cookie("game-type", null);
                    $.removeCookie("game-type");
                    $.cookie("onOpenHit", null);
                    $.removeCookie("onOpenHit");
                    $.cookie("sessionKey", null);
                    $.removeCookie("sessionKey");
                    $.cookie("betValue", null);
                    $.removeCookie("betValue");
                 }   
            });


         var direction;
         var _session;
         var splittedSession;

         var session = {};
         var maxParticipantsAllowed;  


          var counter = 2;
          var playersPlaying = [];
          var playersPlayingTemp = [];
          var playersPlayingNextRound = [];
          var wrongMeldersArray = [];
          var cardsInHand = [];

          var nextPlayerName;
          var nextPlayerName1;


          /** ==== Melding Variables : Needed while melding ====== **/
          
          /* Joker card retrieve while melding */
          var jokerValue;
          
          /* Meld Objects inside: isSeq, isPure etc */
          var meldCardEvaluator1 = [], meldCardEvaluator2 = [], meldCardEvaluator3 = [], meldCardEvaluator4 = [];

          var pureSequence = 0;
          var impureSequence = 0;
          var matchingCards = 0;


            var cardsSelected = [];
            var cardPull = 0;
            var cardDiscard = 0;
            var cardMelded = 0;
            var cardGroupSelected;
            var meldingProcess = 0;

            var group1 = [],
                group2 = [],
                group3 = [],
                group4 = [],
                group5 = [],
                group6 = [];

            var meldedGroup1 = [],
                meldedGroup2 = [],
                meldedGroup3 = [],
                meldedGroup4 = [],
                meldedGroup5 = [],
                meldedGroup6 = [];

            var meldCardArr1 = [],
              meldCardArr2 = [],
              meldCardArr3 = [],
              meldCardArr4 = [];    
                
            var victimGroups = []; /* For finding groups whose cards are not in a proper grouping */

             var testingFlag = 0;
             var gameStatusFlag = '';
             var gameStatusArray = [];
            
           

          

          var waitHandler = function(callback){

             callback();

              var interval = setInterval(function(){
                 if(counter == 0){
                    clearInterval(interval);
                    return;
                  }
                   // console.log(counter);
                    $('.loading_container .popup .popup_cont').text("Please wait while other players join");
                   counter--;
                 
                }, 1000);

              

          }

          var gameStartHandler = function(callback){

            $('.loading_container').css({'display': 'block'});

              var interval = setInterval(function(){
                 if(counter == 0){
                    clearInterval(interval);
                    $('.loading_container').css({'display':'none'});
                    callback();
                  }
                   // console.log(counter);
                   $('.loading_container .popup .popup_cont').text("Game will start in " + counter + ' seconds');
                   counter--;
                 
                }, 1000);


                

          }



          // var sixPlCounter;
          var creatorCookie = $.cookie("creator");
        

       


          var gameStartHandlerSixPl = function(sixPlCounter, creatorCookie, roomId, sessionKey, callback){



            $('.loading_container').css({'display': 'block'});

              var interval = setInterval(function(){
               
                if(sixPlCounter <= 0){
                    clearInterval(interval);
                    $('.loading_container').css({'display':'none'});
                    
                    console.log("0 encountered");
                    
                    callback();
                    // return;
                   
                }
                
                

                 if(creatorCookie){

                    console.log(sixPlCounter);

                    /* update game start counter */
                     var ajxData4001 = {'action': 'update-game-counter', roomId: roomId, counter: sixPlCounter, sessionKey:sessionKey};

                         $.ajax({
                          type: 'POST',
                          url: 'ajax/updateGameCounter.php',
                          cache: false,
                          data: ajxData4001,
                          success: function(result){
                            // console.log(result);

                     } }); 




                 }

                 $('.loading_container .popup .popup_cont').text("Game will start in " + sixPlCounter + ' seconds');
                 sixPlCounter--;

                 
                }, 1000);


                

          }

          var nextGameCounter = 5;


          var nextGameStartHandler = function(callback){
                var interval = setInterval(function(){
                 
                 if(nextGameCounter <= 0){
                    // $('.result_bottom').css({'display': 'none'});
                    // $('.result_bottom').text("");
                    clearInterval(interval);
                     
                    
                    callback();
                  }
                   // console.log(counter);
                   $('.result_bottom').css({'display': 'block'});
                   // $('.result_bottom #timer').text(nextGameCounter);
                   $('.result_bottom').text('Next game will start in '+nextGameCounter+' seconds');
                   nextGameCounter--;
                 
                }, 1000);

          };

          var waitHandlerNormal = function(callback){
             callback();
             $('.loading_container .popup .popup_cont').text('Please wait while other player joins');
          }

          var TossWinnerHandler = function(callback){
            callback();
          };


          $('.player_card_me').delegate('.showFoldedCard', 'click', function(){
                $('.me .toss_me .playingCards').html('<div class="card card_2 back board_center_back showMyHand"></div>');
                $('.player_card_me').hide();

          }); /*baaal*/

           $('.group_blog5').delegate('.showFoldedCard', 'click', function(){
                $('.me .toss_me .playingCards').html('<div class="card card_2 back board_center_back showMyHand"></div>');
                $('.group_blog5').hide();

          }); 

           $('.me').delegate('.showMyHand', 'click', function(){
                $('.player_card_me').show();
                $('.group_blog5').show();
                $(this).hide();


          }); /*baaal*/



          /* ======= Show cards in scoreboard ========= */

              function showCardsInScoreboard(player, roomIdCookie, sessionKeyCookie){

                 var $playingCards = $('<div class="playingCards"></div>');
                 var $hand = $('<ul class="hand"></ul>');

                 var ajxDataGetMeldedCards = { 'action': 'get-my-cards', roomId: roomIdCookie, sessionKey: sessionKeyCookie, player: player};


                 $.ajax({
                        type: 'POST',
                        data: ajxDataGetMeldedCards,
                        cache: false,
                        dataType: 'JSON',
                        url: 'ajax/showCardsScoreboard.php',
                        success: function(result){

                            var g1 = result.g1;
                            var g2 = result.g2;
                            var g3 = result.g3;
                            var g4 = result.g4;

                            


                            for(var x = 1; x < 5; x++){

                                  for(var i = 0; i < eval('g'+x).length; i++){

                                    if(eval('g'+x)[i] != "Joker"){

                                         var cardNumber = eval('g'+x)[i].substr(0, eval('g'+x)[i].indexOf('OF'));
                                         var cardHouse =  eval('g'+x)[i].substr(eval('g'+x)[i].indexOf("OF") + 2);

                                          var li = '<li>'+
                                            '<a class="card card_2 rank-'+cardNumber+' '+cardHouse+' ui-widget-content handCard" href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+'>'+
                                            '<span class="rank">'+cardNumber+'</span>'+
                                            '<span class="suit">&'+cardHouse+';</span></a></li>';

                                    }else{

                                         var li = '<li><a href="javascript:;" class="handCard ui-widget-content card card_2 joker" data-rank="joker"></a></li>';
                                    }    


                                    $hand.append(li);

                                }

                                $hand.append('<li></li><li></li>');


                            }

                             $playingCards.append($hand);


                            
                        } });

                 /*ASHIT*/

                   return $playingCards;

            


    } 


          /* ---- Shuffle ------- */

          function shuffle(array) {
              var currentIndex = array.length, temporaryValue, randomIndex;

              // While there remain elements to shuffle...
              while (0 !== currentIndex) {

                // Pick a remaining element...
                randomIndex = Math.floor(Math.random() * currentIndex);
                currentIndex -= 1;

                // And swap it with the current element.
                temporaryValue = array[currentIndex];
                array[currentIndex] = array[randomIndex];
                array[randomIndex] = temporaryValue;
              }

           
            return array;
          }


          /** =========== Meld Functions ============== **/


        // check that values match and suits are unique
        function isSameValue(cards){
          var check = _isSameValue(cards, false);
          
          // if the non-wildcards matched, just assume any wildcards are also matches,
          // otherwise, pull the fake jokers and try again
          if(check.isSameValue) {
            return check;
          }
          
          check = _isSameValue(cards, true);
          
          if(!check.isSameValue) {
            check.score = undefined;
          }
          
          return check;
        }

        function _isSameValue(cards, useJokers) {
          var suits = ["clubs","hearts","spades","diams"];
          var jokers = [];
          var remainder = [];
          cards.forEach(function(card){
            ((card.suit === "joker" || (useJokers && card.value === jokerValue)) ? jokers : remainder).push(card);
          });
          if(useJokers) {
          }
          var score = 0;
          var pure = jokers.length === 0;
          var value;
          var matched = remainder.every(function(card){
            if(value) {
              if(value != card.value) {
                return false;
              }
              var suit = suits.indexOf(card.suit);
              if(suit < 0) {
                return false;
              }
              suits.splice(suit,1);
              score += (card.value > 1 && card.value < 10) ? card.value : 10;
              return true;
            }
            value = card.value;
            score += (card.value > 1 && card.value < 10) ? card.value : 10;
            return true;
          });
          return {isPure: pure, isSameValue: matched, score: score};
        }

        // check that suits match; values are unchecked
        function isSameSuit(cards){
          var check = _isSameSuit(cards, false);
          
          if(check.isSameSuit) {
            return check;
          }
          
          check = _isSameSuit(cards, true);
          
          return check;
        }

        function _isSameSuit(cards, useJokers) {
          var jokers = [];
          var remainder = [];
          cards.forEach(function(card){
            (card.suit === "joker" ? jokers : (useJokers && card.value === jokerValue ? jokers : remainder)).push(card);
          });
          var pure = jokers.length === 0;
          var suit;
          var sameSuit = remainder.every(function(card){
            if(!suit){
              suit = card.suit;
              return true;
            }
            return card.suit == suit;
          });
          return {isPure: pure, isSameSuit: sameSuit};
        }

        // check for sequence; suits must match
        function isSequence(cards){
          var sameSuit = isSameSuit(cards);
          if(!sameSuit.isSameSuit) {
            return {isPure:undefined, isSequence: false, score: undefined};
          }
          
          var check = _isSequence(cards, sameSuit, false);
          
          if(check.isSequence) {
            return check;
          }
          
          check = _isSequence(cards, sameSuit, true);
          
          return check;
        }

        function _isSequence(cards, sameSuit, useJokers) {
          var score = 0;
          
          var jokers = [];
          var remainder = cards.filter(function(card){
            if(card.suit === "joker" || (useJokers && card.value === jokerValue)) {
              jokers.push(card);
              return false;
            }
            return true;
          });
          var pure = jokers.length === 0;
          
          if(remainder.length <= 1) {
            return {isPure: false, isSequence: true, score: 0};
          }

          // Sort the non-jokers
          remainder = remainder.sort(function(a, b){
            return a.value - b.value;
          });
          
          // find a sequence
          // rotate through each card to start and count through them, filling in with available jokers as necessary
          for(var i = 0; i < remainder.length; i++){
            var jokerCount = jokers.length;
            var lastValue = remainder[0].value;
            score = (lastValue > 1 && lastValue < 10) ? lastValue: 10;
            var fail = false;
            for(var j = 1; j < remainder.length; j++){
              // aces at the beginning or end, but not in the middle
              if(lastValue >= 14) {
                fail = true;
                break;
              }
              if(lastValue + 1 == remainder[j].value) {
                lastValue++;
                score += (lastValue > 1 && lastValue < 10) ? lastValue: 10;
                continue;
              }
              if(lastValue == 13 && remainder[j].value == 1) {
                lastValue++;
                score += (lastValue > 1 && lastValue < 10) ? lastValue: 10;
                continue;
              }
              if(jokerCount > 0) {
                jokerCount--;
                j--;
                lastValue++;
                score += (lastValue > 1 && lastValue < 10) ? lastValue: 10;
                continue;
              }
              fail = true;
              break;
            }
            // Did we make it through a complete sequence?
            if(!fail){
              return {isPure: pure, isSequence: true, score: score };
            }
            // move bottom card to top and try again
            remainder.unshift(remainder.pop());
          }
          
          return {isPure: undefined, isSequence: false, score: undefined};
        }

        function getSummary(cards, i){
          if(cards.length != 3 && cards.length != 4) {
            // invalid number of cards, return undefined
            return;
          }
          
          var long = cards.length == 4;
          var sameValue = isSameValue(cards);
          var sequence = isSequence(cards);
          var pure = (sameValue.isSameValue && sameValue.isPure) || (sequence.isSequence && sequence.isPure);
          
     /*     return JSON.stringify({
            "cards": cards,
            "isLong": long, 
            "isPure": pure, 
            "isSameValue": sameValue.isSameValue, 
            "isSequence": sequence.isSequence,
            "sameValueScore": sameValue.score,
            "sequenceScore": sequence.score
          });*/

           eval('meldCardEvaluator'+i).push({"cards": cards, "isLong": long, "isPure": pure, "isSameValue": sameValue.isSameValue, "isSequence": sequence.isSequence, "sameValueScore": sameValue.score, "sequenceScore": sequence.score });

           return JSON.stringify( eval(' meldCardEvaluator'+i));
        }


     

        /*function toString(cards) {
          var results = [];
          cards.forEach(function(card){
            if(card.suit === "joker") {
              results.push("JK");
            }
            else {
              var value;
              switch (card.value) {
                case  1: value = "A"; break;
                case 11: value = "J"; break;
                case 12: value = "Q"; break;
                case 13: value = "K"; break;
                default:
                  value = card.value;
                  break;
              }
              results.push(value+card.suit.charAt(0));
            }
          });
          return results.join(' ');
        }

        function handScore(cards) {
          return cards.reduce(function(score, card){
            return score + (card.suit === "joker" ? 0 : ((card.value > 1 && card.value < 10) ? card.value : 10));
          }, 0);
        }*/


          /* show select */

            function removeCardFromGroups(card, cardArray){

                var index = cardArray.indexOf(card);

                if (index > -1) {
                    cardArray.splice(index, 1);
                    return true;
                }


            }


            /*  Get next greater player function */

            function getItem(array, search) {
                return array.reduce(function (r, a) {
                    return a > search && (!r || r > a) ? a : r;
                }, undefined);
            }



         /*  FINAL POINTS RUMMY SCOREBOARD OTHERS */
         
         function pointsRummyFinalScoreboardOthers(nextPlayer, roomIdCookie, gamePlayersCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValue, callback){

            console.log("WWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWW");
         }   



        /* DEAL NEXT ROUND CARDS FOR OTHERS */

       
        function dealNextRoundCardsOthers(nextPlayer, roomIdCookie, gamePlayersCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValue, callback){

             gameStatusFlag = '';
             gameStatusArray.length = 0;

              playersPlayingTemp.length = 0;
              playersPlayingTemp = playersPlaying.slice();

             
              var nextPlayer;
              var isLost = false;

               $('.current-player .playingCardsDiscard .hand').html('');
              
              if(gameTypeCookie != "deals2" && gameTypeCookie != "deals3"){
                $('.drop').show();
             }

                
                /* Check if someone has lost */



                 var ajxData745454 = {'action': 'get-players-out', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                          $.ajax({
                            type: 'POST',
                            data: ajxData745454,
                            dataType: 'json',
                            cache: false,
                            url: 'ajax/getPlayersOut.php',
                            success: function(result){

                                

                             console.log("players out ------------------------ ", result);

                             if($.trim(result.noResult) != 'none'){ 

                                 

                               if(result.playersWhoLost.length > 0){   

                                    for(var i = 0; i < result.playersWhoLost.length; i++){
                                        $('.current-player[data-user="'+parseInt(result.playersWhoLost)+'"]').html("");



                                         if(removeCardFromGroups(result.playersWhoLost[i], playersPlaying)){
                                                console.log("player removed ", playersPlaying);
                                        }

                                        if(parseInt(result.playersWhoLost[i]) == parseInt(userId) ){
                                           isLost = true;
                                           // alert("is lost match");
                                        }



                                     } 



                                    playersPlayingTemp = playersPlaying.slice();


                                    

                                }

                            }

                         }


                     });  



                 /*  Get player scores for display */
            
                 for(var j = 0; j < playersPlaying.length; j++){ 

                    var ajxData703 = {'action': 'get-players', player: playersPlaying[j]};

                          $.ajax({
                            type: 'POST',
                            data: ajxData703,
                            dataType: 'json',
                            cache: false,
                            url: 'ajax/getAllPlayers.php',
                            success: function(player){


                                var ajxData201 = {'action': 'get-scoreboard', roomId: roomIdCookie, player: player.id, sessionKey: sessionKeyCookie};

                                $.ajax({
                                    type: 'POST',
                                    url: 'ajax/getScoreBoardNextRound.php',
                                    cache: false,
                                    data: ajxData201,
                                    dataType: 'json',
                                    success: function(result){ 

                                      var totalPoints = parseInt(result.total_points);
                                      var points = parseInt(result.points);

                                    $('.current-player[data-user="'+player.id+'"] .player_name #score').text(totalPoints);

                                    console.log("player ", player.id ,", score ", totalPoints);


                                   if(gamePlayersCookie == "2"){

                                        
                                            $('.result_sec #score_reports tr[data-user = "'+player.id+'"] #score').text(points);

                                            $('.result_sec #score_reports tr[data-user = "'+player.id+'"] #total_score').text(totalPoints);
                                     
                                     }


                                    }
                                    
                                });   


                            }
                        });                   
                    } 

              setTimeout(function(){  


                     console.log("ISLOST  ----- ", isLost);

                      /* update score of users */
                             var ajxData73303 = {'action': 'update-user-scores', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                              $.ajax({
                                type: 'POST',
                                data: ajxData73303,
                                cache: false,
                                url: 'ajax/updateUserScores.php',
                                success: function(result){
                                    console.log(result);
                                  

                                } });           


                     if(isLost == false){ 

                      $('.result_sec .result_bottom').text("");

                      $('.current-player .toss .playingCards').html("");



                    $('.result_sec').css({'display': 'none'});
                    $('.result_sec tbody[id="score_reports"] tr').remove();      


                      var ajxData205 = {'action': 'deal-cards', roomId: roomIdCookie, sessionKey: sessionKeyCookie, playerId: userId};

                        $.ajax({

                          type: 'POST',
                            url: 'ajax/dealCards.php',
                            cache: false,
                            data: ajxData205,
                            success: function(result){ 

                                if($.trim(result) == "ok"){

                        

                            var ajxData225 = {'action': 'get-cards', roomId: roomIdCookie, playerId: userId, sessionKey: sessionKeyCookie};

                             $.ajax({

                                type: 'POST',
                                url: 'ajax/getMyCards.php',
                                cache: false,
                                data: ajxData225,
                                dataType: 'json',
                                success: function(myCards){ 

                                    setTimeout(function(){

                                        console.log("PLAYERS " + playersPlayingTemp);



                                        console.log("my cards: " + myCards);


                                        for(var j = 0; j < playersPlayingTemp.length; j++){


                                            if(playersPlayingTemp[j] == userId){

                                                for(var i = 0; i < myCards.length; i++){

                                                    cardsInHand.push(myCards[i]);

                                                    if(myCards[i] != "Joker"){


                                                    var cardNumber = myCards[i].substr(0, myCards[i].indexOf('OF'));
                                                    var cardHouse =  myCards[i].substr(myCards[i].indexOf("OF") + 2);

                                                    
                                                        $('.me .playingCards .hand').append(
                                                            '<li><a class="card handCard card_2 rank-'+cardNumber.toUpperCase()+' '+cardHouse+'" href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+'>'+
                                                                '<span class="rank">'+cardNumber.toUpperCase()+'</span>'+
                                                                '<span class="suit">&'+cardHouse+';</span>'+
                                                                '</a></li>');

                                                    }else{
                                                         $('.me .playingCards .hand').append('<li class="ui-sortable-handle"><a href="javascript:;" class="card joker card_2 handCard " data-rank="joker"></a></li>');



                                                    }    

                                                    

                                                }

                                            }

                                            if(playersPlayingTemp[j] != userId){

                                                $('.current-player[data-user='+playersPlayingTemp[j]+'] .playingCards .deck').append('<li>'+
                                                                '<div class="card card_2 back">*</div>'+
                                                            '</li><li>'+
                                                                '<div class="card card_2 back">*</div>'+
                                                            '</li><li>'+
                                                                '<div class="card card_2 back">*</div>'+
                                                            '</li><li>'+
                                                                '<div class="card card_2 back">*</div>'+
                                                            '</li><li>'+
                                                                '<div class="card card_2 back">*</div>'+
                                                            '</li>'
                                                       );

                                            }


                                            console.log("COUNT ", j);


                                        }


                                        $('.player_card_me .hand li a').removeClass('showFoldedCard');
                                        $('.group_blog .playingCards .hand li a').removeClass('showFoldedCard');

                                        $('.player_card_me').show();





                                        /* Get throw card */

                                         var ajxData245 = {'action': 'get-throw-card', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                                               $.ajax({

                                                    type: 'POST',
                                                    url: 'ajax/getThrowCardNextRound.php',
                                                    cache: false,
                                                    data: ajxData245,
                                                    dataType: 'json',
                                                    success: function(result){ 

                                                    
                                                     
                                                     var throwCard = result.throw_card;
                                                     var tossWinner = result.toss_winner;

                                             

                                                    var cardNumber = throwCard.substr(0, throwCard.indexOf('OF'));
                                                    var cardHouse =  throwCard.substr(throwCard.indexOf("OF") + 2);

                                                      if(myCards[i] != "Joker"){       


                                                            $('.card-throw .playingCards').html('<a href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+' id="cardDeckSelectShow'+userId+'" class="cardDeckSelect noSelect">'+
                                                                '<div class="card card_2 rank-'+cardNumber+' '+cardHouse+'">'+
                                                                '<span class="rank">'+cardNumber+'</span>'+
                                                                '<span class="suit">&'+cardHouse+';</span>'+
                                                                '</div></a>');
                                                        }else{

                                                      
                                                            $('.card-throw .playingCards').append(' <a href="javascript:;" data-rank="joker" id="cardDeckSelectShow'+userId+'" class="cardDeckSelect noSelect"><div class="card joker card_2"></div></a>');
                                                      

                                                        } 


                                                     console.log('throw card : '+ throwCard);


                                                       var ajxData201 = {'action': 'get-joker', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                                                            $.ajax({
                                                                type: 'POST',
                                                                url: 'ajax/getJokerNextRound.php',
                                                                cache: false,
                                                                data: ajxData201,
                                                                success: function(resultJoker){ 

                                                                
                                                                console.log('joker: ' + resultJoker);
                                                                
                                                                 // <span class="card card_2 joker">
           
                                                                if(resultJoker != "Joker"){

                                                                var cardNumber = resultJoker.substr(0, resultJoker.indexOf('OF'));
                                                                var cardHouse =  resultJoker.substr(resultJoker.indexOf("OF") + 2);

                                                                $('.joker-assign .playingCards').html(

                                                                '<span class="card card_2 rank-'+cardNumber.toLowerCase()+' '+cardHouse+'" href="#">'+
                                                                '<span class="rank">'+cardNumber+'</span>'+
                                                                '<span class="suit">&'+cardHouse+';</span>'+
                                                                '</span>');

                                                                    
                                                               }else{

                                                                $('.joker-assign .playingCards').html('<span class="card card_2 joker"></span>');
                                                               }     



                                                       

                                                  
                                                                }

                                                            });


                                                        


                                                             /* configuring buttons */

                                                       if(tossWinner == userId){
                                                            $('.cardDeckSelect').removeClass('noSelect').addClass('clickable');
                                                            $('.cardDeckSelectShow').removeClass('noSelect').addClass('clickable');

                                                             $('.drop button').attr('disabled', false);
                                                             $('.drop button').css({'cursor':'pointer'});
                                                        

                                                       }else{
                                                             $('.drop button').attr('disabled', true);
                                                            $('.drop button').css({'cursor':'default'});
                                                       }

                                                    }
                                                });        


                                      }, 3000);
                                      
                                     }
                                     
                                    });    


                                } } });


                             }else{
                                $('.loading_container').css({'display':'block'});
                                $('.loading_container .popup .popup_cont').text("You have lost the game!");

                                var ajxData704 = {'action': 'update-my-status-2', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                $.ajax({
                                    type: 'POST',
                                    data: ajxData704,
                                    cache: false,
                                    url: 'ajax/updateMyStatus2.php',
                                    success: function(results){
                                        console.log(results);
                                        if(results){
                                           console.log("status updated");
                                           // alert("status updated");

                                        }
                                    } }); 
                             }


                              callback();

                        }, 5000);     

                       

            

        }

        /* Points Rummy Final Score */

        /*PAAAAAAL*/

        function pointsRummyFinalScoreboardMelder(roomIdCookie, gamePlayersCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValue, callback){

             gameStatusFlag = '';
             gameStatusArray.length = 0;
             gameStatusFlagMeld = 'over';
             playersWhoLostArray = [];
             playerWhoDidNotLostArray = [];

            console.log("ZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZ");

            /* get winner */

             var ajxDataPWON = {'action': 'get-player-won', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

              $.ajax({
                type: 'POST',
                data: ajxDataPWON,
                dataType: 'json',
                cache: false,
                url: 'ajax/getPlayerWon.php',
                success: function(player){
                    if($.trim(player) == 0){
                        /* player won not found yet */

                        var ajxData745454 = {'action': 'get-players-out', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                          $.ajax({
                            type: 'POST',
                            data: ajxData745454,
                            dataType: 'json',
                            cache: false,
                            url: 'ajax/getPlayersOut.php',
                            success: function(result){

                                

                             console.log("players out ------------------------ ", result);

                             if($.trim(result.noResult) != 'none'){ 

                                // playersWhoLostArray
                                 //playersWhoLostArray = result.playersWhoLost.slice();


                               if(result.playersWhoLost.length > 0){

                                    for(var i = 0; i < result.playersWhoLost.length; i++){
                                        
                                        /*SUDIP*/
                                        playersWhoLostArray.push(parseInt(result.playersWhoLost[i]));


                                     var ajxData703 = {'action': 'get-players', player: result.playersWhoLost[i]};

                                      $.ajax({
                                        type: 'POST',
                                        data: ajxData703,
                                        dataType: 'json',
                                        cache: false,
                                        url: 'ajax/getAllPlayers.php',
                                        success: function(player){

                                            console.log(player.id + ' ' + player.name);

                                            var ajxData704 = {'action': 'get-scoreboard', roomId: roomIdCookie, player: player.id, sessionKey: sessionKeyCookie};

                                              $.ajax({
                                                type: 'POST',
                                                data: ajxData704,
                                                dataType: 'json',
                                                cache: false,
                                                url: 'ajax/getScoreBoard.php',
                                                success: function(results){

                                                    

                                                    console.log("Player id checking ", player.id);

                                                    var points = results.points;
                                                    var totalPts = results.total_points;
                                                    var playerWon = results.player_won;

                                                     $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="count"]').text(points);

                                                      $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="result"]').text('Lost');
                                        
                                                     if(totalPts != 0.00){
                                                        $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_chips"]').text('-'+totalPts);

                                                        if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                          $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]').html(showCardsInScoreboard(player.id, roomIdCookie, sessionKeyCookie));

                                                        }
                                                     }else{
                                                         $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_chips"]').text('');
                                                     }

                                                    
                                                     



                                                } })




                                        } });



                                    }

                                    console.log("COMING FROM PL LOST ARR ", playersWhoLostArray);

                                    jQuery.grep(playersPlayingTemp, function(el) {
                                        if (jQuery.inArray(el, playersWhoLostArray) == -1) playerWhoDidNotLostArray.push(el);
                                    });



                                console.log("PLAYER DID NOT LOST AAAAAAA ", playerWhoDidNotLostArray);



                                    /* Find the winner */

                                   
                                       
                                         /* get total score of all the players */
                                          var ajxDataGetPlScore = {'action': 'get-all-scores-pr', roomId: roomIdCookie, sessionKey: sessionKeyCookie, betValue: betValue, playerWon: parseInt(playerWhoDidNotLostArray[0])};

                                          $.ajax({
                                            type: 'POST',
                                            data: ajxDataGetPlScore,
                                            dataType: 'json',
                                            cache: false,
                                            url: 'ajax/getAllScoresPR.php',
                                            success: function(result){
                                                    
                                                    console.log(result);
                                                    // alert("Calculated");

                                                    $('.result_sec tbody[id="score_reports"] tr[data-user="'+result.playerWon+'"] td[id="count"]').text(0);

                                                    $('.result_sec tbody[id="score_reports"] tr[data-user="'+result.playerWon+'"] td[id="total_chips"]').text('+'+(result.winningAmount).toFixed(2));

                                                    if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+result.playerWon+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                       $('.result_sec tbody[id="score_reports"] tr[data-user="'+result.playerWon+'"] td[id="cards"]').html(showCardsInScoreboard(result.playerWon, roomIdCookie, sessionKeyCookie));

                                                    }
                                                     

                                                     var signalOthers = {type: 'points-rummy-fso', message: 'points game get scoreboard others', winner: result.playerWon, playersLostArr: playersWhoLostArray, winningAmount: result.winningAmount};

                                                      connection.send(JSON.stringify(signalOthers));

                                                      if(parseInt(userId) === parseInt(result.playerWon)){
                                                         $('.loading_container').css({'display':'block'});
                                                         $('.loading_container .popup .popup_cont').text("You have won the game!");

                                                      }else{
                                                        $('.loading_container').css({'display':'block'});
                                                        $('.loading_container .popup .popup_cont').text("You have lost the game!");

                                                      }




                                             } });

                                          /*SAGNIK*/





                                     


                               } 

                             }
                             
                           } })    


                    }else{


                          var ajxData745454 = {'action': 'get-players-out', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                          $.ajax({
                            type: 'POST',
                            data: ajxData745454,
                            dataType: 'json',
                            cache: false,
                            url: 'ajax/getPlayersOut.php',
                            success: function(result){

                                

                             console.log("players out ------------------------ ", result);

                             if($.trim(result.noResult) != 'none'){ 

                                // playersWhoLostArray
                                 //playersWhoLostArray = result.playersWhoLost.slice();


                               if(result.playersWhoLost.length > 0){

                                    for(var i = 0; i < result.playersWhoLost.length; i++){
                                        
                                        /*SUDIP*/
                                        playersWhoLostArray.push(parseInt(result.playersWhoLost[i]));


                                     var ajxData703 = {'action': 'get-players', player: result.playersWhoLost[i]};

                                      $.ajax({
                                        type: 'POST',
                                        data: ajxData703,
                                        dataType: 'json',
                                        cache: false,
                                        url: 'ajax/getAllPlayers.php',
                                        success: function(player){

                                            console.log(player.id + ' ' + player.name);

                                            var ajxData704 = {'action': 'get-scoreboard', roomId: roomIdCookie, player: player.id, sessionKey: sessionKeyCookie};

                                              $.ajax({
                                                type: 'POST',
                                                data: ajxData704,
                                                dataType: 'json',
                                                cache: false,
                                                url: 'ajax/getScoreBoard.php',
                                                success: function(results){

                                                    

                                                    console.log("Player id checking ", player.id);

                                                    var points = results.points;
                                                    var totalPts = results.total_points;
                                                    var playerWon = results.player_won;

                                                    $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="result"]').text('Lost');

                                                     $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="count"]').text(points);
                                        
                                                     if(totalPts != 0.00){
                                                        $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_chips"]').text('-'+totalPts);

                                                         if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]').html(showCardsInScoreboard(player.id, roomIdCookie, sessionKeyCookie));

                                                            }


                                                     }else{
                                                         $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_chips"]').text('');
                                                     }

                                                    

                                                    




                                                } })




                                        } });



                                    }

                                       
                                         /* get total score of all the players */
                                          var ajxDataGetPlScore = {'action': 'get-all-scores-pr', roomId: roomIdCookie, sessionKey: sessionKeyCookie, betValue: betValue, playerWon: $.trim(player)};

                                          $.ajax({
                                            type: 'POST',
                                            data: ajxDataGetPlScore,
                                            dataType: 'json',
                                            cache: false,
                                            url: 'ajax/getAllScoresPR.php',
                                            success: function(result){
                                                    console.log(result);
                                                   // alert("Calculated");

                                                    $('.result_sec tbody[id="score_reports"] tr[data-user="'+result.playerWon+'"] td[id="count"]').text(0);

                                                    $('.result_sec tbody[id="score_reports"] tr[data-user="'+result.playerWon+'"] td[id="total_chips"]').text('+'+(result.winningAmount).toFixed(2));

                                                    if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+result.playerWon+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                       $('.result_sec tbody[id="score_reports"] tr[data-user="'+result.playerWon+'"] td[id="cards"]').html(showCardsInScoreboard(result.playerWon, roomIdCookie, sessionKeyCookie));

                                                    }

                                                    var signalOthers = {type: 'points-rummy-fso', message: 'points game get scoreboard others', winner: result.playerWon, playersLostArr: playersWhoLostArray, winningAmount: result.winningAmount};

                                                      connection.send(JSON.stringify(signalOthers));

                                                       if(parseInt(userId) === parseInt(result.playerWon)){
                                                         $('.loading_container').css({'display':'block'});
                                                         $('.loading_container .popup .popup_cont').text("You have won the game!");

                                                          setTimeout(function(){
                                                            $('.loading_container').hide();
                                                            $('.loading_container .popup .popup_cont').text();


                                                            $('.popup_rejoin').show();
                                                            $('.popup_rejoin .popup_with_button_cont p').text("Do you want to rejoin?");
                                                            
                                                         }, 4000);

                                                      }else{
                                                        $('.loading_container').css({'display':'block'});
                                                        $('.loading_container .popup .popup_cont').text("You have lost the game!");

                                                         setTimeout(function(){
                                                            $('.loading_container').hide();
                                                            $('.loading_container .popup .popup_cont').text();


                                                            $('.popup_rejoin').show();
                                                            $('.popup_rejoin .popup_with_button_cont p').text("Do you want to rejoin?");
                                                            
                                                         }, 4000);


                                                      }
                                                     
                                             } });





                                     


                               } 

                             }
                             
                           } }) 



                    }

                } });    
        
         }




        /*  DEAL NEXT ROUND CARDS FOR MYSELF   */

        
       

        function dealNextRoundCardsMelder(roomIdCookie, gamePlayersCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValue, callback){

             // alert("cards melder next round function called");

             gameStatusFlag = '';
             gameStatusArray.length = 0;

              playersPlayingTemp.length = 0;
              playersPlayingTemp = playersPlaying.slice();
              
              
              var nextPlayer;
              var tossWinner;
              var isLost = false;
              var gameStatusFlagMeld;
              var playerWon;
              var dealsRummy2 = false;
              var dealsRummy3 = false;
              var dealsRummy3CheckDiff = false;
              var myScore;
              var otherScore;
              var other;

              $('#validateCards'+userId).attr('disabled', false);

               $('.current-player .playingCardsDiscard .hand').html('');
               


               if(gameTypeCookie != "deals2" && gameTypeCookie != "deals3"){
                    $('.drop').show();
                }




              /* update round  */

              var ajxData74545323 = {'action': 'update-round', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

              $.ajax({
                type: 'POST',
                data: ajxData74545323,
                cache: false,
                url: 'ajax/updateRound.php',
                success: function(result){

                    console.log("updated round ~~~~~~~~~~~~~~~ ", result);

                } });    



                if(gameTypeCookie != "deals2" && gameTypeCookie != "deals3"){

                    // alert("lets just check....");

                 var ajxData745454 = {'action': 'get-players-out', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                          $.ajax({
                            type: 'POST',
                            data: ajxData745454,
                            dataType: 'json',
                            cache: false,
                            url: 'ajax/getPlayersOut.php',
                            success: function(result){

                                

                             console.log("players out ------------------------ ", result);

                             if($.trim(result.noResult) != 'none'){ 

                               if(result.playersWhoLost.length > 0){   

                                    for(var i = 0; i < result.playersWhoLost.length; i++){
                                        $('.current-player[data-user="'+parseInt(result.playersWhoLost)+'"]').html("");



                                        if(removeCardFromGroups(parseInt(result.playersWhoLost[i]), playersPlaying)){
                                                console.log("player removed ", playersPlaying);
                                                // alert("player removed");
                                        }

                                        if(parseInt(result.playersWhoLost[i]) == parseInt(userId) ){
                                           isLost = true;
                                           // alert("is lost match");
                                        }

                                          if(playersPlaying.length - result.playersWhoLost.length != 0){
                                                gameStatusFlagMeld = "cont"
                                          }else{
                                                gameStatusFlagMeld = "over";
                                          }
                                        



                                     } 



                                    playersPlayingTemp = playersPlaying.slice();




                                    

                                }

                            }else{
                                gameStatusFlagMeld = "cont";
                            }


                            
                            if(playersPlayingTemp.length == 1){

                             gameStatusFlagMeld = "over";

                            
                             var ajxData854 = { 'action': 'update-player-won', roomId: roomIdCookie, player: parseInt(playersPlayingTemp[0]), sessionKey: sessionKeyCookie };

                             $.ajax({
                                type: 'POST',
                                data: ajxData854,
                                cache: false,
                                url: 'ajax/updatePlayerWon.php',
                                success: function(result){
                                    console.log(result);
                                    playerWon = parseInt(playersPlayingTemp[0]);
                            } });


                         }





                         }


                    


                     });


                 }   


                     /* For DEAL'S RUMMY (BEST OF 2 ) */
                     
                     if(gameTypeCookie == "deals2" || gameTypeCookie == "deals3"){

                         gameStatusFlagMeld = "cont";
                        /* check round */

                         var ajxData854 = { 'action': 'get-round', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                             $.ajax({
                                type: 'POST',
                                data: ajxData854,
                                cache: false,
                                url: 'ajax/getRound.php',
                                success: function(round){
                                    console.log("current round ", round);

                                    if(gameTypeCookie == "deals2" && round >= 3){
                                        dealsRummy2 = true;
                                       
                                    }else if(gameTypeCookie == "deals3" && round >= 4){
                                        dealsRummy3 = true;
                                       
                                    }else if(gameTypeCookie == "deals3" && round >=3){
                                        dealsRummy3CheckDiff = true;
                                        dealsRummy3 = true;
                                        // alert("dealsRummy3CheckDiff hit");
                                    }

                                    
                            } });





                     }     
                          




                     


            setTimeout(function(){  


                      console.log("gameStatusMeld Flag ------------------------ ", gameStatusFlagMeld);      

                   /*  get last toss winner from  db */

                    if(gameStatusFlagMeld == "cont"){        



                            var ajxData235 = {'action': 'get-toss-winner', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                            $.ajax({

                                type: 'POST',
                                url: 'ajax/getTossWinner.php',
                                cache: false,
                                data: ajxData235,
                                success: function(lastWinner){ 



                                console.log("toss winner received now :) : " + lastWinner);

                              
                               


                                if(getItem(playersPlaying, parseInt(lastWinner)) ){
                                  
                                  nextPlayer = getItem(playersPlaying, parseInt(lastWinner));
                                     
                                }else{
                                  nextPlayer = playersPlaying[0];
                                     
                                }

                                console.log("nextplayer ", nextPlayer);


                           /*  Do the card deck selection  process  */
                       
                            var ajxData1060 = {'action': 'next-round-process', roomId: roomIdCookie, playerTurn: nextPlayer, sessionKey: sessionKeyCookie};

                             $.ajax({
                                type: 'POST',
                                url: 'ajax/nextRoundProcess.php',
                                cache: false,
                                data: ajxData1060,
                                // dataType: "json",
                                success: function(result){
                                    console.log(result);
                                    if($.trim(result) == "ok"){

                                        /* show message of next player */


                                        if(parseInt(userId) == parseInt(nextPlayer)){
                                            $('.game_message').html('<p>Your turn</p>').show();

                                            $('.drop button').attr('disabled', false);
                                            $('.drop button').css({'cursor':'pointer'});
                                        }else{

                                             $('.drop button').attr('disabled', true);
                                             $('.drop button').css({'cursor':'default'});

                                        /* get-player-name */

                                        var ajxData20 = {'action': 'get-player-name', player: parseInt(nextPlayer)};

                                          $.ajax({
                                              type: 'POST',
                                              url: 'ajax/getPlayerName.php',
                                              cache: false,
                                              data: ajxData20,
                                              success: function(theName){
                                                $('.game_message').html('<p>' + theName + ' will play</p>').show();
                                                
                                              } }); 

                                        }

                                            // alert("next round process hit!!!!");

                                            var ajxData245 = {'action': 'get-throw-card', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                                           $.ajax({

                                                type: 'POST',
                                                url: 'ajax/getThrowCard.php',
                                                cache: false,
                                                data: ajxData245,
                                                dataType: 'json',
                                                success: function(result){ 

                                                
                                                 
                                                 var throwCard = result.throw_card;
                                                 
                                                 tossWinner = result.toss_winner;

                                               

                                                  if(throwCard != "Joker"){ 

                                                   var cardNumber = throwCard.substr(0, throwCard.indexOf('OF'));
                                                   var cardHouse =  throwCard.substr(throwCard.indexOf("OF") + 2);      

                                                        if(tossWinner == userId){

                                                            $('.card-throw .playingCards').html('<a href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+' id="cardDeckSelectShow'+userId+'" class="cardDeckSelect clickable">'+
                                                            '<div class="card card_2 rank-'+cardNumber+' '+cardHouse+'">'+
                                                            '<span class="rank">'+cardNumber+'</span>'+
                                                            '<span class="suit">&'+cardHouse+';</span>'+
                                                            '</div></a>');


                                                        }else{

                                                             $('.card-throw .playingCards').html('<a href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+' id="cardDeckSelectShow'+userId+'" class="cardDeckSelect noSelect">'+
                                                            '<div class="card card_2 rank-'+cardNumber+' '+cardHouse+'">'+
                                                            '<span class="rank">'+cardNumber+'</span>'+
                                                            '<span class="suit">&'+cardHouse+';</span>'+
                                                            '</div></a>');


                                                        }

                                               }else{

                                                    if(tossWinner == userId){
                                                        $('.card-throw .playingCards').append(' <a href="javascript:;" data-rank="joker" id="cardDeckSelectShow'+userId+'" class="cardDeckSelect clickable"><div class="card joker card_2"></div></a>');
                                                    }else{
                                                        $('.card-throw .playingCards').append(' <a href="javascript:;" data-rank="joker" id="cardDeckSelectShow'+userId+'" class="cardDeckSelect noSelect"><div class="card joker card_2"></div></a>');
                                                    }

                                                } 







                                   var ajxData201 = {'action': 'get-joker', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                                        $.ajax({
                                            type: 'POST',
                                            url: 'ajax/getJokerNextRound.php',
                                            cache: false,
                                            data: ajxData201,
                                            success: function(resultJoker){ 

                                            
                                            console.log('joker: ' + resultJoker);
                                                    

                                            if(resultJoker != "Joker"){

                                                var cardNumber = resultJoker.substr(0, resultJoker.indexOf('OF'));
                                                var cardHouse =  resultJoker.substr(resultJoker.indexOf("OF") + 2);

                                                $('.joker-assign .playingCards').html(

                                                '<span class="card card_2 rank-'+cardNumber.toLowerCase()+' '+cardHouse+'" href="#">'+
                                                '<span class="rank">'+cardNumber+'</span>'+
                                                '<span class="suit">&'+cardHouse+';</span>'+
                                                '</span>');

                                                    
                                               }else{

                                                $('.joker-assign .playingCards').html('<span class="card card_2 joker"></span>');
                                               } 

                                            var ajxData260 = {'action': 'current-player', roomId: roomIdCookie, player: parseInt(tossWinner), sessionKey: sessionKeyCookie };

                                                 $.ajax({

                                                    type: 'POST',
                                                    url: 'ajax/updateCurrentPlayer.php',
                                                    cache: false,
                                                    data: ajxData260,
                                                    success: function(result){ 
                                                        if($.trim(result) == "ok"){
                                                            console.log("current player updated");
                                                        }
                                                       
                                                    }
                                                 });       

                                            }

                                    });

                                } });          


                            } } }); 

                                                                                            
                             } });                                                 

                             

                              
                                console.log("DealNextRoundFUnction HIt ", playersPlayingTemp);

                                
                                 for(var j = 0; j < playersPlaying.length; j++){ 

                                 var ajxData703 = {'action': 'get-players', player: playersPlaying[j]};

                                                  $.ajax({
                                                    type: 'POST',
                                                    data: ajxData703,
                                                    dataType: 'json',
                                                    cache: false,
                                                    url: 'ajax/getAllPlayers.php',
                                                    success: function(player){


                                                        var ajxData201 = {'action': 'get-scoreboard', roomId: roomIdCookie, player: player.id, sessionKey: sessionKeyCookie};

                                                        $.ajax({
                                                            type: 'POST',
                                                            url: 'ajax/getScoreBoardNextRound.php',
                                                            cache: false,
                                                            data: ajxData201,
                                                            dataType: 'json',
                                                            success: function(result){ 

                                                            var totalPoints = parseInt(result.total_points);
                                                            var points = parseInt(result.points);

                                                            // console.log("player ", player.id ,", total.score ", totalPoints, ' points ', points);

                                                            $('.current-player[data-user="'+player.id+'"] .player_name #score').text(totalPoints);

                                                             /*  for 2 players game only  */

                                                             if(gamePlayersCookie == "2"){

                                                               
                                                                    $('.result_sec #score_reports tr[data-user = "'+player.id+'"] #score').text(points);

                                                                    $('.result_sec #score_reports tr[data-user = "'+player.id+'"] #total_score').text(totalPoints);


                                                                    if(dealsRummy2 == true || dealsRummy3 == true || dealsRummy3CheckDiff == true){

                                                                      if(parseInt(userId) == parseInt(player.id)){
                                                                        myScore = totalPoints;
                                                                      }else{
                                                                        otherScore = totalPoints;
                                                                        other = player.id;
                                                                      }

                                                                    }

                                                              }







                                                            }
                                                            
                                                        });   


                                                    }
                                                });                   
                                            }

                                     }

                            }, 6000);
          

                        

                           /* Checking for deals Rummy */
                          setTimeout(function(){  

                            console.log("dealsrummy2", dealsRummy2);
                            console.log("dealsRummy3", dealsRummy3);

                           if(dealsRummy2 == true || dealsRummy3 == true){

                            // alert("dealsRummy 3 algo");

                                if(parseInt(myScore) > parseInt(otherScore)){
                                    

                                    if(dealsRummy3CheckDiff == true){
                                        // alert("yo..");
                                        
                                        if(parseInt(myScore) - parseInt(otherScore) >= 80){

                                             // alert("dealsRummy3CheckDiff hit hoho");
                                            /* Update player won */

                                        console.log("playersPlayingTemp1 test", playersPlayingTemp);


                                            var ajxData854 = { 'action': 'update-player-won', roomId: roomIdCookie, player: parseInt(other), sessionKey: sessionKeyCookie };

                                                 $.ajax({
                                                    type: 'POST',
                                                    data: ajxData854,
                                                    cache: false,
                                                    url: 'ajax/updatePlayerWon.php',
                                                    success: function(result){
                                                     
                                                        playerWon = parseInt(other);

                                                          /* Update other player lost */

                                                          var ajxData852222 = { 'action': 'update-my-status', roomId: roomIdCookie, player: parseInt(userId), sessionKey: sessionKeyCookie };

                                                         $.ajax({
                                                            type: 'POST',
                                                            data: ajxData852222,
                                                            cache: false,
                                                            url: 'ajax/updateMyStatus.php',
                                                            success: function(result){

                                                        if(removeCardFromGroups(parseInt(userId), playersPlayingTemp)){
                                                            console.log("player removed ", playersPlayingTemp);
                                                        }


                                                                 var signal14555 = {type: 'update-players-playing', message: 'updating players playing', playersPlayingTemp: playersPlayingTemp}
                                            
                                                                 connection.send(JSON.stringify(signal14555));    

                                                                 console.log("mysscore ", myScore);
                                                                 console.log("other score ", otherScore);

                                                                   /* I lost, gameover */
                                                                    gameStatusFlagMeld = "over";
                                                                    isLost = true;

                                                                    // alert("hit 1");

                                                             

                                                                   console.log("is lost test check ", isLost); 
                                                                   console.log("playersPlayingTemp2 test", playersPlayingTemp);
                                                               
                                                               
                                                        } });

                                                

                                                

                                                } });





                                        }else if(parseInt(myScore) - parseInt(otherScore) < 80){

                                            gameStatusFlagMeld = "cont";
                                            isLost = false;


                                        }
                                    

                                    }else{

                                  
                                    /* Update player won */

                                    console.log("playersPlayingTemp1 test", playersPlayingTemp);


                                        var ajxData854 = { 'action': 'update-player-won', roomId: roomIdCookie, player: parseInt(other), sessionKey: sessionKeyCookie };

                                             $.ajax({
                                                type: 'POST',
                                                data: ajxData854,
                                                cache: false,
                                                url: 'ajax/updatePlayerWon.php',
                                                success: function(result){
                                                 
                                                    playerWon = parseInt(other);

                                                      /* Update other player lost */

                                                      var ajxData852222 = { 'action': 'update-my-status', roomId: roomIdCookie, player: parseInt(userId), sessionKey: sessionKeyCookie };

                                                     $.ajax({
                                                        type: 'POST',
                                                        data: ajxData852222,
                                                        cache: false,
                                                        url: 'ajax/updateMyStatus.php',
                                                        success: function(result){

                                                        if(removeCardFromGroups(parseInt(userId), playersPlayingTemp)){
                                                                console.log("player removed ", playersPlayingTemp);
                                                        }


                                                             var signal14555 = {type: 'update-players-playing', message: 'updating players playing', playersPlayingTemp: playersPlayingTemp}
                                        
                                                             connection.send(JSON.stringify(signal14555));    

                                                             console.log("mysscore ", myScore);
                                                             console.log("other score ", otherScore);

                                                               /* I lost, gameover */
                                                                gameStatusFlagMeld = "over";
                                                                isLost = true;

                                                                // alert("hit 1");

                                                         

                                                               console.log("is lost test check ", isLost); 
                                                               console.log("playersPlayingTemp2 test", playersPlayingTemp);
                                                           
                                                           
                                                    } });

                                            

                                            

                                            } });


                                        }     
                                   

                                }else if(parseInt(myScore) < parseInt(otherScore)){

                                    /* Update player won */

                                    console.log("playersPlayingTemp1 test", playersPlayingTemp);

                                     if(dealsRummy3CheckDiff == true){
                                        // alert("yo.. 2");
                                          if(parseInt(otherScore) - parseInt(myScore) >= 80){

                                            
                                             // alert("dealsRummy3CheckDiff hit hoho 2");


                                        var ajxData854 = { 'action': 'update-player-won', roomId: roomIdCookie, player: parseInt(userId), sessionKey: sessionKeyCookie };

                                             $.ajax({
                                                type: 'POST',
                                                data: ajxData854,
                                                cache: false,
                                                url: 'ajax/updatePlayerWon.php',
                                                success: function(result){
                                                 
                                                    playerWon = parseInt(userId);

                                                      /* Update other player lost */

                                                      var ajxData852222 = { 'action': 'update-my-status', roomId: roomIdCookie, player: parseInt(other), sessionKey: sessionKeyCookie };

                                                     $.ajax({
                                                        type: 'POST',
                                                        data: ajxData852222,
                                                        cache: false,
                                                        url: 'ajax/updateMyStatus.php',
                                                        success: function(result){

                                                            if(removeCardFromGroups(parseInt(other), playersPlayingTemp)){
                                                                console.log("player removed ", playersPlayingTemp);
                                                            }


                                                             var signal14555 = {type: 'update-players-playing', message: 'updating players playing', playersPlayingTemp: playersPlayingTemp}
                                        
                                                             connection.send(JSON.stringify(signal14555));    

                                                             console.log("mysscore ", myScore);
                                                             console.log("other score ", otherScore);

                                                               /* I lost, gameover */
                                                                gameStatusFlagMeld = "over";
                                                                isLost = false;

                                                                // alert("hit 2");

                                                         

                                                            console.log("is lost test check ", isLost); 
                                                            console.log("playersPlayingTemp2 test", playersPlayingTemp);
                                                           
                                                           
                                                    } });

                                            

                                            

                                            } });




                                          }else if(parseInt(otherScore) - parseInt(myScore) < 80){
                                          
                                             gameStatusFlagMeld = "cont";
                                             isLost = false;


                                          }  


                                     }else{   


                                        var ajxData854 = { 'action': 'update-player-won', roomId: roomIdCookie, player: parseInt(userId), sessionKey: sessionKeyCookie };

                                             $.ajax({
                                                type: 'POST',
                                                data: ajxData854,
                                                cache: false,
                                                url: 'ajax/updatePlayerWon.php',
                                                success: function(result){
                                                 
                                                    playerWon = parseInt(userId);

                                                      /* Update other player lost */

                                                      var ajxData852222 = { 'action': 'update-my-status', roomId: roomIdCookie, player: parseInt(other), sessionKey: sessionKeyCookie };

                                                     $.ajax({
                                                        type: 'POST',
                                                        data: ajxData852222,
                                                        cache: false,
                                                        url: 'ajax/updateMyStatus.php',
                                                        success: function(result){

                                                            if(removeCardFromGroups(parseInt(other), playersPlayingTemp)){
                                                                console.log("player removed ", playersPlayingTemp);
                                                              }


                                                             var signal14555 = {type: 'update-players-playing', message: 'updating players playing', playersPlayingTemp: playersPlayingTemp}
                                        
                                                             connection.send(JSON.stringify(signal14555));    

                                                             console.log("mysscore ", myScore);
                                                             console.log("other score ", otherScore);

                                                               /* I lost, gameover */
                                                                gameStatusFlagMeld = "over";
                                                                isLost = false;

                                                                // alert("hit 2");

                                                         

                                                            console.log("is lost test check ", isLost); 
                                                            console.log("playersPlayingTemp2 test", playersPlayingTemp);
                                                           
                                                           
                                                    } });

                                            

                                            

                                            } });

                                       }      

                                
                                }else if(parseInt(myScore) - parseInt(otherScore) == 0){
                                    /* tie, continue */
                                    gameStatusFlagMeld = "cont";
                                    isLost = false;

                                     // alert("hit 3");
                                     console.log("mysscore ", myScore);
                                     console.log("other score ", otherScore);

                                }


                           }


                        }, 7000);  


                      

                    setTimeout(function(){ 

                        console.log("just checking is lost ", isLost); 

                      if(gameStatusFlagMeld == "cont"){ 

                           setTimeout(function(){ 


                            console.log("ISLOST ------------- ", isLost);

                            if(isLost == false){


                                 $('.result_sec .result_bottom').text("");

                                 $('.current-player .toss .playingCards').html("");



                                $('.result_sec').css({'display': 'none'});
                                $('.result_sec tbody[id="score_reports"] tr').remove();



                                 var ajxData205 = {'action': 'deal-cards', roomId: roomIdCookie, playerId: userId, sessionKey: sessionKeyCookie};

                                    $.ajax({

                                        type: 'POST',
                                        url: 'ajax/dealCards.php',
                                        cache: false,
                                        data: ajxData205,
                                        success: function(result){ 

                                            if($.trim(result) == "ok"){

                                                   
                                                var ajxData225 = {'action': 'get-cards', roomId: roomIdCookie, playerId: userId, sessionKey: sessionKeyCookie};

                                                 $.ajax({

                                                    type: 'POST',
                                                    url: 'ajax/getMyCards.php',
                                                    cache: false,
                                                    data: ajxData225,
                                                    dataType: 'json',
                                                    success: function(myCards){ 

                                                        setTimeout(function(){



                                                            console.log("PLAYERS " + playersPlayingTemp); 


                                                            console.log("my cards: " + myCards);


                                                            for(var j = 0; j < playersPlayingTemp.length; j++){


                                                                if(playersPlayingTemp[j] == userId){

                                                                    for(var i = 0; i < myCards.length; i++){

                                                                            cardsInHand.push(myCards[i]);

                                                                            

                                                                            if(myCards[i] != "Joker"){

                                                                                var cardNumber = myCards[i].substr(0, myCards[i].indexOf('OF'));
                                                                                var cardHouse =  myCards[i].substr(myCards[i].indexOf("OF") + 2);

                                                                                $('.me .playingCards .hand').append(
                                                                                    '<li><a class="card handCard card_2 rank-'+cardNumber.toUpperCase()+' '+cardHouse+'" href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+'>'+
                                                                                        '<span class="rank">'+cardNumber.toUpperCase()+'</span>'+
                                                                                        '<span class="suit">&'+cardHouse+';</span>'+
                                                                                        '</a></li>');

                                                                            }else{
                                                                                 $('.me .playingCards .hand').append('<li class="ui-sortable-handle"><a href="javascript:;" class="card joker card_2 handCard " data-rank="joker"></a></li>');



                                                                            }    

                                                                            

                                                                        }

                                                                    }

                                                                    if(playersPlayingTemp[j] != userId){

                                                                        $('.current-player[data-user='+playersPlayingTemp[j]+'] .playingCards .deck').append('<li>'+
                                                                                        '<div class="card card_2 back">*</div>'+
                                                                                    '</li><li>'+
                                                                                        '<div class="card card_2 back">*</div>'+
                                                                                    '</li><li>'+
                                                                                        '<div class="card card_2 back">*</div>'+
                                                                                    '</li><li>'+
                                                                                        '<div class="card card_2 back">*</div>'+
                                                                                    '</li><li>'+
                                                                                        '<div class="card card_2 back">*</div>'+
                                                                                    '</li>'
                                                                               );

                                                                    }


                                                                    console.log("COUNT ", j);


                                                                }


                                                                 // console.log('throw card : '+ throwCard);

                                                                $('.player_card_me .hand li a').removeClass('showFoldedCard');
                                                                $('.group_blog .playingCards .hand li a').removeClass('showFoldedCard');

                                                                $('.player_card_me').show();
                                                                
                                                              


                                                        }, 3000);
                      
                                                    } });    
                                                
                                                } } });

                                            /* configuring buttons */

                                           if(tossWinner == userId){
                                                $('.cardDeckSelect').removeClass('noSelect').addClass('clickable');
                                            }

                                        }else{
                                            $('.loading_container').css({'display':'block'});
                                            $('.loading_container .popup .popup_cont').text("You have lost the game!");

                                            var ajxData704 = {'action': 'update-my-status-2', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                               $.ajax({
                                                type: 'POST',
                                                data: ajxData704,
                                                cache: false,
                                                url: 'ajax/updateMyStatus2.php',
                                                success: function(results){
                                                    console.log(results);
                                                    if(results){
                                                       console.log("status updated");
                                                       // alert("status updated");

                                                    }
                                                } }); 
                                            
                                           
                                            
                                        }

                                    }, 4000);          


                                    setTimeout(function(){ 
                            
                                        var signal444 = {type: 'next-round-process', message: 'request for next round process', playerToPlay:tossWinner, playersPlaying: playersPlaying, isLost: isLost};
                                        
                                            connection.send(JSON.stringify(signal444));


                                          callback();

                                        if(isLost == true){

                                        /* disconnect user */

                                        var ajxData20555 = {'action': 'get-connection-id', roomId: roomIdCookie, playerId: userId, sessionKey: sessionKeyCookie};

                                        $.ajax({

                                            type: 'POST',
                                            url: 'ajax/getConnectionId.php',
                                            cache: false,
                                            data: ajxData20555,
                                            success: function(connectionId){ 
                                            
                                                console.log(connectionId);
                                                // // alert("connection removed");
                                                // connection.remove(connectionId);
                                                connection.close();
                                                // location.reload();


                                             } }); 

                                        }        



                                    }, 6000);


                        }else if(gameStatusFlagMeld == "over"){
                            // send a signal 

                            var signal445 = {type: 'game-over-signal', message: 'game over', playerWon: playerWon};
                                        
                             connection.send(JSON.stringify(signal445));

                            if(parseInt(playerWon) == parseInt(userId)){
                                $('.loading_container').css({'display':'block'});
                                $('.loading_container .popup .popup_cont').text("You have won the game!");

                                 setTimeout(function(){
                                    $('.loading_container').hide();
                                    $('.loading_container .popup .popup_cont').text();


                                    $('.popup_rejoin').show();
                                    $('.popup_rejoin .popup_with_button_cont p').text("Do you want to rejoin?");
                                    
                                 }, 4000);

                                /* Update the realChips table */

                                 var ajxData704407 = {'action': 'update-real-wallet', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                $.ajax({
                                    type: 'POST',
                                    data: ajxData704407,
                                    cache: false,
                                    url: 'ajax/updateRealWallet.php',
                                    success: function(results){
                                        
                                         // alert("Total chips coming.......................");
                                         console.log(results);   
                                    } }); 



                            }else{
                                $('.loading_container').css({'display':'block'});
                                $('.loading_container .popup .popup_cont').text("You have lost the game!");

                                 setTimeout(function(){
                                    $('.loading_container').hide();
                                    $('.loading_container .popup .popup_cont').text();


                                    $('.popup_rejoin').show();
                                    $('.popup_rejoin .popup_with_button_cont p').text("Do you want to rejoin?");
                                    
                                 }, 4000);

                                var ajxData704 = {'action': 'update-my-status-2', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                $.ajax({
                                    type: 'POST',
                                    data: ajxData704,
                                    cache: false,
                                    url: 'ajax/updateMyStatus2.php',
                                    success: function(results){
                                        console.log(results);
                                        if(results){
                                           console.log("status updated");
                                           // alert("status updated");

                                        }
                                    } }); 

                                  setTimeout(function(){
                                               
                                        // $('.result_sec').remove();

                                        /* disconnect user */

                                        var ajxData20555 = {'action': 'get-connection-id', roomId: roomIdCookie, playerId: userId, sessionKey: sessionKeyCookie};

                                        $.ajax({

                                            type: 'POST',
                                            url: 'ajax/getConnectionId.php',
                                            cache: false,
                                            data: ajxData20555,
                                            success: function(connectionId){ 
                                            
                                                console.log(connectionId);
                                                // // alert("connection removed");
                                                // connection.remove(connectionId);
                                                connection.close();
                                                // location.reload();


                                             } });    





                                    }, 10000 );
                                          

                            }

                            

                        }                  
                
                    }, 8000 );



        }





        


           function getScoreBoardWrongVal2(roomIdCookie, gameType, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie){ 

            console.log("hit the func...");
            
             // $('.result_sec tbody[id="score_reports"]').html("");
             
             
             var status;

             for(var i = 0; i < playersPlaying.length; i++){

                console.log('doing for ', playersPlayingTemp[i]);

                 var ajxData703 = {'action': 'get-players', player: playersPlaying[i]};

                  $.ajax({
                    type: 'POST',
                    data: ajxData703,
                    dataType: 'json',
                    cache: false,
                    url: 'ajax/getAllPlayers.php',
                    success: function(player){

                        console.log(player.id + ' ' + player.name);



                       
                         if( !$('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"]').length ){

                            if(gameType != "score"){

                                var tblRow1 = $('<tr data-user="'+player.id+'"><td id="name">'+player.name+'</td><td id="result"></td><td id="cards"></td><td id="score"></td><td id="total_score"></td></tr>');

                            }else{
                                var tblRow1 = $('<tr data-user="'+player.id+'"><td id="name">'+player.name+'</td><td id="result"></td><td id="cards"></td><td id="count"></td><td id="total_chips"></td></tr>');
                            }

                            
                             $('.result_sec tbody[id="score_reports"]').append(tblRow1);

                            



                          }      


                          if(gameType != "score"){

                             if( $.trim( $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="score"]').html() ) == ""){

                                console.log("SCORE SECTION EMPTY ", player.id);

                                 var ajxData704 = {'action': 'get-scoreboard', roomId: roomIdCookie, player: player.id, sessionKey: sessionKeyCookie};

                                  $.ajax({
                                    type: 'POST',
                                    data: ajxData704,
                                    dataType: 'json',
                                    cache: false,
                                    url: 'ajax/getScoreBoard.php',
                                    success: function(results){

                                        

                                        console.log("Player id checking ", player.id);

                                        var points = results.points;
                                        var totalPts = results.total_points;
                                        var playerWon = results.player_won;

                                        console.log("Player Won ", playerWon);
                                              
                                               if(userId == player.id){
                                                    status = "Lost";
                                                }else if(playerWon == player.id){
                                                    status = "<img src='images/winner.png'>";
                                                }else{
                                                    status = "Lost";
                                                }

                                                 $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="result"]').html(status);

                                                 console.log("status called ", player.id);

                                         

                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="score"]').text(Math.round(points));

                                             if(totalPts != null){
                                                    $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_score"]').text(Math.round(totalPts));

                                                    if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                        $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]').html(showCardsInScoreboard(player.id, roomIdCookie, sessionKeyCookie));

                                                    }  


                                                }else{
                                                    $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_chips"]').text('');
                                                }


                                              
                                        
                                           

                                                      

                                        }
                                        
                                     }); 


                            }else{
                                console.log("Not getting considered player ", player.id);
                            }


                          }else{

                             if( $.trim( $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="count"]').html() ) == ""){

                                console.log("SCORE SECTION EMPTY ", player.id);

                                 var ajxData704 = {'action': 'get-scoreboard', roomId: roomIdCookie, player: player.id, sessionKey: sessionKeyCookie};

                                  $.ajax({
                                    type: 'POST',
                                    data: ajxData704,
                                    dataType: 'json',
                                    cache: false,
                                    url: 'ajax/getScoreBoard.php',
                                    success: function(results){

                                        

                                        console.log("Player id checking ", player.id);

                                        var points = results.points;
                                        var totalPts = results.total_points;
                                        var playerWon = results.player_won;

                                        console.log("Player Won ", playerWon);
                                              
                                               if(userId == player.id){
                                                    status = "Lost";
                                                }else if(playerWon == player.id){
                                                    status = "<img src='images/winner.png'>";
                                                }else{
                                                    status = "Lost";
                                                }

                                                 $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="result"]').text(status);

                                                 console.log("status called ", player.id);

                                         

                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="count"]').text(Math.round(points));

                                            if(playerWon != player.id){
                                                if(totalPts != null){
                                                    $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_chips"]').text('-'+totalPts);
                                                }else{
                                                    $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_chips"]').text('');
                                                }

                                                if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                    $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]').html(showCardsInScoreboard(player.id, roomIdCookie, sessionKeyCookie));

                                               } 

                                            }else{
                                                 $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_chips"]').text("");
                                            }



                                        
                                            
                                                      

                                        }
                                        
                                     }); 


                            }else{
                                console.log("Not getting considered player ", player.id);
                            }


                          }


                           

                      

                     

                      }      


                    }); 



            }

          }



      /* Wrong val display function force called without points update */
      
              function wrongValidationDisplayProcess2ForceCall(roomIdCookie, gameType, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie){

                console.log("wrong validation display process 2 hit");


                console.log("Players playing temp ", playersPlayingTemp);

                // alert("wrong val display process 2 forcecall");


                /* update melded count */

                var ajxData852 = { 'action': 'update-melded-count', roomId: roomIdCookie, sessionKey: sessionKeyCookie};


                 $.ajax({
                        type: 'POST',
                        data: ajxData852,
                        cache: false,
                        url: 'ajax/updateMeldedCount.php',
                        success: function(result){
                            console.log(result);
                        } });    


                var ajxData853 = { 'action': 'update-player-melded', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};


                 $.ajax({
                        type: 'POST',
                        data: ajxData853,
                        cache: false,
                        url: 'ajax/updatePlayerMelded.php',
                        success: function(result){
                            console.log(result);
                        } });



                var ajxData852 = { 'action': 'meld-card-validation-no-group-f-call', roomId: roomIdCookie, player: userId, gameType: gameType, sessionKey: sessionKeyCookie};


                 $.ajax({
                        type: 'POST',
                        data: ajxData852,
                        cache: false,
                        url: 'ajax/meldCardValidationNoGroupFCall.php',
                        success: function(totalPoints){

                            console.log(" total points received ", totalPoints);
                           
                            setTimeout(function(){


                                 console.log(playersPlayingTemp);
                                 console.log('hit');


                                 var interval = setInterval(function(){

                                    

                                     var ajxData703 = {'action': 'get-melded-count', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                                      $.ajax({
                                        type: 'POST',
                                        data: ajxData703,
                                        cache: false,
                                        url: 'ajax/getMeldedCount.php',
                                        success: function(count){

                                            console.log("count scoreboard ", count);
                                            console.log("playersPlayingTemp.length ", playersPlaying.length);

                                            

                                           if(count < playersPlaying.length){
                                                getScoreBoardWrongVal2(roomIdCookie, gameType, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie);

                                            }else if(count == playersPlaying.length){
                                                clearInterval(interval);
                                                getScoreBoardWrongVal2(roomIdCookie, gameType, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie);
                                                
                                            }
                                            
                                         } });       


                                 }, 5000);
                               


                              if(gameType == "101"){  


                                if(totalPoints >= 101){


                                    /*  Update player gamedata */
                                      var ajxData704 = {'action': 'update-my-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                      $.ajax({
                                        type: 'POST',
                                        data: ajxData704,
                                        cache: false,
                                        url: 'ajax/updateMyStatus.php',
                                        success: function(results){
                                           
                                        } });

                                        /* remove player from playersPlayingTemp array */
                                
                                        if(removeCardFromGroups(userId, playersPlayingTemp)){
                                            console.log("player removed ", playersPlayingTemp);
                                        }   


                                }
                              
                              }else if(gameType == "201"){  
                              
                                if(totalPoints >= 201){
                                    /*  Update player gamedata */

                                     var ajxData704 = {'action': 'update-my-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                      $.ajax({
                                        type: 'POST',
                                        data: ajxData704,
                                        cache: false,
                                        url: 'ajax/updateMyStatus.php',
                                        success: function(results){
                                          
                                        } }); 

                                      /* remove player from playersPlayingTemp array */
                                
                                        if(removeCardFromGroups(userId, playersPlayingTemp)){
                                            console.log("player removed ", playersPlayingTemp);
                                        }

                                }
                              
                              }else if(gameType == "score"){

                                         var ajxData704 = {'action': 'update-my-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                              $.ajax({
                                                type: 'POST',
                                                data: ajxData704,
                                                cache: false,
                                                url: 'ajax/updateMyStatus.php',
                                                success: function(results){
                                                    
                                                } }); 

                                              /* remove player from playersPlayingTemp array */
                                                
                                                if(removeCardFromGroups(userId, playersPlayingTemp)){
                                                    console.log("player removed ", playersPlayingTemp);
                                                }


                                   }




                            /* Send signal to the melder  */

                         setTimeout(function(){
                            var signal13 = {type: 'get-scoreboard-melder', message: 'asking the melder to get scoreboard', myStatus: "won"};

                              var signal14 = {type: 'update-players-playing', message: 'updating players playing', playersPlayingTemp: playersPlayingTemp}

                            connection.send(JSON.stringify(signal13));
                            connection.send(JSON.stringify(signal14)); 


                            console.log("Now it's your turn 4!!"); 
                        }, 2000);

                                    
                               
                                         

                        }, 3000);
                               

                    } 
                
                }); 


        }    





        /* Wrong meld Validation Display other melders */


        function wrongValidationDisplayProcess2(totalScoreSum, roomIdCookie, gameType, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie){

                 // alert("hit the wrong val dis 2");

                console.log("wrong validation display process 2 hit");


                console.log("Players playing temp ", playersPlayingTemp);


                /* update melded count */

                var ajxData852 = { 'action': 'update-melded-count', roomId: roomIdCookie, sessionKey: sessionKeyCookie};


                 $.ajax({
                        type: 'POST',
                        data: ajxData852,
                        cache: false,
                        url: 'ajax/updateMeldedCount.php',
                        success: function(result){
                            console.log(result);
                        } });    


                var ajxData853 = { 'action': 'update-player-melded', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};


                 $.ajax({
                        type: 'POST',
                        data: ajxData853,
                        cache: false,
                        url: 'ajax/updatePlayerMelded.php',
                        success: function(result){
                            console.log(result);
                        } });



              var ajxData701 = {'action': 'update-score', roomId: roomIdCookie, player: userId, gameType: gameType, score: totalScoreSum, sessionKey: sessionKeyCookie, betValue: betValueCookie};
             


             $.ajax({
                    type: 'POST',
                    data: ajxData701,
                    cache: false,
                    url: 'ajax/updateScore.php',
                    success: function(totalPoints){

                            console.log(" total points received ", totalPoints);
                           
                             
                            setTimeout(function(){


                                 console.log(playersPlayingTemp);
                                 console.log('hit');

                                 /* getMeldCount */

                                

                                 var interval = setInterval(function(){

                                    

                                     var ajxData703 = {'action': 'get-melded-count', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                                      $.ajax({
                                        type: 'POST',
                                        data: ajxData703,
                                        cache: false,
                                        url: 'ajax/getMeldedCount.php',
                                        success: function(count){

                                            console.log("count scoreboard ", count);
                                            console.log("playersPlayingTemp.length ", playersPlaying.length);

                                            

                                           if(count < playersPlaying.length){
                                                getScoreBoardWrongVal2(roomIdCookie, gameType, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie);

                                            }else if(count == playersPlaying.length){
                                                clearInterval(interval);
                                                getScoreBoardWrongVal2(roomIdCookie, gameType, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie);
                                                
                                            }
                                            
                                         } });       


                                 }, 5000);
                               


                              if(gameType == "101"){  


                                if(totalPoints >= 101){


                                    /*  Update player gamedata */
                                      var ajxData704 = {'action': 'update-my-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                      $.ajax({
                                        type: 'POST',
                                        data: ajxData704,
                                        cache: false,
                                        url: 'ajax/updateMyStatus.php',
                                        success: function(results){
                                           
                                        } });

                                
                                        if(removeCardFromGroups(userId, playersPlayingTemp)){
                                            console.log("player removed ", playersPlayingTemp);
                                        }   

                                }
                              
                              }else if(gameType == "201"){  
                              
                                if(totalPoints >= 201){
                                    /*  Update player gamedata */

                                     var ajxData704 = {'action': 'update-my-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                      $.ajax({
                                        type: 'POST',
                                        data: ajxData704,
                                        cache: false,
                                        url: 'ajax/updateMyStatus.php',
                                        success: function(results){
                                          
                                        } }); 

                                      /* remove player from playersPlayingTemp array */
                                
                                        if(removeCardFromGroups(userId, playersPlayingTemp)){
                                            console.log("player removed ", playersPlayingTemp);
                                        }

                                   
                                }
                              
                              }else if(gameType == "score"){

                                     var ajxData704 = {'action': 'update-my-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                          $.ajax({
                                            type: 'POST',
                                            data: ajxData704,
                                            cache: false,
                                            url: 'ajax/updateMyStatus.php',
                                            success: function(results){
                                                
                                            } }); 

                                          /* remove player from playersPlayingTemp array */
                                            
                                            if(removeCardFromGroups(userId, playersPlayingTemp)){
                                                console.log("player removed ", playersPlayingTemp);
                                            }


                              }  





                            /* Send signal to the melder  */

                         setTimeout(function(){
                            var signal13 = {type: 'get-scoreboard-melder', message: 'asking the melder to get scoreboard', myStatus: "won"};

                              var signal14 = {type: 'update-players-playing', message: 'updating players playing', playersPlayingTemp: playersPlayingTemp}

                            connection.send(JSON.stringify(signal13));
                            connection.send(JSON.stringify(signal14)); 


                            console.log("Now it's your turn 4!!"); 
                        }, 2000);

                                    
                               
                                         

                        }, 3000);
                               

                    } 
                
                }); 


        }


   /* Wrong Meld Validation Display 6 players */
   
       function wrongValidationDisplayProcessSixPlayers(points, roomIdCookie, gameType, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, event){


        console.log("==========================!!!!======================");
        console.log("RM ", roomIdCookie);
        console.log("GT ", gameType);
        console.log("SK ", sessionKeyCookie);
        console.log("CT ", chipsToTablePRCookie);
        console.log("CB ", currentBalanceCookie);
        console.log("MB ", minBuyingPRCookie);
        console.log("BV ", betValueCookie);

            console.log("wrong validation display process 1 hit 6 players");   
            console.log("playersPlayingTemp ", playersPlayingTemp);

            // alert("wrong val dis process 6 players!");


            if(playersPlayingTemp.length > 2){
                $('.result_sec').css({'display': 'none'});

                if($.trim(event) == "lost"){
                    $('.loading_container').css({'display':'block'});
                    $('.loading_container .popup .popup_cont').text("You have melded incorrectly");
                }

              

                var counterM = 3;

                var intervalM = setInterval(function(){

                    if(counterM <= 0){

                        clearInterval(intervalM);



                        /* DB UPDATES */

                        /* update melded count */

                        var ajxData850 = { 'action': 'update-melded-count', roomId: roomIdCookie, sessionKey: sessionKeyCookie};


                         $.ajax({
                                type: 'POST',
                                data: ajxData850,
                                cache: false,
                                url: 'ajax/updateMeldedCount.php',
                                success: function(result){
                                    console.log(result);
                                } });


                         /* update wrong melders list */

                          var ajxData852ss = { 'action': 'update-wrong-melders', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};


                         $.ajax({
                                type: 'POST',
                                data: ajxData852ss,
                                cache: false,
                                url: 'ajax/updateWrongMelders.php',
                                success: function(result){
                                    console.log("sql ", result);
                                } });

                       /* update player has melded */
                       
                          var ajxData853 = { 'action': 'update-player-melded', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};


                         $.ajax({
                                type: 'POST',
                                data: ajxData853,
                                cache: false,
                                url: 'ajax/updatePlayerMelded.php',
                                success: function(result){
                                    console.log(result);
                                } }); 


                          var ajxData852 = { 'action': 'meld-card-validation-no-group', roomId: roomIdCookie, player: userId, gameType: gameType, sessionKey: sessionKeyCookie, betValue: betValueCookie, points: points};

                           



                        $.ajax({
                            type: 'POST',
                            data: ajxData852,
                            cache: false,
                            url: 'ajax/meldCardValidationNoGroup.php',
                            success: function(totalPoints){


                                if(gameType != 'score'){
                                    $('.current-player[data-user="'+userId+'"] .player_name #score').text(parseInt(totalPoints));
                                }

                                /* Check If I need to remove me out of the array */ 

                                   if(gameType == "101"){ 
                                    
                                        if(totalPoints >= 101){
                                            
                                             var ajxData704 = {'action': 'update-my-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                              $.ajax({
                                                type: 'POST',
                                                data: ajxData704,
                                                cache: false,
                                                url: 'ajax/updateMyStatus.php',
                                                success: function(results){
                                                    if(results){
                                                       console.log("status updated");
                                                       // alert("status updated");
                                                    }
                                                } });    


                                              /* remove player from playersPlayingTemp array */
                                        
                                            if(removeCardFromGroups(userId, playersPlayingTemp)){
                                                console.log("player removed ", playersPlayingTemp);
                                            }

                                        }

                                   }else if(gameType == "201"){ 
                                    
                                        if(totalPoints >= 201){
                                            
                                               var ajxData704 = {'action': 'update-my-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                              $.ajax({
                                                type: 'POST',
                                                data: ajxData704,
                                                cache: false,
                                                url: 'ajax/updateMyStatus.php',
                                                success: function(results){
                                                    if(results){
                                                        console.log("status updated");
                                                        // alert("status updated");
                                                    }
                                                } }); 

                                              /* remove player from playersPlayingTemp array */
                                                
                                                if(removeCardFromGroups(userId, playersPlayingTemp)){
                                                    console.log("player removed ", playersPlayingTemp);
                                                }

                                            
                                        }

                                   }else if(gameType == "score"){

                                         var ajxData704 = {'action': 'update-my-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                              $.ajax({
                                                type: 'POST',
                                                data: ajxData704,
                                                cache: false,
                                                url: 'ajax/updateMyStatus.php',
                                                success: function(results){
                                                    
                                                } }); 

                                              /* remove player from playersPlayingTemp array */
                                                
                                                if(removeCardFromGroups(userId, playersPlayingTemp)){
                                                    console.log("player removed ", playersPlayingTemp);
                                                }


                                   } 




                                 /* send signal to other players */

                                   var signal1011 = {type: 'wrong-meld-six-players', message: 'wrong meld six players game', firstMelder: userId, totalPoints: totalPoints, event: event};


                                     connection.send(JSON.stringify(signal1011));
                                  // connection.send(JSON.stringify(signal14)); 

                                    $('.loading_container').css({'display':'none'});
                                    $('.loading_container .popup .popup_cont').text("");


                                


                            } });     


                       



                    }


                   

                    counterM--;

                }, 1000)

               

            


            }else if(playersPlayingTemp.length == 2){

                  // alert(" game down to 2 ");

                  /* update melded count */

                   var ajxData850 = { 'action': 'update-melded-count', roomId: roomIdCookie, sessionKey: sessionKeyCookie};


                         $.ajax({
                                type: 'POST',
                                data: ajxData850,
                                cache: false,
                                url: 'ajax/updateMeldedCount.php',
                                success: function(result){
                                    console.log(result);
                                } });

                       /* update player has melded */
                       
                          var ajxData853 = { 'action': 'update-player-melded', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};


                         $.ajax({
                                type: 'POST',
                                data: ajxData853,
                                cache: false,
                                url: 'ajax/updatePlayerMelded.php',
                                success: function(result){
                                    console.log(result);
                                } }); 


                          var ajxData852ss = { 'action': 'update-wrong-melders', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};


                         $.ajax({
                                type: 'POST',
                                data: ajxData852ss,
                                cache: false,
                                url: 'ajax/updateWrongMelders.php',
                                success: function(result){
                                    console.log("sql ", result);
                                } });

                 
                    var ajxData852 = { 'action': 'meld-card-validation-no-group', roomId: roomIdCookie, player: userId, gameType: gameType, sessionKey: sessionKeyCookie, betValue: betValueCookie, points: points};

                    console.log("players ", playersPlayingTemp);



                    $.ajax({
                        type: 'POST',
                        data: ajxData852,
                        cache: false,
                        url: 'ajax/meldCardValidationNoGroup.php',
                        success: function(totalPoints){
                           

                            console.log(" total points received ", totalPoints);



                                


                                    for(var i = 0; i < playersPlaying.length; i++){

                                      console.log("doing for ", playersPlayingTemp[i]);


                                       /* Get user names  */

                                        var ajxData856 = { 'action': 'get-players', player: playersPlaying[i]};


                                        $.ajax({
                                            type: 'POST',
                                            data: ajxData856,
                                            dataType: 'json',
                                            cache: false,
                                            url: 'ajax/getAllPlayers.php',
                                            success: function(player){

                                                if(gameType != "score"){

                                                    console.log(player.id + ' ' + player.name);

                                                     var tblRow1 = $('<tr data-user="'+player.id+'"><td id="name">'+player.name+'</td><td id="result"></td><td id="cards"></td><td id="score"></td><td id="total_score"></td></tr>');

                                                     $('.result_sec tbody[id="score_reports"]').append(tblRow1);

                                                    var ajxData704 = {'action': 'get-scoreboard', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                                      $.ajax({
                                                        type: 'POST',
                                                        data: ajxData704,
                                                        dataType: 'json',
                                                        cache: false,
                                                        url: 'ajax/getScoreBoard.php',
                                                        success: function(results){

                                                            var points = results.points;
                                                            var totalPts = results.total_points;

                                                            if($.trim(event) == "lost"){
                                                                $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="result"]').text("wrong show");
                                                            }else if($.trim(event) == "drop"){
                                                                 $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="result"]').text("Drop");
                                                            }

                                                            


                                                        $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="score"]').text(Math.round(points));
                                                        
                                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="total_score"]').text(Math.round(totalPts));

                                                            if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="cards"]').html(showCardsInScoreboard(userId, roomIdCookie, sessionKeyCookie));

                                                            } 



                                                        }
                                                        
                                                     });       

                                                    }else{


                                                    console.log(player.id + ' ' + player.name);

                                                     var tblRow1 = $('<tr data-user="'+player.id+'"><td id="name">'+player.name+'</td><td id="result"></td><td id="cards"></td><td id="count"></td><td id="total_chips"></td></tr>');

                                                     $('.result_sec tbody[id="score_reports"]').append(tblRow1);

                                                    var ajxData704 = {'action': 'get-scoreboard', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                                      $.ajax({
                                                        type: 'POST',
                                                        data: ajxData704,
                                                        dataType: 'json',
                                                        cache: false,
                                                        url: 'ajax/getScoreBoard.php',
                                                        success: function(results){

                                                            var points = results.points;
                                                            var totalPts = results.total_points;


                                                             if($.trim(event) == "lost"){
                                                                $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="result"]').text("wrong show");
                                                            }else if($.trim(event) == "drop"){
                                                                 $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="result"]').text("Drop");
                                                            }


                                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="count"]').text(points);
                                                        
                                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="total_chips"]').text('-'+totalPts);

                                                            if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="cards"]').html(showCardsInScoreboard(userId, roomIdCookie, sessionKeyCookie));

                                                          } 



                                                        }
                                                        
                                                     });    



                                                    }  

                                                }
                                            })        




                                    }

                                   if(gameType == "101"){ 
                                    
                                        if(totalPoints >= 101){
                                            
                                             var ajxData704 = {'action': 'update-my-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                              $.ajax({
                                                type: 'POST',
                                                data: ajxData704,
                                                cache: false,
                                                url: 'ajax/updateMyStatus.php',
                                                success: function(results){
                                                    console.log(results);
                                                    // alert("status updated");
                                                    console.log("updated status");

                                                     if(removeCardFromGroups(userId, playersPlayingTemp)){
                                                        console.log("player removed ", playersPlayingTemp);
                                                    }

                                                } });    


                                              


                                            
                                            var gameStatus = "over";
                                        }else{
                                            var gameStatus = "continue";
                                        }

                                   }else if(gameType == "201"){ 
                                    
                                        if(totalPoints >= 201){
                                            
                                               var ajxData704 = {'action': 'update-my-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                              $.ajax({
                                                type: 'POST',
                                                data: ajxData704,
                                                cache: false,
                                                url: 'ajax/updateMyStatus.php',
                                                success: function(results){
                                                    console.log(results);
                                                    // alert("status updated");
                                                    console.log("updated status");

                                                     if(removeCardFromGroups(userId, playersPlayingTemp)){
                                                        console.log("player removed ", playersPlayingTemp);
                                                    }
                                                    
                                                } }); 

                                              
                                        }

                                   }else if(gameType == "score"){

                                         var ajxData704 = {'action': 'update-my-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                              $.ajax({
                                                type: 'POST',
                                                data: ajxData704,
                                                cache: false,
                                                url: 'ajax/updateMyStatus.php',
                                                success: function(results){
                                                    
                                                } }); 

                                              /* remove player from playersPlayingTemp array */
                                                
                                                if(removeCardFromGroups(userId, playersPlayingTemp)){
                                                    console.log("player removed ", playersPlayingTemp);
                                                }


                                   }  


                                    /* Send signal to opponent */

                                    if($.trim(event) == 'lost'){
                                        var status = "wrongshow";
                                    }else if($.trim(event) == 'drop'){
                                        var status = "drop";
                                    }
                                    

                                    var signal1011 = {type: 'get-scoreboard-six-players', message: 'get all your scoreboards', firstMelder: userId, status: status};

                                    connection.send(JSON.stringify(signal1011));
                                  

                                 

                               
                           
                        }
                    });  

            }    

       }     


  /* Wrong Meld Validation Display */

        function wrongValidationDisplayProcess(points, roomIdCookie, gameType, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, event){

            console.log("wrong validation display process 1 hit");   
            console.log("playersPlayingTemp ", playersPlayingTemp);
            // alert("wrong melder wrong val dis process");

            /* update melded count */

                   var ajxData850 = { 'action': 'update-melded-count', roomId: roomIdCookie, sessionKey: sessionKeyCookie};


                         $.ajax({
                                type: 'POST',
                                data: ajxData850,
                                cache: false,
                                url: 'ajax/updateMeldedCount.php',
                                success: function(result){
                                    console.log(result);
                                } });

                       /* update player has melded */
                       
                          var ajxData853 = { 'action': 'update-player-melded', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};


                         $.ajax({
                                type: 'POST',
                                data: ajxData853,
                                cache: false,
                                url: 'ajax/updatePlayerMelded.php',
                                success: function(result){
                                    console.log(result);
                                } }); 


                          var ajxData852ss = { 'action': 'update-wrong-melders', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};


                         $.ajax({
                                type: 'POST',
                                data: ajxData852ss,
                                cache: false,
                                url: 'ajax/updateWrongMelders.php',
                                success: function(result){
                                    console.log("sql ", result);
                                } });

                 
                    var ajxData852 = { 'action': 'meld-card-validation-no-group', roomId: roomIdCookie, player: userId, gameType: gameType, sessionKey: sessionKeyCookie, betValue: betValueCookie, points: points};

                    console.log("players ", playersPlayingTemp);



                    $.ajax({
                        type: 'POST',
                        data: ajxData852,
                        cache: false,
                        url: 'ajax/meldCardValidationNoGroup.php',
                        success: function(totalPoints){
                           

                            console.log(" total points received ", totalPoints);



                                


                                    for(var i = 0; i < playersPlaying.length; i++){

                                      console.log("doing for ", playersPlayingTemp[i]);


                                       /* Get user names  */

                                        var ajxData856 = { 'action': 'get-players', player: playersPlaying[i]};


                                        $.ajax({
                                            type: 'POST',
                                            data: ajxData856,
                                            dataType: 'json',
                                            cache: false,
                                            url: 'ajax/getAllPlayers.php',
                                            success: function(player){

                                               /*SAGNIK CHA*/

                                               if(gameType != "score"){

                                                    console.log(player.id + ' ' + player.name);

                                                     var tblRow1 = $('<tr data-user="'+player.id+'"><td id="name">'+player.name+'</td><td id="result"></td><td id="cards"></td><td id="score"></td><td id="total_score"></td></tr>');

                                                     $('.result_sec tbody[id="score_reports"]').append(tblRow1);

                                                    var ajxData704 = {'action': 'get-scoreboard', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                                      $.ajax({
                                                        type: 'POST',
                                                        data: ajxData704,
                                                        dataType: 'json',
                                                        cache: false,
                                                        url: 'ajax/getScoreBoard.php',
                                                        success: function(results){

                                                            var points = results.points;
                                                            var totalPts = results.total_points;

                                                            if($.trim(event) == "lost"){
                                                                $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="result"]').text("wrong show");
                                                            }else if($.trim(event) == "drop"){
                                                                $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="result"]').text("Drop");
                                                            }


                                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="score"]').text(Math.round(points));
                                                        
                                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="total_score"]').text(Math.round(totalPts));

                                                            if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                                $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="cards"]').html(showCardsInScoreboard(userId, roomIdCookie, sessionKeyCookie));

                                                            } 



                                                        }
                                                        
                                                     });       

                                               }else{


                                                    console.log(player.id + ' ' + player.name);

                                                     var tblRow1 = $('<tr data-user="'+player.id+'"><td id="name">'+player.name+'</td><td id="result"></td><td id="cards"></td><td id="count"></td><td id="total_chips"></td></tr>');

                                                     $('.result_sec tbody[id="score_reports"]').append(tblRow1);

                                                    var ajxData704 = {'action': 'get-scoreboard', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                                      $.ajax({
                                                        type: 'POST',
                                                        data: ajxData704,
                                                        dataType: 'json',
                                                        cache: false,
                                                        url: 'ajax/getScoreBoard.php',
                                                        success: function(results){

                                                            var points = results.points;
                                                            var totalPts = results.total_points;


                                                             if($.trim(event) == "lost"){
                                                                $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="result"]').text("wrong show");
                                                            }else if($.trim(event) == "drop"){
                                                                $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="result"]').text("Drop");
                                                            }


                                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="count"]').text(Math.round(points));
                                                        
                                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="total_chips"]').text('-'+totalPts);

                                                            if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                              $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="cards"]').html(showCardsInScoreboard(userId, roomIdCookie, sessionKeyCookie));

                                                            } 



                                                        }
                                                        
                                                     });    



                                               }   

                                            }
                                        })        




                                    }

                                   if(gameType == "101"){ 
                                    
                                        if(totalPoints >= 101){
                                            
                                             var ajxData704 = {'action': 'update-my-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                              $.ajax({
                                                type: 'POST',
                                                data: ajxData704,
                                                cache: false,
                                                url: 'ajax/updateMyStatus.php',
                                                success: function(results){
                                                   
                                                } });    


                                              /* remove player from playersPlayingTemp array */
                                        
                                            if(removeCardFromGroups(userId, playersPlayingTemp)){
                                                console.log("player removed ", playersPlayingTemp);
                                            }


                                            
                                            
                                        }

                                   }else if(gameType == "201"){ 
                                    
                                        if(totalPoints >= 201){
                                            
                                               var ajxData704 = {'action': 'update-my-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                              $.ajax({
                                                type: 'POST',
                                                data: ajxData704,
                                                cache: false,
                                                url: 'ajax/updateMyStatus.php',
                                                success: function(results){
                                                    
                                                } }); 

                                              /* remove player from playersPlayingTemp array */
                                                
                                                if(removeCardFromGroups(userId, playersPlayingTemp)){
                                                    console.log("player removed ", playersPlayingTemp);
                                                }

                                           
                                        }

                                   }else if(gameType == "score"){

                                         var ajxData704 = {'action': 'update-my-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                              $.ajax({
                                                type: 'POST',
                                                data: ajxData704,
                                                cache: false,
                                                url: 'ajax/updateMyStatus.php',
                                                success: function(results){
                                                    
                                                } }); 

                                              /* remove player from playersPlayingTemp array */
                                                
                                                if(removeCardFromGroups(userId, playersPlayingTemp)){
                                                    console.log("player removed ", playersPlayingTemp);
                                                }


                                   }  


                                    /* Send signal to opponent */

                                     if($.trim(event) == "lost"){
                                        var status = "wrongshow";
                                     }else if($.trim(event) == "drop"){
                                        var status = "drop";
                                    }
                                   

                                    var signal1011 = {type: 'get-scoreboard', message: 'get all your scoreboards', firstMelder: userId, status: status};

                                     var signal14 = {type: 'update-players-playing-melder', message: 'updating players playing', playersPlayingTemp: playersPlayingTemp}

                                    connection.send(JSON.stringify(signal1011));
                                    connection.send(JSON.stringify(signal14)); 


                                 

                               
                           
                        }
                    });  

                         
   }

         /** Successful Melding **/

         function successfulMelding(roomIdCookie, gameType, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie){

             console.log("successful melding hit");
             console.log(playersPlayingTemp);
             // alert("hit success melding");

             /* update melded count */

                var ajxData850 = { 'action': 'update-melded-count', roomId: roomIdCookie, sessionKey: sessionKeyCookie};


                 $.ajax({
                        type: 'POST',
                        data: ajxData850,
                        cache: false,
                        url: 'ajax/updateMeldedCount.php',
                        success: function(result){
                            console.log(result);
                        } });

               /* update player has melded */
               
                  var ajxData853 = { 'action': 'update-player-melded', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};


                 $.ajax({
                        type: 'POST',
                        data: ajxData853,
                        cache: false,
                        url: 'ajax/updatePlayerMelded.php',
                        success: function(result){
                            console.log(result);
                        } });


              /* update player won */
              

                var ajxData854 = { 'action': 'update-player-won', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};


                 $.ajax({
                        type: 'POST',
                        data: ajxData854,
                        cache: false,
                        url: 'ajax/updatePlayerWon.php',
                        success: function(result){
                            console.log(result);
                        } });


            var ajxData855 = { 'action': 'success-melding', roomId: roomIdCookie, player: userId, gameType: gameType, sessionKey: sessionKeyCookie, betValue: betValueCookie};


            $.ajax({
                type: 'POST',
                data: ajxData855,
                cache: false,
                url: 'ajax/successfulMelding.php',
                success: function(totalPoints){

                    console.log(" total points received ", totalPoints);
                  

                       

                            for(var i = 0; i < playersPlaying.length; i++){

                              console.log("doing for ", playersPlaying[i]);


                               /* Get user names  */

                                var ajxData856 = { 'action': 'get-players', player: playersPlaying[i]};


                                $.ajax({
                                    type: 'POST',
                                    data: ajxData856,
                                    dataType: 'json',
                                    cache: false,
                                    url: 'ajax/getAllPlayers.php',
                                    success: function(player){

                                        if(gameType != "score"){

                                            console.log(player.id + ' ' + player.name);

                                            var tblRow1 = $('<tr data-user="'+player.id+'"><td id="name">'+player.name+'</td><td id="result"></td><td id="cards"></td><td id="score"></td><td id="total_score"></td></tr>');

                                             $('.result_sec tbody[id="score_reports"]').append(tblRow1);

                                            var ajxData704 = {'action': 'get-scoreboard', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                              $.ajax({
                                                type: 'POST',
                                                data: ajxData704,
                                                dataType: 'json',
                                                cache: false,
                                                url: 'ajax/getScoreBoard.php',
                                                success: function(results){

                                                    var points = results.points;
                                                    var totalPts = results.total_points;

                                                 

                                                   if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                     $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="cards"]').html(showCardsInScoreboard(userId, roomIdCookie, sessionKeyCookie));

                                                   }

                                                   


                                                    $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="result"]').html("<img src='images/winner.png'>");


                                                    $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="score"]').text(Math.round(points));
                                                
                                                    $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="total_score"]').text(Math.round(totalPts));



                                                }
                                                
                                             });       

                                       }else{


                                            console.log(player.id + ' ' + player.name);

                                             var tblRow1 = $('<tr data-user="'+player.id+'"><td id="name">'+player.name+'</td><td id="result"></td><td id="cards"></td><td id="count"></td><td id="total_chips"></td></tr>');

                                             $('.result_sec tbody[id="score_reports"]').append(tblRow1);

                                            var ajxData704 = {'action': 'get-scoreboard', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                              $.ajax({
                                                type: 'POST',
                                                data: ajxData704,
                                                dataType: 'json',
                                                cache: false,
                                                url: 'ajax/getScoreBoard.php',
                                                success: function(results){

                                                    var points = results.points;
                                                    var totalPts = results.total_points;

                                                    

                                                     if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                       $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="cards"]').html(showCardsInScoreboard(userId, roomIdCookie, sessionKeyCookie));

                                                   }


                                                    $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="result"]').html("<img src='images/winner.png'>");


                                                    $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="count"]').text(Math.round(points));
                                                
                                                    $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="total_chips"]').text("");



                                                }
                                                
                                             });    



                                       }   

                                    }
                                })        




                            }



                          if(gameType == "101"){  
                            if(totalPoints >= 101){
                                // alert("You have lost the game!");
                              
                                 var ajxData704 = {'action': 'update-my-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                      $.ajax({
                                        type: 'POST',
                                        data: ajxData704,
                                        cache: false,
                                        url: 'ajax/updateMyStatus.php',
                                        success: function(results){
                                            
                                        } }); 


                                /* remove player from playersPlayingTemp array */
                                
                                if(removeCardFromGroups(userId, playersPlayingTemp)){
                                    console.log("player removed ", playersPlayingTemp);
                                }

                                }
                          }else if(gameType == "201"){  
                            if(totalPoints >= 201){
                                
                                 var ajxData704 = {'action': 'update-my-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                      $.ajax({
                                        type: 'POST',
                                        data: ajxData704,
                                        cache: false,
                                        url: 'ajax/updateMyStatus.php',
                                        success: function(results){
                                            
                                        } }); 


                                /* remove player from playersPlayingTemp array */
                                
                                if(removeCardFromGroups(userId, playersPlayingTemp)){
                                    console.log("player removed ", playersPlayingTemp);
                                }      

                            }
                          } 


                            /* Send signal to opponent */



                            var status = "rightshow";
                            meldingProcess = 1;


                            var signal102 = {type: 'get-scoreboard', message: 'get all your scoreboards', firstMelder: userId, status: status, playersPlayingTemp: playersPlayingTemp};


                            connection.send(JSON.stringify(signal102));


                         

                       
                   
                }
            });      


         }



         function forceMelder(totalScoreSum, gameType, roomIdCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie){

            console.log("force melder hit");
            // alert("force melder hit");

            /* update melded count */

                var ajxData852 = { 'action': 'update-melded-count', roomId: roomIdCookie, sessionKey: sessionKeyCookie};


                 $.ajax({
                        type: 'POST',
                        data: ajxData852,
                        cache: false,
                        url: 'ajax/updateMeldedCount.php',
                        success: function(result){
                            console.log(result);
                } }); 


                /* Update player melded */ 

                   var ajxData853 = { 'action': 'update-player-melded', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};


                 $.ajax({
                        type: 'POST',
                        data: ajxData853,
                        cache: false,
                        url: 'ajax/updatePlayerMelded.php',
                        success: function(result){
                            console.log(result);
                    } });

             /* Update Score */


             var ajxData701 = {'action': 'update-score', roomId: roomIdCookie, player: userId, gameType: gameType, score: totalScoreSum, sessionKey: sessionKeyCookie, betValue: betValueCookie};

             $.ajax({
                    type: 'POST',
                    data: ajxData701,
                    cache: false,
                    url: 'ajax/updateScore.php',
                    success: function(totalPoints){
                       console.log(" total points received ", totalPoints);

                                 setTimeout(function(){

                                 var interval = setInterval(function(){

                                    

                                     var ajxData703 = {'action': 'get-melded-count', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                                      $.ajax({
                                        type: 'POST',
                                        data: ajxData703,
                                        cache: false,
                                        url: 'ajax/getMeldedCount.php',
                                        success: function(count){

                                            console.log("count scoreboard ", count);
                                            console.log("playersPlayingTemp.length ", playersPlaying.length);

                                            

                                           if(count < playersPlaying.length){
                                                getScoreBoardWrongVal2(roomIdCookie, gameType, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie);

                                            }else if(count == playersPlaying.length){
                                                clearInterval(interval);
                                                getScoreBoardWrongVal2(roomIdCookie, gameType, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie);
                                                
                                            }
                                            
                                         } });       


                                 }, 5000);
                    

                         if(gameType == "101"){   
                         
                          if(totalPoints >= 101){
                                // alert("You have lost the game!");
                               
                                 var ajxData704 = {'action': 'update-my-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                      $.ajax({
                                        type: 'POST',
                                        data: ajxData704,
                                        cache: false,
                                        url: 'ajax/updateMyStatus.php',
                                        success: function(results){
                                            
                                        } }); 


                                    /* remove player from playersPlayingTemp array */
                                
                                if(removeCardFromGroups(userId, playersPlayingTemp)){
                                    console.log("player removed ", playersPlayingTemp);
                                }  


                                
                           }
                         
                          }else if(gameType == "201"){   
                         
                          if(totalPoints >= 201){
                                // alert("You have lost the game!");
                                
                                 var ajxData704 = {'action': 'update-my-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                      $.ajax({
                                        type: 'POST',
                                        data: ajxData704,
                                        cache: false,
                                        url: 'ajax/updateMyStatus.php',
                                        success: function(results){
                                           
                                        } }); 

                                  /* remove player from playersPlayingTemp array */
                                
                                if(removeCardFromGroups(userId, playersPlayingTemp)){
                                    console.log("player removed ", playersPlayingTemp);
                                }    

                           }
                         
                          }else if(gameType == "score"){

                             var ajxData704 = {'action': 'update-my-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                  $.ajax({
                                    type: 'POST',
                                    data: ajxData704,
                                    cache: false,
                                    url: 'ajax/updateMyStatus.php',
                                    success: function(results){
                                        
                                    } }); 

                                  /* remove player from playersPlayingTemp array */
                                    
                                    if(removeCardFromGroups(userId, playersPlayingTemp)){
                                        console.log("player removed ", playersPlayingTemp);
                                    }


                            } 


                           setTimeout(function(){
                           
                               var signal13 = {type: 'get-scoreboard-melder', message: 'asking the melder to get scoreboard', myStatus: "won"};

                               var signal14 = {type: 'update-players-playing', message: 'updating players playing', playersPlayingTemp: playersPlayingTemp}
                                                       
                                connection.send(JSON.stringify(signal13)); 
                                connection.send(JSON.stringify(signal14));
                                console.log("Now it's your turn 2"); 

                           
                            }, 2000); 


                        }, 3000);   
                    }
             })      





         }   


          /** ========= Game Algorithm ============== */

             
               var userId = "<?php echo $user_id; ?>";
               var name = "<?php echo $displayName; ?>";

               var connection = new RTCMultiConnection();
               var sessionName = "<?php echo $room; ?>";

   

          $(function(){



          /** ======= JOIN ============ */

            var sessions = {};
            var sessionArray = [];    

             connection.onNewSession = function(session) {
            
                  $('.join').attr('disabled', 'disabled');
                  connection.onNewSession = function() {};
                  connection.join(session);

             }; 

             function IDGenerator() {
     
                 this.length = 15;
                 this.timestamp = +new Date;
                 
                 var _getRandomInt = function( min, max ) {
                    return Math.floor( Math.random() * ( max - min + 1 ) ) + min;
                 }
                 
                 this.generate = function() {
                     var ts = this.timestamp.toString();
                     var parts = ts.split( "" ).reverse();
                     var id = "";
                     
                     for( var i = 0; i < this.length; ++i ) {
                        var index = _getRandomInt( 0, parts.length - 1 );
                        id += parts[index];  
                     }
                     
                     return id;
                 }

         
            }

            

            

             function createGame(roomId, gameType, gamePlayers, betValue, chipsToTable, currentBalance, minBuying){

                    connection.close();

                    direction = "many-to-many";

                    _session = "data";
                    splittedSession = _session.split('+');

                    session = {};
                    for (var i = 0; i < splittedSession.length; i++) {
                        session[splittedSession[i]] = true;
                    }

                    maxParticipantsAllowed = 256;

                    var Generator = new IDGenerator();
                    var uniqueId = Generator.generate();



                    connection.extra = {
                        'session-name': uniqueId
                    };

                    connection.session = session;
                    connection.maxParticipantsAllowed = maxParticipantsAllowed;

                  
                    connection.sessionid = uniqueId;
                    $.cookie("room", roomId);
                    $.cookie("sessionKey", uniqueId);
                    $.cookie("game-type", gameType);
                    $.cookie("game-players", gamePlayers);
                    $.cookie("creator", "1");
                    $.cookie("betValue", betValue);
                    $.cookie("chipsToTablePR", chipsToTable);
                    $.cookie("currentBalancePR", currentBalance);
                    $.cookie("minBuyingPR", minBuying);
                    
                    connection.open();
                    // connection.connect(uniqueId);

                    /* room table creation */




                    /**  Update the Room Value */

                    var ajxData01 = {'action': 'room-update', roomId: roomId, sessionKey:uniqueId};

                        $.ajax({
                            type: 'POST',
                            url: 'ajax/roomUpdate.php',
                            cache: false,
                            data: ajxData01,
                            success: function(result){


                               $('.room1').css({ display: 'none'});
                               $('.room2').css({ display: 'block'});  

                               var ajxData02 = {'action': 'player-update', roomId: roomId, playerId: userId, creator: "true", sessionKey: uniqueId};

                                $.ajax({
                                    type: 'POST',
                                    url: 'ajax/playerUpdate.php',
                                    cache: false,
                                    data: ajxData02,
                                    success: function(result){

                                       /* Remove previous player - gamedata of this room  */ 

                                        var ajxData100 = {'action': 'remove-gamedata', roomId: roomId, sessionKey: uniqueId};

                                        $.ajax({
                                            type: 'POST',
                                            url: 'ajax/removeGamedata.php',
                                            cache: false,
                                            data: ajxData100,
                                            success: function(result){

                                                 /* Show myself */

                                                 if($.trim(result) == "ok"){

                                                        

                                                      $('.me .me_pic img').css({'display': 'block'});
                                                      $('.me .player_name .player_name_me').text(name);
                                                      $('.me .player_name').css({'display': 'block'});

                                                      
                                                      waitHandlerNormal(function(){
                                                       
                                                        $('.loading_container').css({'display': 'block'});
                                                      });

                                                      console.log(result);

                                                       /* update connection id */

                                                    var ajxData010011 = {'action': 'connection-id-update', roomId: roomId, connectionId: connection.userid, player: userId, sessionKey: uniqueId};

                                                        $.ajax({
                                                            type: 'POST',
                                                            url: 'ajax/insertConnectionId.php',
                                                            cache: false,
                                                            data: ajxData010011,
                                                            success: function(result){
                                                                console.log("connection id updated");
                                                                console.log(result);

                                                            } });  


                                                   

                                                }

                                            }
                                            
                                        });        


                                     

                                    }
                                });


                            }
                        });

             }


             $('.popup_bg').delegate('.joinGameBtn', 'click', function(){

                        var roomId = $('#roomIdHidden').val();
                        var gameType = $('#gameTypeHidden').val();
                        var gamePlayers = $('#gamePlayersHidden').val();
                        var betValue = $('#betValueHidden').val();

                        $('.popup_bg').hide();
                        $('.popup_bg .popup_with_button_cont p').text("");

                        




                        var ajxData010011 = {'action': 'room-available', roomId: roomId, userId: userId};

                        $.ajax({
                            type: 'POST',
                            url: 'ajax/ifRoomAvailable.php',
                            cache: false,
                            data: ajxData010011,
                            success: function(sessionKey){
                                console.log("fetch room available");
                                console.log(sessionKey);

                                if($.trim(sessionKey) == "yes"){
                                   
                                   $('.loading_container').show();   
                                   $('.loading_container .popup .popup_cont').text("You are already playing this game! Join another game");
                                   setTimeout(function(){
                                         $('.loading_container').hide();
                                         $('.loading_container .popup .popup_cont').text("");   
                                   }, 3000);

                               }else{

                                if($.trim(sessionKey) != "no"){
                                    /* table available, join the table */

                                        var keyRetrieved = $.trim(sessionKey);

                                        console.log("session key retrieved ", keyRetrieved);
                                        connection.connect(keyRetrieved);
                                      
                                    /* update connection id */

                                    var ajxData010011 = {'action': 'connection-id-update', roomId: roomId, connectionId: connection.userid, player: userId, sessionKey: keyRetrieved};

                                        $.ajax({
                                            type: 'POST',
                                            url: 'ajax/insertConnectionId.php',
                                            cache: false,
                                            data: ajxData010011,
                                            success: function(result){
                                                console.log("connection id updated");
                                                console.log(result);

                                                  $.cookie("room", roomId);
                                                  $.cookie("game-type", gameType);
                                                  $.cookie("game-players", gamePlayers);
                                                  $.cookie("sessionKey", keyRetrieved);
                                                  $.cookie("betValue", betValue);


                                                  $('.room1').css({ display: 'none'});
                                                  $('.room2').css({ display: 'block'});   


                                                    /** Update Player Count */

                                                   var ajxData03 = {'action': 'player-update', roomId: roomId, playerId: userId, creator: "false", sessionKey: keyRetrieved};

                                                    $.ajax({
                                                        type: 'POST',
                                                        url: 'ajax/playerUpdate.php',
                                                        cache: false,
                                                        data: ajxData03,
                                                        success: function(result){
                                                          console.log('player updated!');
                                                        }
                                                    });

                                                   
                                                    $('.me .me_pic img').css({'display': 'block'});
                                                    $('.me .player_name .player_name_me').text(name);
                                                    $('.me .player_name').css({'display': 'block'});

                                    } });


                                


                            console.log("don't have to create room");
                        }else if($.trim(sessionKey) == "no"){
                            /* room not available, create new table */

                            
                             createGame(roomId, gameType, gamePlayers, betValue, null, null, null);
                            


                        }

                      }  

                    } });



             });




                  $(document).ready(function(){

                        var rejoinCookie = $.cookie("rejoin");
                        var gamePlayers = $.cookie("game-players");

                        console.log("rejoinCookie ------------------------- ", rejoinCookie);

                        if($.trim(rejoinCookie) == "0"){
                              $('.room1').css({ display: 'block'});
                              $('.room2').css({ display: 'none'});   
                        }else if($.trim(rejoinCookie) == "1"){

                           $('.room1').css({ display: 'none'});
                           $('.room2').css({ display: 'block'});

                              if(gamePlayers == "2"){
                                $('.play_board').removeClass('square_board');
                                $('.play_board').addClass('round_board');
                                
                           }else if(gamePlayers == "6"){
                               $('.play_board').removeClass('round_board');
                                $('.play_board').addClass('square_board');
                                
                           }


                            rejoinRequest();


                        }

                });

                  function rejoinRequest(){



                   /* Rejoin Another game */

                    /* PRITAM PAAL */
                      var roomId = $.cookie("room");
                      var gamePlayers = $.cookie("game-players");
                      var gameType = $.cookie("game-type");

                     
                      console.log("room 3333333333333333333333", roomId);
                     

                      var chipsToTablePRCookie = $.trim($.cookie("chipsToTablePR"));
                      var currentBalanceCookie = $.trim($.cookie("currentBalance"));
                      var minBuyingPRCookie = $.trim($.cookie("minBuying"));
                      var betValue = $.trim($.cookie("betValue"));



                   var ajxData010011 = {'action': 'room-available', roomId: roomId, userId: userId};

                        $.ajax({
                            type: 'POST',
                            url: 'ajax/ifRoomAvailable.php',
                            cache: false,
                            data: ajxData010011,
                            success: function(sessionKey){
                                console.log("fetch room available");
                                console.log(sessionKey);

                                if($.trim(sessionKey) == "yes"){
                                   
                                   $('.loading_container').show();   
                                   $('.loading_container .popup .popup_cont').text("You are already playing this game! Join another game");
                                   setTimeout(function(){
                                         $('.loading_container').hide();
                                         $('.loading_container .popup .popup_cont').text("");   
                                   }, 3000);

                               }else{

                                if($.trim(sessionKey) != "no"){
                                    /* table available, join the table */

                                        var keyRetrieved = $.trim(sessionKey);

                                        console.log("session key retrieved ", keyRetrieved);
                                        connection.connect(keyRetrieved);
                                      
                                    /* update connection id */

                                    var ajxData010011 = {'action': 'connection-id-update', roomId: roomId, connectionId: connection.userid, player: userId, sessionKey: keyRetrieved};

                                        $.ajax({
                                            type: 'POST',
                                            url: 'ajax/insertConnectionId.php',
                                            cache: false,
                                            data: ajxData010011,
                                            success: function(result){
                                                console.log("connection id updated");
                                                console.log(result);

                                                  $.cookie("room", roomId);
                                                  $.cookie("game-type", gameType);
                                                  $.cookie("game-players", gamePlayers);
                                                  $.cookie("sessionKey", keyRetrieved);
                                                  $.cookie("betValue", betValue);


                                                  $('.room1').css({ display: 'none'});
                                                  $('.room2').css({ display: 'block'});   


                                                    /** Update Player Count */

                                                   var ajxData03 = {'action': 'player-update', roomId: roomId, playerId: userId, creator: "false", sessionKey: keyRetrieved};

                                                    $.ajax({
                                                        type: 'POST',
                                                        url: 'ajax/playerUpdate.php',
                                                        cache: false,
                                                        data: ajxData03,
                                                        success: function(result){
                                                          console.log('player updated!');
                                                          // alert("1");
                                                          $('.me .me_pic img').css({'display': 'block'});
                                                          $('.me .player_name .player_name_me').text(name);
                                                          $('.me .player_name').css({'display': 'block'});
                                                        }
                                                    });

                                                   





                                    } });


                                


                            console.log("don't have to create room");
                        }else if($.trim(sessionKey) == "no"){
                            /* room not available, create new table */

                             // alert("2");   
                             createGame(roomId, gameType, gamePlayers, betValue, null, null, null);
                            


                        }

                      }  

                    } });



            }




            
                $('.popup_rejoin #rejoinBtn').click(function(){

                     //resetGameConditions();

                     $.cookie("rejoin", "1");

                     $('.popup_rejoin').hide();
                     $('.popup_rejoin .popup_with_button_cont p').text("");

                     setTimeout(function(){
                        location.reload();
                     }, 2000);

                });


                $('.popup_rejoin .goToLobbyBtn').click(function(){

                    // $('.room1').css({ display: 'block'});
                    // $('.room2').css({ display: 'none'});   

                    $('.popup_rejoin').hide();
                    $('.popup_rejoin .popup_with_button_cont p').text("");

                    setTimeout(function(){
                        location.reload();
                    }, 2000);



                });

                
             $('.tabscontent').delegate('.join', 'click', function(){

                  // alert("asdsa");  

                  var gameType = $(this).attr('game-type');
                  var roomId = $(this).attr('id');
                 
                  var gamePlayers = $(this).attr('game-players');
                  var betValue = $(this).attr('game-bet');
                  var minBuyingCalculated = betValue * 80;
                  var myRealChips = $.trim($('.real-chips-wallet .count').text());


                  if(gamePlayers == "2"){
                    $('.play_board').addClass('round_board');
                   }else if(gamePlayers == "6"){
                     $('.play_board').addClass('square_board');
                   }
            
                  if(gameType != "score"){

                    if(gameType == "101" || gameType == "201"){
                        var gameShow = "Pool";
                        var gameTypeShow = gameType;
                    }else if(gameType == "deals2" || gameType == "deals3"){
                        var gameTypeShow = "";
                        var gameShow = "Deal Game";

                    }
                    
                    $('.popup_bg').show();
                    $('.popup_bg .popup_with_button_cont p').html('Do you want to join '+gameTypeShow+' '+gameShow+' / '+gamePlayers+ ' players / '+ betValue + ' bet table?');

                    $('.popup_bg #confirmBtn').removeClass("okBtn");
                    $('.popup_bg #confirmBtn').removeClass("dropBtn");
                    $('.popup_bg #confirmBtn').addClass("joinGameBtn");


                    $('#roomIdHidden').val(roomId);
                    $('#gameTypeHidden').val(gameType);
                    $('#gamePlayersHidden').val(gamePlayers);
                    $('#betValueHidden').val(betValue);

                    

                  }else if(gameType == "score"){

                    $('#myModal #bet_amount').text(betValue);
                    $('#myModal #min_buying').text(minBuyingCalculated);
                    $('#myModal #current_balance').text(myRealChips);
                    $('#myModal #chips_to_table').val(myRealChips);

                    $('#roomIdHidden').val(roomId);
                    $('#gameTypeHidden').val(gameType);
                    $('#gamePlayersHidden').val(gamePlayers);

                    console.log("modal bet ", betValue);
                    console.log("modal minbuying ", minBuyingCalculated);
                    console.log("modal realchips ", myRealChips);
                    console.log("modal chips_to_table ", myRealChips);


               }

                    
        });   


        $('.joinPR').on('click', function(){

                var betAmount =  $('#myModal #bet_amount').text();
                var minBuying = $('#myModal #min_buying').text();
                var currentBalance = $('#myModal #current_balance').text();
                var chipsToTable = $('#myModal #chips_to_table').val();


                var roomIdHidden = $('#roomIdHidden').val();
                var gameTypeHidden = $('#gameTypeHidden').val();
                var gamePlayersHidden = $('#gamePlayersHidden').val();

                // console.log(roomIdHidden + ' ' + gameTypeHidden + ' ' + gamePlayersHidden);

                 joinGamePointsRummy(roomIdHidden, gameTypeHidden, gamePlayersHidden, betAmount, chipsToTable, currentBalance, minBuying);



        });



        function joinGamePointsRummy(roomId, gameType, gamePlayers, betValue, chipsToTable, currentBalance, minBuying){

                 var ajxData010011 = {'action': 'room-available', roomId: roomId, userId: userId};

                        $.ajax({
                            type: 'POST',
                            url: 'ajax/ifRoomAvailable.php',
                            cache: false,
                            data: ajxData010011,
                            success: function(sessionKey){
                                console.log("fetch room available");
                                console.log(sessionKey);

                                if($.trim(sessionKey) == "yes"){
                                   $('.loading_container').show();   
                                   $('.loading_container .popup .popup_cont').text("You are already playing this game! Join another game");
                                   setTimeout(function(){
                                         $('.loading_container').hide();  
                                         $('.loading_container .popup .popup_cont').text(""); 
                                   }, 3000);
                                }else{

                                    if($.trim(sessionKey) != "no"){
                                    /* table available, join the table */

                                        var keyRetrieved = $.trim(sessionKey);

                                        console.log("session key retrieved ", keyRetrieved);
                                        connection.connect(keyRetrieved);
                                      
                                    /* update connection id */

                                    var ajxData010011 = {'action': 'connection-id-update', roomId: roomId, connectionId: connection.userid, player: userId, sessionKey: keyRetrieved};

                                        $.ajax({
                                            type: 'POST',
                                            url: 'ajax/insertConnectionId.php',
                                            cache: false,
                                            data: ajxData010011,
                                            success: function(result){
                                                console.log("connection id updated");
                                                console.log(result);

                                                  $.cookie("room", roomId);
                                                  $.cookie("game-type", gameType);
                                                  $.cookie("game-players", gamePlayers);
                                                  $.cookie("sessionKey", keyRetrieved);
                                                  $.cookie("betValue", betValue);
                                                  $.cookie("chipsToTablePR", chipsToTable);
                                                  $.cookie("currentBalancePR", currentBalance);
                                                  $.cookie("minBuyingPR", minBuying);


                                                  /* For points rummy  */



                                                  $('.room1').css({ display: 'none'});
                                                  $('.room2').css({ display: 'block'});   

                                                    $('#myModal').modal('hide');
                                                    $('body').removeClass('modal-open');
                                                    $('.modal-backdrop').remove();


                                                    /** Update Player Count */

                                                   var ajxData03 = {'action': 'player-update', roomId: roomId, playerId: userId, creator: "false", sessionKey: keyRetrieved};

                                                    $.ajax({
                                                        type: 'POST',
                                                        url: 'ajax/playerUpdate.php',
                                                        cache: false,
                                                        data: ajxData03,
                                                        success: function(result){
                                                          console.log('player updated!');
                                                        }
                                                    });

                                                   
                                                    $('.me .me_pic img').css({'display': 'block'});
                                                    $('.me .player_name .player_name_me').text(name);
                                                    $('.me .player_name').css({'display': 'block'});





                                    } });


                                


                            console.log("don't have to create room");
                        }else if($.trim(sessionKey) == "no"){
                            /* room not available, create new table */

                            
                             createGame(roomId, gameType, gamePlayers, betValue, chipsToTable, currentBalance, minBuying);

                            $('#myModal').modal('hide');
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
                            


                        } }

                    } });






            }

         


            /** ====== When Connection Closes ====== */

        connection.onclose = function(e) {
            console.log('Data connection is closed between you and ' + e.userid);



        };

        connection.onleave = function(e) {
            
             console.log('Data connection is closed between you and ' + e.userid);


        };

                 


            /** ======= When Connection opens up ===== */

             // var connectionOpenFlag = 0;

            connection.onopen = function(){

             var onOpenHitCookie = $.cookie("onOpenHit");
             console.log("connection open called!");

              console.log("sessions: " + JSON.stringify(sessions));

                    $.cookie("rejoin", "0");

            
                   /* Check player count */
                   var roomIdCookie = $.cookie("room");
                   var gamePlayersCookie = $.cookie("game-players");
                   var creatorCookie = $.cookie("creator");
                   var sessionKeyCookie = $.trim($.cookie("sessionKey"));
                   var betValueCookie = $.trim($.cookie("betValue"));
                   var gameTypeCookie = $.trim($.cookie("game-type"));         


                    var ajxData04 = {'action': 'player-count', roomId: roomIdCookie, gamePlayers: gamePlayersCookie, sessionKey: sessionKeyCookie};

                     $.ajax({
                      type: 'POST',
                      url: 'ajax/getPlayerCount.php',
                      cache: false,
                      data: ajxData04,
                      success: function(result){

                        console.log("BLAHHHHH ", result);

                        if($.trim(result) == "ok"){


                                    
                                 $('.current-player #score').text("0");
                                    
                           
                                    var ajxData06 = {'action': 'get-players', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                                     $.ajax({
                                        type: 'POST',
                                        url: 'ajax/getPlayers.php',
                                        cache: false,
                                        data: ajxData06,
                                        dataType: "json",
                                        success: function(players){

                                             playersPlaying.length = 0;
                                             playersPlayingTemp.length = 0;

                                            for(var i = 0; i < players.length; i++){
                                                playersPlaying.push(parseInt(players[i]));
                                                playersPlayingTemp.push(parseInt(players[i]));
                                            }

                                            for(var i = 0; i < playersPlayingTemp.length; i++){

                                                if(playersPlayingTemp[i] != userId){

                                                    // get player name

                                                var ajxData20 = {'action': 'get-player-name', player: playersPlayingTemp[i]};

                                                      $.ajax({
                                                          type: 'POST',
                                                          url: 'ajax/getPlayerName.php',
                                                          cache: false,
                                                          data: ajxData20,
                                                          success: function(theName){

                                                          $('.player_3 .player_pic_3 img').css({'display': 'block'});
                                                          $('.player_3 .player_name .player_name_3').text(theName);
                                                          $('.player_3 .player_name').css({'display': 'block'});


                                                          $('.player_3').attr('data-user', playersPlayingTemp[i]);

                                                          }

                                                     });

                                                      break;


                                                }


                                            }

                                                if(creatorCookie){

                                                     var ajxData20 = {'action': 'update-room-started', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                                                      $.ajax({
                                                          type: 'POST',
                                                          url: 'ajax/roomUpdateStarted.php',
                                                          cache: false,
                                                          data: ajxData20,
                                                          success: function(result){

                                                            // alert("hit room update started!");

                                                            
                                                            var ajxData27 = {'action': 'shuffle-deck', roomId: roomIdCookie, players: gamePlayersCookie, sessionKey: sessionKeyCookie};

                                                            $.ajax({
                                                                type: 'POST',
                                                                url: 'ajax/shuffleDeck.php',
                                                                cache: false,
                                                                data: ajxData27,
                                                                success: function(result){ 
                                                                    console.log("shuffled " + result); 

                                                                    
                                                            } });  

                                                        } });  


                                                    }

                                           

                                            gameStartHandler(function(){  // game starting counter

                                                /* deduct chips from user balance  */

                                                if(gameTypeCookie != "score"){


                                                    var ajxData000111 = {'action': 'chip-deduct', roomId: roomIdCookie, user: userId, sessionKey: sessionKeyCookie, chip: betValueCookie};

                                                    $.ajax({
                                                        type: 'POST',
                                                        url: 'ajax/chipDeduct.php',
                                                        cache: false,
                                                        data: ajxData000111,
                                                        success: function(result){ 
                                                            console.log("chip deduction result ", result); 

                                                            
                                                    } }); 


                                                }else{

                                                var ajxData000111 = {'action': 'chip-deduct', roomId: roomIdCookie, user: userId, sessionKey: sessionKeyCookie};

                                                        $.ajax({
                                                            type: 'POST',
                                                            url: 'ajax/chipDeductPR.php',
                                                            cache: false,
                                                            data: ajxData000111,
                                                            success: function(result){ 
                                                                console.log("chip deduction result ", result); 

                                                                
                                                        } }); 

                                                    
                                                }

                                                   

                                                $('.deck-middle').css({'display': 'block'});                                         

                                                var signal1 = {type: 'toss-shuffle', message: 'deck shuffled for toss'};
                                                connection.send(JSON.stringify(signal1));
                 

                                            });  




                                        }
                                     });       



                        }else if($.trim(result == "wait")){

                                    /* set the score */
                                    $('.current-player #score').text("0");


                          
                                    var ajxData06 = {'action': 'get-players', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                                     $.ajax({
                                        type: 'POST',
                                        url: 'ajax/getPlayers.php',
                                        cache: false,
                                        data: ajxData06,
                                        dataType: "json",
                                        success: function(players){

                                            playersPlaying.length = 0;
                                            playersPlayingTemp.length = 0;

                                            $('.player_1').attr('data-user', "");
                                            $('.player_1 .player_pic_1 img').css({'display': 'none'});
                                            $('.player_1 .player_name .player_name_1').text("");
                                            $('.player_1 .player_name').css({'display': 'none'});

                                            $('.player_2').attr('data-user', "");
                                            $('.player_2 .player_pic_2 img').css({'display': 'none'});
                                            $('.player_2 .player_name .player_name_2').text("");
                                            $('.player_2 .player_name').css({'display': 'none'});

                                            $('.player_3').attr('data-user', "");
                                            $('.player_3 .player_pic_3 img').css({'display': 'none'});
                                            $('.player_3 .player_name .player_name_3').text("");
                                            $('.player_3 .player_name').css({'display': 'none'});

                                             $('.player_4').attr('data-user', "");
                                            $('.player_4 .player_pic_4 img').css({'display': 'none'});
                                            $('.player_4 .player_name .player_name_4').text("");
                                            $('.player_4 .player_name').css({'display': 'none'});

                                             $('.player_5').attr('data-user', "");
                                            $('.player_5 .player_pic_5 img').css({'display': 'none'});
                                            $('.player_5 .player_name .player_name_5').text("");
                                            $('.player_5 .player_name').css({'display': 'none'});

                                            for(var i = 0; i < players.length; i++){
                                                playersPlaying.push(parseInt(players[i]));
                                                playersPlayingTemp.push(parseInt(players[i]));
                                            }

                                            console.log("PLayers playing ", playersPlayingTemp);

                                            for(var i = 0; i < playersPlayingTemp.length; i++){

                                                if(playersPlayingTemp[i] != userId){

                                                    // get player name

                                                var ajxData20 = {'action': 'get-players', player: playersPlayingTemp[i]};

                                                      $.ajax({
                                                          type: 'POST',
                                                          url: 'ajax/getAllPlayers.php',
                                                          cache: false,
                                                          data: ajxData20,
                                                          dataType: "json",
                                                          success: function(player){



                                                            console.log("Player ", player.name ," - ", player.id);


                                                                


                                                               if( !$('.player_1').attr('data-user') ){ 
                                                                 
                                                                  $('.player_1 .player_pic_1 img').css({'display': 'block'});
                                                                  $('.player_1 .player_name .player_name_1').text(player.name);
                                                                  $('.player_1 .player_name').css({'display': 'block'});

                                                                  $('.player_1').attr('data-user', player.id);
                                                               
                                                               }else if( !$('.player_2').attr('data-user') ){ 
                                                                
                                                                 $('.player_2 .player_pic_2 img').css({'display': 'block'});
                                                                  $('.player_2 .player_name .player_name_2').text(player.name);
                                                                  $('.player_2 .player_name').css({'display': 'block'});

                                                                  $('.player_2').attr('data-user', player.id);
                                                              
                                                               }else if( !$('.player_3').attr('data-user') ){ 
                                                                 $('.player_3 .player_pic_3 img').css({'display': 'block'});
                                                                  $('.player_3 .player_name .player_name_3').text(player.name);
                                                                  $('.player_3 .player_name').css({'display': 'block'});

                                                                  $('.player_3').attr('data-user', player.id);
                                                              
                                                               }else if( !$('.player_4').attr('data-user') ){ 
                                                                 $('.player_4 .player_pic_4 img').css({'display': 'block'});
                                                                  $('.player_4 .player_name .player_name_4').text(player.name);
                                                                  $('.player_4 .player_name').css({'display': 'block'});

                                                                  $('.player_4').attr('data-user', player.id);
                                                              
                                                               }else if( !$('.player_5').attr('data-user') ){ 
                                                                 $('.player_5 .player_pic_5 img').css({'display': 'block'});
                                                                  $('.player_5 .player_name .player_name_5').text(player.name);
                                                                  $('.player_5 .player_name').css({'display': 'block'});

                                                                  $('.player_5').attr('data-user', player.id);
                                                               }   
                                                            


                                                           
                                                          



                                                          }

                                                     });


                                                }


                                            }



                                            if(!onOpenHitCookie){


                                             var ajxData4000 = {'action': 'get-game-counter', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                                                 $.ajax({
                                                  type: 'POST',
                                                  url: 'ajax/getGameCounter.php',
                                                  cache: false,
                                                  data: ajxData4000,
                                                  success: function(counter){

                                                    var sixPlCounter;

                                                    if(parseInt(counter) == 0){
                                                         sixPlCounter = 20;
                                                    }else{
                                                         sixPlCounter = counter;
                                                    }

                                                 

                                                 $.cookie("onOpenHit", "1");
                                                 
                                                 gameStartHandlerSixPl(sixPlCounter, creatorCookie, roomIdCookie, sessionKeyCookie, function(){


                                                   /* Check if only 2 players have joined */
                                                   
                                                   if(playersPlayingTemp.length == 2){

                                                        for(var i = 0; i < playersPlayingTemp.length; i++){

                                                            if(playersPlayingTemp[i] != userId){

                                                                // get player name

                                                                var ajxData20 = {'action': 'get-players', player: playersPlayingTemp[i]};

                                                                      $.ajax({
                                                                          type: 'POST',
                                                                          url: 'ajax/getAllPlayers.php',
                                                                          cache: false,
                                                                          data: ajxData20,
                                                                          dataType: "json",
                                                                          success: function(player){



                                                                        // console.log("Player ", player.name ," - ", player.id);

                                                                    $('.player_3 .player_pic_3 img').css({'display': 'block'});
                                                                    $('.player_3 .player_name .player_name_3').text(player.name);
                                                                    $('.player_3 .player_name').css({'display': 'block'});

                                                                    $('.player_3').attr('data-user', player.id);


                                                                  $('.player_1 .player_pic_1 img').css({'display': 'none'});
                                                                  $('.player_1 .player_name .player_name_1').text('');
                                                                  $('.player_1 .player_name').css({'display': 'none'});

                                                                  $('.player_1').attr('data-user', '');


                                                                   } });


                                                              }    






                                                   } }










                                                    if(gameTypeCookie != "score"){


                                                            var ajxData000111 = {'action': 'chip-deduct', roomId: roomIdCookie, user: userId, sessionKey: sessionKeyCookie, chip: betValueCookie};

                                                            $.ajax({
                                                                type: 'POST',
                                                                url: 'ajax/chipDeduct.php',
                                                                cache: false,
                                                                data: ajxData000111,
                                                                success: function(result){ 
                                                                    console.log("chip deduction result ", result); 

                                                                    
                                                            } }); 


                                                    }else{



                                                         var ajxData000111 = {'action': 'chip-deduct', roomId: roomIdCookie, user: userId, sessionKey: sessionKeyCookie};

                                                            $.ajax({
                                                                type: 'POST',
                                                                url: 'ajax/chipDeductPR.php',
                                                                cache: false,
                                                                data: ajxData000111,
                                                                success: function(result){ 
                                                                    console.log("chip deduction result ", result); 

                                                                    
                                                            } }); 





                                                    }

                                                    $('.deck-middle').css({'display': 'block'});

                                                    console.log("Test checking players ", playersPlayingTemp);

                                                    /* deduct chips from user balance  */

                                                    if(creatorCookie){

                                                     var ajxData20 = {'action': 'update-room-started', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                                                          $.ajax({
                                                              type: 'POST',
                                                              url: 'ajax/roomUpdateStarted.php',
                                                              cache: false,
                                                              data: ajxData20,
                                                              success: function(result){

                                                                  var ajxData27 = {'action': 'shuffle-deck', roomId: roomIdCookie, players: gamePlayersCookie, sessionKey: sessionKeyCookie};

                                                                $.ajax({
                                                                    type: 'POST',
                                                                    url: 'ajax/shuffleDeck.php',
                                                                    cache: false,
                                                                    data: ajxData27,
                                                                    success: function(result){ 
                                                                        console.log("shuffled " + result); 

                                                                } });  




                                                              }
                                                           });

                                                     }     
                                                     



                                                    var signal1 = {type: 'toss-shuffle', message: 'deck shuffled for toss'};
                                                    connection.send(JSON.stringify(signal1));  

                                                });

                                             } });   

                                          } 


                                        }

                                     });   

                                }

                          } });   


                           
              
            }; 

            /** ===== Receiving all the Signals ===== */ 

             var tossFlag = 0;
             var selectJokerFlag = 0;
             var throwCardFlag = 0;
             var dealCardFlag = 0;



            connection.onmessage = function(e) {

              var dataReceived = JSON.parse(e.data);
              var roomIdCookie = $.cookie("room");
              var gamePlayersCookie = $.cookie("game-players");
              var creatorCookie = $.cookie("creator");
              var gameTypeCookie = $.cookie("game-type");
              var sessionKeyCookie = $.trim($.cookie("sessionKey"));

              var chipsToTablePRCookie = $.trim($.cookie("chipsToTablePR"));
              var currentBalanceCookie = $.trim($.cookie("currentBalance"));
              var minBuyingPRCookie = $.trim($.cookie("minBuying"));
              var betValueCookie = $.trim($.cookie("betValue"));



              var tossArray = [];

             

              // console.log(dataReceived);

           if(dataReceived.type == "toss-shuffle"){

                /**  ==== Toss cards among the players ==== */

                tossFlag++;

                // console.log("Toss flag ", tossFlag);

                if(tossFlag == 1){

                /* Get deck cards */
                    console.log("wait");

                    setTimeout(function(){

                        /* Calculate winning amount */

                        if(gameTypeCookie != "score"){

                             var winningAmount;

                             var ajxDataWinningAmt = {'action': 'get-winning-amount', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                                $.ajax({
                                    type: 'POST',
                                    url: 'ajax/getWinningAmount.php',
                                    cache: false,
                                    data: ajxDataWinningAmt,
                                    success: function(winningAmt){ 
                                        console.log("winning amount result ", winningAmt); 
                                        winningAmount = parseFloat(winningAmt).toFixed(2);

                                        $('.game_info #game_prize_money').text(winningAmount);

                                        
                                } }); 


                        }else{
                            $('.game_info #game_prize_money').hide();
                        }    


                            if(gameTypeCookie == "101" && gamePlayersCookie == "2"){
                                var gameName = "101 Pool (2 players)";
                            }else if(gameTypeCookie == "101" && gamePlayersCookie == "6"){
                                var gameName = "101 Pool (6 players)";
                            }else if(gameTypeCookie == "201" && gamePlayersCookie == "2"){
                                var gameName = "201 Pool (2 players)";
                            }else if(gameTypeCookie == "201" && gamePlayersCookie == "6"){
                                var gameName = "201 Pool (6 players)";
                            }else if(gameTypeCookie == "deals2"){
                                var gameName = "deal2";
                            }else if(gameTypeCookie == "deals3"){
                                 var gameName = "deal3";
                            }else if(gameTypeCookie == "score" && gamePlayersCookie == "2"){
                                 var gameName = "Score game (2 players)";
                            }else if(gameTypeCookie == "score" && gamePlayersCookie == "6"){
                                 var gameName = "Score game (6 players)";
                            }

                            $('.game_info #game_name').text(gameName);
                            $('.game_info #game_bet span').text(betValueCookie);
                            $('.game_info2 #table_id a').text(sessionKeyCookie);



                        var ajxData101 = {'action': 'get-shuffled-deck', roomId: roomIdCookie, sessionKey: sessionKeyCookie};
                             
                            var tossWinner;

                         $.ajax({
                              type: 'POST',
                              url: 'ajax/getShuffledDeck.php',
                              cache: false,
                              data: ajxData101,
                              dataType: 'json',
                              success: function(cards){

                                console.log("card get ", cards);
                                console.log("players ", playersPlayingTemp);

                                console.log("hit the right thing!");

                                for(var j = 0; j < playersPlayingTemp.length; j++){

                                    // console.log("card yo ", cards[j]);


                                        if(cards[j] != "Joker"){
                                            var cardNumber = cards[j].substr(0, cards[j].indexOf('OF'));
                                            var cardHouse =  cards[j].substr(cards[j].indexOf("OF") + 2);

                                            var cardValue = cardNumber;

                                            console.log("cardnumber: " + cardNumber + " cardhouse: " + cardHouse);
                                            // BLAHSSSSSSSSSS
                                            $('.current-player[data-user='+playersPlayingTemp[j]+'] .toss .playingCards').html(
                                                    ' <span class="card card_2 rank-'+cardNumber.toLowerCase()+' '+cardHouse+'" href="#">'+
                                                    '<span class="rank">'+cardNumber+'</span>'+
                                                    '<span class="suit">&'+cardHouse+';</span>'+
                                                    '</span>');


                                            if(cardNumber == "J"){
                                                cardValue = "12";
                                            }else if(cardNumber == "Q"){
                                                cardValue = "13";
                                            }else if(cardNumber == "K"){
                                                cardValue = "14";
                                            }else if(cardNumber == "A"){
                                                cardValue = "1";
                                            }

                                        }else{

                                            cardValue = "1";

                                            $('.current-player[data-user='+playersPlayingTemp[j]+'] .toss .playingCards').html('<div class="card card_2 joker" href="#"></div>');
                                        }

                                  

                                        tossArray.push({ player: playersPlayingTemp[j], value: cardValue });



                                }



                                 var largestCardValueToss = Math.max.apply(Math, tossArray.map(function(fn){
                                return fn.value;} ))
                                
                                      tossWinner = tossArray.find(function(fn){ return fn.value == largestCardValueToss; })

                                        console.log("tosswinner ", tossWinner.player);


                                  
                                        /* Get toss winner name and display */

                                        var ajxData20 = {'action': 'get-player-name', player: tossWinner.player};

                                          $.ajax({
                                              type: 'POST',
                                              url: 'ajax/getPlayerName.php',
                                              cache: false,
                                              data: ajxData20,
                                              success: function(theName){

                                                // $('#rummyModal').modal('show');
                                            console.log(theName + " has won the toss and will play first hand");


                                        $('.game_message').html('<p>' + theName + ' has won the toss and will play the first hand</p>').show();

                                                
                                            setTimeout(function(){
                                                tossArray.length = 0;
                                                $('.current-player .toss .playingCards').html("");
                                               

                                            }, 5000);    
                                            
                                            /* Toss winner enters the db */

                                                if(creatorCookie){

                                                    var ajxData200 = {'action': 'toss-done', player: tossWinner.player, roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                                                    $.ajax({
                                                        type: 'POST',
                                                        url: 'ajax/insertTossWinner.php',
                                                        cache: false,
                                                        data: ajxData200,
                                                        success: function(result){
                                                            console.log(result);
                                                            if($.trim(result) == "ok"){

                                                            /** === Distribute 13 cards among players === */

                                                                console.log("Now it's time to Distribute!!");

                                                                /* select joker for this game */
                                                        
                                                                setTimeout(function(){    

                                                                    var ajxData201 = {'action': 'choose-joker', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                                                                    $.ajax({
                                                                        type: 'POST',
                                                                        url: 'ajax/chooseJoker.php',
                                                                        cache: false,
                                                                        data: ajxData201,
                                                                        success: function(resultJoker){ 

                                                                        console.log('joker: ' + resultJoker);
                                                                          
                                                                if(resultJoker != "Joker"){

                                                                    var cardNumber = resultJoker.substr(0, resultJoker.indexOf('OF'));
                                                                    var cardHouse =  resultJoker.substr(resultJoker.indexOf("OF") + 2);

                                                                    $('.joker-assign .playingCards').html(

                                                                    '<span class="card card_2 rank-'+cardNumber.toLowerCase()+' '+cardHouse+'" href="#">'+
                                                                    '<span class="rank">'+cardNumber+'</span>'+
                                                                    '<span class="suit">&'+cardHouse+';</span>'+
                                                                    '</span>');

                                                                        
                                                                   }else{

                                                                    $('.joker-assign .playingCards').html('<span class="card card_2 joker"></span>');
                                                                   } 


                                                                /* get throw card */

                                                            var ajxData245 = {'action': 'get-throw-card', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                                                               $.ajax({

                                                                    type: 'POST',
                                                                    url: 'ajax/getThrowCard.php',
                                                                    cache: false,
                                                                    data: ajxData245,
                                                                    dataType: 'json',
                                                                    success: function(result){ 

                                                                    
                                                                     
                                                                     var throwCard = result.throw_card;
                                                               
                                                                      if(throwCard != "Joker"){

                                                                        var cardNumber = throwCard.substr(0, throwCard.indexOf('OF'));
                                                                        var cardHouse =  throwCard.substr(throwCard.indexOf("OF") + 2);       

                                                                            if(tossWinner.player == userId){

                                                                                $('.card-throw .playingCards').html('<a href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+' id="cardDeckSelectShow'+userId+'" class="cardDeckSelect clickable">'+
                                                                                '<div class="card card_2 rank-'+cardNumber+' '+cardHouse+'">'+
                                                                                '<span class="rank">'+cardNumber+'</span>'+
                                                                                '<span class="suit">&'+cardHouse+';</span>'+
                                                                                '</div></a>');


                                                                            }else{

                                                                                 $('.card-throw .playingCards').html('<a href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+' id="cardDeckSelectShow'+userId+'" class="cardDeckSelect noSelect">'+
                                                                                '<div class="card card_2 rank-'+cardNumber+' '+cardHouse+'">'+
                                                                                '<span class="rank">'+cardNumber+'</span>'+
                                                                                '<span class="suit">&'+cardHouse+';</span>'+
                                                                                '</div></a>');


                                                                            }

                                                                   }else{

                                                                        if(tossWinner.player == userId){
                                                                        $('.card-throw .playingCards').append('<a href="javascript:;" data-rank="joker" id="cardDeckSelectShow'+userId+'" class="cardDeckSelect clickable"><div class="card joker card_2"></div></a>');
                                                                        }else{
                                                                            $('.card-throw .playingCards').append(' <a href="javascript:;" data-rank="joker" id="cardDeckSelectShow'+userId+'" class="cardDeckSelect noSelect"><div class="card joker card_2"></div></a>');
                                                                        }

                                                                    } 


                                                                /* update db set current player is tosswinner */

                                                               var ajxData260 = {'action': 'current-player', roomId: roomIdCookie, 
                                                                player: parseInt(tossWinner.player), sessionKey: sessionKeyCookie };

                                                                 $.ajax({

                                                                    type: 'POST',
                                                                    url: 'ajax/updateCurrentPlayer.php',
                                                                    cache: false,
                                                                    data: ajxData260,
                                                                    success: function(result){ 
                                                                        if($.trim(result) == "ok"){
                                                                            console.log("current player updated");
                                                                        }
                                                                        
                                                                    }
                                                                 });   


                                                                 /* deal cards for me */
                                                                 if(gameTypeCookie != "score"){
                                                                    var point = 0;
                                                                    var chipsTaken = 0;
                                                                 }else{
                                                                    var point = 0;
                                                                    var chipsTaken = $.trim($.cookie("chipsToTablePR"));
                                                                 }

                                                                 var ajxData205 = {'action': 'deal-cards', roomId: roomIdCookie, playerId: userId, sessionKey: sessionKeyCookie, point: point, chipsTaken: chipsTaken};

                                                         $.ajax({

                                                          type: 'POST',
                                                            url: 'ajax/dealCards.php',
                                                            cache: false,
                                                            data: ajxData205,
                                                            success: function(result){ 

                                                            if($.trim(result) == "ok"){

                                                                console.log("Card shuffled done!!");

                                                                   /* show my cards */

                                                                var ajxData225 = {'action': 'get-cards', roomId: roomIdCookie, playerId: userId, sessionKey: sessionKeyCookie};

                                                                 $.ajax({

                                                                    type: 'POST',
                                                                    url: 'ajax/getMyCards.php',
                                                                    cache: false,
                                                                    data: ajxData225,
                                                                    dataType: 'json',
                                                                    success: function(myCards){ 

                                                                     
                                                                    console.log("PLAYERS " + playersPlayingTemp);


                                                                    console.log("my cards: " + myCards);

                                                                for(var j = 0; j < playersPlayingTemp.length; j++){


                                                                    if(playersPlayingTemp[j] == userId){

                                                                        for(var i = 0; i < myCards.length; i++){

                                                                            cardsInHand.push(myCards[i]);

                                                                            if(myCards[i] != "Joker"){

                                                                            var cardNumber = myCards[i].substr(0, myCards[i].indexOf('OF'));
                                                                            var cardHouse =  myCards[i].substr(myCards[i].indexOf("OF") + 2);


                                                                             

                                                                                $('.me .playingCards .hand').append(
                                                                                    '<li><a class="card handCard card_2 rank-'+cardNumber.toUpperCase()+' '+cardHouse+'" href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+'>'+
                                                                                        '<span class="rank">'+cardNumber.toUpperCase()+'</span>'+
                                                                                        '<span class="suit">&'+cardHouse+';</span>'+
                                                                                        '</a></li>');

                                                                            }else{
                                                                                 $('.me .playingCards .hand').append('<li class="ui-sortable-handle"><a href="javascript:;" class=" card joker card_2 handCard " data-rank="joker"></a></li>');
                                                                            

                                                                            }    

                                                                            

                                                                        }

                                                                    }

                                                                    if(playersPlayingTemp[j] != userId){

                                                                        $('.current-player[data-user='+playersPlayingTemp[j]+'] .playingCards .deck').append('<li>'+
                                                                                        '<div class="card card_2 back">*</div>'+
                                                                                    '</li><li>'+
                                                                                        '<div class="card card_2 back">*</div>'+
                                                                                    '</li><li>'+
                                                                                        '<div class="card card_2 back">*</div>'+
                                                                                    '</li><li>'+
                                                                                        '<div class="card card_2 back">*</div>'+
                                                                                    '</li><li>'+
                                                                                        '<div class="card card_2 back">*</div>'+
                                                                                    '</li>'
                                                                               );

                                                                    }


                                                                    console.log("COUNT ", j);


                                                                    }


                                                                    } });

                                                                    } } });


                                                                 var signal2 = {type: 'select-joker', message: 'dealing time', jokerCard: resultJoker, throw_card: throwCard, tossWinner: tossWinner.player, winningAmount: winningAmount};
                                                               
                                                                    connection.send(JSON.stringify(signal2));





                                                                    $('.sort').css({'display':'block'});
                                                                    $('.discard').css({'display':'block'});
                                                                    
                                                                if(gameTypeCookie != "deals2" && gameTypeCookie != "deals3"){
                                                                    $('.drop').show();
                                                                }
                                                                    
                                                                   
                                                                   // setTimeout(function(){

                                                                    if(parseInt(userId) === parseInt(tossWinner.player)){
                                                                        $('.drop button').attr('disabled', false);
                                                                        $('.drop button').css({'cursor':'pointer'});
                                                                    }else{
                                                                         $('.drop button').attr('disabled', true);
                                                                         $('.drop button').css({'cursor':'default'});
                                                                    }
                                                                    

                                                                   // }, 2000);
                                                                    

                                                                    $('.sort button').attr('disabled', false);


                                                                    // $('.auto_drop button').attr('disabled', false);

                                                                    $('#cardDeckSelect'+tossWinner.player).addClass("clickable");
                                                                    $('#cardDeckSelect'+tossWinner.player).removeClass("noSelect");

                                                                     $('#cardDeckSelectShow'+tossWinner.player).addClass("clickable");
                                                                     $('#cardDeckSelectShow'+tossWinner.player).removeClass("noSelect");
                                    


                                                                    console.log('throw card : '+ throwCard);

                                                                    console.log("Toss winner last ", tossWinner.player);




                                                                    }
                                                                });        
                                                          
                                                                }

                                                                })

                                                                }, 4000);       


                                                            }
                                                        }
                                                    })

                                                }else{
                                                    console.log("wait for shuffling!");
                                                }


                                              }

                                         });


                            }


                        });



                    }, 3000)

                   
                }


              }else if(dataReceived.type == "select-joker"){

                 /* Other players */

                   selectJokerFlag++;

                   console.log("Select joker flag ", selectJokerFlag);

                   if(selectJokerFlag == 1){

                    console.log(dataReceived.message);



                    /* Show all the game info sections */

                    if(gameTypeCookie != "score"){
                        $('.game_info #game_prize_money').text(dataReceived.winningAmount);
                    }else{
                        $('.game_info #game_prize_money').hide();
                    }           




                            if(gameTypeCookie == "101" && gamePlayersCookie == "2"){
                                var gameName = "101 Pool (2 players)";
                            }else if(gameTypeCookie == "101" && gamePlayersCookie == "6"){
                                var gameName = "101 Pool (6 players)";
                            }else if(gameTypeCookie == "201" && gamePlayersCookie == "2"){
                                var gameName = "201 Pool (2 players)";
                            }else if(gameTypeCookie == "201" && gamePlayersCookie == "6"){
                                var gameName = "201 Pool (6 players)";
                            }else if(gameTypeCookie == "deals2"){
                                var gameName = "deal2";
                            }else if(gameTypeCookie == "deals3"){
                                 var gameName = "deal3";
                            }else if(gameTypeCookie == "score" && gamePlayersCookie == "2"){
                                 var gameName = "Score game (2 players)";
                            }else if(gameTypeCookie == "score" && gamePlayersCookie == "6"){
                                 var gameName = "Score game (6 players)";
                            }

                            $('.game_info #game_name').text(gameName);
                            $('.game_info #game_bet span').text(betValueCookie);
                            $('.game_info2 #table_id a').text(sessionKeyCookie);

         
                    if(dataReceived.jokerCard != "Joker"){

                        var cardNumber = dataReceived.jokerCard.substr(0, dataReceived.jokerCard.indexOf('OF'));
                        var cardHouse =  dataReceived.jokerCard.substr(dataReceived.jokerCard.indexOf("OF") + 2);

                        $('.joker-assign .playingCards').html(

                        '<span class="card card_2 rank-'+cardNumber.toLowerCase()+' '+cardHouse+'" href="#">'+
                        '<span class="rank">'+cardNumber+'</span>'+
                        '<span class="suit">&'+cardHouse+';</span>'+
                        '</span>');


                    }else{

                        $('.joker-assign .playingCards').html('<span class="card card_2 joker"></span>');
                    } 


                    /* Send card deal signal */

                     if(gameTypeCookie != "score"){
                        var point = 0;
                        var chipsTaken = 0;
                     }else{
                        var point = 0;
                        var chipsTaken = $.trim($.cookie("chipsToTablePR"));
                     }

                    


                    var ajxData205 = {'action': 'deal-cards', roomId: roomIdCookie, playerId: userId, sessionKey: sessionKeyCookie, point: point, chipsTaken: chipsTaken};

                    $.ajax({

                      type: 'POST',
                        url: 'ajax/dealCards.php',
                        cache: false,
                        data: ajxData205,
                        success: function(result){ 

                            if($.trim(result) == "ok"){



                                /* show my cards */

                                var ajxData225 = {'action': 'get-cards', roomId: roomIdCookie, playerId: userId, sessionKey: sessionKeyCookie};

                                 $.ajax({

                                    type: 'POST',
                                    url: 'ajax/getMyCards.php',
                                    cache: false,
                                    data: ajxData225,
                                    dataType: 'json',
                                    success: function(myCards){




                                        setTimeout(function(){

                                            console.log("PLAYERS " + playersPlayingTemp);


                                            console.log("my cards: " + myCards);


                                            for(var j = 0; j < playersPlayingTemp.length; j++){




                                                if(playersPlayingTemp[j] == userId){

                                                    for(var i = 0; i < myCards.length; i++){

                                                        cardsInHand.push(myCards[i]);

                                                        

                                                        if(myCards[i] != "Joker"){

                                                            var cardNumber = myCards[i].substr(0, myCards[i].indexOf('OF'));
                                                            var cardHouse =  myCards[i].substr(myCards[i].indexOf("OF") + 2);

                                                            $('.me .playingCards .hand').append(
                                                                '<li><a class="card handCard card_2 rank-'+cardNumber.toUpperCase()+' '+cardHouse+'" href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+'>'+
                                                                    '<span class="rank">'+cardNumber.toUpperCase()+'</span>'+
                                                                    '<span class="suit">&'+cardHouse+';</span>'+
                                                                    '</a></li>');

                                                        }else{
                                                             $('.me .playingCards .hand').append('<li class="ui-sortable-handle"><a href="javascript:;" class="card joker card_2 handCard " data-rank="joker"></a></li>');



                                                        }    

                                                        

                                                    }

                                                }

                                                if(playersPlayingTemp[j] != userId){

                                                    $('.current-player[data-user='+playersPlayingTemp[j]+'] .playingCards .deck').append('<li>'+
                                                                    '<div class="card card_2 back">*</div>'+
                                                                '</li><li>'+
                                                                    '<div class="card card_2 back">*</div>'+
                                                                '</li><li>'+
                                                                    '<div class="card card_2 back">*</div>'+
                                                                '</li><li>'+
                                                                    '<div class="card card_2 back">*</div>'+
                                                                '</li><li>'+
                                                                    '<div class="card card_2 back">*</div>'+
                                                                '</li>'
                                                           );

                                                }


                                                console.log("COUNT ", j);


                                            }

                                            
                                       

                                           var cardNumber = dataReceived.throw_card.substr(0, dataReceived.throw_card.indexOf('OF'));
                                            var cardHouse =  dataReceived.throw_card.substr(dataReceived.throw_card.indexOf("OF") + 2);



                                           if(dataReceived.throw_card != "Joker"){       

                                                if(dataReceived.tossWinner == userId){

                                                    $('.card-throw .playingCards').html('<a href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+' id="cardDeckSelectShow'+userId+'" class="cardDeckSelect clickable">'+
                                                    '<div class="card card_2 rank-'+cardNumber+' '+cardHouse+'">'+
                                                    '<span class="rank">'+cardNumber+'</span>'+
                                                    '<span class="suit">&'+cardHouse+';</span>'+
                                                    '</div></a>');


                                                }else{

                                                     $('.card-throw .playingCards').html('<a href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+' id="cardDeckSelectShow'+userId+'" class="cardDeckSelect noSelect">'+
                                                    '<div class="card card_2 rank-'+cardNumber+' '+cardHouse+'">'+
                                                    '<span class="rank">'+cardNumber+'</span>'+
                                                    '<span class="suit">&'+cardHouse+';</span>'+
                                                    '</div></a>');


                                                }

                                       }else{
                                        
                                            if(dataReceived.tossWinner == userId){
                                             $('.card-throw .playingCards').append(' <a href="javascript:;" data-rank="joker" id="cardDeckSelectShow'+userId+'" class="cardDeckSelect clickable"><div class="card joker card_2"></div></a>');
                                            }else{
                                                 $('.card-throw .playingCards').append(' <a href="javascript:;" data-rank="joker" id="cardDeckSelectShow'+userId+'" class="cardDeckSelect noSelect"><div class="card joker card_2"></div></a>');
                                            }

                                        } 


                                        }, 4000);

                                           console.log("HITTTTTTTTTTTTTTTTTTTTTTTTTTTTTTT");     

                                        

                                             

                                            $('.sort').css({'display':'block'});
                                            $('.discard').css({'display':'block'});
                                            // $('.auto_drop').css({'display':'block'});

                                            if(gameTypeCookie != "deals2" && gameTypeCookie != "deals3"){
                                                $('.drop').show();
                                            }
                                            
                                            if(parseInt(userId) === parseInt(dataReceived.tossWinner)){
                                                $('.drop button').attr('disabled', false);
                                                $('.drop button').css({'cursor':'pointer'});
                                            }else{
                                                 $('.drop button').attr('disabled', true);
                                                 $('.drop button').css({'cursor':'default'});
                                            }


                                            $('.sort button').attr('disabled', false);

                                            

                                            $('#cardDeckSelect'+dataReceived.tossWinner).addClass("clickable");
                                            $('#cardDeckSelect'+dataReceived.tossWinner).removeClass("noSelect");

                                            $('#cardDeckSelectShow'+dataReceived.tossWinner).addClass("clickable");
                                            $('#cardDeckSelectShow'+dataReceived.tossWinner).removeClass("noSelect");


                                            /* send a signal to the owner get the actual points for points rummy */

                                           if(gameTypeCookie == "score"){

                                             var signalGetPoints = {type: 'get-points-owner', message: 'asking the creator to get points'};
                                                               
                                             connection.send(JSON.stringify(signalGetPoints));


                                           } 






                                    } });    


                                

                            }

                        } 

                    });

                
                }

              }else if(dataReceived.type == "reshuffling"){
                 $('.loading_container').show();   
                 $('.loading_container .popup .popup_cont').text("Please wait deck gets reshuffled..");


              }else if(dataReceived.type == "reshuffling-done"){
                 $('.loading_container').hide();
                 $('.loading_container .popup .popup_cont').text("");

                  $('.current-player .playingCardsDiscard .hand').html('');
              
              }else if(dataReceived.type == "get-points-owner"){

               // alert("yo");

                /*GUBHO BHAI*/

                for(var i = 0; i < playersPlayingTemp.length; i++){

                     var ajxDataGetPlDet = {'action': 'get-players', player: playersPlaying[i]};

                      $.ajax({
                        type: 'POST',
                        data: ajxDataGetPlDet,
                        dataType: 'json',
                        cache: false,
                        url: 'ajax/getAllPlayers.php',
                        success: function(player){

                            console.log(player.id + ' ' +player.name);

                             var ajxDataGetPoints = {'action': 'get-points', player: player.id, roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                              $.ajax({
                                type: 'POST',
                                data: ajxDataGetPoints,
                                cache: false,
                                url: 'ajax/getPointsPR.php',
                                success: function(chips){

                                $('.current-player[data-user="'+player.id+'"] .player_name #score').text(chips);


                                } });    

                      } });

                   }  


                    var signalGetPointsOthers = {type: 'get-points-others', message: 'asking others to get points'};
                    connection.send(JSON.stringify(signalGetPointsOthers));


              }else if(dataReceived.type == "get-points-others"){

                //alert("yo 2");

                 /*GUBHO BHAI 2*/

                for(var i = 0; i < playersPlayingTemp.length; i++){

                     var ajxDataGetPlDet = {'action': 'get-players', player: playersPlaying[i]};

                      $.ajax({
                        type: 'POST',
                        data: ajxDataGetPlDet,
                        dataType: 'json',
                        cache: false,
                        url: 'ajax/getAllPlayers.php',
                        success: function(player){

                            console.log(player.id + ' ' +player.name);

                             var ajxDataGetPoints = {'action': 'get-points', player: player.id, roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                              $.ajax({
                                type: 'POST',
                                data: ajxDataGetPoints,
                                cache: false,
                                url: 'ajax/getPointsPR.php',
                                success: function(chips){

                                $('.current-player[data-user="'+player.id+'"] .player_name #score').text(chips);


                                } });    






                         var tblRow1 = $('<tr data-user="'+player.id+'"><td id="name">'+player.name+'</td><td id="result" style="width: 167px;"></td><td id="cards" style="width: 400px;"></td><td id="score"></td><td id="total_score"></td></tr>');  


                      } });

                   }  


              }else if(dataReceived.type == "deal-card"){
                console.log(dataReceived.message);

                dealCardFlag++;

                console.log("dealCardFlag ", dealCardFlag);

                if(dealCardFlag == 1){

                /* Player 1 */

                var ajxData205 = {'action': 'deal-cards', roomId: roomIdCookie, playerId: userId, sessionKey: sessionKeyCookie};

                 $.ajax({

                      type: 'POST',
                        url: 'ajax/dealCards.php',
                        cache: false,
                        data: ajxData205,
                        success: function(result){ 

                            if($.trim(result) == "ok"){

                                console.log("Card shuffled done!!");

                                   /* show my cards */

                                var ajxData225 = {'action': 'get-cards', roomId: roomIdCookie, playerId: userId, sessionKey: sessionKeyCookie};

                                 $.ajax({

                                    type: 'POST',
                                    url: 'ajax/getMyCards.php',
                                    cache: false,
                                    data: ajxData225,
                                    dataType: 'json',
                                    success: function(myCards){ 

                                        setTimeout(function(){

                                            console.log("PLAYERS " + playersPlayingTemp);


                                            console.log("my cards: " + myCards);

                                            for(var j = 0; j < playersPlayingTemp.length; j++){


                                                if(playersPlayingTemp[j] == userId){

                                                    for(var i = 0; i < myCards.length; i++){

                                                        cardsInHand.push(myCards[i]);

                                                        if(myCards[i] != "Joker"){

                                                        var cardNumber = myCards[i].substr(0, myCards[i].indexOf('OF'));
                                                        var cardHouse =  myCards[i].substr(myCards[i].indexOf("OF") + 2);


                                                         

                                                            $('.me .playingCards .hand').append(
                                                                '<li><a class="card handCard card_2 rank-'+cardNumber.toUpperCase()+' '+cardHouse+'" href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+'>'+
                                                                    '<span class="rank">'+cardNumber.toUpperCase()+'</span>'+
                                                                    '<span class="suit">&'+cardHouse+';</span>'+
                                                                    '</a></li>');

                                                        }else{
                                                             $('.me .playingCards .hand').append('<li class="ui-sortable-handle"><a href="javascript:;" class=" card joker card_2 handCard " data-rank="joker"></a></li>');
                                                        

                                                        }    

                                                        

                                                    }

                                                }

                                                if(playersPlayingTemp[j] != userId){

                                                    $('.current-player[data-user='+playersPlayingTemp[j]+'] .playingCards .deck').append('<li>'+
                                                                    '<div class="card card_2 back">*</div>'+
                                                                '</li><li>'+
                                                                    '<div class="card card_2 back">*</div>'+
                                                                '</li><li>'+
                                                                    '<div class="card card_2 back">*</div>'+
                                                                '</li><li>'+
                                                                    '<div class="card card_2 back">*</div>'+
                                                                '</li><li>'+
                                                                    '<div class="card card_2 back">*</div>'+
                                                                '</li>'
                                                           );

                                                }


                                                console.log("COUNT ", j);


                                            }


                                            /* Check toss winner */

                                             var ajxData235 = {'action': 'get-toss-winner', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                                             $.ajax({

                                                type: 'POST',
                                                url: 'ajax/getTossWinner.php',
                                                cache: false,
                                                data: ajxData235,
                                                success: function(winner){ 

                                                    console.log("toss winner received : " + winner);


                                                    $('.sort').css({'display':'block'});
                                                    $('.discard').css({'display':'block'});
                                                    $('.auto_drop').css({'display':'block'});

                                                    $('.sort button').attr('disabled', false);
                                                  
                                                    $('.auto_drop button').attr('disabled', false);

                                                } 
                                            });




                                        }, 4000);




                                    } });    


                      

                               
                            }

                        } 

                    });


                }

              }else if(dataReceived.type == "throw-card"){

                /* me */

                    throwCardFlag++;

                    if(throwCardFlag == 1){


                      var cardNumber = dataReceived.throw_card.substr(0, dataReceived.throw_card.indexOf('OF'));
                      var cardHouse =  dataReceived.throw_card.substr(dataReceived.throw_card.indexOf("OF") + 2);



                       if(dataReceived.throw_card != "Joker"){       

                            if(dataReceived.tossWinner == userId){

                                $('.card-throw .playingCards').html('<a href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+' id="cardDeckSelectShow'+userId+'" class="cardDeckSelect clickable">'+
                                '<div class="card card_2 rank-'+cardNumber+' '+cardHouse+'">'+
                                '<span class="rank">'+cardNumber+'</span>'+
                                '<span class="suit">&'+cardHouse+';</span>'+
                                '</div></a>');


                            }else{

                                 $('.card-throw .playingCards').html('<a href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+' id="cardDeckSelectShow'+userId+'" class="cardDeckSelect noSelect">'+
                                '<div class="card card_2 rank-'+cardNumber+' '+cardHouse+'">'+
                                '<span class="rank">'+cardNumber+'</span>'+
                                '<span class="suit">&'+cardHouse+';</span>'+
                                '</div></a>');


                            }

                   }else{
                    
                        if(dataReceived.tossWinner == userId){
                         $('.card-throw .playingCards').append(' <a href="javascript:;" data-rank="joker" id="cardDeckSelectShow'+userId+'" class="cardDeckSelect clickable"><div class="card joker card_2"></div></a>');
                        }else{
                             $('.card-throw .playingCards').append(' <a href="javascript:;" data-rank="joker" id="cardDeckSelectShow'+userId+'" class="cardDeckSelect noSelect"><div class="card joker card_2"></div></a>');
                        }

                    } 


                      /* update db set current player is tosswinner */

                       var ajxData260 = {'action': 'current-player', roomId: roomIdCookie, 
                        player: parseInt(dataReceived.tossWinner), sessionKey: sessionKeyCookie};

                             $.ajax({

                                type: 'POST',
                                url: 'ajax/updateCurrentPlayer.php',
                                cache: false,
                                data: ajxData260,
                                success: function(result){ 
                                    if($.trim(result) == "ok"){
                                        console.log("current player updated");
                                    }
                                    // console.log("current-player " + result);
                                }
                             });       




                    console.log("throw card get " + dataReceived.throw_card);
                     console.log("toss winner get " + dataReceived.tossWinner);
                    


                }     

              }else if(dataReceived.type == "card-discarded"){


                /* Get the card discarded */

              
                  var ajxData852 = { 'action': 'get-my-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};


                 $.ajax({
                        type: 'POST',
                        data: ajxData852,
                        cache: false,
                        url: 'ajax/getMyStatus.php',
                        success: function(status){


                        if($.trim(status) != "out"){

                                console.log(dataReceived.message);

                                var cardToBeShown = dataReceived.cardDiscarded;
                                var playerPlayed = dataReceived.player;
                                var nextPlayer = dataReceived.nextPlayer;
                                var playerPlayed = dataReceived.player;

                                console.log("nextplayer get ", nextPlayer);


                                if(cardToBeShown != "Joker"){

                                var cardNumber1 = cardToBeShown.substr(0, cardToBeShown.indexOf('OF'));
                                var cardHouse1 =  cardToBeShown.substr(cardToBeShown.indexOf("OF") + 2);

                                $('.current-player[data-user="'+playerPlayed+'"] .playingCardsDiscard .hand').append('<li><span class="card card_3 rank-'+cardNumber1+' '+cardHouse1+'">'+
                                 '<span class="rank">'+cardNumber1+'</span>'+
                                 '<span class="suit">&'+cardHouse1+';</span>'+
                                 '</span></li>');


                                



                                }else{

                                     $('.current-player[data-user="'+playerPlayed+'"] .playingCardsDiscard .hand').append('<li><span class="card joker card_3"></span></li>');


                                }            


                              

                                $('.current-player[data-user="'+playerPlayed+'"] .playingCardsDiscard .hand li:empty').remove();




                                

                                  
                               
                                    if(parseInt(userId) == parseInt(nextPlayer)){

                                        if(cardToBeShown != "Joker"){

                                            var cardNumber = cardToBeShown.substr(0, cardToBeShown.indexOf('OF'));
                                            var cardHouse =  cardToBeShown.substr(cardToBeShown.indexOf("OF") + 2);



                                          $('.card-throw .playingCards').append('<a href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+' id="cardDeckSelectShow'+nextPlayer+'" class="cardDeckSelect clickable">'+
                                         '<div class="card card_2 rank-'+cardNumber+' '+cardHouse+'">'+
                                         '<span class="rank">'+cardNumber+'</span>'+
                                         '<span class="suit">&'+cardHouse+';</span>'+
                                                '</div></a>');




                                        }else{

                                              $('.card-throw .playingCards').append(' <a href="javascript:;" data-rank="joker" id="cardDeckSelectShow'+nextPlayer+'" class="cardDeckSelect clickable"><div class="card joker card_2"></div></a>');


                                        } 

                                        $('.game_message').html('<p>Your turn to play</p>').show();

                                         $('.drop button').attr('disabled', false);
                                         $('.drop button').css({'cursor':'pointer'});

                                  }else{


                                        if(cardToBeShown != "Joker"){

                                            var cardNumber = cardToBeShown.substr(0, cardToBeShown.indexOf('OF'));
                                            var cardHouse =  cardToBeShown.substr(cardToBeShown.indexOf("OF") + 2);



                                          $('.card-throw .playingCards').append('<a href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+' id="cardDeckSelectShow'+nextPlayer+'" class="cardDeckSelect noSelect">'+
                                         '<div class="card card_2 rank-'+cardNumber+' '+cardHouse+'">'+
                                         '<span class="rank">'+cardNumber+'</span>'+
                                         '<span class="suit">&'+cardHouse+';</span>'+
                                                '</div></a>');

                                         
                                                              


                                        }else{

                                              $('.card-throw .playingCards').append(' <a href="javascript:;" data-rank="joker" id="cardDeckSelectShow'+nextPlayer+'" class="cardDeckSelect noSelect"><div class="card joker card_2"></div></a>');

                                             
                                        }

                                        /* get-player-name */

                                         var ajxData20 = {'action': 'get-player-name', player: parseInt(nextPlayer)};

                                          $.ajax({
                                              type: 'POST',
                                              url: 'ajax/getPlayerName.php',
                                              cache: false,
                                              data: ajxData20,
                                              success: function(theName){
                                               $('.game_message').html('<p>' + theName + ' will play</p>').show();
                                                
                                              } });   

                                          $('.drop button').attr('disabled', true);
                                          $('.drop button').css({'cursor':'default'});


                                  }  


                                    /*  update db current player */

                                    var ajxData270 = {'action': 'current-player', roomId: roomIdCookie, 
                                        player: nextPlayer, sessionKey: sessionKeyCookie};

                                         $.ajax({

                                            type: 'POST',
                                            url: 'ajax/updateCurrentPlayer.php',
                                            cache: false,
                                            data: ajxData270,
                                            success: function(result){ 
                                                if($.trim(result) == "ok"){
                                                    console.log("current player updated");

                                                    if(userId == nextPlayer){


                                                        cardPull = 0;
                                                        cardDiscard = 0;

                                                    $('.cardDeckSelect').removeClass('noSelect').addClass('clickable');


                                                    }

                                                }
                                                
                                            }
                                         });     

                        } } });           



              }else if(dataReceived.type == "card-pulled-show-card" ){

                    console.log(dataReceived.message);
                    // console.log("player pulled ", dataReceived.player);

                    var cardPulled = dataReceived.cardPulled;


                     if(cardPulled != "Joker"){

                        var cardNumber = cardPulled.substr(0, cardPulled.indexOf('OF'));
                        var cardHouse =  cardPulled.substr(cardPulled.indexOf("OF") + 2);



                    $('.card-throw .playingCards a[data-rank='+cardNumber+'][data-suit='+cardHouse+']').remove();

                                          


                    }else{

                        $('.card-throw .playingCards a[data-rank=joker]').remove();

                    } 

              }else if(dataReceived.type == "card-melded"){

               

                 /* get my status */

                  var ajxData852 = { 'action': 'get-my-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};


                 $.ajax({
                        type: 'POST',
                        data: ajxData852,
                        cache: false,
                        url: 'ajax/getMyStatus.php',
                        success: function(status){

                        // alert("hit my status");

                        console.log("my status jeta ikhlam,,..  .. " , status);

                        if($.trim(status) != "out"){

                        $('.game_message').html('').show();    
                        console.log("get card melded message : " , dataReceived.message);

                        var playerWhoMelded = dataReceived.player;
                        var cardToBeShown = dataReceived.cardDiscarded;


                             if(userId == nextPlayer){

                                if(cardToBeShown != "Joker"){

                                    var cardNumber = cardToBeShown.substr(0, cardToBeShown.indexOf('OF'));
                                    var cardHouse =  cardToBeShown.substr(cardToBeShown.indexOf("OF") + 2);



                                  $('.card-throw .playingCards').append('<a href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+' id="cardDeckSelectShow'+userId+'" class="cardDeckSelect clickable">'+
                                 '<div class="card card_2 rank-'+cardNumber+' '+cardHouse+'">'+
                                 '<span class="rank">'+cardNumber+'</span>'+
                                 '<span class="suit">&'+cardHouse+';</span>'+
                                        '</div></a>');

                                                      


                                }else{

                                      $('.card-throw .playingCards').append(' <a href="javascript:;" data-rank="joker" id="cardDeckSelectShow'+userId+'" class="cardDeckSelect clickable"><div class="card joker card_2"></div></a>');

                                } 

                          }else{


                            if(cardToBeShown != "Joker"){

                                var cardNumber = cardToBeShown.substr(0, cardToBeShown.indexOf('OF'));
                                var cardHouse =  cardToBeShown.substr(cardToBeShown.indexOf("OF") + 2);



                              $('.card-throw .playingCards').append('<a href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+' id="cardDeckSelectShow'+userId+'" class="cardDeckSelect noSelect">'+
                             '<div class="card card_2 rank-'+cardNumber+' '+cardHouse+'">'+
                             '<span class="rank">'+cardNumber+'</span>'+
                             '<span class="suit">&'+cardHouse+';</span>'+
                                    '</div></a>');

                                                  


                            }else{

                                  $('.card-throw .playingCards').append(' <a href="javascript:;" data-rank="joker" id="cardDeckSelectShow'+userId+'" class="cardDeckSelect noSelect"><div class="card joker card_2"></div></a>');

                            } 


                      }


                        /* get player name  */

                         var ajxData20 = {'action': 'get-player-name', player: playerWhoMelded};

                              $.ajax({
                                  type: 'POST',
                                  url: 'ajax/getPlayerName.php',
                                  cache: false,
                                  data: ajxData20,
                                  success: function(theName){

                                     // $('#rummyModal').modal('show');
                                     // $('.modal-body p').text(theName + " has melded. Validating. Please wait!");

                                     $('.loading_container .popup .popup_cont').text("");
                                     $('.loading_container').css({'display':'block'});
                                     $('.loading_container .popup .popup_cont').text(theName + " has melded. Validating. Please wait!");


                                  }

                             });


                    }

                } });    


              }else if(dataReceived.type == "wrong-meld-six-players"){

                    

                   
                    var melder = dataReceived.firstMelder;
                    var totalPts = dataReceived.totalPoints;
                    var event = dataReceived.event;

                    var counterT = 3;

                    if(event == "lost"){

                         var ajxData20 = {'action': 'get-player-name', player: melder};

                          $.ajax({
                              type: 'POST',
                              url: 'ajax/getPlayerName.php',
                              cache: false,
                              data: ajxData20,
                              success: function(theName){ 

                                $('.loading_container').css({'display':'block'});
                                $('.loading_container .popup .popup_cont').text(theName + " has melded incorrectly");


                         } });



                    }

                    


                      


                     var intervalT = setInterval(function(){

                        if(counterT <= 0){

                            clearInterval(intervalT);

                        if(gameTypeCookie != "score"){    
                            $('.current-player[data-user="'+melder+'"] .player_name #score').text(parseInt(totalPts));
                        }    

                         $('.loading_container').css({'display':'none'});
                         $('.loading_container .popup .popup_cont').text("");

                             /* Check status of wrongmelder */

                         var ajxData04 = {'action': 'get-status-wrongmelder', roomId: roomIdCookie, player: melder, sessionKey: sessionKeyCookie};

                         $.ajax({
                          type: 'POST',
                          url: 'ajax/getStatusWrongMelder.php',
                          cache: false,
                          data: ajxData04,
                          success: function(status){

                                if(status != "over"){


                                    /* The player will play in the next round as well */

                                    for(var i = 0; i < playersPlayingTemp.length; i++){
                                       
                                        playersPlayingNextRound.push(playersPlayingTemp[i]);

                                        
                                    }

                                   
                                    console.log("players playing next round ...... ", playersPlayingNextRound);

                                     if(removeCardFromGroups(parseInt(melder), playersPlayingTemp)){

                                     console.log("players playing ....... ", playersPlayingTemp);


                                      /*  get last toss winner from  db */

                                     setTimeout(function(){

                                          var ajxData235 = {'action': 'get-toss-winner', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                                         $.ajax({

                                            type: 'POST',
                                            url: 'ajax/getTossWinner.php',
                                            cache: false,
                                            data: ajxData235,
                                            success: function(lastWinner){ 



                                                console.log("toss winner received now :) : " + lastWinner);

                                  
                                                var nextPlayer;

                                                if( getItem(playersPlayingTemp, parseInt(lastWinner)) ){
                                                    nextPlayer = getItem(playersPlayingTemp, parseInt(lastWinner));
                                                }else{
                                                  nextPlayer = playersPlayingTemp[0];
                                                }

                                                console.log("nextplayer ", nextPlayer);


                                                
                                                     if(parseInt(userId) == parseInt(nextPlayer)){
                                                         $('.cardDeckSelect').removeClass('noSelect').addClass('clickable');

                                                         $('.game_message').html('<p>Your turn</p>').show();

                                                     }else{
                                                        $('.cardDeckSelect').removeClass('clickable').addClass('noSelect');


                                                          var ajxData20 = {'action': 'get-player-name', player: parseInt(nextPlayer)};

                                                          $.ajax({
                                                              type: 'POST',
                                                              url: 'ajax/getPlayerName.php',
                                                              cache: false,
                                                              data: ajxData20,
                                                              success: function(theName){
                                                                  $('.game_message').html('<p>' + theName + ' will play</p>').show();
                                                                
                                                              } }); 


                                                     }

                                                     if(event == "drop"){
                                                        var drop = true;
                                                     }else{
                                                        var drop = false;
                                                     }


                                                  var signal13 = {type: 'get-scoreboard-wrongshow-gamegoing', message: 'continue game', playersPlayingTemp: playersPlayingTemp, playersPlayingNextRound: playersPlayingNextRound, nextPlayer: nextPlayer, melder: melder, drop: drop};

                                                connection.send(JSON.stringify(signal13)); 


                                           


                                            } 
                                        });

                                     }, 3000);

                                    }  

                                    cardPull = 0;
                                    cardDiscard = 0;


                                     /* remove card decks of melder */

                                     // $('.group_blog5').remove();

                                     $('.current-player[data-user="'+melder+'"] .playingCards .deck').html("");



                                     /* show toss card of melder */
                                    $('.current-player[data-user="'+melder+'"] .toss .playingCards').html('<div class="card card_2 back"></div>');
                                  

                                }

                          }
                          
                        });    


                      




                        }

                        counterT--;



                     }, 1000);


              }else if(dataReceived.type == "get-scoreboard-six-players"){

                // alert("get scoreboard 6 players");

                 console.log("get scoreboard message: ", dataReceived.message);

                 /* get my status */

              var ajxData852 = { 'action': 'get-my-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};


             $.ajax({
                    type: 'POST',
                    data: ajxData852,
                    cache: false,
                    url: 'ajax/getMyStatus.php',
                    success: function(status){

                    // alert("hit my status");

                    console.log("my status jeta ikhlam,,..  .. " , status);

                    if($.trim(status) != "out"){

                        /* meld update */

                         checkIfAllMelded(function(){

                            /* Update all the melded groups in db */

                                var ajxData81200 = { 'action': 'update-all-groups', roomId: roomIdCookie, player: userId, meldedGroup1: meldedGroup1, meldedGroup2: meldedGroup2, meldedGroup3: meldedGroup3, meldedGroup4: meldedGroup4, sessionKey: sessionKeyCookie};


                                $.ajax({
                                    type: 'POST',
                                    data: ajxData81200,
                                    cache: false,
                                    url: 'ajax/updateMeldedGroups.php',
                                    success: function(result){
                                       if($.trim(result) == "ok"){
                                            console.log("all groups updated");
                                       }
                                    }
                                });


                            }); 


                        var melder = dataReceived.firstMelder;
                        var status = dataReceived.status;
                       

                      
                        
                        console.log("CHINTEEEEE");
                        

                        console.log("melder ", melder);
                        console.log("status ", status);

                        

                        if(status == "wrongshow" || status == "drop"){

                           console.log("BLAHHHHH");

                             

                              if(playersPlayingTemp.length == 2){

                                

                                 var ajxData853 = { 'action': 'update-player-melded', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};


                                 $.ajax({
                                        type: 'POST',
                                        data: ajxData853,
                                        cache: false,
                                        url: 'ajax/updatePlayerMelded.php',
                                        success: function(result){
                                            console.log(result);
                                        } });






                                    /* update melded count */

                                    var ajxData852 = { 'action': 'update-melded-count', roomId: roomIdCookie, sessionKey: sessionKeyCookie};


                                     $.ajax({
                                            type: 'POST',
                                            data: ajxData852,
                                            cache: false,
                                            url: 'ajax/updateMeldedCount.php',
                                            success: function(result){
                                                console.log(result);
                                    } });  



                               

                             
                                 /*  get wrong melders */

                                 var ajxData85200 = { 'action': 'get-wrong-melders', roomId: roomIdCookie, sessionKey: sessionKeyCookie};
                                 
                                 $.ajax({
                                     type: 'POST',
                                     data: ajxData85200,
                                     dataType: 'json',
                                     cache: false,
                                     url: 'ajax/getWrongMelders.php',
                                     success: function(wrongMelders){
                                        
                                        console.log("wrong melders -==============", wrongMelders);




                                        for(var i = 0; i < wrongMelders.length; i++){

                                            wrongMeldersArray.push(wrongMelders[i]);


                                            if(parseInt(wrongMelders[i]) != parseInt(userId)){

                                                var ajxData701 = {'action': 'update-score', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                             $.ajax({
                                                    type: 'POST',
                                                    data: ajxData701,
                                                    cache: false,
                                                    url: 'ajax/updateScoreToZero.php',
                                                    success: function(result){
                                                        if( $.trim(result == "ok") ){

                                                            // alert("hittt 22");

                                                             


                                                            }   } 
                                                        
                                                        });              


                                            }

                                        }

                                         setTimeout(function(){ 
                                            $('.loading_container').css({'display':'none'});
                                        }, 3000);


                                     } });
                              
                                
                                 

                              
                                            setTimeout(function(){


                                             console.log("players TEMP ", playersPlayingTemp);
                                             console.log('hit');
                                             $('.result_sec').css({'display': 'block'});

                                             for(var i = 0; i < playersPlaying.length; i++){

                                                console.log('doing for ', playersPlaying[i]);

                                                 var ajxData703 = {'action': 'get-players', player: playersPlaying[i]};

                                                  $.ajax({
                                                    type: 'POST',
                                                    data: ajxData703,
                                                    dataType: 'json',
                                                    cache: false,
                                                    url: 'ajax/getAllPlayers.php',
                                                    success: function(player){

                                                        console.log(player.id + ' ' +player.name);

                                                        /*MAKAAL*/

                                                         if(gameTypeCookie != "score"){

                                                        var tblRow1 = $('<tr data-user="'+player.id+'"><td id="name">'+player.name+'</td><td id="result"></td><td id="cards"></td><td id="score"></td><td id="total_score"></td></tr>');

                                                        $('.result_sec tbody[id="score_reports"]').append(tblRow1);

                                                       

                                                         var ajxData704 = {'action': 'get-scoreboard', roomId: roomIdCookie, player: player.id, sessionKey: sessionKeyCookie};

                                                          $.ajax({
                                                            type: 'POST',
                                                            data: ajxData704,
                                                            dataType: 'json',
                                                            cache: false,
                                                            url: 'ajax/getScoreBoard.php',
                                                            success: function(results){

                                                                  var ajxData85201 = { 'action': 'check-wrong-melder', roomId: roomIdCookie, player: player.id, sessionKey: sessionKeyCookie};
                         
                                                                 $.ajax({
                                                                     type: 'POST',
                                                                     data: ajxData85201,
                                                                  
                                                                     cache: false,
                                                                     url: 'ajax/checkWrongMelder.php',
                                                                     success: function(flag){


                                                                        console.log(" report check wrong melder for ", player.id ," - flag ", flag);
                                                                   
                                                                        var statusFlag;

                                                                       
                                                                        if(flag == 1){
                                                                            statusFlag = "Lost";
                                                                        }else{
                                                                            statusFlag = "<img src='images/winner.png'>";
                                                                        }

                                                                          $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="result"]').html(statusFlag);

                                                                   



                                                                 

                                                                } })     


                                                                var points = results.points;
                                                                var totalPts = results.total_points;


                                                               if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                                   $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]').html(showCardsInScoreboard(player.id, roomIdCookie, sessionKeyCookie));

                                                                }


                                                                $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="score"]').text(Math.round(points));
                                                            
                                                                $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_score"]').text(Math.round(totalPts));

                                                            


                                                            }
                                                            
                                                         }); 


                                                         }else{

                                                            /*MAKAL2*/

                                                            var tblRow1 = $('<tr data-user="'+player.id+'"><td id="name">'+player.name+'</td><td id="result"></td><td id="cards"></td><td id="count"></td><td id="total_chips"></td></tr>');

                                                        $('.result_sec tbody[id="score_reports"]').append(tblRow1);

                                                       

                                                         var ajxData704 = {'action': 'get-scoreboard', roomId: roomIdCookie, player: player.id, sessionKey: sessionKeyCookie};

                                                          $.ajax({
                                                            type: 'POST',
                                                            data: ajxData704,
                                                            dataType: 'json',
                                                            cache: false,
                                                            url: 'ajax/getScoreBoard.php',
                                                            success: function(results){

                                                                  var ajxData85201 = { 'action': 'check-wrong-melder', roomId: roomIdCookie, player: player.id, sessionKey: sessionKeyCookie};
                         
                                                                 $.ajax({
                                                                     type: 'POST',
                                                                     data: ajxData85201,
                                                                  
                                                                     cache: false,
                                                                     url: 'ajax/checkWrongMelder.php',
                                                                     success: function(flag){


                                                                        console.log(" report check wrong melder for ", player.id ," - flag ", flag);
                                                                   
                                                                        var statusFlag;

                                                                       
                                                                        if(flag == 1){
                                                                            statusFlag = "Lost";
                                                                        }else{
                                                                            statusFlag = "<img src='images/winner.png'>";
                                                                        }

                                                                          $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="result"]').html(statusFlag);

                                                                   



                                                                 

                                                                } })     


                                                                var points = results.points;
                                                                var totalPts = results.total_points;

                                                               
                                                                

                                                                $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="count"]').text(points);

                                                                if(totalPts != 0.00){
                                                            
                                                                    $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_chips"]').text('-'+totalPts);

                                                                      if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                                        $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]').html(showCardsInScoreboard(player.id, roomIdCookie, sessionKeyCookie));

                                                                     }


                                                                }else{
                                                                     $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_chips"]').text('');
                                                                }

                                                              

                                                            }
                                                            
                                                         }); 


                                                         } 

                                                      }      


                                                    }); 



                                            }

                                        

                                           
                                             setTimeout(function(){

                                                    playersPlayingTemp.length = 0;
                                                    playersPlayingTemp = playersPlaying.slice();       




                                                       var signal13 = {type: 'get-scoreboard-melder-six-player', message: 'asking the melder to get scoreboard', myStatus: "lost", gameStatusTrick: true, playersPlaying: playersPlaying, playersPlayingTemp:playersPlayingTemp, melder: melder};

                                                         connection.send(JSON.stringify(signal13));   



                                               




                                                console.log("Now it's your turn 3!!"); 
                                            }, 2000);

                                            
                                       
                                                 

                                }, 3000);
                                               

                                }

                             }


                     }

                     else{


                        var ajxData20555 = {'action': 'get-connection-id', roomId: roomIdCookie, playerId: userId, sessionKey: sessionKeyCookie};

                        $.ajax({

                            type: 'POST',
                            url: 'ajax/getConnectionId.php',
                            cache: false,
                            data: ajxData20555,
                            success: function(connectionId){ 
                            
                                console.log(connectionId);
                                // // alert("connection removed");
                                // connection.remove(connectionId);
                                connection.close();
                                // location.reload();


                             } });   

                        
                    } 



                } });     



              }else if(dataReceived.type == "get-scoreboard"){

                    console.log("get scoreboard message: ", dataReceived.message);

                    //alert("get scoreboard");

                 var ajxData852 = { 'action': 'get-my-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};


                 $.ajax({
                        type: 'POST',
                        data: ajxData852,
                        cache: false,
                        url: 'ajax/getMyStatus.php',
                        success: function(status){

                        // alert("hit my status");

                        console.log("my status jeta ikhlam,,..  .. " , status);

                        if($.trim(status) != "out"){


                            var melder = dataReceived.firstMelder;
                            var status = dataReceived.status;
                          

                            console.log("melder ", melder);
                            console.log("status ", status);

                            

                            if(status == "wrongshow" || status == "drop"){

                                checkIfAllMelded(function(){

                                    /* Update all the melded groups in db */

                                        var ajxData81200 = { 'action': 'update-all-groups', roomId: roomIdCookie, player: userId, meldedGroup1: meldedGroup1, meldedGroup2: meldedGroup2, meldedGroup3: meldedGroup3, meldedGroup4: meldedGroup4, sessionKey: sessionKeyCookie};


                                        $.ajax({
                                            type: 'POST',
                                            data: ajxData81200,
                                            cache: false,
                                            url: 'ajax/updateMeldedGroups.php',
                                            success: function(result){
                                               if($.trim(result) == "ok"){
                                                    console.log("all groups updated");
                                               }
                                            }
                                        });


                                    }); 

                                 

                                   if(gamePlayersCookie == "2"){

                                        /* update melded count */

                                        var ajxData850 = { 'action': 'update-melded-count', roomId: roomIdCookie, sessionKey: sessionKeyCookie};


                                        $.ajax({
                                            type: 'POST',
                                            data: ajxData850,
                                            cache: false,
                                            url: 'ajax/updateMeldedCount.php',
                                            success: function(result){
                                                console.log(result);
                                                // alert("updated melded count!");
                                            } });

                                   }

                                  

                                    var ajxData701 = {'action': 'update-score', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie, gameType: gameTypeCookie};

                                     $.ajax({
                                            type: 'POST',
                                            data: ajxData701,
                                            cache: false,
                                            url: 'ajax/updateScoreToZero.php',
                                            success: function(result){
                                                if( $.trim(result == "ok") ){


                                                      setTimeout(function(){ 
                                                        $('.loading_container').css({'display':'none'});
                                                      }, 3000);


                                                        setTimeout(function(){


                                                         console.log(playersPlaying);
                                                         console.log('hit');
                                                         $('.result_sec').css({'display': 'block'});

                                                         for(var i = 0; i < playersPlaying.length; i++){

                                                            console.log('doing for ', playersPlaying[i]);

                                                             var ajxData703 = {'action': 'get-players', player: playersPlaying[i]};

                                                              $.ajax({
                                                                type: 'POST',
                                                                data: ajxData703,
                                                                dataType: 'json',
                                                                cache: false,
                                                                url: 'ajax/getAllPlayers.php',
                                                                success: function(player){


                                                                    if(gameTypeCookie != "score"){

                                                                    console.log(player.id + ' ' +player.name);



                                                                    var tblRow1 = $('<tr data-user="'+player.id+'"><td id="name">'+player.name+'</td><td id="result"></td><td id="cards"></td><td id="score"></td><td id="total_score"></td></tr>');

                                                                    $('.result_sec tbody[id="score_reports"]').append(tblRow1);

                                                                     var status;

                                                                    if(melder == player.id && dataReceived.status == "wrongshow"){
                                                                        status = "wrong show";
                                                                    }else if(melder == player.id && dataReceived.status == "drop"){
                                                                        status = "Drop";
                                                                    }else if(melder != player.id){
                                                                        status = "<img src='images/winner.png'>";
                                                                    }


                                                                     var ajxData704 = {'action': 'get-scoreboard', roomId: roomIdCookie, player: player.id, sessionKey: sessionKeyCookie};

                                                                      $.ajax({
                                                                        type: 'POST',
                                                                        data: ajxData704,
                                                                        dataType: 'json',
                                                                        cache: false,
                                                                        url: 'ajax/getScoreBoard.php',
                                                                        success: function(results){

                                                                            var points = results.points;
                                                                            var totalPts = results.total_points;


                                                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="result"]').html(status);


                                                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="score"]').text(Math.round(points));
                                                                        
                                                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_score"]').text(Math.round(totalPts));

                                                                            if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                                                $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]').html(showCardsInScoreboard(player.id, roomIdCookie, sessionKeyCookie));

                                                                            }





                                                                        }
                                                                        
                                                                     }); 

                                                                     }else{

                                                                         console.log(player.id + ' ' +player.name);



                                                                    var tblRow1 = $('<tr data-user="'+player.id+'"><td id="name">'+player.name+'</td><td id="result"></td><td id="cards"></td><td id="count"></td><td id="total_chips"></td></tr>');

                                                                    $('.result_sec tbody[id="score_reports"]').append(tblRow1);

                                                                     var status;

                                                                    if(melder == player.id && dataReceived.status == "wrongshow"){
                                                                        status = "wrong show";
                                                                    }else if(melder == player.id && dataReceived.status == "drop"){
                                                                        status = "Drop";
                                                                    }else if(melder != player.id){
                                                                        status = "<img src='images/winner.png'>";
                                                                    }


                                                                     var ajxData704 = {'action': 'get-scoreboard', roomId: roomIdCookie, player: player.id, sessionKey: sessionKeyCookie};

                                                                      $.ajax({
                                                                        type: 'POST',
                                                                        data: ajxData704,
                                                                        dataType: 'json',
                                                                        cache: false,
                                                                        url: 'ajax/getScoreBoard.php',
                                                                        success: function(results){

                                                                            var points = results.points;
                                                                            var totalPts = results.total_points;


                                                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="result"]').html(status);


                                                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="count"]').text(Math.round(points));

                                                                            if(totalPts != 0.00){
                                                                        
                                                                                $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_chips"]').text('-'+totalPts);

                                                                                 if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                                                   $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]').html(showCardsInScoreboard(player.id, roomIdCookie, sessionKeyCookie));

                                                                                }

                                                                            }else{
                                                                                
                                                                                $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_chips"]').text('');
                                                                            }

                                                                            
                                                                          


                                                                        }
                                                                        
                                                                     }); 

                                                                     } 


                                                                  }      


                                                                }); 



                                                        }

                                                        
                                                         setTimeout(function(){



                                                           if(dataReceived.status == "drop"){
                                                                var drop = true;
                                                           }else{
                                                             var drop = false;
                                                           } 

                                                           var signal13 = {type: 'get-scoreboard-melder', message: 'asking the melder to get scoreboard', myStatus: "lost", gameStatusTrick: true, drop: drop};

                                                              connection.send(JSON.stringify(signal13));   





                                                            console.log("Now it's your turn 1!!"); 
                                                        }, 2000);

                                                        
                                                   
                                                             

                                            }, 3000);
                                                   

                                        }   } 
                                    
                                    });        


                                 // }     

                            }else if(status == "rightshow"){

                                console.log("opponent has melded in right way!!");               


                                /* check if a player's game is over  */

                                var ajxData852 = { 'action': 'get-my-melded-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};


                                         $.ajax({
                                                type: 'POST',
                                                data: ajxData852,
                                                cache: false,
                                                url: 'ajax/getMyMeldedStatus.php',
                                                success: function(status){

                                                // alert("hit my status");

                                                console.log("my status jeta ikhlam,,..  .. " , status);

                                                if($.trim(status) != "Y"){


                                                    meldingProcess = 1;
                                                    $('.show_your_card_sec').css({'display': 'block'});
                                                    $('.show_your_card_blog .playingCards .hand').html("");
                                                    $('.loading_container').css({'display':'none'});


                                                    $('#meld'+userId).css({'display': 'none'});
                                                    cardPull = 1;
                                                    cardDiscard = 1;
                                                    cardMelded = 1;

                                                    $('.discard button').attr('disabled', true);


                                     

                                                 if(group1.length == 0 && group2.length == 0 && group3.length == 0 && group4.length == 0 && group5.length == 0 && group6.length == 0){


                                                    


                                                     $('#meldAll'+userId).css({'display': 'block'});
                                                     


                                                     

                                                 }else{


                                                     for(var i = 1; i < 7; i++){
                                                        if( eval('group'+i).length > 1){
                                                            console.log("group"+i+ "  not empty");
                                                            


                                                            $('.meld_group_btn button[data-button='+i+']').show();
                                                                

                                                        }
                                                    }


                                                 }   


                                            }else{


                                                /* check if all other players have melded then show scoreboard */

                                                // wrongValidationDisplayProcess2

                                                  var intervalCheck = setInterval(function(){

                                            

                                                     var ajxData703 = {'action': 'check-player-melded', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                                                      $.ajax({
                                                        type: 'POST',
                                                        data: ajxData703,
                                                        cache: false,
                                                        url: 'ajax/checkPlayerMelded.php',
                                                        success: function(count){

                                                            console.log("count melded ", count);
                                                            
                                                           if(parseInt(playersPlaying.length) - parseInt(count) != 1){
                                                               console.log("everyone has not melded yet!");

                                                            }else if(parseInt(playersPlaying.length) - parseInt(count) == 1 ){
                                                                clearInterval(intervalCheck);

                                                                 $('.result_sec').css({'display': 'block'});
                                                                 $('.loading_container').css({'display':'none'});

                                                                wrongValidationDisplayProcess2ForceCall(roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie);
                                                                
                                                            }
                                                            
                                                         } });       


                                                }, 5000);


                                            } } });    
                                 
                                    }

                    } } });                



              }else if (dataReceived.type == "get-scoreboard-wrongshow-gamegoing"){

                testingFlag++;
                var wrongMelder = dataReceived.melder;

                if(wrongMelder == userId && testingFlag == 1){


                    $('.drop').hide();

                   

                    /* Signal for wrongmelder 6 players game */

                        playersPlayingNextRound = dataReceived.playersPlayingNextRound;
                        playersPlayingTemp = dataReceived.playersPlayingTemp;  
                        var nextPlayer = dataReceived.nextPlayer;
                        var drop = dataReceived.drop;

                         // alert("players playing temp ", playersPlayingTemp);

                         var ajxData20 = {'action': 'get-player-name', player: parseInt(nextPlayer)};

                          $.ajax({
                              type: 'POST',
                              url: 'ajax/getPlayerName.php',
                              cache: false,
                              data: ajxData20,
                              success: function(theName){
                                  $('.game_message').html('<p>' + theName + ' will play</p>').show();
                                
                          } }); 


                         /*  update db current player */

                        var ajxData270 = {'action': 'current-player', roomId: roomIdCookie, 
                            player: nextPlayer, sessionKey: sessionKeyCookie };

                             $.ajax({

                                type: 'POST',
                                url: 'ajax/updateCurrentPlayer.php',
                                cache: false,
                                data: ajxData270,
                                success: function(result){ 
                                    if($.trim(result) == "ok"){
                                        console.log("current player updated");                                                    
                                    

                                    }
                                   
                                }
                             });    

                        /* update melded count and player melded */

                           var ajxData703 = {'action': 'update-melded-count-player-melded', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                          $.ajax({
                            type: 'POST',
                            data: ajxData703,
                            cache: false,
                            url: 'ajax/updateMeldedCountAndPlayerMelded.php',
                            success: function(result){



                            } });    



                        console.log("playersPlayingTemp now 4545454545 ", playersPlayingTemp);
                        console.log("playersPlayingTemp next round 445454545454 ", playersPlayingNextRound);


                        /* update player turn */

                         var ajxData703 = {'action': 'update-player-turn', roomId: roomIdCookie, nextPlayer: nextPlayer, sessionKey: sessionKeyCookie};

                          $.ajax({
                            type: 'POST',
                            data: ajxData703,
                            cache: false,
                            url: 'ajax/updatePlayerTurn.php',
                            success: function(result){


                                if($.trim(result) == "ok"){

                                    console.log("Player Turn Updated!");

                                    setTimeout(function(){

                                        $('.result_sec .result_bottom').text("");

                                        $('.result_sec').css({'display': 'none'});
                                        $('.result_sec tbody[id="score_reports"] tr').remove();


                                        /*  Hide show section */

                                        $('.show_your_card_sec').css({'display': 'none'});
                                      
                                        if(drop == false){
                                            $('.show_your_card_sec .show_your_card_blog .playingCards .hand').html("");
                                            $('.show_your_card_sec .show_your_card_blog a[class="removeMeldedGroup"]').remove();
                                            $('.group_blog5').remove();
                                            $('.current-player[data-user="'+userId+'"] .toss .playingCards').html('<div class="card card_2 back board_center_back"></div>');
                                        }else{

                                            $('.player_card_me .hand li a').removeClass('handCard');
                                            $('.group_blog5 .playingCards .hand li a').removeClass('handCard');

                                             $('.player_card_me .hand li a').addClass('showFoldedCard');
                                            $('.group_blog .playingCards .hand li a').addClass('showFoldedCard');

                                            $('.player_card_me').hide();
                                            $('.group_blog5').hide();
                                          
                                            $('.current-player[data-user="'+userId+'"] .toss .playingCards').html('<div class="card card_2 back board_center_back showMyHand"></div>');



                                        }    


                                         

                                        /* show folded card */

                                        

                                        // $('#meldAll'+userId+' button').hide();

                                        $('#sort'+userId).hide();
                                        $('.discard button').hide();

                                       

                                        testingFlag = 0;

                                        /* send signal to update testing flag */

                                        var signalTestingFlagUpdate = {"type":"testing-flag-update"};
                                        connection.send(JSON.stringify(signalTestingFlagUpdate));

                                        $('.cardDeckSelect').removeClass('clickable').addClass('noSelect');


                                    }, 2000);

                                }


                            }
                        });           
                            
                         

                }

              }else if (dataReceived.type == "testing-flag-update"){

                testingFlag = 0;

              }else if(dataReceived.type == "get-scoreboard-melder"){


                    //alert("get scoreboard melder");
                   
                   
                    var firstMelder = dataReceived.melder;

                    console.log(dataReceived.message);

                            

                            /*  Check if only the first melder gets the call */

                             var ajxData703 = {'action': 'get-first-melder', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                                  $.ajax({
                                    type: 'POST',
                                    data: ajxData703,
                                    cache: false,
                                    url: 'ajax/getFirstMelder.php',
                                    success: function(player){

                                        console.log("Winner ", player);
                                        console.log("my Id ", userId);

                                        if( parseInt(player) == parseInt(userId) || parseInt(player) == 0){

                                            // alert("hitting 0");

                                             
                                            var myStatus = dataReceived.myStatus;      
                                            var gameStatusTrick = dataReceived.gameStatusTrick;
                                            var drop = dataReceived.drop;
                                           

                                          



                                            console.log("YOOOO");
                                          
                                            console.log(myStatus);
                                            console.log(gameStatusTrick);
                                           
                                             /* Check if everyone has melded */

                                var ajxData703 = {'action': 'get-melded-count', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                                  $.ajax({
                                    type: 'POST',
                                    data: ajxData703,
                                    cache: false,
                                    url: 'ajax/getMeldedCount.php',
                                    success: function(count){

                                        console.log("playersPlayingTemp checking ", playersPlaying);

                                        console.log("count ", count);

                                       if(count == playersPlaying.length){

                                             // alert("hitting 1");

                                            console.log("count match proceed");


                                  if(gamePlayersCookie == "2"){

                                     for(var i = 0; i < playersPlaying.length; i++){

                                        console.log('doing for ', playersPlaying[i]);

                                         var ajxData703 = {'action': 'get-players', player: playersPlaying[i]};

                                          $.ajax({
                                            type: 'POST',
                                            data: ajxData703,
                                            dataType: 'json',
                                            cache: false,
                                            url: 'ajax/getAllPlayers.php',
                                            success: function(player){


                                              if(gameTypeCookie != "score"){  



                                            if(player.id != userId){
                                        
                                                console.log(player.id + ' ' +player.name);

                                                

                                                // $('#scoreBoard tbody').append(tblRow);
                                               if(myStatus == "lost"){
                                                  var status = "<img src='images/winner.png'>";
                                               }else if(myStatus == "won" && drop == false){
                                                 var status = "Lost";
                                               }else if(myStatus == "won" && drop == true){
                                                 var status = "Drop";
                                               } 
                                               

                                                var ajxData704 = {'action': 'get-scoreboard', roomId: roomIdCookie, player: player.id, sessionKey: sessionKeyCookie};

                                                  $.ajax({
                                                    type: 'POST',
                                                    data: ajxData704,
                                                    dataType: 'json',
                                                    cache: false,
                                                    url: 'ajax/getScoreBoard.php',
                                                    success: function(results){

                                                        var points = results.points;
                                                        var totalPts = results.total_points;

                                                         console.log(points);
                                                         console.log(totalPts);

                                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="result"]').html(status);


                                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="score"]').text(Math.round(points));
                                                                
                                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_score"]').text(Math.round(totalPts));

                                                            if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                                   $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]').html(showCardsInScoreboard(player.id, roomIdCookie, sessionKeyCookie));

                                                            }


                                                         }
                                                         
                                                   })
                                                   
                                                 }


                                             }else{



                                                if(player.id != userId){
                                        
                                                console.log(player.id + ' ' +player.name);

                                                

                                                // $('#scoreBoard tbody').append(tblRow);
                                               if(myStatus == "lost"){
                                                  var status = "<img src='images/winner.png'>";
                                               }else if(myStatus == "won" && drop == false){
                                                 var status = "Lost";
                                               }else if(myStatus == "won" && drop == true){
                                                 var status = "Drop";
                                               }  
                                               

                                                var ajxData704 = {'action': 'get-scoreboard', roomId: roomIdCookie, player: player.id, sessionKey: sessionKeyCookie};

                                                  $.ajax({
                                                    type: 'POST',
                                                    data: ajxData704,
                                                    dataType: 'json',
                                                    cache: false,
                                                    url: 'ajax/getScoreBoard.php',
                                                    success: function(results){

                                                        var points = results.points;
                                                        var totalPts = results.total_points;

                                                         console.log(points);
                                                         console.log(totalPts);

                                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="result"]').html(status);


                                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="count"]').text(Math.round(points));
                                                                

                                                            if(totalPts != 0.00){
                                                                
                                                                $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_chips"]').text('-'+totalPts);

                                                                    if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                                        $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]').html(showCardsInScoreboard(player.id, roomIdCookie, sessionKeyCookie));

                                                                    }
                                                            }else{
                                                                $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_chips"]').text("");
                                                            }


                                                        

                                                         }
                                                         
                                                   })
                                                   
                                                 }





                                             }



                                                 
                                                } 

                                             })
                                                
                                            }

                                           }                 



                                            /*  Start Another Round */

                                                       testingFlag++;

                                                       console.log("testing flag ", testingFlag);

                                                        if(testingFlag == 1){

                                                                    
                                                              setTimeout(function(){  

                                                            // console.log("hitting 3");
                                                           
                                                           // if(gameStatusFlag == "cont"){

                                                            console.log("CONTTTTTTTTTTTTTTTTTT------------------");
                                                            console.log("hit 4");

                                                            // console.log("hitting 4");
 

                                                                /* send signal to others to start next game  */

                                                                 var signal0011 = {type : 'start-next-round', message: 'request to start next round'};
                                                                
                                                                 connection.send(JSON.stringify(signal0011));   




                                                                nextGameStartHandler(function(){

                                                                     $('.result_sec .result_bottom').text("");

                
                                                                    console.log("game handler callback........");

                                                                    


                                                                    /*  Hide show section */

                                                                    $('.show_your_card_sec').css({'display': 'none'});
                                                                    $('.show_your_card_sec .show_your_card_blog .playingCards .hand').html("");

                                                                    $('.show_your_card_sec .show_your_card_blog a[class="removeMeldedGroup"]').remove();



                                                                    /** HELOOOOOOOOO **/


                                                                    /*  Hide card groups */

                                                                    $('.group_blog5').remove();

                                                                    $('.card-throw .playingCards').html("");
                                                                    $('.joker-assign .playingCards').html("");

                                                                    $('.player_card_me .hand').html("");

                                                                $('.current-player .playingCards .deck').html("");

                                                                    cardsInHand.length = 0;
                                                                    meldCardEvaluator1.length = 0;
                                                                    meldCardEvaluator2.length = 0;
                                                                    meldCardEvaluator3.length = 0;
                                                                    meldCardEvaluator4.length = 0;

                                                                    pureSequence = 0;
                                                                    impureSequence = 0;
                                                                    matchingCards = 0;

                                                                    cardsSelected,length = 0;
                                                                    cardPull = 0;
                                                                    cardDiscard = 0;
                                                                    cardMelded = 0;

                                                                    meldingProcess = 0;
                                                                    group1.length = 0;
                                                                    group2.length = 0;
                                                                    group3.length = 0;
                                                                    group4.length = 0;
                                                                    group5.length = 0;
                                                                    group6.length = 0;

                                                                    meldedGroup1.length = 0;
                                                                    meldedGroup2.length = 0;
                                                                    meldedGroup3.length = 0;
                                                                    meldedGroup4.length = 0;
                                                                    meldedGroup5.length = 0;
                                                                    meldedGroup6.length = 0;

                                                                    meldCardArr1.length = 0;
                                                                    meldCardArr2.length = 0;
                                                                    meldCardArr3.length = 0;
                                                                    meldCardArr4.length = 0;
                                                                   
                                                                    victimGroups.length = 0;

                                                                    cardGroupSelected = '';

                                                                    $('.discard button').attr('disabled', true);
                                                                    $('.sort button').attr('disabled', false);


                                                                    // $('.meldAll button').hide();

                                                              

                                                                  if(gameTypeCookie != "score"){        

                                                                    dealNextRoundCardsMelder(roomIdCookie, gamePlayersCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, function(){

                                                                            console.log("asds");

                                                                            testingFlag = 0;
                                                                            nextGameCounter = 5;

                                                                        var signalTestingFlagUpdate = {"type":"testing-flag-update"};
                                                                        connection.send(JSON.stringify(signalTestingFlagUpdate));

                                                                        });

                                                                 }else{

                                                                    pointsRummyFinalScoreboardMelder(roomIdCookie, gamePlayersCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, function(){

                                                                            console.log("asds");

                                                                            testingFlag = 0;
                                                                            nextGameCounter = 5;

                                                                        var signalTestingFlagUpdate = {"type":"testing-flag-update"};
                                                                        //connection.send(JSON.stringify(signalTestingFlagUpdate));


                                                                    });    

                                                                 }


                                                            });

                                                        
     

                                                    }, 1000); 
      


                                                        }
    


                                       } 

                                } });


                                        }else{ /* check if winner */
                                            console.log("not the winner");
                                        }

                                    }  /* first ajax success */
                             }); /* first ajax */          

                         
                   


                        }else if(dataReceived.type == "get-scoreboard-melder-six-player"){

                            // alert("get scoreboard melder 6 players");

                            var firstMelder = dataReceived.melder;
                               
                            

                             testingFlag++;

                             console.log(" TESTING FLAG ", testingFlag); 
                           

                            if(firstMelder == userId && testingFlag == 1){

                                checkIfAllMelded(function(){

                                    /* Update all the melded groups in db */

                                        var ajxData81200 = { 'action': 'update-all-groups', roomId: roomIdCookie, player: userId, meldedGroup1: meldedGroup1, meldedGroup2: meldedGroup2, meldedGroup3: meldedGroup3, meldedGroup4: meldedGroup4, sessionKey: sessionKeyCookie};


                                        $.ajax({
                                            type: 'POST',
                                            data: ajxData81200,
                                            cache: false,
                                            url: 'ajax/updateMeldedGroups.php',
                                            success: function(result){
                                               if($.trim(result) == "ok"){
                                                    console.log("all groups updated");
                                               }
                                            }
                                        });


                                    }); 
                                
                                console.log("CAUGHT!!!");

                                var gameStatus = dataReceived.gameStatus; 
                                var myStatus = dataReceived.myStatus;      
                                var gameStatusTrick = dataReceived.gameStatusTrick;
                                var playersPlayingTempGet = dataReceived.playersPlayingTemp;

                                gameStatusArray.push(gameStatus);

                                playersPlayingTemp.length = 0;
                                playersPlayingTemp = playersPlayingTempGet.slice();



                                console.log("YOOOO");
                                console.log(gameStatus);
                                console.log(myStatus);
                                console.log(gameStatusTrick);
                                console.log(playersPlayingTempGet);

                                 /* Check if everyone has melded */

                                var ajxData703 = {'action': 'get-melded-count', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                                  $.ajax({
                                    type: 'POST',
                                    data: ajxData703,
                                    cache: false,
                                    url: 'ajax/getMeldedCount.php',
                                    success: function(count){

                                        console.log("playersPlayingTemp checking", playersPlaying);

                                        console.log("Count of melding ", count);
                                       if(count == playersPlaying.length){

                                            

                                            console.log("count match proceed");


                                                 for(var i = 0; i < playersPlaying.length; i++){

                                                    console.log('doing for ', playersPlaying[i]);

                                                     var ajxData703 = {'action': 'get-players', player: playersPlaying[i]};

                                                      $.ajax({
                                                        type: 'POST',
                                                        data: ajxData703,
                                                        dataType: 'json',
                                                        cache: false,
                                                        url: 'ajax/getAllPlayers.php',
                                                        success: function(player){

                                                        var statusFlag;

                                                         if(gameTypeCookie != "score"){  

                                                                if(player.id != userId){
                                                            
                                                                    console.log(player.id + ' ' +player.name);

                                                                    var ajxData85201 = { 'action': 'check-wrong-melder', roomId: roomIdCookie, player: player.id, sessionKey: sessionKeyCookie};
                                     
                                                                             $.ajax({
                                                                                 type: 'POST',
                                                                                 data: ajxData85201,
                                                                              
                                                                                 cache: false,
                                                                                 url: 'ajax/checkWrongMelder.php',
                                                                                 success: function(flag){


                                                                                    console.log(" report check wrong melder for ", player.id ," - flag ", flag);
                                                                               
                                                                                   

                                                                                   
                                                                                    if(flag == 1){
                                                                                        statusFlag = "Lost";
                                                                                    }else{
                                                                                        statusFlag = "<img src='images/winner.png'>";
                                                                                    }

                                                                                      $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="result"]').html(statusFlag);

                                                                                      

                                                                            } })     

                                                                    

                                                                    // $('#scoreBoard tbody').append(tblRow);
                                                                   if(myStatus == "lost"){
                                                                      statusFlag = "<img src='images/winner.png'>";
                                                                   }else if(myStatus == "won"){
                                                                      statusFlag = "Lost";
                                                                   } 
                                                                   

                                                                    var ajxData704 = {'action': 'get-scoreboard', roomId: roomIdCookie, player: player.id, sessionKey: sessionKeyCookie};

                                                                      $.ajax({
                                                                        type: 'POST',
                                                                        data: ajxData704,
                                                                        dataType: 'json',
                                                                        cache: false,
                                                                        url: 'ajax/getScoreBoard.php',
                                                                        success: function(results){

                                                                            var points = results.points;
                                                                            var totalPts = results.total_points;

                                                                             console.log(points);
                                                                             console.log(totalPts);

                                                                                $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="result"]').html(statusFlag);


                                                                                $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="score"]').text(Math.round(points));
                                                                                    
                                                                                $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_score"]').text(Math.round(totalPts));

                                                                                if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                                                $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]').html(showCardsInScoreboard(player.id, roomIdCookie, sessionKeyCookie));

                                                                                }


                                                                             }
                                                                             
                                                                       })
                                                                       
                                                                     }

                                                           }else{


                                                                 if(player.id != userId){
                                                            
                                                                    console.log(player.id + ' ' +player.name);

                                                                    var ajxData85201 = { 'action': 'check-wrong-melder', roomId: roomIdCookie, player: player.id, sessionKey: sessionKeyCookie};
                                     
                                                                             $.ajax({
                                                                                 type: 'POST',
                                                                                 data: ajxData85201,
                                                                              
                                                                                 cache: false,
                                                                                 url: 'ajax/checkWrongMelder.php',
                                                                                 success: function(flag){


                                                                                    console.log(" report check wrong melder for ", player.id ," - flag ", flag);
                                                                               
                                                                                   

                                                                                   
                                                                                    if(flag == 1){
                                                                                        statusFlag = "Lost";
                                                                                    }else{
                                                                                        statusFlag = "<img src='images/winner.png'>";
                                                                                    }

                                                                                      $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="result"]').html(statusFlag);

                                                                                      

                                                                            } })     

                                                                    

                                                                    // $('#scoreBoard tbody').append(tblRow);
                                                                   if(myStatus == "lost"){
                                                                      statusFlag = "<img src='images/winner.png'>";
                                                                   }else if(myStatus == "won"){
                                                                      statusFlag = "Lost";
                                                                   } 
                                                                   

                                                                    var ajxData704 = {'action': 'get-scoreboard', roomId: roomIdCookie, player: player.id, sessionKey: sessionKeyCookie};

                                                                      $.ajax({
                                                                        type: 'POST',
                                                                        data: ajxData704,
                                                                        dataType: 'json',
                                                                        cache: false,
                                                                        url: 'ajax/getScoreBoard.php',
                                                                        success: function(results){

                                                                            var points = results.points;
                                                                            var totalPts = results.total_points;

                                                                             console.log(points);
                                                                             console.log(totalPts);

                                                                                $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="result"]').html(statusFlag);


                                                                                $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="count"]').text(Math.round(points));

                                                                                if(totalPts != 0.00){
                                                                
                                                                                  $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_chips"]').text('-'+totalPts);

                                                                                   if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                                                    $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]').html(showCardsInScoreboard(player.id, roomIdCookie, sessionKeyCookie));

                                                                                    }


                                                                              }else{
                                                                                  $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_chips"]').text("");
                                                                              }

                                                                             
                                                                                    

                                                                             }
                                                                             
                                                                       })
                                                                       
                                                                     }





                                                           }  


                                                             
                                                            } 

                                                         })
                                                            
                                                        }                

                                        
                                                     

                                                setTimeout(function(){

                                                          
                                                           
                                                    // if(gameStatusFlag == "cont"){

                                                    console.log("CONTTTTTTTTTTTTTTTTTT------------------");
                                                    console.log("hit 4");


                                                     var signal0011 = {type : 'start-next-round', message: 'request to start next round'};
                                                    
                                                     connection.send(JSON.stringify(signal0011));   


                                                                

                                                                nextGameStartHandler(function(){

                                                                   
                                                                    console.log("game handler callback........");


                                                                    /*  Hide show section */

                                                                    $('.show_your_card_sec').css({'display': 'none'});
                                                                    $('.show_your_card_sec .show_your_card_blog .playingCards .hand').html("");

                                                                    $('.show_your_card_sec .show_your_card_blog a[class="removeMeldedGroup"]').remove();



                                                                    /** HELOOOOOOOOO **/


                                                                    /*  Hide card groups */

                                                                    $('.group_blog5').remove();

                                                                    $('.card-throw .playingCards').html("");
                                                                    $('.joker-assign .playingCards').html("");

                                                                    $('.player_card_me .hand').html("");

                                                                    $('.current-player .playingCards .deck').html("");

                                                                    
                                                                


                                                                    cardsInHand.length = 0;
                                                                    meldCardEvaluator1.length = 0;
                                                                    meldCardEvaluator2.length = 0;
                                                                    meldCardEvaluator3.length = 0;
                                                                    meldCardEvaluator4.length = 0;

                                                                    pureSequence = 0;
                                                                    impureSequence = 0;
                                                                    matchingCards = 0;

                                                                    cardsSelected,length = 0;
                                                                    cardPull = 0;
                                                                    cardDiscard = 0;
                                                                    cardMelded = 0;

                                                                    meldingProcess = 0;
                                                                    group1.length = 0;
                                                                    group2.length = 0;
                                                                    group3.length = 0;
                                                                    group4.length = 0;
                                                                    group5.length = 0;
                                                                    group6.length = 0;

                                                                    meldedGroup1.length = 0;
                                                                    meldedGroup2.length = 0;
                                                                    meldedGroup3.length = 0;
                                                                    meldedGroup4.length = 0;
                                                                    meldedGroup5.length = 0;
                                                                    meldedGroup6.length = 0;

                                                                    meldCardArr1.length = 0;
                                                                    meldCardArr2.length = 0;
                                                                    meldCardArr3.length = 0;
                                                                    meldCardArr4.length = 0;
                                                                    
                                                                    victimGroups.length = 0;

                                                                    cardGroupSelected = '';

                                                                     playersPlayingNextRound.length = 0;

                                                                    $('.discard button').attr('disabled', true);
                                                                    $('.sort button').attr('disabled', false);

                                                                    // $('.meldAll button').hide();


                                                     

                                                                    if(gameTypeCookie != "score"){        

                                                                        dealNextRoundCardsMelder(roomIdCookie, gamePlayersCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, function(){

                                                                                console.log("asds");

                                                                                testingFlag = 0;
                                                                                nextGameCounter = 5;

                                                                            var signalTestingFlagUpdate = {"type":"testing-flag-update"};
                                                                            connection.send(JSON.stringify(signalTestingFlagUpdate));

                                                                            });

                                                                 }else{

                                                                        pointsRummyFinalScoreboardMelder(roomIdCookie, gamePlayersCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, function(){

                                                                                console.log("asds");

                                                                                testingFlag = 0;
                                                                                nextGameCounter = 5;

                                                                            var signalTestingFlagUpdate = {"type":"testing-flag-update"};
                                                                           // connection.send(JSON.stringify(signalTestingFlagUpdate));


                                                                        });    

                                                                 }


                                                            });




                                                    }, 1000); 

                                                    

                                                } 

                                } });



                            }

                          


                        }else if(dataReceived.type == "game-over-signal"){

                            var playerWon = dataReceived.playerWon;

                              if(parseInt(playerWon) == parseInt(userId)){
                                $('.loading_container').css({'display':'block'});
                                $('.loading_container .popup .popup_cont').text("You have won the game!");

                                 setTimeout(function(){
                                    $('.loading_container').hide();
                                    $('.loading_container .popup .popup_cont').text();


                                    $('.popup_rejoin').show();
                                    $('.popup_rejoin .popup_with_button_cont p').text("Do you want to rejoin?");
                                    
                                 }, 4000);

                                  var ajxData704407 = {'action': 'update-real-wallet', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                $.ajax({
                                    type: 'POST',
                                    data: ajxData704407,
                                    cache: false,
                                    url: 'ajax/updateRealWallet.php',
                                    success: function(results){
                                        
                                         // alert("Total chips coming.......................");
                                         console.log(results);   
                                    } }); 

                            }else{
                                $('.loading_container').css({'display':'block'});
                                $('.loading_container .popup .popup_cont').text("You have lost the game!");

                                 setTimeout(function(){
                                    $('.loading_container').hide();
                                    $('.loading_container .popup .popup_cont').text();


                                    $('.popup_rejoin').show();
                                    $('.popup_rejoin .popup_with_button_cont p').text("Do you want to rejoin?");
                                    
                                 }, 4000);

                                var ajxData704 = {'action': 'update-my-status-2', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                  $.ajax({
                                    type: 'POST',
                                    data: ajxData704,
                                    cache: false,
                                    url: 'ajax/updateMyStatus2.php',
                                    success: function(results){
                                        console.log(results);
                                        if(results){
                                           console.log("status updated");
                                           // alert("status updated");

                                        }
                                    } });    

                                setTimeout(function(){
                                               
                                    // $('.result_sec').remove();

                                    /* disconnect user */

                                    var ajxData20555 = {'action': 'get-connection-id', roomId: roomIdCookie, playerId: userId, sessionKey: sessionKeyCookie};

                                    $.ajax({

                                        type: 'POST',
                                        url: 'ajax/getConnectionId.php',
                                        cache: false,
                                        data: ajxData20555,
                                        success: function(connectionId){ 
                                        
                                            console.log(connectionId);
                                            // // alert("connection removed");
                                            // connection.remove(connectionId);
                                            connection.close();
                                            // location.reload();


                                         } });    





                                }, 10000 );

                             
                            }


                        }else if(dataReceived.type == "start-next-round"){

                            var ajxData852 = { 'action': 'get-my-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};


                             $.ajax({
                                    type: 'POST',
                                    data: ajxData852,
                                    cache: false,
                                    url: 'ajax/getMyStatus.php',
                                    success: function(status){

                                    // alert("hit my status");

                                    console.log("my status jeta ikhlam,,..  .. " , status);

                                    if($.trim(status) != "out"){

                                setTimeout(function(){

                  
                                    nextGameStartHandler(function(){

                                        $('.current-player .toss .playingCards').html("");

                                        
                                        /*  Hide show section */

                                        $('.show_your_card_sec').css({'display': 'none'});
                                      
                                        $('.show_your_card_sec .show_your_card_blog .playingCards .hand').html("");
                                        $('.show_your_card_sec .show_your_card_blog a[class="removeMeldedGroup"]').remove();

                                        /** HELOOOOOOOOO **/


                                        /*  Hide card groups */

                                        $('.group_blog5').remove();

                                        $('.card-throw .playingCards').html("");
                                        $('.joker-assign .playingCards').html("");

                                        $('.player_card_me .hand').html("");


                                         $('.current-player .playingCards .deck').html("");

                                       

                                        // playersPlayingTemp.length = 0;

                                        cardsInHand.length = 0;
                                        meldCardEvaluator1.length = 0;
                                        meldCardEvaluator2.length = 0;
                                        meldCardEvaluator3.length = 0;
                                        meldCardEvaluator4.length = 0;

                                        pureSequence = 0;
                                        impureSequence = 0;
                                        matchingCards = 0;

                                        cardsSelected,length = 0;
                                        cardPull = 0;
                                        cardDiscard = 0;
                                        cardMelded = 0;

                                        meldingProcess = 0;
                                        group1.length = 0;
                                        group2.length = 0;
                                        group3.length = 0;
                                        group4.length = 0;
                                        group5.length = 0;
                                        group6.length = 0;

                                        meldedGroup1.length = 0;
                                        meldedGroup2.length = 0;
                                        meldedGroup3.length = 0;
                                        meldedGroup4.length = 0;
                                        meldedGroup5.length = 0;
                                        meldedGroup6.length = 0;

                                        meldCardArr1.length = 0;
                                        meldCardArr2.length = 0;
                                        meldCardArr3.length = 0;
                                        meldCardArr4.length = 0;
                                       
                                        victimGroups.length = 0;

                                        playersPlayingNextRound.length = 0;

                                        cardGroupSelected = '';

                                        $('.discard button').attr('disabled', true);
                                        $('.sort button').attr('disabled', false);

                                        $('.discard button').show();
                                        $('.sort button').show();

                                        $('.discard').show();
                                        $('.sort').show();

                                        // $('.meldAll button').hide();




                                    });

                                  }, 1000); 

                            } 
                            

                          } });    




                        }else if(dataReceived.type == "next-round-process"){

                            /* get my status */

                              var ajxData852 = { 'action': 'get-my-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};


                             $.ajax({
                                    type: 'POST',
                                    data: ajxData852,
                                    cache: false,
                                    url: 'ajax/getMyStatus.php',
                                    success: function(status){

                                // alert("hit my status");

                                console.log("my status jeta ikhlam,,..  .. " , status);

                                if($.trim(status) != "out"){

                                                      
                                    $('#validateCards'+userId).attr('disabled', false);

                                    console.log(dataReceived.message);

                                    // alert("next round process!!");


                                    var tossWinner = dataReceived.playerToPlay;
                                    var isLost = dataReceived.isLost;
                                    var newPlayersPlaying = dataReceived.playersPlaying;

                                    playersPlaying.length = 0;
                                    playersPlayingTemp.length = 0;

                                    playersPlaying = newPlayersPlaying.slice();
                                    playersPlayingTemp = newPlayersPlaying.slice();


                                     if(parseInt(userId) == parseInt(tossWinner)){
                                            $('.game_message').html('<p>Your turn</p>').show();
                                      }else{

                                        /* get-player-name */

                                        var ajxData20 = {'action': 'get-player-name', player: parseInt(tossWinner)};

                                          $.ajax({
                                              type: 'POST',
                                              url: 'ajax/getPlayerName.php',
                                              cache: false,
                                              data: ajxData20,
                                              success: function(theName){
                                                $('.game_message').html('<p>' + theName + ' will play</p>').show();
                                                
                                              } }); 

                                        }
                               


                                      /* Update player won and wrong melders  */

                                       var ajxData7774 = {'action': 'update-wrong-melders', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                                         $.ajax({
                                            type: 'POST',
                                            data: ajxData7774,
                                            cache: false,
                                            url: 'ajax/updateWrongMeldersNextRound.php',
                                            success: function(result){
                                                console.log("wrong melders updated!");
                                            } });    


                                    if(gameTypeCookie != "score"){     

                                        dealNextRoundCardsOthers(tossWinner, roomIdCookie, gamePlayersCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, function(){
                                             console.log("blah");
                                             gameStatusFlag = '';
                                             gameStatusArray.length = 0;
                                             nextGameCounter = 5;
                                        });

                                    }

                           
                        } } });




                        }else if(dataReceived.type == "points-rummy-fso"){

                            

                             console.log(dataReceived.message);

                             var playersLostArr1 = dataReceived.playersLostArr.slice();
                             var winningAmount = dataReceived.winningAmount;
                             var winner = dataReceived.winner;


                             for(var i = 0; i < playersLostArr1.length; i++){
                                

                                     var ajxData703 = {'action': 'get-players', player: playersLostArr1[i]};

                                      $.ajax({
                                        type: 'POST',
                                        data: ajxData703,
                                        dataType: 'json',
                                        cache: false,
                                        url: 'ajax/getAllPlayers.php',
                                        success: function(player){

                                            console.log(player.id + ' ' + player.name);

                                            var ajxData704 = {'action': 'get-scoreboard', roomId: roomIdCookie, player: player.id, sessionKey: sessionKeyCookie};

                                              $.ajax({
                                                type: 'POST',
                                                data: ajxData704,
                                                dataType: 'json',
                                                cache: false,
                                                url: 'ajax/getScoreBoard.php',
                                                success: function(results){

                                                    

                                                    console.log("Player id checking ", player.id);

                                                    var points = results.points;
                                                    var totalPts = results.total_points;
                                                    var playerWon = results.player_won;


                                                    $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="result"]').text('Lost');

                                                     $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="count"]').text(Math.round(points));
                                        
                                                    $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_chips"]').text('-'+totalPts);

                                                    if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                        $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]').html(showCardsInScoreboard(player.id, roomIdCookie, sessionKeyCookie));

                                                    }
                                           

                                                } })




                                        } });



                                    }




                                    $('.result_sec tbody[id="score_reports"] tr[data-user="'+winner+'"] td[id="result"]').html("<img src='images/winner.png'>");

                                     $('.result_sec tbody[id="score_reports"] tr[data-user="'+winner+'"] td[id="count"]').text(0);
                        
                                    $('.result_sec tbody[id="score_reports"] tr[data-user="'+winner+'"] td[id="total_chips"]').text('+'+winningAmount.toFixed(2));

                                    if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+winner+'"] td[id="cards"]' .playingCards).length == 0 ){

                                       $('.result_sec tbody[id="score_reports"] tr[data-user="'+winner+'"] td[id="cards"]').html(showCardsInScoreboard(winner, roomIdCookie, sessionKeyCookie));

                                    }


                                     if(parseInt(userId) === parseInt(winner)){
                                         $('.loading_container').css({'display':'block'});
                                         $('.loading_container .popup .popup_cont').text("You have won the game!");
                                      }else{
                                        $('.loading_container').css({'display':'block'});
                                        $('.loading_container .popup .popup_cont').text("You have lost the game!");
                                      }



                        }else if(dataReceived.type == "update-players-playing"){
                            
                            var getplayersPlayingTemp = dataReceived.playersPlayingTemp;




                           

                             var ajxData703 = {'action': 'get-first-melder', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                                  $.ajax({
                                    type: 'POST',
                                    data: ajxData703,
                                    cache: false,
                                    url: 'ajax/getFirstMelder.php',
                                    success: function(player){

                                        console.log(dataReceived.message);

                                        if(parseInt(player) != parseInt(userId)){

                                            // alert("blah");

                                             playersPlayingTemp.length = 0;

                                             for(var i = 0; i < getplayersPlayingTemp.length; i++){
                                                playersPlayingTemp.push(getplayersPlayingTemp[i]);
                                             }

                                             console.log("new players playing ", playersPlayingTemp);

                                        }
                                        
                                    } });        




                        }else if(dataReceived.type == "update-players-playing-melder"){
                            
                            var getplayersPlayingTemp = dataReceived.playersPlayingTemp;

                            playersPlayingTemp.length = 0;

                             for(var i = 0; i < getplayersPlayingTemp.length; i++){
                                playersPlayingTemp.push(getplayersPlayingTemp[i]);
                             }

                             console.log("new players playing ", playersPlayingTemp);

                                



                        }
                    };  



            
            /* card click */


         


            $('.group').delegate('.handCard', 'click', function(){

                var card;
                
                var rank = $(this).attr('data-rank');
                var suit = $(this).attr('data-suit');

               

                /* joker card */

                if(!suit){
                    card = "Joker";
                }else{
                     card = rank+'OF'+suit;
                }

                /* Find the group it belongs to */




                if( $(this).hasClass('activeCard') ){
                    $(this).removeClass('activeCard');
                    cardGroupSelected = '';

                    var index = cardsSelected.indexOf(card);

                    if (index > -1) {
                            cardsSelected.splice(index, 1);
                    }

                }else{
                     $(this).addClass('activeCard');
                      cardsSelected.push(card);
                      

                       
                      cardGroupSelected = $(this).closest('.group_blog5').attr('data-group');

                }

                /* discard button disabled or enabled */

                if(cardsSelected.length == 1){

                     if(cardPull == 1 && cardDiscard == 0){
                        $('#meld'+userId).css({'display': 'block'});
                      }

                     $('.group_btn').css({'display': 'none'});

                    /* Check if it's my turn. If my turn, then enable button, else not */
                    var roomIdCookie = $.cookie("room");
                    var sessionKeyCookie = $.trim($.cookie("sessionKey"));


                    var ajxData505 = {'action': 'get-player-turn', roomId: roomIdCookie, sessionKey: sessionKeyCookie};


                    $.ajax({
                        type: 'POST',
                         url: 'ajax/getCurrentPlayer.php',
                        cache: false,
                        data: ajxData505,
                        success: function(player){
                            console.log("AAAAAAAAAAAAAAAAAAAAAAAA " + player );
                             console.log("me: ", userId);
                            if( parseInt(player) == parseInt(userId) ){
                                if(cardPull == 1){
                                 $('.discard button').attr('disabled', false);
                                } 
                            }
                        }


                    });


                   
                }else{

                        $('#meld'+userId).css({'display': 'none'});
                        $('.discard button').attr('disabled', true);
         
                     
                      
                          if(cardsSelected.length != 0){

                            if(group1.length == 0 || group2.length == 0 || group3.length == 0 || group4.length == 0 || group5.length == 0 || group6.length == 0){

                                $('.group_btn').css({'display': 'block'});
                            }


                             
                         }
                }

                console.log(cardsSelected);
               

            });




              $('.hand').delegate('.handCard', 'click', function(){
                var rank = $(this).attr('data-rank');
                var suit = $(this).attr('data-suit');

                var card = rank+'OF'+suit;

                 if(!suit){
                    card = "Joker";
                }else{
                     card = rank+'OF'+suit;
                }


                if( $(this).hasClass('activeCard') ){
                    $(this).removeClass('activeCard');

                   

                    var index = cardsSelected.indexOf(card);

                    if (index > -1) {
                            cardsSelected.splice(index, 1);
                           
                          
                           
                    }

                }else{
                     $(this).addClass('activeCard');
                     
                        
                     
                     
                      cardsSelected.push(card);

                      // if(cardsSelected.length == 1){

                      // }

                }

                /* discard button disabled or enabled */

                if(cardsSelected.length == 1){

                    if(cardPull == 1 && cardDiscard == 0){
                        $('#meld'+userId).css({'display': 'block'});
                    }

                    /* Check if it's my turn. If my turn, then enable button, else not */
                    $('.group_btn').css({'display': 'none'});
                    var roomIdCookie = $.cookie("room");
                    var sessionKeyCookie = $.trim($.cookie("sessionKey"));

                    var ajxData505 = {'action': 'get-player-turn', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                    $.ajax({
                        type: 'POST',
                         url: 'ajax/getCurrentPlayer.php',
                        cache: false,
                        data: ajxData505,
                        success: function(player){
                            console.log("AAAAAAAAAAAAAAAAAAAAAAAA " + player );
                             console.log("me: ", userId);
                            if( parseInt(player) == parseInt(userId) ){
                                if(cardPull == 1){
                                 $('.discard button').attr('disabled', false);
                                } 
                            }
                        }


                    });


                   
                }else{
                        
                            
                        $('#meld'+userId).css({'display': 'none'});
                       

                        $('.discard button').attr('disabled', true);
                    
                    
                     if(cardsSelected.length != 0){
                         $('.group_btn').css({'display': 'block'});
                     }
                }

                console.log(cardsSelected);
               

            });





            /** =======  MELD CARD BUTTON PRESSED ======= **/
            
            $('#meld'+userId).click(function(){ 

                // RANADIP

                $('.popup_bg').show();
                $('.popup_bg .popup_with_button_cont p').text("Are you sure you want to meld?");
                $('.popup_bg #confirmBtn').removeClass('joinGameBtn');
                 $('.popup_bg #confirmBtn').removeClass("dropBtn");
                $('.popup_bg #confirmBtn').addClass('okBtn');

            });

            /** Cancel Melding * */
            
            $('.popup_bg .cancelBtn').click(function(){
                $('.popup_bg').hide();
                $('.popup_bg .popup_with_button_cont p').text("");
                


            });    

            /* Confirm Melding */
            $('.popup_bg').delegate('.okBtn', 'click', function(){
           

                 cardMelded = 1;
                 var roomIdCookie = $.cookie("room");
                 var sessionKeyCookie = $.trim($.cookie("sessionKey"));

                  // $(this).css({'display': 'none'});

                  $('.popup_bg').hide();
                  $('.popup_bg .popup_with_button_cont p').text("");



                  $('.game_message').html('').show();


                    $('.show_your_card_sec').css({'display': 'block'});

                    /* show meld all button */

                     /* Send a signal to other user for popup  */

                     var signal001 = {type : 'card-melded', player: userId, message: 'card melded', cardDiscarded: cardsSelected[0]};
                     connection.send(JSON.stringify(signal001));   


                  


                     if(cardGroupSelected){

                        
          

                 
                        /* remove from that group */

                        if( removeCardFromGroups(cardsSelected[0], eval('group'+cardGroupSelected)) ){


                            if(cardsSelected[0] != "Joker"){

                                var cardNumber = cardsSelected[0].substr(0, cardsSelected[0].indexOf('OF'));
                                var cardHouse =  cardsSelected[0].substr(cardsSelected[0].indexOf("OF") + 2);


                               $('.group_blog5[data-group='+cardGroupSelected+'] .playingCards .hand').find('li a[data-rank='+cardNumber+'][data-suit='+cardHouse+']').eq(0).remove();

                                $('.group_blog5[data-group='+cardGroupSelected+'] .playingCards .hand').find('li a[data-rank='+cardNumber+'][data-suit='+cardHouse+']').removeClass('activeCard');

                        if($('.group_blog5[data-group='+cardGroupSelected+'] .playingCards ul li a').length == 0){
                                    $('.group_blog5[data-group='+cardGroupSelected+']').remove();
                        }




                               $('.card-throw .playingCards').append('<a href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+' id="cardDeckSelectShow'+userId+'" class="cardDeckSelect noSelect">'+
                             '<div class="card card_2 rank-'+cardNumber+' '+cardHouse+'">'+
                             '<span class="rank">'+cardNumber+'</span>'+
                             '<span class="suit">&'+cardHouse+';</span>'+
                                    '</div></a>');

                                          


                            }else{

                                 $('.group_blog5[data-group='+cardGroupSelected+'] .playingCards .hand').find('li[data-rank="joker"]').eq(0).remove();


                                  $('.group_blog5[data-group='+cardGroupSelected+'] .playingCards .hand').find('li[data-rank="joker"]').removeClass('activeCard');

                                if($('.group_blog5[data-group='+cardGroupSelected+'] .playingCards ul li a').length == 0){
                                    $('.group_blog5[data-group='+cardGroupSelected+']').remove();
                                 }  

                               
                                  $('.card-throw .playingCards').append(' <a href="javascript:;" data-rank="joker" id="cardDeckSelectShow'+userId+'" class="cardDeckSelect noSelect"><div class="card joker card_2"></div></a>');

                            }    


                            /* remove card from db */

                             var ajxData700 = {'action': 'remove-card-discard', roomId: roomIdCookie, playerId: userId, cardGroup: eval('group'+cardGroupSelected), groupNos: cardGroupSelected, sessionKey: sessionKeyCookie};

                                $.ajax({
                                    type: 'POST',
                                    data: ajxData700,
                                    cache: false,
                                    url: 'ajax/removeCardDiscard.php',
                                    success: function(result){
                                        if( $.trim(result == "ok") ){
                                            console.log("card discard done");
                                            cardDiscard = 1;
                                            
                                            cardsSelected.length = 0;


                                            
                                            $('#meld'+userId).css({'display': 'none'});
                        
                                            /* Show meld button on top of each group */


                                            for(var i = 1; i < 7; i++){
                                                if( eval('group'+i).length > 1){
                                                    console.log("group"+i+ "  not empty");
                                                    


                                                    $('.meld_group_btn button[data-button='+i+']').show();

                                                }
                                            }


                                           
                                        }
                                        

                                    }

                                });



                            
                        }




                     }else{

                          /** show meld all button **/

                            $('#meldAll'+userId).css({'display': 'block'});
                            console.log("Card group selected : " + cardGroupSelected);
  

                        /* Cards have not been divided into group yet */

                           /* Remove the card from cardsInHand */

                            if( removeCardFromGroups(cardsSelected[0], cardsInHand) ){

                                console.log(cardsInHand);


                                if(cardsSelected[0] != "Joker"){

                                    var cardNumber = cardsSelected[0].substr(0, cardsSelected[0].indexOf('OF'));
                                    var cardHouse =  cardsSelected[0].substr(cardsSelected[0].indexOf("OF") + 2);

                           

                                 $('.player_card_me .hand li a[data-rank='+cardNumber+'][data-suit='+cardHouse+']').remove();


                                  $('.card-throw .playingCards').append('<a href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+' id="cardDeckSelectShow'+userId+'" class="cardDeckSelect noSelect">'+
                                 '<div class="card card_2 rank-'+cardNumber+' '+cardHouse+'">'+
                                 '<span class="rank">'+cardNumber+'</span>'+
                                 '<span class="suit">&'+cardHouse+';</span>'+
                                        '</div></a>');
                                              


                                }else{

                                     $('.player_card_me .hand li a[data-rank="joker"]').remove();

                                     
                                      $('.card-throw .playingCards').append(' <a href="javascript:;" data-rank="joker" id="cardDeckSelectShow'+userId+'" class="cardDeckSelect noSelect"><div class="card joker card_2"></div></a>');


                                }

                                var ajxData701 = {'action': 'remove-card-discard-hand', roomId: roomIdCookie, playerId: userId, cardsInHand: cardsInHand, sessionKey: sessionKeyCookie };

                                $.ajax({
                                    type: 'POST',
                                    data: ajxData701,
                                    cache: false,
                                    url: 'ajax/removeCardDiscardHand.php',
                                    success: function(result){
                                        if( $.trim(result == "ok") ){
                                            console.log("card discard done");
                                            cardDiscard = 1;
                                          
                                            cardsSelected.length = 0;
                                            $('#meld'+userId).css({'display': 'none'});

                                           
                                    }
                                        

                                }

                            });



                     }


                    




                  }

            });

            

            /* ==== Group Each Melding Btn clicked ======= */

            $('.group').delegate('.meld_group_btn button', 'click', function(){

                if(meldedGroup1.length > 0 && meldedGroup2.length > 0 && meldedGroup3.length > 0 && meldedGroup4.length > 0){

                    return false;
                
                 }else{   
                
                var groupBtn = $(this).attr('data-button');
                var flagMeld1 = 0;
                var self = $(this);
                var roomIdCookie = $.cookie("room"); 
                var sessionKeyCookie = $.trim($.cookie("sessionKey"));



                    if(meldedGroup1.length == 0){
                        flagMeld1 = 1;
                    }else if(meldedGroup2.length == 0){
                        flagMeld1 = 2;
                    }else if(meldedGroup3.length == 0){
                        flagMeld1 = 3;
                    }else if(meldedGroup4.length == 0){
                        flagMeld1 = 4;
                    }

                     console.log('flag set earyl ' + flagMeld1);


                    for(var j = 0; j < eval('group'+groupBtn).length; j++){
                            /* Push the cards into new melded group */

                            eval('meldedGroup'+flagMeld1).push( eval('group'+groupBtn)[j] );

                    }


                    /* Update melded groups in db */


                    var ajxData800 = { 'action': 'update-meld-card', roomId: roomIdCookie, player: userId, groupDeleted: groupBtn, meldedGroup: eval('meldedGroup'+flagMeld1), meldedGroupNos: flagMeld1, sessionKey: sessionKeyCookie };


                        $.ajax({
                            type: 'POST',
                            data: ajxData800,
                            cache: false,
                            url: 'ajax/updateMeldedCard.php',
                            success: function(result){
                                if( $.trim(result == "ok") ){

                                    console.log("DB Updated");

                                    /* View */

                                    /* Show melded card in melded card section */

                                    $('.show_your_card_blog[data-group="'+flagMeld1+'"]').prepend('<a href="javascript:;" class="removeMeldedGroup" id="'+flagMeld1+'"><i class="fa fa-close"></i></a>');


                                    for(var i = 0; i < eval('meldedGroup'+flagMeld1).length; i++){

                                                    /* show the card */

                                        if(eval('meldedGroup'+flagMeld1)[i] != "Joker"){

                                            var cardNumber = eval('meldedGroup'+flagMeld1)[i].substr(0, eval('meldedGroup'+flagMeld1)[i].indexOf('OF'));
                                            
                                            var cardHouse =  eval('meldedGroup'+flagMeld1)[i].substr(eval('meldedGroup'+flagMeld1)[i].indexOf("OF") + 2);


                                            
                                            $('.show_your_card_blog[data-group="'+flagMeld1+'"] .playingCards .hand').append('<li><a class="card card_2 rank-'+cardNumber+' '+cardHouse+'">'+
                                                '<span class="rank">'+cardNumber+'</span>'+
                                                '<span class="suit">&'+cardHouse+';</span>'+
                                                '</a></li>');


                                        }else{

                                            $('.show_your_card_blog[data-group="'+flagMeld1+'"] .playingCards .hand').append('<li><a href="javascript:;" data-rank="joker" class="card card_2 joker"></a></li>');


                                        }


                                    }



                                    /* Remove empty group from UI */

                                    $('.group_blog5[data-group="'+groupBtn+'"]').remove();

                                    /* set the flag value to 0 */
                                    flagMeld1 = 0;
                                    console.log('flag set ' + flagMeld1);

                                     /* Remove cards from that group  */

                                    eval('group'+groupBtn).length = 0;
                    

                                    /* Hide the button */

                                    self.parent().css({'display': 'none'});



                                }
                                
                            }
                            
                        });


                   }                 
             
            
            });


            /* === Remove melded card group  */

            $('.show_your_card_sec').delegate('.removeMeldedGroup', 'click', function(){

                var meldedGroupNos = $(this).attr('id');
                var self = $(this);
                var roomIdCookie = $.cookie("room");
                var sessionKeyCookie = $.trim($.cookie("sessionKey")); 


                var meldedGroup = eval('meldedGroup'+meldedGroupNos);

                
                 var flag = 0;

                    if(group1.length == 0){
                        flag = 1;
                    }else if(group2.length == 0){
                        flag = 2;
                    }else if(group3.length == 0){
                        flag = 3;
                    }else if(group4.length == 0){
                        flag = 4;
                    }else if(group5.length == 0){
                        flag = 5;
                    }else if(group6.length == 0){
                        flag = 6;
                    }


                 var groupToBeAdded = eval('group'+flag);      


                 /*  Add cards into the group */

                 for(var i = 0; i < meldedGroup.length; i++){

                    groupToBeAdded.push(meldedGroup[i]);
                 }


                 console.log("Group to be added " + flag);


                /* ajax remove melded group from db */


                 /* Update melded groups in db */


                    var ajxData801 = { 'action': 'remove-meld-card', roomId: roomIdCookie, player: userId, meldedGroupNos: meldedGroupNos, sessionKey: sessionKeyCookie};


                        $.ajax({
                            type: 'POST',
                            data: ajxData801,
                            cache: false,
                            url: 'ajax/removeMeldedCard.php',
                            success: function(result){
                                if( $.trim(result == "ok") ){

                                    console.log("DB Updated");
                                    meldedGroup.length = 0;

                                    /* View */

                                    /*  remove from melded panel */


                                    $('.show_your_card_blog[data-group="'+meldedGroupNos+'"] .playingCards .hand').html("");

                                    self.remove();
                                    updateGroupsWhileMelding();
                                   

                                }
                            }    
                        });        
                });






            /** === Meld All Button clicked ===== **/

            $('#meldAll'+userId).click(function(){

                var roomIdCookie = $.cookie("room");
                var sessionKeyCookie = $.trim($.cookie("sessionKey"));
                
                var self = $(this);

                /* if group exist */

                 if(cardGroupSelected){


                    /*  check if group not empty insert into meldedGroups */

                        for(var q = 1; q < 7; q++){

                            if(meldedGroup1.length > 0 && meldedGroup2.length > 0 && meldedGroup3.length > 0 && meldedGroup4.length > 0){
                                break;
                            }

                            if( eval('group'+q).length > 0 ){
                                /*  Group has card */

                                /*  Insert those cards into meldedGroups */

                               var meldedGrpFlag = 0;

                               if(meldedGroup1.length == 0){
                                meldedGrpFlag = 1;
                               }else if(meldedGroup2.length == 0){
                                meldedGrpFlag = 2;
                               }else if(meldedGroup3.length == 0){
                                meldedGrpFlag = 3;
                               }else if(meldedGroup4.length == 0){
                                meldedGrpFlag = 4;
                               }



                                for(var j = 0; j < eval('group'+q).length; j++){


                                    eval('meldedGroup'+meldedGrpFlag).push( eval('group'+q)[0] );
                                    
                                    console.log("Card pushed ", eval('group' + q)[0]);


                                    if(removeCardFromGroups(eval('group'+q)[0], eval('group'+q))){
                                      console.log("Grp " + q + " - " + eval("group"+q));
                                    }

                                    console.log("Melded group ", eval("meldedGroup"+meldedGrpFlag));
                                    console.log("Grp " + q + " - " + eval("group"+q));
                                   

                                   
             
                                } 

                             


                                /*  Ajax remove cards from group */
                            }



                        }



                        /*  Show melded cards in meld section */


                        /*  4 melded groups, 4 iterations */

                        for(var k = 1; k < 5; k++){


                            $('.show_your_card_blog[data-group="'+k+'"]').prepend('<a href="javascript:;" class="removeMeldedGroup" id="'+k+'"><i class="fa fa-close"></i></a>');


                            for(var i = 0; i < eval('meldedGroup'+k).length; i++){

                                /* show the card */

                                if(eval('meldedGroup'+k)[i] != "Joker"){

                                    var cardNumber = eval('meldedGroup'+k)[i].substr(0, eval('meldedGroup'+k)[i].indexOf('OF'));
                                    
                                    var cardHouse =  eval('meldedGroup'+k)[i].substr(eval('meldedGroup'+k)[i].indexOf("OF") + 2);


                                    
                                    $('.show_your_card_blog[data-group="'+k+'"] .playingCards .hand').append('<li><a class="card card_2 rank-'+cardNumber+' '+cardHouse+'">'+
                                        '<span class="rank">'+cardNumber+'</span>'+
                                        '<span class="suit">&'+cardHouse+';</span>'+
                                        '</a></li>');


                                }else{

                                    $('.show_your_card_blog[data-group="'+k+'"] .playingCards .hand').append('<li><a href="javascript:;" data-rank="joker" class="card card_2 joker"></a></li>');


                                }


                            }




                        }


                        console.log('group1 ', group1);
                        console.log('group2 ', group2);
                        console.log('group3 ', group3);
                        console.log('group4 ', group4);
                        console.log('group5 ', group5);
                        console.log('group6 ', group6);

                        console.log('meldgroup1 ', meldedGroup1);
                        console.log('meldgroup2 ', meldedGroup2);
                        console.log('meldgroup3 ', meldedGroup3);
                        console.log('meldgroup4 ', meldedGroup4);


                        if(group1.length == 0){

                            $('.group_blog5[data-group="1"] .playingCards .hand').html("");

                        }

                        if(group2.length == 0){

                            $('.group_blog5[data-group="2"] .playingCards .hand').html("");

                        }

                        if(group3.length == 0){

                            $('.group_blog5[data-group="3"] .playingCards .hand').html("");

                        }

                        if(group4.length == 0){

                            $('.group_blog5[data-group="4"] .playingCards .hand').html("");

                        }

                        if(group5.length == 0){

                            $('.group_blog5[data-group="5"] .playingCards .hand').html("");

                        }

                        if(group6.length == 0){

                            $('.group_blog5[data-group="6"] .playingCards .hand').html("");

                        }

                   


                 }else{



                   

                    if(cardsInHand.length > 0){



                        /* Transfer all cards to group1 */
                        
                        for(var i = 0; i < cardsInHand.length; i++){
                            group1.push(cardsInHand[i]);
                        }

                        cardsInHand.length = 0; // empty cards in hand



                        /* transfer all group1 cards to meldedGroup */


                        for(var i = 0; i < group1.length; i++){

                            meldedGroup1.push(group1[i]);

                        }



                        group1.length = 0; // empty group1


                        /*  Update melded cards group  */

                         var ajxData802 = { 'action': 'update-meld-card', roomId: roomIdCookie, player: userId, meldedGroup: meldedGroup1, meldedGroupNos: 1, sessionKey: sessionKeyCookie };


                        $.ajax({
                            type: 'POST',
                            data: ajxData802,
                            cache: false,
                            url: 'ajax/updateMeldedCardNoGroup.php',
                            success: function(result){
                                if( $.trim(result == "ok") ){

                                    console.log("DB Updated ---- melded group !");


                                    /*  UI changes */

                                     /* remove cards from table  */

                                     $('.player_card_me .hand').html("");


                                       /* Show melded card in melded card section */

                                    $('.show_your_card_blog[data-group="1"]').prepend('<a href="javascript:;" class="removeMeldedGroup" id="1"><i class="fa fa-close"></i></a>');


                                    for(var i = 0; i < meldedGroup1.length; i++){

                                        /* show the card */

                                        if(meldedGroup1[i] != "Joker"){

                                            var cardNumber = meldedGroup1[i].substr(0, meldedGroup1[i].indexOf('OF'));
                                            
                                            var cardHouse =  meldedGroup1[i].substr(meldedGroup1[i].indexOf("OF") + 2);


                                            
                                            $('.show_your_card_blog[data-group="1"] .playingCards .hand').append('<li><a class="card card_2 rank-'+cardNumber+' '+cardHouse+'">'+
                                                '<span class="rank">'+cardNumber+'</span>'+
                                                '<span class="suit">&'+cardHouse+';</span>'+
                                                '</a></li>');


                                        }else{

                                            $('.show_your_card_blog[data-group="1"] .playingCards .hand').append('<li><a href="javascript:;" data-rank="joker" class="card card_2"></a></li>');


                                        }


                                    }


                                    self.hide();

                                }
                            }
                         });           


                    }


                }   




            });




            function checkIfAllMelded(callback){

                if(group1.length == 0 && group2.length == 0 && group3.length == 0 && group4.length == 0 && group5.length == 0 && group6.length == 0){

                    meldedGroup1.length = 0;

                    for(var i = 0; i < cardsInHand.length; i++){
                         meldedGroup1.push( cardsInHand[i] );
                    }

                    for(var i = 0; i < meldedGroup1.length; i++){
                         if( meldedGroup1[i] != "Joker"){

                            var cardNumber = meldedGroup1[i].substr(0, meldedGroup1[i].indexOf('OF'));
                            var cardHouse = meldedGroup1[i].substr(meldedGroup1[i].indexOf("OF") + 2);


                            
                            $('.show_your_card_blog[data-group="4"] .playingCards .hand').append('<li><a class="card card_2 rank-'+cardNumber+' '+cardHouse+'">'+
                                '<span class="rank">'+cardNumber+'</span>'+
                                '<span class="suit">&'+cardHouse+';</span>'+
                                '</a></li>');


                        }else{

                            $('.show_your_card_blog[data-group="4"] .playingCards .hand').append('<li><a href="javascript:;" data-rank="joker" class="card card_2 joker"></a></li>');


                        }
                    }

                    cardsInHand.length = 0;



                }else{

                var flagMeld2;

                for(var i = 1; i < 7; i++){

                        flagMeld2 = 0;

                        if(eval('group'+i).length > 0){


                            if(meldedGroup1.length > 0 && meldedGroup2.length > 0 && meldedGroup3.length > 0 && meldedGroup4.length > 0){

                              

                                for(var j = 0; j < eval('group'+i).length; j++){
                                    meldedGroup4.push( eval('group'+i)[j] );

                                    if(eval('group'+i)[j] != "Joker"){

                                            var cardNumber = eval('group'+i)[j].substr(0, eval('group'+i)[j].indexOf('OF'));
                                            
                                            var cardHouse =  eval('group'+i)[j].substr(eval('group'+i)[j].indexOf("OF") + 2);


                                            
                                            $('.show_your_card_blog[data-group="4"] .playingCards .hand').append('<li><a class="card card_2 rank-'+cardNumber+' '+cardHouse+'">'+
                                                '<span class="rank">'+cardNumber+'</span>'+
                                                '<span class="suit">&'+cardHouse+';</span>'+
                                                '</a></li>');


                                        }else{

                                            $('.show_your_card_blog[data-group="4"] .playingCards .hand').append('<li><a href="javascript:;" data-rank="joker" class="card card_2 joker"></a></li>');


                                        }

                                }  


                                console.log("SOFT KITTY====================================");







                                eval('group'+i).length = 0;

                            }else{


                                 if(meldedGroup1.length == 0){
                                    flagMeld2 = 1;
                                 }else if(meldedGroup2.length == 0){
                                    flagMeld2 = 2;
                                 }else if(meldedGroup3.length == 0){
                                    flagMeld2 = 3;
                                 }else if(meldedGroup4.length == 0){
                                    flagMeld2 = 4;
                                 }


                                 for(var j = 0; j < eval('group'+i).length; j++){
                                       eval('meldedGroup'+flagMeld2).push( eval('group'+i)[j] );

                                 }

                                 eval('group'+i).length = 0;


                                  $('.show_your_card_blog[data-group="'+flagMeld2+'"]').prepend('<a href="javascript:;" class="removeMeldedGroup" id="'+flagMeld2+'"><i class="fa fa-close"></i></a>');


                                    for(var x = 0; x < eval('meldedGroup'+flagMeld2).length; x++){

                                        /** ------------ show the card --------------- **/

                                        if(eval('meldedGroup'+flagMeld2)[x] != "Joker"){

                                            var cardNumber = eval('meldedGroup'+flagMeld2)[x].substr(0, eval('meldedGroup'+flagMeld2)[x].indexOf('OF'));
                                            
                                            var cardHouse =  eval('meldedGroup'+flagMeld2)[x].substr(eval('meldedGroup'+flagMeld2)[x].indexOf("OF") + 2);


                                            
                                            $('.show_your_card_blog[data-group="'+flagMeld2+'"] .playingCards .hand').append('<li><a class="card card_2 rank-'+cardNumber+' '+cardHouse+'">'+
                                                '<span class="rank">'+cardNumber+'</span>'+
                                                '<span class="suit">&'+cardHouse+';</span>'+
                                                '</a></li>');


                                        }else{

                                            $('.show_your_card_blog[data-group="'+flagMeld2+'"] .playingCards .hand').append('<li><a href="javascript:;" data-rank="joker" class="card card_2"></a></li>');


                                        }


                                    }




                            } 

                        }

                        
                    }


                  }  

                    callback();



            }


            /** === Send cards for validation ===== **/

            

            /**** =========== VALIDATE ******* ============== */

            $('#validateCards'+userId).click(function(){

                $(this).attr('disabled', true);

                

                
                 var roomIdCookie = $.cookie("room");
                 var gameTypeCookie = $.cookie("game-type");
                 var gamePlayersCookie = $.cookie("game-players");
                 var sessionKeyCookie = $.trim($.cookie("sessionKey"));

                  var chipsToTablePRCookie = $.trim($.cookie("chipsToTablePR"));
                  var currentBalanceCookie = $.trim($.cookie("currentBalance"));
                  var minBuyingPRCookie = $.trim($.cookie("minBuying"));
                  var betValueCookie = $.trim($.cookie("betValue"));

                 

                 checkIfAllMelded(function(){

                    $('.meldAll').hide();

                    $('.group_blog5').remove();



                        /* Update all the melded groups in db */

                        var ajxData81200 = { 'action': 'update-all-groups', roomId: roomIdCookie, player: userId, meldedGroup1: meldedGroup1, meldedGroup2: meldedGroup2, meldedGroup3: meldedGroup3, meldedGroup4: meldedGroup4, sessionKey: sessionKeyCookie};


                        $.ajax({
                            type: 'POST',
                            data: ajxData81200,
                            cache: false,
                            url: 'ajax/updateMeldedGroups.php',
                            success: function(result){
                               if($.trim(result) == "ok"){
                                    console.log("all groups updated");
                               }
                            }
                        }); 


                         if(meldingProcess == 0){


                            var groupCountFlag = 0;

                            if(meldedGroup1.length > 0){
                                groupCountFlag = 1;
                            }

                            if(meldedGroup2.length > 0){
                                groupCountFlag = 2;
                            }

                            if(meldedGroup3.length > 0){
                                groupCountFlag = 3;
                            }

                            if(meldedGroup4.length > 0){
                                groupCountFlag = 4;
                            }



                            if(groupCountFlag != 4){

                               if(gamePlayersCookie == "2"){
                                 
                                 $('.result_sec').css({'display': 'block'});
                                 wrongValidationDisplayProcess(80, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'lost');
                               }else if(gamePlayersCookie == "6"){
                                $('.result_sec').css({'display': 'block'});
                                 wrongValidationDisplayProcessSixPlayers(80, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'lost');
                               } 
                                


                            }else{


                               

                                

                                var ajxData888 = { 'action': 'get-joker-card', roomId: roomIdCookie, sessionKey: sessionKeyCookie};


                                        $.ajax({
                                            type: 'POST',
                                            data: ajxData888,
                                            cache: false,
                                            url: 'ajax/getJokerCard.php',
                                            success: function(card){

                                                console.log("joker retrieve ", card);

                                                if(card != "Joker"){
                                                    var cardNumber = card.substr(0, card.indexOf('OF'));

                                                    
                                                    console.log("cardNumber ", cardNumber);


                                                     if($.trim(cardNumber) == "J"){
                                                        jokerValue = 11;
                                                    }else if($.trim(cardNumber) == "Q"){
                                                        jokerValue = 12;
                                                       
                                                    }else if($.trim(cardNumber) == "K"){
                                                        jokerValue = 13;
                                                        
                                                    }else if($.trim(cardNumber) == "A"){
                                                        jokerValue = 1;

                                                    }else{
                                                        jokerValue = parseInt(cardNumber);
                                                    }

                                                }else{

                                                   jokerValue = 20;
                                                    
                                                }



                                           }
                                           
                                    });


                                /* Lets analyze all the groups */ 

                                    /** ========= If any group has less than 3 or more than 4 cards  OR  =========== */

                                

                                     setTimeout(function(){

                                        

                                        var flag2GroupMoreThan3Cards = 0;
                                        var meldLessOrMoreCards = 0;


                                        for(var i = 1; i < 5; i++){
                                            
                                            if( eval('meldedGroup'+i).length < 3 || eval('meldedGroup'+i).length > 4 ){
                                                console.log("wrongshow ", 'meldedGroup'+i );
                                                 if(gamePlayersCookie == "2"){
                                                   $('.result_sec').css({'display': 'block'}); 
                                                  wrongValidationDisplayProcess(80, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'lost');
                                                }else if(gamePlayersCookie == "6"){
                                                    $('.result_sec').css({'display': 'block'});
                                                 wrongValidationDisplayProcessSixPlayers(80, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'lost');
                                                } 
                                                meldLessOrMoreCards++;
                                                break;
                                            }else if(flag2GroupMoreThan3Cards > 1){
                                                console.log("wrongshow more than 3 cards in more than 1 grp!");
                                                 if(gamePlayersCookie == "2"){
                                                 $('.result_sec').css({'display': 'block'});
                                                 wrongValidationDisplayProcess(80, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'lost');
                                                }else if(gamePlayersCookie == "6"){
                                                 $('.result_sec').css({'display': 'block'});
                                                 wrongValidationDisplayProcessSixPlayers(80, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'lost');
                                                } 
                                                break;
                                            }

                                             if( eval('meldedGroup'+i).length > 3){

                                                flag2GroupMoreThan3Cards++;

                                              }  


                                        }




                                          if(flag2GroupMoreThan3Cards == 1 && meldLessOrMoreCards == 0){

                                        /** ============= Proper Show . Let's resume ============= */
                                        
                                         


                                                /* Segrating the cards into suit and values and pushing them in MeldCardArr(Nos) */

                                                // code under construction

                                              for(var i = 1; i < 5; i++){

                                                for(j = 0; j < eval('meldedGroup'+i).length; j++){

                                                    if(eval('meldedGroup'+i)[j] != "Joker"){

                                                        var cardNumber = eval('meldedGroup'+i)[j].substr(0, eval('meldedGroup'+i)[j].indexOf('OF'));
                                                
                                                        var cardHouse =  eval('meldedGroup'+i)[j].substr(eval('meldedGroup'+i)[j].indexOf("OF") + 2);

                                                          var cardValue;

                                                         if(cardNumber == "J"){
                                                        
                                                            cardValue = 11;
                                                        }else if(cardNumber == "Q"){
                                                            cardValue = 12;
                                                       
                                                        }else if(cardNumber == "K"){
                                                            cardValue = 13;
                                                        
                                                        }else if(cardNumber == "A"){
                                                            cardValue = 1;

                                                        }else{
                                                            cardValue = cardNumber;
                                                        }

                                                        eval('meldCardArr'+i).push({"value": parseInt(cardValue),"suit": cardHouse});

                                                    }else{

                                                        eval('meldCardArr'+i).push({"suit": "joker"});

                                                    }

                                                }


                                              }


                                                // meldCardArr1.push({"value":1,"suit":"hearts"},{"value":12,"suit":"diams"},{"value":12,"suit":"clubs"});
                                                // meldCardArr2.push({"value":2,"suit":"hearts"},{"suit":"joker"},{"suit":"joker"},{"value":4,"suit":"hearts"});
                                                // meldCardArr3.push({"value":5,"suit":"hearts"},{"value":6,"suit":"hearts"},{"value":7,"suit":"hearts"});
                                                // meldCardArr4.push({"value":5,"suit":"hearts"},{"value":5,"suit":"diams"},{"value":5,"suit":"clubs"});

                                                //  meldCardArr1.push({"value":8,"suit":"hearts"},{"value":9,"suit":"hearts"},{"value":10,"suit":"hearts"});
                                                // meldCardArr2.push({"value":11,"suit":"spades"},{"value":12, "suit":"spades"},{"value":13, "suit":"spades"},{"value":1,"suit":"spades"});
                                                // meldCardArr3.push({"value":7,"suit":"clubs"},{"value":7,"suit":"diams"},{"value":7,"suit":"spades"});
                                                // meldCardArr4.push({"value":7,"suit":"diams"},{"value":7,"suit":"hearts"},{"value":2,"suit":"hearts"});


                                             
                                        /** ======================= Checking the melding function , Melding Algorithm ================ **/



                                            

                                            for(var i = 1; i < 5; i++){
                                                console.log("Joker value ", jokerValue);
                                                console.log(getSummary(eval('meldCardArr'+i), i));

                                                 // test output
                                            }

                                          




                                            /* set the values of pureSeq, matchingCards and impureSeq  */
                                             

                                            for(var i = 1; i < 5; i++){
                                                if( eval('meldCardEvaluator'+i)[0].isPure === true && eval('meldCardEvaluator'+i)[0].isSequence === true){
                                                    pureSequence++;
                                                    console.log(pureSequence);
                                                }else if(eval('meldCardEvaluator'+i)[0].isPure === false && eval('meldCardEvaluator'+i)[0].isSequence === true){
                                                    impureSequence++;
                                                    console.log(impureSequence);
                                                }else if(eval('meldCardEvaluator'+i)[0].isSameValue === true){
                                                    matchingCards++;
                                                    console.log(matchingCards);
                                                }else if( eval('meldCardEvaluator'+i)[0].isPure === false && eval('meldCardEvaluator'+i)[0].isSequence === true && eval('meldCardEvaluator'+i)[0].isSameValue === true ){
                                                        impureSequence++;
                                                        console.log(impureSequence);
                                                }else if( eval('meldCardEvaluator'+i)[0].isPure === true && eval('meldCardEvaluator'+i)[0].isSequence === true && eval('meldCardEvaluator'+i)[0].isSameValue === true ){
                                                        pureSequence++;
                                                        console.log(pureSequence);
                                                       
                                                }  
                                            }



                                          setTimeout(function(){


                                                console.log("group total ", pureSequence+impureSequence+matchingCards);

                                                if( (pureSequence+impureSequence+matchingCards) === 4 && (pureSequence+impureSequence) >= 2){


                                                console.log("success melding");


                                                /* successful melding */
                                                
                                            $('.result_sec').css({'display': 'block'}); 
                                            successfulMelding(roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie);   





                                            }else{


                                                console.log('meld error');
                                                console.log(pureSequence+impureSequence+matchingCards);
                                                console.log("pure " + pureSequence);
                                                console.log("impure " + impureSequence);
                                                console.log("matching " + matchingCards);
                                               
                                                if(gamePlayersCookie == "2"){
                                                 $('.result_sec').css({'display': 'block'});   
                                                 wrongValidationDisplayProcess(80, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'lost');
                                               }else if(gamePlayersCookie == "6"){
                                                $('.result_sec').css({'display': 'block'});
                                                 wrongValidationDisplayProcessSixPlayers(80, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'lost');
                                               } 

                                            }

                                          }, 3000);  
                                          



                                          

                                        }


                                          
                                     }, 3000);  



                                  




                            }

                         }else if(meldingProcess == 1){


                            /* If cards have already melded by another user */

                            console.log("MELDING PROCESS ----------------- ", 1);



                            /* Total score sum */
                            var totalScoreSum = 0;

                             /* Check number of groups */

                                var groupCountFlag = 0;

                                if(meldedGroup1.length > 0){
                                    groupCountFlag = 1;
                                }

                                if(meldedGroup2.length > 0){
                                    groupCountFlag = 2;
                                }

                                if(meldedGroup3.length > 0){
                                    groupCountFlag = 3;
                                }

                                if(meldedGroup4.length > 0){
                                    groupCountFlag = 4;
                                }


                           


                            /*  If only 1 or no group */

                            if(groupCountFlag <= 1){

                                /* get total score */


                                // transfer all cards to meldedgroup1 from cardsInHand

                                if(cardsInHand.length > 0){

                                    for(var i = 0; i < cardsInHand.length; i++){

                                        meldedGroup1.push(cardsInHand[i]);
                                    }

                                    cardsInHand.length = 0;

                                }


                                        for(j = 0; j < meldedGroup1.length; j++){

                                            if(meldedGroup1[j] != "Joker"){

                                                var cardNumber = meldedGroup1[j].substr(0, meldedGroup1[j].indexOf('OF'));
                                        
                                                var cardHouse =  meldedGroup1[j].substr(meldedGroup1[j].indexOf("OF") + 2);

                                                var cardValue;

                                                 if(cardNumber == "J"){
                                                    cardValue = 11;
                                                }else if(cardNumber == "Q"){
                                                    cardValue = 12;
                                                }else if(cardNumber == "K"){
                                                    cardValue = 13;
                                                }else if(cardNumber == "A"){
                                                    cardValue = 1;
                                                }else{
                                                    cardValue = cardNumber;
                                                }

                                                meldCardArr1.push({"value": parseInt(cardValue),"suit": cardHouse});



                                            }else{

                                                meldCardArr1.push({"suit": "joker"});

                                            }

                                        }


                                      
                                        for(var j = 0; j < meldCardArr1.length; j++){

                                            if(meldCardArr1[j].suit !== "joker"){
                                              

                                                if(meldCardArr1[j].value == 1 || meldCardArr1[j].value == 11 || meldCardArr1[j].value == 12 || meldCardArr1[j].value == 13){
                                                    totalScoreSum = totalScoreSum + 10;
                                                }else{
                                                    totalScoreSum = totalScoreSum + meldCardArr1[j].value;
                                                }
                                                

                                            }else{
                                                 totalScoreSum = totalScoreSum + 10;
                                            }

                                        

                                        }




                                 $('.result_sec').css({'display': 'block'});
                                 wrongValidationDisplayProcess2(totalScoreSum, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie); 

                                 // Score is 80
                                         


                             }else if(groupCountFlag > 1){


                             
                                var ajxData888 = { 'action': 'get-joker-card', roomId: roomIdCookie, sessionKey: sessionKeyCookie};


                                        $.ajax({
                                            type: 'POST',
                                            data: ajxData888,
                                            cache: false,
                                            url: 'ajax/getJokerCard.php',
                                            success: function(card){

                                                console.log("joker retrieve ", card);

                                                if(card != "Joker"){
                                                    var cardNumber = card.substr(0, card.indexOf('OF'));

                                                    
                                                    console.log("cardNumber ", cardNumber);

                                                    
                                                   if($.trim(cardNumber) == "J"){
                                                        jokerValue = 11;
                                                    }else if($.trim(cardNumber) == "Q"){
                                                        jokerValue = 12;
                                                       
                                                    }else if($.trim(cardNumber) == "K"){
                                                        jokerValue = 13;
                                                        
                                                    }else if($.trim(cardNumber) == "A"){
                                                        jokerValue = 1;

                                                    }else{
                                                        jokerValue = parseInt(cardNumber);
                                                    }

                                                }else{

                                                   jokerValue = 20;
                                                    
                                                }



                                           }
                                           
                                    });



                                     setTimeout(function(){

                                            // Adding the cards into arrays for evaluation



                                         

                                              for(var i = 1; i < 5; i++){

                                                for(j = 0; j < eval('meldedGroup'+i).length; j++){

                                                    if(eval('meldedGroup'+i)[j] != "Joker"){

                                                        var cardNumber = eval('meldedGroup'+i)[j].substr(0, eval('meldedGroup'+i)[j].indexOf('OF'));
                                                
                                                        var cardHouse =  eval('meldedGroup'+i)[j].substr(eval('meldedGroup'+i)[j].indexOf("OF") + 2);

                                                        var cardValue;

                                                         if(cardNumber == "J"){
                                                        
                                                            cardValue = 11;
                                                        }else if(cardNumber == "Q"){
                                                            cardValue = 12;
                                                       
                                                        }else if(cardNumber == "K"){
                                                            cardValue = 13;
                                                        
                                                        }else if(cardNumber == "A"){
                                                            cardValue = 1;

                                                        }else{
                                                            cardValue = cardNumber;
                                                        }

                                                        eval('meldCardArr'+i).push({"value": parseInt(cardValue),"suit": cardHouse});



                                                    }else{

                                                        eval('meldCardArr'+i).push({"suit": "joker"});

                                                    }

                                                }


                                              }



                                               // meldCardArr1.push({"value":2,"suit":"clubs"},{"value":3,"suit":"clubs"},{"value":5,"suit":"clubs"},{"value":4,"suit":"clubs"});
                                               //  meldCardArr2.push({"value":2,"suit":"hearts"},{"suit":"joker"},{"suit": "joker"});
                                               //  meldCardArr3.push({"value":5,"suit":"hearts"},{"value":5,"suit":"diams"},{"value":7,"suit":"hearts"});
                                               //  meldCardArr4.push({"value":5,"suit":"hearts"},{"value":7,"suit":"diams"},{"value":8,"suit":"clubs"});



                                            for(var i = 1; i < 5; i++){
                                                console.log("Joker value ", jokerValue);
                                                console.log(getSummary(eval('meldCardArr'+i), i));
                                            }


                                            /* add total score */


                                            for(var i = 1; i < 5; i++){

                                               

                                                for(var j = 0; j < eval('meldCardArr'+i).length; j++){

                                                      if(  eval('meldCardArr'+i).length !==0 ){ 

                                                        if(eval('meldCardArr'+i)[j].suit !== "joker"){
                                                          

                                                            if(eval('meldCardArr' + i)[j].value == 1 || eval('meldCardArr' + i)[j].value == 11 || eval('meldCardArr' + i)[j].value == 12 || eval('meldCardArr' + i)[j].value == 13){
                                                                totalScoreSum = totalScoreSum + 10;
                                                            }else{
                                                                totalScoreSum = totalScoreSum + eval('meldCardArr'+i)[j].value;
                                                            }
                                                            

                                                        }else{
                                                            totalScoreSum = totalScoreSum + 10;
                                                        }

                                                      }

                                                   

                                                }



                                            }

                                            console.log("total score sum", totalScoreSum);


                                            var pureSeqGroup = [];
                                            var impureSeqGroup = [];
                                            var matchingCardsGroup = [];
                                            var victimsGroup = [];


                                            for(var i = 1; i < 5; i++){

                                               if( eval('meldCardEvaluator'+i).length !== 0){ 

                                                    if( eval('meldCardEvaluator'+i)[0].isPure === true && eval('meldCardEvaluator'+i)[0].isSequence === true){
                                                        pureSequence++;
                                                        pureSeqGroup.push(i);
                                                        console.log("pushing into pure seq ", i);
                                                    }else if(eval('meldCardEvaluator'+i)[0].isPure === false && eval('meldCardEvaluator'+i)[0].isSequence === true){
                                                        impureSequence++;
                                                        impureSeqGroup.push(i);
                                                        console.log("pushing into impure seq ", i);
                                                    }else if(eval('meldCardEvaluator'+i)[0].isSameValue === true){
                                                        matchingCards++;
                                                        matchingCardsGroup.push(i);
                                                       console.log("pushing into matching cards ", i);
                                                    }else if( eval('meldCardEvaluator'+i)[0].isPure === false && eval('meldCardEvaluator'+i)[0].isSequence === false && eval('meldCardEvaluator'+i)[0].isSameValue === false){
                                                    
                                                        victimsGroup.push(i);   
                                                        console.log("pushing into victims ", i);
                                                    }else if( eval('meldCardEvaluator'+i)[0].isPure === false && eval('meldCardEvaluator'+i)[0].isSequence === true && eval('meldCardEvaluator'+i)[0].isSameValue === true ){
                                                        impureSequence++;
                                                        impureSeqGroup.push(i);
                                                        console.log("pushing into impure seq ", i);
                                                    }else if( eval('meldCardEvaluator'+i)[0].isPure === true && eval('meldCardEvaluator'+i)[0].isSequence === true && eval('meldCardEvaluator'+i)[0].isSameValue === true ){
                                                        pureSequence++;
                                                        pureSequence.push(i);
                                                        console.log("pushing into pure seq ", i);
                                                    }  
                                            }

                                          }






                                          setTimeout(function(){

                                            console.log('pure seq', pureSequence);
                                            console.log('impure seq', impureSequence);
                                            console.log('matchingCards', matchingCards);


                                            console.log("pure seq group", pureSeqGroup);
                                            console.log("impure seq group", impureSeqGroup);
                                            console.log("matching seq group", matchingCardsGroup);
                                            console.log("other victim group", victimsGroup);



                                             
                                            console.log('totalScoreSum ', totalScoreSum);

                                            if(pureSequence == 0){
                                                console.log("80 points");
                                                totalScoreSum = 80;


                                            }else{

                                                if(pureSequence == 1 && pureSequence+impureSequence == 1 ){

                                                    for(var i = 0; i < pureSeqGroup.length; i++){

                                                        for(var j = 0; j <  eval('meldCardArr'+pureSeqGroup[i]).length; j++){

                                                            if(eval('meldCardArr' + pureSeqGroup[i])[j].value == 1 || eval('meldCardArr' + pureSeqGroup[i])[j].value == 11 || eval('meldCardArr' + pureSeqGroup[i])[j].value == 12 || eval('meldCardArr' + pureSeqGroup[i])[j].value == 13){

                                                                totalScoreSum = totalScoreSum - 10;

                                                            }else{

                                                                totalScoreSum = totalScoreSum - eval('meldCardArr'+pureSeqGroup[i])[j].value;
                                                            }    


                                                        }

                                                        console.log("testing 1", eval('meldCardArr'+pureSequence[i]));

                                                    }

                                                  
                                                  
                                                }else if( pureSequence == 1 && pureSequence+impureSequence >= 2){

                                                    for(var i = 0; i < pureSeqGroup.length; i++){

                                                        console.log('meldCardArr'+pureSeqGroup[i]);

                                                        for(var j = 0; j <  eval('meldCardArr'+pureSeqGroup[i]).length; j++){

                                                            if(eval('meldCardArr' + pureSeqGroup[i])[j].value == 1 || eval('meldCardArr' + pureSeqGroup[i])[j].value == 11 || eval('meldCardArr' + pureSeqGroup[i])[j].value == 12 || eval('meldCardArr' + pureSeqGroup[i])[j].value == 13){

                                                                totalScoreSum = totalScoreSum - 10;

                                                            }else{

                                                                totalScoreSum = totalScoreSum - eval('meldCardArr'+pureSeqGroup[i])[j].value;
                                                            }    


                                                        }

                                                        console.log("testing 2", eval('meldCardArr'+pureSeqGroup[i]));

                                                    }

                                                    

                                                    if(impureSeqGroup.length > 0){

                                                        for(var i = 0; i < impureSeqGroup.length; i++){

                                                             console.log('meldCardArr'+impureSeqGroup[i]);

                                                            for(var j = 0; j <  eval('meldCardArr'+impureSeqGroup[i]).length; j++){

                                                                if(eval('meldCardArr' + impureSeqGroup[i])[j].value == 1 || eval('meldCardArr' + impureSeqGroup[i])[j].value == 11 || eval('meldCardArr' + impureSeqGroup[i])[j].value == 12 || eval('meldCardArr' + impureSeqGroup[i])[j].value == 13 || eval('meldCardArr'+impureSeqGroup[i])[j].suit == "joker"){

                                                                    totalScoreSum = totalScoreSum - 10;

                                                                }else{

                                                                    totalScoreSum = totalScoreSum - eval('meldCardArr'+impureSeqGroup[i])[j].value;
                                                                }    


                                                            }

                                                            console.log("testing 3", eval('meldCardArr'+impureSeqGroup[i]));

                                                        }


                                                    }


                                           

                                                    if(matchingCardsGroup.length > 0){

                                                        for(var i = 0; i < matchingCardsGroup.length; i++){

                                                             console.log('meldCardArr'+matchingCardsGroup[i]);

                                                            for(var j = 0; j <  eval('meldCardArr'+matchingCardsGroup[i]).length; j++){

                                                                if(eval('meldCardArr' + matchingCardsGroup[i])[j].value == 1 || eval('meldCardArr' + matchingCardsGroup[i])[j].value == 11 || eval('meldCardArr' + matchingCardsGroup[i])[j].value == 12 || eval('meldCardArr' + matchingCardsGroup[i])[j].value == 13 || eval('meldCardArr'+matchingCardsGroup[i])[j].suit == "joker"){

                                                                    totalScoreSum = totalScoreSum - 10;

                                                                }else{

                                                                    totalScoreSum = totalScoreSum - eval('meldCardArr'+matchingCardsGroup[i])[j].value;
                                                                }    


                                                            }

                                                            console.log("testing 4", eval('meldCardArr'+matchingCardsGroup[i]));


                                                        }

                                                       


                                                    }


                                                   


                                                   
                                                  


                                                }       


                                    }

                                      console.log("new total score sum", totalScoreSum);


                                        /* Update score */      
                                        $('.result_sec').css({'display': 'block'});
                                        forceMelder(totalScoreSum, gameTypeCookie, roomIdCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie);



                                }, 3000);  
                                          


                                          
                                }, 3000);  



                            }

                        } 




            
              });


            });


            function cardDiscardPlayer(roomIdCookie, sessionKeyCookie, card){

                deckCount(roomIdCookie, sessionKeyCookie, function(count){

                        
                        if(count > 2){

                            var ajxDataCardDiscard = {'action': 'add-card-discard', roomId: roomIdCookie, playerId: userId, sessionKey: sessionKeyCookie, card:card};

                            $.ajax({
                                type: 'POST',
                                data: ajxDataCardDiscard,
                                cache: false,
                                url: 'ajax/addCardDiscard.php',
                                success: function(result){
                                   console.log("Card discarded added ======================== ", result);

                                } });

                        }else if(count <= 2){

                            /* send a signal that cards are getting re shuffled */

                            var signalReshuffle = {type: 'reshuffling', message: 'cards are getting reshuffled'};
                            connection.send(JSON.stringify(signalReshuffle));

                             $('.loading_container').show();   
                             $('.loading_container .popup .popup_cont').text("Please wait deck gets reshuffled..");



                            /* reshuffle cards */

                            reShuffleDeck(roomIdCookie, sessionKeyCookie, function(result){
                                 console.log("DECK reshuffled result ======================== ", result);

                                 setTimeout(function(){
                                      $('.loading_container').hide();
                                      $('.loading_container .popup .popup_cont').text("");

                                       $('.current-player .playingCardsDiscard .hand').html('');



                                   var signalReshuffleDone = {type: 'reshuffling-done', message: 'reshuffling finished'};
                                    connection.send(JSON.stringify(signalReshuffleDone));

                                 }, 5000);

                                


                            })



                          

                        }        

                })

               

            }


            function deckCount(roomIdCookie, sessionKeyCookie, callback){

                var ajxDataCardDeckCount = {'action': 'deckCount', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                    $.ajax({
                        type: 'POST',
                        data: ajxDataCardDeckCount,
                        cache: false,
                        url: 'ajax/deckCount.php',
                        success: function(count){
                           //console.log("DECK reshuffled result ======================== ", result);
                           console.log("DECK count result ======================== ", count);
                           callback(count);
                        } }); 

            }


               function reShuffleDeck(roomIdCookie, sessionKeyCookie, callback){

                var ajxDataCardReShuffle = {'action': 'reShuffleDeck', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                    $.ajax({
                        type: 'POST',
                        data: ajxDataCardReShuffle,
                        cache: false,
                        url: 'ajax/reShuffleDeck.php',
                        success: function(result){
                           callback(result);
                        } }); 

            }


            /* Drop Func */ 

            /*AVIK*/

            $('.drop button').click(function(){

                $('.popup_bg').show();
                $('.popup_bg .popup_with_button_cont p').text("Are you sure you want to drop?");
                $('.popup_bg #confirmBtn').removeClass('joinGameBtn');
                $('.popup_bg #confirmBtn').removeClass("okBtn");
                $('.popup_bg #confirmBtn').addClass('dropBtn');

                /* Check */

            });

             $('.popup_bg').delegate('.dropBtn', 'click', function(){

                 var roomIdCookie = $.cookie("room");
                 var sessionKeyCookie = $.trim($.cookie("sessionKey"));
                 var gameTypeCookie = $.cookie("game-type");
                 var gamePlayersCookie = $.cookie("game-players");
                 var betValueCookie = $.trim($.cookie("betValue"));

                 var chipsToTablePRCookie = $.trim($.cookie("chipsToTablePR"));
                 var currentBalanceCookie = $.trim($.cookie("currentBalance"));
                 var minBuyingPRCookie = $.trim($.cookie("minBuying"));

                $('.popup_bg').hide();
                $('.popup_bg .popup_with_button_cont p').text("");

                /* Meld my cards */

                checkIfAllMelded(function(){

                        $('.player_card_me .hand li a').removeClass('handCard');
                        $('.group_blog5 .playingCards .hand li a').removeClass('handCard');

                        $('.player_card_me .hand li a').addClass('showFoldedCard');
                        $('.group_blog .playingCards .hand li a').addClass('showFoldedCard');

                        $('.player_card_me').hide();
                        $('.group_blog5').hide();
                        $('.drop').hide();
                      
                        $('.current-player[data-user="'+userId+'"] .toss .playingCards').html('<div class="card card_2 back board_center_back showMyHand"></div>');

                        /* Update all the melded groups in db */

                        var ajxData81200 = { 'action': 'update-all-groups', roomId: roomIdCookie, player: userId, meldedGroup1: meldedGroup1, meldedGroup2: meldedGroup2, meldedGroup3: meldedGroup3, meldedGroup4: meldedGroup4, sessionKey: sessionKeyCookie};


                        $.ajax({
                            type: 'POST',
                            data: ajxData81200,
                            cache: false,
                            url: 'ajax/updateMeldedGroups.php',
                            success: function(result){
                               if($.trim(result) == "ok"){
                                    console.log("all groups updated");
                               }
                            }
                        }); 



                        /* Check if it will be drop or middle drop */
                        var ajxDataCheckDropType = {'action': 'check-drop-type', roomId: roomIdCookie, sessionKey: sessionKeyCookie, player: userId};

                            $.ajax({
                                type: 'POST',
                                data: ajxDataCheckDropType,
                                cache: false,
                                url: 'ajax/checkDropType.php',
                                success: function(count){
                                   
                                   if(count == 0 && gameTypeCookie == "score"){ // Score game drop

                                        if(gamePlayersCookie == "2"){

                                            $('.result_sec').css({'display': 'block'});

                                            wrongValidationDisplayProcess(10, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'drop');
                                        
                                        }else if(gamePlayersCookie == "6"){
                                             wrongValidationDisplayProcessSixPlayers(10, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'drop');

                                        }
                                   
                                   }else if(count == 1 && gameTypeCookie == "score"){ // Score game middle drop
                                      

                                        $('.result_sec').css({'display': 'block'});

                                        if(gamePlayersCookie == "2"){
                                            wrongValidationDisplayProcess(30, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'drop');
                                        }else if(gamePlayersCookie == "6"){
                                             wrongValidationDisplayProcessSixPlayers(30, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'drop');
                                            
                                        }
                                   }else if(count == 0 && gameTypeCookie == "101"){ // Pool game drop

                                        if(gamePlayersCookie == "2"){

                                            $('.result_sec').css({'display': 'block'});

                                            wrongValidationDisplayProcess(20, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'drop');
                                        
                                        }else if(gamePlayersCookie == "6"){
                                             wrongValidationDisplayProcessSixPlayers(20, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'drop');

                                        }
                                   
                                   }else if(count == 1 && gameTypeCookie == "101"){ // Score game middle drop
                                      

                                        $('.result_sec').css({'display': 'block'});

                                        if(gamePlayersCookie == "2"){
                                            wrongValidationDisplayProcess(40, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'drop');
                                        }else if(gamePlayersCookie == "6"){
                                             wrongValidationDisplayProcessSixPlayers(40, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'drop');
                                            
                                        }
                                   }else if(count == 0 && gameTypeCookie == "201"){ // Pool game drop

                                        if(gamePlayersCookie == "2"){

                                            $('.result_sec').css({'display': 'block'});

                                            wrongValidationDisplayProcess(25, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'drop');
                                        
                                        }else if(gamePlayersCookie == "6"){
                                             wrongValidationDisplayProcessSixPlayers(25, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'drop');

                                        }
                                   
                                   }else if(count == 1 && gameTypeCookie == "201"){ // Pool game drop

                                        if(gamePlayersCookie == "2"){

                                            $('.result_sec').css({'display': 'block'});

                                            wrongValidationDisplayProcess(50, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'drop');
                                        
                                        }else if(gamePlayersCookie == "6"){
                                             wrongValidationDisplayProcessSixPlayers(50, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'drop');

                                        }
                                   
                                   }


                                 


                                   /* Add drop user id in Drop field */

                                    // var ajxDataDropMeldersUpdate = { 'action': 'update-drop-melders', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};


                                    //    $.ajax({
                                    //         type: 'POST',
                                    //         data: ajxDataDropMeldersUpdate,
                                    //         cache: false,
                                    //         url: 'ajax/updateDropMelders.php',
                                    //         success: function(result){
                                    //             console.log("sql ", result);
                                    //     } });


                                
                                } });


                   });     


             
 


              });  




            /*  discard button click */

            $('.discard button').click(function(){

                 if( $(this).is('[disabled=disabled]') ){
                    return false;
                  }else{



                    var roomIdCookie = $.cookie("room");
                    var sessionKeyCookie = $.trim($.cookie("sessionKey")); 
                    var self = $(this);
                    var nextPlayerToSend;

                    $('.drop button').attr('disabled', true);
                    $('.drop button').css({'cursor':'default'});

                    if(cardsSelected[0] != "Joker"){

                        var cardNumber1 = cardsSelected[0].substr(0, cardsSelected[0].indexOf('OF'));
                        var cardHouse1 =  cardsSelected[0].substr(cardsSelected[0].indexOf("OF") + 2);

                         $('.me .playingCardsDiscard .hand').append('<li><span class="card card_3 rank-'+cardNumber1+' '+cardHouse1+'">'+
                             '<span class="rank">'+cardNumber1+'</span>'+
                             '<span class="suit">&'+cardHouse1+';</span>'+
                                    '</span><li>');

                    }else{

                         $('.me .playingCardsDiscard .hand').append('<li><span class="card joker card_3"></span></li>'); 
                    }    

                    $('.me .playingCardsDiscard .hand li:empty').remove();

                    

                      
                     if(cardGroupSelected){

                        /* remove from that group */

                        $('.group_blog5 .playingCards .hand li a').removeClass('activeCard');

                        if( removeCardFromGroups(cardsSelected[0], eval('group'+cardGroupSelected)) ){


                            if(cardsSelected[0] != "Joker"){

                                var cardNumber = cardsSelected[0].substr(0, cardsSelected[0].indexOf('OF'));
                                var cardHouse =  cardsSelected[0].substr(cardsSelected[0].indexOf("OF") + 2);





                              $('.group_blog5[data-group='+cardGroupSelected+'] .playingCards .hand').find('li a[data-rank='+cardNumber+'][data-suit='+cardHouse+']').eq(0).remove();

                               $('.group_blog5[data-group='+cardGroupSelected+'] .playingCards .hand').find('li a[data-rank='+cardNumber+'][data-suit='+cardHouse+']').removeClass('activeCard');

                               /* check if that is the only card in the group */
                        
                                if($('.group_blog5[data-group='+cardGroupSelected+'] .playingCards ul li a').length == 0){
                                    $('.group_blog5[data-group='+cardGroupSelected+']').remove();
                                }



                              $('.card-throw .playingCards').append('<a href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+' id="cardDeckSelectShow'+userId+'" class="cardDeckSelect noSelect">'+
                             '<div class="card card_2 rank-'+cardNumber+' '+cardHouse+'">'+
                             '<span class="rank">'+cardNumber+'</span>'+
                             '<span class="suit">&'+cardHouse+';</span>'+
                                    '</div></a>');

                            cardDiscardPlayer(roomIdCookie, sessionKeyCookie, cardsSelected[0]);

                       
           


                            }else{

                                 $('.group_blog5[data-group='+cardGroupSelected+'] .playingCards .hand').find('li a[data-rank="joker"]').eq(0).remove();

                                  $('.group_blog5[data-group='+cardGroupSelected+'] .playingCards .hand').find('li[data-rank="joker"]').removeClass('activeCard');

                            if($('.group_blog5[data-group='+cardGroupSelected+'] .playingCards ul li a').length == 0){
                                $('.group_blog5[data-group='+cardGroupSelected+']').remove();
                            }

                               
                                  $('.card-throw .playingCards').append(' <a href="javascript:;" data-rank="joker" id="cardDeckSelectShow'+userId+'" class="cardDeckSelect noSelect"><div class="card joker card_2"></div></a>');


                                  
                                     cardDiscardPlayer(roomIdCookie, sessionKeyCookie, cardsSelected[0]);

                                   
                                    

                            }    


                            /* remove card from db */

                             var ajxData700 = {'action': 'remove-card-discard', roomId: roomIdCookie, playerId: userId, cardGroup: eval('group'+cardGroupSelected), groupNos: cardGroupSelected, sessionKey: sessionKeyCookie };

                                $.ajax({
                                    type: 'POST',
                                    data: ajxData700,
                                    cache: false,
                                    url: 'ajax/removeCardDiscard.php',
                                    success: function(result){
                                        if( $.trim(result == "ok") ){
                                            console.log("card discard done");
                                            cardDiscard = 1;

                                            if(getItem(playersPlayingTemp, parseInt(userId)) ){
                                                nextPlayerToSend = getItem(playersPlayingTemp, parseInt(userId));
                                            }else{
                                                nextPlayerToSend = playersPlayingTemp[0];
                                            }


                                             /* get-player-name */

                                         var ajxData20 = {'action': 'get-player-name', player: parseInt(nextPlayerToSend)};

                                          $.ajax({
                                              type: 'POST',
                                              url: 'ajax/getPlayerName.php',
                                              cache: false,
                                              data: ajxData20,
                                              success: function(theName){
                                                  $('.game_message').html('<p>' + theName + ' will play</p>').show();
                                                
                                              } });  


                     
                                          


                                            /* send discard signal to other players  */

                                            var signal10 = {type: 'card-discarded', message: 'discard done', player: userId, cardDiscarded: cardsSelected[0], nextPlayer: nextPlayerToSend};

                                            cardsSelected.length = 0;
                                                           
                                            connection.send(JSON.stringify(signal10));


                                            self.attr('disabled', true);
                                            $('#meld'+userId).css({'display': 'none'});


                                           
                                        }
                                        

                                    }

                                });



                            
                        }




                     }else{

                        /* Cards have not been divided into group yet */

                         /* Remove the card from cardsInHand */

                         $('.player_card_me .hand li a').removeClass('activeCard');

                            if( removeCardFromGroups(cardsSelected[0], cardsInHand) ){

                                console.log(cardsInHand);


                                if(cardsSelected[0] != "Joker"){

                                    var cardNumber = cardsSelected[0].substr(0, cardsSelected[0].indexOf('OF'));
                                    var cardHouse =  cardsSelected[0].substr(cardsSelected[0].indexOf("OF") + 2);

                           

                                 $('.player_card_me .hand').find('li a[data-rank='+cardNumber+'][data-suit='+cardHouse+']').eq(0).parent().remove();

                               


                                  $('.card-throw .playingCards').append('<a href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+' id="cardDeckSelectShow'+userId+'" class="cardDeckSelect noSelect">'+
                                 '<div class="card card_2 rank-'+cardNumber+' '+cardHouse+'">'+
                                 '<span class="rank">'+cardNumber+'</span>'+
                                 '<span class="suit">&'+cardHouse+';</span>'+
                                        '</div></a>');

                                  cardDiscardPlayer(roomIdCookie, sessionKeyCookie, cardsSelected[0]);




                                }else{

                                     $('.player_card_me .hand').find('li a[data-rank="joker"]').eq(0).parent().remove();

                                    
                                      $('.card-throw .playingCards').append(' <a href="javascript:;" data-rank="joker" id="cardDeckSelectShow'+userId+'" class="cardDeckSelect noSelect"><div class="card joker card_2"></div></a>');

                                      cardDiscardPlayer(roomIdCookie, sessionKeyCookie, cardsSelected[0]);

                                    
                                }

                                var ajxData700 = {'action': 'remove-card-discard-hand', roomId: roomIdCookie, playerId: userId, cardsInHand: cardsInHand, sessionKey: sessionKeyCookie };

                                $.ajax({
                                    type: 'POST',
                                    data: ajxData700,
                                    cache: false,
                                    url: 'ajax/removeCardDiscardHand.php',
                                    success: function(result){
                                        if( $.trim(result == "ok") ){
                                            console.log("card discard done");
                                            cardDiscard = 1;


                                            if(getItem(playersPlayingTemp, parseInt(userId)) ){
                                                nextPlayerToSend = getItem(playersPlayingTemp, parseInt(userId));
                                            }else{
                                                nextPlayerToSend = playersPlayingTemp[0];
                                            }


                                             /* get-player-name */

                                         var ajxData20 = {'action': 'get-player-name', player: parseInt(nextPlayerToSend)};

                                          $.ajax({
                                              type: 'POST',
                                              url: 'ajax/getPlayerName.php',
                                              cache: false,
                                              data: ajxData20,
                                              success: function(theName){
                                                 $('.game_message').html('<p>' + theName + ' will play</p>').show();
                                                
                                              } });  


                                         // send discard signal to other players  

                                        var signal10 = {type: 'card-discarded', message: 'discard done', player: userId, cardDiscarded: cardsSelected[0], nextPlayer: nextPlayerToSend};

                                         cardsSelected.length = 0;
                                                   
                                         connection.send(JSON.stringify(signal10));

                                         self.attr('disabled', true);
                                          $('#meld'+userId).css({'display': 'none'});


                                           
                                    }
                                        

                                }

                            });



                     }


                    




                  }  




               }   


                
            });    


  


            /* group button click */

            
            $('.group_cards').click(function(){

                /* Check to see if cards have already been sorted */

                    if( !$.trim( $('.me .playingCards .hand').html() ).length == true ){
                     
                     /* Already sorted */

                     console.log("11111111111111111 selected");

                    

                  

                    var flag = 0;

                    if(group1.length == 0){
                        flag = 1;
                    }else if(group2.length == 0){
                        flag = 2;
                    }else if(group3.length == 0){
                        flag = 3;
                    }else if(group4.length == 0){
                        flag = 4;
                    }else if(group5.length == 0){
                        flag = 5;
                    }else if(group6.length == 0){
                        flag = 6;
                    }

                                   
                    console.log("flag got " + flag);

                    for(var i = 0; i < cardsSelected.length; i++){
                        
 

                            /*  segregate cardvalues and suits */

                            if(cardsSelected[i] != "Joker"){
                                var cardNumber = cardsSelected[i].substr(0, cardsSelected[i].indexOf('OF'));
                                var cardHouse =  cardsSelected[i].substr(cardsSelected[i].indexOf("OF") + 2);

                                if($.trim(cardNumber) == "J"){
                                  cardNumber = 11;
                                }else if($.trim(cardNumber) == "Q"){
                                  cardNumber = 12;
                                }else if($.trim(cardNumber) == "K"){
                                  cardNumber = 13;
                                }else if($.trim(cardNumber) == "A"){
                                  cardNumber = 1;
                                }else{
                                  cardNumber = parseInt(cardNumber);
                                }

                            }else{
                                cardNumber = 20;
                            } 


                               /* remove the card from current group */

                            // console.log("cardsselected asdasd : ", cardsSelected);

                          
                            if(removeCardFromGroups(cardsSelected[i], group1)){
                                console.log(cardsSelected[i] + " card removed group 1");
                            }else if(removeCardFromGroups(cardsSelected[i], group2)){
                                console.log(cardsSelected[i] + " card removed group 2 ");
                            }else if(removeCardFromGroups(cardsSelected[i], group3)){
                                console.log(cardsSelected[i] + " card removed group 3");
                            }else if(removeCardFromGroups(cardsSelected[i], group4)){
                                console.log(cardsSelected[i] + " card removed group 4");
                            }else if(removeCardFromGroups(cardsSelected[i], group5)){
                                console.log(cardsSelected[i] + " card removed group 5");
                            }else if(removeCardFromGroups(cardsSelected[i], group6)){
                                console.log(cardsSelected[i] + " card removed group 6");
                            }

                         
                           /* add card into array */

                         
                           
                            if(flag == 1){
                               
                                group1.push({card: cardsSelected[i], value: cardNumber});
                                console.log("Group 1 card inserted " + cardsSelected[i]);

                            }else if(flag == 2){
                                
                                group2.push({card: cardsSelected[i], value: cardNumber});
                                console.log("Group 2 card inserted " + cardsSelected[i]);

                            }else if(flag == 3){
                               
                                group3.push({card: cardsSelected[i], value: cardNumber});
                                console.log("Group 3 card inserted " + cardsSelected[i]);

                            }else if(flag == 4){
                                group4.push({card: cardsSelected[i], value: cardNumber});
                               
                                console.log("Group 4 card inserted " + cardsSelected[i]);

                            }else if(flag == 5){
                                group5.push({card: cardsSelected[i], value: cardNumber});
                               
                                console.log("Group 5 card inserted " + cardsSelected[i]);

                            }else if(flag == 6){
                                group6.push({card: cardsSelected[i], value: cardNumber});
                               
                                console.log("Group 6 card inserted " + cardsSelected[i]);


                            }



                         

                          
                           
                    
                    }


                       if(cardMelded == 1) {
                                
                            /* add meld button when a group gets added  */
                                       
                            $('.meld_group_btn button[data-button='+flag+']').show();

                   
                        } 

                     

                    
                     

                     console.log('flag set ' + flag);

                     /* Update db and display cards */

                     // updateGroups();

                     console.log("group1 " + JSON.stringify(group1));
                     console.log("group2 " + JSON.stringify(group2));
                     console.log("group3 " + JSON.stringify(group3));
                     console.log("group4 " + JSON.stringify(group4));
                     console.log("group5 " + JSON.stringify(group5));
                     console.log("group6 " + JSON.stringify(group6));



                      cardGetAndSorting(flag, true);

                      // flag = 0;

                     $('.group_btn').css({'display': 'none'});


                     cardsSelected.length = 0;

                }else{


                    console.log("2222222222222 selected");
                   
                    /*  Divide the groups 1 by 1 */


                    for(var i = 0; i < cardsSelected.length; i++){

                        console.log(cardsSelected);

                        /*  segregate cardvalues and suits */

                        if(cardsSelected[i] != "Joker"){
                            var cardNumber = cardsSelected[i].substr(0, cardsSelected[i].indexOf('OF'));
                            var cardHouse =  cardsSelected[i].substr(cardsSelected[i].indexOf("OF") + 2);

                            if($.trim(cardNumber) == "J"){
                              cardNumber = 11;
                            }else if($.trim(cardNumber) == "Q"){
                              cardNumber = 12;
                            }else if($.trim(cardNumber) == "K"){
                              cardNumber = 13;
                            }else if($.trim(cardNumber) == "A"){
                              cardNumber = 1;
                            }else{
                              cardNumber = parseInt(cardNumber);
                            }

                        }else{
                            cardNumber = 20;
                        } 
                        

                        // group1.push(cardsSelected[i]);
                        group1.push({card: cardsSelected[i], value: cardNumber});
                           

                        if(removeCardFromGroups(cardsSelected[i], cardsInHand)){
                                console.log("card removed");
                        }    
                           
                            
                    
                    }

                    /*  Swap cards in hand with group 2 */
                    for(var i = 0; i < cardsInHand.length; i++){

                     if(cardsInHand[i] != "Joker"){
                        var cardNumber = cardsInHand[i].substr(0, cardsInHand[i].indexOf('OF'));
                        var cardHouse =  cardsInHand[i].substr(cardsInHand[i].indexOf("OF") + 2);

                        if($.trim(cardNumber) == "J"){
                          cardNumber = 11;
                        }else if($.trim(cardNumber) == "Q"){
                          cardNumber = 12;
                        }else if($.trim(cardNumber) == "K"){
                          cardNumber = 13;
                        }else if($.trim(cardNumber) == "A"){
                          cardNumber = 1;
                        }else{
                          cardNumber = parseInt(cardNumber);
                        }

                    }else{
                        cardNumber = 20;
                    } 


                        // group2.push(cardsInHand[i]);
                        group2.push({card: cardsInHand[i], value: cardNumber});

                    }


                    cardsInHand.length = 0;    


                    console.log("cards in hand " + cardsInHand);
                   
                    $('.me .playingCards .hand').html("");

                    // console.log("group1 : " + JSON.stringify(group1));
                    // console.log("group2 " + JSON.stringify(group2));




                    // updateGroups();
                    cardGetAndSorting(1, false);

                     $('.group_btn').css({'display': 'none'});


                     cardsSelected.length = 0;

                     console.log("group1 " + JSON.stringify(group1));
                     console.log("group2 " + JSON.stringify(group2));
                     console.log("group3 " + JSON.stringify(group3));
                     console.log("group4 " + JSON.stringify(group4));
                     console.log("group5 " + JSON.stringify(group5));
                     console.log("group6 " + JSON.stringify(group6));


                     

                }
               
            })

          


               function updateGroupsWhileMelding(){

                    /*  Update db and set groups */

                    var roomIdCookie = $.cookie("room");
                    var sessionKeyCookie = $.trim($.cookie("sessionKey"));

                        var ajxData506 = {'action': 'update-group', roomId: roomIdCookie, playerId: userId, 
                         group1: group1, group2: group2, group3: group3, group4: group4, group5: group5, group6:group6, sessionKey: sessionKeyCookie};

                        $.ajax({
                            type: 'POST',
                            data: ajxData506,
                            cache: false,
                            url: 'ajax/updateCardGroup.php',
                            success: function(result){
                                if( $.trim(result == "ok") ){
                                    console.log("grouping done");
                                    console.log(result);
                                    $('.group_blog5').remove();


                                   cardGetAndSorting1(group1, group2, group3, group4, group5, group6);
                                
                                }
                                

                            }

                        })




            }

             function cardGetAndSorting1(group1, group2, group3, group4, group5, group6){

                 $('.group_blog5').remove();

                var $groupParent = $('.group');

                 if(group1.length !== 0){

                    var $each_group = $('<div class="group_blog5" data-group="1"></div>');
                     var $meld_group_btn = $('<div class="meld_group_btn"><button type="button" class="meld_group" data-button="1">Meld</button></div>');
                    var $playingCards = $('<div class="playingCards"></div>');
                    var $hand = $('<ul class="hand"></ul>');

                    $hand.append('<li></li>');

                   
                    for(var i = 0; i < group1.length; i++){

                        if(group1[i] != "Joker"){

                             var cardNumber = group1[i].substr(0, group1[i].indexOf('OF'));
                             var cardHouse =  group1[i].substr(group1[i].indexOf("OF") + 2);

                              var li = '<li>'+
                                '<a class="card card_2 rank-'+cardNumber+' '+cardHouse+' ui-widget-content handCard" href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+'>'+
                                '<span class="rank">'+cardNumber+'</span>'+
                                '<span class="suit">&'+cardHouse+';</span></a></li>';

                        }else{

                             var li = '<li class="card joker card_2"><a href="javascript:;" class="handCard ui-widget-content card card_2 joker" data-rank="joker"></a></li>';
                        }    


                         $hand.append(li);
                    }

                    $hand.append('<li></li>');

                     $playingCards.append($hand);
                     $each_group.append($meld_group_btn);
                     $each_group.append($playingCards);
                     $groupParent.append($each_group);



               }


                if(group2.length !== 0){

                    var $each_group = $('<div class="group_blog5" data-group="2"></div>');
                     var $meld_group_btn = $('<div class="meld_group_btn"><button type="button" class="meld_group" data-button="2">Meld</button></div>');
                    var $playingCards = $('<div class="playingCards"></div>');
                    var $hand = $('<ul class="hand"></ul>');

                    $hand.append('<li></li>');

                   
                    for(var i = 0; i < group2.length; i++){

                        if(group2[i] != "Joker"){

                             var cardNumber = group2[i].substr(0, group2[i].indexOf('OF'));
                             var cardHouse =  group2[i].substr(group2[i].indexOf("OF") + 2);

                              var li = '<li>'+
                                '<a class="card card_2 rank-'+cardNumber+' '+cardHouse+' ui-widget-content handCard" href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+'>'+
                                '<span class="rank">'+cardNumber+'</span>'+
                                '<span class="suit">&'+cardHouse+';</span></a></li>';

                        }else{

                             var li = '<li class="card joker card_2"><a href="javascript:;" class="handCard ui-widget-content card card_2 joker" data-rank="joker"></a></li>';
                        }    


                         $hand.append(li);
                    }

                    $hand.append('<li></li>');

                     $playingCards.append($hand);
                     $each_group.append($meld_group_btn);
                     $each_group.append($playingCards);
                     $groupParent.append($each_group);



               }

                if(group3.length !== 0){

                    var $each_group = $('<div class="group_blog5" data-group="3"></div>');
                     var $meld_group_btn = $('<div class="meld_group_btn"><button type="button" class="meld_group" data-button="3">Meld</button></div>');
                    var $playingCards = $('<div class="playingCards"></div>');
                    var $hand = $('<ul class="hand"></ul>');

                    $hand.append('<li></li>');

                   
                    for(var i = 0; i < group3.length; i++){

                        if(group3[i] != "Joker"){

                             var cardNumber = group3[i].substr(0, group3[i].indexOf('OF'));
                             var cardHouse =  group3[i].substr(group3[i].indexOf("OF") + 2);

                              var li = '<li>'+
                                '<a class="card card_2 rank-'+cardNumber+' '+cardHouse+' ui-widget-content handCard" href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+'>'+
                                '<span class="rank">'+cardNumber+'</span>'+
                                '<span class="suit">&'+cardHouse+';</span></a></li>';

                        }else{

                             var li = '<li class="card joker card_2"><a href="javascript:;" class="handCard ui-widget-content card card_2 joker" data-rank="joker"></a></li>';
                        }    


                         $hand.append(li);
                    }

                    $hand.append('<li></li>');

                     $playingCards.append($hand);
                     $each_group.append($meld_group_btn);
                     $each_group.append($playingCards);
                     $groupParent.append($each_group);



               }


                if(group4.length !== 0){

                    var $each_group = $('<div class="group_blog5" data-group="4"></div>');
                    var $meld_group_btn = $('<div class="meld_group_btn"><button type="button" class="meld_group" data-button="4">Meld</button></div>');
                    var $playingCards = $('<div class="playingCards"></div>');
                    var $hand = $('<ul class="hand"></ul>');

                    $hand.append('<li></li>');

                   
                    for(var i = 0; i < group4.length; i++){

                        if(group4[i] != "Joker"){

                             var cardNumber = group4[i].substr(0, group4[i].indexOf('OF'));
                             var cardHouse =  group4[i].substr(group4[i].indexOf("OF") + 2);

                              var li = '<li>'+
                                '<a class="card card_2 rank-'+cardNumber+' '+cardHouse+' ui-widget-content handCard" href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+'>'+
                                '<span class="rank">'+cardNumber+'</span>'+
                                '<span class="suit">&'+cardHouse+';</span></a></li>';

                        }else{

                             var li = '<li class="card joker card_2"><a href="javascript:;" class="handCard ui-widget-content card card_2 joker" data-rank="joker"></a></li>';
                        }    


                         $hand.append(li);
                    }

                    $hand.append('<li></li>');

                     $playingCards.append($hand);
                     $each_group.append($meld_group_btn);
                     $each_group.append($playingCards);
                     $groupParent.append($each_group);



               }


                if(group5.length !== 0){

                    var $each_group = $('<div class="group_blog5" data-group="5"></div>');
                    var $meld_group_btn = $('<div class="meld_group_btn"><button type="button" class="meld_group" data-button="5">Meld</button></div>');
                    var $playingCards = $('<div class="playingCards"></div>');
                    var $hand = $('<ul class="hand"></ul>');

                    $hand.append('<li></li>');

                   
                    for(var i = 0; i < group5.length; i++){

                        if(group5[i] != "Joker"){

                             var cardNumber = group5[i].substr(0, group5[i].indexOf('OF'));
                             var cardHouse =  group5[i].substr(group5[i].indexOf("OF") + 2);

                              var li = '<li>'+
                                '<a class="card card_2 rank-'+cardNumber+' '+cardHouse+' ui-widget-content handCard" href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+'>'+
                                '<span class="rank">'+cardNumber+'</span>'+
                                '<span class="suit">&'+cardHouse+';</span></a></li>';

                        }else{

                             var li = '<li class="card joker card_2"><a href="javascript:;" class="handCard ui-widget-content card card_2 joker" data-rank="joker"></a></li>';
                        }    


                         $hand.append(li);
                    }

                    $hand.append('<li></li>');

                     $playingCards.append($hand);
                     $each_group.append($meld_group_btn);
                     $each_group.append($playingCards);
                     $groupParent.append($each_group);



               }


                if(group6.length !== 0){

                    var $each_group = $('<div class="group_blog5" data-group="6"></div>');
                    var $meld_group_btn = $('<div class="meld_group_btn"><button type="button" class="meld_group" data-button="6">Meld</button></div>');
                    var $playingCards = $('<div class="playingCards"></div>');
                    var $hand = $('<ul class="hand"></ul>');

                    $hand.append('<li></li>');

                   
                    for(var i = 0; i < group6.length; i++){

                        if(group6[i] != "Joker"){

                             var cardNumber = group6[i].substr(0, group6[i].indexOf('OF'));
                             var cardHouse =  group6[i].substr(group6[i].indexOf("OF") + 2);

                              var li = '<li>'+
                                '<a class="card card_2 rank-'+cardNumber+' '+cardHouse+' ui-widget-content handCard" href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+'>'+
                                '<span class="rank">'+cardNumber+'</span>'+
                                '<span class="suit">&'+cardHouse+';</span></a></li>';

                        }else{

                             var li = '<li class="card joker card_2"><a href="javascript:;" class="handCard ui-widget-content card card_2 joker" data-rank="joker"></a></li>';
                        }    


                         $hand.append(li);
                    }

                    $hand.append('<li></li>');

                     $playingCards.append($hand);
                     $each_group.append($meld_group_btn);
                     $each_group.append($playingCards);
                     $groupParent.append($each_group);



               }


               if(cardMelded == 1){
                 // $('.meld_group_btn').css({'display': 'block'});

                 for(var i = 1; i < 7; i++){
                    if( eval('group'+i).length <= 1){
                        $('.meld_group_btn button[data-button="'+i+'"]').hide();
                    }else{
                        $('.meld_group_btn button[data-button="'+i+'"]').show();
                    }
                 }


               }





            }

            function updateGroups(){

                    /*  Update db and set groups */

                    var roomIdCookie = $.cookie("room");
                    var sessionKeyCookie = $.trim($.cookie("sessionKey"));

                        var ajxData506 = {'action': 'update-group', roomId: roomIdCookie,  playerId: userId, 
                         group1: group1, group2: group2, group3: group3, group4: group4, group5: group5, group6:group6, sessionKey: sessionKeyCookie};

                        $.ajax({
                            type: 'POST',
                            data: ajxData506,
                            cache: false,
                            url: 'ajax/updateCardGroup.php',
                            success: function(result){
                                if( $.trim(result == "ok") ){
                                    console.log("grouping done");
                                    console.log(result);
                                   
                                }
                                

                            }

                        })




            }


            function cardGetAndSorting(flag, sorted){

                 $('.group_blog5').remove();

                  var $groupParent = $('.group');

                   
                    if(sorted == true){ 
                        for(var i = 1; i < 7; i++){
                           if(i != flag){

                                if( eval('group'+i).length > 0){


                                    var $each_group = $('<div class="group_blog5" data-group="'+i+'"></div>');
                                    var $meld_group_btn = $('<div class="meld_group_btn"><button type="button" class="meld_group" data-button="'+i+'">Meld</button></div>');
                                    var $playingCards = $('<div class="playingCards"></div>');
                                    var $hand = $('<ul class="hand"></ul>');

                                    $hand.append('<li></li>');


                                    console.log("Group ", eval('group'+i));

                                   
                                    for(var j = 0; j < eval('group'+i).length; j++){

                                        if(eval('group'+i)[j] != "Joker"){

                                             var cardNumber = eval('group'+i)[j].substr(0, eval('group'+i)[j].indexOf('OF'));
                                             var cardHouse =  eval('group'+i)[j].substr(eval('group'+i)[j].indexOf("OF") + 2);

                                              var li = '<li>'+
                                                '<a class="card card_2 rank-'+cardNumber+' '+cardHouse+' ui-widget-content handCard" href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+'>'+
                                                '<span class="rank">'+cardNumber+'</span>'+
                                                '<span class="suit">&'+cardHouse+';</span></a></li>';

                                        }else{

                                             var li = '<li><a href="javascript:;" class="card joker card_2 handCard ui-widget-content" data-rank="joker"></a></li>';
                                        }    


                                         $hand.append(li);
                                    }

                                    $hand.append('<li></li>');

                                     $playingCards.append($hand);
                                     $each_group.append($meld_group_btn);
                                     $each_group.append($playingCards);
                                     $groupParent.append($each_group);


                                }


                           }  
                        } 
                    }else if(sorted == false){


                        var $each_group = $('<div class="group_blog5" data-group="2"></div>');
                        var $meld_group_btn = $('<div class="meld_group_btn"><button type="button" class="meld_group" data-button="2">Meld</button></div>');
                        var $playingCards = $('<div class="playingCards"></div>');
                        var $hand = $('<ul class="hand"></ul>');

                        $hand.append('<li></li>');

                        group2.sort(function(a, b){
                            return a.value - b.value;
                        });

                       
                        for(var i = 0; i < group2.length; i++){

                            if(group2[i].card != "Joker"){

                                 var cardNumber = group2[i].card.substr(0, group2[i].card.indexOf('OF'));
                                 var cardHouse =  group2[i].card.substr(group2[i].card.indexOf("OF") + 2);

                                  var li = '<li>'+
                                    '<a class="card card_2 rank-'+cardNumber+' '+cardHouse+' ui-widget-content handCard" href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+'>'+
                                    '<span class="rank">'+cardNumber+'</span>'+
                                    '<span class="suit">&'+cardHouse+';</span></a></li>';

                            }else{

                                 var li = '<li><a href="javascript:;" class="card joker card_2 handCard ui-widget-content" data-rank="joker"></a></li>';
                            }    


                             $hand.append(li);
                        }

                        $hand.append('<li></li>');

                         $playingCards.append($hand);
                         $each_group.append($meld_group_btn);
                         $each_group.append($playingCards);
                         $groupParent.append($each_group);


                    }


                    /** The new group **/


                   
                    var $each_group = $('<div class="group_blog5" data-group="'+flag+'"></div>');
                    var $meld_group_btn = $('<div class="meld_group_btn"><button type="button" class="meld_group" data-button="'+flag+'">Meld</button></div>');
                    var $playingCards = $('<div class="playingCards"></div>');
                    var $hand = $('<ul class="hand"></ul>');

                    eval('group'+flag).sort(function(a, b){
                        return a.value - b.value;
                    });

                    $hand.append('<li></li>');

                   
                    for(var i = 0; i < eval('group'+flag).length; i++){

                        if(eval('group'+flag)[i].card != "Joker"){

                             var cardNumber = eval('group'+flag)[i].card.substr(0, eval('group'+flag)[i].card.indexOf('OF'));
                             var cardHouse =  eval('group'+flag)[i].card.substr(eval('group'+flag)[i].card.indexOf("OF") + 2);

                              var li = '<li>'+
                                '<a class="card card_2 rank-'+cardNumber+' '+cardHouse+' ui-widget-content handCard" href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+'>'+
                                '<span class="rank">'+cardNumber+'</span>'+
                                '<span class="suit">&'+cardHouse+';</span></a></li>';

                        }else{

                             var li = '<li><a href="javascript:;" class="card joker card_2 handCard ui-widget-content" data-rank="joker"></a></li>';
                        }    


                         $hand.append(li);
                    }

                    $hand.append('<li></li>');

                     $playingCards.append($hand);
                     $each_group.append($meld_group_btn);
                     $each_group.append($playingCards);
                     $groupParent.append($each_group);



                 /* Removing object property index value from card groups for db update */

                     for(var i = 1; i < 7; i++){
                         for(var j = 0; j < eval('group'+i).length; j++){
                             if(typeof eval('group'+i)[0] === "object"){
                                 delete eval('group'+i)[j]["value"];
                            }
                         }
                     }


                     if(typeof group1[0] === "object"){
                        group1 = group1.map(x => x.card);
                     }

                     if(typeof group2[0] === "object"){
                        group2 = group2.map(x => x.card);
                    }

                    if(typeof group3[0] === "object"){
                        group3 = group3.map(x => x.card);
                     }

                     if(typeof group4[0] === "object"){
                        group4 = group4.map(x => x.card);
                     }

                     if(typeof group5[0] === "object"){
                        group5 = group5.map(x => x.card);
                     }

                     if(typeof group6[0] === "object"){
                        group6 = group6.map(x => x.card);
                     }   

            



                   if(cardMelded == 1){
                     // $('.meld_group_btn').css({'display': 'block'});

                     for(var i = 1; i < 7; i++){
                        if( eval('group'+i).length == 1 || eval('group'+i).length == 0){
                            $('.meld_group_btn button[data-button="'+i+'"]').hide();
                        }else{
                            $('.meld_group_btn button[data-button="'+i+'"]').show();
                        }
                     }


                   }


                     console.log("group1 " + JSON.stringify(group1));
                     console.log("group2 " + JSON.stringify(group2));
                     console.log("group3 " + JSON.stringify(group3));
                     console.log("group4 " + JSON.stringify(group4));
                     console.log("group5 " + JSON.stringify(group5));
                     console.log("group6 " + JSON.stringify(group6));



                    updateGroups();


            }


            $('.sort button').click(function(){

                 if( $(this).is('[disabled=disabled]') ){
                    return false;
                  }else{

                  $('.sort button').attr('disabled', true);   

                 var roomIdCookie = $.cookie("room");
                 var sessionKeyCookie = $.trim($.cookie("sessionKey"));

                /* get my cards */

                 var ajxData225 = {'action': 'get-cards', roomId: roomIdCookie, playerId: userId, sessionKey: sessionKeyCookie};

                 $.ajax({

                    type: 'POST',
                    url: 'ajax/getMyCards.php',
                    cache: false,
                    data: ajxData225,
                    dataType: 'json',
                    success: function(myCards){

                        console.log("card length: " + myCards.length); 

                        for(var i = 0; i < myCards.length; i++){
                        
                            

                            if(myCards[i] != "Joker"){
                                var cardNumber = myCards[i].substr(0, myCards[i].indexOf('OF'));
                                var cardHouse =  myCards[i].substr(myCards[i].indexOf("OF") + 2);

                                if($.trim(cardNumber) == "J"){
                                  cardNumber = 11;
                                }else if($.trim(cardNumber) == "Q"){
                                  cardNumber = 12;
                                }else if($.trim(cardNumber) == "K"){
                                  cardNumber = 13;
                                }else if($.trim(cardNumber) == "A"){
                                  cardNumber = 1;
                                }else{
                                  cardNumber = parseInt(cardNumber);
                                }

                            }else{
                                cardNumber = 20;
                            }

                             if(cardNumber == 20){
                               group5.push("Joker");
                            }else{

                                if(cardHouse == "spades"){
                               
                                    group1.push({card: myCards[i], value: cardNumber});
                                }else if(cardHouse == "clubs"){
                               
                                    group2.push({card: myCards[i], value: cardNumber});
                                }else if(cardHouse == "diams"){
                              
                                    group3.push({card: myCards[i], value: cardNumber});
                                }else if(cardHouse == "hearts"){
                                
                                    group4.push({card: myCards[i], value: cardNumber});
                                }


                            }

                        }

                         cardSorting();

                            console.log("group 1 ", group1);
                            console.log("group 2 ", group2);
                            console.log("group 3 ", group3);
                            console.log("group 4 ", group4);
                            console.log("group 5 ", group5);



                       

                        
                    }
                    
                });        

               }  

            });


           

            function cardSorting(){

                // console.log("group 1 AAAAAAAAAAAAA ", JSON.stringify(group1));

                var roomIdCookie = $.cookie("room");
                var sessionKeyCookie = $.trim($.cookie("sessionKey"));

                $('.me .playingCards .hand').html("");

                $('.group').css({'display' : 'block'});

                var $groupParent = $('.group');

                   if(group1.length !== 0){

                        var $each_group = $('<div class="group_blog5" data-group="1"></div>');
                        var $meld_group_btn = $('<div class="meld_group_btn"><button type="button" class="meld_group" data-button="1">Meld</button></div>');
                        var $playingCards = $('<div class="playingCards"></div>');
                        var $hand = $('<ul class="hand"></ul>');

                        group1.sort(function(a, b){
                            return a.value - b.value;
                        });

                        $hand.append('<li></li>');

                       
                        for(var i = 0; i < group1.length; i++){
                             var cardNumber = group1[i].card.substr(0, group1[i].card.indexOf('OF'));
                             var cardHouse =  group1[i].card.substr(group1[i].card.indexOf("OF") + 2);

                              var li = '<li>'+
                                '<a class="card card_2 rank-'+cardNumber+' '+cardHouse+' ui-widget-content handCard" href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+'>'+
                                '<span class="rank">'+cardNumber+'</span>'+
                                '<span class="suit">&'+cardHouse+';</span></a></li>';


                             $hand.append(li);
                        }

                         $hand.append('<li></li>');   
                         $playingCards.append($hand);
                         $each_group.append($meld_group_btn);
                         $each_group.append($playingCards);
                         $groupParent.append($each_group);



                    }

                    if(group2.length !== 0){

                        var $each_group = $('<div class="group_blog5" data-group="2"></div>');
                        var $meld_group_btn = $('<div class="meld_group_btn"><button type="button" class="meld_group" data-button="2">Meld</button></div>');
                        var $playingCards = $('<div class="playingCards"></div>');
                        var $hand = $('<ul class="hand"></ul>');

                        group2.sort(function(a, b){
                            return a.value - b.value;
                        });

                        $hand.append('<li></li>');

                        for(var i = 0; i < group2.length; i++){
                             var cardNumber = group2[i].card.substr(0, group2[i].card.indexOf('OF'));
                             var cardHouse =  group2[i].card.substr(group2[i].card.indexOf("OF") + 2);

                              

                                var li = '<li>'+
                                '<a class="card card_2 rank-'+cardNumber+' '+cardHouse+' ui-widget-content handCard ui-widget-content" href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+'>'+
                                '<span class="rank">'+cardNumber+'</span>'+
                                '<span class="suit">&'+cardHouse+';</span></a></li>';


                             $hand.append(li);

                        }


                         $hand.append('<li></li>');
                         $playingCards.append($hand);
                         $each_group.append($meld_group_btn);
                         $each_group.append($playingCards);

                         $groupParent.append($each_group);


                    }

                    if(group3.length !== 0){

                        var $each_group = $('<div class="group_blog5" data-group="3"></div>');
                        var $meld_group_btn = $('<div class="meld_group_btn"><button type="button" class="meld_group" data-button="3">Meld</button></div>');
                        var $playingCards = $('<div class="playingCards"></div>');
                        var $hand = $('<ul class="hand"></ul>');

                        group3.sort(function(a, b){
                            return a.value - b.value;
                        });

                        $hand.append('<li></li>');

                        for(var i = 0; i < group3.length; i++){
                            var cardNumber = group3[i].card.substr(0, group3[i].card.indexOf('OF'));
                            var cardHouse =  group3[i].card.substr(group3[i].card.indexOf("OF") + 2);

                            

                                var li = '<li>'+
                                '<a class="card card_2 rank-'+cardNumber+' '+cardHouse+' ui-widget-content handCard" href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+'>'+
                                '<span class="rank">'+cardNumber+'</span>'+
                                '<span class="suit">&'+cardHouse+';</span></a></li>';


                             $hand.append(li);

                        }

                         $hand.append('<li></li>');
                         $playingCards.append($hand);
                         $each_group.append($meld_group_btn);
                         $each_group.append($playingCards);

                         $groupParent.append($each_group);


                    }

                    if(group4.length !== 0){

                        var $each_group = $('<div class="group_blog5" data-group="4"></div>');
                         var $meld_group_btn = $('<div class="meld_group_btn"><button type="button" class="meld_group" data-button="4">Meld</button></div>');
                        var $playingCards = $('<div class="playingCards"></div>');
                        var $hand = $('<ul class="hand"></ul>');

                        group4.sort(function(a, b){
                            return a.value - b.value;
                        });

                        $hand.append('<li></li>');

                        for(var i = 0; i < group4.length; i++){
                            var cardNumber = group4[i].card.substr(0, group4[i].card.indexOf('OF'));
                            var cardHouse =  group4[i].card.substr(group4[i].card.indexOf("OF") + 2);

                           
                            var li = '<li>'+
                                '<a class="card card_2 rank-'+cardNumber+' '+cardHouse+' ui-widget-content handCard" href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+'>'+
                                '<span class="rank">'+cardNumber+'</span>'+
                                '<span class="suit">&'+cardHouse+';</span></a></li>';

                            $hand.append(li);

                        }

                         $hand.append('<li></li>');   
                         $playingCards.append($hand);
                         $each_group.append($meld_group_btn);
                         $each_group.append($playingCards);
                         $groupParent.append($each_group);


                    }

                    if(group5.length !== 0){

                        var $each_group = $('<div class="group_blog5" data-group="5"></div>');
                        var $meld_group_btn = $('<div class="meld_group_btn"><button type="button" class="meld_group" data-button="5">Meld</button></div>');
                        var $playingCards = $('<div class="playingCards"></div>');
                        var $hand = $('<ul class="hand"></ul>');

                       
                        $hand.append('<li></li>');

                        for(var i = 0; i < group5.length; i++){
                            
                            
                              var li = '<li><a href="javascript:;" class="handCard ui-widget-content card joker card_2" data-rank="joker"></a></li>';

                             $hand.append(li);

                        }

                         $hand.append('<li></li>');
                         $playingCards.append($hand);
                         $each_group.append($meld_group_btn);
                         $each_group.append($playingCards);

                         $groupParent.append($each_group);


                    }


                    /* Removing object property index value from card groups for db update */

                    for(var i = 1; i < 5; i++){
                        for(var j = 0; j < eval('group'+i).length; j++){
                            delete eval('group'+i)[j]["value"];
                        }


                        
                    }

                  
                    
                   group1 = group1.map(x => x.card);
                   group2 = group2.map(x => x.card);
                   group3 = group3.map(x => x.card);
                   group4 = group4.map(x => x.card);

                    console.log("group 1 prev", JSON.stringify(group1));
                    console.log("group 2 prev", JSON.stringify(group2));
                    console.log("group 3 prev", JSON.stringify(group3));
                    console.log("group 4 prev", JSON.stringify(group4));
                    console.log("group 5 prev", JSON.stringify(group5));


                   
                     /*  Update db and set groups */

                    var ajxData505 = {'action': 'update-group', roomId: roomIdCookie, playerId: userId, 
                     group1: group1, group2: group2, group3: group3, group4: group4, group5: group5, sessionKey: sessionKeyCookie};

                    $.ajax({
                        type: 'POST',
                        data: ajxData505,
                        cache: false,
                        url: 'ajax/updateCardGroup.php',
                        success: function(result){
                            if( $.trim(result == "ok") ){
                                console.log("grouping done");
                                console.log(result);
                                
                                
                            }
                            

                        }

                    })


                      



                }





                    var parentgroupRemoving;

                    $('.group').delegate('.hand','mouseover', function(){

                        var roomIdCookie = $.cookie("room");
                        var sessionKeyCookie = $.trim($.cookie("sessionKey"));
                      
                        $(this).sortable({
                            connectWith: '.group .hand',
                            start: function(event, ui){
                                console.log("starting....");
                                  
                                  parentgroupRemoving = $(ui.item).closest('.group_blog5').attr('data-group');

                                 

                            },
                            stop: function(event, ui){

                                /* get rank and house */

                                var card;
                
                                var rank = $(ui.item).find('a').attr('data-rank');
                                var suit = $(ui.item).find('a').attr('data-suit');

                                card = rank+'OF'+suit;

                                /* joker card */

                                if(!suit){
                                    card = "Joker";
                                }

                            
                                /* Remove the card from the group it exists */

                                    /* search the group */

                                    var parentGroup = $(ui.item).closest('.group_blog5').attr('data-group');

                                    /* If group changed, then remove it from previous group */

                                    if(parentGroup != parentgroupRemoving){

                                        var groupRemoval = "group"+parentgroupRemoving;
                                        var groupAdded = "group"+parentGroup;


                                        if( removeCardFromGroups(card, eval(groupRemoval)) ){
                                          

                                            
                                            // console.log('old ' + eval(groupRemoval));
                                            
                                            eval(groupAdded).push(card);


                                            /* update the database */

                                             var ajxData555 = {'action': 'update-group-drag', roomId: roomIdCookie, playerId: userId, groupRemoval: eval(groupRemoval), groupAdded: eval(groupAdded), groupRemNos: parentgroupRemoving, groupAddNos: parentGroup, sessionKey: sessionKeyCookie };

                                                $.ajax({
                                                    type: 'POST',
                                                    data: ajxData555,
                                                    cache: false,
                                                    url: 'ajax/updateCardGroupDragAndDrop.php',
                                                    success: function(result){
                                                        if( $.trim(result == "ok") ){
                                                           console.log("Swapping Success!!!");
                                                        }
                                                        

                                                    }

                                                });


                                                if($('.group_blog5[data-group='+parentgroupRemoving+'] .playingCards ul li a').length == 0){
                                                    $('.group_blog5[data-group='+parentgroupRemoving+']').remove();
                                                }



                                               

                                         /* ====== When Melded ======= */   

                                    if(cardMelded == 1){


                                        /* Hide meld button when a group gets removed or has 1 card */


                                        if(  eval(groupRemoval).length <= 1){
                                            $('.meld_group_btn button[data-button="'+parentgroupRemoving+'"]').hide();

                                        }

                                        /* Add meld button when group gets added and has atleast 2 cards */



                                        if(  eval(groupAdded).length >= 2){
                                            $('.meld_group_btn button[data-button="'+parentGroup+'"]').show();

                                            /*Lawrance*/

                                        }

                                    }


                                           
                                    }
                                    

                                }

                            }
                        })
                    });

                    $('.player_card_me').delegate('.hand', 'mouseover', function(){

                        $(this).sortable({
                            connectWith: '.player_card_me .hand',
                            // axis: "x",
                            start: function(event, ui){
                               console.log("starting...");
                            },
                            stop: function(event, ui){
                                $(ui.item).removeAttr('style');
                                // $(ui.item).css({'left': ''});
                                console.log( $(ui.item) );
                            }
                          

                        })

                        // $(this).removeAttr('style');

                    })


                    /* check if joker is pulled more than once from open deck */

                    function checkJokerPulled(roomIdCookie, sessionKeyCookie){

                        var ajxDataJP = {'action': 'get-joker-pulled', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                          $.ajax({
                                type: 'POST',
                                data: ajxDataJP,
                                cache: false,
                                url: 'ajax/getJokerCardPulledCount.php',
                                success: function(count){
                                    return count;

                                 }
                                    
                         }); 



                    }


                    function updateJokerPulledCount(roomIdCookie, sessionKeyCookie){

                          var ajxDataJPC = {'action': 'update-joker-pulled', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                          $.ajax({
                                type: 'POST',
                                data: ajxDataJPC,
                                cache: false,
                                url: 'ajax/updateJokerCardPulledCount.php',
                                success: function(count){
                                    return count;

                                 }
                                    
                                });   

                    }


                    function cardPulledOpenDeck(roomIdCookie, sessionKeyCookie, self, card, rank, suit){

                         /*  If group exists */

                        if(group1.length != 0 || group2.length != 0 || group3.length != 0 || group4.length != 0 || group5.length != 0 || group6.length != 0){


                        /* add the card to group */
                            var groupCount = 1;
                        
                             /* add the card to the last group in display */

                            var groupNumber = $('.group_blog5:last-child').attr('data-group');
                            eval('group'+groupNumber).push(card);
                            var groupAddedTo = eval('group'+groupNumber);


                            /* send the card to the db */

                               var ajxData600 = {'action': 'update-group-throwcard', roomId: roomIdCookie, playerId: userId, groupAdded: groupAddedTo, groupAddNos: groupNumber, sessionKey: sessionKeyCookie};

                              $.ajax({
                                    type: 'POST',
                                    data: ajxData600,
                                    cache: false,
                                    url: 'ajax/updateGroupThrowCard.php',
                                    success: function(result){
                                        if( $.trim(result == "ok") ){
                                        
                                            console.log("Got from deck show card!!!");



                                               /* show the card */

                                            if(card != "Joker"){

                                                
                                                  var li = '<li>'+
                                                    '<a class="card card_2 rank-'+rank+' '+suit+' ui-widget-content handCard" href="javascript:;" data-rank='+rank+' data-suit='+suit+'>'+
                                                    '<span class="rank">'+rank+'</span>'+
                                                    '<span class="suit">&'+suit+';</span></a></li>';

                                            }else{

                                                 var li = '<li><a href="javascript:;" class=" card joker card_2 handCard ui-widget-content" data-rank="joker"></a></li>';
                                            }    

                                             

                                           $('.group_blog5[data-group="'+groupNumber+'"] .playingCards .hand').append(li);

                                            self.remove();

                                            $('.cardDeckSelect').removeClass('clickable').addClass('noSelect');


                                           cardPull = 1;

                    
                                            var signal11 = {type: 'card-pulled-show-card', message: 'card pulled', player: userId, cardPulled: card};
                                                           
                                            connection.send(JSON.stringify(signal11));



                                        }
                                        

                                    }

                                })


                        }else{


                            /* Group does not exist */


                            cardsInHand.push(card);

                            console.log(card);
                            console.log(cardsInHand);

                            /* send the card to the db */

                            var ajxData600 = {'action': 'update-hand-throwcard', roomId: roomIdCookie, playerId: userId, cardsInHand: cardsInHand, sessionKey: sessionKeyCookie};

                              $.ajax({
                                    type: 'POST',
                                    data: ajxData600,
                                    cache: false,
                                    url: 'ajax/updateHandThrowCard.php',
                                    success: function(result){
                                        if( $.trim(result == "ok") ){
                                        
                                            console.log("Got from deck show card in Hand!!!");



                                               /* show the card */

                                            if(card != "Joker"){

                                                
                                                $('.player_card_me .hand').append('<li class="ui-sortable-handle">'+
                                                '<a href="javascript:;" data-rank='+rank+' data-suit='+suit+'  class="card handCard card_2 rank-'+rank+' '+suit+'">'+
                                                    '<span class="rank">'+rank+'</span>'+
                                                    '<span class="suit">&'+suit+';</span>'+    
                                                    '</a></li>');

                                            }else{

                                                $('.player_card_me .hand').append('<li class="ui-sortable-handle">'+
                                                '<a href="javascript:;" data-rank="joker" class="card handCard card_2 joker">'+ 
                                                '</a></li>');

                                            }    


                                    


                                            self.remove();

                                            $('.cardDeckSelect').removeClass('clickable').addClass('noSelect');


                                             cardPull = 1;

                                           /* Send card Pull signal to others */



                                            var signal11 = {type: 'card-pulled-show-card', message: 'card pulled', player: userId, cardPulled: card};
                                                           
                                            connection.send(JSON.stringify(signal11));



                                        }
                                        

                                    }

                                })





                        } 




                    }



                  /** ====== Get show card from the deck ====== **/

                  $('.card-throw').delegate('#cardDeckSelectShow'+userId, 'click', function(){

                        if( $(this).hasClass('clickable') ){

                            if(cardPull == 0){
                               

                                var roomIdCookie = $.cookie("room");
                                var sessionKeyCookie = $.trim($.cookie("sessionKey"));
                                var self = $(this);
                                var cardJCR;
                                

                                var flagGroup = 0;
                                var card;
                        
                                var rank = $(this).attr('data-rank');
                                var suit = $(this).attr('data-suit');

                                

                                /* joker card */

                                if(!suit){
                                    card = "Joker";
                                }else{
                                    card = rank+'OF'+suit;
                                }


                                /* Check if the card is joker */

                                /* If card pulling is not proper Joker get joker card from game*/


                                if(card != "Joker"){


                                    var ajxDataJC = {'action': 'get-joker-card', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                                          $.ajax({
                                                type: 'POST',
                                                data: ajxDataJC,
                                                cache: false,
                                                url: 'ajax/getJokerCard.php',
                                                success: function(result){
                                                    

                                                        cardJCR = result;

                                                        console.log("LOOOOK ", cardJCR ," ===========");
                                                        // alert("joker got");



                                                         if($.trim(cardJCR) != "Joker"){

                                                            var cardNumber = cardJCR.substr(0, cardJCR.indexOf('OF'));
                                                            console.log("cardnumber hehe ", cardNumber);
                                                            console.log("rank hehe ", rank);

                                                            if($.trim(cardNumber) == $.trim(rank)){

                                                                console.log("match");
                                                                // console.log("report ", checkJokerPulled(roomIdCookie, sessionKeyCookie) );
                                                                /* Pulling a joker card | wildcard */

                                                                 var ajxDataJP = {'action': 'get-joker-pulled', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                                                                  $.ajax({
                                                                        type: 'POST',
                                                                        data: ajxDataJP,
                                                                        cache: false,
                                                                        url: 'ajax/getJokerCardPulledCount.php',
                                                                        success: function(count){
                                                                            //return count;

                                                                            if(parseInt(count) >= 1){
                                                                                alert("joker already pulled");
                                                                            }else{

                                                                                /* update count */
                                                                                updateJokerPulledCount(roomIdCookie, sessionKeyCookie);

                                                                                cardPulledOpenDeck(roomIdCookie, sessionKeyCookie, self, card, rank, suit);

                                                                                



                                                                            }


                                                                         }
                                                                            
                                                                 });

                                                               


                                                            }else{

                                                                 cardPulledOpenDeck(roomIdCookie, sessionKeyCookie, self, card, rank, suit);



                                                            }

                                                        }    

                                                       



                                                     } });


                                            }else{

                                                var ajxDataJP = {'action': 'get-joker-pulled', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                                                      $.ajax({
                                                            type: 'POST',
                                                            data: ajxDataJP,
                                                            cache: false,
                                                            url: 'ajax/getJokerCardPulledCount.php',
                                                            success: function(count){
                                                                //return count;

                                                                if(parseInt(count) >= 1){
                                                                    alert("joker already pulled");
                                                                }else{

                                                                    /* update count */
                                                                    updateJokerPulledCount(roomIdCookie, sessionKeyCookie);

                                                                    cardPulledOpenDeck(roomIdCookie, sessionKeyCookie, self, card, rank, suit);

                                                                    



                                                                }


                                                             }
                                                                
                                                     });



                                            }         

                            }else{
                                return false;
                            }


                           

                        }else{
                            return false;
                        }      

                  });

                 


                 $('#cardDeckSelect'+userId).click(function(){
                    if( $(this).hasClass('clickable') ){

                        if(cardPull == 0){

                         var roomIdCookie = $.cookie("room");
                         var sessionKeyCookie = $.trim($.cookie("sessionKey"));
                         var self = $(this);
                         var flagGroup = 0;
                         var card;

                         $(this).prop('disabled', true);
                         $('.cardDeckSelect').removeClass('clickable').addClass('noSelect');
                         cardPull = 1;

                        
                       /* Get a card from the shuffled deck  */

                          var ajxData800 = {'action': 'get-card-from-deck', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                              $.ajax({
                                    type: 'POST',
                                    data: ajxData800,
                                    cache: false,
                                    dataType: 'json',
                                    url: 'ajax/getThrowCardFromShuffledDeck.php',
                                    success: function(result){

                                        card = result.card_received;

                                        

                                        console.log("card rec: " + card);
                                
                                        /*  If group exists */

                                   if(group1.length != 0 || group2.length != 0 || group3.length != 0 || group4.length != 0 || group5.length != 0 || group6.length != 0){


                                   /* add the card to group */
                                    var groupCount = 1;
                                    
                                    /* add the card to the last group in display */

                                    var groupNumber = $('.group_blog5:last-child').attr('data-group');
                                    eval('group'+groupNumber).push(card);
                                    var groupAddedTo = eval('group'+groupNumber);





                                    /* send the card to the db */

                                       var ajxData600 = {'action': 'update-group-throwcard', roomId: roomIdCookie, playerId: userId, groupAdded: groupAddedTo, groupAddNos: parseInt(groupNumber), sessionKey: sessionKeyCookie};

                                      $.ajax({
                                            type: 'POST',
                                            data: ajxData600,
                                            cache: false,
                                            url: 'ajax/updateGroupThrowCard.php',
                                            success: function(result){
                                                if( $.trim(result == "ok") ){
                                                
                                                    console.log("Got from deck show card!!!");

                                                    if(card != "Joker"){

                                                        var cardNumber = card.substr(0, card.indexOf('OF'));
                                                        var cardHouse =  card.substr(card.indexOf("OF") + 2);

                                                        
                                                          var li = '<li>'+
                                                            '<a class="card card_2 rank-'+cardNumber+' '+cardHouse+' ui-widget-content handCard" href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+'>'+
                                                            '<span class="rank">'+cardNumber+'</span>'+
                                                            '<span class="suit">&'+cardHouse+';</span></a></li>';

                                                    }else{

                                                         var li = '<li><a href="javascript:;" class="card joker card_2 handCard ui-widget-content" data-rank="joker"></a></li>';
                                                    }    


                                            $('.group_blog5[data-group="'+groupNumber+'"] .playingCards .hand').append(li);


                                           
                                                   /* Send card Pull signal to others */



                                                    var signal11 = {type: 'card-pulled-show-card', message: 'card pulled', player: userId, cardPulled: card};
                                                                   
                                                    connection.send(JSON.stringify(signal11));



                                                }
                                                

                                            }

                                        })


                                }else{


                                    /* Group does not exist */


                                    cardsInHand.push(card);

                                    console.log(card);
                                    console.log(cardsInHand);

                                    /* send the card to the db */

                                    var ajxData600 = {'action': 'update-hand-throwcard', roomId: roomIdCookie, playerId: userId, cardsInHand: cardsInHand, sessionKey: sessionKeyCookie};

                                      $.ajax({
                                            type: 'POST',
                                            data: ajxData600,
                                            cache: false,
                                            url: 'ajax/updateHandThrowCard.php',
                                            success: function(result){
                                                if( $.trim(result == "ok") ){
                                                
                                                    console.log("Got from deck show card in Hand!!!");



                                                       /* show the card */

                                                    if(card != "Joker"){

                                                         var cardNumber = card.substr(0, card.indexOf('OF'));
                                                        var cardHouse =  card.substr(card.indexOf("OF") + 2);


                                                        
                                                        $('.player_card_me .hand').append('<li class="ui-sortable-handle">'+
                                                        '<a href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+'  class="card handCard card_2 rank-'+cardNumber+' '+cardHouse+'">'+
                                                            '<span class="rank">'+cardNumber+'</span>'+
                                                            '<span class="suit">&'+cardHouse+';</span>'+    
                                                            '</a></li>');

                                                    }else{

                                                        $('.player_card_me .hand').append('<li class="ui-sortable-handle">'+
                                                        '<a href="javascript:;" data-rank="joker" class="card handCard card_2 joker">'+ 
                                                        '</a></li>');

                                                    }    

                                                    $('.cardDeckSelect').removeClass('clickable').addClass('noSelect');


                                                     cardPull = 1;

                                                   /* Send card Pull signal to others */



                                                    var signal11 = {type: 'card-pulled-show-card', message: 'card pulled', player: userId, cardPulled: card};
                                                                   
                                                    connection.send(JSON.stringify(signal11));



                                                }
                                                

                                            }

                                        });





                                }          

                                        

                            }
                                    
                        });         


                    }else{
                        
                        return false;
                    
                    }

                   }else{
                    return false;
                   } 

                 })

                  
         
         });

        
  </script>

<script type="text/javascript">
$(document).ready(function(){
    $('.launch-modal').click(function(){
        $('#myModal').modal({
            backdrop: 'static'
        });
    }); 
});


</script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.launch-modal-2').click(function(){
            $('#myModal').modal({
                backdrop: 'static'
            });
        });

           // sudip
    $( "#modal_login" ).on('click', function() {
        var username = $('#username').val();
        var password = $('#password').val();
        // var ajxData = {'ajax': 'TRUE', username: username, password: password};
        var ajxData = 'ajax=TRUE&username='+username+'&password='+password;
             $.ajax({
              type: 'POST',
              url: 'ajax/ajaxlogin.php',
              cache: false,
              data: ajxData,
              dataType: 'JSON',
              success: function(result){
                if(result.SUCCESS == 'YES'){
                    alert('Successfuly Login..');
                    $('#myModal1').css('display','none');
                    location.reload();
                }
                if(result.SUCCESS == 'NO'){
                    alert('Authentication failed..'); 
                }
                // console.log(result);

         } }); 
    });

    // ajax logout
    $("#logout" ).on('click',function() {
        var ajxData = 'ajax=TRUE';
             $.ajax({
              type: 'POST',
              url: 'ajax/ajaxlogout.php',
              cache: false,
              data: ajxData,
              dataType: 'JSON',
              success: function(result){
                if(result.SUCCESS == 'YES'){
                    alert('Successfuly Logout..');
                    location.reload();
                }
            } 
        }); 
    });


    });
</script>
<!-- sudip -->
<?php
    if($_REQUEST['id']=='') { 
        if(!isset($_SESSION['user_id'])) {
?>
        <script type="text/javascript">
           $('#myModal1').modal({
            backdrop: 'static'
        });
        </script>
<?php        
  
        }
    } 
 ?>

 <!-- custom scrollbar plugin -->
<script src="js/jquery.mCustomScrollbar.concat.min.js"></script>           
<script>
    (function($){
        $(window).on("load",function(){
            $(".table-list").mCustomScrollbar({
                setHeight:350,
                theme:"dark-3"
            });
        });
    })(jQuery);
</script>
<script>
  

    $(document).ready(function(){
      setInterval(function(){
         // alert(1);
    var ajxData = 'ajax=TRUE';
                $.ajax({
                 type: 'POST',
                 url: 'ajax/ajaxtotalplayer.php',
                 cache: false,
                 data: ajxData,
                 dataType: 'html',
                 success: function(result){
                 $('#total_player').html(result);
               } 
           });

      },30000);
    });


    $(document).ready(function(){

        getTotalPlayerPerGame();

      setInterval(function(){
            getTotalPlayerPerGame();
      },10000);
    });

    function getTotalPlayerPerGame(){     
        
        var ajxData = {'action': 'fetch_count'};

                    $.ajax({
                     type: 'POST',
                     url: 'ajax/ajaxtableplayer.php',
                     cache: false,
                     data: ajxData,
                     dataType: 'JSON',
                     success: function(result){
                    
                      // var result = JSON.parse(result);   
                    
                     // console.log("aasdasdasd ", result);
                    

                     for(var i = 0; i < result.length; i++){
                        //console.log("test ", result[i][0]);
                        $('.table-striped #player'+parseInt(result[i]['gameId'])+' .totalplayercount').text(result[i]['count']);
                        
                     }

                    
                   
                   } 
               });




      }       

   </script>
   <script>
        (function($){
            $(window).on("load",function(){             
                
                
                $(".content-5, .content-4, .content-3, .content-2, .content-1, .content-me").mCustomScrollbar({
                    axis:"x",
                    theme:"dark-thin",
                    autoExpandScrollbar:true,
                    advanced:{autoExpandHorizontalScroll:true}
                });
            });
        })(jQuery);
    </script>

   
</body>
</html>