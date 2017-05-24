$(document).ready(function(){ 
    var url="http://mobisteinreport.com/admin";
    var url1="http://mobisteinreport.com/admin";
    var userId;
    var usersColors = [];
    var colors = [];
	var username;
    ct=0;
    //to display the clicked user in blue color
    var links = $('.usersElements').click(function() {
       links.removeClass('active');
       $(this).addClass('active');
    });


    //function that returns the agregators of the given id
    function getCountries(userid){
        $.ajax({
            url: url+"/displayCountries",
            type: "POST",
            data: {id:userid},
            dataType:"json",
            success:function(obj){
                $("#countries").empty();
                var rows = '';
                for(i=0;i<obj.length;i++)
                {
                    var key='a'+obj[i].id;
                    rows += "<div class='row oddEven' id="+key+"><div class='col-xs-2 col-md-2'><input class='checkBoxAllAgre' type='checkbox' name='ct[]' value=" + obj[i].id + "></div><div class='col-xs-8 col-md-7'><span class='point' id='items'>"+ obj[i].country+ "</span></div><div class='col-xs-1 col-md-1'></div></div>";
                }
                $("#countries").append(rows);
                      
            },
            error:function(){
                alert("no countries");
                $("#countries").empty();
            }            
        });
    }
                
    function removeCountries(id,data){ 
        $.ajax({
            url: url+"/removeCountries",
            type: "POST",
            data: {data:data,id:id},
            dataType:"json",
            success:function(obj){
                $("#countries").empty();
                var rows = '';
                for(i=0;i<obj.length;i++)
                {
                    var key='a'+obj[i].id;
                    rows += "<div class='row oddEven' id="+key+"><div class='col-xs-2 col-md-2'><input class='checkBoxAllAgre' type='checkbox' name='ct[]' value=" + obj[i].id + "></div><div class='col-xs-8 col-md-7'><span class='point' id='items'>"+ obj[i].country+ "</span></div><div class='col-xs-1 col-md-1'></div></div>";
                }
                $("#countries").append(rows);
                                     
            },
            error:function(){
                alert("no Countries");
                $("#countries").empty();
            }         
        });
    }
            
            
    function reDesignCountries(){
        $( ".allCountries" ).each(function( index ) {
            var idUser = $(this).attr("id");
            if($.inArray(idUser, usersColors) == -1) {
                var letters = '0123456789ABCDEF';
                var color = '#';
                for (var i = 0; i < 6; i++ ) {
                    color += letters[Math.floor(Math.random() * 16)];
                } 

                usersColors.push(idUser);
                colors.push(color);

                $(this).find(".colorsByUsers").css("background-color", color);
         
                } else {
                var index = usersColors.indexOf(idUser);
         
                $(this).find(".colorsByUsers").css("background-color", colors[index]);
         
            } 
        });
    }
            
    function getAllCountriesUsers(){
        $.ajax({
            url: url+"/getAllCountriesUsers",
            type: "POST",
            dataType:"json",
            success:function(obj){
                console.log(obj);
                $("#aggs").empty();
                for(i=0;i<obj.length;i++)
                {    
                    var username=obj[i].username;
                    if(username==null)
                    {
                        username='Unatributed';
                    }
                    var id='ctusr'+obj[i].id;

                    if(username!='Unatributed')
                    {
                        var aliasArr=username.split(',');
                        if(aliasArr.length >1)
                        {
                            $("#aggs").append("<div class='row oddEven allCountries ' id="+obj[i].uid+" ><div id="+id+"><div class='col-xs-1 col-md-1'></div><div class='col-xs-1 col-md-1'><input type='checkbox' name='ctusr[]' value=" + obj[i].id + "></div><div class='col-xs-8 col-md-7 col-lg-6'><span id='items'>"+ obj[i].country+"</span></div><div class='col-xs-2 col-md-2'><a href='#'' data-toggle='tooltip' title="+username+"><img data-toggle='popover' data-placement='top' data-content='view' data-original-title='' id='trident' class='iconsTable' src="+url1+"/images/trident.svg></a></div><div class='col-xs-1 col-md-1'></div></div></div> ");
             
                        }
                        else
                        {
                            username=username.charAt(0).toUpperCase() + username.substr(1).toLowerCase();
                            $("#aggs").append("<div class='row oddEven allCountries ' id="+obj[i].uid+" ><div id="+id+"><div class='col-xs-1 col-md-1'></div><div class='col-xs-1 col-md-1'><input type='checkbox' name='ctusr[]' value=" + obj[i].id + "></div><div class='col-xs-8 col-md-7 col-lg-6'> <span id='items'>"+ obj[i].country+"</span> </div><div class='col-xs-2 col-md-2'><div class='colorsByUsers'>"+username+"</div></div><div class='col-xs-1 col-md-1'></div></div></div> ");
             
                        }

                    }
                    else
                    {
                        $("#aggs").append("<div class='row oddEven allCountries ' id="+obj[i].uid+" ><div id="+id+"><div class='col-xs-1 col-md-1'></div><div class='col-xs-1 col-md-1'><input type='checkbox' name='ctusr[]' value=" + obj[i].id + "></div><div class='col-xs-8 col-md-7 col-lg-6'> <span id='items'>"+ obj[i].country+"</span> </div><div class='col-xs-2 col-md-2'><div class='colorsByUsers'>"+username+"</div></div><div class='col-xs-1 col-md-1'></div></div></div> ");
             
                    }
                
                }
                reDesignCountries();            
            },
            
            error:function(){
                alert('Error! No Countries');
                $("#aggs").empty();
            }
            
        });

    }
            
    function addCountries(id1,data1){
        $.ajax({
            url: url+"/addCountries",
            type: "POST",
            data: {countries:data1,id:id1},
            dataType:"json",
            success:function(obj){
                $("#aggs").empty();
                for(i=0;i<obj.length;i++)
                {    
                    var username=obj[i].username;
                    if(username==null)
                    {
                        username='Unatributed';
                    }
                    var id='ctusr'+obj[i].id;

                    if(username!='Unatributed')
                    {
                        var aliasArr=username.split(',');
                        if(aliasArr.length >1)
                        {
                            
                            $("#aggs").append("<div class='row oddEven allCountries ' id="+obj[i].uid+" ><div id="+id+"><div class='col-xs-1 col-md-1'></div><div class='col-xs-1 col-md-1'><input type='checkbox' name='ctusr[]' value=" + obj[i].id + "></div><div class='col-xs-8 col-md-7 col-lg-6'><span id='items'>"+ obj[i].country+"</span></div><div class='col-xs-2 col-md-2'><a href='#'' data-toggle='tooltip' title="+username+"><img data-toggle='popover' data-placement='top' data-content='view' data-original-title='' id='trident' class='iconsTable' src="+url1+"/images/trident.svg></a></div><div class='col-xs-1 col-md-1'></div></div></div> ");
             
                        }
                        else
                        {
                            username=username.charAt(0).toUpperCase() + username.substr(1).toLowerCase();
                            $("#aggs").append("<div class='row oddEven allCountries ' id="+obj[i].uid+" ><div id="+id+"><div class='col-xs-1 col-md-1'></div><div class='col-xs-1 col-md-1'><input type='checkbox' name='ctusr[]' value=" + obj[i].id + "></div><div class='col-xs-8 col-md-7 col-lg-6'> <span id='items'>"+ obj[i].country+"</span> </div><div class='col-xs-2 col-md-2'><div class='colorsByUsers'>"+username+"</div></div><div class='col-xs-1 col-md-1'></div></div></div> ");
             
                        }

                    }
                    else
                    {
                        $("#aggs").append("<div class='row oddEven allCountries ' id="+obj[i].uid+" ><div id="+id+"><div class='col-xs-1 col-md-1'></div><div class='col-xs-1 col-md-1'><input type='checkbox' name='ctusr[]' value=" + obj[i].id + "></div><div class='col-xs-8 col-md-7 col-lg-6'> <span id='items'>"+ obj[i].country+"</span> </div><div class='col-xs-2 col-md-2'><div class='colorsByUsers'>"+username+"</div></div><div class='col-xs-1 col-md-1'></div></div></div> ");
             
                    }
                
                           
                }
                reDesignCountries();
                                 
            },
            
            error:function(){
                alert('Error! No Countries');
                $("#aggs").empty();
            }
            
        });
    }
            
                               
    $( ".usersElements" ).each(function( index ) {
        var idUser = $(this).attr("id");
        if($.inArray(idUser, usersColors) == -1) {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++ ) {
                color += letters[Math.floor(Math.random() * 16)];
            } 
            usersColors.push(idUser);
            colors.push(color);
            $(this).find(".colorsByUser").css("background-color", color); 
        } 
        else
        {
            var index = usersColors.indexOf(idUser);
         
            $(this).find(".colorsByUser").css("background-color", colors[index]);
         
        } 
    });
            
            
    $( ".allCountries" ).each(function( index ) {
        var idUser = $(this).attr("id");
        if($.inArray(idUser, usersColors) == -1) {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++ ) {
                color += letters[Math.floor(Math.random() * 16)];
            } 

            usersColors.push(idUser);
            colors.push(color);

            $(this).find(".colorsByUsers").css("background-color", color);
         
            } else {
            var index = usersColors.indexOf(idUser);
         
             $(this).find(".colorsByUsers").css("background-color", colors[index]);
         
            } 
    });
    
            
            
    $("body").on("click", ".usersElements", function() { 
        ct=1;
        userId = $(this).attr("id");
        getCountries(userId);
		username=$('#items'+userId).text();
		//alert(username);
		//alert($( "#titlerow" ).text());
		$( "#titlerow" ).text(username+" Ct.");
    });
            
    $('#rmct').click(function(){   
        var checked = $(" [name='ct[]']:checked").length > 0;
        if (checked){
            var data = new Array();
            $("input[name='ct[]']:checked").each(function(i) {
                data.push($(this).val());
            });
                    
            removeCountries(userId,data);
            setTimeout(function() { getAllCountriesUsers(); }, 1000);
        }
        else{
            alert("Please select Countries to remove.");
        }
        
    });
            
    $('#addct').click(function(){
        var checked = $(" [name='ctusr[]']:checked").length > 0;
        if (checked && ct==1){
            var data1 = new Array();
            $("input[name='ctusr[]']:checked").each(function(i) {
                data1.push($(this).val());
            });
            addCountries(userId,data1);
            setTimeout(function() { getCountries(userId);}, 1000);
        }
        
        else{
            alert('Please select users and Countries');
        }
        
    });
            
            //search section starts here
            
    $('#search').keyup(function(){  
        var string1= $(this).val().toLowerCase();
        console.log("str1="+string1);
        $("input[name='ct[]']").each(function(i) {
                
            var string=$(this).parent().attr('id');
            var string2=$('#'+string).text().toLowerCase();
            console.log("str2="+string2);
            var valtest=string2.indexOf(string1);
            console.log("index value:"+valtest);
            if(valtest >= 0){
                $('#'+string).show();
            }
            else{
                $('#'+string).hide();
            }
                
        });
    });
                
    $('#search1').keyup(function(){ 
        var string1= $(this).val().toLowerCase();
        console.log("str1="+string1);
        $( ".usersElements" ).each(function( index ) {
            var string=$(this).attr('id');
            var string2=$('#'+string).text().toLowerCase();
            console.log("str2="+string2);
            var valtest=string2.indexOf(string1);
            console.log("index value:"+valtest);
            if(valtest >= 0){
                $('#'+string).show();
            }
            else{
                $('#'+string).hide();
            }       
        });
    });
            
    $('#searchUC').keyup(function(){    
        var string1= $(this).val().toLowerCase();
        console.log("str1="+string1);
        $( ".allCountries" ).each(function( index ){
                    
            var string=$(this).children().first().attr('id');
            var string2=$('#'+string).text().toLowerCase();
            //console.log("str2="+string2);
            var valtest=string2.indexOf(string1);
                    
            if(valtest >= 0){
                $('#'+string).show();
                $('#'+string).parent().addClass( "oddEven" );
                        
            }
            else{
                $('#'+string).hide();
                    $('#'+string).parent().removeClass( "oddEven" );

                }
                
            });
        });
                        
    });
        
        