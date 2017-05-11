



function getScoreBoardWrongVal2(roomIdCookie, gameType, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie){ 

   console.log("AIAIAIBIBI");



    console.log("hit the func...");
    $('.result_bottom').text("");
    $('.leave_table_btn').attr('disabled', true);

     intervalCounter = window.clearInterval(intervalCounter);
     playerCounterFlag = 0;

     // $('.result_sec tbody[id="score_reports"]').html("");
     
     
     var status;

     for(var i = 0; i < playersPlayingWholeGame.length; i++){

        console.log("CICICIBIBI");

        console.log('doing for ', playersPlayingWholeGame[i]);

         var ajxData703 = {'action': 'get-players', player: playersPlayingWholeGame[i]};

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

                     if( $.trim( $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_score"]').html() ) == ""){

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
                                var scoreboardStatus = results.scoreboard_status;

                                console.log("Player Won ", playerWon);

                                if(scoreboardStatus == "won"){
                                    scoreboardStatus = "<img src='images/winner.png'>";
                                }
                                      
                                      
                                         $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="result"]').html(scoreboardStatus);

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

                     if( $.trim( $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="total_chips"]').html() ) == ""){

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
                                var scoreboardStatus = results.scoreboard_status;

                                console.log("Player Won ", playerWon);

                                if(scoreboardStatus == "won"){
                                    scoreboardStatus = "<img src='images/winner.png'>";
                                }
                                    

                                 $('.result_sec tbody[id="score_reports"] tr[data-user="'+player.id+'"] td[id="result"]').html(scoreboardStatus);

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


    $('.leave_table_btn').attr('disabled', true);
    intervalCounter = window.clearInterval(intervalCounter);
    playerCounterFlag = 0;
     $('.current-player .card_submit_time').hide(); 
    $('.current-player .card_submit_time').text("");

    console.log("wrong validation display process 2 hit");
    $('.result_bottom').text("");


    console.log("Players playing temp ", playersPlayingTemp);

   
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

                                }else if(count >= playersPlaying.length){
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
                var signal13 = {room:roomName, type: 'get-scoreboard-melder', message: 'asking the melder to get scoreboard', myStatus: "won"};

                  var signal14 = {room:roomName, type: 'update-players-playing', message: 'updating players playing', playersPlayingTemp: playersPlayingTemp}

               // connection.send(JSON.stringify(signal13));
               // connection.send(JSON.stringify(signal14)); 
                socket.emit(socketEventName, JSON.stringify(signal13));
                socket.emit(socketEventName, JSON.stringify(signal14));
                console.log("Now it's your turn 4!!"); 
            }, 2000);

                        
                   
                             

            }, 3000);
                   

        } 
    
    }); 


}    





/* Wrong meld Validation Display other melders */


function wrongValidationDisplayProcess2(totalScoreSum, roomIdCookie, gameType, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, event){

     
     $('.leave_table_btn').attr('disabled', true);
     intervalCounter = window.clearInterval(intervalCounter);
     playerCounterFlag = 0;
     $('.current-player .card_submit_time').hide(); 
     $('.current-player .card_submit_time').text("");

    console.log("wrong validation display process 2 hit");
    $('.result_bottom').text("");


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

      
       if($.trim(event) == "lost"){
        var statusScoreboard = "lost";
      }else if($.trim(event) == "drop"){
         var statusScoreboard = "drop";
      }else if($.trim(event) == "middledrop"){
         var statusScoreboard = "middle drop";
      }  

        var ajxDataLost = { 'action': 'update-player-scoreboard-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie, status:statusScoreboard};


     $.ajax({
            type: 'POST',
            data: ajxDataLost,
            cache: false,
            url: 'ajax/updatePlayerScoreboardStatus.php',
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

                                }else if(count >= playersPlaying.length){
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
                var signal13 = {room:roomName, type: 'get-scoreboard-melder', message: 'asking the melder to get scoreboard', myStatus: "won"};

                  var signal14 = {room:roomName, type: 'update-players-playing', message: 'updating players playing', playersPlayingTemp: playersPlayingTemp}

               // connection.send(JSON.stringify(signal13));
               // connection.send(JSON.stringify(signal14)); 

                socket.emit(socketEventName, JSON.stringify(signal13));
                socket.emit(socketEventName, JSON.stringify(signal14));

                console.log("Now it's your turn 4!!"); 
            }, 2000);

                        
                   
                             

            }, 3000);
                   

        } 
    
    }); 


}


/* Wrong Meld Validation Display 6 players */

function wrongValidationDisplayProcessSixPlayers(points, roomIdCookie, gameType, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, event){



$('.leave_table_btn').attr('disabled', true);
intervalCounter = window.clearInterval(intervalCounter);
playerCounterFlag = 0;
$('.current-player .card_submit_time').hide(); 
$('.current-player .card_submit_time').text("");

console.log("==========================!!!!======================");
console.log("RM ", roomIdCookie);
console.log("GT ", gameType);
console.log("SK ", sessionKeyCookie);
console.log("CT ", chipsToTablePRCookie);
console.log("CB ", currentBalanceCookie);
console.log("MB ", minBuyingPRCookie);
console.log("BV ", betValueCookie);
$('.result_bottom').text("");

console.log("wrong validation display process 1 hit 6 players");   
console.log("playersPlayingTemp ", playersPlayingTemp);

// alert("wrong val dis process 6 players!");


if(playersPlayingTemp.length > 2){
    $('.result_sec').css({'display': 'none'});

    if($.trim(event) == "wrongshow"){
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


              if($.trim(event) == "wrongshow"){
                var statusScoreboard = "wrongshow";
              }else if($.trim(event) == "drop"){
                 var statusScoreboard = "drop";
              }else if($.trim(event) == "middledrop"){
                 var statusScoreboard = "middle drop";
              }  

                var ajxDataLost = { 'action': 'update-player-scoreboard-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie, status:statusScoreboard};


             $.ajax({
                    type: 'POST',
                    data: ajxDataLost,
                    cache: false,
                    url: 'ajax/updatePlayerScoreboardStatus.php',
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
                            
                                

                            }

                            if(removeCardFromGroups(userId, playersPlayingTemp)){
                                console.log("player removed ", playersPlayingTemp);
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
                                    
                                   
                                
                            }

                             if(removeCardFromGroups(userId, playersPlayingTemp)){
                                console.log("player removed ", playersPlayingTemp);
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


                          

                      
                                    var nextPlayer1;

                                    if( getItem(playersPlayingTemp, parseInt(userId)) ){
                                        nextPlayer1 = getItem(playersPlayingTemp, parseInt(userId));
                                    }else{
                                        nextPlayer1 = playersPlayingTemp[0];
                                    }

                                    $('.drop button').attr('disabled', false);
                                    $('.drop button').css({'cursor':'pointer'});

                                    // kriti

                                    console.log("nextplayer Blahhhh", nextPlayer1);
                                    //alert("ok");

                                     /* send signal to other players */

                                   var signal1011 = {room:roomName, type: 'wrong-meld-six-players', message: 'wrong meld six players game', firstMelder: userId, totalPoints: totalPoints, event: event, nextPlayer:nextPlayer1};


                                     //connection.send(JSON.stringify(signal1011));
                                  // connection.send(JSON.stringify(signal14)); 
                                    socket.emit(socketEventName, JSON.stringify(signal1011));
                                    
                                    $('.loading_container').css({'display':'none'});
                                    $('.loading_container .popup .popup_cont').text("");



                         },3000);



                    


                    


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

              if($.trim(event) == "wrongshow"){
                var statusScoreboard = "wrongshow";
              }else if($.trim(event) == "drop"){
                 var statusScoreboard = "drop";
              }else if($.trim(event) == "middledrop"){
                 var statusScoreboard = "middle drop";
              }  

                var ajxDataLost = { 'action': 'update-player-scoreboard-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie, status:statusScoreboard};


             $.ajax({
                    type: 'POST',
                    data: ajxDataLost,
                    cache: false,
                    url: 'ajax/updatePlayerScoreboardStatus.php',
                    success: function(result){
                        console.log(result);
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
                                                var scoreboardStatus = results.scoreboard_status;

                                               
                                                     
                                              
                                               $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="result"]').text(scoreboardStatus);   


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
                                                var scoreboardStatus = results.scoreboard_status;


                                               
                                                $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="result"]').text(scoreboardStatus);
                                               

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

                                         

                                    } });    


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

                        if($.trim(event) == 'wrongshow'){
                            var status = "wrongshow";
                        }else if($.trim(event) == 'drop' || $.trim(event) == 'middledrop'){
                            var status = "drop";
                        }
                        

                        var signal1011 = {room:roomName, type: 'get-scoreboard-six-players', message: 'get all your scoreboards', firstMelder: userId, status:status};

                        //connection.send(JSON.stringify(signal1011));
                      
                        socket.emit(socketEventName, JSON.stringify(signal1011));
                     

                   
               
            }
        });  

}    

}     


/* Wrong Meld Validation Display */

function wrongValidationDisplayProcess(points, roomIdCookie, gameType, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, event){


$('.leave_table_btn').attr('disabled', true); 
intervalCounter = window.clearInterval(intervalCounter);
playerCounterFlag = 0;
$('.current-player .card_submit_time').hide(); 
$('.current-player .card_submit_time').text("");

console.log("wrong validation display process 1 hit");   
console.log("playersPlayingTemp ", playersPlayingTemp);
$('.result_bottom').text("");
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

              if($.trim(event) == "wrongshow"){
                var statusScoreboard = "wrongshow";
              }else if($.trim(event) == "drop"){
                 var statusScoreboard = "drop";
              }else if($.trim(event) == "middledrop"){
                 var statusScoreboard = "middle drop";
              }else if($.trim(event) == "lost"){
                var statusScoreboard = "lost";
              }  

                var ajxDataLost = { 'action': 'update-player-scoreboard-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie, status:statusScoreboard};


             $.ajax({
                    type: 'POST',
                    data: ajxDataLost,
                    cache: false,
                    url: 'ajax/updatePlayerScoreboardStatus.php',
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
                                                var scoreboardStatus = results.scoreboard_status;

                                              
                                                 $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="result"]').text(scoreboardStatus);


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
                                                 var scoreboardStatus = results.scoreboard_status;

                                              
                                                 $('.result_sec tbody[id="score_reports"] tr[data-user="'+userId+'"] td[id="result"]').text(scoreboardStatus);


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

                         if($.trim(event) == "wrongshow"){
                            var status = "wrongshow";
                         }else if($.trim(event) == "drop" || $.trim(event) == 'middledrop'){
                            var status = "drop";
                        }else if($.trim(event) == "lost"){
                            var status = "lost"
                        }
                       

                        var signal1011 = {room:roomName, type: 'get-scoreboard', message: 'get all your scoreboards', firstMelder: userId, status: status, leaveTable:false};

                         var signal14 = {room:roomName, type: 'update-players-playing-melder', message: 'updating players playing', playersPlayingTemp: playersPlayingTemp}

                        //connection.send(JSON.stringify(signal1011));
                        //connection.send(JSON.stringify(signal14)); 

                        socket.emit(socketEventName, JSON.stringify(signal1011));
                        socket.emit(socketEventName, JSON.stringify(signal14));
                     

                   
               
            }
        });  

             
}

/** Successful Melding **/

function successfulMelding(roomIdCookie, gameType, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, event){

$('.leave_table_btn').attr('disabled', true);
intervalCounter = window.clearInterval(intervalCounter);
playerCounterFlag = 0;
$('.current-player .card_submit_time').hide(); 
$('.current-player .card_submit_time').text("");

 $('.result_bottom').text("");

 console.log("successful melding hit");
 console.log(playersPlayingTemp);

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


      if($.trim(event) == "won"){
        var statusScoreboard = "won";
      }

        var ajxDataLost = { 'action': 'update-player-scoreboard-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie, status:statusScoreboard};


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


var ajxData855 = { 'action': 'success-melding', roomId: roomIdCookie, player: userId, gameType: gameType, sessionKey: sessionKeyCookie, betValue: betValueCookie};


$.ajax({
    type: 'POST',
    data: ajxData855,
    cache: false,
    url: 'ajax/successfulMelding.php',
    success: function(totalPoints){

        console.log(" total points received ", totalPoints);
      

           

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


                var signal102 = {room:roomName, type: 'get-scoreboard', message: 'get all your scoreboards', firstMelder: userId, status: status, playersPlayingTemp: playersPlayingTemp, leaveTable:false};


                //connection.send(JSON.stringify(signal102));
                socket.emit(socketEventName, JSON.stringify(signal102));

             

           
       
    }
});      


}



function forceMelder(totalScoreSum, gameType, roomIdCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, event){


$('.leave_table_btn').attr('disabled', true); 
intervalCounter = window.clearInterval(intervalCounter);
playerCounterFlag = 0;
$('.current-player .card_submit_time').hide(); 
$('.current-player .card_submit_time').text("");

console.log("force melder hit");
$('.result_bottom').text("");
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



    var ajxData852ss = { 'action': 'update-wrong-melders', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};


     $.ajax({
            type: 'POST',
            data: ajxData852ss,
            cache: false,
            url: 'ajax/updateWrongMelders.php',
            success: function(result){
                console.log("sql ", result);
            } });

      
       if($.trim(event) == "lost"){
        var statusScoreboard = "lost";
      }else if($.trim(event) == "drop"){
         var statusScoreboard = "drop";
      }else if($.trim(event) == "middledrop"){
         var statusScoreboard = "middle drop";
      }  

        var ajxDataLost = { 'action': 'update-player-scoreboard-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie, status:statusScoreboard};


     $.ajax({
            type: 'POST',
            data: ajxDataLost,
            cache: false,
            url: 'ajax/updatePlayerScoreboardStatus.php',
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
                                    // alert('count equals');
                                    getScoreBoardWrongVal2(roomIdCookie, gameType, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie);
                                    
                                }
                                
                             } });       


                     }, 5000);
        

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


               setTimeout(function(){
               
                   var signal13 = {room:roomName, type: 'get-scoreboard-melder', message: 'asking the melder to get scoreboard', myStatus: "won"};

                   var signal14 = {room:roomName, type: 'update-players-playing', message: 'updating players playing', playersPlayingTemp: playersPlayingTemp}
                                           
                    // connection.send(JSON.stringify(signal13)); 
                    // connection.send(JSON.stringify(signal14));

                    socket.emit(socketEventName, JSON.stringify(signal13));
                    socket.emit(socketEventName, JSON.stringify(signal14));

                    console.log("Now it's your turn 2"); 

               
                }, 2000); 


            }, 3000);   
        }
 })      





}  