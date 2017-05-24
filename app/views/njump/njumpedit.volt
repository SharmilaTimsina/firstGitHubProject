{% extends "/headfooter.volt" %}
{% block title %}<title>NJump edit</title>{% endblock %}
{% block scriptimport %}    
	
	<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:700|Roboto:400,500,700" rel="stylesheet">

	<script src="/js/njump/checkMobile.js"></script>

	<link href="/css/chosen.min.css" rel="stylesheet"/>

	<script src="/js/njump/njumpedit_v1_3.js"></script>

	<script src="/js/njump/searchbox.js"></script>
	<script src="/js/njump/chosen.jquery.min.js"></script>

	<script src="/js/datepickerjst/moment.min.js"></script>

	<script src="/js/njump/clipboard.min.js"></script>

	<script src="/js/njump/notify.js"></script>

	<script src="/js/njump/timepicker.js"></script>


  	<link href="/css/njumpedit.css" rel="stylesheet"/>
	<link href="/css/simple-sidebar2.css" rel="stylesheet"/>
	<link href="/css/njumptimePick.css" rel="stylesheet"/>
	
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
      <div style="margin-top: -50px;" id="wrapper" >

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
         
            	<div class="row" id="rowButtonsEdits">
					<div class="col-lg-7" style="display: -webkit-inline-box;">					
						<img class="iconsDivObject2 favoriteStar" data-toggle="popover" data-placement="bottom"  title="" data-content="favorite" data-clipboard-text="" src="/img/njumppage/starpower_full_edit.svg">
						<div>
						

						<div id="njumpGaneratedName"><span id="njumpgeneratednamevar"></span> | <span id="globalnamevar"></span><span id="badnjumpgeneratedicon"></span></div>
						
						<div id="njumpContentName" style="margin-top: 10px;display: -webkit-inline-box;"><span id="countrycontentname"></span><span>  |  </span><span id="sourcecontentname"></span><span>  |  </span><span id="categorycontentname"></span><span></span>

						<div style="margin-top: -2px;margin-left: 18px;width: 226px;" class="divsInfoRow">
							<img style="margin-top: 5px;" class="iconsDivObject3 copyTo" data-toggle="popover" data-placement="top" style="color:black;" title="" data-content="copy njump link to clipboard" data-clipboard-text="" src="/img/njumppage/copy.svg">
							<select id="selectsDomainsInfoRow"></select>
						</div>

						</div>


						</div>
					</div>
					<div class="col-lg-5">
						<div class="row" style="margin-top: -36px; margin-bottom: 30px;">
							<span id="moreinfo"><img data-toggle="popover" data-container="body" title="" data-html="true" data-placement="left" data-content="<b>CTRL+ALT+Z</b> - show/hide sidebar<br><b>CTRL+ALT+X</b> - new njump<br><b>CTRL+ALT+A</b> - copy njump url to clipboard" data-original-title="" style="width: 24px;float: right;" id="moreinfoIcon" src="/img/njumppage/info_com_perninha.svg"></span>
						</div>
						<div class="row">
							<div class="col-md-9">
								
								<img class="iconsDivObject2 deleteNjump" style="float: right;" data-toggle="popover" data-placement="bottom"  title="" data-content="delete this njump" data-clipboard-text="" src="/img/njumppage/trash.svg">

								<img class="iconsDivObject2 cloneNjumpAction" style="float: right;" data-toggle="popover" data-placement="bottom"  title="" data-content="clone this njump" data-clipboard-text="" src="/img/njumppage/clone.svg">

								<img class="iconsDivObject5" id="newnjumpimg" data-toggle="popover" data-placement="bottom"  title="" data-content="create new njump" data-clipboard-text="" src="/img/njumppage/+Njump.svg">

							</div>
							<div class="col-md-3" style="display: -webkit-inline-box; margin-top: 7px;margin-left: -13px;padding-left: 0px;">
								<div>
									<img class="iconsDivObject5" id="periodIcon" src="/img/njumppage/time_travel.svg">
								</div>
								<div style="width: 95%;">
									<select id="selectsPeriod">
										<option value="0">Today</option>
										<option value="1">Yesterday</option>
										<option value="2">Last 3 Days</option>
										<option value="3">Last 7 Days</option>
										<option value="4">Last Hour</option>
										<option value="5">Last 3 Hours</option>
										<option value="6">Last 6 Hours</option>
										<option value="7">Last 12 Hours</option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-1">
						
					</div>
					<div class="col-lg-1">
						
					</div>
					<div class="col-lg-8">

					</div>
					
				</div>
            	<div class="row" id="ObjectsRow">
					<div class="col-lg-12">
						<div class="row" id="editNjumpTableTopRow">
							<div class="col-lg-2" style="height: 0%;">
								<input id="selectAllCheckBox" class="checkBoxSelectLine2" type="checkbox">
								<div class="statusDivObject" id="statusDivRow"></div>
								<div class="divExpandAll"  data-toggle="popover" data-placement="top" style="color:black;" title="" data-content="expand all" data-clipboard-text=""></div>
								<img id="deleteSelectedModal" class="iconsDivObject99 disabled" data-toggle="popover" data-placement="top"  title="" data-content="delete selected" data-clipboard-text="" src="/img/njumppage/delete_selected_red.svg">
								<img id="copytoSelectedModal" class="iconsDivObject99 disabled" data-toggle="popover" data-placement="top"  title="" data-content="copy selected to" data-clipboard-text="" src="/img/njumppage/clone_to_newNjump_yellow.svg">

								<img id="reloadSorter" class="iconsDivObject101" data-toggle="popover" data-placement="top"  title="" data-content="reload sorter" data-clipboard-text="" src="/img/njumppage/mentos_green2.svg">
							</div>
							
							<div style="height: 100%;" class="col-lg-10">
								<div style="height: 100%;margin-top: 0.5%;" class="row">
									<div class="col-lg-3">
										<span class="sorterTiles">OFFER<img orderT="ASC" type="5" class="sortable" src="/img/iconssort/sort_both.png"></span>
									</div>
									<div style="padding-left: 0px;" class="col-lg-2">
										<span class="sorterTiles">PRE-LANDING PAGE<img orderT="ASC" type="6" class="sortable" src="/img/iconssort/sort_both.png"></span>
									</div>
									<div style="padding-left: 0px;" class="col-lg-2">
										<span class="sorterTiles">CARRIER<img orderT="ASC" type="0" class="sortable" src="/img/iconssort/sort_asc.png"></span></span>
									</div>
									<div style="padding-left: 0px;" class="col-lg-2">
										<span class="sorterTiles">PROPORTION<img orderT="ASC" type="4" class="sortable" src="/img/iconssort/sort_both.png"></span></span>
									</div>
									
									<div style="padding-left: 0px;" class="col-lg-1">
										<span class="sorterTiles">%<img orderT="ASC" type="4" class="sortable" src="/img/iconssort/sort_both.png"></span></span>
									</div>
									<div class="col-lg-1">
										<span class="sorterTiles">EPC<img orderT="ASC" type="3" class="sortable" src="/img/iconssort/sort_both.png"></span></span>
									</div>
									<div class="col-lg-1">
										<span class="sorterTiles">CLICKS<img orderT="ASC" type="2" class="sortable" src="/img/iconssort/sort_both.png"></span></span>
									</div>
								</div>
							</div>
							
						
							
							
						</div>
					</div>
				</div>
				<div id="DivForLines">
					
						
				</div>

			</div>



			<div id="lastRowAddLine" class="row rowsInfoNjump3">
					<div class="col-lg-10">
						
					</div>
					<div class="col-lg-2">
						<div class="row">
							<select data-placeholder="Choose offer..." id="selectoffersnewrow"><option></option></select>
						</div>
						<div class="row">
							<img id="newLineIcon" class="iconsDivObject7" data-toggle="popover" data-placement="bottom"  title="" data-content="add new line" data-clipboard-text="" src="/img/njumppage/more_lines.svg">
						</div>
					</div>
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
	<div class="modal fade" id="modalCloneMultipleNJump" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" class="modal hide fade" data-keyboard="false" data-backdrop="static">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">CLONE Lines NJump</h4>
	      </div>
	      <div class="modal-body" style="padding-bottom: 0px;">
	        	<div class="row">
	        		<div class="col-md-12" style="margin-bottom: 15px;font-weight: 600;">
	        			<input id="checkBoxCopyToThis" type="checkbox" name="" value="">Copy to current NJump<br>
	        		</div>
	        	</div>
	        	<div class="row">
	        		<div class="col-md-12" style="margin-bottom: 20px;">
	        			<select multiple id="njumpsCloneMultipleLines" data-placeholder="NJumps *" style="width: 350px; display: none;" tabindex="-1">
  							<option></option>
 								
						</select>
	        		</div>
	        	</div>
	        	<div class="row">
	        		<div class="col-md-12" style="margin-bottom: 20px; text-align: center;">
	        			<span id="nameNjumpCopyToLines"></span>
	        		</div>
	        	</div>
	        	
	      </div>
	      <div class="modal-footer">
	        	<button id="okModalButtonMultipleClone" type="button" class="btn btn-warning">OK</button>
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


	<!-- Modal -->
	<div class="modal fade" id="modalDeleteMultipleLines" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" class="modal hide fade" data-keyboard="false" data-backdrop="static">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	     
	      <div class="modal-body">
	        	<div class="row">
	        		<div class="col-md-12" style="margin-bottom: 20px;">
	        			<p id="modalBodyTextDelete2">Are you sure <br>you want to delete?</p>
	        		</div>
	        	</div>
	        	<div class="row">
	        		<div id="listLinesNames" class="col-md-12" style="text-align: center;">
	        			
	        		</div>
	        	</div>
	      </div>
	      <div class="modal-footer">
	        	<button id="deleteMultipleLineModalButton" type="button" class="btn btn-danger buttonsModalDelete" >DELETE</button>
	        	<button id="deleteMultipleLineModalCancel" type="button" class="btn btn-default buttonsModalDelete" data-dismiss="modal" aria-label="Close">CANCEL</button>
	      </div>
	    </div>
	  </div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="modalDeleteLine" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" class="modal hide fade" data-keyboard="false" data-backdrop="static">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	     
	      <div class="modal-body">
	        	<div class="row">
	        		<div class="col-md-12" style="margin-bottom: 20px;">
	        			<p id="modalBodyTextDelete2">Are you sure <br>you want to delete?</p>
	        		</div>
	        	</div>
	        	<div class="row">
	        		<div class="col-md-12" style="margin-bottom: 20px; text-align: center;">
	        			<span id="nameNjumpDelete2"></span>
	        		</div>
	        	</div>
	      </div>
	      <div class="modal-footer">
	        	<button id="deleteLineModalButton" type="button" class="btn btn-danger buttonsModalDelete" >DELETE</button>
	        	<button id="deleteLineModalCancel" type="button" class="btn btn-default buttonsModalDelete" data-dismiss="modal" aria-label="Close">CANCEL</button>
	      </div>
	    </div>
	  </div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="modalTimeLine" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" class="modal hide fade" data-keyboard="false" data-backdrop="static">
	  <div class="modal-dialog" style="width: 1055px; margin-top: -19px;" role="document">
	    <div class="modal-content" style="width: 1132px;">
	     
	      <div class="modal-body">
	        	<button id="removeAllSch" type="button" class="btn btn-default btn btn-warning">RESET</button>
       			<div class="day_parting_panel">
			        <div class="timezone_section">
			            <input type="hidden" name="parting_timezone" value="" />
			            <div id="tz_country_modal" title="Timezone Selecionada">
			                <select data-placeholder="Escolha um país..." class="chosen-select" id="country_list"></select>
			            </div>
			        </div>
			    </div>
			    <div id="day_parting_selected"></div>
			    <table cellspacing="1" cellpadding="3" id="day_parting">
			        <thead>
			        </thead>
			        <tbody>
			        </tbody>
			    </table>
			    <br />
			    <script language="JavaScript">
			        parting.init("day_parting", null,
			            null);
			    </script>

    
	      </div>
	      <div class="modal-footer">
	        	<button id="SaveTimePick" type="button" class="btn btn-default buttonsModalSave btn btn-success">SAVE</button>
	        	<button id="cancelTimePick" type="button" class="btn btn-default buttonsModalDelete" data-dismiss="modal" aria-label="Close">CLOSE</button>
	      </div>
	    </div>
	  </div>
	</div>
	
	<script>
		var sourcenamevar = <?php echo (isset($sourcenamevar)) ? $sourcenamevar . ';' : "'';"; ?> 

		var globalnamevar = <?php echo (isset($globalnamevar)) ? $globalnamevar . ';' : "'';"; ?>

		var njumpgeneratednamevar = <?php echo (isset($njumpgeneratednamevar)) ? $njumpgeneratednamevar . ';' : "'';"; ?>

		var areaname = <?php echo (isset($areanamevar)) ? $areanamevar . ';'  : "'';"; ?>

		var domainsvar = <?php echo (isset($domainsvar)) ? $domainsvar : "'';"; ?>

		var ispsvar = <?php echo (isset($ispsvar)) ? $ispsvar : "'';"; ?>

		var carriersvar = <?php echo (isset($carriersvar)) ? $carriersvar : "'';"; ?>

		var osvar = <?php echo (isset($osvar)) ? $osvar : "'';"; ?>

		var lpsvar = <?php echo (isset($lpsvar)) ? $lpsvar : "'';"; ?>

		var sbacksvar = <?php echo (isset($sbacksvar)) ? $sbacksvar : "'';"; ?>

		var countriesvar = <?php echo (isset($countriesvar)) ?  $countriesvar : "'';"; ?>

		var sourcesvar = <?php echo (isset($sourcesvar)) ?  $sourcesvar : "'';"; ?>

		var areasvar = <?php echo (isset($areasvar)) ?  $areasvar : "'';"; ?>

		var countryname = <?php echo (isset($countryname)) ?  $countryname : "'';"; ?>

		var njumpsvar = <?php echo (isset($njumpsvar)) ?  $njumpsvar : "'';"; ?>

		var sourcenamevar = <?php echo (isset($sourcenamevar)) ?  $sourcenamevar : "'';"; ?>

		var statusvar = <?php echo (isset($statusvar)) ?  $statusvar : "'';"; ?>

		var favoritevar = <?php echo (isset($favoritevar)) ?  $favoritevar : "'';"; ?>

		var offersvar = <?php echo (isset($offersvar)) ?  $offersvar : "'';"; ?>

		var domainvar = <?php echo (isset($domainvar)) ?  $domainvar : "'';"; ?>

		var globalsourcevar = <?php echo (isset($globalsourcevar)) ?  $globalsourcevar : "'99';"; ?>

		var paramsvar = <?php echo (isset($paramsvar)) ?  $paramsvar : "'';"; ?>

	</script>
    <script> 
    	$(document).ready(function(){
	 
	    	$("#style-1").height($( "#sidebar-wrapper" ).height() - 265);

		    $( window ).resize(function() {
		    	$("#style-1").height($( "#sidebar-wrapper" ).height() - 265);
		    });

		    $("#wrapper").toggleClass("toggled");
		    $("#open").show();
		    $("#rowbottom").fadeOut();
        	$("#close").hide();
        	$("#closestring").hide();
        	$("#containerchosen").fadeOut();

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