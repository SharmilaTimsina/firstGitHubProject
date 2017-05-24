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
    var nlc     = 0;
    
    $(function() {
        $('#gcombo').filterByText($('#textbox'), true);
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
     
     $("body").on("keypress","#gcombo",function(e){
     
        var p = e.which;
        if(p==13){
                $("#mtable").html('');
            //    $('#sleeper').addClass( "spinner" );
                chash = $(this).val();
                //cname = $("option:selected", this).text();
                $("#ntitle").html(chash);
              //   $('#sleeper').removeClass( "spinner" );
              
                $.ajax({url: "http://mobisteinreport.com/isp/get_group?gn="+$(this).val(), success: function(result){
             
                    $("#mtable").html(result);
                }});
        }
    });

   

    $("body").on("click","#gcombo",function(){
        
        $("#mtable").html('');
      
       // $('#sleeper').addClass( "spinner" );
        chash = $(this).val();
       
          $("#ntitle").html(chash);
         //  $('#sleeper').removeClass( "spinner" );
         //alert("./get_group?gn="+$(this).val());
          $.ajax({url: "http://mobisteinreport.com/isp/get_group?gn="+$(this).val(), success: function(result){
            //alert(result);    
            
            $("#mtable").html(result);
        }});
    
    });
    
    
    
      $("body").on("click",".delb",function(){
        $('.ew').hide();
        
        
       //var values = $("#delf").serialize();
        var data = {};
        data['iid'] = $(this).attr('id');
       
       // alert(data);
        $.ajax({url: "http://mobisteinreport.com/isp/delete_isp",data: data,type: "post", success: function(result){
                   // alert(result);
                      if(result==1){
                           
                           $("#mtable").html('');
                           $.ajax({url: "http://mobisteinreport.com/isp/get_groups", success: function(result){
                                $("#textbox").html(''); 
                                $("#gcombo").html(result);
                                $('#gcombo').filterByText($('#textbox'), true);
                     }});
                      }else{
                          alert('Error');
                      }
            }});
 
     });    
     $(".delg").click(function(){
        $('.ew').hide();
        
        
       //var values = $("#delf").serialize();
        var data = {};
        data['nhash'] = encodeURIComponent(chash);
       // alert(data);
        $.ajax({url: "http://mobisteinreport.com/isp/delete_isp",data: data,type: "post", success: function(result){
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
      
    
     $("#ngroup").click(function(){
         
         
         $('#ew').hide();
         $.post("http://mobisteinreport.com/isp/create_group",
           ($("#gname").serialize()),
        function(data){
         
          
            if(data!=0){
                $('#sleeper').addClass( "spinner" );
                 $('#nnjm').modal('hide');
                 $.ajax({url: "http://mobisteinreport.com/isp/get_groups", success: function(result){
                    //alert(result);    
                    $("#gcombo").html(result);
                    $("#mtable").html('');
                    $('#sleeper').removeClass( "spinner" );
                   $.ajax({url: "http://mobisteinreport.com/isp/get_group?gn="+data, success: function(res){
                        //alert(result);    
                                
                                $("#mtable").html(res);
                                $("#ntitle").html(data);
                            }}); 
                   
                     }});
            }else{
                 $('#ew').text('Error');
                 $('#ew').show();
            }
        });
     });
     
      $("body").on("click","#nline",function(){
        
         
         $('#ew').hide();
         var data = {}; 
         data['gn'] = chash;
         data['is'] = $("#iline").text();
         data['co'] = $("#cline").text();
       
         $.post("http://mobisteinreport.com/isp/create_group",
           data,
        function(ret){
         
         
          
            if(ret!=0){
                $('#sleeper').addClass( "spinner" );
                 $('#nnjm').modal('hide');
                 $.ajax({url: "http://mobisteinreport.com/isp/get_groups", success: function(result){
                    //alert(result);    
                    $("#gcombo").html(result);
                    $("#mtable").html('');
                    $('#sleeper').removeClass( "spinner" );
                   $.ajax({url: "http://mobisteinreport.com/isp/get_group?gn="+ret, success: function(res){
                        //alert(result);    
                                
                                $("#mtable").html(res);
                                $("#ntitle").html(ret);
                            }}); 
                   
                     }});
            }else{
                 $('#ew').text('Error');
                 $('#ew').show();
            }
        });
     });
     
     
});