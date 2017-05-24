$(document).ready(function() {

	fillSelects();

	function fillSelects() {

		$.ajax({
			url: '/landingmanager/getFilters',
			type: 'GET',
			async: true,
			success: function(data) {
				
				var json = $.parseJSON(data);

				var selectBoxVerticals = '';
				for (var i = 0; i < json[0]['verticals'].length; i++) {
					selectBoxVerticals += '<option value="' + json[0]['verticals'][i]['id'] + '">' + json[0]['verticals'][i]['name'] + '</option>';
				}
				$("#verticalsSb").append(selectBoxVerticals);

				var selectBoxLanguages = '';
				for (var i = 0; i < json[1]['languages'].length; i++) {
					selectBoxLanguages += '<option value="' + json[1]['languages'][i]['id'].toUpperCase() + '">' + json[1]['languages'][i]['name'] + '</option>';
				}
				$("#languageSb").append(selectBoxLanguages);

				var selectBoxDomains = '';
				for (var i = 0; i < json[2]['domains'].length; i++) {
					selectBoxDomains += '<option value="' + json[2]['domains'][i]['id'] + '">' + json[2]['domains'][i]['name'] + '</option>';
				}
				$("#domainSb").append(selectBoxDomains);

				var selectBoxCountries = '';
				for (var i = 0; i < json[3]['geo'].length; i++) {
					selectBoxCountries += '<option value="' + json[3]['geo'][i]['id'] + '">' + json[3]['geo'][i]['id'] + ' - ' + json[3]['geo'][i]['name'] + '</option>';
				}
				$("#countriesSb").append(selectBoxCountries);

				var selectBoxEth = '';
				for (var i = 0; i < json[4]['ethnicity'].length; i++) {
					selectBoxEth += '<option value="' + json[4]['ethnicity'][i]['id'] + '">' + json[4]['ethnicity'][i]['name'] + '</option>';
				}
				$("#ethSb").append(selectBoxEth);

				var selectBoxClients = '';
				for (var i = 0; i < json[5]['clients'].length; i++) {
					selectBoxClients += '<option value="' + json[5]['clients'][i]['id'] + '">' + json[5]['clients'][i]['agregator'] + '</option>';
				}
				$("#clientsSb").append(selectBoxClients);

			},	
			error: function(response) {
	
			},
			cache: false,
			contentType: false,
			processData: false
		});

		$(document).one("ajaxStop", function() {
			$(".chosen-select-deselect").chosen({
				allow_single_deselect: true,
				no_results_text: "Nothing found!",
				width: "100%",
				search_contains: true
			})

			$(".chosen-select-deselect2").chosen({
				allow_single_deselect: true,
				no_results_text: "Nothing found!",
				width: "100%",
				search_contains: true
			}).change(function(event){

				if(event.target == this){
					getOffers()
				}

			});

			checkParam();

			$(".hidebefore").show();
		});
	}

	var typeLpAction = 'new';
	var lpId = 0;
	function checkParam() {
		var edit = gup('edit', window.location);
		var clone = gup('clone', window.location);
		//var new = gup('new', window.location);

		if(edit != null) {
			typeLpAction = 'edit';
			fillInformation(edit);
			$(".titlesPages").html('Edit PreLanding Page (' + edit + ')')
			$("#titleLp").html('Edit PreLanding (' + edit + ')')
			lpId = edit;
			$("#atargetview").attr('href', '/landingmanager/viewlp?lp=' + edit)
			$("#atargetclone").attr('href', '/landingmanager/newlp?clone=' + edit)
			$("#deletelp").attr('lpid', edit);
			$("#divEditIcons").show();
		} else if(clone != null) {
			typeLpAction = 'clone';
			fillInformation(clone);
			$(".titlesPages").html('Clone PreLanding Page (' + clone + ')')
			$("#titleLp").html('Clone PreLanding (' + clone + ')')
			lpId = clone;
			$("#atargetview2").attr('href', '/landingmanager/viewlp?lp=' + clone)
			$("#atargetedit2").attr('href', '/landingmanager/newlp?edit=' + clone)
			$("#deletelp2").attr('lpid', clone);
			$("#divCloneIcons").show();
		} else {
			$("#titleLp").html('New PreLanding')
			$("#hrefopen").remove();
		}
	}

	function fillInformation(value) {
		var formData = new FormData();
		formData.append('lpId', value);

		$.ajax({
			url: '/landingmanager/getLpInformation',
			type: 'POST',
			data: formData,
			async: true,
			success: function(data) {
				
				var json = $.parseJSON(data);

				if(json[0].verticals != '' && json[0].verticals != null) {
					var arrayVerticals = json[0].verticals.split(","); 
					$("#verticalsSb").val(arrayVerticals).trigger('chosen:updated');
				}

				if(json[0].clients != '' && json[0].clients != null) {
					var arrayClients = json[0].clients.split(","); 
					$("#clientsSb").val(arrayClients).trigger('chosen:updated');
				}

				if(json[0].ethnicity != '' && json[0].ethnicity != null) {
					var arrayEth = json[0].ethnicity.split(","); 
					$("#ethSb").val(arrayEth).trigger('chosen:updated');
				}

				if(json[0].languages != '' && json[0].languages != null) {
					var arrayLanguages = json[0].languages.split(","); 
					$("#languageSb").val(arrayLanguages).trigger('chosen:updated');
				}

				if(json[0].domain != '' && json[0].domain != null) {
					var arrayDomains = json[0].domain.split(","); 
					$("#domainSb").val(arrayDomains).trigger('chosen:updated');
				}

				if(json[0].countries != '' && json[0].countries != null) {
					var arrayCountries = json[0].countries.toUpperCase().split(","); 
					$("#countriesSb").val(arrayCountries).trigger('chosen:updated');
				}

				getOffers();

				$(document).one("ajaxStop", function() {
					if(json[0].offers != '' && json[0].offers != null) {
						var arrayOffers = json[0].offers.split(","); 
						$("#offersSb").val(arrayOffers).trigger('chosen:updated');
					}
				});

				if(json[0].comments != '') {
					$("#textareaComments").val(json[0].comments);
				}

				if(json[0].url != '') {
					$("#inputUrl").val(json[0].url);
					$("#copytoclip").attr('data-clipboard-text' , json[0].url);
					$("#hrefopen").attr('href' , json[0].url);
					$("#hrefopen").attr('already' , 1);
				} else {
					$("#hrefopen").attr('already' , 0);
				}

				if(json[0].name != '') {
					$("#inputName").val(json[0].name);
				}	

				$('.iconsTable').popover({ trigger: "hover" });
			},	
			error: function(response) {

			},
			cache: false,
			contentType: false,
			processData: false
		});

		$(document).one("ajaxStop", function() {
			$(".hidebefore").show();
		});
	}

	$("body").on("click", "#hrefopen", function() { 

		if($(this).attr('already') == 0 && $("#inputUrl").val() != '') {
			window.open($("#inputUrl").val(), '_blank');
		}
	});

	var clipboard = new Clipboard('.copyTo');

	function gup( name, url ) {
	    if (!url) url = location.href;
	    name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
	    var regexS = "[\\?&]"+name+"=([^&#]*)";
	    var regex = new RegExp( regexS );
	    var results = regex.exec( url );
	    return results == null ? null : results[1];
	}


	function getOffers() {
		
		if($("#countriesSb").chosen().val() == null && $("#clientsSb").chosen().val() == null) {
			$("#offersSb").html('<option></option>');
			$("#offersSb").trigger("chosen:updated");

			return
		} 

		var formData = new FormData();
		formData.append('geo', $("#countriesSb").chosen().val());
		formData.append('client', $("#clientsSb").chosen().val());

		$("#offersSb").html('<option></option>');

		$.ajax({
			url: '/landingmanager/getOfferByGeo',
			type: 'POST',
			data: formData,
			async: true,
			success: function(data) {
				
				var json = $.parseJSON(data);

				var selectBoxOffers = '';
				for (var i = 0; i < json.length; i++) {
					selectBoxOffers += '<option value="' + json[i]['hash'] + '">' + json[i]['campaign'] + '</option>';
				}
				$("#offersSb").append(selectBoxOffers);

			},	
			error: function(response) {
		
			},
			cache: false,
			contentType: false,
			processData: false
		});

		$(document).one("ajaxStop", function() {
			$("#offersSb").trigger("chosen:updated");
		});
	}

	$("body").on("click", "#buttonSave", function() { 

		var formData = new FormData();
		formData.append('verticals', $("#verticalsSb").chosen().val());
		formData.append('languages', $("#languageSb").chosen().val());
		formData.append('domains', $("#domainSb").chosen().val());
		formData.append('countries', $("#countriesSb").chosen().val());
		formData.append('ethnicity', $("#ethSb").chosen().val());
		formData.append('clients', $("#clientsSb").chosen().val());
		formData.append('offers', $("#offersSb").chosen().val());

		formData.append('name', $("#inputName").val());
		formData.append('url', $("#inputUrl").val());
		formData.append('comments', $("#textareaComments").val());

		formData.append('saveType', typeLpAction);
		formData.append('lpId', lpId);

		$.ajax({
			url: '/landingmanager/saveLp',
			type: 'POST',
			data: formData,
			async: true,
			success: function(data) {
				
				if(data == 1) {
					window.location = '/landingmanager';
				} else {
					alert("Error: duplicated URL");
				}

			},	
			error: function(response) {
	
			},
			cache: false,
			contentType: false,
			processData: false
		});
	});
	
	$("body").on("click", "#deletelp, #deletelp2", function() { 
	
		var r = confirm("Are you sure?");
		if (r == true) {
		    
		    var formData = new FormData();
			formData.append('lpid', $(this).attr('lpid'));

		    $.ajax({
				url: '/landingmanager/deleteLp',
				type: 'POST',
				data: formData,
				async: true,
				success: function(data) {
					
					$("#buttonFilter").click();
					
				},	
				error: function(response) {
					
				},
				cache: false,
				contentType: false,
				processData: false
			});

		} else {
		   
		}

	});
	

});