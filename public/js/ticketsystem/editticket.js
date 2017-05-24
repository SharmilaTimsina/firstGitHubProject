
$(document).ready(function () {
	  var objDiv = document.getElementById("chatpabelbody");
		objDiv.scrollTop = objDiv.scrollHeight; 

	setTimeout(function(){  
	    	$( '.search-box-sel-all' ).unbind();
			$( '.search-box-sel-all2' ).unbind(); 
		}, 1000);
});

var usersColors = [];
var colors = [];

 $("#usersSB").attr('disabled', true);
 $("#usersSBAssigned").attr('disabled', true);

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
						$("#usersSBAssigned").empty();

						$("#usersSB").append(selectBoxUsers);
						$("#usersSBAssigned").append('<option value="100">- - -</option>' + selectBoxUsers);

						$("#usersSB").attr('disabled', false);
						$("#usersSBAssigned").attr('disabled', false);

						/*
						$("#usersSB option[value='" + idAuth + "']").each(function() {
						    $(this).remove();
						});
						*/

						$("#usersSBAssigned").append('<option value="' + $("#requesterid").text() + '">' + $("#requester").text() + '</option>');
						$("#usersSBAssigned").closest('.SumoSelect').find('.options').append('<li class="opt"><label>' + $("#requester").text() + '</label></li>');

						$('select#usersSB')[0].sumo.reload();
						$('select#usersSBAssigned')[0].sumo.reload();

						if(getSumoSelects("#statusSB", 1) != 1) {
							$('select#usersSBAssigned')[0].sumo.disable();
						}

						var users2 = jsonGlobal.users_area.split(",");
						for (var i = users2.length - 1; i >= 0; i--) {
							$('select#usersSB')[0].sumo.selectItem(users2[i]);
						};

						for (var i = 0; i < usersIt.length; i++) {
							$('select#usersSB')[0].sumo.selectItem(usersIt[i]);
						}

											
					} else {
						
						$("#usersSB").empty();
						$("#usersSBAssigned").empty();

						$("#usersSB").attr('disabled', true);
						 $("#usersSBAssigned").attr('disabled', true);

						$('select#usersSB')[0].sumo.reload();
						$('select#usersSBAssigned')[0].sumo.reload();

						if(getSumoSelects("#statusSB", 1) != 1) {
							$('select#usersSBAssigned')[0].sumo.disable();
						}

						alert('No users for selected Area!');

					}

					//$("#usersSB").css("display", "none");
					//$("#usersSBAssigned").css("display", "none");
				},	
				error: function(response) {
					alert("error");
					$("#usersSB").css("display", "none");
					$("#usersSBAssigned").css("display", "none");
				},
				cache: false,
				contentType: false,
				processData: false
			});
  		} else {
  			$("#usersSB").empty();
  			$("#usersSBAssigned").empty();

			$("#usersSB").attr('disabled', true);
			$("#usersSBAssigned").attr('disabled', true);

			$("#usersSBAssigned").append('<option value="' + $("#requesterid").text() + '">' + $("#requester").text() + '</option>');
			$("#usersSBAssigned").closest('.SumoSelect').find('.options').append('<li class="opt"><label>' + $("#requester").text() + '</label></li>');

			$('select#usersSB')[0].sumo.reload();
			$('select#usersSBAssigned')[0].sumo.reload();

			$("select#usersSB").css('display', 'none');
			$("#usersSBAssigned").css("display", "none");

			if(getSumoSelects("#statusSB", 1) != 1) {
				$('select#usersSBAssigned')[0].sumo.disable();
			}
					
  		}
});

var jsonGlobal = $.parseJSON(ticketInfo);
if(ticketInfo != '') {
	
	var json = jsonGlobal;
	

	var dates = json.required_period.split(" to ");
	var areas = json.areas.split(",");
	var users = json.users_area.split(",");

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

	    $('#periodReport').daterangepicker({
			startDate: dates[0],
			endDate: dates[1],
			alwaysShowCalendars: true,
			format: 'YYYY-MM-DD',
            separator: ' to '
        });

	$("#id_ticket").text(json.id_ticket);
	$("#requester").text(json.requester);
	$("#requesterid").text(json.requesterid);
	$("#daterequested").text(json.creation_date);
	$("#incharge").text(((json.incharge != null) ? json.incharge : '- - -'));
	$("#dateincharged").text(((json.incharged_at != null) ? json.incharged_at : '- - -'));

	$("#subjectticket").val(json.subject);

	if(json.status != 1) {
		$('select#usersSBAssigned')[0].sumo.disable();
	}

	$('select#statusSB')[0].sumo.selectItem(json.status);
	$('select#typeSB')[0].sumo.selectItem(json.type);
	$('select#prioritySB')[0].sumo.selectItem(json.priority);
	
	for (var i = areas.length - 1; i >= 0; i--) {
		$('select#areaSB')[0].sumo.selectItem(areas[i]);
	};

	$(".MultiControls .btnOk").click();

	$(document).one("ajaxStop", function() {
		for (var i = users.length - 1; i >= 0; i--) {
			$('select#usersSB')[0].sumo.selectItem(users[i]);
		};
	});

	

	var jsonFiles = $.parseJSON(ticketFiles);

	for (var i = jsonFiles.length - 1; i >= 0; i--) {
		$("#files").append('<span>' + jsonFiles[i].file_date + ' - ' + jsonFiles[i].uploader + ' - <a href="./downloadFile?id_file=' + jsonFiles[i].id_file + '&id_ticket=' + json.id_ticket + '" class="atagsdivfiles">' + jsonFiles[i].file_name + '</a></span><br>');
	};

	updateChat(ticketChat);

	var canEdit = json.can_edit;
	var canClose = json.can_close;
	var canPick = json.can_pick;
	var canReopen = json.can_reopen;
	var is_incharge = json.is_incharge;

	$(document).one("ajaxStop", function() {

		if(!canClose) {
			$("#buttonCloseTicket").attr("disabled", true);
			$("#buttonCloseTicket").remove();
		}

		if(!canReopen) {
			$("#buttonReopenTicket").attr("disabled", true);
			$("#buttonReopenTicket").remove();
		}

		if(!canPick) {
			$("#buttonPickTicket").attr("disabled", true);
		}	

		if(!canEdit) {
			$("#buttonEditTicket").attr("disabled", true);
			
			$('#periodReport').attr("disabled", true);
			$("#subjectticket").attr("disabled", true);
			$("#requestDetails").attr("disabled", true);

			$('select#statusSB')[0].sumo.disable();
			$('select#typeSB')[0].sumo.disable();
			$('select#prioritySB')[0].sumo.disable();
			$('select#areaSB')[0].sumo.disable();
			$('select#usersSB')[0].sumo.disable();
			$('select#usersSBAssigned')[0].sumo.disable();
		}

		if(!is_incharge) {
			$('select#statusSB')[0].sumo.disable();
			$('select#usersSBAssigned')[0].sumo.disable();
			$('select#areaSB')[0].sumo.disable();
			$('select#usersSB')[0].sumo.disable();
		}	

		$('select#usersSBAssigned')[0].sumo.selectItem(((json.assigned != 0 && json.assigned != null && json.assigned != '') ? json.assigned : '100'));
	});
	
} else {
	//window.location.replace('index');		
}


function getRandomColor(user) {
    	
 
    if($.inArray(user, usersColors) == -1) {
    	var letters = '0123456789ABCDEF';
	    var color = '#';
	    for (var i = 0; i < 6; i++ ) {
	        color += letters[Math.floor(Math.random() * 16)];
	    }	

	    usersColors.push(user);
	    colors.push(color);

	    return color;
    } else {
    	var index = usersColors.indexOf(user);
    	return colors[index];
    }

    
}


$('#btn-inputchat').keypress(function (e) {
  if (e.which == 13) {
  	sendMessage();
  }
});

$("body").on("click", "#sendMessage", function() {

	sendMessage();

});

function sendMessage() {
	if($("#btn-inputchat").val() != '') {
		var formData = new FormData();
		formData.append('message', $("#btn-inputchat").val());
		formData.append('id_ticket', $("#id_ticket").text());

		$.ajax({
			url: './sendMessage',
			type: 'POST',
			data: formData,
			async: true,
			success: function(data) {
			
				updateChat(data);

				
			},	
			error: function(response) {
				alert("error");
			},
			cache: false,
			contentType: false,
			processData: false
		});
	}

	
}

function refreshChat() {
	var formData = new FormData();
	formData.append('id_ticket', $("#id_ticket").text());

	$.ajax({
		url: './refreshChat',
		type: 'POST',
		data: formData,
		async: true,
		success: function(data) {
		
			updateChat(data);

			
		},	
		error: function(response) {
			alert("error");
		},
		cache: false,
		contentType: false,
		processData: false
	});
}

$("body").on("click", "#imagerefresh", function() {

		refreshChat();

});


function updateChat(data) {
	var jsonChat = $.parseJSON(data);

	$("#chatbody").empty();

	var messages = '';
	for (var i = 0; i < jsonChat.length; i++) {

		messages += '<li class="left clearfix"><span class="chat-img pull-left">'
        +    '<p style="background-color: ' + getRandomColor(jsonChat[i].sender) + '" class="imgCircle" class="img-circle">' + jsonChat[i].sender.charAt(0) + '</p>'
        + ' </span>'
        +    '<div class="chat-body clearfix">'
        +        '<div class="header">'
        +            '<strong class="primary-font">' + jsonChat[i].sender + '</strong> <small class="pull-right text-muted">'
        +                '<span class="glyphicon glyphicon-time"></span>' + jsonChat[i].message_date + '</small>'
        +       '</div>'
        +        '<p>'
        +            jsonChat[i].message
        +        '</p>'
        +     '</div>'
        + '</li>';
    }

	$("#chatbody").append(messages);

	$("#btn-inputchat").val('');

	var objDiv = document.getElementById("chatpabelbody");
	objDiv.scrollTop = objDiv.scrollHeight;
}

$(document).delegate(':file', 'change', function() {
	if (confirm('Are you sure you want to upload this file?')) {
		submitfile();
	} else {
		$(":file").filestyle('clear');	
	}
});

function submitfile() {
	$(":file").filestyle('disabled', true);
		
	$("#files").empty();

	var fileInput = document.getElementById('filestyle-0');
	var file = fileInput.files[0];
	var formData = new FormData();
	formData.append('file', file);
	formData.append('id_ticket', $("#id_ticket").text());
	
	$.ajax({
		url: './uploadFile',
		type: 'POST',
		data: formData,
		async: true,
		success: function(data) {
			
			$(":file").filestyle('disabled', false);

			refreshChat();

			var jsonFiles = $.parseJSON(data);

			for (var i = jsonFiles.length - 1; i >= 0; i--) {
				$("#files").append('<span>' + jsonFiles[i].file_date + ' - ' + jsonFiles[i].uploader + ' - <a href="./downloadFile?id_file=' + jsonFiles[i].id_file + '&id_ticket=' + json.id_ticket + '" class="atagsdivfiles">' + jsonFiles[i].file_name + '</a></span><br>');
			};

		},
		error: function(response) {
			alert("error");
		},
		cache: false,
		contentType: false,
		processData: false
	});
}

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

		if(getSumoSelects("#statusSB", 1).length == 0) {
			return false;
		}

		if(getSumoSelects("#usersSBAssigned", 1).length == 0) {
			return false;
		}

		if($("#periodReport").val() == '') {
			return false;
		}

		return true;
	}

	$("body").on("click", "#buttonEditTicket", function() {

		if(formValid()) {
			
			$("#buttonEditTicket").attr('disabled', true);		

			var formData = new FormData();	
			formData.append('subject' , $("#subjectticket").val());
			formData.append('details' , $("#requestDetails").val());
			formData.append('areas' , getSumoSelects("#areaSB", 1));
			formData.append('users' , getSumoSelects("#usersSB", 1));
			formData.append('type' , getSumoSelects("#typeSB", 1));
			formData.append('priority' , getSumoSelects("#prioritySB", 1));
			formData.append('requiredDate' , $("#periodReport").val());
			formData.append('status' , getSumoSelects("#statusSB", 1));
			formData.append('assigned' , checkAssign());
			formData.append('id_ticket' , $("#id_ticket").text());

			$.ajax({
				url: './edit_ticket',
				type: 'POST',
				data: formData,
				async: true,
				success: function(data) {
					$("#buttonEditTicket").attr('disabled', false);
					
					var jsonFiles = $.parseJSON(data);

					updateChat(jsonFiles.chat);

					files = $.parseJSON(jsonFiles.files);

					$("#files").empty();
					for (var i = files.length - 1; i >= 0; i--) {
						$("#files").append('<span>' + files[i].file_date + ' - ' +files[i].uploader + ' - <a href="./downloadFile?id_file=' + files[i].id_file + '&id_ticket=' + $("#id_ticket").text() + '" class="atagsdivfiles">' + files[i].file_name + '</a></span><br>');
					};

					alert("Complete!");
					location.reload();

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


function checkAssign() {
	if(getSumoSelects("#usersSBAssigned", 1) == '100') {
		return '';
	} else {
		return getSumoSelects("#usersSBAssigned", 1);
	}
}





$("body").on("click", "#buttonPickTicket", function() {

	$("#files").empty();

	var formData = new FormData();
	formData.append('id_ticket', $("#id_ticket").text());
	
	$.ajax({
		url: './pickTicket',
		type: 'POST',
		data: formData,
		async: true,
		success: function(data) {
			
			location.reload();

		},
		error: function(response) {
			alert("error");
		},
		cache: false,
		contentType: false,
		processData: false
	});

});

$("body").on("click", "#buttonCloseTicket", function() {

	if (confirm('Are you sure you want to close this ticket?')) {

		var formData = new FormData();
		formData.append('id_ticket', $("#id_ticket").text());
		
		$.ajax({
			url: './closeTicket',
			type: 'POST',
			data: formData,
			async: true,
			success: function(data) {
				
				location.reload();

			},
			error: function(response) {
				alert("error");
			},
			cache: false,
			contentType: false,
			processData: false
		});

	}
});

$("body").on("click", "#buttonReopenTicket", function() {

	if (confirm('Are you sure you want to reopen this ticket?')) {

		var formData = new FormData();
		formData.append('id_ticket', $("#id_ticket").text());
		
		$.ajax({
			url: './reopenTicket',
			type: 'POST',
			data: formData,
			async: true,
			success: function(data) {
				
				location.reload();

			},
			error: function(response) {
				alert("error");
			},
			cache: false,
			contentType: false,
			processData: false
		});

	}
});


$("body").on("change", "#statusSB", function() {
	if(getSumoSelects("#statusSB", 1) == '1') {
		$('select#usersSBAssigned')[0].sumo.enable();
	} else {
		if(jsonGlobal.status != 1)
			$('select#usersSBAssigned')[0].sumo.disable();
	}
});

$("body").on("click", ".formassigned .SumoSelect", function() {
	if(getSumoSelects("#statusSB", 1) == '1') {
		var users = getSumoSelects("#usersSB", 2)
		var sb = $(this);

		var i = 0;
		$(this).find('.opt').each(function() {
		    
		    if((jQuery.inArray($(this).find('label').text(), users) !== -1) || $(this).find('label').text() == $("#requester").text()) {
		    	$("select#usersSBAssigned")[0].sumo.enableItem(i);
		    } else {
		    	if($(this).find('label').text() != '- - -')
		    		$("select#usersSBAssigned")[0].sumo.disableItem(i);
		    }

		    i++;
		});

		//$("select#usersSBAssigned")[0].sumo.enableItem(0);
	}
});

