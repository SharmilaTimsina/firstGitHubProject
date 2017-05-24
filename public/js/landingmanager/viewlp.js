$(document).ready(function() {

	checkParam();

	function checkParam() {
		lpId = gup('view', window.location);

		fillInformation(lpId)
		
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
				}

				if(json[0].name != '') {
					$("#inputName").val(json[0].name);
				}	

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
				}

			},	
			error: function(response) {
	
			},
			cache: false,
			contentType: false,
			processData: false
		});
	});

});