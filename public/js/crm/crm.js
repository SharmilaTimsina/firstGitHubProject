var arrayDates = [];
var arrayClicks = [];
var arrayConversions = [];
var arrayCR = [];
var arrayCPA = [];

var chart = null; 
$(document).ready(function() {
	$("#selectboxClients").val('');
		
	$("body").on("click", "#createClientButton", function() {
		$("#rowInfoAggr").show();
		$("#rowchartAggr").hide();
		$("#createNow").show();
		$("#editNow").hide();
		$("#rowchartAggr").hide();
		
		$("#createeditform").find("input[type=text], textarea, select").val("");
		$("#selectboxClients").val('');
		
		
		$("#status2").hide();
	});
	

	
	var form = $("#createeditform");
	$(form).validate({
		rules: {
			amanagername: "required",
			selectboxClientsIds: "required",
			aggrskype: "required",
			workemail: "required",
			selectboxClientsAskfornumbers: "required",
			selectboxClientsState: "required",
			agglanguage: "required",
			aggstatus: "required",
			aggnotes: "required"
		}
	});

	function submitForm(type) {
	
		
		if(type == 1) {
			strfinal = './crm/createClient'
		} else if(type == 2) {
			strfinal = './crm/editClient'
		}
		
		if (form.valid()) {

			$("#status2").show();	
			$("#rowInfoAggr").hide();
		
			var formData = new FormData($("#createeditform")[0]);
			formData.append('aggname', $("#selectboxClientsIds option:selected").text());
			
			if(type == 2) {
				formData.append('idTable', idforedit);
			}
			
			$.ajax({
				url: strfinal,
				type: 'POST',
				data: formData,
				async: true,
				success: function(data) {
					
					if(type == 1) {
						alert('Create Complete');
					} else if(type == 2) {
						alert('Edit Complete');
					}
					
					
				},
				error: function(response) {
					alert("error");
				},
				cache: false,
				contentType: false,
				processData: false
			});
			
		} else {
			alert("Fill all fields");
		}
		
		$(document).ajaxStop(function() {
			$("#status2").hide();
			$("#rowInfoAggr").show();
			
			if(type == 1) {
				updateClientsSelectBox();
			}
			
		});
	}
	
	
	$('#selectboxClientsIds').on('change', function() {
		$("#aggrname").val($(this).val());
	});
	
	var type = 0;
	$('#createNow').on('click', function(event) {
		event.preventDefault();
		
		//updateClientsSelectBox();
		
		type = 1;
		submitForm(1);
	});
	
	$('#editNow').on('click', function(event) {
		event.preventDefault();
		type = 2;
		
		submitForm(2);
	});
	
	function updateClientsSelectBox() {
		
		$.ajax({
			url: './getClients',
			type: 'GET',
			async: true,
			success: function(data) {
				
				$("#selectboxClients").empty();
				
				$("#selectboxClients").html(data);
				$("#selectboxClients").val('');
		

			},
			error: function(response) {
				alert("error");
			},
			cache: false,
			contentType: false,
			processData: false
		});

	}
	
		/*
	$("body").on("click", "#loadButton", function() {
		
		
		$("#createeditform").find("input[type=text], textarea, select").val("");
		
		var formData = new FormData();
		formData.append('id' , $("#selectboxClientsIds").val());
		
		$.ajax({
			url: './getClient',
			type: 'POST',
			data: formData,
			async: true,
			success: function(data) {
				
			},
			error: function(response) {
				alert("error");
			},
			cache: false,
			contentType: false,
			processData: false
		});
		
		
		$(document).ajaxStop(function() {
			
			if(type == 1) {
				$("#status2").hide();
			} else if(type == 2) {
				$("#status2").hide();
				$("#rowInfoAggr").show();
				$("#rowchartAggr").show();
			}
			
			
		});
		
		
	});
	*/
	
	var idforedit = 0;
	$('#loadButton').on('click', function() {
		
		if(chart != null)
			chart.destroy();
		
		if($("#selectboxClients").val() != '' && $("#selectboxClients").val() != null) {
		
			$('#loadButton').attr("disabled", true);
			
			type = 2;
			
			$("#status2").show();
			
			$("#rowInfoAggr").hide();
			$("#rowchartAggr").hide();
			$("#createNow").hide();
			$("#editNow").show();
			
			var formData = new FormData();
			formData.append('idAgg', $("#selectboxClients option:selected").val());
			
			dashboardChart($("#selectboxClients option:selected").val());
			
			$.ajax({
				url: './crm/getClient',
				type: 'POST',
				data: formData,
				async: true,
				success: function(data) {
					
					var json = $.parseJSON(data);
					
					idforedit = json[0]['id'];
					
					$("#amanagername").val(json[0]['amanagerName']);
					$("#selectboxClientsIds").val(json[0]['idAgregator']);
					$("#aggrname").val(json[0]['aggregatorName']);
					$("#aggrskype").val(json[0]['skype']);
					$("#workemail").val(json[0]['workEmail']);
					$("#selectboxClientsAskfornumbers").val(json[0]['askForNumbers']);
					$("#selectboxClientsState").val(json[0]['stateAggr']);
					$("#agglanguage").val(json[0]['language']);
					$("#aggstatus").val(json[0]['status']);
					$("#aggnotes").val(json[0]['notes']);
					
					$('#loadButton').attr("disabled", false);

				},
				error: function(response) {
					alert("error");
					$('#loadButton').attr("disabled", false);
				},
				cache: false,
				contentType: false,
				processData: false
			});
			
			$(document).ajaxStop(function() {
				$('#loadButton').attr("disabled", false);
				
				if(type == 1) {
					$("#status2").hide();
				} else if(type == 2) {
					$("#status2").hide();
					$("#rowInfoAggr").show();
					$("#rowchartAggr").show();
				}
				
				
			});
			
		} else {
			alert('Select aggregator');
		}
	});
	
	
	

	
	function dashboardChart(id) {	
		
		//chart
		$.ajax({
			url: "./crm/last7Days?id=" + id,
			dataType: "text",
			async: true,
			success: function(data) {
		
				var json = $.parseJSON(data);
						
				for (var i = 0; i < json[0].length; i++) { 
					//chart
					if(arrayDates.length <= 8) {	
						var contentClicks = json[0][i].clicks;	
						var contentConversions = json[0][i].conversions;	
						var contentCR = json[0][i].cr;	
						var contentCPA = json[0][i].revenue;	
						var date = json[0][i].date;
						
						var resDate = '';
						if(date != null)
							resDate = date.substring(5, 10);
						
						arrayDates.push(resDate);
						arrayClicks.push(contentClicks);
						arrayConversions.push(contentConversions);
						arrayCR.push(Number(contentCR).toFixed(2));
						arrayCPA.push(contentCPA);	
			
					}				
				}
			
				$.getScript("http://mobisteinreport.com/js/crm/chartDashboard.js", function(){
					chartDash('REVENUE');
				});
				
				
				$("#r3c1").text(json[1][0].pshift['revenue'] + "%");
				$("#r3c2").text(json[1][0].pshift['clicks'] + "%");
				$("#r3c3").text(json[1][0].pshift['epc'] + "%");
				
				$("#r1c1").text(json[1][0].today['revenue']);
				$("#r1c2").text(json[1][0].today['clicks']);
				$("#r1c3").text(json[1][0].today['epc']);
				
				$("#r2c1").text(json[1][0].last3days['revenue']);
				$("#r2c2").text(json[1][0].last3days['clicks']);
				$("#r2c3").text(json[1][0].last3days['epc']);
				
			}
		});	
		
		
		$(document).ajaxStop(function() {
			$('.greybox').show();
			$('.a').show();
		});
	
	}
		
});






 