$(document).ready(function() {

	if($("#deleteScreenshotURL").attr("href").trim() == '') {
		$("#infoScreenshot").hide();
	}

	if($("#deleteBannersURL").attr("href").trim() == '') {
		$("#infoBanners").hide();
	}
	
	/*
	$(":file").filestyle({
		placeholder: "No files",
		buttonText: "Select a file"
	});
	*/

	fillSelects();

	function fillSelects() {

		$.ajax({
			url: './getDims',
			type: 'GET',
			async: true,
			success: function(data) {
				
				var json = $.parseJSON(data);

				var flow = json['flow'];
				var model = json['model'];
				var vertical = json['vertical'];
				var status = json['status'];
				var countries = json['countries'];
				var currencyType = json['curtype'];
				var area = json['area'];
				var aggs = json['aggs'];
				var accounts = json['accounts'];
				var cms = json['cms'];
				var ownership = json['ownership'];

				var selectBoxFlow = '<option>- - -</option>';
				for (var i = 0; i < flow.length; i++) {
					selectBoxFlow += '<option value="' + flow[i]['id'] + '">' + flow[i]['name'] + '</option>';
				}

				var selectBoxModel = '<option>- - -</option>';
				for (var i = 0; i < model.length; i++) {
					selectBoxModel += '<option value="' + model[i]['id'] + '">' + model[i]['name'] + '</option>';
				}

				var selectBoxvertical = '<option>- - -</option>';
				for (var i = 0; i < vertical.length; i++) {
					selectBoxvertical += '<option value="' + vertical[i]['id'] + '">' + vertical[i]['name'] + '</option>';
				}

				var selectBoxStatus = '<option>- - -</option>';
				for (var i = 0; i < status.length; i++) {
					selectBoxStatus += '<option value="' + status[i]['id'] + '">' + status[i]['name'] + '</option>';
				}

				var selectBoxCountries = '<option>- - -</option>';
				for (var i = 0; i < countries.length; i++) {
					selectBoxCountries += '<option value="' + countries[i]['id'] + '">' + countries[i]['name'] + '</option>';
				}

				var selectBoxCurrencyType = '<option>- - -</option>';
				for (var i = 0; i < currencyType.length; i++) {
					selectBoxCurrencyType += '<option value="' + currencyType[i]['currency'] + '">' + currencyType[i]['currency'] + '</option>';
				}

				var selectBoxArea = '<option>- - -</option>';
				for (var i = 0; i < area.length; i++) {
					selectBoxArea += '<option value="' + area[i]['id'] + '">' + area[i]['name'] + '</option>';
				}

				var selectBoxAggs = '<option>- - -</option>';
				for (var i = 0; i < aggs.length; i++) {
					selectBoxAggs += '<option value="' + aggs[i]['id'] + '">' + aggs[i]['id'] + ' - ' + aggs[i]['name'] + '</option>';
				}

				var selectBoxAccounts = '<option>- - -</option>';
				for (var i = 0; i < accounts.length; i++) {
					selectBoxAccounts += '<option value="' + accounts[i]['id'] + '">' + accounts[i]['name'] + '</option>';
				}

				var selectBoxCms = '<option>- - -</option>';
				for (var i = 0; i < cms.length; i++) {
					selectBoxCms += '<option value="' + cms[i]['id'] + '">' + cms[i]['name'] + '</option>';
				}

				var selectBoxOwnership = '<option>- - -</option>';
				for (var i = 0; i < ownership.length; i++) {
					selectBoxOwnership += '<option value="' + ownership[i]['id'] + '">' + ownership[i]['name'] + '</option>';
				}
				
				$("#flowSB").append(selectBoxFlow);
				$("#modelSB").append(selectBoxModel);
				$("#verticalSB").append(selectBoxvertical);
				$("#statusSB").append(selectBoxStatus);
				$("#countrySB").append(selectBoxCountries);
				$("#currencySB").append(selectBoxCurrencyType);
				$("#areaSB").append(selectBoxArea);
				$("#aggsSB").append(selectBoxAggs);
				$("#accountSB").append(selectBoxAccounts);
				$("#cmSB").append(selectBoxCms);
				$("#ownerSB").append(selectBoxOwnership);

				window.searchSelAll = $('.search-box-sel-all').SumoSelect({ csvDispCount: 3, selectAll:false, search: true, searchText:'Enter here.', okCancelInMulti:false });
				
			},	
			error: function(response) {
				alert("error");
			},
			cache: false,
			contentType: false,
			processData: false
		});

		$(document).one("ajaxStop", function() {
			checkParam();
	    });
	}

	$("body").on("change", "#areaSB", function() {
		if(getSumoSelects('#areaSB', 1) == 2) {
			$('select#cmSB')[0].sumo.selectItem('34');
		}
	});

	$("body").on("change", "#countrySB", function() {
   
    	$.ajax({
			url: './getCarriers?country=' + getSumoSelects('#countrySB', 1),
			type: 'GET',
			async: true,
			success: function(data) {
			
				var json = $.parseJSON(data);

				var carriers = json['carriers'];
				var cm = json['cms'];

				if (undefined !== carriers && carriers.length) {
					var selectBoxCarrier = '<option>- - -</option>';
					for (var i = 0; i < carriers.length; i++) {
						selectBoxCarrier += '<option value="' + carriers[i]['carrierTag'] + '">' + carriers[i]['carrierTag'] + '</option>';
					}

					$("#carrierSB").empty();

					$("#carrierSB").append(selectBoxCarrier);

					$("#carrierSB").attr('disabled', false);
					$('select#carrierSB')[0].sumo.reload();
				
				} else {
					
					$("#carrierSB").empty();
					$("#carrierSB").attr('disabled', true);
					$('select#carrierSB')[0].sumo.reload();

					alert('No carriers for selected Country!');
				}

				if (undefined !== cm && cm.length) {
					if(cm == 0) {
						$('select#cmSB')[0].sumo.selectItem('- - -');
					} else {
						$('select#cmSB')[0].sumo.selectItem(cm);
						$('select#cmSB')[0].sumo.selectItem(jsonVar.campaignmanager);
					}
				}
				
			},	
			error: function(response) {
				alert("error");
			},
			cache: false,
			contentType: false,
			processData: false
		});
	});

	$("body").on("change", "#aggsSB", function() {
   		
   		$("#infoaggregator").empty();

    	$.ajax({
			url: './getAccount?agg=' + getSumoSelects('#aggsSB', 1),
			type: 'GET',
			async: true,
			success: function(data) {
				
				var json = $.parseJSON(data);

				$('select#accountSB')[0].sumo.selectItem(json.account);
				$('select#accountSB')[0].sumo.selectItem("'" + jsonVar.accountmanager + "'");

				$("#infoaggregator").append(json.info);

				
			},	
			error: function(response) {
				alert("error");
			},
			cache: false,
			contentType: false,
			processData: false
		});
	});
	
	function formValid() {
		if($("#offname").val() == '') {
			alert('Offer name missing!');
			return false;
		}

		if($("#cpa").val() == '') {
			alert('CPA missing!');
			return false;
		}

		if(getSumoSelects("#countrySB", 5).length == 0) {
			alert('Country missing!');
			return false;
		}

		if(getSumoSelects("#carrierSB", 5).length == 0) {
			alert('Carrier missing!');
			return false;
		}

		if(getSumoSelects("#currencySB", 5).length == 0) {
			alert('Currency type missing!');
			return false;
		}

		if(getSumoSelects("#aggsSB", 5).length == 0) {
			alert('Aggregator missing!');
			return false;
		}

		if(getSumoSelects("#areaSB", 5).length == 0) {
			alert('Area missing!');
			return false;
		}

		return true;
	}

	$("body").on("click", "#buttonsaveOfferPack", function() {

   		if(formValid()) {

   			$("#buttonsaveOfferPack").attr('disabled', true);

   			var formData = new FormData();
   			formData.append('flow', getSumoSelects("#flowSB", 5));
   			formData.append('offername', $("#offname").val());
   			formData.append('model', getSumoSelects("#modelSB", 5));
   			formData.append('jumpurl', $("#jumpurl").val());
   			formData.append('cpa', $("#cpa").val());
			formData.append('vertical', getSumoSelects("#verticalSB", 5));
   			formData.append('status', getSumoSelects("#statusSB", 5));
   			formData.append('dailycap', $("#daylicap").val());
   			formData.append('country', getSumoSelects("#countrySB", 5));
   			formData.append('carrier', getSumoSelects("#carrierSB", 5));
   			formData.append('currencytype', getSumoSelects("#currencySB", 5));
   			formData.append('agregatorid', getSumoSelects("#aggsSB", 5));
   			formData.append('area', getSumoSelects("#areaSB", 5));
   			formData.append('accountmanager', getSumoSelects("#accountSB", 5));
   			formData.append('campaignmanager', getSumoSelects("#cmSB", 5));
   			formData.append('description', $("#description").val());
   			formData.append('ownership', getSumoSelects("#ownerSB", 5));
   			formData.append('clonehash', hashFromClone);

   			if(clone == true) {
   				formData.append('removescreenshot', removeScreenshot);
   				formData.append('removebanner', removeBanners);
   			}	
   			

   			//formData.append( 'screenshot', $( '#screenshot' )[0].files );
   			//formData.append( 'banners', $( '#bannersZip' )[0].files );

   			/*
			jQuery.each(jQuery('#screenshot')[0].files, function(i, file) {
			    formData.append('screenshot', file);
			});

			jQuery.each(jQuery('#bannersZip')[0].files, function(i, file) {
			    formData.append('banners', file);
			});*/
			
			jQuery.each(storedFiles, function(i, file) {
			    formData.append('screenshot[]', file);
			});

			jQuery.each(storedFiles2, function(i, file) {
			    formData.append('banners[]', file);
			});

   			
   			formData.append('exclusive', $("input[name=exclusive]:checked").val());

   			var checked = []
			$("input[name='regulations[]']:checked").each(function ()
			{
			    checked.push($(this).val());
			});
			formData.append('regulationsArr', checked);

			if(editoffer) {
				formData.append('offerhash', jsonVar.hash);
			}

   			$.ajax({
				url: './newOfferpack',
				type: 'POST',
				data: formData,
				async: true,
				success: function(data) {
					
					var e = 0;
					if(data == 999) {
						alert("Please provide a valid URL beginning with 'http://' or 'https://'");
						e = 1;
						$("#buttonsaveOfferPack").attr('disabled', false);
					}

					if(e != 1) {
						var res = data.split(":");
						if(res[0] == 0) {
							alert('Complete');

							$("#buttonsaveOfferPack").attr('disabled', false);

							window.location.replace('index?njump=' + res[1]);
					
						} else {
							alert('Error: ' + data );
						}
					}
				},
				error: function(response) {
					alert('error');
				},
				cache: false,
				contentType: false,
				processData: false
			});

   		}     	
	});
	
	var hashFromClone;
	var editoffer = false;
	function checkParam() {
 		var t = '';
 		if(jsonVar != '' && clone == false) { //edit
 			editoffer = true;
 			t = 0;
 		} else if(jsonVar != '' && clone == true) { //clone
 			hashFromClone = jsonVar.hash;
 			//$("#deleteBannersURL").remove();
 			editoffer = false;
 			t = 1;
 		} else { //new
 			editoffer = false;
 			t = 2;
 		}

 		if(clone == true) {
 			$("#deleteScreenshotURL").attr("href", '');
 			$("#deleteBannersURL").attr("href", '');
 			$("#deleteScreenshotURL").removeAttr("href");
 			$("#deleteBannersURL").removeAttr("href");
 		}

    	if(t == 1 || t == 0) {
    		
    		$('select#flowSB')[0].sumo.selectItem(jsonVar.flowid);
    		$('select#countrySB')[0].sumo.selectItem(jsonVar.country);
    		$('select#statusSB')[0].sumo.selectItem(jsonVar.status);
    		$('select#areaSB')[0].sumo.selectItem(jsonVar.area);
    		$('select#modelSB')[0].sumo.selectItem(jsonVar.modelid);
    		$('select#accountSB')[0].sumo.selectItem(jsonVar.accountmanager);
    		$('select#verticalSB')[0].sumo.selectItem(jsonVar.verticalid);
    		$('select#currencySB')[0].sumo.selectItem(jsonVar.curtype);
    		$('select#aggsSB')[0].sumo.selectItem(jsonVar.agregator);
    		$('select#cmSB')[0].sumo.selectItem(jsonVar.campaignmanager);
    		$('select#ownerSB')[0].sumo.selectItem(jsonVar.ownershipid);

    		var description;
			if(jsonVar.description == null) {
				description = '';
			} else {
				description = jsonVar.description;
			}
			$("#description").text(description);

    		$("#offname").val(jsonVar.offername);
    		$("#jumpurl").val(jsonVar.rurl);
    		$("#cpa").val(jsonVar.cpa);
    		$("#daylicap").val(jsonVar.dailycap);

    		/*
    		if(jsonVar.exclusive == 1)
    			$("#exclusiveCB").prop("checked", true);
    		*/

    		$("input[name=exclusive][value='" + jsonVar.exclusive + "']").prop("checked",true);

    		$(document).one("ajaxStop", function() {
				$('select#carrierSB')[0].sumo.selectItem(jsonVar.carrier);

				if(t == 0) {
					$('select#countrySB')[0].sumo.disable();
					//$('select#areaSB')[0].sumo.disable();
					//$('select#currencySB')[0].sumo.disable();
					$('select#aggsSB')[0].sumo.disable();
				}
	    	});

    		var reg = '';
    		if(jsonVar.regulations != null) {
    			reg = jsonVar.regulations.split(",");
    		}


    		$("input[name='regulations[]']").prop("checked", false)
    		$("input[name='regulations[]']").each(function ()
			{
			    if(reg.indexOf($(this).val()) != -1) {
			    	$(this).prop("checked", true)
				}
			});
			
    	} else {
    		$('select#modelSB')[0].sumo.selectItem(1);
    		$('select#statusSB')[0].sumo.selectItem(1);
    		$('select#currencySB')[0].sumo.selectItem('USD');
    		$('select#ownerSB')[0].sumo.selectItem('1');
    	}
	}

	

	$("body").on("click", "#deleteScreenshotURL", function() {
		removeScreenshot = 1;
		$("#infoScreenshot").hide();
	});

	$("body").on("click", "#deleteBannersURL", function() {
		removeBanners = 1;
		$("#infoBanners").hide();
	});

	$("body").on("click", ".filename", function() {
	 	storedFiles[$(this).attr('index')] = '';
		$(this).remove();
	});
});

var removeScreenshot = 0;
var removeBanners = 0;


var selDiv = "";
var storedFiles = []; //store the object of the all files

document.addEventListener("DOMContentLoaded", init, false); 

function init() {
   //To add the change listener on over file element
   document.querySelector('#screenshot').addEventListener('change', handleFileSelect, false);
   //allocate division where you want to print file name
   selDiv = document.querySelector("#filelist");

     var dropZoneId = "drop-zone";
  var buttonId = "clickHere";
  var mouseOverClass = "mouse-over";

  var dropZone = $("#" + dropZoneId);
  var ooleft = dropZone.offset().left;
  var ooright = dropZone.outerWidth() + ooleft;
  var ootop = dropZone.offset().top;
  var oobottom = dropZone.outerHeight() + ootop;
  var inputFile = dropZone.find("input");
  document.getElementById(dropZoneId).addEventListener("dragover", function (e) {
     
      /*
      e.preventDefault();
      e.stopPropagation();
      dropZone.addClass(mouseOverClass);
      var x = e.pageX;
      var y = e.pageY;

      if (!(x < ooleft || x > ooright || y < ootop || y > oobottom)) {
          inputFile.offset({ top: y - 15, left: x - 100 });
      } else {
          inputFile.offset({ top: -400, left: -400 });
      }
      */

  }, true);

  if (buttonId != "") {
      /*
      var clickZone = $("#" + buttonId);

      var oleft = clickZone.offset().left;
      var oright = clickZone.outerWidth() + oleft;
      var otop = clickZone.offset().top;
      var obottom = clickZone.outerHeight() + otop;
	

      $("#" + buttonId).mousemove(function (e) {
          var x = e.pageX;
          var y = e.pageY;
          if (!(x < oleft || x > oright || y < otop || y > obottom)) {
              inputFile.offset({ top: y - 15, left: x - 160 });
          } else {
              inputFile.offset({ top: -400, left: -400 });
          }
      });
      */
  }

  document.getElementById(dropZoneId).addEventListener("drop", function (e) {
      console.log("drop1")

      $("#" + dropZoneId).removeClass(mouseOverClass);

  }, true);
}

//function to handle the file select listenere
function handleFileSelect(e) {

   //to check that even single file is selected or not
   if(!e.target.files) return;      

   //for clear the value of the selDiv
   selDiv.innerHTML = "";

   //get the array of file object in files variable
   var files = e.target.files;
   var filesArr = Array.prototype.slice.call(files);

   selDiv.innerHTML += "<div class='info'>(click in the file to delete)</div>";
   //print if any file is selected previosly 
   for(var i=0;i<storedFiles.length;i++)
   {
   	   if(typeof storedFiles[i].name !== 'undefined' ) {
   	   		 selDiv.innerHTML += "<div index='" + i + "' class='filename'> <span> " + storedFiles[i].name + "</span></div>";
   	   }
      
   }
   filesArr.forEach(function(f) {
       //add new selected files into the array list
       var index = storedFiles.push(f) - 1;
       //print new selected files into the given division
       selDiv.innerHTML += "<div index='" + index + "' class='filename'> <span> " + f.name + "</span></div>";


   });

   $("#filelist").css('height', 'auto');
   if($("#filelist").height() !== $("#filelist2").height()) {
   		$(".filesLists").height(Math.max($("#filelist").height(), $("#filelist2").height()))
   } 
  	
  	removeElements()

   //store the array of file in our element this is send to other page by form submit
   //$("input[name=replyfiles]").val(storedFiles);
 }  















var selDiv2 = "";
var storedFiles2 = []; //store the object of the all files

document.addEventListener("DOMContentLoaded", init2, false); 

function init2() {
   //To add the change listener on over file element
   document.querySelector('#bannersZip').addEventListener('change', handleFileSelect2, false);
   //allocate division where you want to print file name
   selDiv2 = document.querySelector("#filelist2");

      var dropZoneId = "drop-zone2";
  var buttonId = "clickHere2";
  var mouseOverClass = "mouse-over";

  var dropZone = $("#" + dropZoneId);
  var ooleft = dropZone.offset().left;
  var ooright = dropZone.outerWidth() + ooleft;
  var ootop = dropZone.offset().top;
  var oobottom = dropZone.outerHeight() + ootop;
  var inputFile = dropZone.find("input");
  document.getElementById(dropZoneId).addEventListener("dragover", function (e) {
     
      /*
      e.preventDefault();
      e.stopPropagation();
      dropZone.addClass(mouseOverClass);
      var x = e.pageX;
      var y = e.pageY;

      if (!(x < ooleft || x > ooright || y < ootop || y > oobottom)) {
          inputFile.offset({ top: y - 15, left: x - 100 });
      } else {
          inputFile.offset({ top: -400, left: -400 });
      }
      */

  }, true);

  if (buttonId != "") {
      /*
      var clickZone = $("#" + buttonId);

      var oleft = clickZone.offset().left;
      var oright = clickZone.outerWidth() + oleft;
      var otop = clickZone.offset().top;
      var obottom = clickZone.outerHeight() + otop;
	

      $("#" + buttonId).mousemove(function (e) {
          var x = e.pageX;
          var y = e.pageY;
          if (!(x < oleft || x > oright || y < otop || y > obottom)) {
              inputFile.offset({ top: y - 15, left: x - 160 });
          } else {
              inputFile.offset({ top: -400, left: -400 });
          }
      });
      */
  }

  document.getElementById(dropZoneId).addEventListener("drop", function (e) {
   
      $("#" + dropZoneId).removeClass(mouseOverClass);

  }, true);
}

//function to handle the file select listenere
function handleFileSelect2(e) {
   //to check that even single file is selected or not
   if(!e.target.files) return;      

   //for clear the value of the selDiv
   selDiv2.innerHTML = "";

   //get the array of file object in files variable
   var files = e.target.files;
   var filesArr = Array.prototype.slice.call(files);

   selDiv2.innerHTML += "<div class='info'>(click in the file to delete)</div>";
   //print if any file is selected previosly 
   for(var i=0;i<storedFiles2.length;i++)
   {
   	   if(typeof storedFiles2[i].name !== 'undefined' ) {
   	   		 selDiv2.innerHTML += "<div index='" + i + "' class='filename'> <span> " + storedFiles2[i].name + "</span></div>";
   	   }
      
   }
   filesArr.forEach(function(f) {
       //add new selected files into the array list
       var index = storedFiles2.push(f) - 1;
       //print new selected files into the given division
       selDiv2.innerHTML += "<div index='" + index + "' class='filename'> <span> " + f.name + "</span></div>";

       
       
       
   });

   $(".filesLists").css('height', 'auto');
   if($("#filelist").height() !== $("#filelist2").height()) {
   		$(".filesLists").height(Math.max($("#filelist").height(), $("#filelist2").height()))
   } 
  
   removeElements();
   //store the array of file in our element this is send to other page by form submit
   //$("input[name=replyfiles2]").val(storedFiles2);
 }  


function removeElements() {
	if(storedFiles.length != 0) {
		$("#infoScreenshot").hide();
		removeScreenshot = 1;
	} 

	if(storedFiles2.length != 0) {
		$("#infoBanners").hide();
		removeBanners = 1;
	} 
}