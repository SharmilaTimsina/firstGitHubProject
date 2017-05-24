$(document).ready(function () {
        window.searchSelAll = $('.search-box-sel-all').SumoSelect({
	        csvDispCount: 3,
	        selectAll: false,
	        search: true,
	        searchText: 'Enter here.',
	        okCancelInMulti: true
	    });

	    window.searchSelAll = $('.search-box-sel-all2').SumoSelect({
	        csvDispCount: 3,
	        selectAll: false,
	        search: true,
	        searchText: 'Enter here.',
	        okCancelInMulti: false
	    });

	    setTimeout(function(){  
	    	$( '.search-box-sel-all' ).unbind();
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

	    $("#usersSB").attr('disabled', true);

        $("body").on("click", ".MultiControls .btnOk", function() {
	  		if(getSumoSelects('#areaSB', 6) != '') {
	  			$.ajax({
					url: './getUsersMultiple?area=' + getSumoSelects('#areaSB', 6),
					type: 'GET',
					async: true,
					success: function(data) {
					
						var json = $.parseJSON(data);

						var users = json['users'];
									
						if (undefined !== users && users.length) {
							var usersIt = [];
							var selectBoxUsers = '';
							for (var i = 0; i < users.length; i++) {
								if(users[i]['area'] == 1)
									usersIt.push(users[i]['id']);

								selectBoxUsers += '<option ' + users[i]['option'] + ' value="' + users[i]['id'] + '">' + users[i]['name'] + '</option>';
							}

							$("#usersSB").empty();

							$("#usersSB").append(selectBoxUsers);

							$("#usersSB option[value='" + idAuth + "']").each(function() {
							    $(this).remove();
							});

							$("#usersSB").attr('disabled', false);
							$('select#usersSB')[0].sumo.reload();

							for (var i = 0; i < usersIt.length; i++) {
								$('select#usersSB')[0].sumo.selectItem(usersIt[i]);
							}

						} else {
							
							$("#usersSB").empty();
							$("#usersSB").attr('disabled', true);
							$('select#usersSB')[0].sumo.reload();

							alert('No users for selected Area!');

						}

						$("#usersSB").css("display", "none");
					},	
					error: function(response) {
						alert("error");
						$("#usersSB").css("display", "none");
					},
					cache: false,
					contentType: false,
					processData: false
				});
	  		} else {
	  			$("#usersSB").empty();
				$("#usersSB").attr('disabled', true);
				$('select#usersSB')[0].sumo.reload();
				$("select#usersSB").css('display', 'none');
	  		}
	});

	function formValid() {

		if($("#subjectticket").val() == '') {
			return false;
		}

		if($("#requestDetails").val() == '') {
			return false;
		}

		if(getSumoSelects("#areaSB", 1).length == 0) {
			return false;
		}

		if(getSumoSelects("#usersSB", 1).length == 0) {
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

		if(formValid()) {
			
			$("#buttonCreateTicket").attr('disabled', true);		

			var formData = new FormData();	
			formData.append('subject' , $("#subjectticket").val());
			formData.append('details' , $("#requestDetails").val().replace("'", ""));
			formData.append('areas' , getSumoSelects("#areaSB", 1));
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
					window.location.replace('.');
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

	});

	setTimeout(function(){  
    	$( '.search-box-sel-all' ).unbind();
		$( '.search-box-sel-all2' ).unbind(); 
	}, 1000);
});