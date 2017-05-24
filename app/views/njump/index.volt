{% extends "/headfooter.volt" %}
{% block title %}<title>NJump</title>{% endblock %}
{% block scriptimport %}    
	
	<script src="/js/njump/checkMobile.js"></script>

	<link href="/css/chosen.min.css" rel="stylesheet"/>

	<script src="/js/njump/index.js"></script>

	<script src="/js/njump/searchbox.js"></script>
	<script src="/js/njump/chosen.jquery.min.js"></script>

	<script src="/js/njump/clipboard.min.js"></script>

	<link href="/css/njump.css" rel="stylesheet"/>
	<link href="/css/simple-sidebar.css" rel="stylesheet"/>

	<script src="/js/njump/notify.js"></script>

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
      <div style="margin-top: -50px;" id="wrapper">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav" style="margin: 20px;">
                <div class="row" style="height: 40px;"> 
		          	<div class="col-md-12">
		          		<span style="float: right;" class="showclose" id="close"><img id="imgcloseIcon" class="imgShowclose" src="/img/leftjump.svg"><span id="closestring">CLOSE</span></span>
		          		<span class="showclose" id="open"><img class="imgShowclose" src="/img/rightjump.svg"></span>
		          	</div>
		        </div>
		        <div class="row" id="containerchosen">
		          	<div class="col-md-6">
		        		<select id="countrySideBar" data-placeholder="Country" style="width: 350px; display: none;" class="chosen-select-deselect" tabindex="-1">
  <option></option>
 
						</select>
          			</div>
          			<div class="col-md-6">
		        		<select id="sourcesSideBar" data-placeholder="Source" style="width: 350px; display: none;" class="chosen-select-deselect" tabindex="-1">
  <option></option>
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
			            <ul id="myUL2">
			              	<li><a id="firstrowresults" class="header" style="    border-radius: 5px;color: grey; font-weight: lighter;" align="center" href="#">- no filter applied -</a></li>
			            </ul>
			            <ul style="display: none;" id="spinner">
			              	<li><a class="header" style="color: grey; font-weight: lighter;font-weight: 900;" align="center" href="#">- searching -</a></li>
			            </ul>
			            <div  style="display: none;"  class="scrollbar"  id="style-1">
				            <ul id="myUL">
				              		
				            </ul>
			            </div>
		  			</div>
		  		</div>
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->





        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
            	<div class="row">
            		<span id="moreinfo"><img data-toggle="popover" title="" data-html="true" data-placement="bottom" data-content="<b>CTRL+ALT+Z</b> - show/hide sidebar<br><b>CTRL+ALT+X</b> - new njump" data-original-title="" style="width: 26px;float: right;margin-right: 53px;" id="moreinfoIcon" src="/img/njumppage/info_com_perninha.svg"></span>
            	</div>
            	<div class="row" style="margin-bottom: 30px;">
					<span id="spanNjump"><img id="newnjumpimg" src="/img/njumppage/+Njump.svg"></span>
                </div>
                <div class="row" id="ObjectsRow">
					<div class="col-lg-3"><div class="objectfavorite" country="MX" namenjump="mxsexo-2"><div class="row"><div class="topDiv"><div class="row"><div class="njumpgeneratednameDivObject" data-toggle="popover" title="" data-placement="top" data-content="mxsexo-2" data-original-title="">mxsexo-2</div><div style="background-color:lime" class="statusDivObject"><div style="height: 17px;width: 18px;" data-toggle="popover" data-placement="top" title="" data-content="null" data-original-title=""></div><img class="starObject" favoritetype="1" favorite="52e28ceaca66a" src="/img/njumppage/starpower_filled.svg"></div></div><div class="row"><div class="globalnameDivObject" data-toggle="popover" data-placement="top" title="" data-content="mxsexo" data-original-title="">mxsexo</div></div></div></div>

					<div class="row" style="margin-top: 13px;">
						<div class="col-lg-2 colTableLastToday" style="margin-top: 32px;padding-left: 0px;">
							<table>
								<tr>
									<td></td>
								</tr>
								<tr>
									<td>REVENUE</td>
								</tr>
								<tr>
									<td>CLICKS</td>
								</tr>
								<tr>
									<td>EPC</td>
								</tr>
							</table>
						</div>
						<div class="col-lg-10 colTableLastToday" style="display: -webkit-inline-box;padding-left: 21px;">
							<table class="tablelasttoday">
								<tr>
									<td>TODAY</td>
								</tr>
								<tr>
									<td>456$</td>
								</tr>
								<tr>
									<td>100000</td>
								</tr>
								<tr>
									<td>345.33</td>
								</tr>
							</table>
							<table  class="tablelasttoday">
								<tr>
									<td>LAST 3 DAYS</td>
								</tr>
								<tr>
									<td>12300$</td>
								</tr>
								<tr>
									<td>456</td>
								</tr>
								<tr>
									<td>345.33</td>
								</tr>
							</table>
						</div>
						
					</div>


					<div class="row" id="rowTopWorst">
						<section class="sectionWT"><div class="topWorst">TOP</div><div class="offersWorstTp">asdasdasdasdasdsadsadsadasdsadasdsadasdasdasd</div></section>
						
						<section style="margin-top: -17px;" class="sectionWT"><div class="topWorst">WORST</div><div class="offersWorstTp">asdsadsadsadsadasdasdasdasdassaasdasdasdsad</div></section>
					</div>




					<div class="row"><div class="divIconsObject"><img class="iconsDivObject deleteNjump" src="/img/njumppage/trash.svg"><img class="iconsDivObject cloneNjumpAction" src="/img/njumppage/clone.svg"><img class="iconsDivObject" src="/img/njumppage/edit.svg"><img class="iconsDivObject copyTo" data-clipboard-text="BLABLA" src="/img/njumppage/copy.svg"></div></div></div></div>
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->






    </div>
    <!-- /#wrapper -->

	<!-- Modal -->
	<div class="modal fade" id="modalCreateNJump" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" class="modal hide fade" data-keyboard="false" data-backdrop="static">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">NEW NJump</h4>
	      </div>
	      <div class="modal-body">
	        	<div class="row">
	        		<div class="col-md-12" style="margin-bottom: 20px;">
	        			<select id="countryNewModal" data-placeholder="Country *" style="width: 350px; display: none;" class="chosen-select-deselect2" tabindex="-1">
  							<option></option>
 
						</select>
	        		</div>
	        	</div>
	        	<div class="row">
	        		<div class="col-md-12" style="margin-bottom: 20px;">
	        			<select id="sourceNewModal" data-placeholder="Source *" style="width: 350px; display: none;" class="chosen-select-deselect2" tabindex="-1">
  							<option></option>
 
						</select>
	        		</div>
	        	</div>
	        	<div id="rowAreaModalNew" class="row">
	        		<div class="col-md-12" style="margin-bottom: 20px;">
	        			<select id="areaNewModal" data-placeholder="Area *" style="width: 350px; display: none;" class="chosen-select-deselect2" tabindex="-1">
  							<option></option>
 
						</select>
	        		</div>
	        	</div>
	        	<div class="row">
	        		<div class="col-md-12">
	        			<input type="text" id="nameNewModal" placeholder="Name *" title="">
	        		</div>
	        	</div>
	      </div>
	      <div class="modal-footer">
	        	<button id="okModalButtonSave" type="button" class="btn btn-success">OK</button>
	      </div>
	    </div>
	  </div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="modalCloneNJump" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" class="modal hide fade" data-keyboard="false" data-backdrop="static">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">CLONE NJump</h4>
	      </div>
	      <div class="modal-body">
	        	<div class="row">
	        		<div class="col-md-12" style="margin-bottom: 20px;">
	        			<select id="countryCloneModal" data-placeholder="Country *" style="width: 350px; display: none;" class="chosen-select-deselect3" tabindex="-1">
  							<option></option>
 
						</select>
	        		</div>
	        	</div>
	        	<div class="row">
	        		<div class="col-md-12" style="margin-bottom: 20px;">
	        			<select id="sourceCloneModal" data-placeholder="Source *" style="width: 350px; display: none;" class="chosen-select-deselect3" tabindex="-1">
  							<option></option>
 
						</select>
	        		</div>
	        	</div>
	        	<div id="rowAreaModalClone" class="row">
	        		<div class="col-md-12" style="margin-bottom: 20px;">
	        			<select id="areaCloneModal" data-placeholder="Area *" style="width: 350px; display: none;" class="chosen-select-deselect3" tabindex="-1">
  							<option></option>
 
						</select>
	        		</div>
	        	</div>
	        	<div class="row">
	        		<div class="col-md-12">
	        			<input type="text" id="nameCloneModal" placeholder="Name *" title="">
	        		</div>
	        	</div>
	      </div>
	      <div class="modal-footer">
	        	<button id="okModalButtonClone" type="button" class="btn btn-warning">OK</button>
	      </div>
	    </div>
	  </div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="modalDeleteNJump" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" class="modal hide fade" data-keyboard="false" data-backdrop="static">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	     
	      <div class="modal-body">
	        	<div class="row">
	        		<div class="col-md-12" style="margin-bottom: 20px;">
	        			<p id="modalBodyTextDelete">Are you sure <br>you want to delete?</p>
	        		</div>
	        	</div>
	        	<div class="row">
	        		<div class="col-md-12" style="margin-bottom: 20px; text-align: center;">
	        			<span id="nameNjumpDelete"></span>
	        		</div>
	        	</div>
	      </div>
	      <div class="modal-footer">
	        	<button id="deleteModalButton" type="button" class="btn btn-danger buttonsModalDelete" >DELETE</button>
	        	<button id="deleteModalCancel" type="button" class="btn btn-default buttonsModalDelete" data-dismiss="modal" aria-label="Close">CANCEL</button>
	      </div>
	    </div>
	  </div>
	</div>

	<script>
		var njumps = <?php echo (isset($njumps)) ? $njumps : "'';"; ?>	
		var sourcesvar = <?php echo (isset($sourcesvar)) ? $sourcesvar : "'';"; ?>	
		var countriesvar = <?php echo (isset($countriesvar)) ? $countriesvar : "'';"; ?>		
		var areasvar = <?php echo (isset($areasvar)) ? $areasvar : "'';"; ?>		
	</script>
    <script> 
    	$(document).ready(function(){

    		//$('#myUL').mousedown(function(e){if(e.button==1)return false});
    		

	    	$("#style-1").height($( "#sidebar-wrapper" ).height() - 265);

		    $( window ).resize(function() {
		    	$("#style-1").height($( "#sidebar-wrapper" ).height() - 265);
		    });

		    $(".showclose").click(function(e) {
		        e.preventDefault();
		        
		        hideShowSideBar();
		    });

		    function hideShowSideBar() {
		    	if(!$("#wrapper").hasClass("toggled")) {
		        	$("#rowbottom").fadeOut();
		        	$("#close").hide();
		        	$("#closestring").hide();
		        	$("#containerchosen").fadeOut();
		        }

		        setTimeout(function(){ 
		        	$("#wrapper").toggleClass("toggled");
		        	$("#open").show();
		        }, 200);

	        	if($("#wrapper").hasClass("toggled")) {
	        		setTimeout(function(){ 
			        	$("#rowbottom").fadeIn();
			        	$("#open").hide();
			        	$("#close").fadeIn();
			        	$("#closestring").show();
			        	$("#containerchosen").fadeIn();
		        	}, 400);
		        } 
		    }

	    	$(document).keydown(function(evt){
			    if (evt.keyCode==90 && (evt.ctrlKey) && (evt.altKey)){
			        evt.preventDefault();
			        //CTRL+ALT+Z
			        hideShowSideBar()
			    } else if(evt.keyCode==88 && (evt.ctrlKey) && (evt.altKey)) {
			    	evt.preventDefault();
			    	//CTRL+ALT+X
			    	createNewNjump()
			    } 
			});

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
			    'background-size': '27px 60px'
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

		$(document).keypress(function(e) {
		    if(e.which == 13) {
		    	if($('#modalCreateNJump').is(':visible')) {
		    		$("#okModalButtonSave").click();
		    	} else if($('#modalCloneNJump').is(':visible')) {
		    		$("#okModalButtonClone").click();
		    	} else if($('#modalTimeLine').is(':visible')) {
		    		$("#SaveTimePick").click();
		    	}
		    }
		});
    </script>
  
	
{% endblock %}
{% block simplescript %}
{% endblock %}