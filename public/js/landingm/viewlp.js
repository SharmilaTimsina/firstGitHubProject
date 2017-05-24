$(document).ready(function() {

	checkParam();

	function checkParam() {
		lpId = gup('lp', window.location);

		fillInformation(lpId)
		
	}

	function fillInformation(value) {
		var formData = new FormData();
		formData.append('lpId', value);

		$.ajax({
			url: '/landingmanager/getLpInformationView',
			type: 'POST',
			data: formData,
			async: true,
			success: function(data) {
				
				var json = $.parseJSON(data);

				$("#verticalsgi").html(json.verticals);
				$("#languagesgi").html(json.languages);
				$("#domainsgi").html(json.domain);
				$("#countriesgi").html(json.countries);
				$("#ethgi").html(json.ethnicity);

				$("#clientsgi").html(json.clients);
				$("#offersgi").html(json.offers);
				$("#namegi").html(json.name);
				$("#commentsgi").text(json.comments);
				$("#urlgi").html(json.url);

				$("#imgpreviewlp").attr('src', 'http://api.screenshotlayer.com/api/capture?access_key=62b1c5cf63041dccbaa7a6fdd64b1556&url=' + json.url + '&viewport=360x640&width=');
				$(".previewObjecttop").append('<img data-toggle="popover" data-placement="top" data-content="copy to clipboard" data-original-title="" class="iconsTable tablecopyurlto copyTo" data-clipboard-text="' + json.url + '" src="/img/njumppage/copy.svg">');

				$("#titleLp").html('View PreLanding (' + json.id + ')')
				$("#titlepage").html('View PreLanding (' + json.id + ')')

				$("#urltoppreview").html(json.url);

				$("#atargetclone").attr('href', '/landingmanager/newlp?clone=' + json.id)
				$("#deletelp").attr('lpid', json.id);
				$("#atargetedit").attr('href', '/landingmanager/newlp?edit=' + json.id)
				$("#hrefopen").attr('href', json.url)

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

	var clipboard = new Clipboard('.copyTo');

	function gup( name, url ) {
	    if (!url) url = location.href;
	    name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
	    var regexS = "[\\?&]"+name+"=([^&#]*)";
	    var regex = new RegExp( regexS );
	    var results = regex.exec( url );
	    return results == null ? null : results[1];
	}

	$("body").on("click", "#deletelp", function() { 
	
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