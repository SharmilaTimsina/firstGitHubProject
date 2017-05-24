$(document).ready(function() {
		
	var table = $('#tableTicketsUserSide').DataTable({
		"order": [[ 5, 'desc' ], [ 6, 'desc' ]],
		"pageLength": 100
	});
		
	var form = $("#createTicketForm");
	$(form).validate({
		rules: {
			requestName: "required",
			requestDetails: "required",
			urgencyRequest: "required",
			timeLimitRequest: "required"
		}
	});

	$(form).on("submit", function(event) {
		event.preventDefault();

		if (form.valid()) {

			var formData = new FormData($(this)[0]);
			formData.delete('teamTicket');
			
			formData.append('teamTicket', $('#ticketteam1').multipleSelect('getSelects'));
			
			$.ajax({
				url: '/ticketing/createTicket',
				type: 'POST',
				data: formData,
				async: true,
				success: function(data) {
					refreshTable(data);
					alert('Create Complete');
					
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
			$("#closemodal").click();
		});
	});
	
	function refreshTable(data) {
		table.destroy();
	
		$("#tbodyTicket").empty();
	
		$("#tbodyTicket").html(data);
	
		table = $('#tableTicketsUserSide').DataTable({
			"order": [[ 5, 'desc' ], [ 6, 'desc' ]],
			"pageLength": 100
		});
	}

	
	$("body").on("click", ".modalIcon", function() {
		$("input[name=requestName]").val('');
		$("textarea[name=requestDetails]").val('');
		$("input[name=timeLimitRequest]").val('');
		$("input[name=acceptedate]").val('');
		$("textarea[name=itcomments]").val('');
		$("input[name=expectingday]").val('');
		
		$("#urgencyRequest2").find('option').removeAttr("selected");
		$("#statusRequest").find('option').removeAttr("selected");
		$("#incharItRequest").find('option').removeAttr("selected");
		
		if($(this).closest("tr").attr('admin') == '1') {
			$('select[name=statusRequest]').attr('disabled',false);
			$('select[name=incharItRequest]').attr('disabled',false);
			$('input[name=expectingday]').attr('disabled',false);
			$('textarea[name=itcomments]').attr('disabled',false);
			$("#editTicketButtonModal").attr('disabled',false);
		} 
		
		var requestname = $(this).closest("tr").find("#requestName").text();    
		var insertDate = $(this).closest("tr").find("#insertDate").text();    
		var urgency = $(this).closest("tr").find("#urgency").attr('idUrgency');    
		var dateLimit = $(this).closest("tr").find("#dateLimit").text(); 		
		var inchargeit = $(this).closest("tr").find("#inchargeit").attr('idInchargit'); 	
		var statusRequest = $(this).closest("tr").find("#statusRequest").attr('idStatus'); 	
		var details = $(this).closest("tr").attr('details'); 	
		var accepteddate = $(this).closest("tr").attr('accepteddate'); 	
		var comments = $(this).closest("tr").attr('comments'); 	
		var idTicket = $(this).closest("tr").attr('idTicket'); 	
		var expday = $(this).closest("tr").attr('expeday'); 	
		
		$("#editTicketForm").attr('idTicket', idTicket);
		$("input[name=requestName]").val(requestname);
		$("textarea[name=requestDetails]").val(details);
		$("input[name=timeLimitRequest]").val(dateLimit);
		$("input[name=expectingday]").val(expday);
		$('select[name=statusRequest] option[value=' + statusRequest + ']').attr('selected','selected');
		$('select[name=incharItRequest] option[value=' + inchargeit + ']').attr('selected','selected');
		$('select[name=urgencyRequest] option[value=' + urgency + ']').attr('selected','selected');
		$('select[name=statusRequest]').val(statusRequest);
		$('select[name=incharItRequest]').val(inchargeit);
		$('select[name=urgencyRequest]').val(urgency);
		
		var team = $(this).closest("tr").attr('team').split(',');
		$("select[name=teamTicket]").multipleSelect("setSelects", team);
		
		$("input[name=acceptedate]").val(accepteddate);
		$("textarea[name=itcomments]").val(comments);
		
		if(($(this).closest("tr").attr('c') == '0' && $(this).closest("tr").attr('admin') == '0') || statusRequest == 2) {
			$("input[name=requestName]").attr('disabled',true);
			$("textarea[name=requestDetails]").attr('disabled',true);
			$("input[name=timeLimitRequest]").attr('disabled',true);
			$("input[name=expectingday]").attr('disabled',true);
			$('select[name=statusRequest]').attr('disabled',true);
			$('select[name=incharItRequest]').attr('disabled',true);
			$('select[name=urgencyRequest]').attr('disabled',true);
			$('select[name=statusRequest]').attr('disabled',true);
			$("#ticketteam2").multipleSelect("disable");
			$("input[name=acceptedate]").attr('disabled',true);
			$("textarea[name=itcomments]").attr('disabled',true);
			$("#editTicketButtonModal").attr('disabled',true);
		}
		
	});
	
	var form = $("#editTicketForm");
	$(form).validate({
		rules: {
			requestName: "required",
			requestDetails: "required",
			urgencyRequest: "required",
			timeLimitRequest: "required"
		}
	});

	$(form).on("submit", function(event) {
		event.preventDefault();

		if (form.valid()) {

			var formData = new FormData($(this)[0]);
			formData.append('idTicket', $(this).attr("idTicket"));
			formData.delete('teamTicket');
			
			formData.append('teamTicket', $('#ticketteam2').multipleSelect('getSelects'));
			
			$.ajax({
				url: '/ticketing/editTicket',
				type: 'POST',
				data: formData,
				async: true,
				success: function(data) {
					refreshTable(data);
					alert('Edit Complete');
					
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
			$("#closemodal2").click();
		});
	});

	$('select[name=teamTicket').multipleSelect();
	$("select[name=teamTicket").multipleSelect("uncheckAll");
});