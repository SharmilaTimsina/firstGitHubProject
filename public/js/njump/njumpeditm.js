var carriersJsObject = {};

$(document).ready(function() {
	
	var myHash = njumphash;
	
	fillSelects();

	function fillSelects() {
		for (var i = 0; i < carriersvar.length; i++) {
			carriersJsObject[carriersvar[i]['id']] = carriersvar[i]['name'];
		}
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

		var badNjump = '';
		if(statusvar > 1) {
			badNjump =  '<img class="iconsDivObject51" data-toggle="popover" data-placement="bottom"  title="" data-content="bad njump" data-clipboard-text="" src="/img/njumppage/youmesseditup_wht.svg">'
		} else {
			badNjump =  '<img style="display: none" class="iconsDivObject51" data-toggle="popover" data-placement="bottom"  title="" data-content="bad njump" data-clipboard-text="" src="/img/njumppage/youmesseditup_wht.svg">'
		}
		
		$("#countryNjumpRow").text(countryname[1]);

		$("#njumpGaneratedName").html(njumpgeneratednamevar + ' | ' + globalnamevar + '		' + badNjump);

		$("#sourceInfoRow").text(sourcenamevar);

		setlines();
	}

	function setlines() {

		var njumpsJson = njumpsvar;

		$("#DivForLines").empty();

		var rows = '';
		for (var i = 0; i < njumpsJson.length; i++) {
				var njumpInf = njumpsJson[i]

				var badLine = '';
				if(njumpInf.status > 1) {
					badLine = '<img class="iconsDivObject50 badNjump" data-toggle="popover" data-placement="bottom"  title="" data-content="bad line" data-clipboard-text="" src="/img/njumppage/youmesseditup_wht.svg">'
				} else {
					badLine = '<img style="display: none;" class="iconsDivObject50" data-toggle="popover" data-placement="bottom"  title="" data-content="bad line" data-clipboard-text="" src="/img/njumppage/youmesseditup_wht.svg">'
				}
				
				row = '<div idLine="' + njumpInf.id + '" class="col-md-12 linesM">'
							+ '<div class="row">'
								+ '<p class="titlesLines">' + njumpInf.linename + badLine + '</p>'
							+ '</div>'
							+ '<div class="row lineBorder">'
								+ '<p class="titlesLines">Carriers:<span carriersids="' +  njumpInf.carrierids + '" class="carriersString"></span></p>'
							+ '</div>'
							+ '<div class="row" style="margin-top: 10px;">'
								+ '<p class="titlesLines">Proportion:</p>'
								+ '<input idLine="' + njumpInf.id + '" type="number" value="' + njumpInf.proportion + '" class="inputProportion">'
							+ '</div>'
				      	+ '</div>';

				rows = rows + row;
		}

		$("#DivForLines").append(rows);

		reloadPercents();
	}

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
						} else {
							$(element).closest('.rownInfo').find('.iconsDivObject50').addClass('badNjump')
							$(element).closest('.rownInfo').find('.iconsDivObject50').show();
						}

						if(njumpInf2.njumpstatus <= 1) {
							$(".iconsDivObject51").hide();
						} else {
							$(".iconsDivObject51").show();
						}

					} else {
						errorMessage();
					}

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
			}
		});
	}

	function savedMessage() {
		$('.notifyjs-wrapper').trigger('notify-hide');
		setTimeout(function(){
			
			$.notify("Saved!", { 
				clickToHide: false,
				autoHide: false,
				autoHideDelay: 500,
				arrowShow: true,
				arrowSize: 5,
				breakNewLines: true,
				elementPosition: "bottom",
				globalPosition: "top left",
				style: "bootstrap",
				className: "success",
				showAnimation: "slideDown",
				showDuration: 100,
				hideAnimation: "slideUp",
				hideDuration: 100,
				gap: 5
			});

		}, 300);
	}

	function errorMessage() {
		$('.notifyjs-wrapper').trigger('notify-hide');
	
		$.notify("error!", { 
			clickToHide: false,
			autoHide: false,
			autoHideDelay: 500,
			arrowShow: true,
			arrowSize: 5,
			breakNewLines: true,
			elementPosition: "bottom",
			globalPosition: "top left",
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
		
		$.notify(message, { 
			clickToHide: false,
			autoHide: false,
			autoHideDelay: 500,
			arrowShow: true,
			arrowSize: 5,
			breakNewLines: true,
			elementPosition: "bottom",
			globalPosition: "top left",
			style: "bootstrap",
			className: "error",
			showAnimation: "slideDown",
			showDuration: 100,
			hideAnimation: "slideUp",
			hideDuration: 100,
			gap: 5
		});
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
			globalPosition: "top left",
			style: "bootstrap",
			className: "warn",
			showAnimation: "slideDown",
			showDuration: 100,
			hideAnimation: "slideUp",
			hideDuration: 100,
			gap: 5
		});
	}
	
	function reloadPercents() {
		
		var valuesObject = {};
		$(".linesM").each(function( index ) {

			var values = '';
			if($(this).find('.carriersString').attr('carriersids') == '') {
				values = $(this).find('.carriersString').attr('carriersids');
			} else {
				values = $(this).find('.carriersString').attr('carriersids').split(',');
			}
			
			var proportions = $(this).find('.inputProportion').val();

			if(values == null || values == '') {
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

		$(".linesM").each(function( index ) {
			
			var values = '';
			if($(this).find('.carriersString').attr('carriersids') == '') {
				values = $(this).find('.carriersString').attr('carriersids');
			} else {
				values = $(this).find('.carriersString').attr('carriersids').split(',');
			}

			var proportions = $(this).find('.inputProportion').val();

			var element = $(this);

			if(values == null || values == '') {
				if(parseInt(valuesObject[0][2]) != 0) {
					element.find('.carriersString').html('<b>(' + ((100*parseInt(proportions)) / (parseInt(valuesObject[0][2]))).toFixed(2) + '%)</b>');
				} else {
					element.find('.carriersString').html('<b>(0%)</b>');
				}
			} else if(values.length == 1) {
				if(parseInt(valuesObject[values[0]][0]) != 0) {
					element.find('.carriersString').html(carriersJsObject[valuesObject[values[0]][0]] + '<b>(' + ((100*parseInt(proportions)) / (parseInt(valuesObject[values[0]][2]))).toFixed(2) + '%)</b>');
				} else {
					element.find('.carriersString').html('<b>(0%)</b>');
				}
			} else if(values.length > 1) {
				
				var dataContent="";

				$(values).each(function( index ) {
					if(valuesObject.hasOwnProperty(values[index])) {
						
						if(parseInt(valuesObject[values[index]][2]) != 0) {
							dataContent += carriersJsObject[valuesObject[values[index]][0]] + '<b>(' + ((100*parseInt(proportions)) / (parseInt(valuesObject[values[index]][2]))).toFixed(2) + '%)</b> ';
						} else {
							dataContent += carriersJsObject[valuesObject[values[index]][0]] + '<b>(0%)<b>';
						}
					} 
				});

				element.find('.carriersString').html(dataContent);
			}
		});	
	}
});
