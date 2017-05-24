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
					selectBoxLanguages += '<option value="' + json[1]['languages'][i]['id'] + '">' + json[1]['languages'][i]['name'] + '</option>';
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
				errorMessage();
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

			$(".hidebefore").show();
		});
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
				errorMessage();
			},
			cache: false,
			contentType: false,
			processData: false
		});

		$(document).one("ajaxStop", function() {
			$("#offersSb").trigger("chosen:updated");
		});
	}
});