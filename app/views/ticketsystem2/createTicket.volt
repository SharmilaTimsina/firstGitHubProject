{% extends "/headfooter.volt" %}
{% block title %}<title>Ticket System</title>{% endblock %}
{% block scriptimport %}    

	<script src="/js/ticketsystem/createticket2.js"></script>
	<link rel="stylesheet" type="text/css" href="/css/ticketsystem.css" />
	
	<script src="/js/datepickerjst/moment.min.js"></script>
	<script src="/js/datepickerjst/daterangepicker.js"></script>
	<link rel="stylesheet" type="text/css" href="/js/datepickerjst/daterangepicker.css" />


	<script type="text/javascript" src="/js/jquery.sumoselect.min.js"></script>
    <link href="/css/sumoselect.css" rel="stylesheet"/>

    <script type="text/javascript" src="/js/vendor/bootstrap-filestyle.min.js"></script> 

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
					<h5 style="font-size: 25px;">Create Ticket</h3>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6"> 
					<table>
						<tr>
							<td>
								<div class="form-group formgroupinvest">
									<label  for="tdate">Subject*:</label>
									<input style="width: 470px;" type="text" class="form-control datepicker" id="subjectticket" name="subjectticket">
								</div>
							</td>
						</tr>
						<tr>
							<td>
        						<div class="form-group formgroupinvest">
									<label for="tdate">Details*:</label><br>
									<textarea id="requestDetails" style="width: 470px; padding: 10PX;max-width: 470px; height: 400px;" class="textAreaModal valid" name="requestDetails" aria-required="true" aria-invalid="false"></textarea>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="form-group formgroupinvest">
									<label for="tdate">Files:</label><br>
									<input id="files" style="width: 287px;" name="files[]" type="file" accept="">
									<script>	
										$(":file").filestyle({
											placeholder: "No files",
											buttonText: "Choose file to upload"
										});
										$(".bootstrap-filestyle").find('input[type="text"]').css('width', '298px');
									</script>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="form-group formgroupinvest">
									<button id="buttonCreateTicket" type="button" name="submit" class="btn btn-success buttonsInvest">Create Ticket</button>
								</div>
							</td>
						</tr>
					</table>
				</div>
				<div class="col-md-6"> 
				<table>
					<tr>
							<td>
								<div class="form-group formgroupinvest">
									<label for="tdate">Type*:</label><br>
									<select class="search-box-sel-all2" id="typeSB">
										<option value="0">IT Request</option>
										<option value="1">Design Request</option>
									</select>
								</div>
							</td>
						</tr>
					<tr>
							<td>
								<div class="form-group formgroupinvest">
									<label for="tdate">Who can see the ticket:</label><br>
									<select multiple class="search-box-sel-all2" id="usersSB">
										
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="form-group formgroupinvest">
									<label for="tdate">Priority*:</label><br>
									<select class="search-box-sel-all2" id="prioritySB">
										<option value="0">Low</option>
										<option value="1">Normal</option>
										<option value="2">High</option>
										<option value="3">Urgent</option>
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="form-group">
									<label for="periodReport">Required period*:</label>
									<input style="width: 201px;" class="form-control selectFilter" title="Date" id="periodReport" type="text" name="periodReport" >
								</div>
							</td>
						</tr>
					</table>
				</div>
				<style>
				
				</style>
			</div>
		</div>
	</div>
	<script>
		var idAuth = <?php echo (isset($authID)) ? $authID : "''"; ?>;
		var usersApi = <?php echo (isset($users)) ? $users : "''"; ?>;
	</script>
{% endblock %}
{% block simplescript %}
{% endblock %}