var table = null;
$(document).ready(function() {
   
	//var table = $('#tableHeadReport').DataTable();
	submitForm(2);
	$(".groupsbyAffiliates").attr('disabled', true);
   
   $(":file").filestyle({
		placeholder: "No files",
		buttonText: "Choose file"
	});
	
	$('.datepicker').datepicker({
		dateFormat:"yy-mm-dd",
		beforeShowDay: function (date) {
		   //getDate() returns the day (0-31)
		   if (date.getDate() == LastDayOfMonth(date.getFullYear(),date.getMonth()) || date.getDate() == 15) {
			   return [true, ''];
		   }
		   return [false, ''];
		}
	});

	$('#tdateMulti').datepicker({
		dateFormat:"yy-mm-dd",
		beforeShowDay: function (date) {
		   //getDate() returns the day (0-31)
		   if (date.getDate() == LastDayOfMonth(date.getFullYear(),date.getMonth()) || date.getDate() == 15) {
			   return [true, ''];
		   }
		   return [false, ''];
		}
	});
	
	function LastDayOfMonth(Year, Month) {
		return(new Date((new Date(Year, Month+1,1))-1)).getDate();
	}
	
	$('#buttonUploadCsv').on('click', function(e) {
		e.preventDefault();
		
		$(":file").filestyle('disabled', true);
		$("#buttonUploadCsv").attr('disabled', true);
		
		var fileInput = document.getElementById('filestyle-0');
		var file = fileInput.files[0];
		var formData = new FormData();
		formData.append('file', file);
		formData.append('aggname', $("#aggclient option:selected").attr('agname'));
		formData.append('agregator', $("#aggclient option:selected").val());
		formData.append('tdate', $("#tdate").val());
		
		$.ajax({
			url: './freporting/upload',
			type: 'POST',
			data: formData,
			async: true,
			success: function(data) {
				alert('Complete');
				$(":file").filestyle('disabled', false);
				$("#buttonUploadCsv").attr('disabled', false);
			},
			error: function(response) {
				alert("error");
			},
			cache: false,
			contentType: false,
			processData: false
		});
	});



	$('#buttonUploadCsvMultiAgg').on('click', function(e) {
		e.preventDefault();
		
		$(":file").filestyle('disabled', true);
		$("#buttonUploadCsvMultiAgg").attr('disabled', true);
		
		var fileInput = document.getElementById('filestyle-1');
		var file = fileInput.files[0];
		var formData = new FormData();
		formData.append('file', file);
		formData.append('tdate', $("#tdateMulti").val());
		
		$.ajax({
			url: './freporting/uploadMultiAgg',
			type: 'POST',
			data: formData,
			async: true,
			success: function(data) {
				alert('Complete');
				$(":file").filestyle('disabled', false);
				$("#buttonUploadCsvMultiAgg").attr('disabled', false);
			},
			error: function(response) {
				alert("error");
			},
			cache: false,
			contentType: false,
			processData: false
		});
	});
	
	function valid() {
		
		if($("#convPeriod2").val() == '')
			return false;
		
		if(getSumoSelects("#selectCountries", 3).length == 0) 
			return false;
		
		if(getSumoSelects("#selectAccount", 3).length == 0)
			return false;
		
		if(getSumoSelects("#selectAgreg", 3).length == 0)
			return false;
		
		
		return true;
	}

	$('#buttonSetFilter').on('click', function(e) {
		e.preventDefault();
		
		submitForm(2);
	});
	
	
	var buttonClicked = false;
	var currentPage = -1;
	function submitForm(type) {
		
		var types = $('input[name=typeOfInfo]:checked').val();
		
		if(valid()) {
			//var array = [];
			var array_ids = [];
			for (x in $("#formCheckBoxes").serializeArray()) {
				array_ids.push($("#formCheckBoxes").serializeArray()[x].value);
				//array.push($("#formCheckBoxes").serializeArray()[x].name);
			}
			
			var formData = new FormData();
			formData.append('date', $("#convPeriod2").val());		
			formData.append('countries', getSumoSelects("#selectCountries", 3));
			formData.append('accounts', getSumoSelects("#selectAccount", 4));
			formData.append('aggregators', getSumoSelects("#selectAgreg", 3));
			formData.append('groupby', $("#agregatebycountry").is(':checked'));
			formData.append('typeInfo', types);
			//formData.append('checkBox', array);
			formData.append('checkBoxIds', array_ids);

			formData.append('affiliates', $("#affiliatesCheck").is(':checked'));
			formData.append('groupbyaffCheck', $("#groupbyaffCheck").is(':checked'));
			formData.append('groupbymonthCheck', $("#groupbymonthCheck").is(':checked'));
			formData.append('groupbyaffiliateCheck', $("#groupbyaffiliateCheck").is(':checked'));
			formData.append('groupbycountryCheck', $("#agregatebycountry").is(':checked'));
			
			if(type == 2) {
				
				
				if($("#chartJsAtivate").is(":checked") && getSumoSelects("#selectAgreg", 3).length < 20 && $.inArray('ALL', getSumoSelects("#selectAgreg", 3)) == -1) {
					arrayAggregators = getSumoSelects("#selectAgreg", 3);
					runChart(0, true);
				} else if($("#chartJsAtivate").is(":checked") && ($.inArray('ALL', getSumoSelects("#selectAgreg", 3)) > -1 || getSumoSelects("#selectAgreg", 3).length > 20) ) {
					alert('Please consider select 20 aggregators at max.');
					$("#chartJsAtivate").attr("checked", false);
					return;
				} else {
					$("#rowChart").hide();
				}
				
				$("#tableHeadReport").empty();
				
				$.ajax({
					url: './freporting/setFilter',
					type: 'POST',
					data: formData,
					async: true,
					success: function(data) {

						if(typeof table != 'undefined' && table != null)
							table.destroy();
						
						json = $.parseJSON(data);
						
						$('#tableHeadReport').empty();
						
						$("#tableHeadReport").append(json['thead']);
						$("#tableHeadReport").append(json['tbody']);
						$("#tableHeadReport").append(json['tfoot']);
						
						table = $('#tableHeadReport').DataTable();

						if(currentPage != -1) {
							table.page(currentPage).draw(false);
							currentPage = -1;
						}

					
					},
					error: function(response) {
						alert("error");
					},
					cache: false,
					contentType: false,
					processData: false
				});
			} else if(type == 1) {
				$.ajax({
					url: './freporting/downloadCsv',
					type: 'POST',
					data: formData,
					async: true,
					success: function(data) {
						alert("Complete");
						
						var text = data;
						var filename = moment().toString();
						var blob = new Blob([text], {type: "text/plain;charset=utf-8"});
						saveAs(blob, filename+".csv");
					},
					error: function(response) {
						alert("error");
					},
					cache: false,
					contentType: false,
					processData: false
				});
			}
		} else {
			alert("Provide: Date and at least one Country, Agregator and Account!")
		}
	}
	
	var comment = '';
	var agg = '';
	var coun = '';
	var date = '';
	$('body').on('click','.detailsImg', function(e) {
		//$("#textareaDetails").text('');
		$("#textareaDetails").val('');
		
		comment = $(this).attr('c');
		agg = $(this).attr('agg');
		coun = $(this).attr('coun');
		date = $(this).attr('date');
		
		//$("#textareaDetails").text(comment);
		$("#textareaDetails").val(comment);
	});
	
	$('#editDetails').on('click', function(e) {
		currentPage = table.page();

		formData = new FormData();
		formData.append('comment', $("#textareaDetails").val());
		formData.append('agg', agg);
		formData.append('coun', coun);
		formData.append('date', date);
		
		$.ajax({
			url: './freporting/updateComment',
			type: 'POST',
			data: formData,
			async: true,
			success: function(data) {
				alert("Complete");
				submitForm(2);
			},
			error: function(response) {
				alert("error");
			},
			cache: false,
			contentType: false,
			processData: false
		});
	});
	
	
	
	$('#buttonDownloadExcel').on('click', function(e) {
		submitForm(1);
	});
		
	$("#checkAllCheckBox").change(function(){  
		var status = this.checked; 
		$('.checkboxFilter').each(function(){ 
			this.checked = status;
		});
	});

	$('.checkboxFilter').change(function(){ 
		if(this.checked == false){
			$("#checkAllCheckBox")[0].checked = false; 
		}
	});

	$('#affiliatesCheck').change(function() {
		if($(this).is(':checked')) {
			$(".disableIfAffs").attr('disabled', true);
			$("#agregatebycountry").attr('disabled', false);
			$('select#selectAccount')[0].sumo.disable();
			$(".groupsbyAffiliates").attr('disabled', false);

			$.ajax({
				url: './freporting/getAffs',
				type: 'GET',
				async: true,
				success: function(data) {
					var select = $('#selectAgreg');
					select.empty().append(data);

					$('select#selectAgreg')[0].sumo.reload();
					$('select#selectAgreg')[0].sumo.selectAll();
				},
				error: function(response) {
					
				},
				cache: false,
				contentType: false,
				processData: false
			});

		} else {
			$(".disableIfAffs").attr('disabled', false);
			$('select#selectAccount')[0].sumo.enable();
			$(".groupsbyAffiliates").attr('disabled', true);
			
			$.ajax({
				url: './freporting/getAggs',
				type: 'GET',
				async: true,
				success: function(data) {
					var select = $('#selectAgreg');
					select.empty().append(data);

					$('select#selectAgreg')[0].sumo.reload();
					$('select#selectAgreg')[0].sumo.selectAll();
				},
				error: function(response) {
					
				},
				cache: false,
				contentType: false,
				processData: false
			});

		}
	});
	
	
	$(document).ready(function() {
	
	
	});
});
	var arrayAggregators = [];
	var chart = null;	
	var dataChart = '';
	
	var dataSetsTypeZero = [];
	var dataSetsTypeOne = [];
	var labels = [];
	function runChart(type, runReport) {
		
		if(chart != null) {
			chart.destroy();
			dataSetsTypeZero = [];
			dataSetsTypeOne = [];
			labels = [];
		}
				
		var types = $('input[name=typeOfInfo]:checked').val();		
		
		formData = new FormData();
		formData.append('aggreg' , arrayAggregators);
		formData.append('typeInfo' , types);
		
		var state = false;
		if(runReport) {
			$.ajax({
				url: './freporting/getChart',
				type: 'POST',
				data: formData,
				async: true,
				success: function(data) {
					dataChart = data;
					state = processData(data);				
				},
				error: function(response) {
					alert("error");
				},
				cache: false,
				contentType: false,
				processData: false
			});
			
			$(document).one("ajaxStop", function() {
				createChart(type, state);
			});
		} else {
			state = processData(dataChart);	
			
			createChart(type, state);
		}
		
	}
	
	function processData(data) {
		var json = $.parseJSON(data);
		
		
		//cada each é um agregator
		jQuery.each( json, function( i, val ) {
			labels = json['serviceDate'];
			
			dataSetsTypeZero.push({label: json[i]['agregatorid'],
								   fillColor: "transparent",
								   strokeColor: json[i]['color'],
								   pointHighlightFill: json[i]['color'],
								   data: json[i]['totalAmount']});
			
			dataSetsTypeOne.push({label: json[i]['agregatorid'],
				                  fillColor: "transparent",
							      strokeColor: json[i]['color'],
							      pointHighlightFill: json[i]['color'],
							      data: json[i]['difLeads']});
		});
		
		if(json.length == 0) {
			$("#rowChart").hide();
			alert('No results for chart');
			$("#chartJsAtivate").attr("checked", false);
			return false;
		} else {
			return true;
		}
	}
	
	function createChart(type, state) {
		//chart
		var chartDash = document.getElementById('chartDashboard').getContext('2d');
			
		if(type == 0 && state) {
			
			//months = ['1', '2'];
			var data = {
			  labels: labels,
			  datasets: dataSetsTypeZero};

	
			chart = new Chart(chartDash).Line(data, {
				tooltipTemplate: "<%= datasetLabel %> : <%= value + '$'%>",
				multiTooltipTemplate: "<%= datasetLabel %> : <%= value + '$'%>"
			});	
		
			$("#rowChart").show();
		} else if(type == 1 && state) {
		
			var data = {
			  labels: labels,
			  datasets: dataSetsTypeOne};
			  		
			chart = new Chart(chartDash).Line(data, {
				tooltipTemplate: "<%= datasetLabel %> : <%= value + '%'%>",
				multiTooltipTemplate: "<%= datasetLabel %> : <%= value + '%'%>"
			});	
						
		}
		
		if(state)
			$("#rowChart").show();
			
	}

	

	
	
	
	
