/* DEAL NEXT ROUND CARDS FOR OTHERS */

 
  function dealNextRoundCardsOthers(nextPlayer, roomIdCookie, gamePlayersCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValue, callback){

      playerCounterFlag = 0;
      intervalCounter = window.clearInterval(intervalCounter);

       $('.loading_container').hide();
       $('.loading_container .popup .popup_cont').text(""); 

        var connectionIssueCount = $.trim($.cookie("connectionIssue"));
        var netSpeed = $.trim($.cookie("netSpeed"));

         if(netSpeed <= 1){
            connectionIssueCount++;
            $.cookie("connectionIssue", connectionIssueCount);

         } 

      $('.leave_table_btn').attr('disabled', true);
      


       gameStatusFlag = '';
       gameStatusArray.length = 0;
       $('.result_bottom').text("");

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
                                 

                                  $('.current-player[data-user="'+parseInt(result.playersWhoLost)+'"]').hide();

                                  var $div = $(".current-player").filter(function() {
                                      return $(this).data("user") == parseInt(result.playersWhoLost);
                                  });

                                  $div.attr('data-user', '');



                                   if(removeCardFromGroups(result.playersWhoLost[i], playersPlaying)){
                                          console.log("player removed ", playersPlaying);
                                  }

                                  if(parseInt(result.playersWhoLost[i]) == parseInt(userId) ){
                                     isLost = true;
                                  }



                               } 



                              playersPlayingTemp = playersPlaying.slice();


                              

                          }

                      }

                   }


               });  



           /*  Get player scores for display */
      
           for(var j = 0; j < playersPlayingWholeGame.length; j++){ 

              var ajxData703 = {'action': 'get-players', player: playersPlayingWholeGame[j]};

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
                                    

                                        /* Check if any of the players' score is less than or equal to 79 in 6 player pool */

                                                if( (gameTypeCookie == "101" && gamePlayersCookie == "6") || (gameTypeCookie == "101" && gamePlayersCookie == "6")){

                                                var ajxGetPlScore = {'action': 'get-pl-score', roomId: roomIdCookie, playerId: userId, sessionKey: sessionKeyCookie};

                                                $.ajax({

                                                    type: 'POST',
                                                    url: 'ajax/getPlScore.php',
                                                    cache: false,
                                                    data: ajxGetPlScore,
                                                    dataType: 'JSON',
                                                    success: function(result){ 

                                                       // alert("got other scores");
                                                       console.log(result);
                                                       console.log(Math.round(result['highestPoint']));
                                                       
                                                         if(Math.round(result['highestPoint']) <= 79){


                                                              

                                                             $('.loading_container').hide();
                                                             $('.loading_container .popup .popup_cont').text();


                                                              $('.popup_rejoin').show();
                                                              $('.popup_rejoin .popup_with_button_cont p').text("Do you want to rejoin at " + parseFloat(Math.round(result['highestPoint']) + 1) + " points ?");          
                                                              $.cookie("rejoinScore", parseFloat( Math.round(result['highestPoint']) + 1) );

                                                              //  rejoinGameStartHandler(function(){

                                                             

                                                              //      connection.close();
                                                              //      location.reload();


                                                              //       $('.popup_rejoin #rejoinBtn').attr('disabled', true);

                                                              // });


                                                                var RejoinGameStartHandler = new rejoinGameStartHandler();
                                    
                                                                RejoinGameStartHandler.counter = 10;
                                                                rejoinGameCounter = setInterval(RejoinGameStartHandler.updateCounter, 1000);  



                                                         }else{

//                                                             connection.close();

                                                              $('.loading_container').hide();
                                                              $('.loading_container .popup .popup_cont').text();

                                                            $('.popup_play_again').show();
                                                            $('.popup_play_again .popup_with_button_cont p').text("Do you want to play again?");
                                                         }







                                                       


                                                    } })  



                                               

                                                }


                                  }
                              } }); 
                       }

                       // if(isLost == false){

                            nextGameStartHandler(function(){
                              if(isLost == false){
                                 $('.result_sec').css({'display': 'none'});
                                 $('.result_sec tbody[id="score_reports"] tr').remove();
                                 callback();
                              }
                              
                              
    
                           }); 

                      // }  
                        
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

      $('.result_bottom').text("");

       console.log("cookie CB ", currentBalanceCookie);
       console.log("cookie chips ", chipsToTablePRCookie);

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
                                              var scoreboardStatus = results.scoreboard_status;

                                              

                                               $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="count"]').text(points);

                                                $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="result"]').text(scoreboardStatus);
                                  
                                               if(totalPts != 0.00){
                                                  $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_chips"]').text('-'+totalPts);

                                                  if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                      if(scoreboardStatus != 'drop' || scoreboardStatus != 'middle drop'){

                                                          $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]').html(showCardsInScoreboard(player.id, roomIdCookie, sessionKeyCookie));

                                                      }

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
                                               

                                               var signalOthers = {room:roomName, type: 'points-rummy-fso', message: 'points game get scoreboard others', winner: result.playerWon, playersLostArr: playersWhoLostArray, winningAmount: result.winningAmount};

                                                //connection.send(JSON.stringify(signalOthers));
                                                socket.emit(socketEventName, JSON.stringify(signalOthers));
                                                if(parseInt(userId) === parseInt(result.playerWon)){


                                                  var ajxData704407 = {'action': 'update-real-wallet', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                                  $.ajax({
                                                      type: 'POST',
                                                      data: ajxData704407,
                                                      cache: false,
                                                      url: 'ajax/updateRealWalletPR.php',
                                                      success: function(results){
                                                          
                                                           // alert("Total chips coming.......................");
                                                           console.log(results);   
                                                      } }); 

                                                  //alert("won 1");

                                                  

                                                   // $('.loading_container').css({'display':'block'});
                                                   // $('.loading_container .popup .popup_cont').text("You have won the game!");

                                                     setTimeout(function(){
                                                      // $('.loading_container').hide();
                                                      // $('.loading_container .popup .popup_cont').text();

                                                      if(result.dealMeOut == 1){


                                                          $.cookie("rejoinPR", "0");
                                                          $.cookie("rejoin", "0");
                                                          $.cookie("onOpenHit", null);
                                                          $.removeCookie("onOpenHit");

                                                           setTimeout(function(){
                                                              location.reload();
//                                                              connection.close();
                                                          }, 3000);

                                                      }else{

                                                          $.cookie("rejoinPR", "1");
                                                          $.cookie("rejoin", "0");
                                                          $.cookie("onOpenHit", null);
                                                          $.removeCookie("onOpenHit");

                                                      }


                                                      var countRejoin = 10;

                                                     
                                                      console.log("result winning amount ", result.winningAmount);

                                                     var newCurrentBalanceCookie = parseFloat(currentBalanceCookie) + parseFloat(result.winningAmount);

                                                      var newChipsToTablePRCookie = parseFloat(chipsToTablePRCookie) + parseFloat(result.winningAmount);



                                                       $.cookie("chipsToTablePR", newChipsToTablePRCookie);
                                                       $.cookie("currentBalancePR", newCurrentBalanceCookie);

                                                        

                                                       console.log("new currentblnccookie", newCurrentBalanceCookie);
                                                       console.log("new chips to table ", newChipsToTablePRCookie);



                                                      


                                                      var intervalRejoin = setInterval(function(){

                                                       
                                                      $('.result_bottom').css({'display': 'block'});
                                                      $('.result_bottom').text('You will rejoin in '+countRejoin+' seconds');

                                                        countRejoin--;

                                                        if(countRejoin <= 0){
                                                          clearInterval(intervalRejoin);
                                                          setTimeout(function(){
                                                            location.reload();
                                                          }, 3000);
                                                        }

                                                      



              
                                                      },1000);

                                                      
                                                   }, 4000);

                                                }else{

                                                  $.cookie("onOpenHit", null);
                                                  $.removeCookie("onOpenHit");
                                                  //alert("lost 1");

                                                  // $('.loading_container').css({'display':'block'});
                                                  // $('.loading_container .popup .popup_cont').text("You have lost the game!");

                                                   if(result.dealMeOut == 1){


                                                          $.cookie("rejoinPR", "0");
                                                          $.cookie("rejoin", "0");
                                                          $.cookie("onOpenHit", null);
                                                          $.removeCookie("onOpenHit");

                                                           setTimeout(function(){
                                                              location.reload();
//                                                              connection.close();
                                                          }, 3000);
                                                  }



                                                  setTimeout(function(){
                                                      $('.loading_container').hide();
                                                      $('.loading_container .popup .popup_cont').text();






                                                      /* calculate my loss and current balance */

                                                      var ajxData7454547787 = {'action': 'get-my-loss', roomId: roomIdCookie, sessionKey: sessionKeyCookie, player: userId};

                                                    $.ajax({
                                                      type: 'POST',
                                                      data: ajxData7454547787,
                                                      dataType: 'json',
                                                      cache: false,
                                                      url: 'ajax/getMyLoss.php',
                                                      success: function(result){

                                                         //alert('get my loss');

                                                         console.log("LOSS ", JSON.stringify(result));
                                                         console.log("lost chips ", result.lost_chips);
                                                       

                                                       
                                                        var countRejoin = 10;

                                                        var newCurrentBalanceCookie = result.balance_chips;
                                                       
                                                        
                                                        var chipDiff = parseFloat(chipsToTablePRCookie) - parseFloat(result.lost_chips);

                                                        console.log("chip Diff ", chipDiff);



                                                        if(parseFloat(minBuyingPRCookie) > parseFloat(chipDiff)){

                                                          //alert("minBuying less");

                                                          var chipsReqd = parseFloat(minBuyingPRCookie) - parseFloat(chipDiff);

                                                          $('#myModal').modal('show'); 
                                                          $('#myModal .joinPR').addClass('rejoinPr');

                                                         $('#myModal #bet_amount').text(betValue);
                                                         $('#myModal #min_buying').text(minBuyingPRCookie);
                                                         $('#myModal #current_balance').text(newCurrentBalanceCookie);
                                                         $('#myModal #chips_to_table').val(parseFloat(chipsReqd));

                                                          // $.cookie("chipsToTablePR", chipsToTablePRCookie);


                                                        }else if(parseFloat(minBuyingPRCookie) <= parseFloat(chipDiff)){

                                                          setTimeout(function(){

                                                          $.cookie("rejoinPR", "1");
                                                          $.cookie("rejoin", "0");

                                                          $.cookie("currentBalancePR", newCurrentBalanceCookie);
                                                          $.cookie("chipsToTablePR", chipDiff);


                                                          var intervalRejoin = setInterval(function(){

                                                         
                                                            $('.result_bottom').css({'display': 'block'});
                                                            $('.result_bottom').text('You will rejoin in '+countRejoin+' seconds');

                                                            if(countRejoin <= 0){
                                                              clearInterval(intervalRejoin);
                                                               setTimeout(function(){
                                                                  location.reload();
                                                              }, 3000);
                                                            }

                                                            countRejoin--;



                
                                                          },1000);

                                                        }, 3000);  




                                                      }


                                                    } });  
                                                     
                                                      
                                                   }, 4000);

                                                }




                                       } });

                                


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
                                              var scoreboardStatus = results.scoreboard_status;

                                              $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="result"]').text(scoreboardStatus);
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

                                              var signalOthers = {room:roomName, type: 'points-rummy-fso', message: 'points game get scoreboard others', winner: result.playerWon, playersLostArr: playersWhoLostArray, winningAmount: result.winningAmount};

                                                //connection.send(JSON.stringify(signalOthers));
                                                socket.emit(socketEventName, JSON.stringify(signalOthers));

                                                  if(parseInt(userId) === parseInt(result.playerWon)){

                                                    var ajxData704407 = {'action': 'update-real-wallet', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

                                                    $.ajax({
                                                        type: 'POST',
                                                        data: ajxData704407,
                                                        cache: false,
                                                        url: 'ajax/updateRealWalletPR.php',
                                                        success: function(results){
                                                            
                                                             // alert("Total chips coming.......................");
                                                             console.log(results);   
                                                        } }); 
                                                    
                                                   // $('.loading_container').css({'display':'block'});
                                                   // $('.loading_container .popup .popup_cont').text("You have won the game!");

                                                  //alert("won 2");

                                                  setTimeout(function(){
                                                      // $('.loading_container').hide();
                                                      // $('.loading_container .popup .popup_cont').text();

                                                     

                                                      if(result.dealMeOut == 1){


                                                          $.cookie("rejoinPR", "0");
                                                          $.cookie("rejoin", "0");
                                                          $.cookie("onOpenHit", null);
                                                          $.removeCookie("onOpenHit");

                                                          setTimeout(function(){
                                                              location.reload();
//                                                              connection.close();
                                                          }, 3000);
                                                         

                                                      }else{

                                                          $.cookie("rejoinPR", "1");
                                                          $.cookie("rejoin", "0");
                                                          $.cookie("onOpenHit", null);
                                                          $.removeCookie("onOpenHit");

                                                      }

                                                      var countRejoin = 10;

                                                       console.log("currentblnccookie", currentBalanceCookie);
                                                       console.log("chips to table ", chipsToTablePRCookie);

                                                     console.log("result winning amount ", result.winningAmount);

                                                      var newCurrentBalanceCookie = parseFloat(currentBalanceCookie) + parseFloat(result.winningAmount);

                                                      var newChipsToTablePRCookie = parseFloat(chipsToTablePRCookie) + parseFloat(result.winningAmount);

                                                       $.cookie("chipsToTablePR", newChipsToTablePRCookie);
                                                       $.cookie("currentBalancePR", newCurrentBalanceCookie);

                                                       console.log("new currentblnccookie", newCurrentBalanceCookie);
                                                       console.log("new chips to table ", newChipsToTablePRCookie);

                                                       



                                                      


                                                      var intervalRejoin = setInterval(function(){

                                                       
                                                      $('.result_bottom').css({'display': 'block'});
                                                      $('.result_bottom').text('You will rejoin in '+countRejoin+' seconds');

                                                        countRejoin--;

                                                        if(countRejoin <= 0){
                                                          clearInterval(intervalRejoin);
                                                           setTimeout(function(){
                                                            location.reload();
                                                          }, 3000);
                                                        }

                                                      



              
                                                      },1000);

                                                      
                                                   }, 4000);

                                                }else{

                                                  $.cookie("onOpenHit", null);
                                                  $.removeCookie("onOpenHit");
                                                 // alert("lost 2");

                                                  // $('.loading_container').css({'display':'block'});
                                                  // $('.loading_container .popup .popup_cont').text("You have lost the game!");


                                                  if(result.dealMeOut == 1){


                                                          $.cookie("rejoinPR", "0");
                                                          $.cookie("rejoin", "0");
                                                          $.cookie("onOpenHit", null);
                                                          $.removeCookie("onOpenHit");

                                                          setTimeout(function(){
                                                              location.reload();
//                                                              connection.close();
                                                          }, 3000);

                                                  }


                                                  setTimeout(function(){
                                                      $('.loading_container').hide();
                                                      $('.loading_container .popup .popup_cont').text();



                                                      /* calculate my loss and current balance */

                                                      var ajxData7454547787 = {'action': 'get-my-loss', roomId: roomIdCookie, sessionKey: sessionKeyCookie, player: userId};

                                                    $.ajax({
                                                      type: 'POST',
                                                      data: ajxData7454547787,
                                                      dataType: 'json',
                                                      cache: false,
                                                      url: 'ajax/getMyLoss.php',
                                                      success: function(result){

                                                         //alert('get my loss');

                                                         console.log("LOSS ", JSON.stringify(result));
                                                         console.log("lost chips ", result.lost_chips);
                                                       

                                                       
                                                        var countRejoin = 10;

                                                        var newCurrentBalanceCookie = result.balance_chips;
                                                       

                                                        var chipDiff = parseFloat(chipsToTablePRCookie) - parseFloat(result.lost_chips);

                                                           console.log("chip Diff ", chipDiff);

                                                        if(parseFloat(minBuyingPRCookie) > parseFloat(chipDiff)){

                                                          //alert("min buying less");

                                                          var chipsReqd = parseFloat(minBuyingPRCookie) - parseFloat(chipDiff);

                                                          $('#myModal').modal('show'); 
                                                          $('#myModal .joinPR').addClass('rejoinPr');

                                                         $('#myModal #bet_amount').text(betValue);
                                                         $('#myModal #min_buying').text(minBuyingPRCookie);
                                                         $('#myModal #current_balance').text(newCurrentBalanceCookie);
                                                         $('#myModal #chips_to_table').val(parseFloat(chipsReqd));

                                                          // $.cookie("chipsToTablePR", chipsToTablePRCookie);


                                                        }else if(parseFloat(minBuyingPRCookie) <= parseFloat(chipDiff)){

                                                          setTimeout(function(){

                                                          $.cookie("rejoinPR", "1");
                                                          $.cookie("rejoin", "0");

                                                          $.cookie("currentBalancePR", newCurrentBalanceCookie);
                                                          $.cookie("chipsToTablePR", chipDiff);

                                                          var intervalRejoin = setInterval(function(){

                                                         
                                                            $('.result_bottom').css({'display': 'block'});
                                                            $('.result_bottom').text('You will rejoin in '+countRejoin+' seconds');

                                                            if(countRejoin <= 0){
                                                              clearInterval(intervalRejoin);
                                                               setTimeout(function(){
                                                                  location.reload();
                                                              }, 3000);
                                                            }

                                                            countRejoin--;



                
                                                          }, 1000);

                                                         }, 3000); 


                                                      }


                                                    } })  
                                                     
                                                      
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
      

       $('.loading_container').hide();
       $('.loading_container .popup .popup_cont').text(""); 

       // alert("Here i am");
       playerCounterFlag = 0;
       intervalCounter = window.clearInterval(intervalCounter);

       $('.result_bottom').text("");
       gameStatusFlag = '';
       gameStatusArray.length = 0;

        playersPlayingTemp.length = 0;
        playersPlayingTemp = playersPlaying.slice();
        

         var connectionIssueCount = $.trim($.cookie("connectionIssue"));
        var netSpeed = $.trim($.cookie("netSpeed"));

         if(netSpeed <= 1){
            connectionIssueCount++;
            $.cookie("connectionIssue", connectionIssueCount);

         } 
        
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
                                  

                                  $('.current-player[data-user="'+parseInt(result.playersWhoLost)+'"]').hide();

                                  var $div = $(".current-player").filter(function() {
                                      return $(this).data("user") == parseInt(result.playersWhoLost);
                                  });

                                  $div.attr('data-user', '');



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

                          
                         


                          /*if(getItem(playersPlaying, parseInt(lastWinner)) ){
                            
                            nextPlayer = getItem(playersPlaying, parseInt(lastWinner));
                               
                          }else{
                            nextPlayer = playersPlaying[0];
                               
                          }*/

                           console.debug('New toss player');
                           console.log(playersPlayingTemp, lastWinner)
                          nextPlayer = findNextPlayer(playersPlayingTemp,parseInt(lastWinner));
                          

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
                                     

                                      $('.drop button').attr('disabled', false);
                                      $('.drop button').css({'cursor':'pointer'});
                                  }else{

                                       $('.drop button').attr('disabled', true);
                                       $('.drop button').css({'cursor':'default'});

                                
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


                                            // get next connected user
                                            var nextActiveUser = tossWinner;
                                            console.log("Tosswiner: ",tossWinner);
                                            if($.inArray(parseInt(tossWinner),dissconnectedUsers)>=0){
                                              var gotOne = true;

                                              console.log("FOund as dissconnected: ",tossWinner);

                                              $(playersPlaying).each(function(e,v){
                                                  if(($.inArray(parseInt(v),dissconnectedUsers) < 0) && gotOne){
                                                    nextActiveUser = parseInt(v);
                                                     console.log("New user is: ",nextActiveUser);
                                                    var ajxData260 = {'action': 'current-player', roomId: roomIdCookie, 
                                                    player: nextActiveUser, sessionKey: sessionKeyCookie };

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

                                                    gotOne = false;
                                                  }
                                              });
                                              tossWinner = nextActiveUser;
                                            }
                                            console.log("Final toss winer ",tossWinner);
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

                          
                           for(var j = 0; j < playersPlayingWholeGame.length; j++){ 

                           var ajxData703 = {'action': 'get-players', player: playersPlayingWholeGame[j]};

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


                                                           var signal14555 = {room:roomName, type: 'update-players-playing', message: 'updating players playing', playersPlayingTemp: playersPlayingTemp}
                                      
                                                           //connection.send(JSON.stringify(signal14555));    
                                                           socket.emit(socketEventName, JSON.stringify(signal14555));
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


                                                       var signal14555 = {room:roomName, type: 'update-players-playing', message: 'updating players playing', playersPlayingTemp: playersPlayingTemp}
                                  
                                                      // connection.send(JSON.stringify(signal14555));    
                                                       socket.emit(socketEventName, JSON.stringify(signal14555));
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


                                                       var signal14555 = {room:roomName, type: 'update-players-playing', message: 'updating players playing', playersPlayingTemp: playersPlayingTemp}
                                  
                                                       //connection.send(JSON.stringify(signal14555));    
                                                       socket.emit(socketEventName, JSON.stringify(signal14555));

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


                                                       var signal14555 = {room:roomName, type: 'update-players-playing', message: 'updating players playing', playersPlayingTemp: playersPlayingTemp}
                                  
                                                       //connection.send(JSON.stringify(signal14555));    
                                                       socket.emit(socketEventName, JSON.stringify(signal14555));
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
                          
                                  
                                   var signal444 = {room:roomName, type: 'next-round-process', message: 'request for next round process', playerToPlay:tossWinner, playersPlaying: playersPlaying, isLost: isLost};
                                  
                                   //connection.send(JSON.stringify(signal444));
                                   socket.emit(socketEventName, JSON.stringify(signal444));

                                       nextGameStartHandler(function(){

                                        
                                          $('.result_sec').css({'display': 'none'});
                                          $('.result_sec tbody[id="score_reports"] tr').remove();
                                          $('.leave_table_btn').attr('disabled', false);
                                       

                                       }); 


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
                                      
                                           /* Check if any of the players' score is less than or equal to 79 in 6 player pool */

                                                // if( (gameTypeCookie == "101" && gamePlayersCookie == "6") || (gameTypeCookie == "101" && gamePlayersCookie == "6")){

                                                // var ajxGetPlScore = {'action': 'get-pl-score', roomId: roomIdCookie, playerId: userId, sessionKey: sessionKeyCookie};

                                                // $.ajax({

                                                //     type: 'POST',
                                                //     url: 'ajax/getPlScore.php',
                                                //     cache: false,
                                                //     data: ajxGetPlScore,
                                                //     dataType: 'JSON',
                                                //     success: function(result){ 

                                                //        // alert("got other scores");
                                                //        console.log(result);
                                                //        console.log(Math.round(result['highestPoint']));

                                                        
                                                       
                                                //          if(Math.round(result['highestPoint']) <= 79){


                                                //                

                                                //                

                                                //              $('.loading_container').hide();
                                                //              $('.loading_container .popup .popup_cont').text();



                                                //               $('.popup_rejoin').show();
                                                //               $('.popup_rejoin .popup_with_button_cont p').text("Do you want to rejoin at " + parseFloat(Math.round(result['highestPoint']) + 1) + " points ?");           
                                                              
                                                //               $.cookie("rejoinScore", parseFloat( Math.round(result['highestPoint']) + 1) );


                                                //                // rejoinGameStartHandler(function(){

                                                                  

                                                //                //       connection.close();
                                                //                //       // location.reload();

                                                //                //     $('.popup_rejoin #rejoinBtn').attr('disabled', true);

                                                //                // });  

                                                            


                                                //          }else{

                                                //              connection.close();

                                                //               $('.loading_container').hide();
                                                //               $('.loading_container .popup .popup_cont').text();

                                                //             $('.popup_play_again').show();
                                                //             $('.popup_play_again .popup_with_button_cont p').text("Do you want to play again?");
                                                //          }







                                                       


                                                //     } })  



                                               

                                                // }

                                       } }); 

                                  }        



                              }, 6000);


                  }else if(gameStatusFlagMeld == "over"){
                      // send a signal 

                      var signal445 = {room:roomName, type: 'game-over-signal', message: 'game over', playerWon: playerWon};
                                  
                       //connection.send(JSON.stringify(signal445));
                       socket.emit(socketEventName, JSON.stringify(signal445));

                      if(parseInt(playerWon) == parseInt(userId)){
                          $('.loading_container').css({'display':'block'});
                          $('.loading_container .popup .popup_cont').text("You have won the game!");

                           setTimeout(function(){
                              $('.loading_container').hide();
                              $('.loading_container .popup .popup_cont').text();


                              $('.popup_play_again').show();
                              $('.popup_play_again .popup_with_button_cont p').text("Do you want to play again?");
                              
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


                              $('.popup_play_again').show();
                              $('.popup_play_again .popup_with_button_cont p').text("Do you want to play again?");
                              
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
                                      
                                         
                                            /* Check if any of the players' score is less than or equal to 79 in 6 player pool */

                                                // if( (gameTypeCookie == "101" && gamePlayersCookie == "6") || (gameTypeCookie == "101" && gamePlayersCookie == "6")){

                                                // var ajxGetPlScore = {'action': 'get-pl-score', roomId: roomIdCookie, playerId: userId, sessionKey: sessionKeyCookie};

                                                // $.ajax({

                                                //     type: 'POST',
                                                //     url: 'ajax/getPlScore.php',
                                                //     cache: false,
                                                //     data: ajxGetPlScore,
                                                //     dataType: 'JSON',
                                                //     success: function(result){ 

                                                //        // alert("got other scores");
                                                //        console.log(result);
                                                //        console.log(Math.round(result['highestPoint']));
                                                       
                                                //          if(Math.round(result['highestPoint']) <= 79){


                                                //                /*GUBHORONJON*/

                                                //              $('.loading_container').hide();
                                                //              $('.loading_container .popup .popup_cont').text();


                                                //               $('.popup_rejoin').show();
                                                //                $('.popup_rejoin .popup_with_button_cont p').text("Do you want to rejoin at " + parseFloat(Math.round(result['highestPoint']) + 1) + " points ?");        


                                                //                $.cookie("rejoinScore", parseFloat( Math.round(result['highestPoint']) + 1) );

                                                //                rejoinGameStartHandler(function(){

                                                                     
                                                //                       connection.close();
                                                                      


                                                //                       $('.popup_rejoin #rejoinBtn').attr('disabled', true);

                                                //                }); 

                                                //          }else{

                                                //              connection.close();

                                                //               $('.loading_container').hide();
                                                //               $('.loading_container .popup .popup_cont').text();

                                                //             $('.popup_play_again').show();
                                                //             $('.popup_play_again .popup_with_button_cont p').text("Do you want to play again?");
                                                //          }







                                                       


                                                //     } })  



                                               

                                                // }
                                        


                                       } });    





                              }, 10000 );
                                    

                      }

                      

                  }

                  
              }, 8000 );


         
       



  }