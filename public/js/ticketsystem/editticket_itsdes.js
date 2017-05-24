
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

var jsonGlobal = $.parseJSON(ticketInfo);
if(ticketInfo != '') {
	
	var json = jsonGlobal;
	
	var dates = json.required_period.split(" to ");
	var users = json.usersView.split(",");
	var is_requester = json.is_requester;

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

    var selectBoxUsers = '';
    for (var i = 0; i < usersApi.length; i++) {
		selectBoxUsers += '<option value="' + usersApi[i]['id'] + '">' + usersApi[i]['fullname'] + '</option>';
	}

	$("#usersSB").empty();
	$("#usersSB").append(selectBoxUsers);
	$('select#usersSB')[0].sumo.reload();

	$( '.search-box-sel-all2' ).unbind(); 

    $("#id_ticket").text(json.id_ticket);
	$("#requester").text(json.requester);
	$("#requesterid").text(json.requesterid);
	$("#daterequested").text(json.creation_date);
	$("#incharge").text(((json.incharge != null) ? json.incharge : '- - -'));
	$("#dateincharged").text(((json.incharged_at != null) ? json.incharged_at : '- - -'));
	$("#subjectticket").val(json.subject.replace(/&#039/g, "'"));
	$("#id_subject").text(json.subject.replace(/&#039/g, "'"));
	$("#id_deadline").text(json.deadline);
	$("#requestDetails").val(json.details.replace(/&#039/g, "'"));

	$("#id_status").text($('#statusSBHide option[value=' + json.status + ']').text())

	$('select#typeSB')[0].sumo.selectItem(json.type);

	$('select#prioritySB')[0].sumo.selectItem(json.priority);

	for (var i = users.length - 1; i >= 0; i--) {
		$('select#usersSB')[0].sumo.selectItem(users[i]);
	};

	var jsonFiles = $.parseJSON(ticketFiles);

	for (var i = jsonFiles.length - 1; i >= 0; i--) {
		$("#files").append('<span>' + jsonFiles[i].file_date + ' - ' + jsonFiles[i].uploader + ' - <a href="./downloadFile?id_file=' + jsonFiles[i].id_file + '&id_ticket=' + json.id_ticket + '" class="atagsdivfiles">' + jsonFiles[i].file_name + '</a></span><br>');
	};


	var selectBoxUsersMyArea = '';
    for (var i = 0; i < usersMyArea.length; i++) {
		selectBoxUsersMyArea += '<option value="' + usersMyArea[i]['id'] + '">' + usersMyArea[i]['username'] + '</option>';
	}

	$("#usersToBA").append(selectBoxUsersMyArea);
	$('select#usersToBA')[0].sumo.reload();

	$( '.search-box-sel-all' ).unbind();
	$( '.search-box-sel-all2' ).unbind(); 

	updateChat(ticketChat);

	if(!is_requester) {
		$("#subjectticket").attr('disabled', true);
		$("#requestDetails").attr('disabled', true);
		
		$('select#typeSB')[0].sumo.disable();
		$('select#prioritySB')[0].sumo.disable();
		$('select#usersSB')[0].sumo.disable();

		$("#periodReport").attr('disabled', true);
	} else {
		$('select#typeSB')[0].sumo.disable();
	}

	controlButtons(json.status, json.is_incharge, json.isAssignToMe, json.deadline, dates[1]);

} else {
	window.location.replace('index');		
}

function controlButtons(status, incharge, isAssignToMe, date, enddate) {
	
	if(!incharge && !isAssignToMe && status != 0) {
		$("#pickStatus").hide();
		$("#pickedStatus").hide();
		$("#waitingStatus").hide();
		$("#assignCol").hide();
		return;
	}

	if(status == 0) {
		putPickerDeadline(enddate)
		$("#pickStatus").show();
		$("#pickedStatus").hide();
		$("#assignCol").hide();
	} else if(status == 1) {
		$("#pickStatus").hide();
		$("#pickedStatus").hide();
		$("#waitingStatus").show();
		$("#assignCol").hide();
	} else if(status == 2) {
		if(!incharge || isAssignToMe) {
			putPickerDeadline(enddate)
			$("#pickStatus").show();
			$("#pickedStatus").hide();
			$("#assignCol").hide();
		} else {
			$("#pickStatus").hide();
			$("#pickedStatus").hide();
			$("#waitingStatus").show();
			$("#assignCol").hide();
		}
	} else if(status == 3) {
		$("#changedeadline").show();
		putPickerChangeDealine(date)
		$("#pickStatus").hide();
		$("#pickedStatus").show();
		$("#buttonProgressTicket").closest('td').remove();
		$("#assignCol").show();
	} else if(status == 4) {
		$("#pickStatus").hide();
		$("#pickedStatus").hide();
		$("#waitingStatus").show();
		$("#assignCol").hide();
	} else if(status == 5) {
		$("#pickStatus").hide();
		$("#pickedStatus").hide();
		$("#waitingStatus").hide();
		$("#assignCol").hide();
	} else if(status == 6) {
		$("#changedeadline").show();
		putPickerChangeDealine(date)
		$("#pickStatus").hide();
		$("#pickedStatus").show();
		$("#buttonHoldTicket").closest('td').remove();
		$("#assignCol").show();
	} else if(status == 7) {
		$("#pickStatus").hide();
		$("#pickedStatus").hide();
		$("#waitingStatus").show();
		$("#assignCol").hide();
	} 
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

		$("#sendMessage").attr('disabled', true);

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
				$("#sendMessage").attr('disabled', false);
				
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
        +            jsonChat[i].message.replace(/\\"/g, '"')
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


$("body").on("change", "#usersToBA", function() {
	if(getSumoSelects("#usersToBA", 1) == '111') {
		$('#buttonAssignTicket').attr("disabled", true);
	} else {
		$('#buttonAssignTicket').attr("disabled", false);
	}
});

function pickTicket(date) {

	var formData = new FormData();
	formData.append('id_ticket', $("#id_ticket").text());
	formData.append('deadline', date);
	
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

}

function putPickerDeadline(date) {
	$( "#buttonPickTicket" ).datepicker({
      	showOn: "button",
        buttonText: "Pick Ticket",
        dateFormat: 'yy-mm-dd',
        defaultDate: date,
        onSelect: function(selected,evnt) {
	         pickTicket(selected);
	    }
    });

    $(".ui-datepicker-trigger").addClass('btn btn-success');
}

function putPickerChangeDealine(date) {
	$( "#buttonChangedeadline" ).datepicker({
	  	showOn: "button",
	    buttonText: "change deadline",
	    dateFormat: 'yy-mm-dd',
	    defaultDate: date,
	    onSelect: function(selected,evnt) {
	         changeDeadline(selected);
	    }
	});

	$(".ui-datepicker-trigger").addClass('btn btn-info');
}

function changeDeadline(date) {

	var formData = new FormData();
	formData.append('id_ticket', $("#id_ticket").text());
	formData.append('deadline', date);
	
	$.ajax({
		url: './changeDeadline',
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

$("body").on("click", "#buttonValidationTicket", function() {
	var comment = prompt("Comments.");
	
	if (comment === null) {
        return; //break out of the function early
    }

	var formData = new FormData();
	formData.append('id_ticket', $("#id_ticket").text());
	formData.append('message', comment.replace(/\"/g, '\\\"').replace(/'/g, '&#039'));

	$.ajax({
		url: './sendToValidation',
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

$("body").on("click", "#buttonHoldTicket", function() {
	var comment = prompt("Why?");
	
	if (comment === null) {
        return; //break out of the function early
    }

	var formData = new FormData();
	formData.append('id_ticket', $("#id_ticket").text());
	formData.append('message', comment.replace(/\"/g, '\\\"').replace(/'/g, '&#039'));

	$.ajax({
		url: './putOnHold',
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

$("body").on("click", "#buttonProgressTicket", function() {
	var send = confirm("Are you sure?");
	if (send == true) {
		var formData = new FormData();
		formData.append('id_ticket', $("#id_ticket").text());

		$.ajax({
			url: './putInProgress',
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

$("body").on("click", "#buttonRefuseTicket", function() {
	var comment = prompt("Why?");
	
	if (comment === null) {
        return; //break out of the function early
    }

	var formData = new FormData();
	formData.append('id_ticket', $("#id_ticket").text());
	formData.append('message', comment.replace(/\"/g, '\\\"').replace(/'/g, '&#039'));

	$.ajax({
		url: './refuse',
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

$("body").on("click", "#buttonRequestTicket", function() {
	var comment = prompt("What you need?");
	
	if (comment === null) {
        return; //break out of the function early
    }

	var formData = new FormData();
	formData.append('id_ticket', $("#id_ticket").text());
	formData.append('message', comment.replace(/\"/g, '\\\"').replace(/'/g, '&#039'));

	$.ajax({
		url: './request',
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

$("body").on("click", "#buttonAssignTicket", function() {
	var send = confirm("Are you sure?");
	if (send == true) {
		var formData = new FormData();
		formData.append('id_ticket', $("#id_ticket").text());
		formData.append('user', getSumoSelects("#usersToBA", 1));

		$.ajax({
			url: './assign',
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

	String.prototype.escapeSpecialChars = function() {
	    return this.replace(/[\\]/g, '\\\\')
			       .replace(/[\/]/g, '\\/')
			       .replace(/[\b]/g, '\\b')
			       .replace(/[\f]/g, '\\f')
			       .replace(/[\n]/g, '\\n')
			       .replace(/[\r]/g, '\\r')
			       .replace(/[\t]/g, '\\t')
	               .replace(/\"/g, '\\\"')
	               .replace(/'/g, '&#039')
	};
});