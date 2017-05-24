	// Preloader

//<![CDATA[
        $(window).load(function() { // makes sure the whole site is loaded
            $('#status').fadeOut(); // will first fade out the loading animation
            $('#preloader').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website.
            $('body').delay(350).css({'overflow':'visible'});
        })
    //]]>

// datepicker
$(function()
{
    // Regular datepicker
    $('#date').datepicker({
        dateFormat: 'yy-mm-dd',
        prevText: '<i class="fa fa-chevron-left"></i>',
        nextText: '<i class="fa fa-chevron-right"></i>'
    });

    // Date range
    $('.sdate').datepicker({
        dateFormat: 'yy-mm-dd',
        prevText: '<i class="fa fa-chevron-left"></i>',
        nextText: '<i class="fa fa-chevron-right"></i>',
        onSelect: function( selectedDate )
        {
            $('.edate').datepicker('option', 'minDate', selectedDate);
        }
    });
    $('.edate').datepicker({
        dateFormat: 'yy-mm-dd',
        prevText: '<i class="fa fa-chevron-left"></i>',
        nextText: '<i class="fa fa-chevron-right"></i>',
        onSelect: function( selectedDate )
        {
            $('.sdate').datepicker('option', 'maxDate', selectedDate);
        }
    });
       // Datetime range
    $('.sdatet').datepicker({
        dateFormat: $.datepicker.TIMESTAMP,
        prevText: '<i class="fa fa-chevron-left"></i>',
        nextText: '<i class="fa fa-chevron-right"></i>',
        onSelect: function( selectedDate )
        {
            $('.edate').datepicker('option', 'minDate', selectedDate);
        }
    });
    $('.edatet').datepicker({
        dateFormat: $.datepicker.TIMESTAMP,
        prevText: '<i class="fa fa-chevron-left"></i>',
        nextText: '<i class="fa fa-chevron-right"></i>',
        onSelect: function( selectedDate )
        {
            $('.sdate').datepicker('option', 'maxDate', selectedDate);
        }
    }); 
    
}); 

$(document).ready(function(){
 
 $('#divcarr').hide();
 
 $('.switch').click(function(){
     
     $('.switch').not(this).attr('checked', false);

});

$('#country').change(function(){
     
    if($('#country').val()!=='country'){
        //alert($('#country').val());
        $('#divcoun').hide();
        $('#divcarr').show();
       if($('#fcountry').is(':checked') ){
            $('#fcarrier').trigger('click');
        }  
        
    }else{
        //alert($('#country').val());
        $('#divcoun').show();
        $('#divcarr').hide();
       
            
        $('#fcountry').trigger('click');
        
    }

});   
  
 $('#subutton').click(function(){
     
       
        var fcarrier = 0;
        if($('#fcarrier').is(':checked') ){
          fcarrier = 1;
        }
        var fcountry = 0;
        if($('#fcountry').is(':checked') ){
          fcountry = 1;
        }
        var fdate = 0;
        if($('#fdate').is(':checked') ){
          fdate = 1;
        }
        
        var dataArray = { timezone : $("#timezone").val(), country : $("#country").val(), start : $("#start").val(), finish : $("#finish").val(), fcarrier : fcarrier, fcountry : fcountry, fdate : fdate };
        
       
        $.ajax({
        url: 'http://mobisteinaffiliates.com/report/jax',
        type: 'POST',
        data: dataArray,
        dataType: 'text',
        success: function(data){
            //alert(data);
           
                $("#maintable").html(data);
            
        },
        error: function(){
            alert("Please try again later.");
        }
       });

    }) ;
   $('#excel').click(function(){
     
       
        var fcarrier = 0;
        if($('#fcarrier').is(':checked') ){
          fcarrier = 1;
        }
        var fcountry = 0;
        if($('#fcountry').is(':checked') ){
          fcountry = 1;
        }
        var fdate = 0;
        if($('#fdate').is(':checked') ){
          fdate = 1;
        }
        
      
       window.location.href = 'http://mobisteinaffiliates.com/report/excel?timezone='+$("#timezone").val()+'&country='+$("#country").val()+'&start='+$("#start").val()+'&finish='+$("#finish").val()+'&fcarrier='+fcarrier+'&fcountry='+fcountry+'&fdate='+fdate;
       
    

    }) ; 

  });


