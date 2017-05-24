$(document).ready(function() {
   	
   	var countrySelected = '';
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
        
        countrySelected = $(this).val();

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
					agregatorsArray = obj[0].agregators.split(",").map(function(item) {
					    return item.toString();
					});
				
				if(obj[0].sources != null) 
					sourcesArray = obj[0].sources.split(",").map(function(item) {
					    return item.toString();
					});
			},
			error: function(response) {
				alert("error");
			},
			cache: false,
			contentType: false,
			processData: false
		});
		
		$(document).one("ajaxStop", function() {

			$('.searchMulti:eq(1)').multiSelect('deselect_all');
			$('.searchMulti:eq(0)').multiSelect('deselect_all');
			
			if(agregatorsArray != null) {
				$('#custom-headers').multiSelect('select', agregatorsArray);
			}
			
			if(sourcesArray != null) {
				$('#keep-order2').multiSelect('select', sourcesArray);
			}

			$(".selectAll").remove();
			$(".selectNone").remove();

			$( "<img src='http://mobisteinreport.com/img/arrow_right.png' style='cursor: pointer; margin-top: 118px;    margin-left: 18px;' class='selectAll arrows'>" ).insertAfter( ".ms-selectable" )
			$( "<img src='http://mobisteinreport.com/img/arrow_left.png' style='cursor: pointer;margin-left: -30px; margin-top: 36px;' class='selectNone arrows'>" ).insertAfter( ".selectAll" )
			
			$(".editable").css('margin-top', '-138px');
			$(".secondStep").show();
		});
	});

	$('body').on('click', ".selectAll", function() {
		console.log()

		if($(this).parent().attr('id') == 'ms-custom-headers'){
			//select all agregators
			$('.searchMulti:eq(0)').multiSelect('select_all');
		} else if($(this).parent().attr('id') == 'ms-keep-order2') {
			//select all sources
			$('.searchMulti:eq(1)').multiSelect('select_all');
		}	

	});

	$('body').on('click', ".selectNone", function() {
		if($(this).parent().attr('id') == 'ms-custom-headers'){
			//deselect all agregators
			$('.searchMulti:eq(0)').multiSelect('deselect_all');
		} else if($(this).parent().attr('id') == 'ms-keep-order2') {
			//deselect all sources
			$('.searchMulti:eq(1)').multiSelect('deselect_all');
		}
	});

	var selectedsItemsAggs = [];
	$('body').on('click', ".aggsAply", function() {
		var Numberall = $("#ms-custom-headers .ms-selectable .ms-list li").length

		var Numberselected = 0;
		var selectedsItemsAggs = [];
		$( "#ms-custom-headers .ms-selection .ms-list li" ).each(function() {
			if($(this).hasClass('ms-selected')) {
				Numberselected++;
				selectedsItemsAggs.push($(this).data('ms-value'));
			}
		});

		if(Numberall == Numberselected) {
			selectedsItemsAggs = [];
			selectedsItemsAggs.push('ALL');
		} 

		setAggs(selectedsItemsAggs);

	});

	$('body').on('click', ".sourcesAply", function() {
		var Numberall = $("#ms-keep-order2 .ms-selectable .ms-list li").length

		var Numberselected = 0;
		var selectedsItemsSources = [];
		$( "#ms-keep-order2 .ms-selection .ms-list li" ).each(function() {
			if($(this).hasClass('ms-selected')) {
				Numberselected++;
				selectedsItemsSources.push($(this).data('ms-value'));
			}
		});

		if(Numberall == Numberselected) {
			selectedsItemsSources = []
			selectedsItemsSources.push('ALL');
		} 

		setSrcs(selectedsItemsSources);

	});

	function setSrcs(srcs) {
		formData = new FormData();
		formData.append('u', user)
		formData.append('c', countrySelected)
		formData.append('s', srcs)

		$.ajax({
			url: './permission/updateSources',
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

	function setAggs(aggs) {
		formData = new FormData();
		formData.append('u', user)
		formData.append('c', countrySelected)
		formData.append('a', aggs)

		$.ajax({
			url: './permission/updateAgregators',
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
});

user = null;
function fireUserChange(element) {
	if(element != null) {
		
		$("#countriesSS").empty();
		$('.searchMulti:eq(1)').multiSelect('deselect_all');
		$('.searchMulti:eq(0)').multiSelect('deselect_all');
		$(".secondStep").hide();
		$(".editable").css('margin-top', '0px');

		$(".CaptionCont").css('width', '100%');

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

