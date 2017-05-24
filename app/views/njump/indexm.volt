{% extends "/headfooter.volt" %}
{% block title %}<title>NJump</title>{% endblock %}
{% block scriptimport %}    
	
	<script src="/js/njump/indexm_v1_1.js"></script>

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
		          	<div class="col-xs-6">
		          		<select id="countrySideBar" class="selectsMultiPleSearch">
		          			<option value="">Country</option>
		          	
		          		</select>
		          	</div>
		          	<div class="col-xs-6">
		          		<select id="sourcesSideBar" class="selectsMultiPleSearch">
		          			<option value="">Source</option>
		          			
		          		</select>
		          	</div>
		        </div>
		        <div id="rowbottom" class="row">
		          	<div class="col-md-12">
			            <input type="text" id="myInput" placeholder="Search ..." title="Type in a name">
					    <div class="row" style="height: 40px;"> 
				          	<div class="col-md-12">
				          		<button id="resetFilters" type="button" class="btn btn-warning" >RESET</button>
				          	</div>
				        </div>
			            <ul style="display: none;"   id="myUL2">
			              	<li><a id="firstrowresults" class="header" style="    border-radius: 5px;color: grey; font-weight: lighter;" align="center" href="#">- no filter applied -</a></li>
			            </ul>
			            <ul style="display: none;" id="spinner">
			              	<li><a class="header" style="color: grey; font-weight: lighter;font-weight: 900;" align="center" href="#">- searching -</a></li>
			            </ul>
			            <div  class="scrollbar"  id="style-1">
				            <ul id="myUL">
				              	<?php echo (isset($njumps)) ? $njumps : "'';"; ?>	
				            </ul>
			            </div>
		  			</div>
		  		</div>

            </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>
   





	<script>
		var sourcesvar = <?php echo (isset($sourcesvar)) ? $sourcesvar : "'';"; ?>	
		var countriesvar = <?php echo (isset($countriesvar)) ? $countriesvar : "'';"; ?>		
	</script>

    <script> 
    	$(document).ready(function(){

            if ($('#myUL').html().trim() == ''){
            	$("#style-1").hide();
            	$("#myUL2").show()
           	} else {
           		$( ".resultsSearch" ).bind( "click", function(event) {
	                firechange(this);
	            });
           	}

    		$(".footer").remove();

		  	$('#myInput').searchbox({
			  url: '/njump/getNjumps',
			  param: 'searchquery',
			  dom_id: "#myUL",
			  delay: 500
			})

	    	$('[data-toggle="popover"]').popover({ 
	    		trigger: "hover"
	    	});
	    });

	    function firechange(e) {
			$('#selectedFocus').attr('id', '');
	    	$('body').removeClass('selectedResultSearch');
	    	var styles = {
		      backgroundColor : "#f6f6f6",
		      color: "grey"
		    };
		    $('#myUL a').css( styles );
		    $('#myUL a').removeClass('selectedResultSearch')
		   	$('#myUL a').removeClass('iconLiNjumps');

		   	var styles2 = {
		    	'background-image': '',
			    'background-repeat': 'no-repeat',
			    'background-position': 'calc(100% - 13px)',
			    'background-size': '19px 60px'
		    }
		    $( "#myUL a" ).css( styles2 );

		    $(e).addClass('selectedResultSearch');

		    var styles = {
		      backgroundColor : "mediumseagreen",
		      color: "white"
		    };
		    $( e ).css( styles );

		    var styles2 = {
		    	'background-image': 'url(/img/njumppage/selected.svg)',
			    'background-repeat': 'no-repeat',
			    'background-position': 'calc(100% - 13px)',
			    'background-size': '19px 60px'
		    }
		    $( e ).css( styles2 );
		    $( e ).attr('id', 'selectedFocus');
		}
    </script>
  
	
{% endblock %}
{% block simplescript %}
{% endblock %}