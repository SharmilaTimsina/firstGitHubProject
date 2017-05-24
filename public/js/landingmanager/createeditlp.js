$(document).ready(function() {

	fillSelects();

	function fillSelects() {

		$.ajax({
			url: './getDims',
			type: 'GET',
			async: true,
			success: function(data) {
				
				var json = $.parseJSON(data);

				var verticals = json[3]['verticals'];
				var servernames = json[2]['servername'];
				var domain = json[0]['domains'];
				var languages = json[1]['languages'];
			
				var selectBoxVerticals = '';
				for (var i = 0; i < verticals.length; i++) {
					selectBoxVerticals += '<option value="' + verticals[i]['id'] + '">' + verticals[i]['name'] + '</option>';
				}

				var selectBoxServerNames = '';
				for (var i = 0; i < servernames.length; i++) {
					selectBoxServerNames += '<option value="' + servernames[i]['id'] + '">' + servernames[i]['name'] + '</option>';
				}

				var selectBoxDomains = '';
				for (var i = 0; i < domain.length; i++) {
					selectBoxDomains += '<option value="' + domain[i]['id'] + '">' + domain[i]['name'] + '</option>';
				}

				var selectBoxLanguages = '';
				for (var i = 0; i < languages.length; i++) {
					selectBoxLanguages += '<option value="' + languages[i]['id'] + '">' + languages[i]['name'] + '</option>';
				}

				$("#verticalSB").append(selectBoxVerticals);
				$("#servernameSB").append(selectBoxServerNames);
				$("#domainSB").append(selectBoxDomains);
				$("#languagesSB").append(selectBoxLanguages);
					
			},	
			error: function(response) {
				alert("error");
			},
			cache: false,
			contentType: false,
			processData: false
		});

		$( document ).ajaxStop(function() {

			 window.searchSelAll = $('.search-box-sel-all').SumoSelect({
			        csvDispCount: 3,
			        selectAll: false,
			        search: true,
			        searchText: 'Enter here.',
			        okCancelInMulti: false
			    });

			$( '.search-box-sel-all' ).unbind();
			$( '.search-box-sel-all2' ).unbind();

			checkParam()
		});
	}

	function formValid() {
		if($("#namelp").val() == '') {
			alert('LP name missing!');
			return false;
		}

		if(getSumoSelects("#verticalSB", 5).length == 0 ) {
			alert('Vertical missing!');
			return false;
		}

		if(getSumoSelects("#servernameSB", 5).length == 0) {
			alert('Server name missing!');
			return false;
		}

		if(getSumoSelects("#domainSB", 5).length == 0) {
			alert('Domain missing!');
			return false;
		}

		if(getSumoSelects("#languagesSB", 5).length == 0) {
			alert('Language missing!');
			return false;
		}

		return true;
	}

	$("body").on("click", "#buttonCreateTicket", function() {

   		if(formValid()) {

   			$("#buttonCreateTicket").attr('disabled', true);

   			var formData = new FormData();
   			formData.append('name', $("#namelp").val());
   			formData.append('url', $("#urlLp").val());
   			formData.append('comments', $("#requestDetails").val());
   			formData.append('vertical', getSumoSelects("#verticalSB", 5));
   			formData.append('servername', getSumoSelects("#servernameSB", 5));
   			formData.append('domain', getSumoSelects("#domainSB", 5));
   			formData.append('language', getSumoSelects("#languagesSB", 5));
   	
   			if(editlp == true) {
   				formData.append('lpId', lpIdEdit);
   			}	
   			
   			$.ajax({
				url: './newEditLp',
				type: 'POST',
				data: formData,
				async: true,
				success: function(data) {
					if(data == "ok") {
						alert("Complete");
						window.location.replace('/landingmanager');
					}
				},
				error: function(response) {
					alert('error');
				},
				cache: false,
				contentType: false,
				processData: false
			});

   		}     	
	});
	
	var lpIdEdit;
	var editlp = false;
	function checkParam() {

 		if(editLpPhp == false) { //new
 			editlp = false;
 		} else {
 			editlp = true;
 			lpIdEdit = editLpID;
 		} 

    	if(editlp) {
    		$('select#verticalSB')[0].sumo.selectItem(jsonVar.vertical);
    		$('select#servernameSB')[0].sumo.selectItem(jsonVar.servername);
    		$('select#domainSB')[0].sumo.selectItem(jsonVar.domain);
    		$('select#languagesSB')[0].sumo.selectItem(jsonVar.languages);

    		var description;
			if(jsonVar.comments == null) {
				description = '';
			} else {
				description = jsonVar.comments;
			}
			$("#requestDetails").text(description);

    		$("#namelp").val(jsonVar.name);
    		$("#urlLp").val(jsonVar.url);
    	}
	}
});