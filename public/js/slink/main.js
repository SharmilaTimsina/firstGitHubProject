	// Preloader

//<![CDATA[
        $(window).load(function() { // makes sure the whole site is loaded
            $('#status').fadeOut(); // will first fade out the loading animation
            $('#preloader').delay(100).fadeOut('slow'); // will fade out the white DIV that covers the website.
            $('body').delay(100).css({'overflow':'visible'});
        });
    //]]>
 function getgroup(){
     
      $.ajax({url: "./ajaxgroupcombo", success: function(result){
            //alert(result);    
            $("#gcombo").html(result);
        }});
     
 }
 function closest(el, sel) {
    if (el != null)
        return el.matches(sel) ? el 
            : (el.querySelector(sel) 
                || closest(el.parentNode, sel));
}
function hasClass( elem, klass ) {
     return (" " + elem.className + " " ).indexOf( " "+klass+" " ) > -1;
}

jQuery.fn.filterByText = function(textbox, selectSingleMatch) {
  return this.each(function() {
    var select = this;
    var options = [];
    $(select).find('option').each(function() {
      options.push({value: $(this).val(), text: $(this).text(), linkref : $(this).attr('linkref'), ccountry : $(this).attr('ccountry')});
    });
    $(select).data('options', options);
    $(textbox).bind('change keyup', function() {
      var options = $(select).empty().scrollTop(0).data('options');
      var search = $.trim($(this).val());
      var regex = new RegExp(search,'gi');

      $.each(options, function(i) {
		var option = options[i];
		
		//console.log(option);
			
        if(option.text.match(regex) !== null) {
		  $(select).append(
             $('<option class="mlist" linkref="' + option.linkref + '" ccountry="' + option.ccountry + '">').text(option.text).val(option.value)
          );  
        }
      });
      if (selectSingleMatch === true && 
          $(select).children().length === 1) {
        $(select).children().get(0).selected = true;
      }
    });
  });
};

$(document).ready(function(){
   
    var chash   = '';
    var cname   = '';
    var cellval = '';
    var nlc     = 0;
    
    $(function() {
        $('#gcombo').filterByText($('#textbox'), true);
    });  
    
     
	  $("body").on("change","#epc",function(){
			
                $("#mtable").html('');
                
                $("#ntitle").html(cname+': http://njump.youmobistein.com/?jp='+chash+'&linkref=');
             
                $.ajax({url: "./ajaxtable?hash="+chash+"&period="+$(this).val(), success: function(result){
                    $("#ntitle").html(cname+': http://njump.youmobistein.com/?jp='+chash+'&linkref=');    
                    $("#mtable").html(result);
                }});
    });
    
     $("body").on("focus",".cell",function(){
         cellval = $(this).text(); 
         
     });
     $("body").on("keypress","#textbox",function(e){
     
        var p = e.which;
        if(p==13){
            
                $("#mtable").html('');
                chash = $('#gcombo option:first-child').attr('value');
                cname = $('#gcombo option:first-child').text();
                $("#ntitle").html(cname+': http://njump.youmobistein.com/?jp='+chash+'&linkref=');
             
                $.ajax({url: "./ajaxtable?hash="+chash, success: function(result){
                    $("#ntitle").html(cname+': http://njump.youmobistein.com/?jp='+chash+'&linkref=');    
                    $("#mtable").html(result);
                }});
           
            
        }
    });
    
    //deleterow
    $("body").on("click",".del",function(){
         $('#mtable').hide();
         $('#sleeper').addClass( "spinner" );
         var did = $(this).attr('did');
         var el = $(this).parent('th');
         var data = {}; 
         data['did'] = did;
        
           $.ajax({url: "./ajaxdeleterow",data: data,type: "post", success: function(result){
                    
                      if(result==1){
                           el.parent('tr').remove();
                            $('#mtable').show();
                            $('#sleeper').removeClass( "spinner" );
                      }else{
                          alert('Error');
                      }
                     
               
            }});
     
     });
      //clonerow
    $("body").on("click",".cll",function(){
         $('#mtable').hide();
         $('#sleeper').addClass( "spinner" );
         var did = $(this).attr('did');
         var el = $(this).parent('th');
         var data = {}; 
         data['did'] = did;
       
           $.ajax({url: "./ajaxclonerow",data: data,type: "post", success: function(result){
                    //alert(result);
                      if(result==1){
                           $.ajax({url: "./ajaxtable?hash="+chash, success: function(result){
                        //alert(result);    
                        $('#sleeper').removeClass( "spinner" );
                        $('#mtable').show();
                        $("#mtable").html(result);
        }});
                            
                           
                      }else{
                          alert('Error');
                      }
                     
               
            }});
     
     });
     $("body").on("keypress","#gcombo",function(e){
		
        var p = e.which;
        if(p==13){
                $("#mtable").html('');
            //    $('#sleeper').addClass( "spinner" );
                chash = $(this).val();
                cname = $("option:selected", this).text();
                $("#ntitle").html(cname+': http://njump.youmobistein.com/?jp='+chash+'&linkref=');
              //   $('#sleeper').removeClass( "spinner" );
                $.ajax({url: "./ajaxtable?hash="+$(this).val(), success: function(result){
            
                   
                    $("#mtable").html(result);
                }});
        }
    });
    
    
//    $(document).keydown(function(e) {
//    if($("*:focus").hasClass('cell')){  
//          var line = $("*:focus").attr('lin');
//          var col =  $("*:focus").attr('col');
//    switch(e.which) {
//        case 37: // left
//            
//         // $(".cell [lin='"+line+"'][col='"+col-1+"']").focus();
//        break;
//
//        case 38: // up
//        break;
//
//        case 39: // right
//        break;
//
//        case 40: // down
//        break;
//
//        default: return; // exit this handler for other keys
//    }      
//    }
//    e.preventDefault(); // prevent the default action (scroll / move caret)
//});
    document.addEventListener('keydown', function (event) {
		
      var esc = event.which == 27,
      nl = event.which == 13,
      tl = event.which == 9,
     // fl = event.focusout,
      el = event.target,
      input = el.nodeName != 'INPUT' && el.nodeName != 'TEXTAREA' && $(el).hasClass('cell'),
      data = {};
      
      if (input) {

            if (esc) {
              // restore state
              document.execCommand('undo');
              el.blur();
            } else if (nl || tl) {
              event.preventDefault();
              data['text'] = el.innerHTML;
              data['id'] = el.getAttribute('id');
              var currval = el.innerHTML;
              
              // we could send an ajax request to update the field
              $.ajax({url: "./ajaxupdatecell",data: data,type: "post", success: function(result){
                      if(result=='Error'){
                          alert('Error');
                      }
               
            }});

              //alert(JSON.stringify(data));
              el.innerHTML = currval;
              el.blur(); 
              if(tl){
                 
                $('#'+$( '#'+data['id'] ).next().attr('id')).focus();
//                document.getElementById(closest(el,"th").id).focus();  
                
              }
             
              
            }
          }
		  
		   
        }, true);
    $("body").on("change",".sbbox",function(){
        
        data = {};
        data['text'] = $(this).val();
        data['id'] = $(this).attr('id'); 
       // alert($(this).attr('id')+" "+$(this).val());
        $.ajax({url: "./ajaxupdatecell",data: data,type: "post", success: function(result){
                //alert(result);
                      if(result=='Error'){
                          alert('Error');
                      }
         }});
    });

    $("body").on("click",".mlist",function(){
        
        $("#mtable").html('');
      
       // $('#sleeper').addClass( "spinner" );
        chash = $(this).val();
        cname = $(this).text();
          $("#ntitle").html(cname+': http://njump.youmobistein.com/?jp='+chash+'&linkref=');
         //  $('#sleeper').removeClass( "spinner" );
        $.ajax({url: "./ajaxtable?hash="+$(this).val(), success: function(result){
            //alert(result);    
            
            $("#mtable").html(result);
			getAllValues();
        }});
    
    });
    
    
    $("body").on("click","#aline",function(){
        
        
        $('#maint').append('<tr class="linesr"><th id="zlname" class="nlc" contenteditable=""></th><th id="zlurl" class="nlc" contenteditable=""></th><th id="zlop" class="nlc" contenteditable=""></th><th id="zbhour" class="nlc" contenteditable></th><th id="zehour" class="nlc" contenteditable></th><th id="zdev" class="nlc" contenteditable=""></th><th id="zlisp" class="nlc" contenteditable=""></th><th id="zlper" class="nlc" contenteditable=""></th><th class="nlc sbox" ><select><option value="0">Default</option><option value="1">Rotation</option><option value="2">Back</option><option value="3">B-R</option></select></th> <th>&nbsp;</th></tr>');
        
        
        
    });
     $("body").on("click","#newl2",function(){
        var nlines = [];
        $("#maint tr.linesr").each(function(){
            var temparr = [];
            var iter=0;
                $(this).find('th').each (function() {
                    if($(this).hasClass("sbox")){
                        
                         temparr[iter] = $(this).find(":selected").val();
                    }else{
                     temparr[iter] = $(this).text().trim();}
                     iter++;
                });
                nlines.push(temparr);
            });
            $('#mtable').hide();
            $('#sleeper').addClass( "spinner" );
            var data = {};
            data['adata']=nlines;
            data['nhash']=encodeURIComponent(chash);
            data['cname']=encodeURIComponent(cname);
          
                   
           $.ajax({url: "./ajaxinsertmgroup",data: data,type: "post", success: function(result){
                  
				  //alert(result);
                  //var arr = jQuery.parseJSON(result);
				  
                   if(result==1){
                   $('#sleeper').removeClass( "spinner" );
                   $.ajax({url: "./ajaxtable?hash="+chash, success: function(res){
                        //alert(result);   
                               
                                $('#mtable').show(); 
                                $("#mtable").html(res);
                                
                            }}); 
                   
                   
                    }else{
                        $('#sleeper').removeClass( "spinner" );
                        $('#mtable').show();
                        alert('Error: Please fill the fields correctly');
                        
                    }
                     
               
            }});    
        
        
    });
    

        
     
     $("#nnameb").click(function(){
        $('.ew').hide();
        
        var values = $("#nnamef").serialize();
      
        values += "&nhash=" + encodeURIComponent(chash);
        //alert(values);
        $.post("./ajaxname",
        (values),
        function(data){
          var arr = jQuery.parseJSON(data);
            
            if(data==1){
                
                 cname = $("#nname").val();
                 $('#nnamem').modal('hide');
                 $.ajax({url: "./ajaxgroupcombo", success: function(result){
                    $("#textbox").html('');
                    $("#gcombo").html(result);
                    $('#gcombo').filterByText($('#textbox'), true);
                    $("#ntitle").html(cname+': http://njump.youmobistein.com/?jp='+chash+'&linkref=');
//                    $("#mtable").html('');
//                    $('#sleeper').addClass( "spinner" );
//                    $.ajax({url: "./ajaxtable?hash="+chash, success: function(res){
//                                $('#sleeper').removeClass( "spinner" );
//                                $("#mtable").html(res);
//                                
//                            }}); 
                     }});
            }else{
                 $('#ew').text(arr[1]);
                 $('#ew').show();
            }
        });
     });
     

      $("body").on("blur",".cell",function(){    
              var data = {};  
            data['text'] = $(this).text().trim();
            data['id'] = $(this).attr('id');
            var colnumber= $(this).attr("col");
            var rowid = data['id'].split('_')[0];
            if(colnumber == '7' || colnumber == '8'){
                data['bhour'] = $('#'+rowid+'_7').text();
                data['ehour'] = $('#'+rowid+'_8').text();
            }      
            // we could send an ajax request to update the field
                $.ajax({url: "./ajaxupdatecell",data: data,type: "post", success: function(result){
                    if(result=='Error'){
                        alert('Error');
                    }
            }});
         
         
     });
     
     $("#cloneb").click(function(){
        $('.ew').hide();
        
        var values = $("#clonef").serialize();
        values += "&nhash=" + encodeURIComponent(chash);
        //alert(values);
        $.post("./ajaxclone",
        (values),
        function(data){
          var arr = jQuery.parseJSON(data);
            //alert(data);
            if(arr[0]==1){
                
                 
                 $('#clonem').modal('hide');
                 $.ajax({url: "./ajaxgroupcombo", success: function(result){
                      //alert(cname); 
                    $("#textbox").html('');  
                    $("#gcombo").html(result);
                    $('#gcombo').filterByText($('#textbox'), true);
                    
                     }});
                $.ajax({url: "./ajaxtable?hash="+arr[1], success: function(res){
                                chash=arr[1];
                                cname=$("#clonen").val();

                                //$("#textbox").val(cname);
                                $("#gcombo option[value='" + chash + "']").click();

                                $("#mtable").html(res);
                                $("#ntitle").html(cname+': http://njump.youmobistein.com/?jp='+chash+'&linkref=');
                            }}); 
            }else{
                 $('#ew').text(arr[1]);
                 $('#ew').show();
            }
        });
     });    
     
      $("#delb").click(function(){
        $('.ew').hide();
        
        
       //var values = $("#delf").serialize();
        var data = {};
        data['nhash'] = encodeURIComponent(chash);
       // alert(data);
        $.ajax({url: "./ajaxdelete",data: data,type: "post", success: function(result){
                   // alert(result);
                      if(result==1){
                           $('#delm').modal('hide');
                           
                    
                    
                           $("#mtable").html('');
                           $.ajax({url: "./ajaxgroupcombo", success: function(result){
                                $("#textbox").html(''); 
                                $("#gcombo").html(result);
                                $('#gcombo').filterByText($('#textbox'), true);
                     }});
                      }else{
                          alert('Error');
                      }
                     
               
            }});
 
     });    
      
    
     $("#nnjs").click(function(){
         
         
         $('#ew').hide();
         $.post("./ajaxinsertgroup",
           ($("#nnjf").serialize()),
        function(data){
			 
          var arr = jQuery.parseJSON(data);
          
            if(arr[0]==1){
                $('#sleeper').addClass( "spinner" );
                 $('#nnjm').modal('hide');
                 $.ajax({url: "./ajaxgroupcombo", success: function(result){
                       
                    $("#gcombo").html(result);
                    $("#mtable").html('');
                    $('#sleeper').removeClass( "spinner" );
                   $.ajax({url: "./ajaxtable?hash="+arr[1], success: function(res){
                           
                                
                                
                                $("#mtable").html(res);
                                $("#ntitle").html('http://njump.youmobistein.com/?jp='+arr[1]+'&linkref=');
								
								
                            }}); 
                   
                     }});
            }else{
                 $('#ew').text(arr[1]);
                 $('#ew').show();
            }
        });
     });
     
	//percentage conversion
	var percentageTotal = 0;
	$("body").on("keyup",".proportationNumbers",function(){    
		getAllValues();		
     });
	
	
	function getAllValues() {
		percentageTotal = 0;
		$('#maint tr').each(function() {
			percentageTotal += Number($(this).find(".proportationNumbers").text());    
		});
		
		
		//percentageTotal = 100%
		
		$('#maint tr').each(function() {
			var correspondepercent = Math.round((((Number($(this).find(".proportationNumbers").text())) * 100) / percentageTotal) * 100) / 100;    
			
			$(this).find(".percentageCells").html(correspondepercent);
		});
	}
	
	/*
	var currentPercentages = 0;
	$("body").on("focusout",".percentageCells",function(){    
		neew = Number($(this).text());
	
		makeTheJob();
     });
	 
	 var old = 0;
	 var neew = 0;
	 
	 $("body").on("focus",".percentageCells",function(){    
		old = 0;
		neew = 0;
		
		old = Number($(this).text());
     });
     
	 function makeTheJob() {
		currentPercentages = [];
		
		$('#maint .percentageCells').each(function() {
			currentPercentages.push(Number($(this).text()));   
		});
		
		var total = 0;
		$.each(currentPercentages, function(idx, num){
			total += parseFloat(num)
		})
		
		var difference = old - neew ;
		var tochange = difference / (currentPercentages.length - 1); 
		
		var position = currentPercentages.indexOf(neew);
		
		$.each(currentPercentages, function(idx, num){
			if(idx != position) {
				if( difference > 0 ) {
					//is positive
					currentPercentages[idx] = currentPercentages[idx] + tochange;
				} else {
					//is negative 
					currentPercentages[idx] = currentPercentages[idx] - tochange;
				}
			}
		});
		
		var i = 0;
		$('#maint .percentageCells').each(function() {
			$(this).find(".percentageCells").html(currentPercentages[i]);
			i++;
		});
		
		console.log(position);
		console.log(currentPercentages);
		
		/*
		
		
		if(total >= 100) {
			var total = 0;
			
		}
		
		console.log(old);
		console.log(neew);
		
		console.log(currentPercentages);
		
	 }
	 */
});
