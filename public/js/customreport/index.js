$(document).ready(function() {

	var table = $('#tableCustomReport').DataTable();
	var selectedCheckBox = null;
	var timeOn = '';
	var defaultDate = '';
	var defaultStartTime = '';
	var defaultEndTime = '';
	var defaultStartDate = '';
	var defaultEndDate = '';
	var currentPage = 0;
	var totalPages = 0;

	$("input:checkbox").click(function(){

        var group = "input:checkbox[name='"+$(this).prop("name")+"']";
        $(group).closest('tr').find('td:eq(3)').css('color', 'black');
        $(group).closest('tr').find('td:eq(3)').css('background-color', 'transparent');
        $(group).closest('tr').find('td:eq(3)').css('cursor', 'pointer !important');

        if(!$(this).is(':checked')) {
        	$('#' + selectedCheckBox).text(defaultDate);
        	$('#' + selectedCheckBox).attr('datebeginclean', defaultStartDate);
	  		$('#' + selectedCheckBox).attr('dateendclean', defaultEndDate);
	  		$('#' + selectedCheckBox).attr('starttime', defaultStartTime);
	  		$('#' + selectedCheckBox).attr('endtime', defaultEndTime);

        	selectedCheckBox = null;
        	timeOn = '';
        	defaultDate = '';
        	$('.daterangepicker').remove();
        	return;
        } 

        $('#' + selectedCheckBox).text(defaultDate);


        defaultDate = $(this).closest('tr').find('td:eq(3)').text();
        defaultStartTime = $(this).closest('tr').find('td:eq(3)').attr('starttime');
		defaultEndTime = $(this).closest('tr').find('td:eq(3)').attr('endtime');
		defaultStartDate = $(this).closest('tr').find('td:eq(3)').attr('datebeginclean');
		defaultEndDate = $(this).closest('tr').find('td:eq(3)').attr('dateendclean');
        selectedCheckBox = $(this).closest('tr').find('td:eq(3)').attr('id');

        $(group).not(this).prop("checked",false);

        var group = "input:checkbox[name='"+$(this).prop("name")+"']";
        $(group).not(this).prop("checked",false);
        $(group).closest('tr').find('td:eq(3)').css('color', 'black');
        $(group).closest('tr').find('td:eq(3)').css('background-color', 'transparent');

        $('.daterangepicker').remove();
        $(this).closest('tr').find('td:eq(3)').css('color', 'white');
        $(this).closest('tr').find('td:eq(3)').css('background-color', 'cadetblue');
        $(this).closest('tr').find('td:eq(3)').css('cursor', 'pointer');

        if($(this).closest('tr').find('td:eq(3)').attr("time") == 'true') {
        	timeOn = true;
        	$( $(this).closest('tr').find('td:eq(3)') ).daterangepicker({
		        timePicker: true,
		        timePicker24Hour: true,
		        timePickerIncrement: 60,
		        autoApply: false,
		        separator: ' to ',
		        startDate: moment($(this).closest('tr').find('td:eq(3)').attr("startdate")).format('YYYY-MM-DD  HH:mm'),
				endDate: moment($(this).closest('tr').find('td:eq(3)').attr("enddate")).format('YYYY-MM-DD HH:mm'),
		        locale: {
		            format: 'YYYY-MM-DD HH:mm'
		        },
		        ranges: {
					'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
					'Last 3 Days': [moment().subtract(3, 'days'), moment().subtract(1, 'days')],
					'Last 30 Days': [moment().subtract(29, 'days'), moment()],
					'This Month': [moment().startOf('month'), moment().endOf('month')],
					'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
				}
		    });
        } else {
        	timeOn = false;
        	$( $(this).closest('tr').find('td:eq(3)') ).daterangepicker({
	            format: 'YYYY-MM-DD',
	            separator: ' to ',
				autoApply: false,
				startDate: moment($(this).closest('tr').find('td:eq(3)').attr("startdate")).format('YYYY-MM-DD'),
				endDate: moment($(this).closest('tr').find('td:eq(3)').attr("enddate")).format('YYYY-MM-DD'),
				ranges: {
					'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
					'Last 3 Days': [moment().subtract(3, 'days'), moment().subtract(1, 'days')],
					'Last 30 Days': [moment().subtract(29, 'days'), moment()],
					'This Month': [moment().startOf('month'), moment().endOf('month')],
					'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
				}
	        });
        }

        $(".daterangepicker .ranges .range_inputs .applyBtn").on('click' ,function(ev, picker){
			  
			if(selectedCheckBox != null) {
			  	if(!timeOn) {
			 		$('#' + selectedCheckBox).text($(this).closest('.daterangepicker').find('input[name=daterangepicker_start]').val() + ' to ' + $(this).closest('.daterangepicker').find('input[name=daterangepicker_end]').val());
			  		
			  		$('#' + selectedCheckBox).attr('datebeginclean', $(this).closest('.daterangepicker').find('input[name=daterangepicker_start]').val());
			  		$('#' + selectedCheckBox).attr('dateendclean', $(this).closest('.daterangepicker').find('input[name=daterangepicker_end]').val());

			  	} else {
			  		var dateB = $(this).closest('.daterangepicker').find('input[name=daterangepicker_start]').val().split(' ');
			  		var dateE = $(this).closest('.daterangepicker').find('input[name=daterangepicker_end]').val().split(' ');

			  		$('#' + selectedCheckBox).text(dateB[0] +' to '+ dateE[0] + ' (' + dateB[1] + ' to ' + dateE[1] +')');

			  		$('#' + selectedCheckBox).attr('datebeginclean', dateB[0]);
			  		$('#' + selectedCheckBox).attr('dateendclean', dateE[0]);
			  		$('#' + selectedCheckBox).attr('starttime', dateB[1]);
			  		$('#' + selectedCheckBox).attr('endtime', dateE[1]);
			  	}
			}
		});
    });	

	var running = false;
	$(".modalIcon").on('click' ,function(){
		
		if(!running)
			running = true;
		else 
			return;

		$(".modalIcon").css('cursor', 'not-allowed');

		var type = $(this).attr('type');

		if(type == 'delete') {
			console.log("delete");
			if (confirm('Are you sure you want to delete this template?')) {
			    $.ajax({
					url: '/customreport/deleteSavedReport?reportid=' + $(this).attr('idreport'),
					type: 'GET',
					async: true,
					success: function(data) {
						
						if(typeof table != 'undefined' && table != null)
							table.destroy();

						$('#tbodyCustomReportList').empty();
						
						$("#tbodyCustomReportList").html(data);
						
						table = $('#tableCustomReport').DataTable();
					},
					error: function(response) {
						alert("error");
					},
					cache: false,
					contentType: false,
					processData: false
				});
			}
		} else if(type == 'download') {
			console.log("download");

			formData = new FormData();
			formData.append('reportid', $(this).attr('idreport'));
			formData.append('dateb', $(this).closest('tr').find('td:eq(3)').attr('datebeginclean'));
			formData.append('datee', $(this).closest('tr').find('td:eq(3)').attr('dateendclean'));
			formData.append('hourb', $(this).closest('tr').find('td:eq(3)').attr('starttime'));
			formData.append('houre', $(this).closest('tr').find('td:eq(3)').attr('endtime'));

			$.ajax({
				url: '/customreport/downloadReport',
				type: 'POST',
				data: formData,
				async: true,
				success: function(data) {
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
		} else if(type == 'edit') {
			



		} else if(type == 'preview') {
			console.log("preview");
			currentPage = 0;
			elementToSee = $(this);
			getTable($(this));
 		}

 		$(document).one("ajaxStop", function() {
 			$(".modalIcon").css('cursor', 'pointer');
 			running = false;
		});

	});

	$("#nextPage").on('click' ,function(){
		
		console.log(totalPages);
		console.log(currentPage);

		if(currentPage <= totalPages)
			currentPage++;

		if(currentPage == totalPages) {
			$(this).attr('disabled', true);
			$("#prevPage").attr('disabled', false);
		} else {
			$(this).attr('disabled', false);
			$("#prevPage").attr('disabled', false);
		}

		getTable(elementToSee)
	});

	$("#prevPage").on('click' ,function(){
		if(currentPage > 0) {
			currentPage--;
		}
	
		if(currentPage == 0) {
			$(this).attr('disabled', true);
			$("#nextPage").attr('disabled', false);
		} else {
			$(this).attr('disabled', false);
		}

		getTable(elementToSee)
	});

	function getTable(element) {
			    
		formData = new FormData();
		formData.append('reportid', $(element).attr('idreport'));
		formData.append('dateb', $(element).closest('tr').find('td:eq(3)').attr('datebeginclean'));
		formData.append('datee', $(element).closest('tr').find('td:eq(3)').attr('dateendclean'));
		formData.append('hourb', $(element).closest('tr').find('td:eq(3)').attr('starttime'));
		formData.append('houre', $(element).closest('tr').find('td:eq(3)').attr('endtime'));
		formData.append('html', '1');
		formData.append('page', currentPage);

		$.ajax({
			url: '/customreport/downloadReport',
			type: 'POST',
			data: formData,
			async: true,
			success: function(data) {
				$("#tablePreviewReport").html('');				
				$("#tablePreviewReport").html(data);
			},
			error: function(response) {
				alert("error");
			},
			cache: false,
			contentType: false,
			processData: false
		});

		$(document).one("ajaxStop", function() {
			$("#rowTablePreview").show();
			totalPages = $("#theadpages").attr('tablecount');
			
			if(totalPages == 0) {
				$("#nextPage").attr('disabled', true);
				$("#prevPage").attr('disabled', true);
			}

			if(currentPage == 0)
				$("#prevPage").attr('disabled', true);

	    });
	}

	
	
});	


