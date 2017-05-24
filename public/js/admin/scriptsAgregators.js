$(document).ready(function(){ 
    var url="http://mobisteinreport.com/admin";
    var userId;
	var username;
    var usersColors = [];
    var colors = [];
    agr=0;
    //to display the clicked user in blue color
    var links = $('.usersElements').click(function() {
       links.removeClass('active');
       $(this).addClass('active');
    });


    //function that returns the agregators of the given id
    function getAgregators(userid){
        $.ajax({
            url: url+"/displayAgregators",
            type: "POST",
            data: {id:userid},
            dataType:"json",
            success:function(obj){
                $("#agregators").empty();
                var rows = '';
                for(i=0;i<obj.length;i++)
                {
                    var key='a'+obj[i].id;
                    agregator=obj[i].agregator.charAt(0).toUpperCase() + obj[i].agregator.substr(1).toLowerCase();
                    rows += "<div class='row oddEven' id="+key+"><div class='col-xs-2 col-md-2'><input class='checkBoxAllAgre' type='checkbox' name='usragr[]' value=" + obj[i].id + "></div><div class='col-xs-8 col-md-7'><span class='point' id='items'>"+ agregator+ "</span></div><div class='col-xs-1 col-md-1'></div></div>";
                }
                $("#agregators").append(rows);
                      
            },
            error:function(){
                alert("no agregators");
                $("#agregators").empty();
            }            
        });
    }
                
    function removeAgregators(id,data){ 
        $.ajax({
            url: url+"/removeAgregators",
            type: "POST",
            data: {data:data,id:id},
            dataType:"json",
            success:function(obj){
                $("#agregators").empty();
                var rows = '';
                for(i=0;i<obj.length;i++)
                {
                    var key='a'+obj[i].id;
                    agregator=obj[i].agregator.charAt(0).toUpperCase() + obj[i].agregator.substr(1).toLowerCase();
                    rows += "<div class='row oddEven' id="+key+"><div class='col-xs-2 col-md-2'><input class='checkBoxAllAgre' type='checkbox' name='usragr[]' value=" + obj[i].id + "></div><div class='col-xs-8 col-md-7'><span class='point' id='items'>"+ agregator+ "</span></div><div class='col-xs-1 col-md-1'></div></div>";
                }
                $("#agregators").append(rows);
                                     
            },
            error:function(){
                alert("no agregators");
                $("#agregators").empty();
            }         
        });
    }
            
            
    function reDesignAgregators(){
        $( ".allAggregatores" ).each(function( index ) {
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
            
    function getAllAgregatorsUsers(){
        $.ajax({
            url: url+"/getAllAgregatorsUsers",
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
                    var id='au'+obj[i].id;
                    agregator=obj[i].agregator.charAt(0).toUpperCase() + obj[i].agregator.substr(1).toLowerCase();
                    username=username.charAt(0).toUpperCase() + username.substr(1).toLowerCase();
                    $("#aggs").append("<div class='row oddEven allAggregatores ' id="+obj[i].uid+" ><div id="+id+"><div class='col-xs-1 col-md-1'></div><div class='col-xs-1 col-md-1'><input type='checkbox' name='agrwithuser[]' value=" + obj[i].id + "></div><div class='col-xs-8 col-md-7 col-lg-6'> <span id='items'>"+agregator+"</span> </div><div class='col-xs-2 col-md-2'><div class='colorsByUsers'>"+username+"</div><div class='col-xs-1 col-md-1'></div></div></div></div> ");
                    
                }
                reDesignAgregators();            
            },
            
            error:function(){
                alert('Error! No Agregators');
                $("#aggs").empty();
            }
            
        });

    }
            
    function addAgregators(id1,data1){
        $.ajax({
            url: url+"/addAgregators",
            type: "POST",
            data: {agregators:data1,id:id1},
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
                    var id='au'+obj[i].id;
                    agregator=obj[i].agregator.charAt(0).toUpperCase() + obj[i].agregator.substr(1).toLowerCase();
                    username=username.charAt(0).toUpperCase() + username.substr(1).toLowerCase();

                    $("#aggs").append("<div class='row oddEven allAggregatores ' id="+obj[i].uid+" ><div id="+id+"><div class='col-xs-1 col-md-1'></div><div class='col-xs-1 col-md-1'><input type='checkbox' name='agrwithuser[]' value=" + obj[i].id + "></div><div class='col-xs-8 col-md-7 col-lg-6'> <span id='items'>"+agregator+"</span> </div><div class='col-xs-2 col-md-2'><div class='colorsByUsers'>"+username+"</div><div class='col-xs-1 col-md-1'></div></div></div></div> ");
                    
                }
                reDesignAgregators();
                                 
            },
            
            error:function(){
                alert('Error! No Agregators');
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
            
            
    $( ".allAggregatores" ).each(function( index ) {
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
         
        } 
        else 
        {
            var index = usersColors.indexOf(idUser);
         
            $(this).find(".colorsByUsers").css("background-color", colors[index]);
         
        } 
    });
    
            
            
    $("body").on("click", ".usersElements", function() { 
        agr=1;
        userId = $(this).attr("id");
		username=$('#items'+userId).text();
		//alert(username);
        getAgregators(userId);
		//alert($( "#titlerow" ).text());
		$( "#titlerow" ).text(username+" Ag.");
    });
            
    $('#rmagr').click(function(){   
        var checked = $(" [name='usragr[]']:checked").length > 0;
        if (checked){
            var data = new Array();
            $("input[name='usragr[]']:checked").each(function(i) {
                data.push($(this).val());
            });
                    
            removeAgregators(userId,data);
            setTimeout(function() { getAllAgregatorsUsers(); }, 1000);
        }
        else{
            alert("Please select agregators to remove.");
        }
        
    });
            
    $('#addagr').click(function(){
        var checked = $(" [name='agrwithuser[]']:checked").length > 0;
        if (checked && agr==1){
            var data1 = new Array();
            $("input[name='agrwithuser[]']:checked").each(function(i) {
                data1.push($(this).val());
            });
            addAgregators(userId,data1);
                    
             setTimeout(function() { getAgregators(userId);}, 1000);
        }
        
        else{
            alert('Please select users and aggregators');
        }
        
    });
            
            //search section starts here
            
    $('#search').keyup(function(){  
        var string1= $(this).val().toLowerCase();
        console.log("str1="+string1);
        $("input[name='usragr[]']").each(function(i) {
                
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
            
    $('#searchUA').keyup(function(){    
        var string1= $(this).val().toLowerCase();
        console.log("str1="+string1);
        $( ".allAggregatores" ).each(function( index ){
                    
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
        
        