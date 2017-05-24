// JavaScript Document

//labels charts
var typeN = 0;
$(".graphicsLabel").hover(function(e) { 
	$(this).css("color",e.type === "mouseenter"?"black":"#989898");
	
	if(typeN == 1 || typeN == 0)
		$("#clickGraph").css("color", "black");
	else if(typeN == 2)
		$("#conversionsGraph").css("color", "black");
	else if(typeN == 3)
		$("#crGraph").css("color", "black");
	else if(typeN == 4)
		$("#reveneuGraph").css("color", "black");
	
});

function myValues(type) {
	if (type == "CLICKS") {
		var data = {
		labels : arrayDates,
		datasets : [
				{
				fillColor : "transparent",
				strokeColor : "rgb(42,186,255)",
				pointColor : "rgb(42,186,255)",
				pointStrokeColor : "rgb(42,186,255)",
				data : arrayClicks,
				},
					]
		}	
		
		$("#clickGraph").css("color", "black");
		$("#circleClicks").css("background-color", "rgb(42,186,255)");
		$("#circleConversions").css("background-color", "#989898");
		$("#circleCr").css("background-color", "#989898");
		$("#circleRevenue").css("background-color", "#989898");
		$("#crGraph").css("color", "#989898");
		$("#reveneuGraph").css("color", "#989898");
		$("#conversionsGraph").css("color", "#989898");
		
		typeN = 1;
		
		return data;
	}
	else if(type == "CONVERSIONS") {
		var data = {
		labels : arrayDates,
					
		datasets : [
				{
				fillColor : "transparent",
				strokeColor : "rgb(0,90,212)",
				pointColor : "rgb(0,90,212)",
				pointStrokeColor : "rgb(0,90,212)",
				data : arrayConversions,
				}
					]
		}	
		
		$("#circleClicks").css("background-color", "#989898");
		$("#circleCr").css("background-color", "#989898");
		$("#circleRevenue").css("background-color", "#989898");
		$("#crGraph").css("color", "#989898");
		$("#reveneuGraph").css("color", "#989898");
		$("#clickGraph").css("color", "#989898");
		$("#circleConversions").css("background-color", "rgb(0,90,212)");
		$("#conversionsGraph").css("color", "black");

		typeN = 2;

		return data;	
	}
	else if(type == "CR") {
		var data = {
		labels : arrayDates,
					
		datasets : [
				{
					fillColor : "transparent",
					strokeColor : "rgb(0, 255, 255)",
					pointColor : "rgb(0, 255, 255)",
					pointStrokeColor : "#91A3B0",
					data : arrayCR,
				}
					]
		}
			
		$("#circleClicks").css("background-color", "#989898");
		$("#circleCr").css("background-color", "#00FFFF");
		$("#circleRevenue").css("background-color", "#989898");
		$("#crGraph").css("color", "black");
		$("#reveneuGraph").css("color", "#989898");
		$("#clickGraph").css("color", "#989898");
		$("#circleConversions").css("background-color", "#989898");
		$("#conversionsGraph").css("color", "#989898");
		
		typeN = 3;
		
		return data;	
	}
	else if(type == "REVENUE") {
		var data = {
		labels : arrayDates,
					
		datasets : [
				{
				fillColor : "transparent",
				strokeColor : "#0095B6",
				pointColor : "#0095B6",
				pointStrokeColor : "#0095B6",
				data : arrayCPA,
				}
					]
		
		}
		
		$("#circleClicks").css("background-color", "#989898");
		$("#circleCr").css("background-color", "#989898");
		$("#circleRevenue").css("background-color", "#0095B6");
		$("#crGraph").css("color", "#989898");
		$("#reveneuGraph").css("color", "black");
		$("#clickGraph").css("color", "#989898");
		$("#circleConversions").css("background-color", "#989898");
		$("#conversionsGraph").css("color", "#989898");	
		
		typeN = 4;
		
		return data;	
	}	
}	
			
			