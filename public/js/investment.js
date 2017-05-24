$(document).ready(function() {
	
	$("body").on("click", "#buttonFilter", function() {
		
		currentLimit = 0;
		search = '';
		$("#searchInput").val('');
		totalPages = 0;
		numPage = 1;
		col = 1;
		order = 'ASC';
				
		tableContent();
	});
	
	function tableContent() {
		
		
		$("#theadtable").empty();
		$("#tbodytable").empty();
		$("#tfoottable").empty();
		
		$(document).one("ajaxStart", function() {
			$("#spin").show();
			$("#buttonPreviousPage").attr('disabled', true);
			$("#buttonNextPage").attr('disabled', true);
		});
		
		/*
		var country = $("#countryF option:selected").val();
		var source = $("#sourceF option:selected").val();
		var os = $("#selectboxOS option:selected").val();
		var platform = $("#selectboxPlatform option:selected").val();
		var campaign = $("#campaignF option:selected").val();
		var date = $("#convPeriod2").val();
		var domain = $("#domainF option:selected").val();
		var agregation = getSumoSelects("#selectboxMulti", 3);
		*/

		var country = getSumoSelects("#countryF", 3);
		var source = getSumoSelects("#sourceF", 3);
		var os = getSumoSelects("#selectboxOS", 3);
		var platform = getSumoSelects("#selectboxPlatform", 3);
		//var campaign = $("#campaignF option:selected").val();
		var date = $("#convPeriod2").val();
		//var domain = $("#domainF option:selected").val(); 
		var agregation = getSumoSelects("#selectboxMulti", 3);
		var gender = getSumoSelects("#selectboxGender", 3);

		var values = {};
		values['country'] = country;
		values['source'] = source;
		values['os'] = os;
		values['platform'] = platform;
		values['gender'] = gender;
		//values['campaign'] = campaign;
		//values['domain'] = domain;
		values['agregation'] = agregation;
		values['sdate'] = date.substr(0, 10);
		values['edate'] = date.substr(13, 23);
		
		values['currentLimit'] = currentLimit;
		values['search'] = search;
		values['col'] = col;
		values['order'] = order;
		
		
		$.ajax({
			url: '/invest/setfilter',
			type: 'POST',
			data: values,
			crossDomain: true,
			dataType: 'text',
			success: function(data){
			
				var json = $.parseJSON(data);
			
				$("#theadtable").html(json['tableHead']);
				$("#tbodytable").html(json['tableBody']);
				$("#totalPages").text(json['totalPages']);
				$("#currentPage").text(numPage);
				
				totalPages = json['totalPages'];
		
				
				checkColors();
			},
			error: function(){
				alert("Please try again.");
			}
		});
		
		$(document).one("ajaxStop", function() {
			$("#spin").hide();
			
			if(numPage == totalPages) {
				$("#buttonNextPage").attr('disabled', true);
			} else {
				$("#buttonNextPage").attr('disabled', false);
			}
			
			if(numPage == 1) {
				$("#buttonPreviousPage").attr('disabled', true);
			} else {
				$("#buttonPreviousPage").attr('disabled', false);
			}
				
			tablefoot(values);
						
		});
	}
	
	function tablefoot(values) {
		$.ajax({
			url: '/invest/totalsRow',
			type: 'POST',
			data: values,
			crossDomain: true,
			dataType: 'text',
			success: function(data){
			
				var json = $.parseJSON(data);
			
				$("#tfoottable").html(json['tfoot']);
			},
			error: function(){
				alert("Please try again.");
			}
		});
	}
	
	function checkColors() {
		$("table thead tr td").each(function () {
			var col = $(this).text();
			var colN = $(this).parent().children().index($(this));
			colN = colN + 1;
			
			if(col === "Margin") {
				$("#tableTable tbody tr td:nth-child(" + colN + ")").each(function () {
					if( parseInt($(this).text()) > 0) {
						$(this).css("color", "green");
					} else {
						$(this).css("color", "red");
					}
				});
			} else if(col === "ROI") {
				$("#tableTable tbody tr td:nth-child(" + colN + ")").each(function () {
					if( parseInt($(this).text()) > 0 &&  parseInt($(this).text()) < 50) {
						$(this).css("color", "#ff9900");
					} else if ( parseInt($(this).text()) > 50) {
						$(this).css("color", "#008000");
					} else if ( parseInt($(this).text()) < 0 && parseInt($(this).text()) > -10 ) {
						$(this).css("color", "red");
					} else  {
						$(this).css("color", "red");
					}
				});
			}
		});
	}
	
	/*
	$('#countryF').on('change', function() {
		
		var values = {};
		values['country'] = $("#countryF option:selected").val()
		
		$.ajax({
			url: '/invest/campaigns',
			type: 'POST',
			data: values,
			crossDomain: true,
			dataType: 'text',
			success: function(data){
				
				$("#campaignF").empty();
				
				$("#campaignF").html('<option value="">Select</option>' + data);
				
		
			},
			error: function(){
				alert("Please try again.");
			}
		});
	});
	*/
	
	var currentLimit = 0;
	var totalPages = 0;
	var numPage = 1;
	var maxPage;
	$("body").on("click", "#buttonPreviousPage", function() {
		
		if(numPage != 1) {
			numPage--;
			currentLimit = currentLimit - 30;
			$("#currentPage").text(numPage);
			$("#buttonPreviousPage").attr('disabled', false);
			
			tableContent();
		} else {
			$("#buttonPreviousPage").attr('disabled', true);
		}
		
	});
	
	$("body").on("click", "#buttonNextPage", function() {
		if(numPage <= totalPages) {
			numPage++;	
			currentLimit = currentLimit + 30;
			
			$("#currentPage").text(numPage);
			$("#buttonPreviousPage").attr('disabled', false);
		
			
			tableContent();
		} else {
			$("#buttonNextPage").attr('disabled', true);
		}
	});
	
	var search = null;
	$("body").on("click", "#buttonSearch", function() {
		
		search = $("#searchInput").val();
		currentLimit = 0;
		col = 1;
		order = 'ASC';
		
		
		tableContent();	
	});
	
	var col = 1;
	var order = 'ASC';
	$("body").on("click", ".headsort", function() {
		col = $(this).attr('col');
		previousOrder = order;
		
		if(previousOrder == "ASC") {
			order = "DESC";
			$(this).attr('order', 'DESC');
		} else {
			order = "ASC";
			$(this).attr('order', 'ASC');
		}
		
		tableContent();	
	});

	$("body").on("click", "#buttonDownloadReportExcel", function() {
		col = 1;
		order = "ASC";
		
		/*
		var country = $("#countryF option:selected").val();
		var source = $("#sourceF option:selected").val();
		var campaign = $("#campaignF option:selected").val();
		var date = $("#convPeriod2").val();
		var domain = $("#domainF option:selected").val();
		var agregation = getSumoSelects("#selectboxMulti", 3);
		var os = $("#selectboxOS option:selected").val();
		var platform = $("#selectboxPlatform option:selected").val();
		*/

		var country = getSumoSelects("#countryF", 3);
		var source = getSumoSelects("#sourceF", 3);
		var os = getSumoSelects("#selectboxOS", 3);
		var platform = getSumoSelects("#selectboxPlatform", 3);
		//var campaign = $("#campaignF option:selected").val();
		var date = $("#convPeriod2").val();
		//var domain = $("#domainF option:selected").val(); 
		var agregation = getSumoSelects("#selectboxMulti", 3);
		var gender = getSumoSelects("#selectboxGender", 3);
		
		var values = {};
		values['os'] = os;
		values['platform'] = platform;
		values['country'] = country;
		values['source'] = source;
		//values['campaign'] = campaign;
		//values['domain'] = domain;
		values['agregation'] = agregation;
		values['sdate'] = date.substr(0, 10);
		values['edate'] = date.substr(13, 23);
		values['gender'] = gender;
		
		values['search'] = search;
		values['col'] = col;
		values['order'] = order;
		
		$.ajax({
			url: '/invest/getReportExcel',
			type: 'POST',
			data: values,
			crossDomain: true,
			dataType: 'text',
			success: function(data){
				var filename = moment().toString();
				var blob = new Blob([data], {type: "text/plain;charset=utf-8"});
				saveAs(blob, filename+".csv");
			},
			error: function(){
				alert("Please try again.");
			}
		});
	});




	$("body").on("click", "#downloadReportInvest", function() {
		col = 1;
		order = "ASC";
		
		/*
		var country = $("#countryF option:selected").val();
		var source = $("#sourceF option:selected").val();
		var campaign = $("#campaignF option:selected").val();
		var date = $("#convPeriod2").val();
		var domain = $("#domainF option:selected").val();
		var agregation = getSumoSelects("#selectboxMulti", 3);
		var os = $("#selectboxOS option:selected").val();
		var platform = $("#selectboxPlatform option:selected").val();
		*/

		var sources = getSumoSelects("#sourcesMultipleReport", 1);
		var sdate = $("#sdate").val();
		var edate = $("#edate").val();

		var values = {};
		values['sources'] = sources;
		values['edate'] = edate;
		values['sdate'] = sdate;
		
		$.ajax({
			url: '/invest/report',
			type: 'POST',
			data: values,
			crossDomain: true,
			dataType: 'text',
			success: function(data){
				var filename = moment().toString();
				var blob = new Blob([data], {type: "text/plain;charset=utf-8"});
				saveAs(blob, filename+".csv");
			},
			error: function(){
				alert("Please try again.");
			}
			
		});
	});
	
	
});