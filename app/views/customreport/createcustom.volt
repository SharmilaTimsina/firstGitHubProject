{% extends "/headfooter.volt" %}
{% block title %}<title>Custom Report</title>{% endblock %}
{% block scriptimport %}    
	
	<script src="http://mobisteinreport.com/public/js/customreport/main.js"></script>
	
	<script type="text/javascript" src="http://mobisteinreport.com/public/js/jquery.sumoselect.min.js"></script>
	<link href="http://mobisteinreport.com/public/css/sumoselect.css" rel="stylesheet"/>


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
				<div class="col-md-6" style="display: -webkit-box;"> 
					<p id="atts">Attributes</p>
					<div id="products" class="fbox">
						
					</div>
				</div>
				<div class="col-md-6"> 
					<p id="atts2">Selection</p>
					<div id="drop" class="fbox">
					
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6" style="margin-top: 50px;"> 
					<p id="atts">Metrics</p>
					<div id="products2" class="fbox">
						
					</div>
					<button id="buttonSaveReport" type="button" name="submit" class="btn btn-danger buttonsInsert" data-toggle="modal" data-target="#modalSaveReport" onclick="configModal();">SAVE CUSTOM REPORT</button>
				</div>
				<div class="col-md-6"> 
					<p id="atts3">Filters</p>
					<div id="drop2" class="fbox">
					
					</div>
				</div>
			</div>
		</div>



		<div id="modalSaveReport" class="modal fade" role="dialog">
		  <div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal">&times;</button>
		        <h4 class="modal-title">Save custom report</h4>
		      </div>
		      <div class="modal-body" id="modalbodyReport">
		      		<div class="normalval">
			      		<div><p class="labelsDates">Report name</p>
			      		<input id="reportName"></input>
			      		</div>
			      		<div><p class="labelsDates">Report description</p>
			      		<input id="reportDescription"></input>
			      		</div>
			      		<div><p class="labelsDates">Report columns order</p>
			      			 <input checked type="radio" name="orderby" value="0"> Ascendant<br>
	  						 <input type="radio" name="orderby" value="1"> Descendent
			      		</div>
		      		</div>
		      		<div id="modalbodyReport2">
		      		</div>


		      </div>
		      <div class="modal-footer">
		        <button id="closemodal" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		      </div>
		    </div>

		  </div>
		</div>



	</div>
	<style>	
	#trashImage {
	    width: 69px;
	    margin-right: 83px;
	    float: right;
	    cursor: crosshair;
	    margin-top: -68px;
	}
	#products {
	    border: 1px solid;
    	width: 400px;
    	min-height: 536px;
    	margin-top: 30px;
	}
	#drop {
		border: 1px solid;
   		width: 400px;
		min-height: 536px;
	}
	#products2 {
	    border: 1px solid;
	    width: 400px;
	    min-height: 119px;
	    margin-top: -10px;
	    margin-left: -1px;
	}
	#drop2 {
		border: 1px solid;
   		width: 400px;
    	min-height: 536px;
	}
	.draggable {
	    padding: 10px;
	    margin: 10px 14px 0px 15px;
	}
	.draggable2 {
	    padding: 10px;
	    margin: 10px 14px 0px 15px;
	}
	.transparent {
	    opacity: 0.4;
	}
	#atts {
	    margin-top: 3px;
   	 	margin-right: -89px;
   	 	font-size: 19px;
    	font-weight: 700;

	}
	#atts2 {
	    margin-top: 3px;
    	margin-right: -89px;
    	font-size: 19px;
    	font-weight: 700;
    	margin-bottom: 1px;
	}
	#atts3 {
	    margin-top: 52px;
	    margin-right: -89px;
	    font-size: 19px;
	    font-weight: 700;
	    margin-bottom: 1px;
	}
	.btn-success.disabled, .btn-success[disabled], fieldset[disabled] .btn-success, .btn-success.disabled:hover, .btn-success[disabled]:hover, fieldset[disabled] .btn-success:hover, .btn-success.disabled:focus, .btn-success[disabled]:focus, fieldset[disabled] .btn-success:focus, .btn-success.disabled:active, .btn-success[disabled]:active, fieldset[disabled] .btn-success:active, .btn-success.disabled.active, .btn-success[disabled].active, fieldset[disabled] .btn-success.active {
	    opacity: 0.4;
	    cursor: not-allowed;
    	pointer-events: all !important;
	}
	#drop2 .optWrapper.multiple {
	    margin-left: 8px;
	    width: 331px;
	    margin-top: -1px;
	}
	.optWrapper label {
	    color: black;
	}
	div#drop2 .draggable {
	    margin-top: 30px;
	}
	button#buttonSaveReport {
	    margin-top: 50px;
	    margin-left: -1px;
	    padding: 16px;
	}
	p.labelsDates {
	    margin-top: 30px;
	    margin-bottom: -1px;
	}
	button#buttonSaveReportModal {
	    margin-top: 30px;
	}
	.SumoSelect {
	    color: black;
	}
	</style>

	<script>
		var jsonContentSelectBox = '<?php echo $jsonContentSelectBox; ?>';
	</script>


	
{% endblock %}
{% block simplescript %}
{% endblock %}