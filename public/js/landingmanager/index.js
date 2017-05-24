$( '.search-box-sel-all' ).unbind();

table = $('#tableTicketsUserSide').DataTable( {
		  "pageLength": 100
		});	

function fillTable(jsonTable) {
	
	var jsonGlobal = $.parseJSON(jsonTable);

	var rows = '';
	for (var i = 0; i < jsonGlobal.length; i++) {
		var atagedit = '<a href="/landingmanager/createeditlp?idLp=' + jsonGlobal[i].id + '"><img style="cursor: pointer;"  title="" class="icontable" class="modalIcon44" src="/img/iconEdit.svg"></a>'

		rows += '<tr>'
				+ '<td><a class="infoToolTip" href="/landingmanager/getlpinfo?lpId=' + jsonGlobal[i].id + '">' + jsonGlobal[i].id + '</a></td>'
				+ '<td>' + jsonGlobal[i].name + '</td>'
				+ '<td>' + jsonGlobal[i].vertical + '</td>'
				+ '<td>' + jsonGlobal[i].language + '</td>'
				+ '<td><span class="urlcopyclip" data-clipboard-text="' + jsonGlobal[i].url + '">' + jsonGlobal[i].domain + '</span></td>'
				+ '<td>' + jsonGlobal[i].servername + '</td>'
				+ '<td>' + jsonGlobal[i].createdat + '</td>'
				+ '<td>' + jsonGlobal[i].createdby + '</td>'
				+ '<td>' + atagedit + '</td></tr>';


	}

	if(typeof table != 'undefined' && table != null)
		table.destroy();
	
	$('#tbodyLps').empty();
	$("#tbodyLps").append(rows);
	table = $('#tableTicketsUserSide').DataTable( {
		  "pageLength": 100
		});		

	settooltip();

	$( '.search-box-sel-all' ).unbind();

}



function settooltip() {
	 $('.infoToolTip').each(function() {
	     $(this).qtip({
	        content: {
	            text: function(event, api) {
	                $.ajax({
	                    url: api.elements.target.attr('href') // Use href attribute as URL
	                })
	                .then(function(content) {
	                    // Set the tooltip content upon successful retrieval
	                    api.set('content.text', content);
	                }, function(xhr, status, error) {
	                    // Upon failure... set the tooltip content to error
	                    api.set('content.text', status + ': ' + error);
	                });
	    
	                return 'Loading...'; // Set some initial text
	            }
	        },
	        position: {
	            viewport: $(window)
	        },
	        style: 'qtip-wiki'
	     });
	});

	new Clipboard('.urlcopyclip');	


}

$("body").on("click", ".urlcopyclip", function() {
	var element = $(this);
	var old = $(this).text();
	$(this).text('copied');
	element.css('color', 'orange');

	setTimeout(function(){ element.css('color', 'black');  element.text(old); }, 500);


});

function getValueElement(selecBox, key) {
	return $("#" + selecBox + " option[value='" + key + "']").text()
}

$("body").on("click", "#buttonFilterLps", function() {

		var formData = new FormData();	
		formData.append('verticals' , getSumoSelects("#verticalSB", 1));
		formData.append('serversname' , getSumoSelects("#servernameSB", 1));
		formData.append('domains' , getSumoSelects("#domainSB", 1));
		formData.append('languages' , getSumoSelects("#languagesSB", 1));

		$.ajax({
			url: 'landingmanager/setFilter',
			type: 'POST',
			data: formData,
			async: true,
			success: function(data) {
				
				fillTable(data);

			},	
			error: function(response) {
				alert("error");
			},
			cache: false,
			contentType: false,
			processData: false
		});

		$( '.search-box-sel-all' ).unbind();
});


fillSelects();

function fillSelects() {

	$.ajax({
		url: 'landingmanager/getDims',
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

	});
}