{% extends "/headfooter.volt" %}
{% block title %}<title>NJump</title>{% endblock %}
{% block scriptimport %}    
	
	<script src="/js/njump/njumpeditm.js"></script>

	<script src="/js/njump/searchbox_m.js"></script>

	<script src="/js/njump/notify.js"></script>

	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link href="/css/njumpMobile.css" rel="stylesheet"/>

	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">

{% endblock %}
{% block preloader %}
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div>
{% endblock %}
{% block content %}
    <div id="wrapper">

        <!-- Page Content -->
        <div class="container">
            <div class="row">
				<div class="col-md-12">
            		<a id="resetFilters2" href="/njump/indexm" type="button" class="btn btn-warning">BACK</a>
            	</div>
            </div>
            <div class="row"> 
		      	<div class="col-md-12" style="display: -webkit-inline-box;">
					<img class="iconsDivObject2 favoriteStar" src="/img/njumppage/starpower_full_edit.svg">
					<div id="njumpGaneratedName">in_1-adult_1856 | inpro_1_2_2</div>
		      	</div>
		  	</div>
		  	<div id="topLinesRow" class="row"> 
		      	<div class="col-xs-4">
		      		<div class="statusDivObject" id="statusDivRow"></div>
		      	</div>
		      	<div class="col-xs-4">
		      		<div class="divsInfoRow" id="countryNjumpRow"></div>
		      	</div>
		      	<div class="col-xs-4">
					<div id="sourceInfoRow" class="divsInfoRow"></div>
		      	</div>
		    </div>
		    <div id="DivForLines" style="width: 100%;" class="row"> 
		    	
		    </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>
   
	<script>
		var njumphash = <?php echo (isset($njumphash)) ? $njumphash : "'';"; ?>		
		var carriersvar = <?php echo (isset($carriersvar)) ? $carriersvar : "'';"; ?>		
		var sourcenamevar = <?php echo (isset($sourcenamevar)) ? $sourcenamevar : "'';"; ?>	
		var globalnamevar = <?php echo (isset($globalnamevar)) ? $globalnamevar : "'';"; ?>	
		var njumpgeneratednamevar = <?php echo (isset($njumpgeneratednamevar)) ? $njumpgeneratednamevar : "'';"; ?>	
		var areaname = <?php echo (isset($areaname)) ? $areaname : "'';"; ?>	
		var countryname = <?php echo (isset($countryname)) ? $countryname : "'';"; ?>	
		var njumpsvar = <?php echo (isset($njumpsvar)) ? $njumpsvar : "'';"; ?>	
		var sourcenamevar = <?php echo (isset($sourcenamevar)) ? $sourcenamevar : "'';"; ?>	
		var statusvar = <?php echo (isset($statusvar)) ? $statusvar : "'';"; ?>	
		var favoritevar = <?php echo (isset($favoritevar)) ? $favoritevar : "'';"; ?>	
	</script>
 
{% endblock %}
{% block simplescript %}
{% endblock %}