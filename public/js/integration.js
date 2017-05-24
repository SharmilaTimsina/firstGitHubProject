$(document).ready(function() {
    var table = $('#tableAgregators').DataTable();
	var table2 = $('#tableSources').DataTable();
	var table3 = $('#tableUsers').DataTable();
	var table4 = $('#tableDomains').DataTable();
	var table5 = $('#tableMClick').DataTable();
	var table6 = $('#tableRisqIq').DataTable();
	var table7 = $('#tableUsersMainstream').DataTable();
	
	$("body").on("click", ".modalIcon", function() {
		var name = $(this).closest("tr").find("#nameAgregator").text();    
		var id = $(this).closest("tr").find("#id").text();    
		var trackParameter = $(this).closest("tr").find("#trackParameter").text();    
		var sinfo = $(this).closest("tr").find("#sinfo").text(); 		
		var custom_url = $(this).closest("tr").find("#custom_url").text(); 	
		var currency = $(this).closest("tr").find("#currency").text(); 	
		var currencyParam = $(this).closest("tr").find("#currencyParam").text(); 	
		var payoutParam = $(this).closest("tr").find("#payoutParam").text(); 	
		
		$("#idModal").val(id);
		$("#nameModal").val(name);
		$("#trackingparameterModal").val(trackParameter);
		$("#sinfoModal").val(sinfo);
		$("#customurlModal").val(custom_url);
		$("#currencyModal").val(currency);
		$("#currencyParamModal").val(currencyParam);
		$("#payoutParamModal").val(payoutParam);
	});

	$("body").on("click", "#saveAgregator", function() {
		
		if($("#payoutParamModal").val() != '') {
			if($("#currencyModal").val() == "" && $("#currencyParamModal").val() == "") {
				alert("Please insert the 'Currency' OR 'Currency Param'");
			} else if($("#currencyModal").val() != "" && $("#currencyParamModal").val() != "")  {
				alert("Please insert only 'Currency' OR 'Currency Param'");
			} else {
				postInfo();
			}
		} else if($("#payoutParamModal").val() == '' && ($("#currencyModal").val() != "" || $("#currencyParamModal").val() != "")) {
			alert("Please insert the 'Payout Param'");
		} else {
			postInfo();
		}
		
	});
	
	function postInfo() {
		var $inputs = $('#formAgregatorEdit :input');
		var values = {};
		$inputs.each(function() {
			if($(this).val() != "") 
				values[this.name] = $(this).val();	
			else  
				values[this.name] = null;
		});
		
		$.ajax({
			url: '/integration/save_agregator',
			type: 'POST',
			data: values,
			crossDomain: true,
			dataType: 'text',
			success: function(data){
				
				table.destroy();
				
				$("#tbodyAgre").empty();
				
				$("#tbodyAgre").html(data);
				
				table = $('#tableAgregators').DataTable();
		
				$("#openmodalsave").click();
				$("#closeModal33").click();
				
				setTimeout(function(){ $("#close2modal").click();; }, 1000);
			},
			error: function(){
				alert("Please try again.");
			}
		});
	}
	
	$("body").on("click", ".modalIcon2", function() {
		var name = $(this).closest("tr").find("#nameSource").text();    
		var id = $(this).closest("tr").find("#idSource").text();  
		var externalParam = $(this).closest("tr").find("#externalParamSource").text();  		
				
		$("#idModalSource").val(id);
		$("#nameModalSource").val(name);
		$("#externalParamModalSource").val(externalParam);
	});
	
	$("body").on("click", "#saveSource", function() {
		
		var $inputs = $('#formSourceEdit :input');
		var values = {};
		$inputs.each(function() {
			if($(this).val() != "") 
				values[this.name] = $(this).val();	
			else  
				values[this.name] = "null";
		});
		
		$.ajax({
			url: '/integration/save_source',
			type: 'POST',
			data: values,
			dataType: 'text',
			success: function(data){
				
				table2.destroy();
				
				$("#tbodySourc").empty();
				
				$("#tbodySourc").html(data);
				
				table2 = $('#tableSources').DataTable();
		
				$("#openmodalsaveSource").click();
				$("#closeModalSource").click();
				
				setTimeout(function(){ $("#close2modal").click();; }, 1000);
			},
			error: function(){
				alert("Please try again.");
			}
		});
	});
	
	$("body").on("click", "#downloadReportConversions", function() {
		
		var values = {};
		values["yearAndMonth"] = $("#convPeriod").val();	
		values["startDay"] = $("#convPeriod2").val();	
		values["endDay"] = $("#convPeriod3").val();	
		values["duplicate"] = $("#duplicateCheckBox").is(":checked");
		values["agregator"] = $("#selectboxAgregator").val();	
	
		if($("#convPeriod").val() != "" && $("#convPeriod2").val() != "" && $("#convPeriod3").val() != "" && $("#selectboxAgregator").val() != null)
			location.assign('/integration/report_conversion?' + jQuery.param(values));
		else
			alert("Please fill all the options.");
	});
	
	$("body").on("click", ".modalIcon3", function() {
		var name = $(this).closest("tr").find("#nameUser").text();    
		var countries = $(this).closest("tr").find("#countriesUser").text();    
		var sources = $(this).closest("tr").find("#sourcesUser").text();   		
				
		$("#usernameModalUser").val(name);
		$("#countriesModalUser").val(countries);
		$("#sourcesModalUser").val(sources);
	});
	
	$("body").on("click", "#saveUser", function() {
		
		var $inputs = $('#formUserEdit :input');
		var values = {};
		$inputs.each(function() {
			if($(this).val() != "") 
				values[this.name] = $(this).val();	
			else  
				values[this.name] = "";
		});
		
		$.ajax({
			url: '/integration/save_user',
			type: 'POST',
			data: values,
			dataType: 'text',
			success: function(data){
				
				table3.destroy();
				
				$("#tbodyUsers").empty();
				
				$("#tbodyUsers").html(data);
				
				table3 = $('#tableUsers').DataTable();
		
				$("#openmodalsaveUser").click();
				$("#closeModalUser").click();
				
				setTimeout(function(){ $("#close2modal").click();; }, 1000);
			},
			error: function(){
				alert("Please try again.");
			}
		});
	});
	
	
	var idDomain = 0;
	$("body").on("click", ".modalIcon4", function() {
		var domain = $(this).closest("tr").find("#domainDomain").text();    
		var countries = $(this).closest("tr").find("#countriesDomain").text();    
		var sources = $(this).closest("tr").find("#sourcesDomain").text();   
		var facebookpageID = $(this).closest("tr").find("#facebookPageIdDomain").text();   			
		idDomain = $(this).closest("tr").attr('idDomain');
			
		$("#domainModal").val(domain);
		$("#countriesModalDomain").val(countries);
		$("#sourcesModalDomain").val(sources);
		$("#facebookpageModalDomain").val(facebookpageID);
	});
	
	$("body").on("click", "#saveDomain", function() {
		
		var $inputs = $('#formDomainEdit :input');
		var values = {};
		$inputs.each(function() {
			if($(this).val() != "") 
				values[this.name] = $(this).val();	
			else  
				values[this.name] = "";
		});
		
		values['id'] = idDomain;	
		
		$.ajax({
			url: '/integration/save_domain',
			type: 'POST',
			data: values,
			dataType: 'text',
			success: function(data){
				
				table4.destroy();
				
				$("#tbodyDomains").empty();
				
				$("#tbodyDomains").html(data);
				
				table4 = $('#tableDomains').DataTable();
		
				$("#openmodalsave").click();
				$("#closeModalDomain").click();
				
				setTimeout(function(){ $("#close2modal").click();; }, 1000);
			},
			error: function(){
				alert("Please try again.");
			}
		});
	});
	
	var idDomainDelete = 0;
	$("body").on("click", ".modalIcon44", function() {
		idDomainDelete = $(this).closest("tr").attr('idDomain');
	});
	
	$("body").on("click", "#deleteDomainButton", function() {
		
		var values = {};
		values['id'] = idDomainDelete;	
		
		$.ajax({
			url: '/integration/delete_domain',
			type: 'POST',
			data: values,
			dataType: 'text',
			success: function(data){
				
				table4.destroy();
				
				$("#tbodyDomains").empty();
				
				$("#tbodyDomains").html(data);
				
				table4 = $('#tableDomains').DataTable();
		
				$("#openmodalsave").click();
				$("#closeModalDeleteDomain").click();
				
				setTimeout(function(){ $("#close2modal").click();; }, 1000);
			},
			error: function(){
				alert("Please try again.");
			}
		});
	});
	
	
	$("body").on("click", "#savenewDomain", function() {
		
		var $inputs = $('#formDomainNew :input');
		var values = {};
		$inputs.each(function() {
			if($(this).val() != "") 
				values[this.name] = $(this).val();	
			else  
				values[this.name] = "";
		});
		
		$.ajax({
			url: '/integration/new_domain',
			type: 'POST',
			data: values,
			dataType: 'text',
			success: function(data){
				
				table4.destroy();
				
				$("#tbodyDomains").empty();
				
				$("#tbodyDomains").html(data);
				
				table4 = $('#tableDomains').DataTable();
		
				$("#openmodalsave").click();
				$("#closeModalnewDomain").click();
				
				setTimeout(function(){ $("#close2modal").click();; }, 1000);
			},
			error: function(){
				alert("Please try again.");
			}
		});
	});
	
	$("body").on("click", "#setEdit2", function() {

		if($("#editClickDescription").val() != '' && $("#editClickSearch").val() != '' && $("#editClickSearch").val().length > 5 ) {
			$("#setEdit").click();

			var values = {};
			values["editClickDescription"] = $("#editClickDescription").val();	
			values["editClickLPSearch"] = $("#editClickSearch").val();	
			
			$("#descriptionModal").text($("#editClickDescription").val());
			$("#lpsearchModal").text($("#editClickSearch").val());
			
			$.ajax({
				url: '/integration/get_clicksInfo',
				type: 'POST',
				data: values,
				dataType: 'text',
				success: function(data){
					var json = $.parseJSON(data);
					
					$("#numberLinesAffected").text(json[0].numberLines);
					$("#confirmEditModal").attr("disabled", false);
					
					
				},
				error: function(){
					alert("Please try again.");
				}
			});
		} else {
			alert('error');
		}
	});
	
	
	$("body").on("click", "#confirmEditModal", function() {

		var values = {};
		values["editClickDescription"] = $("#editClickDescription").val();	
		values["editClickLPSearch"] = $("#editClickSearch").val();	
		
		$("#confirmEditModal").attr("disabled", true);

		$.ajax({
			url: '/integration/setEditClicks',
			type: 'POST',
			data: values,
			dataType: 'text',
			success: function(data){
				
				table5.destroy();
				
				$("#tbodyMClick").empty();
				
				$("#tbodyMClick").html(data);
				
				table5 = $('#tableMClick').DataTable();
		
				$("#openmodalsave").click();
				$("#closeModalConfirmEdit").click();
				$("#confirmEditModal").attr("disabled", false);
				
				setTimeout(function(){ $("#close2modal").click();; }, 1000);
				
			},
			error: function(){
				alert("Please try again.");
			}
		});
	});
	
	
	
	
	var idGroup = 0;
	$("body").on("click", ".modalIcon5", function() {
		var description = $(this).closest("tr").find("#descriptionMultiClick").text();    
		var search = $(this).closest("tr").find("#searchMultiClick").text();    
		var date = $(this).closest("tr").find("#dateMultiClick").text();   		
		
		idGroup = $(this).closest("tr").attr('idgroup');
		
		$("#descriptionModalReverse").text(description);
		$("#lpsearchModalReverse").text(search);
		$("#dateModalReverse").text(date);
		
	});
	
	$("body").on("click", "#confirmReverseModal", function() {

		var values = {};
		
		values['id'] = idGroup;	
		
		$.ajax({
			url: '/integration/reverse_multiclick',
			type: 'POST',
			data: values,
			dataType: 'text',
			success: function(data){
				
				table5.destroy();
				
				$("#tbodyMClick").empty();
				
				$("#tbodyMClick").html(data);
				
				table5 = $('#tableMClick').DataTable();
		
				$("#openmodalsave").click();
				$("#closeModal").click();
				
				setTimeout(function(){ $("#close2modal").click();; }, 1000);
			},
			error: function(){
				alert("Please try again.");
			}
		});
		
	});
	
	
	
	$("body").on("click", "#submitTest", function() {
		
		if($('#urlTestRisq').val() != "" && $('#testNameRisq').val() != "") {
		
			var values = {};
		
			values['urlTestRisk'] = $("#urlTestRisq").val();	
			values['nameTestRisk'] = $('#testNameRisq').val();	
		
			$.ajax({
				url: '/integration/save_risqiq',
				type: 'POST',
				data: values,
				dataType: 'text',
				success: function(data){
					
					$("#openmodalsaveUser").click();
					
					setTimeout(function(){ $("#close2modal").click();; }, 1000);
				},
				error: function(){
					alert("Please try again.");
				}
			});
		}
	});
		
		
	var idRisqIqDelete = 0;
	$("body").on("click", ".modalIcon77", function() {
		idRisqIqDelete = $(this).closest("tr").attr('idRisqIq');
	});	
		
	$("body").on("click", "#deleteRisqIqButton", function() {
		
		var values = {};
		values['hash'] = idRisqIqDelete;	
		
		$.ajax({
			url: '/integration/delete_risqiq',
			type: 'POST',
			data: values,
			dataType: 'text',
			success: function(data){
				
				table6.destroy();
				
				$("#tbodyRisqIq").empty();
				
				$("#tbodyRisqIq").html(data);
				
				table6 = $('#tableRisqIq').DataTable();
		
				$("#openmodalsave").click();
				$("#closeModalDeleteRisqIq").click();
				
				setTimeout(function(){ $("#close2modal").click();; }, 1000);
			},
			error: function(){
				alert("Please try again.");
			}
		});
	});
	
	
	
	
});