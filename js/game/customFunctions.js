$('.player_card_me').delegate('.showFoldedCard', 'click', function(){
      $('.me .toss_me .playingCards').html('<div class="card card_2 back board_center_back showMyHand"></div>');
      $('.player_card_me').hide();

}); 

 $('.group_blog5').delegate('.showFoldedCard', 'click', function(){
      $('.me .toss_me .playingCards').html('<div class="card card_2 back board_center_back showMyHand"></div>');
      $('.group_blog5').hide();

}); 

 $('.me').delegate('.showMyHand', 'click', function(){
      $('.player_card_me').show();
      $('.group_blog5').show();
      $(this).hide();


}); 


$('.popup_bg').delegate('.dropBtn', 'click', function(){
    dropFunction();  
});  



function cardDiscardAuto(roomIdCookie, sessionKeyCookie, netSpeed){
    playerCounterFlag = 0;

    cardGotPulled = $.trim(cardGotPulled);

    if(cardsInHand.length == 0){

            /* remove from that group */

            $('.group_blog5 .playingCards .hand li a').removeClass('activeCard');

            var cardGroupNote;

            for(cardGroupNote = 6; cardGroupNote>0; cardGroupNote--){
                if(removeCardFromGroups(cardGotPulled, eval('group'+cardGroupNote))){
                   break;
                }
            }




            if(cardGotPulled != "Joker"){

                var cardNumber = cardGotPulled.substr(0, cardGotPulled.indexOf('OF'));
                var cardHouse =  cardGotPulled.substr(cardGotPulled.indexOf("OF") + 2);





              $('.group_blog5[data-group='+cardGroupNote+'] .playingCards .hand').find('li a[data-rank='+cardNumber+'][data-suit='+cardHouse+']').eq(0).remove();

               $('.group_blog5[data-group='+cardGroupNote+'] .playingCards .hand').find('li a[data-rank='+cardNumber+'][data-suit='+cardHouse+']').removeClass('activeCard');

               /* check if that is the only card in the group */
        
                 if($('.group_blog5[data-group='+cardGroupNote+'] .playingCards ul li a').length == 0){
                $('.group_blog5[data-group='+cardGroupNote+']').remove();
            }


              $('.card-throw .playingCards').append('<a href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+' id="cardDeckSelectShow'+userId+'" class="cardDeckSelect noSelect">'+
             '<div class="card card_2 rank-'+cardNumber+' '+cardHouse+'">'+
             '<span class="rank">'+cardNumber+'</span>'+
             '<span class="suit">&'+cardHouse+';</span>'+
                    '</div></a>');

             cardDiscardPlayer(roomIdCookie, sessionKeyCookie, cardGotPulled);

       



            }else{

                 $('.group_blog5[data-group='+cardGroupNote+'] .playingCards .hand').find('li a[data-rank="joker"]').eq(0).remove();

                  $('.group_blog5[data-group='+cardGroupNote+'] .playingCards .hand').find('li[data-rank="joker"]').removeClass('activeCard');

           

            if($('.group_blog5[data-group='+cardGroupNote+'] .playingCards ul li a').length == 0){
                $('.group_blog5[data-group='+cardGroupNote+']').remove();
            }

               
                  $('.card-throw .playingCards').append(' <a href="javascript:;" data-rank="joker" id="cardDeckSelectShow'+userId+'" class="cardDeckSelect noSelect"><div class="card joker card_2"></div></a>');


                  
                     cardDiscardPlayer(roomIdCookie, sessionKeyCookie, cardGotPulled);

                   
                    

            }    


                /* remove card from db */

                 var ajxData700 = {'action': 'remove-card-discard', roomId: roomIdCookie, playerId: userId, cardGroup: eval('group'+cardGroupNote), groupNos: cardGroupNote, sessionKey: sessionKeyCookie, netSpeed:netSpeed };

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


                               
                                
                                $('.current-player[data-user="'+userId+'"] .card_submit_time').hide(); 
                                $('.current-player[data-user="'+userId+'"] .card_submit_time').text(""); 
                               


                                 intervalCounter = window.clearInterval(intervalCounter);
                                 var PlayerCounterHandler = new playerCounterHandler(nextPlayerToSend);
                                    
                                    
                                    PlayerCounterHandler.playerCounter = 30;
                                    PlayerCounterHandler.run();
                                    intervalCounter = setInterval(PlayerCounterHandler.updateCounter, 1000); 


                                /* send discard signal to other players  */

                                var signal10 = {room:roomName, type: 'card-discarded', message: 'discard done', player: userId, cardDiscarded: cardGotPulled, nextPlayer: nextPlayerToSend};

                                cardsSelected.length = 0;
                                cardGotPulled = '';
                                
                                               
                                //connection.send(JSON.stringify(signal10));
                                socket.emit(socketEventName, JSON.stringify(signal10));


                                $('.discard button').attr('disabled', true);
                                $('.discard_top').remove();

                                $('#meld'+userId).css({'display': 'none'});


                               
                            }
                            

                        }

                    });




         }else{

          
             $('.player_card_me .hand li a').removeClass('activeCard');

                if( removeCardFromGroups(cardGotPulled, cardsInHand) ){

                    console.log(cardsInHand);


                    if(cardGotPulled != "Joker"){

                        var cardNumber = cardGotPulled.substr(0, cardGotPulled.indexOf('OF'));
                        var cardHouse =  cardGotPulled.substr(cardGotPulled.indexOf("OF") + 2);

               

                     $('.player_card_me .hand').find('li a[data-rank='+cardNumber+'][data-suit='+cardHouse+']').eq(0).parent().remove();

                   


                      $('.card-throw .playingCards').append('<a href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+' id="cardDeckSelectShow'+userId+'" class="cardDeckSelect noSelect">'+
                     '<div class="card card_2 rank-'+cardNumber+' '+cardHouse+'">'+
                     '<span class="rank">'+cardNumber+'</span>'+
                     '<span class="suit">&'+cardHouse+';</span>'+
                            '</div></a>');

                      cardDiscardPlayer(roomIdCookie, sessionKeyCookie, cardGotPulled);




                    }else{

                         $('.player_card_me .hand').find('li a[data-rank="joker"]').eq(0).parent().remove();

                        
                          $('.card-throw .playingCards').append(' <a href="javascript:;" data-rank="joker" id="cardDeckSelectShow'+userId+'" class="cardDeckSelect noSelect"><div class="card joker card_2"></div></a>');

                          cardDiscardPlayer(roomIdCookie, sessionKeyCookie, cardGotPulled);

                        
                    }

                    var ajxData700 = {'action': 'remove-card-discard-hand', roomId: roomIdCookie, playerId: userId, cardsInHand: cardsInHand, sessionKey: sessionKeyCookie, netSpeed: netSpeed };

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


                          

                               
                                $('.current-player[data-user="'+userId+'"] .card_submit_time').hide(); 
                                $('.current-player[data-user="'+userId+'"] .card_submit_time').text(""); 
                               



                                intervalCounter = window.clearInterval(intervalCounter);
                                var PlayerCounterHandler = new playerCounterHandler(nextPlayerToSend);
                                
                                
                                PlayerCounterHandler.playerCounter = 30;
                                PlayerCounterHandler.run();
                                intervalCounter = setInterval(PlayerCounterHandler.updateCounter, 1000); 

                            var signal10 = {room:roomName, type: 'card-discarded', message: 'discard done', player: userId, cardDiscarded: cardGotPulled, nextPlayer: nextPlayerToSend};

                             cardsSelected.length = 0;
                             cardGotPulled = '';
                                       
                            // connection.send(JSON.stringify(signal10));
                             socket.emit(socketEventName, JSON.stringify(signal10));
                              $('.discard button').attr('disabled', true);
                              $('.discard_top').remove();
                              $('#meld'+userId).css({'display': 'none'});


                               
                        }
                            

                    }

                });



         }


        




      }

}




function cardDiscardAuto_offline(roomIdCookie, sessionKeyCookie, netSpeed, nextPlayerId = "", nextPlrId = ""){
    playerCounterFlag = 0;

    cardGotPulled = $.trim(cardGotPulled); 


            /*

            if(cardGotPulled != "Joker"){

                var cardNumber = cardGotPulled.substr(0, cardGotPulled.indexOf('OF'));
                var cardHouse =  cardGotPulled.substr(cardGotPulled.indexOf("OF") + 2);




              $('.card-throw .playingCards').append('<a href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+' id="cardDeckSelectShow'+userId+'" class="cardDeckSelect noSelect">'+
             '<div class="card card_2 rank-'+cardNumber+' '+cardHouse+'">'+
             '<span class="rank">'+cardNumber+'</span>'+
             '<span class="suit">&'+cardHouse+';</span>'+
                    '</div></a>');



            }else{


               
                  $('.card-throw .playingCards').append(' <a href="javascript:;" data-rank="joker" id="cardDeckSelectShow'+userId+'" class="cardDeckSelect noSelect"><div class="card joker card_2"></div></a>');
                 
                    

            }
*/
            console.log('Card pull ---- ---- --- Done -- || ');
            $('.current-player[data-user="'+nextPlayerId+'"] .card_submit_time').hide(); 
            $('.current-player[data-user="'+nextPlayerId+'"] .card_submit_time').text(""); 
           


            intervalCounter = window.clearInterval(intervalCounter);
            var PlayerCounterHandler = new playerCounterHandler(nextPlrId);
                
                
            PlayerCounterHandler.playerCounter = 30;
            PlayerCounterHandler.run();
            intervalCounter = setInterval(PlayerCounterHandler.updateCounter, 1000); 


            // insert discarded card into db
            var ajxDataCardDiscard = {'action': 'add-card-discard', roomId: roomIdCookie, playerId: nextPlayerId, sessionKey: sessionKeyCookie, card:cardGotPulled};

            $.ajax({
            type: 'POST',
            data: ajxDataCardDiscard,
            cache: false,
            url: 'ajax/addCardDiscard.php',
            success: function(result){
              console.log("Card discarded added ======================== ", result);
            } });


            // place msg emit here  
            var signal10 = {room:roomName, type: 'card-discarded', message: 'discard done', player: nextPlayerId, cardDiscarded: cardGotPulled, nextPlayer: nextPlrId};
                        cardsSelected.length = 0;
                           cardGotPulled = '';

            console.log(signal10);                    
            
            socket.emit('allmsg', JSON.stringify(signal10));     


}


          /* ======= Show cards in scoreboard ========= */
function showCardsInScoreboard(player, roomIdCookie, sessionKeyCookie){

    console.log("player commmmmm ", player);
    console.log("roomid commmmmm ", roomIdCookie);
    console.log("session commmmmm ", sessionKeyCookie);

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
                var g5 = result.g5;

                // console.log("GROUPSSSSSSSSSSSSSSSSSSSSSSSS");
                // console.log(g1);
                // console.log(g2);
                // console.log(g3);
                // console.log(g4);
                // console.log(g5);


                for(var x = 1; x < 6; x++){

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

    

       return $playingCards;




    }


    function checkIfAllMelded(callback){

    if(group1.length == 0 && group2.length == 0 && group3.length == 0 && group4.length == 0 && group5.length == 0 && group6.length == 0 && cardsInHand.length != 0){

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



    }else if(cardsInHand.length == 0){

    var flagMeld2;
    // cardsInHand.length = 0;

    for(var i = 1; i < 7; i++){

            flagMeld2 = 0;

            if(eval('group'+i).length > 0){


                if(meldedGroup1.length > 0 && meldedGroup2.length > 0 && meldedGroup3.length > 0 && meldedGroup4.length > 0){

                  

                    for(var j = 0; j < eval('group'+i).length; j++){
                        meldedGroup5.push( eval('group'+i)[j] );

                        // if(eval('group'+i)[j] != "Joker"){

                        //         var cardNumber = eval('group'+i)[j].substr(0, eval('group'+i)[j].indexOf('OF'));
                                
                        //         var cardHouse =  eval('group'+i)[j].substr(eval('group'+i)[j].indexOf("OF") + 2);


                                
                        //         $('.show_your_card_blog[data-group="4"] .playingCards .hand').append('<li><a class="card card_2 rank-'+cardNumber+' '+cardHouse+'">'+
                        //             '<span class="rank">'+cardNumber+'</span>'+
                        //             '<span class="suit">&'+cardHouse+';</span>'+
                        //             '</a></li>');


                        //     }else{

                        //         $('.show_your_card_blog[data-group="4"] .playingCards .hand').append('<li><a href="javascript:;" data-rank="joker" class="card card_2 joker"></a></li>');


                        //     }

                    }  


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




   function dropFunction(){

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



      $('.cardDeckSelect').addClass("noSelect");
      $('.cardDeckSelect').removeClass("clickable");




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


              /* drop clicked */

              var ajxDataDropClicked = { 'action': 'drop-clicked', roomId: roomIdCookie, player: userId, sessionKey: sessionKeyCookie};


              $.ajax({
                  type: 'POST',
                  data: ajxDataDropClicked,
                  cache: false,
                  url: 'ajax/dropClicked.php',
                  success: function(result){
                     console.log("DROP CLICKED ======== " + result);
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
                                  $('.result_sec').css({'display': 'block'});
                                   wrongValidationDisplayProcessSixPlayers(10, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'drop');

                              }
                         
                         }else if(count == 1 && gameTypeCookie == "score"){ // Score game middle drop
                            

                              $('.result_sec').css({'display': 'block'});

                              if(gamePlayersCookie == "2"){
                                  wrongValidationDisplayProcess(30, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'middledrop');
                              }else if(gamePlayersCookie == "6"){
                                  $('.result_sec').css({'display': 'block'});
                                   wrongValidationDisplayProcessSixPlayers(30, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'middledrop');
                                  
                              }
                         }else if(count == 0 && gameTypeCookie == "101"){ // Pool game drop

                              if(gamePlayersCookie == "2"){

                                  $('.result_sec').css({'display': 'block'});

                                  wrongValidationDisplayProcess(20, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'drop');
                              
                              }else if(gamePlayersCookie == "6"){
                                  $('.result_sec').css({'display': 'block'});
                                   wrongValidationDisplayProcessSixPlayers(20, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'drop');

                              }
                         
                         }else if(count == 1 && gameTypeCookie == "101"){ // Score game middle drop
                            

                              $('.result_sec').css({'display': 'block'});

                              if(gamePlayersCookie == "2"){
                                  wrongValidationDisplayProcess(40, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'middledrop');
                              }else if(gamePlayersCookie == "6"){
                                  $('.result_sec').css({'display': 'block'});
                                   wrongValidationDisplayProcessSixPlayers(40, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'middledrop');
                                  
                              }
                         }else if(count == 0 && gameTypeCookie == "201"){ // Pool game drop

                              if(gamePlayersCookie == "2"){

                                  $('.result_sec').css({'display': 'block'});

                                  wrongValidationDisplayProcess(25, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'drop');
                              
                              }else if(gamePlayersCookie == "6"){
                                  $('.result_sec').css({'display': 'block'});
                                   wrongValidationDisplayProcessSixPlayers(25, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'drop');

                              }
                         
                         }else if(count == 1 && gameTypeCookie == "201"){ // Pool game drop

                              if(gamePlayersCookie == "2"){

                                  $('.result_sec').css({'display': 'block'});

                                  wrongValidationDisplayProcess(50, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'middledrop');
                              
                              }else if(gamePlayersCookie == "6"){
                                  $('.result_sec').css({'display': 'block'});
                                   wrongValidationDisplayProcessSixPlayers(50, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'middledrop');

                              }
                         
                         }



                         /* check if drop and go */

                         if(gameTypeCookie == "score"){

                                var ajxDataCheckDropAndGo = {'action': 'check-dropandgo', roomId: roomIdCookie, sessionKey: sessionKeyCookie, player: userId};

                                $.ajax({
                                  type: 'POST',
                                  data: ajxDataCheckDropAndGo,
                                  cache: false,
                                  url: 'ajax/checkDropAndGo.php',
                                  success: function(count){
                                      if(count == 1){
                                          /* drop and go selected */

                                          

                                          var nextPlayer;

                                           if(getItem(playersPlayingTemp, parseInt(userId)) ){

                                              nextPlayer = getItem(playersPlayingTemp, parseInt(userId));
                                 
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


                                          var signalDropAndGo = {room:roomName, type: 'drop-and-go', userDropped: userId, nextPlayer: nextPlayer};

                                          //connection.send(JSON.stringify(signalDropAndGo));
                                          socket.emit(socketEventName, JSON.stringify(signalDropAndGo));

                                        
                                  

                                          $('.loading_container').css({'display':'block'});
                                          $('.loading_container .popup .popup_cont').text("Please wait... You will be taken to another table!");

                                            setTimeout(function(){
                                              connection.close();
                                              $.cookie("rejoinPR", "1");
                                              $.cookie("rejoin", "0");
                                              $.cookie("onOpenHit", null);
                                              $.removeCookie("onOpenHit");
                                              location.reload();

                                           }, 8000);   





                                      }


                                  } });    


                         }



                      
                      
                      } });


         });     




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