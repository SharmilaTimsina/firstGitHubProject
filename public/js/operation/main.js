$(document).ready(function () {

    //   $( "#spicker" ).datepicker();
    //   $( "#epicker" ).datepicker();
    var parser = document.createElement('a');
    parser.href = window.location.href;
    $("#campaignupdater").change(function () {

        $.ajax({
            "url": '/operation/getCampaignLink?campID=' + $('#campaignupdater').val(),
            "success": function (json) {
                var miniarr = json.split("$-$");
                var cpa = miniarr[1];
				if (miniarr[9] == 2)
                {
                    $('#mainstreamcheck').prop('checked', true);
					$('#campaignCategory').show();
					if(miniarr[10])
						$('#campaignCategory').val(miniarr[10]);
                } else {
					$('#mainstreamcheck').prop('checked', false);
					$('#campaignCategory').hide();
				}
                if (miniarr[3] != 'USD')
                {
                    cpa = miniarr[4];
                }
                $('#linkC').val(miniarr[0]);
                $('#campaigncpaID').val(cpa);
                $('#affiliateCa').val(miniarr[2]);
                $('#cpaCurrencyID').val(miniarr[3]);
				if(lvl < 3)
					var link = 'http://jump.youmobistein.com/?jp=';
				else 
					var link = 'http://jump.mobipiumlink.com/?jp=';
				link = link + $('#campaignupdater').val() + '&id=' + miniarr[5] + '_' + miniarr[6] + '_'+miniarr[8]+'_' + miniarr[7]+'_';
                $('#linkgenerate').attr("type", "text");
                $('#linkgenerate').val(link);
            }

        });
    });
    
    $("#aggNames").change(function () {

        $.ajax({
            "url": '/operation/getAggInfo?aggID=' + $('#aggNames').val(),
            "success": function (json) {
                var v = json.split("--.--");
                $('#aggconnector').text(v[0]);
                $('#aggparameter').text(v[1]);
            }
        });
    });
    
    $("#newsrcbutton").click(function(){
        $.ajax({
            url: '/operation/createSource?src=' + $('#newSrc').val()+'&sourcetype='+$('#sourcetype').find('input[name=source]:checked').val()+'&investment='+$('#investment').is(':checked')+ '&bulks='+$('#bulks').is(':checked') + '&copyfromthis=' + $("#copyfromthis  option:selected").val(),
            success: function(result){
                if(result > 1) {
                    $("#completed").html('<div class="alert alert-warning" role="alert"><label>New Source successfully created </label></div>');
                    var option = document.createElement("option");
                    option.text = $('#newSrc').val()+'-'+result;
                    option.value = result;
                    addOptionToArr(document.getElementById("sourcesList").options, $('#newSrc').val(), option);
                }
        }});
    });

     $("#copysrcbutton").click(function(){
        $.ajax({
            url: '/operation/copySource?investment='+$('#investment2').is(':checked')+ '&bulks='+$('#bulks2').is(':checked') + '&copyfromthis=' + $("#copyfromthis2  option:selected").val() + '&copytothis=' + $("#copytothis  option:selected").val(),
            success: function(result){
                if(result > 1) {
                    $("#completed").html('<div class="alert alert-warning" role="alert"><label>Copy Source success</label></div>');
                } else {
                    alert("error");
                }
        }});
    });
    
    $("#newaggbutton").click(function(){
        $.ajax({
            url: '/operation/createAggregator?agg=' + $('#newAgg').val() + '&tracking=' + $('#newTracking').val() + '&company=' + $('input[name=companyname]:checked').val(),
            success: function(result){
                if(result > 1) {
                    $("#completed").html('<div class="alert alert-warning" role="alert"><label>New Aggregator successfully created </label></div>');
                    var anoption = document.createElement("option");
                    anoption.text = $('#newAgg').val() + '-'+result;
                    anoption.value = result;
                    addOptionToArr(document.getElementById("aggNames").options, $('#newAgg').val(), anoption);
                }
				else{
					$("#completed").html('<div class="alert alert-warning" role="alert"><label>Could not add new agregator. </label></div>');
				}
                
        }});
    });
    
	$("#newcatbutton").click(function(){
        $.ajax({
            url: '/operation/createCategory?cat=' + $('#newCategory').val(),
            success: function(result){
                if(result > 1) {
                    $("#completed").html('<div class="alert alert-warning" role="alert"><label>New Category successfully created </label></div>');
                    var option = document.createElement("option");
                    option.text = $('#newCategory').val()+'-'+result;
                    option.value = result;
                    addOptionToArr(document.getElementById("categoriesList").options, $('#newCategory').val(), option);
                }
        }});
    });
	
    $("#tlinkbutton").click(function(){
        
         $.ajax({
            url: '/operation/link_checker?c=' + $('#tagr').val() + '&u=' + encodeURIComponent($('#turl').val())+'&p='+$('#tparam').val(),
            success: function(result){
                
                $("#linktresult").text(result);
                
        }});
        
    });
    $("#tagr").change(function(){
        
         if($("#tagr").val()!=0){
            $("#tparam").hide();
            $("#paramhide").hide();
        }else{
             $("#tparam").show();
            $("#paramhide").show();
        }
    });
	
	$("#clienturlbutton").click(function(){
        
         $.ajax({
            url: '/operation/campaign_url?u=' + encodeURIComponent($('#clienturl').val()),
            success: function(result){
                
                $("#clienturlresult").text(result);
                
        }});
        
    });
	
	$("#conversionbutton").click(function(){
        
         $.ajax({
            url: '/operation/findLatestConv?campaignname=' + $('#convcampaignname').val(),
            success: function(result){
                if(result.indexOf('jumbotron')>-1)
					$("#conversionresult").html('Error, open a private window and login.');
				else if(result == '0')
					$("#conversionresult").html('No conversions were registered.');
				else 
					$("#conversionresult").html(result);
                
        }});
        
    });
    
	$("#newcampaignmainstream").click(function(){
        if($("#newcampaignmainstream").prop('checked') == true){
			$("#newJumpCategory").show();
		}
		else{
			$("#newJumpCategory").hide();
			$("#newJumpCategory").val('');
		}
    });
	
	$("#aggNames").change(function(){
        if($("#aggNames").val() == 76){
			$("#newVuclipCampaign").show();
		}
		else{
			$("#newVuclipCampaign").hide();
			$("#newJumpCategory").val('');
		}
    });
	
	
	$("#mainstreamcheck").click(function(){
        if($("#mainstreamcheck").prop('checked') == true){
			$("#campaignCategory").show();
		}
		else{
			$("#campaignCategory").hide();
			$("campaignCategory option").prop("selected", false);
		}
    });
	
    $(function() {
        $('#campaignupdater').filterByText($('#campaignfilter'));
        $('#campaignNames').filterByText($('#campaignfilter2'));
        $('#aggNames').filterByText($('#aggfilter'));
        $('#sourcesList').filterByText($('#sourcefilter'));
    });
    
    function addOptionToArr(arr, val, newOption){
        for (var i=0;i<arr.length;i++) {
            if(val > arr[i].text)
                continue;
            if(val <= arr[i].text) {
                arr.add(newOption, arr[i]);
                break;
            }
        }
    }
    
    jQuery.fn.filterByText = function (textbox) {
        return this.each(function () {
            var select = this;
            var options = [];
            $(select).find('option').each(function () {
                options.push({value: $(this).val(), text: $(this).text()});
            });
            $(select).data('options', options);

            $(textbox).bind('change keyup', function () {
                var options = $(select).empty().data('options');
                var search = $.trim($(this).val());
                var regex = new RegExp(search, "gi");
                $.each(options, function (i) {
                    var option = options[i];
                    if (option.text.match(regex) !== null) {
                        $(select).append(
                                $('<option>').text(option.text).val(option.value)
                                );
                    }
                });
            });
        });
    };

});