$(document).ready(function () {
    var testing = getUrlParameter("testing");
    var sourcetype = $("#sourcetypeID").val();

    $('#fperiod').val(decodeURIComponent(getUrlParameter('fperiod').replace(/\+/g, '%20')));
    $('#speriod').val(decodeURIComponent(getUrlParameter('speriod').replace(/\+/g, '%20')));
    $('#hour1').val(getUrlParameter('hoursStart'));
    $('#hour2').val(getUrlParameter('hoursEnd'));
    
    window.searchSelAll = $('.search-box-sel-all').SumoSelect({csvDispCount: 3, selectAll: false, search: true, searchText: 'Enter here.', okCancelInMulti: false});

    $("#browseit").on('click', function(e) {
        $('#status').show();
        $('#preloader').show();
        e.preventDefault();
        
        
        

        $.ajax({
            url: './statistics?testing=1&action=statRequest&'+$("#formdataBrowse").serialize(),
            success: function (result) {
                if (result.indexOf("<table") > -1) {
                    $("#countrytable").remove();
                    $("#sourcetable").remove();
                    $("#sourcetable2").remove();
                    $("#aggregatortable").remove();
                    $("#campaigntable").remove();
                    $("#operatortable").remove();
                    $("#hourtabletable").remove();
                    $("#global").append(result);
                    
                }
            },
            complete: function () {
                $('#status').fadeOut(); // will first fade out the loading animation
                $('#preloader').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website.
                $('body').delay(350).css({'overflow': 'visible'});
                rebindGoto();
            }});



    });

    $("#campID").on('change', function () {

        if (getSumoSelects("#campID", 1).length != 0) {
            console.log('1');
            $('select#opID').prop('disabled', true);
            $('select#aggID')[0].sumo.disable();
            $('select#ccID')[0].sumo.disable();
            $('select#so')[0].sumo.disable();
        } else {
            console.log('2');
            $('select#opID').prop('disabled', false);
            $('select#aggID')[0].sumo.enable();
            $('select#ccID')[0].sumo.enable();
            $('select#so')[0].sumo.enable();
        }


        if (jQuery.inArray("allcampaigns", getSumoSelects("#campID", 1)) !== -1) {
            console.log('3');
            $('select#opID').prop('disabled', false);
            $('select#aggID')[0].sumo.enable();
            $('select#ccID')[0].sumo.enable();
            $('select#so')[0].sumo.enable();
        }

    });


    $("#aggID").on('change', function () {

        if (getSumoSelects("#aggID", 1).length != 0) {
            console.log('4');
            //$('select#campID')[0].sumo.unSelectAll();
            $('select#campID')[0].sumo.disable();
        } else {
            console.log('5');
            $('select#campID')[0].sumo.enable();
        }

    });

    $("#ccID").on('change', function (e) {
        e.preventDefault();
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
            console.log('6');
        } else {
            console.log('7');
            $('#opID option[value="allops"]').attr("selected", "selected");
            $("#opID").prop('disabled', true);
            $('select#campID')[0].sumo.enable();
        }

    });


    function getUrlParameter(sParam)
    {
        var sPageURL = window.location.search.substring(1);
        var sURLVariables = sPageURL.split("&");
        for (var i = 0; i < sURLVariables.length; i++)
        {
            var sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] == sParam)
            {
                return sParameterName[1];
            }
        }
        return '';
    }
    ;

    function getUrlParameterArray(sParam)
    {
        var sPageURL = window.location.search.substring(1);
        var sURLVariables = sPageURL.split("&");
        var all = [];
        for (var i = 0; i < sURLVariables.length; i++)
        {
            var sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] == sParam)
            {
                all.push(sParameterName[1]);
            }
        }
        return all;
    }
    ;
    var srctype = getUrlParameter('selectedSourceType');
    if (srctype != null) {
        $('#sourcetypeID option[value="' + srctype + '"]').attr("selected", "selected");
    }
    //$('#cc option[value="' + getUrlParameter('cc') + '"]').attr("selected", "selected");
    
    
    
//$('#startD2').val(decodeURIComponent(getUrlParameter('s2').replace(/\+/g, '%20')));
    //$('#endD2').val(decodeURIComponent(getUrlParameter('e2').replace(/\+/g, '%20')));

    //$('#aggID option[value="' + getUrlParameter('agg') + '"]').attr("selected", "selected");
    //$('#campID option[value="' + getUrlParameter('selectedCampaign') + '"]').attr("selected", "selected");
    $('#aggFunction option[value="' + getUrlParameter('aggFunction') + '"]').attr("selected", "selected");


    var src = getUrlParameterArray('src%5B%5D');
    if (src != null) {
        src.forEach(function (currentValue, index, arr) {
            $('select#so')[0].sumo.selectItem(currentValue);
        });
    }
    var cc = getUrlParameterArray('cc%5B%5D');
    if (cc != null) {
        cc.forEach(function (currentValue, index, arr) {
            $('select#ccID')[0].sumo.selectItem(currentValue);
        });
    }
    var agg = getUrlParameterArray('agg%5B%5D');
    if (agg != null) {
        agg.forEach(function (currentValue, index, arr) {
            $('select#aggID')[0].sumo.selectItem(currentValue);
        });
    }
    var cmp = getUrlParameterArray('selectedCampaign%5B%5D');
    if (cmp != null) {
        cmp.forEach(function (currentValue, index, arr) {
            $('select#campID')[0].sumo.selectItem(currentValue);
        });
    }
    //$('select#aggFunction')[0].sumo.selectItem(getUrlParameter('aggFunction'));

    if (getSumoSelects("#campID", 1).length == 0) {
        console.log('7');
        $('select#ccID')[0].sumo.enable();
        $('select#aggID')[0].sumo.enable();
    } else {
        console.log('8');
        $('select#ccID')[0].sumo.disable();
        $('select#aggID')[0].sumo.disable();
    }
    if (getSumoSelects("#aggID", 1).length > 0) {
        $('select#campID')[0].sumo.disable();
        console.log('9');
    } else {
        console.log('10');
        $('select#campID')[0].sumo.enable();
    }



    if (getSumoSelects("#ccID", 1).length > 0 && getSumoSelects("#ccID", 1)[0] != 'ALL') {
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
                $("#opID").prop('disabled', false);
                var op = getUrlParameter('selectedOperator');
                if (op != null)
                    $('#opID option[value="' + op + '"]').attr("selected", "selected");

            }
        });
        
        

        $('select#campID')[0].sumo.disable();

    } else {
        $('#opID option[value="allops"]').attr("selected", "selected");
        $("#opID").prop('disabled', true);
        $('select#campID')[0].sumo.enable();
    }


    $(".opt").on('click', function(e) {
        console.log("ok");

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