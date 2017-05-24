var cmsGlobal = '';
var currencyGlobal = '';
var statusGlobal = '';

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

                var selectBoxFlow = '';
                for (var i = 0; i < flow.length; i++) {
                    selectBoxFlow += '<option value="' + flow[i]['id'] + '">' + flow[i]['name'] + '</option>';
                }

                var selectBoxModel = '';
                for (var i = 0; i < model.length; i++) {
                    selectBoxModel += '<option value="' + model[i]['id'] + '">' + model[i]['name'] + '</option>';
                }

                var selectBoxvertical = '';
                for (var i = 0; i < vertical.length; i++) {
                    selectBoxvertical += '<option value="' + vertical[i]['id'] + '">' + vertical[i]['name'] + '</option>';
                }

                var selectBoxStatus = '';
                for (var i = 0; i < status.length; i++) {
                    selectBoxStatus += '<option value="' + status[i]['id'] + '">' + status[i]['name'] + '</option>';
                }

                var selectBoxCountries = '';
                for (var i = 0; i < countries.length; i++) {
                    selectBoxCountries += '<option value="' + countries[i]['id'] + '">' + countries[i]['name'] + '</option>';
                }

                var selectBoxCurrencyType = '';
                for (var i = 0; i < currencyType.length; i++) {
                    selectBoxCurrencyType += '<option value="' + currencyType[i]['currency'] + '">' + currencyType[i]['currency'] + '</option>';
                }

                var selectBoxArea = '';
                for (var i = 0; i < area.length; i++) {
                    selectBoxArea += '<option value="' + area[i]['id'] + '">' + area[i]['name'] + '</option>';
                }

                var selectBoxAggs = '';
                for (var i = 0; i < aggs.length; i++) {
                    selectBoxAggs += '<option value="' + aggs[i]['id'] + '">' + aggs[i]['id'] + ' - ' + aggs[i]['name'] + '</option>';
                }

                var selectBoxAccounts = '';
                for (var i = 0; i < accounts.length; i++) {
                    selectBoxAccounts += '<option value="' + accounts[i]['id'] + '">' + accounts[i]['name'] + '</option>';
                }

                var selectBoxCms = '';
                for (var i = 0; i < cms.length; i++) {
                    selectBoxCms += '<option value="' + cms[i]['id'] + '">' + cms[i]['name'] + '</option>';
                }

                var selectBoxExclusive = '';
                for (var i = 0; i < exclusive.length; i++) {
                    selectBoxExclusive += '<option value="' + exclusive[i]['id'] + '">' + exclusive[i]['name'] + '</option>';
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
            },
            error: function (response) {
     
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

            $(".selects-all").chosen({
                allow_single_deselect: true,
                no_results_text: "Nothing found!",
                width: "100%",
                search_contains: true
            });

            $('#carrierSB').attr('disabled', true).trigger("chosen:updated");

            checkParam()
        });
    }

    function getCarriers() {
        if ($('#countrySB').chosen().val() != null) {
            $.ajax({
                url: './getCarriersMultiple?country=' + $('#countrySB').chosen().val(),
                type: 'GET',
                async: true,
                success: function (data) {

                    var json = $.parseJSON(data);

                    var carriers = json['carriers'];

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
                },
                error: function (response) {
                  
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

        $(document).one("ajaxStop", function() {
             fillSelectBox("carrierSB", parametersOffers['carrier']);
        });
    }

    $("body").on("click", "#buttonFilter", function () {
        setFilter();
    });

    $("body").on("click", "#buttonResetFilter", function() {
        $("#countrySB").val('').trigger('chosen:updated');
        $(".selects-all").val('').trigger('chosen:updated');
        $("#searchInput").val('');
    });

    //check parameters url
    function checkParam() {
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1);
        var splitHashes = hashes.split('&');

        var arrayParameters = {};
    
        for(var i = 0; i < splitHashes.length; i++) {
          hash = splitHashes[i].split('=');
          
          if (!window.location.search) {
          	//break;
          } else {
          	arrayParameters[hash[0]] = hash[1];
          }
             
          if(hash[1]) {

              if(hash[0] === "country" ) 
                  fillSelectBox("countrySB", hash[1])
              else if(hash[0] === "carrier" ) 
                  fillSelectBox("carrierSB", hash[1]);
              else if(hash[0] === "client" ) 
                  fillSelectBox("aggsSB", hash[1]);
              else if(hash[0] === "area" ) 
                  fillSelectBox("areaSB", hash[1]);
              else if(hash[0] === "vertical" ) 
                  fillSelectBox("verticalSB", hash[1]);
              else if(hash[0] === "model") 
                  fillSelectBox("modelSB", hash[1]);
              else if(hash[0] === "status" ) 
                  fillSelectBox("statusSB", hash[1]);
              else if(hash[0] === "account" ) 
                  fillSelectBox("accountSB", hash[1]);
              else if(hash[0] === "exclusive" ) 
                  fillSelectBox("exclusiveSB", hash[1]);
              else if(hash[0] === "searchinput") 
                  $("#searchInput").val(hash[1]);
          }
        }

        setArrayParameters(arrayParameters);
    }

    //parameters url for later use
    var parametersOffers = {};
    function setArrayParameters(arrayParameters) {

        if (Object.keys(arrayParameters).length > 0)
            parametersOffers = arrayParameters;

        getCarriers();

        $(document).one("ajaxStop", function() {
            setFilter();
            return;
        });

        setFilter();
        
    }

    //check if value in parameter exists 
    function existsInFilter(filterName, hashContent) {
        if($("#" + filterName + " option[value='" + hashContent + "']").length > 0)
            return true;
        else
            return false;
    }

    //fill selectbox with value if exists
    function fillSelectBox(filterName, hashContent) {

        if(hashContent !== null && typeof hashContent !== "undefined") {
            hashContent = decodeURIComponent(hashContent).split(",")

            $(hashContent).each(function( index ) {
                
                $("#" + filterName + " > [value='"+ hashContent[index] +"']").attr("selected", "true"); 
            });

            $("#" + filterName).trigger("chosen:updated")

        } else {
            return '';
        }
    }

    var formDataExcel;
    function setFilter() {
        
        $("#checkall").prop('checked', false);

        var formData = new FormData();

        formData.append('countries', $("#countrySB").chosen().val());
        formData.append('carriers', $("#carrierSB").chosen().val());
        formData.append('aggs', $("#aggsSB").chosen().val());
        formData.append('area', $("#areaSB").chosen().val());
        formData.append('vertical', $("#verticalSB").chosen().val());
        formData.append('model', $("#modelSB").chosen().val());
        formData.append('status', $("#statusSB").chosen().val());
        formData.append('account', $("#accountSB").chosen().val());
        formData.append('exclusive', $("#exclusiveSB").chosen().val());
        formData.append('searchInput', (typeof $("#searchInput").val() === "undefined") ? '' : $("#searchInput").val());

        parametersOffers['country'] = orEmpty($("#countrySB").chosen().val());
        parametersOffers['carrier'] = orEmpty($("#carrierSB").chosen().val());
        parametersOffers['client'] = orEmpty($("#aggsSB").chosen().val());
        parametersOffers['area'] = orEmpty($("#areaSB").chosen().val());
        parametersOffers['vertical'] = orEmpty($("#verticalSB").chosen().val());
        parametersOffers['model'] = orEmpty($("#modelSB").chosen().val());
        parametersOffers['status'] = orEmpty($("#statusSB").chosen().val());
        parametersOffers['exclusive'] = orEmpty($("#exclusiveSB").chosen().val());
        parametersOffers['searchinput'] = (typeof $("#searchInput").val() === "undefined") ? '' : $("#searchInput").val();

        formDataExcel = formData;

        if (Object.keys(parametersOffers).length > 0)
            history.pushState("", "", "index2?" + jQuery.param(parametersOffers));

        $.ajax({
            url: './setFilter2',
            type: 'POST',
            data: formData,
            async: true,
            success: function (data) {

                fillTable(data);

            },
            error: function (response) {
        
            },
            cache: false,
            contentType: false,
            processData: false
        });
    }

    function orEmpty(entity) {
        if(entity !== null && typeof entity !== "undefined") {
            return entity.join();
        } else {
            return '';
        }
    };


    function settooltip() {
        $('.infoToolTip').each(function () {
            $(this).qtip({
                content: {
                    text: function (event, api) {
                        $.ajax({
                            url: api.elements.target.attr('href') // Use href attribute as URL
                        })
                                .then(function (content) {
                                    // Set the tooltip content upon successful retrieval
                                    api.set('content.text', content);
                                }, function (xhr, status, error) {
                                    // Upon failure... set the tooltip content to error
                                    api.set('content.text', status + ': ' + error);
                                });

                        return 'Loading...'; // Set some initial text
                    }
                },
                position: {
                    viewport: $(window)
                },
                style: 'qtip-wiki'
            });
        });
    }

    function fillTable(data) {
        json = $.parseJSON(data);

        var rows = '';
        numRows = json.length;
        for (i = 0; i < json.length; i++) {

            var info = "'" + json[i].mask + "','" + json[i].campaign_name + "'";

            var cpaEditableAff = '';
            var statusIfAff = '';
            var affType = '';
            var iconsEdits = '';

            var carrier;
            if (json[i].carrier == null) {
                carrier = '';
            } else {
                carrier = json[i].carrier;
            }

            var infoClip = 'Country: ' + getValueElement("countrySB", json[i].country.toUpperCase()) 
            				+ '\nCarrier: ' + carrier
            				+ '\nCampaign: ' + json[i].campaign_name
            				+ '\nCPA: ' + json[i].cpa
            				+ '\nCurrency: ' + json[i].curtype;
            var desired = '';
            if(json[i].comments != undefined && json[i].comments != '') {
                desired = json[i].comments.replace(/[^\w\s]/gi, '')
            }
            if(desired != ''){
                desired = desired.replace(/^\s*\n/gm,'');
            }
            infoClip += '\nComments: ' + desired;
            if (json[i].imageurl != '') {
            	infoClip += '\nScreenshot URL: ' + json[i].imageurl;
            }	
            
            infoClip += '\nJump: ' + json[i].jumpname;
            
            if (json[i].area == 1) {
                cpaEditableAff = 'cpaEditable2';
                statusIfAff = getValueElement("statusSB", json[i].status);
                affType = 0;
                iconsEdits = '';

            } else {
                cpaEditableAff = 'cpaEditable';

                statusIfAff = '<select hashPack="' + json[i].mask + '" id="SB' + json[i].mask + '" class="form-control selects-all selectBoxTable">'
                        + '<option ' + ((json[i].status == 1) ? 'selected' : '') + ' value="1">Active</option>'
                        + '<option ' + ((json[i].status == 2) ? 'selected' : '') + ' value="2">Paused by Mobipium</option>'
                        + '<option ' + ((json[i].status == 3) ? 'selected' : '') + ' value="3">Paused by Client</option>'
                        + '<option ' + ((json[i].status == 4) ? 'selected' : '') + ' value="4">Disabled</option><select>';

                affType = 1;
                iconsEdits = '<a class="iconsDivObjectPopOver" data-toggle="popover" data-placement="bottom"  title="" data-content="edit" data-clipboard-text="" href="./offerpackedit2?offerhash=' + json[i].mask + '"><img class="icontable" src="/img/njumppage/edit.svg"></a>'
                        + '<img class="iconsDivObjectPopOver modalIcon44 icontable" data-placement="bottom"  title="" data-content="disabled" data-clipboard-text="" style="cursor: pointer;width:20px; margin:1px; margin-left: 5px;" onclick="disablePackOffer(' + info + ')" src="/img/njumppage/trash.svg">'
                        + '<a class="iconsDivObjectPopOver" data-placement="bottom"  title="" data-content="clone" data-clipboard-text="" href="./offerpackedit2?clone=' + json[i].mask + '"><img class="icontable" style="width:20px; margin:1px; margin-left: 5px;" src="/img/njumppage/clone.svg"></a>';

                if (json[i].banner == '') {

                } else {
                    iconsEdits += '<a target="_blank" data-placement="bottom"  title="" data-content="download banners" data-clipboard-text="" class="iconsDivObjectPopOver" href="' + json[i].banner + '"><img style="cursor: pointer; margin-left: 5px;" class="icontable modalIcon44" style="width:20px; margin:1px" src="/img/njumppage/banners.svg"></a>';
                }

                if (json[i].imageurl == '') {

                } else {
                    iconsEdits += '<div style=" margin-left: 5px;" bannerszip="' + json[i].banner + '" imageiurl="' + json[i].imageurl + '" screenshotzip="' + json[i].screenshotzip + '" data-content="view/download screenshot" data-clipboard-text="" class="iconsDivObjectPopOver openModalImage" data-placement="bottom"  title=""> <img style="cursor: pointer;" class="icontable modalIcon44" style="width:20px; margin:1px; margin-left: 5px;" src="/img/njumppage/triye2.svg"></div>';
                }

                iconsEdits += '<img id="copylineinfoid" infoClip="' + infoClip + '"  class="iconsDivObjectPopOver icontable copylineinfo" data-placement="bottom"  title="" data-content="copy line info" data-clipboard-text="" style="cursor: pointer;width:20px; margin:1px; margin-left: 5px;" src="/img/njumppage/clipboard.svg">'
            }

            var vertical;
            if (json[i].vertical == null) {
                vertical = '';
            } else {
                vertical = json[i].vertical;
            }


            var cpahistory = '';
            if(json[i].cpahistory != '') {
            	var elementsCpa = json[i].cpahistory.split(",");

            	for(var j = 0; j < elementsCpa.length; j++) {

            		var info = elementsCpa[j].split('|');
			        var row = info[1] + ' - ' + info[0] + ' ' + info[2];
						
		        	cpahistory += row + '<br />';
		        }
            	
            }

            var statusHistory = '';
            if(json[i].statushistory != '') {
            	var elementsStatus = json[i].statushistory.split(",");

            	for(var k = 0; k < elementsStatus.length; k++) {
		        	
		        	var info = elementsStatus[k].split('|');
			        var row = info[1] + ' - ' + info[0];

		        	statusHistory += row + '<br />';
		        }
            }

            var tds = '';
            tds += '<td><input type="checkbox" class="checkOption" name="allRows" value="' + json[i].mask + '"></td>'
                    + '<td class="td_datastyle">' + json[i].insertTimestamp + '</td>'
                    + '<td class="td_countrystyle">' + getValueElement("countrySB", json[i].country.toUpperCase()) + '</td>'
                    + '<td class="td_carrierstyle">' + carrier + '</td>'
                    + '<td class="td_advstyle">' + getValueElement("aggsSB", json[i].advertiser) + '</td>'
                    + '<td class="td_offnamestyle"><a class="infoToolTip" href="./getofferinfo?offerhash=' + json[i].mask + '"><span Cname="' + json[i].campaign_name + '" Curl="' + json[i].clienturl + '" id="campaignName" class="jumpnamecopyclicpcampaign">' + json[i].campaign_name + '</span></a></td>'
                    + '<td class="td_areastyle">' + getValueElement("areaSB", json[i].area) + '</td>'
                    + '<td class="td_verticalstyle">' + vertical + '</td>'
                    + '<td class="td_jumpstyle"><div style="width:200px; margin-top:2%"><span id="jumpnames" Jname="' + json[i].jumpname + '" class="jumpnamecopyclicp" data-clipboard-text="' + json[i].jumpname + '">' + json[i].jumpname + '</span><img data-placement="bottom"  data-content="copy njump url" data-clipboard-text="" class="iconCopylinkjump iconsDivObjectPopOver icontable jumpurlcopy" id="jumpurls" Jurl="' + json[i].jumpurl + '" data-clipboard-text="' + json[i].jumpurl + '" src="/img/njumppage/copy.svg"></div></td>'
                    + '<td class="td_modelstyle">' + getValueElement("modelSB", json[i].modelid) + '</td>'
                    + '<td class="td_paystyle"><div data-content="' + cpahistory + '" data-html="true" data-clipboard-text="" data-placement="bottom" title="CPA History" class="historyPopOver ' + cpaEditableAff + '">' + json[i].cpa + '</div></td>'
                    + '<td class="td_curstyle">' + json[i].curtype + '</td>'
                    + '<td class="td_capstyle">' + json[i].dailycap + '</td>'
                    + '<td class="td_amstyle">' + getValueElement("accountSB", json[i].accountmanager) + '</td>'
                    + '<td class="td_cmstyle">' + getValueElementGlobal(json[i].campaignmanager) + '</td>'
                    + '<td class="td_statstyle"><div data-content="' + statusHistory + '" data-html="true" data-clipboard-text="" data-placement="bottom" title="Status History" class="historyPopOver">' + statusIfAff + '</div></td>'
                    + '<td  class="td_optstyle"style="width: 158px;display: -webkit-inline-box;padding: 4px;margin-left: 5px;">'
                    + iconsEdits
                    + '</td>';

            rows += '<tr class="rowInfo" type="' + affType + '">' + tds + '</tr>';

        }


        if (typeof table != 'undefined' && table != null)
            table.destroy();

        $('#tableBodyPackOffers').empty();

        $("#tableBodyPackOffers").append(rows);

        
        table = $('.datatab').removeAttr('width').DataTable({
            "pageLength": 100,
            "columns": [{"width": "1%"}, {"width": "7%"}, null, null, null, null, null, null, null, null, null, null, null, null, null, null, {"width": "1%"}],
            'aoColumnDefs': [{
                'bSortable': false,
                'aTargets': [0, -1] /* 1st one, start by the right */
            }]
        });
        $('.datatab').css("width", "100%");
        table.columns.adjust().draw();
		
		
		/*
        table = $('.datatab').DataTable( {
				  "pageLength": 100,
				  "autoWidth": true,
				  "bSort": true
				});	
				
		table.columns.adjust().draw();
		*/

        settooltip();

        $('.iconsDivObjectPopOver').popover({ trigger: "hover" });
        $('.historyPopOver').popover({ trigger: "hover" });

    }
    
    table = $('.datatab').DataTable({
            "pageLength": 100,
            "columns": [{"width": "1%"}, {"width": "7%"}, null, null, null, null, null, null, null, null, null, null, null, null, null, null, {"width": "1%"}],
            'aoColumnDefs': [{
                'bSortable': false,
                'aTargets': [0, -1] /* 1st one, start by the right */
            }]
        })

    function getValueElementGlobal(key) {
        var toReturn = '';
        $(cmsGlobal).each(function () {
            if (this.id == key) {
                toReturn = this.name;
                return false;
            }
        });
        return toReturn;
    }

    function getValueElement(selecBox, key) {
        return $("#" + selecBox + " option[value='" + key + "']").text()
    }
    
    var textToCopy = '';
    var textToCopy2 = '';
    var textToCopy3 = '';
    new Clipboard(".jumpnamecopyclicpcampaign", {
        text: function (trigger) {
            return textToCopy;
        }
    });

    new Clipboard(".jumpnamecopyclicp", {
        text: function (trigger) {
            return textToCopy2;
        }
    });


    new Clipboard(".jumpurlcopy", {
        text: function (trigger) {
            return textToCopy3;
        }
    });


    new Clipboard(".copylineinfo", {
        text: function (trigger) {
            return getInfoLines(trigger);
        }
    });

    function getInfoLines(trigger) {

        $(trigger).closest('tr').find('.checkOption').prop('checked', true);

        var selectedOptions = $('input[name=allRows]:checked').length;

        var info = [];
        $(".checkOption:checked").each(function () {
            info.push($(this).closest('tr').find('#copylineinfoid').attr('infoClip'));
        });

        if(selectedOptions == 1) {
            $(trigger).closest('tr').find('.checkOption').prop('checked', false);
        }

        return info.join('\n\n');
    }

    var oldValueSBTable = '';
    $("body").on("click", ".selectBoxTable", function () {
        oldValueSBTable = $(this).val();
        $(this).closest("tr").find('.checkOption').prop('checked', true);
    });

    new Clipboard("#copytoscreenshotimage", {
        text: function (trigger) {
            return $("#copytoscreenshotimage").attr('copyto');
        }
    });

    $("body").on("click", ".openModalImage", function () {
        var urlImage = $(this).attr("imageiurl");
        var screenshotzip = $(this).attr("screenshotzip");
        var bannerzip = $(this).attr("bannerszip");

        $("#screenShotMo").attr("src", urlImage);
        $("#downloadZipScreenshot").attr("href", screenshotzip);
        $("#copytoscreenshotimage").attr("copyTo", urlImage);

        if(bannerzip != '') {
            $("#downloadZipBanner").show();
            $("#downloadZipBanner").attr("href", bannerzip);
        } else {
            $("#downloadZipBanner").hide();
        }

        $("#modalScreenshot").modal("show")
    });

     $("body").on("click", "#downloadwxcel", function (e) {
        e.preventDefault();

        formDataExcel.append('excel', '1');

        $.ajax({
            url: './setFilter2',
            type: 'POST',
            data: formDataExcel,
            async: true,
            success: function (data) {

                if(data != 0) {
                    blob = new Blob([data], { type: 'text/csv' })
                    saveAs(blob, "offers.csv")
                } else {
                    alert("No data available");
                }
                    
                
            },
            error: function (response) {
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });

    $("body").on("click", "#copytoscreenshotimage", function (e) {
       
        var element = $(this)

        var oldSrc = '/img/njumppage/copy.svg?'+Math.random();

        element.attr('src', '/img/njumppage/copied.svg?'+Math.random());
        
        setTimeout(function () {
            element.attr('src', oldSrc);
        }, 500);
    });

	$(document).delegate('.selectBoxTable', 'change', function () {
        var reason = '';
        var newValue = $(this).val();
        var selectedOptions = $('input[name=allRows]:checked').length;
        var idsChanged = [];
        if (selectedOptions >= 1) {
            if (confirm('This will change the status for ' + selectedOptions + ' pack offers. Continue?')) {

                if (newValue == 2 || newValue == 3) {
                    while (reason == null || reason == '') {
                        reason = window.prompt("Why are you pausing this?", "");
                    }
                }

                //if(selectedOptions > 1) {
                $(".checkOption:checked").each(function () {
                    if ($(this).closest('tr').attr('type') == 1) {
                        $(this).closest('tr').find('.selectBoxTable').val(newValue);
                        idsChanged.push($(this).val());
                    }
                });
                //} else {
                //$(this).closest("tr").find('.checkOption').attr('checked', false);
                //idsChanged.push($(this).closest('tr').find('.checkOption:checked').val());
                //}
            } else {
                $(this).val(oldValueSBTable);
                $('.checkOption').prop('checked', false);
            }

            updateCellsStatus(idsChanged, newValue, reason);
        }
    });

	function updateCellsStatus(idsChanged, newValue, reason) {
	        formData = new FormData();
	        formData.append('ids', idsChanged);
	        formData.append('valueStatus', newValue);
	        formData.append('reason', reason);

	        $.ajax({
	            url: './updateStatus',
	            type: 'POST',
	            data: formData,
	            async: true,
	            success: function (data) {

	                $('.checkOption').prop('checked', false);

	                alert("Complete");
	            },
	            error: function (response) {
	                alert("Error");
	            },
	            cache: false,
	            contentType: false,
	            processData: false
	        });
	    }

 	$("body").on("click", ".jumpnamecopyclicpcampaign", function (e) {
        textToCopy = '';

        e.preventDefault();
        var selectedOptions = $('input[name=allRows]:checked').length;

        if (selectedOptions == 0)
            $(this).closest('tr').find('.checkOption').prop('checked', true);


        $(".checkOption:checked").each(function () {
            var element = $(this).closest('tr').find('#campaignName');
            var old = element.text();
            element.text('copied');
            element.css('color', 'orange');

            setTimeout(function () {
                element.css('color', 'black');
                element.text(old);
            }, 500);
        });

        selectedOptions = $('input[name=allRows]:checked').length;

        if (selectedOptions > 1) {

            $(".checkOption:checked").each(function () {

                var campaigName = $(this).closest('tr').find('#campaignName').attr('Cname');
                var url = $(this).closest('tr').find('#campaignName').attr('Curl');

                textToCopy += campaigName + ' ' + url + '\n';
            });

        } else {
            var campaigName = $(this).closest('tr').find('#campaignName').attr('Cname');
            var url = $(this).closest('tr').find('#campaignName').attr('Curl');

            textToCopy += campaigName + ' ' + url + '\n';

            $(this).closest('tr').find('.checkOption').prop('checked', false);
        }


    });

    $("body").on("click", ".jumpnamecopyclicp", function (e) {
        textToCopy2 = '';

        e.preventDefault();
        var selectedOptions = $('input[name=allRows]:checked').length;

        if (selectedOptions == 0)
            $(this).closest('tr').find('.checkOption').prop('checked', true);

        $(".checkOption:checked").each(function () {
            var element = $(this).closest('tr').find('#jumpnames');
            var old = element.text();
            element.text('copied');
            element.css('color', 'orange');

            setTimeout(function () {
                element.css('color', 'black');
                element.text(old);
            }, 500);
        });

        selectedOptions = $('input[name=allRows]:checked').length;

        if (selectedOptions > 1) {

            $(".checkOption:checked").each(function () {

                
                var url = $(this).closest('tr').find('#jumpnames').attr('Jname');

                textToCopy2 += url + '\n';
            });

        } else {
            
            var url = $(this).closest('tr').find('#jumpnames').attr('Jname');

            textToCopy2 += url + '\n';

            $(this).closest('tr').find('.checkOption').prop('checked', false);
        }


    });

    $("body").on("click", ".jumpurlcopy", function (e) {
        element = $(this);

        textToCopy3 = '';

        e.preventDefault();
        var selectedOptions = $('input[name=allRows]:checked').length;

        if (selectedOptions == 0)
            $(this).closest('tr').find('.checkOption').prop('checked', true);


        selectedOptions = $('input[name=allRows]:checked').length;

        if (selectedOptions > 1) {

            $(".checkOption:checked").each(function () {

                
                var url = $(this).closest('tr').find('#jumpurls').attr('Jurl');

                textToCopy3 += url + '\n';
            });

        } else {
            
            var url = $(this).closest('tr').find('#jumpurls').attr('Jurl');

            textToCopy3 += url + '\n';

            $(this).closest('tr').find('.checkOption').prop('checked', false);
        }

        var oldSrc = '/img/njumppage/copy.svg?'+Math.random();

        element.attr('src', '/img/njumppage/copied.svg?'+Math.random());
        
        setTimeout(function () {
            element.attr('src', oldSrc);
        }, 500);

    });

    

    $("body").on("click", ".cpaEditable", function () {
        $(this).attr('contenteditable', true);
        $(this).focus();
    });

    $("body").on("change", "#checkall", function () {
        if ($(this).is(":checked")) {
            $(".checkOption").prop('checked', true);
        } else {
            $(".checkOption").prop('checked', false);
        }
    });

    var oldValue = '';
    $("body").on("focus", ".cpaEditable", function (e) {
        e.preventDefault();

        oldValue = $(this).text();
        $(this).closest('tr').find('.checkOption').prop('checked', true);

    });

	$('#searchInput').keypress(function (e) {
		if (e.which == 13) {
			setFilter();
			return false;    //<---- Add this line
		}
	});

    $("body").on("blur", ".cpaEditable", function (e) {
        e.preventDefault();

        var selectedOptions = $('input[name=allRows]:checked').length;
        var newValue = $(this).text();
        if (newValue != oldValue) {

            if (selectedOptions >= 1) {
                if (confirm('This will change the CPA for ' + selectedOptions + ' pack offers. Continue?')) {
                    var idsChanged = [];
                    if (selectedOptions > 1) {
                        $(".checkOption:checked").each(function () {
                            if ($(this).closest('tr').attr('type') == 1) {
                                $(this).closest('tr').find('.cpaEditable').text(newValue);
                                idsChanged.push($(this).val());
                            }
                        });
                    } else {
                        $(this).text(newValue);
                        idsChanged.push($(this).closest('tr').find('.checkOption:checked').val());
                        $('.checkOption').prop('checked', false);
                    }

                    updateCells(idsChanged, newValue);
                } else {
                    $(this).text(oldValue);
                    $('.checkOption').prop('checked', false);
                }
            }
        } else {
            if (selectedOptions == 1) {
                $('.checkOption').prop('checked', false);
            }
        }


        $(this).removeAttr('contenteditable');
    });

    $("body").on("keydown", ".cpaEditable", function (e) {
        //e.preventDefault();
        if (e.keyCode == '13') {
            $(this).trigger('blur');
        }
    });


    function updateCells(idsChanged, newValue) {

        formData = new FormData();
        formData.append('ids', idsChanged);
        formData.append('valueCpa', newValue);

        $.ajax({
            url: './updateCpa',
            type: 'POST',
            data: formData,
            async: true,
            success: function (data) {
                alert("Complete");
            },
            error: function (response) {
                alert("Error");
            },
            cache: false,
            contentType: false,
            processData: false
        });
    }

    $("body").on("click", ".checkOption", function () {
        if (!$(this).is(":checked") && $("#checkall").is(":checked")) {
            $("#checkall").prop('checked', false);
        } else if ($('input[name=allRows]:checked').length === numRows) {
            $("#checkall").prop('checked', true);
        }
    });
});

function disablePackOffer(mask, name) {
    if (confirm('You will disable the pack offer ' + name + ' .Continue?')) {

        var formData = new FormData();
        formData.append('mask', mask);

        $.ajax({
            url: './disableOffer',
            type: 'POST',
            data: formData,
            async: true,
            success: function (data) {

                alert("Complete");

                fillTable(data)

            },
            error: function (response) {
                alert("error");
            },
            cache: false,
            contentType: false,
            processData: false
        });

    }
}



/*
var statusGlobal;
function fillTable(data) {
    json = $.parseJSON(data);

    var rows = '';
    numRows = json.length;
    for (i = 0; i < json.length; i++) {

        var info = "'" + json[i].mask + "','" + json[i].campaign_name + "'";

        var cpaEditableAff = '';
        var statusIfAff = '';
        var affType = '';
        var iconsEdits = '';
        if (json[i].area == 1) {
            cpaEditableAff = 'cpaEditable2';
            statusIfAff = getValueElement("statusSB", json[i].status);
            affType = 0;
            iconsEdits = '';

        } else {
            cpaEditableAff = 'cpaEditable';

            statusIfAff = '<select hashPack="' + json[i].mask + '" id="SB' + json[i].mask + '" class="selectBoxTable">'
                    + '<option ' + ((json[i].status == 1) ? 'selected' : '') + ' value="1">Active</option>'
                    + '<option ' + ((json[i].status == 2) ? 'selected' : '') + ' value="2">Paused by Mobipium</option>'
                    + '<option ' + ((json[i].status == 3) ? 'selected' : '') + ' value="3">Paused by Client</option>'
                    + '<option ' + ((json[i].status == 4) ? 'selected' : '') + ' value="4">Disabled</option><select>';

            affType = 1;
            iconsEdits = '<a href="./offerpackedit?offerhash=' + json[i].mask + '"><img title="Edit" class="icontable" src="/img/iconEdit.svg"></a>'
                    + '<img style="cursor: pointer;" onclick="disablePackOffer(' + info + ')" title="Disable" class="icontable" class="modalIcon44" src="/img/iconDelete.png">'
                    + '<a href="./offerpackedit?offerhash=' + json[i].mask + '&clone=1"> <img style="cursor: pointer;" title="Clone" class="icontable" class="modalIcon44" src="/img/clonepack.png"></a>';

            if (json[i].banner == '') {

            } else {
                iconsEdits += '<a target="_blank" href="/offerpack/getBanners?offerhash=' + json[i].mask + '"><img style="cursor: pointer;"  title="Download banners" class="icontable" class="modalIcon44" src="/img/downloadzip.png"></a>';
            }

            if (json[i].imageurl == '') {

            } else {
                iconsEdits += '<a target="_blank" href="/offerpack/getScreenshot?offerhash=' + json[i].mask + '"> <img style="cursor: pointer;" title="Download screenshot" class="icontable" class="modalIcon44" src="/img/screenshot.png"></a>';
            }
        }

        var carrier;
        if (json[i].carrier == null) {
            carrier = '';
        } else {
            carrier = json[i].carrier;
        }

        var vertical;
        if (json[i].vertical == null) {
            vertical = '';
        } else {
            vertical = json[i].vertical;
        }

        var tds = '';
        tds += '<td><input type="checkbox" class="checkOption" name="allRows" value="' + json[i].mask + '"></td>'
                + '<td>' + json[i].insertTimestamp + '</td>'
                + '<td>' + getValueElement("countrySB", json[i].country.toUpperCase()) + '</td>'
                + '<td>' + carrier + '</td>'
                + '<td>' + getValueElement("aggsSB", json[i].advertiser) + '</td>'
                + '<td><a class="infoToolTip" href="./getofferinfo?offerhash=' + json[i].mask + '"><span Cname="' + json[i].campaign_name + '" Curl="' + json[i].clienturl + '" id="campaignName" class="jumpnamecopyclicpcampaign">' + json[i].campaign_name + '</span></a></td>'
                + '<td>' + getValueElement("areaSB", json[i].area) + '</td>'
                + '<td>' + vertical + '</td>'
                + '<td><div style="width:250px; margin-top:2%"><span id="jumpnames" Jname="' + json[i].jumpname + '" class="jumpnamecopyclicp" data-clipboard-text="' + json[i].jumpname + '">' + json[i].jumpname + '</span><img style="cursor: pointer;float:right;margin-top:-5%; margin-right:5px; padding:0px; width:40px" id="jumpurls" title="Clone" class="icontable jumpurlcopy" Jurl="' + json[i].jumpurl + '" data-clipboard-text="' + json[i].jumpurl + '" src="/img/njumppage/copy.svg"></div></td>'
                + '<td>' + getValueElement("modelSB", json[i].modelid) + '</td>'
                + '<td class="' + cpaEditableAff + '">' + json[i].cpa + '</td>'
                + '<td>' + json[i].curtype + '</td>'
                + '<td>' + json[i].dailycap + '</td>'
                + '<td>' + getValueElement("accountSB", json[i].accountmanager) + '</td>'
                + '<td>' + getValueElementGlobal(json[i].campaignmanager) + '</td>'
                + '<td>' + statusIfAff + '</td>'
                + '<td>'
                + iconsEdits
                + '</td>';

        rows += '<tr type="' + affType + '">' + tds + '</tr>';

    }


    if (typeof table != 'undefined' && table != null)
        table.destroy();

    $('#tableBodyPackOffers').empty();

    $("#tableBodyPackOffers").append(rows);

    table = $('#tablePackOffers').removeAttr('width').DataTable({
        "pageLength": 25,
        //"fixedHeader": true,
        //"bAutoWidth": false,
        //"columnDefs": [
        //    {width: '100px', targets: 1}
        //]
        "columns": [{"width": "1%"}, {"width": "7%"}, null, null, null, null, null, null, null, null, null, null, null, null, null, null, {"width": "1%"}]


    });
    $('#tablePackOffers').css("width", "100%");
    settooltip();
}







function disablePackOffer(mask, name) {
    if (confirm('You will disable the pack offer ' + name + ' .Continue?')) {

        var formData = new FormData();
        formData.append('mask', mask);

        $.ajax({
            url: './disableOffer',
            type: 'POST',
            data: formData,
            async: true,
            success: function (data) {

                alert("Complete");

                

                fillTable(data)

            },
            error: function (response) {
                alert("error");
            },
            cache: false,
            contentType: false,
            processData: false
        });

    }
}


var cmsGlobal = '';
var currencyGlobal = '';

function getValueElementGlobal(key) {
    var toReturn = '';
    $(cmsGlobal).each(function () {
        if (this.id == key) {
            toReturn = this.name;
            return false;
        }
    });
    return toReturn;
}

function getValueElement(selecBox, key) {
    return $("#" + selecBox + " option[value='" + key + "']").text()
}

$(document).ready(function () {

    $('.search-box-sel-all').unbind();
    $('.search-box-sel-all2').unbind();

    $("body").on("click", ".MultiControls .btnOk", function () {

        $('.search-box-sel-all').unbind();
        $('.search-box-sel-all2').unbind();

        if (getSumoSelects('#countrySB', 6) != '') {
            $.ajax({
                url: './getCarriersMultiple?country=' + getSumoSelects('#countrySB', 6),
                type: 'GET',
                async: true,
                success: function (data) {

                    var json = $.parseJSON(data);

                    var carriers = json['carriers'];

                    if (undefined !== carriers && carriers.length) {
                        var selectBoxCarrier = '<option value="ALL">All</option>';
                        for (var i = 0; i < carriers.length; i++) {
                            selectBoxCarrier += '<option value="' + carriers[i]['id'] + '">' + carriers[i]['carrierTag'] + '</option>';
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

                    $("#carrierSB").css("display", "none");
                },
                error: function (response) {
                    alert("error");
                    $("#carrierSB").css("display", "none");
                },
                cache: false,
                contentType: false,
                processData: false
            });
        } else {
            $("#carrierSB").empty();
            $("#carrierSB").attr('disabled', true);
            $('select#carrierSB')[0].sumo.reload();
            $("select#carrierSB").css('display', 'none');
        }
    });

    function checkParam() {
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1);
        var splitHashes = hashes.split('&');

        hash = splitHashes[0].split('=');

        if (hash[1]) {
            setFilter(hash[1]);
        }
    }

    $("body").on("click", "#buttonFilter", function () {
        setFilter();

    });

    function setFilter(hash) {
        $('.search-box-sel-all').unbind();
        $('.search-box-sel-all2').unbind();

        $("#checkall").prop('checked', false);

        var formData = new FormData();

        if (hash) {
            formData.append('jump_hash', hash);
        }

        formData.append('countries', getSumoSelects("#countrySB", 6));
        formData.append('carriers', getSumoSelects("#carrierSB", 7));
        formData.append('aggs', getSumoSelects("#aggsSB", 6));
        formData.append('area', getSumoSelects("#areaSB", 6));
        formData.append('vertical', getSumoSelects("#verticalSB", 6));
        formData.append('model', getSumoSelects("#modelSB", 6));
        formData.append('status', getSumoSelects("#statusSB", 6));
        formData.append('account', getSumoSelects("#accountSB", 6));
        //formData.append('campaign_hash', getSumoSelects("#campaignName", 6));
        //formData.append('jump_hash', getSumoSelects("#jumpname", 6));
        formData.append('searchInput', $("#searchInput").val());

        $.ajax({
            url: './setFilter',
            type: 'POST',
            data: formData,
            async: true,
            success: function (data) {

                fillTable(data);

            },
            error: function (response) {
                alert("error");
            },
            cache: false,
            contentType: false,
            processData: false
        });
    }

    var oldValueSBTable = '';
    $("body").on("click", ".selectBoxTable", function () {
        oldValueSBTable = $(this).val();
        $(this).closest("tr").find('.checkOption').prop('checked', true);
    });

    $(document).delegate('.selectBoxTable', 'change', function () {
        var reason = '';
        var newValue = $(this).val();
        var selectedOptions = $('input[name=allRows]:checked').length;
        var idsChanged = [];
        if (selectedOptions >= 1) {
            if (confirm('This will change the status for ' + selectedOptions + ' pack offers. Continue?')) {

                if (newValue == 2 || newValue == 3) {
                    while (reason == null || reason == '') {
                        reason = window.prompt("Why are you pausing this?", "");
                    }
                }

                //if(selectedOptions > 1) {
                $(".checkOption:checked").each(function () {
                    if ($(this).closest('tr').attr('type') == 1) {
                        $(this).closest('tr').find('.selectBoxTable').val(newValue);
                        idsChanged.push($(this).val());
                    }
                });
                //} else {
                //$(this).closest("tr").find('.checkOption').attr('checked', false);
                //idsChanged.push($(this).closest('tr').find('.checkOption:checked').val());
                //}
            } else {
                $(this).val(oldValueSBTable);
                $('.checkOption').prop('checked', false);
            }

            updateCellsStatus(idsChanged, newValue, reason);
        }
    });

    function updateCellsStatus(idsChanged, newValue, reason) {
        formData = new FormData();
        formData.append('ids', idsChanged);
        formData.append('valueStatus', newValue);
        formData.append('reason', reason);

        $.ajax({
            url: './updateStatus',
            type: 'POST',
            data: formData,
            async: true,
            success: function (data) {

                $('.checkOption').prop('checked', false);

                alert("Complete");
            },
            error: function (response) {
                alert("Error");
            },
            cache: false,
            contentType: false,
            processData: false
        });
    }

    var table = null;




   

    var numRows;





    $("body").on("click", ".jumpnamecopyclicpcampaign", function (e) {
        textToCopy = '';

        e.preventDefault();
        var selectedOptions = $('input[name=allRows]:checked').length;

        if (selectedOptions == 0)
            $(this).closest('tr').find('.checkOption').prop('checked', true);


        $(".checkOption:checked").each(function () {
            var element = $(this).closest('tr').find('#campaignName');
            var old = element.text();
            element.text('copied');
            element.css('color', 'orange');

            setTimeout(function () {
                element.css('color', 'black');
                element.text(old);
            }, 500);
        });

        selectedOptions = $('input[name=allRows]:checked').length;

        if (selectedOptions > 1) {

            $(".checkOption:checked").each(function () {

                var campaigName = $(this).closest('tr').find('#campaignName').attr('Cname');
                var url = $(this).closest('tr').find('#campaignName').attr('Curl');

                textToCopy += campaigName + ' ' + url + '\n';
            });

        } else {
            var campaigName = $(this).closest('tr').find('#campaignName').attr('Cname');
            var url = $(this).closest('tr').find('#campaignName').attr('Curl');

            textToCopy += campaigName + ' ' + url + '\n';

            $(this).closest('tr').find('.checkOption').prop('checked', false);
        }


    });

    $("body").on("click", ".jumpnamecopyclicp", function (e) {
        textToCopy2 = '';

        e.preventDefault();
        var selectedOptions = $('input[name=allRows]:checked').length;

        if (selectedOptions == 0)
            $(this).closest('tr').find('.checkOption').prop('checked', true);

        $(".checkOption:checked").each(function () {
            var element = $(this).closest('tr').find('#jumpnames');
            var old = element.text();
            element.text('copied');
            element.css('color', 'orange');

            setTimeout(function () {
                element.css('color', 'black');
                element.text(old);
            }, 500);
        });

        selectedOptions = $('input[name=allRows]:checked').length;

        if (selectedOptions > 1) {

            $(".checkOption:checked").each(function () {

                
                var url = $(this).closest('tr').find('#jumpnames').attr('Jname');

                textToCopy2 += url + '\n';
            });

        } else {
            
            var url = $(this).closest('tr').find('#jumpnames').attr('Jname');

            textToCopy2 += url + '\n';

            $(this).closest('tr').find('.checkOption').prop('checked', false);
        }


    });

    $("body").on("click", ".jumpurlcopy", function (e) {
        textToCopy3 = '';

        e.preventDefault();
        var selectedOptions = $('input[name=allRows]:checked').length;

        if (selectedOptions == 0)
            $(this).closest('tr').find('.checkOption').prop('checked', true);


        selectedOptions = $('input[name=allRows]:checked').length;

        if (selectedOptions > 1) {

            $(".checkOption:checked").each(function () {

                
                var url = $(this).closest('tr').find('#jumpurls').attr('Jurl');

                textToCopy3 += url + '\n';
            });

        } else {
            
            var url = $(this).closest('tr').find('#jumpurls').attr('Jurl');

            textToCopy3 += url + '\n';

            $(this).closest('tr').find('.checkOption').prop('checked', false);
        }


    });

    

    $("body").on("click", ".cpaEditable", function () {
        $(this).attr('contenteditable', true);
        $(this).focus();
    });

    $("body").on("change", "#checkall", function () {
        if ($(this).is(":checked")) {
            $(".checkOption").prop('checked', true);
        } else {
            $(".checkOption").prop('checked', false);
        }
    });

    var oldValue = '';
    $("body").on("focus", ".cpaEditable", function (e) {
        e.preventDefault();

        oldValue = $(this).text();
        $(this).closest('tr').find('.checkOption').prop('checked', true);

    });

    $("body").on("blur", ".cpaEditable", function (e) {
        e.preventDefault();

        var selectedOptions = $('input[name=allRows]:checked').length;
        var newValue = $(this).text();
        if (newValue != oldValue) {

            if (selectedOptions >= 1) {
                if (confirm('This will change the CPA for ' + selectedOptions + ' pack offers. Continue?')) {
                    var idsChanged = [];
                    if (selectedOptions > 1) {
                        $(".checkOption:checked").each(function () {
                            if ($(this).closest('tr').attr('type') == 1) {
                                $(this).closest('tr').find('.cpaEditable').text(newValue);
                                idsChanged.push($(this).val());
                            }
                        });
                    } else {
                        $(this).text(newValue);
                        idsChanged.push($(this).closest('tr').find('.checkOption:checked').val());
                        $('.checkOption').prop('checked', false);
                    }

                    updateCells(idsChanged, newValue);
                } else {
                    $(this).text(oldValue);
                    $('.checkOption').prop('checked', false);
                }
            }
        } else {
            if (selectedOptions == 1) {
                $('.checkOption').prop('checked', false);
            }
        }


        $(this).removeAttr('contenteditable');
    });

    $("body").on("keydown", ".cpaEditable", function (e) {
        //e.preventDefault();
        if (e.keyCode == '13') {
            $(this).trigger('blur');
        }
    });


    function updateCells(idsChanged, newValue) {

        formData = new FormData();
        formData.append('ids', idsChanged);
        formData.append('valueCpa', newValue);

        $.ajax({
            url: './updateCpa',
            type: 'POST',
            data: formData,
            async: true,
            success: function (data) {
                alert("Complete");
            },
            error: function (response) {
                alert("Error");
            },
            cache: false,
            contentType: false,
            processData: false
        });
    }

    $("body").on("click", ".checkOption", function () {
        if (!$(this).is(":checked") && $("#checkall").is(":checked")) {
            $("#checkall").prop('checked', false);
        } else if ($('input[name=allRows]:checked').length === numRows) {
            $("#checkall").prop('checked', true);
        }
    });

    $(":file").filestyle({
        placeholder: "No files",
        buttonText: "Choose .CSV and Upload"
    });

    $(document).delegate(':file', 'change', function () {
        if (confirm('Are you sure you want to save this Jumps?')) {
            submitform();
        } else {
            $(":file").filestyle('clear');
        }
    });

    $('label').on("click", function (event) {
        $(":file").filestyle('clear');
    });

    function submitform() {
        event.preventDefault();

        if (true) {
            $(":file").filestyle('disabled', true);

            var fileInput = document.getElementById('filestyle-0');
            var file = fileInput.files[0];
            var formData = new FormData();
            formData.append('file', file);

            $.ajax({
                url: './addOffersExcel',
                type: 'POST',
                data: formData,
                async: true,
                success: function (data) {
                    dataTable = data;
                    $(":file").filestyle('disabled', false);
                },
                error: function (response) {
                    alert("error");
                },
                cache: false,
                contentType: false,
                processData: false
            });

        } else {
            alert("File not found.");
        }
    }


    //new Clipboard('.jumpnamecopyclicp');

    var textToCopy = '';
    var textToCopy2 = '';
    var textToCopy3 = '';
    new Clipboard(".jumpnamecopyclicpcampaign", {
        text: function (trigger) {
            return textToCopy;
        }
    });

    new Clipboard(".jumpnamecopyclicp", {
        text: function (trigger) {
            return textToCopy2;
        }
    });


    new Clipboard(".jumpurlcopy", {
        text: function (trigger) {
            return textToCopy3;
        }
    });





});


*/


