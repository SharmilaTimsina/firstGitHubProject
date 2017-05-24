
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

	if(!is_requester)
		$("#buttonEditTicket").hide();

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

	controlButtons(json.status);

} else {
	window.location.replace('index');		
}

function controlButtons(status) {
	$("#buttonNotOkTicket").text("The ticket is incomplete");

	$("#buttonReopenTicket").hide();

	if(status == 0) {
		$("#pickedStatus").hide();
	} else if(status == 1) {
		$(".sent").show();
		$(".finish").hide();
	} else if(status == 2) {
		$("#pickedStatus").hide();
	} else if(status == 3) {
		$("#pickedStatus").hide();
	} else if(status == 4) {
		$(".sent").hide();
		$(".finish").show();
	} else if(status == 5) {
		$("#pickedStatus").hide();
		$("#buttonReopenTicket").show();
		$("#buttonCloseTicket").hide();
	} else if(status == 6) {
		$("#pickedStatus").hide();
	} else if(status == 7) {
		$("#buttonNotOkTicket").text("Send back");
		$(".sent").hide();
		$(".finish").show();
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
		var formData = new FormData();
		formData.append('message', $("#btn-inputchat").val());
		formData.append('id_ticket', $("#id_ticket").text());

		$("#sendMessage").attr('disabled', true);

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

function formValid() {

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


$("body").on("click", "#buttonEditTicket", function() {
	$( '.search-box-sel-all2' ).unbind();

	if(formValid()) {
		
		$("#buttonEditTicket").attr('disabled', true);		

		var formData = new FormData();	
		formData.append('subject' , $("#subjectticket").val().escapeSpecialChars());
		formData.append('details' , $("#requestDetails").val().escapeSpecialChars());
		formData.append('users' , getSumoSelects("#usersSB", 1));
		formData.append('type' , getSumoSelects("#typeSB", 1));
		formData.append('priority' , getSumoSelects("#prioritySB", 1));
		formData.append('requiredDate' , $("#periodReport").val());
		formData.append('id_ticket' , $("#id_ticket").text());

		$.ajax({
			url: './edit_ticket',
			type: 'POST',
			data: formData,
			async: true,
			success: function(data) {
				$("#buttonEditTicket").attr('disabled', false);
				
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

	$( '.search-box-sel-all2' ).unbind();

});

$("body").on("click", "#buttonOkTicket", function() {
	var comment = prompt("Comments. (this will close the ticket)");
	
	if (comment === null) {
        return; //break out of the function early
    }

	var formData = new FormData();
	formData.append('id_ticket', $("#id_ticket").text());
	formData.append('message', comment.replace(/\"/g, '\\\"').replace(/'/g, '&#039'));

	$.ajax({
		url: './ticketok',
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

$("body").on("click", "#buttonNotOkTicket", function() {
	var comment = prompt("What is not ok?");
	
	if (comment === null) {
        return; //break out of the function early
    }

	var formData = new FormData();
	formData.append('id_ticket', $("#id_ticket").text());
	formData.append('message', comment.replace(/\"/g, '\\\"').replace(/'/g, '&#039'));

	$.ajax({
		url: './ticketnotok',
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

$("body").on("click", "#buttonSendedTicket", function() {
	var comment = prompt("Comments.");
	
	if (comment === null) {
        return; //break out of the function early
    }

	var formData = new FormData();
	formData.append('id_ticket', $("#id_ticket").text());
	formData.append('message', comment.replace(/\"/g, '\\\"').replace(/'/g, '&#039'));

	$.ajax({
		url: './infosent',
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
	var comment = prompt("Comments.");
	
	if (comment === null) {
        return; //break out of the function early
    }

	var formData = new FormData();
	formData.append('id_ticket', $("#id_ticket").text());
	formData.append('message', comment.replace(/\"/g, '\\\"').replace(/'/g, '&#039'));

	$.ajax({
		url: './closeticket',
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

$("body").on("click", "#buttonReopenTicket", function() {
	var comment = prompt("Comments.");
	
	if (comment === null) {
        return; //break out of the function early
    }

	var formData = new FormData();
	formData.append('id_ticket', $("#id_ticket").text());
	formData.append('message', comment.replace(/\"/g, '\\\"').replace(/'/g, '&#039'));

	$.ajax({
		url: './reopenticket',
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