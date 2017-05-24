$(document).ready(function() {
   	
	$('body').on('click', "#countriesSBOX .options .opt > span > i", function() {
		if($(this).parent().parent().closest('.opt').hasClass('selected')) {
			$(this).parent().closest('.opt').removeClass('selected');
			$(this).parent().parent().find('.buttonDropDown').attr('disabled', true);

			if($('#countriesSBOX .optWrapper > p').hasClass('selected')) {
				$('#countriesSBOX .optWrapper > p').removeClass('selected');
				$('#countriesSBOX .optWrapper > p').addClass('partial');
			}

			$(this).parent().closest('.opt').insertAfter("#countriesSBOX .options .group");

			
		} else {			
			$(this).parent().closest('.opt').addClass('selected');
			$(this).parent().parent().find('.buttonDropDown').attr('disabled', false);

			if($('#countriesSBOX .optWrapper > p').hasClass('partial')) {
				if(allSelected()) {
					$('#countriesSBOX .optWrapper > p').removeClass('partial');
					$('#countriesSBOX .optWrapper > p').addClass('selected');
				}
			}

			if ( !$( "#groupSelectedsCountries" ).length ) {
				$('<li class="group"><label style="margin-bottom: -7px;">Selected</label><ul id="groupSelectedsCountries"></ul></li>').insertBefore("#countriesSBOX .options .opt:first-child");
			}
						
			$("#groupSelectedsCountries").append($(this).parent().closest('.opt'));
		}

		if(noSelecteds()) {
			if ( !$( "#noresults" ).length ) {
				$("#groupSelectedsCountries").append('<li id="noresults" class="opt"><label style="color: darkgrey;">0 selected</label></li>');
			}
		} else {
			$("#noresults").remove();
		}

		if(allSelected()) {
			updateCountry('ALL')
		} else {
			country = $(this).parent().parent().find('.buttonDropDown').val();
			updateCountry(country)
		}
	});

	$('body').on('click', "#countriesSBOX .optWrapper > p", function() {
		if($(this).hasClass('selected')) {
			$('#countriesSBOX .opt').each(function (ix, e) {
                $("#groupSelectedsCountries").append($(this));
                $(this).addClass('selected');
                $(this).find('.buttonDropDown').attr('disabled', false);
            });

            $(this).removeClass('partial');
			$(this).addClass('selected');

		} else if($(this).hasClass('partial')) {
			
			$('#countriesSBOX .opt').each(function (ix, e) {
               	$("#countriesSBOX .options").append($(this));
                $(this).addClass('selected');
                $(this).find('.buttonDropDown').attr('disabled', false);
            });

			$(this).removeClass('partial');
			$(this).addClass('selected');
		
		} else {
			$('#countriesSBOX .opt').each(function (ix, e) {
                $("#countriesSBOX .options").append($(this));
                $(this).removeClass('selected');
                $(this).find('.buttonDropDown').attr('disabled', true);
            });
		}

		if(noSelecteds()) {
			if ( !$( "#noresults" ).length ) {
				$("#groupSelectedsCountries").append('<li id="noresults" class="opt"><label style="color: darkgrey;">0 selected</label></li>');
			}
		} else {
			$("#noresults").remove();
		}

		if(allSelected()) {
			updateCountry('ALL')
		}
	});

	function allSelected() {
		allS = false;
		$('#countriesSBOX .opt').each(function (ix, e) {
            if($(this).hasClass('selected')) {
            	allS = true;
            } else {
            	allS = false;
            	return allS;
            }
        });

        return allS;
	}

	function noSelecteds() {
		allS = false;
		$('#countriesSBOX .opt').each(function (ix, e) {
            if($(this).hasClass('selected')) {
            	allS = false;
            	return allS;
            } else {
            	allS = true;
            }
        });

        return allS;
	}

	$('body').on('click', ".buttonDropDown", function() {

		$('select#sourcesSS')[0].sumo.unSelectAll();
		$('select#aggsSS')[0].sumo.unSelectAll();
		
		/*
		$('#sourcesSBOX .opt').each(function (ix, e) {
			if($(this).hasClass('selected')) {
            	$("#groupSelectedsSources").append($(this));
            }
        });	

        $('#agregatorsSBOX .opt').each(function (ix, e) {
        	if($(this).hasClass('selected')) {
            	$("#groupSelectedsAgregators").append($(this));
            }

            if(!$(this).hasClass('selected')) {
            	$(".options").append($(this));
            }
        });	
		*/

         
		formData = new FormData();
		formData.append('u', user)
		formData.append('c', $(this).val())

		var agregatorsArray = null;
		var sourcesArray = null;
		$.ajax({
			url: './permission/srcagr',
			type: 'POST',
			data: formData,
			async: true,
			success: function(data) {
				obj = JSON.parse(data);

				if(obj[0].agregators != null) 
					agregatorsArray = obj[0].agregators.split(",");
				
				if(obj[0].sources != null) 
					sourcesArray = obj[0].sources.split(",");
			},
			error: function(response) {
				alert("error");
			},
			cache: false,
			contentType: false,
			processData: false
		});
		
		$(document).one("ajaxStop", function() {

			$("#agregatorsSBOX li").unbind();
			$("#agregatorsSBOX .MultiControls .btnOk").unbind();
			$("#agregatorsSBOX .MultiControls .btnCancel").unbind();

			$("#sourcesSBOX li").unbind();
			$("#sourcesSBOX .MultiControls .btnOk").unbind();
			$("#sourcesSBOX .MultiControls .btnCancel").unbind();

			
			if ( !$( "#groupSelectedsSources" ).length ) {
				$('<li class="group"><label style="margin-bottom: -7px;">Selected</label><ul id="groupSelectedsSources"></ul></li>').insertBefore("#sourcesSBOX .options .opt:first-child");
			}

			
			if ( !$( "#groupSelectedsAgregators" ).length) {
				$('<li class="group"><label style="margin-bottom: -7px;">Selected</label><ul id="groupSelectedsAgregators"></ul></li>').insertBefore("#agregatorsSBOX .options .opt:first-child");
			}

			if(agregatorsArray != null && agregatorsArray != '') {
				if(agregatorsArray.length != 0) {
					$( "#noresults2" ).remove();

					for (i = 0; i < agregatorsArray.length; i++) {
						$('select#aggsSS')[0].sumo.selectItem(agregatorsArray[i]);
					}

					$('#agregatorsSBOX .opt').each(function () {
		              	if($(this).hasClass('selected')) {
		              		console.log("ok22222");
		                	$("#groupSelectedsAgregators").append($(this));
		              	}
		            });			
					
					console.log("ok");
				} else {
					if ( !$( "#noresults2" ).length ) 
						$("#groupSelectedsAgregators").append('<li id="noresults2" class="opt"><label style="color: darkgrey;">0 selected</label></li>');
				}
			} else {
				if ( !$( "#noresults2" ).length ) 
					$("#groupSelectedsAgregators").append('<li id="noresults2" class="opt"><label style="color: darkgrey;">0 selected</label></li>');
				
				console.log("nok");
			}

			/*
			if(sourcesArray != null && sourcesArray != '') {
				$( "#noresults3" ).remove();

				for (i = 0; i < sourcesArray.length; i++) {
					$('select#sourcesSS')[0].sumo.selectItem(sourcesArray[i]);
				}

				$('#sourcesSBOX .opt').each(function () {
	              	if($(this).hasClass('selected')) {
	              		console.log("ok22222");
	                	$("#groupSelectedsSources").append($(this));
	              	}
	            });			
				
				console.log("ok");
			} else {
				if ( !$( "#noresults3" ).length ) 
					$("#groupSelectedsSources").append('<li id="noresults3" class="opt"><label style="color: darkgrey;">0 selected</label></li>');
				
				console.log("nok");
			}

			/*
			if(sourcesArray != null && sourcesArray != '') {
				$( "#noresults3" ).remove();

				for (i = 0; i < sourcesArray.length; i++) {
					$('select#sourcesSS')[0].sumo.selectItem(sourcesArray[i]);
				}

				$('#sourcesSBOX .opt').each(function (ix, e) {
	              	if($(this).hasClass('selected'))
	                	$("#groupSelectedsSources").append($(this));
	            });			
				
			} else {
				if ( !$( "#noresults3" ).length ) 
					$("#groupSelectedsSources").append('<li id="noresults3" class="opt"><label style="color: darkgrey;">0 selected</label></li>');
			}
			*/



			$(".secondStep").show();
		});
	});

	function updateCountry(country) {
		formData = new FormData();
		formData.append('u', user)
		formData.append('c', country)

		$.ajax({
			url: './permission/updatecountry',
			type: 'POST',
			data: formData,
			async: true,
			success: function(data) {
				console.log("ok");
			},
			error: function(response) {
				alert("error");
			},
			cache: false,
			contentType: false,
			processData: false
		});
	}

	//agr
	$('body').on('click', "#agregatorsSBOX .options .opt > span > i", function() {
		if($(this).parent().parent().closest('.opt').hasClass('selected')) {
			$(this).parent().closest('.opt').removeClass('selected');

			if($('#agregatorsSBOX .optWrapper > p').hasClass('selected')) {
				$('#agregatorsSBOX .optWrapper > p').removeClass('selected');
				$('#agregatorsSBOX .optWrapper > p').addClass('partial');
			}

			$(this).parent().closest('.opt').insertAfter("#agregatorsSBOX .options .group");

			
		} else {			
			$(this).parent().closest('.opt').addClass('selected');
			
			if($('#agregatorsSBOX .optWrapper > p').hasClass('partial')) {
				if(allSelected2()) {
					$('#agregatorsSBOX .optWrapper > p').removeClass('partial');
					$('#agregatorsSBOX .optWrapper > p').addClass('selected');
				}
			}

			if ( !$( "#groupSelectedsAgregators" ).length ) {
				$('<li class="group"><label style="margin-bottom: -7px;">Selected</label><ul id="groupSelectedsAgregators"></ul></li>').insertBefore("#agregatorsSBOX .options .opt:first-child");
			}
						
			$("#groupSelectedsAgregators").append($(this).parent().closest('.opt'));
		}

		if(noSelecteds2()) {
			if ( !$( "#noresults2" ).length ) {
				$("#groupSelectedsAgregators").append('<li id="noresults2" class="opt"><label style="color: darkgrey;">0 selected</label></li>');
			}
		} else {
			$("#noresults2").remove();
		}
	});

	$('body').on('click', "#agregatorsSBOX .optWrapper > p", function() {
		if($(this).hasClass('selected')) {
			$('#agregatorsSBOX .opt').each(function (ix, e) {
                $("#groupSelectedsAgregators").append($(this));
                $(this).addClass('selected');
            });

            $(this).removeClass('partial');
			$(this).addClass('selected');

		} else if($(this).hasClass('partial')) {
			
			$('#agregatorsSBOX .opt').each(function (ix, e) {
               	$("#agregatorsSBOX .options").append($(this));
                $(this).addClass('selected');
            });

			$(this).removeClass('partial');
			$(this).addClass('selected');
			
		} else {
			$('#agregatorsSBOX .opt').each(function (ix, e) {
                $("#agregatorsSBOX .options").append($(this));
                $(this).removeClass('selected');
            });
		}

		if(noSelecteds2()) {
			if ( !$( "#noresults2" ).length ) {
				$("#groupSelectedsAgregators").append('<li id="noresults2" class="opt"><label style="color: darkgrey;">0 selected</label></li>');
			}
		} else {
			$("#noresults2").remove();
		}
	});

	function allSelected2() {
		allS = false;
		$('#agregatorsSBOX .opt').each(function (ix, e) {
            if($(this).hasClass('selected')) {
            	allS = true;
            } else {
            	allS = false;
            	return allS;
            }
        });

        return allS;
	}

	function noSelecteds2() {
		allS = false;
		$('#agregatorsSBOX .opt').each(function (ix, e) {
            if($(this).hasClass('selected')) {
            	allS = false;
            	return allS;
            } else {
            	allS = true;
            }
        });

        return allS;
	}

	//src
	$('body').on('click', "#sourcesSBOX .options .opt > span > i", function() {
		if($(this).parent().parent().closest('.opt').hasClass('selected')) {
			$(this).parent().closest('.opt').removeClass('selected');

			if($('#sourcesSBOX .optWrapper > p').hasClass('selected')) {
				$('#sourcesSBOX .optWrapper > p').removeClass('selected');
				$('#sourcesSBOX .optWrapper > p').addClass('partial');
			}

			$(this).parent().closest('.opt').insertAfter("#sourcesSBOX .options .group");

			
		} else {			
			$(this).parent().closest('.opt').addClass('selected');
			
			if($('#sourcesSBOX .optWrapper > p').hasClass('partial')) {
				if(allSelected3()) {
					$('#sourcesSBOX .optWrapper > p').removeClass('partial');
					$('#sourcesSBOX .optWrapper > p').addClass('selected');
				}
			}

			if ( !$( "#groupSelectedsSources" ).length ) {
				$('<li class="group"><label style="margin-bottom: -7px;">Selected</label><ul id="groupSelectedsSources"></ul></li>').insertBefore("#sourcesSBOX .options .opt:first-child");
			}
						
			$("#groupSelectedsSources").append($(this).parent().closest('.opt'));
		}

		if(noSelecteds3()) {
			if ( !$( "#noresults3" ).length ) {
				$("#groupSelectedsSources").append('<li id="noresults3" class="opt"><label style="color: darkgrey;">0 selected</label></li>');
			}
		} else {
			$("#noresults3").remove();
		}
	});

	$('body').on('click', "#sourcesSBOX .optWrapper > p", function() {
		if($(this).hasClass('selected')) {
			$('#sourcesSBOX .opt').each(function (ix, e) {
                $("#groupSelectedsSources").append($(this));
                $(this).addClass('selected');
            });

            $(this).removeClass('partial');
			$(this).addClass('selected');

		} else if($(this).hasClass('partial')) {
			
			$('#sourcesSBOX .opt').each(function (ix, e) {
               	$("#sourcesSBOX .options").append($(this));
                $(this).addClass('selected');
            });

			$(this).removeClass('partial');
			$(this).addClass('selected');
			
		} else {
			$('#sourcesSBOX .opt').each(function (ix, e) {
                $("#sourcesSBOX .options").append($(this));
                $(this).removeClass('selected');
            });
		}

		if(noSelecteds3()) {
			if ( !$( "#noresults3" ).length ) {
				$("#groupSelectedsSources").append('<li id="noresults3" class="opt"><label style="color: darkgrey;">0 selected</label></li>');
			}
		} else {
			$("#noresults3").remove();
		}
	});

	function allSelected3() {
		allS = false;
		$('#sourcesSBOX .opt').each(function (ix, e) {
            if($(this).hasClass('selected')) {
            	allS = true;
            } else {
            	allS = false;
            	return allS;
            }
        });

        return allS;
	}

	function noSelecteds3() {
		allS = false;
		$('#sourcesSBOX .opt').each(function (ix, e) {
            if($(this).hasClass('selected')) {
            	allS = false;
            	return allS;
            } else {
            	allS = true;
            }
        });

        return allS;
	}

	
	$('body').on('click', "#agregatorsSBOX .MultiControls .btnOk", function() {
		

		
		

	});

	$('body').on('click', "#agregatorsSBOX .MultiControls .btnCancel", function() {
		
		
		

	});
	
	$('body').on('click', "#sourcesSBOX .MultiControls .btnOk", function() {
		

		
		

	});

	$('body').on('click', "#sourcesSBOX .MultiControls .btnCancel", function() {
		
		
		

	});
});

user = null;
function fireUserChange(element) {
	if(element != null) {
		
		$("#countriesSS").empty();
		$(".CaptionCont").css('width', '100%');
		$('.search-box-sel-all2')[0].sumo.disable();

		formData = new FormData();
		formData.append('u', $(element).val())
		user = $(element).val();

		$.ajax({
			url: './permission/countries',
			type: 'POST',
			data: formData,
			async: true,
			success: function(data) {
				$("#countriesSS").append(data);
				$('.search-box-sel-all')[0].sumo.reload();
				$('.search-box-sel-all')[0].sumo.enable();
				$(".CaptionCont").css('width', '100%');
				
				$( "#countriesSBOX .opt" ).each(function() {
				  $( this ).attr('title', $( this).find("label").text());

				  if($(this).hasClass('selected')) {
				  	$( this ).append('<button value="' + $('#countriesSS option[name="' + $( this).find("label").text() + '"]').val() + '" class="buttonDropDown btn btn-warning" style="float: right;margin-top: -19px;padding: 0px;padding-left: 10px;padding-right: 10px;">+</button>');
				  } else {
				  	$( this ).append('<button value="' + $('#countriesSS option[name="' + $( this).find("label").text() + '"]').val() + '" disabled class="buttonDropDown btn btn-warning" style="float: right;margin-top: -19px;padding: 0px;padding-left: 10px;padding-right: 10px;">+</button>');
				  }
				});

				if ( !$( "#groupSelectedsCountries" ).length ) {
					$('<li class="group"><label style="margin-bottom: -7px;">Selected</label><ul id="groupSelectedsCountries"></ul></li>').insertBefore("#countriesSBOX .options .opt:first-child");
					$("#groupSelectedsCountries").append('<li id="noresults" class="opt"><label style="color: darkgrey;">0 selected</label></li>');
				}

				$("#countriesSBOX li").unbind();

				results = false;
				$('#countriesSBOX .opt').each(function (ix, e) {
	                if($(this).hasClass('selected')) {
	                	$("#groupSelectedsCountries").append($(this));
	                	$(this).addClass('selected');
	                	$(this).find('.buttonDropDown').attr('disabled', false);
	                	results = true;
	                }
	            });

				if(results)
	            	$("#noresults").remove();

	            $('.search-box-sel-all2')[0].sumo.enable();

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

