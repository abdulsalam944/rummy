<?php include("config.php"); ob_start(); ?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Rummy Game</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.css">
    <link rel="stylesheet" href="css/cards.css">


<style>
      body{
        background-color: #eee;
    }

     .bottom-player{
        position: absolute;
        background: white;
        bottom: 10px;
        right: 48%;
        padding: 10px;
      }

      .player-list-six{
        margin-right: 10%;
        margin-left: 10%;
        margin-top: 5%;
        text-align: center;
      }

      .player-list-two{
        margin-right: 10%;
        margin-left: 10%;
        margin-top: 5%;
        text-align: center;

      }   

      .player-list-six li{
        background: white;
        padding: 5px;
        margin-right: 10%;

      }

       .player-list-two li{
        background: white;
        padding: 10px;

      }

</style>

    


        
   
</head>



<body>


<?php


if(isset($_SESSION['user_id'])) {
   
    $user_id = $_SESSION['user_id'];

// get the displayname of the user

    $sqlName = mysql_query('SELECT * FROM users WHERE id = '.$user_id.' LIMIT 1');
    $rowName = mysql_fetch_array($sqlName);

    $displayName = $rowName['name'];
    // $usertype = $rowName['usertype'];


}

if(isset($_SESSION['user_id'])) {


?>

<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Rummy Game</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="logout.php?val=true">Logout</a></li>
    </ul>
  </div>
</nav>


      
    <div class="room1">

        <h3 class="text-center">Welcome <?php echo $displayName; ?> </h3>


        <ul class="list-group">
     
          <?php 
          
          $sql = mysql_query('SELECT * FROM room');
          $room = '';
          while($rows = mysql_fetch_assoc($sql)){

            $roomId = $rows['id'];
            $room = $rows['name'];
            $created = $rows['created'];
            $status = $rows['status'];

            if($status == 'open'){
              $roomStatus = 'Open';
            }else if($status == 'registering'){
              $roomStatus = 'Registering';
            }else if($status == 'ongoing'){
              $roomStatus = 'Game going on';
            }

            if($created == "N" && $status == "open"){
                 echo  '<li class="list-group-item">'.$room.' &nbsp; <a class="create-room-btn btn btn-success" id="'.$roomId.'">Join Game</a>&nbsp; '.$roomStatus.'</li>';
            }else{
                 echo  '<li class="list-group-item">'.$room.' &nbsp; 
                            <a class="join btn btn-success" id="'.$roomId.'" data-sessionid="'.$roomId.'">Join Game</a>
                            &nbsp; '.$roomStatus.'
                          </li>';
            }


            


          }

            ?>

         
        </ul>




    </div>

<!-- Room 2 -->


<div class="room2">

       

     

            <div class="container-fluid">
            <div class="room_inner">
            <div class="row">
          
                <div class="col-md-12" style="background-color:#2f312f; height:700px;">

                  <div style="display:block; margin-right:10%; margin-left: 10%;">  
                        <ul class="list-inline player-list-six" style="display: none;">
                            <li id="2" data-user="cxinmekadrysyvi" data-position="216">avik</li>
                            <li id="2" data-user="cxinmekadrysyvi" data-position="216">avik</li>
                            <li id="2" data-user="cxinmekadrysyvi" data-position="216">avik</li>
                            <li id="2" data-user="cxinmekadrysyvi" data-position="216">avik</li>
                            <li id="2" data-user="cxinmekadrysyvi" data-position="216">avik</li>
                            <li id="2" data-user="cxinmekadrysyvi" data-position="216">avik</li>
                        </ul>
                        <ul class="list-inline player-list-two">
                            <li id="2" data-user="cxinmekadrysyvi" data-position="216">avik</li>
                        </ul>
                        <div class="playingCards" id="playingCards">
                           <div class="card-toss-opponent">
                            
                          </div>
                        </div>

                   </div> 

                  <div class="user-card-holders"></div> 
                   
             
                   <div class="col-md-12 text-center pointsZone" style="margin-top: 200px; margin-bottom:50px;">

                        <!--  <a class="btn btn-primary btn-bg" disabled="disabled" id="deal<?php echo $user_id; ?>">Deal</a>
                         <a class="btn btn-info btn-bg" disabled="disabled" id="shuffle<?php echo $user_id; ?>">Shuffle</a> -->
                      <!-- 
                        <div style="color: #fff; margin-top:10px; margin-bottom: 10px;" class="playStatus">Waiting for other players to join...
                        </div> 
                      -->
                        
                       <div class="cards">
                         
                       </div>

                     

                   </div>


                  
                     <div class="playingCards deck-middle col-md-8 col-md-offset-2" style="display:block; margin-bottom:30px; margin-top:-150px;">
                        <!-- <ul class="deck"></ul> -->

                         <ul class="deck" style="margin-left:200px;"></ul>

                     </div>

                  




                  
                   <div class="my-cards col-md-12" style="display:block; margin-bottom:30px;">
                       
                   </div>

                   <div class="playingCards" id="playingCards<?php echo $user_id; ?>">
                     <div class="card-toss">
                         
                     </div>
                    
                    </div>
                      
                   <div class="bottom-player">
                    
                       <span id='1'  data-user='connectionId' data-position='1'>Sagnik</span> 
                   </div>
                   




                </div>
                <div class="col-md-3">
            


                </div>
            </div>
        </div>

    </div>



  <!-- <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Small Modal</button> -->
  
  <!-- Modal -->
  <div class="modal fade" id="rummyModal" role="dialog" style="">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
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




    
  </div> 


        

<?php } ?>




<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>


       

  <script src="webrtc/RTCMulticonnection.js"></script>
  
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>

  <script>

  var counter = 5;
  var playersPlaying = [];

  var waitHandler = function(callback){

     callback();

      setInterval(function(){
         if(counter == 0){
            clearInterval(waitHandler);
            return;
          }
           // console.log(counter);
           $('.modal-body p').text("Please wait while the other player joins!");
           counter--;
         
        }, 1000);

      

  }

  var gameStartHandler = function(callback){

      setInterval(function(){
         if(counter == 0){
            clearInterval(waitHandler);
            $('#rummyModal').modal('hide');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
            callback();
          }
           // console.log(counter);
           $('.modal-body p').text("Game will start in " + counter + ' seconds');
           counter--;
         
        }, 1000);


        

  }

  var waitHandlerNormal = function(callback){
    callback();
     $('.modal-body p').text("Please wait while the other player joins!");
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


  /** ========= Game Algorithm ============== */

     
       var userId = "<?php echo $user_id; ?>";
       var name = "<?php echo $displayName; ?>";

       var connection = new RTCMultiConnection();
       var sessionName = "<?php echo $room; ?>";


  $(function(){



   $('.create-room-btn').click(function(){

     var x = confirm("Are you sure you want to join?");

     if(x){

            var roomId = $(this).attr('id');
            var direction = "many-to-many";

            var _session = "data";
            var splittedSession = _session.split('+');

            var session = {};
            for (var i = 0; i < splittedSession.length; i++) {
                session[splittedSession[i]] = true;
            }

            var maxParticipantsAllowed = 256;

            if (direction == 'one-to-one') maxParticipantsAllowed = 1;
            if (direction == 'one-to-many') session.broadcast = true;
            if (direction == 'one-way') session.oneway = true;

            connection.extra = {
                'session-name': $(this).text() || 'Anonymous'
            };

            connection.session = session;
            connection.maxParticipantsAllowed = maxParticipantsAllowed;

          
            connection.sessionid = roomId;
            $.cookie("room", $(this).attr('id'));
            
            connection.open();

            /**  Update the Room Value */

            var ajxData01 = {'action': 'room-update', roomId: roomId};

                $.ajax({
                    type: 'POST',
                    url: 'ajax/roomUpdate.php',
                    cache: false,
                    data: ajxData01,
                    success: function(result){
                        
                      // $('#myModal').modal('show');

                      /** Update Player Count */

                       var ajxData02 = {'action': 'player-update', roomId: roomId, playerId: userId, creator: "true"};

                        $.ajax({
                            type: 'POST',
                            url: 'ajax/playerUpdate.php',
                            cache: false,
                            data: ajxData02,
                            success: function(result){

                              waitHandlerNormal(function(){
                                $('#rummyModal').modal('show');
                              });

                              console.log(result);

                            }
                        });


                    }
                });

        }

  });




  /** ======= JOIN ============ */

    var sessions = {};
    var sessionArray = [];    
        
        
    connection.onNewSession = function(session) {
            
      if (sessions[session.sessionid]) return;
      sessions[session.sessionid] = session;



        $('.join').click(function(){

          var y = confirm("Are you sure you want to join?");

          if(y){
                
              var roomId = $(this).attr('id');
              session = sessions[$(this).attr('data-sessionid')];

              if (!session) alert('No room to join.');
              
              connection.join(session);
              console.log('This is my userid: ' + connection.userid);

               $.cookie("room", $(this).attr('id'));

                /** Update Player Count */

               var ajxData03 = {'action': 'player-update', roomId: roomId, playerId: userId, creator: "false"};

                $.ajax({
                    type: 'POST',
                    url: 'ajax/playerUpdate.php',
                    cache: false,
                    data: ajxData03,
                    success: function(result){
                      console.log('player updated!');
                    }
                });

          }  

        });

    };

         


    /** ======= When Connection opens up ===== */

    connection.onopen = function(){
      console.log("sessions: " + JSON.stringify(sessions));

       /* Check player count */
       var roomIdCookie = $.cookie("room");

        var ajxData04 = {'action': 'player-count', roomId: roomIdCookie};

         $.ajax({
          type: 'POST',
          url: 'ajax/getPlayerCount.php',
          cache: false,
          data: ajxData04,
          success: function(result){
           

               $('#rummyModal').modal('hide'); // hiding wait for other players
               

               /* Getting players */

               var ajxData06 = {'action': 'get-players', roomId: roomIdCookie};

                     $.ajax({
                        type: 'POST',
                        url: 'ajax/getPlayers.php',
                        cache: false,
                        data: ajxData06,
                        dataType: "json",
                        success: function(players){

                          for(var i = 0; i < players.length; i++){
                            playersPlaying.push(players[i]);
                          }

                          $('#rummyModal').modal('show'); // showing for play counter
               
                         gameStartHandler(function(){  // game starting counter

                              // Showing the deck | backside cards
                              var $deckCard = '<li><div class="card back"></div></li>';

                              for(var i = 0; i < 6; i++){
                                  $('.deck-middle .deck').append($deckCard);
                              } 


                              // Choose two cards for toss

                                 var ajxData05 = {'action': 'get-deck'};

                                   $.ajax({
                                      type: 'POST',
                                      url: 'ajax/getCardDeck.php',
                                      cache: false,
                                      dataType: "json",
                                      data: ajxData05,
                                      success: function(cards){

                                          // console.log(cards);
                                          // console.log(cards[0]);

                                          // console.log("players : " + playersPlaying);

                                          /* Card Toss */

                                          for(var i = 0; i < playersPlaying.length; i++){

          

                                                /* Insert toss card in DB */

                                            var ajxData07 = {'action': 'insert-toss', card: cards[i], player: playersPlaying[i], roomId: roomIdCookie};


                                              $.ajax({
                                                type: 'POST',
                                                url: 'ajax/insertToss.php',
                                                cache: false,
                                                data: ajxData07,
                                                success: function(result){

                                                  // get toss cards and place them in position

                                                  var ajxData08 = {'action': 'get-toss-cards', player: playersPlaying[i], roomId: roomIdCookie};

                                                   $.ajax({
                                                      type: 'POST',
                                                      url: 'ajax/getTossCard.php',
                                                      cache: false,
                                                      data: ajxData08,
                                                      success: function(card){

                                                      var cardNumber = card.substr(0, card.indexOf('OF'));
                                                      var cardHouse =  card.substr(card.indexOf("OF") + 2);

                                                        if(playersPlaying[i] == userId){
                                                            $('.card-toss').html(
                                                            '<div class="card rank-'+cardNumber+' '+cardHouse+'" style="margin-left: -680px !important; margin-top: 325px !important;">'+
                                                              '<span class="rank">'+cardNumber+'</span>'+
                                                              '<span class="suit">&'+cardHouse+';</span>'+
                                                              '</div>'
                                                            );
                                                 
                                                        }else if(playersPlaying[i] != userId){

                                                          
                                                        }



                                                      }
                                                   })

                                                }

                                              })

                                          }           





                                                 // if(playersPlaying[i] == userId){
                                                    
                                                 //    $('.card-toss').html(
                                                 //    '<div class="card rank-'+cardNumber+' '+cardHouse+'" style="margin-left: -680px !important; margin-top: 325px !important;">'+
                                                 //      '<span class="rank">'+cardNumber+'</span>'+
                                                 //      '<span class="suit">&'+cardHouse+';</span>'+
                                                 //      '</div>'
                                                 //    );
                                                 // }

                                          

                                       

                                      }

                                });

                           });

                        }

                  }); 

                }


              });




      
    };

    /** ===== Receiving all the Signals ===== */ 

    connection.onmessage = function(e) {

      var dataReceived = JSON.parse(e.data);

      console.log(dataReceived);

    };  


    


    connection.connect();


 
 });

        
  </script>


     



</body>
</html>