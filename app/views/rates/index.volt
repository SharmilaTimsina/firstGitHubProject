{% extends "/headfooter.volt" %}
{% block title %}<title>Exchange Rates</title>{% endblock %}
{% block scriptimport %}    
	<script src="/js/dashboard/main.js"></script>
	<link rel="stylesheet" type="text/css" href="/css/dashboard.css" />

	<script src="/js/dashboard/chart.min.js"></script>	
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
				
		<div class="col-md-6 greybox">
      		<h1 class="titleGreyBox">CURRENCY RATE</h1>
      		<table id="tableDash2" width="100%" border="0">
      			<thead>
      				<tr>
      					<td class="colDash2">Currency</td>
      					<td class="colDash2">Daily average</td>
      					<td class="colDash2">Last 7 days average</td>
      					<td class="colDash2">Month average</td>
      				</tr>
      			</thead>
      			<tbody>
      				<tr class="lineTable">
      					<td class="colDash3">EUR - USD</td>
      					<td id="t01"></td>
      					<td id="t02"></td>
      					<td id="t03"></td>
      				</tr>
      				<tr class="lineTable">
      					<td class="colDash3">BRL - USD</td>
      					<td id="t11"></td>
      					<td id="t12"></td>
      					<td id="t13"></td>
      				</tr>
      				<tr class="lineTable">
      					<td class="colDash3">GBP - USD</td>
      					<td id="t21"></td>
      					<td id="t22"></td>
      					<td id="t23"></td>
      				</tr>
      				<tr class="lineTable">
      					<td class="colDash3">MXN - USD</td>
      					<td id="t31"></td>
      					<td id="t32"></td>
      					<td id="t33"></td>
      				</tr>
      			</tbody>
      		</table>
      		 <div class="row">
			<div class="panel-body">
				<div class="col-md-12">
					<label class="beta blue"></label>
						<button title = "browse selected filter" id="browseit5" name="action" value="statRequest" class="btn btn-primary" onclick='getUrl()'>Download Rate History</button><br>
						<input checked style="margin-left: 8px;" id="eur" class="currencyType" type="checkbox" value=""> EUR
						<input checked id="brl" class="currencyType" type="checkbox" value=""> BRL
						<input checked id="gbp" class="currencyType" type="checkbox" value=""> GBP
						<input checked id="mxn" class="currencyType" type="checkbox" value=""> MXN
						<p style="    margin-top: 0px;" class="pLabelComments">*last 3 months</p>
						<p class="pLabelComments">*accurate results since 2016-10-20</p>
				</div>
			</div>
		</div>
      	</div>


	<script>
		
		function getUrl() {
			window.location='/rates/getRateThreeMonths?eur=' + $("#eur").is(':checked') + '&brl=' + $("#brl").is(':checked') + '&gbp=' + $("#gbp").is(':checked') + '&mxn=' + $("#mxn").is(':checked');
		}

		
		$('.currencyType').change(function() {
			if(!$('.currencyType').is(':checked')) {
				$("#browseit5").attr('disabled', true);
			} else {
				$("#browseit5").attr('disabled', false);
			}
		});

		$('.greybox').show();

		var json3 =  <?php echo $rateInfo ?>;

		var today = json3['today'];
		var month = json3['month'];
		var week = json3['week'];

		for (var i = 0; i < today.length; i++) { 
			if(today[i]['currency'] == 'EUR') {
				$("#t01").text(today[i]['rate']);
			} else if(today[i]['currency'] == 'BRL') {
				$("#t11").text(today[i]['rate']);
			} else if(today[i]['currency'] == 'GBP') {
				$("#t21").text(today[i]['rate']);
			} else if(today[i]['currency'] == 'MXN') {
				$("#t31").text(today[i]['rate']);
			}
		}

		for (var i = 0; i < month.length; i++) { 
			if(today[i]['currency'] == 'EUR') {
				$("#t03").text(month[i]['rate']);
			} else if(today[i]['currency'] == 'BRL') {
				$("#t13").text(month[i]['rate']);
			} else if(today[i]['currency'] == 'GBP') {
				$("#t23").text(month[i]['rate']);
			} else if(today[i]['currency'] == 'MXN') {
				$("#t33").text(month[i]['rate']);
			}
		}

		for (var i = 0; i < week.length; i++) { 
			if(today[i]['currency'] == 'EUR') {
				$("#t02").text(week[i]['rate']);
			} else if(today[i]['currency'] == 'BRL') {
				$("#t12").text(week[i]['rate']);
			} else if(today[i]['currency'] == 'GBP') {
				$("#t22").text(week[i]['rate']);
			} else if(today[i]['currency'] == 'MXN') {
				$("#t32").text(week[i]['rate']);
			}
		}

		
		
		
	
	</script>
		</div>
	</div>

{% endblock %}
{% block simplescript %}
{% endblock %}