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
                           var dataTosend = {
                              room:roomName,
                              player: userId,
                              field:"card_pull",
                              value:1,
                              cardPulled: card
                            };
                            $.post('ajax/cardPullCardDiscard.php',dataTosend,function(data){
                              console.log(data);
                            });

    
                            var signal11 = {room:roomName, type: 'card-pulled-show-card', message: 'card pulled', player: userId, cardPulled: card};
                                           
                            //connection.send(JSON.stringify(signal11));
                            socket.emit(socketEventName, JSON.stringify(signal11));


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
                             var dataTosend = {
                                room:roomName,
                                player: userId,
                                field:"card_pull",
                                value:1,
                                cardPulled: card
                              };
                              $.post('ajax/cardPullCardDiscard.php',dataTosend,function(data){
                                console.log(data);
                              });
                           /* Send card Pull signal to others */



                            var signal11 = {room:roomName, type: 'card-pulled-show-card', message: 'card pulled', player: userId, cardPulled: card};
                                           
                            //connection.send(JSON.stringify(signal11));

                            socket.emit(socketEventName, JSON.stringify(signal11));

                        }
                        

                    }

                })





        } 




    }


/** ====== Get show card from the deck ====== **/


$('.card-throw').delegate('#cardDeckSelectShow'+userId, 'click', function(){

  if( $(this).hasClass('clickable') ){

    var this_ = this; 

  // var dataTosend = {
  //   room:roomName,
  //   player: userId,
  //   field:"card_pull" 
  // };
  // $.post('ajax/cardPullCardDiscard_get.php',dataTosend,function(data){
  //   var resps = data.trim();
  //   cardPull = resps
  //   console.log('cardPulls',resps);
  


  //     if(cardPull == 0){
         

          var roomIdCookie = $.cookie("room");
          var sessionKeyCookie = $.trim($.cookie("sessionKey"));
          var self = $(this_);
          var cardJCR;
          

          var flagGroup = 0;
          var card;
  
          var rank = $(this_).attr('data-rank');
          var suit = $(this_).attr('data-suit');

          

          /* joker card */

          if(!suit){
              card = "Joker";
          }else{
              card = rank+'OF'+suit;
          }


              


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
                                        // alert('match!');

                                        console.log("match");
                                       

                                         var ajxDataJP = {'action': 'get-joker-pulled', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

                                          $.ajax({
                                                type: 'POST',
                                                data: ajxDataJP,
                                                cache: false,
                                                url: 'ajax/getJokerCardPulledCount.php',
                                                success: function(count){
                                                    console.log('count ', count);

                                                    if(parseInt(count) >= 1){
                                                        //alert("You cannot pull joker card");
                                                        $('.loading_container').show();
                                                        $('.loading_container .popup .popup_cont').text("You cannot pull joker card");

                                                        setTimeout(function(){
                                                          $('.loading_container').hide();
                                                          $('.loading_container .popup .popup_cont').text("");
                                                          }, 2000);
                                                    }else{

                                                        
                                                        // alert("1 ========");
                                                        updateJokerPulledCount(roomIdCookie, sessionKeyCookie);
                                                        cardPulledOpenDeck(roomIdCookie, sessionKeyCookie, self, card, rank, suit);

                                                        cardGotPulled = card;

                                                        



                                                    }


                                                 }
                                                    
                                         });

                                       


                                    }else{


                                         // alert("2 ========");
                                         updateJokerPulledCount(roomIdCookie, sessionKeyCookie);
                                         cardPulledOpenDeck(roomIdCookie, sessionKeyCookie, self, card, rank, suit);

                                         cardGotPulled = card;



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
                                        console.log('count ', count);

                                        if(parseInt(count) >= 1){
                                            // alert("You cannot pull joker card");

                                            $('.loading_container').show();
                                            $('.loading_container .popup .popup_cont').text("You cannot pull joker card");

                                            setTimeout(function(){
                                              $('.loading_container').hide();
                                              $('.loading_container .popup .popup_cont').text("");
                                              }, 2000);


                                        }else{

                                            // alert("3 ==========");    
                                            updateJokerPulledCount(roomIdCookie, sessionKeyCookie);
                                            cardPulledOpenDeck(roomIdCookie, sessionKeyCookie, self, card, rank, suit);

                                            cardGotPulled = card;

                                            



                                        }


                                     }
                                        
                             });



                    }

                    
                     $('.drop button').attr('disabled', true);
                     $('.drop button').css({'cursor':'default'});         

           

        }else{
            return false;
        }     


  });


  
  


   function cardPulledClosedDeck(self){

    var this_ = this; 

    var dataTosend = {
      room:roomName,
      player: userId,
      field:"card_pull" 
    };
    // $.post('ajax/cardPullCardDiscard_get.php',dataTosend,function(data){
    //   var resps = data.trim();
    //   cardPull = resps
    //   console.log('cardPulls',resps);

    //    if(cardPull == 0){

               var roomIdCookie = $.cookie("room");
               var sessionKeyCookie = $.trim($.cookie("sessionKey"));
              
               var flagGroup = 0;
               var card;

               if(self !== null){
                  self.prop('disabled', true);
               }
                
              

               
               $('.cardDeckSelect').removeClass('clickable').addClass('noSelect');
               cardPull = 1;
               var dataTosend = {
                  room:roomName,
                  player: userId,
                  field:"card_pull",
                  value:1,
                  cardPulled: card
                };
                $.post('ajax/cardPullCardDiscard.php',dataTosend,function(data){
                  console.log(data);
                });
               

              
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
                              cardGotPulled = card;

                              

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



                                          var signal11 = {room:roomName, type: 'card-pulled-show-card', message: 'card pulled', player: userId, cardPulled: card};
                                                         
                                         // connection.send(JSON.stringify(signal11));
                                          socket.emit(socketEventName, JSON.stringify(signal11));


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
                                      
                                          console.log("Got from deck show card in Hand!!!", card);



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
                                          var dataTosend = {
                                            room:roomName,
                                            player: userId,
                                            field:"card_pull",
                                            value:1,
                                            cardPulled: card
                                          };
                                          $.post('ajax/cardPullCardDiscard.php',dataTosend,function(data){
                                            console.log(data);
                                          });
                                         /* Send card Pull signal to others */



                                          var signal11 = {room:roomName, type: 'card-pulled-show-card', message: 'card pulled', player: userId, cardPulled: card};
                                                         
                                          //connection.send(JSON.stringify(signal11));
                                          socket.emit(socketEventName, JSON.stringify(signal11));


                                      }
                                      

                                  }

                              });





                      }          

                              

                  }
                          
              });         
              
              updateJokerPulledCount(roomIdCookie, sessionKeyCookie);
              $('.drop button').attr('disabled', true);
              $('.drop button').css({'cursor':'default'});

          // }else{
              
          //     return false;
          
          // }

       // });
   }


function cardPulledClosedDeck_offline(self, player){


var dataTosend = {
  room:roomName,
  player: userId,
  field:"card_pull"
};
$.post('ajax/cardPullCardDiscard_get.php',dataTosend,function(data){
  var resps = data.trim();
  cardPull = resps


  console.log('Offline js card pull ', cardPull);

       if(cardPull == 0){

               var roomIdCookie = $.cookie("room");
               var sessionKeyCookie = $.trim($.cookie("sessionKey"));
              
               var flagGroup = 0;
               var card;

               if(self !== null){
                  self.prop('disabled', true);
               }
                
              

               
               $('.cardDeckSelect').removeClass('clickable').addClass('noSelect');
               cardPull = 1;
               var dataTosend = {
                  room:roomName,
                  player: userId,
                  field:"card_pull",
                  value:1,
                  cardPulled: card
                };
                $.post('ajax/cardPullCardDiscard.php',dataTosend,function(data){
                  console.log(data);
                });
               

              
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
                              cardGotPulled = card;

                              

                         //      console.log("card rec: " + card);
                      

                         // /* add the card to group */
                         //  var groupCount = 1;
                          
                         //  /* add the card to the last group in display */

                         //  var groupNumber = 6;
                         //  eval('group'+groupNumber).push(card);
                         //  var groupAddedTo = eval('group'+groupNumber);





                         //  /* send the card to the db */

                         //     var ajxData600 = {'action': 'update-group-throwcard', roomId: roomIdCookie, playerId: player, groupAdded: groupAddedTo, groupAddNos: parseInt(groupNumber), sessionKey: sessionKeyCookie};

                         //    $.ajax({
                         //          type: 'POST',
                         //          data: ajxData600,
                         //          cache: false,
                         //          url: 'ajax/updateGroupThrowCard.php',
                         //          success: function(result){
                         //              if( $.trim(result == "ok") ){
                                      
                         //                  console.log("Got from deck show card auto disconnection!!!");

                         //                  var signal11 = {room:roomName, type: 'card-pulled-show-card', message: 'card pulled', player: player, cardPulled: card};
                                                         
                         //                 // connection.send(JSON.stringify(signal11));
                         //                  socket.emit(socketEventName, JSON.stringify(signal11));


                         //              }
                                      

                         //          }

                         //      })


                             

                              

                  }
                          
              });         
              
              // updateJokerPulledCount(roomIdCookie, sessionKeyCookie);
              //   $('.drop button').attr('disabled', true);
              //   $('.drop button').css({'cursor':'default'});

          }else{
              
              return false;
          
          }

      });

   }


    $('#cardDeckSelect'+userId).click(function(){
            if( $(this).hasClass('clickable') ){
               var self = $(this);
               cardPulledClosedDeck(self);

           }else{
            return false;
           } 

     })  