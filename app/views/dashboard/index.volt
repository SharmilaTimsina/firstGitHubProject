{% extends "/headfooter.volt" %}
{% block title %}<title>Dashboard</title>{% endblock %}
{% block scriptimport %}    
	<script src="/js/dashboard/main.js"></script>
	<link rel="stylesheet" type="text/css" href="/css/dashboard.css" />

	<script src="/js/dashboard/chart.min.js"></script>	
{% endblock %}
{% block preloader %}
	<?php date_default_timezone_set('Europe/Lisbon');?> 
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div>
{% endblock %}
{% block content %}
    <div id="wrap">
        <div class="container">
			<div class="row">
				
					<div id="greybox1" class="col-md-6 greybox">
      <h1 class="titleGreyBox">LAST 7 DAYS + TODAY</h1>
      <div class="row">
		<div>
			<canvas id="chartDashboard" width="552" height="220"></canvas>
		</div>
      </div>
      <div class="row">
        <p class="circle" id="circleClicks" onclick="chartDash('CLICKS')"></p>
        <p class="graphicsLabel" id="clickGraph" onclick="chartDash('CLICKS')">CLICKS</p>
        <p class="circle" id="circleConversions" onclick="chartDash('CONVERSIONS')"></p>
        <p class="graphicsLabel" id="conversionsGraph" onclick="chartDash('CONVERSIONS')">CONVERSIONS</p>
        <p class="circle" id="circleCr" onclick="chartDash('CR')"></p>
        <p class="graphicsLabel" id="crGraph" onclick="chartDash('CR')">CR (%)</p>
        <p class="circle" id="circleRevenue" onclick="chartDash('REVENUE')"></p>
        <p class="graphicsLabel" id="reveneuGraph" onclick="chartDash('REVENUE')">REVENUE</p>
      </div>
    </div>
    <div class="col-md-6 greybox">
      <h1 class="titleGreyBox">OVERVIEW</h1>
      <table id="tableDash" width="100%" border="0">
        <tbody>
          <tr>
            <th class="colDash" scope="col">&nbsp;</th>
            <th class="colDash" scope="col">REVENUE (USD)</th>
            <th class="colDash" scope="col">CLICKS</th>
            <th class="colDash" scope="col">EPC</th>
          </tr>
          <tr class="lineTable">
            <th class="colLeft" scope="row">TODAY</th>
            <td id="r1c1">&nbsp;00</td>
            <td id="r1c2">&nbsp;00</td>
            <td id="r1c3">&nbsp;00</td>
          </tr>
          <tr class="lineTable">
            <th class="colLeft" scope="row">LAST 3 DAYS</th>
            <td id="r2c1">&nbsp;00</td>
            <td id="r2c2">&nbsp;00</td>
            <td id="r2c3">&nbsp;00</td>
          </tr>
          <tr class="lineTable">
            <th class="colLeft" scope="row">TREND</th>
            <td id="r3c1">&nbsp;00</td>
            <td id="r3c2">&nbsp;00</td>
            <td id="r3c3">&nbsp;00</td>
          </tr>
        </tbody>
      </table>
	  <p id="plabeldes">* Results based on last hour reports.</p>	
	   <div class="row">
			<div class="panel-body">
				<div class="col-md-2">
					<label class="beta blue"></label>
					
					<?php 
						$type = $this->session->get('auth')['navtype'];
					
						/*if($type == 5) {
							echo '';
						} else {*/
							echo '<button title = "browse selected filter" id="browseit" name="action" value="statRequest" class="btn btn-primary" style="margin-left: 40%;" onclick=' . 'location.href="/report/statistics?fperiod=' . date("Y-m-d",strtotime("-3 days")) . '+to+' . date("Y-m-d",strtotime("-1 day")) . '&speriod=' . date("Y-m-d") . '&hoursStart=00&hoursEnd=' .  date("H",strtotime("-1 hour")) . '&aggFunction=AVG&selectedSourceType=allsourcestypes&action=prev&testing=1"' . '>Browse General Statistics</button>';
						//}
					
						
					?>
				</div>
			</div>
		</div>
    </div>

      	</div>


	<script>
		

		var arrayDates = [];
		var arrayClicks = [];
		var arrayConversions = [];
		var arrayCR = [];
		var arrayCPA = [];
			
		var json2 = <?php echo $tableinfo ?>;
		//var json2 = $.parseJSON(data2);
		
		$("#r3c1").text(json2[0]['pshift']['revenue'] + "%");
		$("#r3c2").text(json2[0]['pshift']['clicks'] + "%");
		$("#r3c3").text(json2[0]['pshift']['epc'] + "%");
		
		$("#r1c1").text(json2[0]['today']['revenue']);
		$("#r1c2").text(json2[0]['today']['clicks']);
		$("#r1c3").text(json2[0]['today']['epc']);
		
		$("#r2c1").text(json2[0]['last3days']['revenue']);
		$("#r2c2").text(json2[0]['last3days']['clicks']);
		$("#r2c3").text(json2[0]['last3days']['epc']);


		var json =  <?php echo $chartinfo ?>;
		//var json = $.parseJSON(data);
					
		for (var i = 0; i < json.length; i++) { 
			//chart
			if(arrayDates.length <= 8) {	
				var contentClicks = json[i].clicks;	
				var contentConversions = json[i].conversions;	
				var contentCR = json[i].cr;	
				var contentCPA = json[i].revenue;	
				var date = json[i].date;
				var resDate = (date == null) ? 0 : date.substring(5, 10);
				
				/*
				if(i == json.length-1) {
					arrayDates.push(resDate);
					arrayClicks.push(json2[0]['today']['clicks'].replace(/,/g, ''));
					arrayConversions.push(contentConversions);
					arrayCR.push(Number(contentCR).toFixed(2));
					arrayCPA.push(json2[0]['today']['revenue'].replace(/,/g, ''));	
				} else {
				*/
					arrayDates.push(resDate);
					arrayClicks.push(contentClicks);
					arrayConversions.push(contentConversions);
					arrayCR.push(Number(contentCR).toFixed(2));
					arrayCPA.push(contentCPA);	
				//}
			}				
		}
	
		$.getScript("/js/dashboard/chartDashboard.js", function(){
			chartDash('REVENUE');
		});
		
		
		$('.greybox').show();

		
		
		
	
	</script>
		</div>
	</div>

{% endblock %}
{% block simplescript %}
{% endblock %}