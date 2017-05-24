$(document).ready(function () {
	var testing = getUrlParameter("testing");
	var sourcetype = $("#sourcetypeID").val();
	
     $("#browseit").click(function () {
        $('#status').show();
        $('#preloader').show();
        $.ajax({
            url: './statistics?fperiod=' + $('#fperiod').val() + '&speriod=' + $('#speriod').val() + '&hoursStart=' + $('#hour1').val() + '&hoursEnd=' + $('#hour2').val() + '&cc=' + $('#cc').val() + '&agg=' + $('#aggID').val() + '&selectedCampaign=' + $('#campID').val() + '&selectedOperator=' + $('#opID').val() + ($('#so').val() != null ? '&src=' + $('#so').val() : '') + ($('#aggFunction').val() != null ? '&aggFunction=' + $('#aggFunction').val() : '') + '&action=statRequest' + (testing ? '&testing=1': '') + ($("#sourcetypeID").val() ? '&selectedSourceType='+$("#sourcetypeID").val() : ''),
            success: function (result) {
                if (result.indexOf("<table") > -1) {
                    $("#countrytable").remove();
                    $("#sourcetable").remove();
					$("#sourcetable2").remove();
                    $("#aggregatortable").remove();
                    $("#operatortable").remove();
                    $("#global").append(result);
                }
            },
            complete: function () {
                $('#status').fadeOut(); // will first fade out the loading animation
                $('#preloader').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website.
                $('body').delay(350).css({'overflow': 'visible'});
            }});
    });

    $("#butao").click(function (e) {
        e.preventDefault();
        $('#status').show();
        $('#preloader').show();
        var form = $('#aform');
        $.ajax({
            type: "GET",
            data: form.serialize(),
            url: '/report/aggSummary?inside=1',
            success: function (result) {
                if (result.indexOf("<div id=") > -1) {
                    $("#restablediv").remove();
                    $("#global").append(result);
                }
            },
            complete: function () {
                $('#status').fadeOut(); // will first fade out the loading animation
                $('#preloader').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website.
                $('body').delay(350).css({'overflow': 'visible'});
            }});
    });

    
    if ($("#campID").val() !== 'allcampaigns') {
        $("#cc").prop('disabled', true);
        $("#aggID").prop('disabled', true);
    }
    if ($("#aggID").val() !== 'allaggs') {
        $("#campID").prop('disabled', true);
    }
    if ($("#cc").val() !== 'ALL') {
        $.ajax({
            type: "GET",
            url: '/reportmb/getCarrier?country='+$("#cc").val(),
            success: function (result) {
                if (result.indexOf("<option value=") > -1) {
                    $('#opID').find('option').remove().end().append(result);
                }
            },
            complete: function () {
                $('#status').fadeOut(); // will first fade out the loading animation
                $('#preloader').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website.
                $('body').delay(350).css({'overflow': 'visible'});
        }});
        $("#opID").prop('disabled', false);
        var op = getUrlParameter('selectedOperator');
        if(op != null)
            $('#opID option[value="' + op + '"]').attr("selected", "selected");
        $("#campID").prop('disabled', true);

    }
    else{
        $('#opID option[value="allops"]').attr("selected", "selected");
        $("#opID").prop('disabled', true);
        $("#campID").prop('disabled', false);
    }
        
    

    $("#campID").on('change', function () {
        if ($("#campID").val() !== 'allcampaigns') {
            $("#cc").prop('disabled', true);
            $("#aggID").prop('disabled', true);
        }
        else {
            $("#cc").prop('disabled', false);
            $("#aggID").prop('disabled', false);
        }
    });

    $("#aggID").on('change', function () {
        if ($("#aggID").val() !== 'allaggs') {
            $("#campID").prop('disabled', true);
        }
        else {
            $("#campID").prop('disabled', false);
        }
    });

    $("#cc").on('change', function (e) {
        if ($("#cc").val() !== 'ALL') {
            e.preventDefault();
            $.ajax({
                type: "GET",
                url: '/reportmb/getCarrier?country='+$("#cc").val(),
                success: function (result) {
                    if (result.indexOf("<option value=") > -1) {
                        $('#opID').find('option').remove().end().append(result);
                    }
                }});
            $("#opID").prop('disabled', false);
            $("#campID").prop('disabled', true);
            
        }
        else {
            $('#opID option[value="allops"]').attr("selected", "selected");
            $("#campID").prop('disabled', false);
            $("#opID").prop('disabled', true);
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
    };
	
	
});