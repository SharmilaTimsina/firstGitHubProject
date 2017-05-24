{% extends "/headfooter.volt" %}
{% block title %}<title>Preview Results</title>{% endblock %}
{% block preloader %}
<div id="preloader">
    <div id="status">&nbsp;</div>
</div>
{% endblock %}
{% block content %}
    <div id="wrap">
        <div class="container">
            <div class="row">
            <center><div><?php date_default_timezone_set ( 'Europe/Lisbon' ); echo 'Lisbon Time: '.date('Y-m-d H:i:s') ?></div></center>    
            <div class="col-md-1"></div>
            <div class="col-md-10">
               <?php echo $totalTable ?>     
            </div>
            <div class="col-md-1"></div>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-4">
                        <h2 class="sub-header">Country Metrics</h2>
                            <div>
                                <?php echo $countryTable ?>
                            </div>
                    </div>
                    <div class="col-md-1">
                        
                    </div>
                    <div class="col-md-7">
                        <h2 class="sub-header">Detailed Data</h2>
                            <div >
                                <?php echo $table ?>
                            </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
    {% endblock %}
{% block simplescript %}
<script>
   $(document).ready(function () {
        var country = getUrlParameterArray('cc%5B%5D');
        countryRes = '&';
        if (country != null) {
            country.forEach(function (currentValue, index, arr) {
                countryRes += '&country%5B%5D='+currentValue;
            });
        }
       var testing = getUrlParameter('testing');
        $("#lastXdays").on('change', function() {
            $.ajax({
            "url": './getavgdata?day=' + $('#lastXdays').val() +(testing?'&testing=1':'')+countryRes,
            "success": function (json) {
                var miniarr = json.split("~");
                var newtr = '<tr id="pastAvg"><th id="firstColumnAvg" style="color: black; font-weight:normal; font-size:90%">' + miniarr[0] +'</th><th style="color: black; font-weight:normal; font-size:90%">' + miniarr[1].replace(',','') + '</th><th style="color: black; font-weight:normal; font-size:90%">' + miniarr[2].replace(',','') + '</th><th style="color: black; font-weight:normal; font-size:90%">'+ miniarr[3] +'</th><th style="color: black; font-weight:normal; font-size:90%">'+ miniarr[4] + '</th>\n\
                    <th style="color: black; font-weight:normal; font-size:90%">'+ miniarr[5] + '</th></tr>';
                $("#pastAvg").replaceWith(newtr);
                miniarr[3]= miniarr[3].replace(",", "");
                var current =$("#revVar").text().split("|")[0].replace(/\s/g, "");
                var current =current.replace(",", "");
                var sub = (parseFloat(current)-parseFloat(miniarr[3]));
                console.log(sub);
                var div = sub/parseFloat(current);
                console.log(div);
                console.log(div*100);
                console.log((div*100).toFixed(3));
                var result= ((div*100).toFixed(3));
                $("#revVar").text(current + '    |  ' + result + '%' );
            }

        });
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
    });
</script>
{% endblock %}
    