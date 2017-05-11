function leaveTableOnDisconnection(userLeft, nextPlayer){




      var roomIdCookie = $.cookie("room");
      var gamePlayersCookie = $.cookie("game-players");
      var creatorCookie = $.cookie("creator");
      var gameTypeCookie = $.cookie("game-type");
      var sessionKeyCookie = $.trim($.cookie("sessionKey"));

      var chipsToTablePRCookie = $.trim($.cookie("chipsToTablePR"));
      var currentBalanceCookie = $.trim($.cookie("currentBalancePR"));
      var minBuyingPRCookie = $.trim($.cookie("minBuyingPR"));
      var betValueCookie = $.trim($.cookie("betValue"));
      var netSpeed = $.trim($.cookie("netSpeed"));






   
     var userLeft = userLeft;
     var nextPlayerGet = nextPlayer;
    //var creatorLeft = creator;

      console.log("hit function 1");
     


    /* Check if room is full. If room is full then game already started */

     var ajxDataCheckRoomFull = {'action': 'check-room-status', roomId: roomIdCookie, sessionKey: sessionKeyCookie, player: userLeft};

      $.ajax({
          type: 'POST',
          url: 'ajax/checkRoomStatus.php',
          cache: false,
          dataType: 'json',
          data: ajxDataCheckRoomFull,
          success: function(result){ 
           
             console.log("Return result =================== ", result);


            if(result.playerCount == 1 && result.roomStatus != "started"){

              $('.current-player[data-user="'+parseInt(userLeft)+'"]').hide();

              /* delete room */

              var ajxDataDeleteRoom = {'action': 'delete-room', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

              $.ajax({
                  type: 'POST',
                  url: 'ajax/deleteRoom.php',
                  cache: false,
                  data: ajxDataDeleteRoom,
                  success: function(result){ 
                     console.log(result);

                      if(removeCardFromGroups(parseInt(userLeft), playersPlaying)){
                        console.log("player removed ", playersPlaying);
                      }

                      playersPlayingTemp = playersPlaying.slice();

                     if($.trim(result) == "ok"){
                        $.cookie("roomCreated", "1");
                        location.reload();
                     }

                  } });   
            
            }else if(result.playerCount == 1 && result.roomStatus == "started"){


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





                   var ajxDataCheckDropType = {'action': 'check-drop-type', roomId: roomIdCookie, sessionKey: sessionKeyCookie, player: userLeft};

                    $.ajax({
                      type: 'POST',
                      data: ajxDataCheckDropType,
                      cache: false,
                      url: 'ajax/checkDropType.php',
                      success: function(count){
                       
                        if((count == 0 || count == 1) && (gameTypeCookie != "score")){ // pool game 

                          /* update my point to 80 */

                          $('.result_sec').show();

                          var points = 80;

                           var ajxData852 = { 'action': 'meld-card-validation-no-group', roomId: roomIdCookie, player: userLeft, gameType: gameTypeCookie, sessionKey: sessionKeyCookie, betValue: betValueCookie, points: points};

                          $.ajax({
                            type: 'POST',
                            data: ajxData852,
                            cache: false,
                            url: 'ajax/meldCardValidationNoGroup.php',
                            success: function(totalPoints){

                              console.log(totalPoints);


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


                               /* Show scoreboard */

                               for(var i = 0; i < playersPlayingWholeGame.length; i++){

                                  console.log("doing for ", playersPlayingWholeGame[i]);


                                     /* Get user names  */

                                      /* update player has melded */
       
                                      var ajxData853 = { 'action': 'update-player-melded', roomId: roomIdCookie, player: playersPlayingWholeGame[i], sessionKey: sessionKeyCookie};


                                     $.ajax({
                                            type: 'POST',
                                            data: ajxData853,
                                            cache: false,
                                            url: 'ajax/updatePlayerMelded.php',
                                            success: function(result){
                                                console.log(result);
                                            } });






                                      var ajxData856 = { 'action': 'get-players', player: playersPlayingWholeGame[i]};


                                      $.ajax({
                                          type: 'POST',
                                          data: ajxData856,
                                          dataType: 'json',
                                          cache: false,
                                          url: 'ajax/getAllPlayers.php',
                                          success: function(player){

                                              /* meld update */

                               


                                              if(gameTypeCookie != "score"){

                                                  console.log(player.id + ' ' + player.name);

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

                                                        console.log("results ", results);
                                                        console.log("player check ", player.id);

                                                         if(parseInt(player.id) == parseInt(userId)){
                                                            var points = 0;
                                                             var totalPts = results.total_points;
                                                             var status = "<img src='images/winner.png'>";
                                                         }else{
                                                             var points = results.points;
                                                             var totalPts = results.total_points;
                                                             var status = "lost";
                                                         }

                                                          

                                                       

                                                         if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                           $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]').html(showCardsInScoreboard(player.id, roomIdCookie, sessionKeyCookie));

                                                         }




                                                         


                                                          $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="result"]').html(status);


                                                          $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="score"]').text(Math.round(points));
                                                      
                                                          $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_score"]').text(Math.round(totalPts));



                                                      }
                                                      
                                                   });       

                                             }   

                                          }
                                      })        




                                  }


                                
                                 setTimeout(function(){
                                    

                                    $('.loading_container').css({'display':'block'});
                                    $('.loading_container .popup .popup_cont').text("You have won the game!");

                                  }, 2000);



                                 setTimeout(function(){
                                    $('.popup_play_again').show();
                                    $('.popup_play_again .popup_with_button_cont p').text("Do you want to play again?");

                                    $('.loading_container').hide();
                                    $('.loading_container .popup .popup_cont').text();

                                     if(removeCardFromGroups(parseInt(userLeft), playersPlaying)){
                                      console.log("player removed ", playersPlaying);
                                    }

                                   playersPlayingTemp = playersPlaying.slice();
                                 
                                 }, 5000);


                                 

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



                                  




                            } })  





                       }else if(count == 0 && gameTypeCookie == "score"){



                           /* update my point to 80 */

                          $('.result_sec').show();

                          var points = 40;

                           var ajxData852 = { 'action': 'meld-card-validation-no-group', roomId: roomIdCookie, player: userLeft, gameType: gameTypeCookie, sessionKey: sessionKeyCookie, betValue: betValueCookie, points: points};

                          $.ajax({
                            type: 'POST',
                            data: ajxData852,
                            cache: false,
                            url: 'ajax/meldCardValidationNoGroup.php',
                            success: function(totalPoints){

                              console.log(totalPoints);


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


                               /* Show scoreboard */

                               for(var i = 0; i < playersPlayingWholeGame.length; i++){

                                  console.log("doing for ", playersPlayingWholeGame[i]);


                                     /* Get user names  */

                                      /* update player has melded */
       
                                      var ajxData853 = { 'action': 'update-player-melded', roomId: roomIdCookie, player: playersPlayingWholeGame[i], sessionKey: sessionKeyCookie};


                                     $.ajax({
                                            type: 'POST',
                                            data: ajxData853,
                                            cache: false,
                                            url: 'ajax/updatePlayerMelded.php',
                                            success: function(result){
                                                console.log(result);
                                            } });






                                      var ajxData856 = { 'action': 'get-players', player: playersPlayingWholeGame[i]};


                                      $.ajax({
                                          type: 'POST',
                                          data: ajxData856,
                                          dataType: 'json',
                                          cache: false,
                                          url: 'ajax/getAllPlayers.php',
                                          success: function(player){

                                              /* meld update */

                               


                                              if(gameTypeCookie == "score"){

                                                 console.log(player.id + ' ' + player.name);

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

                                                    console.log("results ", results);
                                                    console.log("player check ", player.id);

                                                    if(parseInt(player.id) != parseInt(userId)){
                                                           
                                                       var points = results.points;
                                                       var totalPts = results.total_points;
                                                       var status = "lost";
                                                        


                                                      

                                                       if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                         $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]').html(showCardsInScoreboard(player.id, roomIdCookie, sessionKeyCookie));

                                                     }


                                                      $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="result"]').html(status);


                                                      $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="count"]').text(Math.round(points));
                                                  
                                                      $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_chips"]').text('-'+totalPts);


                                                      } 



                                                  }
                                                  
                                               });       

                                             }   

                                          }
                                      })        




                                  }




                                setTimeout(function(){  


                              /*  get my calculated score */  

                                  var ajxDataGetPlScore = {'action': 'get-all-scores-pr', roomId: roomIdCookie, sessionKey: sessionKeyCookie, betValue: betValueCookie, playerWon: userId};

                                  $.ajax({
                                    type: 'POST',
                                    data: ajxDataGetPlScore,
                                    dataType: 'json',
                                    cache: false,
                                    url: 'ajax/getAllScoresPR.php',
                                    success: function(result){
                                      console.log(result);
                                    

                                    $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="count"]').text(0);

                                     $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="result"]').html("<img src='images/winner.png'>");



                                    $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="total_chips"]').text('+'+(result.winningAmount).toFixed(2));

                                    if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="cards"]' .playingCards).length == 0 ){

                                       $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="cards"]').html(showCardsInScoreboard(userId, roomIdCookie, sessionKeyCookie));

                                    }

                                      setTimeout(function(){
                                    

                                    $('.loading_container').css({'display':'block'});
                                    $('.loading_container .popup .popup_cont').text("You have won the game!");

                                  }, 2000);



                                 setTimeout(function(){
                                   
                                    $('.loading_container').hide();
                                    $('.loading_container .popup .popup_cont').text();

                                     if(removeCardFromGroups(parseInt(userLeft), playersPlaying)){
                                      console.log("player removed ", playersPlaying);
                                    }

                                      playersPlayingTemp = playersPlaying.slice();


                                      $.cookie("rejoinPR", "1");
                                      $.cookie("rejoin", "0");
                                      $.cookie("onOpenHit", null);
                                      $.removeCookie("onOpenHit");

                                      var countRejoin = 10;

                                      

                                       console.log("currentblnccookie", currentBalanceCookie);
                                       console.log("chips to table ", chipsToTablePRCookie);

                                      console.log("result winning amount ", result.winningAmount.toFixed(2));                 

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

                                 
                                 }, 5000);


                                 

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





                                  } }); 


                              }, 3000);    


                            } });  







                       }else if(count == 1 && gameTypeCookie == "score"){



                           /* update my point to 80 */

                          $('.result_sec').show();

                          var points = 80;

                           var ajxData852 = { 'action': 'meld-card-validation-no-group', roomId: roomIdCookie, player: userLeft, gameType: gameTypeCookie, sessionKey: sessionKeyCookie, betValue: betValueCookie, points: points};

                          $.ajax({
                            type: 'POST',
                            data: ajxData852,
                            cache: false,
                            url: 'ajax/meldCardValidationNoGroup.php',
                            success: function(totalPoints){

                              console.log(totalPoints);


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


                               /* Show scoreboard */

                               for(var i = 0; i < playersPlayingWholeGame.length; i++){

                                  console.log("doing for ", playersPlayingWholeGame[i]);


                                     /* Get user names  */

                                      /* update player has melded */
       
                                      var ajxData853 = { 'action': 'update-player-melded', roomId: roomIdCookie, player: playersPlayingWholeGame[i], sessionKey: sessionKeyCookie};


                                     $.ajax({
                                            type: 'POST',
                                            data: ajxData853,
                                            cache: false,
                                            url: 'ajax/updatePlayerMelded.php',
                                            success: function(result){
                                                console.log(result);
                                            } });






                                      var ajxData856 = { 'action': 'get-players', player: playersPlayingWholeGame[i]};


                                      $.ajax({
                                          type: 'POST',
                                          data: ajxData856,
                                          dataType: 'json',
                                          cache: false,
                                          url: 'ajax/getAllPlayers.php',
                                          success: function(player){

                                              /* meld update */

                               


                                              if(gameTypeCookie == "score"){

                                                 console.log(player.id + ' ' + player.name);

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

                                                    console.log("results ", results);
                                                    console.log("player check ", player.id);

                                                    if(parseInt(player.id) != parseInt(userId)){
                                                           
                                                       var points = results.points;
                                                       var totalPts = results.total_points;
                                                       var status = "lost";
                                                        


                                                      

                                                    if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                         $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]').html(showCardsInScoreboard(player.id, roomIdCookie, sessionKeyCookie));

                                                     }


                                                      $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="result"]').html(status);


                                                      $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="count"]').text(Math.round(points));
                                                  
                                                      $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_chips"]').text('-'+totalPts);


                                                      } 




                                                  }
                                                  
                                               });       

                                             }   

                                          }
                                      })        




                                  }


                                    setTimeout(function(){  


                              /*  get my calculated score */  

                                  var ajxDataGetPlScore = {'action': 'get-all-scores-pr', roomId: roomIdCookie, sessionKey: sessionKeyCookie, betValue: betValueCookie, playerWon: userId};

                                  $.ajax({
                                    type: 'POST',
                                    data: ajxDataGetPlScore,
                                    dataType: 'json',
                                    cache: false,
                                    url: 'ajax/getAllScoresPR.php',
                                    success: function(result){
                                      console.log(result);
                                     // alert("Calculated");

                                    $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="count"]').text(0);

                                     $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="result"]').html("<img src='images/winner.png'>");

                                    $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="total_chips"]').text('+'+(result.winningAmount).toFixed(2));

                                    if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="cards"]' .playingCards).length == 0 ){

                                       $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="cards"]').html(showCardsInScoreboard(userId, roomIdCookie, sessionKeyCookie));

                                    }


                                 setTimeout(function(){
                                    

                                    $('.loading_container').css({'display':'block'});
                                    $('.loading_container .popup .popup_cont').text("You have won the game!");

                                  }, 2000);



                                 setTimeout(function(){
                                  

                                    $('.loading_container').hide();
                                    $('.loading_container .popup .popup_cont').text();

                                     if(removeCardFromGroups(parseInt(userLeft), playersPlaying)){
                                      console.log("player removed ", playersPlaying);
                                    }

                                   playersPlayingTemp = playersPlaying.slice();


                                      $.cookie("rejoinPR", "1");
                                      $.cookie("rejoin", "0");
                                      $.cookie("onOpenHit", null);
                                      $.removeCookie("onOpenHit");

                                      var countRejoin = 10;

                                       console.log("currentblnccookie", currentBalanceCookie);
                                       console.log("chips to table ", chipsToTablePRCookie);

                                      console.log("result winning amount ", result.winningAmount.toFixed(2));                 

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



                                 
                                    }, 5000);


                                 

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




                                  } }); 


                              }, 3000); 

                            } })  







                       }

                     } })              



            }else if(result.playerCount > 1 && result.roomStatus == "started"){

                // alert("first check");

                 var ajxDataCheckDropType = {'action': 'check-drop-type', roomId: roomIdCookie, sessionKey: sessionKeyCookie, player: userLeft};

                  $.ajax({
                      type: 'POST',
                      data: ajxDataCheckDropType,
                      cache: false,
                      url: 'ajax/checkDropType.php',
                      success: function(count){




                        console.log("COUNT ====== ", count);
                        console.log("Room status ====== ", result.roomStatus);
                       // alert("here");


                  if((count == 0 || count == 1) && (gameTypeCookie != "score")){

                      // alert("this is the area!");

                     
                      console.log("next pL 2 : ", nextPlayerGet);


                     $('.current-player[data-user="'+parseInt(userLeft)+'"]').hide();

                      var points = 80;

                       var ajxData852 = { 'action': 'meld-card-validation-no-group', roomId: roomIdCookie, player: userLeft, gameType: gameTypeCookie, sessionKey: sessionKeyCookie, betValue: betValueCookie, points: points};

                      $.ajax({
                        type: 'POST',
                        data: ajxData852,
                        cache: false,
                        url: 'ajax/meldCardValidationNoGroup.php',
                        success: function(totalPoints){

                          console.log(totalPoints);

                            var ajxData853 = { 'action': 'update-player-melded', roomId: roomIdCookie, player: userLeft, sessionKey: sessionKeyCookie};


                           $.ajax({
                                  type: 'POST',
                                  data: ajxData853,
                                  cache: false,
                                  url: 'ajax/updatePlayerMelded.php',
                                  success: function(result){
                                      console.log(result);

                                        if(removeCardFromGroups(parseInt(userLeft), playersPlayingTemp)){
                                          console.log("player removed ", playersPlayingTemp);
                                       }

                                       
                                       if(removeCardFromGroups(parseInt(userLeft), playersPlaying)){
                                          console.log("player removed ", playersPlaying);
                                       }







                                        var ajxDataMeldedCount = { 'action': 'get-melded-status', roomId: roomIdCookie, sessionKey: sessionKeyCookie};


                                            $.ajax({
                                              type: 'POST',
                                              data: ajxDataMeldedCount,
                                              cache: false,
                                              url: 'ajax/getPlayersMeldedCount.php',
                                              success: function(count){
                                                
                                               

                                                     if( (playersPlaying.length - count) <= 1 ){

                                                      
                                                        console.log("ppying ", playersPlaying);

                                                        $('.result_sec').show();


                                                           



                                                        if(parseInt(nextPlayerGet) == parseInt(userId)){

                                                            //alert("hey matched!");



                                                            var ajxData853 = { 'action': 'update-player-melded', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};


                                                            $.ajax({
                                                                type: 'POST',
                                                                data: ajxData853,
                                                                cache: false,
                                                                url: 'ajax/updatePlayerMelded.php',
                                                                success: function(result){
                                                                    console.log(result);


                                                            } });   





                                                        var ajxData852 = { 'action': 'update-melded-count', roomId: roomIdCookie, sessionKey: sessionKeyCookie};


                                                         $.ajax({
                                                                type: 'POST',
                                                                data: ajxData852,
                                                                cache: false,
                                                                url: 'ajax/updateMeldedCount.php',
                                                                success: function(result){
                                                                    console.log(result);
                                                            } });   




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

                                                            var ajxDataLost = { 'action': 'update-player-scoreboard-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie, status:"won"};

                                                            // sneha safui
                                                             $.ajax({
                                                                    type: 'POST',
                                                                    data: ajxDataLost,
                                                                    cache: false,
                                                                    url: 'ajax/updatePlayerScoreboardStatus.php',
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


                                                                    var ajxData855 = { 'action': 'success-melding', roomId: roomIdCookie, player: userId, gameType: gameTypeCookie, sessionKey: sessionKeyCookie, betValue: betValueCookie};


                                                                    $.ajax({
                                                                        type: 'POST',
                                                                        data: ajxData855,
                                                                        cache: false,
                                                                        url: 'ajax/successfulMelding.php',
                                                                        success: function(totalPoints){


                                                                  for(var i = 0; i < playersPlayingWholeGame.length; i++){

                                                                      console.log("doing for ", playersPlayingWholeGame[i]);


                                                                       /* Get user names  */

                                                                        var ajxData856 = { 'action': 'get-players', player: playersPlayingWholeGame[i]};


                                                                        $.ajax({
                                                                            type: 'POST',
                                                                            data: ajxData856,
                                                                            dataType: 'json',
                                                                            cache: false,
                                                                            url: 'ajax/getAllPlayers.php',
                                                                            success: function(player){

                                                                                if(gameTypeCookie != "score"){

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

                                                                               }


                                                                              } })


                                                                           }


                                                                               

                                                                


                                                                        } })   


                                                                setTimeout(function(){


                                                                      var status = "rightshow";
                                                                     meldingProcess = 1;


                                                                        var signal102 = {room:roomName, type: 'get-scoreboard', message: 'get all your scoreboards', firstMelder: userId, status: status, playersPlayingTemp: playersPlayingTemp, leaveTable: true};


                                                                        //connection.send(JSON.stringify(signal102));
                                                                        socket.emit(socketEventName, JSON.stringify(signal102));
                                                                    }, 4000);
                                          



                                                                }   



                                                 }else if( (playersPlaying.length - count) > 1 ){

                                                      //alert("1");

                                                      if(parseInt(nextPlayerGet) == parseInt(userId)){
                                                        $('.cardDeckSelect').addClass("clickable");
                                                        $('.cardDeckSelect').removeClass("noSelect");
                                                        cardPull = 0;
                                                        cardDiscard = 0;

                                                        //$('#cardDeckSelect'+userLeft).attr('id', 'cardDeckSelect'+userId);
                                                        $('#cardDeckSelectShow'+userLeft).attr('id', 'cardDeckSelectShow'+userId);

                                                           var PlayerCounterHandler = new playerCounterHandler(parseInt(nextPlayerGet));
                                          
                                                          PlayerCounterHandler.playerCounter = 30;
                                                          PlayerCounterHandler.run();
                                                          intervalCounter = setInterval(PlayerCounterHandler.updateCounter, 1000); 

                                                    
                                                    // sneha bhowmick


                                                     }

                                               }      



                                        } });  



                                    } });




                        } });


                   }else if(count == 0 && gameTypeCookie == "score"){

                      $('.current-player[data-user="'+parseInt(userLeft)+'"]').hide();

                      var points = 40;

                       var ajxData852 = { 'action': 'meld-card-validation-no-group', roomId: roomIdCookie, player: userLeft, gameType: gameTypeCookie, sessionKey: sessionKeyCookie, betValue: betValueCookie, points: points};

                      $.ajax({
                        type: 'POST',
                        data: ajxData852,
                        cache: false,
                        url: 'ajax/meldCardValidationNoGroup.php',
                        success: function(totalPoints){

                          console.log(totalPoints);

                            var ajxData853 = { 'action': 'update-player-melded', roomId: roomIdCookie, player: userLeft, sessionKey: sessionKeyCookie};


                                $.ajax({
                                  type: 'POST',
                                  data: ajxData853,
                                  cache: false,
                                  url: 'ajax/updatePlayerMelded.php',
                                  success: function(result){
                                      console.log(result);

                                        if(removeCardFromGroups(parseInt(userLeft), playersPlayingTemp)){
                                          console.log("player removed ", playersPlayingTemp);
                                       }

                                       //playersPlaying = playersPlayingTemp.slice();

                                        var ajxDataMeldedCount = { 'action': 'get-melded-status', roomId: roomIdCookie, sessionKey: sessionKeyCookie};


                                            $.ajax({
                                              type: 'POST',
                                              data: ajxDataMeldedCount,
                                              cache: false,
                                              url: 'ajax/getPlayersMeldedCount.php',
                                              success: function(count){


                                                 if( (playersPlaying.length - count) <= 1 ){

                                                    $('.result_sec').show();

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


                                                     // 2. update my scoreboard
                                                            
                                                    var ajxData853 = { 'action': 'update-player-melded', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};


                                                    $.ajax({
                                                        type: 'POST',
                                                        data: ajxData853,
                                                        cache: false,
                                                        url: 'ajax/updatePlayerMelded.php',
                                                        success: function(result){
                                                            console.log(result);



                                                        for(var i = 0; i < playersPlayingWholeGame.length; i++){

                                                          console.log("doing for ", playersPlayingWholeGame[i]);


                                                             /* Get user names  */

                                    
                                                              var ajxData856 = { 'action': 'get-players', player: playersPlayingWholeGame[i]};


                                                              $.ajax({
                                                                  type: 'POST',
                                                                  data: ajxData856,
                                                                  dataType: 'json',
                                                                  cache: false,
                                                                  url: 'ajax/getAllPlayers.php',
                                                                  success: function(player){

                                                                      /* meld update */

                                                       


                                                                      if(gameTypeCookie == "score"){

                                                                         console.log(player.id + ' ' + player.name);

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

                                                                            console.log("results ", results);
                                                                            console.log("player check ", player.id);

                                                                            if(parseInt(player.id) != parseInt(userId)){
                                                                                   
                                                                               var points = results.points;
                                                                               var totalPts = results.total_points;
                                                                               var status = "lost";
                                                                                


                                                                              

                                                                            if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                                                 $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]').html(showCardsInScoreboard(player.id, roomIdCookie, sessionKeyCookie));

                                                                             }


                                                                              $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="result"]').html(status);


                                                                              $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="count"]').text(Math.round(points));
                                                                          
                                                                              $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_chips"]').text('-'+totalPts);


                                                                              } 

                    


                                                                          }
                                                                          
                                                                       });       

                                                                     }   

                                                                  }
                                                              })        




                                                          }




                                                        }
                                                        
                                                    });        




                                                 }else if( (playersPlaying.length - count) > 1 ){

                                                      //alert("2");

                                                      if(parseInt(nextPlayerGet) == parseInt(userId)){
                                                        $('.cardDeckSelect').addClass("clickable");
                                                        $('.cardDeckSelect').removeClass("noSelect");
                                                        cardPull = 0;
                                                        cardDiscard = 0;

                                                        //$('#cardDeckSelect'+userLeft).attr('id', 'cardDeckSelect'+userId);
                                                        $('#cardDeckSelectShow'+userLeft).attr('id', 'cardDeckSelectShow'+userId);

                                                           var PlayerCounterHandler = new playerCounterHandler(parseInt(nextPlayerGet));
                                          
                                                          PlayerCounterHandler.playerCounter = 30;
                                                          PlayerCounterHandler.run();
                                                          intervalCounter = setInterval(PlayerCounterHandler.updateCounter, 1000); 

                                                    
                                                    // sneha bhowmick


                                                     }

                                               }


                                           } });        



                                      } });

                                




                        } });

                   }else if(count == 1 && gameTypeCookie == "score"){

                        $('.current-player[data-user="'+parseInt(userLeft)+'"]').hide();

                        var points = 80;

                         var ajxData852 = { 'action': 'meld-card-validation-no-group', roomId: roomIdCookie, player: userLeft, gameType: gameTypeCookie, sessionKey: sessionKeyCookie, betValue: betValueCookie, points: points};

                        $.ajax({
                          type: 'POST',
                          data: ajxData852,
                          cache: false,
                          url: 'ajax/meldCardValidationNoGroup.php',
                          success: function(totalPoints){

                            console.log(totalPoints);

                              var ajxData853 = { 'action': 'update-player-melded', roomId: roomIdCookie, player: userLeft, sessionKey: sessionKeyCookie};


                                $.ajax({
                                  type: 'POST',
                                  data: ajxData853,
                                  cache: false,
                                  url: 'ajax/updatePlayerMelded.php',
                                  success: function(result){
                                      console.log(result);

                                       if(removeCardFromGroups(parseInt(userLeft), playersPlayingTemp)){
                                          console.log("player removed ", playersPlayingTemp);
                                       }

                                       playersPlaying = playersPlayingTemp.slice();

                                     if(parseInt(nextPlayerGet) == parseInt(userId)){

                                       //alert("3")

                                        $('.cardDeckSelect').addClass("clickable");
                                        $('.cardDeckSelect').removeClass("noSelect");
                                         cardPull = 0;
                                         cardDiscard = 0;

                                         //$('#cardDeckSelect'+userLeft).attr('id', 'cardDeckSelect'+userId);
                                         $('#cardDeckSelectShow'+userLeft).attr('id', 'cardDeckSelectShow'+userId);
                                    }


                                         var PlayerCounterHandler = new playerCounterHandler(parseInt(nextPlayerGet));
                                          
                                          PlayerCounterHandler.playerCounter = 30;
                                          PlayerCounterHandler.run();
                                          intervalCounter = setInterval(PlayerCounterHandler.updateCounter, 1000);



                                      } });




                          } });

                   }

                   } })  

            }else if(result.playerCount > 1 && result.roomStatus != "started"){

                $('.current-player[data-user="'+parseInt(userLeft)+'"]').hide();

                if(removeCardFromGroups(parseInt(userLeft), playersPlayingTemp)){
                    console.log("player removed ", playersPlayingTemp);
                }

                playersPlaying = playersPlayingTemp.slice();
                

            }   


             
              
      } }); 

     // var signalReConnectOthers = {type: 'reconnect-others', message: 'starting reconnect others....', nextPlayer: parseInt(nextPlayerGet), userLeft: userLeft};
     // connection.send(JSON.stringify(signalReConnectOthers));

     var signalStartCounterDisconnection = {room:roomName, type: 'start-counter-discard-disconnection', message: 'starting counter....', player: parseInt(nextPlayerGet), userLeft: parseInt(userLeft), counterTime: 30};
     
     //connection.send(JSON.stringify(signalStartCounterDisconnection));
     socket.emit(socketEventName, JSON.stringify(signalStartCounterDisconnection));



}



            



            