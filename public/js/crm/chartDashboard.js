// JavaScript Document

//var chart = null;	

function chartDash(type) {
	var width;
	$.getScript("http://mobisteinreport.com/js/crm/chartLegend.js", function() {
		var data = myValues(type);

		var chartDash = document.getElementById('chartDashboard').getContext('2d');
		
		if(chart != null)
			chart.destroy();
		
		
		
		if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
			if(type != 'CR') {
				chart = new Chart(chartDash).Line(data, {
					scaleGridLineColor : "#E2E2E2",
					responsive: true
				});
			} else {
				chart = new Chart(chartDash).Line(data, {
					tooltipTemplate: "<%if (label){%><%=label %>: <%}%><%= value + ' %' %>",
					multiTooltipTemplate: "<%= value + ' %' %>",
					scaleGridLineColor : "#E2E2E2",
					scaleLabel: function (valuePayload) {
							return Number(valuePayload.value).toFixed(2).replace('.',',') + ' %';
						},
					responsive: true
				});	
			}
			
			width = $("#chartDashboard").css("width").replace(/[^-\d\.]/g, '');
			infinitesize();
			
		} else {
			
			if(type != 'CR') {
				chart = new Chart(chartDash).Line(data, {
					scaleGridLineColor : "#E2E2E2"
				});
			} else {
				chart = new Chart(chartDash).Line(data, {
					tooltipTemplate: "<%if (label){%><%=label %>: <%}%><%= value + ' %' %>",
					multiTooltipTemplate: "<%= value + ' %' %>",
					scaleGridLineColor : "#E2E2E2",
					scaleLabel: function (valuePayload) {
							return Number(valuePayload.value).toFixed(2).replace('.',',') + ' %';
						}
				});	
			}
		}



	});
	
	function infinitesize() {
		$("#chartDashboard").css("width", width - 45 + "px");
		window.setTimeout(function() { infinitesize() }, 10)
	}
}




