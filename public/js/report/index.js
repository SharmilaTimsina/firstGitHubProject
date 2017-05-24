$(document).ready(function() {

    
    $("#campID").on('change', function() {
        if (getSumoSelects("#campID", 1).length != 0) {
            $('select#opID').prop('disabled', true);
            $('select#aggID')[0].sumo.disable();
            $('select#ccID')[0].sumo.disable();
            $('select#soID')[0].sumo.disable();
        } else {
            $('select#opID').prop('disabled', false);
            $('select#aggID')[0].sumo.enable();
            $('select#ccID')[0].sumo.enable();
            $('select#soID')[0].sumo.enable();
        }

        if (jQuery.inArray("allcampaigns", getSumoSelects("#campID", 1)) !== -1) {
            $('select#opID').prop('disabled', false);
            $('select#aggID')[0].sumo.enable();
            $('select#ccID')[0].sumo.enable();
            $('select#soID')[0].sumo.enable();
        }
    });
    $("#aggID").on('change', function() {

        if (getSumoSelects("#aggID", 1).length != 0) {
            $('select#campID')[0].sumo.disable();
        } else {
            $('select#campID')[0].sumo.enable();
        }

    });
    $("#ccID").on('change', function(e) {
        e.preventDefault();
        formData = new FormData();
        formadata = getSumoSelects("#ccID", 1).toString();
        $.ajax({
            url: '/report/getCarrier2?countriescarriers=' + formadata,
            type: 'GET',
            cache: false,
            processData: false,
            success: function (result) {
                if (result.indexOf("<option value=") > -1) {
                    $('#opID').find('option').remove().end().append(result);
                }
            }
        });
        if (getSumoSelects("#ccID", 1).length != 0 && jQuery.inArray("ALL", getSumoSelects("#ccID", 1)) == -1) {
            $('select#campID')[0].sumo.disable();
            $('#opID').prop('disabled', false);
        } else {
            $('#opID option[value="allops"]').attr("selected", "selected");
            $("#opID").prop('disabled', true);
            $('select#campID')[0].sumo.enable();
        }

    });
    var campaignSelects = [];
    var aggsSelects = [];
    var opsSelects = [];
    var ccsSelects = [];
    $('#aggCampaignid option:selected').each(function() {
        if (campaignSelects.indexOf($(this).val()) < 0)
            campaignSelects.push($(this).val());
    });
    $('#ccsid option:selected').each(function() {
        if (ccsSelects.indexOf($(this).val()) < 0)
            ccsSelects.push($(this).val());
    });
    $('#aggAggsID option:selected').each(function() {
        if (aggsSelects.indexOf($(this).val()) < 0)
            aggsSelects.push($(this).val());
    });
    $('#operatorsid option:selected').each(function() {
        if (opsSelects.indexOf($(this).val()) < 0)
            opsSelects.push($(this).val());
    });

    function rebindFuncs() {
        $("#aggCampaignid option").on('mousedown', function(e) {
            e.preventDefault();
            $(this).prop('selected', !$(this).prop('selected'));
            return false;
        });
        $("#ccsid option").on('mousedown', function(e) {
            e.preventDefault();
            $(this).prop('selected', !$(this).prop('selected'));
            return false;
        });
        $("#aggAggsID option").on('mousedown', function(e) {
            e.preventDefault();
            $(this).prop('selected', !$(this).prop('selected'));
            return false;
        });
        $("#operatorsid option").on('mousedown', function(e) {
            e.preventDefault();
            $(this).prop('selected', !$(this).prop('selected'));
            return false;
        });
        $('#aggCampaignid option').on('click', function(e) {
            var pos = campaignSelects.indexOf(e.target.value);
            if ((e.target).selected && pos < 0) {
                campaignSelects.push(e.target.value);
            } else if (pos > -1 && !(e.target).selected) {
                campaignSelects.splice(pos, 1);
            }
            //console.log(selects);
        });
        $('#ccsid option').on('click', function(e) {
            var pos = ccsSelects.indexOf(e.target.value);
            if ((e.target).selected && pos < 0) {
                ccsSelects.push(e.target.value);
            } else if (pos > -1 && !(e.target).selected) {
                ccsSelects.splice(pos, 1);
            }
            //console.log(selects);
        });
        $('#aggAggsID option').on('click', function(e) {
            var pos = aggsSelects.indexOf(e.target.value);
            if ((e.target).selected && pos < 0) {
                aggsSelects.push(e.target.value);
            } else if (pos > -1 && !(e.target).selected) {
                aggsSelects.splice(pos, 1);
            }
            //console.log(selects);
        });
        $('#operatorsid option').on('click', function(e) {
            var pos = opsSelects.indexOf(e.target.value);
            if ((e.target).selected && pos < 0) {
                opsSelects.push(e.target.value);
            } else if (pos > -1 && !(e.target).selected) {
                opsSelects.splice(pos, 1);
            }
            //console.log(selects);
        });
    }
    rebindFuncs();
    $(function() {
        $('#aggCampaignid').filterByText($('#campaignsfilter'), campaignSelects);
    });
    $(function() {
        $('#ccsid').filterByText($('#countriesfilter'), ccsSelects);
    });
    $(function() {
        $('#aggAggsID').filterByText($('#aggregatorsfilter'), aggsSelects);
    });
    $(function() {
        $('#operatorsid').filterByText($('#operatorsfilter'), opsSelects);
    });
    jQuery.fn.filterByText = function(textbox, selects) {
        return this.each(function() {
            var select = this;
            var options = [];
            $(select).find('option').each(function() {
                options.push({
                    value: $(this).val(),
                    text: $(this).text()
                });
            });
            $(select).data('options', options);
            $(textbox).bind('change keyup', function() {

                var options = $(select).empty().data('options');
                var search = $.trim($(this).val());
                var regex = new RegExp(search, "gi");
                $.each(options, function(i) {
                    var option = options[i];
                    if (option.text.match(regex) !== null) {
                        if (selects.indexOf(option.value) != -1) {
                            $(select).append($('<option>').text(option.text).val(option.value).attr('selected', 'selected'));
                        } else {
                            $(select).append($('<option>').text(option.text).val(option.value));
                        }
                    }
                });
                rebindFuncs();
            });
        });
    };
    window.searchSelAll = $('.search-box-sel-all').SumoSelect({
        csvDispCount: 3,
        selectAll: false,
        search: true,
        searchText: 'Enter here.',
        okCancelInMulti: false
    });

 
    $(".opt").on('click', function(e) {
        var string = $(this).find('label').text();
        if(string.indexOf('All') !== -1) {
                	
	        if($(this).hasClass('selected')) {
	        	$(this).closest('.SumoSelect').find('.options li').removeClass('selected');
	        	$(this).closest('.SumoSelect').find('select option:selected').removeAttr("selected");
	        	$(this).closest('.SumoSelect').find('.options li:eq(0)').addClass('selected');
	        	$(this).closest('.SumoSelect').find('select option:eq(0)').prop("selected", true)
	        } else {
	        	$(this).closest('.SumoSelect').find('.options li').removeClass('selected')
	        	$(this).closest('.SumoSelect').find('select option:selected').removeAttr("selected");
	        }
	        
   	 	} else {
	        $(this).closest('.SumoSelect').find('select option:eq(0)').removeAttr("selected");
	        $(this).closest('.SumoSelect').find('.options li:eq(0)').removeClass('selected')
   	 	}

   	 	$($(this).closest('.SumoSelect').find('select'))[0].sumo.setText();
    });
});