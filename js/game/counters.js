var gameStartHandler = function(callback){

  $('.loading_container').css({'display': 'block'});

    var interval = setInterval(function(){
       if(counter <= 0){
          clearInterval(interval);
          $('.loading_container').css({'display':'none'});
          callback();
        }
         

        if(counter <= 3){
          $('.leave_table_btn').attr('disabled', true);
        }


         $('.loading_container .popup .popup_cont').text("Game will start in " + counter + ' seconds');
         counter--;
       
      }, 1000);           

}



  var creatorCookie = $.cookie("creator");
  
  var gameStartHandlerSixPl = function(sixPlCounter, creatorCookie, roomId, sessionKey, callback){



    $('.loading_container').css({'display': 'block'});

      var interval = setInterval(function(){
       
        if(sixPlCounter <= 0){
            clearInterval(interval);
            $('.loading_container').css({'display':'none'});
            
            console.log("0 encountered");
            
            callback();
            // return;
           
        }

        if(sixPlCounter <= 3){
            $('.leave_table_btn').attr('disabled', true);
          }
        
        

         if(creatorCookie){

            console.log(sixPlCounter);

            /* update game start counter */
             var ajxData4001 = {'action': 'update-game-counter', roomId: roomId, counter: sixPlCounter, sessionKey:sessionKey};

                 $.ajax({
                  type: 'POST',
                  url: 'ajax/updateGameCounter.php',
                  cache: false,
                  data: ajxData4001,
                  success: function(result){
                    // console.log(result);

             } }); 




         }

         $('.loading_container .popup .popup_cont').text("Game will start in " + sixPlCounter + ' seconds');
         sixPlCounter--;

         
        }, 1000);


        

  }

         


    var nextGameStartHandler = function(callback){
          var interval = setInterval(function(){
           
           if(nextGameCounter <= 0){
            
              clearInterval(interval);
               
              
              callback();
            }
             // console.log(counter);
             $('.result_bottom').css({'display': 'block'});
             // $('.result_bottom #timer').text(nextGameCounter);
             $('.result_bottom').text('Next game will start in '+nextGameCounter+' seconds');
             nextGameCounter--;
           
          }, 1000);

    };

   


  var rejoinGameStartHandler = function(){

    var self = this; 
    this.counter;
    

    this.updateCounter = function(){

        $('.popup_rejoin .popup_counter p').text('Next game will start in '+self.counter+' seconds');

        if(self.counter == 0){

            $('.popup_rejoin .popup_counter p').text("");
             clearInterval(rejoinGameCounter);
             connection.close();
             location.reload();
              //$('.popup_rejoin #rejoinBtn').attr('disabled', true);
        }    

         self.counter--;


     }   


  };   

  var waitHandlerNormal = function(callback){
     callback();
     $('.loading_container .popup .popup_cont').text('Please wait while other player joins');
  }

  var TossWinnerHandler = function(callback){
    callback();
  };



   var playerCounterHandler = function(playerId){

            var self = this; 
            this.playerCounter;
            
            var roomIdCookie = $.cookie("room");
            var sessionKeyCookie = $.trim($.cookie("sessionKey"));
           
            var gameTypeCookie = $.cookie("game-type"); 
            var connectionIssueCount = $.trim($.cookie("connectionIssue"));


            var chipsToTablePRCookie = $.trim($.cookie("chipsToTablePR"));
            var currentBalanceCookie = $.trim($.cookie("currentBalancePR"));
            var minBuyingPRCookie = $.trim($.cookie("minBuyingPR"));
            var betValueCookie = $.trim($.cookie("betValue"));
            var gamePlayersCookie = $.cookie("game-players");




            this.run = function(){

                console.log("run function called =========== ", playerId);
            
                $('.current-player[data-user="'+playerId+'"] .card_submit_time').show();


            };

        

          this.updateCounter = function(){

               var netSpeed = $.trim($.cookie("netSpeed"));


                if(self.playerCounter >= 0){
                     $('.current-player[data-user="'+playerId+'"] .card_submit_time').text(self.playerCounter);
                 }else{
                     $('.current-player[data-user="'+playerId+'"] .card_submit_time').text("");
                 }

               
               if(self.playerCounter == 0){


                   if(userId == playerId){
                     


                         /* Pull card from closed deck if netspeed is slow */

                        if(netSpeed <= 1){

                            if(connectionIssueCount < 3){

                                /*  Check if before 1st round */
                              var ajxDataCheckDropType = {'action': 'check-drop-type', roomId: roomIdCookie, sessionKey: sessionKeyCookie, player: userId};

                              $.ajax({
                                  type: 'POST',
                                  data: ajxDataCheckDropType,
                                  cache: false,
                                  url: 'ajax/checkDropType.php',
                                  success: function(dropCount){

                                    if(dropCount > 0){ 
                                    
                                    /* Check how many times autoplayed  */


                                      var ajxDataCheckAutoplayedCount = {'action': 'check-autoplayed-count', roomId: roomIdCookie, sessionKey: sessionKeyCookie, player: userId};

                                      $.ajax({
                                          type: 'POST',
                                          data: ajxDataCheckAutoplayedCount,
                                          cache: false,
                                          url: 'ajax/checkAutoPlayedCount.php',
                                          success: function(count){

                                             console.log("AUTOPLAYED COUNT ", count);
                                             
                                             if(count >= 2){ 

                                                   if(gameTypeCookie != "deals2" && gameTypeCookie != "deals3"){
                                                      /* Drop the player */
                                                      intervalCounter = window.clearInterval(intervalCounter);
                                                      playerCounterFlag = 0;
                                                      dropFunction();


                                                  }else{
                                                    /* for deals game 80 points */

                                                    intervalCounter = window.clearInterval(intervalCounter);
                                                    playerCounterFlag = 0;
                                                   
                                                   $('.result_sec').css({'display': 'block'});   
                                               wrongValidationDisplayProcess(80, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'lost');


                                                  }  
                                             
                                             }else if(count < 2){

                                               //var elem = $('#cardDeckSelect'+userId);

                                               if(cardPull == 0){
                                               
                                                  cardPulledClosedDeck(null);

                                                   setTimeout(function(){ 

                                                      cardDiscardAuto(roomIdCookie, sessionKeyCookie, netSpeed); 
                                                
                                                    }, 10000);

                                               /* Discard the last card from group or from hand  */

                                               
                                               }else if(cardPull == 1){

                                                   setTimeout(function(){ 

                                                      cardDiscardAuto(roomIdCookie, sessionKeyCookie, netSpeed); 
                                                
                                                    }, 6000);


                                               } 
                              
                                             }
                                             
                                         } });  


                                    }else if(dropCount == 0){

                                      if(gameTypeCookie != "deals2" && gameTypeCookie != "deals3"){

                                           intervalCounter = window.clearInterval(intervalCounter);
                                           playerCounterFlag = 0;
                                           dropFunction();


                                      }else{

                                           intervalCounter = window.clearInterval(intervalCounter);
                                           playerCounterFlag = 0;
                                          
                                          $('.result_sec').css({'display': 'block'});   
                                          wrongValidationDisplayProcess(80, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'lost');


                                      }



                                    } } });


                                  }else if(connectionIssueCount >= 3){

                                       intervalCounter = window.clearInterval(intervalCounter);
                                       playerCounterFlag = 0;

                                      leaveTable(roomIdCookie, gamePlayersCookie, gameTypeCookie, sessionKeyCookie, betValueCookie);


                                  }


                                }else{


                                    if(cardPull == 0 && cardDiscard == 0){
                                         intervalCounter = window.clearInterval(intervalCounter);
                                         playerCounterFlag = 0;

                                         if(gameTypeCookie != "deals2" && gameTypeCookie != "deals3"){
                                            dropFunction();
                                         }else{

                                            if(gamePlayersCookie == "2"){
                                               $('.result_sec').css({'display': 'block'});   
                                               wrongValidationDisplayProcess(80, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'lost');
                                             }



                                            //wrongValidationDisplayProcess2(80, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'lost');
                                         }



                                    }else if(cardPull == 1 && cardDiscard == 0){

                                       cardDiscardAuto(roomIdCookie, sessionKeyCookie, netSpeed);
                                        
                                    }
                          




                              }    



                  }

                   $('.current-player[data-user="'+playerId+'"] .card_submit_time').hide();   
                   $('.current-player[data-user="'+playerId+'"] .card_submit_time').text("");

                }

                

              
                self.playerCounter--;

        };       

        

      };



 var cardMeldingCounterHandler = function(){

    var self = this; 
    this.counter;
    var roomIdCookie = $.cookie("room");
    var sessionKeyCookie = $.trim($.cookie("sessionKey"));

     this.updateCounter = function(){

        $('.show_your_card_sec .show_your_card_bottom #counter').text("You have " + self.counter + " secs left.");

        if(self.counter == 0){

            $('.show_your_card_sec .show_your_card_bottom #counter').text("");

            if(cardSubmitted == 0){

                /*  Meld card */

                clearInterval(cardMeldingIntervalCounter);
                cardSubmitFinal();



            }

        }    

         self.counter--;


     }   


  }     