$(document).ready(function(){
        $('.gametype').click(function(){
            var dataValue = $(this).attr('data-value');
            var gametype = $(".current").attr('data-value');
            var player_2 = '';
            var player_6 = '';
            var player_9 = '';

            var player_deals2 = '';
            var player_deals3 = '';

            
            $('input:checkbox').removeAttr('checked');

            $.ajax({
                type: "POST",
                url: 'playersubmit.php',
                data: {'gametype': gametype, 'player_2': player_2, 'player_6': player_6, 'player_9': player_9,'player_deals2':player_deals2,'player_deals3':player_deals3},
                        success: function(data){
                        //alert(data);
                        if(gametype == '101')
                        {
                            $('#content1 .table-responsive #playtype_101').html(data);
                        }
                        if(gametype == '201')
                        {
                            $('#content2 .table-responsive #playtype_201').html(data);
                        }
                        if(gametype == 'x')
                        {
                            $('#content3 .table-responsive #playtype_x').html(data);
                        }
                        if(gametype == 'points')
                        {
                            $('#content4 .table-responsive #playtype_points').html(data);
                        }
                        
                        

                        

                    },
            });


        });
      

        
    $("input[type=checkbox]").click(function(){


        var gametype = $(".current").attr('data-value');
        var player_2 = $('#player_2:checked').val();
        var player_6 = $('#player_6:checked').val();
        var player_9 = $('#player_9:checked').val();

        var player_deals2 = $('#player_deals2:checked').val();
        var player_deals3 = $('#player_deals3:checked').val();

     
            $.ajax({
            type: "POST",
            url: 'playersubmit.php',
            data: {'gametype': gametype, 'player_2': player_2, 'player_6': player_6, 'player_9': player_9, 'player_deals2': player_deals2, 'player_deals3': player_deals3 },
                    success: function(data){
                   
                    if(gametype == '101')
                    {
                        $('#content1 .table-responsive #playtype_101').html(data);
                    }
                    if(gametype == '201')
                    {
                        $('#content2 .table-responsive #playtype_201').html(data);
                    }
                    if(gametype == 'x')
                    {
                        $('#content3 .table-responsive #playtype_x').html(data);
                    }
                    if(gametype == 'score')
                    {
                        $('#content4 .table-responsive #playtype_points').html(data);
                    }
                    
                    

                    

                },
        });


    });


               
    $("#tabsholder1").tytabs({
        tabinit:"1",
        fadespeed:"fast"
    });

    $('[data-toggle="tooltip"]').tooltip();


     getTotalPlayerPerGame();

      setInterval(function(){
            getTotalPlayerPerGame();
      },3000);



      setInterval(function(){
        var ajxData = 'ajax=TRUE';
                $.ajax({
                 type: 'POST',
                 url: 'ajax/ajaxtotalplayer.php',
                 cache: false,
                 data: ajxData,
                 dataType: 'html',
                 success: function(result){
                 $('#total_player').html(result);
               } 
           });

      },30000);


              // sudip
    $( "#modal_login" ).on('click', function() {
        var username = $('#username').val();
        var password = $('#password').val();

        // var ajxData = {'ajax': 'TRUE', username: username, password: password};
        var ajxData = 'ajax=TRUE&username='+username+'&password='+password;
             $.ajax({
              type: 'POST',
              url: 'ajax/ajaxlogin.php',
              cache: false,
              data: ajxData,
              dataType: 'JSON',
              success: function(result){
                if(result.SUCCESS == 'YES'){
                    // alert('Successfuly Login..');
                    $('#myModal1').css('display','none');
                    location.reload();
                }
                if(result.SUCCESS == 'NO'){
                    alert('Authentication failed..'); 
                }
                // console.log(result);

         } }); 
    });



    // ajax logout
    $("#logout" ).on('click',function() {
        var ajxData = 'ajax=TRUE';
             $.ajax({
              type: 'POST',
              url: 'ajax/ajaxlogout.php',
              cache: false,
              data: ajxData,
              dataType: 'JSON',
              success: function(result){
                if(result.SUCCESS == 'YES'){
                    // alert('Successfuly Logout..');
                    location.reload();
                }
            } 
        }); 
    });


     $('.launch-modal, .launch-modal-2').click(function(){
        $('#myModal').modal({
            backdrop: 'static'
        });
    }); 



});



    function getTotalPlayerPerGame(){     
        
        var ajxData = {'action': 'fetch_count'};

            $.ajax({
                 type: 'POST',
                 url: 'ajax/ajaxtableplayer.php',
                 cache: false,
                 data: ajxData,
                 dataType: 'JSON',
                 success: function(result){
                
              

                 for(var i = 0; i < result.length; i++){
                    $('.table-striped #player'+parseInt(result[i]['gameId'])+' .totalplayercount').text(result[i]['count']);
                    
                 }

                
               
               } 
            });




    } 


  var myTabs = tabs({
    el: '#tabs',
    tabNavigationLinks: '.c-tabs-nav__link',
    tabContentContainers: '.c-tab'
  });

  myTabs.init();



(function($){
    $(window).on("load",function(){             
        
        
        $(".content-5, .content-4, .content-3, .content-2, .content-1, .content-me").mCustomScrollbar({
            axis:"x",
            theme:"dark-thin",
            autoExpandScrollbar:true,
            advanced:{autoExpandHorizontalScroll:true}
        });
    });
})(jQuery);


(function($){
        $(window).on("load",function(){
            $(".table-list").mCustomScrollbar({
                setHeight:350,
                theme:"dark-3"
            });
        });
})(jQuery);
                       
  
