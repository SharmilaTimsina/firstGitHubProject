var selectBoxCountry = '';
var selectBoxSources = '';
var selectBoxAreas = '';

$(document).ready(function() {
	
	fillSelects();

	function fillSelects() {
		var countries = countriesvar;
		var sources = sourcesvar;
		var areas = areasvar;
	
		for (var i = 0; i < countries.length; i++) {
			selectBoxCountry += '<option value="' + countries[i]['id'] + '">' + countries[i]['id'] + ' - ' + countries[i]['name'] + '</option>';
		}

		for (var i = 0; i < sources.length; i++) {
			selectBoxSources += '<option value="' + sources[i]['id'] + '">' + sources[i]['id'] + ' - ' + sources[i]['sourceName'] + '</option>';
		}

		for (var i = 0; i < areas.length; i++) {
			selectBoxAreas += '<option value="' + areas[i]['id'] + '">' + areas[i]['name'] + '</option>';
		}

		$("#countrySideBar").append(selectBoxCountry);
		$("#sourcesSideBar").append(selectBoxSources);
		
		$(".chosen-select-deselect").chosen({
			allow_single_deselect: true,
			no_results_text: "Nothing found!",
			width: "100%",
			search_contains: true
		}).change(function(event){

			if(event.target == this){
				getNjumps()
			}

		});
	}

	function getNjumps() {
		
		$("#myUL").empty();
      	$("#spinner").show();
      	$("#myUL2").hide();
      	$("#style-1").hide();

		$.ajax({
			url: '/njump/getNjumps?searchquery=' + $("#myInput").val() + '&country=' + $("#countrySideBar").chosen().val() + '&source=' + $("#sourcesSideBar").chosen().val(),
			type: 'GET',
			async: true,
			success: function(data) {
				
				 if(data != '') {
	                $("#myUL").empty().html(data)
	                $("#spinner").hide();
	                $("#style-1").show();
	                $("#style-1").height($( "#sidebar-wrapper" ).height() - 240);

	                $( ".resultsSearch" ).bind( "click", function() {
	                    firechange(this);
                            event.preventDefault()	
						event.stopPropagation();

						var hash = $(this).attr("hash");
						var country = $("#countrySideBar").chosen().val();
						var source = $("#sourcesSideBar").chosen().val();
						var text = $("#myInput").val();

						var allContent = $('#sidebar-wrapper').html();
						localStorage.setItem('allSidebar', allContent);

						var hash = $(this).attr("hash");
						window.location.replace('/njump/njumpedit?njumphash=' + hash + '&red=1&country=' + country + '&source=' + source + '&text=' + text);
	                
	                });
	              }
	              else {
	              	 if($("#countrySideBar").chosen().val() == '' && $("#sourcesSideBar").chosen().val() == '') {
	                	$("#spinner").hide();
	                	$("#firstrowresults").text('- no filter applied -').css("color", "grey");
			            $("#firstrowresults").css("font-weight", "lighter");
			            $("#myUL2").show();
			            $("#style-1").hide();
			          } else {
			          	$("#spinner").hide();
			          	$("#firstrowresults").text('- no results -').css("color", "red");
		                $("#firstrowresults").css("font-weight", "700");
		                $("#myUL2").show();
		                $("#style-1").hide();
			          }
	              }
				
			},	
			error: function(response) {
				errorMessage();
			},
			cache: false,
			contentType: false,
			processData: false
		});
	}

	var njumpsParsed = '';
    for (var i = 0; i < njumps.length; i++) {

    	var star = '';
    	if(njumps[i].favorite == 1) {
    		star = 'starpower_filled.svg';
    	} else {
    		star = 'starpower_hollow.svg';
    	}

    	var status = '';
    	if(njumps[i].status == 1) {
    		//verde 
			status = 'background-color:lime';
    	} else {
    		//vermelho
    		status = 'background-color:red';
    	}

    	var offer1 = '';
    	var offer2 = '';
    	var iconOffers = '';
    	if(njumps[i].offername != '' && njumps[i].offername != null) {
    		var offers = njumps[i].offername.split(',');

    		if(offers.length > 2)
    			iconOffers = '<img class="imgdots" data-placement="top" data-toggle="popover" title="Offers" data-content="' + njumps[i].offername + '" src="/img/njumppage/dots.svg">'

    		offer1 = offers[0];
    		offer2 = offers[1];

    		if (typeof offer1 === "undefined") 
    			offer1 = '';

    		if (typeof offer2 === "undefined") 
    			offer2 = '';
    	} 

    	var carrier1 = '';
    	var carrier2 = '';
    	var iconCarriers = '';
    	if(njumps[i].carrier != '' && njumps[i].carrier != null) {
    		
    		var carriers = njumps[i].carrier.split(',');

    		if(carriers.length > 2)
    			iconCarriers = '<img data-placement="top" data-toggle="popover" title="Carriers" data-content="' + njumps[i].carrier + '" class="imgdots" src="/img/njumppage/dots.svg">'

    		carrier1 = carriers[0];
    		carrier2 = carriers[1];

    		if (typeof carrier1 === "undefined") 
    			carrier1 = '';

    		if (typeof carrier2 === "undefined") 
    			carrier2 = '';
    	} 

    	var badNjump = '';
    	if(njumps[i].status > 1) {
    		badNjump = '<img class="iconsDivObject iconNotClickable" data-toggle="popover" data-placement="top"  title="" data-content="bad njump" data-clipboard-text="" src="/img/njumppage/youmesseditup_wht.svg">'
    	}
    	
    	var urlNjumpToCopy = njumps[i].url;

		njumpsParsed += '<div class="col-lg-3">'
						+'<div class="objectfavorite" njumphash="' + njumps[i].njumphash + '" country="' + njumps[i].country.toUpperCase() + '" nameNjump="' + njumps[i].generatedname + '">'
							+'<div class="row">'
								+'<div class="topDiv">'
									+'<div class="row">'
										+'<div class="njumpgeneratednameDivObject"  data-toggle="popover" title="" data-placement="top"  data-content="' + njumps[i].generatedname + '">' + njumps[i].generatedname + '</div>'
										+'<div style="' + status + '" class="statusDivObject"><div style="height: 17px;width: 18px;"></div><img class="starObject"  data-toggle="popover" data-placement="top"  title="" data-content="favorite" favoriteType="' + njumps[i].favorite + '" favorite="' + njumps[i].njumphash + '" src="/img/njumppage/' + star + '"></div>'
										
									+'</div>'
									+'<div class="row">'
									+	'<div class="globalnameDivObject" data-toggle="popover" data-placement="top"  title="" data-content="' + njumps[i].globalname + '">' + njumps[i].globalname + '</div>'
									+'</div>'
								+'</div>'
							+'</div>'
							+'<div class="row" style="margin-top: 13px;">'
						+'<div class="col-lg-2 colTableLastToday3">'
							+'<table>'
								+'<tr>'
								+	'<td></td>'
								+'</tr>'
								+'<tr>'
									+'<td>REVENUE</td>'
								+'</tr>'
								+'<tr>'
									+'<td>CLICKS</td>'
								+'</tr>'
								+'<tr>'
									+'<td>EPC</td>'
								+'</tr>'
							+'</table>'
						+'</div>'
						+'<div class="col-lg-10 colTableLastToday2">'
							+'<table class="tablelasttoday">'
								+'<tr>'
									+'<td>TODAY</td>'
								+'</tr>'
								+'<tr>'
									+'<td>$' + njumps[i].rev + '</td>'
								+'</tr>'
								+'<tr>'
									+'<td>' + njumps[i].clicks + '</td>'
								+'</tr>'
								+'<tr>'
									+'<td>' + njumps[i].epc + '</td>'
								+'</tr>'
							+'</table>'
							+'<table  class="tablelasttoday">'
								+'<tr>'
									+'<td>LAST 3 DAYS</td>'
								+'</tr>'
								+'<tr>'
									+ '<td>$' + njumps[i].rev3days + '</td>'
								+'</tr>'
								+'<tr>'
									+'<td>' + njumps[i].clicks3days + '</td>'
								+'</tr>'
								+'<tr>'
								+	'<td>' + njumps[i].epc3days + '</td>'
								+'</tr>'
							+'</table>'
						+'</div>'
					+'</div>'
					+'<div class="row" id="rowTopWorst">'
						+'<section class="sectionWT"><div class="topWorst">TOP</div><div class="offersWorstTp">' + ((njumps[i].topoffer == null) ? '' : njumps[i].topoffer) + '</div></section>'
						
						+'<section style="margin-top: -17px;" class="sectionWT"><div class="topWorst">WORST</div><div class="offersWorstTp">' + ((njumps[i].worstoffer == null) ? '' : njumps[i].worstoffer) + '</div></section>'
					+'</div>'
							+'<div class="row">'
								+'<div class="divIconsObject">'
									+'<img class="iconsDivObject deleteNjump" src="/img/njumppage/trash.svg">'
									+'<img class="iconsDivObject cloneNjumpAction" src="/img/njumppage/clone.svg">'
									+'<a href="/njump/njumpedit?njumphash=' + njumps[i].njumphash + '"><img class="iconsDivObject editNjumpAction" src="/img/njumppage/edit.svg"></a>'
									+'<img class="iconsDivObject copyTo" data-toggle="popover" data-placement="top"  title="" data-content="copy njump link to clipboard" data-clipboard-text="' + urlNjumpToCopy + '" src="/img/njumppage/copy.svg">'
								+'</div>'
								+'<div class="divIconsObject2">'
									+ badNjump
								+'</div>'
							+'</div>'
						+'</div>'
					+'</div>';
	}

	$("#ObjectsRow").empty();
	$("#ObjectsRow").append(njumpsParsed);

	$("body").on("click", ".starObject", function() { 
		var hash = $(this).attr('favorite');
		var fav = $(this).attr('favoriteType');

		var ob = $(this);

		$(document).one("ajaxStart", function() {
			//ALERT
			savingMessage();
		});

		var status = 0;
		$.ajax({
			url: '/njump/favoriteNjump?njumphash=' + hash,
			type: 'GET',
			async: true,
			success: function(data) {
				if(data != 1) {

					status = 1;

					if(fav == 1) {
						$(ob).attr("src", '/img/njumppage/starpower_hollow.svg')
						$(ob).attr("favoriteType", '0')
					} else {
						$(ob).attr("src", '/img/njumppage/starpower_filled.svg')
						$(ob).attr("favoriteType", '1')
					}
				} else {
					errorMessage();
				}
			},	
			error: function(response) {
				errorMessage();
			},
			cache: false,
			contentType: false,
			processData: false
		});

		$(document).one("ajaxStop", function() {
			//ALERT
			if(status == 1){
				savedMessage();
			}
		});
	});

	$("body").on("click", "#newnjumpimg", function() { 
		createNewNjump()
	});

	$("body").on("click", ".cloneNjumpAction", function() { 
		$("#countryCloneModal").empty();
		$("#sourceCloneModal").empty();
		$("#areaCloneModal").empty();
		$("#nameCloneModal").val('');

		var s = '<option></option>'
		$("#countryCloneModal").append(s + selectBoxCountry);
		$("#sourceCloneModal").append(s + selectBoxSources);
		
		var country = $(this).closest('.objectfavorite').attr("country");
	
		$("#countryCloneModal").val(country);
		$('#countryCloneModal').attr('disabled', true).trigger("chosen:updated");

		$("#sourceCloneModal").val('');
		$('#sourceCloneModal').trigger("chosen:updated");

		$("#areaCloneModal").val('');
		$('#areaCloneModal').trigger("chosen:updated");

		if(selectBoxAreas != '') {
			$("#areaCloneModal").append(s + selectBoxAreas);
			$("#areaCloneModal").val('');
			$('#areaCloneModal').trigger("chosen:updated");
		} else {
			$("#rowAreaModalClone").remove();
		}
				
		$(".chosen-select-deselect3").chosen({
			allow_single_deselect: true,
			no_results_text: "Nothing found!",
			width: "100%",
			search_contains: true
		})

		$("#modalCloneNJump").modal('show')
	});

	var deleteHash = '';
	$("body").on("click", ".deleteNjump", function() { 
		
		var generatedname = $(this).closest('.objectfavorite').attr("nameNjump");
	
		if(generatedname != '') {
			$("#nameNjumpDelete").text(generatedname);

			$("#modalDeleteNJump").modal('show');

			deleteHash = $(this).closest('.objectfavorite').attr("njumphash");
		} 
	});

	var clipboard = new Clipboard('.copyTo');

	$("body").on("click", ".copyTo", function () {
        var element = $(this);
        
        var oldSrc = '/img/njumppage/copy.svg?'+Math.random();

        element.attr('src', '/img/njumppage/copied.svg?'+Math.random());
        
        setTimeout(function () {
            element.attr('src', oldSrc);
        }, 500);
    });

	$("body").on("click", "#okModalButtonSave", function () {
	    
		var formData = new FormData();
		formData.append('country', $("#countryNewModal").chosen().val());
		formData.append('source', $("#sourceNewModal").chosen().val());
		formData.append('area', $("#areaNewModal").chosen().val());
		formData.append('name', $("#nameNewModal").val());

		$(document).one("ajaxStart", function() {
			//ALERT
			savingMessage();
		});

	    $.ajax({
			url: '/njump/newnjump',
			type: 'POST',
			data: formData,
			async: true,
			success: function(data) {
				if(data != 1) {
					
					window.location.replace('/njump/njumpedit?njumphash=' + data);
				
				} else {
					errorMessage();
				}
			},	
			error: function(response) {
				errorMessage();
			},
			cache: false,
			contentType: false,
			processData: false
		});
	});

	$("body").on("click", "#okModalButtonClone", function () {
		var formData = new FormData();
		formData.append('country', $("#countryCloneModal").chosen().val());
		formData.append('source', $("#sourceCloneModal").chosen().val());
		formData.append('area', $("#areaCloneModal").chosen().val());
		formData.append('name', $("#nameCloneModal").val());
		formData.append('clonehash', cloneHash);

		$(document).one("ajaxStart", function() {
			//ALERT
			savingMessage();
		});

	    $.ajax({
			url: '/njump/njumpclone',
			type: 'POST',
			data: formData,
			async: true,
			success: function(data) {
				if(data != 1) {
					
					window.location.replace('/njump/njumpedit?njumphash=' + data);
				
				} else {
					errorMessage();
				}
			},	
			error: function(response) {
				errorMessage();
			},
			cache: false,
			contentType: false,
			processData: false
		});
	});

	var cloneHash = '';
	$("body").on("click", ".cloneNjumpAction", function () {
		cloneHash = $(this).closest('.objectfavorite').attr("njumphash");
	});


	$("body").on("click", "#deleteModalButton", function () {

		$(document).one("ajaxStart", function() {
			//ALERT
			savingMessage();
		});

	    $.ajax({
			url: '/njump/njumpdelete?njumphash=' + deleteHash,
			type: 'GET',
			async: true,
			success: function(data) {
				if(data != 1) {
					
					location.reload();
				
				} else {
					errorMessage();
				}
			},	
			error: function(response) {
				errorMessage();
			},
			cache: false,
			contentType: false,
			processData: false
		});
	});


	$("body").on("click", ".editNjumpAction", function () {
		editNjumpIcon(this, 1);
	});

	function editNjumpIcon(e, type) {
		var hash = $(e).closest('.objectfavorite').attr("njumphash");
		console.log('bonga'+type);
		var country = $("#countrySideBar").chosen().val();
		var source = $("#sourcesSideBar").chosen().val();
		var text = $("#myInput").val();

		var allContent = $('#sidebar-wrapper').html();
	    localStorage.setItem('allSidebar', allContent);

	    var hash = $(e).closest('.objectfavorite').attr("njumphash");
	    
	    if(type == 1) {
	    	window.location.replace('/njump/njumpedit?njumphash=' + hash + '&red=1&country=' + country + '&source=' + source + '&text=' + text);
	    } 	    
	}

	$("body").on("click", "#resetFilters", function () { 
		$("#countrySideBar").val('').trigger('chosen:updated');
		$("#sourcesSideBar").val('').trigger('chosen:updated');
		$("#myInput").val('');

		var e = jQuery.Event("keyup");
		e.which = 40;
		$("#myInput").trigger(e)

		getNjumps()
	});

	function savedMessage() {
		$('.notifyjs-wrapper').trigger('notify-hide');
		setTimeout(function(){
			$.notify("Saved", "success");
		}, 300);
	}

	function errorMessage() {
		$('.notifyjs-wrapper').trigger('notify-hide');
		setTimeout(function(){
			$.notify("error!", "error");
		}, 300);
	}

	function savingMessage() {
		$('.notifyjs-wrapper').trigger('notify-hide');
		$.notify("Warning: Saving", { 
			clickToHide: false,
			autoHide: false,
			autoHideDelay: 500,
			arrowShow: true,
			arrowSize: 5,
			breakNewLines: true,
			elementPosition: "bottom",
			globalPosition: "top center",
			style: "bootstrap",
			className: "warn",
			showAnimation: "slideDown",
			showDuration: 100,
			hideAnimation: "slideUp",
			hideDuration: 100,
			gap: 5
		});
	}

	$(document).keyup(function(e) {
	     if (e.keyCode == 27) { // escape key maps to keycode `27`
	        if($('.modal').is(':visible')) {
	        	$('.modal').modal("hide");
	        }
	    }
	});
});


function createNewNjump() {

	if($('#modalCreateNJump').is(':visible')) {
		$("#modalCreateNJump").modal('hide')
		return;
	}

	$("#countryNewModal").empty();
	$("#sourceNewModal").empty();
	$("#areaNewModal").empty();
	$("#nameNewModal").val('');

	var s = '<option></option>'
	$("#countryNewModal").append(s + selectBoxCountry);
	$("#sourceNewModal").append(s + selectBoxSources);
	
	$("#countryNewModal").val('');
	$('#countryNewModal').trigger("chosen:updated");

	$("#sourceNewModal").val('');
	$('#sourceNewModal').trigger("chosen:updated");

	$("#areaNewModal").val('');
	$('#areaNewModal').trigger("chosen:updated");

	if(selectBoxAreas != '') {
		$("#areaNewModal").append(s + selectBoxAreas);
		$("#areaNewModal").val('');
		$('#areaNewModal').trigger("chosen:updated");
	} else {
		$("#rowAreaModalNew").remove();
	}
			
	$(".chosen-select-deselect2").chosen({
		no_results_text: "Nothing found!",
		width: "100%",
		search_contains: true,
		allow_single_deselect: true
	})

	$("#modalCreateNJump").modal('show')
}