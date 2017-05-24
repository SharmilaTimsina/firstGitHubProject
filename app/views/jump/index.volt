{% extends "/headfooter.volt" %}
{% block title %}<title>Jump</title>{% endblock %}
{% block scriptimport %}    
	<script src="http://mobisteinreport.com/js/datatables.min.js"></script>

	<script src="http://mobisteinreport.com/js/datatables.min.js"></script>
	
	<script src="http://mobisteinreport.com//js/vendor/jquery.validate.min.js"></script>

	<script src="http://mobisteinreport.com/js/jump/jump.js"></script>
	<link rel="stylesheet" type="text/css" href="http://mobisteinreport.com/css/jump.css" />

	<script type="text/javascript" src="http://mobisteinreport.com/js/vendor/bootstrap-filestyle.min.js"></script> 
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
				<p class="titles">Jump</p>
			</div>
			<div class="row">
				<div class="col-md-12"> 
					<form name="formjump" id="uploadCsv" enctype="multipart/form-data">
						<tr>
							<td>
								<div class="form-group">
									<label for="">Files:</label>
									<input name="files[]" type="file" accept=".csv">
								</div>
							</td>
						</tr>
					</form>	
				</div>
				<div class="row">
					<div class="col-md-12"> 
						<div id="status2" style="display: none;">&nbsp;</div>
					</div>
					<div class="col-md-12" id="colmd12table" style="display: none;"> 
						<table style="width: 99%;" id="tableJump" class="table-striped table-bordered">
							<thead>
								<tr>
									<td>#</td>
									<td>Agregator</td>
									<td>Country</td>
									<td>Campaign</td>
									<td>URL</td>
									<td>Jump</td>
									<td>CPA</td>
									<td>Original CPA</td>
									<td>Currency</td>
									<td>Type</td>
									<td>State</td>
								</tr>
							<tbody id="tbodyJump">
								
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

{% endblock %}
{% block simplescript %}
{% endblock %}