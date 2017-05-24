$(document).ready(function(){
	
	var url="/sales";

	$('#countries').on('change', function() {
		//$("#carriers").attr('multiple','multiple');
		var countries=$(this).val();
		if(countries)
		{
			var formData = new FormData();
			formData.append('countries',countries);
			$.ajax({
				url: url+"/getCarriers",
				type: "POST",
				dataType:"json", 
				data:formData,
				contentType: false,
				cache: false,
				processData: false,
				success:function(obj){
				
					$('#carriers').empty();
					
					var rows='';
					//rows+="<option value=''>Choose Carrier</option>";	
					
					for(i=0;i<obj.length;i++)
					{
						
						rows+="<option value='"+obj[i].name+"'>"+obj[i].name+"</option>";
					
					}
					$('#carriers').append(rows);
					$('#carriers').selectpicker('refresh');
					
				},
				error:function(){
                    
				}
				
		});
			
			
		}
	});
	
	$('#search').on("click", function(){
		//var countries=$('#countries').attr('value');
		var countries=$('#countries').val();
		
		if(countries)
		{
			
			var carriers=$('#carriers').val();
			var date=$('#date').val();
			var formData = new FormData();
			formData.append('countries',countries);
			formData.append('carriers', carriers);
			formData.append('date',date);       
			formData.append('total','1'); 		
			$.ajax({
				url: url+"/getData",
				type: "POST",
				dataType:"json", 
				data:formData,
				contentType: false,
				cache: false,
				processData: false,
				success:function(obj){
					$("#table").empty();
					var rows = '';
					var af,clicks,conversions,CPA,CR,EPC,totalclicks,totalconversions,totalCPA,totalCR,totalEPC;
					for(i=0;i<obj.length;i++)
					{
						if(obj[i].affiliate==0)
						{
							var af='AD';
						}
						else if(obj[i].affiliate==1)
						{
							var af='AF';
						}
						else if(obj[i].affiliate==2)
						{
						var af='MS';
					}
					clicks=obj[i].clicks;
					conversions=obj[i].conversions;
					CPA=obj[i].CPA;
					CR=obj[i].CR;
					EPC=obj[i].EPC;
					/*if(CR%1==0)
					{
						CR=CR/1;
					}
					else
					{
						//display 4 value after decimal
						CR=parseFloat(Math.round(CR * 100) / 100).toFixed(4);

						
					}
					if(CPA%1==0)
					{
						CPA=CPA/1;
					}
					else
					{
						//display 4 value after decimal
						CPA=parseFloat(Math.round(CPA * 100) / 100).toFixed(4);

						
					}
					if(EPC%1==0)
					{
						EPC=EPC/1;
					}
					else
					{
						
						//display 4 value after decimal
						EPC=parseFloat(Math.round(EPC * 100) / 100).toFixed(4);

					}*/
                    rows+="<tr  class='crm-lightgrey'><th scope='row'>"+af+"</th><td>"+clicks+"</td><td>"+conversions+"</td><td>"+CPA+"</td><td>"+ CR+"%</td><td>"+EPC+"</td></tr>";
               
				}
				$("#table").append(rows);
				// for totals
				formData.append('total','2');    
				$.ajax({
				url: url+"/getData",
				type: "POST",
				dataType:"json", 
				data:formData,
				contentType: false,
				cache: false,
				processData: false,
				success:function(obj){
				$("#table").empty;
					var rows1= '';
			
					for(i=0;i<obj.length;i++)
					{
						if(obj[i].clicks){
							clicks=obj[i].clicks;
						}
						else{
							clicks=0;
						}
						if(obj[i].conversions){
							conversions=obj[i].conversions;
						}
						else{
							conversions=0;
						}
						if(obj[i].CPA){
							CPA=obj[i].CPA;
						}
						else{
							CPA=0;
						}
						if(obj[i].CR){
							CR=obj[i].CR;
						}
						else{
							CR=0;
						}if(obj[i].EPC){
							EPC=obj[i].EPC;
						}
						else{
							EPC=0;
						}
					
					/*CPA=obj[i].CPA;
					CR=obj[i].CR;
					EPC=obj[i].EPC;*/
					/*if(CR%1==0)
					{
						CR=CR/1;
					}
					else
					{
						//display 4 value after decimal
						CR=parseFloat(Math.round(CR * 100) / 100).toFixed(4);

						
					}
					if(CPA%1==0)
					{
						CPA=CPA/1;
					}
					else
					{
						//display 4 value after decimal
						CPA=parseFloat(Math.round(CPA * 100) / 100).toFixed(4);

						
					}
					if(EPC%1==0)
					{
						EPC=EPC/1;
					}
					else
					{
						
						//display 4 value after decimal
						EPC=parseFloat(Math.round(EPC * 100) / 100).toFixed(4);

					}*/
						 rows1+="<tr class='crm-darkgrey'><th scope='row'>TOTAL</th><td>"+clicks+"</td><td>"+conversions+"</td><td>"+CPA+"</td><td>"+ CR+"%</td><td>"+EPC+"</td></tr>";
					}
					$("#table").append(rows1);
				
				
				},
				error:function(){
                    
				}            
			}); 
				
			},
			error:function(){
				
                    
            }           		 
        }); 
		}
		else
		{
			alert('Please select countries');
		}
		
	});
	
	
	

	
	

});

	