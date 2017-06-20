  function leaveTable(roomIdCookie, gamePlayersCookie, gameTypeCookie, sessionKeyCookie, betValueCookie){

      var nextPlayer;
      
      $('.popup_leave').hide();
      $.cookie("rejoin", "0");
      $.cookie("rejoinPR", "0"); 

      intervalCounter = window.clearInterval(intervalCounter);
      playerCounterFlag = 0;

      var creator = false;
      
      var creatorCookie = $.cookie("creator");

      if(creatorCookie){

          $.cookie("creator", null);
          $.removeCookie("creator");
          creator = true;
      }else{
          creator = false;
      }






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


             /*  Update player gamedata */
              var ajxData704 = {'action': 'update-my-status', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};

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

                    if(parseInt(player) == parseInt(userId)){


                          /*if(getItem(playersPlayingTemp, parseInt(userId)) ){

                            nextPlayer = getItem(playersPlayingTemp, parseInt(userId));
                               
                          }else{
                            nextPlayer = playersPlayingTemp[0];
                               
                          }*/
                          nextPlayer = findNextPlayer(playersPlayingTemp,parseInt(userId));
                          

                          console.log("nextplayer ", nextPlayer);

                          var ajxData260 = {'action': 'current-player', roomId: roomIdCookie, 
                                    player: parseInt(nextPlayer), sessionKey: sessionKeyCookie };

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


                     var signalLeaveTable = {room:roomName, type: 'leave-table', user: userId, nextPlayer: nextPlayer, message: "someone left...... message..", creator:creator}
            
                        //connection.send(JSON.stringify(signalLeaveTable));
                        socket.emit(socketEventName, JSON.stringify(signalLeaveTable));
                         
//                             connection.close();
                             location.reload();
                        
                } })  
          
      



                }


              } });  

    }



  function leaveTable_fromserver(roomIdCookie, gamePlayersCookie, gameTypeCookie, sessionKeyCookie, betValueCookie, userId_){
    console.log('Leave table offline called...');
      var nextPlayer;
      
      // $('.popup_leave').hide();
      // $.cookie("rejoin", "0");
      // $.cookie("rejoinPR", "0"); 

      // intervalCounter = window.clearInterval(intervalCounter);
      // playerCounterFlag = 0;

      var creator = false;
      
      var creatorCookie = $.cookie("creator");

      if(parseInt(creatorCookie)==parseInt(userId_)){

          /*$.cookie("creator", null);
          $.removeCookie("creator");*/
          creator = true;
      }else{
          creator = false;
      }






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


               checkIfAllMelded(function(){

                  /* Update all the melded groups in db */

                      var ajxData81200 = { 'action': 'update-all-groups', roomId: roomIdCookie, player: userId_, meldedGroup1: meldedGroup1, meldedGroup2: meldedGroup2, meldedGroup3: meldedGroup3, meldedGroup4: meldedGroup4, sessionKey: sessionKeyCookie};


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
              var ajxData704 = {'action': 'update-my-status', roomId: roomIdCookie, player: userId_, sessionKey: sessionKeyCookie};

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

                    if(parseInt(player) == parseInt(userId_)){


                          /*if(getItem(playersPlayingTemp, parseInt(userId)) ){

                            nextPlayer = getItem(playersPlayingTemp, parseInt(userId));
                               
                          }else{
                            nextPlayer = playersPlayingTemp[0];
                               
                          }*/
                          nextPlayer = findNextPlayer(playersPlayingTemp,parseInt(userId_));
                          

                          console.log("nextplayer ", nextPlayer);

                          var ajxData260 = {'action': 'current-player', roomId: roomIdCookie, 
                                    player: parseInt(nextPlayer), sessionKey: sessionKeyCookie };

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


                     var signalLeaveTable  = {room:roomName, type: 'leave-table', user: userId_, nextPlayer: nextPlayer, message: "someone left...... message..", creator:creator}
                      console.log('Signal leave table offline called...',signalLeaveTable);
                        //connection.send(JSON.stringify(signalLeaveTable));
                        socket.emit('allmsg', JSON.stringify(signalLeaveTable));
                         
//                             connection.close();
                             //location.reload();
                        
                } })  
          
      



                }


              } });  

    }



     /** Popup leave **/

      $('.popup_leave #confirmBtn').click(function(){

        var roomIdCookie = $.cookie("room");
        var gamePlayersCookie = $.cookie("game-players");
        var gameTypeCookie = $.cookie("game-type");
        var sessionKeyCookie = $.trim($.cookie("sessionKey"));
        var betValueCookie = $.trim($.cookie("betValue"));
          
         leaveTable(roomIdCookie, gamePlayersCookie, gameTypeCookie, sessionKeyCookie, betValueCookie); 
           
       
      });
