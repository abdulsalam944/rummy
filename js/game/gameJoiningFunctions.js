function createGame(roomId, gameType, gamePlayers, betValue, chipsToTable, currentBalance, minBuying){

        //connection.close();
        $.cookie("roomCreated", "0");
        
        //socket.emit(socketEventName, 'Test message 1');


        direction = "many-to-many";

        _session = "data";
        splittedSession = _session.split('+');

        session = {};
        for (var i = 0; i < splittedSession.length; i++) {
            session[splittedSession[i]] = true;
        }

        maxParticipantsAllowed = 256; 
       
         var ajxDataIdGenerate = {'action': 'id-generate'};

        $.ajax({
            type: 'POST',
            url: 'ajax/ajaxidgenerate.php',
            cache: false,
            data: ajxDataIdGenerate,
            success: function(theId){
                // alert('hi');
                console.log(theId);
                var theUniqueId = $.trim(theId);
               
   
        /*        
        connection.extra = {
            'session-name': theUniqueId
        };

        connection.session = session;
        connection.maxParticipantsAllowed = maxParticipantsAllowed;

      
        connection.sessionid = theUniqueId;
        */
        $.cookie("room", roomId);
        $.cookie("sessionKey", theUniqueId);
        $.cookie("game-type", gameType);
        $.cookie("game-players", gamePlayers);
        $.cookie("creator", "1");
        $.cookie("betValue", betValue);
        $.cookie("chipsToTablePR", chipsToTable);
        $.cookie("currentBalancePR", currentBalance);
        $.cookie("minBuyingPR", minBuying);
        /*
        connection.open();
        connection.connect(theUniqueId);
       */
        /**  Update the Room Value */

        console.log('Creating new room'+theUniqueId);

        var ajxData01 = {'action': 'room-update', roomId: roomId, sessionKey:theUniqueId};

            $.ajax({
                type: 'POST',
                url: 'ajax/roomUpdate.php',
                cache: false,
                data: ajxData01,
                success: function(result){


                   $('.room1').css({ display: 'none'});
                   $('.room2').css({ display: 'block'});  

                   var ajxData02 = {'action': 'player-update', roomId: roomId, playerId: userId, creator: "true", sessionKey: theUniqueId};

                    $.ajax({
                        type: 'POST',
                        url: 'ajax/playerUpdate.php',
                        cache: false,
                        data: ajxData02,
                        success: function(result){

                           /* Remove previous player - gamedata of this room  */ 

                            var ajxData100 = {'action': 'remove-gamedata', roomId: roomId, sessionKey: theUniqueId};

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

                                        var ajxData010011 = {'action': 'connection-id-update', roomId: roomId, connectionId: currentUsersId, player: userId, sessionKey: theUniqueId};

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

        } });

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

                   // alert();

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
                            /*connection.connect(keyRetrieved);*/
                          
                        /* update connection id */
                        var ajxData010011 = {'action': 'connection-id-update', roomId: roomId, connectionId: currentUsersId, player: userId, sessionKey: keyRetrieved};

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

                                      //socket.emit(socketEventName, 'Test message 1');
                                     
                                      $('.me .me_pic img').css({'display': 'block'});
                                      $('.me .player_name .player_name_me').text(name);
                                      $('.me .player_name').css({'display': 'block'});

                        } });


                    

                ConnectSocket();
                
                console.log("don't have to create room");


            }else if($.trim(sessionKey) == "no"){
                /* room not available, create new table */

                
                 createGame(roomId, gameType, gamePlayers, betValue, null, null, null);
                


            }

          }  

        } });

        

 });



function rejoinRequest(){



       /* Rejoin Another game */
            $.cookie("creator", null);
            $.removeCookie("creator");


        /* PRITAM PAAL */
          var roomId = $.cookie("room");
          var gamePlayers = $.cookie("game-players");
          var gameType = $.cookie("game-type");

         
          console.log("room 3333333333333333333333", roomId);
         

          var chipsToTablePRCookie = $.trim($.cookie("chipsToTablePR"));
          var currentBalanceCookie = $.trim($.cookie("currentBalancePR"));
          var minBuyingPRCookie = $.trim($.cookie("minBuyingPR"));
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
                            /*connection.connect(keyRetrieved);*/
                          
                        /* update connection id */

                        var ajxData010011 = {'action': 'connection-id-update', roomId: roomId, connectionId: currentUsersId, player: userId, sessionKey: keyRetrieved};

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


                    
                ConnectSocket();

                console.log("don't have to create room");
            }else if($.trim(sessionKey) == "no"){
                /* room not available, create new table */

                 // alert("2");   
                 createGame(roomId, gameType, gamePlayers, betValue, null, null, null);
                


            }

          }  

        } });



}


  function rejoinRequestPR(){

    console.log('Rejoin PR called');

       /* Rejoin Another game */
        $.cookie("creator", null);
        $.removeCookie("creator");

        /* PRITAM PAAL */
          var roomId = $.cookie("room");
          var gamePlayers = $.cookie("game-players");
          var gameType = $.cookie("game-type");

         
          console.log("room 3333333333333333333333", roomId);
         

          var chipsToTablePRCookie = $.trim($.cookie("chipsToTablePR"));
          var currentBalanceCookie = $.trim($.cookie("currentBalancePR"));
          var minBuyingPRCookie = $.trim($.cookie("minBuyingPR"));
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
                            /*connection.connect(keyRetrieved);*/
                          
                        /* update connection id */

                        var ajxData010011 = {'action': 'connection-id-update', roomId: roomId, connectionId: currentUsersId, player: userId, sessionKey: keyRetrieved};

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
                                      $.cookie("chipsToTablePR", chipsToTablePRCookie);
                                      $.cookie("currentBalancePR", currentBalanceCookie);
                                      $.cookie("minBuyingPR", minBuyingPRCookie);


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


                    
                ConnectSocket();

                console.log("don't have to create room");
            }else if($.trim(sessionKey) == "no"){
                /* room not available, create new table */

                
                 createGame(roomId, gameType, gamePlayers, betValue, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie);

                $('#myModal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                


            } }

        } });









}


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
                            /*connection.connect(keyRetrieved);*/
                          
                        /* update connection id */

                        var ajxData010011 = {'action': 'connection-id-update', roomId: roomId, connectionId: currentUsersId, player: userId, sessionKey: keyRetrieved};

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


                 ConnectSocket();   


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

   if($(this).hasClass('rejoinPr')){
     $.cookie("rejoinPR", "1");
     $.cookie("rejoin", "0");


     //var minBuying = $('#myModal #min_buying').text();
     var chipsToTable = $('#myModal #chips_to_table').val();
     
     $.cookie("chipsToTablePR", chipsToTable);

      $('#myModal').modal('hide');
      $('body').removeClass('modal-open');
      $('.modal-backdrop').remove();




     
     setTimeout(function(){
      location.reload();
    }, 3000);
   
   }else{

    var betAmount =  $('#myModal #bet_amount').text();
    var minBuying = $('#myModal #min_buying').text();
    var currentBalance = $('#myModal #current_balance').text();
    var chipsToTable = $('#myModal #chips_to_table').val();


    var roomIdHidden = $('#roomIdHidden').val();
    var gameTypeHidden = $('#gameTypeHidden').val();
    var gamePlayersHidden = $('#gamePlayersHidden').val();

    // console.log(roomIdHidden + ' ' + gameTypeHidden + ' ' + gamePlayersHidden);

     joinGamePointsRummy(roomIdHidden, gameTypeHidden, gamePlayersHidden, betAmount, chipsToTable, currentBalance, minBuying);

   }  



});


$('.popup_rejoin #rejoinBtn').click(function(){

      // BOLO BHAI

       if( $(this).is('[disabled=disabled]') ){
        return false;
      }else{

       $.cookie("rejoinReqCookie", "1"); 


      $('.popup_rejoin .popup_counter p').text('');

       var roomIdCookie = $.cookie("room");
       var sessionKeyCookie = $.trim($.cookie("sessionKey"));
       var rejoinScore = $.trim($.cookie("rejoinScore"));
       var betValueCookie = $.trim($.cookie("betValue"));
       var winningAmount;

       $.cookie("rejoinScore", parseFloat( Math.round(result['highestPoint']) + 1) );
       window.clearInterval(rejoinGameCounter);

        playersPlayingTemp.push(parseInt(userId));

        playersPlayingTemp.sort(function(a, b){
            return a - b;
        });


        playersPlaying = playersPlayingTemp.slice();

        var signalRejoinAgain = {room:roomName, type: 'update-players-playing-rejoin', message: 'updating players playing on rejoining', playersPlayingTemp: playersPlayingTemp, player: userId, playerName: name, rejoinScore: rejoinScore};

        socket.emit(socketEventName, JSON.stringify(signalRejoinAgain));
       // connection.send(JSON.stringify(signalRejoinAgain));

        console.log("players updated ", playersPlayingTemp);

        $('.popup_rejoin').hide();
        $('.popup_rejoin .popup_with_button_cont p').text("");

         


         

         /* insert data into real wallet & update prize money */
          var ajxData000111 = {'action': 'chip-deduct', roomId: roomIdCookie, user: userId, sessionKey: sessionKeyCookie, chip: betValueCookie, rejoin:1};

        $.ajax({
            type: 'POST',
            url: 'ajax/chipDeduct.php',
            cache: false,
            data: ajxData000111,
            success: function(result){ 
                console.log("chip deduction result on rejoing ", result); 


                /* prize money update */



                 var ajxDataWinningAmt = {'action': 'get-winning-amount', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                    $.ajax({
                        type: 'POST',
                        url: 'ajax/getWinningAmount.php',
                        cache: false,
                        data: ajxDataWinningAmt,
                        success: function(winningAmt){ 
                            console.log("winning amount result on rejoining", winningAmt); 
                            winningAmount = parseFloat(winningAmt).toFixed(2);

                            $('.game_info #game_prize_money').text(winningAmount);

                            setTimeout(function(){

                                 var signalWiningAmount = {room:roomName, type: 'update-winning-amount', amount:winningAmount};

                                //connection.send(JSON.stringify(signalWiningAmount));
                                socket.emit(socketEventName, JSON.stringify(signalWiningAmount));
                            }, 3000);

                           

                            
                    } }); 



                
        } }); 




          /* update my data */

            var ajxDataUpdate1 = {'action': 'update-my-data', roomId: roomIdCookie, userId: userId, sessionKey: sessionKeyCookie, rejoinScore: rejoinScore, chipsTaken: betValueCookie};

            $.ajax({
                type: 'POST',
                url: 'ajax/updateMyDataRejoin.php',
                cache: false,
                data: ajxDataUpdate1,
                success: function(result){

                    $('.me .me_pic img').css({'display': 'block'});
                    $('.me .player_name .player_name_me').text(name);
                    $('.me .player_name').css({'display': 'block'});
                    $('.me').attr('data-user', userId);

                    $('.current-player[data-user="'+parseInt(userId)+'"]').show();
                    $('.current-player[data-user="'+parseInt(userId)+'"] .player_name #score').text(rejoinScore);



                    console.log(result);

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

                      if(throwCard != "Joker"){       


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


                    }
                });        


               

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
                  playerCounterFlag = 0;

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
                  cardGotPulled = '';
                  cardSubmitted = 0;

                  $('.discard button').attr('disabled', true);
                  $('.sort button').attr('disabled', false);

                  $('.discard button').show();
                  $('.sort button').show();

                  $('.discard').show();
                  $('.sort').show();

                  $('.meldAll button').hide();
                  
                  $('.current-player[data-user="'+userId+'"] .player_name #score').text(rejoinScore);


                    $('.result_sec').css({'display': 'none'});
                    $('.result_sec tbody[id="score_reports"] tr').remove();

                   $('.player_card_me .hand li a').removeClass('showFoldedCard');
                  $('.group_blog .playingCards .hand li a').removeClass('showFoldedCard');

                  $('.player_card_me').show();    

                  $.cookie("rejoinReqCookie", "0");




          } });  


        }

    });



     $('.popup_play_again #playAgainBtn').click(function(){

         //resetGameConditions();

         $.cookie("rejoin", "1");
         $.cookie("rejoinPR", "0");

         $('.popup_play_again').hide();
         $('.popup_play_again .popup_with_button_cont p').text("");

         setTimeout(function(){
            location.reload();
         }, 2000);

    });


    


    $('.popup_play_again .goToLobbyBtn').click(function(){

        $.cookie("rejoin", "0");
        $.cookie("rejoinPR", "0"); 

        $('#myModal .joinPR').removeClass('rejoinPr');

        $('.popup_play_again').hide();
        $('.popup_play_again .popup_with_button_cont p').text("");

        setTimeout(function(){
            location.reload();
        }, 2000);

        // sdaasd 



    });

    $('.popup_rejoin .goToLobbyBtn').click(function(){

       //connection.close();

        $.cookie("rejoin", "0");
        $.cookie("rejoinPR", "0"); 
        $.cookie("rejoinScore", "0");

        $('#myModal .joinPR').removeClass('rejoinPr');

        $('.popup_play_again').hide();
        $('.popup_play_again .popup_with_button_cont p').text("");

        setTimeout(function(){
            location.reload();
        }, 2000);

    })


    $('.leave_table_div .leave_table').click(function(){

        $.cookie("rejoin", "0");
        $.cookie("rejoinPR", "0"); 
        $('#myModal .joinPR').removeClass('rejoinPr');

        $('.popup_play_again').hide();
        $('.popup_play_again .popup_with_button_cont p').text("");

        setTimeout(function(){
            location.reload();
        }, 2000);



    });


$(document).ready(function(){

            var rejoinCookie = $.cookie("rejoin");
            var rejoinCookiePR = $.cookie("rejoinPR");
            var roomCreated = $.cookie("roomCreated");

            var gamePlayers = $.cookie("game-players");
            var gameType = $.cookie("game-type");

            console.log("rejoinCookie ------------------------- ", rejoinCookie);
            console.log("rejoinCookiePR ------------------------- ", rejoinCookiePR);

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


            if($.trim(rejoinCookiePR) == "0"){

               $('.room1').css({ display: 'block'});
               $('.room2').css({ display: 'none'});   

            }else if($.trim(rejoinCookiePR) == "1"){

               $('.room1').css({ display: 'none'});
               $('.room2').css({ display: 'block'});

                  if(gamePlayers == "2"){
                    $('.play_board').removeClass('square_board');
                    $('.play_board').addClass('round_board');
                    
               }else if(gamePlayers == "6"){
                   $('.play_board').removeClass('round_board');
                    $('.play_board').addClass('square_board');
                    
               }


                 rejoinRequestPR();



            }



            if($.trim(roomCreated) == "0"){
                  $('.room1').css({ display: 'block'});
                  $('.room2').css({ display: 'none'});   
            }else if($.trim(roomCreated) == "1" && $.trim(gameType) != "score"){

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


            }else if($.trim(roomCreated) == "1" && $.trim(gameType) == "score"){

               $('.room1').css({ display: 'none'});
               $('.room2').css({ display: 'block'});

                  if(gamePlayers == "2"){
                    $('.play_board').removeClass('square_board');
                    $('.play_board').addClass('round_board');
                    
               }else if(gamePlayers == "6"){
                   $('.play_board').removeClass('round_board');
                    $('.play_board').addClass('square_board');
                    
               }


                rejoinRequestPR();


            }

    });        
