
            
/** ===========  SELECT A CARD ==================== **/           


$('.group').delegate('.handCard', 'click', function(){

    var card;
    
    var rank = $(this).attr('data-rank');
    var suit = $(this).attr('data-suit');

   

    /* joker card */

    if(!suit){
        card = "Joker";
    }else{
         card = rank+'OF'+suit;
    }

    /* Find the group it belongs to */




    if( $(this).hasClass('activeCard') ){
        $(this).removeClass('activeCard');
        cardGroupSelected = '';

        var index = cardsSelected.indexOf(card);

        if (index > -1) {
                cardsSelected.splice(index, 1);
        }

    }else{
         $(this).addClass('activeCard');
          cardsSelected.push(card);
          

           
          cardGroupSelected = $(this).closest('.group_blog5').attr('data-group');

    }

    /* discard button disabled or enabled */

    if(cardsSelected.length == 1){

         if(cardPull == 1 && cardDiscard == 0){
            $('#meld'+userId).css({'display': 'block'});
          }

         //$('.group_btn').css({'display': 'none'});
         $('.group_cards').remove();

        /* Check if it's my turn. If my turn, then enable button, else not */
        var roomIdCookie = $.cookie("room");
        var sessionKeyCookie = $.trim($.cookie("sessionKey"));


        var ajxData505 = {'action': 'get-player-turn', roomId: roomIdCookie, sessionKey: sessionKeyCookie};


        $.ajax({
            type: 'POST',
             url: 'ajax/getCurrentPlayer.php',
            cache: false,
            data: ajxData505,
            success: function(player){
                console.log("AAAAAAAAAAAAAAAAAAAAAAAA " + player );
                 console.log("me: ", userId);
                if( parseInt(player) == parseInt(userId) ){
                    if(cardPull == 1){
                     $('.discard button').attr('disabled', false);
                      /*BATMAN*/

                     var discardTopBtn = '<a href="javascript:;" class="discard_top">Discard</a>';

                     $('.group').find('.activeCard').before(discardTopBtn);

                    } 
                }
            }


        });




       
    }else{

            $('#meld'+userId).css({'display': 'none'});
            $('.discard button').attr('disabled', true);
            $('.discard_top').remove();

         
          
              if(cardsSelected.length != 0){

                if(group1.length == 0 || group2.length == 0 || group3.length == 0 || group4.length == 0 || group5.length == 0 || group6.length == 0){

                    //$('.group_btn').css({'display': 'block'});
                     $('.group_cards').remove();
                    var groupTopBtn = '<button type="button" class="group_cards">Group</button>';

                    $('.group').find('.activeCard:last').before(groupTopBtn);
                }


                 
             }
    }

    console.log(cardsSelected);
   

});


            

  $('.hand').delegate('.handCard', 'click', function(){
    var rank = $(this).attr('data-rank');
    var suit = $(this).attr('data-suit');

    var card = rank+'OF'+suit;

     if(!suit){
        card = "Joker";
    }else{
         card = rank+'OF'+suit;
    }

    var self = $(this);


    if( $(this).hasClass('activeCard') ){
        $(this).removeClass('activeCard');

       

        var index = cardsSelected.indexOf(card);

        if (index > -1) {
                cardsSelected.splice(index, 1);
               
              
               
        }

    }else{


         $(this).addClass('activeCard');

         
            
         
         
          cardsSelected.push(card);

        
    }

    /* discard button disabled or enabled */

    if(cardsSelected.length == 1){

        if(cardPull == 1 && cardDiscard == 0){
            $('#meld'+userId).css({'display': 'block'});
        }

        /* Check if it's my turn. If my turn, then enable button, else not */
        //$('.group_btn').css({'display': 'none'});
        $('.group_cards').remove();
        var roomIdCookie = $.cookie("room");
        var sessionKeyCookie = $.trim($.cookie("sessionKey"));

        var ajxData505 = {'action': 'get-player-turn', roomId: roomIdCookie, sessionKey: sessionKeyCookie};

        $.ajax({
            type: 'POST',
             url: 'ajax/getCurrentPlayer.php',
            cache: false,
            data: ajxData505,
            success: function(player){
                console.log("AAAAAAAAAAAAAAAAAAAAAAAA " + player );
                 console.log("me: ", userId);
                if( parseInt(player) == parseInt(userId) ){
                    if(cardPull == 1){
                     $('.discard button').attr('disabled', false);

                     /*BATMAN*/

                     var discardTopBtn = '<a href="javascript:;" class="discard_top">Discard</a>';

                     $('.hand').find('.activeCard').before(discardTopBtn);


                    } 
                }
            }


        });

       


       
    }else{
            
            $('.discard_top').remove();        
            $('#meld'+userId).css({'display': 'none'});
           

            $('.discard button').attr('disabled', true);
        
        
         if(cardsSelected.length != 0){
             //$('.group_btn').css({'display': 'block'});
              $('.group_cards').remove();
             var groupTopBtn = '<button type="button" class="group_cards">Group</button>';
             $('.hand').find('.activeCard:last').before(groupTopBtn);
         }
    }

    console.log(cardsSelected);
   

});
