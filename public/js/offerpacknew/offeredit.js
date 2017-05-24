$(document).ready(function() {

	fillSelects();

	function fillSelects() {

        $.ajax({
            url: './getDims',
            type: 'GET',
            async: true,
            success: function (data) {

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
                var exclusive = json['exclusive'];
                var ownership = json['ownership'];
                var regulations = json['regulations'];

                var selectBoxFlow = '<option value="0">- - -</option>';
                for (var i = 0; i < flow.length; i++) {
                    selectBoxFlow += '<option value="' + flow[i]['id'] + '">' + flow[i]['name'] + '</option>';
                }

                var selectBoxModel = '';
                for (var i = 0; i < model.length; i++) {
                    selectBoxModel += '<option value="' + model[i]['id'] + '">' + model[i]['name'] + '</option>';
                }

                var selectBoxvertical = '<option value="0">- - -</option>';
                for (var i = 0; i < vertical.length; i++) {
                    selectBoxvertical += '<option value="' + vertical[i]['id'] + '">' + vertical[i]['name'] + '</option>';
                }

                var selectBoxStatus = '';
                for (var i = 0; i < status.length; i++) {
                    selectBoxStatus += '<option value="' + status[i]['id'] + '">' + status[i]['name'] + '</option>';
                }

                var selectBoxCountries = '<option value="00">- - -</option>';
                for (var i = 0; i < countries.length; i++) {
                    selectBoxCountries += '<option value="' + countries[i]['id'] + '">' + countries[i]['name'] + '</option>';
                }

                var selectBoxCurrencyType = '';
                for (var i = 0; i < currencyType.length; i++) {
                    selectBoxCurrencyType += '<option value="' + currencyType[i]['currency'] + '">' + currencyType[i]['currency'] + '</option>';
                }

                var selectBoxArea = '<option value="00">- - -</option>';
                for (var i = 0; i < area.length; i++) {
                    selectBoxArea += '<option value="' + area[i]['id'] + '">' + area[i]['name'] + '</option>';
                }

                var selectBoxAggs = '<option value="00">- - -</option>';
                for (var i = 0; i < aggs.length; i++) {
                    selectBoxAggs += '<option value="' + aggs[i]['id'] + '">' + aggs[i]['id'] + ' - ' + aggs[i]['name'] + '</option>';
                }

                var selectBoxAccounts = '<option value="0">- - -</option>';
                for (var i = 0; i < accounts.length; i++) {
                    selectBoxAccounts += '<option value="' + accounts[i]['id'] + '">' + accounts[i]['name'] + '</option>';
                }

                var selectBoxCms = '<option value="0">- - -</option>';
                for (var i = 0; i < cms.length; i++) {
                    selectBoxCms += '<option value="' + cms[i]['id'] + '">' + cms[i]['name'] + '</option>';
                }

                var selectBoxExclusive = '';
                for (var i = 0; i < exclusive.length; i++) {
                    selectBoxExclusive += '<option value="' + exclusive[i]['id'] + '">' + exclusive[i]['name'] + '</option>';
                }

                var selectBoxOwnership = '';
                for (var i = 0; i < ownership.length; i++) {
                    selectBoxOwnership += '<option value="' + ownership[i]['id'] + '">' + ownership[i]['name'] + '</option>';
                }

                cmsGlobal = cms;
                currencyGlobal = currencyType;
                statusGlobal = status;

                $("#countrySB").append(selectBoxCountries);
                $("#aggsSB").append(selectBoxAggs);
                $("#areaSB").append(selectBoxArea);
                $("#verticalSB").append(selectBoxvertical);
                $("#modelSB").append(selectBoxModel);
                $("#statusSB").append(selectBoxStatus);
                $("#accountSB").append(selectBoxAccounts);
                $("#exclusiveSB").append(selectBoxExclusive);
                $("#ownerSB").append(selectBoxOwnership);
                $("#cmSB").append(selectBoxCms);
                $("#flowSB").append(selectBoxFlow);
                $("#currencySB").append(selectBoxCurrencyType);

                //regulations
                $(".tbodyregulations").empty();

                var k = 0;
				var td1 = '';
				var td2 = '';
				var td3 = '';
				var td4 = '';
				var td5 = '';

				for (i = 0; i < regulations.length; i++) { 
				
                    var td = '<td><input checked="" type="checkbox" name="regulations[]" value="' + regulations[i].id + '">' + regulations[i].name + '</td>';
					
					if(k == 0){
						td1 = td;
						k++;	
					} else if(k == 1) {
						td2 = td;
						k++;
					} else if(k == 2) {
						td3 = td;
						k++;
					} else if(k == 3) {
						td4 = td;
						k++;
					} else if(k == 4) {
						td5 = td;
						k++;
					} 

					if(td1 != '' && td2 != '' && td3 != '' && td4 != '' && td5 != '' ){
						var trWithTd = "<tr>" + td1 + td2 + td3 + td4 + td5 + "</tr>";
						$(trWithTd).appendTo(".tbodyregulations");
						td1 = '';
						td2 = '';
						td3 = '';
						td4 = '';
						td5 = '';

                        k = 0;
					} 
				}
				
				if(td1 != '' || td2 != '' || td3 != '' || td4 != '' || td5 != '' ){
					var trWithTd = "<tr>" + td1 + td2 + td3 + td4 + td5 + "</tr>";
					$(trWithTd).appendTo(".tbodyregulations");
				} 			
                
            },
            error: function (response) {
                alert("error");
            },
            cache: false,
            contentType: false,
            processData: false
        });

        $(document).one("ajaxStop", function() {

            $("#countrySB").chosen({
                allow_single_deselect: true,
                no_results_text: "Nothing found!",
                width: "100%",
                search_contains: true
            }).change(function(event){

                if(event.target == this){
                    getCarriers()
                }

            });

            $("#aggsSB").chosen({
                allow_single_deselect: true,
                no_results_text: "Nothing found!",
                width: "100%",
                search_contains: true
            }).change(function(event){

                if(event.target == this){
                    getAccount()
                }

            });

            $("#areaSB").chosen({
                allow_single_deselect: true,
                no_results_text: "Nothing found!",
                width: "100%",
                search_contains: true
            }).change(function(event){

                if(event.target == this){
                    if($('#areaSB').chosen().val() == 2) {
                    	$("#cmSB > [value='34']").attr("selected", "true"); 
                    	$("#cmSB").trigger("chosen:updated");
                    }
                }

            });

            $("#carrierSB").chosen({
                allow_single_deselect: true,
                no_results_text: "Nothing found!",
                width: "100%",
                search_contains: true
            });

            $(".selects-all").chosen({
                allow_single_deselect: true,
                no_results_text: "Nothing found!",
                width: "100%",
                search_contains: true
            });

            $('#carrierSB').attr('disabled', true).trigger("chosen:updated");

            checkParam();
        });
    }

    function getCarriers() {
        if ($('#countrySB').chosen().val() != null && $('#countrySB').chosen().val() != '00') {
            $.ajax({
                url: './getCarriers?country=' + $('#countrySB').chosen().val(),
                type: 'GET',
                async: true,
                success: function (data) {

                    var json = $.parseJSON(data);

                    var carriers = json['carriers'];
                    var cm = json['cms'];

                    if (undefined !== carriers && carriers.length) {
                        var selectBoxCarrier = '';
                        for (var i = 0; i < carriers.length; i++) {
                            selectBoxCarrier += '<option value="' + carriers[i]['carrierTag'] + '">' + carriers[i]['carrierTag'] + '</option>';
                        }

                        $("#carrierSB").empty();

                        $("#carrierSB").append(selectBoxCarrier);

                        $('#carrierSB').attr('disabled', false).trigger("chosen:updated");
                        
                        $("#carrierSB").val('');
                        $('#carrierSB').trigger("chosen:updated");

                    } else {

                        $("#carrierSB").empty();
                        $('#carrierSB').attr('disabled', true).trigger("chosen:updated");
                        $('#carrierSB').trigger("chosen:updated");

                        alert('No carriers for selected Country!');

                    }

                    if (undefined !== cm && cm.length) {
						if($('#areaSB').chosen().val() != '2') {
							if(cm == 0) {
								$("#cmSB > [value='0']").attr("selected", "true"); 
	                    		$("#cmSB").trigger("chosen:updated")
							} else {
								$("#cmSB > [value='" + cm + "']").attr("selected", "true"); 
	                    		$("#cmSB").trigger("chosen:updated")
							}
						}
					}


                },
                error: function (response) {
                    alert("error");
                },
                cache: false,
                contentType: false,
                processData: false
            });
        } else {
            $("#carrierSB").empty();
            $('#carrierSB').attr('disabled', true).trigger("chosen:updated");
            $('#carrierSB').trigger("chosen:updated");
        }
    }

    function getAccount() {
        if ($('#aggsSB').chosen().val() != null) {
            $.ajax({
                url: './getAccount?agg=' + $('#aggsSB').chosen().val(),
                type: 'GET',
                async: true,
                success: function (data) {

                    var json = $.parseJSON(data);

                    $(".parameter-div").empty().append(json.info);

                    $("#accountSB > [value='"+ json.account +"']").attr("selected", "true"); 
                    $("#accountSB").trigger("chosen:updated")

                },
                error: function (response) {
                    alert("error");
                },
                cache: false,
                contentType: false,
                processData: false
            });
        } else {
            $("#infoaggregator").empty();
            $('#accountSB').attr('disabled', true).trigger("chosen:updated");
            $('#accountSB').trigger("chosen:updated");
        }
    }

    //check parameters url
    var cloneOrEdit = 'new';
    var offerHash = '';
    function checkParam() {
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1);
        var splitHashes = hashes.split('&');

        for(var i = 0; i < splitHashes.length; i++) {
          hash = splitHashes[i].split('=');
          
          if(hash[1]) {
              if(hash[0] === "offerhash" ) {
                  cloneOrEdit = 'edit';
                  $("#typeedit").html('EDIT');
                  offerHash = hash[1]
              } else if(hash[0] === "clone" ) {
                  cloneOrEdit = 'clone';
                  $("#typeedit").html('CLONE');
                  offerHash = hash[1]
              }
          }
        }

       fillInformation();
    }

	function formValid() {
		if($("#offname").val() == '') {
			alert('Offer name missing!');
			return false;
		}

		if($("#cpa").val() == '') {
			alert('CPA missing!');
			return false;
		}

		if($('#countrySB').chosen().val() == null || $('#countrySB').chosen().val() == '00') {
			alert('Country missing!');
			return false;
		}

		if($('#carrierSB').chosen().val() == null || $('#carrierSB').chosen().val() == '00') {
			alert('Carrier missing!');
			return false;
		}

		if($('#currencySB').chosen().val() == null || $('#currencySB').chosen().val() == '00') {
			alert('Currency type missing!');
			return false;
		}

		if($('#aggsSB').chosen().val() == null || $('#aggsSB').chosen().val() == '00') {
			alert('Aggregator missing!');
			return false;
		}

		if($('#areaSB').chosen().val() == null || $('#areaSB').chosen().val() == '00') {
			alert('Area missing!');
			return false;
		}

		return true;
	}

	$("body").on("click", "#cpadownloadhistory", function() {  
		if(jsonVar.cpahistory != '') {
			cpaH = jsonVar.cpahistory.split(',');

			var data = 'Date,CPA\n';
			for(var i = 0; i < cpaH.length; i++) {
		        var info = cpaH[i].split('|');
		       	data += info[1] + ',' + info[0] + '\n'
	        }

	        blob = new Blob([data], { type: 'text/csv' })
            saveAs(blob, "cpa_history.csv")
		}
	});

	$("body").on("click", "#statusdownloadhistory", function() {
		if(jsonVar.statushistory != '') {
			statusH = jsonVar.statushistory.split(',');

			var data = 'Date,Status\n';
			for(var i = 0; i < statusH.length; i++) {
		        var info = statusH[i].split('|');
		       	data += info[1] + ',' + info[0] + ',' + info[2] +  '\n'
	        }

	        blob = new Blob([data], { type: 'text/csv' })
            saveAs(blob, "status_history.csv")
		}
	});

	$("body").on("click", ".buttonsaveOfferPack", function() {

   		if(formValid()) {

   			$(".buttonsaveOfferPack").attr('disabled', true);

   			var formData = new FormData();
   			formData.append('flow', $('#flowSB').chosen().val());
   			formData.append('offername', $("#offname").val());
   			formData.append('model', $('#modelSB').chosen().val());
   			formData.append('jumpurl', $("#jumpurl").val());
   			formData.append('cpa', $("#cpa").val());
			formData.append('vertical', $('#verticalSB').chosen().val());
   			formData.append('status', $('#statusSB').chosen().val());
   			formData.append('dailycap', $("#daylicap").val());
   			formData.append('country', $('#countrySB').chosen().val());
   			formData.append('carrier', $('#carrierSB').chosen().val());
   			formData.append('currencytype', $('#currencySB').chosen().val());
   			formData.append('agregatorid', $('#aggsSB').chosen().val());
   			formData.append('area', $('#areaSB').chosen().val());
   			formData.append('accountmanager', $('#accountSB').chosen().val());
   			formData.append('campaignmanager', $('#cmSB').chosen().val());
   			formData.append('description', $("#description").val());
   			formData.append('ownership', $('#ownerSB').chosen().val());
   			formData.append('exclusive', $('#exclusiveSB').chosen().val());

   			if(cloneOrEdit == 'new') {

   			} else if(cloneOrEdit == 'edit') {
   				formData.append('offerhash', offerHash);
   			} else if(cloneOrEdit == 'clone') {
   				formData.append('clonehash', offerHash);
   			}

   			var checked = []
			$("input[name='regulations[]']:checked").each(function ()
			{
			    checked.push($(this).val());
			});
			formData.append('regulationsArr', checked);


			//screenshots
			jQuery.each(storedScreenshots, function(i, file) {
			    formData.append('screenshot[]', file);
			});

			formData.append('screenshotids', getScreenshotsIds());

			//banners
			jQuery.each(storedBanners, function(i, file) {
			    formData.append('´banners[]', file);
			});

			formData.append('bannerids', getBannersIds());

   			$.ajax({
				url: './newOfferpack2',
				type: 'POST',
				data: formData,
				async: true,
				success: function(data) {
					
					var e = 0;
					if(data == 999) {
						alert("Please provide a valid URL beginning with 'http://' or 'https://'");
						e = 1;
						$(".buttonsaveOfferPack").attr('disabled', false);
					}

					if(e != 1) {
						var res = data.split(":");
						if(res[0] == 0) {
							alert('Complete');

							$(".buttonsaveOfferPack").attr('disabled', false);

							window.location.replace('index2?searchinput=' + res[1]);
					
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
	
	function fillInformation() {

		if(cloneOrEdit != 'new') {
			 $("#flowSB > [value='"+ jsonVar.flowid +"']").attr("selected", "true").trigger("chosen:updated");
			 $("#countrySB > [value='"+ jsonVar.country +"']").attr("selected", "true").trigger("chosen:updated");  
			 $("#statusSB > [value='"+ jsonVar.status +"']").attr("selected", "true").trigger("chosen:updated");  
			 $("#areaSB > [value='"+ jsonVar.area +"']").attr("selected", "true").trigger("chosen:updated");  
			 $("#modelSB > [value='"+ jsonVar.modelid +"']").attr("selected", "true").trigger("chosen:updated");  
			 $("#accountSB > [value='"+ jsonVar.accountmanager +"']").attr("selected", "true").trigger("chosen:updated");  
			 $("#verticalSB > [value='"+ jsonVar.verticalid +"']").attr("selected", "true").trigger("chosen:updated");
			 $("#currencySB > [value='"+ jsonVar.curtype +"']").attr("selected", "true").trigger("chosen:updated"); 
			 $("#cmSB > [value='"+ jsonVar.campaignmanager +"']").attr("selected", "true").trigger("chosen:updated");  
			 $("#ownerSB > [value='"+ jsonVar.ownershipid +"']").attr("selected", "true").trigger("chosen:updated"); 

             $("#aggsSB > [value='"+ jsonVar.agregator +"']").attr("selected", "true").trigger("chosen:updated");  
             getAccount()

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

			getCarriers()

			$(document).one("ajaxStop", function() {
				$("#carrierSB > [value='"+ jsonVar.carrier +"']").attr("selected", "true").trigger("chosen:updated");  

                if(cloneOrEdit == 'edit') {
                    $('#carrierSB').prop('disabled', true).trigger("chosen:updated");
                }
			});


			if(jsonVar.cpahistory != '') {
				cpaH = jsonVar.cpahistory.split(',');

				for(var i = 0; i < cpaH.length; i++) {
			        var info = cpaH[i].split('|');
			        var row = '<div class="row">'
						+ '<div class="col-md-7">' + info[1] + '</div>'
						+ '<div class="col-md-5">' + info[0] + ' ' + info[2] + '</div>'
						+ '</div>';

					$("#cpahistorydiv").append(row);
		        }
			}

			if(jsonVar.statushistory != '') {
				statusH = jsonVar.statushistory.split(',');

				for(var i = 0; i < statusH.length; i++) {
			        var info = statusH[i].split('|');
			        var row = '<div class="row">'
						+ '<div class="col-md-4">' + info[1] + '</div>'
						+ '<div class="col-md-8">' + info[0] + '</div>'
						+ '</div>';

					$("#statushistorydiv").append(row);
		        }
			}

			//screenshots
			var json = $.parseJSON(eachscreenshot);
			for(var i = 0; i < json.length; i++) {
		        var row = '<div class="filesInsert row" type="uploadFile" idFileArray="' + json[i].id + '" urlImage="' + json[i].link + '">'
					+ '<div class="col-md-1"><input name="allRows" class="checkScreenshot" type="checkbox"></div>'
					+ '<div class="col-md-1"><a target="_blank" href="' + json[i].link + '"><img width="20px" src="/img/njumppage/triye2.svg"></a></div>'
					+ '<div class="col-md-1"><img class="deleteScreenshot" width="20px" src="/img/njumppage/trash.svg"></div>'
					+ '<div class="col-md-1"><img class="imageDownloadAlone" imageName="' + json[i].filename + '" urlImage="' + json[i].link + '" width="20px" src="/img/njumppage/dload_circle.svg"></a></div>'
					+ '<div class="col-md-8"><span>' + json[i].filename + '</span></div>'
					+ '</div>';

				$("#listscreenshot").append(row);
	        }

	        //banners
			var json = $.parseJSON(eachbanner);
			for(var i = 0; i < json.length; i++) {
		        var row = '<div class="filesInsertbanners row" type="uploadFile" idFileArray="' + json[i].id + '" urlImage="' + json[i].link + '">'
					+ '<div class="col-md-1"><input name="allRowsBanners" class="checkBanner" type="checkbox"></div>'
					+ '<div class="col-md-1"><a target="_blank" href="' + json[i].link + '"><img width="20px" src="/img/njumppage/triye2.svg"></a></div>'
					+ '<div class="col-md-1"><img class="deleteBanner" width="20px" src="/img/njumppage/trash.svg"></div>'
					+ '<div class="col-md-1"><img class="imageDownloadAlone" imageName="' + json[i].filename + '" urlImage="' + json[i].link + '" width="20px" src="/img/njumppage/dload_circle.svg"></a></div>'
					+ '<div class="col-md-8"><span>' + json[i].filename + '</span></div>'
					+ '</div>';

				$("#listbanners").append(row);
	        }

            if(cloneOrEdit == 'edit') {
                $('#countrySB').prop('disabled', true).trigger("chosen:updated");
                $('#aggsSB').prop('disabled', true).trigger("chosen:updated");
                $('#carrierSB').prop('disabled', true).trigger("chosen:updated");
            }

		} else {
			$("#modelSB > [value='1']").attr("selected", "true").trigger("chosen:updated");  
			$("#statusSB > [value='1']").attr("selected", "true").trigger("chosen:updated");  
			$("#currencySB > [value='USD']").attr("selected", "true").trigger("chosen:updated");  
			$("#ownerSB > [value='1']").attr("selected", "true").trigger("chosen:updated");  
			$("#cpa").val('1.000');

            $("#copyurlscreenshots").hide();
            $("#downloadZipScreenshots").hide();
            $("#copyurlbanners").hide();
            $("#downloadZipBanners").hide();
		}
	}

	new Clipboard("#copy-curl", {
        text: function (trigger) {
            return $("#jumpurl").val();
        }
    });


	//download screenshot/banner alone
    $("body").on("click", ".imageDownloadAlone", function() {
    	var a = document.createElement('a');
		a.href = $(this).attr('urlImage');
		a.download = $(this).attr('imageName');
		document.body.appendChild(a);
		a.click();
		document.body.removeChild(a);
    });

	//screenshot
	function getScreenshotsIds() { 
		
		var ids = [];

		$(".filesInsert").each(function (index) {
        	if($(this).attr('type') == 'uploadFile') {
        		ids.push($(this).attr('idFileArray'));
        	}
        });

        return ids.join(',');
	}

	new Clipboard("#copyurlscreenshots", {
        text: function (trigger) {
            return getScreenshotsUrls();
        }
    });

    function getScreenshotsUrls() {
    	var urls = [];

    	$("input[name=allRows]:checked").each(function (index) {
        	urls.push($(this).closest('.filesInsert').attr('urlImage'));
        });

    	return urls.join('   ')
    }

	$("body").on("click", "#checkallscreenshots", function() {
		if ($(this).is(":checked")) {
            $(".checkScreenshot").prop('checked', true);
        } else {
            $(".checkScreenshot").prop('checked', false);
        }
	});

	$("body").on("click", ".checkScreenshot", function () {
        if (!$(this).is(":checked") && $("#checkallscreenshots").is(":checked")) {
            $("#checkallscreenshots").prop('checked', false);
        } else if ($('input[name=allRows]:checked').length === $('input[name=allRows]').length) {
            $("#checkallscreenshots").prop('checked', true);
        }
    });

	$("body").on("click", "#downloadZipScreenshots", function () {
        
        var ids = [];

        $("input[name=allRows]:checked").each(function (index) {
        	ids.push($(this).closest('.filesInsert').attr('idFileArray'));
        });

        if(ids != '') {
        	window.open(downloadScreenshots + '&ids=' + ids.join(','));
        }
    });


    $("body").on("click", ".deleteScreenshot", function() {

		if($(this).closest('.filesInsert').attr('type') == 'newFile') {
			
			removeFileFromArrayScreenshot($(this).closest('.filesInsert').attr('idFileArray'), this);
			
		} else {
			
			$(this).closest('.filesInsert').remove();
		}
		
	});

    function removeFileFromArrayScreenshot(idFile, element) {

    	for (var image in storedScreenshots) {
			if(image == idFile) {
				delete storedScreenshots[image]; 
				break;
			}
		}	

		$(element).closest('.filesInsert').remove();
	}

	//banner
	function getBannersIds() { 
		
		var ids = [];

		$(".filesInsertbanners").each(function (index) {
        	if($(this).attr('type') == 'uploadFile') {
        		ids.push($(this).attr('idFileArray'));
        	}
        });

        return ids.join(',');
	}

	new Clipboard("#copyurlbanners", {
        text: function (trigger) {
            return getBannersUrls();
        }
    });

    function getBannersUrls() {
    	var urls = [];

    	$("input[name=allRowsBanners]:checked").each(function (index) {
        	urls.push($(this).closest('.filesInsertbanners').attr('urlImage'));
        });

    	return urls.join('   ')
    }

	$("body").on("click", "#checkallbanners", function() {
		if ($(this).is(":checked")) {
            $(".checkBanner").prop('checked', true);
        } else {
            $(".checkBanner").prop('checked', false);
        }
	});

	$("body").on("click", ".checkBanner", function () {
        if (!$(this).is(":checked") && $("#checkallbanners").is(":checked")) {
            $("#checkallbanners").prop('checked', false);
        } else if ($('input[name=allRowsBanners]:checked').length === $('input[name=allRowsBanners]').length) {
            $("#checkallbanners").prop('checked', true);
        }
    });

	$("body").on("click", "#downloadZipBanners", function () {
        
        var ids = [];

        $("input[name=allRowsBanners]:checked").each(function (index) {
        	ids.push($(this).closest('.filesInsertbanners').attr('idFileArray'));
        });

        if(ids != '') {
        	window.open(downloadBanners + '&ids=' + ids.join(','));
        }
    });


    $("body").on("click", ".deleteBanner", function() {

		if($(this).closest('.filesInsertbanners').attr('type') == 'newFile') {
			
			removeFileFromArrayBanners($(this).closest('.filesInsertbanners').attr('idFileArray'), this);
			
		} else {
			
			$(this).closest('.filesInsertbanners').remove();
		}
		
	});

    function removeFileFromArrayBanners(idFile, element) {

    	for (var image in storedBanners) {
			if(image == idFile) {
				delete storedBanners[image]; 
				break;
			}
		}	

		$(element).closest('.filesInsertbanners').remove();
	}

});

//screenshots
var storedScreenshots = {};

document.addEventListener("DOMContentLoaded", initScreenshots, false); 

function initScreenshots() {
	document.querySelector('#screenshot').addEventListener('change', handleFileScreenshot, false);
	
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
		 
		if (buttonId != "") {
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
			})
		}

		document.getElementById(dropZoneId).addEventListener("drop", function (e) {
			$("#" + dropZoneId).removeClass(mouseOverClass);
		}, true);

	}, true);
}

var idIncr = 0;
function handleFileScreenshot(e) {

	if(!e.target.files) return;      

	var files = e.target.files;
	var filesArr = Array.prototype.slice.call(files);

	filesArr.forEach(function(f) {
		storedScreenshots[idIncr++] = f;
	});

	updateListScreenshots();
}  

function updateListScreenshots() {

   for (var image in storedScreenshots) {
		var row = '<div class="filesInsert row" type="newFile" idFileArray="' + image + '">'
				+ '<div class="col-md-1"></div>'
				+ '<div class="col-md-1"></div>'
				+ '<div class="col-md-1"><img class="deleteScreenshot" width="20px" src="/img/njumppage/trash.svg"></div>'
				+ '<div class="col-md-1"></div>'
				+ '<div class="col-md-8"><span>' + storedScreenshots[image].name + '</span></div>'
				+ '</div>';

		$("#listscreenshot").append(row);
	}	
}

//banners
var storedBanners = {};

document.addEventListener("DOMContentLoaded", initBanners, false); 

function initBanners() {
	document.querySelector('#banners').addEventListener('change', handleFileBanners, false);
	
	var dropZoneId = "drop-zone-banners";
	var buttonId = "clickHere2";
	var mouseOverClass = "mouse-over";

	var dropZone = $("#" + dropZoneId);
	var ooleft = dropZone.offset().left;
	var ooright = dropZone.outerWidth() + ooleft;
	var ootop = dropZone.offset().top;
	var oobottom = dropZone.outerHeight() + ootop;
	var inputFile = dropZone.find("input");
	
	document.getElementById(dropZoneId).addEventListener("dragover", function (e) {
		 
		if (buttonId != "") {
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
			})
		}

		document.getElementById(dropZoneId).addEventListener("drop", function (e) {
			$("#" + dropZoneId).removeClass(mouseOverClass);
		}, true);

	}, true);
}

var idIncr = 0;
function handleFileBanners(e) {

	if(!e.target.files) return;      

	var files = e.target.files;
	var filesArr = Array.prototype.slice.call(files);

	filesArr.forEach(function(f) {
		storedBanners[idIncr++] = f;
	});

	updateListBanners();
}  

function updateListBanners() {
    for (var image in storedBanners) {
		var row = '<div class="filesInsertbanners row" type="newFile" idFileArray="' + image + '">'
				+ '<div class="col-md-1"></div>'
				+ '<div class="col-md-1"></div>'
				+ '<div class="col-md-1"><img class="deleteBanner" width="20px" src="/img/njumppage/trash.svg"></div>'
				+ '<div class="col-md-1"></div>'
				+ '<div class="col-md-8"><span>' + storedBanners[image].name + '</span></div>'
				+ '</div>';

		$("#listbanners").append(row);
	}	
}

