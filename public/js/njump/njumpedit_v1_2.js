var selectBoxCountry = '';
var selectBoxSources = '';
var selectBoxAreas = '';
var selectBoxDomains = '';
var selectBoxCarriers = '';
var selectBoxOffers = '';
var selectBoxLP = '';
var selectBoxISP = '';
var selectBoxos = '';
var selectBoxSback = '';
var myHash = '';
var carriersJsObject = {};
var selectedLines = [];
var imgMultiCarriers = '<img data-toggle="popover" title="" data-html="true" data-placement="top" data-content="<span class=\'carriersInsidePopover\'></span>" data-original-title="" class="iconMultiCarriers" src="/img/njumppage/trident.svg">';

$(document).ready(function() {
	
	var mySource = globalsourcevar;

	fillSelects();

	function fillSelects() {
		var countries = countriesvar;
		var sources = sourcesvar;
		var areas = areasvar;
		var lps = lpsvar;
		var isps = ispsvar;
		var sbacks = sbacksvar;
		var os = osvar;
	
		for (var i = 0; i < countries.length; i++) {
			selectBoxCountry += '<option value="' + countries[i]['id'] + '">' + countries[i]['id'] + ' - ' + countries[i]['name'] + '</option>';
		}

		for (var i = 0; i < sources.length; i++) {
			selectBoxSources += '<option value="' + sources[i]['id'] + '">' + sources[i]['id'] + ' - ' + sources[i]['sourceName'] + '</option>';
		}

		for (var i = 0; i < areas.length; i++) {
			selectBoxAreas += '<option value="' + areas[i]['id'] + '">' + areas[i]['name'] + '</option>';
		}

		for (var i = 0; i < domainsvar.length; i++) {
			selectBoxDomains += '<option value="' + domainsvar[i]['id'] + '">' + domainsvar[i]['domain'] + '</option>';
		}

		for (var i = 0; i < carriersvar.length; i++) {
			selectBoxCarriers += '<option value="' + carriersvar[i]['id'] + '">' + carriersvar[i]['name'] + '</option>';
			carriersJsObject[carriersvar[i]['id']] = carriersvar[i]['name'];
		}

		for (var i = 0; i < offersvar.length; i++) {
			selectBoxOffers += '<option url="' + offersvar[i]['url'] + '" value="' + offersvar[i]['hash'] + '">' + offersvar[i]['campaign'] + '</option>';
		}

		for (var i = 0; i < lps.length; i++) {
			selectBoxLP += '<option url="' + lps[i]['url'] + '" value="' + lps[i]['id'] + '">' + lps[i]['id'] + ' - ' + lps[i]['name'] +  '</option>';
		}

		for (var i = 0; i < isps.length; i++) {
			selectBoxISP += '<option value="' + isps[i]['id'] + '">' + isps[i]['name'] + '</option>';
		}

		for (var i = 0; i < os.length; i++) {
			selectBoxos += '<option value="' + os[i]['id'] + '">' + os[i]['name'] + '</option>';
		}

		for (var i = 0; i < sbacks.length; i++) {
			selectBoxSback += '<option value="' + sbacks[i]['id'] + '">' + sbacks[i]['name'] + '</option>';
		}

		checkParam();
	
	}

	var red = false;
	function checkParam() {
		var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1);
        var splitHashes = hashes.split('&');

        //hash = splitHashes[0].split('=');

        if (typeof splitHashes[1] != 'undefined') {
        	if(splitHashes[1].split('=')[0] == 'red') {
        		
	    		var url = window.location.href;
				url = url.slice( 0, url.indexOf('&') );
			
				window.history.pushState('', '', url);

				red = true;
	    	
	    	} 
        }
        
        if(red) {
			if(localStorage.getItem('allSidebar')) {
				$('#sidebar-wrapper').html('');
				$('#sidebar-wrapper').html(localStorage.getItem('allSidebar'));

				$("#sourcesSideBar_chosen").remove();
				$("#countrySideBar_chosen").remove();

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

				if(typeof $('#selectedFocus').offset() != 'undefined') {
					if($('#sidebar-wrapper').html() != '') {
						var container = $('#style-1'),
						scrollTo = $('#selectedFocus');

						container.animate({
						    scrollTop: scrollTo.offset().top - container.offset().top + container.scrollTop()
						});
					}
				}
				
			} 
        } else {
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

		for (i = 0; i < splitHashes.length; i++) {
			if(splitHashes[i].split('=')[0] == 'country') {
        		$("#countrySideBar").val(splitHashes[i].split('=')[1]);
				$('#countrySideBar').trigger("chosen:updated");

        	} else if(splitHashes[i].split('=')[0] == 'source') {
 				$("#sourcesSideBar").val(splitHashes[i].split('=')[1]);
				$('#sourcesSideBar').trigger("chosen:updated");

        	} else if(splitHashes[i].split('=')[0] == 'text') {
        		
        		$("#myInput").val(splitHashes[i].split('=')[1]);

        	} else if(splitHashes[i].split('=')[0] == 'njumphash') {
      
        		myHash = splitHashes[i].split('=')[1];

        	}
		}
        
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

	$("body").on("click", ".favoriteStar", function() { 
		var hash = myHash;
		var fav = favoritevar;

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
						$(ob).attr("src", '/img/njumppage/starpower_empty_edit.svg')
						favoritevar = 0;
					} else {
						$(ob).attr("src", '/img/njumppage/starpower_full_edit.svg')
						favoritevar = 1;
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
		createNewNjump();
	});


	$("body").on("click", ".cloneNjumpAction", function() { 
		$("#countryCloneModal").empty();
		$("#sourceCloneModal").empty();
		$("#areaCloneModal").empty();
		$("#nameCloneModal").val('');

		var s = '<option></option>'
		$("#countryCloneModal").append(s + selectBoxCountry);
		$("#sourceCloneModal").append(s + selectBoxSources);
		
		var country = countryname[0];
	
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
		
		var generatedname = njumpgeneratednamevar;
	
		if(generatedname != '') {
			$("#nameNjumpDelete").text(generatedname);

			$("#modalDeleteNJump").modal('show');

			deleteHash = myHash;
		} 
	});

	var clipboard = new Clipboard('.copyTo');
	var clipboardoffer = new Clipboard('.iconNewTab3');

	$(document).keydown(function(evt){
	    if(evt.keyCode==65 && (evt.ctrlKey) && (evt.altKey)) {
	    	evt.preventDefault();
	    	//CTRL+ALT+A
	    	copyTo2();
	    }
	});

	$("body").on("click", ".copyTo", function () {        
        copyTo();
    });
	$("body").on("click", ".iconNewTab3", function () {   
            
        copyToOffer(this);
    });

  	function copyTo() {
  		element = $(".copyTo:eq(0)");

  		var dd = '';
  		if(typeof $("#selectsDomainsInfoRow option:selected").html() == "undefined") {
  			dd = 'youmobistein.com';
  		} else {
  			dd = $("#selectsDomainsInfoRow option:selected").html();
  		}

  		element.attr('data-clipboard-text', 'http://njump.' + dd  + '/?jp=' + myHash + paramsvar + '&linkref=' + mySource + '_');

        var oldSrc = '/img/njumppage/copy.svg?'+Math.random();

        element.attr('src', '/img/njumppage/copied.svg?'+Math.random());
        
        setTimeout(function () {
            element.attr('src', oldSrc);
        }, 500);
  	}
        
  	function copyToOffer(sourceelement) {
  		
                offer = $(sourceelement).parent().children(0).find(":selected").text();
                
  		$(sourceelement).attr('data-clipboard-text', offer);

                var oldSrc = '/img/njumppage/clone_name.svg?'+Math.random();

                $(sourceelement).attr('src', '/img/njumppage/copied.svg?'+Math.random());
        
        setTimeout(function () {
            $(sourceelement).attr('src', oldSrc);
        }, 500);
  	}

  	function copyTo2() {
  		element = $(".copyTo:eq(0)");

  		var dd = '';
  		if(typeof $("#selectsDomainsInfoRow option:selected").html() == "undefined") {
  			dd = 'youmobistein.com';
  		} else {
  			dd = $("#selectsDomainsInfoRow option:selected").html();
  		}

  		element.attr('data-clipboard-text', 'http://njump.' + dd  + '/?jp=' + myHash + paramsvar + '&linkref=' + mySource + '_');

        var oldSrc = '/img/njumppage/copy.svg?'+Math.random();

        element.attr('src', '/img/njumppage/copied.svg?'+Math.random());
        
        setTimeout(function () {
            element.attr('src', oldSrc);
        }, 500);

        $(".copyTo").click();
  	}

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
		formData.append('clonehash', myHash);

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
					
					window.location.replace('/njump');
				
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

	$("body").on("click", "#resetFilters", function () { 
		$("#countrySideBar").val('').trigger('chosen:updated');
		$("#sourcesSideBar").val('').trigger('chosen:updated');
		$("#myInput").val('');

		var e = jQuery.Event("keyup");
		e.which = 40;
		$("#myInput").trigger(e)

		getNjumps()
	});
	
	setinit();

	function setinit() {
		if(statusvar == 1) {
			//verde 
			$("#statusDivRow").css('background-color', 'lime');
		} else {
			//vermelho
			$("#statusDivRow").css('background-color', 'red');
		}

		if(favoritevar == 1) {
			
			$(".favoriteStar").attr('src', '/img/njumppage/starpower_full_edit.svg');
		} else {

			$(".favoriteStar").attr('src', '/img/njumppage/starpower_empty_edit.svg');
		}
		
		$("#countrycontentname").text(countryname[1]);

		$("#selectsDomainsInfoRow").append(selectBoxDomains);

		$("#selectsDomainsInfoRow").chosen({
			allow_single_deselect: true,
			no_results_text: "Nothing found!",
			width: "85%",
			search_contains: true
		}).change(function(event){

			if(event.target == this){
				changeDomain(this)
			}

		});	

		$('#selectsDomainsInfoRow').val(domainvar).trigger('chosen:updated');

		var badNjump = '';
		if(statusvar > 1) {
			badNjump =  '<img class="iconsDivObject51" data-toggle="popover" data-placement="bottom"  title="" data-content="check rows status" data-clipboard-text="" src="/img/njumppage/youmesseditup_wht.svg">'
		} else {
			badNjump =  '<img style="display: none" class="iconsDivObject51" data-toggle="popover" data-placement="bottom"  title="" data-content="check rows status" data-clipboard-text="" src="/img/njumppage/youmesseditup_wht.svg">'
		}

		//$("#njumpGaneratedName").html(njumpgeneratednamevar + ' | ' + globalnamevar + '		' + badNjump);
		$("#njumpgeneratednamevar").html(njumpgeneratednamevar);
		$("#globalnamevar").html(globalnamevar);
		$("#badnjumpgeneratedicon").html(badNjump);
		$("#categorycontentname").text(areaname);
		$("#sourcecontentname").text(sourcenamevar);

		$("#selectoffersnewrow").append(selectBoxOffers);
		$("#selectoffersnewrow").chosen({
			allow_single_deselect: true,
			no_results_text: "Select offer!",
			width: "100%",
			search_contains: true
		})

		$(".selectsBoxRowsEditNjump").chosen({
			allow_single_deselect: true,
			no_results_text: "Nothing found!",
			width: "85%",
			search_contains: true
		})

		$("#selectBoxSortBy").chosen({
			allow_single_deselect: true,
			no_results_text: "Nothing found!",
			width: "85%",
			search_contains: true
		}).change(function(event){

			if(event.target == this){
				changeSorter()
			}

		})

		setlines();
	}

	$("body").on("click", "#orderIcon", function () { 
		if($("#orderIcon").hasClass('ASC')) {
			$("#orderIcon").removeClass('ASC')
			$("#orderIcon").addClass('DESC')
			$("#orderIcon").removeClass('over')
			$("#orderIcon").addClass('out')
			$("#orderIcon").attr('data-content', 'DESC')
		} else if($("#orderIcon").hasClass('DESC')) {
			$("#orderIcon").removeClass('DESC')
			$("#orderIcon").addClass('ASC')
			$("#orderIcon").attr('data-content', 'ASC')
			$("#orderIcon").removeClass('out')
			$("#orderIcon").addClass('over')
		}

		changeSorter();
	});

	function changeSorter() {
		var sorterId = $("#selectBoxSortBy").chosen().val();
		var hash = myHash;
		var order = '';

		if(sorterId == '') {
			sorterId = 1;
			$('#selectBoxSortBy').val(sorterId).trigger('chosen:updated');
		}

		$(document).one("ajaxStart", function() {
			//ALERT
			sortingMessage('Sorting!', 1);
		});
		
		if($("#orderIcon").hasClass('ASC')) {
			order = 0;
		} else if($("#orderIcon").hasClass('DESC')) {
			order = 1;
		}

		var formData = new FormData();
		formData.append('njumphash', hash);
		formData.append('sorter', sorterId);
		formData.append('order', order);

		$.ajax({
			url: '/njump/njumpsortby',
			type: 'POST',
			data: formData,
			async: true,
			success: function(data) {
				
				if(data != 1) {
					
					sortByThis(data);
				
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
	}

	function sortByThis(data) {
		
		if(data == '') {
			errorMessage();
			return;
		}

		var linesIds = data.split(",").reverse();

		for (i = 0; i < linesIds.length; i++) {

			var splitedId = linesIds[i].split("&");

			var colorClass = '';
			if(splitedId[1] == '-1') {
				colorClass = 'even';
			} else if(splitedId[1] == '1') {
				colorClass = 'odd';
			}

			var html = $("#" + myHash + "_" + splitedId[0]).detach().removeClass('odd').removeClass('even').addClass(colorClass);

			html.prependTo("#DivForLines")

		}

		sortingMessage('Sorted', 2);

		reloadBorders();
	}

	function sortingMessage(message, type) {
		var autoH = false;
		var t = '';
		if(type == 1) {
			autoH = false;
			t = "warn";
		} else if(type == 2){
			$('.notifyjs-wrapper').trigger('notify-hide');
			autoH = true;
			t = "success";
		}

		$.notify(message, { 
			clickToHide: false,
			autoHide: autoH,
			autoHideDelay: 500,
			arrowShow: true,
			arrowSize: 5,
			breakNewLines: true,
			elementPosition: "bottom",
			globalPosition: "top center",
			style: "bootstrap",
			className: t,
			showAnimation: "slideDown",
			showDuration: 100,
			hideAnimation: "slideUp",
			hideDuration: 100,
			gap: 5
		});
	}

	function checkIfIsEmpty(njumpInf) {
		/*
		if(njumpInf.lpid != '') {
			return true;
		}
		*/

		if(njumpInf.ispids != '') {
			return true;
		}

		if(njumpInf.time != '') {
			return true;
		}

		if(njumpInf.osids != '') {
			return true;
		}

		if(njumpInf.sback.toString() != '0' && njumpInf.sback.toString() != '') {
			return true;
		}

		return false;
	}

	function getSelectedOptions(njumpInf) {
		var selectedreturn = [];

		/*
		if(njumpInf.lpid != '') {
			selectedreturn.push('LP');
		}
		*/

		if(njumpInf.ispids != '') {
			selectedreturn.push('ISP');
		}

		if(njumpInf.time != '') {
			selectedreturn.push('Time Schedule');
		}

		if(njumpInf.osids != '') {
			selectedreturn.push('OS');
		}

		if(njumpInf.sback.toString() != '0' && njumpInf.sback.toString() != '') {
			selectedreturn.push('SBack');
		}

		return selectedreturn.join(' , ');
	}

	function setlines() {

		var njumpsJson = njumpsvar;

		$("#DivForLines").empty();

		var rows = '';
		for (var i = 0; i < njumpsJson.length; i++) {
				var njumpInf = njumpsJson[i]
				
				var buttonSchedule = '';
				if(njumpInf.time != '') {
					buttonSchedule = "<button lineN='" + njumpInf.id + "' time='" + njumpInf.time + "' type='button' class='btn btn-default buttonsTimeSs SchedulewithIcon'>Schedule</button>";
				} else {
					buttonSchedule = '<button lineN="' + njumpInf.id + '" time="" type="button" class="btn btn-default buttonsTimeSs">Schedule</button>';
				}

				var styleOpenClose = '';
				var classifOpenClose = '';
				var borderStyle = '';
				var iconExpand = 'expand_blue_empty';
				var arrowLabel = 'expand line';
				var borderLeftOpenClosePer = ' borderleftColumnPerc ';
				var borderLeftOpenCloseClicksEpc = ' borderleftColumnClicksepc ';
				if(checkIfIsEmpty(njumpInf)) {
					styleOpenClose = ' style="display: block;" ';
					iconExpand = 'expand_blue_filled';
					classifOpenClose = 'over'
					borderStyle = ' borderleftInfoRow2 '
					borderLeftOpenClosePer = ' borderleftColumnPerc2 ';
					borderLeftOpenCloseClicksEpc = ' borderleftColumnClicksepc2 ';

					arrowLabel = getSelectedOptions(njumpInf);
				}

				var badLine = '';
				var textBadLine = '';
				if(njumpInf.status <= 1) {
					
				} else if(njumpInf.status == 10) { 
					textBadLine = 'missing default row';
					//missing default row
				} else if(njumpInf.status == 2){
					textBadLine = 'missing offer';
					//missing offer
				} else if(njumpInf.status == 11){
					textBadLine = 'missing proportion';
					//missing proportion 
				}

				var badLine = '';
				if(njumpInf.status > 1) {
					badLine = '<img class="iconsDivObject50 badNjump" data-toggle="popover" data-placement="bottom"  title="" data-content="' + textBadLine + '" data-clipboard-text="" src="/img/njumppage/youmesseditup_wht.svg">'
				} else {
					badLine = '<img style="display: none;" class="iconsDivObject50" data-toggle="popover" data-placement="bottom"  title="" data-content="' + textBadLine + '" data-clipboard-text="" src="/img/njumppage/youmesseditup_wht.svg">'
				}

				var colorClass = '';
				if(njumpInf.pos == '-1') {
					colorClass = 'even';
				} else if(njumpInf.pos == '1') {
					colorClass = 'odd';
				}

				var row = '<div classColor="' + colorClass + '" idLine="' + njumpInf.id + '" id="' + njumpInf.njumphash + '_' + njumpInf.id + '"  class="row rownInfo rowsInfoNjump ' + colorClass + '">'
							+ '<div class="col-lg-9 borderleftInfoRow ' + borderStyle + '">'
								+ '<div class="row">'
									+ '<div class="col-lg-3 iconsInfoRow">'
										+ '<input nameLine="' + njumpInf.linename + '" numberLine="' + njumpInf.id + '" class="checkBoxSelectLine" type="checkbox">'
										+ '<img class="iconsDivObject4 deleteLineNjump" idLine="' + njumpInf.id + '" data-toggle="popover" data-placement="bottom"  title="" data-content="delete this line" data-clipboard-text="" src="/img/njumppage/trash.svg">'
										+ '<img class="iconsDivObject4 cloneLineNjump" idLine="' + njumpInf.id + '" data-toggle="popover" data-placement="bottom"  title="" data-content="clone this line" data-clipboard-text="" src="/img/njumppage/clone.svg">'
										+ '<img class="iconsDivObject4 expandOneNjump ' + classifOpenClose + '" data-toggle="popover" data-placement="bottom"  title="" data-content="' + arrowLabel + '" data-clipboard-text="" src="/img/njumppage/' + iconExpand + '.svg">'
										+ badLine
									+ '</div>'
									+ '<div class="col-lg-1"><br>'
										+ '<span class="idnum">' + njumpInf.id + '</span>'
									+ '</div>'
									+ '<div class="col-lg-3 marginFirstLine"><br>'
											+ '<select id="selectBoxRows2" class="selectsBoxRowsEditTableOffers">'
											
											+ '</select>'
											+ '<a target="_blank" class="newtabUrl" href=""><img class="iconNewTab iconNewTab1" src="/img/njumppage/new_tab.svg"></a>'
											+ '<img class="iconNewTab iconNewTab3" src="/img/njumppage/clone_name.svg">'
											+ '<a target="_blank" class="newtabOfferPack" href=""><img class="iconNewTab iconNewTab1" src="/img/njumppage/tab_offers_circle_v2.svg"></a>'
									+ '</div>'
									+ '<div class="col-lg-2 marginFirstLine"><br>'
											+ '<select class="selectsBoxRowsEditTableLP">'
											
											+ '</select>'
											+ '<img class="iconNewTab iconNewTab2" src="/img/njumppage/new_tab.svg">'
										+ '</div>'
									+ '<div class="col-lg-2 marginFirstLine"><br>'
											+ '<select id="selectBoxRows1" multiple class="sBCarriers selectsBoxRowsEditTableCarriers">'
											
											+ '</select>'
									+ '</div>'
									+ '<div class="col-lg-1 marginFirstLine"><br>'
											+ '<input idLine="' + njumpInf.id + '" type="number" min="0" class="inputProportion"></input><img class="resetProportion" src="/img/njumppage/xxx.svg">'
									+ '</div>'
								+ '</div>'
							+ '</div>'
							+ '<div class="col-lg-3 col1CenterVert">'
									+ '<div class="row firstroeClassInfoLeft">'
										+ '<div class="col-lg-4 borderPerTotal ' + borderLeftOpenClosePer + '">'
											+ '<span class="spanClassInfoLeft percentInfoClass">'

											+ '</span>'
										+ '</div>'
										+ '<div class="col-lg-4 borderClicksEpcTotal ' + borderLeftOpenCloseClicksEpc + '">'
											+ '<span class="spanClassInfoLeft spanSmallClssInfo epcChangePeriod">'
												+ njumpInf.epc
											+ '</span>'
										+ '</div>'
										+ '<div class="col-lg-4 borderClicksEpcTotal ' + borderLeftOpenCloseClicksEpc + '">'
											+ '<span class="spanClassInfoLeft spanSmallClssInfo clicksChangePeriod">'
												+ njumpInf.clicks
											+ '</span>'
										+ '</div>'
									+ '</div>'
							+ '</div>'
							+ '<div ' + styleOpenClose + ' class="col-lg-9 bordertopInfoRow">'
								+ '<div class="row rowsSecondLine">'
									+ '<div class="col-lg-3"></div>'
																			
									+ '<div class="col-lg-2"><span class="pTitlesSb">OS</span>'
											+ '<select multiple class="selectsBoxRowsEditTableos">'
											
											+ '</select>'
									+ '</div>'
									+ '<div class="col-lg-2" style="padding-left: 5px;">'
										+ buttonSchedule
									+ '</div>'
									+ '<div class="col-lg-3" style="padding-right: 0px;"><span class="pTitlesSb">ISP</span>'
											+ '<select multiple class="selectsBoxRowsEditTableISP">'
											
											+ '</select>'
									+ '</div>'
									+ '<div class="col-lg-2"><span class="pTitlesSb">SBACK</span>'
											+ '<select multiple class="selectsBoxRowsEditTableSBack">'
											
											+ '</select>'
									+ '</div>'
								+ '</div>'
							+ '</div>'	
						+ '</div>';

				rows = rows + row;
		}

		$("#DivForLines").append(rows);

		var s = '<option></option>'

		$(".selectsBoxRowsEditTableCarriers").append(s + selectBoxCarriers);

		$(".selectsBoxRowsEditTableCarriers").chosen({
			allow_single_deselect: true,
			no_results_text: "Nothing found!",
			width: "85%",
			search_contains: true
		}).change(function(event){

			if(event.target == this){
				changeCarriers(this)
			}

		});

		$(".selectsBoxRowsEditTableOffers").append(s + selectBoxOffers);

		$(".selectsBoxRowsEditTableOffers").chosen({
			allow_single_deselect: true,
			no_results_text: "Nothing found!",
			width: "75%",
			search_contains: true
		}).change(function(event){

			if(event.target == this){
				changeOffer(this)
			}

		});

		$(".selectsBoxRowsEditTableLP").append(s + selectBoxLP);

		$(".selectsBoxRowsEditTableLP").chosen({
			allow_single_deselect: true,
			no_results_text: "Nothing found!",
			width: "85%",
			search_contains: true
		}).change(function(event){

			if(event.target == this){
				changeLP(this)
			}

		});

		$(".selectsBoxRowsEditTableISP").append(s + selectBoxISP);

		$(".selectsBoxRowsEditTableISP").chosen({
			allow_single_deselect: true,
			no_results_text: "Nothing found!",
			width: "85%",
			search_contains: true
		}).change(function(event){

			if(event.target == this){
				changeISP(this)
			}

		});

		$(".selectsBoxRowsEditTableos").append(s + selectBoxos);

		$(".selectsBoxRowsEditTableos").chosen({
			allow_single_deselect: true,
			no_results_text: "Nothing found!",
			width: "84%",
			search_contains: true
		}).change(function(event){

			if(event.target == this){
				changeos(this)
			}

		});

		$(".selectsBoxRowsEditTableSBack").append(s + selectBoxSback);

		$(".selectsBoxRowsEditTableSBack").chosen({
			allow_single_deselect: true,
			no_results_text: "Nothing found!",
			width: "45%",
			search_contains: true
		}).change(function(event){

			if(event.target == this){
				changeSback(this)
			}

		});

		for (var i = 0; i < njumpsJson.length; i++) {
			var njumpInf = njumpsJson[i]
	
			if(njumpInf.carrierids != '') {
				var arrayCarriers = njumpInf.carrierids.split(","); //carrier
				$('#' + njumpInf.njumphash + '_' + njumpInf.id).find('.selectsBoxRowsEditTableCarriers').val(arrayCarriers).trigger('chosen:updated');

				if(njumpInf.carrierids.split(",").length > 1) {
					$('#' + njumpInf.njumphash + '_' + njumpInf.id).find('.percentInfoClass').html(imgMultiCarriers); 
				} else {
					$('#' + njumpInf.njumphash + '_' + njumpInf.id).find('.percentInfoClass').html(''); 
				}
			} else {
				$('#' + njumpInf.njumphash + '_' + njumpInf.id).find('.percentInfoClass').html('- -');
			}
			
			if(njumpInf.offerhash != '') {
				var offerInput = njumpInf.offerhash; //offer
				$('#' + njumpInf.njumphash + '_' + njumpInf.id).find('.selectsBoxRowsEditTableOffers').val(offerInput).trigger('chosen:updated');
			}

			if(njumpInf.lpid != '') {
				var lpInput = njumpInf.lpid; //lp
				$('#' + njumpInf.njumphash + '_' + njumpInf.id).find('.selectsBoxRowsEditTableLP').val(lpInput).trigger('chosen:updated');
			}

			if(njumpInf.ispids != '') {
				var arrayISP = njumpInf.ispids.split(","); //isp
				$('#' + njumpInf.njumphash + '_' + njumpInf.id).find('.selectsBoxRowsEditTableISP').val(arrayISP).trigger('chosen:updated');
			}

			if(njumpInf.osids != '') {
				var arrayos = njumpInf.osids.split(","); //os
				$('#' + njumpInf.njumphash + '_' + njumpInf.id).find('.selectsBoxRowsEditTableos').val(arrayos).trigger('chosen:updated');
			}

			if(njumpInf.sback.toString() != '') {
				var arraySback = njumpInf.sback.toString().split(","); //sback
				$('#' + njumpInf.njumphash + '_' + njumpInf.id).find('.selectsBoxRowsEditTableSBack').val(arraySback).trigger('chosen:updated');
			}

			$('#' + njumpInf.njumphash + '_' + njumpInf.id).find(' .inputProportion').val(njumpInf.proportion);
			$('#' + njumpInf.njumphash + '_' + njumpInf.id).find(' .inputLineName').val(njumpInf.linename);
		}	

		reloadPercents();
		reloadBorders();
		reloadHrefs();
	}

	/*
	$('body').on('click', ".iconNewTab1", function() {

		var element = this;

		if($(element).closest('.rownInfo').find('.selectsBoxRowsEditTableOffers').chosen().val() != null && $(element).closest('.rownInfo').find('.selectsBoxRowsEditTableOffers').chosen().val() != '') {
			
			var options = $(element).closest('.rownInfo').find('.selectsBoxRowsEditTableOffers option:selected');

			for (var i = 0; i < options.length; i++) {
			    var property = $(options[i]).attr('url');

			    window.open(property);
			}
		}

	});
	*/

	function reloadHrefs() {
		$(".rownInfo").each(function( index ) {
		
			var element = this;

			if($(element).find('.selectsBoxRowsEditTableOffers').chosen().val() != null && $(element).find('.selectsBoxRowsEditTableOffers').chosen().val() != '') {
			
			var options = $(element).find('.selectsBoxRowsEditTableOffers option:selected');

			for (var i = 0; i < options.length; i++) {
			    var property = $(options[i]).attr('url');

			    $(element).find(".newtabUrl").attr('href', property);
			    $(element).find(".newtabOfferPack").attr('href', 'https://mobisteinreport.com/offerpack/offerpackedit2?offerhash=' + $(options[i]).attr('value') );
			}
		}


		});
	}
        
	$('body').on('click', ".iconNewTab2", function() {

		var element = this;

		if($(element).closest('.rownInfo').find('.selectsBoxRowsEditTableLP').chosen().val() != null && $(element).closest('.rownInfo').find('.selectsBoxRowsEditTableLP').chosen().val() != '') {
			
			var options = $(element).closest('.rownInfo').find('.selectsBoxRowsEditTableLP option:selected');

			for (var i = 0; i < options.length; i++) {
			    var property = $(options[i]).attr('url');

			    window.open(property);
			}
		}

	});

	$('body').on('change', ".checkBoxSelectLine", function() {
	    selectedLines = [];
	    var allSelected = [];
	    $(".checkBoxSelectLine").each(function( index ) {
			if(this.checked) {
				selectedLines.push({ number: $(this).attr('numberLine'), name: $(this).attr('nameLine') });
				allSelected.push(true);
			} else {
				allSelected.push(false);
			}
		});

	    var checkAll = allSelected.every(checkTrue)
	    if(checkAll) {
	    	$("#selectAllCheckBox").prop('checked', true);
	    } else {
	    	$("#selectAllCheckBox").prop('checked', false);
	    }

		if(selectedLines.length > 0) {
			//$(".buttonTopTable").attr('disabled', false);
			$(".iconsDivObject99").removeClass('disabled');
		} else {
			//$(".buttonTopTable").attr('disabled', true);
			$(".iconsDivObject99").addClass('disabled');
		}
	});

	$(".checkBoxSelectLine2").change(function() {
	    selectedLines = [];
	   
	    $(".checkBoxSelectLine").each(function( index ) {
			$(this).prop('checked', $("#selectAllCheckBox").prop('checked'));

			if(this.checked) {
				selectedLines.push({ number: $(this).attr('numberLine'), name: $(this).attr('nameLine') });
			} 
		});	

		if(selectedLines.length > 0) {
			//$(".buttonTopTable").attr('disabled', false);
			$(".iconsDivObject99").removeClass('disabled');
		} else {
			//$(".buttonTopTable").attr('disabled', true);
			$(".iconsDivObject99").addClass('disabled');
		}
	});

	function checkTrue(value) {
		return value;
	}

	$('body').on('click', "#deleteSelectedModal", function() {
		
		if($(this).hasClass('disabled')) 
			return;

		$("#listLinesNames").empty();

		var linesNames = '';
		$(selectedLines).each(function( index ) {
			linesNames += '<span class="spanMultipleLinesNames">' + selectedLines[index].name + '</span><br>'
		});

		$("#listLinesNames").append(linesNames);

		$("#modalDeleteMultipleLines").modal("show")
	});

	$('body').on('click', "#deleteMultipleLineModalButton", function() {
		
		var hash = myHash;
	    var idLines = [];

	    $(selectedLines).each(function( index ) {
			idLines.push(selectedLines[index].number);
		});

	   	var formData = new FormData();
		formData.append('njumphash', hash);
		formData.append('idsLines', idLines);
		
		$(document).one("ajaxStart", function() {
			//ALERT
			savingMessage();
		});

		var status = 0;
	    $.ajax({
			url: '/njump/njumpdeletemultiplerow',
			type: 'POST',
			data: formData,
			async: true,
			success: function(data) {
				if(data != 1) {	
					
					status = 1;
					
					$(".checkBoxSelectLine").each(function( index ) {
						if(this.checked) {
							if(jQuery.inArray($(this).attr('numberLine'), idLines) != -1) {
								$(this).closest('.rownInfo').remove();
							}
						}
					});

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
				$("#modalDeleteMultipleLines").modal('hide')
			}
		});
	});	

	$('body').on('click', "#copytoSelectedModal", function() {
		
		if($(this).hasClass('disabled')) 
			return;


		$("#nameNjumpCopyToLines").empty();
		$("#njumpsCloneMultipleLines").chosen("destroy");
		$("#njumpsCloneMultipleLines").empty();

		var linesNames = '';
		$(selectedLines).each(function( index ) {
			linesNames += '<span class="spanMultipleLinesNames">' + selectedLines[index].name + '</span><br>'
		});

		$("#nameNjumpCopyToLines").append(linesNames);

		var formData = new FormData();
		formData.append('njumphash', myHash);
		formData.append('country', countryname[0]);

		var status = 0;
		$.ajax({
			url: '/njump/getnjumpsbycountry',
			type: 'POST',
			data: formData,
			async: true,
			success: function(data) {
			
				if(data != 1) {	
					
					if(data == 2) {

						customMessage2('No njumps for this country!', 'warn');

					} else {
						status = 1;

						var jsonInf = $.parseJSON(data);

						var selectBoxNjumpsByCountry = '<option></option>';
						for (var i = 0; i < jsonInf.length; i++) {
							selectBoxNjumpsByCountry += '<option value="' + jsonInf[i]['njumphash'] + '">' + jsonInf[i]['name'] + '</option>';
						}

						$("#njumpsCloneMultipleLines").append(selectBoxNjumpsByCountry);
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
				$("#njumpsCloneMultipleLines").chosen({
					allow_single_deselect: true,
					no_results_text: "Nothing found!",
					width: "100%",
					search_contains: true
				})

				$("#modalCloneMultipleNJump").modal("show")
			}
		});
	});

	$('body').on('click', "#okModalButtonMultipleClone", function() {
		
		var hash = myHash;
	    var idLines = [];
	    var njumps = $("#njumpsCloneMultipleLines").chosen().val();

	    var checked = false;

	    $(selectedLines).each(function( index ) {
			idLines.push(selectedLines[index].number);
		});

	    if($('#checkBoxCopyToThis').is(":checked")) {
	    	checked = true;

	    	if(njumps != null)
	    		njumps.push(myHash);
	    	else 
	    		njumps = [myHash];
	    }
		
	   	var formData = new FormData();
		formData.append('njumphash', hash);
		formData.append('idsLines', idLines);
		formData.append('njumpsClone', njumps);
		
		$(document).one("ajaxStart", function() {
			//ALERT
			savingMessage();
		});

		var status = 0;
	    $.ajax({
			url: '/njump/njumpclonemultiplelines',
			type: 'POST',
			data: formData,
			async: true,
			success: function(data) {
				if(data != 1) {	
					
					status = 1;
					
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
				$("#modalCloneMultipleNJump").modal('hide')

				if(checked) {
					location.reload();
				}
			}
		});
	});

	$('body').on('click', ".buttonsTimeSs", function() {
		parting.refreshContent("day_parting", null,null)

		lineNSchedule = $(this).attr("lineN");
		var time = $(this).attr("time");
		if($(this).hasClass('SchedulewithIcon') && time != '') {
			parting.refreshContent("day_parting", $.parseJSON(time), null)
		} 

		elementButtonSch = $(this);

		$("#modalTimeLine").modal("show")
	});

	function changeCarriers(element) {
		var carriers = $(element).chosen().val();
		var hash = myHash;
		var idLine = $(element).closest('.rownInfo').attr('idLine');
		var type = 3;

		var formData = new FormData();
		formData.append('njumphash', hash);
		formData.append('idLine', idLine);
		formData.append('content', carriers);
		formData.append('type', type);

		if(carriers != null) {
			if(carriers.length > 1) {
				$(element).closest('.rownInfo').find('.percentInfoClass').html(imgMultiCarriers); 
			} else if(carriers.length == 0) {
				$(element).closest('.rownInfo').find('.percentInfoClass').html('- -');
			} else {
				$(element).closest('.rownInfo').find('.percentInfoClass').html(''); 
			}
		} else {
			$(element).closest('.rownInfo').find('.percentInfoClass').html('- -');
		}
		reloadPercents();
		
		saveChanges(formData, element);
	}

	function putHrefNewTabOffer(element) {
		if($(element).closest('.rownInfo').find('.selectsBoxRowsEditTableOffers').chosen().val() != null && $(element).closest('.rownInfo').find('.selectsBoxRowsEditTableOffers').chosen().val() != '') {
			
			var options = $(element).closest('.rownInfo').find('.selectsBoxRowsEditTableOffers option:selected');

			for (var i = 0; i < options.length; i++) {
			    var property = $(options[i]).attr('url');

			    $(element).closest('.rownInfo').find(".newtabUrl").attr('href', property);
			}
		}
	}

	function changeOffer(element) {
	
		putHrefNewTabOffer(element);

		var offer = $(element).chosen().val();
		var hash = myHash;
		var idLine = $(element).closest('.rownInfo').attr('idLine');
		var type = 0;

		var formData = new FormData();
		formData.append('njumphash', hash);
		formData.append('idLine', idLine);
		formData.append('content', offer);
		formData.append('type', type);

		saveChanges(formData, element);
	}

	function changeLP(element) {
		var lp = $(element).chosen().val();
		var hash = myHash;
		var idLine = $(element).closest('.rownInfo').attr('idLine');
		var type = 4;

		var formData = new FormData();
		formData.append('njumphash', hash);
		formData.append('idLine', idLine);
		formData.append('content', lp);
		formData.append('type', type);

		saveChanges(formData, element);
		checkChanges(element);
	}

	function changeISP(element) {
		var isp = $(element).chosen().val();
		var hash = myHash;
		var idLine = $(element).closest('.rownInfo').attr('idLine');
		var type = 6;

		var formData = new FormData();
		formData.append('njumphash', hash);
		formData.append('idLine', idLine);
		formData.append('content', isp);
		formData.append('type', type);

		saveChanges(formData, element);
		checkChanges(element);
	}

	function changeos(element) {
		var os = $(element).chosen().val();
		var hash = myHash;
		var idLine = $(element).closest('.rownInfo').attr('idLine');
		var type = 5;

		var formData = new FormData();
		formData.append('njumphash', hash);
		formData.append('idLine', idLine);
		formData.append('content', os);
		formData.append('type', type);

		saveChanges(formData, element);
		checkChanges(element);
	}

	function changeSback(element) {
		var sback = $(element).chosen().val();
		var hash = myHash;
		var idLine = $(element).closest('.rownInfo').attr('idLine');
		var type = 8;

		var formData = new FormData();
		formData.append('njumphash', hash);
		formData.append('idLine', idLine);
		formData.append('content', sback);
		formData.append('type', type);

		saveChanges(formData, element);
		checkChanges(element);
	}

	//linename
	$('body').on('blur', ".inputLineName", function() {
	    var hash = myHash;
	    var idLine = $(this).attr('idLine');
	   	var type = 1;

		var formData = new FormData();
		formData.append('njumphash', hash);
		formData.append('idLine', idLine);
		formData.append('content', $(this).val());
		formData.append('type', type);

		saveChanges(formData, this);
	});		

	//proportion
	$('body').on('blur', ".inputProportion", function() {
	    var hash = myHash;
	    var idLine = $(this).attr('idLine');
	   	var type = 2;

		var formData = new FormData();
		formData.append('njumphash', hash);
		formData.append('idLine', idLine);
		formData.append('content', $(this).val());
		formData.append('type', type);

		reloadPercents();

		saveChanges(formData, this);
	});	

	function changeDomain(element) {
		var type = 9;

		var formData = new FormData();
		formData.append('njumphash', myHash);
		formData.append('idLine', '');
		formData.append('content', $(element).chosen().val());
		formData.append('type', type);

		saveChanges(formData, element);
	}

	function saveChanges(formData, element) {
		
		$(document).one("ajaxStart", function() {
			//ALERT
			savingMessage();
		});

		var status = 0;
		$.ajax({
			url: '/njump/updatecell',
			type: 'POST',
			data: formData,
			async: true,
			success: function(data) {
				
				if(data != 1) {
					
					var njumpInf2 = jQuery.parseJSON(data);

					if(njumpInf2.error != 1) {

						if(njumpInf2.tableidstatus <= 1) {
							$(element).closest('.rownInfo').find('.iconsDivObject50').removeClass('badNjump')
							$(element).closest('.rownInfo').find('.iconsDivObject50').hide();

						} else if(njumpInf2.tableidstatus == 10) { 

							$(".rownInfo").each(function( index ) {
								$(this).find('.iconsDivObject50').addClass('badNjump')
								$(this).find('.iconsDivObject50').show();
								$(this).find('.iconsDivObject50').attr('data-content', 'missing default row');
							});

							//missing default row

						} else if(njumpInf2.tableidstatus == 2){
							$(element).closest('.rownInfo').find('.iconsDivObject50').addClass('badNjump')
							$(element).closest('.rownInfo').find('.iconsDivObject50').show();
							$(element).closest('.rownInfo').find('.iconsDivObject50').attr('data-content', 'missing offer');
							//missing offer

						} else if(njumpInf2.tableidstatus == 11){
							$(element).closest('.rownInfo').find('.iconsDivObject50').addClass('badNjump')
							$(element).closest('.rownInfo').find('.iconsDivObject50').show();
							$(element).closest('.rownInfo').find('.iconsDivObject50').attr('data-content', 'missing proportion');
							//missing proportion 
						}

						if(njumpInf2.njumpstatus <= 1) {
							$(".iconsDivObject51").hide();

							
							if(njumpInf2.njumpstatus == 0) {
								$(".rownInfo").each(function( index ) {
									$(this).find('.iconsDivObject50').removeClass('badNjump')
									$(this).find('.iconsDivObject50').hide();
								});
							}

						} else {
							$(".iconsDivObject51").show();
						}

						status = 1;

					} else {
						errorMessage();
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
	}

	function checkChanges(element) {

		var selectedreturn = [];
		var result = false;

		/*
		if($(element).closest('.rownInfo').find('.selectsBoxRowsEditTableLP').chosen().val() != null && $(element).closest('.rownInfo').find('.selectsBoxRowsEditTableLP').chosen().val() != '') {
			result = true;
			selectedreturn.push('LP');
		}
		*/

		if($(element).closest('.rownInfo').find('.selectsBoxRowsEditTableISP').chosen().val() != null && $(element).closest('.rownInfo').find('.selectsBoxRowsEditTableISP').chosen().val() != '') {
			result = true;
			selectedreturn.push('ISP');
		}

		if($(element).closest('.rownInfo').find('.selectsBoxRowsEditTableos').chosen().val() != null && $(element).closest('.rownInfo').find('.selectsBoxRowsEditTableos').chosen().val() != '') {
			result = true;
			selectedreturn.push('OS');
		}

		if($(element).closest('.rownInfo').find('.selectsBoxRowsEditTableSBack').chosen().val() != null && $(element).closest('.rownInfo').find('.selectsBoxRowsEditTableSBack').chosen().val() != '') {
			result = true;
			selectedreturn.push('SBack');
		}

		if($(element).closest('.rownInfo').find('.buttonsTimeSs').hasClass('SchedulewithIcon')) {
			result = true;
			selectedreturn.push('Time Schedule');
		}

		if(!result) {
			$(element).closest('.rownInfo').find('.expandOneNjump').attr('src', '/img/njumppage/expand_blue_empty.svg');
			$(element).closest('.rownInfo').find('.expandOneNjump').attr('data-content', 'expand line');
		} else {
			$(element).closest('.rownInfo').find('.expandOneNjump').attr('src', '/img/njumppage/expand_blue_filled.svg');
			$(element).closest('.rownInfo').find('.expandOneNjump').attr('data-content', selectedreturn.join(' , '));
		}
	}

	function savedMessage() {
		$('.notifyjs-wrapper').trigger('notify-hide');
		setTimeout(function(){
			$.notify("Saved", { 
				clickToHide: false,
				autoHide: true,
				autoHideDelay: 1000,
				arrowShow: true,
				arrowSize: 5,
				breakNewLines: true,
				elementPosition: "bottom",
				globalPosition: "top center",
				style: "bootstrap",
				className: "success",
				showAnimation: "slideDown",
				showDuration: 100,
				hideAnimation: "slideUp",
				hideDuration: 100,
				gap: 5
			});
		}, 500);
	}

	function errorMessage() {
		$('.notifyjs-wrapper').trigger('notify-hide');
		
		/*
		setTimeout(function(){
			$.notify("error!", "error");
		}, 300);
		*/

		$.notify("error!", { 
			clickToHide: false,
			autoHide: false,
			autoHideDelay: 500,
			arrowShow: true,
			arrowSize: 5,
			breakNewLines: true,
			elementPosition: "bottom",
			globalPosition: "top center",
			style: "bootstrap",
			className: "error",
			showAnimation: "slideDown",
			showDuration: 100,
			hideAnimation: "slideUp",
			hideDuration: 100,
			gap: 5
		});
	}

	function customMessage(message) {
		$('.notifyjs-wrapper').trigger('notify-hide');
		
		/*
		setTimeout(function(){
			$.notify(message, "error");
		}, 300);
		*/

		$.notify(message, { 
			clickToHide: false,
			autoHide: false,
			autoHideDelay: 500,
			arrowShow: true,
			arrowSize: 5,
			breakNewLines: true,
			elementPosition: "bottom",
			globalPosition: "top center",
			style: "bootstrap",
			className: "error",
			showAnimation: "slideDown",
			showDuration: 100,
			hideAnimation: "slideUp",
			hideDuration: 100,
			gap: 5
		});
	}

	function customMessage2(message, type) {
		$('.notifyjs-wrapper').trigger('notify-hide');
		
		setTimeout(function(){
			$.notify(message, { 
				clickToHide: false,
				autoHide: true,
				autoHideDelay: 2000,
				arrowShow: true,
				arrowSize: 5,
				breakNewLines: true,
				elementPosition: "bottom",
				globalPosition: "top center",
				style: "bootstrap",
				className: type,
				showAnimation: "slideDown",
				showDuration: 100,
				hideAnimation: "slideUp",
				hideDuration: 100,
				gap: 5
			});
		}, 2000);
	}

	function savingMessage() {
		$('.notifyjs-wrapper').trigger('notify-hide');
		$.notify("Saving ...", { 
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
	
	$("body").on("click", ".divExpandAll", function () {
	    
	    //$(this).find('.popover').remove();

	    if($(this).hasClass('over')) {
		
		   	$(this).removeClass("over");
	    	$(this).addClass('out');

	    	$(".expandOneNjump").removeClass("over");
	    	$(".expandOneNjump").addClass('out');
	    	$(".borderleftInfoRow").removeClass('borderleftInfoRow2');

	    	$(".borderClicksEpcTotal").addClass('borderleftColumnClicksepc');
	    	$(".borderClicksEpcTotal").removeClass('borderleftColumnClicksepc2');

	    	$(".borderPerTotal").addClass('borderleftColumnPerc');
	    	$(".borderPerTotal").removeClass('borderleftColumnPerc2');
	    	
	    	$(".bordertopInfoRow").slideUp(100);
	    } else if($(this).hasClass('out')) {
	    	$(this).removeClass("out");
	    	$(this).addClass('over');

	    	$(".expandOneNjump").removeClass("out");
	    	$(".expandOneNjump").addClass('over');

	    	$(".borderleftInfoRow").addClass('borderleftInfoRow2');

	    	$(".bordertopInfoRow").slideDown(100);

	    	$(".borderPerTotal").removeClass('borderleftColumnPerc');
	    	$(".borderPerTotal").addClass('borderleftColumnPerc2');

	    	$(".borderClicksEpcTotal").removeClass('borderleftColumnClicksepc');
	    	$(".borderClicksEpcTotal").addClass('borderleftColumnClicksepc2');

	    	

	    } else {
	    	$(this).addClass('over');
	    	$(".bordertopInfoRow").slideDown(100);

	    	$(".expandOneNjump").removeClass("out");
	    	$(".expandOneNjump").addClass('over');

	    	$(".borderleftInfoRow").addClass('borderleftInfoRow2');

	    	$(".borderPerTotal").removeClass('borderleftColumnPerc');
	    	$(".borderPerTotal").addClass('borderleftColumnPerc2');

	    	$(".borderClicksEpcTotal").removeClass('borderleftColumnClicksepc');
	    	$(".borderClicksEpcTotal").addClass('borderleftColumnClicksepc2');

	    }
	    
	});

	$("body").on("click", "#deleteLineModalButton", function () {
		var hash = myHash;
	    var idLine = elementLineToDelete.attr('idLine');

	   	var formData = new FormData();
		formData.append('njumphash', hash);
		formData.append('id', idLine);
		
		$(document).one("ajaxStart", function() {
			//ALERT
			savingMessage();
		});

		var status = 0;
	    $.ajax({
			url: '/njump/njumpdeleterow',
			type: 'POST',
			data: formData,
			async: true,
			success: function(data) {
				if(data != 1) {	
					
					status = 1;
					elementLineToDelete.closest('.rownInfo').remove();

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
				$("#modalDeleteLine").modal('hide')
			}
		});
	});

	var elementLineToDelete;
	$("body").on("click", ".deleteLineNjump", function () {
	    elementLineToDelete = $(this);
	
		$("#nameNjumpDelete2").text(elementLineToDelete.closest('.rownInfo').find('.inputLineName').val());

	    $("#modalDeleteLine").modal('show');
	});


	$("body").on("click", ".cloneLineNjump", function () {
	    
	    if($(this).closest('.rownInfo').find('.iconsDivObject50').hasClass('badNjump')) {
	    	customMessage('Please correct the line!');
	    } else {
	    	var hash = myHash;
		    var idLine = $(this).attr('idLine');
		   
		    var formData = new FormData();
			formData.append('njumphash', hash);
			formData.append('id', idLine);
			
			$(document).one("ajaxStart", function() {
				//ALERT
				savingMessage();
			});

			var status = 0;
		    $.ajax({
				url: '/njump/njumpnewrow',
				type: 'POST',
				data: formData,
				async: true,
				success: function(data) {
					if(data != 1) {
						
						status = 1;
						createNewLine(data);

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
	    }
	});

	var processingline = false;
	$("body").on("click", "#newLineIcon", function () {
	    var hash = myHash;
	   	
	   	var offer = '';
	   	if($("#selectoffersnewrow").chosen().val() != null && $("#selectoffersnewrow").chosen().val() != '') {
	   		offer = $("#selectoffersnewrow").chosen().val();
	   	} else {
	   		customMessage('Please select offer!');
	   		return;
	   	}

	    if(processingline)
	    	return;

	    var formData = new FormData();
		formData.append('njumphash', hash);
		formData.append('offerhash', offer);
		
		$(document).one("ajaxStart", function() {
			//ALERT
			savingMessage();

			processingline = true;
		});

		var status = 0;
	    $.ajax({
			url: '/njump/njumpnewrow',
			type: 'POST',
			data: formData,
			async: true,
			success: function(data) {
				if(data != 1) {
					
					status = 1;

					//create new line
					createNewLine(data);

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

			processingline = false;
		});
	});

	function createNewLine(data) {
		var njumpInf = jQuery.parseJSON(data);
	
		var buttonSchedule = '';
		if(njumpInf.time != '') {
			buttonSchedule = "<button lineN='" + njumpInf.id + "' time='" + njumpInf.time + "' type='button' class='btn btn-default buttonsTimeSs SchedulewithIcon'>Schedule</button>";
		} else {
			buttonSchedule = '<button lineN="' + njumpInf.id + '" time="" type="button" class="btn btn-default buttonsTimeSs">Schedule</button>';
		}

		var styleOpenClose = '';
		var classifOpenClose = '';
		var iconExpand = 'expand_blue_empty';
		var arrowLabel = 'expand line';
		
		var borderStyle = '';
		var borderLeftOpenClosePer = ' borderleftColumnPerc ';
		var borderLeftOpenCloseClicksEpc = ' borderleftColumnClicksepc ';

		if(checkIfIsEmpty(njumpInf)) {
			styleOpenClose = ' style="display: block;" ';
			iconExpand = 'expand_blue_filled';
			classifOpenClose = 'over'

			borderStyle = ' borderleftInfoRow2 '
			borderLeftOpenClosePer = ' borderleftColumnPerc2 ';
			borderLeftOpenCloseClicksEpc = ' borderleftColumnClicksepc2 ';

			arrowLabel = getSelectedOptions(njumpInf);
		}

		var badLine = '';
		var textBadLine = '';
		if(njumpInf.status <= 1) {
			
		} else if(njumpInf.status == 10) { 
			textBadLine = 'missing default row';
			//missing default row
		} else if(njumpInf.status == 2){
			textBadLine = 'missing offer';
			//missing offer
		} else if(njumpInf.status == 11){
			textBadLine = 'missing proportion';
			//missing proportion 
		}

		var badLine = '';
		if(njumpInf.status > 1) {
			badLine = '<img class="iconsDivObject50 badNjump" data-toggle="popover" data-placement="bottom"  title="" data-content="' + textBadLine + '" data-clipboard-text="" src="/img/njumppage/youmesseditup_wht.svg">'
		} else {
			badLine = '<img style="display: none;" class="iconsDivObject50" data-toggle="popover" data-placement="bottom"  title="" data-content="' + textBadLine + '" data-clipboard-text="" src="/img/njumppage/youmesseditup_wht.svg">'
		}

		var lastColorClassEven = $(".rownInfo").last().hasClass('even');
		var lastColorClassOdd = $(".rownInfo").last().hasClass('odd');
		var colorClass = '';
		if(lastColorClassEven) {
			colorClass = 'odd';
		} else if(lastColorClassOdd) {
			colorClass = 'even';
		}

		var row = '<div classColor="' + colorClass + '" idLine="' + njumpInf.id + '" id="' + njumpInf.njumphash + '_' + njumpInf.id + '"  class="row rownInfo rowsInfoNjump ' + colorClass + '">'
				+ '<div class="col-lg-9 borderleftInfoRow ' + borderStyle + '">'
					+ '<div class="row">'
						+ '<div class="col-lg-3 iconsInfoRow">'
							+ '<input nameLine="' + njumpInf.linename + '" numberLine="' + njumpInf.id + '" class="checkBoxSelectLine" type="checkbox">'
							+ '<img class="iconsDivObject4 deleteLineNjump" idLine="' + njumpInf.id + '" data-toggle="popover" data-placement="bottom"  title="" data-content="delete this line" data-clipboard-text="" src="/img/njumppage/trash.svg">'
							+ '<img class="iconsDivObject4 cloneLineNjump" idLine="' + njumpInf.id + '" data-toggle="popover" data-placement="bottom"  title="" data-content="clone this line" data-clipboard-text="" src="/img/njumppage/clone.svg">'
							+ '<img class="iconsDivObject4 expandOneNjump ' + classifOpenClose + '" data-toggle="popover" data-placement="bottom"  title="" data-content="' + arrowLabel + '" data-clipboard-text="" src="/img/njumppage/' + iconExpand + '.svg">'
							+ badLine
						+ '</div>'
						+ '<div class="col-lg-1"><br>'
							+ '<span class="idnum">' + njumpInf.id + '</span>'
						+ '</div>'
						+ '<div class="col-lg-3 marginFirstLine"><br>'
							+ '<select id="selectBoxRows2" class="selectsBoxRowsEditTableOffers selectsBoxRowsEditTableOffers' + njumpInf.njumphash + '_' + njumpInf.id + '">'

							+ '</select>'
							+ '<a target="_blank" class="newtabUrl" href=""><img class="iconNewTab iconNewTab1" src="/img/njumppage/new_tab.svg"></a>'
							+ '<img class="iconNewTab iconNewTab3" src="/img/njumppage/clone_name.svg">'
							+ '<a target="_blank" class="newtabOfferPack" href=""><img class="iconNewTab iconNewTab1" src="/img/njumppage/tab_offers_circle_v2.svg"></a>'
						+ '</div>'
						+ '<div class="col-lg-2 marginFirstLine"><br>'
							+ '<select class="selectsBoxRowsEditTableLP selectsBoxRowsEditTableLP' + njumpInf.njumphash + '_' + njumpInf.id + '">'

							+ '</select>'
							+ '<img class="iconNewTab iconNewTab2" src="/img/njumppage/new_tab.svg">'
						+ '</div>'
						+ '<div class="col-lg-2 marginFirstLine"><br>'
							+ '<select id="selectBoxRows1" multiple class="sBCarriers selectsBoxRowsEditTableCarriers selectsBoxRowsEditTableCarriers' + njumpInf.njumphash + '_' + njumpInf.id + '">'

							+ '</select>'
						+ '</div>'
						+ '<div class="col-lg-1 marginFirstLine"><br>'
							+ '<input idLine="' + njumpInf.id + '" type="number" min="0" class="inputProportion inputProportion' + njumpInf.njumphash + '_' + njumpInf.id + '"></input><img class="resetProportion" src="/img/njumppage/xxx.svg">'
						+ '</div>'
					+ '</div>'
				+ '</div>'
				+ '<div class="col-lg-3 col1CenterVert">'
					+ '<div class="row firstroeClassInfoLeft">'
						+ '<div class="col-lg-4 borderPerTotal ' + borderLeftOpenClosePer + '">'
							+ '<span class="spanClassInfoLeft percentInfoClass">'

							+ '</span>'
						+ '</div>'
						+ '<div class="col-lg-4 borderClicksEpcTotal ' + borderLeftOpenCloseClicksEpc + '">'
							+ '<span class="spanClassInfoLeft spanSmallClssInfo epcChangePeriod">'
								+ njumpInf.epc
							+ '</span>'
						+ '</div>'
						+ '<div class="col-lg-4 borderClicksEpcTotal ' + borderLeftOpenCloseClicksEpc + '">'
							+ '<span class="spanClassInfoLeft spanSmallClssInfo clicksChangePeriod">'
								+ njumpInf.clicks
							+ '</span>'
						+ '</div>'
					+ '</div>'
				+ '</div>'
				+ '<div ' + styleOpenClose + ' class="col-lg-9 bordertopInfoRow">'
					+ '<div class="row rowsSecondLine">'
						+ '<div class="col-lg-3"></div>'

							+ '<div class="col-lg-2"><span class="pTitlesSb">OS</span>'
								+ '<select multiple class="selectsBoxRowsEditTableos selectsBoxRowsEditTableos' + njumpInf.njumphash + '_' + njumpInf.id + '">'

								+ '</select>'
							+ '</div>'
							+ '<div class="col-lg-2" style="padding-left: 5px;">'
								+ buttonSchedule
							+ '</div>'
							+ '<div class="col-lg-3" style="padding-right: 0px;"><span class="pTitlesSb">ISP</span>'
								+ '<select multiple class="selectsBoxRowsEditTableISP selectsBoxRowsEditTableISP' + njumpInf.njumphash + '_' + njumpInf.id + '"">'

								+ '</select>'
					+ '</div>'
					+ '<div class="col-lg-2"><span class="pTitlesSb">SBACK</span>'
						+ '<select multiple class="selectsBoxRowsEditTableSBack selectsBoxRowsEditTableSBack' + njumpInf.njumphash + '_' + njumpInf.id + '"">'

						+ '</select>'
					+ '</div>'
				+ '</div>'
			+ '</div>'	
		+ '</div>';

		$("#DivForLines").append(row);

		var s = '<option></option>'

		$(".selectsBoxRowsEditTableCarriers" + njumpInf.njumphash + '_' + njumpInf.id).append(s + selectBoxCarriers);

		$(".selectsBoxRowsEditTableCarriers" + njumpInf.njumphash + '_' + njumpInf.id).chosen({
			allow_single_deselect: true,
			no_results_text: "Nothing found!",
			width: "85%",
			search_contains: true
		}).change(function(event){

			if(event.target == this){
				changeCarriers(this)
			}

		});

		$(".selectsBoxRowsEditTableOffers" + njumpInf.njumphash + '_' + njumpInf.id).append(s + selectBoxOffers);

		$(".selectsBoxRowsEditTableOffers" + njumpInf.njumphash + '_' + njumpInf.id).chosen({
			allow_single_deselect: true,
			no_results_text: "Nothing found!",
			width: "75%",
			search_contains: true
		}).change(function(event){

			if(event.target == this){
				changeOffer(this)
			}

		});

		$(".selectsBoxRowsEditTableLP" + njumpInf.njumphash + '_' + njumpInf.id).append(s + selectBoxLP);

		$(".selectsBoxRowsEditTableLP" + njumpInf.njumphash + '_' + njumpInf.id).chosen({
			allow_single_deselect: true,
			no_results_text: "Nothing found!",
			width: "85%",
			search_contains: true
		}).change(function(event){

			if(event.target == this){
				changeLP(this)
			}

		});

		$(".selectsBoxRowsEditTableISP" + njumpInf.njumphash + '_' + njumpInf.id).append(s + selectBoxISP);

		$(".selectsBoxRowsEditTableISP" + njumpInf.njumphash + '_' + njumpInf.id).chosen({
			allow_single_deselect: true,
			no_results_text: "Nothing found!",
			width: "85%",
			search_contains: true
		}).change(function(event){

			if(event.target == this){
				changeISP(this)
			}

		});

		$(".selectsBoxRowsEditTableos" + njumpInf.njumphash + '_' + njumpInf.id).append(s + selectBoxos);

		$(".selectsBoxRowsEditTableos" + njumpInf.njumphash + '_' + njumpInf.id).chosen({
			allow_single_deselect: true,
			no_results_text: "Nothing found!",
			width: "85%",
			search_contains: true
		}).change(function(event){

			if(event.target == this){
				changeos(this)
			}

		});

		$(".selectsBoxRowsEditTableSBack" + njumpInf.njumphash + '_' + njumpInf.id).append(s + selectBoxSback);

		$(".selectsBoxRowsEditTableSBack" + njumpInf.njumphash + '_' + njumpInf.id).chosen({
			allow_single_deselect: true,
			no_results_text: "Nothing found!",
			width: "45%",
			search_contains: true
		}).change(function(event){

			if(event.target == this){
				changeSback(this)
			}

		});

		if(njumpInf.carrierids != '') {
			var arrayCarriers = njumpInf.carrierids.split(","); //carrier
			$('#' + njumpInf.njumphash + '_' + njumpInf.id).find(' .selectsBoxRowsEditTableCarriers' + njumpInf.njumphash + '_' + njumpInf.id).val(arrayCarriers).trigger('chosen:updated');

			if(njumpInf.carrierids.split(",").length > 1) {
				$('#' + njumpInf.njumphash + '_' + njumpInf.id).find('.percentInfoClass').html(imgMultiCarriers); 
			} else {
				$('#' + njumpInf.njumphash + '_' + njumpInf.id).find('.percentInfoClass').html(''); 
			}
		} else {
			$('#' + njumpInf.njumphash + '_' + njumpInf.id).find('.percentInfoClass').html('- -');
		}
				
		if(njumpInf.offerhash != '') {
			var offerInput = njumpInf.offerhash; //offer
			$('#' + njumpInf.njumphash + '_' + njumpInf.id).find(' .selectsBoxRowsEditTableOffers' + njumpInf.njumphash + '_' + njumpInf.id).val(offerInput).trigger('chosen:updated');
		}

		if(njumpInf.lpid != '') {
			var lpInput = njumpInf.lpid; //lp
			$('#' + njumpInf.njumphash + '_' + njumpInf.id).find(' .selectsBoxRowsEditTableLP' + njumpInf.njumphash + '_' + njumpInf.id).val(lpInput).trigger('chosen:updated');
		}

		if(njumpInf.ispids != '') {
			var arrayISP = njumpInf.ispids.split(","); //isp
			$('#' + njumpInf.njumphash + '_' + njumpInf.id).find(' .selectsBoxRowsEditTableISP' + njumpInf.njumphash + '_' + njumpInf.id).val(arrayISP).trigger('chosen:updated');
		}

		if(njumpInf.osids != '') {
			var arrayos = njumpInf.osids.split(","); //os
			$('#' + njumpInf.njumphash + '_' + njumpInf.id).find(' .selectsBoxRowsEditTableos' + njumpInf.njumphash + '_' + njumpInf.id).val(arrayos).trigger('chosen:updated');
		}

		if(njumpInf.sback.toString() != '') {
			var arraySback = njumpInf.sback.toString().split(","); //sback
			$('#' + njumpInf.njumphash + '_' + njumpInf.id).find('.selectsBoxRowsEditTableSBack' + njumpInf.njumphash + '_' + njumpInf.id).val(arraySback).trigger('chosen:updated');
		}

		$('#' + njumpInf.njumphash + '_' + njumpInf.id).find(' .inputProportion' + njumpInf.njumphash + '_' + njumpInf.id).val(njumpInf.proportion);
		$('#' + njumpInf.njumphash + '_' + njumpInf.id).find(' .inputLineName' + njumpInf.njumphash + '_' + njumpInf.id).val(njumpInf.linename);

		reloadPercents();
		reloadBorders();
		reloadHrefs();

		$('.iconsDivObject4').popover({ trigger: "hover" });
	}

	$("body").on("mouseover", ".divExpandAll", function () {
		if($(this).hasClass('over')) {
			$(this).find('.popover').remove();
		}
	});	

	$("body").on("click", ".expandOneNjump", function () {
	    
	    $(this).find('.popover').remove();

	    if($(this).hasClass('over')) {
	    	$(this).removeClass("over");
	    	$(this).addClass('out');
	    	$(this).closest('.rownInfo').find(".bordertopInfoRow").slideUp(100);

	    	$(".divExpandAll").removeClass("over");
	    	$(".divExpandAll").addClass('out');

	    	$(this).closest('.rownInfo').find(".borderleftInfoRow").removeClass('borderleftInfoRow2');


	    	$(this).closest('.rownInfo').find(".borderClicksEpcTotal").addClass('borderleftColumnClicksepc');
	    	$(this).closest('.rownInfo').find(".borderClicksEpcTotal").removeClass('borderleftColumnClicksepc2');

	    	$(this).closest('.rownInfo').find(".borderPerTotal").addClass('borderleftColumnPerc');
	    	$(this).closest('.rownInfo').find(".borderPerTotal").removeClass('borderleftColumnPerc2');

	    } else if($(this).hasClass('out')) {
	    	$(this).removeClass("out");
	    	$(this).addClass('over');
	    	$(this).closest('.rownInfo').find(".bordertopInfoRow").slideDown(100);

	    	$(this).closest('.rownInfo').find(".borderleftInfoRow").addClass('borderleftInfoRow2');

	    	$(this).closest('.rownInfo').find(".borderPerTotal").removeClass('borderleftColumnPerc');
	    	$(this).closest('.rownInfo').find(".borderPerTotal").addClass('borderleftColumnPerc2');

	    	$(this).closest('.rownInfo').find(".borderClicksEpcTotal").removeClass('borderleftColumnClicksepc');
	    	$(this).closest('.rownInfo').find(".borderClicksEpcTotal").addClass('borderleftColumnClicksepc2');

	    } else {
	    	$(this).addClass('over');
	    	$(this).closest('.rownInfo').find(".bordertopInfoRow").slideDown(100);

	    	$(this).closest('.rownInfo').find(".borderleftInfoRow").addClass('borderleftInfoRow2');

	    	$(this).closest('.rownInfo').find(".borderPerTotal").removeClass('borderleftColumnPerc');
	    	$(this).closest('.rownInfo').find(".borderPerTotal").addClass('borderleftColumnPerc2');

	    	$(this).closest('.rownInfo').find(".borderClicksEpcTotal").removeClass('borderleftColumnClicksepc');
	    	$(this).closest('.rownInfo').find(".borderClicksEpcTotal").addClass('borderleftColumnClicksepc2');

	    }
	});

	var lineNSchedule = '';
	var elementButtonSch = '';
	$("body").on("click", "#SaveTimePick", function () {

		var times = JSON.stringify(parting.getSelectedDays())
	
		var lineN = lineNSchedule;

		var formData = new FormData();
		formData.append('njumphash', myHash);
		formData.append('idLine', lineN);
		formData.append('content', times);
		formData.append('type', 7);

		elementButtonSch.attr('time', times);

		if(times == '{}') {
			elementButtonSch.removeClass('SchedulewithIcon')
		} else {
			elementButtonSch.addClass('SchedulewithIcon')
		}

		saveChanges(formData, elementButtonSch);
		checkChanges(elementButtonSch);
	});

	$("body").on("click", "#removeAllSch", function () {
		parting.refreshContent("day_parting", null,null)
	});

	$("body").on("click", ".resetProportion", function () {
		$(this).closest('.rownInfo').find('.inputProportion').val(0);

		$(this).closest('.rownInfo').find('.inputProportion').blur();

		reloadPercents();
	});

	function reloadPercents() {
		
		var valuesObject = {};
		$(".rownInfo").each(function( index ) {

			var values = $(this).find('.sBCarriers').chosen().val();
			var proportions = $(this).find('.inputProportion').val();

			if(values == null) {
				if(!valuesObject.hasOwnProperty(0)) {
					valuesObject[0] = [0, 1, proportions];
				} else {
					valuesObject[0][1] = valuesObject[0][1] + 1;
					valuesObject[0][2] = parseInt(valuesObject[0][2]) + parseInt(proportions);
				}
			}

			$(values).each(function( index ) {
				if(!valuesObject.hasOwnProperty(values[index])) {
					valuesObject[values[index]] = [values[index], 1, proportions];
				} else {
					valuesObject[values[index]][1] = valuesObject[values[index]][1] + 1;
					valuesObject[values[index]][2] = parseInt(valuesObject[values[index]][2]) + parseInt(proportions);
				}
			});
		});

		$(".rownInfo").each(function( index ) {
			
			var values = $(this).find('.sBCarriers').chosen().val();
			var proportions = $(this).find('.inputProportion').val();

			var element = $(this);

			if(values == null) {
				if(parseInt(valuesObject[0][2]) != 0) {
					$(this).find('.percentInfoClass').html(((100*parseInt(proportions)) / (parseInt(valuesObject[0][2]))).toFixed(2) + '%');
				} else {
					$(this).find('.percentInfoClass').html('0%');
				}
			} else if(values.length == 1) {
				if(parseInt(valuesObject[values][2]) != 0) {
					$(this).find('.percentInfoClass').html(((100*parseInt(proportions)) / (parseInt(valuesObject[values][2]))).toFixed(2) + '%');
				} else {
					$(this).find('.percentInfoClass').html('0%');
				}
			} else if(values.length > 1) {
				
				var dataContent="<span class=\'carriersInsidePopover\'>";

				$(values).each(function( index ) {
					if(valuesObject.hasOwnProperty(values[index])) {
						element.find('.percentInfoClass').html(imgMultiCarriers);
						
						if(parseInt(valuesObject[values[index]][2]) != 0) {
							dataContent += '<b>' + carriersJsObject[valuesObject[values[index]][0]] + '</b> - ' + ((100*parseInt(proportions)) / (parseInt(valuesObject[values[index]][2]))).toFixed(2) + '%<br>';
						} else {
							dataContent += '<b>' + carriersJsObject[valuesObject[values[index]][0]] + '</b> - 0%<br>';
						}
					} 
				});

				element.find('.iconMultiCarriers').attr('data-content' , dataContent + "</span>");

			}
		});	

		$('.iconMultiCarriers').popover({ trigger: "hover" });  
	}

	$(document).keyup(function(e) {
	     if (e.keyCode == 27) { // escape key maps to keycode `27`
	        if($('.modal').is(':visible')) {
	        	$('.modal').modal("hide");
	        }
	    }
	});


	$("#selectsPeriod").chosen({
		allow_single_deselect: true,
		no_results_text: "Nothing found!",
		width: "100%",
		search_contains: false,
		disable_search:true
	}).change(function(event){

		if(event.target == this){
			changePeriodicity(this)
		}

	});

	var lastType = 0;
	var lastOrder = 0;
	$("body").on("click", ".sorterTiles", function () {

		$(".sorterTiles").not(this).find('img').attr('orderT', 'ASC');
		$(".sorterTiles").not(this).find('img').attr('src', '/img/iconssort/sort_both.png');

		var sorterId = $(this).find('img').attr('type');
		var hash = myHash;
		var order = '';

		$(document).one("ajaxStart", function() {
			//ALERT
			sortingMessage('Sorting!', 1);
		});
		
		var order;
		if($(this).find('img').attr('ordert') == 'ASC') {
			order = 0;
			lastOrder = order;
			$(this).find('img').attr('ordert', 'DESC');
			$(this).find('img').attr('src', '/img/iconssort/sort_desc.png');
		} else if($(this).find('img').attr('ordert') == 'DESC') {
			order = 1;
			lastOrder = order;
			$(this).find('img').attr('ordert', 'ASC');
			$(this).find('img').attr('src', '/img/iconssort/sort_asc.png');
		} 

		lastType = sorterId;

		var periodicity = $("#selectsPeriod").chosen().val();

		var formData = new FormData();
		formData.append('njumphash', hash);
		formData.append('sorter', sorterId);
		formData.append('order', order);
		formData.append('periodicity', periodicity);

		$.ajax({
			url: '/njump/njumpsortby',
			type: 'POST',
			data: formData,
			async: true,
			success: function(data) {
				
				if(data != 1) {
					
					sortByThis(data);
				
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

	$("body").on("click", "#reloadSorter", function () {

		var sorterId = lastType;
		var hash = myHash;
		var order = lastOrder;
		var periodicity = $("#selectsPeriod").chosen().val();

		var formData = new FormData();
		formData.append('njumphash', hash);
		formData.append('sorter', sorterId);
		formData.append('order', order);
		formData.append('periodicity', periodicity);

		$.ajax({
			url: '/njump/njumpsortby',
			type: 'POST',
			data: formData,
			async: true,
			success: function(data) {
				
				if(data != 1) {
					
					sortByThis(data);
				
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

		reloadBorders();
	});

	getPeriodicity();
	var valuesEpcClicks;
	function getPeriodicity() {
		
		var hash = myHash;

		var formData = new FormData();
		formData.append('njumphash', hash);

		$.ajax({
			url: '/njump/getStatistics',
			type: 'POST',
			data: formData,
			async: true,
			success: function(data) {
				
				if(data != 1) {
					
					valuesEpcClicks = data;

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


	}

	Array.prototype.contains = function(obj) {
	    var i = this.length;
	    while (i--) {
	        if (this[i] === obj) {
	            return true;
	        }
	    }
	    return false;
	}

	function changePeriodicity(element) {
		
		var value = $(element).chosen().val();

		var jsonInf33 = $.parseJSON(valuesEpcClicks);

		var arrayIdsLines = [];
		for (var i = 0; i < jsonInf33.length; i++) {
			arrayIdsLines.push(jsonInf33[i].id);
		}

		$(".rownInfo").each(function( index ) {
			
			if(arrayIdsLines.contains($(this).attr('idline'))) {
				for (var i = 0; i < jsonInf33.length; i++) {
					if($(this).attr('idline') == jsonInf33[i].id) {
					
						if(value == 0) {
							//today
							$(this).find('.epcChangePeriod').html(jsonInf33[i].epc);
							$(this).find('.clicksChangePeriod').html(jsonInf33[i].clicks);

						} else if(value == 1) {
							//yesterday
							$(this).find('.epcChangePeriod').html(jsonInf33[i].epc1days);
							$(this).find('.clicksChangePeriod').html(jsonInf33[i].clicks1days);
		
						} else if(value == 2) {
							//3 days
							$(this).find('.epcChangePeriod').html(jsonInf33[i].epc3days);
							$(this).find('.clicksChangePeriod').html(jsonInf33[i].clicks3days);

						} else if(value == 3) {
							//7 days
							$(this).find('.epcChangePeriod').html(jsonInf33[i].epc7days);
							$(this).find('.clicksChangePeriod').html(jsonInf33[i].clicks7days);
							
						} else if(value == 4) {
							//1 hour
							$(this).find('.epcChangePeriod').html(jsonInf33[i].epc1hours);
							$(this).find('.clicksChangePeriod').html(jsonInf33[i].clicks1hours);
						
						} else if(value == 5) {
							//3 hour
							$(this).find('.epcChangePeriod').html(jsonInf33[i].epc3hours);
							$(this).find('.clicksChangePeriod').html(jsonInf33[i].clicks3hours);
							
						} else if(value == 6) {
							//6 hour
							$(this).find('.epcChangePeriod').html(jsonInf33[i].epc6hours);
							$(this).find('.clicksChangePeriod').html(jsonInf33[i].clicks6hours);
							
						} else if(value == 7) {
							//12 hour
							$(this).find('.epcChangePeriod').html(jsonInf33[i].epc12hours);
							$(this).find('.clicksChangePeriod').html(jsonInf33[i].clicks12hours);
							
						}
					}
				}
			} else {
				$(this).find('.epcChangePeriod').html('0.000000');
				$(this).find('.clicksChangePeriod').html('0');
			}
		});
	}

	function reloadBorders() {
		$(".rownInfo").removeClass('borderSeparator');

		$(".rownInfo").each(function( index ) {

			var indexplus = index + 1;

			if($(this).attr('classColor') != $(".rownInfo").eq( indexplus ).attr('classColor')) {
				if (typeof $(".rownInfo").eq( indexplus ).attr('idLine') !== "undefined") {
				    $(".rownInfo").eq( indexplus ).addClass('borderSeparator');
				}
			}
		});
	}

	var oldGlobalName
	$("body").on("click", "#globalnamevar", function () {
		if(!$(this).hasClass('inputMode')) {
			oldGlobalName = $(this).text();
			$(this).empty().html('<input id="inputchangeGlobalName" value="' + oldGlobalName + '"></input>').addClass('inputMode');
			
			var inputGN = $("#inputchangeGlobalName");
			inputGN.focus();
    		var val = inputGN.val(); 
			inputGN.val(''); 
			inputGN.val(val);
		} 		
	});	

	$("body").on("focusout", "#inputchangeGlobalName", function () {
		if($(this).val() == oldGlobalName) {
			$("#globalnamevar").empty().html(oldGlobalName).removeClass('inputMode');
		} else {
			
			$(document).one("ajaxStart", function() {
				//ALERT
				savingMessage();
			});

			var hash = myHash;
			var newValueGlobalName = $(this).val();

			var formData = new FormData();
			formData.append('njumphash', hash);
			formData.append('name', newValueGlobalName);

			var statusOn = 0;

			$.ajax({
				url: '/njump/changeGlobalName',
				type: 'POST',
				data: formData,
				async: true,
				success: function(data) {
					
					if(data != 1) {
						
						statusOn = 1;

						$("#globalnamevar").empty().html(newValueGlobalName).removeClass('inputMode');

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
				if(statusOn == 1){
					savedMessage();
				}
			});
		}
	});	

	$("body").on("keypress", "#inputchangeGlobalName", function (e) {
	  if (e.which == 13) {
	    $(this).blur();  
	    return false;    //<---- Add this line
	  }
	});

	window.addEventListener('popstate', function(event) {
	    window.location = '/njump';
	}, false);
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
		allow_single_deselect: true,
		no_results_text: "Nothing found!",
		width: "100%",
		search_contains: true
	})

	$("#modalCreateNJump").modal('show')
}