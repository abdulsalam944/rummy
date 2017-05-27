socket.on('joinRoom', function(e){
  console.log('Message Recieved: ',e);
  if(e=="Connected."){
    onOpen();
  }
});
socket.on(socketEventName, function(e){



          console.log('Message Recieved: ',e);

          console.log(e);
          var dataReceived = JSON.parse(e);

          console.log('Message Recieved - ',dataReceived, roomName);

          //checking whether recieve dissconnet signal and dissconnected user is in this room
          if(dataReceived.type=="code" && dataReceived.msg=="dissconnected"){
            console.log('Players playing '+playersPlaying);
            console.log("Recieve dissconnect signal, dissconnect user is : "+dataReceived.userid);
            
            /*
              Check whether user closed tab/window or internet dissconnects
            */

            setTimeout(function(){
              dataToSend = {room:roomName,user:dataReceived.userid};

              $.post('ajax/check_dissconnect_status.php',dataToSend,function(data){

               // data = data.trim()
               // console.log('Data recieve after dissconnect signal ',data);   
                
               console.log(JSON.parse(data));
               data = JSON.parse(data);
                if(data.user_id!=""){
                    if($.inArray(data.user_id,playersPlaying)){
                      console.log('User is playing in this room.',dissconnectedUsers,data.user_id,dissconnectedUsers.indexOf(data.user_id));
                        if(dissconnectedUsers.indexOf(data.user_id)<0){
                          console.log('Not dissconnected before',data);
                          if(data.self_dis == 1){
                              console.log('Other user closed the game.');  
                              leaveTable();
                          }else{
                              console.log('Other user internet gone');

                              var curUserId = parseInt(data.user_id);
                              if(curUserId!="NaN"){
                                dissconnectedUsers.push(curUserId); 
                                console.log('pushed ', curUserId);
                              }else{
                                  console.log('Not pushed ',curUserId);
                              } 
                          }
                        }else{
                          console.log('User dissconnected before.');
                        }
                    }else{
                        console.log('dissconnected user not found in db. ');
                    }
                }else{
                    console.log('dissconnected user not found in db. ');
                }


              });
            },2000);

            /*
            $.post('ajax/getUserIdFromSocketId.php',{room:roomName,user:dataReceived.userid},function(data){
              console.log('Getting id after dissconnect one user.',data);
              var tempData = JSON.parse(data);

            
              alert(dataReceived.userid);
              disconnected(dataReceived.userid);
              return;
            
             // disconnected();
             
            });
            */
            
          }

          // added to check current messge is for own room or other
          if(dataReceived.room==roomName){ 
            console.log('Message from room: '+dataReceived.room+' my room '+roomName);
           // return; 


           if(dataReceived.type=="code" && dataReceived.msg=="deck-show-card-refresh"){
              if(parseInt(userId)==dataReceived.updateTouser){

                $('.card-throw .playingCards').html(dataReceived.showcards);

                $('.card-throw .playingCards .cardDeckSelect').prop('id','cardDeckSelectShow'+parseInt(userId));
                $('.card-throw .playingCards .cardDeckSelect').removeClass('clickable').addClass('noSelect');


              }
           }


           if(dataReceived.type=="code" && dataReceived.msg=="re-connect"){


              $.post('ajax/update_old_connectionid_with_new.php',{old:dataReceived.oldid,new:dataReceived.newid},function(data){
                  var thisUserId = data.trim();
                  console.log('thisUserId',thisUserId);

                  console.log(dissconnectedUsers);

                  var reOrderArray = [];

                  $(dissconnectedUsers).each(function(e,j){
                    console.log(e,j);
                    if(parseInt(j)==parseInt(thisUserId)){
                        delete dissconnectedUsers[e];
                        console.log('User Detected....!!');


                        //update show card from deck
                        var msgToSend = {room:roomName, type: 'code', msg: 'deck-show-card-refresh',showcards:$('.card-throw .playingCards').clone().html().trim(),updateTouser:parseInt(thisUserId)};
                        socket.emit(socketEventName, JSON.stringify(msgToSend));


                        $.post('ajax/checkWhoIsCurrentPlayer.php',{room:roomName},function(data){

                            var NewData = parseInt(data.trim());
                            if(NewData!=0){


                                // Resetting Auto Play to Zero
                                  $.cookie("connectionIssue", 0);
                                  var roomIdCookie = $.cookie("room");
                                  var sessionKeyCookie = $.trim($.cookie("sessionKey"));
                                  var ajxDataUpdateHandCount = {'action': 'update-hand-count', roomId: roomIdCookie, sessionKey: sessionKeyCookie, player: thisUserId};

                                  $.ajax({
                                      type: 'POST',
                                      data: ajxDataUpdateHandCount,
                                      cache: false,
                                      url: 'ajax/updateHandCount.php',
                                      success: function(result){
                                         // console.log("hand count updated to 0");

                                  } })  ;



                                  if(thisUserId==NewData){

                                    reconnectedUser = parseInt(thisUserId);

                                  }


                                // if(thisUserId==NewData){
                                //   // restart counter

                                //   intervalCounter = window.clearInterval(intervalCounter);
                                //   var PlayerCounterHandler = new playerCounterHandler(thisUserId);
                                //   PlayerCounterHandler.playerCounter = 30;
                                //   PlayerCounterHandler.run();
                                //   intervalCounter = setInterval(PlayerCounterHandler.updateCounter, 1000); 

                                  

                                // }
                            }


                        })

                        


                    }else{
                        reOrderArray.push(j);
                    }
                  });
                  dissconnectedUsers = reOrderArray;
                  // if($.inArray(thisUserId,dissconnectedUsers)){       
                  // console.log(dissconnectedUsers);                                       
                  //     var thisIndex = dissconnectedUsers.indexOf(parseString(thisUserId));
                  //     console.log('Removing ',dissconnectedUsers,thisUserId);
                  //     delete dissconnectedUsers[parseString(thisIndex)];                      
                  //   }
              });
           


             console.log(dataReceived);
           }


          }else{
            console.log('Other room data recieved ',dataReceived.room, 'my room '+roomName);
            return; 
          }

          




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



          var tossArray = [];

         

              // console.log(dataReceived);

         if(dataReceived.type == "drop-and-go"){
                var userDropped = dataReceived.userDropped;
                var nextPlayerGet = dataReceived.nextPlayer;   
               

                
                if(removeCardFromGroups(parseInt(userDropped), playersPlayingTemp)){
                    console.log("player removed ", playersPlayingTemp);
                }

                playersPlaying = playersPlayingTemp.slice();

                 if(parseInt(nextPlayerGet) == parseInt(userId)){
                    $('.cardDeckSelect').addClass("clickable");
                    $('.cardDeckSelect').removeClass("noSelect");
                     cardPull = 0;
                     cardDiscard = 0;

                     $('#cardDeckSelect'+userDropped).attr('id', 'cardDeckSelect'+userId);
                     $('#cardDeckSelectShow'+userDropped).attr('id', 'cardDeckSelectShow'+userId);

                     // var signalBack = {type: 'drop-and-go-back'};
                     // connection.send(JSON.stringify(signalBack));
                 
                }


                  var ajxData20 = {'action': 'get-player-name', player: parseInt(nextPlayerGet)};

                  $.ajax({
                      type: 'POST',
                      url: 'ajax/getPlayerName.php',
                      cache: false,
                      data: ajxData20,
                      success: function(theName){
                         $('.game_message').html('<p>' + theName + ' will play</p>').show();
                  } });  

                    $('.current-player[data-user="'+parseInt(userDropped)+'"]').hide();

                

           }else if(dataReceived.type == "update-winning-amount"){

                var amount = dataReceived.amount;

                $('.game_info #game_prize_money').text(amount);
                //alert('check');

           }else if(dataReceived.type == "leave-table"){

            console.log(dataReceived.message);
            console.log("next pL : ", dataReceived);

           
            var userLeft = dataReceived.user;
            var nextPlayerGet = dataReceived.nextPlayer;
            var creatorLeft = dataReceived.creator;

           



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

                             console.log("next pL 1 : ", dataReceived);
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
                                                  console.log("player removed ", playersPlayingTemp);
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

                                                              if(parseInt(nextPlayerGet) == parseInt(userId)){
                                                                $('.cardDeckSelect').addClass("clickable");
                                                                $('.cardDeckSelect').removeClass("noSelect");
                                                                cardPull = 0;
                                                                cardDiscard = 0;

                                                                $('#cardDeckSelect'+userLeft).attr('id', 'cardDeckSelect'+userId);
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

                                                              if(parseInt(nextPlayerGet) == parseInt(userId)){
                                                                $('.cardDeckSelect').addClass("clickable");
                                                                $('.cardDeckSelect').removeClass("noSelect");
                                                                cardPull = 0;
                                                                cardDiscard = 0;

                                                                $('#cardDeckSelect'+userLeft).attr('id', 'cardDeckSelect'+userId);
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
                                                $('.cardDeckSelect').addClass("clickable");
                                                $('.cardDeckSelect').removeClass("noSelect");
                                                 cardPull = 0;
                                                 cardDiscard = 0;

                                                 $('#cardDeckSelect'+userLeft).attr('id', 'cardDeckSelect'+userId);
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

                        if(creatorLeft == true){


                            // make one of the current creator
                            if(playersPlayingTemp[0] == userId){
                                $.cookie("creator", "1");
                            }



                        }

                        

                    }   


                     
                      
              } }); 



            

           }else if(dataReceived.type == "toss-shuffle"){

                /**  ==== Toss cards among the players ==== */

                tossFlag++;

                // console.log("Toss flag ", tossFlag);

                if(tossFlag == 1){


                    /* if game is 6 players game */

                    if(gamePlayersCookie == "6" && playersPlayingTemp.length > 2){

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


                              


                    }else if(gamePlayersCookie == "6" && playersPlayingTemp.length == 2){


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


                                              // $('.player_1 .player_pic_1 img').css({'display': 'none'});
                                              // $('.player_1 .player_name .player_name_1').text('');
                                              // $('.player_1 .player_name').css({'display': 'none'});

                                              // $('.player_1').attr('data-user', '');


                                               } });


                                          }    






                               } 


                    }
 
                               



                /* Get deck cards */
                    console.log("wait");

                       var ajxData20started = {'action': 'update-room-started', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                        $.ajax({
                            type: 'POST',
                            url: 'ajax/roomUpdateStartedFinal.php',
                            cache: false,
                            data: ajxData20started,
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
                                                cardValue = "15";
                                            }

                                        }else{

                                            cardValue = "16";

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
                                                $('.game_message').html("");
                                                $('.game_message').hide();
                                               


                                            }, 5000);    
                                            
                                            /* Toss winner enters the db */

                                                if(creatorCookie){

                                                    //alert("yo");

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

                                                              // alert("hello");

                                                              console.log(result);

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

                                                                    
                                                                   var signal2 = {room:roomName, type: 'select-joker', message: 'dealing time', jokerCard: resultJoker, throw_card: throwCard, tossWinner: tossWinner.player, winningAmount: winningAmount};
                                                               
                                                                    //connection.send(JSON.stringify(signal2));
                                                                    socket.emit(socketEventName, JSON.stringify(signal2));




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
                                                                    $('.leave_table_btn').attr('disabled', false);


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


                    


                            var ajxData20started = {'action': 'update-room-started', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                            $.ajax({
                                type: 'POST',
                                url: 'ajax/roomUpdateStartedFinal.php',
                                cache: false,
                                data: ajxData20started,
                                success: function(result){ } });



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

                          // alert("hello");

                          console.log(result);

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
                                            $('.leave_table_btn').attr('disabled', false);
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


                                            /* Counter */

                                            setTimeout(function(){

                                                intervalCounter = window.clearInterval(intervalCounter);

                                               var signalStartCounter = {room:roomName, type: 'start-counter', message: 'starting counter....', player: parseInt(dataReceived.tossWinner), counterTime: 45};
                                                        
                                               // connection.send(JSON.stringify(signalStartCounter));
                                                socket.emit(socketEventName, JSON.stringify(signalStartCounter));

                                                  var PlayerCounterHandler = new playerCounterHandler(parseInt(dataReceived.tossWinner));

                                                  PlayerCounterHandler.playerCounter = 45;
                                                  PlayerCounterHandler.run();
                                                  intervalCounter = setInterval(PlayerCounterHandler.updateCounter, 1000);  
                                                  
                                               
                                               


                                            }, 3000);

                                           


                                            

                                            

                                            $('#cardDeckSelect'+dataReceived.tossWinner).addClass("clickable");
                                            $('#cardDeckSelect'+dataReceived.tossWinner).removeClass("noSelect");

                                            $('#cardDeckSelectShow'+dataReceived.tossWinner).addClass("clickable");
                                            $('#cardDeckSelectShow'+dataReceived.tossWinner).removeClass("noSelect");


                                            /* send a signal to the owner get the actual points for points rummy */

                                           if(gameTypeCookie == "score"){

                                             var signalGetPoints = {room:roomName, type: 'get-points-owner', message: 'asking the creator to get points'};
                                                               
                                             //connection.send(JSON.stringify(signalGetPoints));
                                             socket.emit(socketEventName, JSON.stringify(signalGetPoints));

                                           } 






                                    } });    


                                

                            }

                        } 

                    });

                
                }

              }else if(dataReceived.type == "start-counter"){

                   playerCounterFlag++;

                   console.log("playercounter flag ", playerCounterFlag);

                   if(playerCounterFlag == 1){

                        var player = dataReceived.player;
                        var counterTime = dataReceived.counterTime;
                        console.log(dataReceived.message);

                         intervalCounter = window.clearInterval(intervalCounter);

                         var PlayerCounterHandler = new playerCounterHandler(player);
                         PlayerCounterHandler.playerCounter = counterTime;
                         PlayerCounterHandler.run();
                         intervalCounter = setInterval(PlayerCounterHandler.updateCounter, 1000);  
                         
                        


                   }   

              }else if(dataReceived.type == "start-counter-discard-disconnection"){


               

                intervalCounter = window.clearInterval(intervalCounter);

                var player = dataReceived.player;
                var counterTime = dataReceived.counterTime;
                var userLeft = dataReceived.userLeft;
                
                console.log(dataReceived.message);

                 var PlayerCounterHandler = new playerCounterHandler(player);
                
                 PlayerCounterHandler.playerCounter = counterTime;
                 PlayerCounterHandler.run();
                 intervalCounter = setInterval(PlayerCounterHandler.updateCounter, 1000);  


                 console.log("counter received this is the final check ", player);
                 $('.current-player[data-user="'+parseInt(userLeft)+'"]').hide();


                 if(parseInt(userId) == parseInt(player)){
                     $('.cardDeckSelect').addClass("clickable");
                     $('.cardDeckSelect').removeClass("noSelect");
                     cardPull = 0;
                     cardDiscard = 0;

                     $('#cardDeckSelect'+userLeft).attr('id', 'cardDeckSelect'+userId);
                     $('#cardDeckSelectShow'+userLeft).attr('id', 'cardDeckSelectShow'+userId);


                 }




              }else if(dataReceived.type == "start-counter-discard"){

                        intervalCounter = window.clearInterval(intervalCounter);

                        var player = dataReceived.player;
                        var counterTime = dataReceived.counterTime;
                        console.log(dataReceived.message);

                         var PlayerCounterHandler = new playerCounterHandler(player);
                        
                         PlayerCounterHandler.playerCounter = counterTime;
                         PlayerCounterHandler.run();
                         intervalCounter = setInterval(PlayerCounterHandler.updateCounter, 1000);  


                         console.log("counter received this is the final check ", player);
                         
             
              }else if(dataReceived.type == "start-counter-melding"){

                  

                        console.log(dataReceived.message);

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


                                      var CardMeldingCounterHandler = new cardMeldingCounterHandler();
                                    
                                      CardMeldingCounterHandler.counter = 30;
                                      cardMeldingIntervalCounter = setInterval(CardMeldingCounterHandler.updateCounter, 1000);  
                                       
                        

                              } } });

                   

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


                    var signalGetPointsOthers = {room:roomName, type: 'get-points-others', message: 'asking others to get points'};
                    //connection.send(JSON.stringify(signalGetPointsOthers));
                    socket.emit(socketEventName, JSON.stringify(signalGetPointsOthers));

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

                $('.tempBackdrop').fadeOut();
                checkOffline = false;
                //alert('Discarded message recieved.');

                 intervalCounter = window.clearInterval(intervalCounter);
                 playerCounterFlag = 0;


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

                                  $('.current-player[data-user="'+playerPlayed+'"] .card_submit_time').hide(); 
                                  $('.current-player[data-user="'+playerPlayed+'"] .card_submit_time').text(""); 

                                  



                                if(cardToBeShown!=""){
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
                                }

                                $('.current-player[data-user="'+playerPlayed+'"] .playingCardsDiscard .hand li:empty').remove();


                                  if(cardToBeShown!=""){

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

                                      } 
                                         

                                        console.log("PLAYERRRRRRRRRRRRRR YOOOOOOOOOOOO ", nextPlayer);
                                 


                                    // var signalStartCounter = {type: 'start-counter-discard', message: 'starting counter discard....', player: parseInt(nextPlayer), counterTime: 30};
                                                        
                                    // connection.send(JSON.stringify(signalStartCounter));



                                     
                                    var PlayerCounterHandler = new playerCounterHandler(nextPlayer);
                                   
                                    
                                    PlayerCounterHandler.playerCounter = 30;
                                    PlayerCounterHandler.run();
                                    intervalCounter = setInterval(PlayerCounterHandler.updateCounter, 1000);  


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
                                                        $('.drop button').attr('disabled', false);
                                                        $('.drop button').css({'cursor':'pointer'});


                                                    }else{

                                                          $('.cardDeckSelect').addClass('noSelect').removeClass('clickable');
                                                         $('.drop button').attr('disabled', true);
                                                         $('.drop button').css({'cursor':'default'});
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

                    intervalCounter = window.clearInterval(intervalCounter);
                    playerCounterFlag = 0;
               

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

                         $('.current-player[data-user="'+playerWhoMelded+'"] .card_submit_time').hide(); 
                         $('.current-player[data-user="'+playerWhoMelded+'"] .card_submit_time').text(""); 
                                                        


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

                  
                    intervalCounter = window.clearInterval(intervalCounter);
                    playerCounterFlag = 0;
                    $('.current-player .card_submit_time').hide(); 
                    $('.current-player .card_submit_time').text("");

                   
                    var melder = dataReceived.firstMelder;
                    var totalPts = dataReceived.totalPoints;
                    var event = dataReceived.event;
                    var nextPlayer = dataReceived.nextPlayer;

                    //alert("get");
                    console.log("received netxplayer ==== ", nextPlayer);

                    var counterT = 3;

                    if(event == "wrongshow"){

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

                                                    $('#cardDeckSelect'+melder).attr('id', 'cardDeckSelect'+nextPlayer);
                                                    $('#cardDeckSelectShow'+melder).attr('id', 'cardDeckSelectShow'+nextPlayer);
                                               

                                                
                                                     if(parseInt(userId) == parseInt(nextPlayer)){
                                                         $('.cardDeckSelect').removeClass('noSelect').addClass('clickable');

                                                     

                                                         // $('.game_message').html('<p>Your turn</p>').show();

                                                         $('.drop button').attr('disabled', false);
                                                         $('.drop button').css({'cursor':'pointer'});


                                                         // kriti

                                                     }else{
                                                        $('.cardDeckSelect').removeClass('clickable').addClass('noSelect');

                                                        $('.drop button').attr('disabled', true);
                                                        $('.drop button').css({'cursor':'default'});


                                                        

                                                     }

                                                     if(event == "drop" || event == "middledrop"){
                                                        var drop = true;
                                                     }else{
                                                        var drop = false;
                                                     }


                                                  var signal13 = {room:roomName, type: 'get-scoreboard-wrongshow-gamegoing', message: 'continue game', playersPlayingTemp: playersPlayingTemp, playersPlayingNextRound: playersPlayingNextRound, nextPlayer: nextPlayer, melder: melder, drop: drop};

                                                    //connection.send(JSON.stringify(signal13)); 
                                                    socket.emit(socketEventName, JSON.stringify(signal13));

                                           



                                     }, 3000);

                                    }  

                                    cardPull = 0;
                                    cardDiscard = 0;



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

        
                intervalCounter = window.clearInterval(intervalCounter);
                playerCounterFlag = 0;
                 $('.current-player .card_submit_time').hide(); 
                $('.current-player .card_submit_time').text("");

                 console.log("get scoreboard message: ", dataReceived.message);

                 /* get my status */

              var ajxData852 = { 'action': 'get-my-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};


             $.ajax({
                    type: 'POST',
                    data: ajxData852,
                    cache: false,
                    url: 'ajax/getMyStatus.php',
                    success: function(result){

                    // alert("hit my status");

                    console.log("my status jeta ikhlam,,..  .. " , status);

                    if($.trim(result) != "out"){

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


                                    // alert("check this TESTING");



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



                                      var ajxDataLost = { 'action': 'update-player-scoreboard-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie, status:'won'};


                                         $.ajax({
                                                type: 'POST',
                                                data: ajxDataLost,
                                                cache: false,
                                                url: 'ajax/updatePlayerScoreboardStatus.php',
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

                                             for(var i = 0; i < playersPlayingWholeGame.length; i++){

                                                console.log('doing for ', playersPlayingWholeGame[i]);

                                                 var ajxData703 = {'action': 'get-players', player: playersPlayingWholeGame[i]};

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

                                                         
                                                                //var statusFlag;

                                                                var points = results.points;
                                                                var totalPts = results.total_points;
                                                                var scoreboardStatus = results.scoreboard_status;

                                                                 if(scoreboardStatus == "won" || scoreboardStatus == ""){
                                                                   scoreboardStatus = "<img src='images/winner.png'>";
                                                                 }

                                                                  

                                                                $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="result"]').html(scoreboardStatus);



                                                               if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                                  if(scoreboardStatus != 'drop' && scoreboardStatus != 'middle drop'){

                                                                    $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]').html(showCardsInScoreboard(player.id, roomIdCookie, sessionKeyCookie));

                                                                   }

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

                                                              
                                                               

                                                                var points = results.points;
                                                                var totalPts = results.total_points;
                                                                var scoreboardStatus = results.scoreboard_status;

                                                                 if(scoreboardStatus == "won" || scoreboardStatus == ""){
                                                                   scoreboardStatus = "<img src='images/winner.png'>";
                                                                 }
                                                                  

                                                                $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="result"]').html(scoreboardStatus);



                                                                $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="count"]').text(points);

                                                                if(totalPts != 0.00){
                                                            
                                                                    $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_chips"]').text('-'+totalPts);

                                                                      if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                                         if(scoreboardStatus != 'drop' && scoreboardStatus != 'middle drop'){

                                                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]').html(showCardsInScoreboard(player.id, roomIdCookie, sessionKeyCookie));

                                                                        }

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




                                                       var signal13 = {room:roomName, type: 'get-scoreboard-melder-six-player', message: 'asking the melder to get scoreboard', myStatus: "lost", gameStatusTrick: true, playersPlaying: playersPlaying, playersPlayingTemp:playersPlayingTemp, melder: melder};

                                                         //connection.send(JSON.stringify(signal13));   
                                                         socket.emit(socketEventName, JSON.stringify(signal13));


                                               




                                                console.log("Now it's your turn 3!!"); 
                                            }, 2000);

                                            
                                       
                                                 

                                }, 3000);
                                               

                                }

                             }


                     }else{


                        var ajxData20555 = {'action': 'get-connection-id', roomId: roomIdCookie, playerId: userId, sessionKey: sessionKeyCookie};

                        $.ajax({

                            type: 'POST',
                            url: 'ajax/getConnectionId.php',
                            cache: false,
                            data: ajxData20555,
                            success: function(connectionId){ 

                                  //alert("1");
                            
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

                                    //                    // var rejoinReqCookie = $.cookie("rejoinReqCookie"); 

                                    //                    // if(rejoinReqCookie == "0"){
                                    //                    //     connection.close();
                                    //                    //     location.reload();
                                    //                    // }

                                    //                      connection.close();

                                    //                     $('.popup_rejoin #rejoinBtn').attr('disabled', true);

                                                       

                                    //                 }); 


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



                } });     



              }else if(dataReceived.type == "get-scoreboard"){

               // alert("yooooo");

            
                        intervalCounter = window.clearInterval(intervalCounter);
                        playerCounterFlag = 0;
                         $('.current-player .card_submit_time').hide(); 
                         $('.current-player .card_submit_time').text("");

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
                            var leaveTable = dataReceived.leaveTable;
                          

                            console.log("melder ", melder);
                            console.log("status ", status);

                            

                            if(status == "wrongshow" || status == "drop" || status == "lost"){

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


                                           var ajxData853 = { 'action': 'update-player-melded', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};


                                           $.ajax({
                                                  type: 'POST',
                                                  data: ajxData853,
                                                  cache: false,
                                                  url: 'ajax/updatePlayerMelded.php',
                                                  success: function(result){
                                                      console.log(result);
                                                  } }); 


                                            var ajxDataLost = { 'action': 'update-player-scoreboard-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie, status:'won'};


                                             $.ajax({
                                                type: 'POST',
                                                data: ajxDataLost,
                                                cache: false,
                                                url: 'ajax/updatePlayerScoreboardStatus.php',
                                                success: function(result){
                                                    console.log(result);
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

                                                         for(var i = 0; i < playersPlayingWholeGame.length; i++){

                                                            console.log('doing for ', playersPlayingWholeGame[i]);

                                                             var ajxData703 = {'action': 'get-players', player: playersPlayingWholeGame[i]};

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
                                                                            var scoreboardStatus = results.scoreboard_status;

                                                                           
                                                                            if(scoreboardStatus == "won" || scoreboardStatus == ""){
                                                                               scoreboardStatus = "<img src='images/winner.png'>";
                                                                             }

                                                                         

                                                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="result"]').html(scoreboardStatus);


                                                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="score"]').text(Math.round(points));
                                                                        
                                                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_score"]').text(Math.round(totalPts));

                                                                            if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                                                 if(scoreboardStatus != 'drop' && scoreboardStatus != 'middle drop'){

                                                                                    $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]').html(showCardsInScoreboard(player.id, roomIdCookie, sessionKeyCookie));

                                                                                }

                                                                            }





                                                                        }
                                                                        
                                                                     }); 

                                                                     }else{

                                                                         console.log(player.id + ' ' +player.name);



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

                                                                            var points = results.points;
                                                                            var totalPts = results.total_points;
                                                                             var scoreboardStatus = results.scoreboard_status;

                                                                             if(scoreboardStatus == "won" || scoreboardStatus == ""){
                                                                               scoreboardStatus = "<img src='images/winner.png'>";
                                                                             }


                                                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="result"]').html(scoreboardStatus);


                                                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="count"]').text(Math.round(points));

                                                                            if(totalPts != 0.00){
                                                                        
                                                                                $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_chips"]').text('-'+totalPts);

                                                                                 if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                                                    if(scoreboardStatus != 'drop' && scoreboardStatus != 'middle drop'){

                                                                                        $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]').html(showCardsInScoreboard(player.id, roomIdCookie, sessionKeyCookie));

                                                                                    }

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

                                                           var signal13 = {room:roomName, type: 'get-scoreboard-melder', message: 'asking the melder to get scoreboard', myStatus: "lost", gameStatusTrick: true, drop: drop};

                                                            // connection.send(JSON.stringify(signal13));   

                                                             socket.emit(socketEventName, JSON.stringify(signal13));



                                                            console.log("Now it's your turn 1!!"); 
                                                        }, 2000);

                                                        
                                                   
                                                             

                                            }, 3000);
                                                   

                                        }   } 
                                    
                                    });        


                                 // }     

                            }else if(status == "rightshow" && leaveTable != true){

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

                                                /*  update melded count */

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

                                                                 //alert("hello");
                                                                 console.log("melded count ", count);

                                                                 wrongValidationDisplayProcess2ForceCall(roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie);
                                                                
                                                            }
                                                            
                                                         } });       


                                                }, 5000);


                                            } } });    
                                 
                                    }else if(status == "rightshow" && leaveTable == true){

                                            console.log("leave table situation!"); 
                                            //alert("here");
                                          

                                            var ajxData852 = { 'action': 'update-melded-count', roomId: roomIdCookie, sessionKey: sessionKeyCookie};


                                             $.ajax({
                                                    type: 'POST',
                                                    data: ajxData852,
                                                    cache: false,
                                                    url: 'ajax/updateMeldedCount.php',
                                                    success: function(result){
                                                        console.log(result);
                                                } });   



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
                                                                        var scoreboardStatus = results.scoreboard_status;

                                                                        if(scoreboardStatus == "won"){
                                                                            scoreboardStatus = "<img src='images/winner.png'>";
                                                                        }

                                                                     

                                                                       if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                                         if(scoreboardStatus != 'drop' && scoreboardStatus != 'middle drop'){

                                                                             $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]').html(showCardsInScoreboard(userId, roomIdCookie, sessionKeyCookie));

                                                                        }

                                                                       }

                                                                       


                                                                        $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="result"]').html(scoreboardStatus);


                                                                        $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="score"]').text(Math.round(points));
                                                                    
                                                                        $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_score"]').text(Math.round(totalPts));



                                                                    }
                                                                    
                                                                 });       

                                                           }else if(gameTypeCookie == "score"){


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

                                                                   
                                                                           
                                                                       var points = results.points;
                                                                       var totalPts = results.total_points;
                                                                       var scoreboardStatus = results.scoreboard_status;

                                                                        if(scoreboardStatus == "won"){
                                                                            scoreboardStatus = "<img src='images/winner.png'>";
                                                                        }
                                                                        


                                                                      

                                                                       if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                                         $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]').html(showCardsInScoreboard(player.id, roomIdCookie, sessionKeyCookie));

                                                                     }


                                                                      $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="result"]').html(status);


                                                                      $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="count"]').text(Math.round(points));
                                                                  
                                                                      $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_chips"]').text('-'+totalPts);


            


                                                                  }
                                                                  
                                                               }); 





                                                           }


                                                          } })


                                                       }


                                                        var signal13 = {room:roomName, type: 'get-scoreboard-melder', message: 'asking the melder to get scoreboard', myStatus: "lost", gameStatusTrick: true, drop: "blah"};

                                                        //connection.send(JSON.stringify(signal13));   

                                                         socket.emit(socketEventName, JSON.stringify(signal13));  



                                    }

                    } } });                



              }else if (dataReceived.type == "get-scoreboard-wrongshow-gamegoing"){

              
                intervalCounter = window.clearInterval(intervalCounter);
                playerCounterFlag = 0;
                //  $('.current-player .card_submit_time').hide(); 
                //  $('.current-player .card_submit_time').text("");

                testingFlag++;
                var wrongMelder = dataReceived.melder;

                if(wrongMelder == userId && testingFlag == 1){


                    $('.drop').hide();

                   

                    /* Signal for wrongmelder 6 players game */

                        playersPlayingNextRound = dataReceived.playersPlayingNextRound;
                        playersPlayingTemp = dataReceived.playersPlayingTemp;  
                        var nextPlayer = dataReceived.nextPlayer;
                        var drop = dataReceived.drop;


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

                                     // alert("incoming");
                                       // console.log("PPPPPPPPPPPPPP ", nextPlayer);
                                       // console.log("QQQQQQQQ ", playersPlayingTemp);



                                    var signalStartCounter = {room:roomName, type: 'start-counter-discard', message: 'starting counter....', player: parseInt(nextPlayer), counterTime: 30};
                                                        
                                    // connection.send(JSON.stringify(signalStartCounter));
                                        socket.emit(socketEventName, JSON.stringify(signalStartCounter));
                                        var PlayerCounterHandler = new playerCounterHandler(nextPlayer);
                                     
                                        PlayerCounterHandler.playerCounter = 30;
                                        PlayerCounterHandler.run();
                                        intervalCounter = setInterval(PlayerCounterHandler.updateCounter, 1000);

                                    
                                    setTimeout(function(){

                                        $('.result_sec .result_bottom').text("");

                                        $('.result_sec').css({'display': 'none'});
                                        $('.result_sec tbody[id="score_reports"] tr').remove();


                                        /*  Hide show section */

                                        $('.show_your_card_sec').css({'display': 'none'});
                                      
                                        if(drop == false){
                                            // $('.player_card_me .hand li a').removeClass('handCard');
                                            // $('.group_blog5 .playingCards .hand li a').removeClass('handCard');

                                            //  $('.player_card_me .hand li a').addClass('showFoldedCard');
                                            // $('.group_blog5 .playingCards .hand li a').addClass('showFoldedCard');

                                            $('.player_card_me').hide();
                                            $('.group_blog5').hide();
                                          
                                            $('.current-player[data-user="'+userId+'"] .toss .playingCards').html('<div class="card card_3 back board_center_back"></div>');




                                        }else{
                                            // alert("drop");

                                            $('.player_card_me .hand li a').removeClass('handCard');
                                            $('.group_blog5 .playingCards .hand li a').removeClass('handCard');

                                             $('.player_card_me .hand li a').addClass('showFoldedCard');
                                            $('.group_blog5 .playingCards .hand li a').addClass('showFoldedCard');

                                            $('.player_card_me').hide();
                                            $('.group_blog5').hide();
                                          
                                            $('.current-player[data-user="'+userId+'"] .toss .playingCards').html('<div class="card card_3 back board_center_back showMyHand"></div>');



                                        }    


                                         

                                        /* show folded card */

                                        

                                        // $('#meldAll'+userId+' button').hide();

                                        $('#sort'+userId).hide();
                                        $('.discard button').hide();

                                       

                                        testingFlag = 0;

                                        /* send signal to update testing flag */

                                        var signalTestingFlagUpdate = {room:roomName, "type":"testing-flag-update"};
                                       // connection.send(JSON.stringify(signalTestingFlagUpdate));
                                        socket.emit(socketEventName, JSON.stringify(signalTestingFlagUpdate));
                                        $('.cardDeckSelect').removeClass('clickable').addClass('noSelect');


                                    }, 2000);

                                }


                            }
                        });           
                            
                         

                }

              }else if (dataReceived.type == "testing-flag-update"){
                    testingFlag = 0;

              }else if(dataReceived.type == "get-scoreboard-melder"){

                 
                    intervalCounter = window.clearInterval(intervalCounter);
                    playerCounterFlag = 0;
                     $('.current-player .card_submit_time').hide(); 
                    $('.current-player .card_submit_time').text("");


                    //alert("get scoreboard melder");
                   
                   
                    var firstMelder = dataReceived.melder;
                    var status;

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

                                       if(count >= playersPlaying.length){

                                        // alert("count matched!");

                                             // alert("hitting 1");

                                            console.log("count match proceed");


                                 // if(gamePlayersCookie == "2"){

                                     for(var i = 0; i < playersPlayingWholeGame.length; i++){

                                        console.log('doing for ', playersPlayingWholeGame[i]);

                                         var ajxData703 = {'action': 'get-players', player: playersPlayingWholeGame[i]};

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
                                                        var scoreboardStatus = results.scoreboard_status;

                                                        if(scoreboardStatus == "won"){
                                                            scoreboardStatus = "<img src='images/winner.png'>";
                                                        }

                                                         console.log(points);
                                                         console.log(totalPts);

                                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="result"]').html(scoreboardStatus);


                                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="score"]').text(Math.round(points));
                                                                
                                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_score"]').text(Math.round(totalPts));

                                                            if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                                if(scoreboardStatus != 'drop' && scoreboardStatus != 'middle drop'){

                                                                   $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]').html(showCardsInScoreboard(player.id, roomIdCookie, sessionKeyCookie));

                                                                }   

                                                            }


                                                         }
                                                         
                                                   })
                                                   
                                                 }


                                             }else{



                                                if(player.id != userId){
                                        
                                                console.log(player.id + ' ' +player.name);

                                               

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
                                                        var scoreboardStatus = results.scoreboard_status;

                                                        if(scoreboardStatus == "won"){
                                                            scoreboardStatus = "<img src='images/winner.png'>";
                                                        }

                                                         console.log(points);
                                                         console.log(totalPts);

                                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="result"]').html(scoreboardStatus);


                                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="count"]').text(Math.round(points));
                                                                

                                                            if(totalPts != 0.00){
                                                                
                                                                $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_chips"]').text('-'+totalPts);

                                                                    if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                                         if(scoreboardStatus != 'drop' && scoreboardStatus != 'middle drop'){

                                                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]').html(showCardsInScoreboard(player.id, roomIdCookie, sessionKeyCookie));

                                                                          }  

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

                                          // }                 



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

                                                                 var signal0011 = {room:roomName, type : 'start-next-round', message: 'request to start next round'};
                                                                
                                                                 //connection.send(JSON.stringify(signal0011));   

                                                                 socket.emit(socketEventName, JSON.stringify(signal0011));


                                                                // nextGameStartHandler(function(){

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
                                                                    playerCounterFlag

                                                                    cardsSelected.length = 0;
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
                                                                    cardGotPulled = '';
                                                                    cardSubmitted = 0;

                                                                    $('.discard button').attr('disabled', true);
                                                                    $('.sort button').attr('disabled', false);


                                                                    // $('.meldAll button').hide();

                                                              

                                                                  if(gameTypeCookie != "score"){        

                                                                    dealNextRoundCardsMelder(roomIdCookie, gamePlayersCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, function(){

                                                                            console.log("asds");

                                                                            testingFlag = 0;
                                                                            nextGameCounter = 15;

                                                                        var signalTestingFlagUpdate = {room:roomName, "type":"testing-flag-update"};
                                                                        // connection.send(JSON.stringify(signalTestingFlagUpdate));
                                                                          socket.emit(socketEventName, JSON.stringify(signalTestingFlagUpdate));
                                                                        });

                                                                 }else{

                                                                    pointsRummyFinalScoreboardMelder(roomIdCookie, gamePlayersCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, function(){

                                                                            console.log("asds");

                                                                            testingFlag = 0;
                                                                            nextGameCounter = 15;

                                                                        var signalTestingFlagUpdate = {"type":"testing-flag-update"};
                                                                        //connection.send(JSON.stringify(signalTestingFlagUpdate));


                                                                    });    

                                                                 }


                                                            });

                                                        
     

                                                    // }, 1000); 
      


                                                        }
    


                                       } 

                                } });


                                        }else{ /* check if winner */
                                            console.log("not the winner");
                                        }

                                    }  /* first ajax success */
                             }); /* first ajax */          

                         
                   


                        }else if(dataReceived.type == "get-scoreboard-melder-six-player"){

                      
                             intervalCounter = window.clearInterval(intervalCounter);
                             playerCounterFlag = 0;
                              $('.current-player .card_submit_time').hide(); 
                            $('.current-player .card_submit_time').text("");

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
                                       if(count >= playersPlaying.length){

                                            

                                            console.log("count match proceed");


                                                 for(var i = 0; i < playersPlayingWholeGame.length; i++){

                                                    console.log('doing for ', playersPlayingWholeGame[i]);

                                                     var ajxData703 = {'action': 'get-players', player: playersPlayingWholeGame[i]};

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
                                                                            var scoreboardStatus = results.scoreboard_status;

                                                                             if(scoreboardStatus == "won"){
                                                                                 scoreboardStatus = "<img src='images/winner.png'>";
                                                                            }

                                                                             console.log(points);
                                                                             console.log(totalPts);

                                                                                $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="result"]').html(scoreboardStatus);


                                                                                $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="score"]').text(Math.round(points));
                                                                                    
                                                                                $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_score"]').text(Math.round(totalPts));

                                                                                if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                                                     if(scoreboardStatus != 'drop' && scoreboardStatus != 'middle drop'){

                                                                                        $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]').html(showCardsInScoreboard(player.id, roomIdCookie, sessionKeyCookie));


                                                                                    }

                                                                                }


                                                                             }
                                                                             
                                                                       })
                                                                       
                                                                     }

                                                           }else{


                                                                 if(player.id != userId){
                                                            
                                                                    console.log(player.id + ' ' +player.name);

                                                                  
                                                                   

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
                                                                            var scoreboardStatus = results.scoreboard_status;

                                                                            if(scoreboardStatus == "won"){
                                                                                 scoreboardStatus = "<img src='images/winner.png'>";
                                                                            }

                                                                             console.log(points);
                                                                             console.log(totalPts);

                                                                                $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="result"]').html(scoreboardStatus);


                                                                                $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="count"]').text(Math.round(points));

                                                                                if(totalPts != 0.00){
                                                                
                                                                                  $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_chips"]').text('-'+totalPts);

                                                                                   if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                                                     if(scoreboardStatus != 'drop' && scoreboardStatus != 'middle drop'){

                                                                                         $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]').html(showCardsInScoreboard(player.id, roomIdCookie, sessionKeyCookie));

                                                                                        }

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


                                                     var signal0011 = {room:roomName, type : 'start-next-round', message: 'request to start next round'};
                                                    
                                                     //connection.send(JSON.stringify(signal0011));   
                                                     socket.emit(socketEventName, JSON.stringify(signal0011));

                                                                

                                                                // nextGameStartHandler(function(){

                                                                   
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
                                                                    playerCounterFlag

                                                                    cardsSelected.length = 0;
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
                                                                    cardGotPulled = '';
                                                                    cardSubmitted = 0;

                                                                     playersPlayingNextRound.length = 0;

                                                                    $('.discard button').attr('disabled', true);
                                                                    $('.sort button').attr('disabled', false);

                                                                    // $('.meldAll button').hide();


                                                     

                                                                    if(gameTypeCookie != "score"){        

                                                                        dealNextRoundCardsMelder(roomIdCookie, gamePlayersCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, function(){

                                                                                console.log("asds");

                                                                                testingFlag = 0;
                                                                                nextGameCounter = 15;

                                                                            var signalTestingFlagUpdate = {room:roomName, "type":"testing-flag-update"};
                                                                           // connection.send(JSON.stringify(signalTestingFlagUpdate));
                                                                            socket.emit(socketEventName, JSON.stringify(signalTestingFlagUpdate));
                                                                            });

                                                                 }else{

                                                                        pointsRummyFinalScoreboardMelder(roomIdCookie, gamePlayersCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, function(){

                                                                                console.log("asds");

                                                                                testingFlag = 0;
                                                                                nextGameCounter = 15;


                                                                            var signalTestingFlagUpdate = {room:roomName, "type":"testing-flag-update"};
                                                                          // connection.send(JSON.stringify(signalTestingFlagUpdate));
                                                                           socket.emit(socketEventName, JSON.stringify(signalTestingFlagUpdate));

                                                                        });    

                                                                 }


                                                            });




                                                    // }, 1000); 

                                                    

                                                } 

                                } });



                            }

                          


                        }else if(dataReceived.type == "game-over-signal"){

                 
                             intervalCounter = window.clearInterval(intervalCounter);
                             playerCounterFlag = 0;
                              $('.current-player .card_submit_time').hide(); 
                            $('.current-player .card_submit_time').text("");

                            var playerWon = dataReceived.playerWon;

                              if(parseInt(playerWon) == parseInt(userId)){
                                $('.loading_container').css({'display':'block'});
                                $('.loading_container .popup .popup_cont').text("You have won the game!");

                                 setTimeout(function(){
                                    $('.loading_container').hide();
                                    $('.loading_container .popup .popup_cont').text();


                                    $('.popup_play_again').show();
                                    $('.popup_play_again .popup_with_button_cont p').text("Do you want to play again?");
                                    
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


                                                             
                                              //              $('.loading_container').hide();
                                              //              $('.loading_container .popup .popup_cont').text();


                                              //               $('.popup_rejoin').show();
                                              //               $('.popup_rejoin .popup_with_button_cont p').text("Do you want to rejoin at " + parseFloat(Math.round(result['highestPoint']) + 1) + " points ?");           

                                              //               $.cookie("rejoinScore", parseFloat( Math.round(result['highestPoint']) + 1) );

                                              //                rejoinGameStartHandler(function(){

                                              //                    // var rejoinReqCookie = $.cookie("rejoinReqCookie"); 

                                              //                    // if(rejoinReqCookie == "0"){
                                              //                    //     connection.close();
                                              //                    //     location.reload();
                                              //                    // }

                                              //                    connection.close();

                                              //                     $('.popup_rejoin #rejoinBtn').attr('disabled', true);

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


                        }else if(dataReceived.type == "start-next-round"){


                    
                             intervalCounter = window.clearInterval(intervalCounter);
                             playerCounterFlag = 0;
                             $('.current-player .card_submit_time').hide(); 
                             $('.current-player .card_submit_time').text("");

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

                                // setTimeout(function(){

                  
                                //     nextGameStartHandler(function(){

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
                                        playerCounterFlag

                                        cardsSelected.length = 0;
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

                                        // $('.meldAll button').hide();




                                    // });

                                  // }, 1000); 

                            } 
                            

                          } });    




                        }else if(dataReceived.type == "next-round-process"){

                            /* get my status */
                            intervalCounter = window.clearInterval(intervalCounter);
                            playerCounterFlag = 0;

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


                                     

                                    //if(!tossWinner){


                                         var ajxData235 = {'action': 'get-toss-winner', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                                         $.ajax({

                                            type: 'POST',
                                            url: 'ajax/getTossWinner.php',
                                            cache: false,
                                            data: ajxData235,
                                            success: function(winner){ 

                                                console.log("toss winner received : " + winner);
                                                tossWinner = winner;
                                                console.log("HIHIHIHIHIHI ", tossWinner);



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
                                                     nextGameCounter = 15;



                                                     var signalStartCounter = {room:roomName, type: 'start-counter-discard', message: 'starting counter....', player: parseInt(tossWinner), counterTime: 45};
                                                     //connection.send(JSON.stringify(signalStartCounter));
                                                      socket.emit(socketEventName, JSON.stringify(signalStartCounter));
                                                     // alert("yello");  

                                                     intervalCounter = window.clearInterval(intervalCounter);

                                                     var PlayerCounterHandler = new playerCounterHandler(parseInt(tossWinner));
                                                     PlayerCounterHandler.playerCounter = 45;
                                                     console.log("Counter handler ", PlayerCounterHandler.playerCounter);

                                                     PlayerCounterHandler.run();
                                                     intervalCounter = setInterval(PlayerCounterHandler.updateCounter, 1000);
                                                    
                                                     $('.leave_table_btn').attr('disabled', false);


                                                });

                                              }

                                            }
                                            
                                         });       

                                    //}



                           
                        } } });




                        }else if(dataReceived.type == "points-rummy-fso"){

                    
                            intervalCounter = window.clearInterval(intervalCounter);
                            playerCounterFlag = 0;
                            $('.current-player .card_submit_time').hide(); 
                            $('.current-player .card_submit_time').text("");

                             console.log(dataReceived.message);

                             var playersLostArr1 = dataReceived.playersLostArr.slice();
                             var winningAmount = dataReceived.winningAmount;
                             var winner = dataReceived.winner;
                             var dealMeOut;


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
                                                    console.log("POLO =========== ", results);

                                                    var points = results.points;
                                                    var totalPts = results.total_points;
                                                    var playerWon = results.player_won;
                                                    var scoreboardStatus = results.scoreboard_status;
                                                    dealMeOut = results.dealMeOut;


                                                    $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="result"]').text(scoreboardStatus);

                                                     $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="count"]').text(Math.round(points));
                                        
                                                    $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_chips"]').text('-'+totalPts);

                                                    if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]' .playingCards).length == 0 ){

                                                         if(scoreboardStatus != 'drop' && scoreboardStatus != 'middle drop'){

                                                            $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="cards"]').html(showCardsInScoreboard(player.id, roomIdCookie, sessionKeyCookie));

                                                        }

                                                    }
                                           

                                                } })




                                        } });



                                    }


                                    $('.result_sec tbody[id="score_reports"] tr[data-user="'+winner+'"] td[id="result"]').html("<img src='images/winner.png'>");

                                     $('.result_sec tbody[id="score_reports"] tr[data-user="'+winner+'"] td[id="count"]').text(0);
                                    
                                    console.log("winning amount  ======== asdas ", winningAmount);
                                     console.log("winner   ======= asdas ", winner);

                                     $('.result_sec tbody[id="score_reports"] tr[data-user="'+winner+'"] td[id="total_chips"]').text('+'+winningAmount.toFixed(2));

                                    if( $('.result_sec tbody[id="score_reports"] tr[data-user="'+winner+'"] td[id="cards"]' .playingCards).length == 0 ){

                                         
                                        //alert("hello");
                                        $('.result_sec tbody[id="score_reports"] tr[data-user="'+winner+'"] td[id="cards"]').html(showCardsInScoreboard(parseInt(winner), roomIdCookie, sessionKeyCookie));

                                        

                                    }


                                    if(parseInt(userId) === parseInt(winner)){
                                      
                                   
                                       setTimeout(function(){
                                         
                                          if(dealMeOut == 1){


                                                $.cookie("rejoinPR", "0");
                                                $.cookie("rejoin", "0");
                                                $.cookie("onOpenHit", null);
                                                $.removeCookie("onOpenHit");

                                               setTimeout(function(){ 
                                                location.reload();
                                                connection.close();
                                              }, 2000);

                                            }else{

                                                $.cookie("rejoinPR", "1");
                                                $.cookie("rejoin", "0");
                                                $.cookie("onOpenHit", null);
                                                $.removeCookie("onOpenHit");

                                            }

                                          var countRejoin = 10;

                                           console.log("currentblnccookie", currentBalanceCookie);
                                           console.log("chips to table ", chipsToTablePRCookie);

                                          console.log("result winning amount ", winningAmount.toFixed(2));                 

                                        var newCurrentBalanceCookie = parseFloat(currentBalanceCookie) + parseFloat(winningAmount);

                                        var newChipsToTablePRCookie = parseFloat(chipsToTablePRCookie) + parseFloat(winningAmount);

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
                                     

                                      if(dealMeOut == 1){


                                        $.cookie("rejoinPR", "0");
                                        $.cookie("rejoin", "0");
                                        $.cookie("onOpenHit", null);
                                        $.removeCookie("onOpenHit");

                                        setTimeout(function(){ 
                                            location.reload();
                                            connection.close();
                                        }, 2000);

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

                                            console.log("LOSS ", JSON.stringify(result));
                                            console.log(result.lost_chips)
                                            //alert('get my loss');

                                           
                                            var countRejoin = 10;

                                            var newCurrentBalanceCookie = result.balance_chips;
                                           
                                            
                                            var chipDiff = parseFloat(chipsToTablePRCookie) - parseFloat(result.lost_chips);

                                             console.log("chip Diff ", chipDiff);

                                            if(parseFloat(minBuyingPRCookie) > parseFloat(chipDiff)){

                                              //alert("min buying less");

                                              var chipsReqd = parseFloat(minBuyingPRCookie) - parseFloat(chipDiff);

                                              $('#myModal').modal('show'); 
                                              $('#myModal .joinPR').addClass('rejoinPr');

                                             $('#myModal #bet_amount').text(betValueCookie);
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

                                            }, 2000); 


                                          }


                                        } })  
                                         
                                          
                                       }, 4000);

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




                        }else if(dataReceived.type == "update-players-playing-rejoin"){

                           // var PlayerCounterHandler = new playerCounterHandler(tossWinner);
                           // clearInterval(intervalCounter);
                           // playerCounterFlag = 0;  

                          console.log(dataReceived.message);

                          var getplayersPlayingTempRejoin = dataReceived.playersPlayingTemp;
                          var player = dataReceived.player;
                          var playerName = dataReceived.playerName;
                          var rejoinScore = dataReceived.rejoinScore;

                          playersPlayingTemp = getplayersPlayingTempRejoin.slice();
                          playersPlaying = playersPlayingTemp.slice();


                           if( !$('.player_1').attr('data-user') ){ 
                                                                 
                                $('.player_1 .player_pic_1 img').css({'display': 'block'});
                                $('.player_1 .player_name .player_name_1').text(playerName);
                                $('.player_1 .player_name').css({'display': 'block'});

                                $('.player_1').attr('data-user', player);
                             
                             }else if( !$('.player_2').attr('data-user') ){ 
                              
                               $('.player_2 .player_pic_2 img').css({'display': 'block'});
                                $('.player_2 .player_name .player_name_2').text(playerName);
                                $('.player_2 .player_name').css({'display': 'block'});

                                $('.player_2').attr('data-user', player);
                            
                             }else if( !$('.player_3').attr('data-user') ){ 
                               $('.player_3 .player_pic_3 img').css({'display': 'block'});
                                $('.player_3 .player_name .player_name_3').text(playerName);
                                $('.player_3 .player_name').css({'display': 'block'});

                                $('.player_3').attr('data-user', player);
                            
                             }else if( !$('.player_4').attr('data-user') ){ 
                               $('.player_4 .player_pic_4 img').css({'display': 'block'});
                                $('.player_4 .player_name .player_name_4').text(playerName);
                                $('.player_4 .player_name').css({'display': 'block'});

                                $('.player_4').attr('data-user', player);
                            
                             }else if( !$('.player_5').attr('data-user') ){ 
                               $('.player_5 .player_pic_5 img').css({'display': 'block'});
                                $('.player_5 .player_name .player_name_5').text(playerName);
                                $('.player_5 .player_name').css({'display': 'block'});

                                $('.player_5').attr('data-user', player);
                             }



                             $('.current-player[data-user="'+parseInt(player)+'"]').show();
                             $('.current-player[data-user="'+parseInt(player)+'"] .player_name #score').text(Math.round(rejoinScore));

                              $('.current-player[data-user='+parseInt(player)+'] .playingCards .deck').html("");

                              $('.current-player[data-user='+parseInt(player)+'] .playingCards .deck').append('<li>'+
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

                              $('.current-player[data-user='+parseInt(player)+'] .playingCards .deck').show();














                        }else if(dataReceived.type == "update-players-playing-melder"){
                            
                            var getplayersPlayingTemp = dataReceived.playersPlayingTemp;

                            playersPlayingTemp.length = 0;

                             for(var i = 0; i < getplayersPlayingTemp.length; i++){
                                playersPlayingTemp.push(getplayersPlayingTemp[i]);
                             }

                             console.log("new players playing ", playersPlayingTemp);

                                



                        }else if(dataReceived.type == "reconnect-others"){

                              var userLeft = dataReceived.userLeft;
                               var nextPlayerGet = dataReceived.nextPlayer;
                              //var creatorLeft = creator;

                               console.log("hit function 2");

                             
                               


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
                                                                    console.log("player removed ", playersPlayingTemp);
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

                                                                                if(parseInt(nextPlayerGet) == parseInt(userId)){
                                                                                  $('.cardDeckSelect').addClass("clickable");
                                                                                  $('.cardDeckSelect').removeClass("noSelect");
                                                                                  cardPull = 0;
                                                                                  cardDiscard = 0;

                                                                                  $('#cardDeckSelect'+userLeft).attr('id', 'cardDeckSelect'+userId);
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

                                                                                if(parseInt(nextPlayerGet) == parseInt(userId)){
                                                                                  $('.cardDeckSelect').addClass("clickable");
                                                                                  $('.cardDeckSelect').removeClass("noSelect");
                                                                                  cardPull = 0;
                                                                                  cardDiscard = 0;

                                                                                  $('#cardDeckSelect'+userLeft).attr('id', 'cardDeckSelect'+userId);
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
                                                                  $('.cardDeckSelect').addClass("clickable");
                                                                  $('.cardDeckSelect').removeClass("noSelect");
                                                                   cardPull = 0;
                                                                   cardDiscard = 0;

                                                                   $('#cardDeckSelect'+userLeft).attr('id', 'cardDeckSelect'+userId);
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

  


    }
});  
