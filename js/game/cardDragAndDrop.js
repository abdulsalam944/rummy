   $('.group').delegate('.hand','mouseover', function(){

        var roomIdCookie = $.cookie("room");
        var sessionKeyCookie = $.trim($.cookie("sessionKey"));
      
        $(this).sortable({
            connectWith: '.group .hand',
            start: function(event, ui){
                console.log("starting....");
                  
                  parentgroupRemoving = $(ui.item).closest('.group_blog5').attr('data-group');

                 

            },
            stop: function(event, ui){

                /* get rank and house */

                var card;

                var rank = $(ui.item).find('a').attr('data-rank');
                var suit = $(ui.item).find('a').attr('data-suit');

                card = rank+'OF'+suit;

                /* joker card */

                if(!suit){
                    card = "Joker";
                }

            
                /* Remove the card from the group it exists */

                    /* search the group */

                    var parentGroup = $(ui.item).closest('.group_blog5').attr('data-group');

                    /* If group changed, then remove it from previous group */

                    if(parentGroup != parentgroupRemoving){

                        var groupRemoval = "group"+parentgroupRemoving;
                        var groupAdded = "group"+parentGroup;


                        if( removeCardFromGroups(card, eval(groupRemoval)) ){
                          

                            
                            // console.log('old ' + eval(groupRemoval));
                            
                            eval(groupAdded).push(card);


                            /* update the database */

                             var ajxData555 = {'action': 'update-group-drag', roomId: roomIdCookie, playerId: userId, groupRemoval: eval(groupRemoval), groupAdded: eval(groupAdded), groupRemNos: parentgroupRemoving, groupAddNos: parentGroup, sessionKey: sessionKeyCookie };

                                $.ajax({
                                    type: 'POST',
                                    data: ajxData555,
                                    cache: false,
                                    url: 'ajax/updateCardGroupDragAndDrop.php',
                                    success: function(result){
                                        if( $.trim(result == "ok") ){
                                           console.log("Swapping Success!!!");
                                        }
                                        

                                    }

                                });


                            
                                // if( $('.group_blog5[data-group='+parentgroupRemoving+'] .playingCards .hand li:has(*)').length == 0){
                                //     $('.group_blog5[data-group='+parentgroupRemoving+']').remove();
                                // }

                                setTimeout(function(){
                                    if( $('.group_blog5[data-group='+parentgroupRemoving+'] .playingCards .hand li:has(*)').length == 0){
                                      $('.group_blog5[data-group='+parentgroupRemoving+']').remove();
                                   }

                               },2000);



                               

                         /* ====== When Melded ======= */   

                    if(cardMelded == 1){


                        /* Hide meld button when a group gets removed or has 1 card */


                        if(  eval(groupRemoval).length <= 1){
                            $('.meld_group_btn button[data-button="'+parentgroupRemoving+'"]').hide();

                        }

                        /* Add meld button when group gets added and has atleast 2 cards */



                        if(  eval(groupAdded).length >= 2){
                            $('.meld_group_btn button[data-button="'+parentGroup+'"]').show();

                            /*Lawrance*/

                        }

                    }


                           
                    }
                    

                }

            }
        })
    });

    $('.player_card_me').delegate('.hand', 'mouseover', function(){

        $(this).sortable({
            connectWith: '.player_card_me .hand',
            // axis: "x",
            start: function(event, ui){
               console.log("starting...");
            },
            stop: function(event, ui){
                $(ui.item).removeAttr('style');
                // $(ui.item).css({'left': ''});
                console.log( $(ui.item) );
            }
          

        })

        // $(this).removeAttr('style');

    })