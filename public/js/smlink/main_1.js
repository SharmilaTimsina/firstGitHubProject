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
      options.push({value: $(this).val(), text: $(this).text()});
    });
    $(select).data('options', options);
    $(textbox).bind('change keyup', function() {
      var options = $(select).empty().scrollTop(0).data('options');
      var search = $.trim($(this).val());
      var regex = new RegExp(search,'gi');

      $.each(options, function(i) {
        var option = options[i];
        if(option.text.match(regex) !== null) {
          $(select).append(
             $('<option class="mlist">').text(option.text).val(option.value)
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
    
    $(function() {
        $('#gcombo').filterByText($('#textbox'), true);
    });  
    
    
    
     $("body").on("focus",".cell",function(){
         cellval = $(this).text(); 
         
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
                $("#ntitle").html(cname+': http://njump.mobistein.com/?jp='+chash+'&linkref=');
              //   $('#sleeper').removeClass( "spinner" );
                $.ajax({url: "./ajaxtable?hash="+$(this).val(), success: function(result){
            
                   
                    $("#mtable").html(result);
                }});
        }
    });
    
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


    $("body").on("click",".mlist",function(){
        
        $("#mtable").html('');
      
       // $('#sleeper').addClass( "spinner" );
        chash = $(this).val();
        cname = $(this).text();
          $("#ntitle").html(cname+': http://njump.mobistein.com/?jp='+chash+'&linkref=');
         //  $('#sleeper').removeClass( "spinner" );
        $.ajax({url: "./ajaxtable?hash="+$(this).val(), success: function(result){
            //alert(result);    
            
            $("#mtable").html(result);
        }});
    
    });
    

     $("body").on("click","#newl",function(){
       
         $('#mtable').hide();
         $('#sleeper').addClass( "spinner" );
      
        var data = {};
        
       //alert($("#zlurl").text());
        
        data['nhash']= encodeURIComponent(chash);
        data['ngroup'] = encodeURIComponent(cname);
        data['nlurl']  = $("#zlurl").text();
        data['nlname'] = $("#zlname").text();
        data['nlop']   = $("#zlop").text();
        data['nlisp']  = $("#zlisp").text();
        data['nlper']  = $("#zlper").text();
        
      //  alert(JSON.stringify(data));
      
       
       $.ajax({url: "./ajaxinsertgroup",data: data,type: "post", success: function(result){
          var arr = jQuery.parseJSON(result);
            
            if(arr[0]==1){
                     $('#sleeper').removeClass( "spinner" );
                   $.ajax({url: "./ajaxtable?hash="+arr[1], success: function(res){
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
                      //alert(cname); 
                    $("#gcombo").html(result);
                    
//                    $("#mtable").html('');
//                    $('#sleeper').addClass( "spinner" );
//                    $.ajax({url: "./ajaxtable?hash="+chash, success: function(res){
//                                $('#sleeper').removeClass( "spinner" );
//                                $("#mtable").html(res);
//                            }}); 
                     }});
            }else{
                 $('#ew').text(arr[1]);
                 $('#ew').show();
            }
        });
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
            if(data==1){
                
                 
                 $('#clonem').modal('hide');
                 $.ajax({url: "./ajaxgroupcombo", success: function(result){
                      //alert(cname); 
                    $("#gcombo").html(result);
                    
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
                            //alert(cname); 
                           $("#gcombo").html(result);
                    
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
                    //alert(result);    
                    $("#gcombo").html(result);
                    $("#mtable").html('');
                    $('#sleeper').removeClass( "spinner" );
                   $.ajax({url: "./ajaxtable?hash="+arr[1], success: function(res){
                        //alert(result);    
                                
                                
                                $("#mtable").html(res);
                                $("#ntitle").html('http://njump.mobistein.com/?jp='+arr[1]+'&linkref=');
                            }}); 
                   
                     }});
            }else{
                 $('#ew').text(arr[1]);
                 $('#ew').show();
            }
        });
     });
     
     
});