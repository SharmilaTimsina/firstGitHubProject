{% extends "/headfooter.volt" %}
{% block title %}<title>Custom Reports</title>{% endblock %}
{% block scriptimport %}    
	
	<script src="http://mobisteinreport.com/public/js/customreport/index.js"></script>
	
	<script type="text/javascript" src="http://mobisteinreport.com/public/js/jquery.sumoselect.min.js"></script>
	<link href="http://mobisteinreport.com/public/css/sumoselect.css" rel="stylesheet"/>

	<script type="text/javascript" src="https://cdn.datatables.net/v/bs/dt-1.10.12/datatables.min.js"></script>

	<script src="http://mobisteinreport.com/public/js/datepickerjst/moment.min.js"></script>
	<script src="http://mobisteinreport.com/public/js/datepickerjst/daterangepicker.js"></script>
	<link rel="stylesheet" type="text/css" href="http://mobisteinreport.com/public/js/datepickerjst/daterangepicker.css" />

	<script src="http://mobisteinreport.com/public/js/FileSaver.min.js"></script>

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
				<div class="col-md-12"> 
					<h2>Custom reports</h2>
					<a id="buttoncreate" href="http://mobisteinreport.com/customreport/createcustom" type="button" class="btn btn-default">CREATE CUSTOM</a>
					<table width="100%" class="tables table-striped table-bordered" id="tableCustomReport">
						<thead>
							<tr>
								<td>Custom date</td>
								<td>Name</td>
								<td>Description</td>
								<td>Date</td>
								<td>Edit</td>
								<td>Download</td>
								<td>Preview</td>
								<td>Delete</td>
							</tr>
						</thead>
						<tbody id="tbodyCustomReportList">
							<?php echo $table; ?>
						</tbody>						
					</table>
				</div>
			</div>


			<div id="rowTablePreview" class="row" style="margin-top: 40px; display: none;">
				<div class="col-md-12"> 
					<h3>Preview report</h3>
					<div style="float: right;"><button style="padding: 4px;margin: 10px;" id="nextPage" type="button" class="btn btn-default">NEXT PAGE</button><button style="padding: 4px;margin: 10px;" id="prevPage" type="button" class="btn btn-default">PREVIOUS PAGE</button></div>
					<table width="100%" class="tables table-striped table-bordered" id="tablePreviewReport">						

					</table>
				</div>
			</div>
		</div>
	</div>

	<style>
	#buttoncreate {
		float: right;
	    padding: 5px;
	    background-color: powderblue;
	}
	.tables {
	    width: 100%;
	    border: 1px solid #ddd;
	}
	table.tables tr td {
	    padding: 5px;
	}
	table.tables thead tr {
	    background-color: #01A9DB;
	    color: black;
	    font-weight: 700;
	}
	.checkclass {
	    text-align: center;
	}
	img.modalIcon {
	    width: 21px;
	    cursor: pointer;
	}
	</style>

	
{% endblock %}
{% block simplescript %}
{% endblock %}