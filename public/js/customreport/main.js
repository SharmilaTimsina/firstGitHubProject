$(document).ready(function() {

	var array_disabled_ids = [];
	$("#drop").droppable({ accept: ".draggable, .draggable2", 
		drop: function(event, ui) {
			$(this).removeClass("border").removeClass("over");     

			var dropped = ui.draggable;
			var droppedOn = $(this);
			if($(dropped).hasClass('selectable') || $(dropped).hasClass('selectable2')){
				$.each( $(dropped).attr('disa').split(','), function(i, element) {
					if($.inArray($(this), array_disabled_ids) === -1) {
						array_disabled_ids.push(element);
					}
				});
				
				var idexists = $(dropped).attr('id');
				if ( $(this).children("#" + idexists).size() <= 0 ) {
					var useClone = $(dropped).clone()
					if($(dropped).hasClass('selectable')) {
						$(this).append(useClone.removeClass('selectable').addClass('used'));
					} else {
						$(this).append(useClone.removeClass('selectable').addClass('used2'));
					}
				}
			}

			disableElements(array_disabled_ids);

		}, over: function(event, elem) {
			$(this).addClass("over");
		}, out: function(event, elem) {
			$(this).removeClass("over");
		}
	});

	$("#products, #products2").droppable({ accept: ".draggable, .draggable2", 
		drop: function(event, ui) {
			$(this).removeClass("border").removeClass("over");

			var dropped = ui.draggable;
			var droppedOn = $(this);

			if (($(dropped).hasClass('used') || $(dropped).hasClass('used2')) && ($(dropped).attr('typeinput') != 'date')) {
				$(dropped).detach(); 
				return;
			} else {
				return;
			}

			var disabled = $(dropped).attr('disa').split(',');		
			
			$.each( disabled, function(i, element) {
				var removeItem = element;
				array_disabled_ids.splice( $.inArray(removeItem,array_disabled_ids) ,1 );
			});

			$.each( $('#products'), function(i, element) {
			   $('div', element).each(function() {
			   		if($.inArray($(this).attr('id'), array_disabled_ids) === -1) {
			   			$(this).attr('disabled', false);
			   			$(this).removeClass('transparent');
			   		}
			   });
			});

			$.each( $('#products2'), function(i, element) {
			   $('div', element).each(function() {
			   		if($.inArray($(this).attr('id'), array_disabled_ids) === -1) {
			   			$(this).attr('disabled', false);
			   			$(this).removeClass('transparent');
			   		}
			   });
			});
		}
	});
	
	$("#drop2").droppable({ accept: ".draggable", 
		drop: function(event, ui) {
			$(this).removeClass("border").removeClass("over");     

			var dropped = ui.draggable;
			var droppedOn = $(this);
			if($(dropped).hasClass('selectable') && $(dropped).attr('typeinput') != 'time' && $(dropped).attr('typeinput') != 'date') {
				$.each( $(dropped).attr('disa').split(','), function(i, element) {
					if($.inArray($(this), array_disabled_ids) === -1) {
						array_disabled_ids.push(element);
					}
				});
				
				var idexists = $(dropped).attr('id');
				if ( $(this).children("#" + idexists).size() <= 0 ) {
					var useClone = $(dropped).clone()
					
					if($(dropped).attr('combo') == 0) {
						$(this).append(useClone.removeClass('selectable').addClass('used').css('width', '368px').append('<div><input name="' + idexists + '" type="' + $(dropped).attr('typeinput') + '" style="color: black;width: 94%;margin-top: 5px;"></div>'));
					} else if($(dropped).attr('combo') == 1) {
						var name = "name='" + idexists + "'";
						$(this).append(useClone.removeClass('selectable').addClass('used').css('width', '368px').append('<div><select id="' + idexists + 'SB" name="' + idexists + '" multiple="multiple" class="search-box-sel-all" style="margin-left: 8px;width: 330px;">' + populateSB(idexists) + '</select></div><script>$("select[' + name + ']").SumoSelect({ csvDispCount: 3, selectAll:true, search: true, searchText:"Enter here.", okCancelInMulti:false });</script>'));
					}
				}

				disableElements(array_disabled_ids);
				$(".draggable").draggable({ cursor: "crosshair",  helper: 'clone', revert: "invalid"});
			}
		}, over: function(event, elem) {
			$(this).addClass("over");
		}, out: function(event, elem) {
			$(this).removeClass("over");
		}
	});

	function populateSB(id) {
		var json = $.parseJSON(jsonContentSelectBox);

		content = json[id];
		
		var options = '';
		for (key in content) {
		  	options += '<option value="' + key + '">' + content[key] + '</option>';
		}

		return options;
		
	}

	$.ajax({
		url: '/customreport/getAttributes',
		type: 'GET',
		async: true,
		success: function(data) {
			var json = $.parseJSON(data);

			var atts = json['attributes'];
			var msrs = json['measures'];

			for (var i = 0; i < atts.length; i++) {

				var object = json['attributes'][i];
				var attid = object['attid'];
				var attname = object['attname'];
				var att_type = object['att_type'];
				var attgroup = object['attgroup'];
				var dimensiontableid = object['dimensiontableid'];
				var dimensiontable = object['dimensiontable'];
				var columntype = object['columntype'];
				var combo = object['combo'];
				var disabledids = object['disabledids'];

				var color = '';
				switch (parseInt(attgroup)) {
				    case 1:
				        color = "#001c73";
				        break;
				    case 2:
				        color = "#062680";
				        break;
				    case 3:
				        color = "#0e2d8c";
				        break;
				    case 4:
				        color = "#173799";
				        break;
				    case 5:
				        color = "#2141a6";
				        break;
				    case 6:
				        color = "#2d4eb3";
				        break;
				    case 7:
				        color = "#3254bf";
				        break;
				    case 8:
				        color = "#3d61cc";
				        break;
				    case 9:
				        color = "#5176e6";
				        break;
				    case 10:
				        color = "#6185f2";
				        break;
				}

				disa = '';
				if(disabledids != null){
					disa = disabledids;
				}
			
				if(columntype == 'date') {
					$("#drop").append("<div typeinput='" + columntype + "' disa='" + disa + "' style='background-color:" + color + "' combo='" + combo + "' id='" + attid + "' class='used draggable btn btn-success'>" + attname + "</div>");
					$("#products").append("<div typeinput='" + columntype + "' disa='" + disa + "' style='background-color:" + color + "' combo='" + combo + "' id='" + attid + "' class='selectable draggable btn btn-success'>" + attname + "</div>");
				} else {
					$("#products").append("<div typeinput='" + columntype + "' disa='" + disa + "' style='background-color:" + color + "' combo='" + combo + "' id='" + attid + "' class='selectable draggable btn btn-success'>" + attname + "</div>");
				}
			};
			
			for (var i = 0; i < msrs.length; i++) {
				var object = json['measures'][i];
				var attid = object['attid'];
				var attname = object['attname'];
				var att_type = object['att_type'];
				var attgroup = object['attgroup'];
				var dimensiontableid = object['dimensiontableid'];
				var dimensiontable = object['dimensiontable'];
				var columntype = object['columntype'];
				var combo = object['combo'];

				var color = '';
				switch (parseInt(attgroup)) {
				  	case 1:
				        color = "#001c73";
				        break;
				    case 2:
				        color = "#062680";
				        break;
				    case 3:
				        color = "#0e2d8c";
				        break;
				    case 4:
				        color = "#173799";
				        break;
				    case 5:
				        color = "#2141a6";
				        break;
				    case 6:
				        color = "#2d4eb3";
				        break;
				    case 7:
				        color = "#3254bf";
				        break;
				    case 8:
				        color = "#3d61cc";
				        break;
				    case 9:
				        color = "#5176e6";
				        break;
				    case 10:
				        color = "#6185f2";
				        break;
				}

				disa = '';
				if(disabledids != null){
					disa = disabledids;
				}

				$("#products2").append("<div disa='" + disa + "' style='background-color:" + color + "' combo='" + combo + "' id='" + attid + "' class='selectable2 draggable2 btn btn-success'>" + attname + "</div>");
			};

			$(".draggable").draggable({ cursor: "crosshair",  helper: 'clone', revert: "invalid"});
			$(".draggable2").draggable({ cursor: "crosshair",  helper: 'clone', revert: "invalid"});
		},
		error: function(response) {
			alert("error");
		},
		cache: false,
		contentType: false,
		processData: false
	});

	$("#drop").sortable();
	$( "#drop" ).disableSelection();

	$(document).one("ajaxStop", function() {
		checkParam();
    });

	function checkParam() {
    	var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1);
   		var splitHashes = hashes.split('&');

   		hash = splitHashes[0].split('=');

   		if(hash[1]) {
   			editcustom = true;

   			$.ajax({
				url: '/customreport/getAttributes?reportid=' + hash[1],
				type: 'GET',
				async: true,
				success: function(data) {
					
					if(data != 0) {
						json = $.parseJSON(data);

						fillCustomReport(json);
					} else {
						alert('Report not found');
						window.location.replace("http://mobisteinreport.com/customreport");
					}
					
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

	function fillCustomReport(json) {
		var report = json['report'];

		fillSelections(report['selects']);
		fillFilters(report['filters']);
		disableElements(report['disabledids']);
	}

	function fillFilters(attr) {
		for (var key in attr) {
	
			var idexists = key;	
			var elements = attr[key];

			if($("#" + idexists).attr('combo') == 0) {
				var useClone = $("#" + idexists).clone();
				//$("#" + idexists).removeClass('selectable').addClass('used');
				$("#drop2").append(useClone.removeClass('selectable').addClass('used').css('width', '368px').append('<div><input value="' + elements + '" name="' + idexists + '" type="' + $("#" + idexists).attr('typeinput') + '" style="color: black;width: 94%;margin-top: 5px;"></div>'));
			} else if($("#" + idexists).attr('combo') == 1) {
				var selecteds = elements;
				var name = "name='" + idexists + "'";
				
				var useClone = $("#" + idexists).clone();
				//$("#" + idexists).removeClass('selectable').addClass('used');

				$("#drop2").append(useClone.removeClass('selectable').addClass('used').css('width', '368px').append('<div><select id="' + idexists + 'SB" name="' + idexists + '" multiple="multiple" class="search-box-sel-all" style="margin-left: 8px;width: 330px;">' + populateSBEdit(idexists, selecteds) + '</select></div><script>$("select[' + name + ']").SumoSelect({ csvDispCount: 3, selectAll:true, search: true, searchText:"Enter here.", okCancelInMulti:false });</script>'));
			}
		}

		$(".draggable").draggable({ cursor: "crosshair",  helper: 'clone', revert: "invalid"});
		
	}

	function fillSelections(attr) {
		var attr = attr.split(',');
		for (var i = 0; i < attr.length; i++) {
			var idexists = attr[i];

			if ( $("#drop").children("#" + idexists).size() <= 0 ) {
				var useClone = $("#" + idexists).clone();
				if($(useClone).hasClass('selectable')) {
					$("#drop").append(useClone.removeClass('selectable').addClass('used'));
				} else {
					$("#drop").append(useClone.removeClass('selectable').addClass('used2'));
				}
			}
		}
	}

	function disableElements(array_disabled_ids) {
		$.each( $('#products, #products2'), function(i, element) {
		   $('div', element).each(function() {
		   		if($.inArray($(this).attr('id'), array_disabled_ids) !== -1) {
		   			$(this).attr('disabled', true);
		   			$(this).addClass('transparent');
		   		}
		   });
		});
	}
});

var editcustom = false;
var json = '';

function configModal() {
	
	var types = [];
	$.each( $('#drop'), function(i, element) {
	    $('div', element).each(function() {
	   		if($(this).attr('typeinput') == 'date' || $(this).attr('typeinput') == 'time') {
	   			types.push('1');
	   		}
	    });
	});

	$('#modalbodyReport2').empty();
	
	if(!editcustom) {
		if(types.length == 2) {
			var name = "name='100'";
			$('#modalbodyReport2').append('<div><p class="labelsDates">Date range</p><select id="100SB" name="100" class="search-box-sel-all">' + populateSB(100) + '</select></div><script>$("select[' + name + ']").SumoSelect({ csvDispCount: 3, search: true, searchText:"Enter here.", okCancelInMulti:false });</script>');
			var name2 = "name='111I'";
			var name3 = "name='111E'";
			$('#modalbodyReport2').append('<div><p class="labelsDates">Time range begin</p><select id="111ISB" name="111I" class="search-box-sel-all">' + populateSB(111) + '</select></div><script>$("select[' + name2 + ']").SumoSelect({ csvDispCount: 3, search: true, searchText:"Enter here.", okCancelInMulti:false });</script>');
			$('#modalbodyReport2').append('<div><p class="labelsDates">Time range end</p><select id="111ESB" name="111E" class="search-box-sel-all">' + populateSB(1112) + '</select></div><script>$("select[' + name3 + ']").SumoSelect({ csvDispCount: 3, search: true, searchText:"Enter here.", okCancelInMulti:false });</script>');
		} else {
			var name = "name='100'";
			$('#modalbodyReport2').append('<div><p class="labelsDates">Date range</p><select name="100" id="100SB" class="search-box-sel-all">' + populateSB(100) + '</select></div><script>$("select[' + name + ']").SumoSelect({ csvDispCount: 3, search: true, searchText:"Enter here.", okCancelInMulti:false });</script>');
		}

		$('#modalbodyReport2').append('<div><button id="buttonSaveReportModal" type="button" name="submit" class="btn btn-danger buttonsInsert" onclick="saveReportPost();">SAVE</button></div>');
	} else {
		if(types.length == 2) {
			var name = "name='100'";
			$('#modalbodyReport2').append('<div><p class="labelsDates">Date range</p><select id="100SB" name="100" class="search-box-sel-all">' + populateSBEdit(100, json['report']['date']) + '</select></div><script>$("select[' + name + ']").SumoSelect({ csvDispCount: 3, search: true, searchText:"Enter here.", okCancelInMulti:false });</script>');
			var name2 = "name='111I'";
			var name3 = "name='111E'";
			$('#modalbodyReport2').append('<div><p class="labelsDates">Time range begin</p><select id="111ISB" name="111I" class="search-box-sel-all">' + populateSBEdit(111, json['report']['hourB']) + '</select></div><script>$("select[' + name2 + ']").SumoSelect({ csvDispCount: 3, search: true, searchText:"Enter here.", okCancelInMulti:false });</script>');
			$('#modalbodyReport2').append('<div><p class="labelsDates">Time range end</p><select id="111ESB" name="111E" class="search-box-sel-all">' + populateSBEdit(1112, json['report']['hourE']) + '</select></div><script>$("select[' + name3 + ']").SumoSelect({ csvDispCount: 3, search: true, searchText:"Enter here.", okCancelInMulti:false });</script>');
		} else {
			var name = "name='100'";
			$('#modalbodyReport2').append('<div><p class="labelsDates">Date range</p><select name="100" id="100SB" class="search-box-sel-all">' + populateSBEdit(100, json['report']['date']) + '</select></div><script>$("select[' + name + ']").SumoSelect({ csvDispCount: 3, search: true, searchText:"Enter here.", okCancelInMulti:false });</script>');
		}

		$("#reportName").val(json['report']['name']);
		$("#reportDescription").val(json['report']['description']);
		$("input[name=orderby][value='" + json['report']['orderby'] + "']").prop("checked",true);

		$('#modalbodyReport2').append('<div><button id="buttonSaveReportModal" type="button" name="submit" class="btn btn-danger buttonsInsert" onclick="saveReportPost();">EDIT</button></div>');

	}
}

function populateSB(id) {
	var json2 = $.parseJSON(jsonContentSelectBox);

	content = json2[id];
	
	var options = '';
	for (key in content) {
	  	options += '<option value="' + key + '">' + content[key] + '</option>';
	}

	return options;
	
}

function populateSBEdit(id, selecteds) {
	var json = $.parseJSON(jsonContentSelectBox);

	content = json[id];

	var options = '';
	for (key in content) {
	  	if(jQuery.inArray(key, selecteds) !== -1) {
	  		options += '<option selected value="' + key + '">' + content[key] + '</option>';
	  	} else {
			options += '<option value="' + key + '">' + content[key] + '</option>';
	  	}
	}

	return options;
	
}

function saveReportPost() {
	
	var formData = new FormData();
	
	var array_filters = {};
	//FILTERS
	$.each( $('#drop2'), function(i, element) {
	    $('div', element).each(function() {
	   		if($(this).attr('combo') == '1') {
	   			array_filters[$(this).attr('id')] = {values: (getSumoSelects("#" + $(this).attr('id') + "SB", 3)).join()};
	   		} else if($(this).attr('combo') == '0'){
	   			array_filters[$(this).attr('id')] = {values: $(this).find('input[name="' + $(this).attr('id') + '"]').val()};
	   		}
	    });
	});

	//SELECTION
	var date = false;
	var time = false;
	var arrayColumnsOrder = [];
	$.each( $('#drop'), function(i, element) {
	    $('div', element).each(function() {
	   		if($(this).attr('typeinput') == 'date') {
	   			date = true;
	   		} else if($(this).attr('typeinput') == 'time') {
	   			time = true;
	   		}
	   		
	   		arrayColumnsOrder.push($(this).attr('id'));
	   		
	    });
	});

	formData.append('columnsOrder', arrayColumnsOrder);

	//DATE & TIME
	if(date) {
		formData.append('date', (getSumoSelects("#100SB", 1)).join()); 
	}

	if(time) {
		formData.append('hourB', (getSumoSelects("#111ISB", 1)).join()); 
		formData.append('hourE', (getSumoSelects("#111ESB", 1)).join()); 
	}

	formData.append('filters', JSON.stringify(array_filters)); 
	formData.append('name', $("#reportName").val()); 
	formData.append('description', $("#reportDescription").val()); 
	formData.append('orderby', $('input[name="orderby"]:checked').val()); 

	if(editcustom)
		formData.append('reportid', json['report']['id']); 

	$.ajax({
		url: './savecustomreport',
		type: 'POST',
		data: formData,
		async: true,
		success: function(data) {

			if(data != 0) {
				alert('Call IT team');
			} else {
				alert("Complete");
				$("#closemodal").click();
				window.location.replace("http://mobisteinreport.com/customreport");
			}
			
		},
		error: function(response) {
			alert("error");
		},
		cache: false,
		contentType: false,
		processData: false
	});

}
