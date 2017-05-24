var selectBoxCountry = '';
var selectBoxSources = '';

$(document).ready(function() {
	
	fillSelects();

	function fillSelects() {
		var countries = countriesvar;
		var sources = sourcesvar;
	
		for (var i = 0; i < countries.length; i++) {
			selectBoxCountry += '<option value="' + countries[i]['id'] + '">' + countries[i]['id'] + ' - ' + countries[i]['name'] + '</option>';
		}

		for (var i = 0; i < sources.length; i++) {
			selectBoxSources += '<option value="' + sources[i]['id'] + '">' + sources[i]['id'] + ' - ' + sources[i]['sourceName'] + '</option>';
		}

		$("#countrySideBar").append(selectBoxCountry);
		$("#sourcesSideBar").append(selectBoxSources);
		
	}

	$( ".selectsMultiPleSearch" ).change(function() {
		getNjumps();
	});

	function getNjumps() {
		
		$("#myUL").empty();
      	$("#spinner").show();
      	$("#myUL2").hide();
      	$("#style-1").hide();

		$.ajax({
			url: '/njump/getNjumps?mobile=1&searchquery=' + $("#myInput").val() + '&country=' + $("#countrySideBar").val() + '&source=' + $("#sourcesSideBar").val(),
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
	                });
	              }
	              else {
	              	 if($("#countrySideBar").val() == '' && $("#sourcesSideBar").val() == '') {
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

	$("body").on("click", "#resetFilters", function () { 
		$("#countrySideBar").val('');
		$("#sourcesSideBar").val('');
		$("#myInput").val('');

		$("#myUL").empty();
      	$("#spinner").show();
      	$("#myUL2").hide();
      	$("#style-1").hide();

		$.ajax({
			url: '/njump/mreset',
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
	                });
	              }
	              else {
	              	 if($("#countrySideBar").val() == '' && $("#sourcesSideBar").val() == '') {
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
});