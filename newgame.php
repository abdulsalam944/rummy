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
   <link rel="stylesheet" href="css/offline-theme-default.css" />

   <!-- custom scrollbar stylesheet -->
   <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">
   <script src="js/jquery.min.js"></script>
   <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
   <script type="text/javascript" src="js/offline.min.js"></script> 
 <!-- <script type="text/javascript" src="js/offline-simulate-ui.js"></script> -->
  

   <script>
    $(document).ready(function(){

        var rejoinCookie = $.cookie("rejoin");
        var rejoinCookiePR = $.cookie("rejoinPR");
        
        if(!rejoinCookie){
            $.cookie("rejoin", "0");
        }

        if(!rejoinCookiePR){
          $.cookie("rejoinPR", "0");
        }
        
        if($.trim(rejoinCookie) == "0"){
              
              $('.room1').css({ display: 'block'});
              $('.room2').css({ display: 'none'});

        }else if($.trim(rejoinCookie) == "1"){

           $('.room1').css({ display: 'none'});
           $('.room2').css({ display: 'block'});

           // alert("hey");

        }

        if($.trim(rejoinCookiePR) == "0"){

          $('.room1').css({ display: 'block'});
          $('.room2').css({ display: 'none'});
        
        }else if($.trim(rejoinCookiePR) == "1"){

           $('.room1').css({ display: 'none'});
           $('.room2').css({ display: 'block'});

        }  


        console.log("rejoin cookie pr ", rejoinCookiePR);

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
<body onbeforeunload="ConfirmClose()" onunload="HandleOnClose()">

    <input type="hidden" id="roomIdHidden">
    <input type="hidden" id="gameTypeHidden">
    <input type="hidden" id="gamePlayersHidden">
    <input type="hidden" id="betValueHidden">

       

       <div class="room1" style="display: none;">

           <div class="connectionOverlay">
            <div id="text">
                <p>Your internet connection is slow. It is not sufficient to play this game properly. Please wait for sometime..</p>
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
                        <li class="player-total"><?php if(!empty($playercount)){ echo $playercount." PLAYERS"; }else{ echo "0 PLAYER"; }  ?> </li>
                        <li class="table-total"><?php echo @$totaltablenumber;?> TABLE</li>
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
                            <div class="pull-left bonus-point">
                                
                                <h2>Bonus</h2>
                                <p><?php echo $bonusamt; ?><!--No bonus pending--></p>
                     
                            </div>
                            <a href="<?php echo $actual_link; ?>?id=buychips" target="_blank">
                            <div class="bye_real_chips">
                                <div class="bye_real_chips_inner">
                                    <span>
                                        BUY<br>
                                        <small>REAL CHIPS</small>
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
            <a href="#" class="c-tabs-nav__link play-for-cash is-active">
            
            <span>PLAY FOR CASH</span>
          </a>
          <a href="#" class="c-tabs-nav__link tournaments">
            
            <span>TOURNAMENTS</span>
          </a>
                                      
          <a href="#" class="c-tabs-nav__link oneshot">
            
            <span>ONESHOT</span>
          </a>
          <a href="#" class="c-tabs-nav__link private-table">
            
            <span>PRIVATE TABLE</span>
          </a>
          
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
                                           
                                           <tr id="player<?php echo $sqlNamePoints_value['id']; ?>">
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
            <li id="tabb1">Free Tournaments</li>
            <li id="tabb1">Cash Tournaments</li>
        </ul>
        <div class="contents marginbot">

            <div id="contentb1" class="tabscontent" style="display:block;">
                <br>
                <h4 style="color:#fff;">Coming Soon</h4>
            </div>
            
        </div>
    </div>
                      </div>
                  </div>
<!--            <div class="col-xs-12 col-sm-3 col-md-3">
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
            </div>-->
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
            <li id="tabc1">Oneshot</li>
        </ul>
        <div class="contents marginbot">
            <div id="contentc1" class="tabscontent" style="display:block;">
                <br>
                <h4 style="color:#fff;">Coming Soon</h4>
            </div>
        </div>
    </div>
                      </div>
                  </div>
<!--            <div class="col-xs-12 col-sm-3 col-md-3">
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
            </div>-->
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
            <li id="tabd1">Private Table</li>
        </ul>
        <div class="contents marginbot">

            <div id="contentd1" class="tabscontent" style="display:block;">
                <br>
                <h4 style="color:#fff;">Coming Soon</h4>
            </div>
        </div>
    </div>
                      </div>
                  </div>
<!--            <div class="col-xs-12 col-sm-3 col-md-3">
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
            </div>-->
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

             <div class="popup_rejoin_back_disconnected">
                <div class="popup_with_button">
                    <div class="popup_with_button_cont text-center">
                        <p>Click on rejoin when you are connected.</p>
                    </div>
                    <div class="popup_with_button_footer">
                        <button id="rejoinOnConnected" disabled="disabled">Rejoin</button>
                    </div>
                </div>
            </div>

            <div class="connectionOverlay2">
                <div id="text">
                    <p>Your internet connection is slow. You are in autoplay mode now</p>
                </div>
           </div>

           <div class="connectionOverlay3">
                <div id="text">
                    <p>Your internet connection is very slow. Unfortunately we are taking you back to the lobby.</p>
                </div>
           </div>        
            
            <div class="padding_top_9">
            <div class="container">



                <div class="game_top game_info">
                    <a href="#" class="pull-left" id="game_name" data-placement="bottom" data-toggle="tooltip" title="Game Name"></a>
                    <div class="dollar" id="game_bet">
                        <div style="padding:0 10px;" data-placement="bottom" data-toggle="tooltip" title="Game Bet">
                          <img src="images/dollar_icon.png" alt=""> 
                          <span></span>
                        </div>
                        <div style="padding: 0 10px; border-left:1px solid #fff;" id="game_prize_money" data-placement="bottom" data-toggle="tooltip" title="Prize Money"></div>
                    </div>
                   
                    

                    <ul class="game_top_link pull-right">
                      <li><a href="<?php echo $actual_link; ?>?id=referinvite" data-placement="bottom" target="_blank" data-toggle="tooltip" title="Refer a friend" ><img src="images/reffer.png" alt=""></a></li>
                     
                      <li><a href="<?php echo $actual_link; ?>?id=support" target="_blank" data-placement="bottom" data-toggle="tooltip" title="How to play"><img src="images/how_to_play.png" alt=""></a></li>
                      
                      <li><a href="#" data-toggle="tooltip" data-placement="bottom" title="Setting"><img src="images/setting.png" alt=""></a></li>
                      <li><a href="javascript:;" class="leave_table_btn" data-placement="bottom" data-toggle="tooltip" title="Leave Table"><img src="images/home.png" alt=""></a></li>
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
                    <div class="leave_table_div"><a class="leave_table_btn_pr">Leave table</a></div>
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
                            <div class="card_submit_time"></div>
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
                                <div class="player_name_top" id="score">0</div>
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
                            <div class="card_submit_time"></div>
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
                                <div class="player_name_top" id="score">0</div>
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
                            <div class="card_submit_time"></div>
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
                                <div class="player_name_top" id="score">0</div>
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
                            <div class="card_submit_time"></div>
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
                                <div class="player_name_top" id="score">0</div>
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
                            <div class="card_submit_time"></div>
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
                                <div class="player_name_top" id="score">0</div>
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


                     <div class="sort" id="sort<?php echo $user_id; ?>" style="display:none;">
                        <button type="button">Sort</button>
                     </div>

                     <div class="discard" id="discard" style="display:none;"> 
                        <button type="button" disabled>Discard</button> 
                     </div>

                     <div class="game_deal">
                
   
                        <label class="control control--radio dealMeOut">Do not deal next game
                          <input type="radio" class="radio_btn" name="opt" value="dealmeout">
                          <div class="control__indicator"></div>
                        </label>
                        
                        <label class="control control--radio dropAndGo">Drop &amp; Go
                          <input type="radio" class="radio_btn" name="opt" value="dropandgo">
                          <div class="control__indicator"></div>
                        </label>
    
 
                     </div>
                     <div class="game_deal_2">
                
   
                        <label class="control control--checkbox dealMeOut">Do not deal next game
                          <input type="checkbox" id="check_dealMeOut" value="dealmeout">
                          <div class="control__indicator"></div>
                        </label>
                        
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


                      <!--<div class="group_btn" style="display: none;"><button type="button" class="group_cards">Group</button></div>-->

                      <div class="group"></div>



                    <div class="me current-player" data-user="<?php echo $user_id; ?>">
                            <div class="card_submit_time"></div>
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
                                <div class="player_name_top" id="score">0</div>
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
                                        <li id="counter"></li>
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
                    <div class="pull-right" id="table_id" data-placement="bottom" data-toggle="tooltip" title="Game Id">
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

    <div class="modal fade login_modal in" id="myModal" style="z-index: 99999;">
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

   <div class="popup_play_again">
        <div class="popup_with_button">
            <div class="popup_with_button_cont text-center">
                <p>Do you want to play_again?</p>
            </div>
            <div class="popup_with_button_footer">
            <button id="playAgainBtn">Rejoin</button>
            <button class="goToLobbyBtn">Go to Lobby</button>
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
            <div class="popup_with_button_cont text-center popup_counter">
                <p></p>
            </div>
        </div>
   </div>

   <div class="popup_leave">
        <div class="popup_with_button">
            <div class="popup_with_button_cont text-center">
                <p>Are you sure you want to leave table?</p>
            </div>
            <div class="popup_with_button_footer">
            <button id="confirmBtn">Ok</button>
            <button class="cancelBtn">Cancel</button>
            </div>
        </div>
   </div>

   <div class="popup_leave_second">
        <div class="popup_with_button">
            <div class="popup_with_button_cont text-center">
                <p>Are you sure you want to leave table?</p>
            </div>
            <div class="popup_with_button_footer">
            <button id="confirmBtn">Ok</button>
            <button class="cancelBtn">Cancel</button>
            </div>
        </div>
   </div>

    <div class="loading_container">
        <div class="popup">
            <div class="popup_cont text-center"></div>
        </div>
    </div>

    <script>
        
    </script>

    

    <script src="js/jquery-ui.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
    <script type="text/javascript" src="js/tytabs.jquery.min.js"></script>

    <script src="webrtc/RTCMulticonnection.js"></script>
    <script src="http://134.119.221.139:8080/socket.io/socket.io.js"></script>
    <script src="webrtc/gumAdapter.js"></script>
 
  
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.js"></script>




     <!-- custom scrollbar plugin -->
    <script src="js/jquery.mCustomScrollbar.concat.min.js"></script> 
    <script src="js/tabs.js"></script>
    <!--<script type="text/javascript" src="js/checkConnection.js"></script>-->

     <!-- Added by abdul on 2/5/2017 -->
    <script src="js/socket.io.js"></script>
    <!-- / -->

    <script>

    /*
    window.addEventListener("beforeunload", function(event) {
        event.returnValue = "Write something clever here..";
    });
    */

    

    /** ====    Check for internet speed           =======  **/

    $(document).ready(function(){
        var rejoinCookie = $.cookie("rejoin");
        var rejoinCookiePR = $.cookie("rejoinPR");
        var roomCreatedCookie = $.cookie("roomCreated");

        if(!roomCreatedCookie){
            $.cookie("roomCreated", "0");
        }

        $.cookie("rejoinReqCookie", "0"); 

        if($.trim(rejoinCookie) == "0" && $.trim(rejoinCookiePR) == "0" && $.trim(roomCreatedCookie) == "0"){

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
            $.cookie("connectionIssue", 0);

        }   
    });


   /** ========= Game Algorithm ============== */

 
    var userId = "<?php echo $user_id; ?>";
    var name = "<?php echo $displayName; ?>";

    var connection = new RTCMultiConnection();
    var sessionName = "<?php echo $room; ?>";   






    var direction;
    var _session;
    var splittedSession;

    var session = {};
    var maxParticipantsAllowed;  


    var counter = 10;
    var playersPlaying = [];
    var playersPlayingTemp = [];
    var playersPlayingNextRound = [];
    var playersPlayingWholeGame = [];
    var wrongMeldersArray = [];
    var cardsInHand = [];

    var nextPlayerName;
    var nextPlayerName1;
    var intervalCounter;


    /** ==== Melding Variables : Needed while melding ====== **/

    /* Joker card retrieve while melding */
    var jokerValue;

    /* Meld Objects inside: isSeq, isPure etc */
    var meldCardEvaluator1 = [], 
    meldCardEvaluator2 = [], 
    meldCardEvaluator3 = [], 
    meldCardEvaluator4 = [], 
    meldCardEvaluator5 = [];

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
    meldCardArr4 = [],
    meldCardArr5 = [];    

    var victimGroups = []; /* For finding groups whose cards are not in a proper grouping */

    var testingFlag = 0;
    var gameStatusFlag = '';
    var gameStatusArray = [];

    /** ===== Receiving all the Signals ===== */ 

    var tossFlag = 0;
    var selectJokerFlag = 0;
    var throwCardFlag = 0;
    var dealCardFlag = 0;
    var playerCounterFlag = 0;



    var cardMeldingIntervalCounter;
    var cardGotPulled = '';
    var cardSubmitted = 0;
    var rejoinGameCounter;
    var nextGameCounter = 15;
    var parentgroupRemoving;



    var dissconnectedUsers = [];

    </script>

    <script>
     function removeCardFromGroups(card, cardArray){

            var index = cardArray.indexOf(card);

            if (index > -1) {
                cardArray.splice(index, 1);
                return true;
            }


        }
    </script>
    <script type="text/javascript">
        
        var currentUsersId;
        var socketEventName = "chat message";
        var roomName;
        //CONNECTION
        var socket;






        var userid;


        socket = io('http://134.119.221.139:8080');
        socket.on('connect', function() {            
              
            currentUsersId = socket.io.engine.id;

            //reconnect
            if(!userid){ 
               userid=socket.io.engine.id; 
            }
            
            /*var msgToSend = {room:roomId,type:'code',msg:'re-connect',user:userid,oldid:userid,newid:socket.io.engine.id};
              socket.emit(eventName, JSON.stringify(msgToSend));  
*/

              var msgToSend = {room:roomName, type: 'code', msg: 're-connect',oldid:userid,newid:socket.io.engine.id};
              socket.emit(socketEventName, JSON.stringify(msgToSend));


/*
            
            thatUserId = socket.io.engine.id;

            
            


              // console.log('my_user_id:',userid);
              //brodcast connection message

              var msgToSend = {room:roomId,type:'code',msg:'re-connect',user:userid,oldid:userid,newid:socket.io.engine.id};
              socket.emit(eventName, JSON.stringify(msgToSend));  
            */

              // var msgToSend = {room:roomId,type:'code',msg:'re-connect',user:userid,oldid:userid,newid:socket.io.engine.id};
              // socket.emit(eventName, JSON.stringify(msgToSend));  
            

        });
        function ConnectSocket(){            
            socket.emit('joinRoom', 'Connected.');
        }


        

        $(window).unload(function () {
           $.ajax({
             type: 'GET',
             async: false,
             url: 'ajax/makeUserDissconnect.php?userId='+userId+'&sessionId='+$.trim($.cookie("sessionKey"))
           });
        });

        function checkDissconnected(dissconnectedUsers,playersPlaying,nextPlayerId){
          console.log('checkDissconnected : this function is called.');
          console.log(dissconnectedUsers,nextPlayerId);
          console.log(dissconnectedUsers.indexOf(nextPlayerId));
            var index = dissconnectedUsers.indexOf(nextPlayerId);
            if(index>=0){
                console.log('Found in dissconnected members.');
                var nextPlrId = getNextUserId(playersPlaying,nextPlayerId);
                console.log('Check is who is next : ',parseInt(userId),nextPlrId);
                var crntUser = parseInt(userId.trim());
                var nxtUsr = parseInt(nextPlrId);
                if(crntUser==nxtUsr){
                  alert('Last player is dissconnected, I will play now.');
                }else{
                  alert('Last player is dissconnected, User id '+parseInt($nextPlayersId)+' will play now.');
                }
            }
        }

        function getNextUserId(playersPlaying, nextPlayerId){
          console.log('Next player called.');
          var temp_totalUserCount = (playersPlaying.length - 1);
          var temp_curuserPos = playersPlaying.indexOf(nextPlayerId);      
          var temp_nextUser = "";
          //checkIfLastUser
          if(temp_curuserPos >= temp_totalUserCount){
            temp_nextUser = playersPlaying[0];

          }else{
            ++temp_curuserPos;
            temp_nextUser = playersPlaying[temp_curuserPos];
          }
          console.log('Next player is : '+temp_nextUser)
          return temp_nextUser;
        }

    </script>

    <script src="js/game/counters.js"></script>
    
    <script src="js/game/customFunctions.js"></script>
    <script src="js/game/signalReceived.js"></script>
    <script src="js/game/leaveTableFunction.js"></script>
    <script src="js/game/gameAlgo.js"></script>
    <script src="js/game/cardPull.js"></script>
    <script src="js/game/sortAndGrouping.js"></script>
    <script src="js/game/discardAndMelding.js"></script>
    <script src="js/game/clickFunctions.js"></script>
    <script src="js/game/meldingAfterFunctions.js"></script>
    <script src="js/game/gameResultFunctions.js"></script>
    <script src="js/game/cardDragAndDrop.js"></script>
    <script src="js/game/gameJoiningFunctions.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/game/leaveTableOnDisconnection.js"></script>




<script>      
    
   

         // $(function(){



          /** ======= JOIN ============ */

            var sessions = {};
            var sessionArray = [];    

            //commented by abdul
            /* connection.onNewSession = function(session) {
            
                  $('.join').attr('disabled', 'disabled');
                  connection.onNewSession = function() {};
                  connection.join(session);

             }; 

           */




              


         


            /** ====== When Connection Closes ====== */

        /*connection.onleave = function(e) {
            console.log('Data connection is closed between you and ' + e.userid);

        };*/


        // connection.ondisconnected = function(event) { //this line changed by abdul
        function disconnected(event) {

           // if(connection.isInitiator) return; // skip below code for Host
             var roomIdCookie = $.cookie("room");
             var sessionKeyCookie = $.trim($.cookie("sessionKey"));

              intervalCounter = window.clearInterval(intervalCounter);
              var nextPlayer;
            
              // var remoteUser = connection.peers[event.userid];


              console.log("DISCONNECTION User newww : ", event);

              //var remoteUser.userid = event;

              var ajaxGetConnectionId = {'action': 'get-user-id', roomId: roomIdCookie, sessionKey: sessionKeyCookie, playerId: event};

                $.ajax({
                  type: 'POST',
                  url: 'ajax/getUserId.php',
                  cache: false,
                  data: ajaxGetConnectionId,
                  success: function(userIdReceived){

                    console.log("USER ID RECEIVED ", userIdReceived);

                    if(removeCardFromGroups(parseInt(userIdReceived), playersPlayingTemp)){
                        console.log("player removed ", playersPlayingTemp);

                        playersPlaying = playersPlayingTemp.slice();

                          $.cookie("creator", null);
                          $.removeCookie("creator");

                         
                            if(playersPlayingTemp[0] == userId){
                                $.cookie("creator", "1");
                            }
                                
                            setTimeout(function(){

                                var creatorCookie = $.cookie("creator");


                                    /* Check if only 1 player is there in the table  */

                                    var ajxDataCheckPlayerCount = {'action': 'check-player-count', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                                    $.ajax({
                                      type: 'POST',
                                      url: 'ajax/checkPlayerCount.php',
                                      cache: false,
                                      data: ajxDataCheckPlayerCount,
                                      success: function(count){
                                        console.log(count);

                                        if(count <= 1){

                                            /* delete players and room table */

                                            var ajxDataDeleteRoom = {'action': 'delete-room', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                                              $.ajax({
                                                  type: 'POST',
                                                  url: 'ajax/deleteRoom.php',
                                                  cache: false,
                                                  data: ajxDataDeleteRoom,
                                                  success: function(result){ 
                                                     console.log(result);


                                                     if($.trim(result) == "ok"){
                                                        $.cookie("roomCreated", "0");
                                                        location.reload();
                                                     }

                                                  } });  


                                        }else{


                                            if(creatorCookie){ 
 

                                                   checkIfAllMelded(function(){

                                                      /* Update all the melded groups in db */

                                                          var ajxData81200 = { 'action': 'update-all-groups', roomId: roomIdCookie, player: userIdReceived, meldedGroup1: meldedGroup1, meldedGroup2: meldedGroup2, meldedGroup3: meldedGroup3, meldedGroup4: meldedGroup4, sessionKey: sessionKeyCookie};


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


                                                 /*  Update player gamedata */
                                                  var ajxData704 = {'action': 'update-my-status', roomId: roomIdCookie, player: userIdReceived, sessionKey: sessionKeyCookie};

                                                  $.ajax({
                                                    type: 'POST',
                                                    data: ajxData704,
                                                    cache: false,
                                                    url: 'ajax/updateMyStatus.php',
                                                    success: function(results){
                                                       
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


                                                   


                                          /* Check if I'm the next player */

                                              var ajxData505 = {'action': 'get-player-turn', roomId: roomIdCookie, sessionKey: sessionKeyCookie};


                                                $.ajax({
                                                    type: 'POST',
                                                     url: 'ajax/getCurrentPlayer.php',
                                                    cache: false,
                                                    data: ajxData505,
                                                    success: function(player){

                                                        if(parseInt(player) == parseInt(userIdReceived)){


                                                          if(getItem(playersPlayingTemp, parseInt(userIdReceived)) ){

                                                                nextPlayer = getItem(playersPlayingTemp, parseInt(userIdReceived));
                                                                   
                                                              }else{
                                                                nextPlayer = playersPlayingTemp[0];
                                                                   
                                                              }

                                                              console.log("nextplayer ", nextPlayer);

                                                              var ajxData260 = {'action': 'current-player', roomId: roomIdCookie, player: parseInt(nextPlayer), sessionKey: sessionKeyCookie };

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


                                                            
                                                    } }); 
                                  

                                            } 

                                        }


                                   } });    

                               
                                  setTimeout(function(){
                                    if(creatorCookie){
                                        //alert("ok");
                                      leaveTableOnDisconnection(userIdReceived, nextPlayer);
                                    }    
                                }, 6000);          

                                    
                            }, 5000); 



                                }
                        } });  
            };



       



            /** ======= When Connection opens up ===== */

             // var connectionOpenFlag = 0;

          var onOpen = function(){

            console.log('OnOpen Called');

             var onOpenHitCookie = $.cookie("onOpenHit");
             console.log("connection open called!");

              console.log("sessions: " + JSON.stringify(sessions));

                    $.cookie("rejoin", "0");
                    $.cookie("rejoinPR", "0");
                    $.cookie("connectionIssue", 0);
                   

                    
                     
            
                   /* Check player count */
                   var roomIdCookie = $.cookie("room");
                   var gamePlayersCookie = $.cookie("game-players");
                   var creatorCookie = $.cookie("creator");
                   var sessionKeyCookie = $.trim($.cookie("sessionKey"));
                   var betValueCookie = $.trim($.cookie("betValue"));
                   var gameTypeCookie = $.trim($.cookie("game-type"));    


                   if(gameTypeCookie == "score"){
                     $('.result_sec .leave_table_div').show();
                   }     


                    var ajxData04 = {'action': 'player-count', roomId: roomIdCookie, gamePlayers: gamePlayersCookie, sessionKey: sessionKeyCookie};

                    roomName = sessionKeyCookie;

                    console.log(ajxData04);

                     $.ajax({
                      type: 'POST',
                      url: 'ajax/getPlayerCount.php',
                      cache: false,
                      data: ajxData04,
                      success: function(result){

                        console.log("BLAHHHHH ", result);

                        if($.trim(result) == "ok"){


                                    
                                 // $('.current-player #score').text("0");
                                    
                           
                                    var ajxData06 = {'action': 'get-players', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                                     $.ajax({
                                        type: 'POST',
                                        url: 'ajax/getPlayers.php',
                                        cache: false,
                                        data: ajxData06,
                                        dataType: "json",
                                        success: function(players){
                                            console.log('Players in this room : '+players);
                                             playersPlaying.length = 0;
                                             playersPlayingTemp.length = 0;
                                             playersPlayingWholeGame.length = 0;

                                            for(var i = 0; i < players.length; i++){
                                                playersPlaying.push(parseInt(players[i]));
                                               
                                            }

                                            playersPlaying.sort(function(a, b){
                                                return a - b;
                                            });

                                            playersPlayingTemp = playersPlaying.slice();
                                            playersPlayingWholeGame = playersPlaying.slice();

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


                                                    var ajxData000111 = {'action': 'chip-deduct', roomId: roomIdCookie, user: userId, sessionKey: sessionKeyCookie, chip: betValueCookie, rejoin:0};

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

                                                var signal1 = {room:roomName, type: 'toss-shuffle', message: 'deck shuffled for toss'};
                                               // connection.send(JSON.stringify(signal1));
                                                socket.emit(socketEventName, JSON.stringify(signal1));

                                            });  




                                        }
                                     });       



                        }else if($.trim(result) == "wait"){

                                    /* set the score */
                                    // $('.current-player #score').text("0");


                          
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
                                            playersPlayingWholeGame.length = 0;

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

                                            }

                                             playersPlaying.sort(function(a, b){
                                                return a - b;
                                            });

                                              playersPlayingTemp = playersPlaying.slice();
                                              playersPlayingWholeGame = playersPlaying.slice();

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
                                                         sixPlCounter = 30;
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






                                                   } 

                                               }










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



                                                        

                                                        if(playersPlayingTemp.length == 2){
                                                            $('.game_deal_2').show();
                                                            $('.game_deal').hide();
                                                        }else if(playersPlayingTemp.length > 2){
                                                            $('.game_deal').show();
                                                            $('.game_deal_2').hide();
                                                        }



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
                                                     



                                                    var signal1 = {room:roomName, type: 'toss-shuffle', message: 'deck shuffled for toss'};
                                                   // connection.send(JSON.stringify(signal1));  
                                                    socket.emit(socketEventName, JSON.stringify(signal1));
                                                });

                                             } });   

                                          } 


                                        }

                                     });   

                                }

                        } });   


                           
              
            }; 




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
            
            $('.popup_bg .cancelBtn, .popup_leave .cancelBtn').click(function(){
                $('.popup_bg').hide();
                $('.popup_leave').hide();
                $('.popup_bg .popup_with_button_cont p').text("");
                


            });    


            /* on clicking leave table */

            $('.leave_table_btn').click(function(){
              if( $(this).is('[disabled=disabled]') ){
                  return false;
               }else{

                 if( (cardPull == 0) || (cardPull == 1 && cardDiscard == 1)){ 
                    $('.popup_leave').show();
                }


              }
            });

             $('.leave_table_btn_pr').click(function(){
              if( $(this).is('[disabled=disabled]') ){
                  return false;
               }else{

                 if( (cardPull == 0) || (cardPull == 1 && cardDiscard == 1)){ 
                    $('.popup_leave_second').show();
                }


              }
            });

             

           
             $('.popup_leave_second #confirmBtn').click(function(){
                $.cookie("rejoin", "0");
                $.cookie("rejoinPR", "0"); 
                $.cookie("creator", null);
                $.removeCookie("creator");
                $.cookie("roomCreated", "0");

               

                location.reload();
                 
             
            });

            /* Confirm Melding */
            $('.popup_bg').delegate('.okBtn', 'click', function(){


           

                 cardMelded = 1;
                 cardDiscard = 1;
                 var roomIdCookie = $.cookie("room");
                 var sessionKeyCookie = $.trim($.cookie("sessionKey"));

                  // $(this).css({'display': 'none'});

                  $('.popup_bg').hide();
                  $('.popup_bg .popup_with_button_cont p').text("");



                  $('.game_message').html('').show();


                    $('.show_your_card_sec').css({'display': 'block'});

                    /* show meld all button */

                     /* Send a signal to other user for popup  */
                    
                     playerCounterFlag = 0;
                     // $('.current-player[data-user="'+userId+'"] .card_submit_time').hide(); 
                     // $('.current-player[data-user="'+userId+'"] .card_submit_time').text(""); 
                     intervalCounter = window.clearInterval(intervalCounter);


                     var signal001 = {room:roomName, type : 'card-melded', player: userId, message: 'card melded', cardDiscarded: cardsSelected[0]};
                     //connection.send(JSON.stringify(signal001));   
                     socket.emit(socketEventName, JSON.stringify(signal001));

                   

                      var CardMeldingCounterHandler = new cardMeldingCounterHandler();
                      CardMeldingCounterHandler.counter = 30;
                      cardMeldingIntervalCounter = setInterval(CardMeldingCounterHandler.updateCounter, 1000);

                                      
                  


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
                                            cardGotPulled = '';


                                            
                                            $('#meld'+userId).css({'display': 'none'});
                                            $('.discard_top').remove();
                        
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
                                            cardGotPulled = '';
                                          
                                            cardsSelected.length = 0;
                                            $('#meld'+userId).css({'display': 'none'});
                                            $('.discard_top').remove();

                                           
                                    }
                                        

                                }

                            });



                     }


                    




                  }

            });

            
            

            /**** =========== VALIDATE ******* ============== */

            $('#validateCards'+userId).click(function(){
                cardSubmitFinal();
              

            });
               


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
            


            
            $('.group').delegate('.discard_top', 'click', function(){
                discardCard();
            })   

            

            /* discard button */

            $('.hand').delegate('.discard_top', 'click', function(){
                discardCard();
            })




            /*  discard button click */

            $('.discard button').click(function(){

                 if( $(this).is('[disabled=disabled]') ){
                    return false;
                  }else{

                    discardCard();

                  }   


                
            });       



            $('.hand').delegate('.group_cards', 'click', function(){
                groupCardsFunc();

            })

            
            $('.group').delegate('.group_cards', 'click', function(){
                groupCardsFunc();
                
            })

          


    
                 // deal me out of next + drop and go

                

                  $('input[name=opt]').change(function(){
                        var value = $( 'input[name=opt]:checked' ).val();
                        var roomIdCookie = $.cookie("room");
                        var sessionKeyCookie = $.trim($.cookie("sessionKey"));
                       
                        var ajxDataDealAndDrop = {'action': 'update-deal-and-drop', roomId: roomIdCookie, sessionKey: sessionKeyCookie, value: value, user:userId};

                          $.ajax({
                                type: 'POST',
                                data: ajxDataDealAndDrop,
                                cache: false,
                                url: 'ajax/updateDealAndDrop.php',
                                success: function(result){

                                    console.log(result);
                                }
                             });       


                    });   


            

                  
         
        // });

    
        
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
<style type="text/css">
.offlineOverlay{
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0,0,0,0.5);
  display: none;
  z-index: 9999999
}
</style>
<div class="offlineOverlay">
</div>
<script>
Offline.on('confirmed-down',function(){
  $('.offlineOverlay').fadeIn();
});
Offline.on('confirmed-up',function(){
  $('.offlineOverlay').fadeOut();   
});
</script>  
</body>
</html>