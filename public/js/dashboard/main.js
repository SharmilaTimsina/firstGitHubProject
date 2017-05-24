
/*
var arrayDates = [];
var arrayClicks = [];
var arrayConversions = [];
var arrayCR = [];
var arrayCPA = [];

dashboardChart();
  

function dashboardChart() {	
	
	$.ajax({
		url: "http://mobisteinreport.com/dashboard/last7Days",
		dataType: "text",
		success: function(data) {
	
			var json = $.parseJSON(data);
					
			for (var i = 0; i < json.length; i++) { 
				//chart
				if(arrayDates.length <= 8) {	
					var contentClicks = json[i].clicks;	
					var contentConversions = json[i].conversions;	
					var contentCR = json[i].cr;	
					var contentCPA = json[i].revenue;	
					var date = json[i].date;
					var resDate = date.substring(5, 10);
					
					arrayDates.push(resDate);
					arrayClicks.push(contentClicks);
					arrayConversions.push(contentConversions);
					arrayCR.push(Number(contentCR).toFixed(2));
					arrayCPA.push(contentCPA);	
		
				}				
			}
		
			$.getScript("http://mobisteinreport.com/js/dashboard/chartDashboard.js", function(){
				chartDash('REVENUE');
			});
		}
	});	
	
	
	
	$.ajax({
		url: "http://mobisteinreport.com/dashboard/percentageShift",
		dataType: "text",
		success: function(data) {
	
			var json = $.parseJSON(data);
			
			$("#r3c1").text(json[0]['pshift']['revenue'] + "%");
			$("#r3c2").text(json[0]['pshift']['clicks'] + "%");
			$("#r3c3").text(json[0]['pshift']['epc'] + "%");
			
			$("#r1c1").text(json[0]['today']['revenue']);
			$("#r1c2").text(json[0]['today']['clicks']);
			$("#r1c3").text(json[0]['today']['epc']);
			
			$("#r2c1").text(json[0]['last3days']['revenue']);
			$("#r2c2").text(json[0]['last3days']['clicks']);
			$("#r2c3").text(json[0]['last3days']['epc']);
		}
	});	

	$(document).ajaxStop(function() {
		$('.greybox').show();
		$('.a').show();
	});
}

/*
function todayTable(day) {
	var todayRevenue = day.revenue;
	var todayClicks = day.clicks;
	var todayEPC = (todayRevenue / todayClicks).toFixed(3);
	
	$("#r1c1").text(todayRevenue);
	$("#r1c2").text(todayClicks);
	$("#r1c3").text(todayEPC);
		
}

function setLast3DaysTable(day1, day2, day3) {
	var last3daysRevenue = ((Number(day1.revenue) + Number(day2.revenue) + Number(day3.revenue)) / 3).toFixed(3);
	var last3daysClicks = ((Number(day1.clicks) + Number(day2.clicks) + Number(day3.clicks)) / 3).toFixed(3);
	var last3daysEPC = (last3daysRevenue / last3daysClicks).toFixed(3);
	
	$("#r2c1").text(last3daysRevenue);
	$("#r2c2").text(last3daysClicks);
	$("#r2c3").text(last3daysEPC);
}


function percentageShift() {
	percentageRevenue = (last3daysRevenue * 100) / todayRevenue;
	percentageClicks = (last3daysClicks * 100) / todayClicks;
	percentageEPC = (last3daysEPC * 100) / todayEPC;
	
	
	$("#r3c1").text(100 - percentageRevenue);
	$("#r3c2").text(100 - percentageClicks);
	$("#r3c3").text(100 - percentageEPC);
}


jQuery( document ).ready(function( $ ) {
  
	res();
	
	function res () {
		var width = $("#chartDashboard").css("width").replace(/[^-\d\.]/g, '');
		var height = $("#chartDashboard").css("height").replace(/[^-\d\.]/g, '');


		$("#chartDashboard").css("width", width - 100 + "px");
		$("#chartDashboard").css("height", height - 100 + "px");
		
	}	

});
*/


 