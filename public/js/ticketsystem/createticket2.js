$(document).ready(function () {
    window.searchSelAll = $('.search-box-sel-all2').SumoSelect({
        csvDispCount: 3,
        selectAll: false,
        search: true,
        searchText: 'Enter here.',
        okCancelInMulti: false
    });

    setTimeout(function(){  
		$( '.search-box-sel-all2' ).unbind(); 
	}, 1000);

    $('#periodReport').daterangepicker({
		startDate: moment(),
		endDate: moment().add(10, 'day'),
		alwaysShowCalendars: true,
		minDate: moment(),
		format: 'YYYY-MM-DD',
        separator: ' to '
    });

    var selectBoxUsers = '';
    for (var i = 0; i < usersApi.length; i++) {
		selectBoxUsers += '<option value="' + usersApi[i]['id'] + '">' + usersApi[i]['fullname'] + '</option>';
	}

	$("#usersSB").empty();
	$("#usersSB").append(selectBoxUsers);
	$("#usersSB").attr('disabled', false);
	$('select#usersSB')[0].sumo.reload();

	$( '.search-box-sel-all2' ).unbind(); 
    
	function formValid() {

		$( '.search-box-sel-all2' ).unbind(); 

		if($("#subjectticket").val() == '') {
			return false;
		}

		if($("#requestDetails").val() == '') {
			return false;
		}

		if(getSumoSelects("#typeSB", 1).length == 0) {
			return false;
		}

		if(getSumoSelects("#prioritySB", 1).length == 0) {
			return false;
		}

		if($("#periodReport").val() == '') {
			return false;
		}

		return true;
	}

	$("body").on("click", "#buttonCreateTicket", function() {
		$( '.search-box-sel-all2' ).unbind();

		if(formValid()) {
			
			$("#buttonCreateTicket").attr('disabled', true);		

			$( '.search-box-sel-all2' ).unbind(); 

			var formData = new FormData();	
			formData.append('subject' , $("#subjectticket").val().escapeSpecialChars());
			formData.append('details' , $("#requestDetails").val().escapeSpecialChars());
			formData.append('users' , getSumoSelects("#usersSB", 1));
			formData.append('type' , getSumoSelects("#typeSB", 1));
			formData.append('priority' , getSumoSelects("#prioritySB", 1));
			formData.append('requiredDate' , $("#periodReport").val());
			formData.append( 'files', $( '#files' )[0].files[0] );

			$.ajax({
				url: './create_ticket',
				type: 'POST',
				data: formData,
				async: true,
				success: function(data) {
					$("#buttonCreateTicket").attr('disabled', false);
					alert(data);
					window.location.replace('http://mobisteinreport.com/ticketsystem2');
				},	
				error: function(response) {
					alert("error");
				},
				cache: false,
				contentType: false,
				processData: false
			});

		} else {
			alert("Missing fields!");
		}

		$( '.search-box-sel-all2' ).unbind();
	});

	String.prototype.escapeSpecialChars = function() {
	    return this.replace(/[\\]/g, '\\\\')
			       .replace(/[\/]/g, '\\/')
			       .replace(/[\b]/g, '\\b')
			       .replace(/[\f]/g, '\\f')
			       .replace(/[\n]/g, '\\n')
			       .replace(/[\r]/g, '\\r')
			       .replace(/[\t]/g, '\\t')
	               .replace(/\"/g, '\\\"')
	               .replace(/'/g, '&#039');
	};
});