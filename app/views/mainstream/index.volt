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
                <div class="col-md-12"> 
                    <div class="panel-heading">
                        <h3 class="panel-title simple-title">Mainstream Njump Link Generator</h3><a href="./main">- USE ME INSTEAD</a>
                    </div>
                </div>
                <div class="col-md-12"> 
                    <div class="panel-body">
                        <table class="table table-bordered table-hover" id="maintable">
                            <thead> <tr> <th>Domain</th> <th>Njump URL</th> <th>Sources ID</th> <th>Group </th> <th>AD</th> <th>Generated Link</th>  <th> # </th></tr> </thead>
                            <tbody> 
                                <tr id="rowN_1">
                                    <td>
                                        <select id="domainN_1" class="choosedomain" onchange="genURL(this)">
                                            <?php echo $domainlist ?>
                                        </select>
                                    </td>
                                    <td id="tdurlN_1">
                                        <select id="njumpN_1" required="required" onchange="genURL(this)">
                                            <?php echo $njumplist ?>
                                        </select>
                                    </td>
                                    <td id="tdsourceN_1">
                                        <select id="sourceN_1" required="required" onchange="genURL(this)">
                                            <?php echo $sourcelist ?>
                                        </select></td>
                                    <td id="tdgroupN_1" contenteditable="true" onfocusout="genURL(this)">1</td>
                                    <td id="tdadN_1" contenteditable="true" onfocusout="genURL(this)">1</td>
                                    <td id="tdfinalN_1" onchange="genURL(this)">-</td>
                                    <td id="tdbtnN_1" >
                                        <button class="btn btntocopy" id="tdbtncopyN_1" data-clipboard-text="-">Copy ME</button></td>
                                </tr>
                                <tr id="rowN_2">
                                    <td>
                                        <select id="domainN_2" class="choosedomain" onchange="genURL(this)">
                                            <?php echo $domainlist ?>
                                        </select>
                                    </td>
                                    <td id="tdurlN_2">
                                        <select id="njumpN_2" required="required" onchange="genURL(this)">
                                            <?php echo $njumplist ?>
                                        </select>
                                    </td>
                                    <td id="tdsourceN_2">
                                        <select id="sourceN_2" required="required" onchange="genURL(this)">
                                            <?php echo $sourcelist ?>
                                        </select></td>
                                    <td id="tdgroupN_2" contenteditable="true" onfocusout="genURL(this)">1</td>
                                    <td id="tdadN_2" contenteditable="true" onfocusout="genURL(this)">2</td>
                                    <td id="tdfinalN_2" onchange="genURL(this)">-</td>
                                    <td id="tdbtnN_2">
                                        <button id="tdbtncopyN_2" class="btn btntocopy" data-clipboard-text="-">Copy ME</button>
                                        <button id="alineN_2" type="button" class="btn btn-xs btn-primary" onclick="addRow(this)">+</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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
        function genURL(selectObj)
        {
            console.log('genurl selectedobject: ' + selectObj);
            var idname = $(selectObj).attr('id');
            console.log(idname);
            var arr = idname.split("_");
            var rownumber = arr[1];
            console.log("genurl rownumber: " +rownumber);
            var domain = $('#domainN_' + rownumber+' option:selected').val();
            console.log("genurl domain: " +domain);
            var njump = $('#njumpN_' + rownumber+' option:selected').val();
            var source = $('#sourceN_' + rownumber+ ' option:selected').val();
            var group = $('#tdgroupN_' + rownumber).text();
            var ad = $('#tdadN_' + rownumber).text(); 
            var goingtoencode = source + '_' + group + '_' + ad + '_' + njump;
            console.log("genurl newrowid: " + goingtoencode);
            var coded = btoa(goingtoencode);
            var final_result = domain + 'n/?p=' + coded;

            $('#tdfinalN_' + rownumber).text(final_result);
            $('#tdbtncopyN_' + rownumber).attr('data-clipboard-text',final_result);
        }
        
        function addRow(selectObj)
        {
            var idname = $(selectObj).attr('id');
            var arr = idname.split("_");
            var rownumber = arr[1];
            var rowplus1 = String(parseInt(rownumber)+1);
            var newRow = $('#rowN_'+rownumber).clone();
            $(selectObj).remove();
            $(newRow).attr('id','rowN_'+rowplus1);
            console.log("addRow newrowid: " + $(newRow).attr('id'));
            newRow.insertAfter('#rowN_'+rownumber);
            $("#rowN_"+rowplus1 + " #domainN_"+rownumber).attr('id',"domainN_"+rowplus1);
            console.log($("#domainN_"+rowplus1));
            
            $("#rowN_"+rowplus1 + " #tdurlN_"+rownumber).attr('id',"tdurlN_"+rowplus1);
            $("#rowN_"+rowplus1 + " #njumpN_"+rownumber).attr('id',"njumpN_"+rowplus1);
            $("#rowN_"+rowplus1 + " #tdsourceN_"+rownumber).attr('id',"tdsourceN_"+rowplus1);
            $("#rowN_"+rowplus1 + " #sourceN_"+rownumber).attr('id',"sourceN_"+rowplus1);
            $("#rowN_"+rowplus1 + " #tdgroupN_"+rownumber).attr('id',"tdgroupN_"+rowplus1);
            $("#rowN_"+rowplus1 + " #tdadN_"+rownumber).attr('id',"tdadN_"+rowplus1);
            $("#rowN_"+rowplus1 + " #tdfinalN_"+rownumber).attr('id',"tdfinalN_"+rowplus1);
            $("#rowN_"+rowplus1 + " #tdbtnN_"+rownumber).attr('id',"tdbtnN_"+rowplus1);
            $("#rowN_"+rowplus1 + " #tdbtncopyN_"+rownumber).attr('id',"tdbtncopyN_"+rowplus1);
            $("#rowN_"+rowplus1 + " #alineN_"+rownumber).attr('id',"alineN_"+rowplus1);
            
            
            
            var adnumb = parseInt($('#tdadN_' + rowplus1).text());
            if(adnumb < 9 )
                $('#tdadN_' + rowplus1).text(adnumb+1);
            else{
                if(adnumb == 9)
                    adnumb = 0;
                adnumb += 1;
                $('#tdadN_' + rowplus1).text(adnumb);
                var tdgroup =  parseInt($('#tdgroupN_' + rowplus1).text());
                $('#tdgroupN_' + rowplus1).text(tdgroup+1);
            }
            
            
            $('#tdbtncopyN_' + rowplus1).attr('data-clipboard-text','-');
            $('#tdfinalN_' + rowplus1).text('-');
            genURL($('#rowN_'+rowplus1));
            
            
        }
        
        genURL($('#rowN_1'));
        genURL($('#rowN_2'));
        
        
    </script>
{% endblock %}