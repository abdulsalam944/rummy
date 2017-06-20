function updateGroups(){

        /*  Update db and set groups */

        var roomIdCookie = $.cookie("room");
        var sessionKeyCookie = $.trim($.cookie("sessionKey"));

            var ajxData506 = {'action': 'update-group', roomId: roomIdCookie,  playerId: userId, 
             group1: group1, group2: group2, group3: group3, group4: group4, group5: group5, group6:group6, sessionKey: sessionKeyCookie};

            $.ajax({
                type: 'POST',
                data: ajxData506,
                cache: false,
                url: 'ajax/updateCardGroup.php',
                success: function(result){
                    if( $.trim(result == "ok") ){
                        console.log("grouping done");
                        console.log(result);
                       
                    }
                    

                }

            })




}

function cardGetAndSorting1(group1, group2, group3, group4, group5, group6){

	     $('.group_blog5').remove();

	    var $groupParent = $('.group');

	     if(group1.length !== 0){

	        var $each_group = $('<div class="group_blog5" data-group="1"></div>');
	         var $meld_group_btn = $('<div class="meld_group_btn"><button type="button" class="meld_group" data-button="1">Meld</button></div>');
	        var $playingCards = $('<div class="playingCards"></div>');
	        var $hand = $('<ul class="hand sortable"></ul>');

	        $hand.append('<li></li>');

	       
	        for(var i = 0; i < group1.length; i++){

	            if(group1[i] != "Joker"){

	                 var cardNumber = group1[i].substr(0, group1[i].indexOf('OF'));
	                 var cardHouse =  group1[i].substr(group1[i].indexOf("OF") + 2);

	                  var li = '<li>'+
	                    '<a class="card card_2 rank-'+cardNumber+' '+cardHouse+' ui-widget-content handCard" href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+'>'+
	                    '<span class="rank">'+cardNumber+'</span>'+
	                    '<span class="suit">&'+cardHouse+';</span></a></li>';

	            }else{

	                 var li = '<li class="card joker card_2"><a href="javascript:;" class="handCard ui-widget-content card card_2 joker" data-rank="joker"></a></li>';
	            }    


	             $hand.append(li);
	        }

	        $hand.append('<li></li>');

	         $playingCards.append($hand);
	         $each_group.append($meld_group_btn);
	         $each_group.append($playingCards);
	         $groupParent.append($each_group);



	   }


	    if(group2.length !== 0){

	        var $each_group = $('<div class="group_blog5" data-group="2"></div>');
	         var $meld_group_btn = $('<div class="meld_group_btn"><button type="button" class="meld_group" data-button="2">Meld</button></div>');
	        var $playingCards = $('<div class="playingCards"></div>');
	        var $hand = $('<ul class="hand sortable"></ul>');

	        $hand.append('<li></li>');

	       
	        for(var i = 0; i < group2.length; i++){

	            if(group2[i] != "Joker"){

	                 var cardNumber = group2[i].substr(0, group2[i].indexOf('OF'));
	                 var cardHouse =  group2[i].substr(group2[i].indexOf("OF") + 2);

	                  var li = '<li>'+
	                    '<a class="card card_2 rank-'+cardNumber+' '+cardHouse+' ui-widget-content handCard" href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+'>'+
	                    '<span class="rank">'+cardNumber+'</span>'+
	                    '<span class="suit">&'+cardHouse+';</span></a></li>';

	            }else{

	                 var li = '<li class="card joker card_2"><a href="javascript:;" class="handCard ui-widget-content card card_2 joker" data-rank="joker"></a></li>';
	            }    


	             $hand.append(li);
	        }

	        $hand.append('<li></li>');

	         $playingCards.append($hand);
	         $each_group.append($meld_group_btn);
	         $each_group.append($playingCards);
	         $groupParent.append($each_group);



	   }

	    if(group3.length !== 0){

	        var $each_group = $('<div class="group_blog5" data-group="3"></div>');
	         var $meld_group_btn = $('<div class="meld_group_btn"><button type="button" class="meld_group" data-button="3">Meld</button></div>');
	        var $playingCards = $('<div class="playingCards"></div>');
	        var $hand = $('<ul class="hand sortable"></ul>');

	        $hand.append('<li></li>');

	       
	        for(var i = 0; i < group3.length; i++){

	            if(group3[i] != "Joker"){

	                 var cardNumber = group3[i].substr(0, group3[i].indexOf('OF'));
	                 var cardHouse =  group3[i].substr(group3[i].indexOf("OF") + 2);

	                  var li = '<li>'+
	                    '<a class="card card_2 rank-'+cardNumber+' '+cardHouse+' ui-widget-content handCard" href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+'>'+
	                    '<span class="rank">'+cardNumber+'</span>'+
	                    '<span class="suit">&'+cardHouse+';</span></a></li>';

	            }else{

	                 var li = '<li class="card joker card_2"><a href="javascript:;" class="handCard ui-widget-content card card_2 joker" data-rank="joker"></a></li>';
	            }    


	             $hand.append(li);
	        }

	       $hand.append('<li></li>');

	         $playingCards.append($hand);
	         $each_group.append($meld_group_btn);
	         $each_group.append($playingCards);
	         $groupParent.append($each_group);



	   }


	    if(group4.length !== 0){

	        var $each_group = $('<div class="group_blog5" data-group="4"></div>');
	        var $meld_group_btn = $('<div class="meld_group_btn"><button type="button" class="meld_group" data-button="4">Meld</button></div>');
	        var $playingCards = $('<div class="playingCards"></div>');
	        var $hand = $('<ul class="hand sortable"></ul>');

	       $hand.append('<li></li>');

	       
	        for(var i = 0; i < group4.length; i++){

	            if(group4[i] != "Joker"){

	                 var cardNumber = group4[i].substr(0, group4[i].indexOf('OF'));
	                 var cardHouse =  group4[i].substr(group4[i].indexOf("OF") + 2);

	                  var li = '<li>'+
	                    '<a class="card card_2 rank-'+cardNumber+' '+cardHouse+' ui-widget-content handCard" href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+'>'+
	                    '<span class="rank">'+cardNumber+'</span>'+
	                    '<span class="suit">&'+cardHouse+';</span></a></li>';

	            }else{

	                 var li = '<li class="card joker card_2"><a href="javascript:;" class="handCard ui-widget-content card card_2 joker" data-rank="joker"></a></li>';
	            }    


	             $hand.append(li);
	        }

	       $hand.append('<li></li>');

	         $playingCards.append($hand);
	         $each_group.append($meld_group_btn);
	         $each_group.append($playingCards);
	         $groupParent.append($each_group);



	   }


	    if(group5.length !== 0){

	        var $each_group = $('<div class="group_blog5" data-group="5"></div>');
	        var $meld_group_btn = $('<div class="meld_group_btn"><button type="button" class="meld_group" data-button="5">Meld</button></div>');
	        var $playingCards = $('<div class="playingCards"></div>');
	        var $hand = $('<ul class="hand sortable"></ul>');

	       $hand.append('<li></li>');

	       
	        for(var i = 0; i < group5.length; i++){

	            if(group5[i] != "Joker"){

	                 var cardNumber = group5[i].substr(0, group5[i].indexOf('OF'));
	                 var cardHouse =  group5[i].substr(group5[i].indexOf("OF") + 2);

	                  var li = '<li>'+
	                    '<a class="card card_2 rank-'+cardNumber+' '+cardHouse+' ui-widget-content handCard" href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+'>'+
	                    '<span class="rank">'+cardNumber+'</span>'+
	                    '<span class="suit">&'+cardHouse+';</span></a></li>';

	            }else{

	                 var li = '<li class="card joker card_2"><a href="javascript:;" class="handCard ui-widget-content card card_2 joker" data-rank="joker"></a></li>';
	            }    


	             $hand.append(li);
	        }

	        $hand.append('<li></li>');

	         $playingCards.append($hand);
	         $each_group.append($meld_group_btn);
	         $each_group.append($playingCards);
	         $groupParent.append($each_group);



	   }


	    if(group6.length !== 0){

	        var $each_group = $('<div class="group_blog5" data-group="6"></div>');
	        var $meld_group_btn = $('<div class="meld_group_btn"><button type="button" class="meld_group" data-button="6">Meld</button></div>');
	        var $playingCards = $('<div class="playingCards"></div>');
	        var $hand = $('<ul class="hand sortable"></ul>');

	       $hand.append('<li></li>');

	       
	        for(var i = 0; i < group6.length; i++){

	            if(group6[i] != "Joker"){

	                 var cardNumber = group6[i].substr(0, group6[i].indexOf('OF'));
	                 var cardHouse =  group6[i].substr(group6[i].indexOf("OF") + 2);

	                  var li = '<li>'+
	                    '<a class="card card_2 rank-'+cardNumber+' '+cardHouse+' ui-widget-content handCard" href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+'>'+
	                    '<span class="rank">'+cardNumber+'</span>'+
	                    '<span class="suit">&'+cardHouse+';</span></a></li>';

	            }else{

	                 var li = '<li class="card joker card_2"><a href="javascript:;" class="handCard ui-widget-content card card_2 joker" data-rank="joker"></a></li>';
	            }    


	             $hand.append(li);
	        }

	       $hand.append('<li></li>');

	         $playingCards.append($hand);
	         $each_group.append($meld_group_btn);
	         $each_group.append($playingCards);
	         $groupParent.append($each_group);



	   }


	   if(cardMelded == 1){
	     // $('.meld_group_btn').css({'display': 'block'});

	     for(var i = 1; i < 7; i++){
	        if( eval('group'+i).length <= 1){
	            $('.meld_group_btn button[data-button="'+i+'"]').hide();
	        }else{
	            $('.meld_group_btn button[data-button="'+i+'"]').show();
	        }
	     }


	   }





}



 function cardGetAndSorting(flag, sorted){

	     $('.group_blog5').remove();

	      var $groupParent = $('.group');



	        /** The new group **/


	       
	        var $each_group = $('<div class="group_blog5" data-group="'+flag+'"></div>');
	        var $meld_group_btn = $('<div class="meld_group_btn"><button type="button" class="meld_group" data-button="'+flag+'">Meld</button></div>');
	        var $playingCards = $('<div class="playingCards"></div>');
	        var $hand = $('<ul class="hand sortable"></ul>');

	        eval('group'+flag).sort(function(a, b){
	            return a.value - b.value;
	        });

	       $hand.append('<li></li>');

	       
	        for(var i = 0; i < eval('group'+flag).length; i++){

	            if(eval('group'+flag)[i].card != "Joker"){

	                 var cardNumber = eval('group'+flag)[i].card.substr(0, eval('group'+flag)[i].card.indexOf('OF'));
	                 var cardHouse =  eval('group'+flag)[i].card.substr(eval('group'+flag)[i].card.indexOf("OF") + 2);

	                  var li = '<li>'+
	                    '<a class="card card_2 rank-'+cardNumber+' '+cardHouse+' ui-widget-content handCard" href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+'>'+
	                    '<span class="rank">'+cardNumber+'</span>'+
	                    '<span class="suit">&'+cardHouse+';</span></a></li>';

	            }else{

	                 var li = '<li><a href="javascript:;" class="card joker card_2 handCard ui-widget-content" data-rank="joker"></a></li>';
	            }    


	             $hand.append(li);
	        }

	       $hand.append('<li></li>');

	         $playingCards.append($hand);
	         $each_group.append($meld_group_btn);
	         $each_group.append($playingCards);
	         $groupParent.append($each_group);

	       
	        if(sorted == true){ 
	            for(var i = 1; i < 7; i++){
	               if(i != flag){

	                    if( eval('group'+i).length > 0){


	                        var $each_group = $('<div class="group_blog5" data-group="'+i+'"></div>');
	                        var $meld_group_btn = $('<div class="meld_group_btn"><button type="button" class="meld_group" data-button="'+i+'">Meld</button></div>');
	                        var $playingCards = $('<div class="playingCards"></div>');
	                        var $hand = $('<ul class="hand sortable"></ul>');

	                       $hand.append('<li></li>');


	                        console.log("Group ", eval('group'+i));

	                       
	                        for(var j = 0; j < eval('group'+i).length; j++){

	                            if(eval('group'+i)[j] != "Joker"){

	                                 var cardNumber = eval('group'+i)[j].substr(0, eval('group'+i)[j].indexOf('OF'));
	                                 var cardHouse =  eval('group'+i)[j].substr(eval('group'+i)[j].indexOf("OF") + 2);

	                                  var li = '<li>'+
	                                    '<a class="card card_2 rank-'+cardNumber+' '+cardHouse+' ui-widget-content handCard" href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+'>'+
	                                    '<span class="rank">'+cardNumber+'</span>'+
	                                    '<span class="suit">&'+cardHouse+';</span></a></li>';

	                            }else{

	                                 var li = '<li><a href="javascript:;" class="card joker card_2 handCard ui-widget-content" data-rank="joker"></a></li>';
	                            }    


	                             $hand.append(li);
	                        }

	                       $hand.append('<li></li>');

	                         $playingCards.append($hand);
	                         $each_group.append($meld_group_btn);
	                         $each_group.append($playingCards);
	                         $groupParent.append($each_group);


	                    }


	               }  
	            } 
	        }else if(sorted == false){


	            var $each_group = $('<div class="group_blog5" data-group="2"></div>');
	            var $meld_group_btn = $('<div class="meld_group_btn"><button type="button" class="meld_group" data-button="2">Meld</button></div>');
	            var $playingCards = $('<div class="playingCards"></div>');
	            var $hand = $('<ul class="hand sortable"></ul>');

	           $hand.append('<li></li>');

	            group2.sort(function(a, b){
	                return a.value - b.value;
	            });

	           
	            for(var i = 0; i < group2.length; i++){

	                if(group2[i].card != "Joker"){

	                     var cardNumber = group2[i].card.substr(0, group2[i].card.indexOf('OF'));
	                     var cardHouse =  group2[i].card.substr(group2[i].card.indexOf("OF") + 2);

	                      var li = '<li>'+
	                        '<a class="card card_2 rank-'+cardNumber+' '+cardHouse+' ui-widget-content handCard" href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+'>'+
	                        '<span class="rank">'+cardNumber+'</span>'+
	                        '<span class="suit">&'+cardHouse+';</span></a></li>';

	                }else{

	                     var li = '<li><a href="javascript:;" class="card joker card_2 handCard ui-widget-content" data-rank="joker"></a></li>';
	                }    


	                 $hand.append(li);
	            }

	           $hand.append('<li></li>');

	             $playingCards.append($hand);
	             $each_group.append($meld_group_btn);
	             $each_group.append($playingCards);
	             $groupParent.append($each_group);


	        }





	     /* Removing object property index value from card groups for db update */

	         for(var i = 1; i < 7; i++){
	             for(var j = 0; j < eval('group'+i).length; j++){
	                 if(typeof eval('group'+i)[0] === "object"){
	                     delete eval('group'+i)[j]["value"];
	                }
	             }
	         }


	         if(typeof group1[0] === "object"){
	            group1 = group1.map(x => x.card);
	         }

	         if(typeof group2[0] === "object"){
	            group2 = group2.map(x => x.card);
	        }

	        if(typeof group3[0] === "object"){
	            group3 = group3.map(x => x.card);
	         }

	         if(typeof group4[0] === "object"){
	            group4 = group4.map(x => x.card);
	         }

	         if(typeof group5[0] === "object"){
	            group5 = group5.map(x => x.card);
	         }

	         if(typeof group6[0] === "object"){
	            group6 = group6.map(x => x.card);
	         }   





	       if(cardMelded == 1){
	         // $('.meld_group_btn').css({'display': 'block'});

	         for(var i = 1; i < 7; i++){
	            if( eval('group'+i).length == 1 || eval('group'+i).length == 0){
	                $('.meld_group_btn button[data-button="'+i+'"]').hide();
	            }else{
	                $('.meld_group_btn button[data-button="'+i+'"]').show();
	            }
	         }


	       }


	         console.log("group1 " + JSON.stringify(group1));
	         console.log("group2 " + JSON.stringify(group2));
	         console.log("group3 " + JSON.stringify(group3));
	         console.log("group4 " + JSON.stringify(group4));
	         console.log("group5 " + JSON.stringify(group5));
	         console.log("group6 " + JSON.stringify(group6));



	        updateGroups();


}



function cardSorting(){

    // console.log("group 1 AAAAAAAAAAAAA ", JSON.stringify(group1));

    var roomIdCookie = $.cookie("room");
    var sessionKeyCookie = $.trim($.cookie("sessionKey"));

    $('.me .playingCards .hand').html("");

    $('.group').css({'display' : 'block'});

    var $groupParent = $('.group');

       if(group1.length !== 0){

            var $each_group = $('<div class="group_blog5" data-group="1"></div>');
            var $meld_group_btn = $('<div class="meld_group_btn"><button type="button" class="meld_group" data-button="1">Meld</button></div>');
            var $playingCards = $('<div class="playingCards"></div>');
            var $hand = $('<ul class="hand sortable"></ul>');

            group1.sort(function(a, b){
                return a.value - b.value;
            });

           $hand.append('<li></li>');

           
            for(var i = 0; i < group1.length; i++){
                 var cardNumber = group1[i].card.substr(0, group1[i].card.indexOf('OF'));
                 var cardHouse =  group1[i].card.substr(group1[i].card.indexOf("OF") + 2);

                  var li = '<li>'+
                    '<a class="card card_2 rank-'+cardNumber+' '+cardHouse+' ui-widget-content handCard" href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+'>'+
                    '<span class="rank">'+cardNumber+'</span>'+
                    '<span class="suit">&'+cardHouse+';</span></a></li>';


                 $hand.append(li);
            }

             $hand.append('<li></li>');   
             $playingCards.append($hand);
             $each_group.append($meld_group_btn);
             $each_group.append($playingCards);
             $groupParent.append($each_group);



        }

        if(group2.length !== 0){

            var $each_group = $('<div class="group_blog5" data-group="2"></div>');
            var $meld_group_btn = $('<div class="meld_group_btn"><button type="button" class="meld_group" data-button="2">Meld</button></div>');
            var $playingCards = $('<div class="playingCards"></div>');
            var $hand = $('<ul class="hand sortable"></ul>');

            group2.sort(function(a, b){
                return a.value - b.value;
            });

           $hand.append('<li></li>');

            for(var i = 0; i < group2.length; i++){
                 var cardNumber = group2[i].card.substr(0, group2[i].card.indexOf('OF'));
                 var cardHouse =  group2[i].card.substr(group2[i].card.indexOf("OF") + 2);

                  

                    var li = '<li>'+
                    '<a class="card card_2 rank-'+cardNumber+' '+cardHouse+' ui-widget-content handCard ui-widget-content" href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+'>'+
                    '<span class="rank">'+cardNumber+'</span>'+
                    '<span class="suit">&'+cardHouse+';</span></a></li>';


                 $hand.append(li);

            }


            $hand.append('<li></li>');
             $playingCards.append($hand);
             $each_group.append($meld_group_btn);
             $each_group.append($playingCards);

             $groupParent.append($each_group);


        }

        if(group3.length !== 0){

            var $each_group = $('<div class="group_blog5" data-group="3"></div>');
            var $meld_group_btn = $('<div class="meld_group_btn"><button type="button" class="meld_group" data-button="3">Meld</button></div>');
            var $playingCards = $('<div class="playingCards"></div>');
            var $hand = $('<ul class="hand sortable"></ul>');

            group3.sort(function(a, b){
                return a.value - b.value;
            });

           $hand.append('<li></li>');

            for(var i = 0; i < group3.length; i++){
                var cardNumber = group3[i].card.substr(0, group3[i].card.indexOf('OF'));
                var cardHouse =  group3[i].card.substr(group3[i].card.indexOf("OF") + 2);

                

                    var li = '<li>'+
                    '<a class="card card_2 rank-'+cardNumber+' '+cardHouse+' ui-widget-content handCard" href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+'>'+
                    '<span class="rank">'+cardNumber+'</span>'+
                    '<span class="suit">&'+cardHouse+';</span></a></li>';


                 $hand.append(li);

            }

            $hand.append('<li></li>');
             $playingCards.append($hand);
             $each_group.append($meld_group_btn);
             $each_group.append($playingCards);

             $groupParent.append($each_group);


        }

        if(group4.length !== 0){

            var $each_group = $('<div class="group_blog5" data-group="4"></div>');
             var $meld_group_btn = $('<div class="meld_group_btn"><button type="button" class="meld_group" data-button="4">Meld</button></div>');
            var $playingCards = $('<div class="playingCards"></div>');
            var $hand = $('<ul class="hand sortable"></ul>');

            group4.sort(function(a, b){
                return a.value - b.value;
            });

           $hand.append('<li></li>');

            for(var i = 0; i < group4.length; i++){
                var cardNumber = group4[i].card.substr(0, group4[i].card.indexOf('OF'));
                var cardHouse =  group4[i].card.substr(group4[i].card.indexOf("OF") + 2);

               
                var li = '<li>'+
                    '<a class="card card_2 rank-'+cardNumber+' '+cardHouse+' ui-widget-content handCard" href="javascript:;" data-rank='+cardNumber+' data-suit='+cardHouse+'>'+
                    '<span class="rank">'+cardNumber+'</span>'+
                    '<span class="suit">&'+cardHouse+';</span></a></li>';

                $hand.append(li);

            }

            $hand.append('<li></li>');   
             $playingCards.append($hand);
             $each_group.append($meld_group_btn);
             $each_group.append($playingCards);
             $groupParent.append($each_group);


        }

        if(group5.length !== 0){

            var $each_group = $('<div class="group_blog5" data-group="5"></div>');
            var $meld_group_btn = $('<div class="meld_group_btn"><button type="button" class="meld_group" data-button="5">Meld</button></div>');
            var $playingCards = $('<div class="playingCards"></div>');
            var $hand = $('<ul class="hand sortable"></ul>');

           
           $hand.append('<li></li>');

            for(var i = 0; i < group5.length; i++){
                
                
                  var li = '<li><a href="javascript:;" class="handCard ui-widget-content card joker card_2" data-rank="joker"></a></li>';

                 $hand.append(li);

            }

            $hand.append('<li></li>');
             $playingCards.append($hand);
             $each_group.append($meld_group_btn);
             $each_group.append($playingCards);

             $groupParent.append($each_group);


        }


        /* Removing object property index value from card groups for db update */

        for(var i = 1; i < 5; i++){
            for(var j = 0; j < eval('group'+i).length; j++){
                delete eval('group'+i)[j]["value"];
            }


            
        }

      
        
       group1 = group1.map(x => x.card);
       group2 = group2.map(x => x.card);
       group3 = group3.map(x => x.card);
       group4 = group4.map(x => x.card);

        console.log("group 1 prev", JSON.stringify(group1));
        console.log("group 2 prev", JSON.stringify(group2));
        console.log("group 3 prev", JSON.stringify(group3));
        console.log("group 4 prev", JSON.stringify(group4));
        console.log("group 5 prev", JSON.stringify(group5));


       
         /*  Update db and set groups */

        var ajxData505 = {'action': 'update-group', roomId: roomIdCookie, playerId: userId, 
         group1: group1, group2: group2, group3: group3, group4: group4, group5: group5, sessionKey: sessionKeyCookie};

        $.ajax({
            type: 'POST',
            data: ajxData505,
            cache: false,
            url: 'ajax/updateCardGroup.php',
            success: function(result){
                if( $.trim(result == "ok") ){
                    console.log("grouping done");
                    console.log(result);
                    
                    
                }
                

            }

        })


          



}



 function groupCardsFunc(){
    /* Check to see if cards have already been sorted */

   $('.meldAll').hide();

    if( !$.trim( $('.me .playingCards .hand').html() ).length == true ){
        cardsInHand.length = 0;
     
     /* Already sorted */

     console.log("11111111111111111 selected");

  
     changeCardGroups(function(){
        console.log('grouping done!');

            for(var i = 0; i < cardsSelected.length; i++){
                

            if(cardsSelected[i] != "Joker"){
                var cardNumber = cardsSelected[i].substr(0, cardsSelected[i].indexOf('OF'));
                var cardHouse =  cardsSelected[i].substr(cardsSelected[i].indexOf("OF") + 2);

                if($.trim(cardNumber) == "J"){
                  cardNumber = 11;
                }else if($.trim(cardNumber) == "Q"){
                  cardNumber = 12;
                }else if($.trim(cardNumber) == "K"){
                  cardNumber = 13;
                }else if($.trim(cardNumber) == "A"){
                  cardNumber = 1;
                }else{
                  cardNumber = parseInt(cardNumber);
                }

            }else{
                cardNumber = 20;
            } 


            //         /* remove the card from current group */

                  
                  
                    if(removeCardFromGroups(cardsSelected[i], group1)){
                        console.log(cardsSelected[i] + " card removed group 1");
                    }else if(removeCardFromGroups(cardsSelected[i], group2)){
                        console.log(cardsSelected[i] + " card removed group 2 ");
                    }else if(removeCardFromGroups(cardsSelected[i], group3)){
                        console.log(cardsSelected[i] + " card removed group 3");
                    }else if(removeCardFromGroups(cardsSelected[i], group4)){
                        console.log(cardsSelected[i] + " card removed group 4");
                    }else if(removeCardFromGroups(cardsSelected[i], group5)){
                        console.log(cardsSelected[i] + " card removed group 5");
                    }else if(removeCardFromGroups(cardsSelected[i], group6)){
                        console.log(cardsSelected[i] + " card removed group 6");
                    }

                 
            //        /* add card into array */

                   group1.push({card: cardsSelected[i], value: cardNumber});
                   console.log("Group 1 card inserted " + cardsSelected[i]);


             }




          

     


           console.log("group1 " + JSON.stringify(group1));
           console.log("group2 " + JSON.stringify(group2));
           console.log("group3 " + JSON.stringify(group3));
           console.log("group4 " + JSON.stringify(group4));
           console.log("group5 " + JSON.stringify(group5));
           console.log("group6 " + JSON.stringify(group6));



             cardGetAndSorting(1, true);


                if(cardMelded == 1) {
                      
                  /* add meld button when a group gets added  */

                  if(group1.length > 1){
                    $('.meld_group_btn button[data-button="1"]').show();
                  }

                  if(group2.length > 1){
                    $('.meld_group_btn button[data-button="2"]').show();
                  }

                  if(group3.length > 1){
                    $('.meld_group_btn button[data-button="3"]').show();
                  }

                  if(group4.length > 1){
                    $('.meld_group_btn button[data-button="4"]').show();
                  }

                  if(group5.length > 1){
                    $('.meld_group_btn button[data-button="5"]').show();
                  }

                  if(group6.length > 1){
                    $('.meld_group_btn button[data-button="6"]').show();
                  }


                             
                  // $('.meld_group_btn button[data-button="1"]').show();

         
              } 

           
           // $('.group_btn').css({'display': 'none'});
           $('.group_cards').remove();


           cardsSelected.length = 0;

       });


    }else{


        console.log("2222222222222 selected");
       
        /*  Divide the groups 1 by 1 */


        for(var i = 0; i < cardsSelected.length; i++){

            console.log(cardsSelected);

            /*  segregate cardvalues and suits */

            if(cardsSelected[i] != "Joker"){
                var cardNumber = cardsSelected[i].substr(0, cardsSelected[i].indexOf('OF'));
                var cardHouse =  cardsSelected[i].substr(cardsSelected[i].indexOf("OF") + 2);

                if($.trim(cardNumber) == "J"){
                  cardNumber = 11;
                }else if($.trim(cardNumber) == "Q"){
                  cardNumber = 12;
                }else if($.trim(cardNumber) == "K"){
                  cardNumber = 13;
                }else if($.trim(cardNumber) == "A"){
                  cardNumber = 1;
                }else{
                  cardNumber = parseInt(cardNumber);
                }

            }else{
                cardNumber = 20;
            } 
            

            // group1.push(cardsSelected[i]);
            group1.push({card: cardsSelected[i], value: cardNumber});
               

            if(removeCardFromGroups(cardsSelected[i], cardsInHand)){
                    console.log("card removed");
            }    
               
                
        
        }

        /*  Swap cards in hand with group 2 */
        for(var i = 0; i < cardsInHand.length; i++){

         if(cardsInHand[i] != "Joker"){
            var cardNumber = cardsInHand[i].substr(0, cardsInHand[i].indexOf('OF'));
            var cardHouse =  cardsInHand[i].substr(cardsInHand[i].indexOf("OF") + 2);

            if($.trim(cardNumber) == "J"){
              cardNumber = 11;
            }else if($.trim(cardNumber) == "Q"){
              cardNumber = 12;
            }else if($.trim(cardNumber) == "K"){
              cardNumber = 13;
            }else if($.trim(cardNumber) == "A"){
              cardNumber = 1;
            }else{
              cardNumber = parseInt(cardNumber);
            }

        }else{
            cardNumber = 20;
        } 


            // group2.push(cardsInHand[i]);
            group2.push({card: cardsInHand[i], value: cardNumber});

        }


        cardsInHand.length = 0;    


        console.log("cards in hand " + cardsInHand);
       
        $('.me .playingCards .hand').html("");

        // console.log("group1 : " + JSON.stringify(group1));
        // console.log("group2 " + JSON.stringify(group2));




        // updateGroups();
        cardGetAndSorting(1, false);

         // $('.group_btn').css({'display': 'none'});
         $('.group_cards').remove();

         cardsSelected.length = 0;

         console.log("group1 " + JSON.stringify(group1));
         console.log("group2 " + JSON.stringify(group2));
         console.log("group3 " + JSON.stringify(group3));
         console.log("group4 " + JSON.stringify(group4));
         console.log("group5 " + JSON.stringify(group5));
         console.log("group6 " + JSON.stringify(group6));


         

    }
   
}


 /* group button click */

function changeCardGroups(callback){

      // alert('entered the function!');


      for(var i = 5; i >= 1; i--){

         if(eval('group'+i).length > 0){

           for(var j = 0; j < eval('group'+i).length; j++){

                var groupToBeShiftedTo = i+1;
                eval('group'+groupToBeShiftedTo).push(eval('group'+i)[j]);


            }

            eval('group'+i).length = 0;


        }


      }  


      callback();

    


}


function updateGroupsWhileMelding(){

    /*  Update db and set groups */

    var roomIdCookie = $.cookie("room");
    var sessionKeyCookie = $.trim($.cookie("sessionKey"));

        var ajxData506 = {'action': 'update-group', roomId: roomIdCookie, playerId: userId, 
         group1: group1, group2: group2, group3: group3, group4: group4, group5: group5, group6:group6, sessionKey: sessionKeyCookie};

        $.ajax({
            type: 'POST',
            data: ajxData506,
            cache: false,
            url: 'ajax/updateCardGroup.php',
            success: function(result){
                if( $.trim(result == "ok") ){
                    console.log("grouping done");
                    console.log(result);
                    $('.group_blog5').remove();


                   cardGetAndSorting1(group1, group2, group3, group4, group5, group6);
                
                }
                

            }

        })




}



/** SORT BUTTON CLICK **/
$('.sort button').click(function(){

	     if( $(this).is('[disabled=disabled]') ){
	        return false;
	      }else{

	      $('.sort button').attr('disabled', true);   

	     var roomIdCookie = $.cookie("room");
	     var sessionKeyCookie = $.trim($.cookie("sessionKey"));

	    /* get my cards */

	     var ajxData225 = {'action': 'get-cards', roomId: roomIdCookie, playerId: userId, sessionKey: sessionKeyCookie};

	     $.ajax({

	        type: 'POST',
	        url: 'ajax/getMyCards.php',
	        cache: false,
	        data: ajxData225,
	        dataType: 'json',
	        success: function(myCards){

	            console.log("card length: " + myCards.length); 

	            for(var i = 0; i < myCards.length; i++){
	            
	                

	                if(myCards[i] != "Joker"){
	                    var cardNumber = myCards[i].substr(0, myCards[i].indexOf('OF'));
	                    var cardHouse =  myCards[i].substr(myCards[i].indexOf("OF") + 2);

	                    if($.trim(cardNumber) == "J"){
	                      cardNumber = 11;
	                    }else if($.trim(cardNumber) == "Q"){
	                      cardNumber = 12;
	                    }else if($.trim(cardNumber) == "K"){
	                      cardNumber = 13;
	                    }else if($.trim(cardNumber) == "A"){
	                      cardNumber = 1;
	                    }else{
	                      cardNumber = parseInt(cardNumber);
	                    }

	                }else{
	                    cardNumber = 20;
	                }

	                 if(cardNumber == 20){
	                   group5.push("Joker");
	                }else{

	                    if(cardHouse == "spades"){
	                   
	                        group1.push({card: myCards[i], value: cardNumber});
	                    }else if(cardHouse == "clubs"){
	                   
	                        group2.push({card: myCards[i], value: cardNumber});
	                    }else if(cardHouse == "diams"){
	                  
	                        group3.push({card: myCards[i], value: cardNumber});
	                    }else if(cardHouse == "hearts"){
	                    
	                        group4.push({card: myCards[i], value: cardNumber});
	                    }


	                }

	            }

	             cardSorting();

	                console.log("group 1 ", group1);
	                console.log("group 2 ", group2);
	                console.log("group 3 ", group3);
	                console.log("group 4 ", group4);
	                console.log("group 5 ", group5);


	             cardsInHand.length = 0;   
	           

	            
	        }
	        
	    });        

	   }  

});



  /* === Remove melded card group  */

$('.show_your_card_sec').delegate('.removeMeldedGroup', 'click', function(){

    var meldedGroupNos = $(this).attr('id');
    var self = $(this);
    var roomIdCookie = $.cookie("room");
    var sessionKeyCookie = $.trim($.cookie("sessionKey")); 


    var meldedGroup = eval('meldedGroup'+meldedGroupNos);

    
     var flag = 0;

        if(group1.length == 0){
            flag = 1;
        }else if(group2.length == 0){
            flag = 2;
        }else if(group3.length == 0){
            flag = 3;
        }else if(group4.length == 0){
            flag = 4;
        }else if(group5.length == 0){
            flag = 5;
        }else if(group6.length == 0){
            flag = 6;
        }


     var groupToBeAdded = eval('group'+flag);      


     /*  Add cards into the group */

     for(var i = 0; i < meldedGroup.length; i++){

        groupToBeAdded.push(meldedGroup[i]);
     }


     console.log("Group to be added " + flag);


    /* ajax remove melded group from db */


     /* Update melded groups in db */


        var ajxData801 = { 'action': 'remove-meld-card', roomId: roomIdCookie, player: userId, meldedGroupNos: meldedGroupNos, sessionKey: sessionKeyCookie};


            $.ajax({
                type: 'POST',
                data: ajxData801,
                cache: false,
                url: 'ajax/removeMeldedCard.php',
                success: function(result){
                    if( $.trim(result == "ok") ){

                        console.log("DB Updated");
                        meldedGroup.length = 0;

                        /* View */

                        /*  remove from melded panel */


                        $('.show_your_card_blog[data-group="'+meldedGroupNos+'"] .playingCards .hand').html("");

                        self.remove();
                        updateGroupsWhileMelding();
                       

                    }
                }    
            });        
    });



 



