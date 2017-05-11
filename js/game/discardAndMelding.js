

  /* ==== Group Each Melding Btn clicked ======= */

  $('.group').delegate('.meld_group_btn button', 'click', function(){

      if(meldedGroup1.length > 0 && meldedGroup2.length > 0 && meldedGroup3.length > 0 && meldedGroup4.length > 0){
          cardsInHand.length = 0;

          return false;
      
       }else{   
      
      var groupBtn = $(this).attr('data-button');
      var flagMeld1 = 0;
      var self = $(this);
      var roomIdCookie = $.cookie("room"); 
      var sessionKeyCookie = $.trim($.cookie("sessionKey"));
      cardsInHand.length = 0;



          if(meldedGroup1.length == 0){
              flagMeld1 = 1;
          }else if(meldedGroup2.length == 0){
              flagMeld1 = 2;
          }else if(meldedGroup3.length == 0){
              flagMeld1 = 3;
          }else if(meldedGroup4.length == 0){
              flagMeld1 = 4;
          }

           console.log('flag set earyl ' + flagMeld1);


          for(var j = 0; j < eval('group'+groupBtn).length; j++){
                  /* Push the cards into new melded group */

                  eval('meldedGroup'+flagMeld1).push( eval('group'+groupBtn)[j] );

          }


          /* Update melded groups in db */


          var ajxData800 = { 'action': 'update-meld-card', roomId: roomIdCookie, player: userId, groupDeleted: groupBtn, meldedGroup: eval('meldedGroup'+flagMeld1), meldedGroupNos: flagMeld1, sessionKey: sessionKeyCookie };


              $.ajax({
                  type: 'POST',
                  data: ajxData800,
                  cache: false,
                  url: 'ajax/updateMeldedCard.php',
                  success: function(result){
                      if( $.trim(result == "ok") ){

                          console.log("DB Updated");

                          /* View */

                          /* Show melded card in melded card section */

                          $('.show_your_card_blog[data-group="'+flagMeld1+'"]').prepend('<a href="javascript:;" class="removeMeldedGroup" id="'+flagMeld1+'"><i class="fa fa-close"></i></a>');


                          for(var i = 0; i < eval('meldedGroup'+flagMeld1).length; i++){

                                          /* show the card */

                              if(eval('meldedGroup'+flagMeld1)[i] != "Joker"){

                                  var cardNumber = eval('meldedGroup'+flagMeld1)[i].substr(0, eval('meldedGroup'+flagMeld1)[i].indexOf('OF'));
                                  
                                  var cardHouse =  eval('meldedGroup'+flagMeld1)[i].substr(eval('meldedGroup'+flagMeld1)[i].indexOf("OF") + 2);


                                  
                                  $('.show_your_card_blog[data-group="'+flagMeld1+'"] .playingCards .hand').append('<li><a class="card card_2 rank-'+cardNumber+' '+cardHouse+'">'+
                                      '<span class="rank">'+cardNumber+'</span>'+
                                      '<span class="suit">&'+cardHouse+';</span>'+
                                      '</a></li>');


                              }else{

                                  $('.show_your_card_blog[data-group="'+flagMeld1+'"] .playingCards .hand').append('<li><a href="javascript:;" data-rank="joker" class="card card_2 joker"></a></li>');


                              }


                          }



                          /* Remove empty group from UI */

                          $('.group_blog5[data-group="'+groupBtn+'"]').remove();

                          /* set the flag value to 0 */
                          flagMeld1 = 0;
                          console.log('flag set ' + flagMeld1);

                           /* Remove cards from that group  */

                          eval('group'+groupBtn).length = 0;
          

                          /* Hide the button */

                          self.parent().css({'display': 'none'});



                      }
                      
                  }
                  
              });


         }                 
             
            
    });




  
   /** === Meld All Button clicked ===== **/

    $('#meldAll'+userId).click(function(){

        var roomIdCookie = $.cookie("room");
        var sessionKeyCookie = $.trim($.cookie("sessionKey"));
        
        var self = $(this);

        /* if group exist */

         if(cardGroupSelected){


            /*  check if group not empty insert into meldedGroups */

                for(var q = 1; q < 7; q++){

                    if(meldedGroup1.length > 0 && meldedGroup2.length > 0 && meldedGroup3.length > 0 && meldedGroup4.length > 0){
                        break;
                    }

                    if( eval('group'+q).length > 0 ){
                        /*  Group has card */

                        /*  Insert those cards into meldedGroups */

                       var meldedGrpFlag = 0;

                       if(meldedGroup1.length == 0){
                        meldedGrpFlag = 1;
                       }else if(meldedGroup2.length == 0){
                        meldedGrpFlag = 2;
                       }else if(meldedGroup3.length == 0){
                        meldedGrpFlag = 3;
                       }else if(meldedGroup4.length == 0){
                        meldedGrpFlag = 4;
                       }



                        for(var j = 0; j < eval('group'+q).length; j++){


                            eval('meldedGroup'+meldedGrpFlag).push( eval('group'+q)[0] );
                            
                            console.log("Card pushed ", eval('group' + q)[0]);


                            if(removeCardFromGroups(eval('group'+q)[0], eval('group'+q))){
                              console.log("Grp " + q + " - " + eval("group"+q));
                            }

                            console.log("Melded group ", eval("meldedGroup"+meldedGrpFlag));
                            console.log("Grp " + q + " - " + eval("group"+q));
                           

                           
     
                        } 

                     


                        /*  Ajax remove cards from group */
                    }



                }



                /*  Show melded cards in meld section */


                /*  4 melded groups, 4 iterations */

                for(var k = 1; k < 5; k++){


                    $('.show_your_card_blog[data-group="'+k+'"]').prepend('<a href="javascript:;" class="removeMeldedGroup" id="'+k+'"><i class="fa fa-close"></i></a>');


                    for(var i = 0; i < eval('meldedGroup'+k).length; i++){

                        /* show the card */

                        if(eval('meldedGroup'+k)[i] != "Joker"){

                            var cardNumber = eval('meldedGroup'+k)[i].substr(0, eval('meldedGroup'+k)[i].indexOf('OF'));
                            
                            var cardHouse =  eval('meldedGroup'+k)[i].substr(eval('meldedGroup'+k)[i].indexOf("OF") + 2);


                            
                            $('.show_your_card_blog[data-group="'+k+'"] .playingCards .hand').append('<li><a class="card card_2 rank-'+cardNumber+' '+cardHouse+'">'+
                                '<span class="rank">'+cardNumber+'</span>'+
                                '<span class="suit">&'+cardHouse+';</span>'+
                                '</a></li>');


                        }else{

                            $('.show_your_card_blog[data-group="'+k+'"] .playingCards .hand').append('<li><a href="javascript:;" data-rank="joker" class="card card_2 joker"></a></li>');


                        }


                    }




                }


                console.log('group1 ', group1);
                console.log('group2 ', group2);
                console.log('group3 ', group3);
                console.log('group4 ', group4);
                console.log('group5 ', group5);
                console.log('group6 ', group6);

                console.log('meldgroup1 ', meldedGroup1);
                console.log('meldgroup2 ', meldedGroup2);
                console.log('meldgroup3 ', meldedGroup3);
                console.log('meldgroup4 ', meldedGroup4);


                if(group1.length == 0){

                    $('.group_blog5[data-group="1"] .playingCards .hand').html("");

                }

                if(group2.length == 0){

                    $('.group_blog5[data-group="2"] .playingCards .hand').html("");

                }

                if(group3.length == 0){

                    $('.group_blog5[data-group="3"] .playingCards .hand').html("");

                }

                if(group4.length == 0){

                    $('.group_blog5[data-group="4"] .playingCards .hand').html("");

                }

                if(group5.length == 0){

                    $('.group_blog5[data-group="5"] .playingCards .hand').html("");

                }

                if(group6.length == 0){

                    $('.group_blog5[data-group="6"] .playingCards .hand').html("");

                }

           


         }else{



           

            if(cardsInHand.length > 0){



                /* Transfer all cards to group1 */
                
                for(var i = 0; i < cardsInHand.length; i++){
                    group1.push(cardsInHand[i]);
                }

                cardsInHand.length = 0; // empty cards in hand



                /* transfer all group1 cards to meldedGroup */


                for(var i = 0; i < group1.length; i++){

                    meldedGroup1.push(group1[i]);

                }



                group1.length = 0; // empty group1


                /*  Update melded cards group  */

                 var ajxData802 = { 'action': 'update-meld-card', roomId: roomIdCookie, player: userId, meldedGroup: meldedGroup1, meldedGroupNos: 1, sessionKey: sessionKeyCookie };


                $.ajax({
                    type: 'POST',
                    data: ajxData802,
                    cache: false,
                    url: 'ajax/updateMeldedCardNoGroup.php',
                    success: function(result){
                        if( $.trim(result == "ok") ){

                            console.log("DB Updated ---- melded group !");


                            /*  UI changes */

                             /* remove cards from table  */

                             $('.player_card_me .hand').html("");


                               /* Show melded card in melded card section */

                            $('.show_your_card_blog[data-group="1"]').prepend('<a href="javascript:;" class="removeMeldedGroup" id="1"><i class="fa fa-close"></i></a>');


                            for(var i = 0; i < meldedGroup1.length; i++){

                                /* show the card */

                                if(meldedGroup1[i] != "Joker"){

                                    var cardNumber = meldedGroup1[i].substr(0, meldedGroup1[i].indexOf('OF'));
                                    
                                    var cardHouse =  meldedGroup1[i].substr(meldedGroup1[i].indexOf("OF") + 2);


                                    
                                    $('.show_your_card_blog[data-group="1"] .playingCards .hand').append('<li><a class="card card_2 rank-'+cardNumber+' '+cardHouse+'">'+
                                        '<span class="rank">'+cardNumber+'</span>'+
                                        '<span class="suit">&'+cardHouse+';</span>'+
                                        '</a></li>');


                                }else{

                                    $('.show_your_card_blog[data-group="1"] .playingCards .hand').append('<li><a href="javascript:;" data-rank="joker" class="card card_2"></a></li>');


                                }


                            }


                            self.hide();

                        }
                    }
                 });           


            }


        }   




    });



  

  /** === Validation Melding ===== **/

  function cardSubmitFinal(){

        $(this).attr('disabled', true);
        cardSubmitted = 1;
        clearInterval(cardMeldingIntervalCounter);

      

      
       var roomIdCookie = $.cookie("room");
       var gameTypeCookie = $.cookie("game-type");
       var gamePlayersCookie = $.cookie("game-players");
       var sessionKeyCookie = $.trim($.cookie("sessionKey"));

        var chipsToTablePRCookie = $.trim($.cookie("chipsToTablePR"));
        var currentBalanceCookie = $.trim($.cookie("currentBalance"));
        var minBuyingPRCookie = $.trim($.cookie("minBuying"));
        var betValueCookie = $.trim($.cookie("betValue"));


       

       checkIfAllMelded(function(){

          $('.meldAll').hide();

          $('.group_blog5').remove();



              /* Update all the melded groups in db */

              var ajxData81200 = { 'action': 'update-all-groups', roomId: roomIdCookie, player: userId, meldedGroup1: meldedGroup1, meldedGroup2: meldedGroup2, meldedGroup3: meldedGroup3, meldedGroup4: meldedGroup4, meldedGroup5:meldedGroup5, sessionKey: sessionKeyCookie};


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


               if(meldingProcess == 0){


                  var groupCountFlag = 0;

                  if(meldedGroup1.length > 0){
                      groupCountFlag = 1;
                  }

                  if(meldedGroup2.length > 0){
                      groupCountFlag = 2;
                  }

                  if(meldedGroup3.length > 0){
                      groupCountFlag = 3;
                  }

                  if(meldedGroup4.length > 0){
                      groupCountFlag = 4;
                  }



                  if(groupCountFlag != 4){

                     if(gamePlayersCookie == "2"){
                       
                       $('.result_sec').css({'display': 'block'});
                       wrongValidationDisplayProcess(80, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'wrongshow');
                     }else if(gamePlayersCookie == "6"){
                      $('.result_sec').css({'display': 'block'});
                       wrongValidationDisplayProcessSixPlayers(80, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'wrongshow');
                     } 
                      


                  }else{


                     

                      

                      var ajxData888 = { 'action': 'get-joker-card', roomId: roomIdCookie, sessionKey: sessionKeyCookie};


                              $.ajax({
                                  type: 'POST',
                                  data: ajxData888,
                                  cache: false,
                                  url: 'ajax/getJokerCard.php',
                                  success: function(card){

                                      console.log("joker retrieve ", card);

                                      if(card != "Joker"){
                                          var cardNumber = card.substr(0, card.indexOf('OF'));

                                          
                                          console.log("cardNumber ", cardNumber);


                                           if($.trim(cardNumber) == "J"){
                                              jokerValue = 11;
                                          }else if($.trim(cardNumber) == "Q"){
                                              jokerValue = 12;
                                             
                                          }else if($.trim(cardNumber) == "K"){
                                              jokerValue = 13;
                                              
                                          }else if($.trim(cardNumber) == "A"){
                                              jokerValue = 1;

                                          }else{
                                              jokerValue = parseInt(cardNumber);
                                          }

                                      }else{

                                         jokerValue = 20;
                                          
                                      }



                                 }
                                 
                          });


                      /* Lets analyze all the groups */ 

                          /** ========= If any group has less than 3 or more than 4 cards  OR  =========== */

                      

                           setTimeout(function(){

                              

                              var flag2GroupMoreThan3Cards = 0;
                              var meldLessOrMoreCards = 0;


                              for(var i = 1; i < 5; i++){
                                  
                                  if( eval('meldedGroup'+i).length < 3 || eval('meldedGroup'+i).length > 4 ){
                                      console.log("wrongshow ", 'meldedGroup'+i );
                                       if(gamePlayersCookie == "2"){
                                         $('.result_sec').css({'display': 'block'}); 
                                        wrongValidationDisplayProcess(80, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'wrongshow');
                                      }else if(gamePlayersCookie == "6"){
                                          $('.result_sec').css({'display': 'block'});
                                       wrongValidationDisplayProcessSixPlayers(80, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'wrongshow');
                                      } 
                                      meldLessOrMoreCards++;
                                      break;
                                  }else if(flag2GroupMoreThan3Cards > 1){
                                      console.log("wrongshow more than 3 cards in more than 1 grp!");
                                       if(gamePlayersCookie == "2"){
                                       $('.result_sec').css({'display': 'block'});
                                       wrongValidationDisplayProcess(80, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'wrongshow');
                                      }else if(gamePlayersCookie == "6"){
                                       $('.result_sec').css({'display': 'block'});
                                       wrongValidationDisplayProcessSixPlayers(80, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'wrongshow');
                                      } 
                                      break;
                                  }

                                   if( eval('meldedGroup'+i).length > 3){

                                      flag2GroupMoreThan3Cards++;

                                    }  


                              }




                                if(flag2GroupMoreThan3Cards == 1 && meldLessOrMoreCards == 0){

                              /** ============= Proper Show . Let's resume ============= */
                              
                               


                                      /* Segrating the cards into suit and values and pushing them in MeldCardArr(Nos) */

                                      // code under construction

                                    for(var i = 1; i < 5; i++){

                                      for(j = 0; j < eval('meldedGroup'+i).length; j++){

                                          if(eval('meldedGroup'+i)[j] != "Joker"){

                                              var cardNumber = eval('meldedGroup'+i)[j].substr(0, eval('meldedGroup'+i)[j].indexOf('OF'));
                                      
                                              var cardHouse =  eval('meldedGroup'+i)[j].substr(eval('meldedGroup'+i)[j].indexOf("OF") + 2);

                                                var cardValue;

                                               if(cardNumber == "J"){
                                              
                                                  cardValue = 11;
                                              }else if(cardNumber == "Q"){
                                                  cardValue = 12;
                                             
                                              }else if(cardNumber == "K"){
                                                  cardValue = 13;
                                              
                                              }else if(cardNumber == "A"){
                                                  cardValue = 1;

                                              }else{
                                                  cardValue = cardNumber;
                                              }

                                              eval('meldCardArr'+i).push({"value": parseInt(cardValue),"suit": cardHouse});

                                          }else{

                                              eval('meldCardArr'+i).push({"suit": "joker"});

                                          }

                                      }


                                    }


                                      // meldCardArr1.push({"value":1,"suit":"hearts"},{"value":12,"suit":"diams"},{"value":12,"suit":"clubs"});
                                      // meldCardArr2.push({"value":2,"suit":"hearts"},{"suit":"joker"},{"suit":"joker"},{"value":4,"suit":"hearts"});
                                      // meldCardArr3.push({"value":5,"suit":"hearts"},{"value":6,"suit":"hearts"},{"value":7,"suit":"hearts"});
                                      // meldCardArr4.push({"value":5,"suit":"hearts"},{"value":5,"suit":"diams"},{"value":5,"suit":"clubs"});

                                      //  meldCardArr1.push({"value":8,"suit":"hearts"},{"value":9,"suit":"hearts"},{"value":10,"suit":"hearts"});
                                      // meldCardArr2.push({"value":11,"suit":"spades"},{"value":12, "suit":"spades"},{"value":13, "suit":"spades"},{"value":1,"suit":"spades"});
                                      // meldCardArr3.push({"value":7,"suit":"clubs"},{"value":7,"suit":"diams"},{"value":7,"suit":"spades"});
                                      // meldCardArr4.push({"value":7,"suit":"diams"},{"value":7,"suit":"hearts"},{"value":2,"suit":"hearts"});


                                   
                              /** ======================= Checking the melding function , Melding Algorithm ================ **/



                                  

                                  for(var i = 1; i < 5; i++){
                                      console.log("Joker value ", jokerValue);
                                      console.log(getSummary(eval('meldCardArr'+i), i));

                                       // test output
                                  }

                                




                                  /* set the values of pureSeq, matchingCards and impureSeq  */
                                   

                                  for(var i = 1; i < 5; i++){
                                      if( eval('meldCardEvaluator'+i)[0].isPure === true && eval('meldCardEvaluator'+i)[0].isSequence === true){
                                          pureSequence++;
                                          console.log(pureSequence);
                                      }else if(eval('meldCardEvaluator'+i)[0].isPure === false && eval('meldCardEvaluator'+i)[0].isSequence === true){
                                          impureSequence++;
                                          console.log(impureSequence);
                                      }else if(eval('meldCardEvaluator'+i)[0].isSameValue === true){
                                          matchingCards++;
                                          console.log(matchingCards);
                                      }else if( eval('meldCardEvaluator'+i)[0].isPure === false && eval('meldCardEvaluator'+i)[0].isSequence === true && eval('meldCardEvaluator'+i)[0].isSameValue === true ){
                                              impureSequence++;
                                              console.log(impureSequence);
                                      }else if( eval('meldCardEvaluator'+i)[0].isPure === true && eval('meldCardEvaluator'+i)[0].isSequence === true && eval('meldCardEvaluator'+i)[0].isSameValue === true ){
                                              pureSequence++;
                                              console.log(pureSequence);
                                             
                                      }  
                                  }



                                setTimeout(function(){


                                      console.log("group total ", pureSequence+impureSequence+matchingCards);

                                      if( (pureSequence+impureSequence+matchingCards) === 4 && (pureSequence+impureSequence) >= 2){


                                      console.log("success melding");


                                      /* successful melding */
                                      
                                  $('.result_sec').css({'display': 'block'}); 
                                  
                                    successfulMelding(roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'won');   


                                    var signalStartCounterMelding = {room:roomName, type: 'start-counter-melding', message: 'starting counter melding....'};
                  
                                    //connection.send(JSON.stringify(signalStartCounterMelding));
                                    socket.emit(socketEventName, JSON.stringify(signalStartCounterMelding));




                                  }else{


                                      console.log('meld error');
                                      console.log(pureSequence+impureSequence+matchingCards);
                                      console.log("pure " + pureSequence);
                                      console.log("impure " + impureSequence);
                                      console.log("matching " + matchingCards);
                                     
                                      if(gamePlayersCookie == "2"){
                                       $('.result_sec').css({'display': 'block'});   
                                       wrongValidationDisplayProcess(80, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'wrongshow');
                                     }else if(gamePlayersCookie == "6"){
                                      $('.result_sec').css({'display': 'block'});
                                       wrongValidationDisplayProcessSixPlayers(80, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'wrongshow');
                                     } 

                                  }

                                }, 3000);  
                                



                                

                              }


                                
                           }, 3000);  



                        




                  }

               }else if(meldingProcess == 1){


                  /* If cards have already melded by another user */

                  console.log("MELDING PROCESS ----------------- ", 1);

                 

                  /* Total score sum */
                  var totalScoreSum = 0;

                   /* Check number of groups */

                      var groupCountFlag = 0;

                      if(meldedGroup1.length > 0){
                          groupCountFlag = 1;
                      }

                      if(meldedGroup2.length > 0){
                          groupCountFlag = 2;
                      }

                      if(meldedGroup3.length > 0){
                          groupCountFlag = 3;
                      }

                      if(meldedGroup4.length > 0){
                          groupCountFlag = 4;
                      }

                      if(meldedGroup5.length > 0){
                        groupCountFlag = 5;
                      }


                 


                  /*  If only 1 or no group */

                  if(groupCountFlag <= 1){

                      /* get total score */

                       //var jokerCardNumber;

                          var ajxData888 = { 'action': 'get-joker-card', roomId: roomIdCookie, sessionKey: sessionKeyCookie};


                              $.ajax({
                                  type: 'POST',
                                  data: ajxData888,
                                  cache: false,
                                  url: 'ajax/getJokerCard.php',
                                  success: function(card){

                                      console.log("joker retrieve ", card);

                                      if(card != "Joker"){
                                          var cardNumber = card.substr(0, card.indexOf('OF'));
                                          //jokerCardNumber = cardNumber;

                                          
                                          console.log("cardNumber ", cardNumber);

                                          
                                         if($.trim(cardNumber) == "J"){
                                              jokerValue = 11;
                                             
                                          }else if($.trim(cardNumber) == "Q"){
                                              jokerValue = 12;
                                             
                                          }else if($.trim(cardNumber) == "K"){
                                              jokerValue = 13;
                                              
                                          }else if($.trim(cardNumber) == "A"){
                                              jokerValue = 1;
                                              
                                          }else{
                                              jokerValue = parseInt(cardNumber);
                                             
                                          }

                                      }else{

                                         jokerValue = 20;
                                          
                                      }


                             }
                                 
                          });




                      // transfer all cards to meldedgroup1 from cardsInHand

                      if(cardsInHand.length > 0){

                          for(var i = 0; i < cardsInHand.length; i++){

                              meldedGroup1.push(cardsInHand[i]);
                          }

                          cardsInHand.length = 0;

                      }


                              for(j = 0; j < meldedGroup1.length; j++){

                                  if(meldedGroup1[j] != "Joker"){

                                      var cardNumber = meldedGroup1[j].substr(0, meldedGroup1[j].indexOf('OF'));
                              
                                      var cardHouse =  meldedGroup1[j].substr(meldedGroup1[j].indexOf("OF") + 2);

                                      var cardValue;

                                       if(cardNumber == "J"){
                                          cardValue = 11;
                                      }else if(cardNumber == "Q"){
                                          cardValue = 12;
                                      }else if(cardNumber == "K"){
                                          cardValue = 13;
                                      }else if(cardNumber == "A"){
                                          cardValue = 1;
                                      }else{
                                          cardValue = cardNumber;
                                      }


                                      meldCardArr1.push({"value": parseInt(cardValue),"suit": cardHouse});



                                  }else{

                                      meldCardArr1.push({"suit": "joker"});

                                  }

                              }


                            
                              for(var j = 0; j < meldCardArr1.length; j++){

                                  if(meldCardArr1[j].suit !== "joker"){
                                    

                                      if(meldCardArr1[j].value == 1 || meldCardArr1[j].value == 11 || meldCardArr1[j].value == 12 || meldCardArr1[j].value == 13){


                                           if(meldCardArr1[j].value == jokerValue){
                                              totalScoreSum = totalScoreSum + 0;
                                          }else{
                                               totalScoreSum = totalScoreSum + 10;
                                          }

                                         
                                          

                                      }else{
                                         

                                          if(meldCardArr1[j].value == jokerValue){
                                              totalScoreSum = totalScoreSum + 0;
                                          }else{
                                               totalScoreSum = totalScoreSum + meldCardArr1[j].value;
                                          }
                                      }
                                      

                                  }else{
                                       totalScoreSum = totalScoreSum + 0;
                                  }

                              

                              }

                            /* Check if it will be drop or middle drop */
                       var ajxDataCheckDropType = {'action': 'check-drop-type', roomId: roomIdCookie, sessionKey: sessionKeyCookie, player: userId};   


                            $.ajax({
                                  type: 'POST',
                                  data: ajxDataCheckDropType,
                                  cache: false,
                                  url: 'ajax/checkDropType.php',
                                  success: function(count){

                                     // alert("drop type checked");
                                      console.log("count checking ", count);
                             
                                     if(count == 0){ 
                                          totalScoreSum = Math.round(totalScoreSum/2);    

                                     } 

                                     // alert("new total score sum is " + totalScoreSum);
                                     console.log("new total score sum is  ", totalScoreSum);


                                     /* Update score */      
                                        $('.result_sec').css({'display': 'block'});
                                      wrongValidationDisplayProcess2(totalScoreSum, roomIdCookie, gameTypeCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'lost');

                                  } });        

                     

                       // Score is 80
                               


                   }else if(groupCountFlag > 1){


                         

                      var ajxData888 = { 'action': 'get-joker-card', roomId: roomIdCookie, sessionKey: sessionKeyCookie};


                              $.ajax({
                                  type: 'POST',
                                  data: ajxData888,
                                  cache: false,
                                  url: 'ajax/getJokerCard.php',
                                  success: function(card){

                                      console.log("joker retrieve ", card);

                                      if(card != "Joker"){
                                          var cardNumber = card.substr(0, card.indexOf('OF'));
                                          //jokerCardNumber = cardNumber;

                                          
                                          console.log("cardNumber ", cardNumber);

                                          
                                         if($.trim(cardNumber) == "J"){
                                              jokerValue = 11;
                                             
                                          }else if($.trim(cardNumber) == "Q"){
                                              jokerValue = 12;
                                             
                                          }else if($.trim(cardNumber) == "K"){
                                              jokerValue = 13;
                                              
                                          }else if($.trim(cardNumber) == "A"){
                                              jokerValue = 1;
                                              
                                          }else{
                                              jokerValue = parseInt(cardNumber);
                                             
                                          }

                                      }else{

                                         jokerValue = 20;
                                          
                                      }



                                 }
                                 
                          });



                           setTimeout(function(){

                                  // Adding the cards into arrays for evaluation



                               

                                    for(var i = 1; i < parseInt(groupCountFlag+1); i++){

                                      for(j = 0; j < eval('meldedGroup'+i).length; j++){

                                          if(eval('meldedGroup'+i)[j] != "Joker"){

                                              var cardNumber = eval('meldedGroup'+i)[j].substr(0, eval('meldedGroup'+i)[j].indexOf('OF'));
                                      
                                              var cardHouse =  eval('meldedGroup'+i)[j].substr(eval('meldedGroup'+i)[j].indexOf("OF") + 2);

                                              var cardValue;

                                               if(cardNumber == "J"){
                                              
                                                  cardValue = 11;
                                              }else if(cardNumber == "Q"){
                                                  cardValue = 12;
                                             
                                              }else if(cardNumber == "K"){
                                                  cardValue = 13;
                                              
                                              }else if(cardNumber == "A"){
                                                  cardValue = 1;

                                              }else{
                                                  cardValue = cardNumber;
                                              }

                                              eval('meldCardArr'+i).push({"value": parseInt(cardValue),"suit": cardHouse});



                                          }else{

                                              eval('meldCardArr'+i).push({"suit": "joker"});

                                          }

                                      }


                                    }



                                     // meldCardArr1.push({"value":2,"suit":"clubs"},{"value":3,"suit":"clubs"},{"value":5,"suit":"clubs"},{"value":4,"suit":"clubs"});
                                     //  meldCardArr2.push({"value":2,"suit":"hearts"},{"suit":"joker"},{"value":8, "suit": "hearts"});
                                     //  meldCardArr3.push({"value":6,"suit":"hearts"},{"value":6,"suit":"diams"},{"value":12,"suit":"hearts"});
                                     //  meldCardArr4.push({"value":4,"suit":"hearts"},{"value":4,"suit":"diams"},{"value":4,"suit":"clubs"});



                                  for(var i = 1; i < parseInt(groupCountFlag+1); i++){
                                      console.log("Joker value ", jokerValue);
                                      console.log(getSummary(eval('meldCardArr'+i), i));
                                  }


                                  /* add total score */


                                  for(var i = 1; i < parseInt(groupCountFlag+1); i++){

                                     

                                      for(var j = 0; j < eval('meldCardArr'+i).length; j++){

                                            if(  eval('meldCardArr'+i).length !==0 ){ 

                                              if(eval('meldCardArr'+i)[j].suit !== "joker"){
                                                

                                                  if(eval('meldCardArr' + i)[j].value == 1 || eval('meldCardArr' + i)[j].value == 11 || eval('meldCardArr' + i)[j].value == 12 || eval('meldCardArr' + i)[j].value == 13){
                                                     
                                                      if(eval('meldCardArr' + i)[j].value == jokerValue){
                                                          totalScoreSum = totalScoreSum + 0;
                                                      }else{
                                                           totalScoreSum = totalScoreSum + 10;
                                                      }


                                                  }else{

                                                      if(eval('meldCardArr' + i)[j].value == jokerValue){
                                                          totalScoreSum = totalScoreSum + 0;
                                                      }else{
                                                           totalScoreSum = totalScoreSum + eval('meldCardArr'+i)[j].value;
                                                      }

                                                  }


                                              }else{
                                                  totalScoreSum = totalScoreSum + 0;
                                              }

                                            }

                                         

                                      }



                                  }

                                  console.log("total score sum", totalScoreSum);


                                  var pureSeqGroup = [];
                                  var impureSeqGroup = [];
                                  var matchingCardsGroup = [];
                                  var victimsGroup = [];


                                  for(var i = 1; i < 5; i++){

                                     if( eval('meldCardEvaluator'+i).length !== 0){ 

                                          if( eval('meldCardEvaluator'+i)[0].isPure === true && eval('meldCardEvaluator'+i)[0].isSequence === true){
                                              pureSequence++;
                                              pureSeqGroup.push(i);
                                              console.log("pushing into pure seq ", i);
                                          }else if(eval('meldCardEvaluator'+i)[0].isPure === false && eval('meldCardEvaluator'+i)[0].isSequence === true){
                                              impureSequence++;
                                              impureSeqGroup.push(i);
                                              console.log("pushing into impure seq ", i);
                                          }else if(eval('meldCardEvaluator'+i)[0].isSameValue === true){
                                              matchingCards++;
                                              matchingCardsGroup.push(i);
                                             console.log("pushing into matching cards ", i);
                                          }else if( eval('meldCardEvaluator'+i)[0].isPure === false && eval('meldCardEvaluator'+i)[0].isSequence === false && eval('meldCardEvaluator'+i)[0].isSameValue === false){
                                          
                                              victimsGroup.push(i);   
                                              console.log("pushing into victims ", i);
                                          }else if( eval('meldCardEvaluator'+i)[0].isPure === false && eval('meldCardEvaluator'+i)[0].isSequence === true && eval('meldCardEvaluator'+i)[0].isSameValue === true ){
                                              impureSequence++;
                                              impureSeqGroup.push(i);
                                              console.log("pushing into impure seq ", i);
                                          }else if( eval('meldCardEvaluator'+i)[0].isPure === true && eval('meldCardEvaluator'+i)[0].isSequence === true && eval('meldCardEvaluator'+i)[0].isSameValue === true ){
                                              pureSequence++;
                                              pureSequence.push(i);
                                              console.log("pushing into pure seq ", i);
                                          }  
                                  }

                                }






                                setTimeout(function(){

                                  console.log('pure seq', pureSequence);
                                  console.log('impure seq', impureSequence);
                                  console.log('matchingCards', matchingCards);


                                  console.log("pure seq group", pureSeqGroup);
                                  console.log("impure seq group", impureSeqGroup);
                                  console.log("matching seq group", matchingCardsGroup);
                                  console.log("other victim group", victimsGroup);



                                   
                                  console.log('totalScoreSum ', totalScoreSum);

                                  if(pureSequence == 0){
                                      //console.log("80 points");
                                      totalScoreSum = totalScoreSum;


                                  }else{

                                      if(pureSequence == 1 && pureSequence+impureSequence == 1 ){

                                          for(var i = 0; i < pureSeqGroup.length; i++){

                                              for(var j = 0; j <  eval('meldCardArr'+pureSeqGroup[i]).length; j++){

                                                  if(eval('meldCardArr' + pureSeqGroup[i])[j].value == 1 || eval('meldCardArr' + pureSeqGroup[i])[j].value == 11 || eval('meldCardArr' + pureSeqGroup[i])[j].value == 12 || eval('meldCardArr' + pureSeqGroup[i])[j].value == 13){

                                                           if(eval('meldCardArr' + pureSeqGroup[i])[j].value == jokerValue){
                                                              totalScoreSum = totalScoreSum - 0;
                                                          }else{
                                                               totalScoreSum = totalScoreSum - 10;
                                                          }

                                                      

                                                  }else{

                                                      if(eval('meldCardArr' + pureSeqGroup[i])[j].value == jokerValue){
                                                              totalScoreSum = totalScoreSum - 0;
                                                          }else{
                                                               totalScoreSum = totalScoreSum - eval('meldCardArr'+pureSeqGroup[i])[j].value;
                                                          }

                                                  }    


                                              }

                                              console.log("testing 1", eval('meldCardArr'+pureSeqGroup[i]));

                                          }

                                        
                                        
                                      }else if( pureSequence == 1 && pureSequence+impureSequence >= 2){

                                          for(var i = 0; i < pureSeqGroup.length; i++){

                                              console.log('meldCardArr'+pureSeqGroup[i]);

                                              for(var j = 0; j <  eval('meldCardArr'+pureSeqGroup[i]).length; j++){

                                                  if(eval('meldCardArr' + pureSeqGroup[i])[j].value == 1 || eval('meldCardArr' + pureSeqGroup[i])[j].value == 11 || eval('meldCardArr' + pureSeqGroup[i])[j].value == 12 || eval('meldCardArr' + pureSeqGroup[i])[j].value == 13){

                                                       if(eval('meldCardArr' + pureSeqGroup[i])[j].value == jokerValue){
                                                              totalScoreSum = totalScoreSum - 0;
                                                          }else{
                                                               totalScoreSum = totalScoreSum - 10;
                                                          }

                                                     

                                                  }else{

                                                      if(eval('meldCardArr' + pureSeqGroup[i])[j].value == jokerValue){
                                                              totalScoreSum = totalScoreSum - 0;
                                                      }else{
                                                               totalScoreSum = totalScoreSum - eval('meldCardArr'+pureSeqGroup[i])[j].value;
                                                      }

                                                  }    


                                              }

                                              console.log("testing 2", eval('meldCardArr'+pureSeqGroup[i]));

                                          }

                                          

                                          if(impureSeqGroup.length > 0){

                                              for(var i = 0; i < impureSeqGroup.length; i++){

                                                   console.log('meldCardArr'+impureSeqGroup[i]);

                                                  for(var j = 0; j <  eval('meldCardArr'+impureSeqGroup[i]).length; j++){

                                                      if(eval('meldCardArr' + impureSeqGroup[i])[j].value == 1 || eval('meldCardArr' + impureSeqGroup[i])[j].value == 11 || eval('meldCardArr' + impureSeqGroup[i])[j].value == 12 || eval('meldCardArr' + impureSeqGroup[i])[j].value == 13 || eval('meldCardArr'+impureSeqGroup[i])[j].suit == "joker"){

                                                           if(eval('meldCardArr' + impureSeqGroup[i])[j].value == jokerValue){
                                                              totalScoreSum = totalScoreSum - 0;
                                                          }else if(eval('meldCardArr'+impureSeqGroup[i])[j].suit == "joker"){
                                                              totalScoreSum = totalScoreSum - 0;
                                                          }else{
                                                               totalScoreSum = totalScoreSum - 10;
                                                          }

                                                      }else{

                                                          if(eval('meldCardArr' + impureSeqGroup[i])[j].value == jokerValue){
                                                              totalScoreSum = totalScoreSum - 0;
                                                          }else{

                                                              totalScoreSum = totalScoreSum - eval('meldCardArr'+impureSeqGroup[i])[j].value;
                                                          }

                                                      }    


                                                  }

                                                  console.log("testing 3", eval('meldCardArr'+impureSeqGroup[i]));

                                              }


                                          }


                                 

                                          if(matchingCardsGroup.length > 0){

                                              for(var i = 0; i < matchingCardsGroup.length; i++){

                                                   console.log('meldCardArr'+matchingCardsGroup[i]);

                                                  for(var j = 0; j <  eval('meldCardArr'+matchingCardsGroup[i]).length; j++){

                                                      if(eval('meldCardArr' + matchingCardsGroup[i])[j].value == 1 || eval('meldCardArr' + matchingCardsGroup[i])[j].value == 11 || eval('meldCardArr' + matchingCardsGroup[i])[j].value == 12 || eval('meldCardArr' + matchingCardsGroup[i])[j].value == 13 || eval('meldCardArr'+matchingCardsGroup[i])[j].suit == "joker"){

                                                          if(eval('meldCardArr' + matchingCardsGroup[i])[j].value == jokerValue){
                                                              totalScoreSum = totalScoreSum - 0;
                                                          }else if(eval('meldCardArr'+matchingCardsGroup[i])[j].suit == "joker"){
                                                              totalScoreSum = totalScoreSum - 0;
                                                          }else{
                                                               totalScoreSum = totalScoreSum - 10;
                                                          }

                                                            

                                                      }else{

                                                          if(eval('meldCardArr' + matchingCardsGroup[i])[j].value == jokerValue){
                                                              totalScoreSum = totalScoreSum - 0;
                                                          }else{
                                                              totalScoreSum = totalScoreSum - eval('meldCardArr'+matchingCardsGroup[i])[j].value;
                                                          }

                                                          
                                                      }    


                                                  }

                                                  console.log("testing 4", eval('meldCardArr'+matchingCardsGroup[i]));


                                              }

                                             


                                          }


                                         


                                         
                                        


                                      }       


                          }

                            console.log("new total score sum", totalScoreSum);

                            /*  Check if the opponent got the chance to play */

                            // kriti

                             var ajxDataCheckDropType = {'action': 'check-drop-type', roomId: roomIdCookie, sessionKey: sessionKeyCookie, player: userId};

                            $.ajax({
                              type: 'POST',
                              data: ajxDataCheckDropType,
                              cache: false,
                              url: 'ajax/checkDropType.php',
                              success: function(count){

                                  //alert("drop type checked");
                                  console.log("count checking ", count);
                         
                                 if(count == 0){ 
                                      totalScoreSum = Math.round(totalScoreSum/2);    

                                 } 

                                 // alert("new total score sum is " + totalScoreSum);
                                 console.log("new total score sum is  ", totalScoreSum);


                                 /* Update score */      
                                  $('.result_sec').css({'display': 'block'});
                                  forceMelder(totalScoreSum, gameTypeCookie, roomIdCookie, sessionKeyCookie, chipsToTablePRCookie, currentBalanceCookie, minBuyingPRCookie, betValueCookie, 'lost');

                              } });   

                             



                      }, 3000);  
                                


                                
                      }, 3000);  



                  }

              } 




  
    });



  }









  /** Card Discard **/

  function cardDiscardPlayer(roomIdCookie, sessionKeyCookie, card){

        deckCount(roomIdCookie, sessionKeyCookie, function(count){

                
                if(count > 2){

                    var ajxDataCardDiscard = {'action': 'add-card-discard', roomId: roomIdCookie, playerId: userId, sessionKey: sessionKeyCookie, card:card};

                    $.ajax({
                        type: 'POST',
                        data: ajxDataCardDiscard,
                        cache: false,
                        url: 'ajax/addCardDiscard.php',
                        success: function(result){
                           console.log("Card discarded added ======================== ", result);

                        } });

                }else if(count <= 2){

                    /* send a signal that cards are getting re shuffled */

                    var signalReshuffle = {room:roomName, type: 'reshuffling', message: 'cards are getting reshuffled'};
                    //connection.send(JSON.stringify(signalReshuffle));
                    socket.emit(socketEventName, JSON.stringify(signalReshuffle));
                     $('.loading_container').show();   
                     $('.loading_container .popup .popup_cont').text("Please wait deck gets reshuffled..");



                    /* reshuffle cards */

                    reShuffleDeck(roomIdCookie, sessionKeyCookie, function(result){
                         console.log("DECK reshuffled result ======================== ", result);

                         setTimeout(function(){
                              $('.loading_container').hide();
                              $('.loading_container .popup .popup_cont').text("");

                               $('.current-player .playingCardsDiscard .hand').html('');



                           var signalReshuffleDone = {room:roomName, type: 'reshuffling-done', message: 'reshuffling finished'};
                            //connection.send(JSON.stringify(signalReshuffleDone));
                            socket.emit(socketEventName, JSON.stringify(signalReshuffleDone));
                         }, 5000);

                        


                    })



                  

                }        

        })

       

    }



 
 function discardCard(){

        var roomIdCookie = $.cookie("room");
        var sessionKeyCookie = $.trim($.cookie("sessionKey")); 
        var netSpeed = $.trim($.cookie("netSpeed"));
        var self = $(this);
        var nextPlayerToSend;

        $('.drop button').attr('disabled', true);
        $('.drop button').css({'cursor':'default'});

        if(cardsSelected[0] != "Joker"){

            var cardNumber1 = cardsSelected[0].substr(0, cardsSelected[0].indexOf('OF'));
            var cardHouse1 =  cardsSelected[0].substr(cardsSelected[0].indexOf("OF") + 2);

             $('.me .playingCardsDiscard .hand').append('<li><span class="card card_3 rank-'+cardNumber1+' '+cardHouse1+'">'+
                 '<span class="rank">'+cardNumber1+'</span>'+
                 '<span class="suit">&'+cardHouse1+';</span>'+
                        '</span><li>');

        }else{

             $('.me .playingCardsDiscard .hand').append('<li><span class="card joker card_3"></span></li>'); 
        }    

        $('.me .playingCardsDiscard .hand li:empty').remove();

        

          
         if(cardGroupSelected){

            /* remove from that group */

            $('.group_blog5 .playingCards .hand li a').removeClass('activeCard');

            if( removeCardFromGroups(cardsSelected[0], eval('group'+cardGroupSelected)) ){


                if(cardsSelected[0] != "Joker"){

                    var cardNumber = cardsSelected[0].substr(0, cardsSelected[0].indexOf('OF'));
                    var cardHouse =  cardsSelected[0].substr(cardsSelected[0].indexOf("OF") + 2);





                  $('.group_blog5[data-group='+cardGroupSelected+'] .playingCards .hand').find('li a[data-rank='+cardNumber+'][data-suit='+cardHouse+']').eq(0).remove();

                   $('.group_blog5[data-group='+cardGroupSelected+'] .playingCards .hand').find('li a[data-rank='+cardNumber+'][data-suit='+cardHouse+']').removeClass('activeCard');

                   /* check if that is the only card in the group */
                  
                  // if( $('.group_blog5[data-group='+cardGroupSelected+'] .playingCards .hand li:has(*)').length == 0){
                  //   $('.group_blog5[data-group='+cardGroupSelected+']').remove();
                  // }

                   setTimeout(function(){
                        if( $('.group_blog5[data-group='+cardGroupSelected+'] .playingCards .hand li:has(*)').length == 0){
                          $('.group_blog5[data-group='+cardGroupSelected+']').remove();
                       }

                     },2000); 




                  $('.card-throw .playingCards').append('<a href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+' id="cardDeckSelectShow'+userId+'" class="cardDeckSelect noSelect">'+
                 '<div class="card card_2 rank-'+cardNumber+' '+cardHouse+'">'+
                 '<span class="rank">'+cardNumber+'</span>'+
                 '<span class="suit">&'+cardHouse+';</span>'+
                        '</div></a>');

                cardDiscardPlayer(roomIdCookie, sessionKeyCookie, cardsSelected[0]);

           



                }else{

                     $('.group_blog5[data-group='+cardGroupSelected+'] .playingCards .hand').find('li a[data-rank="joker"]').eq(0).remove();

                      $('.group_blog5[data-group='+cardGroupSelected+'] .playingCards .hand').find('li[data-rank="joker"]').removeClass('activeCard');

       

                     setTimeout(function(){
                        if( $('.group_blog5[data-group='+cardGroupSelected+'] .playingCards .hand li:has(*)').length == 0){
                          $('.group_blog5[data-group='+cardGroupSelected+']').remove();
                       }

                     },2000); 
                     

                   
                      $('.card-throw .playingCards').append(' <a href="javascript:;" data-rank="joker" id="cardDeckSelectShow'+userId+'" class="cardDeckSelect noSelect"><div class="card joker card_2"></div></a>');


                      
                         cardDiscardPlayer(roomIdCookie, sessionKeyCookie, cardsSelected[0]);

                       
                        

                }    


                /* remove card from db */

                 var ajxData700 = {'action': 'remove-card-discard', roomId: roomIdCookie, playerId: userId, cardGroup: eval('group'+cardGroupSelected), groupNos: cardGroupSelected, sessionKey: sessionKeyCookie, netSpeed: netSpeed };

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

                                var signal10 = {room:roomName, type: 'card-discarded', message: 'discard done', player: userId, cardDiscarded: cardsSelected[0], nextPlayer: nextPlayerToSend};

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




         }else{

            /* Cards have not been divided into group yet */

             /* Remove the card from cardsInHand */

             $('.player_card_me .hand li a').removeClass('activeCard');

                if( removeCardFromGroups(cardsSelected[0], cardsInHand) ){

                    console.log(cardsInHand);


                    if(cardsSelected[0] != "Joker"){

                        var cardNumber = cardsSelected[0].substr(0, cardsSelected[0].indexOf('OF'));
                        var cardHouse =  cardsSelected[0].substr(cardsSelected[0].indexOf("OF") + 2);

               

                     $('.player_card_me .hand').find('li a[data-rank='+cardNumber+'][data-suit='+cardHouse+']').eq(0).parent().remove();

                   


                      $('.card-throw .playingCards').append('<a href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+' id="cardDeckSelectShow'+userId+'" class="cardDeckSelect noSelect">'+
                     '<div class="card card_2 rank-'+cardNumber+' '+cardHouse+'">'+
                     '<span class="rank">'+cardNumber+'</span>'+
                     '<span class="suit">&'+cardHouse+';</span>'+
                            '</div></a>');

                      cardDiscardPlayer(roomIdCookie, sessionKeyCookie, cardsSelected[0]);




                    }else{

                         $('.player_card_me .hand').find('li a[data-rank="joker"]').eq(0).parent().remove();

                        
                          $('.card-throw .playingCards').append(' <a href="javascript:;" data-rank="joker" id="cardDeckSelectShow'+userId+'" class="cardDeckSelect noSelect"><div class="card joker card_2"></div></a>');

                          cardDiscardPlayer(roomIdCookie, sessionKeyCookie, cardsSelected[0]);

                        
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


                                var PlayerCounterHandler = new playerCounterHandler(nextPlayerToSend);
                                intervalCounter = window.clearInterval(intervalCounter);
                                
                                PlayerCounterHandler.playerCounter = 30;
                                PlayerCounterHandler.run();
                                intervalCounter = setInterval(PlayerCounterHandler.updateCounter, 1000); 


                          

                               
                                $('.current-player[data-user="'+userId+'"] .card_submit_time').hide(); 
                                $('.current-player[data-user="'+userId+'"] .card_submit_time').text(""); 
                              
                            var signal10 = {room:roomName, type: 'card-discarded', message: 'discard done', player: userId, cardDiscarded: cardsSelected[0], nextPlayer: nextPlayerToSend};

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