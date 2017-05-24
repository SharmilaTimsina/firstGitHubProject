
$(document).ready(function() {
	var table = $('#tableCampaignBid').DataTable( {
	  "pageLength": 100,
	  "order": [[ 1, "asc" ]]
	});
		
	var form = $("#addCampaignForm");
	$(form).validate({
		rules: {
			campaignid: "required"
		}
	});

	var idtable = 0;
	function submitForm(url, type) {
		//event.preventDefault();

		if (form.valid()) {

			var formData = new FormData(form[0]);
			
			if(type != 1) {
				formData.append('idcampaign', idtable);
			}

			if (type == 1 || type == 2) {	
				upOnly = 0;
				if($('#Uponly').is(':checked')) {
					upOnly = 1;
				}
				formData.append('uponly', upOnly);
			}
			
			$(document).ajaxStart(function() {
				$('.savingEdit').css("display", "inline-block");
				$("#addcampaignButtonModal").attr('disabled', true);
			});
			
			$.ajax({
				url: url,
				type: 'POST',
				data: formData,
				async: true,
				success: function(data) {
					refreshTable(data);
					alert('Complete');
					
				},
				error: function(response) {
					alert("Error");
				},
				cache: false,
				contentType: false,
				processData: false
				
			});
			
		} else {
			alert("Fill all fields");
		}
		
		$(document).ajaxStop(function() {
			$('.savingEdit').css("display", "none");
			$("#closemodal").click();
			$("#addcampaignButtonModal").attr('disabled', false);
		});
	}
	
	$("body").on("click", ".buttonsmodals", function() {
		var type = $(this).attr('typeButton')
		
		var url = '';
		if(type == 1) {
			url = './addcampaign'
		} else if(type == 2) {
			url = './editcampaign'
		} else if(type == 3) {
			url = './deletecampaign'
		}
		
		submitForm(url, type);
		
		//console.log(type);
		//console.log(url);
	});
	
	$("body").on("click", "#buttonrefresh", function() {
		$.ajax({
			url: './refreshTable',
			type: 'GET',
			async: true,
			success: function(data) {
				refreshTable(data);
			},
			error: function(response) {
				alert("Error");
			},
			cache: false,
			contentType: false,
			processData: false
		});
		
	});
	
	function refreshTable(data) {
		table.destroy();

		$("#tbodyCamp").empty();
	
		$("#tbodyCamp").html(data);
	
		table = $('#tableCampaignBid').DataTable( {
		  "pageLength": 100,
			"order": [[ 1, "asc" ]]
		});
	}
	
	
	$("body").on("click", ".modalIcon", function() {
		$(".editcampaign").show();
		$(".addcampaign").hide();
		
		$("input[name=campaignName]").attr('disabled', true);
		$("input[name=maxbid]").attr('disabled', false);
		$("#selectboxAccounts").attr('disabled', true);
		$("input[name=campaignid]").attr('disabled', true);
		
		$("input[name=campaignid]").css('display', 'block');
		$("#spancampaignid").css('display', 'block');
		
		$("input[name=campaignid]").val('');
		$("input[name=maxbid]").val('');
		$("input[name=campaignName]").val('');
	
		$("#selectboxAccounts").attr('selected', false);
	
		var maxbid = $(this).closest("tr").find("#maxbid").text();    
		var campaignid = $(this).closest("tr").find("#campaignid").text();    
		var campaignName = $(this).closest("tr").find("#campaignName").text();    
		var sourceid = $(this).closest("tr").attr("sourceid");
		var upOnly = $(this).closest("tr").attr("uponly");
			
		idtable = $(this).closest("tr").attr('idcam');    

		$("input[name=campaignid]").val(campaignid);
		$("input[name=campaignName]").val(campaignName);
		$("input[name=maxbid]").val(maxbid);
		
		if(upOnly == 0) {
			$('#Uponly').prop('checked', false);
		} else {
			$('#Uponly').prop('checked', true);
		}

		$("#selectboxAccounts option").removeAttr("selected");
		
		$('#selectboxAccounts option[value=' + sourceid + ']').attr('selected','selected');
		
		$('#selectboxAccounts').val(sourceid);
		
	});
	
	
	$("body").on("click", "#addCampaignButtonOpenModal", function() {
		$(".editcampaign").hide();
		$(".addcampaign").show();
		
		$("input[name=campaignid]").val('');
		$("input[name=maxbid]").val('');
		
		
		$("input[name=campaignName]").attr('disabled', true);
		$("input[name=campaignid]").attr('disabled', false);
		$("input[name=maxbid]").attr('disabled', false);
		$("#selectboxAccounts").attr('disabled', false);
		
		$("input[name=campaignid]").css('display', 'block');
		$("#spancampaignid").css('display', 'none');
		
		$('#Uponly').prop('checked', false);
	});
	
	
	$("body").on("click", "#addAccountButtonModal", function() {

		var formData = new FormData();
		formData.append('email', $("#emailAccount").val());
		formData.append('password', $("#passwordAccount").val());
		formData.append('users', getSumoSelects("#selectboxUsers", 1));
		
		$.ajax({
			url: './addAccount',
			type: 'POST',
			data: formData,
			async: true,
			success: function(data) {
				alert("Complete")
				$("#closeModal2").click();
				
				$("#accounts").empty();
				$("#accounts").html(data);
				
				$("#emailAccount").val('')
				$("#passwordAccount").val('')
				$('select.search-box-sel-all')[0].sumo.unSelectAll();
		
			},
			error: function(response) {
				alert("Error");
			},
			cache: false,
			contentType: false,
			processData: false
		});
	});

	$("body").on("click", "#resetPasswordButtonModal", function() {
		
		var formData = new FormData();
		formData.append('account', $("#accountsReset").val());
		formData.append('password', $("#passwordAccountReset").val());
		
		
		$.ajax({
			url: './resetpass',
			type: 'POST',
			data: formData,
			async: true,
			success: function(data) {
				alert("Complete")
				$("#closeModal3").click();
				
				$("#accountsReset").val('')
				$("#passwordAccountReset").val('')
		
			},
			error: function(response) {
				alert("Error");
			},
			cache: false,
			contentType: false,
			processData: false
		});
	});
	
	window.searchSelAll = $('.search-box-sel-all').SumoSelect({ csvDispCount: 3, selectAll:false, search: true, searchText:'Enter here.', okCancelInMulti:false });
});



 