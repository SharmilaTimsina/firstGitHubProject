{% extends "/headfooter.volt" %}
{% block title %}<title>Mainstream Management</title>{% endblock %}
{% block scriptimport %}    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.3/clipboard.min.js"></script>

    <!-- <script src="http://mobisteinreport.com/js/operation/main.js"></script> -->
{% endblock %}
{% block preloader %}
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div>
{% endblock %}
{% block content %}
    <div id="wrap">
        <div class="container">
            <div class="row">
                <div class="panel-heading">
                    <h3 class="panel-title simple-title">Mainstream Njump Link Generator</h3>
					
                </div>
                <div class="col-md-12"> 
                    <div class="col-md-2">
                        <div class="panel-heading">
                            <h3 class="panel-title simple-title">Choose source</h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <select id="sourceN_1" required="required" >
                                    <?php echo $sourcelist ?>
                                </select>
                            </div>
                        </div>
                    </div>    
                    <div class="col-md-3">
                        <div class="panel-heading">
                            <h3 class="panel-title simple-title">Choose domain</h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <select id="domainN_1" class="choosedomain" >
                                    
                                </select>
                            </div>
                        </div>
                    </div>    
                    <div class="col-md-3">
                        <div class="panel-heading">
                            <h3 class="panel-title simple-title">Choose country</h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <select id="countryN_1" class="choosedomain" >
                                    <?php echo $countries ?>
                                </select>
                            </div>
                        </div>
                    </div>    
                    <div class="col-md-2">
                        <div class="panel-heading">
                            <h3 class="panel-title simple-title">Choose category</h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <select id="categoryN_1" required="required" >
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="panel-heading">
                            <h3 class="panel-title simple-title">Choose njump</h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <select id="njumpN_1" required="required" >
                                    
                                </select>
                            </div>
                        </div>
                    </div>    
                </div>
            </div>
            <div class="row" id="mainstreamhistorydiv">
                <table class="table table-bordered table-hover" id="mainhistorytable" hidden>
                    
                </table>
            </div>
            <div class="row" id="newrowstable">
                <div class="panel-body">
                    <table class="table table-bordered table-hover" id="maintable">
                        <thead> 
                            <tr> <th>Group </th> <th>AD</th> <th>Banner </th> <th>Generated Link</th>  <th> # </th></tr> </thead>
                        <tbody> 
                            <tr id="rowN_1">
                                <td id="tdgroupN_1" contenteditable="true" onfocusout="genURL(this,false)">1</td>
                                <td id="tdadN_1" contenteditable="true" onfocusout="genURL(this,false)">1</td>
                                <td id="tdbannerN_1">
                                    <input type="file" name="fileToUpload" id="fileToUpload_1">
                                </td>
                                <td id="tdfinalN_1">-</td>
                                <td id="tdbtnN_1" >
                                    <button class="btn btntocopy" id="tdbtncopyN_1" data-clipboard-text="-" onclick="saveandcopy(this)">Copy ME</button></td>
                            </tr>
                            <tr id="rowN_2">
                                <td id="tdgroupN_2" contenteditable="true" onfocusout="genURL(this,false)">1</td>
                                <td id="tdadN_2" contenteditable="true" onfocusout="genURL(this,false)">1</td>
                                <td id="tdbannerN_2">
                                    <input type="file" name="fileToUpload" id="fileToUpload_2">
                                </td>
                                <td id="tdfinalN_2" >-</td>
                                <td id="tdbtnN_2" >
                                    <button class="btn btntocopy" id="tdbtncopyN_2" data-clipboard-text="-" onclick="saveandcopy(this)">Copy ME</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>    
        </div>    
    </div>
    <script>
        var clipboard = new Clipboard('.btntocopy');
    </script>
{% endblock %}
{% block simplescript %}
    <script>
        var domainslist = new Object( <?php echo $domainlist; ?>);
        /*
         * 
         function genURL(selectObj)
        {
            console.log('genurl selectedobject: ' + selectObj);
            var idname = $(selectObj).attr('id');
            console.log(idname);
            var arr = idname.split("_");
            var rownumber = arr[1];
            console.log("genurl rownumber: " + rownumber);
            var domain = $('#domainN_' + rownumber + ' option:selected').val();
            console.log("genurl domain: " + domain);
            var njump = $('#njumpN_' + rownumber + ' option:selected').val();
            var source = $('#sourceN_' + rownumber + ' option:selected').val();
            var group = $('#tdgroupN_' + rownumber).text();
            var ad = $('#tdadN_' + rownumber).text();
            var goingtoencode = source + '_' + group + '_' + ad + '_' + njump;
            console.log("genurl newrowid: " + goingtoencode);
            var coded = btoa(goingtoencode);
            var final_result = domain + 'n/?p=' + coded;

            $('#tdfinalN_' + rownumber).text(final_result);
            $('#tdbtncopyN_' + rownumber).attr('data-clipboard-text', final_result);
        }

        function addRow(selectObj)
        {
            var idname = $(selectObj).attr('id');
            var arr = idname.split("_");
            var rownumber = arr[1];
            var rowplus1 = String(parseInt(rownumber) + 1);
            var newRow = $('#rowN_' + rownumber).clone();
            $(selectObj).remove();
            $(newRow).attr('id', 'rowN_' + rowplus1);
            console.log("addRow newrowid: " + $(newRow).attr('id'));
            newRow.insertAfter('#rowN_' + rownumber);
            $("#rowN_" + rowplus1 + " #domainN_" + rownumber).attr('id', "domainN_" + rowplus1);
            console.log($("#domainN_" + rowplus1));

            $("#rowN_" + rowplus1 + " #tdurlN_" + rownumber).attr('id', "tdurlN_" + rowplus1);
            $("#rowN_" + rowplus1 + " #njumpN_" + rownumber).attr('id', "njumpN_" + rowplus1);
            $("#rowN_" + rowplus1 + " #tdsourceN_" + rownumber).attr('id', "tdsourceN_" + rowplus1);
            $("#rowN_" + rowplus1 + " #sourceN_" + rownumber).attr('id', "sourceN_" + rowplus1);
            $("#rowN_" + rowplus1 + " #tdgroupN_" + rownumber).attr('id', "tdgroupN_" + rowplus1);
            $("#rowN_" + rowplus1 + " #tdadN_" + rownumber).attr('id', "tdadN_" + rowplus1);
            $("#rowN_" + rowplus1 + " #tdfinalN_" + rownumber).attr('id', "tdfinalN_" + rowplus1);
            $("#rowN_" + rowplus1 + " #tdbtnN_" + rownumber).attr('id', "tdbtnN_" + rowplus1);
            $("#rowN_" + rowplus1 + " #tdbtncopyN_" + rownumber).attr('id', "tdbtncopyN_" + rowplus1);
            $("#rowN_" + rowplus1 + " #alineN_" + rownumber).attr('id', "alineN_" + rowplus1);



            var adnumb = parseInt($('#tdadN_' + rowplus1).text());
            if (adnumb < 9)
                $('#tdadN_' + rowplus1).text(adnumb + 1);
            else {
                if (adnumb == 9)
                    adnumb = 0;
                adnumb += 1;
                $('#tdadN_' + rowplus1).text(adnumb);
                var tdgroup = parseInt($('#tdgroupN_' + rowplus1).text());
                $('#tdgroupN_' + rowplus1).text(tdgroup + 1);
            }


            $('#tdbtncopyN_' + rowplus1).attr('data-clipboard-text', '-');
            $('#tdfinalN_' + rowplus1).text('-');
            genURL($('#rowN_' + rowplus1));


        }

        genURL($('#rowN_1'));
        genURL($('#rowN_2'));
*/
        var source = null;
        var country = null;
        var domain = null;
        var domainname = null;
        var category = null;
        var njump = null;
        var group = null;
        var ad = null;
        
        $("#newrowstable").hide();
        $("#domainN_1").prop('disabled', true);
        $("#countryN_1").prop('disabled', true);
        $("#categoryN_1").prop('disabled', true);
        $("#njumpN_1").prop('disabled', true);
        
        $("#sourceN_1").on('change', function () {
            if ($("#sourceN_1").val() != '') {
                $("#domainN_1").empty();
                $("#countryN_1").empty();
                $("#categoryN_1").empty();
                $("#njumpN_1").empty();
                $("#domainN_1").append("<option value='' selected='selected'></option>");
                $.each(domainslist,function(index, value){
					eachsource = value.sources.split(",");
                    for(i=0;i<eachsource.length;i++){
						if(eachsource[i] == $("#sourceN_1").val())
							$("#domainN_1").append('<option value="'+value.id+'">'+value.domain+'</option>');
					}
                });
                $("#domainN_1").prop('disabled', false);
            }
            else {
                $("#domainN_1").prop('disabled', true);
            }
        });
        
    
        $("#domainN_1").on('change', function () {
            if ($("#domainN_1").find(":selected").attr('value') != '') {
                $("#categoryN_1").empty();
                $("#njumpN_1").empty();
                source = $("#sourceN_1").find(":selected").attr('value');
                domain = $("#domainN_1").find(":selected").attr('value');
                domainname = $("#domainN_1").find(":selected").text();
                $.get( "./getCountriesWithDomainid/?domainid="+$("#domainN_1").find(":selected").attr('value'), function( data ) {
                    var arr = jQuery.parseJSON(data);
                    if(arr.length > 0){
                        $("#countryN_1").empty();
                        $.each(arr, function( index, value ) {
                            $("#countryN_1").append('<option value="'+value.id+'">'+value.name+'</option>')
                          });
                    }
                });

                $("#countryN_1").val();
                $("#countryN_1").prop('disabled', false);
                $("#newrowstable").hide();
            }
            else {
                $("#countryN_1").prop('disabled', true);
            }
        });
        
        
        $("#countryN_1").on('change', function () {
            if ($("#countryN_1").find(":selected").attr('value') != '') {
                country = $("#countryN_1").find(":selected").attr('value');
                $.get( "./getAllCategories", function( data ) {
                    var arr = jQuery.parseJSON(data);
                    if(arr.length > 0){
                        $("#categoryN_1").empty();
                        $.each(arr, function( index, value ) {
                            $("#categoryN_1").append('<option value="'+value.id+'">'+value.name+'</option>')
                          });
                    }
                });
                var countries = $("#countryN_1").find(":selected").attr('value');
                var request = './getNjumps/?country='+countries+'&source='+source;
                $.get( request, function( data ) {
                    if(data.length > 0){
                        $("#njumpN_1").empty();
                        $("#njumpN_1").append(data);
                          
                    }
                });

                $("#njumpN_1").val();
                $("#njumpN_1").prop('disabled', false);
                $("#categoryN_1").val();
                $("#categoryN_1").prop('disabled', false);
                $("#newrowstable").hide();
            }
            else {
                $("#njumpN_1").prop('disabled', true);
                $("#categoryN_1").prop('disabled', true);
            }
        });
        
        //+((typeof $("#categoryN_1").find(":selected").attr('value') != 'undefined') ? "&category="+$("#categoryN_1").find(":selected").attr('value') : '')
        $("#categoryN_1").on('change', function () {
            category = $("#categoryN_1").find(":selected").attr('value');            
            var countries = ($("#countryN_1").find(":selected").attr('value') != 'undefined' ? $("#countryN_1").find(":selected").attr('value') : '');
            var categories = ((typeof $("#categoryN_1").find(":selected").attr('value') != 'undefined') ? $("#categoryN_1").find(":selected").attr('value') : '');
            var request = './getNjumps/?country='+countries+'&category='+categories+'&source='+source;
            $.get( request, function( data ) {
                if(data.length > 0){
                    $("#njumpN_1").empty();
                    $("#njumpN_1").append(data);
                }
            });

            $("#njumpN_1").val();
            $("#njumpN_1").prop('disabled', false);
            $("#category_1").val();
            $("#category_1").prop('disabled', false);

        });

        $("#njumpN_1").on('change', function () {
            if ($("#njumpN_1").find(":selected").attr('value') != '') {
                njump = $("#njumpN_1").find(":selected").attr('value');
                $("#newrowstable").show();
                $.get( "./getMainstreamHistory?source="+source+"&domain="+domain+"&country="+country+"&njump="+njump, function( data ) {
                    $("#mainhistorytable").empty();
                    $("#mainhistorytable").hide();
                    if(!$.isNumeric(data)){
                        var arr = jQuery.parseJSON(data);
                        


                        $("#mainhistorytable").show();
                        $("#mainhistorytable").append('<thead><tr><th>Group</th><th>AD</th><th>Banner</th><th>Generated Link</th><th> # </th></tr></thead><tbody>');
                        $.each(arr, function( index, value ) {
                            //group ad banner
                            var row = generateMainhistoryRow(index, value);
                            $("#mainhistorytable").append(row);
                            genURL($('#troldid_'+value.id),true);


                        });
                        $("#mainhistorytable").append('</tbody>');
                    }
                    else{
                        //console.log('else'+data);
                        $("#rowN_1 td:nth-child(1)").text(data);
                        $("#rowN_1 td:nth-child(2)").text('1');
                        $("#rowN_2 td:nth-child(1)").text(data);
                        $("#rowN_2 td:nth-child(2)").text('2');
                    }
                    genURL($('#rowN_1'),false);
                    genURL($('#rowN_2'),false);
                });
                
                
            }
            else {
                
            }
        });
        
        function generateMainhistoryRow(index, value){
            var id = value.id;
            var group = value.group;
            var ad = value.ad;
            var banner = '<img src="'+value.banner+'" width="60" height="50"/>';
            grouprow = '<tr id="troldid_'+id+'"><td id="tdgroupid_'+id+'">'+group+'</td>';
            adrow = '<td id="tdadid_'+id+'">'+ad+'</td>';
            bannerrow = '<td id="tdbannerid_'+id+'">'+banner+'</td>';
            finalrow = '<td id="tdfinalid_'+id+'">-</td>';
            tdbtnrow = '<td><button class="btn btntocopy" id="tdbtncopyid_'+id+'" data-clipboard-text="-" onclick="genURL(this, true)">Copy ME</button></td></tr>';
            if(index==0){
                $("#tdgroupN_1").text(group);
                $("#tdadN_1").text(parseInt(ad)+1);
                $("#tdgroupN_2").text(group);
                $("#tdadN_2").text(parseInt(ad)+2);
            }
            return grouprow+adrow+bannerrow+finalrow+tdbtnrow;
        }
        
        function genURL(selectObj,oldurls)
        {
            if(oldurls){
                var idname = $(selectObj).attr('id');
                //console.log(idname);
                var arr = idname.split("_");
                var rownumber = arr[1];
                group = $('#tdgroupid_' + rownumber).text();
                ad = $('#tdadid_' + rownumber).text();
                var goingtoencode = source + '_' + group + '_' + ad + '_' + njump;
                console.log("genurl oldrowid: " + goingtoencode);
                var coded = btoa(goingtoencode);
                var final_result = domainname + 'n/?p=' + coded;
                $('#tdfinalid_' + rownumber).text(final_result);
                $('#tdbtncopyid_' + rownumber).attr('data-clipboard-text', final_result);
            }
            else{
                var idname = $(selectObj).attr('id');
                var arr = idname.split("_");
                var rownumber = arr[1];
                group = $('#tdgroupN_' + rownumber).text();
                ad = $('#tdadN_' + rownumber).text();
                var goingtoencode = source + '_' + group + '_' + ad + '_' + njump;
                console.log("genurl newrowid: " + goingtoencode);
                var coded = btoa(goingtoencode);
                var final_result = domainname + 'n/?p=' + coded;
                $('#tdfinalN_' + rownumber).text(final_result);
                $('#tdbtncopyN_' + rownumber).attr('data-clipboard-text', final_result);
            }
        }
        
        
        function saveandcopy(selectObj)
        {
			source = $("#sourceN_1").find(":selected").attr('value');
			domain = $("#domainN_1").find(":selected").attr('value');
			domainname = $("#domainN_1").find(":selected").text();
			category = $("#categoryN_1").find(":selected").attr('value');
			njump = $("#njumpN_1").find(":selected").attr('value');
			country = $("#countryN_1").find(":selected").attr('value');
            var idname = $(selectObj).attr('id');
            console.log(selectObj);
            var arr = idname.split("_");
            var rownumber = arr[1];
            console.log(rownumber+"rownumber");
            group = $('#tdgroupN_' + rownumber).text();
            ad = $('#tdadN_' + rownumber).text();
            var formData = new FormData();
            console.log('#fileToUpload' + rownumber);
			var filetouploadname = $('#fileToUpload_' + rownumber).val().split('\\').pop();
            console.log(filetouploadname);
			if(filetouploadname != ''){
				var lastChar = filetouploadname.substr(filetouploadname.length - 3);
				if(lastChar != 'tif' && lastChar != 'tiff' && lastChar != 'gif' && lastChar != 'jpeg' && lastChar != 'jpg' && lastChar != 'jif' && lastChar != 'fif' && lastChar != 'png' ){
					alert('open your eyes, ass!');
					return;
				}
			}
            if($('#fileToUpload_' + rownumber).val() != '' ){
                formData.append('file',$('#fileToUpload_' + rownumber).prop('files')[0],$('#fileToUpload_' + rownumber).val().split('\\').pop());
            }
            formData.append('country',country);
            formData.append('domain',domain);
            formData.append('source',source);
            formData.append('group',group);
            formData.append('ad',ad);
            formData.append('njump',njump);
			formData.append('sub_id',source + "_" + group + "_" + ad);
            
            $.ajax({
                type: 'POST',               
                processData: false, // important
                contentType: false, // important
                data: formData,
                url: "./saveNewRow",
                dataType : 'json',  
                // in PHP you can call and process file in the same way as if it was submitted from a form:
                // $_FILES['input_file_name']
                success: function(res){
                    console.log(res);
                    if(res != ''){
                        var arr = [];
                        arr.push(res);
                        console.log('res != ""');
                        
                            
                        
                        $.each(arr, function( index, value ) {
                            //group ad banner
                            var row = generateMainhistoryRow(index, value);
                            
                            if($('#mainhistorytable tr').length == 3){
                                
                                if((parseInt(value.group) == parseInt($("#mainhistorytable tr:nth-child(1) td:nth-child(1)").text())) && (parseInt(value.group) == parseInt($("#mainhistorytable tr:nth-child(1) td:nth-child(1)").text()) && parseInt(value.ad) == parseInt($("#mainhistorytable tr:nth-child(1) td:nth-child(2)").text()))  ){
                                    $('#mainhistorytable tr:first').remove();
                                    $("#mainhistorytable").prepend(row);
                                }
                                else if((parseInt(value.group) < parseInt($("#mainhistorytable tr:nth-child(1) td:nth-child(1)").text())) || (parseInt(value.group) == parseInt($("#mainhistorytable tr:nth-child(1) td:nth-child(1)").text()) && parseInt(value.ad) < parseInt($("#mainhistorytable tr:nth-child(1) td:nth-child(2)").text()))  ){
                                    $('#mainhistorytable tr:last').remove();
                                    $("#mainhistorytable").append(row);
                                }else{
                                    $('#mainhistorytable tr:last').remove();
                                    $("#mainhistorytable").prepend(row);
                                }
                            }
                            else{
                                $("#mainhistorytable").prepend(row);
                            }
                            genURL($('#troldid_'+value.id),true);
                            
                          });
                      }
                        genURL($('#rowN_1'),false);
                        genURL($('#rowN_2'),false);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert('Call IT team.');
                }
                
            }); 
            
        }
    </script>
{% endblock %}