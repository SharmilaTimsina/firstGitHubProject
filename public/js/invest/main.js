$(document).ready(function(){
   
  
	$('.datepicker').datepicker({dateFormat:"yy-mm-dd"});
   
	$('body').on('click', '.imgTable' , function() {
		var t = $(this).attr('t');
		var d = $(this).attr('d');
		var p = $(this).attr('p');
		var os = $(this).attr('os');
		
		$('#titlebulk').text(t);
		$('#descriptionbulk').text(d);
		$('#platformbulk').text(p);
		$('#osbulk').text(os);
		
	});	
 
    

});
     
     
